<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Panel_Settings_Advertisement extends Auth_Controller {

    public function action_index()
    {
        $this->template->title = __('Advertisement settings');

        $validation = $this->validation();

        if ($this->request->post() AND $validation->check())
        {
            $this->store_settings($validation->data());

            Alert::set(Alert::SUCCESS, __('Configuration updated'));

            $this->redirect(Route::url('oc-panel/settings', ['controller' => 'advertisement']));
        }

        return $this->template->content = View::factory('oc-panel/pages/settings/advertisement', [
            'errors' => $validation->errors('validation'),
            'page_options' => $this->page_options(),
            'sort_by_options' => $this->sort_by_options(),
            'ads_in_home_options' => $this->ads_in_home_options(),
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

    private function sort_by_options()
    {
        return [
            'title-asc' => __('Name (A-Z)'),
            'title-desc' => __('Name (Z-A)'),
            'price-asc' => __('Price (Low)'),
            'price-desc' => __('Price (High)'),
            'featured' => __('Featured'),
            'rating' => __('Rating'),
            'favorited' => __('Favorited'),
            'published-desc' => __('Newest'),
            'published-asc' => __('Oldest'),
            'distance' => __('Distance'),
            'event-date' => __('Event date'),
        ];
    }

    private function ads_in_home_options()
    {
        $ads_in_home = [
            0 => __('Latest Ads'),
            1 => __('Featured Ads'),
            4 => __('Featured Ads Random'),
            2 => __('Popular Ads last month'),
            3 => __('None'),
        ];

        if (core::config('advertisement.count_visits') == 0)
        {
            unset($ads_in_home[2]);
        }

        return $ads_in_home;
    }

    private function store_settings($data)
    {
        Model_Config::set_value('advertisement', 'advertisements_per_page', $data['advertisements_per_page']);
        Model_Config::set_value('advertisement', 'feed_elements', $data['feed_elements']);
        Model_Config::set_value('advertisement', 'map_elements', $data['map_elements']);
        Model_Config::set_value('advertisement', 'sort_by', $data['sort_by']);
        Model_Config::set_value('advertisement', 'ads_in_home', $data['ads_in_home']);
        Model_Config::set_value('advertisement', 'delete_ad', $data['delete_ad'] ?? 0);

        Model_Config::set_value('advertisement', 'login_to_post', $data['login_to_post'] ?? 0);
        Model_Config::set_value('advertisement', 'only_admin_post', $data['only_admin_post'] ?? 0);
        Model_Config::set_value('advertisement', 'expire_date', $data['expire_date']);
        Model_Config::set_value('advertisement', 'expire_reactivation', $data['expire_reactivation'] ?? 0);
        Model_Config::set_value('advertisement', 'parent_category', $data['parent_category'] ?? 0);
        Model_Config::set_value('advertisement', 'description_bbcode', $data['description_bbcode'] ?? 0);
        Model_Config::set_value('advertisement', 'captcha', $data['captcha'] ?? 0);
        Model_Config::set_value('advertisement', 'leave_alert', $data['leave_alert'] ?? 0);
        Model_Config::set_value('advertisement', 'tos', $data['tos']);
        Model_Config::set_value('advertisement', 'thanks_page', $data['thanks_page']);
        Model_Config::set_value('advertisement', 'banned_words', $data['banned_words']);
        Model_Config::set_value('advertisement', 'validate_banned_words', $data['validate_banned_words'] ?? 0);
        Model_Config::set_value('advertisement', 'banned_words_among', $data['banned_words_among'] ?? 0);
        Model_Config::set_value('advertisement', 'banned_words_replacement', $data['banned_words_replacement']);

        Model_Config::set_value('advertisement', 'description', $data['description'] ?? 0);
        Model_Config::set_value('advertisement', 'phone', $data['phone'] ?? 0);
        Model_Config::set_value('advertisement', 'location', $data['location'] ?? 0);

        Model_Config::set_value('advertisement', 'ads_per_day_limit', isset($data['ads_per_day_enabled']) ? $data['ads_per_day_limit'] : '0');

        if (Core::is_cloud())
        {
            $data['num_images'] = $data['num_images'] > Model_Domain::current()->num_images_ad ? Model_Domain::current()->num_images_ad : $data['num_images'];
        }

        Model_Config::set_value('advertisement', 'num_images', $data['num_images']);

        Model_Config::set_value('advertisement', 'address', $data['address'] ?? 0);
        Model_Config::set_value('advertisement', 'website', $data['website'] ?? 0);
        Model_Config::set_value('advertisement', 'price', $data['price'] ?? 0);
        Model_Config::set_value('advertisement', 'upload_file', $data['upload_file'] ?? 0);

        Model_Config::set_value('advertisement', 'login_to_view_ad', $data['login_to_view_ad'] ?? 0);
        Model_Config::set_value('advertisement', 'contact', $data['contact'] ?? 0);
        Model_Config::set_value('advertisement', 'login_to_contact', $data['login_to_contact'] ?? 0);
        Model_Config::set_value('advertisement', 'contact_price', $data['contact_price'] ?? 0);
        Model_Config::set_value('advertisement', 'qr_code', $data['qr_code'] ?? 0);
        Model_Config::set_value('advertisement', 'count_visits', $data['count_visits'] ?? 0);
        Model_Config::set_value('advertisement', 'sharing', $data['sharing'] ?? 0);
        Model_Config::set_value('advertisement', 'report', $data['report'] ?? 0);
        Model_Config::set_value('advertisement', 'related', $data['related']);
        Model_Config::set_value('advertisement', 'free', $data['free'] ?? 0);
        Model_Config::set_value('advertisement', 'rich_snippets', $data['rich_snippets'] ?? 0);
    }

    private function validation()
    {
        return Validation::factory($this->request->post())
            ->rule('advertisements_per_page', 'not_empty')
            ->rule('advertisements_per_page', 'digit')
            ->rule('feed_elements', 'not_empty')
            ->rule('feed_elements', 'digit')
            ->rule('map_elements', 'not_empty')
            ->rule('map_elements', 'digit')
            ->rule('sort_by', 'not_empty')
            ->rule('ads_in_home', 'not_empty')
            ->rule('ads_in_home', 'range', [':value', 0, 4])
            ->rule('login_to_post', 'range', [':value', 0, 1])
            ->rule('only_admin_post', 'range', [':value', 0, 1])
            ->rule('expire_date', 'not_empty')
            ->rule('expire_date', 'digit')
            ->rule('ads_per_day_enabled', 'range', [':value', 0, 1])
            ->rule('ads_per_day_limit', 'digit')
            ->rule('parent_category', 'range', [':value', 0, 1])
            ->rule('captcha', 'range', [':value', 0, 1])
            ->rule('address', 'range', [':value', 0, 1])
            ->rule('phone', 'range', [':value', 0, 1])
            ->rule('website', 'range', [':value', 0, 1])
            ->rule('location', 'range', [':value', 0, 1])
            ->rule('price', 'range', [':value', 0, 1])
            ->rule('upload_file', 'range', [':value', 0, 1])
            ->rule('num_images', 'not_empty')
            ->rule('num_images', 'digit')
            ->rule('contact', 'range', [':value', 0, 1])
            ->rule('login_to_contact', 'range', [':value', 0, 1])
            ->rule('qr_code', 'range', [':value', 0, 1])
            ->rule('count_visits', 'range', [':value', 0, 1])
            ->rule('related', 'not_empty')
            ->rule('related', 'digit');
    }
}
