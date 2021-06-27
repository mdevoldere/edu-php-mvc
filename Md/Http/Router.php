<?php 

namespace Md\Http;

use Md\Controllers\IController;
use Md\Loader;

use function basename, str_replace, explode, trim, sprintf;

class Router implements IRouter 
{
    protected string $path;
    protected string $controller;
    protected IRequest $request;

    public function __construct(string $_namespace, string $_path)
    {
        $this->path = (dirname($_path) . DIRECTORY_SEPARATOR . str_replace('\\', DIRECTORY_SEPARATOR, $_namespace) . DIRECTORY_SEPARATOR);

        $_route = str_replace('//', '/', $_SERVER['REQUEST_URI']);
        $_route = explode('?', $_route)[0] ?? '/';
        $_route = explode('/', trim($_route, '/'));     

        $this->request = new Request($_route);

        $this->controller = ('\\' . $_namespace . '\\Controllers\\' . $this->request->getController());
    }  

    public function getPath(): string
    {
        return $this->path;
    }

    public function getViewsPath(): string
    {
        return ($this->path . 'Views/');
    }

    public function getRequest(): IRequest
    {
        return $this->request;
    }

    public function getController(): ?IController
    {
        return (new $this->controller($this));
    }
    
}