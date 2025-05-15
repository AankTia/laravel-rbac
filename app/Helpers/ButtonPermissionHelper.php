<?php

function permittedBackButton($route, $permission, $modue, $label = 'Back')
{
    if (isUserCan($permission, $modue)) {
        $icon = getIcon('left-arrow');

        return <<<HTML
             <a href="{$route}" class="btn btn-sm btn-outline-secondary me-2">
                 <i class="{$icon}"></i> {$label}
             </a>
         HTML;
    }
}

function permittedCreateButton($route, $permission, $modue, $label = '')
{
    if (isUserCan($permission, $modue)) {
        $icon = getIcon('create');
        return <<<HTML
                <a href='{$route}' class='btn btn-sm btn-primary'>
                    <i class='{$icon}'></i> Create New {$label}
                </a>
            HTML;
    }
}

function permittedEditButton($route, $permission, $modue, $label = 'Edit')
{

    if (isUserCan($permission, $modue)) {
        $icon = getIcon('edit');
        return <<<HTML
            <a href='{$route}' class='btn btn-sm btn-warning'>
                <i class='{$icon}'></i> {$label}
            </a>
        HTML;
    }
}

function permittedDeleteButton($route, $permission, $modue, $label = 'Delete')
{
    if (isUserCan($permission, $modue)) {
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

function permittedActivateButton($route, $permission, $modue, $label = 'Activate')
{
    dd();
}

function permittedDeactivateButton($route, $permission, $modue, $label = 'Deactivate')
{
    dd();
}

function permittedCreateButtonIcon($route, $permission, $modue)
{
    dd();
}

function permittedEditButtonIcon($route, $permission, $modue)
{
    dd();
}

function permittedDeleteButtonIcon($route, $permission, $modue)
{
    dd();
}

function permittedActivateButtonIcon($route, $mododule, $permission)
{
    dd();
}

function permittedDeactivateButtonIcon($route, $mododule, $permission)
{
    dd();
}
