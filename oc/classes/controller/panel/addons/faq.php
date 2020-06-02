<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Panel_Addons_Faq extends Auth_Controller {

    public function action_index()
    {
        $this->template->title = __('Faq');

        if ($this->request->post() AND Core::extra_features() == FALSE)
        {
            Alert::set(Alert::WARNING, __('This feature is only available in the PRO version!') . ' ' . __('Upgrade your Yclas site to activate this feature.'));
            $this->redirect(Route::url('oc-panel/addons', ['controller' => 'faq']));
        }

        if($this->request->post())
        {
            Model_Config::set_value('general', 'faq', Core::post('is_active') ?? 0);

            Alert::set(Alert::SUCCESS, __('Configuration updated'));

            $this->redirect(Route::url('oc-panel/addons', ['controller' => 'faq']));
        }

        return $this->template->content = View::factory('oc-panel/pages/addons/faq/index', [
            'is_active' => (bool) Core::config('general.faq')
        ]);
    }
}
