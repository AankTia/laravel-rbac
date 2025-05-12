<?php

function actionTimelinePointColor($action)
{
    $default = 'timeline-point-gray';
    $timelinePointColorClass = [
        'login' => 'timeline-point-success',
        'logout' => 'timeline-point-dark',
        'create' => 'timeline-point-primary',
        'update' => 'timeline-point-warning',
        'set-user-role' => 'timeline-point-primary',
        'update-user-role' => 'timeline-point-warning',
        'unset-user-role' => 'timeline-point-danger',
        'delete' => 'timeline-point-danger',
        'activate' => 'timeline-point-success',
        'deactivate' => 'timeline-point-gray'
    ];

    // switch ($action) {
    //     case 'role-permission-updated';
    //         $result = 'timeline-point-warning';
    //         break;
    //     case 'delete-user':
    //         $result = 'timeline-point-danger';
    //         break;
    //     default:
    //         $result = 'timeline-point-info';


    if (array_key_exists($action, $timelinePointColorClass)) {
        return $timelinePointColorClass[$action];
    } else {
        return $default;
    }
}

function actionIcon($action)
{
    $result = '';

    switch ($action) {
        case 'created':
            $result = getIcon('create');
            break;
        case 'updated':
            $result = getIcon('edit');
            break;
        case 'role-permission-updated';
            $result = getIcon('lock');
            break;
        case 'deleted':
            $result = getIcon('delete');
            break;
        case 'activated':
            $result = getIcon('activate');
            break;
        case 'deactivated':
            $result = getIcon('deactivate');
            break;
        case 'delete-user':
            $result = getIcon('delete-user');
            break;
        default:
            $result = '';
    }

    return $result;
}
