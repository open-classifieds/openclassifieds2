<?php defined('SYSPATH') or die('No direct script access.');

/**
 * 2checkout helper class
 *
 * @package    OC
 * @category   Payment
 * @author     Chema <chema@open-classifieds.com>
 * @copyright  (c) 2009-2014 Open Classifieds Team
 * @license    GPL v3
 */

class twocheckout {
    
    /**
     * for form generation
     */
    const url_sandbox_gateway    = 'https://sandbox.2checkout.com/checkout/purchase';
    const url_gateway            = 'https://www.2checkout.com/checkout/purchase';


    /**
     * formats an amount to the correct format for 2co ex 1.00
     * @param  float $amount 
     * @return string         
     */
    public static function money_format($amount)
    {
        return substr($amount,0,-1);
    }

    /**
     *   NOTE This will  never be exactly since 2co has variable pricing
     */
    public static function calculate_fee($amount)
    {   
        //variables
        $fee            = 2.9;
        $fee_trans      = 0.3;//USD

        //initial exchange fee + stripe fee
        return ($fee * $amount / 100) + $fee_trans;
    }
    
    /**
     * generates HTML for apy buton
     * @param  Model_Order $order 
     * @return string                 
     */
    public static function form(Model_Order $order)
    {
        if ( Core::config('payment.twocheckout_sid')!=''  AND Core::config('payment.twocheckout_secretword')!='' AND Core::extra_features() == TRUE)
        {
            $form_action = ( Core::config('payment.twocheckout_sandbox') == 1)?self::url_sandbox_gateway:self::url_gateway;

            return View::factory('pages/twocheckout/form',array('order'=>$order,'form_action'=>$form_action));
        }

        return '';
    }

    /**
     * validate the return
     * see https://www.2checkout.com/documentation/checkout/
     * @param  Model_Order $order 
     * @return order number or FALSE if not match             
     */
    public static function validate_passback(Model_Order $order)
    {
        $hashSecretWord = Core::config('payment.twocheckout_secretword'); //2Checkout Secret Word
        $hashSid        = Core::config('payment.twocheckout_sid'); //2Checkout account number
        $hashTotal      = self::money_format($order->amount); //Sale total to validate against
        $hashOrder      = (Core::config('payment.twocheckout_sandbox') == 1)?1:Core::request('order_number'); //2Checkout Order Number , in sandox the order_number is always 1
        $StringToHash   = strtoupper(md5($hashSecretWord . $hashSid . $hashOrder . $hashTotal));

        return ($StringToHash == Core::request('key'))?$hashOrder:FALSE;
    }
}