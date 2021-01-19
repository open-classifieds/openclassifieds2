<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Panel_Integrations_Paypal extends Auth_Controller {

    public function action_index()
    {
        $this->template->title = __('Paypal');

        if($this->request->post())
        {
            Model_Config::set_value('payment', 'paypal_account', Core::post('paypal_account'));
            Model_Config::set_value('payment', 'sandbox', Core::post('sandbox') ?? 0);
            Model_Config::set_value('payment', 'paypal_seller', Core::post('paypal_seller') ?? 0);

            Alert::set(Alert::SUCCESS, __('Configuration updated'));

            $this->redirect(Route::url('oc-panel/integrations', ['controller' => 'paypal']));
        }

        return $this->template->content = View::factory('oc-panel/pages/integrations/paypal', [
        ]);
    }
}
