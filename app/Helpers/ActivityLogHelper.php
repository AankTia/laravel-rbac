<?php

function actionTimelinePointColor($action)
{
    // timeline-point-primary
    // timeline-point-secondary
    // timeline-point-success
    // timeline-point-info
    // 
    // 
    // timeline-point-dark
    // timeline-point-gray

    $result = '';

    switch ($action) {
        case 'created':
            $result = 'timeline-point-primary';
            break;
        case 'updated':
            $result = 'timeline-point-warning';
            break;
        case 'role-permission-updated';
            $result = 'timeline-point-warning';
            break;
        case 'deleted':
            $result = 'timeline-point-danger';
            break;
        case 'activated':
            $result = 'timeline-point-success';
            break;
        case 'deactivated':
            $result = 'timeline-point-gray';
            break;
        case 'delete-user':
            $result = 'timeline-point-danger';
            break;
        default:
            $result = 'timeline-point-info';
    }

    return $result;
}

function actionIcon($action) // need move to helper
{
    $result = '';

    switch ($action) {
        case 'created':
            $result = createIcon();
            break;
        case 'updated':
            $result = updateIcon();
            break;
        case 'role-permission-updated';
            $result = lockIcon();
            break;
        case 'deleted':
            $result = deleteIcon();
            break;
        case 'activated':
            $result = activateIcon();
            break;
        case 'deactivated':
            $result = deactivateIcon();
            break;

        case 'delete-user':
            $result = userMinusIcon();
            break;
        default:
            $result = '';
    }

    return $result;
}
