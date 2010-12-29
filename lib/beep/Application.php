<?php
/**
 * Application.php
 *
 * @package     Beep
 * @author      Mattijs Hoitink <mattijs@monkeyandmachine.com>
 * @copyright   Copyright (c) 2010 Mattijs Hoitink
 * @license     The MIT License - https://github.com/mattijs/beep/raw/master/LICENSE
 */

namespace beep;

/**
 * Main Application class for BeepBeep. The class handles request dispatching 
 * and view rendering if this is configured.
 */
class Application
{
    /**
     * Application configuration
     * @var array
     */
    protected $config = array();
    
    /**
     * Construct a new Beep Application
     * @param array $config
     */
    public function __construct(array $options = array())
    {
        $default = array(
            'views'   => getcwd() . DIRECTORY_SEPARATOR . 'views',
            'suffix'  => '.phtml',
        );
        $this->configure($options + $default);
    }
    
    /**
     * Configure the application, loading an array of options
     * @param array $options    The options to configure
     */
    public function configure(array $options)
    {
        $this->config += $options;
    }
    
    /**
     * Get a single or all configuration options. If the key is empty the entire
     * configuration array is returned. If the key is not empty but does not
     * exist the default is returned.
     * 
     * @param string $key    The configuration key
     * @param mixed $default The default value when the configuration key does
     *                       not exist
     * @return mixed         The entire configuration array, the value of the
     *                       requested configuration key, or the default
     *                       value when the requested key does not exist
     */
    public function config($key = null, $default = null)
    {
        if (empty($key)) {
            return $this->config;
        }
        
        // Check if the configuration key exists
        if (array_key_exists($key, $this->config)) {
            return $this->config[$key];
        }
        
        // Configuration key was not found, return the default value
        return $default;
    }
    
    /**
     * Dispatch the Application processing the Request and sending out the 
     * Response.
     * 
     * @param Request $request   The request to dispatch
     * @param Response $response The response to send to the client
     */
    public function run(Request $request = null, Response $response = null)
    {
        // Check for Request and Response classes
        $request  = $request  ?: new Request();
        $response = $response ?: new Response();
        
        // Try to match the route
        $route = Router::match($request);
        if (false === $route) {
            throw new \Exception('No route matched');
        }
        
        // Execute the mapped function and capture the output
        $function = $route->function;
        ob_start();
        $action = $function($request, $response);
        $output = ob_get_clean();
        
        // Add function output to Response
        $response->appendBody($output);
        
        // Send the response
        $response->send();
    }
    
    /**
     * Map a GET route with the Router
     * @param string $route
     * @param \Closure $function
     */
    public function get($route, \Closure $function)
    {
        Router::map($route, $function, array(Request::METHOD_GET));
    }
    
    /**
     * Map a POST route with the Router
     * @param string $route
     * @param \Closure $function
     */
    public function post($route, \Closure $function) 
    {
        Router::map($route, $function, array(Request::METHOD_POST));
    }
    
    /**
     * Map a PUT route with the Router
     * @param string $route
     * @param \Closure $function
     */
    public function put($route, \Closure $function) 
    {
        Router::map($route, $function, array(Request::METHOD_PUT));
    }
    
    /**
     * Map a DELETE route with the Router
     * @param string $route
     * @param \Closure $function
     */
    public function delete($route, \Closure $function) 
    {
        Router::map($route, $function, array(Request::METHOD_DELETE));
    }
}