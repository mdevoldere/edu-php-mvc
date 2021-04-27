<?php 

namespace Md\Http;

use Md\Views\IView;

interface IResponse 
{
    public const HTTP_JSON = 'application/json; charset=utf-8';
    public const HTTP_HTML = 'text/html';
    
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
     * Get Response Body
     * @return string the response body
     */
    public function getBody(): string;

    /**
     * Replace and set Response data 
     * @param array $_data the new response data
     * @return $this
     */
    public function setData(array $_data): IResponse;

    /**
     * Append data to current Response
     * @param array $_data data to append to the current response
     * @return $this
     */
    public function appendData(array $_data): IResponse;

    /**
     * Add 1 value to current Response data (if key already exists, replace data)
     * @param string $_key the key for the value to add
     * @param mixed $_value the associated value
     * @return $this
     */
    public function addData(string $_key, $_value): IResponse;

    /**
     * Replace and set View for rendering 
     * @param IView|null $_data the view to use
     * @return IView|null
     */
    public function setView(?IView $_view): ?IView;
}