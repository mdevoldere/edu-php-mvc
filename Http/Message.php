<?php 

namespace Md\Http;

use function implode, json_encode, mb_convert_case, trim;

/**
 * Class Message
 * Represents a HTTP Message
 */
class Message implements MessageInterface
{
    /** Define a Generic message type */
    public const DEFAULT = 'application/octet-stream';

    /** Define a JSON message type */
    public const JSON = 'application/json';

    /** Define a HTML message type */
    public const HTML = 'text/html';

    /** Define a XML message type */
    public const XML = 'application/xml';

    /** @var string $contentType The current message content-type */
    private string $contentType;

    /** @var string $body The current message body */
    private string $body;

    /** @var string $data The current message body as array */
    private array $data;

    /**
     * Initialize a new HTTP Message with specified content-type and empty body
     * @param string $_contentType The message content-type or Message::DEFAULT if not defined
     * @param string $_body The message body or empty if not defined
     */
    public function __construct(string $_contentType = Message::DEFAULT, string $_body = '') 
    {
        $this->withContentType($_contentType)->withBody($_body);
    }

    /**
     * Get the current message content-type
     * @return string The message content-type
     */
    public function getContentType(): string 
    {
        return $this->contentType;
    }

    /**
     * Set the current message content-type
     * @param string $_contentType The content-type to apply 
     * @return self
     */
    public function withContentType(string $_contentType): MessageInterface 
    {
        $this->contentType = mb_convert_case($_contentType, MB_CASE_LOWER);
        return $this;
    }

    /**
     * Get the current message body
     * @return string The message body
     */
    public function getBody(): string 
    {
        return $this->body;
    }


    /**
     * Set the current message body
     * @param string $_body The message body
     * @return self
     */
    public function withBody(string $_body): MessageInterface 
    {
        $this->body = trim($_body);
        return $this;
    }


    /*public function getData(): array 
    {
        return [$this->body];
    }
    public function withData(array $_data): MessageInterface
    {
        if($this->contentType === Message::JSON) {
            return $this->withBody(json_encode($_data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
        }

        $s = [];
        foreach($_data as $k => $v) {
            $s[] = ($k.'='.$v);
        }

        return $this->withBody(implode(";", $s));
    }*/
}
