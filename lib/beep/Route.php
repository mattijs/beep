<?php
/**
 * Route.php
 * 
 * @package     Beep
 * @author      Mattijs Hoitink <mattijs@monkeyandmachine.com>
 * @copyright   Copyright (c) 2010 Mattijs Hoitink
 * @license     The MIT License - https://github.com/mattijs/beep/raw/master/LICENSE
 */

namespace beep;

/**
 * Route container for beep. Contains route specification and builder for 
 * matching regular expression.
 */
class Route
{
    /**
     * Route pattern
     * @var string
     */
    public $pattern = '';
    
    /**
     * Route pattern converted to a matching regular expression
     * @var string
     */
    public $regexPattern = '';
    
    /**
     * Function to execute on a match
     * @var \Closure
     */
    public $function = null;
    
    /**
     * The allowed HTTP methods for the route
     * @var array
     */
    public $methods = array();
    
    /**
     * Parameter names and values for named parameters in the pattern
     * @var array
     */
    public $params = array();
    
    /**
     * Requirements for the named parameters in the pattern
     * @var array
     */
    public $requirements = array();
    
    /** **/
    
    /**
     * Construct a new Route
     * @param array $data Data for the Route
     */
    public function __construct(array $data = array())
    {
        foreach ($data as $key => $value) {
            $this->{$key} = $value;
        }
    }
    
    /**
     * Build the route parsing named parts and their requirements and building 
     * the regular expression to match the route.
     */
    public function build()
    {
        // Split the URL into parts
        $parts = preg_split('`[/]+`i', $this->pattern, -1, PREG_SPLIT_NO_EMPTY);
        
        $paramMap = array();
        foreach ($parts as $index => &$part) {
            if (1 === preg_match('/^\{(.*)\}$/i', $part, $matches)) {
                // Get the name of the part, an possibly it's requirements
                list($name, $requirements) = explode(':', array_pop($matches), 2) + array('', '');
                
                if (!empty($requirements)) {
                    $this->requirements[$name] = $requirements;
                }
                
                // Get and sanitize the pattern to replace the name part
                $pattern = isset($this->requirements[$name]) ? $this->requirements[$name] : '([^/]+)' ;
                $part = '(' . trim($pattern, '^()$') . ')';
                
                $this->params[$index] = $name;
            }
        }
        
        // Update the complete route pattern
        $this->regexPattern = '^/' . implode('/', $parts) . '(?:/?)$';
    }
}