<?php 

namespace Md\Controllers;


use Md\Http\IResponse;


interface IController 
{
    public function handleRequest(): IResponse;
}