<?php 

namespace Md\Http;

use function mb_convert_case, parse_str, file_get_contents, sprintf;

class Request implements IRequest
{
    private string $method;
    private string $controller;
    private string $action;
    private ?string $id;

    public function __construct(array $_route)
    {
        $this->method = mb_convert_case($_SERVER['REQUEST_METHOD'] ?? 'get', MB_CASE_LOWER);
        $this->controller = mb_convert_case($_route[0] ?? 'home', MB_CASE_TITLE);
        $this->action = mb_convert_case($_route[1] ?? 'index', MB_CASE_LOWER);
        $this->id = ($_route[2] ?? null);
    }

    public function getMethod(): string
    {
        return $this->method;
    }

    public function getRoute(): string
    {
        return sprintf('%s/%s/%s', $this->controller, $this->action, $this->id);
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

    public function getData(): array
    {
        switch($this->method)
        {
            case 'get':
            case 'delete':
                return Http::secure($_GET ?? []);
            break;
            case 'post':
                $a = Http::secure($_POST ?? []);
                if(!empty($_FILES)) {
                    $a['_files'] = &$_FILES;
                }
                return $a;
            break;
            case 'put':
                $a = null;
                parse_str(file_get_contents('php://input'), $a);
                return Http::secure($a);
            break;
            default:
                return [];
            break;
        }
    }
}