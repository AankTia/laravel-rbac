<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\Role;
use App\Models\User;
use App\Models\UserRole;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{

    public function __construct()
    {
        $this->middleware('check.permission:user,read')->only('index', 'show');
        $this->middleware('check.permission:user,create')->only('create', 'store');
        $this->middleware('check.permission:user,update')->only('edit', 'update');
        $this->middleware('check.permission:user,delete')->only('destroy');
        $this->middleware('check.permission:user,activate')->only('activate');
        $this->middleware('check.permission:user,deactivate')->only('deactivate');
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = User::query()->with('userRole');
        if ($request->has('search_name')) {
            $keyword = $request->search_name;

            $query->where(function ($q) use ($keyword) {
                $q->where('name', 'LIKE', "%{$keyword}%");
            });
        }
        if ($request->has('search_status')) {
            if ($request->search_status === 'active') {
                $query->where('is_active', true);
            } elseif ($request->search_status === 'inactive') {
                $query->where('is_active', false);
            }
        }
        if ($request->has('search_role')) {
            $roleId = Role::where('slug', $request->search_role)->pluck('id')->first();
            if ($roleId) {
                $query->whereRelation('userRole', 'role_id', $roleId);
            }
        }

        $users = $query->orderBy('id', 'asc')
            ->paginate(10);

        $users->appends($request->all());

        if (isSuperAdmin()) {
            $roleOptions = Role::all()->pluck('name', 'slug');
        } else {
            $roleOptions = Role::where('allow_to_be_assigne', true)->pluck('name', 'slug');
        }

        return view('users.index', compact('users', 'roleOptions'));
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        $lastActivity = $user->getLatestHistory();

        $userActivityLogs = $user->userActivities()
            ->latest()
            ->get();

        return view('users.show', compact('user', 'lastActivity', 'userActivityLogs'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $roleOptions = Role::where('allow_to_be_assigne', true)->pluck('name', 'id');

        return view('users.create', compact('roleOptions'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate(
            [
                'name'      => 'required|string|max:255',
                'email'     => 'required|email|unique:users,email',
                'role_id'   => 'required|exists:roles,id',
                'is_active' => 'required',
                'password'  => 'required|min:8|confirmed',
            ],
            [
                'role_id.required' => 'The role field is required.',
                'role_id.exists' => 'The selected role is not exists.',
                'is_active.required' => 'The status field is required.'
            ]
        );

        $userData = $request->all();
        $userData['is_active'] = ($userData['is_active'] == 'active') ? 1 : 0;

        $user = new User();
        $validated = $user->validate('create', $userData);

        // Hash password before saving
        $validated['password'] = bcrypt($validated['password']);

        DB::beginTransaction();
        try {
            $user->fill($validated)->save();

            $logSubjectProperties = $user->getOriginalSubjectProperties();
            if ($request->role_id) {
                $user->setRole($request->role_id, Auth::id());

                $logSubjectProperties['attributes']['role'] = [
                    'label' => $user->getAttributeLabel('role'),
                    'value' => $user->getRoleName()
                ];
            }

            $logData  = [
                'user_description' => 'Created a new User : ' . $user->name,
                'subject_properties' => $logSubjectProperties
            ];

            $user->createStoredDataLog($logData);

            DB::commit();

            return redirect()
                ->route('users.show', $user)
                ->with('success', 'User created successfully.');
        } catch (Exception $e) {
            DB::rollBack();

            $message = $e->getMessage();

            return redirect()
                ->route('users.index')
                ->with('error', 'Failed to create a new User. ' . $message);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        if (isSuperAdmin()) {
            $roleOptions = Role::all()->pluck('name', 'id');
        } else {
            $roleOptions = Role::where('allow_to_be_assigne', true)->pluck('name', 'id');
        }

        return view('users.edit', compact('user', 'roleOptions'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        $validationRule = [
            'name'      => 'required|string|max:255',
            'email'     => 'required|email|unique:users,email,' . $user->id,
            'role_id'   => 'required|exists:roles,id',
            'is_active' => 'required',
        ];
        if ($request->has('password') && $request->password != null) {
            $validationRule['password'] = 'required|min:8|confirmed';
        }

        $validationMessage = [
            'role_id.required' => 'The role field is required.',
            'role_id.exists' => 'The selected role is not exists.',
            'is_active.required' => 'The status field is required.'
        ];
        $request->validate(
            $validationRule,
            $validationMessage
        );

        $user->name = $request->name;
        $user->email = $request->email;
        $user->is_active = ($request->is_active == 'active') ? 1 : 0;

        if (!empty($request->password)) {
            $user->password = bcrypt($request->password);
        }

        DB::beginTransaction();
        try {
            $userName = $user->name;
            $isUserChange = !empty($user->getDirty());

            $logSubjectProperties = [];
            $logDescription = [];
            if ($isUserChange) {
                $logSubjectProperties = $user->getChangedSubjectProperties();
                $logDescription[] = $user->getChangeDataDescription();

                // check is user data changes, 
                // this for handle if onlu change role
                $user->save();
            } else {
                $logSubjectProperties['attributes'] = [];
            }
            
            $userRole = $user->userRole;
            $newRoleName = Role::find($request->role_id)->name;
            if ($userRole) {
                if ($request->role_id != $userRole->role_id) {
                    $currentRoleName = $user->getRoleName();

                    $logDescription[] = 'Change Role from ' . $currentRoleName . ' to ' . $newRoleName;
                    $logSubjectProperties['attributes']['role'] = [
                        'label' => $user->getAttributeLabel('role'),
                        'new_value' => $newRoleName,
                        'old_value' => $currentRoleName,
                    ];

                    $user->changeRole($request->role_id, Auth::id());
                }
            } else {
                if ($request->role_id) {
                    $logDescription[] = 'Set Role';
                    $logSubjectProperties['attributes']['role'] = [
                        'label' => $user->getAttributeLabel('role'),
                        'new_value' => $newRoleName,
                        'old_value' => null,
                    ];

                    $user->setRole($request->role_id, Auth::id());
                }
            }

            $logUserDescription = $logDescription;
            $logUserDescription[] = 'for User : ' . $userName;

            $user->createUpdatedDataLog([
                'user_description' => implode(' ', $logUserDescription),
                'subject_description' => implode(' ', $logDescription),
                'subject_properties' => $logSubjectProperties
            ]);

            DB::commit();

            return redirect()
                ->route('users.show', $user)
                ->with('success', 'User updated successfully.');
        } catch (Exception $e) {
            DB::rollBack();

            $message = $e->getMessage();

            return redirect()
                ->route('users.show', $user)
                ->with('error', 'Failed to update User. ' . $message);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        $userName = $user->name;

        $logSubjectProperties = $user->getOriginalSubjectProperties();
        $logSubjectProperties['attributes']['role'] = [
            'label' => $user->getAttributeLabel('role'),
            'value' => $user->getRoleName()
        ];

        $logData  = [
            'user_description' => 'Deleted User : ' . $user->name,
            'subject_properties' => $logSubjectProperties
        ];

        DB::beginTransaction();
        try {
            // This is a soft delete
            $user->delete();
            $user->createDeletedDataLog($logData);

            DB::commit();

            return redirect()
                ->route('users.index')
                ->with('success', 'Successfully delete User : ' . $userName);
        } catch (Exception $e) {
            DB::rollBack();

            $message = $e->getMessage();

            return redirect()
                ->route('users.index')
                ->with('error', 'Failed to delete User:  ' . $userName . '. ' . $message);
        }
    }

    /**
     * Activate the specified resource in storage.
     */
    public function activate(Request $request, User $user)
    {
        if ($user->is_active) {
            return redirect()->route('users.show', ['user' => $user])
                ->with('info', 'User in inactive status.');
        }

        DB::beginTransaction();
        try {
            $user->activate();

            $user->createActivateDataLog([
                'user_description' => 'Activated User : ' . $user->name,
            ]);

            DB::commit();

            return redirect()
                ->route('users.show', $user)
                ->with('success', 'User activated successfully.');
        } catch (Exception $e) {
            DB::rollBack();

            $message = $e->getMessage();

            return redirect()
                ->route('users.show', $user)
                ->with('error', 'Failed to Activate User:  ' . $message);
        }
    }

    /**
     * Deactivate the specified resource in storage.
     */
    public function deactivate(Request $request, User $user)
    {
        if ($user->isSuperAdmin()) {
            return redirect()->route('users.show', ['user' => $user])
                ->with('info', 'Cannot diactivate This User with Super Admin Role.');
        }

        if (!$user->is_active) {
            return redirect()->route('users.show', ['user' => $user])
                ->with('info', 'User in inactive status.');
        }

        DB::beginTransaction();
        try {
            $user->deactivate();

            $user->createDeactivateDataLog([
                'user_description' => 'Deactivated User : ' . $user->name,
            ]);

            DB::commit();

            return redirect()
                ->route('users.show', $user)
                ->with('success', 'User deactivated successfully.');
        } catch (Exception $e) {
            DB::rollBack();

            $message = $e->getMessage();

            return redirect()
                ->route('users.show', $user)
                ->with('error', 'Failed to Deactivate User:  ' . $message);
        }
    }
}
