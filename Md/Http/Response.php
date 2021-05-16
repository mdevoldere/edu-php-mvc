<?php 

namespace Md\Http;

use Md\Views\IView;
use Md\Views\View;

use function json_encode;

class Response implements IResponse
{
    private int $code;

    private string $contentType;

    private array $data;

    private ?View $view;

    public function __construct()
    {
        $this->code = 200;
        $this->contentType = IResponse::HTTP_JSON;
        $this->data = [];
        $this->view = null;
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

    public function getBody(): string
    {
        if($this->view === null) {
            return json_encode($this->data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
        }
        
        return $this->view->fetch($this->data);        
    }

    public function getData(): array
    {
        return $this->data;
    }

    public function setData(array $_data = []): IResponse
    {
        $this->data = $_data;
        return $this;
    }

    public function appendData(array $_data = []): IResponse
    {
        foreach($_data as $k => $v) {
            $this->addData($k, $v);
        }
        return $this;
    }

    public function addData(string $_key, $_value = null): IResponse
    {
        $this->data[$_key] = $_value;
        return $this;
    }

    public function setView(?IView $_view): IResponse
    {
        $this->view = $_view;
        $this->contentType = ($_view !== null) ? IResponse::HTTP_HTML : IResponse::HTTP_JSON;
        return $this;
    }
}