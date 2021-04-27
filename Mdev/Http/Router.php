<?php 

namespace Md\Http;

use Md\Controllers\IController;
use Md\Loader;

use function basename, str_replace, explode, trim, sprintf;

class Router implements IRouter 
{
    protected string $namespace;
    protected string $path;
    protected string $url;
    protected string $controller;
    protected string $view;
    protected IRequest $request;

    public function __construct(string $_namespace, string $_baseUrl = '/')
    {
        $this->path = (dirname(__DIR__, 2) . DIRECTORY_SEPARATOR . str_replace('\\', DIRECTORY_SEPARATOR, $_namespace) . DIRECTORY_SEPARATOR);
        $this->namespace = $_namespace;
        $this->url = $_baseUrl;

        $_route = (($this->url !== '/') ? str_replace($this->url, '/', $_SERVER['REQUEST_URI']) : $_SERVER['REQUEST_URI']);
        $_route = str_replace('//', '/', $_route);
        $_route = explode('?', $_route)[0] ?? '/';
        $_route = Http::secure(explode('/', trim($_route, '/')));     

        $this->request = new Request($_route);

        $this->controller = sprintf('\\%s\\Controllers\\%s', $this->namespace, $this->request->getController());

        $this->view = sprintf('%s/%s', basename($this->request->getController(), 'Controller'), basename($this->request->getAction(), 'Action'));
    }  

    public function getPath(): string
    {
        return $this->path;
    }

    public function getViewPath(): string
    {
        return $this->view;
    }

    public function getRequest(): IRequest
    {
        return $this->request;
    }

    public function getController(): ?IController
    {
        if(Loader::classPath($this->controller) === null) {
            return null;
        }
        return (new $this->controller($this));
    }
    
}