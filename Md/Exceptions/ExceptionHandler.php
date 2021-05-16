<?php

namespace Md\Exceptions;

use Md\Http\Http;

use function set_error_handler, set_exception_handler, sprintf;

/**
 * A class that handles both errors and exceptions
 */
class ExceptionHandler 
{

    public function __construct()
    { 
        set_error_handler([$this, 'errorHandler']);
        set_exception_handler([$this, 'errorHandler']);        
    }

    public function exceptionHandler(\Throwable $exception)
    {
        switch($exception->getCode()) 
        {
            case 200:
            case 201:
            case 202:
            case 204:
            case 400:
            case 401:
            case 403:
            case 404:
            case 405:
            case 406:
            case 500:
            case 501:
                Http::end($exception->getCode(), $exception->getMessage());
            break;
            default:
                Http::end(500, $exception->getCode() . ' ' . $exception->getMessage());
            break;
        }
    }

    public function errorHandler($error_level, $error_message, $error_file, $error_line)
    {
        $error_message = sprintf('%s %s %d %s', $error_level, $error_message, $error_line, $error_file);
        
        Http::end(500, $error_message);         
    }
}