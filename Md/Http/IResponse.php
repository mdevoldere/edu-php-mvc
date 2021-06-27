<?php 

namespace Md\Http;

use Md\Views\IView;

interface IResponse 
{
    public const JSON = 'application/json; charset=utf-8';
    public const HTML = 'text/html';
    
    /**
     * Get associated Request
     * @return string the request
     */
    public function getRequest(): IRequest;
    
    /**
     * Get Response HTTP Code
     * @return int the response http code
     */
    public function getCode(): int;

    /**
     * Set Response HTTP code
     * @param int $_code the new response http code
     * @return $this 
     */
    public function setCode(int $_code): IResponse;

    /**
     * Get Response Content-Type
     * @return int the response Content-Type
     */
    public function getContentType(): string;

    /**
     * Set Response Content-Type
     * @param string $_contentType
     * @return self
     */
    public function setContentType(string $_contentType): IResponse;

    /**
     * Get Response Body
     * @return string the response body
     */
    public function getBody(): string;

     /**
     * Set Response Body
     * @param string $_body
     * @return self
     */
    public function setBody(string $_body): IResponse;
}
