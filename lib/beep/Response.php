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
     * HTTP status code
     * @var int
     */
    public $code = 200;
    
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
    
    public function __construct()
    {
        // Add at least one default header
        $this->setHeader('X-Powered-By', 'Beep ' . beep::VERSION);
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