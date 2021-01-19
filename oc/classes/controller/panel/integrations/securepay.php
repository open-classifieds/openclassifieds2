<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Panel_Integrations_SecurePay extends Auth_Controller {

    public function action_index()
    {
        $this->template->title = __('SecurePay');

        if($this->request->post())
        {
            Model_Config::set_value('payment', 'securepay_merchant', Core::post('securepay_merchant'));
            Model_Config::set_value('payment', 'securepay_password', Core::post('securepay_password'));
            Model_Config::set_value('payment', 'securepay_testing', Core::post('securepay_testing') ?? 0);

            Alert::set(Alert::SUCCESS, __('Configuration updated'));

            $this->redirect(Route::url('oc-panel/integrations', ['controller' => 'securepay']));
        }

        return $this->template->content = View::factory('oc-panel/pages/integrations/securepay', [
        ]);
    }
}
