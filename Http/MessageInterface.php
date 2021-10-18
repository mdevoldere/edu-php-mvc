<?php 

namespace Md\Http;

/**
 * Interface MessageInterface
 * Represents a HTTP Message
 * 
 */
interface MessageInterface 
{
    /**
     * Get the current message content-type
     * @return string The message content-type
     */
    public function getContentType(): string;

    /**
     * Set the current message content-type
     * @param string $_contentType The content-type to apply 
     * @return MessageInterface
     */
    public function withContentType(string $_contentType): MessageInterface;
    
    /**
     * Get the current message body
     * @return string The message body
     */
    public function getBody(): string;

    /**
     * Set the current message body
     * @param string $_body The message body
     * @return MessageInterface
     */
    public function withBody(string $_body): MessageInterface;


    /**
     * Get the current message body as array
     * @return string The message body
     */
    //public function getData(): array;
    /**
     * Set the current message body
     * @param array $_data The message body to parse.
     * @return MessageInterface
     */
    //public function withData(array $_data): MessageInterface;
}
