<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Panel_Integrations extends Auth_Controller {

    public function action_index()
    {
        $this->template->title = __('Integrations');

        $integrations = [
            [
                'name' => 'algolia',
                'config_name' => 'general.algolia_search',
                'label' => 'Algolia',
                'description' => 'Lorem ipsum dolor sit amet',
                'logo' => '/themes/default/img/oc-panel/integrations/algolia.png'
            ],
            [
                'name' => 'akismet',
                'config_name' => 'general.akismet_key',
                'label' => 'Akismet',
                'description' => 'Lorem ipsum dolor sit amet',
                'logo' => '/themes/default/img/oc-panel/integrations/akismet.svg'
            ],
            [
                'name' => 'carquery',
                'config_name' => 'general.carquery',
                'label' => 'CarQuery',
                'description' => 'Lorem ipsum dolor sit amet',
                'logo' => '/themes/default/img/oc-panel/integrations/carquery.png'
            ],
            [
                'name' => 'clickatell',
                'config_name' => 'general.sms_auth',
                'label' => 'Clickatell',
                'description' => 'Lorem ipsum dolor sit amet',
                'logo' => '/themes/default/img/oc-panel/integrations/clickatell.png'
            ],
            [
                'name' => 'cloudinary',
                'config_name' => 'general.cloudinary',
                'label' => 'Cloudinary',
                'description' => 'Lorem ipsum dolor sit amet',
                'logo' => '/themes/default/img/oc-panel/integrations/cloudinary.png'
            ],
            [
                'name' => 'disqus',
                'config_name' => 'general.blog_disqus',
                'label' => 'Disqus',
                'description' => __('Disqus allows you to embed comments within advertisements, blog posts and faqs'),
                'logo' => '/themes/default/img/oc-panel/integrations/disqus.svg'
            ],
            [
                'name' => 'dropbox',
                'config_name' => 'advertisement.dropbox_app_key',
                'label' => 'Dropbox',
                'description' => 'Lorem ipsum dolor sit amet',
                'logo' => '/themes/default/img/oc-panel/integrations/dropbox.svg'
            ],
            [
                'name' => 'elasticemail',
                'config_name' => 'email.elastic_username',
                'label' => 'ElasticEmail',
                'description' => 'Lorem ipsum dolor sit amet',
                'logo' => '/themes/default/img/oc-panel/integrations/elasticemail.jpg'
            ],
            [
                'name' => 'escrow',
                'config_name' => 'payment.escrow_pay',
                'label' => 'Escrow',
                'description' => 'Lorem ipsum dolor sit amet',
                'logo' => '/themes/default/img/oc-panel/integrations/escrow.jpg'
            ],
            [
                'name' => 'facebook',
                'config_name' => 'general.facebook',
                'label' => 'Facebook',
                'description' => 'Lorem ipsum dolor sit amet',
                'logo' => '/themes/default/img/oc-panel/integrations/facebook.svg'
            ],
            [
                'name' => 'googleanalytics',
                'config_name' => 'general.analytics',
                'label' => 'Google Analytics',
                'description' => 'Lorem ipsum dolor sit amet',
                'logo' => '/themes/default/img/oc-panel/integrations/google-analytics.png'
            ],
            [
                'name' => 'googleauthenticator',
                'config_name' => 'general.google_authenticator',
                'label' => 'Google Authenticator',
                'description' => 'Lorem ipsum dolor sit amet',
                'logo' => '/themes/default/img/oc-panel/integrations/google-authenticator.svg'
            ],
            [
                'name' => 'gcm',
                'config_name' => 'general.gcm_apikey',
                'label' => 'Google Cloud Messaging',
                'description' => 'Lorem ipsum dolor sit amet',
                'logo' => '/themes/default/img/oc-panel/integrations/google-gcm.png'
            ],
            [
                'name' => 'googlepicker',
                'config_name' => 'advertisement.google_picker',
                'label' => 'Google Picker',
                'description' => 'Lorem ipsum dolor sit amet',
                'logo' => '/themes/default/img/oc-panel/integrations/google-picker.png'
            ],
            [
                'name' => 'googlemaps',
                'config_name' => 'advertisement.gm_api_key',
                'label' => 'Google Maps',
                'description' => 'Lorem ipsum dolor sit amet',
                'logo' => '/themes/default/img/oc-panel/integrations/google-maps.png'
            ],
            [
                'name' => 'logbee',
                'config_name' => 'advertisement.logbee',
                'label' => 'Logbee',
                'description' => 'Lorem ipsum dolor sit amet',
                'logo' => '/themes/default/img/oc-panel/integrations/logbee.png'
            ],
            [
                'name' => 'mailgun',
                'config_name' => 'email.mailgun_api_key',
                'label' => 'Mailgun',
                'description' => 'Lorem ipsum dolor sit amet',
                'logo' => '/themes/default/img/oc-panel/integrations/mailgun.png'
            ],
            [
                'name' => 'pinterest',
                'config_name' => 'advertisement.pinterest',
                'label' => 'Pinterest',
                'description' => 'Lorem ipsum dolor sit amet',
                'logo' => '/themes/default/img/oc-panel/integrations/pinterest.svg'
            ],
            [
                'name' => 'pusher',
                'config_name' => 'general.pusher_notifications',
                'label' => 'Pusher',
                'description' => 'Lorem ipsum dolor sit amet',
                'logo' => '/themes/default/img/oc-panel/integrations/pusher.png'
            ],
            [
                'name' => 'recaptcha',
                'config_name' => 'general.recaptcha_active',
                'label' => 'reCaptcha',
                'description' => 'Lorem ipsum dolor sit amet',
                'logo' => '/themes/default/img/oc-panel/integrations/recaptcha.png'
            ],
            [
                'name' => 'twitter',
                'config_name' => 'advertisement.twitter',
                'label' => 'Twitter',
                'description' => 'Lorem ipsum dolor sit amet',
                'logo' => '/themes/default/img/oc-panel/integrations/twitter.svg'
            ],
        ];

        return $this->template->content = View::factory('oc-panel/pages/integrations/index', [
            'integrations' => $integrations,
        ]);
    }
}
