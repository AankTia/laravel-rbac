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

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $viewData = [
            'title' => "Users",
            // 'subtitle' => "Manage all roles in the system"
        ];

        $query = User::query();
        // if ($request->has('search_keyword')) {
        //     $keyword = $request->search_keyword;

        //     $query->where(function ($q) use ($keyword) {
        //         $q->where('name', 'LIKE', "%{$keyword}%");
        //     });
        // }
        $users = $query->orderBy('id', 'asc')
            ->paginate(10);

        $users->appends($request->all());

        return view('users.index')
            ->with('viewData', $viewData)
            ->with('users', $users);
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        $viewData = [
            'title' => "User Details",
            'subtitle' => $user->name
        ];

        $recentActivities = $user->actitityLogs;

        return view('users.show', compact('user'))
            ->with('viewData', $viewData)
            ->with('recentActivities', $recentActivities);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $viewData = [
            'title' => "Create New User",
        ];

        $roleOptions = Role::where('allow_to_be_assigne', true)->pluck('name', 'id');

        return view('users.create')
            ->with('viewData', $viewData)
            ->with('roleOptions', $roleOptions);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $userData = $request->all();
        $userData['is_active'] = ($userData['is_active'] == 'active');

        $user = new User();
        $validated = $user->validate('create', $userData);

        // Hash password before saving
        $validated['password'] = bcrypt($validated['password']);

        $user->fill($validated)->save();

        if ($request->role_id) {
            UserRole::create([
                'user_id' => $user->id,
                'role_id' => $request->role_id,
                'assigned_by_id' => Auth::id(),
                'assigned_at' => Carbon::now()
            ]);
        }

        return redirect()->route('users.show', ['user' => $user])
            ->with('success', 'User created successfully.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        $viewData = [
            'title' => "Edit User"
        ];

        if (Auth::user()->isSuperAdmin()) {
            $roleOptions = Role::all()->pluck('name', 'id');
        } else {
            $roleOptions = Role::where('allow_to_be_assigne', true)->pluck('name', 'id');
        }

        return view('users.edit', compact('user'))
            ->with('viewData', $viewData)
            ->with('roleOptions', $roleOptions);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        $userData = $request->all();
        $userData['is_active'] = ($userData['is_active'] == 'active');

        $validated = $user->validate('update', $userData);

        // Hash password if it was sent
        if (!empty($validated['password'])) {
            $validated['password'] = bcrypt($validated['password']);
        } else {
            unset($validated['password']); // Don't overwrite if null
        }

        $user->update($validated);

        if ($request->role_id) {
            if ($user->userRole) {
                $user->userRole->update([
                    'role_id' => $request->role_id,
                    'assigned_by_id' => Auth::id(),
                    'assigned_at' => Carbon::now()
                ]);
            } else {
                UserRole::create([
                    'user_id' => $user->id,
                    'role_id' => $request->role_id,
                    'assigned_by_id' => Auth::id(),
                    'assigned_at' => Carbon::now()
                ]);
            }
        }

        return redirect()->route('users.show', ['user' => $user])
            ->with('success', 'User updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        $name = $user->name;
        $user->delete(); // This is a soft delete
        return redirect()->route('users.index')->with('success', 'User: ' . $name . ' deleted successfully.');
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

        $user->update(['is_active' => true]);

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

        $user->update(['is_active' => false]);

        return redirect()->route('users.show', ['user' => $user])
            ->with('success', 'User deactivated successfully.');
    }
}
