<?php defined('SYSPATH') or die('No direct script access.');
/**
 * CONTROLLER NEW 
 */
class Controller_New extends Controller
{
	
	/**
	 * 
	 * NEW ADVERTISEMENT 
	 * 
	 */
	public function action_index()
	{

		//template header
		$this->template->title           	= __('Publish new advertisement');
		$this->template->meta_description	= __('Publish new advertisement');
		
		$this->template->styles = array('http://cdn.jsdelivr.net/bootstrap.datepicker/0.1/css/datepicker.css' => 'screen');
        $this->template->scripts['footer'] = array('http://cdn.jsdelivr.net/bootstrap.datepicker/0.1/js/bootstrap-datepicker.js',
        										   'js/new.js');

		//find all, for populating form select fields 
		list($categories,$order_categories)  = Model_Category::get_all();
		list($locations,$order_locations)  	 = Model_Location::get_all();
		
		// bool values from DB, to show or hide this fields in view
		$form_show = array('captcha'	=>core::config('advertisement.captcha'),
						   'website'	=>core::config('advertisement.website'),
						   'phone'		=>core::config('advertisement.phone'),
						   'location'	=>core::config('advertisement.location'),
						   'address'	=>core::config('advertisement.address'),
						   'price'		=>core::config('advertisement.price'));

		//render view publish new
		$this->template->content = View::factory('pages/ad/new', array('categories'		    => $categories,
                                                                       'order_categories'   => $order_categories,
																	   'locations' 			=> $locations,
                                                                       'order_locations'    => $order_locations,
																	   'form_show'			=> $form_show,
                                                                       'fields'             => Model_Field::get_all()));
		if ($_POST) 
        {
            // $_POST array with all fields 
            $data = array(  'title'         => $title       =   $this->request->post('title'),
                            'cat'           => $cat         =   $this->request->post('category'),
                            'loc'           => $loc         =   $this->request->post('location'),
                            'description'   => $description =   $this->request->post('description'),
                            'price'         => $price       =   $this->request->post('price'),
                            'address'       => $address     =   $this->request->post('address'),
                            'phone'         => $phone       =   $this->request->post('phone'),
                            'website'       => $website     =   $this->request->post('website')
                            ); 
            
            // append to $data new custom values
            foreach ($_POST as $name => $field) 
            {
            	// get by prefix
				if (strpos($name,'cf_') !== false) 
				{
					$data[$name] = $field;
					//checkbox and radio when selected return string 'on' as a value
					if($field == 'on')
						$data[$name] = 1;
				}
        	}
	
            // depending on user flow (moderation mode), change usecase
            $moderation = core::config('general.moderation'); 

            if ($moderation == Model_Ad::POST_DIRECTLY) // direct post no moderation
            {
                if (Core::config('sitemap.on_post') == TRUE)
                    Sitemap::generate(); 

                $status = Model_Ad::STATUS_PUBLISHED;
                $this->save_new_ad($data, $status, $published = TRUE, $moderation, $form_show['captcha']);

            }
            elseif( $moderation == Model_Ad::MODERATION_ON 
                 || $moderation == Model_Ad::PAYMENT_ON 
                 || $moderation == Model_Ad::EMAIL_CONFIRAMTION 
                 || $moderation == Model_Ad::EMAIL_MODERATION 
                 || $moderation == Model_Ad::PAYMENT_MODERATION)
            {
                $status = Model_Ad::STATUS_NOPUBLISHED;
                $this->save_new_ad($data, $status, $published = FALSE, $moderation, $form_show['captcha']);
            }
        }
		
 	}

 	/**
 	 * [save_new_ad Save new advertisement if validated, with a given parameters 
 	 * 
 	 * @param  [array] $data   [post values]
 	 * @param  [int] $status [status of advert.]
 	 * @param  [bool] $published [Confirms if advert is published. ref to model_ad]
 	 * @param  [int] $moderation [moderation status/mode]
 	 * 
 	 * @return [view] View dependant on usecase 
 	*/
 	public function save_new_ad($data, $status, $published, $moderation)
 	{
 		$user 		= new Model_User();
		$new_ad 	= new Model_Ad();

		//$_POST is submitted for a new ad 
		if($this->request->post()) 
		{
            if (captcha::check('newad'))
			{		
				//FORM DATA 
				$seotitle = $new_ad->gen_seo_title($data['title']); 
				
				$new_ad->title 			= Model_Ad::banned_words($data['title']);
				$new_ad->id_location 	= $data['loc'];
				$new_ad->id_category 	= $data['cat'];
				$new_ad->description 	= Model_Ad::banned_words($data['description']);
				$new_ad->seotitle 		= $seotitle;	 
				$new_ad->status 		= $status; 
				$new_ad->price 			= floatval(str_replace(',', '.', $data['price'])); 								
				$new_ad->address 		= $data['address'];
				$new_ad->phone			= $data['phone'];
				$new_ad->website		= $data['website']; 
				
				// set custom values
				foreach ($data as $name => $field) 
	            {
	            	// get only custom values with prefix
					if (strpos($name,'cf_') !== false) 
						$new_ad->$name = $field;
	        	}
	        	
	        	// User detection, if doesnt exists create
	        	$auth_user = Auth::instance();
		 		if (!$auth_user->logged_in()) 
				{
					$name 		= core::post('name');
					$email		= core::post('email');
					$user_id 	= $user->create_new_user($name, $email);
				}
				else
				{
					$user_id 	= $auth_user->get_user()->id_user;
					$name 		= $auth_user->get_user()->name;
					$email 		= $auth_user->get_user()->email;
				}

				// SAVE AD
				$new_ad->id_user = $user_id; // after handling user

				try
				{	
					//akismet spam filter
					if(!core::akismet(Model_Ad::banned_words($data['title']), $email,
								 	  Model_Ad::banned_words($data['description'])))
					{
						if($moderation == Model_Ad::EMAIL_MODERATION OR $moderation == Model_Ad::EMAIL_CONFIRAMTION)
							$new_ad->status = Model_Ad::STATUS_UNCONFIRMED;
						
						$new_ad->save();
					}
					else
					{
						Alert::set(Alert::SUCCESS, __('This post has been considered as spam! We are sorry but we cant publish this advertisement.'));
						$this->request->redirect('default');
					}//akismet

					// if moderation is off update db field with time of creation 
					if($published)
					{	
						$_ad_published = new Model_Ad();
						$_ad_published->where('seotitle', '=', $seotitle)->limit(1)->find();
						$_ad_published->published = $_ad_published->created;
						$_ad_published->save();
						$created = $_ad_published->created;
					}
					else 
					{
						$created = new Model_Ad();
						$created = $created->where('seotitle', '=', $seotitle)->limit(1)->find(); 
						$created = $created->created;
					}
					
					$user = $user->where('email', '=', $email)
						->limit(1)
						->find();

					// after successful posting send them email depending on moderation
					if($moderation == Model_Ad::EMAIL_CONFIRAMTION OR 
					   $moderation == Model_Ad::EMAIL_MODERATION)
					{
						$edit_url = core::config('general.base_url').'oc-panel/profile/update/'.$new_ad->id_ad;
                    	$delete_url = core::config('general.base_url').'oc-panel/ad/delete/'.$new_ad->id_ad;

						//we get the QL, and force the regen of token for security
                    	$url_ql = $user->ql('default',array( 'controller' => 'ad', 
                                                          	 'action'     => 'confirm_post',
                                                          	 'id'		  => $new_ad->id_ad),TRUE);
                    	
                    	
                    	$ret = $user->email('ads.confirm',array('[URL.QL]'=>$url_ql,
                    											'[AD.NAME]'=>$new_ad->title,
                    											'[URL.EDITAD]'=>$edit_url,
                    											'[URL.DELETEAD]'=>$delete_url));
                    	
					}
					elseif($moderation == Model_Ad::MODERATION_ON)
					{

                    	$edit_url = core::config('general.base_url').'oc-panel/profile/update/'.$new_ad->id_ad;
                    	$delete_url = core::config('general.base_url').'oc-panel/ad/delete/'.$new_ad->id_ad;

						//we get the QL, and force the regen of token for security
                    	$url_ql = $user->ql('oc-panel',array( 'controller'=> 'profile', 
                                                          	  'action'    => 'update',
                                                          	  'id'		  => $new_ad->id_ad),TRUE);

                    	$ret = $user->email('ads.notify',array('[URL.QL]'		=>$url_ql,
                    										   '[AD.NAME]'		=>$new_ad->title,
                    										   '[URL.EDITAD]'	=>$edit_url,
                    										   '[URL.DELETEAD]'	=>$delete_url)); // email to notify user of creating, but it is in moderation currently 
					}
					elseif($moderation == Model_Ad::POST_DIRECTLY)
					{
						$edit_url = core::config('general.base_url').'oc-panel/profile/update/'.$new_ad->id_ad;
                    	$delete_url = core::config('general.base_url').'oc-panel/ad/delete/'.$new_ad->id_ad;

						$url_cont = $user->ql('contact', array(),TRUE);
						$url_ad = $user->ql('ad', array('category'=>$data['cat'],
			                							'seotitle'=>$seotitle), TRUE);

						$ret = $user->email('ads.user_check',array('[URL.CONTACT]'	=>$url_cont,
			                										'[URL.AD]'		=>$url_ad,
			                										'[AD.NAME]'		=>$new_ad->title,
			                										'[URL.EDITAD]'	=>$edit_url,
                    										   		'[URL.DELETEAD]'=>$delete_url));
					}


					// new ad notification email to admin (notify_email), if set to TRUE 
					if(core::config('email.new_ad_notify'))
					{
						 
						$url_ad = $user->ql('ad', array('category'=>$data['cat'],
				                						'seotitle'=>$seotitle), TRUE);
						
						$replace = array('[URL.AD]'    	   =>$url_ad,
	                                  	 '[AD.TITLE]'  	   =>$new_ad->title);

		                Email::content(core::config('email.notify_email'),
		                                    core::config('general.site_name'),
		                                    core::config('email.notify_email'),
		                                    core::config('general.site_name'),'ads.to_admin',
		                                    $replace);
		            }
				}
				catch (ORM_Validation_Exception $e)
				{
					Form::errors($content->errors);
				}
				catch (Exception $e)
				{
					throw new HTTP_Exception_500($e->getMessage());
				}

				// IMAGE UPLOAD 
				// in case something wrong happens user is redirected to edit advert. 
	    		$filename = NULL;
	    		$counter = 0;

	    		for ($i=0; $i < core::config("advertisement.num_images"); $i++) 
	    		{ 
	    			$counter++;

	    			if (isset($_FILES['image'.$i]))
	        		{
		        		$img_files = $_FILES['image'.$i];
		            	$filename = $new_ad->save_image($img_files, $new_ad->id_ad, $created, $new_ad->seotitle, $counter);
	        		}
	        		
	        		if ($filename){
			        	$new_ad->has_images = 1;
			        	try 
						{
							$new_ad->save();
						} 
						catch (Exception $e) 
						{
							throw new HTTP_Exception_500($e->getMessage());
						}
	        		}
		        	
		        	if($filename = FALSE)
		        		$this->request->redirect(Route::url('oc-panel', array('controller'=>'profile','action'=>'update','id'=>$new_ad->id_ad)));
		        }

				// PAYMENT METHOD ACTIVE (and other alerts)
				if($moderation == Model_Ad::PAYMENT_ON || $moderation == Model_Ad::PAYMENT_MODERATION)
				{
					$payment_order = new Model_Order();
					$order_id = $payment_order->make_new_order($data, $usr, $seotitle);

					if($order_id == NULL)
					{
						if($moderation == Model_Ad::PAYMENT_ON)
						{

							$new_ad->status = 1;
							$new_ad->published = Date::unix2mysql(time());
							Alert::set(Alert::SUCCESS, __('Advertisement is published. Congratulations!'));	
						}
						if($moderation == Model_Ad::PAYMENT_MODERATION)
							Alert::set(Alert::SUCCESS, __('Advertisement is created but needs to be validated first before it is published.'));

						$this->request->redirect(Route::url('default'));
					}
					// redirect to payment
        			$this->request->redirect(Route::url('default', array('controller'=> 'payment_paypal','action'=>'form' , 'id' => $order_id))); // @TODO - check route
				}
				elseif ($moderation == Model_Ad::EMAIL_MODERATION OR $moderation == Model_Ad::EMAIL_CONFIRAMTION)
				{
					Alert::set(Alert::INFO, __('Advertisement is posted but first you need to activate. Please check your email!'));
					$this->request->redirect(Route::url('default'));
				}
				elseif ($moderation == Model_Ad::MODERATION_ON)
				{
					Alert::set(Alert::INFO, __('Advertisement is received, but first administrator needs to validate. Thank you for being patient!'));
					$this->request->redirect(Route::url('default'));
				}
				else
				{
					Model_Subscribe::find_subscribers($data, floatval(str_replace(',', '.', $data['price'])), $seotitle, $email);
					Alert::set(Alert::SUCCESS, __('Advertisement is posted. Congratulations!'));
					$this->request->redirect(Route::url('default'));
				}
			}//captcha
			else
			{ 
				Alert::set(Alert::ALERT, __('Captcha is not correct'));
			}
		}//is post
 	}// save new ad

}
