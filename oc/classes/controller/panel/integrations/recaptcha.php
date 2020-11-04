<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Panel_Integrations_ReCaptcha extends Auth_Controller {

    public function action_index()
    {
        $this->template->title = __('reCaptcha');

        $validation = $this->validation();

        if($this->request->post() AND $validation->check())
        {
            Model_Config::set_value('general', 'recaptcha_active', Core::post('is_active') ?? 0);
            Model_Config::set_value('general', 'recaptcha_type', Core::post('recaptcha_type') ?? 0);
            Model_Config::set_value('general', 'recaptcha_sitekey', Core::post('recaptcha_sitekey'));
            Model_Config::set_value('general', 'recaptcha_secretkey', Core::post('recaptcha_secretkey'));

            Alert::set(Alert::SUCCESS, __('Configuration updated'));

            $this->redirect(Route::url('oc-panel/integrations', ['controller' => 'recaptcha']));
        }

        return $this->template->content = View::factory('oc-panel/pages/integrations/recaptcha', [
            'errors' => $validation->errors('validation'),
            'is_active' => (bool) Core::config('general.recaptcha_active')
        ]);
    }

    private function validation()
    {
        $validation = Validation::factory($this->request->post());

        if ((bool) Core::post('is_active') ?? 0)
        {
            $validation->rule('recaptcha_sitekey', 'not_empty')
                ->rule('recaptcha_secretkey', 'not_empty');
        }

        return $validation;
    }
}
