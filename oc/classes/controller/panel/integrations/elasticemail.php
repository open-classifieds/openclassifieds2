<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Panel_Integrations_ElasticEmail extends Auth_Controller {

    public function action_index()
    {
        $this->template->title = __('Elastic Email');

        if($this->request->post())
        {
            Model_Config::set_value('email', 'elastic_username', Core::post('elastic_username'));
            Model_Config::set_value('email', 'elastic_password', Core::post('elastic_password'));
            Model_Config::set_value('email', 'elastic_listname', Core::post('elastic_listname'));

            Alert::set(Alert::SUCCESS, __('Configuration updated'));

            $this->redirect(Route::url('oc-panel/integrations', ['controller' => 'elasticemail']));
        }

        return $this->template->content = View::factory('oc-panel/pages/integrations/elasticemail', [
            'is_active' => (bool) Core::config('email.elastic_username')
        ]);
    }
}
