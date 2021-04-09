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
class Cron_Subscription {

    /**
     * expired featured ads
     * @return void
     */
    public static function renew()
    {

        if (Core::config('general.subscriptions')==TRUE)
        {
            //get expired subscription that are active
            $subscriptions = new Model_Subscription();
            $subscriptions = $subscriptions
                                        ->where('status','=',1)
                                        ->where('expire_date','<=',Date::unix2mysql())
                                        ->order_by('created','desc')
                                        ->find_all();

            foreach ($subscriptions as $s)
            {
                //disable the plan
                $s->status = 0;
                try
                {
                    $s->save();
                }
                catch (Exception $e)
                {
                    throw HTTP_Exception::factory(500,$e->getMessage());
                }

                $plan = $s->plan;

                if($plan->loaded() AND $plan->status==1)
                {
                    //generate a new order
                    $order = Model_Order::new_order(NULL, $s->user, $plan->id_plan, $plan->price, core::config('payment.paypal_currency'), __('Subscription to ').$plan->name);

                    //free plan no checkout
                    if ($plan->price==0)
                    {
                        $order->confirm_payment('cash');
                    }
                    else
                    {
                        $paid = FALSE;

                        //customers who paid with sripe we can charge the recurrency
                        if ($order->user->stripe_agreement!=NULL)
                        {
                            StripeKO::init();

                            // Create the charge on Stripe's servers - this will charge the user's card
                            try
                            {
                                $charge = \Stripe\Charge::create(array(
                                                                    "amount"    => StripeKO::money_format($order->amount), // amount in cents, again
                                                                    "currency"  => $order->currency,
                                                                    'customer'  => $order->user->stripe_agreement,
                                                                    "description" => $order->description,
                                                                    "metadata"    => array("id_order" => $order->id_order))
                                                                );
                                $paid = TRUE;
                            }
                            catch(Exception $e)
                            {
                                // The card has been declined
                                Kohana::$log->add(Log::ERROR, 'Stripe The card has been declined');
                                $paid = FALSE;
                            }
                        }

                        if ($paid === TRUE)
                        {
                            //mark as paid
                            $order->confirm_payment('stripe',$charge->id);
                        }
                        else
                        {
                            //if config enabled
                            if ( Core::config('general.subscriptions_expire') == TRUE )
                            {
                                //deactivate ads
                                DB::update('ads')->set(array('status' =>Model_Ad::STATUS_UNAVAILABLE ))->where('id_user', '=',$s->user->id_user)->where('status', '=',Model_Ad::STATUS_PUBLISHED)->execute();
                            }

                            $checkout_url = $s->user->ql('default',array('controller'=>'plan','action'=>'checkout','id'=>$order->id_order));

                            $s->user->email('plan-expired', array(  '[PLAN.NAME]'      => $plan->name,
                                                                    '[URL.CHECKOUT]'   => $checkout_url));

                        }
                    }

                }//if plan loaded

            }//end foreach

        }//if subscription active

    }//function

    /**
     * remember the user his subscription is about to expire
     * @return void
     */
    public static function to_expire()
    {
        $days = 2;

        if (! Core::config('general.subscriptions'))
        {
            return;
        }

        //get expiring subscription that are active
        $subscriptions = (new Model_Subscription())
            ->where('status', '=', 1)
            ->where(DB::expr('DATE(expire_date)'), '=', Date::format('+'.$days.' days','Y-m-d'))
            ->order_by('created', 'desc')
            ->find_all();

        foreach ($subscriptions as $subscription)
        {
            SMS::send_transactional($subscription->user, 'sms_2factorin_expiring_subscription_template');
        }
    }
}
