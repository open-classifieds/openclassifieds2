<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Panel_Integrations_Mollie extends Auth_Controller {

    public function action_index()
    {
        $this->template->title = 'Mollie';

        if($this->request->post())
        {
            Model_Config::set_value('payment', 'mollie_api_key', Core::post('mollie_api_key'));

            Alert::set(Alert::SUCCESS, __('Configuration updated'));

            $this->redirect(Route::url('oc-panel/integrations', ['controller' => 'mollie']));
        }

        return $this->template->content = View::factory('oc-panel/pages/integrations/mollie');
    }
}
