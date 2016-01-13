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
        $status = is_numeric(Core::get('status'))?Core::get('status'):Model_Ad::STATUS_PUBLISHED;
        $ads = $ads->where('status', '=', $status);
		
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
		
		$format_id = explode('_', $id);
		$auth_user = Auth::instance();
	
		$nb_Ads_Deleted = 0;
		foreach ($format_id as $id) 
		{
			
			if (isset($id) AND $id !== '')
			{
				$ad = new Model_Ad($id);
				
				if($ad->loaded())
				{
					try
					{
						$ad->delete();
						$nb_Ads_Deleted++;
					}
					catch (Exception $e)
					{
						Alert::set(Alert::ERROR, sprintf(__('Warning, something went wrong while deleting Ad with id %u'),$id).':<br>'.$e->getMessage());
						//throw HTTP_Exception::factory(500,$e->getMessage());
					}
				}
			}

		}
		$level_Alert = ($nb_Ads_Deleted > 0) ? Alert::SUCCESS : Alert::INFO;
		if ($nb_Ads_Deleted == 1)
			$nb_Ads_Deleted = __('Advertisement has been permanently deleted');
		elseif ($nb_Ads_Deleted >= 2)
			$nb_Ads_Deleted = sprintf(__('%u advertisements have been permanently deleted'),$nb_Ads_Deleted);
		else
			$nb_Ads_Deleted = __('None (0) advertisement has been deleted');

		Alert::set($level_Alert, $nb_Ads_Deleted);

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
		$param_current_url = Core::get('current_url');
		$format_id = explode('_', $id);

		foreach ($format_id as $id) 
		{ 
			if (isset($id) AND $id !== '')
			{ 
				$spam_ad = new Model_Ad($id);

				if ($spam_ad->loaded())
				{
					if ($spam_ad->status != Model_Ad::STATUS_SPAM)
					{
						//mark user as spamer
						$spam_ad->user->user_spam();
                        //mark as as spam
						$spam_ad->status = Model_Ad::STATUS_SPAM;
						
						try{
							$spam_ad->save();
						}
						catch (Exception $e){
							throw HTTP_Exception::factory(500,$e->getMessage());
						}
					}
				}
				
			}
		}
		Alert::set(Alert::SUCCESS, __('Advertisement is marked as spam'));
		
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
		$param_current_url = Core::get('current_url');
		$format_id = explode('_', $id);

		foreach ($format_id as $id) 
		{
			if (isset($id) AND is_numeric($id))
			{
				$deact_ad = new Model_Ad($id);
				$deact_ad->deactivate();
			}
		}
		Alert::set(Alert::SUCCESS, __('Advertisement is deactivated'));
		
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
        $param_current_url = Core::get('current_url');
        if (isset($id) AND is_numeric($id))
        {
            $ad = new Model_Ad($id);
            $ad->unfeature();
        }
        
        Alert::set(Alert::SUCCESS, __('Removed featured ad'));
        
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
		$param_current_url = Core::get('current_url');
		$format_id = explode('_', $id);

		foreach ($format_id as $id) 
		{
			if (isset($id) AND $id !== '')
			{
				$active_ad = new Model_Ad($id);

				if ($active_ad->loaded())
				{
					if ($active_ad->status != Model_Ad::STATUS_PUBLISHED)
					{
						$active_ad->published = Date::unix2mysql();
						$active_ad->status    = Model_Ad::STATUS_PUBLISHED;
						
						try
						{
							$active_ad->save();
                            Model_Subscribe::notify($active_ad);
						}
						catch (Exception $e)
						{
							throw HTTP_Exception::factory(500,$e->getMessage());
						}
					}
				}
			}
		}

		$this->multiple_mails($format_id); // sending many mails at the same time @TODO EMAIl

		Alert::set(Alert::SUCCESS, __('Advertisement is active and published'));
			
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

}
