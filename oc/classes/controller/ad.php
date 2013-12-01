<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Ad extends Controller {
	

	/**
	 * Publis all adver.-s without filter
	 */
	public function action_listing()
	{ 
		

		Breadcrumbs::add(Breadcrumb::factory()->set_title(__('Home'))->set_url(Route::url('default')));
		
        /**
         * we get the model of category and location from controller to filter and generate urls titles etc...
         */
        
        $location = NULL;
        $location_parent = NULL;
        if (Controller::$location!==NULL)
        {
            if (Controller::$location->loaded())
            {
            	$location = Controller::$location;
                //adding the location parent
                if ($location->id_location_parent!=1 AND $location->parent->loaded())
                    $location_parent = $location->parent;
            }  
        }

        $category = NULL;
        $category_parent = NULL;
        if (Controller::$category!==NULL)
        {
            if (Controller::$category->loaded())
            {
                $category = Controller::$category;
                //adding the category parent
                if ($category->id_category_parent!=1 AND $category->parent->loaded())
                    $category_parent = $category->parent;

            }
           
        }

        //base title
        if ($category!==NULL)
            $this->template->title = $category->name;
        else
            $this->template->title = __('all');

        //adding location titles and breadcrumbs
        if ($location!==NULL)
        {
            $this->template->title .= ' - '.$location->name;

            if ($location_parent!==NULL)
            {
                $this->template->title .=' ('.$location_parent->name.')';
                Breadcrumbs::add(Breadcrumb::factory()->set_title($location_parent->name)->set_url(Route::url('list', array('location'=>$location_parent->seoname))));
            }

            Breadcrumbs::add(Breadcrumb::factory()->set_title($location->name)->set_url(Route::url('list', array('location'=>$location->seoname))));
                
            if ($category_parent!==NULL)
                Breadcrumbs::add(Breadcrumb::factory()->set_title($category_parent->name)
                    ->set_url(Route::url('list', array('category'=>$category_parent->seoname,'location'=>$location->seoname))));
            
            if ($category!==NULL)
                Breadcrumbs::add(Breadcrumb::factory()->set_title($category->name)
                    ->set_url(Route::url('list', array('category'=>$category->seoname,'location'=>$location->seoname))));
        }
        else
        {
            if ($category_parent!==NULL)
            {
                $this->template->title .=' ('.$category_parent->name.')';
                Breadcrumbs::add(Breadcrumb::factory()->set_title($category_parent->name)
                    ->set_url(Route::url('list', array('category'=>$category_parent->seoname))));
            }
                
            
            if ($category!==NULL)
                Breadcrumbs::add(Breadcrumb::factory()->set_title($category->name)
                    ->set_url(Route::url('list', array('category'=>$category->seoname))));
        }


    

        $data = $this->list_logic($category, $location);
   		
		$this->template->bind('content', $content);
		$this->template->content = View::factory('pages/ad/listing',$data);
 	}

    /**
     * gets data to the view and filters the ads
     * @param  Model_Category $category 
     * @param  Model_Location $location
     * @return array           
     */
	public function list_logic($category = NULL, $location = NULL)
	{

		//user recognition 
		$user = (Auth::instance()->get_user() == NULL) ? NULL : Auth::instance()->get_user();

		$ads = new Model_Ad();
		
		//filter by category or location
        if ($category!==NULL)
        {
            $ads->where('id_category', 'in', $category->get_siblings_ids());
        }

        if ($location!==NULL)
        {
            $ads->where('id_location', 'in', $location->get_siblings_ids());
        }


		//only published ads
        $ads->where('status', '=', Model_Ad::STATUS_PUBLISHED);
		

        //if ad have passed expiration time dont show 
        if(core::config('advertisement.expire_date') > 0)
        {
            $ads->where(DB::expr('DATE_ADD( published, INTERVAL '.core::config('advertisement.expire_date').' DAY)'), '>', DB::expr('NOW()'));
        }
        

		$res_count = $ads->count_all();
		// check if there are some advet.-s
		if ($res_count > 0)
		{
   
       		// pagination module
       		$pagination = Pagination::factory(array(
                    'view'           	=> 'pagination',
                    'total_items'    	=> $res_count,
                    'items_per_page' 	=> core::config('general.advertisements_per_page'),
     	    ))->route_params(array(
                    'controller' 		=> $this->request->controller(),
                    'action'      		=> $this->request->action(),
                    'category' 			=> ($category!==NULL)?$category->seoname:NULL,
                    'location'			=> ($location!==NULL)?$location->seoname:NULL, 
    	    ));
    	   
     	    Breadcrumbs::add(Breadcrumb::factory()->set_title(__("Page ").$pagination->current_page));

     	    //we sort all ads with few parameters
       		$ads = $ads->order_by('published','desc')
		        	            ->limit($pagination->items_per_page)
		        	            ->offset($pagination->offset)
		        	            ->find_all();
		}
		else
		{
			// array of categories sorted for view
			return array('ads'			=> NULL,
						 'pagination'	=> NULL, 
						  'user'          => $user, 
                         'category'     => $category,
                         'location'     => $location,);
		}
		
		// array of categories sorted for view
		return array('ads'			=> $ads,
					 'pagination'	=> $pagination, 
					 'user'			=> $user, 
					 'category'		=> $category,
					 'location'		=> $location,);
	}

	/**
	 * 
	 * Display single advert. 
	 * @throws HTTP_Exception_404
	 * 
	 */
	public function action_view()
	{
		
		$seotitle = $this->request->param('seotitle',NULL);
		
		if ($seotitle!==NULL)
		{
			$ad = new Model_Ad();
			$ad->where('seotitle','=', $seotitle)
                ->where('status','!=',Model_Ad::STATUS_SPAM)
				->limit(1)->cached()->find();

			if ($ad->loaded())
			{
                Breadcrumbs::add(Breadcrumb::factory()->set_title(__('Home'))->set_url(Route::url('default')));

                $location = NULL;
                $location_parent = NULL;
                if ($ad->location->loaded() AND $ad->id_location!=1)
                {
                    $location = $ad->location;
                    //adding the location parent
                    if ($location->id_location_parent!=1 AND $location->parent->loaded())
                        $location_parent = $location->parent;
                }  
                

                $category = NULL;
                $category_parent = NULL;
                if ($ad->category->loaded())
                {
                    $category = $ad->category;
                    //adding the category parent
                    if ($category->id_category_parent!=1 AND $category->parent->loaded())
                        $category_parent = $category->parent;

                }
                   
                

                //base category  title
                if ($category!==NULL)
                    $this->template->title = $category->name;
                else
                    $this->template->title = '';

                //adding location titles and breadcrumbs
                if ($location!==NULL)
                {
                    $this->template->title .= ' - '.$location->name;

                    if ($location_parent!==NULL)
                    {
                        $this->template->title .=' ('.$location_parent->name.')';
                        Breadcrumbs::add(Breadcrumb::factory()->set_title($location_parent->name)->set_url(Route::url('list', array('location'=>$location_parent->seoname))));
                    }

                    Breadcrumbs::add(Breadcrumb::factory()->set_title($location->name)->set_url(Route::url('list', array('location'=>$location->seoname))));
                        
                    if ($category_parent!==NULL)
                        Breadcrumbs::add(Breadcrumb::factory()->set_title($category_parent->name)
                            ->set_url(Route::url('list', array('category'=>$category_parent->seoname,'location'=>$location->seoname))));
                    
                    if ($category!==NULL)
                        Breadcrumbs::add(Breadcrumb::factory()->set_title($category->name)
                            ->set_url(Route::url('list', array('category'=>$category->seoname,'location'=>$location->seoname))));
                }
                else
                {
                    if ($category_parent!==NULL)
                    {
                        $this->template->title .=' ('.$category_parent->name.')';
                        Breadcrumbs::add(Breadcrumb::factory()->set_title($category_parent->name)
                            ->set_url(Route::url('list', array('category'=>$category_parent->seoname))));
                    }
                        
                    
                    if ($category!==NULL)
                        Breadcrumbs::add(Breadcrumb::factory()->set_title($category->name)
                            ->set_url(Route::url('list', array('category'=>$category->seoname))));
                }



                $this->template->title = $ad->title.' - '. $this->template->title;
				
				Breadcrumbs::add(Breadcrumb::factory()->set_title($ad->title));   	

				
                $this->template->meta_description = text::removebbcode($ad->description);

				$permission = TRUE; //permission to add hit to advert and give access rights. 
				if(!Auth::instance()->logged_in() || 
					(Auth::instance()->get_user()->id_user != $ad->id_user && Auth::instance()->get_user()->id_role != Model_Role::ROLE_ADMIN) || 
					Auth::instance()->get_user()->id_role != Model_Role::ROLE_ADMIN)
				{	
					if(!Auth::instance()->logged_in())
						$visitor_id = NULL;
					else
						$visitor_id = Auth::instance()->get_user()->id_user;
					$do_hit = $ad->count_ad_hit($ad->id_ad, $visitor_id, ip2long(Request::$client_ip)); // hits counter
					
					$permission = FALSE;
					$user = NULL;
					
				} 
                else 
                    $user = Auth::instance()->get_user()->id_user;

				//count how many matches are found 
		        $hits = new Model_Visit();
		        $hits = $hits->where('id_ad','=', $ad->id_ad)->count_all();

				$captcha_show = core::config('advertisement.captcha');	

				$this->template->bind('content', $content);
				$this->template->content = View::factory('pages/ad/single',array('ad'				=>$ad,
																				   'permission'		=>$permission, 
																				   'hits'			=>$hits, 
																				   'captcha_show'	=>$captcha_show,
																				   'user'			=>$user,
																				   'custom_fields'	=>$ad->custom_columns()));

			}
			//not found in DB
			else
			{
				//throw 404
				throw new HTTP_Exception_404();
			}
			
		}
		else//this will never happen
		{
			//throw 404
			throw new HTTP_Exception_404();
		}
	}
	
	
	/**
	 * [image_path Get directory path of specific advert.]
	 * @param  [array] $data [all values of one advert.]
	 * @return [array]       [array of dir. path where images of advert. are ]
	 */
	public function image_path($data)
	{
		$obj_ad = new Model_Ad();
		$directory = $obj_ad->gen_img_path($data->id_ad, $data->created);

		$path = array();
		if(is_dir($directory))
		{	
			$filename = array_diff(scandir($directory, 1), array('..','.')); //return all file names , and store in array 

			foreach ($filename as $filename) {
				array_push($path, $directory.$filename);		
			}
		}
		else
		{ 	
			return FALSE ;
		}

		return $path;
	}

	/**
	 * [action_to_top] [pay to go on top, and make order]
	 *
	 * @TODO if paymant is corrent and done update order table(status, pay_date), and put it to top (change published date)
	 */
	public function action_to_top()
	{
		$payer_id 		= Auth::instance()->get_user()->id_user; 
		$id_product 	= Paypal::to_top;
		$description 	= 'to_top';
		// update orders table
		// fields
		$ad = new Model_Ad($this->request->param('id'));
		
		//case when payment is set to 0, it gets published without payment
		if(core::config('payment.pay_to_go_on_top') == FALSE)
		{
			$ad->status = 1;
			$ad->published = Date::unix2mysql(time());

			try {
				$ad->save();
				$this->request->redirect(Route::url('list')); 

			} catch (Exception $e) {
				throw new HTTP_Exception_500($e->getMessage());
			}
		}
		
		$ord_data = array('id_user' 	=> $payer_id,
						  'id_ad' 		=> $ad->id_ad,
						  'id_product' 	=> $id_product,
						  'paymethod' 	=> 'paypal', // @TODO - to strict
						  'currency' 	=> core::config('payment.paypal_currency'),
						  'amount' 		=> core::config('payment.pay_to_go_on_top'),
						  'description'	=> $description);

		$order_id = new Model_Order(); // create order , and returns order id
		$order_id = $order_id->set_new_order($ord_data);
	
		
		// redirect to payment
		$this->request->redirect(Route::url('default', array('controller' =>'payment_paypal','action'=>'form' ,'id' => $order_id)));

	}
	
	/**
	 * [action_to_featured] [pay to go in featured]
	 *
	 * @TODO - when paypal returns token, update
	 */
	public function action_to_featured()
	{
		$payer_id 		= Auth::instance()->get_user()->id_user; 
		$id_product 	= Paypal::to_featured;
		$description 	= 'to_featured';

		// update orders table
		// fields
		$ad = new Model_Ad($this->request->param('id'));
	
		//case when payment is set to 0, it gets published without payment
		if(core::config('payment.pay_to_go_on_feature') == FALSE)
		{
			$ad->status = 1;
			$ad->featured = Date::unix2mysql(time() + (core::config('payment.featured_days') * 24 * 60 * 60));

			try {
				$ad->save();
				$this->request->redirect(Route::url('list')); 

			} catch (Exception $e) {
				throw new HTTP_Exception_500($e->getMessage());
			}
		}

		$ord_data = array('id_user' 	=> $payer_id,
						  'id_ad' 		=> $ad->id_ad,
						  'id_product' 	=> $id_product,
						  'paymethod' 	=> 'paypal', // @TODO - to strict
						  'currency' 	=> core::config('payment.paypal_currency'),
						  'amount' 		=> core::config('payment.pay_to_go_on_feature'),
						  'description'	=> $description);
		
		$order_id = new Model_Order(); // create order , and returns order id
		$order_id = $order_id->set_new_order($ord_data);
		// redirect to payment
		$this->request->redirect(Route::url('default', array('controller' =>'payment_paypal','action'=>'form' ,'id' => $order_id)));
	}
	
	public function action_confirm_post()
	{
		$advert_id = $this->request->param('id');

		$advert = new Model_Ad($advert_id);

		if($advert->loaded())
		{
			if(core::config('general.moderation') == Model_Ad::EMAIL_CONFIRMATION)
			{

				$advert->status = 1; // status active
				$advert->published = Date::unix2mysql(time());

				try 
				{
					$advert->save();

					//subscription is on
					$data = array(	'title' 		=> $title 		= 	$advert->title,
									'cat'			=> $cat 		= 	$advert->category,
									'loc'			=> $loc 		= 	$advert->location,	
								 );

					Model_Subscribe::find_subscribers($data, floatval(str_replace(',', '.', $advert->price)), $advert->seotitle, Auth::instance()->get_user()->email); // if subscription is on
					
					Alert::set(Alert::INFO, __('Your advertisement is successfully activated! Thank you!'));
					$this->request->redirect(Route::url('ad', array('category'=>$advert->id_category, 'seotitle'=>$advert->seotitle)));	
				} 
				catch (Exception $e) 
				{
					throw new HTTP_Exception_500($e->getMessage());
				}
			}
			if(core::config('general.moderation') == Model_Ad::EMAIL_MODERATION)
			{

				$advert->status = 0; // status active

				try 
				{
					$advert->save();
					Alert::set(Alert::INFO, __('Advertisement is received, but first administrator needs to validate. Thank you for being patient!'));
					$this->request->redirect(Route::url('ad', array('category'=>$advert->id_category, 'seotitle'=>$advert->seotitle)));	
				} 
				catch (Exception $e) 
				{
					throw new HTTP_Exception_500($e->getMessage());
				}
			}
		}
	}

	public function action_advanced_search()
	{
		//template header
		$this->template->title           	= __('Advanced Search');
		$this->template->meta_description	= __('Advanced Search');

		$this->template->styles = array('http://cdn.jsdelivr.net/bootstrap.datepicker/0.1/css/datepicker.css' => 'screen');
        $this->template->scripts['footer'] = array('http://cdn.jsdelivr.net/bootstrap.datepicker/0.1/js/bootstrap-datepicker.js',
                                                   'js/new.js');

		//breadcrumbs
		Breadcrumbs::add(Breadcrumb::factory()->set_title(__('Home'))->set_url(Route::url('default')));
		

		// $cat_obj = new Model_Category();
		// $loc_obj = new Model_Location();
		list($cat_obj,$order_categories)  = Model_Category::get_all();
		list($loc_obj,$order_locations)  = Model_Location::get_all();
		
		$pagination = NULL;
        $ads   = NULL;
		$user = (Auth::instance()->get_user() == NULL) ? NULL : Auth::instance()->get_user();

		if($this->request->query()) // after query has detected
		{			
        	// variables 
        	$search_advert 	= core::get('title');
        	$search_loc 	= core::get('location');

        	// filter by each variable
        	$ads = new Model_Ad();
        	
        	//if ad have passed expiration time dont show 
	        if(core::config('advertisement.expire_date') > 0)
	        {
	            $ads->where(DB::expr('DATE_ADD( published, INTERVAL '.core::config('advertisement.expire_date').' DAY)'), '>', DB::expr('NOW()'));
	        }

	        if(!empty($search_advert) OR core::get('search'))
	        {	
	        	// if user is using search from header
	        	if(core::get('search'))
	        		$search_advert = core::get('search');

	        	$ads = $ads->where('title', 'like', '%'.$search_advert.'%');
	        		if(core::config('general.search_by_description') == TRUE)
	        			   $ads = $ads->or_where('description', 'like', '%'.$search_advert.'%');
	        }

	        // append to $data new custom values
        	$cf_fields = array();
            foreach ($this->request->query() as $name => $field) 
            {
            	// get by prefix
				if (strpos($name,'cf_') !== false) 
				{
					$cf_fields[$name] = $field;
					//checkbox and radio when selected return string 'on' as a value
						if($field == 'on')
							$cf_fields[$name] = 1;
				}
        	}
	        	
	        $category = NULL;
            //filter by category 
            if (core::get('category')!==NULL)
            {
                $category = new Model_Category();
                $category->where('seoname','=',core::get('category'))->limit(1)->find();
                if ($category->loaded())
                    $ads->where('id_category', '=', $category->get_siblings_ids());
            }

	        $location = NULL;
            //filter by location 
            if (core::get('location')!==NULL)
            {
                $location = new Model_location();
                $location->where('seoname','=',core::get('location'))->limit(1)->find();
                if ($location->loaded())
                    $ads->where('id_location', '=', $location->get_siblings_ids());
            }

	        foreach ($cf_fields as $key => $value) 
	        {	
	        	if(!empty($value))
	        	{
		        	if(is_numeric($value))
		        		$ads = $ads->where($key, '=', $value);
		        	elseif(is_string($value))
		        		$ads = $ads->where($key, 'like', '%'.$value.'%');
		        }
	        }

	        $ads = $ads->where('status', '=', Model_Ad::STATUS_PUBLISHED);

	        // count them for pagination
			$res_count = $ads->count_all();

			if($res_count>0)
			{
			
				// pagination module
                $pagination = Pagination::factory(array(
                        'view'              => 'pagination',
                        'total_items'       => $res_count,
                        'items_per_page'    => core::config('general.advertisements_per_page'),
                ))->route_params(array(
                        'controller'        => $this->request->controller(),
                        'action'            => $this->request->action(),
                        'category'          => ($category!==NULL)?$category->seoname:NULL,
                ));

		        Breadcrumbs::add(Breadcrumb::factory()->set_title(__("Page ").$pagination->offset));
				
				$ads = $ads->order_by('published','desc')
							   ->limit($pagination->items_per_page)
			        	       ->offset($pagination->offset)
			        	       ->find_all();
			}
				
		}

		$this->template->bind('content', $content);

		$this->template->content = View::factory('pages/search', array('ads'		=> $ads, 
																		   'categories'	=> $cat_obj,
																		   'order_categories'=> $order_categories,
																		   'locations'	=> $loc_obj, 
																		   'order_locations'=>$order_locations,
																		   'pagination'	=> $pagination, 
																		   'user'		=> $user,
																		   'fields' 		=> Model_Field::get_all()));
        
		
	}

	
}// End ad controller

