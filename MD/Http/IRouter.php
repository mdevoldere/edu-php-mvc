<?php 

namespace MD\Http;

use MD\Controllers\IController;

/**
 * Interface Irouter
 * 
 * Map Http Request with Local System 
 * 
 * @method string getPath() get current App local path
 * @method string getViewPath() get current App Views path (relative to getPath())
 * @method IRequest getRequest() get current Http Request 
 * @method null|IController getController() get controller using current Http Request
 */
interface IRouter 
{
    public function getPath(): string;

    public function getViewPath(): string;

    public function getRequest(): IRequest;

    public function getController(): ?IController;
}