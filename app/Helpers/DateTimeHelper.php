<?php

use Carbon\Carbon;

function formatedDatetime($datetime, $format = 'd M Y, H:i')
{
    if ($datetime) {
        if ($datetime instanceof Carbon) {
            return $datetime->format($format);
        } else {
            return Carbon::parse($datetime)->format($format);
        }
    } else {
        return null;
    }
}


function humanDateTime($datetime, $format = 'd M Y, H:i', $threshold = 12)
{
    $date = Carbon::parse($datetime);
    $now = Carbon::now();

    return $date->diffInHours($now, false) <= $threshold
        ? $date->diffForHumans()
        : $date->format($format);
}
