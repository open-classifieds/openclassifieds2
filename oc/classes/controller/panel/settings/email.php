<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Panel_Settings_Email extends Auth_Controller {

    public function action_index()
    {
        $this->template->title = __('Email settings');

        $validation = $this->validation();

        if ($this->request->post() AND $validation->check())
        {
            $this->store_settings($validation->data());

            if ($this->request->post('service') != 'elastic')
            {
                $this->redirect(Route::url('oc-panel/settings',['controller'=>'email','action'=>'test']));
            }

            Alert::set(Alert::SUCCESS, __('Configuration updated'));

            $this->redirect(Route::url('oc-panel/settings', ['controller' => 'email']));
        }

        return $this->template->content = View::factory('oc-panel/pages/settings/email', [
            'errors' => $validation->errors('validation'),
            'service' => $this->get_email_service(),
        ]);
    }

    public function action_test()
    {
        if (Email::send(
            Core::config('email.notify_email'),Core::config('email.notify_name'),
            'Test Email Sent','Test Email Sent from email service '.Core::config('email.service'),
            Core::config('email.notify_email'),Core::config('email.notify_name')
        ))
        {
            Alert::set(Alert::SUCCESS, __('Email succesfully sent.'));
        }
        else
        {
            Alert::set(Alert::ALERT, __('Email was not sent, please review your email configuration.'));
        }

        $this->redirect(Route::url('oc-panel',array('controller'=>'settings','action'=>'email')));
    }

    private function validation()
    {
        return Validation::factory($this->request->post())
            ->rule('notify_email', 'email')
            ->rule('notify_name', 'not_empty')
            ->rule('new_ad_notify', 'range', [':value', 0, 1])
            ->rule('smtp_ssl', 'range', [':value', 0, 1])
            ->rule('smtp_port', 'digit')
            ->rule('smtp_auth', 'range', [':value', 0, 1]);
    }

    private function store_settings($data)
    {
        Model_Config::set_value('email', 'notify_email', $data['notify_email']);
        Model_Config::set_value('email', 'notify_name', $data['notify_name']);
        Model_Config::set_value('email', 'new_ad_notify', $data['new_ad_notify'] ?? 0);
        Model_Config::set_value('email', 'service', $data['service']);
        Model_Config::set_value('email', 'smtp_host', $data['smtp_host']);
        Model_Config::set_value('email', 'smtp_port', $data['smtp_port']);
        Model_Config::set_value('email', 'smtp_user', $data['smtp_user']);
        Model_Config::set_value('email', 'smtp_pass', $data['smtp_pass']);
        Model_Config::set_value('email', 'smtp_secure', $data['smtp_secure']);
        Model_Config::set_value('email', 'smtp_auth', $data['smtp_auth']);
    }

    private function get_email_service()
     {
         if(in_array(Core::config('email.service'), ['elasticemail', 'elastic']))
         {
             return 'elasticemail';
         }

         if(in_array(Core::config('email.service'), ['mailgun']))
         {
             return 'elasticemail';
         }

         if(in_array(Core::config('email.service'), ['smtp', 'gmail', 'outlook', 'yahoo', 'zoho']))
         {
             return 'smtp';
         }

         if(in_array(Core::config('email.service'), ['mail', null, '']))
         {
             return 'mail';
         }

         return '';
     }
}
