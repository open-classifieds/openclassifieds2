<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Panel_Addons_Blacklist extends Auth_Controller {

    public function action_index()
    {
        $this->template->title = __('Black list');

        if($this->request->post())
        {
            Model_Config::set_value('general', 'black_list', Core::post('is_active') ?? 0);

            Alert::set(Alert::SUCCESS, __('Configuration updated'));

            $this->redirect(Route::url('oc-panel/addons', ['controller' => 'blacklist']));
        }

        $black_list = (new Model_User())
            ->where('status', '=', Model_User::STATUS_SPAM)
            ->order_by('id_user')
            ->find_all();

        return $this->template->content = View::factory('oc-panel/pages/addons/black-list/index', [
            'is_active' => (bool) Core::config('general.black_list'),
            'black_list' => $black_list
        ]);
    }

    public function action_delete()
    {
        if(! $id = $this->request->param('id'))
        {
            return;
        }

        $user = new Model_User($id);

        if(! $user->loaded())
        {
            return;
        }

        $user->status = Model_User::STATUS_ACTIVE;

        try {
            $user->save();

            Alert::set(Alert::SUCCESS, sprintf(__('User %s has been removed from black list.'),$user->name));

            $this->redirect(Route::url('oc-panel/addons', array('controller'=>'blacklist')));
        } catch (Exception $e){}

        $this->redirect(Route::url('oc-panel/addons', array('controller'=>'blacklist')));
    }
}
