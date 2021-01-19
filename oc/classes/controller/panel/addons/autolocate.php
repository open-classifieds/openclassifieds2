<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Panel_Addons_Autolocate extends Auth_Controller {

    public function action_index()
    {
        $this->template->title = __('Auto locate');

        if($this->request->post())
        {
            Model_Config::set_value('general', 'auto_locate', Core::post('is_active') ?? 0);

            Alert::set(Alert::SUCCESS, __('Configuration updated'));

            $this->redirect(Route::url('oc-panel/addons', ['controller' => 'autolocate']));
        }

        return $this->template->content = View::factory('oc-panel/pages/addons/auto-locate/index', [
            'is_active' => (bool) Core::config('general.auto_locate'),
        ]);
    }
}
