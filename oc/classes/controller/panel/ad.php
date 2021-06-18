<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Panel_Ad extends Auth_Controller {

	public function __construct($request, $response)
	{
		parent::__construct($request, $response);

		Breadcrumbs::add(Breadcrumb::factory()->set_title(__('Ads'))->set_url(Route::url('oc-panel',array('controller'  => 'ad'))));
	}

   	/**
   	 * List all Advertisements (PUBLISHED)
   	 */
	public function action_index()
	{
		//template header
		$this->template->title           	= __('Advertisements');
		$this->template->meta_description	= __('Advertisements');
		Breadcrumbs::add(Breadcrumb::factory()->set_title(__('List')));

		$this->template->scripts['footer'][]= 'js/jquery.toolbar.js';
		$this->template->scripts['footer'][]= 'js/oc-panel/moderation.js';


		$ads = new Model_Ad();

        $fields = array('title','id_ad','published','created','id_category', 'id_location','status');

        //filter ads by status
        if($status = Core::get('status') AND is_numeric($status))
        {
        	$ads->where('status', '=', $status);
        }

		//filter = active
        if((New Model_Field())->get('expiresat') AND Core::get('filter')=='active')
        {
            $ads->where_open()
            ->or_where(DB::expr('DATE(cf_expiresat)'), '>', Date::unix2mysql())
            ->or_where('cf_expiresat','IS',NULL)
            ->where_close();
        }
        elseif(core::config('advertisement.expire_date') > 0 AND Core::get('filter')=='active')
        {
            $ads->where(DB::expr('DATE_ADD( published, INTERVAL '.core::config('advertisement.expire_date').' DAY)'), '>', Date::unix2mysql());
        }

        //filter = expired
        if((New Model_Field())->get('expiresat') AND Core::get('filter')=='expired')
        {
            $ads->where_open()
           	->or_where(DB::expr('DATE(cf_expiresat)'), '>', Date::unix2mysql())
            ->or_where('cf_expiresat','IS',NULL)
            ->where_close();
        }
        elseif(core::config('advertisement.expire_date') > 0 AND Core::get('filter')=='expired')
        {
            $ads->where(DB::expr('DATE_ADD( published, INTERVAL '.core::config('advertisement.expire_date').' DAY)'), '<', Date::unix2mysql());
        }

        //filter = featured
        if(Core::get('filter')=='featured')
        {
        	$ads->where('featured', '>=', Date::unix2mysql());
        }

		// sort ads by search value
		if($q = $this->request->query('search'))
		{
			$ads = $ads->where('title', 'like', '%'.$q.'%');
			if(core::config('general.search_by_description') == TRUE)
	        	$ads = $ads->or_where('description', 'like', '%'.$q.'%');
		}

        if (is_numeric(Core::request('filter__id_user')))
            $ads = $ads->where('id_user', '=',Core::request('filter__id_user'));

        $ads_count = clone $ads;
		$res_count = $ads_count->count_all();
		if ($res_count > 0)
		{

			$pagination = Pagination::factory(array(
                    'view'           	=> 'oc-panel/crud/pagination',
                    'total_items'    	=> $res_count,
                    'items_per_page' 	=> 50
     	    ))->route_params(array(
                    'controller' 		=> $this->request->controller(),
                    'action'      		=> $this->request->action(),

    	    ));

    	    $ads = $ads->order_by(core::get('order','published'),core::get('sort','desc'))
                	            ->limit($pagination->items_per_page)
                	            ->offset($pagination->offset)
                	            ->find_all();


			$this->template->content = View::factory('oc-panel/pages/ad',array('res'			=> $ads,
																				'pagination'	=> $pagination,
                                                                                'fields'        => $fields
                                                                                ));

		}
		else
		{
			$this->template->content = View::factory('oc-panel/pages/ad', array('res' => NULL,'fields'        => $fields));
		}
	}

	/**
	 * Action MODERATION
	 */

	public function action_moderate()
	{
		//template header
		$this->template->title           	= __('Moderation');
		$this->template->meta_description	= __('Moderation');

		$this->template->scripts['footer'][]= 'js/jquery.toolbar.js';
		$this->template->scripts['footer'][]= '/js/oc-panel/moderation.js';


		//find all tables

		$ads = new Model_Ad();

		$res_count = $ads->where('status', '=', Model_Ad::STATUS_NOPUBLISHED)->count_all();

		if ($res_count > 0)
		{

			$pagination = Pagination::factory(array(
                    'view'           	=> 'oc-panel/crud/pagination',
                    'total_items'    	=> $res_count,
                    'items_per_page' 	=> core::config('advertisement.advertisements_per_page')
     	    ))->route_params(array(
                    'controller' 		=> $this->request->controller(),
                    'action'      		=> $this->request->action(),

    	    ));
    	    $ads = $ads->where('status', '=', Model_Ad::STATUS_NOPUBLISHED)
    	    					->order_by('created','desc')
                	            ->limit($pagination->items_per_page)
                	            ->offset($pagination->offset)
                	            ->find_all();


			$this->template->content = View::factory('oc-panel/pages/moderate',array('ads'			=> $ads,
																					'pagination'	=> $pagination,
																					));
		}
		else
		{
			Alert::set(Alert::INFO, __('You do not have any advertisements waiting to be published'));
			$this->template->content = View::factory('oc-panel/pages/moderate', array('ads' => NULL));
		}

	}

	/**
	 * Delete advertisement: Delete
     * @todo move to model ad
	 */
	public function action_delete()
	{
		$id = $this->request->param('id');
		$id_ads = (isset($id) AND is_numeric($id)) ? array($id) : Core::get('id_ads');
		$param_current_url = Core::get('current_url');
		$i = 0;

		if (is_array($id_ads))
		{
			$ads = new Model_Ad();
			$ads = $ads->where('id_ad', 'in', $id_ads)->find_all();

			foreach ($ads as $ad)
			{
				try
				{
					$ad->delete();
					$i++;
				}
				catch (Exception $e)
				{
					Alert::set(Alert::ERROR, sprintf(__('Warning, something went wrong while deleting Ad with id %u'),$id).':<br>'.$e->getMessage());
					//throw HTTP_Exception::factory(500,$e->getMessage());
				}
			}
		}

		$alert_type = ($i > 0) ? Alert::SUCCESS : Alert::INFO;

		if ($i == 1)
			$alert_text = __('Advertisement has been permanently deleted');
		elseif ($i >= 2)
			$alert_text = sprintf(__('%u advertisements have been permanently deleted'), $i);
		else
			$alert_text = __('None (0) advertisement has been deleted');

		Alert::set($alert_type, $alert_text);

		$param_current_url = Core::get('current_url');

		if ($param_current_url == Model_Ad::STATUS_NOPUBLISHED AND in_array(core::config('general.moderation'), Model_Ad::$moderation_status))
			HTTP::redirect(Route::url('oc-panel',array('controller'=>'ad','action'=>'moderate')));
		elseif ($param_current_url == Model_Ad::STATUS_PUBLISHED)
			HTTP::redirect(Route::url('oc-panel',array('controller'=>'ad','action'=>'index')));
		else
			HTTP::redirect(Route::url('oc-panel',array('controller'=>'ad','action'=>'index')).'?status='.$param_current_url);
	}

	/**
	 * Mark advertisement as spam : STATUS = 30
	 */
	public function action_spam()
	{
		$id = $this->request->param('id');
		$id_ads = (isset($id) AND is_numeric($id)) ? array($id) : Core::get('id_ads');
		$param_current_url = Core::get('current_url');

		if (is_array($id_ads))
		{
			$ads = new Model_Ad();
			$ads = $ads->where('id_ad', 'in', $id_ads)->find_all();

			foreach ($ads as $ad)
			{
				if ($ad->status != Model_Ad::STATUS_SPAM)
				{
					//mark user as spamer
					$ad->user->user_spam();
					//mark as as spam
					$ad->status = Model_Ad::STATUS_SPAM;

					try{
						$ad->save();
					}
					catch (Exception $e){
						throw HTTP_Exception::factory(500,$e->getMessage());
					}
				}
			}

			Alert::set(Alert::SUCCESS, __('Advertisement is marked as spam'));
		}

		if ($param_current_url == Model_Ad::STATUS_NOPUBLISHED AND in_array(core::config('general.moderation'), Model_Ad::$moderation_status))
			HTTP::redirect(Route::url('oc-panel',array('controller'=>'ad','action'=>'moderate')));
		elseif ($param_current_url == Model_Ad::STATUS_PUBLISHED)
			HTTP::redirect(Route::url('oc-panel',array('controller'=>'ad','action'=>'index')));
		else
			HTTP::redirect(Route::url('oc-panel',array('controller'=>'ad','action'=>'index')).'?status='.$param_current_url);
	}


	/**
	 * Mark advertisement as deactivated : STATUS = 50
	 */
	public function action_deactivate()
	{
		$id = $this->request->param('id');
		$id_ads = (isset($id) AND is_numeric($id)) ? array($id) : Core::get('id_ads');
		$param_current_url = Core::get('current_url');

		if (is_array($id_ads))
		{
			$ads = new Model_Ad();
			$ads = $ads->where('id_ad', 'in', $id_ads)->find_all();

			foreach ($ads as $ad)
				$ad->deactivate();

			Alert::set(Alert::SUCCESS, __('Advertisement is deactivated'));
		}

		if ($param_current_url == Model_Ad::STATUS_NOPUBLISHED AND in_array(core::config('general.moderation'), Model_Ad::$moderation_status))
			HTTP::redirect(Route::url('oc-panel',array('controller'=>'ad','action'=>'moderate')));
		elseif ($param_current_url == Model_Ad::STATUS_PUBLISHED)
			HTTP::redirect(Route::url('oc-panel',array('controller'=>'ad','action'=>'index')));
		else
			HTTP::redirect(Route::url('oc-panel',array('controller'=>'ad','action'=>'index')).'?status='.$param_current_url);
	}

	/**
	 * Mark advertisement as deactivated : STATUS = 50 and set the stock equal to zero
	 */
	public function action_sold()
	{
		$id = $this->request->param('id');
		$id_ads = (isset($id) AND is_numeric($id)) ? array($id) : Core::get('id_ads');
		$param_current_url = Core::get('current_url');

		if (is_array($id_ads))
		{
			$ads = new Model_Ad();
			$ads = $ads->where('id_ad', 'in', $id_ads)->find_all();

			foreach ($ads as $ad)
				$ad->sold();

			Alert::set(Alert::SUCCESS, __('Advertisement is marked as sold'));
		}

		if ($param_current_url == Model_Ad::STATUS_NOPUBLISHED AND in_array(core::config('general.moderation'), Model_Ad::$moderation_status))
			HTTP::redirect(Route::url('oc-panel',array('controller'=>'ad','action'=>'moderate')));
		elseif ($param_current_url == Model_Ad::STATUS_PUBLISHED)
			HTTP::redirect(Route::url('oc-panel',array('controller'=>'ad','action'=>'index')));
		else
			HTTP::redirect(Route::url('oc-panel',array('controller'=>'ad','action'=>'index')).'?status='.$param_current_url);

	}

    /**
     * removes featred ad
     */
    public function action_unfeature()
    {
		$id = $this->request->param('id');
		$id_ads = (isset($id) AND is_numeric($id)) ? array($id) : Core::get('id_ads');
		$param_current_url = Core::get('current_url');

		if (is_array($id_ads))
		{
			$ads = new Model_Ad();
			$ads = $ads->where('id_ad', 'in', $id_ads)->find_all();

			foreach ($ads as $ad)
				$ad->unfeature();

			Alert::set(Alert::SUCCESS, __('Removed featured ad'));
		}

        if ($param_current_url == Model_Ad::STATUS_NOPUBLISHED AND in_array(core::config('general.moderation'), Model_Ad::$moderation_status))
            HTTP::redirect(Route::url('oc-panel',array('controller'=>'ad','action'=>'moderate')));
        elseif ($param_current_url == Model_Ad::STATUS_PUBLISHED)
            HTTP::redirect(Route::url('oc-panel',array('controller'=>'ad','action'=>'index')));
        else
            HTTP::redirect(Route::url('oc-panel',array('controller'=>'ad','action'=>'index')).'?status='.$param_current_url);
    }

	/**
	 * Mark advertisement as active : STATUS = 1
	 */

	public function action_activate()
	{

		$id = $this->request->param('id');
		$id_ads = (isset($id) AND is_numeric($id)) ? array($id) : Core::get('id_ads');
		$param_current_url = Core::get('current_url');

		if (is_array($id_ads))
		{
			$ads = new Model_Ad();
			$ads = $ads->where('id_ad', 'in', $id_ads)->find_all();

			foreach ($ads as $ad)
			{
				//if theres subscription we need to check
                if (Core::config('general.subscriptions') == TRUE AND
					$ad->user->subscription()->loaded() AND
					$ad->user->subscription()->amount_ads_left <= 0 AND
					$ad->user->subscription()->amount_ads_left != -1  )
				{
					Alert::set(Alert::WARNING, sprintf(__('The customer %s does not have more ads left to publish.'),$ad->user->email));
				}
				elseif ($ad->status != Model_Ad::STATUS_PUBLISHED)
				{
					// update publish date if ad was not published before or
					//	was published and to_top is not enabled
					if($ad->published == NULL OR
						($ad->published != NULL AND
						(core::config('payment.pay_to_go_on_top') == 0 OR
						core::config('payment.to_top') == FALSE)))
					{
						$ad->published = Date::unix2mysql();
					}

					$ad->status    = Model_Ad::STATUS_PUBLISHED;

					try
					{
						$ad->save();
						Model_Subscription::new_ad($ad->user);
						Model_Subscribe::notify($ad);

						// Post featured ad on social media
                        if (! is_null($ad->featured) AND Core::config('advertisement.social_post_only_featured') == TRUE)
                        {
                            Social::social_post_featured_ad($ad);
                        }
						// Post on social media
                        else
                        {
                            Social::post_ad($ad, $ad->get_first_image('image'));
                        }
					}
					catch (Exception $e)
					{
						throw HTTP_Exception::factory(500,$e->getMessage());
					}
				}
			}

			$this->multiple_mails($id_ads); // sending many mails at the same time @TODO EMAIl

			Alert::set(Alert::SUCCESS, __('Advertisement is active and published'));
		}

		if ($param_current_url == Model_Ad::STATUS_NOPUBLISHED AND in_array(core::config('general.moderation'), Model_Ad::$moderation_status))
			HTTP::redirect(Route::url('oc-panel',array('controller'=>'ad','action'=>'moderate')));
		elseif ($param_current_url == Model_Ad::STATUS_PUBLISHED)
			HTTP::redirect(Route::url('oc-panel',array('controller'=>'ad','action'=>'index')));
		else
			HTTP::redirect(Route::url('oc-panel',array('controller'=>'ad','action'=>'index')).'?status='.$param_current_url);
	}

	/**
	 * Delete all ads in that category
	 *
	 * Depending on what status they have, it delets all of them
	 */
	public function action_delete_all()
	{
		$query = $this->request->query();

		$ads = new Model_Ad();
		$ads = $ads->where('status', '=', $query)->find_all();

		if (isset($ads))
		{
			try
			{
                $i = 0;
                foreach ($ads as $ad)
                {
                    $ad->delete();
                    $i++;
                }
                Alert::set(Alert::INFO, $i.' '.__('Ads deleted'));
				//DB::delete('ads')->where('status', '=', $query)->execute();
			}
			catch (Exception $e)
			{
				Alert::set(Alert::ALERT, __('Warning, something went wrong while deleting'));
				throw HTTP_Exception::factory(500,$e->getMessage());
			}
		}

		if ($query['status'] == Model_Ad::STATUS_NOPUBLISHED AND in_array(core::config('general.moderation'), Model_Ad::$moderation_status) )
			HTTP::redirect(Route::url('oc-panel',array('controller'=>'ad','action'=>'moderate')));
		elseif ($query['status'] == Model_Ad::STATUS_PUBLISHED)
			HTTP::redirect(Route::url('oc-panel',array('controller'=>'ad','action'=>'index')));
		else
			HTTP::redirect(Route::url('oc-panel',array('controller'=>'ad','action'=>'index')).'?status='.$query['status']);
	}


	//temporary function until i figure out how to deal with mass mails @TODO EMAIL
	public function multiple_mails($receivers)
	{
		foreach ($receivers as $num => $receiver_id) {
			if(is_numeric($receiver_id))
			{
				$ad 		= new Model_Ad($receiver_id);
				if($ad->loaded())
				{
                    $cat        = $ad->category;
                    $usr        = $ad->user;

					//we get the QL, and force the regen of token for security
					$url_ql = $usr->ql('ad',array( 'category' => $cat->seoname,
				 	                                'seotitle'=> $ad->seotitle),TRUE);

					$ret = $usr->email('ads-activated',array('[USER.OWNER]'=>$usr->name,
															 '[URL.QL]'=>$url_ql,
															 '[AD.NAME]'=>$ad->title));

				}
			}

		}

	}

    /**
     * Mark advertisement as no published : STATUS = 0
     */
    public function action_send_to_moderation()
    {
        if (! in_array(core::config('general.moderation'), Model_Ad::$moderation_status))
        {
            HTTP::redirect(Route::url('oc-panel',array('controller'=>'ad','action'=>'index')));
        }

        $id = $this->request->param('id');
        $id_ads = (isset($id) AND is_numeric($id)) ? array($id) : Core::get('id_ads');
        $param_current_url = Core::get('current_url');

        if (is_array($id_ads))
        {
            $ads = new Model_Ad();
            $ads = $ads->where('id_ad', 'in', $id_ads)->find_all();

            foreach ($ads as $ad)
            {
                if ($ad->status != Model_Ad::STATUS_NOPUBLISHED)
                {
                    $ad->status = Model_Ad::STATUS_NOPUBLISHED;

                    try{
                        $ad->save();
                    }
                    catch (Exception $e){
                        throw HTTP_Exception::factory(500,$e->getMessage());
                    }
                }
            }

            Alert::set(Alert::SUCCESS, __('Advertisement sended to moderation'));
        }

        if ($param_current_url == Model_Ad::STATUS_NOPUBLISHED AND in_array(core::config('general.moderation'), Model_Ad::$moderation_status))
            HTTP::redirect(Route::url('oc-panel',array('controller'=>'ad','action'=>'moderate')));
        elseif ($param_current_url == Model_Ad::STATUS_PUBLISHED)
            HTTP::redirect(Route::url('oc-panel',array('controller'=>'ad','action'=>'index')));
        else
            HTTP::redirect(Route::url('oc-panel',array('controller'=>'ad','action'=>'index')).'?status='.$param_current_url);
    }

}
