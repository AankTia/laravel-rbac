<?php

function roleAllowToBeAssigneBadge($value)
{
    $badgeDefault = <<<HTML
        <span>{$value}</span>
    HTML;

    if ($value === null) {
        return $badgeDefault;
    } else {
        if (in_array($value, [true, 1])) {
            return <<<HTML
                        <span class="badge bg-label-primary">Allowed</span>
                    HTML;
        } elseif (in_array($value, [false, 0])) {
            return <<<HTML
                        <span class="badge bg-label-secondary">Not Allowed</span>
                    HTML;
        } else {
            return $badgeDefault;
        }
    }
}
