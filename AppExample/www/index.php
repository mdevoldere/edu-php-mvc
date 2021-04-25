<?php 
/**
 * run "php -S localhost:3000 router.php inside this directory
 * go to http://localhost:3000 
*/


 /** Autoloader */
require dirname(__DIR__, 2).'/MD/Loader.php';

/** Set IRouter */
$router = new \Md\Http\Router('AppExample', '/');

/** Run App */
\Md\App::run($router);
