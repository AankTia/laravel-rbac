<?php

use Illuminate\Support\Facades\Auth;

function currentUser()
{
    return Auth::user();
}

function currentUserId()
{
    return Auth::id();
}

function isSuperAdmin()
{
    return Auth::user()->isSuperAdmin();
}

function isUserCan($action, $module)
{
    if (Auth::user()) {
        return Auth::user()->hasPermission($action, $module);
    } else {
        return false;
    }
}

/**
 * Parse module and permission for check permission.
 *
 * @param string $value (e.g., 'user.create')
 * @return array (e.g., ['module' => 'user', 'permission' => 'create'])
 */
function explodePermission(string $value)
{
    $explodedValue = explode('.', $value);
    return [
        'module' => $explodedValue[0],
        'permission' => $explodedValue[1]
    ];
}
