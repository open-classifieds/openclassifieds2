<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Panel_Order extends Auth_Crud {

    /**
    * @var $_index_fields ORM fields shown in index
    */
    protected $_index_fields = array('id_order','id_user', 'paymethod','amount','status');
    
    /**
     * @var $_orm_model ORM model name
     */
    protected $_orm_model = 'order';

    /**
     *
     * list of possible actions for the crud, you can modify it to allow access or deny, by default all
     * @var array
     */
    public $crud_actions = array('update');

    public function __construct($request, $response)
    {
        parent::__construct($request, $response);

        Breadcrumbs::add(Breadcrumb::factory()->set_title(__('Orders'))->set_url(Route::url('oc-panel',array('controller'  => 'order'))));
    }


    /**
     *
     * Loads a basic list info
     * @param string $view template to render 
     */
    public function action_index($view = NULL)
    {
        $this->template->title = __('Orders');

        $this->template->styles = array('//cdn.jsdelivr.net/bootstrap.datepicker/0.1/css/datepicker.css' => 'screen');
        $this->template->scripts['footer'] = array('//cdn.jsdelivr.net/bootstrap.datepicker/0.1/js/bootstrap-datepicker.js',
                                                    'js/oc-panel/crud/index.js','js/oc-panel/stats/dashboard.js');

        $orders = new Model_Order();

        //filter email
        if (core::request('email')!==NULL)
        {
            $user = new Model_User();
            $user->where('email','=',core::request('email'))->limit(1)->find();
            if ($user->loaded())
                $orders = $orders->where('id_user', '=', $user->id_user);
        }

        //filter date
        $from_date_temp = Core::request('from_date'); // This temporary variable is needed - see http://stackoverflow.com/questions/1532693/weird-php-error-cant-use-function-return-value-in-write-context for why
        $to_date_temp = Core::request('to_date'); // This temporary variable is needed - see http://stackoverflow.com/questions/1532693/weird-php-error-cant-use-function-return-value-in-write-context for why
        if (!empty($from_date_temp) AND !empty($to_date_temp))
        {
            //Getting the dates range
            $from_date = Core::request('from_date',strtotime('-1 month'));
            $to_date   = Core::request('to_date',time());

            $orders = $orders->where('pay_date','between',array($from_date,$to_date));
        }

        //filter status
        if (is_numeric(core::request('status')))
        {
            $orders = $orders->where('status', '=', core::request('status'));
        }

        //order by paid if we are filtering paid....
        if (core::request('status')==Model_Order::STATUS_PAID)
            $orders->order_by('pay_date','desc');  
        else
            $orders->order_by('id_order','desc');        

        $items_per_page = core::request('items_per_page',10);

        $pagination = Pagination::factory(array(
                    'view'           => 'oc-panel/crud/pagination',
                    'total_items'    => $orders->count_all(),
                    'items_per_page' => $items_per_page,
        ))->route_params(array(
                    'controller' => $this->request->controller(),
                    'action'     => $this->request->action(),
        ));

        $pagination->title($this->template->title);

        $orders = $orders
                    ->limit($items_per_page)
                    ->offset($pagination->offset)
                    ->find_all();

        $pagination = $pagination->render();

        
        $this->render('oc-panel/pages/order/index', array('orders' => $orders,
            'pagination'=>$pagination));
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
