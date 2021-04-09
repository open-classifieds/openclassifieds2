<?php

class Twofactorin {

    public static function send()
    {
        return;
    }

    public static function send_auth_code($phone)
    {
        $api_key = Core::config('general.sms_2factorin_api');

        $request = Request::factory("https://2factor.in/API/V1/{$api_key}/SMS/{$phone}/AUTOGEN");

        $response = json_decode($request->execute()->body());

        if($response->Status === 'Success');
        {
            return $response->Details;
        }

        return FALSE;
    }

    public static function verify_auth_code($auth_code, $request_code)
    {
        $api_key = Core::config('general.sms_2factorin_api');

        $request = Request::factory("https://2factor.in/API/V1/{$api_key}/SMS/VERIFY/{$auth_code}/{$request_code}");

        $response = json_decode($request->execute()->body());

        return $response->Status === 'Success';
    }

    public static function send_transactional($user, $transaction_name)
    {
        if (empty($user->phone))
        {
            return FALSE;
        }

        $api_key = Core::config('general.sms_2factorin_api');
        $template_name = Core::config("general.sms_2factorin_{$transaction_name}_template");

        $request = Request::factory("https://2factor.in/API/V1/{$api_key}/ADDON_SERVICES/SEND/TSMS")
            ->method(Request::POST)
            ->post([
                'From' => Core::config('general.sms_2factorin_sender_id'),
                'To' => $user->phone,
                'TemplateName' => $template_name,
                'VAR1' => $user->name,
            ]);

        $response = json_decode($request->execute()->body());

        return $response->Status === 'Success';
    }
}
