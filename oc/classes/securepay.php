<?php defined('SYSPATH') or die('No direct script access.');

/**
 * Securepay helper class
 *
 * @package    OC
 * @category   Payment
 * @author     Chema <chema@open-classifieds.com>
 * @copyright  (c) 2009-2016 Open Classifieds Team
 * @license    GPL v3
 */

class Securepay {
    

    /**
     * formats an amount to the correct format. 2.50 == 250
     * @param  float $amount 
     * @return string         
     */
    public static function money_format($amount)
    {
        return round($amount,2)*100;
    }

    /**
     * returns formated id_order
     * @param  integer $id_order 
     * @return string           
     */
    public static function id_order($id_order)
    {
        return 'MM-'.$id_order;
    }

    /**
     * get the url for the form
     * @return string 
     */
    public static function get_url()
    {
        return 'https://payment.securepay.com.au/'. ((Core::config('payment.securepay_testing') == TRUE)?'test':'live').'/v2/invoice';
    }

    /**
     * fingerprint used to generate the form
     * @param  Model_Order $order     
     * @param  string      $timestamp smt
     * @return string               
     */
    public static function fingerprint_form(Model_Order $order, $timestamp)
    {
        //SHA1 ABC0010|txnpassword|0|ORDER_ID|5320|201106141010
        $fingerprint  = Core::config('payment.securepay_merchant').'|'
                            .Core::config('payment.securepay_password').'|0|'
                            .Securepay::id_order($order->id_order).'|'
                            .Securepay::money_format($order->amount).'|'
                            .$timestamp;
        return sha1($fingerprint);
    }

    /**
     * fingerprint used to validate
     * @param  Model_Order $order       
     * @param  string      $timestamp   
     * @param  string      $result_code 
     * @return string                   
     */
    public static function fingerprint_validation(Model_Order $order, $timestamp, $result_code)
    {
        //ABC0010|mytxnpasswd|MyReference|1000|201105231545|1
        $fingerprint  = Core::config('payment.securepay_merchant').'|'
                            .Core::config('payment.securepay_password').'|'
                            .Securepay::id_order($order->id_order).'|'
                            .Securepay::money_format($order->amount).'|'
                            .$timestamp.'|'
                            .$result_code;
        return sha1($fingerprint);
    }

    /**
     * generates HTML for apy buton
     * @param  Model_Order $order 
     * @return string                 
     */
    public static function button(Model_Order $order)
    {
        if ( Core::config('payment.securepay_merchant')!='' AND 
            Core::config('payment.securepay_password')!='' AND 
            Core::extra_features() == TRUE AND
            $order->loaded())
        {
            // GMT / UTC "YYYYMMDDHHMMSS"
            $timestamp = gmdate('YmdHIis');

            $fingerprint  = Securepay::fingerprint_form($order, $timestamp);   

            return View::factory('pages/securepay/button',array('order' => $order,
                                                                'url'   => Securepay::get_url(),
                                                                'fingerprint' => $fingerprint,
                                                                'timestamp'   => $timestamp));
        }

        return '';
    }

}