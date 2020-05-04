<?php

/**
 * Stripe helper class
 *
 * @package    OC
 * @category   Payment
 * @author     Chema <chema@open-classifieds.com>
 * @copyright  (c) 2009-2014 Open Classifieds Team
 * @license    GPL v3
 */

class StripeKO {

    public static function init()
    {
        // include class vendor
        require Kohana::find_file('vendor/stripe', 'init');

        // Set your secret key: remember to change this to your live secret key in production
        // See your keys here https://manage.stripe.com/account
        \Stripe\Stripe::setAppInfo('Open Classifieds', Core::VERSION, 'http://open-classifieds.com');
        \Stripe\Stripe::setApiKey(Core::config('payment.stripe_private'));
    }

    /**
     * formats an amount to the correct format for paymill. 2.50 == 250
     * @param  float $amount
     * @return string
     */
    public static function money_format($amount)
    {
        return round($amount,2)*100;
    }

    /**
     * how much the site owner earn?
     * @param  integer $amount
     * @param  integer $fee
     * @return integer
     */
    public static function application_fee($amount, $fee = NULL)
    {
        //percentage we take, in case not passed take default
        if ($fee === NULL)
            $fee  = Core::config('payment.stripe_appfee');

        //initial exchange fee + stripe fee
        return ($fee * $amount / 100);
    }


    /**
     *   NOTE This will  never be exactly since stripe has variable pricing
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
    public static function button(Model_Order $order)
    {
        if (Core::config('payment.stripe_legacy') === '0')
        {
            return NULL;
        }

        if (Core::config('payment.stripe_private')!='' AND Core::config('payment.stripe_public')!='' AND Core::extra_features() == TRUE)
        {
            return View::factory('pages/stripe/button',array('order'=>$order));
        }

        return '';
    }


    /**
     * generates HTML for pay buton
     * @param  Model_Order $order
     * @return string
     */
    public static function button_connect(Model_Order $order)
    {
        if (Core::config('payment.stripe_legacy') === '0')
        {
            return NULL;
        }

        if ( !empty($order->ad->user->stripe_user_id) AND
            Core::config('payment.stripe_connect')==TRUE AND
            Core::config('payment.stripe_private')!='' AND
            Core::config('payment.stripe_public')!='' AND
            Core::extra_features() == TRUE AND
            $order->id_product == Model_Order::PRODUCT_AD_SELL )
        {
            if ($order->ad->price != NULL AND $order->ad->price > 0 AND
                (core::config('payment.stock')==0 OR ($order->ad->stock > 0 AND core::config('payment.stock')==1)))
                return View::factory('pages/stripe/button_connect',array('order'=>$order));
        }

        return '';
    }

    /**
     * generates HTML for pay buton
     * @param  Model_Ad $ad
     * @return string
     */
    public static function button_guest_connect(Model_Ad $ad)
    {
        if (Core::config('payment.stripe_legacy') === '0')
        {
            return NULL;
        }

        if ( !empty($ad->user->stripe_user_id) AND
            Core::config('payment.stripe_connect')==TRUE AND
            Core::config('payment.stripe_private')!='' AND
            Core::config('payment.stripe_public')!='' AND
            Core::extra_features() == TRUE)
        {
            if ($ad->price != NULL AND $ad->price > 0 AND
                (core::config('payment.stock')==0 OR ($ad->stock > 0 AND core::config('payment.stock')==1)))
            {
                if($quantity = (int) core::get('quantity', 1))
                {
                    $ad->price = $ad->price * $quantity;
                }

                if ($ad->shipping_price() AND $ad->shipping_pickup() AND core::get('shipping_pickup'))
                    $ad->price = $ad->price;
                elseif($ad->shipping_price())
                    $ad->price = $ad->price + $ad->shipping_price();

                return View::factory('pages/stripe/button_guest_connect',array('ad'=>$ad));
            }
        }

        return '';
    }
}
