<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Panel_Myads extends Auth_Frontcontroller {


	public function action_index()
	{
		$user = Auth::instance()->get_user();
		$ads = new Model_Ad();

		Controller::$full_width = TRUE;

		$my_adverts = $ads->where('id_user', '=', $user->id_user);

		$res_count = $my_adverts->count_all();

		if ($res_count > 0)
		{

			$pagination = Pagination::factory(array(
                    'view'           	=> 'pagination',
                    'total_items'    	=> $res_count,
                    'items_per_page' 	=> core::config('advertisement.advertisements_per_page')
     	    ))->route_params(array(
                    'controller' 		=> $this->request->controller(),
                    'action'      		=> $this->request->action(),

    	    ));

            $this->template->styles = array('//cdn.jsdelivr.net/sweetalert/1.1.3/sweetalert.css' => 'screen');

            $this->template->scripts['footer'] = array('//cdn.jsdelivr.net/sweetalert/1.1.3/sweetalert.min.js');

    	    Breadcrumbs::add(Breadcrumb::factory()->set_title(__('My ads'))->set_url(Route::url('oc-panel',array('controller'=>'myads','action'=>'index'))));
    	    Breadcrumbs::add(Breadcrumb::factory()->set_title(sprintf(__("Page %d"), $pagination->current_page)));
    	    $ads = $my_adverts->order_by('published','desc')
                	            ->limit($pagination->items_per_page)
                	            ->offset($pagination->offset)
                	            ->find_all();


          	$this->template->content = View::factory('oc-panel/profile/ads', array('ads'=>$ads,
          																		   'pagination'=>$pagination,
          																		   'user'=>$user));
        }
        else
        {

        	$this->template->content = View::factory('oc-panel/profile/ads', array('ads'=>$ads,
          																		   'pagination'=>NULL,
          																		   'user'=>$user));
        }
	}

	/**
	 * Mark advertisement as deactivated : STATUS = 50
	 */
	public function action_deactivate()
	{

		$deact_ad = new Model_Ad($this->request->param('id'));

		if ($deact_ad->loaded())
		{
			if(Auth::instance()->get_user()->id_user != $deact_ad->id_user)
            {
                Alert::set(Alert::ALERT, __("This is not your advertisement."));
                HTTP::redirect(Route::url('oc-panel',array('controller'=>'myads','action'=>'index')));
            }

            if ($deact_ad->deactivate())
			{
				Alert::set(Alert::SUCCESS, __('Advertisement is deactivated'));
                HTTP::redirect(Route::url('oc-panel',array('controller'=>'myads','action'=>'index')));
			}
			else
			{
				Alert::set(Alert::ALERT, __("Warning, Advertisement is already marked as 'deactivated'"));
				HTTP::redirect(Route::url('oc-panel',array('controller'=>'myads','action'=>'index')));
			}
		}
		else
		{
			//throw 404
			throw HTTP_Exception::factory(404,__('Page not found'));
		}

	}

    /**
     * delete advertisement
     */
    public function action_delete()
    {

        $delete_ad = new Model_Ad($this->request->param('id'));
        if (core::config('advertisement.delete_ad')==TRUE AND $delete_ad->loaded())
        {
            if(Auth::instance()->get_user()->id_user != $delete_ad->id_user)
            {
                Alert::set(Alert::ALERT, __("This is not your advertisement."));
                HTTP::redirect(Route::url('oc-panel',array('controller'=>'myads','action'=>'index')));
            }

            try {
                $delete_ad->delete();
                Alert::set(Alert::SUCCESS, __('Advertisement deleted'));
            } catch (Exception $e) {
                Alert::set(Alert::ERROR, __('Advertisement not deleted'));
            }

            HTTP::redirect(Route::url('oc-panel',array('controller'=>'myads','action'=>'index')));

        }
        else
        {
            //throw 404
            throw HTTP_Exception::factory(404,__('Page not found'));
        }

    }

	/**
	 * Mark advertisement as active : STATUS = 1
	 */

	public function action_activate()
	{
        $user = Auth::instance()->get_user();
		$id = $this->request->param('id');

		if (isset($id))
		{
			$active_ad = new Model_Ad($id);

			if ($active_ad->loaded())
			{
                $activate = FALSE;

                //admin whatever he wants
                if ($user->is_admin())
                {
                    $activate = TRUE;
                }
                //chekc user owner and if not moderation
                elseif ($user->id_user == $active_ad->id_user AND !in_array(core::config('general.moderation'), Model_Ad::$moderation_status) )
                {
                    $activate = TRUE;
                }
                else
                {
                    Alert::set(Alert::ALERT, __("This is not your advertisement."));
                }

                //its not published
                if ($active_ad->status == Model_Ad::STATUS_PUBLISHED)
                {
                    $activate = FALSE;
                    Alert::set(Alert::ALERT, __("Advertisement is already marked as 'active'"));
                }

                //expired but cannot reactivate option
                if((New Model_Field())->get('expiresat')
                    AND (Date::formatted_time($active_ad->cf_expiresat)
                        < Date::formatted_time())
                    AND $active_ad->published !== NULL
                ) {
                    $activate = FALSE;
                    Alert::set(Alert::ALERT, __("Advertisement can not be marked as “active”. It’s expired."));
                }
                elseif (Core::config('advertisement.expire_reactivation') == FALSE
                    AND core::config('advertisement.expire_date') > 0
                    AND (Date::formatted_time($active_ad->published.'+'.core::config('advertisement.expire_date').' days')
                        < Date::formatted_time()))
                {
                    $activate = FALSE;
                    Alert::set(Alert::ALERT, __("Advertisement can not be marked as “active”. It’s expired."));
                }

                //pending payment
                if ($activate === TRUE AND ($order = $active_ad->get_order()) !== FALSE AND $order->status == Model_Order::STATUS_CREATED )
                {
                    $activate = FALSE;
                    Alert::set(Alert::ALERT, __("Advertisement can not be marked as “active”. There is a pending payment."));
                }

                //expired subscription
                if ($this->user->expired_subscription())
                {
                    Alert::set(Alert::INFO, __('Please, choose a plan first'));
                    HTTP::redirect(Route::url('pricing'));
                }

                //activate the ad
                if ($activate === TRUE)
                {
                    // update publish date if ad was not published before or
                    //  was published and to_top is not enabled
                    if($active_ad->published == NULL OR
                        ($active_ad->published != NULL AND
                        (core::config('payment.pay_to_go_on_top') == 0 OR
                        core::config('payment.to_top') == FALSE)))
                    {
                        $active_ad->published = Date::unix2mysql();
                    }

                    $active_ad->status     = Model_Ad::STATUS_PUBLISHED;

                    try
                    {
                        $active_ad->save();
                    }
                    catch (Exception $e)
                    {
                        throw HTTP_Exception::factory(500,$e->getMessage());
                    }
                }
                //we dont activate something happened
                else
                {
                    HTTP::redirect(Route::url('oc-panel',array('controller'=>'myads','action'=>'index')));
                }

			}
			else
			{
				//throw 404
				throw HTTP_Exception::factory(404,__('Page not found'));
			}
		}


		// send confirmation email
		$cat = new Model_Category($active_ad->id_category);
		$usr = new Model_User($active_ad->id_user);
		if($usr->loaded())
		{
			//we get the QL, and force the regen of token for security
			$url_ql = $usr->ql('ad',array( 'category' => $cat->seoname,
		 	                                'seotitle'=> $active_ad->seotitle),TRUE);

			$ret = $usr->email('ads-activated',array('[USER.OWNER]'=>$usr->name,
													 '[URL.QL]'=>$url_ql,
													 '[AD.NAME]'=>$active_ad->title));
		}

		Alert::set(Alert::SUCCESS, __('Advertisement is active and published'));
		HTTP::redirect(Route::url('oc-panel',array('controller'=>'myads','action'=>'index')));
	}

    /**
     * Mark advertisement as deactivated : STATUS = 50 and set the stock equal to zero
     */
    public function action_sold()
    {

        $ad = new Model_Ad($this->request->param('id'));

        if ($ad->loaded())
        {
            if (Auth::instance()->get_user()->id_user != $ad->id_user)
            {
                Alert::set(Alert::ALERT, __("This is not your advertisement."));
                HTTP::redirect(Route::url('oc-panel',array('controller'=>'myads','action'=>'index')));
            }

            if ($this->request->post())
            {
                $validation = Validation::factory($this->request->post())
                    ->rule('amount', 'price');

                if ($validation->check())
                {
                    $id_product = Model_Order::PRODUCT_AD_SELL;
                    $amount     = Core::request('amount');
                    $currency   = $ad->currency();
                    $user       = Auth::instance()->get_user();

                    // New order and mark as paid
                    $order = Model_Order::new_order($ad, $user, $id_product, $amount, $currency, __('Purchase').': '.$ad->seotitle);
                    $order->confirm_payment('cash', sprintf('Done by user %d - %s', $user->id_user, $user->email));

                    if ($ad->sold())
                        Alert::set(Alert::SUCCESS, __('Advertisement is marked as Sold'));
                }
                else
                {
                    $errors = $validation->errors('config');

                    foreach ($errors as $error)
                        Alert::set(Alert::ALERT, $error);
                }

                HTTP::redirect(Route::url('oc-panel',array('controller'=>'myads','action'=>'index')));
            }

            Alert::set(Alert::ALERT, __("Warning, Advertisement is already marked as 'sold'"));
            HTTP::redirect(Route::url('oc-panel',array('controller'=>'myads','action'=>'index')));
        }

        //throw 404
        throw HTTP_Exception::factory(404,__('Page not found'));

    }

	/**
	 * Edit advertisement: Update
	 *
	 * All post fields are validated
	 */
	public function action_update()
	{
		//template header
		$this->template->title           	= __('Edit advertisement');
		$this->template->meta_description	= __('Edit advertisement');

		Controller::$full_width = TRUE;

		//local files
        if (Theme::get('cdn_files') == FALSE)
        {
            $this->template->styles = array('css/jquery.sceditor.default.theme.min.css' => 'screen',
                                            'css/dropzone.min.css' => 'screen',
                                            'css/jquery-ui-sortable.min.css' => 'screen',
                                            '//cdnjs.cloudflare.com/ajax/libs/selectize.js/0.12.1/css/selectize.bootstrap3.min.css' => 'screen',
                                            '//cdn.jsdelivr.net/sweetalert/1.1.3/sweetalert.css' => 'screen');

            $this->template->scripts['footer'] = array( 'js/jquery.sceditor.bbcode.min.js',
                                                        'js/jquery.sceditor.plaintext.min.js',
                                                        'js/dropzone.min.js',
                                                        Route::url('jslocalization', ['controller' => 'jslocalization', 'action' => 'dropzone']),
                                                        'js/jquery-ui-sortable.min.js',
                                                        '//cdn.jsdelivr.net/sweetalert/1.1.3/sweetalert.min.js',
                                                        '//cdnjs.cloudflare.com/ajax/libs/selectize.js/0.12.1/js/standalone/selectize.min.js',
                                                        'js/load-image.all.min.js',
                                                        '//cdn.jsdelivr.net/npm/exif-js',
                                                        'js/oc-panel/edit_ad.js');

            $this->template->scripts['async_defer'][] = '//maps.google.com/maps/api/js?libraries=geometry&v=3&key='.core::config("advertisement.gm_api_key").'&callback=initLocationsGMap&language='.i18n::get_gmaps_language(i18n::$locale);
            $this->template->scripts['async_defer'][] = '//apis.google.com/js/api.js?onload=onApiLoad';
        }
        else
        {
            $this->template->styles = array('css/jquery.sceditor.default.theme.min.css' => 'screen',
                                            'css/dropzone.min.css' => 'screen',
                                            'css/jquery-ui-sortable.min.css' => 'screen',
                                            '//cdnjs.cloudflare.com/ajax/libs/selectize.js/0.12.1/css/selectize.bootstrap3.min.css' => 'screen',
                                            '//cdn.jsdelivr.net/sweetalert/1.1.3/sweetalert.css' => 'screen');

            $this->template->scripts['footer'] = array( 'js/jquery.sceditor.bbcode.min.js',
                                                        'js/jquery.sceditor.plaintext.min.js',
                                                        'js/dropzone.min.js',
                                                        Route::url('jslocalization', ['controller' => 'jslocalization', 'action' => 'dropzone']),
                                                        'js/jquery-ui-sortable.min.js',
                                                        '//cdn.jsdelivr.net/sweetalert/1.1.3/sweetalert.min.js',
                                                        '//cdnjs.cloudflare.com/ajax/libs/selectize.js/0.12.1/js/standalone/selectize.min.js',
                                                        'js/load-image.all.min.js',
                                                        'js/oc-panel/edit_ad.js');

            $this->template->scripts['async_defer'][] = '//maps.google.com/maps/api/js?libraries=geometry&v=3&key='.core::config("advertisement.gm_api_key").'&callback=initLocationsGMap&language='.i18n::get_gmaps_language(i18n::$locale);
            $this->template->scripts['async_defer'][] = '//apis.google.com/js/api.js?onload=onApiLoad';
        }

        if (core::config('advertisement.cloudinary_cloud_name') AND core::config('advertisement.cloudinary_cloud_preset'))
            $this->template->scripts['footer'][] = 'https://widget.cloudinary.com/v2.0/global/all.js';

		Breadcrumbs::add(Breadcrumb::factory()->set_title(__('My ads'))->set_url(Route::url('oc-panel',array('controller'=>'myads','action'=>'index'))));

		$form = new Model_Ad($this->request->param('id'));

        if($form->loaded() AND (Auth::instance()->get_user()->id_user == $form->id_user
            OR Auth::instance()->get_user()->is_admin()
            OR Auth::instance()->get_user()->is_moderator()))
		{
            // deleting single image by path
            if(is_numeric($deleted_image = core::request('img_delete')))
            {
                $form->delete_image($deleted_image);

                $this->redirect(Route::url('oc-panel', array('controller'	=>'myads', 'action' =>'update', 'id' =>$form->id_ad)));
            }// end of img delete

            // set primary image
            if(is_numeric($primary_image = core::request('primary_image')))
            {
                $form->set_primary_image($primary_image);

                $this->redirect(Route::url('oc-panel', array('controller'   =>'myads', 'action' =>'update', 'id' =>$form->id_ad)));
            }

            // deleting single video by field name
            if($deleted_video = core::request('video_delete'))
            {
                $form->delete_video(core::request('video_delete'));

                $this->redirect(Route::url('oc-panel', array('controller'   =>'myads', 'action' =>'update', 'id' =>$form->id_ad)));
            }// end of video delete

            $original_category = $form->category;

			$extra_payment = core::config('payment');

			if ($this->request->post())
			{
                $data = $this->request->post();

                //to make it backward compatible with older themes: UGLY!!
                if (isset($data['category']) AND is_numeric($data['category']))
                {
                    $data['id_category'] = $data['category'];
                    unset($data['category']);
                }

                if (isset($data['location']) AND is_numeric($data['location']))
                {
                    $data['id_location'] = $data['location'];
                    unset($data['location']);
                }

                if(isset($data['cf_vatcountry']) AND $data['cf_vatcountry'] AND isset($data['cf_vatnumber']) AND $data['cf_vatnumber']){
                    if (!euvat::verify_vies($data['cf_vatnumber'],$data['cf_vatcountry']) AND euvat::is_eu_country($data['cf_vatcountry'])){
                        Alert::set(Alert::ERROR, __('Invalid EU Vat Number, please verify number and country match'));
                        $this->redirect(Route::url('oc-panel', array('controller'   =>'myads', 'action' =>'update', 'id' =>$form->id_ad)));                     }
                }

                $return = $form->save_ad($data);

                //there was an error on the validation
                if (isset($return['validation_errors']) AND is_array($return['validation_errors']))
                {
                    foreach ($return['validation_errors'] as $f => $err)
                        Alert::set(Alert::ALERT, $err);
                }
                //another error
                elseif (isset($return['error']))
                {
                    Alert::set($return['error_type'], $return['error']);
                }
                //success!!!
                elseif (isset($return['message']))
                {
                    // IMAGE UPLOAD
                    // in case something wrong happens user is redirected to edit advert.
                    $filename = NULL;

                    if (Core::post('ajax'))
                    {
                        for ($i = 0; $i < core::config('advertisement.num_images'); $i++)
                        {
                            $files = Arr::re_array_multiple_file_uploads($_FILES['file']);

                            if (isset($files[$i]))
                            {
                                $filename = $form->save_image($files[$i]);
                            }
                        }
                    }
                    else
                    {
                        for ($i = 0; $i < core::config("advertisement.num_images"); $i++)
                        {
                            if (Core::post('base64_image' . $i))
                                $filename = $form->save_base64_image(Core::post('base64_image' . $i));
                            elseif (isset($_FILES['image' . $i]))
                                $filename = $form->save_image($_FILES['image' . $i]);
                        }
                    }

                    if ($filename!==NULL)
                    {
                        $form->last_modified = Date::unix2mysql();
                        try
                        {
                            $form->save();
                        }
                        catch (Exception $e)
                        {
                            throw HTTP_Exception::factory(500,$e->getMessage());
                        }
                    }

                    Alert::set(Alert::SUCCESS, $return['message']);

                    //redirect user to pay
                    if (Core::post('ajax'))
                    {
                        if (isset($return['checkout_url']) AND !empty($return['checkout_url']))
                        {
                            $this->auto_render = FALSE;
                            $this->template = View::factory('js');
                            $this->response->headers('Content-Type', 'application/json');
                            $this->response->status('200');
                            $this->template->content = json_encode(['redirect_url' => $return['checkout_url']]);
                            return;
                        }
                    }
                    else
                    {
                        if (isset($return['checkout_url']) AND !empty($return['checkout_url']))
                            $this->redirect($return['checkout_url']);
                    }
                }

                if (Core::post('ajax'))
                {
                    $this->auto_render = FALSE;
                    $this->template = View::factory('js');
                    $this->response->headers('Content-Type', 'application/json');
                    $this->response->status('200');
                    $this->template->content = json_encode(['redirect_url' => Route::url('oc-panel', array('controller'	=>'mylistings', 'action' =>'update', 'id' =>$form->id_ad))]);

                    return;
                }

        		$this->redirect(Route::url('oc-panel', array('controller'	=>'myads', 'action' =>'update', 'id' =>$form->id_ad)));

			}

            //get all orders
            $orders = new Model_Order();
            $orders = $orders->where('id_user', '=', $form->id_user)
                            ->where('status','=',Model_Order::STATUS_CREATED)
                            ->where('id_ad','=',$form->id_ad)->find_all();

            Breadcrumbs::add(Breadcrumb::factory()->set_title(__('Update')));
            $this->template->content = View::factory('oc-panel/profile/edit_ad', array('ad'            => $form,
                                                                                       'extra_payment' => $extra_payment,
                                                                                       'orders'        => $orders));

		}
		else
		{
			Alert::set(Alert::ERROR, __('You dont have permission to access this link'));
			$this->redirect(Route::url('default'));
		}
	}

    /**
     * confirms the post of and advertisement
     * @return void
     */
    public function action_confirm()
    {
        $advert = new Model_Ad($this->request->param('id'));

        if($advert->loaded())
        {

            if(Auth::instance()->get_user()->id_user !== $advert->id_user)
            {
                Alert::set(Alert::ALERT, __("This is not your advertisement."));
                HTTP::redirect(Route::url('oc-panel',array('controller'=>'myads','action'=>'index')));
            }

            if(core::config('general.moderation') == Model_Ad::EMAIL_CONFIRMATION)
            {

                if (Core::config('general.subscriptions') == TRUE AND
                        $advert->user->subscription()->loaded() AND
                        $advert->user->subscription()->amount_ads_left <= 0 AND
                        $advert->user->subscription()->amount_ads_left != -1  )
                {
                    Alert::set(Alert::WARNING, sprintf(__('You do not have more ads left to publish.'),$active_ad->email));
                    HTTP::redirect(Route::url('pricing'));
                }
                else
                {
                    $advert->status = Model_Ad::STATUS_PUBLISHED; // status active
                    $advert->published = Date::unix2mysql();

                    try
                    {
                        $advert->save();
                        Model_Subscription::new_ad($advert->user);
                        Model_Subscribe::notify($advert);

                        // Post on social media
                        Social::post_ad($advert);

                        Alert::set(Alert::INFO, __('Your advertisement is successfully activated! Thank you!'));
                    }
                    catch (Exception $e)
                    {
                        throw HTTP_Exception::factory(500,$e->getMessage());
                    }
                }
            }
            elseif(core::config('general.moderation') == Model_Ad::EMAIL_MODERATION)
            {
                $advert->status = Model_Ad::STATUS_NOPUBLISHED;

                try
                {
                    $advert->save();
                    Alert::set(Alert::INFO, __('Advertisement is received, but first administrator needs to validate. Thank you for being patient!'));
                }
                catch (Exception $e)
                {
                    throw HTTP_Exception::factory(500,$e->getMessage());
                }

                $this->redirect(Route::url('oc-panel',array('controller'=>'myads','action'=>'index')));
            }

            $this->redirect(Route::url('ad', array('category'=>$advert->category->seoname, 'seotitle'=>$advert->seotitle)));
        }

    }

	public function action_stats()
   	{
        Breadcrumbs::add(Breadcrumb::factory()->set_title(__('My ads'))->set_url(Route::url('oc-panel',array('controller'=>'myads','action'=>'index'))));
        Breadcrumbs::add(Breadcrumb::factory()->set_title(__('Stats')));

        Controller::$full_width = TRUE;

        $this->template->scripts['footer'] = array('js/chart.min.js', 'js/chart.js-php.js', 'js/oc-panel/stats/dashboard.js');

        $this->template->title = __('Stats');
        $this->template->bind('content', $content);
        $content = View::factory('oc-panel/profile/stats');

        $list_ad = array();
        $advert  = new Model_Ad();

        //single stats for 1 ad
        if (is_numeric($id_ad = $this->request->param('id')))
        {
            $advert = new Model_Ad($id_ad);

            if($advert->loaded())
            {
                //if admin or moderator user is the advert user ;) hack!!
                if($this->user->is_admin() OR $this->user->is_moderator())
                    $user = $advert->user;
                else
                    $user  = $this->user;

                if($user->id_user !== $advert->id_user)
                {
                    Alert::set(Alert::ALERT, __("This is not your advertisement."));
                    HTTP::redirect(Route::url('oc-panel',array('controller'=>'myads','action'=>'index')));
                }

                Breadcrumbs::add(Breadcrumb::factory()->set_title($advert->title));

                // make a list of 1 ad (array), and than pass this array to query (IN).. To get correct visits
                $list_ad[] = $id_ad;
            }
        }

        //we didnt filter by ad, so lets get them all!
        if (empty($list_ad))
        {
            $ads = new Model_Ad();
            $collection_of_user_ads = $ads->where('id_user', '=', $this->user->id_user)->find_all();

            $list_ad = array();
            foreach ($collection_of_user_ads as $key) {
                // make a list of ads (array), and than pass this array to query (IN).. To get correct visits
                $list_ad[] = $key->id_ad;
            }
        }

        // if user doesn't have any ads
        if(empty($list_ad))
            $list_ad = array(NULL);

        $content->advert = $advert;


        //Getting the dates and range
        $from_date = Core::post('from_date',strtotime('-1 month'));
        $to_date   = Core::post('to_date',time());

        //we assure is a proper time stamp if not we transform it
        if (is_string($from_date) === TRUE)
            $from_date = strtotime($from_date);
        if (is_string($to_date) === TRUE)
            $to_date   = strtotime($to_date);

        //mysql formated dates
        $my_from_date = Date::unix2mysql($from_date);
        $my_to_date   = Date::unix2mysql($to_date);

        //dates range we are filtering
        $dates     = Date::range($from_date, $to_date,'+1 day','Y-m-d',array('date'=>0,'count'=> 0),'date');

        //dates displayed in the form
        $content->from_date = date('Y-m-d',$from_date);
        $content->to_date   = date('Y-m-d',$to_date) ;


        /////////////////////CONTACT STATS////////////////////////////////

        //visits created last XX days
        $query = DB::select(DB::expr('created date'))
                        ->select(DB::expr('SUM(contacts) count'))
                        ->from('visits')
                        ->where('id_ad', 'in', $list_ad)
                        ->where('created','between',array($my_from_date,$my_to_date))
                        ->group_by('created')
                        ->order_by('date','asc')
                        ->execute();

        $contacts_dates = $query->as_array('date');

        //Today
        $query = DB::select(DB::expr('SUM(contacts) count'))
                        ->from('visits')
                        ->where('id_ad', 'in', $list_ad)
                        ->where('created','=',DB::expr('CURDATE()'))
                        ->group_by('created')
                        ->order_by('created','asc')
                        ->execute();

        $contacts = $query->as_array();
        $content->contacts_today     = (isset($contacts[0]['count']))?$contacts[0]['count']:0;

        //Yesterday
        $query = DB::select(DB::expr('SUM(contacts) count'))
                        ->from('visits')
                        ->where('id_ad', 'in', $list_ad)
                        ->where('created','=',date('Y-m-d',strtotime('-1 day')))
                        ->group_by('created')
                        ->order_by('created','asc')
                        ->execute();

        $contacts = $query->as_array();
        $content->contacts_yesterday = (isset($contacts[0]['count']))?$contacts[0]['count']:0; //

        //Last 30 days contacts
        $query = DB::select(DB::expr('SUM(contacts) count'))
                        ->from('visits')
                        ->where('id_ad', 'in', $list_ad)
                        ->where('created','between',array(date('Y-m-d',strtotime('-30 day')),date::unix2mysql()))
                        ->execute();

        $contacts = $query->as_array();
        $content->contacts_month = (isset($contacts[0]['count']))?$contacts[0]['count']:0;

        //total contacts
        $query = DB::select(DB::expr('SUM(contacts) count'))
                        ->where('id_ad', 'in', $list_ad)
                        ->from('visits')
                        ->execute();

        $contacts = $query->as_array();
        $content->contacts_total = (isset($contacts[0]['count']))?$contacts[0]['count']:0;

        /////////////////////VISITS STATS////////////////////////////////

        //visits created last XX days
        $query = DB::select(DB::expr('created date'))
                        ->select(DB::expr('SUM(hits) count'))
                        ->from('visits')
                        ->where('id_ad', 'in', $list_ad)
                        ->where('created','between',array($my_from_date,$my_to_date))
                        ->group_by('created')
                        ->order_by('date','asc')
                        ->execute();

        $visits = $query->as_array('date');

        $stats_daily = array();
        foreach ($dates as $date)
        {
            $count_contants = (isset($contacts_dates[$date['date']]['count']))?$contacts_dates[$date['date']]['count']:0;
            $count_visits = (isset($visits[$date['date']]['count']))?$visits[$date['date']]['count']:0;

            $stats_daily[] = array('date'=>$date['date'],'views'=> $count_visits, 'contacts'=>$count_contants);
        }

        $content->stats_daily = $stats_daily;

        //Today
        $query = DB::select(DB::expr('SUM(hits) count'))
                        ->from('visits')
                        ->where('id_ad', 'in', $list_ad)
                        ->where('created','=',DB::expr('CURDATE()'))
                        ->group_by('created')
                        ->order_by('created','asc')
                        ->execute();

        $visits = $query->as_array();
        $content->visits_today     = (isset($visits[0]['count']))?$visits[0]['count']:0;

        //Yesterday
        $query = DB::select(DB::expr('SUM(hits) count'))
                        ->from('visits')
                        ->where('id_ad', 'in', $list_ad)
                        ->where('created','=',date('Y-m-d',strtotime('-1 day')))
                        ->group_by('created')
                        ->order_by('created','asc')
                        ->execute();

        $visits = $query->as_array();
        $content->visits_yesterday = (isset($visits[0]['count']))?$visits[0]['count']:0;


        //Last 30 days visits
        $query = DB::select(DB::expr('SUM(hits) count'))
                        ->from('visits')
                        ->where('id_ad', 'in', $list_ad)
                        ->where('created','between',array(date('Y-m-d',strtotime('-30 day')),date::unix2mysql()))
                        ->execute();

        $visits = $query->as_array();
        $content->visits_month = (isset($visits[0]['count']))?$visits[0]['count']:0;

        //total visits
        $query = DB::select(DB::expr('SUM(hits) count'))
                        ->where('id_ad', 'in', $list_ad)
                        ->from('visits')
                        ->execute();

        $visits = $query->as_array();
        $content->visits_total = (isset($visits[0]['count']))?$visits[0]['count']:0;

    }

}
