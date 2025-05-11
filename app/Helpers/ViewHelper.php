<?php

function activeInactiveStatusBadgeFor($value) {
    if ($value === 1 || $value === true) {
        return <<<HTML
            <span class="badge rounded-pill bg-label-primary">Active</span>
        HTML;
    } else {
        return <<<HTML
            <span class="badge rounded-pill bg-label-danger">Inactive</span>
        HTML;
    }
}