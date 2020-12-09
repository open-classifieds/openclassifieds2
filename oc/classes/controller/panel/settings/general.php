<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Panel_Settings_General extends Auth_Controller {

    public function action_index()
    {
        $this->template->title = __('General settings');

        $validation = $this->validation();

        if ($this->request->post() AND $validation->check())
        {
            $this->store_settings($validation->data());

            Alert::set(Alert::SUCCESS, __('Configuration updated'));

            //set model domain maintenance to current
            if (Core::is_cloud() AND Model_Domain::current()->maintenance != Core::post('maintenance') ?? 0)
            {
                Model_Domain::current()->maintenance = Core::post('maintenance') ?? 0;

                try
                {
                    Model_Domain::current()->save();
                }
                catch(Exception $e)
                {
                    throw HTTP_Exception::factory(500,$e->getMessage());
                }
            }

            $this->redirect(Route::url('oc-panel/settings', ['controller' => 'general']));
        }

        return $this->template->content = View::factory('oc-panel/pages/settings/general', [
            'errors' => $validation->errors('validation'),
            'page_options' => $this->page_options(),
        ]);
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

    private function validation()
    {
        return Validation::factory($this->request->post())
            ->rule('maintenance', 'range', [':value', 0, 1])
            ->rule('private_site', 'range', [':value', 0, 1])
            ->rule('disallowbots', 'range', [':value', 0, 1])
            ->rule('cookie_consent', 'range', [':value', 0, 1])
            ->rule('site_name', 'not_empty')
            ->rule('moderation', 'not_empty')
            ->rule('moderation', 'range', [':value', 0, 5])
            ->rule('search_by_description', 'range', [':value', 0, 1]);
    }

    private function store_settings($data)
    {
        if (Core::post('multilingual') == 1)
        {
            //set i18n.locale as default language to all the ads
            DB::update('ads')
                ->set(['locale' => core::config('i18n.locale')])
                ->where('locale', 'IS', NULL)
                ->execute();
        }

        Model_Config::set_value('general', 'maintenance', $data['maintenance'] ?? 0);
        Model_Config::set_value('general', 'site_name', $data['site_name']);
        Model_Config::set_value('general', 'site_description', $data['site_description']);
        Model_Config::set_value('general', 'disallowbots', $data['disallowbots'] ?? 0);
        Model_Config::set_value('general', 'private_site', $data['private_site'] ?? 0);
        Model_Config::set_value('general', 'cookie_consent', $data['cookie_consent'] ?? 0);
        Model_Config::set_value('general', 'users_must_verify_email', $data['users_must_verify_email'] ?? 0);
        Model_Config::set_value('general', 'moderation', $data['moderation']);
        Model_Config::set_value('general', 'landing_page', $data['landing_page']);
        Model_Config::set_value('general', 'alert_terms', $data['alert_terms']);
        Model_Config::set_value('general', 'private_site_page', $data['private_site_page']);
        Model_Config::set_value('general', 'contact_page', $data['contact_page']);
        Model_Config::set_value('general', 'email_domains', $data['email_domains']);
        Model_Config::set_value('general', 'disallowed_email_domains', $data['disallowed_email_domains']);
        Model_Config::set_value('general', 'api_key', $data['api_key']);
        Model_Config::set_value('general', 'html_head', Kohana::$_POST_ORIG['html_head']);
        Model_Config::set_value('general', 'html_footer', Kohana::$_POST_ORIG['html_footer']);
        Model_Config::set_value('general', 'country', $data['country']);
        Model_Config::set_value('general', 'multilingual', $data['multilingual'] ?? 0);
        Model_Config::set_value('general', 'languages', $data['languages']);
        Model_Config::set_value('general', 'number_format', $data['number_format']);
        Model_Config::set_value('general', 'date_format', $data['date_format']);
        Model_Config::set_value('i18n', 'timezone', $data['timezone']);
        Model_Config::set_value('general', 'measurement', $data['measurement']);
        Model_Config::set_value('general', 'search_by_description', $data['search_by_description'] ?? 0);
        Model_Config::set_value('general', 'search_multi_catloc', $data['search_multi_catloc'] ?? 0);

        if (Core::is_cloud())
        {
            Model_Config::set_value('general', 'adstxt', $data['adstxt']);
            Model_Config::set_value('general', 'robotstxt', $data['robotstxt']);
        }
    }
}
