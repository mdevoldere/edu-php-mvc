<?php 

namespace Md\Http;


use function array_map, header, http_response_code, preg_match;

/**
 * HTTP Operations
 */
class Http 
{
    /** Current */
    static private RequestInterface $request;

    static public function getRequest() 
    {
        return self::$request = self::$request ?? new Request();
    }

    static public function setRequest($_request) 
    {
        self::$request = $_request;
    }

    static public function getResponse() 
    {
        return self::getRequest()->getResponse();
    }


    /**
     * Secure data in array
     * Accept alphanumerics characters only
     * @return array the input array whitout
     * @todo Move method outside this class
     */
    static public function secure(array $_data = []): array
    {
        return array_filter(array_map(function ($v) {
            $v = basename($v);
            if(!preg_match("/^[A-Za-z0-9\.\-]*$/", $v)) {
                self::badRequest('Invalid data');
            }
            return $v;
           // return \preg_replace("/[^a-zA-Z0-9]/", "", \basename(\strip_tags($v), '.php'));
        }, $_data));
    }

    static public function end(int $_code = 500, string $data = 'Internal Error'): void 
    {
        http_response_code($_code);
        exit($data);
    }

    static public function response(ResponseInterface $response): void 
    {
        header('Content-Type: ' . $response->getContentType());
        self::end($response->getCode(), $response->getBody());
    }

    static public function ok(string $data): void 
    {        
        self::end(200, $data);
    }

    static public function accepted(string $data): void 
    {
        self::end(202, $data);
    }

    static public function badRequest(string $msg = 'Invalid message received'): void 
    {
        self::end(400, $msg);
    }

    static public function unauthorized(string $msg = 'Invalid Token'): void 
    {
        self::end(401, $msg);
    }

    static public function forbidden(string $msg = 'You are not allowed to access this resource'): void 
    {
        self::end(403, $msg);
    }

    static public function notFound(string $msg = 'Not found'): void 
    {
        self::end(404, ('No route for '.$_SERVER['REQUEST_URI']. ' ('.$msg.')'));
    }

    static public function notAllowed(): void 
    {
        self::end(405, ('No route for '.$_SERVER['REQUEST_METHOD'].' '.$_SERVER['REQUEST_URI']));
    }

    static public function notImplemented(): void 
    {
        self::end(501, 'Not implemented');
    }
}
