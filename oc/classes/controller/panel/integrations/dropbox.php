<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Panel_Integrations_Dropbox extends Auth_Controller {

    public function action_index()
    {
        $this->template->title = __('Dropbox');

        if($this->request->post())
        {
            Model_Config::set_value('advertisement', 'dropbox_app_key', Core::post('dropbox_app_key'));

            Alert::set(Alert::SUCCESS, __('Configuration updated'));

            $this->redirect(Route::url('oc-panel/integrations', ['controller' => 'dropbox']));
        }

        return $this->template->content = View::factory('oc-panel/pages/integrations/dropbox', [
            'is_active' => (bool) Core::config('advertisement.dropbox_app_key')
        ]);
    }
}
