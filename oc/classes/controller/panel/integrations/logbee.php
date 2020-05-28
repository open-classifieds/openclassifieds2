<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Panel_Integrations_Logbee extends Auth_Controller {

    public function action_index()
    {
        $this->template->title = __('Logbee');

        if($this->request->post())
        {
            Model_Config::set_value('advertisement', 'logbee', Core::post('is_active') ?? 0);

            Alert::set(Alert::SUCCESS, __('Configuration updated'));

            $this->redirect(Route::url('oc-panel/integrations', ['controller' => 'logbee']));
        }

        return $this->template->content = View::factory('oc-panel/pages/integrations/logbee', [
            'is_active' => (bool) Core::config('advertisement.logbee')
        ]);
    }
}
