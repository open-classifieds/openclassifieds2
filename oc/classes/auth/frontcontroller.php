<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Front end controller for OC user/admin auth in the app
 *
 * @package    OC
 * @category   Controller
 * @author     Chema <chema@open-classifieds.com>
 * @copyright  (c) 2009-2013 Open Classifieds Team
 * @license    GPL v3
 */

class Auth_Frontcontroller extends Controller
{

    /**
    *
    * Contruct that checks you are loged in before nothing else happens!
    */
    function __construct(Request $request, Response $response)
    {
        //the user was loged in and with the right permissions
        parent::__construct($request,$response);
    
        // Assign the request to the controller
        $this->request = $request;
    
        // Assign a response to the controller
        $this->response = $response;
    
        //login control, don't do it for auth controller so we dont loop
        if ($this->request->controller()!='auth')
        {
            $url_bread = Route::url('oc-panel',array('controller'  => 'profile', 'action'  => 'public'));
            Breadcrumbs::add(Breadcrumb::factory()->set_title(__('My Profile'))->set_url($url_bread));
            
            //check if user is login
            if (!Auth::instance()->logged_in( $request->controller(), $request->action(), $request->directory()))
            {
                Alert::set(Alert::ERROR, __('You do not have permissions to access '.$request->controller().' '.$request->action()));
                $url = Route::url('oc-panel',array(   'controller' => 'auth',
                                                    'action'     => 'login'));
                $this->redirect($url);
            }
        }

    }
}