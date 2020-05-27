<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Panel_Integrations_Facebook extends Auth_Controller {

    public function action_index()
    {
        $this->template->title = __('Facebook');

        if($this->request->post())
        {
            Model_Config::set_value('advertisement', 'fbcomments', Core::post('fbcomments'));

            Alert::set(Alert::SUCCESS, __('Configuration updated'));

            $this->redirect(Route::url('oc-panel/integrations', ['controller' => 'facebook']));
        }

        return $this->template->content = View::factory('oc-panel/pages/integrations/facebook', [
        ]);
    }
}
