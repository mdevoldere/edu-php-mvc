<?php 

namespace Md\Http; 

/**
 * Interface RequestInterface
 * Represents the current Incoming HTTP Request (IHR)
 * 
 */
interface RequestInterface extends MessageInterface
{
    /**
     * Get current HTTP Message method
     * @return string the current request method
     */
    public function getMethod(): string;

    /**
     * Get the Uri used by the current request
     * @return UriInterface the Uri used by the current request
     */
    public function getUri(): UriInterface;

    /**
     * Get the Uri path as array
     * @return array the Uri path as array
     */
    //public function getRoute(int $_pos): ?string;

    /**
     * Get current Request HTTP data (get params, post form etc...) 
     * Data depends on the HTTP method used
     * @return array the current request data
     */
    public function getData(): array;
}
