<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Panel_Auth extends Controller {
    
    /**
     * 
     * Check if we need to login the user or display the form, same form for normal user and admin
     */
    public function action_login()
    {       
        $this->template->meta_description = __('Login to').' '.core::config('general.site_name');
        
        //if user loged in redirect home
        if (Auth::instance()->logged_in())
        {
            $this->redirect(Route::url('oc-panel'));
        }
        //private site only allows post to login
        elseif(!$_POST AND core::config('general.private_site')==1)
        {
            $this->redirect(Route::url('default'));
        }
        //posting data so try to login
        elseif ($this->request->post() AND CSRF::valid('login') AND Valid::email(core::post('email')))
        {
            $blocked_login = FALSE;
            
            // Load the user
            $user = new Model_User;
            $user   ->where('email', '=', core::post('email'))
                    ->where('status', 'in', array(Model_User::STATUS_ACTIVE,Model_User::STATUS_SPAM))
                    ->limit(1)
                    ->find();
            
            // Check if we must block this login attempt.
            if ($user->loaded() AND $user->failed_attempts > 2) {
                // failed 2 or 3 attempts, wait 1 minute until next attempt
                if ($user->failed_attempts < 5 AND $user->last_failed > Date::unix2mysql(strtotime('-1 minute')))
                {
                    $blocked_login = TRUE;
                    Alert::set(Alert::ERROR, __('Login has been temporarily disabled due to too many unsuccessful login attempts. Please try again in a minute.'));
                }
                // failed more than 4 attempts, wait 24 hours until next attempt
                elseif ($user->failed_attempts > 4 AND $user->last_failed > Date::unix2mysql(strtotime('-24 hours')))
                {
                    $blocked_login = TRUE;
                    Alert::set(Alert::ERROR, __('Login has been temporarily disabled due to too many unsuccessful login attempts. Please try again in 24 hours.'));                    
                }
            }
            
            //not blocked so try to login
            if (! $blocked_login)
            {
                Auth::instance()->login(core::post('email'), 
                                        core::post('password'),
                                        (bool) core::post('remember'));
                
                //redirect index
                if (Auth::instance()->logged_in())
                {
                    if ($user->loaded())
                    {
                        $user->failed_attempts = 0;
                        
                        try 
                        {
                            // Save the user
                            $user->update();
                        }
                        catch (ORM_Validation_Exception $e)
                        {
                            Form::set_errors($e->errors(''));
                        }
                        catch(Exception $e)
                        {
                            throw HTTP_Exception::factory(500,$e->getMessage());
                        }
                    }                    
                    
                    //is an admin so redirect to the admin home
                    Auth::instance()->login_redirect();
                }
                else 
                {
                    Form::set_errors(array( __('Wrong email or password').'. '
                                            .'<a class="alert-link" href="'.Route::url('oc-panel',array(   'directory'=>'user',
                                                                                        'controller'=>'auth',
                                                                                        'action'=>'forgot'))
                                            .'">'.__('Have you forgotten your password?').'</a>'));
                    if ($user->loaded())
                    {
                        // fifth failed attempt, invalidate token?
                        if ($user->failed_attempts == 4) {
                            $user->token            = NULL;                            
                            $user->user_agent       = NULL;                            
                            $user->token_created    = NULL;                            
                            $user->token_expires    = NULL;                            
                        }
                        
                        $user->failed_attempts = new Database_Expression('failed_attempts + 1');
                        $user->last_failed = Date::unix2mysql(time());
                        
                        try 
                        {
                            // Save the user
                            $user->update();
                        }
                        catch (ORM_Validation_Exception $e)
                        {
                            Form::set_errors($e->errors(''));
                        }
                        catch(Exception $e)
                        {
                            throw HTTP_Exception::factory(500,$e->getMessage());
                        }
                    }
                }
            }
        }
        
        //private site
        if (!Auth::instance()->logged_in() AND core::config('general.private_site')==1)
        {
            $this->redirect(Route::url('default'));
        }

        //Login page
        $this->template->title            = __('Login');        
        $this->template->content = View::factory('pages/auth/login');
    }
    
    /**
     * 
     * Logout user session
     */
    public function action_logout()
    {
        Auth::instance()->logout(TRUE);    

        if(Valid::URL($this->request->referrer()) AND strpos($this->request->referrer(), 'oc-panel')===FALSE)
            $redir  = $this->request->referrer();
        else
            $redir = Route::url('oc-panel',array('controller'=>'auth','action'=>'login'));
            
        $this->redirect($redir);
    
    }
    
    /**
     * Sends an email with a link to change your password
     * 
     */
    public function action_forgot()
    {
        //template header
        $this->template->title            = __('Remember password');
        $this->template->content = View::factory('pages/auth/forgot');
        $this->template->meta_description = __('Here you can reset your password if you forgot it.');
        
        //if user loged in redirect home
        if (Auth::instance()->logged_in())
        {
            $this->redirect(Route::get('oc-panel')->uri());
        }
        //posting data so try to remember password
        elseif (core::post('email') AND CSRF::valid('forgot'))
        {
            $email = core::post('email');
            
            if (Valid::email($email))
            {
                //check we have this email in the DB
                $user = new Model_User();
                $user = $user->where('email', '=', $email)
                            ->limit(1)
                            ->find();
                
                if ($user->loaded())
                {
                    
                    //we get the QL, and force the regen of token for security
                    $url_ql = $user->ql('oc-panel',array( 'controller' => 'profile', 
                                                          'action'     => 'changepass'),TRUE);

                    //we don't use this since checks if the user is subscribed which is stupid since you want to remember your password.
                    //$ret = $user->email('auth-remember',array('[URL.QL]'=>$url_ql));
                    $ret = Email::content($user->email,$user->name,NULL,NULL,'auth-remember',array('[URL.QL]'=>$url_ql)); 

                    //email sent notify and redirect him
                    if ($ret)
                    {
                        Alert::set(Alert::SUCCESS, __('Email to recover password sent'));
                        $this->redirect(Route::url('oc-panel',array('controller'=>'auth','action'=>'login')));
                    }

                }
                else
                {
                    Form::set_errors(array(__('User not in database')));
                }
                
            }
            else
            {
                Form::set_errors(array(__('Invalid Email')));
            }
            
        }
                
            
    }
        
    /**
     * Sends request to admin (private site)
     * 
     */
    public function action_request()
    {
        //template header
        $this->template->title            = __('Request Access');
        $this->template->content = View::factory('pages/auth/request');
        $this->template->meta_description = __('Send your Name and Email to the administrator of the website');
        
        //if user loged in redirect home
        if (Auth::instance()->logged_in())
        {
            $this->redirect(Route::get('oc-panel')->uri());
        }
        
        elseif (core::post('email') AND core::post('name'))
        {
            $name = core::post('name');
            $email = core::post('email');
            
            if (Valid::email($email))
            {
                //check we have this email in the DB
                $user = new Model_User();
                $user = $user->where('email', '=', $email)
                            ->limit(1)
                            ->find();
                
                if (!$user->loaded())
                {

                    // email sent to admin
                    $replace = array('[EMAIL.BODY]'     =>$name.' requests access.',
                                     '[EMAIL.SUBJECT]'  =>'Access Request',
                                      '[EMAIL.SENDER]'  =>$name,
                                      '[EMAIL.FROM]'    =>$email);

                    if (Email::content(core::config('email.notify_email'),
                                        core::config('general.site_name'),
                                        $email,
                                        $name,'contact-admin',
                                        $replace))
                        Alert::set(Alert::SUCCESS, __('Your request has been sent'));
                    else
                        Alert::set(Alert::ERROR, __('Request not sent'));

                }
                else
                {
                    Alert::set(Alert::ERROR,__('User already exists'));
                }
                
            }
            else
            {
                Alert::set(Alert::ERROR,__('Invalid Email'));
            }
            
        }
                
        $this->redirect(Route::get('default')->uri());    
    }
    
    /**
     * Simple register for user
     *
     */
    public function action_register()
    {
        //validates captcha
        if (Core::post('ajaxValidateCaptcha'))
        {
            $this->auto_render = FALSE;
            $this->template = View::factory('js');

            if (captcha::check('register', TRUE))
                $this->template->content = 'true';
            else
                $this->template->content = 'false';
            
            return;
        }
        $this->template->meta_description = __('Create a new profile at').' '.core::config('general.site_name');
        $this->template->content = View::factory('pages/auth/register');
        $this->template->content->msg = '';
        
        //if user loged in redirect home
        if (Auth::instance()->logged_in())
        {
            $this->redirect(Route::get('oc-panel')->uri());
        }
        elseif ($this->request->post())
        {
            if(captcha::check('register')) {
                $validation =   Validation::factory($this->request->post())
                                ->rule('name', 'not_empty')
                                ->rule('name', 'min_length', array(':value', 2))
                                ->rule('name', 'max_length', array(':value', 145))
                                ->rule('email', 'not_empty')
                                ->rule('email', 'email')
                                ->rule('email', 'email_domain')
                                ->rule('password1', 'not_empty')
                                ->rule('password2', 'not_empty')
                                ->rule('password1', 'matches', array(':validation', 'password1', 'password2'));

                if(core::post('cf_vatnumber') AND core::post('cf_vatcountry'))
                {
                    if (!euvat::verify_vies(core::post('cf_vatnumber'),core::post('cf_vatcountry')))
                    {
                        Alert::set(Alert::ERROR, __('Invalid EU Vat Number, please verify number and country match'));
                        $this->redirect(Route::url('oc-panel', array('controller'=>'auth','action'=>'register')));
                    }
                }

                if ($validation->check())
                {
                    //posting data so try to remember password
                    if (CSRF::valid('register'))
                    {
                        $email = core::post('email');
                    
                        //check we have this email in the DB
                        $user = new Model_User();
                        $user = $user->where('email', '=', $email)
                                ->limit(1)
                                ->find();
                        
                        if ($user->loaded())
                        {
                            Form::set_errors(array(__('User already exists')));
                        }
                        else
                        {
                            //creating the user
                            $user = Model_User::create_email($email,core::post('name'),core::post('password1'));

                            //add custom fields
                            $save_cf = FALSE;
                            foreach ($this->request->post() as $custom_field => $value) 
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
                        
                            //login the user
                            Auth::instance()->login(core::post('email'), core::post('password1'));
                        
                            Alert::set(Alert::SUCCESS, __('Welcome!'));
                            //login the user
                            $this->redirect(Core::post('auth_redirect',Route::url('oc-panel')));
                        
                        }
                    }
                }
                else
                {
                    $errors = $validation->errors('auth');
                    
                    foreach ($errors as $error) 
                        Alert::set(Alert::ALERT, $error);                
                }
            }
            else
            { 
                Alert::set(Alert::ALERT, __('Captcha is not correct'));
            }
        }
    
        //template header
        $this->template->title            = __('Register new user');
            
    }
    
    /**
     *
     * Quick login for users.
     * Useful for confirmation emails, remember passwords etc...
     */
    public function action_ql()
    {
        $ql = $this->request->param('id');
        $url = Auth::instance()->ql_login($ql);
        
        //not a url go to login!
        if ($url==FALSE)
        {
            $url = Route::url('oc-panel',array('controller' => 'auth', 
                                                'action'     => 'login'));  
        }
        $this->redirect($url);
    }

    public function action_unsubscribe()
    {    
        $email_encoded = $this->request->param('id');

        $user = new Model_User();

        //mail encoded
        if ($email_encoded!==NULL)
        {
            //decode emails
            $email_encoded = Base64::fix_from_url($email_encoded);
            $encrypt = new Encrypt(Core::config('auth.hash_key'), MCRYPT_MODE_NOFB, MCRYPT_RIJNDAEL_128);
            $email   = $encrypt->decode($email_encoded);

            if (Valid::email($email))
            {
                //check we have this email in the DB
                $user = new Model_User();
                $user = $user->where('email', '=', $email)
                            ->limit(1)
                            ->find();            
            }
            else
                Alert::set(Alert::INFO, __('Not valid email.'));
        }
        //in case no parameter but user is loged in
        elseif (Auth::instance()->logged_in())
        {
            $user = Auth::instance()->get_user();
        }

        //lets unsubscribe the user
        if ($user->loaded())
        {
            $user->subscriber = 0;
            $user->last_modified = Date::unix2mysql();

            try {
                $user->save();
                Alert::set(Alert::SUCCESS, __('You have successfully unsubscribed'));                
            } catch (Exception $e) {
                //throw 500
                throw HTTP_Exception::factory(500,$e->getMessage());
            }   

            //unsusbcribe from elasticemail
            if ( Core::config('email.elastic_listname')!='' )
                ElasticEmail::unsubscribe(Core::config('email.elastic_listname'),$user->email);
        }
        else
            Alert::set(Alert::INFO, __('Please login to unsubscribe.'));


        //smart redirect
        if (Auth::instance()->logged_in())
            $this->redirect(Route::url('oc-panel',array('controller'=>'profile','action'=>'edit')));
        else
            $this->redirect(Route::url('default'));
    }

    /**
     * 2step verification form
     * 
     */
    public function action_2step()
    {
        // 2step disabled or trying to access directly
        if (!Auth::instance()->logged_in() OR Core::config('general.google_authenticator') == FALSE )
            $this->redirect(Route::get('oc-panel')->uri());

        //template header
        $this->template->title            = __('2 Step Authentication');
        $this->template->content = View::factory('pages/auth/2step');

        //if user loged in redirect home
        if  ( Auth::instance()->logged_in() AND ( Cookie::get('google_authenticator') == $this->user->id_user OR $this->user->google_authenticator == '' ) )
        {
            $this->redirect(Route::get('oc-panel')->uri());
        }
        //posting data so try to remember password
        elseif (core::post('code') AND CSRF::valid('2step'))
        {            
            //load library
            require Kohana::find_file('vendor', 'GoogleAuthenticator');

            $ga = new PHPGangsta_GoogleAuthenticator();
            if ($ga->verifyCode($this->user->google_authenticator, core::post('code'), 2)) 
            {
                //set cookie
                Cookie::set('google_authenticator' , $this->user->id_user, Core::config('auth.lifetime') );

                // redirect to the url we wanted to see
                Auth::instance()->login_redirect();
            } 
            else 
            {
                Form::set_errors(array(__('Invalid Code')));
            }
            
        }
    }


}