<?php

function getIcon($name)
{
    $icons = [
        'show' => 'bx bx-show-alt',
        'edit' => 'bx bx-edit-alt',
        'delete' => 'bx bx-trash',
        'create' => 'bx bx-plus',
        'activate' => 'bx bx-check',
        'deactivate' => 'bx bx-minus',
        'clock' => 'bx bx-time',
        'user' => 'bx bx-user',
        'delete-user' => 'bx bx-user-minus',
        'left-arrow' => 'bx bx-left-arrow-alt',
        'right-arrow' => 'bx bx-right-arrow-alt',
        'history' => 'bx bx-history',
        'lock' => 'bx bx-lock'
    ];

    if (isset($icons[$name])) {
        return $icons[$name];
    } else {
        return null;
    }
}
