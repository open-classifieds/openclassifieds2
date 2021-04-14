<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Panel_Integrations_Clickatell extends Auth_Controller {

    public function action_index()
    {
        $this->template->title = __('Clickatell');

        $validation = $this->validation();

        if($this->request->post() AND $validation->check())
        {
            if (Core::post('is_active') == 1)
            {
                if(!empty(Core::post('sms_clickatell_api')) OR (!Core::post('sms_clickatell_api') == NULL))
                {
                    $test_sms_auth = Clickatell::testAPIkey(Core::post('sms_clickatell_api'), Core::post('sms_clickatell_two_way_phone'));

                    if($test_sms_auth == FALSE){
                        // disable sms_auth
                        $this->request->post('is_active', 0);

                        Alert::set(Alert::ALERT, '2 Step SMS Authentication was not enabled. Please configure <a href="//docs.yclas.com/2-step-sms-authentication/">Clickatell</a> to enable 2 Step SMS Authentication!');
                    } else {
                        Alert::set(Alert::SUCCESS, '2 Step SMS Authentication activated');
                    }
                }
                else
                {
                    $this->request->post('is_active', 0);

                    Alert::set(Alert::ALERT, '2 Step SMS Authentication was not enabled. Please configure <a href="//docs.yclas.com/2-step-sms-authentication/">Clickatell</a> to enable 2 Step SMS Authentication!');
                }
            }

            Model_Config::set_value('general', 'sms_auth', Core::post('is_active') ?? 0);
            Model_Config::set_value('general', 'sms_clickatell_api', Core::post('sms_clickatell_api'));
            Model_Config::set_value('general', 'sms_clickatell_two_way_phone', Core::post('sms_clickatell_two_way_phone'));

            if (Core::post('is_active') ?? 0)
            {
                Model_Config::set_value('general', 'sms_service', 'clickatell');
            }

            Alert::set(Alert::SUCCESS, __('Configuration updated'));

            $this->redirect(Route::url('oc-panel/integrations', ['controller' => 'clickatell']));
        }

        return $this->template->content = View::factory('oc-panel/pages/integrations/clickatell', [
            'errors' => $validation->errors('validation'),
            'is_active' => (bool) Core::config('general.sms_auth')
        ]);
    }

    private function validation()
    {
        $validation = Validation::factory($this->request->post());

        if ((bool) Core::post('is_active') ?? 0)
        {
            $validation->rule('sms_clickatell_api', 'not_empty')
                ->rule('sms_clickatell_two_way_phone', 'not_empty');
        }

        return $validation;
    }
}
