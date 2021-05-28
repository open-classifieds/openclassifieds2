<?php defined('SYSPATH') or die('No direct script access.');

/**
 * mollie helper class
 *
 * @package    OC
 * @category   Payment
 * @author     Oliver <oliver@open-classifieds.com>
 * @copyright  (c) 2009-2015 Open Classifieds Team
 * @license    GPL v3
 */

class mollie {

    /**
     * generates HTML for pay button
     * @return string
     */
    public static function button(Model_Order $order)
    {
        if (! Core::extra_features())
        {
            return;
        }

        if (empty(Core::config('payment.mollie_api_key')))
        {
            return;
        }

        return View::factory('pages/mollie/button', ['order' => $order]);
    }

    public static function money_format($amount)
    {
        return number_format(round($amount, 2), 2);
    }
}
