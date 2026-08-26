<?php

use Illuminate\Foundation\Application;
use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

// Determine if the application is in maintenance mode...
if (file_exists($maintenance = __DIR__.'/../storage/framework/maintenance.php')) {
    require $maintenance;
}

// Define fast native polyfills before Symfony polyfill-mbstring is loaded
if (!function_exists('mb_strimwidth')) {
    function mb_strimwidth($string, $start, $width, $trim_marker = '', $encoding = null) {
        $string = substr($string, $start);
        if (strlen($string) <= $width) {
            return $string;
        }
        return substr($string, 0, $width - strlen($trim_marker)) . $trim_marker;
    }
}
if (!function_exists('mb_strwidth')) {
    function mb_strwidth($string, $encoding = null) {
        return strlen($string);
    }
}

// Register the Composer autoloader...
require __DIR__.'/../vendor/autoload.php';

// Bootstrap Laravel and handle the request...
/** @var Application $app */
$app = require_once __DIR__.'/../bootstrap/app.php';

$app->handleRequest(Request::capture());
