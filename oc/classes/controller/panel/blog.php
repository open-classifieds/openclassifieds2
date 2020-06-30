<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Panel_Blog extends Auth_Crud {

	/**
	 * @var $_index_fields ORM fields shown in index
	 */
    protected $_index_fields = ['title', 'created', 'status'];

	/**
	 * @var $_orm_model ORM model name
	 */
	protected $_orm_model = 'post';

	/**
     *
     * Loads a basic list info
     * @param string $view template to render
     */
    public function action_index($view = NULL)
    {
        $this->template->title = __('Blog');

        $posts = (new Model_Post())->where('id_forum', 'IS', NULL);

        $pagination = Pagination::factory([
            'view' => 'oc-panel/crud/pagination',
            'total_items' => $posts->count_all(),
            //'items_per_page' => 10// @todo from config?,
        ])->route_params([
            'controller' => $this->request->controller(),
            'action' => $this->request->action(),
        ]);

        $pagination->title(__('Blog'));

        $posts = $posts->order_by('created','desc')
            ->limit($pagination->items_per_page)
            ->offset($pagination->offset)
            ->find_all();

        return $this->render('oc-panel/pages/blog/index', [
            'posts' => $posts,
            'pagination'=> $pagination->render()
        ]);
    }


    /**
     * CRUD controller: CREATE
     */
    public function action_create()
    {
        $post = new FormOrm('post');

        if ($this->request->post())
        {
            $post->submit();

            if ($post->submit_status() == FormOrm::SUBMIT_STATUS_SUCCESS)
            {
                $post->object->description = Kohana::$_POST_ORIG['formorm']['description'];
                $post->save_object();

                Alert::set(Alert::SUCCESS, __('Blog post created').'. '.__('Please to see the changes delete the cache')
                    .'<br><a class="btn btn-primary btn-mini ajax-load" href="'.Route::url('oc-panel',array('controller'=>'tools','action'=>'cache')).'?force=1" title="'.__('Delete All').'">'
                    .__('Delete All').'</a>');

                $this->redirect(Route::get('oc-panel')->uri(['controller' => 'Blog']));
            }
            else
            {
                Alert::set(Alert::ERROR, __('Check form for errors'));
            }
        }

        $this->template->title = __('New blog post');

        return $this->render('oc-panel/pages/blog/create', ['form_fields' => $post->fields]);
    }


    /**
     * CRUD controller: UPDATE
     */
    public function action_update()
    {
        $post = new FormOrm('post', $this->request->param('id'));

        if ($this->request->post())
        {
            $post->submit();

            if ($post->submit_status() == FormOrm::SUBMIT_STATUS_SUCCESS)
            {
                $post->object->description = Kohana::$_POST_ORIG['formorm']['description'];
                $post->save_object();

                Alert::set(Alert::SUCCESS, __('Blog post updated').'. '.__('Please to see the changes delete the cache')
                    .'<br><a class="btn btn-primary btn-mini ajax-load" href="'.Route::url('oc-panel',array('controller'=>'tools','action'=>'cache')).'?force=1" title="'.__('Delete All').'">'
                    .__('Delete All').'</a>');

                $this->redirect(Route::get('oc-panel')->uri(['controller' => 'Blog']));
            }
            else
            {
                Alert::set(Alert::ERROR, __('Check form for errors'));
            }
        }

        $this->template->title = __('Update blog post');

        return $this->render('oc-panel/pages/blog/update', ['post' => $post->object, 'form_fields' => $post->fields]);
    }
}
