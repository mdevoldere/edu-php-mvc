<?php 


namespace Md\Http;

class HttpFactory 
{
    static public function createRequest(string $_method, string $_url): RequestInterface
    {
        return new Request();
    }

    static public function createResponse(): ResponseInterface
    {
        return new Response();
    }
}
