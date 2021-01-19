<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Panel_Addons_AddToHomeScreen extends Auth_Controller {

    public function action_index()
    {
        $this->template->title = __('Add to home screen');

        if($this->request->post())
        {
            if (empty(Theme::get('apple-touch-icon')))
            {
                Alert::set(Alert::WARNING, __('Please, first upload your apple-touch-icon icon on theme options.'));

                $this->redirect(Route::url('oc-panel/addons', ['controller' => 'addtohomescreen']));
            }

            Model_Config::set_value('general', 'add_to_home_screen', Core::post('is_active') ?? 0);

            Alert::set(Alert::SUCCESS, __('Configuration updated'));

            $this->redirect(Route::url('oc-panel/addons', ['controller' => 'addtohomescreen']));
        }

        return $this->template->content = View::factory('oc-panel/pages/addons/add-to-home-screen/index', [
            'is_active' => (bool) Core::config('general.add_to_home_screen'),
        ]);
    }
}
