<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Panel_Integrations_Mercadopago extends Auth_Controller {

    public function action_index()
    {
        $this->template->title = __('Mercadopago');

        if($this->request->post())
        {
            Model_Config::set_value('payment', 'mercadopago_client_id', Core::post('mercadopago_client_id'));
            Model_Config::set_value('payment', 'mercadopago_client_secret', Core::post('mercadopago_client_secret'));

            Alert::set(Alert::SUCCESS, __('Configuration updated'));

            $this->redirect(Route::url('oc-panel/integrations', ['controller' => 'mercadopago']));
        }

        return $this->template->content = View::factory('oc-panel/pages/integrations/mercadopago', [
        ]);
    }
}
