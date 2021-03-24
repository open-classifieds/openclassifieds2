<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Panel_Integrations_Razorpay extends Auth_Controller {

    public function action_index()
    {
        $this->template->title = __('Razorpay');

        if($this->request->post())
        {
            Model_Config::set_value('payment', 'razorpay_key_id', Core::post('razorpay_key_id'));
            Model_Config::set_value('payment', 'razorpay_key_secret', Core::post('razorpay_key_secret'));

            Alert::set(Alert::SUCCESS, __('Configuration updated'));

            $this->redirect(Route::url('oc-panel/integrations', ['controller' => 'razorpay']));
        }

        return $this->template->content = View::factory('oc-panel/pages/integrations/razorpay', [
        ]);
    }
}
