<?php

/**
 * Stripe V3 helper class
 *
 * @package    OC
 * @category   Payment
 * @author     Oliver <oliver@open-classifieds.com>
 * @copyright  (c) 2009-2014 Open Classifieds Team
 * @license    GPL v3
 */

class StripeCheckout {

    public static function init()
    {
        // include class vendor
        require Kohana::find_file('vendor/stripe', 'init');

        // Set your secret key: remember to change this to your live secret key in production
        // See your keys here https://manage.stripe.com/account
        \Stripe\Stripe::setAppInfo('Yclas', Core::VERSION, 'https://yclas.com');
        \Stripe\Stripe::setApiKey(Core::config('payment.stripe_private'));
    }

    /**
     * formats an amount to the correct format for paymill. 2.50 == 250
     * @param  float $amount
     * @return string
     */
    public static function money_format($amount)
    {
        return (round($amount, 2) * 100);
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
        if (Core::config('payment.stripe_legacy') === '1')
        {
            return NULL;
        }

        if (Core::extra_features() == FALSE)
        {
            return NULL;
        }

        if (Core::config('payment.stripe_private') == '')
        {
            return NULL;
        }

        if (Core::config('payment.stripe_public') == '')
        {
            return NULL;
        }

        self::init();

        $parameters = [
            'payment_method_types' => ['card'],
            'line_items' => [[
                'name' => Model_Order::product_desc($order->id_product),
                'description' => Text::limit_chars(Text::removebbcode($order->description), 30, NULL, TRUE),
                'amount' => StripeCheckout::money_format($order->amount),
                'currency' => $order->currency,
                'quantity' => 1,
            ]],
            'success_url' => Route::url('default', ['controller' => 'stripecheckout', 'action' => 'success', 'id' => $order->id_order]),
            'cancel_url' => Route::url('default', ['controller' => 'ad', 'action' => 'checkout', 'id' => $order->id_order]),
            'locale' => 'auto',
        ];

        if (Auth::instance()->logged_in())
        {
            $parameters['client_reference_id'] = Auth::instance()->get_user()->id_user;
            $parameters['customer_email'] = Auth::instance()->get_user()->email;
        }

        $stripe_session = \Stripe\Checkout\Session::create($parameters);

        $order->txn_id = $stripe_session->payment_intent;
        $order->save();

        return View::factory('pages/stripe_checkout/button', ['session_id' => $stripe_session->id]);
    }


    /**
     * generates HTML for pay buton
     * @param  Model_Order $order
     * @return string
     */
    public static function button_connect(Model_Order $order)
    {
        if (Core::config('payment.stripe_legacy') === '1')
        {
            return NULL;
        }

        if (empty($order->ad->user->stripe_user_id))
        {
            return NULL;
        }

        if (Core::config('payment.stripe_connect') == '')
        {
            return NULL;
        }

        if (Core::config('payment.stripe_private') == '')
        {
            return NULL;
        }

        if (Core::config('payment.stripe_public') == '')
        {
            return NULL;
        }

        if (Core::extra_features() == FALSE)
        {
            return NULL;
        }

        if ($order->id_product != Model_Order::PRODUCT_AD_SELL)
        {
            return NULL;
        }

        if ($order->ad->price == NULL)
        {
            return NULL;
        }

        if ($order->ad->price <= 0)
        {
            return NULL;
        }

        if (core::config('payment.stock') == 1 AND $order->ad->stock <= 0)
        {
            return NULL;
        }

        self::init();

        $parameters = [
            'payment_method_types' => ['card'],
            'line_items' => [[
                'name' => $order->ad->title,
                'description' => Text::limit_chars(Text::removebbcode($order->description), 30, NULL, TRUE),
                'amount' => StripeCheckout::money_format($order->amount),
                'currency' => $order->currency,
                'quantity' => 1,
            ]],
            'success_url' => Route::url('default', ['controller' => 'stripecheckout', 'action' => 'success_connect', 'id' => $order->id_order]),
            'cancel_url' => Route::url('default', ['controller' => 'ad', 'action' => 'checkout', 'id' => $order->id_order]),
            'locale' => 'auto',
        ];

        if (Auth::instance()->logged_in())
        {
            $parameters['client_reference_id'] = Auth::instance()->get_user()->id_user;
            $parameters['customer_email'] = Auth::instance()->get_user()->email;
        }

        if ($order->ad->user->is_admin())
        {
            $stripe_session = \Stripe\Checkout\Session::create($parameters);
        }
        else
        {
            $fee = NULL;

            if ($order->ad->user->subscription()->loaded())
            {
                $fee = $order->ad->user->subscription()->plan->marketplace_fee;
            }

            $application_fee = StripeCheckout::application_fee($order->amount, $fee);

            $parameters['payment_intent_data'] = [
                'application_fee_amount' => StripeCheckout::money_format($application_fee),
                'transfer_data' => [
                    'destination' => $order->ad->user->stripe_user_id,
                ]
            ];

            $stripe_session = \Stripe\Checkout\Session::create($parameters);
        }

        $order->txn_id = $stripe_session->payment_intent;
        $order->save();

        return View::factory('pages/stripe_checkout/button_connect', ['order' => $order, 'session_id' => $stripe_session->id]);
    }

    /**
     * generates HTML for pay buton
     * @param  Model_Ad $ad
     * @return string
     */
    public static function button_guest_connect(Model_Ad $ad)
    {
        if (Core::config('payment.stripe_legacy') === '1')
        {
            return NULL;
        }

        if (empty($ad->user->stripe_user_id))
        {
            return NULL;
        }

        if (Core::config('payment.stripe_connect') == '')
        {
            return NULL;
        }

        if (Core::config('payment.stripe_private') == '')
        {
            return NULL;
        }

        if (Core::config('payment.stripe_public') == '')
        {
            return NULL;
        }

        if (Core::extra_features() == FALSE)
        {
            return NULL;
        }

        if (core::config('payment.stock') == 1 AND $ad->stock <= 0)
        {
            return NULL;
        }

        if($quantity = (int) core::get('quantity', 1))
        {
            $ad->price = $ad->price * $quantity;
        }

        if ($ad->shipping_price() AND $ad->shipping_pickup() AND core::get('shipping_pickup'))
        {
            $ad->price = $ad->price;
        }
        elseif ($ad->shipping_price())
        {
            $ad->price = $ad->price + $ad->shipping_price();
        }

        self::init();

        $parameters = [
            'payment_method_types' => ['card'],
            'line_items' => [[
                'name' => $ad->title,
                'description' => Text::limit_chars(Text::removebbcode($ad->description), 30, NULL, TRUE),
                'amount' => StripeCheckout::money_format($ad->price),
                'currency' => $ad->currency(),
                'quantity' => 1,
            ]],
            'success_url' => Route::url('default', ['controller' => 'stripecheckout', 'action' => 'success_connect_guest', 'id' => $ad->id_ad]),
            'cancel_url' => Route::url('default', ['controller' => 'ad', 'action' => 'buy', 'id' => $ad->id_ad]),
            'locale' => 'auto',
        ];

        if ($ad->user->is_admin())
        {
            $stripe_session = \Stripe\Checkout\Session::create($parameters);
        }
        else
        {
            $fee = NULL;

            if ($ad->user->subscription()->loaded())
            {
                $fee = $ad->user->subscription()->plan->marketplace_fee;
            }

            if ($ad->shipping_price() AND $ad->shipping_pickup() AND core::get('shipping_pickup'))
            {
                $ad->price = $ad->price;
            }
            elseif($ad->shipping_price())
            {
                $ad->price = $ad->price + $ad->shipping_price();
            }

            $application_fee = StripeCheckout::application_fee($ad->price, $fee);

            $parameters['payment_intent_data'] = [
                'application_fee_amount' => StripeCheckout::money_format($application_fee),
                'transfer_data' => [
                    'destination' => $ad->user->stripe_user_id,
                ]
            ];

            $stripe_session = \Stripe\Checkout\Session::create($parameters);
        }

        return View::factory('pages/stripe_checkout/button_connect', ['ad' => $ad, 'session_id' => $stripe_session->id]);
    }
}
