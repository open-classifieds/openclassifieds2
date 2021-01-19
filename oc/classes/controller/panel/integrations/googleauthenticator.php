<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Panel_Integrations_GoogleAuthenticator extends Auth_Controller {

    public function action_index()
    {
        $this->template->title = __('Google Authenticator');

        if($this->request->post())
        {
            Model_Config::set_value('general', 'google_authenticator', Core::post('is_active') ?? 0);

            Alert::set(Alert::SUCCESS, __('Configuration updated'));

            $this->redirect(Route::url('oc-panel/integrations', ['controller' => 'googleauthenticator']));
        }

        return $this->template->content = View::factory('oc-panel/pages/integrations/google-authenticator', [
            'is_active' => (bool) Core::config('general.google_authenticator')
        ]);
    }
}
