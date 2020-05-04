<?php defined('SYSPATH') or die('No direct script access.');

/**
 * robokassa helper class
 *
 * @package    OC
 * @category   Payment
 * @author     Chema <chema@open-classifieds.com>
 * @copyright  (c) 2009-2015 Open Classifieds Team
 * @license    GPL v3
 */

class robokassa {
    
    const url_gateway            = 'https://auth.robokassa.ru/Merchant/Index.aspx?';
    const url_sandbox_gateway    = 'https://auth.robokassa.ru/Merchant/Index.aspx?IsTest=1&';

    /**
     * generates HTML for apy buton
     * @param  Model_Order $order 
     * @return string                 
     */
    public static function button(Model_Order $order)
    {
        if (Core::config('payment.robokassa_login')!='' AND Core::config('payment.robokassa_pass1')!='' AND Core::extra_features() == TRUE)
        {
            // your registration data
            $mrh_login = Core::config('payment.robokassa_login');      // your login here
            $mrh_pass1 = Core::config('payment.robokassa_pass1');   // merchant pass1 here

            // order properties
            $inv_id    = $order->id_order;        // shop's invoice number 
            $inv_desc  = $order->description;   // invoice desc
            $out_summ  = $order->amount;   // invoice summ

            // build CRC value
            $crc  = md5("$mrh_login:$out_summ:$inv_id:$mrh_pass1");

            // build URL
            $url = ( ( Core::config('payment.robokassa_testing') == 1 ) ? self::url_sandbox_gateway : self::url_gateway);
            $url.= "MrchLogin=$mrh_login&OutSum=$out_summ&InvId=$inv_id&Desc=$inv_desc&SignatureValue=$crc";

            return View::factory('pages/robokassa/button',array('url'=>$url));
        }

        return '';
    }


    public static function check_result(Model_Order $order, $type = 'result')
    {
        // as a part of ResultURL script

        // your registration data
        $mrh_pass = ($type == 'result') ? Core::config('payment.robokassa_pass2') : Core::config('payment.robokassa_pass1');   // merchant pass2 here

        // HTTP parameters:
        $out_summ   = Core::request('OutSum');
        $inv_id     = $order->id_order;
        $crc        = Core::request('SignatureValue');

        // build own CRC
        $my_crc = strtoupper(md5("$out_summ:$inv_id:$mrh_pass"));

        return (strtoupper($my_crc) == strtoupper($crc)) ? $crc : FALSE;
    }

}