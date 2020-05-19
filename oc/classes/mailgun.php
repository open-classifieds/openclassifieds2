<?php
/**
 * Simple Mailgun class
 *
 * @package    OC
 * @category   Core
 * @author     Oliver <oliver@open-classifieds.com>
 * @copyright  (c) 2009-2015 Open Classifieds Team
 * @license    GPL v3
 */

class Mailgun {
    /**
     * Send using Mailgun PHP client
     *
     */
    public static function send($to, $to_name = '', $subject, $body_html, $reply, $replyName)
    {
        require_once Kohana::find_file('vendor', 'mailgun/vendor/autoload', 'php');

        $api_key = core::config('email.mailgun_api_key');
        $domain = core::config('email.mailgun_domain');
        $notify_email = core::config('email.notify_name') . ' <' . core::config('email.notify_email') . '>';

        if(is_array($to))
        {
            $to_aux = '';

            foreach ($to as $contact)
            {
                $to_aux .= $contact['name']. ' <'. $contact['email'] . '>,';
            }

            $to = $to_aux;
        }

        //single email convert to array
        elseif (!is_array($to))
        {
            $to = $to_name . ' <' . $to . '>';
        }

        $mailgun = Mailgun\Mailgun::create($api_key);

        try {
            $mailgun->messages()->send($domain, [
                'from' => $notify_email,
                'to' => $to,
                'subject' => $subject,
                'html' => $body_html,
                'h:Reply-To' => $replyName . ' <' . $reply . '>',
            ]);
        } catch (Exception $e) {
            Alert::set(Alert::ALERT, $e->getMessage());
            return FALSE;
        }

        return TRUE;
    }
}
