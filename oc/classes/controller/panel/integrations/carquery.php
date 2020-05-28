<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Panel_Integrations_CarQuery extends Auth_Controller {

    public function action_index()
    {
        $this->template->title = __('Carquery');

        if($this->request->post())
        {
            Model_Config::set_value('general', 'carquery', Core::post('is_active') ?? 0);

            Alert::set(Alert::SUCCESS, __('Configuration updated'));

            $this->redirect(Route::url('oc-panel/integrations', ['controller' => 'carquery']));
        }

        return $this->template->content = View::factory('oc-panel/pages/integrations/carquery', [
            'is_active' => (bool) Core::config('general.carquery')
        ]);
    }
}
