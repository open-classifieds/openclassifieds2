<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Panel_Integrations_Algolia extends Auth_Controller {

    public function action_index()
    {
        $this->template->title = __('Algolia');

        $validation = $this->validation();

        if($this->request->post() AND $validation->check())
        {
            Model_Config::set_value('general', 'algolia_search', Core::post('is_active') ?? 0);
            Model_Config::set_value('general', 'algolia_search_application_id', Core::post('algolia_search_application_id'));
            Model_Config::set_value('general', 'algolia_search_admin_key', Core::post('algolia_search_admin_key'));
            Model_Config::set_value('general', 'algolia_search_only_key', Core::post('algolia_search_only_key'));

            Alert::set(Alert::SUCCESS, __('Configuration updated'));

            $this->redirect(Route::url('oc-panel/integrations', ['controller' => 'algolia']));
        }

        return $this->template->content = View::factory('oc-panel/pages/integrations/algolia', [
            'errors' => $validation->errors('validation'),
            'is_active' => (bool) Core::config('general.algolia_search')
        ]);
    }

    private function validation()
    {
        $validation = Validation::factory($this->request->post());

        if ((bool) Core::post('is_active') ?? 0)
        {
            $validation->rule('algolia_search_application_id', 'not_empty')
                ->rule('algolia_search_admin_key', 'not_empty')
                ->rule('algolia_search_only_key', 'not_empty');
        }

        return $validation;
    }
}
