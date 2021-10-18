<?php 

namespace Md\Http; 

/**
 * Interface ServerRequestInterface
 * Represents an incoming HTTP Request (IHR)
 * 
 */
interface ServerRequestInterface //extends MessageInterface
{

    public function getMethod(): string;

    public function getContentType(): string;

    public function getUri(): UriInterface;

    public function getData(): array;
}