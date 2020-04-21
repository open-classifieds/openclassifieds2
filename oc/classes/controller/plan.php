<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Plan extends Controller {


    /**
     *
     * Contruct that checks you are loged in before nothing else happens!
     */
    function __construct(Request $request, Response $response)
    {
        if (Theme::get('premium')!=1)
        {
            Alert::set(Alert::INFO,  __('Upgrade your Yclas site to PRO to activate this feature.'));
            $this->redirect(Route::url('oc-panel',array('controller'=>'market')));
        }

        parent::__construct($request,$response);
    }

    /**
     *
     * Display pricing page
     * @throws HTTP_Exception_404
     */
    public function action_index()
    {
        if (Core::config('general.subscriptions')==TRUE)
        {
            Controller::$full_width = TRUE;
            $this->template->title            = __('Pricing');
            $this->template->meta_description = $this->template->title;
            Breadcrumbs::add(Breadcrumb::factory()->set_title(__('Home'))->set_url(Route::url('default')));
            Breadcrumbs::add(Breadcrumb::factory()->set_title($this->template->title));

            $plans = New Model_Plan();

            $plans = $plans->where('status','=',1)
                            ->order_by('price','asc')
                            ->cached()->find_all();

            if ($plans->count() === 0 AND Auth::instance()->logged_in() AND Auth::instance()->get_user()->is_admin())
            {
                $url = Route::url('oc-panel', ['controller' => 'plan', 'action' => 'index']);
                Alert::set(Alert::INFO, __('Please, <a href="' . $url . '">create a plan</a> first. More information <a href="//docs.yclas.com/membership-plans/#subscription-expire" target="_blank">here</a>'));
            }

            $subscription = ($this->user!=FALSE AND $this->user->subscription()->loaded())?$this->user->subscription():FALSE;

            $this->template->content = View::factory('pages/plan/pricing',array('plans'=>$plans,'user'=>$this->user,'subscription'=>$subscription));

        }
        else//this should never happen
        {
            //throw 404
            throw HTTP_Exception::factory(404,__('Page not found'));
        }
    }


    /**
     * [action_buy] Pay for ad, and set new order
     *
     */
    public function action_buy()
    {
        if (Core::config('general.subscriptions')==FALSE)
            throw HTTP_Exception::factory(404,__('Page not found'));

        //getting the user that wants to buy now
        if (!Auth::instance()->logged_in())
        {
            Alert::set(Alert::INFO, __('To buy this product you need to register first.'));
            $this->redirect(Route::url('oc-panel'));
        }

        //check plan exists
        $plan  = new Model_Plan();
        $plan->where('seoname','=',$this->request->param('id'))->where('status','=',1)->find();

        //plan loaded
        if($plan->loaded())
        {
            //free plan can not be renewed
            if ($plan->price==0 AND $this->user->subscription()->id_plan == $plan->id_plan)
            {
                Alert::set(Alert::WARNING, __('Free plan can not be renewed, before expired'));
                HTTP::redirect(Route::url('pricing'));
            }

            //current subscribed plan can not be renewed before expired
            if ($this->user->subscription()->id_plan == $plan->id_plan)
            {
                Alert::set(Alert::WARNING, __('Your plan can not be renewed, before expired'));
                HTTP::redirect(Route::url('pricing'));
            }

            //check if elegible to downgrade
            if ($current_subscription = $this->user->subscription() AND $current_subscription->loaded())
            {
                $amount_ads_used = $current_subscription->amount_ads - $current_subscription->amount_ads_left;

                if ($amount_ads_used > $plan->amount_ads)
                {
                    Alert::set(Alert::WARNING, __('Plan has a fewer amount of ads than your current subscription.'));
                    HTTP::redirect(Route::url('pricing'));
                }
            }
            elseif ( Core::config('general.subscriptions_expire') == TRUE)
            {
                // potential user ads to be reenabled
                $amount_ads_to_use = (New Model_Ad)
                    ->where('status', '=', Model_Ad::STATUS_UNAVAILABLE)
                    ->where('id_user', '=', $this->user->id_user)
                    ->count_all();

                if ($amount_ads_to_use > $plan->amount_ads)
                {
                    Alert::set(Alert::WARNING, __('Plan has a fewer amount of ads than you have on your account.'));
                    HTTP::redirect(Route::url('pricing'));
                }
            }

            $order = Model_Order::new_order(NULL, $this->user, $plan->id_plan, $plan->price, core::config('payment.paypal_currency'), __('Subscription to ').$plan->name);

            //free plan no checkout
            if ($plan->price==0)
            {
                $order->confirm_payment('cash');
                $this->redirect(Route::url('oc-panel', array('controller'=>'profile','action'=>'orders')));
            }
            else
                $this->redirect(Route::url('default', array('controller' =>'plan','action'=>'checkout' ,'id' => $order->id_order)));
        }
        else
            throw HTTP_Exception::factory(404,__('Page not found'));

    }


    /**
     * pay an invoice, renders the paymenthods button, anyone with an ID of an order can pay it, we do not have control
     * @return [type] [description]
     */
    public function action_checkout()
    {
        $order = new Model_Order($this->request->param('id'));

        if ($order->loaded())
        {
            //hack jquery paymill
            Paymill::jquery();

            //if paid...no way jose
            if ($order->status != Model_Order::STATUS_CREATED)
            {
                Alert::set(Alert::INFO, __('This order was already paid.'));
                $this->redirect(Route::url('default'));
            }

            //checks coupons or amount of featured days
            $order->check_pricing();

            //adds VAT to the amount
            $order->add_VAT();

            //template header
            $this->template->title              = __('Checkout').' '.Model_Order::product_desc($order->id_product);
            Breadcrumbs::add(Breadcrumb::factory()->set_title(__('Home'))->set_url(Route::url('default')));
            Breadcrumbs::add(Breadcrumb::factory()->set_title(__('Pricing'))->set_url(Route::url('pricing')));
            Breadcrumbs::add(Breadcrumb::factory()->set_title($this->template->title ));

            Controller::$full_width = TRUE;

            $this->template->bind('content', $content);

            $this->template->content = View::factory('pages/ad/checkout',array('order' => $order));
        }
        else
        {
            //throw 404
            throw HTTP_Exception::factory(404,__('Page not found'));
        }
    }


} // End Page controller
