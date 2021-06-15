<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Extended functionality for Auth module
 *
 * @package    OC
 * @category   Auth
 * @author     Chema <chema@open-classifieds.com>
 * @copyright  (c) 2009-2013 Open Classifieds Team
 * @license    GPL v3
 */

class Auth_OC extends Kohana_Auth {

	protected $_encrypt;

	/**
	 * Loads Session and configuration options.
	 *
	 * @return  void
	 */
	public function __construct($config = array())
	{
		// Save the config in the object
		$this->_config = $config;

		$this->_session = Session::instance($this->_config['session_type']);

		// Initialize the Encryption helper
		$this->_encrypt = Encrypt::instance();
	}


	/**
	 * Gets the currently logged in user from the session (with auto_login check).
	 * Returns FALSE if no user is currently logged in.
	 *
	 * @return  mixed
	 */
	public function get_user($default = NULL)
	{
		$user = parent::get_user($default);

		if ( ! $user)
		{
			// check for "remembered" login
			$user = $this->auto_login();
		}

		return $user;
	}

	/**
	 * Checks if a session is active.
	 *
	 * @return  boolean
	 */
	public function logged_in($controller='home', $action = NULL, $directory = NULL)
	{

		// Get the user from the session
		$user = $this->get_user();

		if ( ! $user)
			return FALSE;


		if ($user instanceof Model_User AND $user->loaded() AND
			$user->has_access($controller, $action, $directory) )
		{
			return TRUE;
		}

		return FALSE;
	}


    /**
     * returns a user if email and password matches
     *
     * @param   string   email
     * @param   string   password
     * @param   boolean  enable autologin
     * @return  mixed / Model_User
     */
    public function email_login($email, $password)
    {
        // Load the user
        $user = new Model_User;
        $user->where('email', '=', $email)
            ->where('status','in',array(Model_User::STATUS_ACTIVE,Model_User::STATUS_SPAM))
            ->limit(1)
            ->find();

        // If the passwords match, perform a login
        if ($user->password === $this->hash($password))
        {
            return $user;
        }

        // Login failed
        return FALSE;
    }

	/**
	 * Logs a user in.
	 *
	 * @param   string   email
	 * @param   string   password
	 * @param   boolean  enable autologin
	 * @return  boolean
	 */
	protected function _login($email, $password, $remember)
	{
		// If the passwords match, perform a login
		if ( ($user = $this->email_login($email, $password)) !== FALSE)
		{
			// Complete the login with the found data
			$user->complete_login(($remember)?$this->_config['lifetime']:NULL);

			if ($remember === TRUE)
			{
				// Set the new token
				Cookie::set('authautologin', $user->token, $this->_config['lifetime']);
			}

			//writes the session
			$this->complete_login($user);

			return TRUE;
		}

		// Login failed
		return FALSE;
	}


	/**
	 * Logs a user in, based on the authautologin cookie, or seted param token
	 * @param string $token
	 * @return  mixed
	 */
	public function auto_login($token = NULL)
	{
		//in case token is not provided check the cookie, perfect for the QL
		if ($token === NULL )
			$token = Cookie::get('authautologin');

		if ($token!==NULL)
		{
			// Load the user from the token
			$user = new Model_User;
			$user ->where('token', '=', $token)
			->where('status','in', [Model_User::STATUS_ACTIVE, Model_User::STATUS_SPAM, Model_User::STATUS_UNVERIFIED])
			->where('token_expires','>',Date::unix2mysql())
			->limit(1)
			->find();

			if ($user->loaded())
			{
				// Complete the login with the found data, and new token
				$user->complete_login($this->_config['lifetime']);

				// Set the new token
				Cookie::set('authautologin', $user->token, $this->_config['lifetime']);

				//writes the session
				$this->complete_login($user);

				// Automatic login was successful
				return $user;
			}
		}

		return FALSE;
	}


    /**
     * Logs a user in, based on the authautologin cookie, or seted param token
     * @param string $token
     * @return  mixed
     */
    public function api_login($token = NULL)
    {
        if ($token!==NULL)
        {
            // Load the user from the token
            $user = new Model_User;
            $user ->where('api_token', '=', $token)
            ->where('status','in',array(Model_User::STATUS_ACTIVE,Model_User::STATUS_SPAM))
            ->limit(1)
            ->find();

            if ($user->loaded())
            {
                return $user;
            }
        }

        return FALSE;
    }


    /**
     * Logs a user in using social auth
     * @param string $token
     * @return  mixed
     */
    public function social_login($provider, $identifier)
    {
        // Load the user
        $user = new Model_User;
        $user ->where('hybridauth_provider_name', '=', $provider)
        ->where('hybridauth_provider_uid','=',$identifier)
        ->where('status','in',array(Model_User::STATUS_ACTIVE,Model_User::STATUS_SPAM))
        ->limit(1)
        ->find();

        if ($user->loaded())
        {
            // Complete the login with the found data, and new token
            $user->complete_login($this->_config['lifetime']);

            // Set the new token
            Cookie::set('authautologin', $user->token, $this->_config['lifetime']);

            //writes the session
            $this->complete_login($user);

            // social login was successful
            return $user;
        }


        return FALSE;
    }

    /**
     * Logs a user in using phone
     * @param string $token
     * @return  mixed
     */
    public function phone_login($phone, $code)
    {
        // Load the user
        $user = new Model_User;
        $user ->where('phone', '=', $phone)
        ->where('status','in',array(Model_User::STATUS_ACTIVE,Model_User::STATUS_SPAM))
        ->limit(1)
        ->find();

        if ($user->loaded() AND SMS::verify_auth_code(Session::instance()->get('sms_auth_code'), $code))
        {
            // Complete the login with the found data, and new token
            $user->complete_login($this->_config['lifetime']);

            // Set the new token
            Cookie::set('authautologin', $user->token, $this->_config['lifetime']);

            //set cookie
            Cookie::set('sms_auth' , $user->id_user, $this->_config['lifetime']);

            //writes the session
            $this->complete_login($user);

            // social login was successful
            return $user;
        }


        return FALSE;
    }


	/**
	 * Log a user out and remove any autologin cookies.
	 *
	 * @param   boolean  completely destroy the session
	 * @param	boolean  remove all token for user
	 * @return  boolean
	 */
	public function logout($destroy = FALSE, $logout_all = FALSE)
	{
		// Set by force_login()
		$this->_session->delete('auth_forced');
        Cookie::delete('google_authenticator');
        Cookie::delete('sms_auth');

		if ($token = Cookie::get('authautologin'))
		{
			// Delete the autologin cookie to prevent re-login
			Cookie::delete('authautologin');

            if ($logout_all)
            {
                // Load the user from the token
                $user = new Model_User;
                $user ->where('token', '=', $token)->limit(1)->find();

                // generates new autologin token from the database
                if ($user->loaded())
                    $user->create_token();
            }

		}

		return parent::logout($destroy);
	}

	/**
	 * Get the stored password for a username.
	 *
	 * @param   mixed   username string, or user ORM object
	 * @return  string
	 */
	public function password($user)
	{
		//not an object trying to load it
		if ( ! is_object($user))
		{
			$email = $user;

			// Load the user
			$user = new Model_User;
			$user->where('email', '=', $email)->limit(1)->find();
		}

		if ($user->loaded())
		{
			return $user->password;
		}

		return FALSE;

	}

	/**
	 * Compare password with original (hashed). Works for current (logged in) user
	 *
	 * @param   string  $password
	 * @return  boolean
	 */
	public function check_password($password)
	{
		$user = $this->get_user();

		if ( ! $user)
		return FALSE;

		return ($this->hash($password) === $user->password);
	}


	/**
	 * Encodes the data received, creating a encoded quicklogin string
	 * @param string $token user token
	 * @param integer $lifetime
	 * @param string $url
	 */
	public function ql_encode($token = NULL , $url = NULL, $expires = NULL)
	{
		//using default value
		if ($expires === NULL)
		{
			$expires = time() + $this->_config['ql_lifetime'];
		}

		//URL and token is mandatory
		if ($url === NULL OR $token===NULL)
		{
			return FALSE;
		}

		// Generate a string from the pieces
		$out = implode($this->_config['ql_separator'], array($token, $expires, $url));
		$out = $this->_encrypt->encode($out);
		$out = Base64::fix_to_url($out);
		return $out;
	}

	/**
	 * Decodes the quicklogin string, and returns the encripted data in plain
	 * @param   string  $ql  Prepared quicklogin string
	 * @return  array   original unencrypted data, in array. [0]=>token, [1]=>expires [, [2]=>url ]
	 */
	public function ql_decode($ql)
	{
		$out = $ql;
		$out = Base64::fix_from_url($out);
		$out = $this->_encrypt->decode($out);
		$out = explode($this->_config['ql_separator'], $out, 3);

		return $out;
	}

	/**
	 * Logs a user in using the quicklogin string.
	 * @param string $quicklogin
	 * @return  mixed. Boolean if login was OK, or string with URL if it has one
	 */
	public function ql_login($quicklogin=NULL)
	{
		if ($quicklogin===NULL)
		return FALSE;

		$data = $this->ql_decode($quicklogin);

		//not a real QL
		if (core::count($data) != 3)
		{
			return FALSE;
		}

		// Prepare decoded data
		$token   = $data[0];
		$expires = (int) $data[1];
		$url     = trim($data[2]);

		//if the QL is not expired we try to login the user
		if  ($expires >= time())
		{
			//if user loged in no new token
            if (Auth::instance()->logged_in())
            {
                //he was already loged in...
                return $url;
            }
            else if ($this->auto_login($token)!==FALSE)
            {
                //reset failed attempts if he used a correct QL.
                $user = Auth::instance()->get_user();
                if ($user->failed_attempts > 0)
                {
                    $user->failed_attempts  = 0;
                    $user->last_failed      = NULL;
                    try
                    {
                        // Save the user
                        $user->update();
                    }
                    catch (ORM_Validation_Exception $e)
                    {
                        throw HTTP_Exception::factory(500,$e->errors(''));
                    }
                    catch(Exception $e)
                    {
                        throw HTTP_Exception::factory(500,$e->getMessage());
                    }
                }

                return $url;//loged in!!!
            }
		}

		return FALSE;
	}

	/**
     *
     * Redirects the user to the home or to the admin, used in the controller for login
     */
    public function login_redirect()
    {
        $redirect = Core::request('auth_redirect');

        if ($redirect === NULL OR strpos($redirect,Route::url('default'))!=0 )
            $redirect = Route::url('oc-panel');


        HTTP::redirect($redirect);
    }



}
