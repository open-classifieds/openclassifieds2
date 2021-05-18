<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Panel_Order extends Auth_CrudAjax {

    /**
    * @var $_index_fields ORM fields shown in index
    */
    protected $_index_fields = array('id_order','id_user','id_product','id_ad','paymethod','amount','currency','pay_date','created','status');

    /**
     * @var $_orm_model ORM model name
     */
    protected $_orm_model = 'order';

    /**
     *
     * list of possible actions for the crud, you can modify it to allow access or deny, by default all
     * @var array
     */
    public $crud_actions = array('create','update');

    protected $_fields_caption = array( 'id_user'       => array('model'=>'user','caption'=>'email'),
                                        'id_ad'         => array('model'=>'ad','caption'=>'title','format'=>'title'),
                                        'id_product'    => 'Model_Order::product_desc',
                                        'amount'        => 'i18n::format_currency_without_symbol',
                                         );

    function __construct(Request $request, Response $response)
    {
        $this->_filter_fields = array(  'id_user'    => 'INPUT',
                                        'pay_date'   => 'DATE',
                                        'created'    => 'DATE',
                                        'paymethod'  => array('type'=>'DISTINCT','table'=>'orders','field'=>'paymethod'),
                                        'id_product' => Model_Order::products(),
                                        'status'     => Model_Order::$statuses
                                        );

        if (core::config('payment.stripe_escrow'))
        {
            $this->_index_fields = array_merge($this->_index_fields, [
                'shipped', 'received', 'cancelled'
            ]);
        }

        parent::__construct($request, $response);

        $this->_buttons_actions = array(
                                        array( 'url'   => Route::url('oc-panel', array('controller'=>'order', 'action'=>'pay')).'/' ,
                                                'title' => __('Mark as paid'),
                                                'class' => '',
                                                'icon'  => 'fa fa-fw fa-usd'
                                                ),
                                        array( 'url'   => Route::url('oc-panel', array('controller'=>'profile', 'action'=>'order')).'/' ,
                                                'title' => __('See order'),
                                                'class' => '',
                                                'icon'  => 'fa fa-fw fa-search'
                                                )
                                        );

        if (core::config('payment.stripe_escrow'))
        {
            $this->_buttons_actions = array_merge($this->_buttons_actions, [
                [
                    'url' => Route::url('oc-panel', ['controller'=>'order', 'action'=>'received']).'/',
                    'title' => __('Mark as received'),
                    'class' => '',
                    'icon' => '',
                ],
                [
                    'url' => Route::url('oc-panel', ['controller'=>'order', 'action'=>'cancel']).'/',
                    'title' => __('Cancel order'),
                    'class' => '',
                    'icon' => '',
                ],
                [
                    'url' => Route::url('oc-panel', ['controller'=>'order', 'action'=>'refund']).'/',
                    'title' => __('Refund order'),
                    'class' => '',
                    'icon' => '',
                ],
            ]);
        }
    }

    /**
     * marks an order as paid.
     */
    public function action_pay()
    {
        $this->auto_render = FALSE;

        $id_order = $this->request->param('id');

        //retrieve info for the item in DB
        $order = new Model_Order();
        $order = $order->where('id_order', '=', $id_order)
                       ->where('status', '=', Model_Order::STATUS_CREATED)
                       ->limit(1)->find();

        if ($order->loaded())
        {
            //mark as paid
            $order->confirm_payment('cash',sprintf('Done by user %d - %s',$this->user->id_user,$this->user->email));
            //redirect him to his ads
            Alert::set(Alert::SUCCESS, __('Thanks for your payment!'));
        }
        else
        {
            Alert::set(Alert::INFO, __('Order could not be loaded'));
        }

        $this->redirect(Route::url('oc-panel', array('controller'=>'order','action'=>'index')));
    }

    public function action_received()
    {
        if (! core::config('payment.stripe_escrow'))
        {
            throw HTTP_Exception::factory(404,__('Page not found'));
        }

        $id_order = $this->request->param('id');

        $order = (new Model_Order)
            ->where('id_order', '=', $id_order)
            ->find();

        if (! $order->loaded())
        {
            Alert::set(ALERT::WARNING, __('Order could not be loaded'));

            $this->redirect(Route::url('oc-panel', ['controller'=>'order','action'=>'index']));
        }

        if (! empty($order->received))
        {
            Alert::set(ALERT::WARNING, __('Order cannot be marked as received'));

            $this->redirect(Route::url('oc-panel', ['controller'=>'order','action'=>'index']));
        }

        if (! in_array($order->id_product, [Model_Order::PRODUCT_AD_SELL, Model_Order::PRODUCT_AD_CUSTOM]))
        {
            Alert::set(ALERT::WARNING, __('Order cannot be marked as received'));

            $this->redirect(Route::url('oc-panel', ['controller'=>'order','action'=>'index']));
        }

        $order->mark_as_received();

        StripeKO::payout($order);

        Alert::set(ALERT::SUCCESS, __('Order marked as received.'));

        $this->redirect(Route::url('oc-panel', ['controller' => 'order', 'action' => 'index']));
    }

    public function action_cancel()
    {
        if (! core::config('payment.stripe_escrow'))
        {
            throw HTTP_Exception::factory(404,__('Page not found'));
        }

        $id_order = $this->request->param('id');

        $order = (new Model_Order)
            ->join('ads')
            ->using('id_ad')
            ->where('order.id_order', '=', $id_order)
            ->find();

        if (! $order->loaded())
        {
            Alert::set(ALERT::WARNING, __('Order could not be loaded'));

            $this->redirect(Route::url('oc-panel', ['controller'=>'order','action'=>'sales']));
        }

        if (! empty($order->cancelled))
        {
            Alert::set(ALERT::WARNING, __('Order cannot be cancelled'));

            $this->redirect(Route::url('oc-panel', ['controller'=>'order','action'=>'index']));
        }

        if (! in_array($order->id_product, [Model_Order::PRODUCT_AD_SELL, Model_Order::PRODUCT_AD_CUSTOM]))
        {
            Alert::set(ALERT::WARNING, __('Order cannot be cancelled'));

            $this->redirect(Route::url('oc-panel', ['controller'=>'order','action'=>'index']));
        }

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

        Alert::set(ALERT::SUCCESS, __('Order cancelled.'));

        $this->redirect(Route::url('oc-panel', ['controller'=>'order','action'=>'index']));
    }

    public function action_refund()
    {
        if (! core::config('payment.stripe_escrow'))
        {
            throw HTTP_Exception::factory(404,__('Page not found'));
        }

        $id_order = $this->request->param('id');

        $order = (new Model_Order)
            ->join('ads')
            ->using('id_ad')
            ->where('order.id_order', '=', $id_order)
            ->find();

        if (! $order->loaded())
        {
            Alert::set(ALERT::WARNING, __('Order could not be loaded'));

            $this->redirect(Route::url('oc-panel', ['controller'=>'order','action'=>'sales']));
        }

        if (empty($order->cancelled))
        {
            Alert::set(ALERT::WARNING, __('Order cannot be cancelled'));

            $this->redirect(Route::url('oc-panel', ['controller'=>'order','action'=>'index']));
        }

        if (! in_array($order->id_product, [Model_Order::PRODUCT_AD_SELL, Model_Order::PRODUCT_AD_CUSTOM]))
        {
            Alert::set(ALERT::WARNING, __('Order cannot be cancelled'));

            $this->redirect(Route::url('oc-panel', ['controller'=>'order','action'=>'index']));
        }

        StripeKO::refund($order);

        Alert::set(ALERT::SUCCESS, __('Order refunded.'));

        $this->redirect(Route::url('oc-panel', ['controller'=>'order','action'=>'index']));
    }

}
