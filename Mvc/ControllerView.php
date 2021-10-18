<?php 

namespace Md\Mvc;

use Md\Db\IRepository;
use Md\Db\Repository;
use Md\Http\IRequest;
use Md\Http\IResponse;
use Md\Http\IRouter;
use Md\Http\Response;
use Md\Views\View;

/**
 * Abstract Class ControllerView
 */
abstract class ControllerView extends Controller implements IController
{
    /** @var IRouter $router The router used by current App */
    protected IRouter $router; 

    /** @var IRequest $request The HTTP request extracted from $router */
    protected IRequest $request;

    /** @var IResponse $response The HTTP response object */
    protected IResponse $response;

    /** @var null|IRepository $repo The Repository to use or null if no repository */
    protected ?IRepository $repo;

    /** @var bool $view defines if a view should be loaded (true) */
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

        $layout = new View($this->router->getViewsPath()); 
        $layout->setFile('_layout');
        $layout->setChild('page', $this->request->getView());  
        $this->response->setView($layout);    
        return $this->response;
    }

    /**
     * Set Generic Repository from table name and primary key name 
     * Remember to open the connection with DbContext before use any repo
     */
    public function setRepository(string $_table, string $_pk)
    {
        $this->repo = new Repository($_table, $_pk);
    }

    abstract public function indexAction(): void;
}