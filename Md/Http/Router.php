<?php 

namespace Md\Http;

use Md\Controllers\IController;

use function dirname, explode, str_replace, trim;

class Router implements IRouter 
{
    protected IController $controller;
    protected IResponse $response;

    public function __construct(string $_namespace, string $_path)
    {
        $_route = str_replace('//', '/', $_SERVER['REQUEST_URI']);
        $_route = explode('?', $_route)[0] ?? '/';
        $_route = explode('/', trim($_route, '/'));     
        $request = new Request($_route, (dirname($_path) . DIRECTORY_SEPARATOR . str_replace('\\', DIRECTORY_SEPARATOR, $_namespace) . DIRECTORY_SEPARATOR));
        $this->response = new Response($request);
        $ctrl = ('\\' . $_namespace . '\\Controllers\\' . $request->getController());
        $this->controller = new $ctrl($this->response);
    }  

    public function getRequest(): IRequest
    {
        return $this->response->getRequest();
    }

    public function getResponse(): IResponse
    {
        return $this->response;
    }

    public function getController(): IController
    {
        return $this->controller;
    }
}
