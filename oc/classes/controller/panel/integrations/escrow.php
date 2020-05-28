<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Panel_Integrations_Escrow extends Auth_Controller {

    public function action_index()
    {
        $this->template->title = __('Escrow');

        if($this->request->post())
        {
            Model_Config::set_value('payment', 'escrow_pay', Core::post('is_active') ?? 0);
            Model_Config::set_value('payment', 'escrow_sandbox', Core::post('escrow_sandbox') ?? 0);

            Alert::set(Alert::SUCCESS, __('Configuration updated'));

            $this->redirect(Route::url('oc-panel/integrations', ['controller' => 'escrow']));
        }

        return $this->template->content = View::factory('oc-panel/pages/integrations/escrow', [
            'is_active' => (bool) Core::config('payment.escrow_pay')
        ]);
    }
}
