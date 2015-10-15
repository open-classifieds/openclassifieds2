<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Panel_Profile extends Auth_Frontcontroller {

    

	public function action_index()
	{
		Breadcrumbs::add(Breadcrumb::factory()->set_title(__('Home')));
		
		$this->template->title = __('Home');
		//$this->template->scripts['footer'][] = 'js/user/index.js';
		$this->template->content = View::factory('oc-panel/home-user');
	}


	public function action_changepass()
	{
		Breadcrumbs::add(Breadcrumb::factory()->set_title(__('Change password')));
		
		$this->template->title   = __('Change password');

		$user = Auth::instance()->get_user();

		$this->template->bind('content', $content);
		$this->template->content = View::factory('oc-panel/profile/edit',array('user'=>$user,'custom_fields'=>Model_UserField::get_all()));
		$this->template->content->msg ='';

		if ($this->request->post())
		{
			$user = Auth::instance()->get_user();
			
			if (core::post('password1')==core::post('password2'))
			{
				$new_pass = core::post('password1');
				if(!empty($new_pass)){

					$user->password = core::post('password1');
                    $user->last_modified = Date::unix2mysql();
                    
					try
					{
						$user->save();
					}
					catch (ORM_Validation_Exception $e)
					{
						throw HTTP_Exception::factory(500,$e->getMessage());
					}
					catch (Exception $e)
					{
						throw HTTP_Exception::factory(500,$e->getMessage());
					}

					Alert::set(Alert::SUCCESS, __('Password is changed'));
				}
				else
				{
					Form::set_errors(array(__('Nothing is provided')));
				}
			}
			else
			{
				Form::set_errors(array(__('Passwords do not match')));
			}
			
		}

	  
	}

	public function action_image()
	{
        $user = Auth::instance()->get_user();

        if (Core::post('photo_delete') AND $user->delete_image()==TRUE )
            Alert::set(Alert::SUCCESS, __('Photo deleted.'));
        else
        {
            //get image
            $image = $_FILES['profile_image']; //file post

            $result = $user->upload_image($image);

            if ($result === TRUE)
                Alert::set(Alert::SUCCESS, $image['name'].' '.__('Image is uploaded.'));
            else 
                Alert::set(Alert::ALERT,$result);
        }

        $this->redirect(Route::url('oc-panel',array('controller'=>'profile', 'action'=>'edit')));
	}

	public function action_edit()
	{
        $this->template->scripts['footer'] = array('js/oc-panel/edit_profile.js');

		Breadcrumbs::add(Breadcrumb::factory()->set_title(__('Edit profile')));
		// $this->template->title = $user->name;
		//$this->template->meta_description = $user->name;//@todo phpseo
		$user = Auth::instance()->get_user();

		$this->template->bind('content', $content);
		$this->template->content = View::factory('oc-panel/profile/edit',array('user'=>$user,'custom_fields'=>Model_UserField::get_all()));

		if($this->request->post())
		{
			
			$user->name = core::post('name');
            $user->description = core::post('description');
			$user->email = core::post('email');
			$user->subscriber = core::post('subscriber',0);
			//$user->seoname = $user->gen_seo_title(core::post('name'));
            $user->last_modified = Date::unix2mysql();

            //modify custom fields
            foreach ($this->request->post() as $custom_field => $value) 
            {
                if (strpos($custom_field,'cf_')!==FALSE)
                {
                    $user->$custom_field = $value;
                }
            }

			try {
				$user->save();
				Alert::set(Alert::SUCCESS, __('You have successfully changed your data'));				
			} catch (Exception $e) {
				//throw 500
				throw HTTP_Exception::factory(500,$e->getMessage());
			}	

            $this->redirect(Route::url('oc-panel', array('controller'=>'profile','action'=>'edit')));
		}
	}

    public function action_orders()
    {
        $user = Auth::instance()->get_user();

        $this->template->title = __('My payments');
        Breadcrumbs::add(Breadcrumb::factory()->set_title(__('My payments')));
        Controller::$full_width = TRUE;

        $orders = new Model_Order();
        $orders = $orders->where('id_user', '=', $user->id_user);


        $pagination = Pagination::factory(array(
                    'view'           => 'oc-panel/crud/pagination',
                    'total_items'    => $orders->count_all(),
        ))->route_params(array(
                    'controller' => $this->request->controller(),
                    'action'     => $this->request->action(),
        ));

        $pagination->title($this->template->title);

        $orders = $orders->order_by('created','desc')
        ->limit($pagination->items_per_page)
        ->offset($pagination->offset)
        ->find_all();

        $pagination = $pagination->render();

        $this->template->bind('content', $content);
        $this->template->content = View::factory('oc-panel/profile/orders', array('orders' => $orders,'pagination'=>$pagination));

        
    }
   /**
    * list all subscription for a given user
    * @return view 
    */ 
   public function action_subscriptions()
   {
        $this->template->title = __('My subscriptions');
        Breadcrumbs::add(Breadcrumb::factory()->set_title(__('My subscriptions')));

   		Controller::$full_width = TRUE;

   		$subscriptions = new Model_Subscribe();

   		$user = Auth::instance()->get_user()->id_user;

		//get all for this user
		$query = $subscriptions->where('id_user','=',$user)
							   ->find_all();

   		if(count($query) != 0)
   		{
   			// get categories, location, date, and price range to show in view 					   
   			

			$subs = $query->as_array();
			foreach ($subs as $s) 
			{

				$min_price = $s->min_price;
				$max_price = $s->max_price;
				$created   = $s->created;

				$category = new Model_Category($s->id_category);
				$location = new Model_Location($s->id_location);

				$list[] = array('min_price'=>$min_price,
								'max_price'=>$max_price,
								'created'=>$created,
								'category'=>$category->name,
								'location'=>$location->name,
								'id'=>$s->id_subscribe);
			}
			
			$this->template->content = View::factory('oc-panel/profile/subscriptions', array('list'=>$list));
   		}
   		else
   		{
   			Alert::set(Alert::INFO, __('No Subscriptions'));
   		}
    }

	public function action_unsubscribe()
	{
		$id_subscribe = $this->request->param('id');

		$subscription = new Model_Subscribe($id_subscribe);

		if($subscription->loaded() AND $subscription->id_user == Auth::instance()->get_user()->id_user)
		{
			try 
			{
				$subscription->delete();
				Alert::set(Alert::SUCCESS, __('You are unsubscribed'));
			} 
			catch (Exception $e) 
			{
				throw HTTP_Exception::factory(500,$e->getMessage());
			}

            $this->redirect(Route::url('oc-panel', array('controller'=>'profile','action'=>'subscriptions')));
		}
	}

    public function action_favorites()
    {
        $user = Auth::instance()->get_user();

        //favs or unfavs
        if (is_numeric($id_ad = $this->request->param('id')))
        {
            $this->auto_render = FALSE;
            $this->template = View::factory('js');

            $ad = new Model_Ad($id_ad);
            //ad exists
            if ($ad->loaded())
            {   
                //if fav exists we delete
                if (Model_Favorite::unfavorite($user->id_user,$id_ad)===TRUE)
                {
                    //fav existed deleting
                    $this->template->content = __('Deleted');
                }
                else
                {
                    //create the fav
                    Model_Favorite::favorite($user->id_user,$id_ad);
                    $this->template->content = __('Saved');
                }
            }
            else
                $this->template->content = __('Ad Not Found');
            
        }
        else
        {
            $this->template->title = __('My Favorites');
            Breadcrumbs::add(Breadcrumb::factory()->set_title($this->template->title));
            Controller::$full_width = TRUE;
            
            $this->template->styles = array('//cdn.jsdelivr.net/sweetalert/0.1.2/sweet-alert.min.css' => 'screen');
            
            $this->template->scripts['footer'][] = '//cdn.jsdelivr.net/sweetalert/0.1.2/sweet-alert.min.js';
            $this->template->scripts['footer'][] = 'js/oc-panel/favorite.js';

            $favorites = new Model_Favorite();
            $favorites = $favorites->where('id_user', '=', $user->id_user)
                            ->order_by('created','desc')
                            ->find_all();

            $this->template->bind('content', $content);
            $this->template->content = View::factory('oc-panel/profile/favorites', array('favorites' => $favorites));
        }
    }
    
    public function action_notifications()
    {
        $this->auto_render = FALSE;
        $this->template = View::factory('js');
        
        $user = Auth::instance()->get_user();
        $user->notification_date = Date::unix2mysql();
        $user->save();
        
        $this->template->content = __('Saved');
    }

   /**
    * redirects to public profile, we use it so we can cache the view and redirect them
    * @return redirect 
    */ 
   public function action_public()
   {
        $this->redirect(Route::url('profile',array('seoname'=>Auth::instance()->get_user()->seoname)));
   }


   /**
    * all this functions are only redirect, just in case we missed any link or if they got the link via email so keeps working.
    * now all in myads controller
    */
   
    public function action_ads()
    {
        $this->redirect(Route::url('oc-panel',array('controller'=>'myads','action'=>'index')));
    }

    public function action_deactivate()
    {
        $this->redirect(Route::url('oc-panel',array('controller'=>'myads','action'=>'deactivate','id'=>$this->request->param('id'))));
    }


    public function action_activate()
    {
        $this->redirect(Route::url('oc-panel',array('controller'=>'myads','action'=>'activate','id'=>$this->request->param('id'))));
    }

    public function action_update()
    {
        $this->redirect(Route::url('oc-panel',array('controller'=>'myads','action'=>'update','id'=>$this->request->param('id'))));
    }

    public function action_confirm()
    {
        $this->redirect(Route::url('oc-panel',array('controller'=>'myads','action'=>'confirm','id'=>$this->request->param('id'))));
    }

    public function action_stats()
    {
        if (is_numeric($id_ad = $this->request->param('id')))
            $this->redirect(Route::url('oc-panel',array('controller'=>'myads','action'=>'stats','id'=>$id_ad)));           
        else
            $this->redirect(Route::url('oc-panel',array('controller'=>'myads','action'=>'stats')));
    }


}
