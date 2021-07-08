<?php 

namespace Md\Controllers;

use Md\Db\IRepository;
use Md\Db\Repository;
use Md\Http\IRequest;
use Md\Http\IResponse;
use Md\Http\Response;
use Md\Views\View;

/**
 * Abstract Class Controller
 */
abstract class Controller implements IController
{
    /** @var IRequest $request The HTTP request extracted from $router */
    protected IRequest $request;

    /** @var IResponse $response The HTTP response object */
    protected IResponse $response;

    /** @var null|IRepository $repo The Repository to use or null if no repository */
    protected ?IRepository $repo;

    /** @var array $data Data sent to response */
    protected array $data;

    public function __construct(IResponse $_response)
    {
        $this->request = $_response->getRequest();
        $this->response = $_response;
        $this->repo = null;
        $this->data = [];
        $this->init();
    }
    
    /**
     * Default Controller Action
     */
    abstract public function indexAction(): void;

    /**
     * Load custom components
     */
    protected function init() {}

    /**
     * Handle current request
     */
    public function handleRequest(): IResponse
    {
        $a = $this->request->getAction();

        if(!method_exists($this, $a)) {
            return $this->response->setCode(404)->setBody('Error : Invalid Action');
        }

        $this->{$a}();

        if($this->response->getContentType() === IResponse::JSON) {
            return $this->response->setBody(json_encode($this->data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
        }

        $layout = new View($this->request->getLocalPath('Views/')); 
        $layout->setFile('_layout');
        $layout->setChild('page', $this->request->getView());  
        return $this->response->setBody($layout->fetch($this->data));  
    }

    /**
     * Set Generic Repository from table name and primary key name 
     * Remember to set the "default" connection with DbContext before use any generic repo
     */
    public function setRepository(string $_table, string $_pk)
    {
        $this->repo = new Repository($_table, $_pk);
    }

}