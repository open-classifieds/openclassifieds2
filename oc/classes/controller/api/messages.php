<?php defined('SYSPATH') or die('No direct script access.');


class Controller_Api_Messages extends Api_User {


    /**
     * gets all the threads of the user
     */
    public function action_index()
    {   
        //get single thread
        if (is_numeric($this->request->param('id')))
        {
            $this->action_get();
        }
        else
        {
            $messages = Model_Message::get_threads($this->user, (isset($this->_filter_params['status'])?$this->_filter_params['status']:NULL));

            //filter results by param, verify field exists and has a value
            $messages->api_filter($this->_filter_params);

            //how many? used in header X-Total-Count
            $count = $messages->count_all();

            //by default sort by status not read and when was created TODO
            if(empty($this->_sort))
            {
                $this->_sort['created'] = 'desc';
            }

            //after counting sort values
            $messages->api_sort($this->_sort);

            //pagination with headers
                $pagination = $messages->api_pagination($count,$this->_params['items_per_page']);

            $messages = $messages->cached()->find_all();

            $m = array();     
            //convert it to array                   
            foreach ($messages as $message)
                $m[] = $message->as_array();

            $this->rest_output(array('messages' => $m),200,$count,($pagination!==FALSE)?$pagination:NULL);
        }
    }

    /**
     * get all unread messages forthe loged in user
     * @return [type] [description]
     */
    public function action_unread()
    {
        $messages = Model_Message::get_unread_threads($this->user);

        //filter results by param, verify field exists and has a value
        $messages->api_filter($this->_filter_params);

        //how many? used in header X-Total-Count
        $count = $messages->count_all();

        //by default sort by status not read and when was created
        if(empty($this->_sort))
            $this->_sort['created'] = 'desc';
        
        //after counting sort values
        $messages->api_sort($this->_sort);

        //pagination with headers
            $pagination = $messages->api_pagination($count,$this->_params['items_per_page']);

        $messages = $messages->cached()->find_all();

        $m = array();     
        //convert it to array                   
        foreach ($messages as $message)
            $m[] = $message->as_array();

        $this->rest_output(array('messages' => $m),200,$count,($pagination!==FALSE)?$pagination:NULL);
    }

    /**
     * get all messages from a thread
     * @return [type] [description]
     */
    public function action_get()
    {
        try
        {
            if (is_numeric($id_msg_thread = $this->request->param('id')))
            {
                $messages = Model_Message::get_thread($id_msg_thread,$this->user);

                if ($messages!==FALSE)
                {
                    $m = array();     
                    //convert it to array                   
                    foreach ($messages as $message)
                        $m[] = $message->as_array();

                    $this->rest_output(array('messages' => $m));
                }
                else
                    $this->_error(__('Message not found'),404);
            }
            else
                $this->_error(__('Message not found'),404);
            
        }
        catch (Kohana_HTTP_Exception $khe)
        {
            $this->_error($khe);
        }
       
    }

    /**
     * Handle POST requests for messages
     */
    public function action_create()
    {
        try
        {
            //get message
            if (isset($this->_post_params['message']))
                $message = $this->_post_params['message'];
            else
                $this->_error(__('Message not sent'));

            //price?
            $price = (isset($this->_post_params['price']) AND is_numeric($this->_post_params['price']))?$this->_post_params['price']:NULL;

            //message to the user
            if (isset($this->_post_params['id_user']) AND is_numeric($id_user_to = $this->_post_params['id_user']))
                $ret = Model_Message::send_user($message, $this->user, new Model_User($id_user_to));
            //message advertisement
            elseif (isset($this->_post_params['id_ad']) AND is_numeric($id_ad = $this->_post_params['id_ad']))
                $ret = Model_Message::send_ad($message, $this->user, $id_ad,$price);
            //reply thread
            elseif (isset($this->_post_params['id_message_parent']) AND is_numeric($id_message_parent = $this->_post_params['id_message_parent']))
                $ret = Model_Message::reply($message, $this->user, $id_message_parent,$price);
            
            //good response!
            if ($ret !== FALSE)
                $this->rest_output(array('message' => $ret->as_array()));
            else
                $this->_error(__('Message not sent'));

            
        }
        catch (Kohana_HTTP_Exception $khe)
        {
            $this->_error($khe);
        }
       
    }



} // END