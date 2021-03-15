<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Social extends Controller {

    public function action_oauth()
    {
         //if user loged in redirect home
        if (Auth::instance()->logged_in())
            Auth::instance()->login_redirect();

        if (core::config('social.oauth2_enabled')==FALSE AND Core::extra_features() == TRUE)
            $this->redirect(Route::url('default'));

        require_once Kohana::find_file('vendor', 'oauth2/vendor/autoload','php');

        $provider = new \League\OAuth2\Client\Provider\GenericProvider([
            'clientId'                => Core::config('social.oauth2_client_id'),    // The client ID assigned to you by the provider
            'clientSecret'            => Core::config('social.oauth2_client_secret'),   // The client password assigned to you by the provider
            'redirectUri'             =>  Route::url('default',array('controller'=>'social','action'=>'oauth','id'=>1)),
            'urlAuthorize'            => Core::config('social.oauth2_url_authorize'),
            'urlAccessToken'          => Core::config('social.oauth2_url_access_token'),
            'urlResourceOwnerDetails' => Core::config('social.oauth2_url_resource_owner_details'),
        ]);

        $provider_name = 'oauth2';

        // If we don't have an authorization code then get one
        if (!isset($_GET['code'])) {

            // Fetch the authorization URL from the provider; this returns the
            // urlAuthorize option and generates and applies any necessary parameters
            // (e.g. state).
            $authorizationUrl = $provider->getAuthorizationUrl();

            // Get the state generated for you and store it to the session.
            $_SESSION['oauth2state'] = $provider->getState();

            // Redirect the user to the authorization URL.
            $this->redirect($authorizationUrl);

        // Check given state against previously stored one to mitigate CSRF attack
        }
        elseif ( empty($_GET['state']) OR (isset($_SESSION['oauth2state']) AND $_GET['state'] !== $_SESSION['oauth2state'])  )
        {
            unset($_SESSION['oauth2state']);
            $this->redirect(Route::url('default'));

        }
        else
        {

            try {

                // Try to get an access token using the authorization code grant.
                $accessToken = $provider->getAccessToken('authorization_code', [
                    'code' => $_GET['code']
                ]);

                // We have an access token, which we may use in authenticated
                // requests against the service provider's API.
                /*echo $accessToken->getToken() . "\n";
                echo $accessToken->getRefreshToken() . "\n";
                echo $accessToken->getExpires() . "\n";
                echo ($accessToken->hasExpired() ? 'expired' : 'not expired') . "\n";*/

                // Using the access token, we may look up details about the
                // resource owner.
                $resourceOwner = $provider->getResourceOwner($accessToken);
                $user_profile  = $resourceOwner->toArray();

                //getting unique identifier, different options...
                $user_id = NULL;
                if (isset($user_profile['sub']))
                    $user_id = $user_profile['sub'];
                elseif (isset($user_profile['id']))
                    $user_id = $user_profile['id'];
                elseif (isset($user_profile['preferred_username']))
                    $user_id = $user_profile['preferred_username'];
                elseif (isset($user_profile['username']))
                    $user_id = $user_profile['username'];
                elseif (isset($user_profile['email']))
                    $user_id = $user_profile['email'];

                if ($user_id === NULL)
                {
                    Alert::set(Alert::ERROR,  __('Error: please try again!'));
                    $this->redirect(Route::url('default'));
                }

                $user_email = (isset($user_profile['email']))?$user_profile['email']:NULL;

                $user_name = NULL;
                if (isset($user_profile['name']))
                    $user_name = $user_profile['name'];
                elseif (isset($user_profile['preferred_username']))
                    $user_name = $user_profile['preferred_username'];
                elseif (isset($user_profile['username']))
                    $user_name = $user_profile['username'];

                //try to login the user with same provider and identifier
                $user = Auth::instance()->social_login($provider_name, $user_id);

                //we couldnt login create account
                if ($user == FALSE)
                {
                    //if not email provided
                    if (!Valid::email($user_email,TRUE))
                    {
                        Alert::set(Alert::INFO, __('We need your email address to complete'));
                        //redirect him to select the email to register
                        $this->redirect(Route::url('default',array('controller'=>'social',
                                                                            'action'=>'register',
                                                                            'id'    =>$provider_name)).'?uid='.$user_id.'&name='.$user_name);
                    }
                    else
                    {
                        //register the user in DB
                        Model_User::create_social($user_email,$user_name,$provider_name,$user_id);
                        //log him in
                        Auth::instance()->social_login($provider_name, $user_profile->identifier);
                    }
                }
                else
                    Alert::set(Alert::SUCCESS, __('Welcome!'));

                $this->redirect(Session::instance()->get_once('auth_redirect',Route::url('default')));


            } catch (\League\OAuth2\Client\Provider\Exception\IdentityProviderException $e) {

                // Failed to get the access token or user details.
                Alert::set(Alert::ERROR, $e->getMessage());
                $this->redirect(Route::url('default'));

            }

        }
    }

	public function action_login()
	{
         //if user loged in redirect home
        if (Auth::instance()->logged_in())
            Auth::instance()->login_redirect();

		Social::include_vendor();
		$user = FALSE;
		$config = Social::get();

		if ($this->request->query('hauth_start') OR $this->request->query('hauth_done'))
		{
			try
			{
				Hybrid_Endpoint::process($this->request->query());
			}
			catch (Exception $e)
			{
				Alert::set(Alert::ERROR, $e->getMessage());
				$this->redirect(Route::url('default'));
			}

		}
		else
		{
			$provider_name = $this->request->param('id');

			try
			{
				// initialize Hybrid_Auth with a given file
				$hybridauth = new Hybrid_Auth( $config );

				// try to authenticate with the selected provider
                if ($provider_name == 'openid')
                    $params = array( 'openid_identifier' => 'https://openid.stackexchange.com/');
                else
                    $params = NULL;

				$adapter = $hybridauth->authenticate( $provider_name , $params);


				if ($hybridauth->isConnectedWith($provider_name))
				{
					//var_dump($adapter->getUserProfile());
                    $user_profile = $adapter->getUserProfile();
				}
			}
			catch( Exception $e )
			{
				Alert::set(Alert::ERROR, __('Error: please try again!')." ".$e->getMessage());
                $this->redirect(Route::url('default'));
			}

            //try to login the user with same provider and identifier
            $user = Auth::instance()->social_login($provider_name, $user_profile->identifier);

            //we couldnt login create account
            if ($user == FALSE)
            {
                $email = ($user_profile->emailVerified!=NULL)? $user_profile->emailVerified: $user_profile->email;
                $name  = ($user_profile->firstName!=NULL)? $user_profile->firstName.' '.$user_profile->lastName: $user_profile->displayName;
                //if not email provided
                if (!Valid::email($email,TRUE))
                {
                    Alert::set(Alert::INFO, __('We need your email address to complete'));
                    //redirect him to select the email to register
                    $this->redirect(Route::url('default',array('controller'=>'social',
                                                                        'action'=>'register',
                                                                        'id'    =>$provider_name)).'?uid='.$user_profile->identifier.'&name='.$name);
                }
                else
                {
                    //register the user in DB
                    Model_User::create_social($email,$name,$provider_name,$user_profile->identifier);

                    if (Core::config('general.users_must_verify_email'))
                    {
                        Alert::set(Alert::SUCCESS, __('Please confirm your email address, a confirmation email was sent to your registration email address.'));

                        $this->redirect(Route::url('oc-panel', ['directory' => 'user', 'controller' => 'auth', 'action' => 'login']));
                    }

                    //log him in
                    Auth::instance()->social_login($provider_name, $user_profile->identifier);
                }
            }
            else
                Alert::set(Alert::SUCCESS, __('Welcome!'));

            $this->redirect(Session::instance()->get_once('auth_redirect',Route::url('default')));

		}
	}

    /**
     * simple registration without password
     * @return [type] [description]
     */
    public function action_register()
    {
        $provider_name = $this->request->param('id');

        if(!isset($provider))
            $provider = $provider_name;

        if(!isset($uid))
            $uid = core::get('uid');

        $this->template->content = View::factory('pages/auth/register-social', ['form_action'=>Route::url('default',array('controller'=>'social','action'=>'register','id'=>$provider)).'?uid='.$uid]);

        if (core::post('email') AND CSRF::valid('register_social'))
        {
            $email = core::post('email');

            if (Valid::email($email,TRUE))
            {
                //register the user in DB
                Model_User::create_social($email,core::post('name'),$provider_name,core::get('uid'));
                //log him in
                Auth::instance()->social_login($provider_name,core::get('uid'));

                Alert::set(Alert::SUCCESS, __('Welcome!'));

                //change the redirect
                $this->redirect(Route::url('default'));
            }
            else
            {
                Form::set_errors(array(__('Invalid Email')));
            }

        }

        //template header
        $this->template->title            = __('Register new user');

    }
}
