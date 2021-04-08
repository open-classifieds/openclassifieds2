<?php

class Sms  {

    public static function send($phone, $message)
    {
        if (Core::config('general.sms_service') === 'clickatell')
        {
            return Clickatell::send($phone, $message);
        }
        elseif (Core::config('general.sms_service') === '2factorin')
        {
            return Twofactorin::send($phone, $message);
        }

        return FALSE;
    }

    public static function send_auth_code($phone)
    {
        if (Core::config('general.sms_service') === 'clickatell')
        {
            return Clickatell::send_auth_code($phone);
        }
        elseif (Core::config('general.sms_service') === '2factorin')
        {
            return Twofactorin::send_auth_code($phone);
        }

        return FALSE;
    }

    public static function verify_auth_code($auth_code, $request_code)
    {
        if (Core::config('general.sms_service') === 'clickatell')
        {
            return Clickatell::verify_auth_code($auth_code, $request_code);
        }
        elseif (Core::config('general.sms_service') === '2factorin')
        {
            return Twofactorin::verify_auth_code($auth_code, $request_code);
        }

        return FALSE;
    }

    public static function send_transactional($user, $transaction_name)
    {
        if (Core::config('general.sms_auth') == FALSE)
        {
            return FALSE;
        }

        if (Core::config('general.sms_service') === '2factorin')
        {
            return Twofactorin::send_transactional($user, $transaction_name);
        }

        return FALSE;
    }
}
