<?php

foreach (glob(__DIR__ . '/*Helper.php') as $filename) {
    require_once $filename;
}
