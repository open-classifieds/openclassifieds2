<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Panel_Integrations_Twitter extends Auth_Controller {

    public function action_index()
    {
        $this->template->title = __('Twitter');

        $validation = $this->validation();

        if($this->request->post() AND $validation->check())
        {
            Model_Config::set_value('advertisement', 'twitter', Core::post('is_active') ?? 0);
            Model_Config::set_value('advertisement', 'social_post_only_featured', Core::post('social_post_only_featured') ?? 0);
            Model_Config::set_value('advertisement', 'twitter_consumer_key', Core::post('twitter_consumer_key'));
            Model_Config::set_value('advertisement', 'twitter_consumer_secret', Core::post('twitter_consumer_secret'));
            Model_Config::set_value('advertisement', 'access_token', Core::post('access_token'));
            Model_Config::set_value('advertisement', 'access_token_secret', Core::post('access_token_secret'));

            Alert::set(Alert::SUCCESS, __('Configuration updated'));

            $this->redirect(Route::url('oc-panel/integrations', ['controller' => 'twitter']));
        }

        return $this->template->content = View::factory('oc-panel/pages/integrations/twitter', [
            'errors' => $validation->errors('validation'),
            'is_active' => (bool) Core::config('advertisement.twitter')
        ]);
    }

    private function validation()
    {
        $validation = Validation::factory($this->request->post());

        if ((bool) Core::post('is_active') ?? 0)
        {
            $validation->rule('twitter_consumer_key', 'not_empty')
                ->rule('twitter_consumer_secret', 'not_empty')
                ->rule('access_token', 'not_empty')
                ->rule('access_token_secret', 'not_empty');
        }

        return $validation;
    }
}
