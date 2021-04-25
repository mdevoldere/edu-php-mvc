<?php 

namespace MD\Controllers;


use MD\Http\IResponse;


interface IController 
{
    public function handleRequest(): IResponse;
}