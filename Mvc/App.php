<?php 

namespace Md\Mvc;

use Md\App\Debug;
use Md\Db\Db;
use Md\Http\Http;
use Md\Http\Message;
use Md\Http\Request;
use Md\Http\RequestInterface;
use Md\Http\Response;
use Md\Http\ResponseInterface;
use Md\Http\ServerRequest;
use Md\Http\Uri;

use function dirname, is_file;

/**
 * MD MVC App Launcher
 * 
 */
class App  
{
    /**
     * Run MVC App using Default Components
     * @param string $_path App path (last path fragment = App namespace)
     */
    static public function run(array $_options): void
    {  
        //$s = new Uri('https://arfp.eu/crm//doc.php?id=1');
        //$t = new Uri('https://arfp.eu:8000////crm/../doc/test///////lol/......///index.php?id=1');
        //Debug::e([$s, $s->__toString(), $t, $t->__toString()]);

       /* $web = new LocalPath($_path);
        $root = new LocalPath(dirname($_path));
        $app = new Module($root->getPath($_namespace));

        $uri = new Uri($_SERVER['REQUEST_URI']);
        $request = new Request($uri);*/

       // $router = new Router(new Route(new Request(), $_options));

        //$serverRequest = new ServerRequest();

        //Debug::e([]);

        $app = new App(new Route($_options), new Response());
        
        $app->handleRequest();
    }

    private RouterInterface $router;

    public function __construct(RouterInterface $_router, ResponseInterface $_response)
    {        
        $this->router = $_router;

        //Debug::e($this);

        if(null !== ($controller = $this->router->createController())) {
            Debug::e($this);
        }
        
        Http::notFound();
    }

    public function getPath(string $_subPath = ''): string
    {
        return ($this->path . $_subPath);
    }

    /**
     * Override to Load customs components
     */
    protected function initComponents() : void {}

    /**
     * Override to cutomize Databases components 
     */
    protected function initDatabases(): void
    {
        $conf = is_file($this->getPath('var/db.conf.php')) ? (require $this->router->getPath('var/db.conf.php')) : [];

        foreach($conf as $context => $params) {
            Db::register($context, $params);
        }
    }

    /**
     * Run MVC App using IRouter
     */
    public function handleRequest(): void 
    {  
        Debug::e($this);
        $this->initDatabases();
        $this->initComponents();
        $response = $this->controller->handleRequest();

        if($response->getContentType() === Message::HTML) {
            $layout = new View($this->router->getPath('Views/')); 
            $layout->setFile('_layout');
            View::setVar('page', $response->getBody()); 
            $response->setBody($layout->fetch());
        }

        Http::response($response);
    }
}
