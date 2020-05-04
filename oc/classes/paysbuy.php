<?php defined('SYSPATH') or die('No direct script access.');

/**
 * paysbuy helper class
 *
 * @package    OC
 * @category   Payment
 * @author     Chema <chema@open-classifieds.com>
 * @copyright  (c) 2009-2015 Open Classifieds Team
 * @license    GPL v3
 */

class paysbuy {
    

    const url_gateway            = 'https://www.paysbuy.com/paynow.aspx';
    const url_recheck            = 'https://www.paysbuy.com/getinvoice/getinvoicestatus.asmx/GetInvoice';

    const url_sandbox_gateway    = 'https://demo.paysbuy.com/paynow.aspx';
    const url_sandbox_recheck    = 'https://demo.paysbuy.com/getinvoice/getinvoicestatus.asmx/GetInvoice';

    
    /**
     * generates HTML for apy buton
     * @param  Model_Order $order 
     * @return string                 
     */
    public static function form(Model_Order $order)
    {
        if ( Core::config('payment.paysbuy')!=''  AND Core::extra_features() == TRUE)
        {
            $form_action = ( Core::config('payment.paysbuy_sandbox') == 1)?self::url_sandbox_gateway:self::url_gateway;

            return View::factory('pages/paysbuy/form',array('order'=>$order,'form_action'=>$form_action));
        }

        return '';
    }


    // Original Source by : Siambox.com http://www.thaihosttalk.com/index.php?topic=19899.0
    public static function recheck($psbmail, $cart, $psbRef, $amount)
    {
        $query = "invoiceNo=$cart&merchantEmail=".Core::config('payment.paysbuy')."&strApCode=$psbRef";

        // Request URI (Secure)
        $ch = curl_init($form_action = ( Core::config('payment.paysbuy_sandbox') == 1)?self::url_sandbox_check:self::url_check );

        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, "$query");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $xmlResponse  = curl_exec($ch);
        curl_close($ch);

        $StatusResult   = self::XMLGetValue($xmlResponse, 'StatusResult');
        $AmountResult   = self::XMLGetValue($xmlResponse, 'AmountResult');

        if ($StatusResult == 'Accept' AND $AmountResult == $amount)
            return TRUE;
        else
            return FALSE; // Reject the payment.
        
    }

    private static function XMLGetValue($msg, $str)
    {
        $str1 = "<$str>";
        $str2 = "</$str>";
        $start_pos = strpos($msg, $str1);
        $stop_post = strpos($msg, $str2);
        $start_pos += strlen($str1);
        return substr($msg, $start_pos, $stop_post - $start_pos);
    }
}