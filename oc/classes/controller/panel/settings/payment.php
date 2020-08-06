<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Panel_Settings_Payment extends Auth_Controller {

    public function action_index()
    {
        $this->template->title = __('Payment settings');

        $validation = $this->validation();

        if ($this->request->post() AND $validation->check())
        {
            $this->validate_vat();

            $this->store_settings($validation->data());

            Alert::set(Alert::SUCCESS, __('Configuration updated'));

            $this->redirect(Route::url('oc-panel/settings', ['controller' => 'payment']));
        }

        return $this->template->content = View::factory('oc-panel/pages/settings/payment', [
            'errors' => $validation->errors('validation'),
            'page_options' => $this->page_options(),
            'featured_plans' => Model_Order::get_featured_plans(),
        ]);
    }

    public function action_delete_featured_plan()
    {
        if (! is_numeric(Core::get('delete_plan')))
        {
            return;
        }

        Model_Order::delete_featured_plan(Core::get('delete_plan'));

        $this->redirect(Route::url('oc-panel/settings',['controller'=>'payment']));
    }

    public function action_update_featured_plan()
    {
        if (! is_numeric(Core::post('featured_days')))
        {
            return;
        }

        if (! is_numeric(Core::post('featured_price')))
        {
            return;
        }

        Model_Order::set_featured_plan(Core::request('featured_days'),Core::request('featured_price'),Core::request('featured_days_key'));

        Alert::set(Alert::SUCCESS, __('Featured plan updated'));

        $this->redirect(Route::url('oc-panel/settings',['controller'=>'payment']));
    }

    private function store_settings($data)
    {
        Model_Config::set_value('payment', 'paypal_currency', $data['paypal_currency']);
        Model_Config::set_value('payment', 'to_featured', $data['to_featured'] ?? 0);
        Model_Config::set_value('payment', 'to_top', $data['to_top'] ?? 0);
        Model_Config::set_value('payment', 'pay_to_go_on_top', str_replace(',', '.', $data['pay_to_go_on_top']));
        Model_Config::set_value('payment', 'alternative', $data['alternative']);
        Model_Config::set_value('payment', 'vat_country', $data['vat_country']);
        Model_Config::set_value('payment', 'vat_number', $data['vat_number']);
        Model_Config::set_value('payment', 'vat_non_eu', $data['vat_non_eu']);
        Model_Config::set_value('general', 'moderation', $data['moderation']);

        if (Core::extra_features() == TRUE)
        {
            Model_Config::set_value('payment', 'stock', $data['stock'] ?? 0);
        }
    }

    private function validation()
    {
        return Validation::factory($this->request->post())
            ->rule('pay_to_go_on_top', 'not_empty')
            ->rule('pay_to_go_on_top', 'price')
            ->rule('to_featured', 'range', [':value', 0, 1])
            ->rule('to_top', 'range', [':value', 0, 1])
            ->rule('stock', 'range', [':value', 0, 1]);
    }

    private function validate_vat()
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
    }

    private function page_options()
    {
        $page_options = ['' => __('Deactivated')];

        foreach (Model_Content::get_pages() as $page)
        {
            $page_options[$page->seotitle] = $page->title;
        }

        return $page_options;
    }
}
