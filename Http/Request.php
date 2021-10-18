<?php 

namespace Md\Http;

use function explode, parse_str, file_get_contents;

class Request extends Message implements RequestInterface
{
    private UriInterface $uri;
    //private array $route;
    private string $method;
    

    public function __construct(?UriInterface $_uri = null)
    {
        parent::__construct(explode(',', $_SERVER['HTTP_ACCEPT'] ?? Message::HTML)[0] ?? Message::HTML);
        $this->setMethod($_SERVER['REQUEST_METHOD'] ?? 'GET');
        $this->uri = $_uri ?? new Uri($_SERVER['REQUEST_URI'] ?? '/');    
        //$this->route = $this->uri->getParts();
    }

    public function setMethod(string $_method): RequestInterface
    {
        $this->method = $_method;
        return $this;
    }

    public function getMethod(): string 
    {
        return $this->method;
    }

    public function getUri(): UriInterface 
    {
        return $this->uri;
    }

    /*public function getRoute(int $_pos): ?string
    {
        return $this->route[$_pos] ?? null;
    }*/

    public function getData(): array
    {
        switch($this->method)
        {
            case 'GET':
            case 'DELETE':
                return $this->uri->getQueryArray();
            break;
            case 'POST':
                $a = Uri::secure($_POST ?? []);
                if(!empty($_FILES)) {
                    $a['_files'] = $_FILES;
                }
                return $a;
            break;
            case 'PATCH':
            case 'PUT':
                $a = [];
                parse_str(file_get_contents('php://input'), $a);
                return Uri::secure($a);
            break;
            default:
                return [];
            break;
        }
    }   
}
