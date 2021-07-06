<?php 

namespace Md\Http;

use Exception;
use Md\Controllers\IController;

use function dirname, explode, str_replace, trim;

class Router implements IRouter 
{
    protected string $controller;
    protected IResponse $response;

    public function __construct(string $_namespace, string $_path, string $_baseUrl = '/')
    {
        $_route = str_replace('//', '/', $_SERVER['REQUEST_URI']);
        $_route = explode('?', $_route)[0] ?? '/';
        $_route = trim($_route, '/');

        if($_baseUrl !== '/') {
            $_route = str_replace($_baseUrl, '', $_route);
        }

        $_route = explode('/', $_route);     
        $request = new Request($_route, (dirname($_path) . DIRECTORY_SEPARATOR . str_replace('\\', DIRECTORY_SEPARATOR, $_namespace) . DIRECTORY_SEPARATOR));
        $this->response = new Response($request);
        $this->controller = ('\\' . $_namespace . '\\Controllers\\' . $request->getController());
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
        $c = str_replace('\\', '/', $this->controller);
        if(!is_file($this->getRequest()->getLocalPath($c)))
        try {
            return new $this->controller($this->response);
        } catch(Exception $e) {
            exit('Error : Invalid controller');
        }
        
    }
}
