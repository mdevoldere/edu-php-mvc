<?php 

namespace Md\Http;

interface UriInterface 
{
    //public function withScheme(?string $_scheme = null): UriInterface;
    public function withHost(string $_host): UriInterface;
    public function withPort(int $_port): UriInterface;
    public function withPath(string $_path): UriInterface;
    public function withQuery(string $_query): UriInterface;
    public function withQueryArray(array $_query): UriInterface;
    //public function getScheme(): string;
    public function getHost(): string; 
    public function getPort(): int;
    public function getPath(): string;
    public function getParts(): array;
    public function getQuery(): string;
    public function getQueryArray(): array;
}
