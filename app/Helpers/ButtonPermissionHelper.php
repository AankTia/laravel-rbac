<?php

function permittedBackButton($route, $permissionName, $modue, $label = 'Back')
{
    if (isUserCan($permissionName, $modue)) {
        $icon = getIcon('left-arrow');
        return <<<HTML
             <a href="{$route}" class="btn btn-sm btn-outline-secondary me-2">
                 <i class="{$icon}"></i> {$label}
             </a>
         HTML;
    }
}

function permittedCreateButton($route, $permissionName, $modue, $label = '')
{
    if (isUserCan($permissionName, $modue)) {
        $icon = getIcon('create');
        return <<<HTML
                <a href='{$route}' class='btn btn-sm btn-primary'>
                    <i class='{$icon}'></i> Create New {$label}
                </a>
            HTML;
    }
}

function permittedEditButton($route, $permissionName, $modue, $label = 'Edit')
{
    if (isUserCan($permissionName, $modue)) {
        $icon = getIcon('edit');
        return <<<HTML
            <a href='{$route}' class='btn btn-sm btn-warning'>
                <i class='{$icon}'></i> {$label}
            </a>
        HTML;
    }
}

function permittedDeleteButton($route, $permissionName, $modue, $label = 'Delete')
{
    if (isUserCan($permissionName, $modue)) {
        $deleteIcon = getIcon('delete');
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
}

function permittedActivateButton($route, $permissionName, $modue, $label = 'Activate')
{
    if (isUserCan($permissionName, $modue)) {
        $icon = getIcon('activate');
        $csrf = csrf_field();

        return <<<HTML
            <form action="{$route}" method="POST" style="display:inline;">
                {$csrf}
                <button type="submit" onclick="return confirm('Are you sure?')" class="btn btn-sm btn-info">
                    <i class="{$icon}"></i> {$label}
                </button>
            </form>
        HTML;
    }
}

function permittedDeactivateButton($route, $permissionName, $modue, $label = 'Deactivate')
{
    if (isUserCan($permissionName, $modue)) {
        $icon = getIcon('deactivate');
        $csrf = csrf_field();

        return <<<HTML
            <form action="{$route}" method="POST" style="display:inline;">
                {$csrf}
                <button type="submit" onclick="return confirm('Are you sure?')" class="btn btn-sm btn-secondary">
                    <i class="{$icon}"></i> {$label}
                </button>
            </form>
        HTML;
    }
}

function permittedReadHistoriesButton($route, $permissionName, $modue, $label = 'Show Histories')
{
    if (isUserCan($permissionName, $modue)) {
        $icon = getIcon('history');
        return <<<HTML
             <button class="btn btn-primary btn-sm" type="button">
                 <i class="{$icon}"></i> {$label}
             </button>
         HTML;
    }
}

function permittedShowButtonIcon($route, $permissionName, $modue)
{
    if (isUserCan($permissionName, $modue)) {
        $icon = getIcon('show');
        return <<<HTML
            <a href='{$route}' class='btn btn-icon btn-outline-primary mb-2'>
                <span class="tf-icons {$icon}"></span>
            </a>
        HTML;
    }
}

function permittedCreateButtonIcon($route, $permissionName, $modue)
{
    dd();
}

function permittedEditButtonIcon($route, $permissionName, $modue)
{
    if (isUserCan($permissionName, $modue)) {
        $icon = getIcon('edit');
        return <<<HTML
                <a href='{$route}' class='btn btn-icon btn-outline-warning mb-2'>
                    <span class="tf-icons {$icon}"></span>
                </a>
            HTML;
    }
}

function permittedDeleteButtonIcon($route, $permissionName, $modue)
{
    if (isUserCan($permissionName, $modue)) {
        $icon = getIcon('delete');
        $csrf = csrf_field();
        $method = method_field('DELETE');

        return <<<HTML
            <form action="{$route}" method="POST" style="display:inline;" onsubmit="return confirm('Are you sure you want to delete this item?');">
                {$csrf}
                {$method}
                <button type="submit" onclick="return confirm('Are you sure to delete?')" class="btn btn-icon btn-outline-danger mb-2">
                    <span class="tf-icons {$icon}"></span>
                </button>
            </form>
        HTML;
    }
}

function permittedActivateButtonIcon($route, $modue, $permissionName)
{
    if (isUserCan($permissionName, $modue)) {
        dd();
    }
}

function permittedDeactivateButtonIcon($route, $modue, $permissionName)
{
    if (isUserCan($permissionName, $modue)) {
        dd();
    }
}
