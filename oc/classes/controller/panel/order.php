<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Panel_Order extends Auth_CrudAjax {

    /**
    * @var $_index_fields ORM fields shown in index
    */
    protected $_index_fields = array('id_order','id_user','id_product', 'paymethod','amount','currency','pay_date','created','status');
    
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
                                        'id_product'    => 'Model_Order::product_desc',
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
        
        parent::__construct($request, $response);

        $this->_buttons_actions = array(
                                        array( 'url'   => Route::url('oc-panel', array('controller'=>'order', 'action'=>'pay')).'/' ,
                                                'title' => __('Mark as paid'),
                                                'class' => 'btn btn-xs btn-danger',
                                                'icon'  => 'glyphicon glyphicon-usd'
                                                ), 
                                        );
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

}
