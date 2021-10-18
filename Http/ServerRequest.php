<?php 

namespace Md\Http;

use Md\App\Debug;

use function explode, parse_str, file_get_contents;

class ServerRequest implements ServerRequestInterface
{
    private static $instance = null;

    public static function get(): ServerRequestInterface 
    {
        if(self::$instance === null) {
            self::$instance = new static();
        }

        return self::$instance;
    }

    private string $method;

    private string $contentType;

    private UriInterface $uri;

    private function __construct()
    {
        $this->method = $_SERVER['REQUEST_METHOD'] ?? 'GET';
        $this->contentType = explode(',', $_SERVER['HTTP_ACCEPT'] ?? '*/*')[0] ?? '*/*';
        $this->uri = new Uri($_SERVER['REQUEST_URI']);
    }

    public function getMethod(): string 
    {
        return $this->method;
    }

    public function getContentType(): string 
    {
        return $this->contentType;
    }

    public function getUri(): UriInterface 
    {
        return $this->uri;
    }

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
