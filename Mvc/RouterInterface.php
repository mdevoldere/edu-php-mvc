<?php 

namespace Md\Mvc;

use Md\Http\RequestInterface;

/**
 * Interface RouterInterface
 * 
 * Locate the controller using RouteInterface Object
 * 
 */
interface RouterInterface
{
    public function getRoute(): RouteInterface;

    public function createController(): ?ControllerInterface;
}
