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
                'description' => __('Login with Facebook, Google, Twitter, and other platforms.'),
                'is_pro' => TRUE,
            ],
        ];

        return $this->template->content = View::factory('oc-panel/pages/addons/index', [
            'addons' => $addons,
        ]);
    }
}
