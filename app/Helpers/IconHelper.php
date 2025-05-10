<?php

function getIconFor($name)
{
    $icons = [
        'show' => 'bx bx-show-alt',
        'edit' => 'bx bx-edit-alt',
        'delete' => 'bx bx-trash'
    ];

    if (isset($icons[$name])) {
        return $icons[$name];
    } else {
        return null;
    }
}

if (!function_exists('createIcon')) {
    function createIcon()
    {
        return 'bx bx-plus';
    }
}

if (!function_exists('updateIcon')) {
    function updateIcon()
    {
        return 'bx bx-pencil';
    }
}

if (!function_exists('deleteIcon')) {
    function deleteIcon()
    {
        return 'bx bx-trash';
    }
}

if (!function_exists('activateIcon')) {
    function activateIcon()
    {
        return 'bx bx-check';
    }
}

if (!function_exists('deactivateIcon')) {
    function deactivateIcon()
    {
        return 'bx bx-minus';
    }
}

if (!function_exists('clockIcon')) {
    function clockIcon()
    {
        return 'bx bx-time';
    }
}

if (!function_exists('userIcon')) {
    function userIcon()
    {
        return 'bx bx-user';
    }
}

if (!function_exists('userMinusIcon')) {
    function userMinusIcon()
    {
        return 'bx bx-user-minus';
    }
}

if (!function_exists('leftArrowIcon')) {
    function leftArrowIcon()
    {
        return 'bx bx-left-arrow-alt';
    }
}

if (!function_exists('rightArrowIcon')) {
    function rightArrowIcon()
    {
        return 'bx bx-right-arrow-alt';
    }
}

if (!function_exists('historyIcon')) {
    function historyIcon()
    {
        return 'bx bx-history';
    }
}

if (!function_exists('lockIcon')) {
    function lockIcon()
    {
        return 'bx bx-lock';
    }
}
