<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Panel_Messages extends Auth_Frontcontroller {

    public function action_index()
    {
        $messages   = Model_Message::get_threads($this->user,core::get('status'));
        $res_count  = $messages->count_all();

        Breadcrumbs::add(Breadcrumb::factory()->set_title(__('Messaging'))->set_url(Route::url('oc-panel', array('controller' => 'messages', 'action' => 'index'))));
        Breadcrumbs::add(Breadcrumb::factory()->set_title(__('Inbox')));

        Controller::$full_width = TRUE;

        if ($res_count > 0)
        {

            $pagination = Pagination::factory(array('view'              => 'oc-panel/crud/pagination',
                                                    'total_items'       => $res_count,
                                                    'items_per_page'    => core::config('advertisement.advertisements_per_page')
                                                    ))
                                    ->route_params(array(
                                                    'controller'    => $this->request->controller(),
                                                    'action'        => $this->request->action(),
                                                    ));

            Breadcrumbs::add(Breadcrumb::factory()->set_title(sprintf(__("Page %d"), $pagination->current_page)));

            $messages = $messages   ->order_by('created','desc')
                                    ->limit($pagination->items_per_page)
                                    ->offset($pagination->offset)
                                    ->find_all();

            $this->template->styles = array('css/jquery.sceditor.default.theme.min.css' => 'screen');

            $this->template->scripts['footer'] = array( 'js/jquery.sceditor.bbcode.min.js',
                                                        'js/jquery.sceditor.plaintext.min.js',
                                                        'js/messages.js');

            $this->template->content = View::factory('oc-panel/pages/messages/index', array('messages'      => $messages,
                                                                                            'pagination'    => $pagination,
                                                                                            'user'          => $this->user));
        }
        else
        {

            $this->template->content = View::factory('oc-panel/pages/messages/index', array('messages'      => NULL,
                                                                                            'pagination'    => NULL,
                                                                                            'user'          => $this->user));
        }
    }

    public function action_message()
    {
        Controller::$full_width = TRUE;

        //Detect spam users, show him alert
        if (core::config('general.black_list') == TRUE AND Model_User::is_spam() === TRUE)
        {
            Alert::set(Alert::ALERT, __('Your profile has been disable for posting, due to recent spam content! If you think this is a mistake please contact us.'));
            $this->redirect(Route::url('default'));
        }

        if ($this->request->param('id') !== NULL AND is_numeric($id_msg_thread = $this->request->param('id')))
        {
            $messages = Model_Message::get_thread($id_msg_thread, $this->user);

            if ($messages !== FALSE)
            {
                $msg_thread = new Model_Message();
                $msg_thread = $msg_thread->where('id_message','=',$id_msg_thread)
                                            ->where('id_message_parent','=',$id_msg_thread)->find();

                // send reply message
                if ($this->request->post() AND Form::token('reply_message', TRUE))
                {
                    $validation = Validation::factory($this->request->post())->rule('message', 'not_empty');

                    if ($validation->check())
                    {
                        $ret = Model_Message::reply(core::post('message'), $this->user, $id_msg_thread, NULL);

                        if ($ret !== FALSE)
                        {
                            Alert::set(Alert::SUCCESS, __('Reply created.'));
                            $this->redirect(Route::url('oc-panel', array('controller' => 'messages', 'action' => 'message', 'id' => Request::current()->param('id'))));
                        }
                        else
                            Alert::set(Alert::ERROR, __('Message not sent'));
                    }
                    else
                    {
                        $errors = $validation->errors('message');
                    }
                }

                Breadcrumbs::add(Breadcrumb::factory()->set_title(__('Messaging'))->set_url(Route::url('oc-panel', array('controller' => 'messages', 'action' => 'index'))));
                if ($msg_thread->id_ad !== NULL AND $msg_thread->ad->loaded())
                    Breadcrumbs::add(Breadcrumb::factory()->set_title($msg_thread->ad->title));
                else
                    Breadcrumbs::add(Breadcrumb::factory()->set_title(__('Direct Message')));

                $this->template->styles = array('css/jquery.sceditor.default.theme.min.css' => 'screen',
                                                '//cdn.jsdelivr.net/sweetalert/1.1.3/sweetalert.css' => 'screen');

                $this->template->scripts['footer'] = array( 'js/jquery.sceditor.bbcode.min.js',
                                                            'js/jquery.sceditor.plaintext.min.js',
                                                            '//cdn.jsdelivr.net/sweetalert/1.1.3/sweetalert.min.js',
                                                            'js/messages.js');

                $this->template->content = View::factory('oc-panel/pages/messages/message', array(  'msg_thread'    => $msg_thread,
                                                                                                    'messages'      => $messages,
                                                                                                    'user'          => $this->user));
            }
            else
            {
                Alert::set(Alert::ERROR, __('Message not found'));
                $this->redirect(Route::url('oc-panel', array('controller' => 'messages', 'action' => 'index')));
            }
        }
        else
        {
            Alert::set(Alert::ERROR, __('Message not found'));
            $this->redirect(Route::url('oc-panel', array('controller' => 'messages', 'action' => 'index')));
        }
    }

    public function action_custom_order()
    {
        if (! core::config('general.custom_orders'))
        {
             $this->redirect(Route::url('default'));
        }

        if (! $this->request->post())
        {
            Alert::set(Alert::ERROR, __('Message not found'));

            $this->redirect(Route::url('oc-panel', array('controller' => 'messages', 'action' => 'index')));
        }

        if (core::config('general.black_list') == TRUE AND Model_User::is_spam() === TRUE)
        {
            Alert::set(Alert::ALERT, __('Your profile has been disable for posting, due to recent spam content! If you think this is a mistake please contact us.'));

            $this->redirect(Route::url('default'));
        }

        if ($this->request->param('id') === NULL OR ! is_numeric($id_msg_thread = $this->request->param('id')))
        {
            Alert::set(Alert::ERROR, __('Message not found'));

            $this->redirect(Route::url('oc-panel', array('controller' => 'messages', 'action' => 'index')));
        }

        if (Model_Message::get_thread($id_msg_thread, $this->user) === FALSE)
        {
            Alert::set(Alert::ERROR, __('Message not found'));

            $this->redirect(Route::url('oc-panel', array('controller' => 'messages', 'action' => 'index')));
        }

        $validation = Validation::factory($this->request->post())->rule('description', 'not_empty')->rule('amount', 'not_empty');

        if (! $validation)
        {
            $errors = $validation->errors('message');

            $this->redirect(Route::url('oc-panel', array('controller' => 'messages', 'action' => 'index')));
        }


        $msg_thread = (new Model_Message())
                        ->where('id_message', '=', $id_msg_thread)
                        ->where('id_message_parent', '=', $id_msg_thread)
                        ->find();

        if ($msg_thread->id_ad === NULL OR $msg_thread->ad->id_user !== $this->user->id_user)
        {
            $this->redirect(Route::url('oc-panel', array('controller' => 'messages', 'action' => 'index')));
        }

        $amount     = core::post('amount');
        $currency   = (isset($msg_thread->ad->cf_currency) AND $msg_thread->ad->cf_currency != '') ? $msg_thread->ad->cf_currency:core::config('payment.paypal_currency');
        $id_product = Model_Order::PRODUCT_AD_CUSTOM;

        $order = Model_Order::new_order($msg_thread->ad, $msg_thread->from, $id_product, $amount, $currency, core::post('description'), NULL, 1);

        $checkout_url = PHP_EOL.PHP_EOL.'[url=' . Route::url('default', ['controller' => 'ad', 'action' => 'checkout' , 'id' => $order->id_order]) . ']' . __('Pay order') . '[/url]';

        $message = Model_Message::reply(core::post('description') . $checkout_url, $this->user, $id_msg_thread, core::post('amount'));

        if ($message === FALSE)
        {
            Alert::set(Alert::ERROR, __('Message not sent'));

            $this->redirect(Route::url('oc-panel', array('controller' => 'messages', 'action' => 'message', 'id' => $id_msg_thread)));
        }

        Alert::set(Alert::SUCCESS, __('Reply created.'));

        $this->redirect(Route::url('oc-panel', array('controller' => 'messages', 'action' => 'message', 'id' => $id_msg_thread)));
    }


    public function action_status()
    {
        if ($this->request->param('id') !== NULL AND is_numeric($id_msg_thread = $this->request->param('id')) AND is_numeric(Core::get('status')))
        {
            if (Model_Message::status_thread($id_msg_thread, $this->user, Core::get('status')))
            {
                Alert::set(Alert::SUCCESS,__('Done'));
            }
        }
        else
            Alert::set(Alert::ERROR, __('Message not found'));

        $this->redirect(Route::url('oc-panel', array('controller' => 'messages', 'action' => 'index')));
    }
}
