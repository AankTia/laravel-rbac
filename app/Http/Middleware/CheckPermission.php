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
    public function handle(Request $request, Closure $next, $permission, $module)
    {
        if (!$request->user() || !$request->user()->hasPermission($permission, $module)) {
            return redirect('/')
                ->with('error', 'You do not have permission to ' . $permission . ' ' . $module);
        }

        return $next($request);
    }
}
