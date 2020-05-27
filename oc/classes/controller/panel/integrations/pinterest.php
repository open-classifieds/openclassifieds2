<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Panel_Integrations_Pinterest extends Auth_Controller {

    public function action_index()
    {
        $this->template->title = __('Pinterest');

        if($this->request->post())
        {
            Model_Config::set_value('advertisement', 'pinterest', Core::post('is_active') ?? 0);
            Model_Config::set_value('advertisement', 'social_post_only_featured', Core::post('social_post_only_featured') ?? 0);
            Model_Config::set_value('advertisement', 'pinterest_app_id', Core::post('pinterest_app_id'));
            Model_Config::set_value('advertisement', 'pinterest_app_secret', Core::post('pinterest_app_secret'));
            Model_Config::set_value('advertisement', 'pinterest_board', Core::post('pinterest_board'));

            Alert::set(Alert::SUCCESS, __('Configuration updated'));

            $this->redirect(Route::url('oc-panel/integrations', ['controller' => 'pinterest']));
        }

        return $this->template->content = View::factory('oc-panel/pages/integrations/pinterest', [
            'is_active' => (bool) Core::config('advertisement.pinterest')
        ]);
    }
}
