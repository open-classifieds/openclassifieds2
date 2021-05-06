<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Cron for advertisements
 *
 * @author      Chema <chema@open-classifieds.com>
 * @package     Cron
 * @copyright   (c) 2009-2014 Open Classifieds Team
 * @license     GPL v3
 *
 */
class Cron_Ad {

    /**
     * expired featured ads
     * @return void
     */
    public static function expired_featured()
    {
        //find expired ads of yesterday
        $ads = new Model_Ad();
        $ads = $ads ->where('status','=',Model_Ad::STATUS_PUBLISHED)
                    ->where('featured','<', Date::unix2mysql())
                    ->where('featured','IS NOT',NULL)
                    ->find_all();

        foreach ($ads as $ad)
        {
            $edit_url = $ad->user->ql('oc-panel',array('controller'=>'myads','action'=>'update','id'=>$ad->id_ad));

            $ad->user->email('ad-expired', array('[AD.NAME]'      =>$ad->title,
                                                 '[URL.EDITAD]'   =>$edit_url));

            //unset featured ad
            $ad->featured = NULL;
            try
            {
                $ad->validation_required(FALSE)->save();
            }
            catch (Exception $e)
            {
                throw HTTP_Exception::factory(500,$e->getMessage());
            }
        }
    }


    /**
     * expired featured ads
     * @return void
     */
    public static function expired()
    {
        //feature expire ads from yesterday
        if((New Model_Field())->get('expiresat'))
        {
            $ads = new Model_Ad();
            $ads = $ads->where('status','=',Model_Ad::STATUS_PUBLISHED)
                ->where(DB::expr('DATE(cf_expiresat)'), '<', Date::unix2mysql())
                ->find_all();;
        }
        elseif(core::config('advertisement.expire_date') > 0)
        {
            $ads = new Model_Ad();
            $ads = $ads ->where('status','=',Model_Ad::STATUS_PUBLISHED)
                        ->where(DB::expr('DATE(DATE_ADD( published, INTERVAL '.core::config('advertisement.expire_date').' DAY))'),'<', Date::unix2mysql())
                        ->find_all();
        }
        else
        {
            return;
        }

        foreach ($ads as $ad)
        {
            $edit_url = $ad->user->ql('oc-panel',array('controller'=>'myads','action'=>'update','id'=>$ad->id_ad));

            $ad->user->email('ad-expired', array('[AD.NAME]'      =>$ad->title,
                                                 '[URL.EDITAD]'   =>$edit_url));

            //Change status to unavailable
            $ad->status = Model_Ad::STATUS_UNAVAILABLE;

            try
            {
                $ad->validation_required(FALSE)->save();
            }
            catch (Exception $e)
            {
                throw HTTP_Exception::factory(500,$e->getMessage());
            }
        }
    }


    /**
     * remember the user his ad is about to expire
     * @param integer days num of days before to notify
     * @return void
     */
    public static function to_expire($days = 2)
    {
        //feature expire ads from yesterday
        if((New Model_Field())->get('expiresat'))
        {
            $ads = new Model_Ad();
            $ads = $ads->where('status','=',Model_Ad::STATUS_PUBLISHED)
                ->where(DB::expr('DATE(cf_expiresat)'), '=', Date::format('+'.$days.' days','Y-m-d'))
                ->find_all();
        }
        elseif(core::config('advertisement.expire_date') > 0)
        {
            $ads = new Model_Ad();
            $ads = $ads ->where('status','=',Model_Ad::STATUS_PUBLISHED)
                        ->where(DB::expr('DATE(DATE_ADD( published, INTERVAL '.core::config('advertisement.expire_date').' DAY))'),'=', Date::format('+'.$days.' days','Y-m-d'))
                        ->find_all();

        }
        else
        {
            return;
        }

        foreach ($ads as $ad)
        {
            $edit_url = $ad->user->ql('oc-panel',array('controller'=>'myads','action'=>'update','id'=>$ad->id_ad));

            $ad->user->email('ad-to-expire', array('[AD.NAME]'      =>$ad->title,
                                                   '[URL.EDITAD]'   =>$edit_url));
        }
    }


    /**
     * unpaid orders for ads 2 days ago reminder
     * @param integer $days, how many days after created
     * @return void
     */
    public static function unpaid($days = 2)
    {
        //getting orders not paid from 2 days ago
        $orders = new Model_Order();
        $orders = $orders->where('status','=',Model_Order::STATUS_CREATED)
                            ->where(DB::expr('DATE( created)'),'=', Date::format('-'.$days.' days','Y-m-d'))
                            ->where('id_ad','IS NOT',NULL)
                            ->find_all();

        foreach ($orders as $order)
        {
            $url_checkout = $order->user->ql('default', array('controller'=>'ad','action'=>'checkout','id'=>$order->id_order));

            $order->user->email('new-order', array(  '[ORDER.ID]'    => $order->id_order,
                                                     '[ORDER.DESC]'  => $order->description,
                                                     '[URL.CHECKOUT]'=> $url_checkout));
        }
    }

    /**
     * unreceived orders reminder
     * @return void
     */
    public static function unreceived()
    {
        $days = Core::config('general.ewallet_mark_as_received_reminder_after_n_days');

        $orders = (new Model_Order)
            ->where('received', 'IS', NULL)
            ->where(DB::expr('DATE(pay_date)'), '=', Date::format('-'.$days.' days','Y-m-d'))
            ->where('id_product', 'IN', [Model_Order::PRODUCT_AD_SELL, Model_Order::PRODUCT_AD_CUSTOM])
            ->find_all();

        foreach ($orders as $order)
        {
            $url = $order->user->ql('oc-panel', [
                'controller' => 'profile',
                'action' => 'order_received',
                'id' => $order->id_order,
            ]);

            $order->user->email('mark-as-received', [
                '[ORDER.ID]' => $order->id_order,
                '[ORDER.DESC]' => $order->description,
                '[URL.CHECKOUT]'=> $url,
            ]);
        }
    }

    /**
     * mark orders as received
     * @return void
     */
    public static function mark_as_received()
    {
        $days = Core::config('general.ewallet_mark_as_received_after_n_days');

        $orders = (new Model_Order)
            ->where('received', 'IS', NULL)
            ->where(DB::expr('DATE(pay_date)'), '=', Date::format('-'.$days.' days','Y-m-d'))
            ->where('id_product', 'IN', [Model_Order::PRODUCT_AD_SELL, Model_Order::PRODUCT_AD_CUSTOM])
            ->find_all();

        foreach ($orders as $order)
        {
            $order->mark_as_received();
        }
    }

    /**
     * mark orders as cancelled
     * @return void
     */
    public static function mark_as_cancelled()
    {
        if (! core::config('payment.stripe_escrow'))
        {
            return;
        }

        $days = Core::config('payment.stripe_cancel_orders_after_n_days');

        $orders = (new Model_Order)
            ->where('status', '=', Model_Order::STATUS_PAID)
            ->where('shipped', 'IS', NULL)
            ->where('cancelled', 'IS', NULL)
            ->where(DB::expr('DATE(pay_date)'), '=', Date::format('-'.$days.' days','Y-m-d'))
            ->where('id_product', 'IN', [Model_Order::PRODUCT_AD_SELL])
            ->find_all();

        foreach ($orders as $order)
        {
            $order->mark_as_cancelled();

            $order->user->email('order-cancelled', [
                '[ORDER.ID]' => $order->id_order,
                '[ORDER.DESC]' => $order->description,
            ]);

            $order->ad->user->email('order-cancelled', [
                '[ORDER.ID]' => $order->id_order,
                '[ORDER.DESC]' => $order->description,
            ]);

            StripeKO::reverse_transfer($order);
        }
    }



}
