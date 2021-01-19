<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Panel_Integrations_Paguelofacil extends Auth_Controller {

    public function action_index()
    {
        $this->template->title = __('Paguelofacil');

        if($this->request->post())
        {
            Model_Config::set_value('payment', 'paguelofacil_cclw', Core::post('paguelofacil_cclw'));
            Model_Config::set_value('payment', 'paguelofacil_testing', Core::post('paguelofacil_testing') ?? 0);

            Alert::set(Alert::SUCCESS, __('Configuration updated'));

            $this->redirect(Route::url('oc-panel/integrations', ['controller' => 'paguelofacil']));
        }

        return $this->template->content = View::factory('oc-panel/pages/integrations/paguelofacil', [
        ]);
    }
}
