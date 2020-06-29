<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Panel_Settings_Advertisement extends Auth_Controller {

    public function action_index()
    {
        $this->template->title = __('Advertisement settings');

        if($this->request->post())
        {
            $validation = Validation::factory($this->request->post())
                ->rule('advertisements_per_page', 'not_empty')
                ->rule('advertisements_per_page', 'digit')
                ->rule('feed_elements', 'not_empty')
                ->rule('feed_elements', 'digit')
                ->rule('map_elements', 'not_empty')
                ->rule('map_elements', 'digit')
                ->rule('sort_by', 'not_empty')
                ->rule('ads_in_home', 'not_empty')
                ->rule('ads_in_home', 'range', array(':value', 0, 4))
                ->rule('login_to_post', 'range', array(':value', 0, 1))
                ->rule('only_admin_post', 'range', array(':value', 0, 1))
                ->rule('expire_date', 'not_empty')
                ->rule('expire_date', 'digit')
                ->rule('parent_category', 'range', array(':value', 0, 1))
                ->rule('captcha', 'range', array(':value', 0, 1))
                ->rule('address', 'range', array(':value', 0, 1))
                ->rule('phone', 'range', array(':value', 0, 1))
                ->rule('website', 'range', array(':value', 0, 1))
                ->rule('location', 'range', array(':value', 0, 1))
                ->rule('price', 'range', array(':value', 0, 1))
                ->rule('upload_file', 'range', array(':value', 0, 1))
                ->rule('num_images', 'not_empty')
                ->rule('num_images', 'digit')
                ->rule('contact', 'range', array(':value', 0, 1))
                ->rule('login_to_contact', 'range', array(':value', 0, 1))
                ->rule('qr_code', 'range', array(':value', 0, 1))
                ->rule('count_visits', 'range', array(':value', 0, 1))
                ->rule('related', 'not_empty')
                ->rule('related', 'digit');


            if (!$validation->check())
            {
                foreach ($validation->errors('config') as $error)
                {
                    Alert::set(Alert::ALERT, $error);
                }

                $this->redirect(Route::url('oc-panel/settings', ['controller' => 'advertisement']));
            }

            Model_Config::set_value('advertisement', 'advertisements_per_page', Core::post('advertisements_per_page'));
            Model_Config::set_value('advertisement', 'feed_elements', Core::post('feed_elements'));
            Model_Config::set_value('advertisement', 'map_elements', Core::post('map_elements'));
            Model_Config::set_value('advertisement', 'sort_by', Core::post('sort_by'));
            Model_Config::set_value('advertisement', 'ads_in_home', Core::post('ads_in_home'));
            Model_Config::set_value('advertisement', 'delete_ad', Core::post('delete_ad') ?? 0);

            Model_Config::set_value('advertisement', 'login_to_post', Core::post('login_to_post') ?? 0);
            Model_Config::set_value('advertisement', 'only_admin_post', Core::post('only_admin_post') ?? 0);
            Model_Config::set_value('advertisement', 'expire_date', Core::post('expire_date'));
            Model_Config::set_value('advertisement', 'expire_reactivation', Core::post('expire_reactivation') ?? 0);
            Model_Config::set_value('advertisement', 'parent_category', Core::post('parent_category') ?? 0);
            Model_Config::set_value('advertisement', 'description_bbcode', Core::post('description_bbcode') ?? 0);
            Model_Config::set_value('advertisement', 'captcha', Core::post('captcha') ?? 0);
            Model_Config::set_value('advertisement', 'leave_alert', Core::post('leave_alert') ?? 0);
            Model_Config::set_value('advertisement', 'tos', Core::post('tos'));
            Model_Config::set_value('advertisement', 'thanks_page', Core::post('thanks_page'));
            Model_Config::set_value('advertisement', 'banned_words', Core::post('banned_words'));
            Model_Config::set_value('advertisement', 'validate_banned_words', Core::post('validate_banned_words') ?? 0);
            Model_Config::set_value('advertisement', 'banned_words_among', Core::post('banned_words_among') ?? 0);
            Model_Config::set_value('advertisement', 'banned_words_replacement', Core::post('banned_words_replacement'));

            Model_Config::set_value('advertisement', 'description', Core::post('description') ?? 0);
            Model_Config::set_value('advertisement', 'phone', Core::post('phone') ?? 0);
            Model_Config::set_value('advertisement', 'location', Core::post('location') ?? 0);

            $num_images = Core::post('num_images');

            if (Core::is_cloud())
            {
                $num_images = $num_images > Model_Domain::current()->num_images_ad ? Model_Domain::current()->num_images_ad : $num_images;
            }

            Model_Config::set_value('advertisement', 'num_images', $num_images);

            Model_Config::set_value('advertisement', 'address', Core::post('address') ?? 0);
            Model_Config::set_value('advertisement', 'website', Core::post('website') ?? 0);
            Model_Config::set_value('advertisement', 'price', Core::post('price') ?? 0);
            Model_Config::set_value('advertisement', 'upload_file', Core::post('upload_file') ?? 0);

            Model_Config::set_value('advertisement', 'contact', Core::post('contact') ?? 0);
            Model_Config::set_value('advertisement', 'login_to_view_ad', Core::post('login_to_view_ad') ?? 0);
            Model_Config::set_value('advertisement', 'login_to_contact', Core::post('login_to_contact') ?? 0);
            Model_Config::set_value('advertisement', 'qr_code', Core::post('qr_code') ?? 0);
            Model_Config::set_value('advertisement', 'count_visits', Core::post('count_visits') ?? 0);
            Model_Config::set_value('advertisement', 'sharing', Core::post('sharing') ?? 0);
            Model_Config::set_value('advertisement', 'report', Core::post('report') ?? 0);
            Model_Config::set_value('advertisement', 'related', Core::post('related'));
            Model_Config::set_value('advertisement', 'free', Core::post('free') ?? 0);
            Model_Config::set_value('advertisement', 'rich_snippets', Core::post('rich_snippets') ?? 0);

            Alert::set(Alert::SUCCESS, __('Configuration updated'));

            $this->redirect(Route::url('oc-panel/settings', ['controller' => 'advertisement']));
        }

        $page_options = ['' => __('Deactivated')];

        foreach (Model_Content::get_pages() as $page)
        {
            $page_options[$page->seotitle] = $page->title;
        }

        return $this->template->content = View::factory('oc-panel/pages/settings/advertisement', [
            'page_options' => $page_options,
        ]);
    }
}
