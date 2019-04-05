<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Abstract plugin controller class. 
 * It adds hooks to the construct, before and after methods.
 *
 * example to execute functions instead of the current action:
 * 
 * To go along before/after the action is called:
 * 
 *
 * @package    OC/Plugin
 * @category   Controller
 * @author     Chema <chema@open-classifieds.com>
 * @copyright  (c) 2009-2013 Open Classifieds Team
 * @license    GPL v3
 */

// Blackout - 11:47 AM 5/04/2019
// * Updated to reflect new changes to the original Kohana_Controller i.e. execute() now exists
// * Changed Hook::do_action() calls to now pass $this instead of $this->request->param() - now we can actually modify the controllers content from a hook!

abstract class Kohana_Controller {

	/**
	 * @var  Request  Request that created the controller
	 */
	public $request;

	/**
	 * @var  Response The response that will be returned from controller
	 */
	public $response;
	
	/**
	 * @var Hook name
	 */
	protected $hook;

	/**
	 * Creates a new controller instance. Each controller must be constructed
	 * with the request object that created it.
	 *
	 * @param   Request   $request  Request that created the controller
	 * @param   Response  $response The request's response
	 * @return  void
	 */
	public function __construct(Request $request, Response $response)
	{
		// Assign the request to the controller
		$this->request = $request;

		// Assign a response to the controller
		$this->response = $response;
		
		// Hook
		$this->hook = $this->request->controller().'_'.$this->request->action();
		Hook::do_action($this->hook, [$this]);
	}

	/**
	 * Executes the given action and calls the [Controller::before] and [Controller::after] methods.
	 *
	 * Can also be used to catch exceptions from actions in a single place.
	 *
	 * 1. Before the controller action is called, the [Controller::before] method
	 * will be called.
	 * 2. Next the controller action will be called.
	 * 3. After the controller action is called, the [Controller::after] method
	 * will be called.
	 *
	 * @throws  HTTP_Exception_404
	 * @return  Response
	 */
	public function execute()
	{
		// Execute the "before action" method
		$this->before();

		// Determine the action to use
		$action = 'action_'.$this->request->action();

		// If the action doesn't exist, it's a 404
		if ( ! method_exists($this, $action))
		{
			throw HTTP_Exception::factory(404,
				'The requested URL :uri was not found on this server.',
				[':uri' => $this->request->uri()]
			)->request($this->request);
		}

		// Execute the action itself
		$this->{$action}();

		// Execute the "after action" method
		$this->after();

		// Return the response
		return $this->response;
	}

	/**
	 * Automatically executed before the controller action. Can be used to set
	 * class properties, do authorization checks, and execute other custom code.
	 *
	 * @return  void
	 */
	public function before()
	{
		Hook::do_action($this->hook.'_before', [$this]);
	}

	/**
	 * Automatically executed after the controller action. Can be used to apply
	 * transformation to the response, add extra output, and execute
	 * other custom code.
	 *
	 * @return  void
	 */
	public function after()
	{
		Hook::do_action($this->hook.'_after', [$this]);
	}

	/**
	 * Issues a HTTP redirect.
	 *
	 * Proxies to the [HTTP::redirect] method.
	 *
	 * @param  string  $uri   URI to redirect to
	 * @param  int     $code  HTTP Status code to use for the redirect
	 * @throws HTTP_Exception
	 */
	public static function redirect($uri = '', $code = 302)
	{
		return HTTP::redirect( (string) $uri, $code);
	}

	/**
	 * Checks the browser cache to see the response needs to be returned,
	 * execution will halt and a 304 Not Modified will be sent if the
	 * browser cache is up to date.
	 *
	 *     $this->check_cache(sha1($content));
	 *
	 * @param  string  $etag  Resource Etag
	 * @return Response
	 */
	protected function check_cache($etag = NULL)
	{
		return HTTP::check_cache($this->request, $this->response, $etag);
	}

}
