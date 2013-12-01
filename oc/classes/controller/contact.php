<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Contact extends Controller {

	public function action_index()
	{ 

		//template header
		$this->template->title           	= __('Contact Us');
		$this->template->meta_description	= __('Contact Us');

		Breadcrumbs::add(Breadcrumb::factory()->set_title(__('Home'))->set_url(Route::url('default')));
		Breadcrumbs::add(Breadcrumb::factory()->set_title(__('Contact Us')));

		if($this->request->post()) //message submition  
		{

            if (captcha::check('contact'))
            {
                //akismet spam filter
                if(!core::akismet(core::post('name'), 
                                  core::post('email'),
                                  core::post('message')))
                {
                    $replace = array('[EMAIL.BODY]'     =>core::post('message'),
                                      '[EMAIL.SENDER]'  =>core::post('name'),
                                      '[EMAIL.FROM]'    =>core::post('email'));

                    if (Email::content(core::config('email.notify_email'),
                                        core::config('general.site_name'),
                                        core::post('email'),
                                        core::post('name'),'contact.admin',
                                        $replace))
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
         
            if (captcha::check('contact'))
			{ 
                //akismet spam filter
                if(!core::akismet(core::post('name'), 
                                  core::post('email'),
                                  core::post('message')))
                {
                    if(isset($_FILES['file']))
                        $file = $_FILES['file'];
                    else 
                        $file = NULL;
                    
                    $ret = $user->email('user.contact',array('[EMAIL.BODY]'		=>core::post('message'),
                                                             '[AD.NAME]'        =>$ad->title,
                        									 '[EMAIL.SENDER]'	=>core::post('name'),
                        									 '[EMAIL.FROM]'		=>core::post('email')),
                                                        core::post('email'),
                                                        core::post('name'),
                                                        $file);
                    
                    //if succesfully sent
                    if ($ret)
                    {
                        Alert::set(Alert::SUCCESS, __('Your message has been sent'));

                        // we are updating field of visit table (contact)
                        $visit_contact_obj = new Model_Visit();

                        $visit_contact_obj->where('id_ad', '=', $this->request->param('id'))
                                          ->order_by('created', 'desc')
                                          ->limit(1)->find();
                                                          
                        try {
                            $visit_contact_obj->contacted = 1;
                            $visit_contact_obj->save();
                        } catch (Exception $e) {
                            //throw 500
                            throw new HTTP_Exception_500($e->getMessage());
                        }

                    }
                    else
                        Alert::set(Alert::ERROR, __('Message not sent'));

                    
                    Request::current()->redirect(Route::url('ad',array('category'=>$ad->category->seoname,'seotitle'=>$ad->seotitle)));
			    }
                else
                {
                    Alert::set(Alert::SUCCESS, __('This email has been considered as spam! We are sorry but we can not send this email.'));
                }
            }
			else
			{
				Alert::set(Alert::ERROR, __('You made some mistake'));
			}
		}
	
	}


    //email message generating, for single profile.   
    public function action_userprofile_contact()
    {
        $user = new Model_User($this->request->param('id'));

        //message to user
        if($user->loaded() AND $this->request->post() )
        {

            if (captcha::check('contact'))
            {
                //akismet spam filter
                if(!core::akismet(core::post('name'), 
                                  core::post('email'),
                                  core::post('message')))
                {
                    $ret = $user->email('userprofile.contact',array('[EMAIL.BODY]'     =>core::post('message'),
                                                                    '[EMAIL.SENDER]'   =>core::post('name'),
                                                                    '[EMAIL.SUBJECT]'   =>core::post('subject'),
                                                                    '[EMAIL.FROM]'     =>core::post('email')),core::post('email'),core::post('name'));
                    
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
                Alert::set(Alert::ERROR, __('You made some mistake'));

            Request::current()->redirect(Route::url('profile',array('seoname'=>$user->seoname)));
        }
    
    }

}