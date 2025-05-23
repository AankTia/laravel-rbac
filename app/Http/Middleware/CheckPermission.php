<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckPermission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, $module, $permission)
    {
        if (!$request->user() || !$request->user()->hasPermission($permission, $module)) {
            return redirect('/')
                ->with('error', 'You do not have permission to ' . ucwords($permission) . ' ' . ucwords($module));
        }

        return $next($request);
    }
}
