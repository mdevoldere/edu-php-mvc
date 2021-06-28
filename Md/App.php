<?php 

namespace Md;

use Md\Db\DbContext;
use Md\Http\Http;
use Md\Http\IRequest;
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
        self::handleDatabases($_router->getRequest());

        if(null !== ($c = $_router->getController())) {
            Http::response($c->handleRequest());
        }

        Http::notFound('invalid controller');
    }

    static protected function handleDatabases(IRequest $_request) : void
    {
        $f = ($_request->getLocalPath().'var/db.conf.php');

        if(is_file($f)) {
            $a = require $f;
            foreach($a as $context => $params) {
                DbContext::setContext($context, $params);
            }
        }
    }
}
