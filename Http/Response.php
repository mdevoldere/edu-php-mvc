<?php 

namespace Md\Http;


class Response extends Message implements ResponseInterface
{
    private int $code;

    public function __construct()
    {
        parent::__construct();
        $this->code = 200;
    }

    public function getCode(): int
    {
        return $this->code;
    }

    public function withCode(int $_code): ResponseInterface 
    {
        $this->code = $_code;
        return $this;
    } 
}
