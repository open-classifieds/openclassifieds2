<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Action Hooks 
 *  
 * Hooks are global to the application 
 *  
 * Adding action to a hoook: 
 * Hook::add_action('unique_name_hook','some_class::hook_test'); 
 * OR shortcut: 
 * add_action('unique_name_hook','other_class::hello'); 
 * add_action('unique_name_hook','some_public_function'); 
 *   
 * Performing all the actions for the hook 
 * do_action('unique_name_hook');//you can use too Hook::do_action(); 
 * @package    OC/Plugin
 * @category   Helper
 * @author     Chema <chema@open-classifieds.com>
 * @copyright  (c) 2009-2013 Open Classifieds Team
 * @license    GPL v3
 */

class Hook 
{ 
    //action hooks array    
    public static $actions = array(); 
  
    /** 
     * ads a function to an action hook 
     * @param $hook 
     * @param $function 
     */ 
	public static function add_action($hooks, $function) 
	{
		// Blackout - 1:45 PM 5/04/2019
		// Modified to support an Array of hooks for 1 function
		
		foreach ((array)$hooks AS $hook)
		{
			$hook=mb_strtolower($hook); 
			// create an array of function handlers if it doesn't already exist 
			if(!self::exists_action($hook)) 
			{ 
				self::$actions[$hook] = array();  
			}

			// append the current function to the list of function handlers 
			if (is_callable($function)) 
			{ 
				self::$actions[$hook][] = $function; 
			}
		}
	} 
  
    /** 
     * executes the functions for the given hook 
     * @param string $hook 
     * @param array $params 
     * @return boolean true if a hook was setted 
     */ 
	public static function do_action($hook,$params=NULL) 
	{
		// Blackout - 1:45 PM 5/04/2019
		// Modified to pull Hooks from get_action() for wildcard support
	
		$hook = mb_strtolower($hook);
		$actions = Hook::get_action($hook);
		// call each function handler associated with this hook 
		foreach ($actions as $function)
		{ 
			if (is_array($params)) 
			{ 
				call_user_func_array($function, $params); 
			} 
			else  
			{ 
				call_user_func($function); 
			} 
			//cant return anything since we are in a loop! dude! 
		}
	}
  
    /** 
     * gets the functions for the given hook 
     * @param string $hook 
     * @return mixed  
     */ 
    public static function get_action($hook) 
    {
        // Blackout - 1:45 PM 5/04/2019
        // Modified to allow *, *_before and *_after support - these will run on any controller
	
        // Variables
        $hook = mb_strtolower($hook);
        
        // Determine which wildcard action to look for
        $wildcard = (preg_match('/(_after|_before)$/', $hook, $m) ? '*' . $m[1] : '*');
        $wildcard_actions = (isset(self::$actions[$wildcard]) ? self::$actions[$wildcard] : []);
        
        // Return Hooks
        return (isset($wildcard_actions) && isset(Hook::$actions[$hook]) ? array_merge($wildcard_actions, Hook::$actions[$hook]) : (isset(Hook::$actions[$hook]) ? Hook::$actions[$hook] : (isset($wildcard_actions) ? $wildcard_actions : [])));
    } 
  
    /** 
     * check exists the functions for the given hook 
     * @param string $hook 
     * @return boolean  
     */ 
    public static function exists_action($hook) 
    { 
        $hook=mb_strtolower($hook); 
        return (isset(self::$actions[$hook]))? TRUE:FALSE; 
    } 

}//end Class 
  
  
    /** 
     * Hooks Shortcuts not in class 
     */ 
    function add_action($hook,$function) 
    { 
        return Hook::add_action($hook,$function); 
    } 
  
    function do_action($hook) 
    { 
        return Hook::do_action($hook); 
    } 
