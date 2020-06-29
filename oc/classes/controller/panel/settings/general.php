<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Panel_Settings_General extends Auth_Controller {

    public function action_index()
    {
        $this->template->title = __('General settings');

        if($this->request->post())
        {
            $validation = Validation::factory($this->request->post())
                ->rule('maintenance', 'range', [':value', 0, 1])
                ->rule('private_site', 'range', [':value', 0, 1])
                ->rule('disallowbots', 'range', [':value', 0, 1])
                ->rule('cookie_consent', 'range', [':value', 0, 1])
                ->rule('site_name', 'not_empty')
                ->rule('moderation', 'not_empty')
                ->rule('moderation', 'range', [':value', 0, 5])
                ->rule('search_by_description', 'range', [':value', 0, 1]);

            if (!$validation->check())
            {
                foreach ($validation->errors('config') as $error)
                {
                    Alert::set(Alert::ALERT, $error);
                }

                $this->redirect(Route::url('oc-panel/settings', ['controller' => 'general']));
            }

            if (Core::post('maintenance') ?? 0 == 0)
            {
                Alert::del('maintenance');
            }

            if (Core::post('private_site') ?? 0 == 0)
            {
                Alert::del('private_site');
            }

            if (Core::post('multilingual') == 1)
            {
                //set i18n.locale as default language to all the ads
                DB::update('ads')
                    ->set(['locale' => core::config('i18n.locale')])
                    ->where('locale', 'IS', NULL)
                    ->execute();
            }

            Model_Config::set_value('general', 'maintenance', Core::post('maintenance') ?? 0);
            Model_Config::set_value('general', 'site_name', Core::post('site_name'));
            Model_Config::set_value('general', 'site_description', Core::post('site_description'));
            Model_Config::set_value('general', 'disallowbots', Core::post('disallowbots') ?? 0);
            Model_Config::set_value('general', 'private_site', Core::post('private_site') ?? 0);
            Model_Config::set_value('general', 'cookie_consent', Core::post('cookie_consent') ?? 0);
            Model_Config::set_value('general', 'moderation', Core::post('moderation'));
            Model_Config::set_value('general', 'landing_page', Core::post('landing_page'));
            Model_Config::set_value('general', 'alert_terms', Core::post('alert_terms'));
            Model_Config::set_value('general', 'private_site_page', Core::post('private_site_page'));
            Model_Config::set_value('general', 'contact_page', Core::post('contact_page'));
            Model_Config::set_value('general', 'email_domains', Core::post('email_domains'));
            Model_Config::set_value('general', 'disallowed_email_domains', Core::post('disallowed_email_domains'));
            Model_Config::set_value('general', 'api_key', Core::post('api_key'));
            Model_Config::set_value('general', 'html_head', Kohana::$_POST_ORIG['html_head']);
            Model_Config::set_value('general', 'html_footer', Kohana::$_POST_ORIG['html_footer']);
            Model_Config::set_value('general', 'country', Core::post('country'));
            Model_Config::set_value('general', 'multilingual', Core::post('multilingual') ?? 0);
            Model_Config::set_value('general', 'languages', Core::post('languages'));
            Model_Config::set_value('general', 'number_format', Core::post('number_format'));
            Model_Config::set_value('general', 'date_format', Core::post('date_format'));
            Model_Config::set_value('i18n', 'timezone', Core::post('timezone'));
            Model_Config::set_value('general', 'measurement', Core::post('measurement'));
            Model_Config::set_value('general', 'search_by_description', Core::post('search_by_description') ?? 0);
            Model_Config::set_value('general', 'search_multi_catloc', Core::post('search_multi_catloc') ?? 0);

            if (Core::is_cloud())
            {
                Model_Config::set_value('general', 'adstxt', Core::post('adstxt'));
                Model_Config::set_value('general', 'robotstxt', Core::post('robotstxt'));
            }

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

        $page_options = ['' => __('Deactivated')];

        foreach (Model_Content::get_pages() as $page)
        {
            $page_options[$page->seotitle] = $page->title;
        }

        return $this->template->content = View::factory('oc-panel/pages/settings/general', [
            'page_options' => $page_options,
        ]);
    }
}
