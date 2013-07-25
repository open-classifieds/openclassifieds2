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
		
		
		$user 		= new Model_User();
		
		//find all, for populating form select fields 
		list($categories,$order_categories)  = Model_Category::get_all();

		list($locations,$order_locations)  = Model_Location::get_all();
		
		// bool values from DB, to show or hide this fields in view
		$form_show = array('captcha'	=>core::config('advertisement.captcha'),
						   'website'	=>core::config('advertisement.website'),
						   'phone'		=>core::config('advertisement.phone'),
						   'location'	=>core::config('advertisement.location'),
						   'address'	=>core::config('advertisement.address'),
						   'price'		=>core::config('advertisement.price'));

		//render view publish new
		$this->template->content = View::factory('pages/ad/new', array('categories'		    => $categories,
                                                                        'order_categories'  => $order_categories,
																	   'locations' 			=> $locations,
                                                                        'order_locations'  => $order_locations,
																	   'form_show'			=> $form_show));
		
		// $_POST array with all fields 
		$data = array(	'auth' 			=> $auth 		= 	Auth::instance(),
						'title' 		=> $title 		= 	$this->request->post('title'),
						'cat'			=> $cat 		= 	$this->request->post('category'),
						'loc'			=> $loc 		= 	$this->request->post('location'),
						'description'	=> $description = 	$this->request->post('description'),
						'price'			=> $price 		= 	$this->request->post('price'),
						'address'		=> $address 	= 	$this->request->post('address'),
						'phone'			=> $phone 		= 	$this->request->post('phone'),
						'website'		=> $website 	= 	$this->request->post('website'),
						'user'			=> $user
						); 
		
		// depending on user flow (moderation mode), change usecase
		$moderation = core::config('general.moderation'); 


		if ($moderation == Model_Ad::POST_DIRECTLY) // direct post no moderation
		{
			if (Core::config('sitemap.on_post') == TRUE)
				Sitemap::generate(); 

			$status = Model_Ad::STATUS_PUBLISHED;
			$this->save_new_ad($data, $status, $published = TRUE, $moderation, $form_show['captcha']);

		}
		elseif($moderation == Model_Ad::MODERATION_ON 
			 || $moderation == Model_Ad::PAYMENT_ON 
			 || $moderation == Model_Ad::EMAIL_CONFIRAMTION 
			 || $moderation == Model_Ad::EMAIL_MODERATION 
			 || $moderation == Model_Ad::PAYMENT_MODERATION)
		{
			$status = Model_Ad::STATUS_NOPUBLISHED;
			$this->save_new_ad($data, $status, $published = FALSE, $moderation, $form_show['captcha']);
		}

			
 	}

	public function randString($length = 8) {
		$chars = "abcdefghjklmnpqrstuvwxyzABCDEFGHJKLMNPQRSTUVWXYZ23456789";	
		$str = "";
		$size = strlen($chars);
		for($i = 0; $i < $length; $i++) {
			$str .= $chars[rand(0, $size - 1)];
		}
		return $str;
	}

 	/**
 	 * [save_new_ad Save new advertisement if validated, with a given parameters 
 	 * 
 	 * @param  [array] $data   [post values]
 	 * @param  [int] $status [status of advert.]
 	 * @param  [bool] $published [Confirms if advert is published. ref to model_ad]
 	 * @param  [int] $moderation [moderation status/mode]
 	 * @param  [bool] $captcha_show [Chaptcha active/notactive. ref to db config]
 	 * 
 	 * @return [view] View dependant on usecase 
 	 */
 	public function save_new_ad($data, $status, $published, $moderation, $captcha_show)
 	{
 		
		$new_ad = new Model_Ad();

		//$_POST is submitted for a new ad 
		if($this->request->post()) 
		{
			if($captcha_show == FALSE || captcha::check('contact') ) 
			{		
				//FORM DATA 
				$seotitle = $new_ad->gen_seo_title($data['title']); 
				
				$new_ad->title 			= $data['title'];
				$new_ad->id_location 	= $data['loc'];
				$new_ad->id_category 	= $data['cat'];
				$new_ad->description 	= $data['description'];
				$new_ad->type 	 		= '0';
				$new_ad->seotitle 		= $seotitle;	 
				$new_ad->status 		= $status; 
				$new_ad->price 			= floatval(str_replace(',', '.', $data['price'])); 								
				$new_ad->address 		= $data['address'];
				$new_ad->phone			= $data['phone'];
				$new_ad->website		= $data['website']; 

				try
				{

					/////////////////// USER DETECTION ////////////////
	        		// case when user is not logged in. 
	        		// We create new user if he doesn't exists in DB
	        		// and send him mail for ad created + new profile created
			 		if (!$data['auth']->logged_in()) 
					{
				
						$name 		= $this->request->post('name');
						$email		= $this->request->post('email');
						$password	= $this->request->post('password');
						// $seoname	= URL::title($this->request->post('name'));
						
						// check validity of email
						if (Valid::email($email,TRUE))
						{
							// search for email in DB
							$user = $data['user']->where('email', '=', $email)
									->limit(1)
									->find();

							if(!$user->loaded())
							{ 
								$new_password_plain = self::randString(8);
								$user->email 	= $email;
								$user->name 	= $name;
								$user->status 	= Model_User::STATUS_ACTIVE;
								$user->id_role	= Model_Role::ROLE_USER;//normal user
								$user->password = $new_password_plain;
								$user->seoname 	= $name;
								
								try
								{
									
									$user->save();

									Alert::set(Alert::SUCCESS, __('New profile has been created. Welcome ').$name.' !');
								
									//we get the QL, and force the regen of token for security
			                    	$url_pwch = $user->ql('oc-panel',array('controller' => 'profile', 
			                    										   'action'		=> 'edit'),TRUE);

			                    	$ret = $user->email('user.new',array('[URL.PWCH]'=>$url_pwch,
			                    										 '[USER.PWD]'=>$new_password_plain));
														
								}
								catch (ORM_Validation_Exception $e)
								{
									throw new HTTP_Exception_500($e->getMessage());
								}
								catch (Exception $e)
								{
									throw new HTTP_Exception_500($e->getMessage());
								}
							}

								$usr = $data['user']->id_user;
						}
						else
						{
							Alert::set(Alert::ALERT, __('Invalid Email'));
							$this->request->redirect(Route::url('post_new'));
						}
					}
					else
					{
						$usr 		= $data['auth']->get_user()->id_user;
						$name 		= $data['auth']->get_user()->name;
						$email 		= $data['auth']->get_user()->email;
					}

					// SAVE AD
					$new_ad->id_user 		= $usr; // after handling user
					
					if($moderation == Model_Ad::EMAIL_MODERATION OR $moderation == Model_Ad::EMAIL_CONFIRAMTION)
					{
						$new_ad->status = Model_Ad::STATUS_UNCONFIRMED;
						$new_ad->save();
					}
					else
						$new_ad->save();
	
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
					
					$user = new Model_User();
					$user = $user->where('email', '=', $email)
						->limit(1)
						->find();


					// after successful posting send them email depending on moderation
					if($moderation == Model_Ad::EMAIL_CONFIRAMTION OR $moderation == Model_Ad::EMAIL_MODERATION)
					{
						//we get the QL, and force the regen of token for security
                    	$url_ql = $user->ql('default',array( 'controller' => 'ad', 
                                                          	 'action'     => 'confirm_post',
                                                          	 'id'		  => $new_ad->id_ad),TRUE);

                    	$ret = $user->email('ads.confirm',array('[URL.QL]'=>$url_ql));
					}
					else if($moderation == Model_Ad::MODERATION_ON)
					{
						//we get the QL, and force the regen of token for security
                    	$url_ql = $user->ql('oc-panel',array( 'controller'=> 'profile', 
                                                          	  'action'    => 'update',
                                                          	  'id'		  => $new_ad->id_ad),TRUE);

                    	$ret = $user->email('ads.notify',array('[URL.QL]'=>$url_ql));
					}
					else if($moderation == Model_Ad::POST_DIRECTLY)
					{
						$url_cont = $user->ql('contact', array(),TRUE);
						$url_ad = $user->ql('ad', array('category'=>$data['cat'],
			                							'seotitle'=>$seotitle), TRUE);

						$ret = $user->email('ads.user_check',array('[URL.CONTACT]'	=>$url_cont,
			                										'[URL.AD]'		=>$url_ad)); 
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
				$error_message = NULL;
	    		$filename = NULL;
	    		$counter = 0;

	    		for ($i=0; $i < core::config("advertisement.num_images"); $i++) 
	    		{ 
	    			$counter++;

	    			if (isset($_FILES['image'.$i]))
	        		{
		        		$img_files = array($_FILES['image'.$i]);

		            	$filename = $new_ad->save_image($img_files, $new_ad->id_ad, $created, $new_ad->seotitle, $counter);

	        		}

	        		if ($filename['error'] == TRUE)
		       		{
			        	$new_ad->has_images = 1;
		        	}
		        	
		        	if($filename['error_name'] == "wrong_format" || $filename['error_name'] == 'upload_unsuccessful')
		        	{
		        		Alert::set(Alert::ALERT, __('Something went wrong with uploading pictures, please check format'));

		        		$this->request->redirect(Route::url('oc-panel', array('controller'=>'profile','action'=>'update','id'=>$new_ad->id_ad)));
		        	}

		        	try {
		        		$new_ad->save();
		        	} catch (Exception $e) {
		        		Alert::set(Alert::ALERT, __('Something went wrong with uploading pictures'));
		        		$this->request->redirect(Route::url('oc-panel', array('controller'=>'profile','action'=>'update','id'=>$ad->id_ad)));
		        	}
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
							try {
									$new_ad->save();
									Alert::set(Alert::SUCCESS, __('Advertisement is published. Congratulations!'));
								} catch (Exception $e) {
									throw new HTTP_Exception_500($e->getMessage());
								}	
						}
						if($moderation == Model_Ad::PAYMENT_MODERATION)
							Alert::set(Alert::SUCCESS, __('Advertisement is created but needs to be validated first before it is published.'));


						$this->request->redirect(Route::url('default'));
					}
					// redirect to payment
        			$this->request->redirect(Route::url('default', array('controller'=> 'payment_paypal','action'=>'form' , 'id' => $order_id))); // @TODO - check route
				}
				else if ($moderation == Model_Ad::EMAIL_MODERATION OR $moderation == Model_Ad::EMAIL_CONFIRAMTION)
				{
					Alert::set(Alert::INFO, __('Advertisement is posted but first you need to activate. Please check your email!'));
					$this->request->redirect(Route::url('default'));
				}
				else if ($moderation == Model_Ad::MODERATION_ON)
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

			}
			else
			{ 
				Alert::set(Alert::ALERT, __('Captcha is not correct'));
			}
		}
 	}

}
