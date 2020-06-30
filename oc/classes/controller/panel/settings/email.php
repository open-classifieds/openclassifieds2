<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Panel_Settings_Email extends Auth_Controller {

    public function action_index()
    {
        $this->template->title = __('Email settings');

        if($this->request->post())
        {
            $validation = Validation::factory($this->request->post())
                ->rule('notify_email', 'email')
                ->rule('notify_name', 'not_empty')
                ->rule('new_ad_notify', 'range', array(':value', 0, 1))
                ->rule('smtp_ssl', 'range', array(':value', 0, 1))
                ->rule('smtp_port', 'digit')
                ->rule('smtp_auth', 'range', array(':value', 0, 1));

            if (!$validation->check())
            {
                foreach ($validation->errors('config') as $error)
                {
                    Alert::set(Alert::ALERT, $error);
                }

                $this->redirect(Route::url('oc-panel/settings', ['controller' => 'email']));
            }

            Model_Config::set_value('email', 'notify_email', Core::post('notify_email'));
            Model_Config::set_value('email', 'notify_name', Core::post('notify_name'));
            Model_Config::set_value('email', 'new_ad_notify', Core::post('new_ad_notify') ?? 0);
            Model_Config::set_value('email', 'service', Core::post('service'));
            Model_Config::set_value('email', 'smtp_host', Core::post('smtp_host'));
            Model_Config::set_value('email', 'smtp_port', Core::post('smtp_port'));
            Model_Config::set_value('email', 'smtp_user', Core::post('smtp_user'));
            Model_Config::set_value('email', 'smtp_pass', Core::post('smtp_pass'));
            Model_Config::set_value('email', 'smtp_secure', Core::post('smtp_secure'));
            Model_Config::set_value('email', 'smtp_auth', Core::post('smtp_auth'));

            if ($this->request->post('service') != 'elastic')
            {
                $this->redirect(Route::url('oc-panel/settings',['controller'=>'email','action'=>'test']));
            }

            Alert::set(Alert::SUCCESS, __('Configuration updated'));

            $this->redirect(Route::url('oc-panel/settings', ['controller' => 'email']));
        }

        return $this->template->content = View::factory('oc-panel/pages/settings/email', [
        ]);
    }

    public function action_test()
    {
        if (Email::send(core::config('email.notify_email'),core::config('email.notify_name'),
                        'Test Email Sent','Test Email Sent from email service '.core::config('email.service'),
                        core::config('email.notify_email'),core::config('email.notify_name')))
            Alert::set(Alert::SUCCESS, __('Email succesfully sent.'));
        else
            Alert::set(Alert::ALERT, __('Email was not sent, please review your email configuration.'));

        $this->redirect(Route::url('oc-panel',array('controller'=>'settings','action'=>'email')));
    }
}
