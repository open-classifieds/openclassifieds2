<?php defined('SYSPATH') or die('No direct script access.');

/**
 * MercadoPago helper class
 *
 * @package    OC
 * @category   Payment
 * @author     Chema <chema@open-classifieds.com>
 * @copyright  (c) 2009-2016 Open Classifieds Team
 * @license    GPL v3
 */

class MercadoPago {
    

    public static function money_format($amount)
    {
        return round($amount,2);
    }
    
    /**
     * generates HTML for apy buton
     * @param  Model_Order $order 
     * @return string                 
     */
    public static function button(Model_Order $order)
    {
        if (Core::config('payment.mercadopago_client_id')!='' AND Core::config('payment.mercadopago_client_secret')!='' AND Core::extra_features() == TRUE)
        {
            // Include Mercadopago library
            require Kohana::find_file('vendor/mercadopago', 'mercadopago');

            // Create an instance with your MercadoPago credentials (CLIENT_ID and CLIENT_SECRET):
            $mp = new MP(core::config('payment.mercadopago_client_id'), core::config('payment.mercadopago_client_secret'));

            $preference_data = array(
                "items" => array(
                    array(
                        "id"          => $order->id_order,
                        "title"       => Model_Order::product_desc($order->id_product),
                        "currency_id" => $order->currency,
                        "picture_url" => $order->ad->get_first_image(),
                        "description" => Text::limit_chars(Text::removebbcode($order->description), 30, NULL, TRUE),
                        "category_id" => $order->ad->category->name,
                        "quantity"    => 1,
                        "unit_price"  => self::money_format($order->amount)
                    )
                ),
                "payer" => array(
                    "name"  => Auth::instance()->logged_in() ? Auth::instance()->get_user()->name : $order->ad->user->name,
                    "email" => Auth::instance()->logged_in() ? Auth::instance()->get_user()->email : $order->ad->user->email,
                ),
                "back_urls" => array(
                    "success" => Route::url('oc-panel', array('controller'=>'profile','action'=>'orders')),
                    "failure" => Route::url('default', array('controller'=>'ad','action'=>'checkout','id'=>$order->id_order)),
                ),
                "auto_return" => "approved",
                "notification_url" => Route::url('default',array('controller'=>'mercadopago','action'=>'ipn','id'=>$order->id_order)),
                "expires" => false,
            );

            $preference = $mp->create_preference($preference_data);
            $link       = $preference["response"]["init_point"];

            return View::factory('pages/mercadopago/button',array('link'=>$link));
        }

        return '';
    }


}