<?php
/**
 * Router.php
 * 
 * @package     Beep
 * @author      Mattijs Hoitink <mattijs@monkeyandmachine.com>
 * @copyright   Copyright (c) 2010 Mattijs Hoitink
 * @license     The MIT License - https://github.com/mattijs/beep/raw/master/LICENSE
 */

namespace beep;

/**
 * Beep router class
 * @uses Route
 */
class Router
{
    /**
     * List of defined routes
     * @var array[Route]
     */
    public static $routes = array();
    
    /** **/
    
    /**
     * Map a route pattern
     * @param string $pattern
     * @param Closure $function
     * @param array $methods
     */
    public static function map($pattern, \Closure $function, array $methods = array())
    {
        // Check defined methods
        if (empty($methods)) {
            $methods = array(
                Requrest::METHOD_GET,
                Requrest::METHOD_POST,
                Requrest::METHOD_PUT,
                Requrest::METHOD_DELETE
            );
        }
        
        // Construct the route
        $route = new Route(array(
            'pattern'      => trim($pattern),
            'function'     => $function,
            'methods'      => $methods,
            'requirements' => array(),
            'params'       => array(),
        ));
        
        // Build the route
        $route->build();
        
        // Add it to the list
        static::$routes[] = $route;
    }
    
    /**
     * Match a request to a route
     * @param Request $request The Request to match
     * @return array|boolean   The matched router, or FALSE when no match was found
     */
    public static function match(Request $request)
    {
        foreach (static::$routes as $route) {
            // Check if request method matches first
            if (!in_array($request->method, $route->methods)) {
                continue;
            }
            
            // Check if route pattern matches request path
            if (1 === preg_match("`{$route->regexPattern}`i", $request->path, $paramMatches)) {
                
                // Get the matched URL
                $url = array_shift($paramMatches);
                
                // Shift first item, it's always empty because of leading /
                $urlParts = preg_split('`[/]+`i', $url, -1, PREG_SPLIT_NO_EMPTY);
                ksort($urlParts);
                
                $params = $route->params;
                ksort($params);
                
                if (!empty($params)) {
                    // Always add parameters by their numeric key
                    $route->params += array_intersect_key($urlParts, $params);
                    
                    // If the parameter map contains names for the matches, 
                    // add the matches by their names as well
                    $route->params += array_combine($params, array_intersect_key($urlParts, $params));
                }
                
                return $route;
            }
        }
        
        return false;
    }
    
    /**
     * Clear routes registered with the Router
     */
    public static function clear()
    {
        static::$routes = array();
    }
}