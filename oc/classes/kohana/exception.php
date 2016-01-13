<?php defined('SYSPATH') OR die('No direct script access.');
/**
 * Kohana exception class. Translates exceptions using the [I18n] class.
 *
 * @package    Kohana
 * @category   Exceptions
 * @author     Kohana Team
 * @copyright  (c) 2008-2012 Kohana Team
 * @license    http://kohanaframework.org/license
 */
class Kohana_Exception extends Kohana_Kohana_Exception {


    /**
     * Creates a new translated exception.
     *
     *     throw new Kohana_Exception('Something went terrible wrong, :user',
     *         array(':user' => $user));
     *
     * @param   string          $message    error message
     * @param   array           $variables  translation variables
     * @param   integer|string  $code       the exception code
     * @param   Exception       $previous   Previous exception
     * @return  void
     */
    public function __construct($message = "", array $variables = NULL, $code = 0, Exception $previous = NULL)
    {
        
        //when exceptions where thrown we where getting a ErrorException [ Fatal Error ]: Call to undefined function __()
        //since i18n was not loaded yet. nasty but works...
        if (!function_exists('__'))
        {
            function __($message,$variables = NULL)
            {
                return is_array($variables) ? strtr($message, $variables):$message;
            }
        }

        parent::__construct($message , $variables , $code , $previous );
    }

    /**
     * Get a Response object representing the exception
     *
     * @uses    Kohana_Exception::text
     * @param   Exception  $e
     * @return  Response
     */
    public static function response($e)
    {

        if (Kohana::$environment === Kohana::DEVELOPMENT)
        {
            // Show the normal Kohana error page.
            return parent::response($e);
        }
        else
        {
            // Lets log the Exception, Just in case it's important!
            Kohana::$log->add(Log::ERROR, parent::text($e));

            // Generate a nicer looking "Oops" page.
            $view = View::factory('pages/error/default', array('message'=>$e->getMessage()) );
 
            $response = Response::factory()
                ->status(500)
                ->body($view->render());
 
            return $response;
        }
        
    }

}
