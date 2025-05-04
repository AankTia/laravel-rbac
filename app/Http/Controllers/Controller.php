<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Auth;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    function logActivity($request, $action)
    {
        ActivityLog::create([
            'user_id' => Auth::id(),
            'ip_address' => $request->ip(),
            'user_agent'=> $request->userAgent(),
            'action' => $action,
        ]);
    }
}
