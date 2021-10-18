<?php 

namespace Md\Mvc;

use Md\Db\RepositoryInterface;
use Md\Db\Repository;
use Md\Http\RequestInterface;
use Md\Http\ResponseInterface;

/**
 * Abstract Class Controller 
 * Default Controller implementation.
 * Returns JSON Response
 */
abstract class Controller implements ControllerInterface
{
    /** @var RouteInterface $request The HTTP request extracted from $router */
    protected RouteInterface $route;

    /** @var ResponseInterface $response The HTTP response object */
    protected ResponseInterface $response;

    /** @var null|RepositoryInterface $repo The Repository to use or null if no repository */
    protected ?RepositoryInterface $repo;

    /** @var array $data Data sent to response */
    protected array $data;

    public function __construct()
    {
        $this->repo = null;
        $this->data = [];
        $this->onLoad();
    }
    
    /**
     * Default Controller Action
     */
    // abstract public function indexAction(): void;

    /**
     * Load custom components when loading the controller
     */
    protected function onLoad() {}

    /**
     * Load custom components before processing the request
     */
    protected function beforeRequest() {}

    /**
     * Execute actions after processing the request
     */
    protected function afterRequest() {}

    public function getResponse(): ResponseInterface
    {
        return $this->response;
    }

    /**
     * Handle current request
     */
    public function handleRequest(RouteInterface $_route, ResponseInterface $_response): ResponseInterface
    {
        $this->route = $_route;
        $this->response = $_response;

        $a = ($this->route->getAction());

        if(!method_exists($this, $a)) {
            return $this->response->withCode(404)->withBody('Invalid Action');
        }

        $this->beforeRequest();
        $this->{$a}();
        $this->afterRequest();

        if($this->response->getContentType() === ResponseInterface::JSON) {
            return $this->response->setBody(json_encode($this->data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
        }

        $view = new View($this->request->getLocalPath('Views/')); 
        return $this->response->setBody($view->setFile($this->request->getView())->fetch($this->data));
    }

    /**
     * Set Generic Repository from table name and primary key name 
     * A generic repo use Db::getContext('default')
     */
    public function setRepository(string $_table, string $_pk)
    {
        $this->repo = new Repository($_table, $_pk);
    }

}
