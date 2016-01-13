<?php

/**
* 2co class
*
* @package Open Classifieds
* @subpackage Core
* @category Helper
* @author Chema Garrido <chema@open-classifieds.com>
* @license GPL v3
*/

class Controller_twocheckout extends Controller{
    
    /**
     * [action_form] generates the form to pay at paypal
     */
    public function action_pay()
    { 
        $this->auto_render = FALSE;

        //sandobx doesnt do the x_receipt_link_url redirect so in sanbbox instead we put the order id
        $id_order = (Core::config('payment.twocheckout_sandbox') == 1)? Core::request('x_receipt_link_url') : $this->request->param('id') ;
      
        //retrieve info for the item in DB
        $order = new Model_Order();
        $order = $order->where('id_order', '=', $id_order)
                       ->where('status', '=', Model_Order::STATUS_CREATED)
                       ->limit(1)->find();

        if ($order->loaded())
        {
            //its a fraud...lets let him know
            if ( $order->is_fraud() === TRUE )
            {
                Alert::set(Alert::ERROR, __('We had, issues with your transaction. Please try paying with another paymethod.'));
                $this->redirect(Route::url('default', array('controller'=>'ad','action'=>'checkout','id'=>$order->id_order)));
            }

            if ( ($order_id = twocheckout::validate_passback($order))!==FALSE ) 
            {
                //mark as paid
                $order->confirm_payment('2checkout',$order_id);
                
                //redirect him to his ads
                Alert::set(Alert::SUCCESS, __('Thanks for your payment!'));
                $this->redirect(Route::url('oc-panel', array('controller'=>'profile','action'=>'orders')));
            }
            else
            {
                Alert::set(Alert::INFO, __('Please fill your card details.'));
                $this->redirect(Route::url('default', array('controller'=>'ad','action'=>'checkout','id'=>$order->id_order)));
            }
            
        }
        else
        {
            Alert::set(Alert::INFO, __('Order could not be loaded'));
            $this->redirect(Route::url('default', array('controller'=>'ad','action'=>'checkout','id'=>$order->id_order)));
        }
    }


}