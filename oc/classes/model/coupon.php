<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Coupon
 *
 * @author      Chema <chema@open-classifieds.com>
 * @package     Core
 * @copyright   (c) 2009-2013 Open Classifieds Team
 * @license     GPL v3
 */

class Model_Coupon extends Model_OC_Coupon {


    /**
     * verifies if a coupon is valid for that product
     * @param  int $id_product 
     * @return bolean
     */
    public static function valid($id_product)
    {
        //loaded, not for product ad sell
        if (Model_Coupon::current()->loaded()  AND  $id_product!=Model_Order::PRODUCT_AD_SELL AND
            (Model_Coupon::current()->id_product == $id_product OR Model_Coupon::current()->id_product == NULL))
            return TRUE;

        return FALSE;
            
    }


    /**
     * calculates the price adding a coupon
     * @param  int $id_product 
     * @param  float $amount     
     * @return float             
     */
    public static function price($id_product,$amount)
    {
        //coupon added only calculate price if coupon is NULL or for that poroduct
        if (self::valid($id_product))
        {
            //calculating price by applying either a discount amount or a discount percentage
            $discounted_price = abs(Model_Coupon::current()->discount_amount);
            if ($discounted_price > 0)
                $discounted_price = round($amount - $discounted_price, 2);
            else
            {
                $discounted_price = abs(Model_Coupon::current()->discount_percentage);
                if ($discounted_price > 0)
                    $discounted_price = round($amount - ($amount * $discounted_price / 100.0), 2);
                else
                    // both discount_amount and discount_percentage are 0
                    $discounted_price = 0;
            }
            //in case calculated price is negative
            $amount = max($discounted_price, 0);
        }

        //return the price
        return $amount;
    }

    /**
     * 
     * formmanager definitions
     * 
     */
    public function form_setup($form)
    {   

        $form->fields['id_product']['display_as']   = 'select';
        $form->fields['id_product']['options']      = array_keys(Model_Order::products());

        $form->fields['valid_date']['attributes']['placeholder']        = 'yyyy-mm-dd';
        $form->fields['valid_date']['attributes']['data-toggle']        = 'datepicker';
        $form->fields['valid_date']['attributes']['data-date']          = '';
        $form->fields['valid_date']['attributes']['data-date-format']   = 'yyyy-mm-dd';
    }


}