<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Panel_Integrations_Akismet extends Auth_Controller {

    public function action_index()
    {
        $this->template->title = __('Akismet');

        if($this->request->post())
        {
            Model_Config::set_value('general', 'akismet_key', Core::post('akismet_key'));

            Alert::set(Alert::SUCCESS, __('Configuration updated'));

            $this->redirect(Route::url('oc-panel/integrations', ['controller' => 'akismet']));
        }

        return $this->template->content = View::factory('oc-panel/pages/integrations/akismet', [
            'is_active' => (bool) Core::config('general.akismet_key')
        ]);
    }
}
