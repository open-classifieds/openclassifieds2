<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Panel_Integrations_Bitpay extends Auth_Controller {

    public function action_index()
    {
        $this->template->title = __('Bitpay');

        if($this->request->post())
        {
            Model_Config::set_value('payment', 'bitpay_sandbox', Core::post('bitpay_sandbox') ?? 0);
            Model_Config::set_value('payment', 'bitpay_pairing_code', Core::post('bitpay_pairing_code'));

            Alert::set(Alert::SUCCESS, __('Configuration updated'));

            $this->redirect(Route::url('oc-panel/integrations', ['controller' => 'bitpay']));
        }

        return $this->template->content = View::factory('oc-panel/pages/integrations/bitpay', [
        ]);
    }
}
