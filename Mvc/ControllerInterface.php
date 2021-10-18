<?php 

namespace Md\Mvc;

use Md\Http\RequestInterface;
use Md\Http\ResponseInterface;

interface ControllerInterface 
{
    /**
     * Handle current request and returns response object
     * @param RouteInterface $_route the request to handle
     * @return ResponseInterface 
     */
    public function handleRequest(RouteInterface $_route, ResponseInterface $_response): ResponseInterface;

}