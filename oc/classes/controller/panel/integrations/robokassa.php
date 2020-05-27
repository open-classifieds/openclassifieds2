<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Panel_Integrations_Robokassa extends Auth_Controller {

    public function action_index()
    {
        $this->template->title = __('Robokassa');

        if($this->request->post())
        {
            Model_Config::set_value('payment', 'robokassa_login', Core::post('robokassa_login'));
            Model_Config::set_value('payment', 'robokassa_pass1', Core::post('robokassa_pass1'));
            Model_Config::set_value('payment', 'robokassa_pass2', Core::post('robokassa_pass2'));
            Model_Config::set_value('payment', 'robokassa_testing', Core::post('robokassa_testing') ?? 0);

            Alert::set(Alert::SUCCESS, __('Configuration updated'));

            $this->redirect(Route::url('oc-panel/integrations', ['controller' => 'robokassa']));
        }

        return $this->template->content = View::factory('oc-panel/pages/integrations/robokassa', [
        ]);
    }
}
