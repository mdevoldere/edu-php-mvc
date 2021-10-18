<?php 


namespace Md\Mvc;

use Md\Http\RequestInterface;

interface ViewInterface 
{

}

interface ControllerInterface 
{

}

interface LocalPathInterface 
{
    public function isDir(string $_sub = ''): bool;
    public function isFile(string $_sub): bool;
    public function getPath(string $_sub = ''): string;
    public function getContents(string $_sub = ''): string;
    public function getArray(string $_sub = ''): array;
}

interface ModuleInterface extends LocalPathInterface
{
    public function getNamespace(string $_sub = ''): string;
    public function getController(string $_name): string;
    public function getViewsPath(): LocalPathInterface;
}

interface RouteInterface 
{
    public function getController(): ControllerInterface;
}

interface AppInterface 
{
    public function run(RequestInterface $request, RouteInterface $_route);
}
