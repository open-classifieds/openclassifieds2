<?php defined('SYSPATH') or die('No direct script access.');


class Controller_Api_Auth extends Api_Auth {


    /**
     * Handle GET requests.
     */
    public function action_login()
    {
        // If the passwords match, perform a login
        if ( ($user = Auth::instance()->email_login(Core::request('email'), Core::request('password'))) !== FALSE)
        {
            if ($user->loaded())
            {
                //save device id only if its different
                if (Core::request('device_id')!==NULL AND $user->device_id!=Core::request('device_id'))
                {
                    $user->device_id = Core::request('device_id');
                    try
                    {
                        $user->save();
                    }
                    catch (Kohana_HTTP_Exception $khe)
                    {}
                }

                $this->rest_output( array('user' => self::get_user_array($user)) );
            }
        }
        else
            $this->_error(__('Wrong user name or password'),401);
    }

    public function action_social()
    {
        $user = FALSE;

        $provider_name = Core::request('social_network');
        $identifier    = Core::request('token');
        $email         = Core::request('email');
        $name          = Core::request('name');

        $user = Auth::instance()->social_login($provider_name, $identifier);

        //not found in database
        if ($user == FALSE)
        {
            //register the user in DB
            Model_User::create_social($email,$name,$provider_name,$identifier);
            //log him in
            $user = Auth::instance()->social_login($provider_name, $identifier);
        }


        if ( $user!== FALSE AND $user->loaded() )
        {
            //save device id only if its different
            if (Core::request('device_id')!==NULL AND $user->device_id!=Core::request('device_id'))
            {
                $user->device_id = Core::request('device_id');
                try
                {
                    $user->save();
                }
                catch (Kohana_HTTP_Exception $khe)
                {}
            }

            $this->rest_output( array('user' => self::get_user_array($user)) );
        }

    }

    public function action_index()
    {
        $this->action_login();
    }


    public function action_create()
    {
        $validation =   Validation::factory($this->request->post())
                            ->rule('name', 'not_empty')
                            ->rule('email', 'not_empty')
                            ->rule('email', 'email');

        if ($validation->check())
        {
            $email = $this->_post_params['email'];

            if (Model_User::find_by_email($email)->loaded())
            {
                $this->_error(__('User already exists'));
            }
            else
            {
                //creating the user
                try
                {
                    $user = Model_User::create_email($this->_post_params['email'],$this->_post_params['name'],isset($this->_post_params['password'])?$this->_post_params['password']:NULL);
                }
                catch (ORM_Validation_Exception $e)
                {
                    $errors = '';

                    foreach ($e->errors('models') as $error)
                        $errors .= $error.' - ';

                    $this->_error($errors);

                    return;
                }

                //add custom fields
                $save_cf = FALSE;
                foreach ($this->_post_params as $custom_field => $value)
                {
                    if (strpos($custom_field,'cf_')!==FALSE)
                    {
                        $user->$custom_field = $value;
                        $save_cf = TRUE;
                    }
                }
                //saves the user only if there was CF
                if($save_cf === TRUE)
                    $user->save();
                
                $user->reload();
                $this->rest_output( array('user' => self::get_user_array($user)) );
            }

        }
        else
        {
            $errors = '';
            $e = $validation->errors('auth');

            foreach ($e as $error)
                $errors.=$error.' - ';

            $this->_error($errors);
        }

    }


    public static function get_user_array($user)
    {
        $res = $user->as_array();
        $res['user_token'] = $user->api_token();
        $res['image']      = $user->get_profile_image();

        //I do not want to return this fields...
        $hidden_fields =  array('password','token',
                                'hybridauth_provider_uid','token_created','token_expires',
                                'user_agent');

        //remove the hidden fields
        foreach ($res as $key => $value)
        {
            if(in_array($key,$hidden_fields))
                unset($res[$key]);
        }

        return $res;
    }

} // END
