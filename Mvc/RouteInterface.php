<?php 

namespace Md\Mvc;

use Md\Http\ServerRequestInterface;

interface RouteInterface
{
    public function getPath(string $_sub = ''): string;

    public function getRealPath(string $_sub = ''): string;

    public function getWebPath(string $_sub = ''): string;

    public function getRequest(): ServerRequestInterface;

    public function getController(): string;

    public function getAction(): string;

    public function getId(): ?string;
}
