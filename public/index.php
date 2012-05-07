<?php

ini_set('display_errors',true);
error_reporting(E_ALL & ~E_NOTICE);
date_default_timezone_set('Europe/Berlin');

define('APPLICATION_PATH', realpath(dirname(__FILE__).'/..'));

require_once APPLICATION_PATH . '/library/AutoLoader.php';
OsuMirror_AutoLoader::getInstance()
    ->registerAutoLoader();

OsuMirror_ErrorHandler::getInstance();

$route = OsuMirror_Route::getInstance();
$route->run();