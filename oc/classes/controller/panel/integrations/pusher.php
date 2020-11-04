<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Panel_Integrations_Pusher extends Auth_Controller {

    public function action_index()
    {
        $this->template->title = __('Pusher');

        $validation = $this->validation();

        if($this->request->post() AND $validation->check())
        {
            Model_Config::set_value('general', 'pusher_notifications', Core::post('is_active') ?? 0);
            Model_Config::set_value('general', 'pusher_notifications_app_id', Core::post('pusher_notifications_app_id'));
            Model_Config::set_value('general', 'pusher_notifications_key', Core::post('pusher_notifications_key'));
            Model_Config::set_value('general', 'pusher_notifications_secret', Core::post('pusher_notifications_secret'));
            Model_Config::set_value('general', 'pusher_notifications_cluster', Core::post('pusher_notifications_cluster'));

            Alert::set(Alert::SUCCESS, __('Configuration updated'));

            $this->redirect(Route::url('oc-panel/integrations', ['controller' => 'pusher']));
        }

        return $this->template->content = View::factory('oc-panel/pages/integrations/pusher', [
            'errors' => $validation->errors('validation'),
            'is_active' => (bool) Core::config('general.pusher_notifications')
        ]);
    }

    private function validation()
    {
        $validation = Validation::factory($this->request->post());

        if ((bool) Core::post('is_active') ?? 0)
        {
            $validation->rule('pusher_notifications_app_id', 'not_empty')
                ->rule('pusher_notifications_key', 'not_empty')
                ->rule('pusher_notifications_secret', 'not_empty')
                ->rule('pusher_notifications_cluster', 'not_empty');
        }

        return $validation;
    }
}
