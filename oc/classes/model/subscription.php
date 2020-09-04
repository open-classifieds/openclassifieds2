<?php defined('SYSPATH') or die('No direct script access.');
/**
 * plan for memberships
 *
 * @author      Chema <chema@open-classifieds.com>
 * @package     OC
 * @copyright   (c) 2009-2013 Open Classifieds Team
 * @license     GPL v3
 * *
 */
class Model_Subscription extends ORM {

    /**
     * Table name to use
     *
     * @access  protected
     * @var     string  $_table_name default [singular model name]
     */
    protected $_table_name = 'subscriptions';

    /**
     * Column to use as primary key
     *
     * @access  protected
     * @var     string  $_primary_key default [id]
     */
    protected $_primary_key = 'id_subscription';

    /**
     * Rule definitions for validation
     *
     * @return array
     */
    public function rules()
    {
        return array(
                    'amount_ads_left'   => array(array('numeric')),
                    'amount_ads'        => array(array('numeric')),
                );
    }

    public function exclude_fields()
    {
        return array();
    }

    /**
     * @var  array  ORM Dependency/hirerachy
     */
    protected $_belongs_to = array(

        'user' => array(
                'model'       => 'user',
                'foreign_key' => 'id_user',
            ),
        'plan' => array(
                'model'       => 'plan',
                'foreign_key' => 'id_plan',
            ),
        'order' => array(
                'model'       => 'order',
                'foreign_key' => 'id_order',
            ),
    );

    /**
     * new order therefore new subscription created
     * @param  Model_Order $order
     * @return void
     */
    public static function new_order(Model_Order $order)
    {
        $plan = new Model_Plan($order->id_product);

        //disable all the previous membership
        DB::update('subscriptions')->set(array('status' => 0))->where('id_user', '=',$order->id_user)->execute();

        //reenable the ads
        if ( Core::config('general.subscriptions_expire') == TRUE)
        {
            DB::update('ads')->set(array('status' =>Model_Ad::STATUS_PUBLISHED ))->where('id_user', '=',$order->user->id_user)->where('status', '=',Model_Ad::STATUS_UNAVAILABLE)->execute();
        }

        //calculate amount ads left
        $amount_ads_left = $plan->amount_ads;

        if($plan->amount_ads != -1)
        {
            $amount_ads_used = (New Model_Ad)
                ->where('status', '=', Model_Ad::STATUS_PUBLISHED)
                ->where('id_user', '=', $order->user->id_user)
                ->count_all();

            $amount_ads_left = max($plan->amount_ads - $amount_ads_used, 0);
        }

        //create a new subscription for this product
        $subscription = new Model_Subscription();
        $subscription->id_order = $order->id_order;
        $subscription->id_user  = $order->id_user;
        $subscription->id_plan  = $plan->id_plan;
        $subscription->amount_ads       = $plan->amount_ads;
        $subscription->amount_ads_left  = $amount_ads_left;
        $subscription->expire_date      = Date::unix2mysql(strtotime('+'.$plan->days.' days'));
        $subscription->status   = 1;

        try {
            $subscription->save();
        } catch (Exception $e) {
            throw HTTP_Exception::factory(500,$e->getMessage());
        }
    }

    /**
     * when there a new ad we decrease the subscription
     * @param  Model_User $user user to decrease ad
     * @return void
     */
    public static function new_ad(Model_User $user)
    {
        if (Core::config('general.subscriptions')==TRUE)
        {
            $subscription = $user->subscription();
            if ($subscription->loaded() AND $subscription->amount_ads_left > 0)
            {
                $subscription->amount_ads_left--;
                try {
                    $subscription->save();
                } catch (Exception $e) {
                    throw HTTP_Exception::factory(500,$e->getMessage());
                }
            }
        }
    }

    protected $_table_columns =  array (
        'id_subscription' =>
            array (
            'type' => 'int',
            'min' => '0',
            'max' => '4294967295',
            'column_name' => 'id_subscription',
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
        'id_order' =>
            array (
            'type' => 'int',
            'min' => '0',
            'max' => '4294967295',
            'column_name' => 'id_order',
            'column_default' => NULL,
            'data_type' => 'int unsigned',
            'is_nullable' => false,
            'ordinal_position' => 2,
            'display' => '10',
            'comment' => '',
            'extra' => '',
            'key' => '',
            'privileges' => 'select,insert,update,references',
            ),
        'id_user' =>
            array (
                'type' => 'int',
                'min' => '0',
                'max' => '4294967295',
                'column_name' => 'id_user',
                'column_default' => NULL,
                'data_type' => 'int unsigned',
                'is_nullable' => false,
                'ordinal_position' => 3,
                'display' => '10',
                'comment' => '',
                'extra' => '',
                'key' => '',
                'privileges' => 'select,insert,update,references',
                ),
        'id_plan' =>
            array (
                'type' => 'int',
                'min' => '0',
                'max' => '4294967295',
                'column_name' => 'id_plan',
                'column_default' => NULL,
                'data_type' => 'int unsigned',
                'is_nullable' => false,
                'ordinal_position' => 4,
                'display' => '10',
                'comment' => '',
                'extra' => '',
                'key' => '',
                'privileges' => 'select,insert,update,references',
                ),
        'amount_ads' =>
            array (
                'type' => 'int',
                'min' => '-2147483648',
                'max' => '2147483647',
                'column_name' => 'amount_ads',
                'column_default' => '1',
                'data_type' => 'int',
                'is_nullable' => true,
                'ordinal_position' => 5,
                'display' => '10',
                'comment' => '',
                'extra' => '',
                'key' => '',
                'privileges' => 'select,insert,update,references',
                ),
        'amount_ads_left' =>
            array (
                'type' => 'int',
                'min' => '-2147483648',
                'max' => '2147483647',
                'column_name' => 'amount_ads_left',
                'column_default' => '0',
                'data_type' => 'int',
                'is_nullable' => true,
                'ordinal_position' => 6,
                'display' => '10',
                'comment' => '',
                'extra' => '',
                'key' => '',
                'privileges' => 'select,insert,update,references',
                ),
        'expire_date' =>
            array (
                'type' => 'string',
                'column_name' => 'expire_date',
                'column_default' => NULL,
                'data_type' => 'datetime',
                'is_nullable' => true,
                'ordinal_position' => 7,
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
                'ordinal_position' => 8,
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
                'ordinal_position' => 9,
                'display' => '1',
                'comment' => '',
                'extra' => '',
                'key' => '',
                'privileges' => 'select,insert,update,references',
                ),
    );

} // END Model_Plan
