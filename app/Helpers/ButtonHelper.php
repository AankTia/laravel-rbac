<?php

function backButton($route, $permission, $label)
{
    $explodedPermission = explodePermission($permission);
    if (isUserCan($explodedPermission['permission'], $explodedPermission['module'])) {
        if ($label == null || trim($label) == '') {
            $label = 'Back';
        }
        $icon = leftArrowIcon();

        return <<<HTML
            <a href="{$route}" class="btn btn-sm btn-outline-secondary me-2">
                <i class="{$icon}"></i> {$label}
            </a>
        HTML;
    } else {
        return null;
    }
}

function cancelButton($route, $label = 'Cancel')
{
    return <<<HTML
            <a href="{$route}" class="btn btn-sm btn-outline-secondary me-2">
                {$label}
            </a>
        HTML;
}

function createButton($route, $permission, $label)
{
    $explodedPermission = explodePermission($permission);
    if (isUserCan($explodedPermission['permission'], $explodedPermission['module'])) {
        $icon = createIcon();
        return <<<HTML
                <a href='{$route}' class='btn btn-sm btn-primary'>
                    <i class='{$icon}'></i> Create New {$label}
                </a>
            HTML;
    } else {
        return null;
    }
}

function submitCreateButton($label = 'Save')
{
    return <<< HTML
            <button type="submit" class="btn btn-sm btn-primary">{$label}</button>
        HTML;
}

function editButton($route, $label = 'Edit')
{
    $icon = updateIcon();
    return <<<HTML
            <a href='{$route}' class='btn btn-sm btn-warning'>
                <i class='{$icon}'></i> {$label}
            </a>
        HTML;
}

function submitEditButton($label = 'Update')
{
    return <<< HTML
            <button type="submit" class="btn btn-sm btn-warning">{$label}</button>
        HTML;
}

function deleteButton($route, $label = 'Delete')
{
    $deleteIcon = deleteIcon();
    $csrf = csrf_field();
    $method = method_field('DELETE');

    return <<<HTML
            <form action="{$route}" method="POST" style="display:inline;" onsubmit="return confirm('Are you sure you want to delete this item?');">
                {$csrf}
                {$method}
                <button type="submit" class="btn btn-sm btn-danger">
                    <i class="{$deleteIcon}"></i> {$label}
                </button>
            </form>
        HTML;
}

function deleteUserFromRoleButton($route)
{
    if (isUserCan('delete-user', 'role')) {
        $csrf = csrf_field();
        $method = method_field('DELETE');
        $deleteIcon = deleteIcon();

        return <<<HTML
            <form action="{$route}" method="POST" style="display:inline;" onsubmit="return confirm('Are you sure you want to delete this User from Role?');">
                {$csrf}
                {$method}
                <button type="submit" class="btn btn-sm btn-danger">
                    <i class="{$deleteIcon}"></i> Delete
                </button>
            </form>
        HTML;
    } else {
        return null;
    }
}

function buttonIconForShow($route, $permission)
{
    $explodedPermission = explodePermission($permission);
    if (isUserCan($explodedPermission['permission'], $explodedPermission['module'])) {
        $icon = getIconFor('show');
        return <<<HTML
                <a href='{$route}' class='btn btn-icon btn-outline-primary mb-2'>
                    <span class="tf-icons {$icon}"></span>
                </a>
            HTML;
    } else {
        return null;
    }
}

function buttonIconForEdit($route, $permission)
{
    $explodedPermission = explodePermission($permission);
    if (isUserCan($explodedPermission['permission'], $explodedPermission['module'])) {
        $icon = getIconFor('edit');
        return <<<HTML
                <a href='{$route}' class='btn btn-icon btn-outline-warning mb-2'>
                    <span class="tf-icons {$icon}"></span>
                </a>
            HTML;
    } else {
        return null;
    }
}

function submitButtonIconForDelete()
{
    $icon = getIconFor('delete');
    return <<<HTML
        <button type="submit" onclick="return confirm('Are you sure to delete?')" class="btn btn-icon btn-outline-danger mb-2">
            <span class="tf-icons {$icon}"></span>
        </button>
    HTML;
}
