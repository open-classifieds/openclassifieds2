<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Contact extends Controller {

	public function action_index()
	{ 

		//template header
		$this->template->title           	= __('Contact Us');
		$this->template->meta_description	= __('Contact').' '.core::config('general.site_name');

		Breadcrumbs::add(Breadcrumb::factory()->set_title(__('Home'))->set_url(Route::url('default')));
		Breadcrumbs::add(Breadcrumb::factory()->set_title(__('Contact Us')));

		if($this->request->post()) //message submition  
		{
            //captcha check
            if(captcha::check('contact'))
            {
                //check if user is loged in
                if (Auth::instance()->logged_in())
                {
                    $email_from = Auth::instance()->get_user()->email;
                    $name_from  = Auth::instance()->get_user()->name;
                }
                else
                {
                    $email_from = core::post('email');
                    $name_from  = core::post('name');
                }

                //akismet spam filter
                if(!core::akismet($name_from, $email_from,core::post('message')))
                {
                    $replace = array('[EMAIL.BODY]'    =>core::post('message'),
                                     '[EMAIL.SUBJECT]' =>core::post('subject'),
                                     '[EMAIL.SENDER]'  =>$name_from,
                                     '[EMAIL.FROM]'    =>$email_from);

                    if (Email::content(core::config('email.notify_email'),
                                        core::config('general.site_name'),
                                        $email_from,
                                        $name_from,'contact-admin',
                                        $replace))
                        Alert::set(Alert::SUCCESS, __('Your message has been sent'));
                    else
                        Alert::set(Alert::ERROR, __('Message not sent'));
                }
                else
                {
                    Alert::set(Alert::WARNING, __('This email has been considered as spam! We are sorry but we can not send this email.'));
                }
            }
            else
                Alert::set(Alert::ERROR, __('Check the form for errors'));
					
				
		}

        $this->template->content = View::factory('pages/contact');
		
	}

	//email message generating, for single ad. Client -> owner  
	public function action_user_contact()
	{	
		$ad = new Model_Ad($this->request->param('id'));

		//message to user
		if($ad->loaded() AND $this->request->post() )
		{

            $user = new Model_User($ad->id_user);
        
            //require login to contact
            if ( (core::config('advertisement.login_to_contact') == TRUE OR core::config('general.messaging') == TRUE)
				AND !Auth::instance()->logged_in())
            {
                Alert::set(Alert::INFO, __('Please, login before contacting'));
                HTTP::redirect(Route::url('ad',array('category'=>$ad->category->seoname,'seotitle'=>$ad->seotitle)));
            }

            if(captcha::check('contact'))
            {
				//check if user is loged in
				if (Auth::instance()->logged_in())
				{
					$email_from = $this->user->email;
					$name_from  = $this->user->name;
				}
				else
				{
					$email_from = core::post('email');
					$name_from  = core::post('name');
				}

				//akismet spam filter
				if(!core::akismet($name_from, $email_from,core::post('message')))
				{
					if(core::config('general.messaging'))
					{
						//price?
						$price	= (core::post('price') !== NULL AND is_numeric(core::post('price'))) ? core::post('price') : NULL;

						$ret	= Model_Message::send_ad(core::post('message'), $this->user, $ad->id_ad, $price);
					}
					else
					{
	                    if(isset($_FILES['file']))
	                        $file = $_FILES['file'];
	                    else
	                        $file = NULL;

	                    //contact email is set use that one
	                    if( isset($ad->cf_contactemail) AND Valid::email($ad->cf_contactemail))
	                        $to = $ad->cf_contactemail;
	                    else
	                        $to = NULL;

	                    $ret = $user->email('user-contact',array('[EMAIL.BODY]'=> core::post('message'),
	                                                        '[AD.NAME]'     => $ad->title,
	                        				'[EMAIL.SENDER]'=> $name_from,
	                        				'[EMAIL.FROM]'	=> $email_from,
	                                                        '[URL.AD]'      => Route::url('ad',array('category'=>$ad->category->seoname,'seotitle'=>$ad->seotitle))
	                                                             ),
	                                                        $email_from,
	                                                        $name_from,
	                                                        $file, $to);
					}

					//if succesfully sent
					if ($ret)
					{
						Alert::set(Alert::SUCCESS, __('Your message has been sent'));

						// we are updating field of visit table (contact)
                        			Model_Visit::contact_ad($ad->id_ad);
					}
					else
						Alert::set(Alert::ERROR, __('Message not sent'));
						HTTP::redirect(Route::url('ad',array('category'=>$ad->category->seoname,'seotitle'=>$ad->seotitle)));
				}
				else
				{
						Alert::set(Alert::SUCCESS, __('This email has been considered as spam! We are sorry but we can not send this email.'));
				}
            }
			else
			{
						Alert::set(Alert::ERROR, __('Captcha is not correct'));
						HTTP::redirect(Route::url('ad',array('category'=>$ad->category->seoname,'seotitle'=>$ad->seotitle)));
			}
		}
	
	}


    //email message generating, for single profile.   
    public function action_userprofile_contact()
    {
        $user_to = new Model_User($this->request->param('id'));

        //message to user
        if($user_to->loaded() AND $this->request->post() )
        {

            if(captcha::check('contact'))
            {
                //check if user is loged in
                if (Auth::instance()->logged_in())
                {
                    $email_from = Auth::instance()->get_user()->email;
                    $name_from  = Auth::instance()->get_user()->name;
                }
                else
                {
                    $email_from = core::post('email');
                    $name_from  = core::post('name');
                }

                //require login to contact
                if (core::config('general.messaging') == TRUE AND !Auth::instance()->logged_in())
                {
                    Alert::set(Alert::INFO, __('Please, login before contacting'));
                    HTTP::redirect(Route::url('oc-panel'));
                }

                //akismet spam filter
                if(!core::akismet($name_from, $email_from,core::post('message')))
                {
					if(core::config('general.messaging'))
					{
						$ret = Model_Message::send_user(core::post('message'), $this->user, $user_to);						
					}
					else
					{
	                    $ret = $user_to->email('user-profile-contact',array('[EMAIL.BODY]'=> core::post('message'),
										      '[EMAIL.SENDER]'	  => $name_from,
	                                                                    	      '[EMAIL.SUBJECT]'	  => core::post('subject'),
	                                                                    	      '[EMAIL.FROM]'      => $email_from),
										$email_from, $name_from);
					}

                    //if succesfully sent
                    if ($ret)
                        Alert::set(Alert::SUCCESS, __('Your message has been sent'));
                    else
                        Alert::set(Alert::ERROR, __('Message not sent'));
                }
                else
                {
                    Alert::set(Alert::SUCCESS, __('This email has been considered as spam! We are sorry but we can not send this email.'));
                }

            }
            else
                Alert::set(Alert::ERROR, __('Captcha is not correct'));

            HTTP::redirect(Route::url('profile',array('seoname'=>$user_to->seoname)));
        }
    
    }

}
