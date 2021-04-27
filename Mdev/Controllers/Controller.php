<?php 

namespace Md\Controllers;

use Md\Db\IRepository;
use Md\Db\Repository;
use Md\Http\IRequest;
use Md\Http\IResponse;
use Md\Http\IRouter;
use Md\Http\Response;
use Md\Views\View;

abstract class Controller implements IController
{
    protected IRouter $router; 

    protected IRequest $request;

    protected IResponse $response;

    protected ?IRepository $repo;

    protected bool $view;

    public function __construct(IRouter $_router)
    {
        $this->router = $_router;
        $this->request = $_router->getRequest();
        $this->response = new Response();
        $this->repo = null;
        $this->view = false;
        $this->init();
    }

    protected function init() 
    {
        
    }

    public function handleRequest(): IResponse
    {
        $a = $this->request->getAction();

        if(!method_exists($this, $a)) {
            return $this->response->setCode(404)->addData('error', 'Invalid Action');
        }

        $this->{$a}();

        if($this->view === false) {
            return $this->response;
        }

        $layout = new View($this->router->getPath().'Views/'); 
        $layout->setFile('_layout');
        $layout->setChild('page', $this->router->getViewPath());    
        $this->response->setView($layout);    
        return $this->response;
    }

    public function setRepository(string $_table, string $_pk)
    {
        $this->repo = new Repository($_table, $_pk);
    }

    abstract public function indexAction(): void;
}