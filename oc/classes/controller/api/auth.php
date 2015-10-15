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

                $res = $user->as_array();
                $res['user_token'] = $user->api_token();
                $res['image']      = $user->get_profile_image();

                //I do not want to return this fields...
                $hidden_fields =  array('password','token',
                                        'hybridauth_provider_uid','token_created','token_expires',
                                        'user_agent');

                //all fields
                $this->_return_fields = array_keys($res);

                //remove the hidden fields
                foreach ($this->_return_fields as $key => $value) 
                {
                    if(in_array($value,$hidden_fields))
                        unset($this->_return_fields[$key]);
                }

                $this->rest_output(array('user' => $res));
            }
        }
        else
            $this->_error(__('Wrong user name or password'),401);
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
                    
            //check we have this email in the DB
            $user = new Model_User();
            $user = $user->where('email', '=', $email)
                    ->limit(1)
                    ->find();
            
            if ($user->loaded())
            {
                $this->_error(__('User already exists'));
            }
            else
            {
                //creating the user
                $user = Model_User::create_email($this->_post_params['email'],$this->_post_params['name'],isset($this->_post_params['password'])?$this->_post_params['password']:NULL);

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

                //create the API token since he registered int he app
                $res = $user->as_array();
                $res['user_token'] = $user->api_token();
            
                $this->rest_output(array('user' => $res));
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

} // END