<?php 

namespace Md\Controllers;

use Md\Db\IRepository;
use Md\Db\Repository;
use Md\Http\IRequest;
use Md\Http\IResponse;
use Md\Http\IRouter;
use Md\Http\Response;
use Md\Views\View;

/**
 * Abstract Class Controller
 */
abstract class Controller implements IController
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
          
        return $this->response;
    }

    /**
     * Set Generic Repository from table name and primary key name 
     * Remember to open the default connection with DbContext before use any generic repo
     */
    public function setRepository(string $_table, string $_pk)
    {
        $this->repo = new Repository($_table, $_pk);
    }

    /**
     * Default Controller Action
     */
    abstract public function indexAction(): void;
}