<?php

function backButton($route, $label)
{
    $icon = leftArrowIcon();
    return <<<HTML
            <a href="{$route}" class="btn btn-sm btn-outline-secondary me-2">
                <i class="{$icon}"></i> {$label}
            </a>
        HTML;
}

function createButton($route, $label = '') {
    $icon = createIcon();
    return <<<HTML
            <a href='{$route}' class='btn btn-sm btn-primary'>
                <i class='{$icon}'></i> Add New {$label}
            </a>
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
