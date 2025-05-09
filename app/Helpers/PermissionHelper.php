<?php

use Illuminate\Support\Facades\Auth;

function currentUser() {
    return Auth::user();
}

function currentUserId() {
    return Auth::id();
}

function isUserCan($action, $module)
{
    if (Auth::user()) {
        return Auth::user()->hasPermission($action, $module);
    } else {
        return false;
    }
}