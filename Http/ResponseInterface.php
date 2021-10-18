<?php 

namespace Md\Http;

interface ResponseInterface extends MessageInterface
{
    //public const JSON = 'application/json; charset=utf-8';
    //public const HTML = 'text/html';
    
    /**
     * Get Response HTTP Code
     * @return int the response http code
     */
    public function getCode(): int;

    /**
     * Set Response HTTP code
     * @param int $_code the new response http code
     * @return self 
     */
    public function withCode(int $_code): ResponseInterface;

}
