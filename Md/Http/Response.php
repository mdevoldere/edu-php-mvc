<?php 

namespace Md\Http;

use Md\Views\IView;
use Md\Views\View;

use function json_encode;

class Response implements IResponse
{
    private int $code;

    private string $contentType;

    private IRequest $request;

    private string $body;

    public function __construct(IRequest $_request)
    {
        $this->code = 200;
        $this->contentType = IResponse::JSON;
        $this->request = $_request;
        $this->body = "";
    }

    public function getRequest(): IRequest
    {
        return $this->request;
    }  

    public function getCode(): int
    {
        return $this->code;
    }

    public function setCode(int $_code): IResponse 
    {
        $this->code = $_code;
        return $this;
    }

    public function getContentType(): string
    {
        return $this->contentType;
    }

    public function setContentType(string $_contentType): IResponse
    {
        $this->contentType = $_contentType;
        return $this;
    }

    public function getBody(): string
    {        
        return $this->body;        
    }

    public function setBody(string $_body): IResponse
    {
        $this->body = $_body;
        return $this;
    }  
}
