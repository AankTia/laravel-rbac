<?php

use Illuminate\Support\Facades\Auth;

function isUserCan($action, $module)
{
    if (Auth::user()) {
        return Auth::user()->hasPermission($action, $module);
    } else {
        return false;
    }
}