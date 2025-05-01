<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

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
}
