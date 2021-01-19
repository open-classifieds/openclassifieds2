<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Panel_Addons_EWallet extends Auth_Controller {

    public function action_index()
    {
        $this->template->title = __('eWallet');

        if($this->request->post())
        {
            Model_Config::set_value('general', 'ewallet', Core::post('is_active') ?? 0);
            Model_Config::set_value('general', 'ewallet_add_money', Core::post('add_money') ?? 0);
            Model_Config::set_value('general', 'ewallet_gamification', Core::post('gamification') ?? 0);
            Model_Config::set_value('general', 'ewallet_gamification_earn_on_sign_up', Core::post('ewallet_gamification_earn_on_sign_up', NULL));
            Model_Config::set_value('general', 'ewallet_money_symbol', Core::post('money_symbol', NULL));
            Model_Config::set_value('general', 'ewallet_mark_as_received_reminder_after_n_days', Core::post('mark_as_received_reminder_after_n_days', NULL));
            Model_Config::set_value('general', 'ewallet_mark_as_received_after_n_days', Core::post('mark_as_received_after_n_days', NULL));
            Model_Config::set_value('payment', 'paypal_seller', Core::post('buy_now') ?? 0);

            Alert::set(Alert::SUCCESS, __('Configuration updated'));

            $this->redirect(Route::url('oc-panel/addons', ['controller' => 'ewallet']));
        }

        return $this->template->content = View::factory('oc-panel/pages/addons/ewallet/index', [
            'is_active' => (bool) Core::config('general.ewallet'),
            'money_packages' => Model_Transaction::get_money_packages(),
        ]);
    }

    public function action_delete_money_package()
    {
        if (! is_numeric(Core::get('delete_package')))
        {
            return;
        }

        Model_Transaction::delete_money_package(Core::get('delete_package'));

        $this->redirect(Route::url('oc-panel/addons', ['controller' => 'ewallet']));
    }

    public function action_update_money_package()
    {
        if (! is_numeric(Core::post('money')))
        {
            return;
        }

        if (! is_numeric(Core::post('price')))
        {
            return;
        }

        Model_Transaction::set_money_package(Core::request('money'), Core::request('price'), Core::request('money_key'));

        Alert::set(Alert::SUCCESS, __('Money package updated'));

        $this->redirect(Route::url('oc-panel/addons', ['controller' => 'ewallet']));
    }
}
