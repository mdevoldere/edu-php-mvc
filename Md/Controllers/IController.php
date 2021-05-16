<?php 

namespace Md\Controllers;


use Md\Http\IResponse;


interface IController 
{
    /**
     * Handle current request and returns response object
     * @return IResponse 
     */
    public function handleRequest(): IResponse;
}