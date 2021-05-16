<?php 

namespace Md;

use Md\Controllers\IController;
use Md\Db\DbContext;
use Md\Http\Http;
use Md\Http\IRouter;

/**
 * MD MVC App Launcher
 * 
 * Override handleController() to customize controllers loading
 * 
 */
class App
{
    /**
     * Run MVC App using IRouter
     * @param IRouter $_router the IRouter object to use
     */
    final static public function run(IRouter $_router): void
    {   
        self::handleDatabases($_router);

        if(null !== ($c = static::handleController($_router))) {
            Http::response($c->handleRequest());
        }

        Http::notFound('invalid controller');
    }

    /**
     * Load Controller from given IRouter
     * @param IRouter $_router the IRouter object to use
     * @return IController|null the loaded IController or null if not found
     */
    static protected function handleController(IRouter $_router): ?IController 
    {
        return $_router->getController();
    }

    static protected function handleDatabases(IRouter $_router) : void
    {
        $f = ($_router->getPath().'var/db.conf.php');

        if(is_file($f)) {
            $a = require $f;
            foreach($a as $context => $params) {
                DbContext::setContext($context, $params);
            }
        }
    }
}
