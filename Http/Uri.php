<?php 

namespace Md\Http;

use function explode, parse_str, parse_url, trim;

class Uri implements UriInterface 
{

    /**
     * Secure data in array
     * Remove paths (basename)
     * Accept alphanumerics characters, dash, dot
     * @return array the input array 
     */
    static public function secure(array $_data = []): array
    {
        return array_filter(array_map(function ($v) {
            $v = basename($v);
            if(!preg_match("/^[A-Za-z0-9\.\-]*$/", $v)) {
                http_response_code(400);
                exit('Bad Request');
            }
            return $v;
           // return \preg_replace("/[^a-zA-Z0-9]/", "", \basename(\strip_tags($v), '.php'));
        }, $_data));
    }

    public string $scheme;

    public string $host;

    public int $port;

    public string $path;

    public string $query;

    public function __construct(string $_uri = '/')
    {
        $u = parse_url(filter_var($_uri, FILTER_SANITIZE_URL));

        $this->withHost($u['host'] ?? $_SERVER['SERVER_NAME'] ?? 'localhost')
             ->withPort($u['port'] ?? $_SERVER['SERVER_PORT'] ?? 0)
             ->withPath($u['path'] ?? '')
             ->withQuery($u['query'] ?? '');
    }

    public function __toString(): string
    {
        return ($this->scheme . '://' . $this->host . 
            (!in_array($this->port, [0, 80, 443]) ? (':'. $this->port) : '') . '/' . 
            $this->path . (!empty($this->query) ? '?'.$this->query : ''));
    }

    public function withHost(string $_host): UriInterface
    {
        $this->host = $_host;
        return $this;
    }

    public function withPort(int $_port): UriInterface 
    {
        $this->port = (filter_var(
            $_port, 
            FILTER_VALIDATE_INT, 
            ['options' => ['min_range' => 0, 'max_range' => 65535]]
        ) !== false ? $_port : 0);
        $this->scheme = ($this->port !== 80 ? 'https' : 'http');
        return $this;
    }

    public function withPath(string $_path): UriInterface
    {   
        $this->path = mb_convert_case(preg_replace(['#\.+/#','#/+#'], '/', trim($_path ?? '', '/')), MB_CASE_LOWER);
        return $this;
    }

    public function withQuery(string $_query): UriInterface
    {
        $this->query = $_query;
        return $this;
    }

    public function withQueryArray(array $_query): UriInterface
    {
        return $this->withQuery(http_build_query($_query));
    }

    public function getHost(): string 
    {
        return $this->host;
    }

    public function getPort(): int 
    {
        return $this->host;
    }

    public function getPath(): string
    {
        return $this->path;
    }

    public function getParts(): array
    {
        return self::secure(explode('/', $this->path));;
    }

    public function getQuery(): string
    {
        return $this->query;
    }

    public function getQueryArray(): array 
    {
        $q = [];
        parse_str($this->query ?? '', $q);
        return self::secure($q);
    }
}
