<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Panel_Settings_Payment extends Auth_Controller {

    public function action_index()
    {
        $this->template->title = __('Payment settings');

        if($this->request->post())
        {
            if(Core::request('vat_country') AND Core::request('vat_number'))
            {
                // if is an eu country check if VAT number is valid
                if (euvat::is_eu_country(Core::request('vat_country')) AND !euvat::verify_vies(Core::request('vat_number'),Core::request('vat_country')))
                {
                    Alert::set(Alert::ERROR, __('Invalid EU Vat Number, please verify number and country match'));

                    $this->redirect(Route::url('oc-panel/settings', ['controller' => 'payment']));
                }
                // if is a non-eu country, check if VAT rate is filled and greater than 0
                elseif(!euvat::is_eu_country(Core::request('vat_country')) AND (!Core::request('vat_non_eu') OR Core::request('vat_non_eu') < 0))
                {
                    Alert::set(Alert::ERROR, __('Please enter a valid VAT rate.'));

                    $this->redirect(Route::url('oc-panel/settings', ['controller' => 'payment']));
                }
            }

            $validation = Validation::factory($this->request->post())
                ->rule('pay_to_go_on_top', 'not_empty')
                ->rule('pay_to_go_on_top', 'price')
                ->rule('to_featured', 'range', array(':value', 0, 1))
                ->rule('to_top', 'range', array(':value', 0, 1))
                ->rule('stock', 'range', array(':value', 0, 1));

            if (!$validation->check())
            {
                foreach ($validation->errors('config') as $error)
                {
                    Alert::set(Alert::ALERT, $error);
                }

                $this->redirect(Route::url('oc-panel/settings', ['controller' => 'payment']));
            }

            Model_Config::set_value('payment', 'paypal_currency', Core::post('paypal_currency'));
            Model_Config::set_value('payment', 'to_featured', Core::post('to_featured') ?? 0);
            Model_Config::set_value('payment', 'to_top', Core::post('to_top') ?? 0);
            Model_Config::set_value('payment', 'pay_to_go_on_top', str_replace(',', '.', Core::post('pay_to_go_on_top')));
            Model_Config::set_value('payment', 'alternative', Core::post('alternative'));
            Model_Config::set_value('payment', 'vat_country', Core::post('vat_country'));
            Model_Config::set_value('payment', 'vat_number', Core::post('vat_number'));
            Model_Config::set_value('payment', 'vat_non_eu', Core::post('vat_non_eu'));
            Model_Config::set_value('payment', 'stock', Core::post('stock') ?? 0);

            Alert::set(Alert::SUCCESS, __('Configuration updated'));

            $this->redirect(Route::url('oc-panel/settings', ['controller' => 'payment']));
        }

        $page_options = ['' => __('Deactivated')];

        foreach (Model_Content::get_pages() as $page)
        {
            $page_options[$page->seotitle] = $page->title;
        }

        return $this->template->content = View::factory('oc-panel/pages/settings/payment', [
            'page_options' => $page_options,
        ]);
    }
}
