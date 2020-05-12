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

        $this->template->styles = ['//cdnjs.cloudflare.com/ajax/libs/selectize.js/0.12.1/css/selectize.bootstrap3.min.css' => 'screen'];
        $this->template->scripts['footer'] = ['js/oc-panel/edit_profile.js','//cdnjs.cloudflare.com/ajax/libs/selectize.js/0.12.1/js/standalone/selectize.min.js'];

        Breadcrumbs::add(Breadcrumb::factory()->set_title(__('Change password')));

        $this->template->title   = __('Change password');

        $user = Auth::instance()->get_user();

        $id_location = ($user->id_location!==null)?$user->id_location:null;
        $selected_location = new Model_Location();

        // if user set his location already
        if ($id_location!==NULL)
        {
            if (is_numeric($id_location))
                $selected_location->where('id_location','=',$id_location)->limit(1)->find();
            else
                $selected_location->where('seoname','=',$id_location)->limit(1)->find();

            if ($selected_location->loaded())
                $id_location = $selected_location->id_location;
        }

        $this->template->bind('content', $content);
        $this->template->content = View::factory('oc-panel/profile/edit',array(
                                                    'user'=>$user,
                                                    'custom_fields'=>Model_UserField::get_all(),
                                                    'id_location'=>$user->id_location,
                                                    'selected_location'=>$selected_location));
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
                        throw HTTP_Exception::factory(500,$e->errors(''));
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

        // Delete image
        if (is_numeric($deleted_image = core::request('img_delete')))
        {
            $user->delete_image($deleted_image);
            Alert::set(Alert::SUCCESS, __('Image is deleted.'));
            $this->redirect(Route::url('oc-panel',array('controller'=>'profile', 'action'=>'edit')));
        }

        // Set primary image
        if (is_numeric($primary_image = core::request('primary_image')))
        {
            $user->set_primary_image($primary_image);
            $this->redirect(Route::url('oc-panel',array('controller'=>'profile', 'action'=>'edit')));
        }

        // Image upload
        $filename = NULL;

        for ($i=0; $i < core::config("advertisement.num_images"); $i++)
        {
            if (Core::post('base64_image'.$i))
                $filename = $user->save_base64_image(Core::post('base64_image'.$i));
            elseif (isset($_FILES['image'.$i]))
                $filename = $user->save_image($_FILES['image'.$i]);
        }
        if ($filename !== NULL)
        {
            $user->last_modified = Date::unix2mysql();

            try
            {
                $user->save();
            }
            catch (Exception $e)
            {
                throw HTTP_Exception::factory(500,$e->getMessage());
            }

            Alert::set(Alert::SUCCESS, __('Image is uploaded.'));
            $this->redirect(Route::url('oc-panel',array('controller'=>'profile', 'action'=>'edit')));
        }

        $this->redirect(Route::url('oc-panel',array('controller'=>'profile', 'action'=>'edit')));
	}

	public function action_edit()
    {
        $this->template->styles = ['css/jasny-bootstrap.min.css' => 'screen', '//cdnjs.cloudflare.com/ajax/libs/selectize.js/0.12.1/css/selectize.bootstrap3.min.css' => 'screen'];
        $this->template->scripts['footer'] = ['js/jasny-bootstrap.min.js', 'js/canvasResize.js', 'js/load-image.all.min.js', 'js/oc-panel/edit_profile.js','//cdnjs.cloudflare.com/ajax/libs/selectize.js/0.12.1/js/standalone/selectize.min.js'];

        if(core::config('advertisement.map_pub_new'))
        {
            $this->template->scripts['async_defer'][] = '//maps.google.com/maps/api/js?libraries=geometry&v=3&key='.core::config("advertisement.gm_api_key").'&callback=initLocationsGMap&language='.i18n::get_gmaps_language(i18n::$locale);
        }

        Breadcrumbs::add(Breadcrumb::factory()->set_title(__('Edit profile')));
        // $this->template->title = $user->name;
        //$this->template->meta_description = $user->name;//@todo phpseo
        $user = Auth::instance()->get_user();

        //get locations
        $locations = new Model_Location;
        $locations = $locations->where('id_location', '!=', '1');

        $id_location = ($user->id_location!==null)?$user->id_location:null;
        $selected_location = new Model_Location();

        // if user set his location already
        if ($id_location!==NULL)
        {
            if (is_numeric($id_location))
                $selected_location->where('id_location','=',$id_location)->limit(1)->find();
            else
                $selected_location->where('seoname','=',$id_location)->limit(1)->find();

            if ($selected_location->loaded())
                $id_location = $selected_location->id_location;
        }

        $this->template->bind('content', $content);
        $this->template->content = View::factory('oc-panel/profile/edit',array(
                            'user'=>$user,
                            'custom_fields'=>Model_UserField::get_all(),
                            'id_location'=>$id_location,
                            'selected_location'=>$selected_location
                            ));

        if($this->request->post())
        {
            //change elastic email status, he was subscribed but not anymore
            if ( Core::config('email.elastic_listname')!=''  AND $user->subscriber == 1 AND core::post('subscriber',0) == 0 )
                ElasticEmail::unsubscribe(Core::config('email.elastic_listname'),$user->email);
            elseif ( Core::config('email.elastic_listname')!=''  AND $user->subscriber == 0 AND core::post('subscriber',0) == 1 )
                ElasticEmail::subscribe(Core::config('email.elastic_listname'),$user->email,$user->name);

            $user->name = core::post('name');
            $user->description = core::post('description');
            $user->email = core::post('email');
            $user->subscriber = core::post('subscriber',0);
            $user->phone = core::post('phone');
            $user->id_location = core::post('location');
            $user->address = core::post('address');
            $user->latitude = core::post('latitude');
            $user->longitude = core::post('longitude');

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

            if(core::post('cf_vatnumber') AND core::post('cf_vatcountry'))
            {
                if (!euvat::verify_vies(core::post('cf_vatnumber'),core::post('cf_vatcountry')) AND euvat::is_eu_country(core::post('cf_vatcountry')))
                {
                    Alert::set(Alert::ERROR, __('Invalid EU Vat Number, please verify number and country match'));
                    $this->redirect(Route::url('oc-panel', array('controller'=>'profile','action'=>'edit')));
                }
            }

            try {
                $user->save();
                Alert::set(Alert::SUCCESS, __('You have successfully changed your data'));
            } catch (ORM_Validation_Exception $e) {
                $errors = $e->errors('models');
                foreach ($errors as $f => $err)
                    {
                    Alert::set(Alert::ALERT, $err);
                }
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
                    'view'           => 'pagination',
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

    public function action_order()
    {
        Breadcrumbs::add(Breadcrumb::factory()->set_title(__('My Purchases'))->set_url(Route::url('oc-panel',array('controller'=>'profile','action'=>'orders'))));
        Breadcrumbs::add(Breadcrumb::factory()->set_title(__('Order')));
        $this->template->title   = __('View Order');

        $user = Auth::instance()->get_user();
        $id_order = $this->request->param('id');

        $order = new Model_Order;
        $order->where('id_order', '=', $id_order);

        //if admin we do not verify the user
        if ($user->id_role!=Model_Role::ROLE_ADMIN)
            $order->where('id_user','=',$user->id_user);

        $order->find();

        if( ! $order->loaded() )
        {
            Alert::set(ALERT::WARNING, __('Order could not be loaded'));
            $this->redirect(Route::url('oc-panel',array('controller'=>'profile','action'=>'orders')));
        }

        $this->template->bind('content', $content);
        $this->template->content = View::factory('oc-panel/profile/order');

        $content->order = $order;
        $content->product = $order->id_product;
        $content->user = $user;

        if(core::get('print') == 1)
        {
            $this->template->scripts['footer'] = array('js/oc-panel/order.js');
        }

    }

    public function action_sales()
    {
        //check pay to featured top is enabled check stripe config too
        if(core::config('payment.paypal_seller') == FALSE AND Core::config('payment.stripe_connect')==FALSE  AND Core::config('payment.escrow_pay')==FALSE)
            throw HTTP_Exception::factory(404,__('Page not found'));

        $user = Auth::instance()->get_user();

        $this->template->title = __('My sales');
        Breadcrumbs::add(Breadcrumb::factory()->set_title(__('My sales')));
        Controller::$full_width = TRUE;

        $orders = new Model_Order();
        $orders = $orders->join('ads')
                        ->using('id_ad')
                        ->where('order.status','=',Model_Order::STATUS_PAID)
                        ->where('order.id_product','=',Model_Order::PRODUCT_AD_SELL)
                        ->where('ads.id_user', '=', $user->id_user);


        $pagination = Pagination::factory(array(
                    'view'           => 'pagination',
                    'total_items'    => $orders->count_all(),
        ))->route_params(array(
                    'controller' => $this->request->controller(),
                    'action'     => $this->request->action(),
        ));

        $pagination->title($this->template->title);

        $orders = $orders->order_by('pay_date','desc')
        ->limit($pagination->items_per_page)
        ->offset($pagination->offset)
        ->find_all();

        $pagination = $pagination->render();

        $this->template->bind('content', $content);
        $this->template->content = View::factory('oc-panel/profile/sales', array('orders' => $orders,'pagination'=>$pagination));


    }

   /**
    * list all subscription for a given user
    * @return view
    */
    public function action_subscriptions()
    {
        $this->template->title = __('My subscriptions');
        $this->template->styles = array('//cdn.jsdelivr.net/sweetalert/1.1.3/sweetalert.css' => 'screen');
        $this->template->scripts['footer'][] = '//cdn.jsdelivr.net/sweetalert/1.1.3/sweetalert.min.js';

        Breadcrumbs::add(Breadcrumb::factory()->set_title(__('My subscriptions')));

   		Controller::$full_width = TRUE;

   		$subscriptions = (new Model_Subscribe())
            ->where('id_user', '=', $this->user->id_user);

        $total_subscriptions = $subscriptions->count_all();

        $pagination = NULL;

        if ($total_subscriptions > 0)
        {
            $pagination = Pagination::factory([
                'view' => 'pagination',
                'total_items' => $total_subscriptions,
            ])->route_params([
                'controller' => $this->request->controller(),
                'action'     => $this->request->action(),
            ]);

            $subscriptions = $subscriptions
                ->limit($pagination->items_per_page)
                ->offset($pagination->offset)
                ->find_all();
        }
        else
        {
            Alert::set(Alert::INFO, __('No Subscriptions'));
        }

        if ($this->user->subscriber == 0)
        {
            Alert::set(Alert::INFO,  __('You can not receive emails. Enable it in your profile.'));
        }

        $this->template->content = View::factory('oc-panel/profile/subscriptions', compact('subscriptions', 'pagination'));
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

            //unsusbcribe from elasticemail
            if ( Core::config('email.elastic_listname')!='' )
                ElasticEmail::unsubscribe(Core::config('email.elastic_listname'),Auth::instance()->get_user()->email);

            $this->redirect(Route::url('oc-panel', array('controller'=>'profile','action'=>'subscriptions')));
		}
	}

    /**
     * removes the stripe agreement
     * @return [type] [description]
     */
    public function action_cancelsubscription()
    {

        if ( $this->user->stripe_agreement != NULL )
        {
            $this->user->stripe_agreement = NULL;
            try {
                $this->user->save();
                Alert::set(Alert::SUCCESS, __('You have successfully canceled your subscription.'));
            } catch (Exception $e) {
                //throw 500
                throw HTTP_Exception::factory(500,$e->getMessage());
            }
        }

        $this->redirect(Route::url('oc-panel',array('controller'=>'profile', 'action'=>'edit')));
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

            $this->template->styles = array('//cdn.jsdelivr.net/sweetalert/1.1.3/sweetalert.css' => 'screen');

            $this->template->scripts['footer'][] = '//cdn.jsdelivr.net/sweetalert/1.1.3/sweetalert.min.js';
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

    //2 step auth verification code generation
    public function action_2step()
    {
        $action = $this->request->param('id');

        if ($action == 'enable')
        {
            //load library
            require Kohana::find_file('vendor', 'GoogleAuthenticator');
            $ga = new PHPGangsta_GoogleAuthenticator();

            if (core::post('code') AND CSRF::valid('2step'))
            {
                if ($ga->verifyCode(Session::instance()->get('ga_secret_temp'), core::post('code'), 2))
                {
                    $this->user->google_authenticator = Session::instance()->get('ga_secret_temp');
                    //set cookie
                    Cookie::set('google_authenticator' , $this->user->id_user, Core::config('auth.lifetime') );
                    Alert::set(Alert::SUCCESS, __('2 Step Authentication Enabled'));

                    try {
                        $this->user->save();
                    } catch (Exception $e) {
                        //throw 500
                        throw HTTP_Exception::factory(500,$e->getMessage());
                    }
                    $this->redirect(Route::url('oc-panel', array('controller'=>'profile','action'=>'edit')));
                }
                else
                    Form::set_errors(array(__('Invalid Code')));
            }
            elseif( Session::instance()->get('ga_secret_temp') == NULL )
                Session::instance()->set('ga_secret_temp',$ga->createSecret());

            //template header
            $this->template->title            = __('2 Step Authentication');
            $this->template->content = View::factory('pages/auth/2step',array('form_action'=>Route::url('oc-panel',array('controller'=>'profile','action'=>'2step','id'=>'enable'))));
        }
        elseif($action == 'disable')
        {
            $this->user->google_authenticator = '';
            Cookie::delete('google_authenticator');
            Alert::set(Alert::INFO, __('2 Step Authentication Disabled'));
            try {
                $this->user->save();
            } catch (Exception $e) {
                //throw 500
                throw HTTP_Exception::factory(500,$e->getMessage());
            }

            $this->redirect(Route::url('oc-panel', array('controller'=>'profile','action'=>'edit')));
        }
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
