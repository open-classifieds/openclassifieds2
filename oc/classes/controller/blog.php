<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Blog extends Controller {

    public function __construct($request, $response)
    {
        if (core::config('general.blog') != 1)
            $this->redirect(Route::url('default'));

        parent::__construct($request, $response);
        Breadcrumbs::add(Breadcrumb::factory()->set_title(__('Home'))->set_url(Route::url('default')));
        Breadcrumbs::add(Breadcrumb::factory()->set_title(__('Blog'))->set_url(Route::url('blog')));

    }

	public function action_index()
	{
        //if they want to see a single post
        $seotitle = $this->request->param('seotitle',NULL);
        if ($seotitle!==NULL)
            return $this->action_view($seotitle);


	    //template header
	    $this->template->title            = __('Blog');
	    $this->template->meta_description = core::config('general.site_name').' '.__('blog section.');

	    $posts = new Model_Post();
        $posts->where('status','=', Model_Post::STATUS_ACTIVE)->where('id_forum','IS',NULL);

        if ( ($search=Core::get('search'))!==NULL AND strlen(Core::get('search'))>=3 )
        $posts->where_open()
             ->where('title','like','%'.$search.'%')->or_where('description','like','%'.$search.'%')
             ->where_close();

        $res_count = clone $posts;
        $res_count = $res_count->count_all();
        // check if there are some post
        if ($res_count > 0)
        {

            // pagination module
            $pagination = Pagination::factory(array(
                    'view'              => 'pagination',
                    'total_items'       => $res_count,
            ))->route_params(array(
                    'controller'        => $this->request->controller(),
                    'action'            => $this->request->action(),
            ));

            Breadcrumbs::add(Breadcrumb::factory()->set_title(__("Page ").$pagination->current_page));

            //we sort all ads with few parameters
            $posts = $posts->order_by('created','desc')
                                ->limit($pagination->items_per_page)
                                ->offset($pagination->offset)
                                ->find_all();
        }
        else
        {
           $posts       = NULL;
           $pagination  = NULL;
        }

        $this->template->bind('content', $content);

        $this->template->content = View::factory('pages/blog/listing',array('posts'=>$posts,
        															'pagination'=>$pagination,
                                                                    'user'=>Auth::instance()->get_user(),
        															));

	}


   /**
     *
     * Display single page
     * @throws HTTP_Exception_404
     */
    public function action_view($seotitle)
    {

        $post = new Model_Post();

        // if visitor or user with ROLE_USER display post with STATUS_ACTIVE
        if (! Auth::instance()->logged_in() OR
            (Auth::instance()->logged_in() AND Auth::instance()->get_user()->id_role == Model_Role::ROLE_USER))
            $post->where('status','=',Model_Post::STATUS_ACTIVE);

        $post->where('seotitle','=',$seotitle)
            ->where('id_forum','IS',NULL)
            ->cached()->limit(1)->find();

        if ($post->loaded())
        {
            Breadcrumbs::add(Breadcrumb::factory()->set_title($post->title));

            if ($post->status == 0)
                    Alert::set(Alert::ALERT, __('Blog post unpublished.'));

            $this->template->title            = $post->title;
            $this->template->meta_description = $post->description;

            Controller::$image = $post->get_first_image();

            $previous = new Model_Post();
            $previous = $previous->where('status','=',Model_Post::STATUS_ACTIVE)
                        ->where('id_forum','IS',NULL)
                        ->order_by('created','desc')
                        ->where('id_post', '<', $post->id_post)
                        ->cached()
                        ->limit(1)->find();
            $next = new Model_Post();
            $next = $next->where('status','=',Model_Post::STATUS_ACTIVE)
                        ->where('id_forum','IS',NULL)
                        ->where('id_post', '>', $post->id_post)
                        ->cached()
                        ->limit(1)->find();

            $this->template->bind('content', $content);
            $this->template->content = View::factory('pages/blog/post',array('post'=>$post,'next'=>$next,'previous'=>$previous));
        }
        //not found in DB
        else
        {
            //throw 404
            throw HTTP_Exception::factory(404,__('Page not found'));
        }


    }


} // End Blog
