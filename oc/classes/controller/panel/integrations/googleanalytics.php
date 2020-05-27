<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Panel_Integrations_GoogleAnalytics extends Auth_Controller {

    public function action_index()
    {
        $this->template->title = __('Google Analytics');

        if($this->request->post())
        {
            Model_Config::set_value('general', 'analytics', Core::post('analytics'));

            Alert::set(Alert::SUCCESS, __('Configuration updated'));

            $this->redirect(Route::url('oc-panel/integrations', ['controller' => 'googleanalytics']));
        }

        return $this->template->content = View::factory('oc-panel/pages/integrations/google-analytics', [
            'is_active' => (bool) Core::config('general.analytics')
        ]);
    }
}
