<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Panel_Integrations_Serfinsa extends Auth_Controller {

    public function action_index()
    {
        $this->template->title = __('Serfinsa');

        if($this->request->post())
        {
            Model_Config::set_value('payment', 'serfinsa_token', Core::post('serfinsa_token'));
            Model_Config::set_value('payment', 'serfinsa_sandbox', Core::post('serfinsa_sandbox') ?? 0);

            Alert::set(Alert::SUCCESS, __('Configuration updated'));

            $this->redirect(Route::url('oc-panel/integrations', ['controller' => 'serfinsa']));
        }

        return $this->template->content = View::factory('oc-panel/pages/integrations/serfinsa', [
        ]);
    }
}
