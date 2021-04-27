<?php

namespace Md;

/**
 * MDClass AutoLoader
 * 
 * @deprecated
 * 
 * @version 1.0.0
 * @author mdevoldere <dev@devoldere.net>
 * 
 * @method static string classPath(string $_classname) Get a class local path from its name
 * @method static void autoload(string $_classname) Autoloader (triggered by PHP)
 * @method static void register() Register this Loader in PHP autoloading stack                     
 * 
 * @todo Attach/Detach Error Handler(s)
 */
abstract class Loader
{
    /**
     * @deprecated
     * Get local path from class name
     * ex: \Md\MyNamespace\MyClass --> RootPath/MD/MyNamespace/MyClass.php 
     * @param string $_classname the class full name
     * @return string|null the class local path or null if file not exists
     */
    static public function classPath(string $_classname): ?string
    {
        $_classname = (\str_replace('\\', DIRECTORY_SEPARATOR, $_classname) . '.php');
        $c = (MD . $_classname);
        echo ('<pre>'.var_export($c, true));
        return \is_file($c) ? $c : null;
    }

    /**
     * @deprecated
     * Autoloader (triggered by PHP)
     * @param string $_classname the class to autoload
     */
    static public function autoload(string $_classname)
    {
        if (null !== ($classname = self::classpath($_classname))) {
            //echo $classname.'<br>';
            require $classname;
            return true;
        }

        //echo $_classname.'<br>';
        exit('Invalid Component');
        //return false;
    }

    /**
     * @deprecated
     * Register this Loader in PHP autoloading stack
     * Required before use \Md\* classes
     */
    static public function register()
    {
        /** const MD: define RootPath for autoloading purposes */
        \define('MD', (dirname(__DIR__) . DIRECTORY_SEPARATOR));

        /** register */
        //\spl_autoload_register('\\Md\\Loader::autoload');

        // \set_error_handler('');
    }

}

// register this loader when loading this file
// \Md\Loader::register();
