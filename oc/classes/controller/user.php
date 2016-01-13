<?php defined('SYSPATH') or die('No direct access allowed.');

/**
 * 
 * Display user profile
 * @throws HTTP_Exception_404
 */
class Controller_User extends Controller {
	
/*    public function __construct($request, $response)
    {
        parent::__construct($request, $response);
        
        
    }*/

    public function action_index()
    {
        $db_prefix  = Database::instance('default')->table_prefix();

        //include num of ads so we can filter, sort and display next to each user
        $query_count = '(SELECT count(id_ad) FROM '.$db_prefix.'ads 
                        WHERE id_user='.$db_prefix.'user.id_user AND 
                                status='.Model_Ad::STATUS_PUBLISHED.')';

        $users = new Model_User();
        $users  ->select(array(DB::expr($query_count),'ads_count'))
                ->where('status','=', Model_User::STATUS_ACTIVE);

        //search filter
        if(core::request('search')!==NULL AND strlen(core::request('search'))>=3)
        {   
            $search = core::request('search');

            $users
                ->where_open()
                ->where('name', 'like', '%'.$search.'%')
                ->or_where('description', 'like', '%'.$search.'%')
                ->where_close();
        }

        //cf filter
        foreach (array_merge($_POST,$_GET) as $name => $value) 
        {
            //value set and is a CF
            if (    isset($value) AND $value != NULL AND 
                    strpos($name,'cf_') !== FALSE AND 
                    array_key_exists(str_replace('cf_','',$name), Model_UserField::get_all()) 
                ) 
            {
                //checkbox when selected return string 'on' as a value
                $value = ($value == 'on')?1:$value;
                
                if(is_numeric($value))
                    $users->where($name, '=', $value);
                elseif(is_string($value))
                    $users->where($name, 'like', '%'.$value.'%');
            }
        }


        $pagination = Pagination::factory(array(
                'view'              => 'pagination',
                'total_items'       => $users->count_all(),
                'items_per_page'    => core::config('advertisement.advertisements_per_page')
        ));

        /**
         * order depending on the sort parameter
         */
        switch (core::request('sort')) 
        {
            //num of ads desc
            case 'ads-asc':
                $users->order_by('ads_count','asc')->order_by('created','desc');
                break;
            //num of ads desc
            case 'ads-desc':
                $users->order_by('ads_count','desc')->order_by('created','desc');
                break;
            //name z->a
            case 'name-asc':
                $users->order_by('name','asc')->order_by('created','desc');
                break;
            //name a->z
            case 'name-desc':
                $users->order_by('name','desc')->order_by('created','desc');
                break;
            //rating
            case 'rating':
                $users->order_by('rate','desc')->order_by('created','desc');
                break;
            //oldest first
            case 'created-asc':
                $users->order_by('created','asc');
                break;
            //newest first
            case 'created-desc':
            default:
                $users->order_by('created','desc');
                break;
        }

        $users = $users->limit($pagination->items_per_page)
                        ->offset($pagination->offset)
                        ->find_all();
        

        //if home page is the users
        if ( ($landing = json_decode(core::config('general.landing_page'))) != NULL
            AND $landing->controller == 'user'
            AND $landing->action == 'index'
            AND (isset($pagination) AND $pagination->current_page == 1) )
        {
            //only show site title
            $this->template->title = NULL;

            // if we have site description lets use that ;)
            if (core::config('general.site_description') != '')
                $this->template->meta_description = core::config('general.site_description');
        }
        else
        {
            Breadcrumbs::add(Breadcrumb::factory()->set_title(__('Home'))->set_url(Route::url('default')));
            Breadcrumbs::add(Breadcrumb::factory()->set_title(__('Users')));
            $this->template->title = __('Users search');
        }

        if (Theme::get('infinite_scroll'))
        {
            $this->template->scripts['footer'][] = '//cdn.jsdelivr.net/jquery.infinitescroll/2.0b2/jquery.infinitescroll.js';
            $this->template->scripts['footer'][] = 'js/users.js';
        }

        $this->template->content = View::factory('pages/user/list',array('users'     => $users,
                                                                        'pagination' => $pagination,
                                                                        )); 
    }	

	public function action_profile()
	{
        Breadcrumbs::add(Breadcrumb::factory()->set_title(__('Home'))->set_url(Route::url('default')));
		Breadcrumbs::add(Breadcrumb::factory()->set_title(__('Users'))->set_url(Route::url('profiles')));
		Breadcrumbs::add(Breadcrumb::factory()->set_title(__('User Profile')));
		$seoname = $this->request->param('seoname',NULL);
		if ($seoname!==NULL)
		{
			$user = new Model_User();
			$user->where('seoname','=', $seoname)
                 ->where('status','=', Model_User::STATUS_ACTIVE)
				 ->limit(1)->cached()->find();
			
			if ($user->loaded())
			{
				$this->template->title = __('User Profile').' - '.$user->name;
				
				//$this->template->meta_description = $user->name;//@todo phpseo
				
				$this->template->bind('content', $content);

				$ads = new Model_Ad();
				$ads->where('id_user', '=', $user->id_user)
                            ->where('status', '=', Model_Ad::STATUS_PUBLISHED)
                            ->order_by('created','desc');
				
				
				// case when user dont have any ads
				if( ($count_all = $ads->count_all()) == 0)
                {
                    $profile_ads = NULL;
                    $pagination  = NULL;
                } 
                else
                {
                    $pagination = Pagination::factory(array(
                            'view'              => 'pagination',
                            'total_items'       => $count_all,
                            'items_per_page'    => core::config('advertisement.advertisements_per_page')
                    ));

                    $ads = $ads->limit($pagination->items_per_page)
                        ->offset($pagination->offset)->cached()->find_all();
                }

				$this->template->content = View::factory('pages/user/profile',array('user'=>$user, 'profile_ads'=>$ads,'pagination'=>$pagination));
			}
			//not found in DB
			else
			{
				//throw 404
				throw HTTP_Exception::factory(404,__('Page not found'));
			}
			
		}
		else//this will never happen
		{
			//throw 404
			throw HTTP_Exception::factory(404,__('Page not found'));
		}
	}

	
}// End Userprofile Controller