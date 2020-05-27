<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Panel_Integrations_Paytabs extends Auth_Controller {

    public function action_index()
    {
        $this->template->title = __('Paytabs');

        if($this->request->post())
        {
            Model_Config::set_value('payment', 'paytabs_merchant_email', Core::post('paytabs_merchant_email'));
            Model_Config::set_value('payment', 'paytabs_secret_key', Core::post('paytabs_secret_key'));

            Alert::set(Alert::SUCCESS, __('Configuration updated'));

            $this->redirect(Route::url('oc-panel/integrations', ['controller' => 'paytabs']));
        }

        return $this->template->content = View::factory('oc-panel/pages/integrations/paytabs', [
        ]);
    }
}
