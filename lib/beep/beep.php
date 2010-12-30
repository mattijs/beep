<?php
/**
 * beep.php
 *
 * @package     Beep
 * @author      Mattijs Hoitink <mattijs@monkeyandmachine.com>
 * @copyright   Copyright (c) 2010 Mattijs Hoitink
 * @license     The MIT License - https://github.com/mattijs/beep/raw/master/LICENSE
 */

namespace beep;

// BeepBeep requirements
require_once __DIR__ . DIRECTORY_SEPARATOR . 'Application.php';
require_once __DIR__ . DIRECTORY_SEPARATOR . 'Request.php';
require_once __DIR__ . DIRECTORY_SEPARATOR . 'Response.php';
require_once __DIR__ . DIRECTORY_SEPARATOR . 'Route.php';
require_once __DIR__ . DIRECTORY_SEPARATOR . 'Router.php';

/**
 * Beep is the base class for configuring and running the Beep Application. The 
 * use of this class is optional.
 */
class beep
{
    /**
     * Beep version
     */
    const VERSION = '0.1';
    
    /**
     * Reference to the Beep application
     * @var Application
     */
    protected static $app = null;
    
    /** **/
    
    /**
     * Initialize Beep.
     * @param array $options    Options to set in the Beep Application
     */
    public static function init(array $options = array())
    {
        $app = static::app();
        $app->configure($options);
    }
    
    /**
     * Get the Beep Application
     * @return Application
     */
    public static function app()
    {
        if (null === static::$app) {
            static::$app = new Application();
        }
        
        return static::$app;
    }
    
    public static function get($route, $callback)
    {
        static::app()->get($route, $callback);
    }
    
    public static function post($route, $callback)
    {
        static::app()->post($route, $callback);
    }
    
    public static function put($route, $callback)
    {
        static::app()->put($route, $callback);
    }
    
    public static function delete($route, $callback)
    {
        static::app()->delete($route, $callback);
    }
    
    public static function render($template)
    {
        static::app()->render(array(
            'view'   => $template, 
            'layout' => $layout,
        ));
    }
    
    /**
     * Beep Beep! ;P Run the Beep Application
     */
    public static function beep()
    {
        static::app()->run();
    }
}