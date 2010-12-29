<?php
/**
 * Request.php
 * 
 * @package     Beep
 * @author      Mattijs Hoitink <mattijs@monkeyandmachine.com>
 * @copyright   Copyright (c) 2010 Mattijs Hoitink
 * @license     The MIT License - https://github.com/mattijs/beep/raw/master/LICENSE
 */

namespace beep;

/**
 * Request data send to the BeepBeep Application.
 */
class Request
{
    // HTTP method constants
    const METHOD_GET    = 'GET';
    const METHOD_POST   = 'POST';
    const METHOD_PUT    = 'PUT';
    const METHOD_DELETE = 'DELETE';
    
    /**
     * Request protocol
     * @var string
     */
    public $protocol = 'HTTP/1.1';
    
    /**
     * Request method
     * @var string
     */
    public $method = self::METHOD_GET;
    
    /**
     * Request scheme
     * @var string
     */
    public $scheme = 'http';
    
    /**
     * Request host
     * @var string
     */
    public $host = 'localhost';
    
    /**
     * Request port
     */
    public $port = 80;
    
    /**
     * Request path
     * @var string
     */
    public $path = '/';
    
    /**
     * Request query string, broken down
     * @var array
     */
    public $query = array();
    
    /**
     * Request fragment
     * @var string
     */
    public $fragment = '';
    
    /** **/
    
    /**
     * Construct a new Request object
     * @todo Don't trust $_SERVER['PATH_INFO']: implement real path finding
     * @todo Parse request fragment
     */
    public function __construct()
    {
        $this->protocol = strtoupper($_SERVER['SERVER_PROTOCOL']);
        $this->method   = strtoupper($_SERVER['REQUEST_METHOD']);
        $this->scheme   = empty($_SERVER['HTTPS']) ? 'http' : 'https';
        $this->host     = $_SERVER['SERVER_ADDR'];
        $this->port     = $_SERVER['SERVER_PORT'];
        $this->path     = isset($_SERVER['PATH_INFO']) ? $_SERVER['PATH_INFO'] : $_SERVER['REQUEST_URI'];
        $this->query    = $this->chopQueryString($_SERVER['QUERY_STRING']);
    }
    
    /**
     * Chop down a request query string
     * @see https://gist.github.com/657201
     * @param string $query The query string to chop
     * @return array        The query string parts as an array
     */
    public function chopQueryString($query)
    {
        parse_str($query);
        unset($query);
        return get_defined_vars();
    }
}