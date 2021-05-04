<?php defined('SYSPATH') or die('No direct script access.');

/**
 * razorpay helper class
 *
 * @package    OC
 * @category   Payment
 * @author     Oliver <oliver@open-classifieds.com>
 * @copyright  (c) 2009-2015 Open Classifieds Team
 * @license    GPL v3
 */

class razorpay {

    /**
     * generates HTML for pay button
     * @param  Model_Order $order
     * @return string
     */
    public static function button(Model_Order $order)
    {
        if (! Core::extra_features())
        {
            return;
        }

        if (empty(Core::config('payment.razorpay_key_id')) OR empty(Core::config('payment.razorpay_key_secret')))
        {
            return;
        }

        require_once Kohana::find_file('vendor', 'razorpay-php/Razorpay', 'php');

        $api = new \Razorpay\Api\Api(Core::config('payment.razorpay_key_id'), Core::config('payment.razorpay_key_secret'));

        $razorpay_order = $api->order->create([
            'receipt' => $order->id_order,
            'amount' => $order->amount * 100,
            'currency' => 'INR',
            'payment_capture' => 1
        ]);

        $order->txn_id = $razorpay_order['id'];

        $order->save();

        return View::factory('pages/razorpay/button', [
            'order' => $order,
            'razorpay_order' => $razorpay_order,
            'key_id' => Core::config('payment.razorpay_key_id'),
        ]);
    }

}
