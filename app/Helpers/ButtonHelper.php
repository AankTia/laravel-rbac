<?php

function backButton($route, $title)
{
    echo "<a href='{$route}' class='btn btn-sm btn-outline-secondary me-2'>
            <i class='bx bx-left-arrow-alt me-1'></i> {$title}
        </a>";
}

function createButton($route, $title) {}

function editButton($route, $title = 'Edit')
{
    $updateIcon = updateIcon();
    echo "<a href='{$route}' class='btn btn-sm btn-warning'>
        <i class='{$updateIcon}'></i> {$title}
    </a>";
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
