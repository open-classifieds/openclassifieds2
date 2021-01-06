<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Coupon
 *
 * @author      Chema <chema@open-classifieds.com>
 * @package     Core
 * @copyright   (c) 2009-2013 Open Classifieds Team
 * @license     GPL v3
 */

class Model_Coupon extends ORM {

    /**
     * @var  string  Table name
     */
    protected $_table_name = 'coupons';

    /**
     * @var  string  PrimaryKey field name
     */
    protected $_primary_key = 'id_coupon';


    /**
     * Rule definitions for validation
     *
     * @return array
     */
    public function rules()
    {
        return array(
                    'valid_date'             => array(  array('not_empty'),array('date') ),
                    'number_coupons'         => array(  array('not_empty'),
                                                        array('numeric'),
                                                        array('range',array(':value',0,10000000000)),
                                                    ),
                    'discount_amount'        => array(  array('price'),
                                                        array('range',array(':value',0,10000000000)),
                                                    ),
                    'discount_percentage'    => array(  array('price'),
                                                        array('range',array(':value',0,100)),
                                                    ),
                    'notes'                   => array(
                                                        array('max_length', array(':value', 245)),
                                                    ),
                    'name'                   => array(
                                                        array('not_empty'),
                                                        array('max_length', array(':value', 145)),
                                                        array('min_length', array(':value', 3)),
                                                        array(array($this, 'unique'), array('name', ':value')),
                                                    ),
                );
    }

    /**
     * global Model Coupon instance get from controller so we can access from anywhere like Model_Coupon::current()
     * @var Model_Coupon
     */
    protected static $_current = NULL;


    public function exclude_fields()
    {
        return array('created');
    }

    /**
     * verifies if a coupon is valid for that product
     * @param  int $id_product
     * @return bolean
     */
    public static function valid($id_product)
    {
        //loaded, not for product ad sell
        if (Model_Coupon::current()->loaded() AND !in_array($id_product, [Model_Order::PRODUCT_AD_SELL, Model_Order::PRODUCT_AD_CUSTOM]) AND
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

    /**
     * decreases de number available of coupon and deletes de cookie ;)
     * @param  model_coupon $coupon
     * @return void
     */
    public static function sale(Model_Coupon $coupon = NULL)
    {
        if ($coupon===NULL)
            $coupon = self::current();

        if ($coupon->loaded())
        {
            $coupon->number_coupons--;
            try {
                $coupon->save();
            }
            catch (ORM_Validation_Exception $e)
            {
                throw HTTP_Exception::factory(500,$e->errors(''));
            }
            catch (Exception $e)
            {
                throw HTTP_Exception::factory(500,$e->getMessage());
            }
            Session::instance()->set('coupon','');
        }
    }



    /**
     * returns the current category
     * @return Model_Category
     */
    public static function current()
    {
        //we don't have so let's retrieve
        if (self::$_current === NULL)
            self::$_current = self::get_coupon();

        return self::$_current;
    }


    /**
     * get the coupon from the query or from the sesion or the post in paypal
     * @return Model_Coupon or null if not found
     */
    public static function get_coupon($coupon_name = NULL)
    {
        if ($coupon_name===NULL)
            $coupon_name = core::post('custom',core::request('coupon',Session::instance()->get('coupon')));

        $coupon = new Model_Coupon();

        /**
         * Deletes a coupon in use
         */
        if(core::request('coupon_delete') != NULL)
        {
            Session::instance()->set('coupon','');
            Alert::set(Alert::INFO, __('Coupon deleted.'));
        }
        //selected coupon Paypal custom field, or coupon via get/post or session
        elseif( $coupon_name!==NULL AND !empty($coupon_name) )
        {
            $slug_coupon   = new Model_Coupon();
            $coupon = $slug_coupon->where('name', '=', $coupon_name )
                    ->where('number_coupons','>',0)
                    ->where('valid_date','>',Date::unix2mysql())
                    ->where('status','=',1)
                    ->limit(1)->find();
            if ($coupon->loaded())
            {
                //only add it to session if its different than before
                if (Session::instance()->get('coupon')!=$coupon->name)
                {
                    Alert::set(Alert::SUCCESS, __('Coupon added!'));
                    Session::instance()->set('coupon',$coupon->name);
                }

            }
            else
            {
                Alert::set(Alert::INFO, __('Coupon not valid, expired or already used.'));
                Session::instance()->set('coupon','');
            }

        }

        return $coupon;
    }

    /**
     * tells if theres coupons active in the platform, to show the coupon form, or not ;)
     * @return bool
     */
    public static function available()
    {
        $coupon   = new self();
        $coupon = $coupon
                    ->where('number_coupons','>',0)
                    ->where('valid_date','>',Date::unix2mysql())
                    ->where('status','=',1)
                    ->limit(1)->find();

        return $coupon->loaded();
    }

    protected $_table_columns =
array (
  'id_coupon' =>
  array (
    'type' => 'int',
    'min' => '0',
    'max' => '4294967295',
    'column_name' => 'id_coupon',
    'column_default' => NULL,
    'data_type' => 'int unsigned',
    'is_nullable' => false,
    'ordinal_position' => 1,
    'display' => '10',
    'comment' => '',
    'extra' => 'auto_increment',
    'key' => 'PRI',
    'privileges' => 'select,insert,update,references',
  ),
  'id_product' =>
  array (
    'type' => 'int',
    'min' => '0',
    'max' => '4294967295',
    'column_name' => 'id_product',
    'column_default' => NULL,
    'data_type' => 'int unsigned',
    'is_nullable' => true,
    'ordinal_position' => 2,
    'display' => '10',
    'comment' => '',
    'extra' => '',
    'key' => '',
    'privileges' => 'select,insert,update,references',
  ),
  'name' =>
  array (
    'type' => 'string',
    'column_name' => 'name',
    'column_default' => NULL,
    'data_type' => 'varchar',
    'is_nullable' => false,
    'ordinal_position' => 3,
    'character_maximum_length' => '145',
    'collation_name' => 'latin1_swedish_ci',
    'comment' => '',
    'extra' => '',
    'key' => 'UNI',
    'privileges' => 'select,insert,update,references',
  ),
  'notes' =>
  array (
    'type' => 'string',
    'column_name' => 'notes',
    'column_default' => NULL,
    'data_type' => 'varchar',
    'is_nullable' => true,
    'ordinal_position' => 4,
    'character_maximum_length' => '245',
    'collation_name' => 'latin1_swedish_ci',
    'comment' => '',
    'extra' => '',
    'key' => '',
    'privileges' => 'select,insert,update,references',
  ),
  'discount_amount' =>
  array (
    'type' => 'float',
    'exact' => true,
    'column_name' => 'discount_amount',
    'column_default' => '0.000',
    'data_type' => 'decimal',
    'is_nullable' => false,
    'ordinal_position' => 5,
    'numeric_precision' => '14',
    'numeric_scale' => '3',
    'comment' => '',
    'extra' => '',
    'key' => '',
    'privileges' => 'select,insert,update,references',
  ),
  'discount_percentage' =>
  array (
    'type' => 'float',
    'exact' => true,
    'column_name' => 'discount_percentage',
    'column_default' => '0.000',
    'data_type' => 'decimal',
    'is_nullable' => false,
    'ordinal_position' => 6,
    'numeric_precision' => '14',
    'numeric_scale' => '3',
    'comment' => '',
    'extra' => '',
    'key' => '',
    'privileges' => 'select,insert,update,references',
  ),
  'number_coupons' =>
  array (
    'type' => 'int',
    'min' => '-2147483648',
    'max' => '2147483647',
    'column_name' => 'number_coupons',
    'column_default' => NULL,
    'data_type' => 'int',
    'is_nullable' => true,
    'ordinal_position' => 7,
    'display' => '10',
    'comment' => '',
    'extra' => '',
    'key' => '',
    'privileges' => 'select,insert,update,references',
  ),
  'valid_date' =>
  array (
    'type' => 'string',
    'column_name' => 'valid_date',
    'column_default' => NULL,
    'data_type' => 'datetime',
    'is_nullable' => true,
    'ordinal_position' => 8,
    'comment' => '',
    'extra' => '',
    'key' => '',
    'privileges' => 'select,insert,update,references',
  ),
  'created' =>
  array (
    'type' => 'string',
    'column_name' => 'created',
    'column_default' => 'CURRENT_TIMESTAMP',
    'data_type' => 'timestamp',
    'is_nullable' => false,
    'ordinal_position' => 9,
    'comment' => '',
    'extra' => '',
    'key' => '',
    'privileges' => 'select,insert,update,references',
  ),
  'status' =>
  array (
    'type' => 'int',
    'min' => '-128',
    'max' => '127',
    'column_name' => 'status',
    'column_default' => '0',
    'data_type' => 'tinyint',
    'is_nullable' => false,
    'ordinal_position' => 10,
    'display' => '1',
    'comment' => '',
    'extra' => '',
    'key' => '',
    'privileges' => 'select,insert,update,references',
  ),
);


}
