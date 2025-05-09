<?php

function userStatusBadge($isActive)
{
    if ($isActive) {
        return <<<HTML
            <span class="badge rounded-pill bg-label-primary">Active</span>
        HTML;
    } else {
        return <<<HTML
            <span class="badge rounded-pill bg-label-danger">Inactive</span>
        HTML;
    }
}
