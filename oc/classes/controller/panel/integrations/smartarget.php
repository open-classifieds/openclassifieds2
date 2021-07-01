<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Panel_Integrations_Smartarget extends Auth_Controller {

    public function action_index()
    {
        $this->template->title = 'Smartarget';

        if($this->request->post())
        {
            Model_Config::set_value('general', 'smartarget_id', Core::post('smartarget_id'));

            Alert::set(Alert::SUCCESS, __('Configuration updated'));

            $this->redirect(Route::url('oc-panel/integrations', ['controller' => 'smartarget']));
        }

        return $this->template->content = View::factory('oc-panel/pages/integrations/smartarget', [
            'is_active' => (bool) Core::config('general.smartarget_id')
        ]);
    }
}
