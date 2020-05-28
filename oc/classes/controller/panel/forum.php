<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Panel_Forum extends Auth_Crud {

	/**
	* @var $_index_fields ORM fields shown in index
	*/
	protected $_index_fields = array('name','order','price', 'id_forum', 'id_forum_parent');

	/**
	 * @var $_orm_model ORM model name
	 */
	protected $_orm_model = 'forum';

    public function action_index($view = NULL)
    {
        //template header
        $this->template->title  = __('Forums');

        Breadcrumbs::add(Breadcrumb::factory()->set_title(__('Forums')));
        $this->template->styles              = array('css/sortable.css' => 'screen');
        $this->template->scripts['footer'][] = 'js/jquery-sortable-min.js';
        $this->template->scripts['footer'][] = 'js/oc-panel/forums.js';

        list($forums,$order)  = Model_Forum::get_all();

        $this->template->content = View::factory('oc-panel/pages/forum/forums',array('forums' => $forums,'order'=>$order));
    }

    public function action_saveorder()
    {
        $this->auto_render = FALSE;
        $this->template = View::factory('js');

        $forum = new Model_Forum(core::get('id_forum'));

        if ($forum->loaded())
        {
            //saves the current forum
            $forum->id_forum_parent = core::get('id_forum_parent');
            $forum->parent_deep     = core::get('deep');


            //saves the forums in the same parent the new orders
            $order = 0;
            foreach (core::get('brothers') as $id_forum)
            {
                $id_forum = substr($id_forum,3);//removing the li_ to get the integer

                //not the main forum so loading and saving
                if ($id_forum!=core::get('id_forum'))
                {
                    $c = new Model_Forum($id_forum);
                    $c->order = $order;
                    $c->save();
                }
                else
                {
                    //saves the main forum
                    $forum->order  = $order;
                    $forum->save();
                }
                $order++;
            }
            //Core::delete_cache();
            Model_Forum::cache_delete();

            $this->template->content = __('Saved');
        }
        else
            $this->template->content = __('Error');


    }

    public function action_create()
    {
        $forum = new Model_Forum();

        if($this->request->post())
        {

            $forum->name = Core::post('name');
            $forum->id_forum_parent = Core::post('id_forum_parent');
            $forum->description = Core::post('description');
            $forum->seoname = Core::post('seoname') ?? $forum->gen_seoname(Core::post('name'));

            try {
                $forum->save();

                Alert::set(Alert::SUCCESS, __('Forum is created.'));
            } catch (Exception $e) {
                Alert::set(Alert::ERROR, $e->getMessage());
            }

            $this->redirect(Route::url('oc-panel/addons', ['controller' => 'forums']));
        }

        $parent_forums[0] = __('None');

        foreach (Model_Forum::get_all()[0] as $parent)
        {
            $parent_forums[$parent['id']] = $parent['name'];
        }

        $this->template->content = View::factory('oc-panel/pages/forum/create', [
            'forum' => $forum,
            'parent_forums' => $parent_forums
        ]);
    }

    public function action_update()
    {
        $forum = new Model_Forum($this->request->param('id'));

        if (! $forum->loaded())
        {
            throw HTTP_Exception::factory(404, __('Faq not found'));
        }

        if($this->request->post())
        {

            $forum->name = Core::post('name');
            $forum->id_forum_parent = Core::post('id_forum_parent');
            $forum->description = Core::post('description');

            if(Core::post('seoname') != $forum->seoname)
            {
                $forum->seoname = $forum->gen_seoname(Core::post('seoname'));
            }

            try {
                $forum->update();

                Alert::set(Alert::SUCCESS, __('Forum is updated.'));
            } catch (Exception $e) {
                Alert::set(Alert::ERROR, $e->getMessage());
            }

            $this->redirect(Route::url('oc-panel/addons', ['controller' => 'forums']));
        }

        $parent_forums[0] = __('None');

        foreach (Model_Forum::get_all()[0] as $parent)
        {
            if ($parent['id'] != $forum->id_forum)
            {
                $parent_forums[$parent['id']] = $parent['name'];
            }
        }

        $this->template->content = View::factory('oc-panel/pages/forum/update', [
            'forum' => $forum,
            'parent_forums' => $parent_forums
        ]);
    }

    public function action_delete()
    {
        $forum = new Model_Forum($this->request->param('id'));

        if (! $forum->loaded())
        {
            throw HTTP_Exception::factory(404, __('Forum not found'));
        }

        try
        {
            $forum->delete();

            Alert::set(Alert::SUCCESS, __('Forum deleted'));
        }
        catch (Exception $e)
        {
            Alert::set(Alert::ERROR, $e->getMessage());
        }

        $this->redirect(Route::url('oc-panel/addons', ['controller' => 'forums']));
    }

}
