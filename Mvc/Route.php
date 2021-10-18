<?php 

namespace Md\Mvc;

use Md\App\Debug;
use Md\Http\Http;
use Md\Http\ServerRequest;
use Md\Http\ServerRequestInterface;

use function mb_convert_case, trim;

class Route implements RouteInterface, RouterInterface
{
    private string $path;

    private string $realPath;

    private string $namespace;

    private string $instance;

    private string $app;

    private string $module;

    private string $web;

    private array $route;

    private ServerRequestInterface $request;


    public function __construct(array $_options)
    {
        $this->path = (dirname($_options['path']) . DIRECTORY_SEPARATOR);
        $this->realPath = $this->path;
        $this->namespace = '\\'.$_options['namespace'];
        $this->instance = basename($_options['path']);
        $this->request = ServerRequest::get();
        $this->route = $this->request->getUri()->getParts();

        $this->route[0] = mb_convert_case($this->route[0] ?? 'Home', MB_CASE_TITLE);

        if($this->route[0] === 'Api') {
            $this->namespace .= '\\Api';
            $this->realPath .= 'Api' . DIRECTORY_SEPARATOR;
        } 
        elseif(is_dir($this->path . 'App' . DIRECTORY_SEPARATOR . $this->route[0])) {
            $this->namespace .= '\\App\\'.$this->route[0];
            $this->realPath .= 'App' . DIRECTORY_SEPARATOR . $this->route[0] . DIRECTORY_SEPARATOR;
        }

        if($this->path !== $this->realPath) {
            array_shift($this->route);
            $this->route[0] = mb_convert_case($this->route[0] ?? 'Home', MB_CASE_TITLE);
        }

        $this->route[1] = $this->route[1] ?? 'index';
        $this->route[2] = $this->route[2] ?? null;
    }

    public function getPath(string $_sub = ''): string 
    {
        return ($this->path . $_sub);
    }

    public function getRealPath(string $_sub = ''): string 
    {
        return ($this->realPath . $_sub);
    }

    public function getWebPath(string $_sub = ''): string
    {
        return ($this->path . $this->instance . DIRECTORY_SEPARATOR . $_sub);
    }

    public function getRequest(): ServerRequestInterface
    {
        return $this->request;
    }

    public function getController(): string
    {
        return ($this->route[0] . 'Controller');
    }

    public function getAction(): string
    {
        return ($this->route[1] . 'Action');
    }

    public function getId(): ?string
    {
        return $this->route[2];
    }

    public function getRoute(): RouteInterface
    {
        return $this;
    }

    public function createController(): ?ControllerInterface
    {
        if(is_file($this->getRealPath('Controllers' . DIRECTORY_SEPARATOR . $this->getController() .'.php'))) {
            $c = ($this->namespace . 'Controllers\\' . $this->getController());
            return new $c();
        }

        return null;
    }
}
