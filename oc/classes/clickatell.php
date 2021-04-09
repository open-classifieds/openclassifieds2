<?php

require Kohana::find_file('vendor/clickatell-php-3.0.0/src/', 'Rest');
require Kohana::find_file('vendor/clickatell-php-3.0.0/src/', 'ClickatellException');

use Clickatell\ClickatellException;

class Clickatell  {

    public static function send($phone, $message)
    {
        if (empty(Core::config('general.sms_clickatell_api')) OR Core::config('general.sms_clickatell_api') == NULL)
        {
            return 'Please set your Clickatell SMS API Key in the panel';
        }

        $clickatell = new \Clickatell\Rest(Core::config('general.sms_clickatell_api'));

        $data = [
            'to' => [$phone],
            'content' => $message,
        ];

        if(!empty(Core::config('general.sms_clickatell_two_way_phone')))
        {
            $data['from'] = Core::config('general.sms_clickatell_two_way_phone');
        }

        // Full list of support parameters can be found at https://www.clickatell.com/developers/api-documentation/rest-api-request-parameters/
        try {
            $result = $clickatell->sendMessage($data);

            foreach ($result as $message)
            {
                return ($message['accepted'] == TRUE) ? TRUE : $message['error'];

                //var_dump($message);

                /*
                [
                    'apiMsgId'  => null|string,
                    'accepted'  => boolean,
                    'to'        => string,
                    'error'     => null|string
                ]
                */
            }

        } catch (ClickatellException $e) {
            return $e->getMessage();
            // Any API call error will be thrown and should be handled appropriately.
            // The API does not return error codes, so it's best to rely on error descriptions.
            //var_dump($e->getMessage());
        }

    }

    public static function send_auth_code($phone)
    {
        $code = Text::random('numeric', 6);

        $response = self::send($phone,__('Your code:').' '.$code);

        if ($response !== TRUE)
        {
            Form::set_errors([$response]);

            return;
        }

        return $code;
    }

    public static function verify_auth_code($auth_code, $request_code)
    {
        return $auth_code === $request_code;
    }

    public static function testAPIkey($apikey, $phone)
    {
        $user = Auth::instance()->get_user();

        if(empty($user->phone) OR $user->phone == NULL){
            Alert::set(Alert::ALERT, 'Please <a href="'.Route::url('oc-panel',array('controller'=>'profile','action'=>'edit')).'">edit your profile</a> and enter your phone number');

            return FALSE;
        }

        if(empty($apikey) OR $apikey == NULL){
            Alert::set(Alert::ALERT, 'Please configure <a href="//docs.yclas.com/2-step-sms-authentication/">Clickatell</a> to enable 2 Step SMS Authentication!');

            return FALSE;
        }

        $clickatell = new \Clickatell\Rest($apikey);

        $data = [
            'to' => [$user->phone],
            'content' => '2 Step SMS Authentication enabled - '.Core::config('general.site_name'),
        ];

        if(!empty($phone)){
            $data['from'] = $phone;
        }

        try {
            $result = $clickatell->sendMessage($data);

            foreach ($result as $message)
            {
                if($message['accepted'] == TRUE){
                    return TRUE;
                } else {
                    Alert::set(Alert::ALERT, $message['error']);
                    Alert::set(Alert::ALERT, 'Please configure <a href="//docs.yclas.com/2-step-sms-authentication/">Clickatell</a> to enable 2 Step SMS Authentication!');

                    return FALSE;
                }
            }

        } catch (ClickatellException $e) {
            return FALSE;
        }
    }
}
