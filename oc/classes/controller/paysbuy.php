<?php defined('SYSPATH') or die('No direct script access.');

/**
 * paysbuy class
 *
 * @package Open Classifieds
 * @subpackage Core
 * @category Payment
 * @author Chema Garrido <chema@open-classifieds.com>
 * @license GPL v3
 */

class Controller_Paysbuy extends Controller{
	

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
            // Security Checking : The results from Paysbuy should be completed and not corrupted.
            if (empty($_POST['result']) OR empty($_POST['apCode']) OR empty($_REQUEST['amt'])) 
            {
                Alert::set(Alert::INFO, __('Please fill your card details.'));
                $this->redirect(Route::url('default', array('controller'=>'ad','action'=>'checkout','id'=>$order->id_order)));
            }
        
            //its a fraud...lets let him know
            if ( $order->is_fraud() === TRUE )
            {
                Alert::set(Alert::ERROR, __('We had, issues with your transaction. Please try paying with another paymethod.'));
                $this->redirect(Route::url('default', array('controller'=>'ad','action'=>'checkout','id'=>$order->id_order)));
            }
           
            // Value from untrusts identity which Act as Paysbuy Co. Ltd.
            $payment_status  = substr($_POST["result"], 0, 2);
            $cartnumber      = trim(substr($_POST["result"],2));
            $amount          = $_POST['amt'];
            $psbRef          = $_POST['apCode'];

            //correct payment?
            if($payment_status == '00' AND paysbuy::recheck($cartnumber, $psbRef, $amount) ) 
            {
                //mark as paid
                $order->confirm_payment('paysbuy',$psbRef);
                
                //redirect him to his ads
                Alert::set(Alert::SUCCESS, __('Thanks for your payment!'));
                $this->redirect(Route::url('oc-panel', array('controller'=>'profile','action'=>'orders')));
            }
            else
            {
                // The card has been declined
                Kohana::$log->add(Log::ERROR, 'Paysbuy The card has been declined');
                Alert::set(Alert::ERROR, 'Paysbuy The card has been declined');
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
