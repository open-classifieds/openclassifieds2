<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Panel_Integrations_Zenith extends Auth_Controller {

    public function action_index()
    {
        $this->template->title = __('Zenith');

        if($this->request->post())
        {
            Model_Config::set_value('payment', 'zenith_merchantid', Core::post('zenith_merchantid'));
            Model_Config::set_value('payment', 'zenith_uid', Core::post('zenith_uid'));
            Model_Config::set_value('payment', 'zenith_pwd', Core::post('zenith_pwd'));
            Model_Config::set_value('payment', 'zenith_testing', Core::post('zenith_testing') ?? 0);
            Model_Config::set_value('payment', 'zenith_merchant_name', Core::post('zenith_merchant_name'));
            Model_Config::set_value('payment', 'zenith_merchant_phone', Core::post('zenith_merchant_phone'));

            Alert::set(Alert::SUCCESS, __('Configuration updated'));

            $this->redirect(Route::url('oc-panel/integrations', ['controller' => 'zenith']));
        }

        return $this->template->content = View::factory('oc-panel/pages/integrations/zenith', [
        ]);
    }
}
