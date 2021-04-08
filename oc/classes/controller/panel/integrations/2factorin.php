<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Panel_Integrations_2FactorIn extends Auth_Controller {

    public function action_index()
    {
        $this->template->title = __('2Factor');

        $validation = $this->validation();

        if($this->request->post() AND $validation->check())
        {
            Model_Config::set_value('general', 'sms_auth', Core::post('is_active') ?? 0);
            Model_Config::set_value('general', 'sms_2factorin_api', Core::post('sms_2factorin_api'));
            Model_Config::set_value('general', 'sms_2factorin_sender_id', Core::post('sms_2factorin_sender_id'));
            Model_Config::set_value('general', 'sms_2factorin_subscription_payment_template', Core::post('sms_2factorin_subscription_payment_template'));
            Model_Config::set_value('general', 'sms_2factorin_expiring_subscription_template', Core::post('sms_2factorin_expiring_subscription_template'));
            Model_Config::set_value('general', 'sms_2factorin_featured_ad_payment_template', Core::post('sms_2factorin_featured_ad_payment_template'));

            if (Core::post('is_active') ?? 0)
            {
                Model_Config::set_value('general', 'sms_service', '2factorin');
            }

            Alert::set(Alert::SUCCESS, __('Configuration updated'));

            $this->redirect(Route::url('oc-panel/integrations', ['controller' => '2factorin']));
        }

        return $this->template->content = View::factory('oc-panel/pages/integrations/2factorin', [
            'errors' => $validation->errors('validation'),
            'is_active' => (bool) Core::config('general.sms_auth')
        ]);
    }

    private function validation()
    {
        $validation = Validation::factory($this->request->post());

        if ((bool) Core::post('is_active') ?? 0)
        {
            $validation->rule('sms_2factorin_api', 'not_empty');
        }

        return $validation;
    }
}
