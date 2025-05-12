<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\Role;
use App\Models\User;
use App\Models\UserRole;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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

        $saveNewUser = $user->fill($validated)->save();

        if ($saveNewUser) {
            if ($request->role_id) {
                $createdUserRole = UserRole::create([
                    'user_id' => $user->id,
                    'role_id' => $request->role_id,
                    'assigned_by_id' => Auth::id(),
                    'assigned_at' => Carbon::now()
                ]);

                if ($createdUserRole) {
                    $newestUserHistory = $user->getLatestHistory();
                    if ($newestUserHistory->action == 'create') {
                        $subjectProperties = $newestUserHistory->subject_properties;
                        $subjectProperties['attributes']['role'] = [
                            'label' => User::$attributeLabels['role'],
                            'value' => $user->getRoleName()
                        ];
                        $newestUserHistory->update([
                            'subject_properties' => $subjectProperties
                        ]);
                    }
                }
            }

            return redirect()->route('users.show', ['user' => $user])
                ->with('success', 'User created successfully.');
        } else {
            return redirect()->route('users.show', ['user' => $user])
                ->with('error', 'Create new user failed.');
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

        $userChanges = !empty($user->getDirty());
        $updatedUser = false;
        if ($userChanges) {
            $updatedUser = $user->save();
        }

        if ($request->role_id) {
            $newRoleName = Role::find($request->role_id)->name;
            $userRole = $user->userRole;

            if ($userRole) {
                if ($request->role_id != $userRole->role_id) {
                    $oldRoleName = $userRole->role->name;
                    $updateUserRole = $userRole->update(['role_id' => $request->role_id]);
                    if ($updateUserRole) {
                        if ($updatedUser) {
                            $newestUserHistory = $user->getLatestHistory();
                            if ($newestUserHistory->action == 'update') {
                                $subjectProperties = $newestUserHistory->subject_properties;
                                $subjectProperties['attributes']['role'] = [
                                    'label' => $user->getAttributeLabel('role'),
                                    'old_value' => $oldRoleName,
                                    'new_value' => $newRoleName
                                ];
                                $newestUserHistory->update([
                                    'subject_properties' => $subjectProperties
                                ]);
                            }
                        } else {
                            $user->createLogActivity('update-user-role', [
                                'user_description' => 'Updated Role from ' . $oldRoleName . ' to ' . $newRoleName . ' for user : ' . $user->name,
                                'subject_description' => 'Updated Role from ' . $oldRoleName . ' to ' . $newRoleName,
                                'subject_properties' => [
                                    'attributes' => [
                                        'role' => [
                                            'label' => $user->getAttributeLabel('role'),
                                            'old_value' => $oldRoleName,
                                            'new_value' => $newRoleName
                                        ]
                                    ]
                                ]
                            ]);
                        }
                    }
                }
            } else {
                $createdUserRole = UserRole::create([
                    'user_id' => $user->id,
                    'role_id' => $request->role_id,
                    'assigned_by_id' => Auth::id(),
                    'assigned_at' => Carbon::now()
                ]);

                if ($createdUserRole) {
                    $user->createLogActivity('set-user-role', [
                        'user_description' => 'Set ' . $newRoleName . ' Role for user : ' . $user->name,
                        'subject_description' => 'Set ' . $newRoleName . ' Role',
                    ]);
                }
            };
        }

        return redirect()
            ->route('users.show', ['user' => $user])
            ->with('success', 'User updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        $userName = $user->name;
        $originalAttributes = $user->getOriginalActivityAttributes();

        $logActivityData = [
            'user_description' => 'Deleted User : ' . $userName,
            'subject_description' => 'Deleted User',
            'subject_properties' => [
                'attributes' => $originalAttributes
            ]
        ];

        // $user->delete(); // This is a soft delete


        $user->createLogActivity('delete', $logActivityData);

        return redirect()
            ->route('users.index')
            ->with('success', 'User: ' . $userName . ' deleted successfully.');
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

        $user->update(['is_active' => 1]);

        return redirect()->route('users.show', ['user' => $user])
            ->with('success', 'User activated successfully.');
    }

    /**
     * Deactivate the specified resource in storage.
     */
    public function deactivate(Request $request, User $user)
    {
        if ($user->isSuperAdmin()) {
            return redirect()->route('users.show', ['user' => $user])
                ->with('info', 'Cannot diactivate User with Super Admin Role.');
        }

        if (!$user->is_active) {
            return redirect()->route('users.show', ['user' => $user])
                ->with('info', 'User in inactive status.');
        }

        $user->update(['is_active' => 0]);

        return redirect()->route('users.show', ['user' => $user])
            ->with('success', 'User deactivated successfully.');
    }
}
