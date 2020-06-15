<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Panel_Addons extends Auth_Controller {

    public function action_index()
    {
        $this->template->title = __('Addons');

        $addons = [
            [
                'name' => 'blog',
                'config_name' => 'general.blog',
                'label' => __('Blog'),
                'description' => __('Enables a simple blog system for your website.'),
                'is_pro' => FALSE,
            ],
            [
                'name' => 'forums',
                'config_name' => 'general.forums',
                'label' => __('Forums'),
                'description' => __('Enables a simple forum system for your website.'),
                'is_pro' => TRUE,
            ],
            [
                'name' => 'faq',
                'config_name' => 'general.faq',
                'label' => __('Faq'),
                'description' => __('Enables a simple frequent asked questions system for your website.'),
                'is_pro' => TRUE,
            ],
            [
                'name' => 'messaging',
                'config_name' => 'general.messaging',
                'label' => __('Messaging'),
                'description' => __('Enables an internal messaging system to send instant messages to sellers.'),
                'is_pro' => TRUE,
            ],
            [
                'name' => 'reviews',
                'config_name' => 'advertisement.reviews',
                'label' => __('Reviews'),
                'description' => __('A review system to obtain useful feedback from your users about the published ads and users who posted them.'),
                'is_pro' => TRUE,
            ],
            [
                'name' => 'subscriptions',
                'config_name' => 'general.subscriptions',
                'label' => __('Subscriptions / Memberships'),
                'description' => __('With Subscriptions/Memberships you can charge daily, weekly, monthly or yearly subscription to your users to be able to post to your website.'),
                'is_pro' => TRUE,
            ],
            [
                'name' => 'sociallogin',
                'config_name' => 'general.social_auth',
                'label' => __('Social login'),
                'description' => __('Login with Facebook, Google and OAuth2'),
                'is_pro' => TRUE,
            ],
            [
                'name' => 'blacklist',
                'config_name' => 'general.black_list',
                'label' => __('Black list'),
                'description' => __('If advertisement is marked as spam, user is also marked. Can not publish new ads or register until removed from Black List! Also will not allow users from disposable email addresses to register.'),
                'is_pro' => TRUE,
            ],
            [
                'name' => 'autolocate',
                'config_name' => 'general.auto_locate',
                'label' => __('Auto locate'),
                'description' => __('Get the geographical position of a user. Requires setting up SSL on your website.'),
                'is_pro' => TRUE,
            ],
            [
                'name' => 'adblockdetector',
                'config_name' => 'general.adblock',
                'label' => __('Adblock detector'),
                'description' => __("Shows a notice to disable AdBlock if it's detected."),
                'is_pro' => TRUE,
            ],
            [
                'name' => 'addtohomescreen',
                'config_name' => 'general.add_to_home_screen',
                'label' => __('Add to home screen'),
                'description' => __('Show the Add to Home Screen dialog on Android devices with Chrome browser.'),
                'is_pro' => TRUE,
            ],
        ];

        return $this->template->content = View::factory('oc-panel/pages/addons/index', [
            'addons' => $addons,
        ]);
    }
}
