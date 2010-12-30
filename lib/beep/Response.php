<?php
/**
 * Response.php
 * 
 * @package     Beep
 * @author      Mattijs Hoitink <mattijs@monkeyandmachine.com>
 * @copyright   Copyright (c) 2010 Mattijs Hoitink
 * @license     The MIT License - https://github.com/mattijs/beep/raw/master/LICENSE
 */
namespace beep;

/**
 * Beep response class for sending HTTP responses to clients
 */
class Response
{
    /**
     * HTTP response status codes with their message
     */
    public $statuses = array (
        // Informational 1xx
        100 => 'Continue',
        101 => 'Switching Protocols',
        // Successful 2xx
        200 => 'OK',
        201 => 'Created',
        202 => 'Accepted',
        203 => 'Non-Authoritative Information',
        204 => 'No Content',
        205 => 'Reset Content',
        206 => 'Partial Content',
        // Redirection 3xx
        300 => 'Multiple Choices',
        301 => 'Moved Permanently',
        302 => 'Found',
        303 => 'See Other',
        304 => 'Not Modified',
        305 => 'Use Proxy',
        306 => 'Not Used',
        307 => 'Temporary Redirect',
        // Client Error 4xx
        400 => 'Bad Request',
        401 => 'Unauthorized',
        402 => 'Payment Required',
        403 => 'Forbidden',
        404 => 'Not Found',
        405 => 'Method Not Allowed',
        406 => 'Not Acceptable',
        407 => 'Proxy Authentication Required',
        408 => 'Request Timeout',
        409 => 'Conflict',
        410 => 'Gone',
        411 => 'Length Required',
        412 => 'Precondition Failed',
        413 => 'Request Entity Too Large',
        414 => 'Request-URI Too Long',
        415 => 'Unsupported Media Type',
        416 => 'Requested Range Not Satisfiable',
        417 => 'Expectation Failed',
        // Server Error 5xx
        500 => 'Internal Server Error',
        501 => 'Not Implemented',
        502 => 'Bad Gateway',
        503 => 'Service Unavailable',
        504 => 'Gateway Timeout',
        505 => 'HTTP Version Not Supported'
    );
    
    /**
     * The HTTP procol to use
     * @var string
     */
    public $protocol = 'HTTP/1.1';
    
    /**
     * HTTP status code
     * @var int
     */
    public $status = 200;
    
    /**
     * HTTP response headers
     * @var array
     */
    public $headers = array();
    
    /**
     * Response body
     * @var string
     */
    public $body = '';
    
    /** **/
    
    /**
     * Construct a new Response
     */
    public function __construct()
    {
        // Add at least one default header
        $this->setHeader('X-Powered-By', 'Beep ' . beep::VERSION);
    }
    
    /**
     * Get and/or set the Response status code. If a new code is passed in this 
     * will be set as the status code for the Response.
     * @param int|NULL $status  The new status code (Optional)
     * @return int              The status code for the response
     */
    public function status($status = null)
    {
        if (null !== $status) {
            $this->status = $status;
        }
        
        return $this->status;
    }
    
    /**
     * Returns the message for a status
     * @param int $status   The status code to retreive the message for
     * @return string       The message for the status code
     */
    public function statusMessage($status = null)
    {
        if (null === $status) {
            $status = $this->status;
        }
        
        if (!array_key_exists($status, $this->statuses)) {
            throw new \Exception("Status code '{$status}' does not exist");
        }
        
        return $this->statuses[$status];
    }
    
    /**
     * Append the Response body with a string
     * @param string $body The body part to append
     */
    public function appendBody($body)
    {
        $this->body .= $body;
    }
    
    /**
     * Set an HTTP header
     * @param string $header The header to set
     * @param string $value  The value for the header
     */
    public function setHeader($header, $value)
    {
        $this->headers[$header] = $value;
    }
    
    /**
     * Set multiple HTTP headers as an array
     * @param array $headers The headers to set
     */
    public function setHeaders($headers)
    {
        foreach ($headers as $header => $value) {
            $this->setHeader($header, $value);
        }
    }
    
    /**
     * Send the Response back to the client
     */
    public function send($body = '', array $headers = array())
    {
        $this->sendHeaders($headers);
        $this->sendBody($body);
    }
    
    /**
     * Send Response headers to the client. Additional headers will overwrite 
     * previously set headers.
     * @param array $headers Additional headers to send
     * @return void
     */
    public function sendHeaders(array $headers = array())
    {
        // Merge additional headers
        $this->setHeaders($headers);
        
        // Send the HTTP response code
        $status = $this->status();
        $message = $this->statusMessage();
        header("{$this->protocol} {$status} {$message}");
        
        // Construct and send headers
        foreach ($this->headers as $header => $value) {
            header("{$header}: {$value}", true);
        }
    }
    
    /**
     * Send the Response body back to the client
     * @param string $body Additional body part
     * @return void
     */
    public function sendBody($body = '')
    {
        $this->appendBody($body);
        echo $this->body;
    }
}