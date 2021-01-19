<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Panel_Integrations_Mailgun extends Auth_Controller {

    public function action_index()
    {
        $this->template->title = __('Mailgun');

        if($this->request->post())
        {
            Model_Config::set_value('email', 'mailgun_api_key', Core::post('mailgun_api_key'));
            Model_Config::set_value('email', 'mailgun_domain', Core::post('mailgun_domain'));

            Alert::set(Alert::SUCCESS, __('Configuration updated'));

            $this->redirect(Route::url('oc-panel/integrations', ['controller' => 'mailgun']));
        }

        return $this->template->content = View::factory('oc-panel/pages/integrations/mailgun', [
            'is_active' => (bool) Core::config('email.mailgun_api_key')
        ]);
    }
}
