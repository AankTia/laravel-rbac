<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserRequest;
use App\Models\Role;
use App\Models\User;
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

        return view('users.show', compact('user'))
            ->with('viewData', $viewData);
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
    public function store(StoreUserRequest $request)
    {
        $userData = $request->validated();
        $userData['is_active'] = ($userData['is_active'] == 'active');
        $userData['created_by_id'] = Auth::user()->id;

        $user = User::create($userData);

        return redirect()->route('users.show', ['user' => $user])
            ->with('success', 'User created successfully.');
    }
}
