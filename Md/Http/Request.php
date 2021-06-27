<?php 

namespace Md\Http;

use function mb_convert_case, parse_str, file_get_contents, sprintf;

class Request implements IRequest
{
    private string $localPath;
    private string $method;
    private string $controller;
    private string $action;
    private ?string $id;

    public function __construct(array $_route, string $_localPath)
    {
        $_route = Http::secure($_route);
        $this->localPath = $_localPath;
        $this->method = mb_convert_case($_SERVER['REQUEST_METHOD'] ?? 'GET', MB_CASE_UPPER);
        $this->controller = mb_convert_case($_route[0] ?? 'Home', MB_CASE_TITLE);
        $this->action = mb_convert_case($_route[1] ?? 'index', MB_CASE_LOWER);
        $this->id = ($_route[2] ?? null);
    }

    public function getMethod(): string
    {
        return $this->method;
    }

    public function getRoute(): string
    {
        return mb_convert_case($this->controller . '/' . $this->action . '/' . $this->id, MB_CASE_LOWER);
    }

    public function getData(): array
    {
        switch($this->method)
        {
            case 'GET':
            case 'DELETE':
                return Http::secure($_GET ?? []);
            break;
            case 'POST':
                $a = Http::secure($_POST ?? []);
                if(!empty($_FILES)) {
                    $a['_files'] = $_FILES;
                }
                return $a;
            break;
            case 'PUT':
                $a = null;
                parse_str(file_get_contents('php://input'), $a);
                return Http::secure($a);
            break;
            default:
                return [];
            break;
        }
    }

    public function getController(): string
    {
        return ($this->controller . 'Controller');
    }

    public function getAction(): string
    {
        return ($this->action . 'Action');
    }

    public function getId(): ?string
    {
        return $this->id;
    }

    public function getLocalPath(?string $_subpath): string 
    {
        return $this->localPath . ($_subpath);
    }

    public function getView(): string
    {
        return ($this->controller.'/'.$this->action);
    }
}
