<?php

ini_set('display_errors', true);
error_reporting(E_ALL);

define('APPLICATION_PATH', realpath(dirname(__FILE__).'/..'));

require_once APPLICATION_PATH . '/library/AutoLoader.php';
OsuMirror_AutoLoader::getInstance()
    ->registerAutoLoader();

$route = OsuMirror_Route::getInstance();
$route->run();