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
                'description' => 'Lorem ipsum dolor sit amet',
                'is_pro' => FALSE,
            ],
            [
                'name' => 'forums',
                'config_name' => 'general.forums',
                'label' => __('Forums'),
                'description' => 'Lorem ipsum dolor sit amet',
                'is_pro' => TRUE,
            ],
            [
                'name' => 'faq',
                'config_name' => 'general.faq',
                'label' => __('Faq'),
                'description' => 'Lorem ipsum dolor sit amet',
                'is_pro' => TRUE,
            ],
            [
                'name' => 'messaging',
                'config_name' => 'general.messaging',
                'label' => __('Messaging'),
                'description' => 'Lorem ipsum dolor sit amet',
                'is_pro' => TRUE,
            ],
            [
                'name' => 'reviews',
                'config_name' => 'advertisement.reviews',
                'label' => __('Reviews'),
                'description' => __('Reviews for ads and users.'),
                'is_pro' => TRUE,
            ],
            [
                'name' => 'subscriptions',
                'config_name' => 'general.subscriptions',
                'label' => __('Subscriptions / Memberships'),
                'description' => 'Lorem ipsum dolor sit amet',
                'is_pro' => TRUE,
            ],
            [
                'name' => 'sociallogin',
                'config_name' => 'general.social_auth',
                'label' => __('Social login'),
                'description' => 'Lorem ipsum dolor sit amet',
                'is_pro' => TRUE,
            ],
        ];

        return $this->template->content = View::factory('oc-panel/pages/addons/index', [
            'addons' => $addons,
        ]);
    }
}
