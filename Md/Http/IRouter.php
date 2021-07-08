<?php 

namespace Md\Http;

use Md\Controllers\IController;

/**
 * Interface Irouter
 * 
 * Map Http Request with Local System 
 * 
 * @method null|IController getController() get controller using current Http Request
 * @method IRequest getRequest() get current Http Request 
 * @method IResponse getResponse() get current Http Reponse 
 */
interface IRouter 
{
    public function getController(): ?IController;
    public function getRequest(): IRequest;
    public function getResponse(): IResponse;
}
