<?php
/**
 * /!\ For local ENV 
 * replace htaccess rules in php internal server
 * run "php -S localhost:3000 router.php inside this directory
 */


/**
 * if static file, let server return the file directly
 */
if(file_exists(__DIR__.$_SERVER['REQUEST_URI'])) {
    return false;
}

/**
 * PHP doesn't consider favicon to be a file, let's fix it !
 */
if($_SERVER['REQUEST_URI'] === '/favicon.ico') {
    return false;
}

/**
 * if no match files, index.php takes over  
 */ 
require (__DIR__.'/index.php');
