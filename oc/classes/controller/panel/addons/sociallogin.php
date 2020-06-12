<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Panel_Addons_SocialLogin extends Auth_Controller {

    public function action_index()
    {
        $this->template->title = __('Social login');

        $config = Social::get();

        if ($this->request->post() AND Core::extra_features() == FALSE)
        {
            Alert::set(Alert::WARNING, __('This feature is only available in the PRO version!') . ' ' . __('Upgrade your Yclas site to activate this feature.'));
            $this->redirect(Route::url('oc-panel/addons', ['controller' => 'sociallogin']));
        }

        if($this->request->post())
        {
            $config['providers']['Facebook']['enabled'] = Core::post('facebook_enabled') ?? 0;
            $config['providers']['Facebook']['keys']['id'] = Core::post('facebook_id');
            $config['providers']['Facebook']['keys']['secret'] = Core::post('facebook_secret');

            $config['providers']['Google']['enabled'] = Core::post('google_enabled') ?? 0;
            $config['providers']['Google']['keys']['id'] = Core::post('google_id');
            $config['providers']['Google']['keys']['secret'] = Core::post('google_secret');

            $config_new['base_url'] = Route::url('default',['controller'=>'social','action'=>'login','id'=>1]);
            $config_new['debug_file'] = DOCROOT.'oc/vendor/hybridauth/logs.txt';

            Model_Config::set_value('general', 'social_auth', Core::post('is_active') ?? 0);

            Model_Config::set_value('social', 'oauth2_enabled', Core::post('oauth2_enabled') ?? 0);
            Model_Config::set_value('social', 'oauth2_client_id', Core::post('oauth2_client_id'));
            Model_Config::set_value('social', 'oauth2_client_secret', Core::post('oauth2_client_secret'));
            Model_Config::set_value('social', 'oauth2_url_authorize', Core::post('oauth2_url_authorize'));
            Model_Config::set_value('social', 'oauth2_url_access_token', Core::post('oauth2_url_access_token'));
            Model_Config::set_value('social', 'oauth2_url_resource_owner_details', Core::post('oauth2_url_resource_owner_details'));

            Model_Config::set_value('social', 'config', json_encode($config));

            Alert::set(Alert::SUCCESS, __('Configuration updated'));

            $this->redirect(Route::url('oc-panel/addons', ['controller' => 'sociallogin']));
        }

        return $this->template->content = View::factory('oc-panel/pages/addons/social-login/index', [
            'is_active' => (bool) Core::config('general.social_auth'),
            'config' => Social::get(),
        ]);
    }
}
