<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Product reviews
 *
 * @author      Chema <chema@open-classifieds.com>
 * @author      Xavi <xavi@open-classifieds.com>
 * @package     Core
 * @copyright   (c) 2009-2014 Open Classifieds Team
 * @license     GPL v3
 */

class Model_Message extends ORM {

    /**
     * status constants
     */
    const STATUS_NOTREAD = 0; 
    const STATUS_READ    = 1; 
    const STATUS_ARCHIVED= 2;
    const STATUS_SPAM    = 5;
    const STATUS_DELETED = 7;

    
    /**
     * @var  string  Table name
     */
    protected $_table_name = 'messages';

    /**
     * @var  string  PrimaryKey field name
     */
    protected $_primary_key = 'id_message';

    /**
     * @var  array  ORM Dependency/hirerachy
     */
    protected $_belongs_to = array(
        'from' => array(
                'model'       => 'user',
                'foreign_key' => 'id_user_from',
            ),
        'to' => array(
                'model'       => 'user',
                'foreign_key' => 'id_user_to',
            ),
        'parent'   => array(
                'model'       => 'message', 
                'foreign_key' => 'id_message_parent',
            ),
        'ad'   => array(
                'model'       => 'ad', 
                'foreign_key' => 'id_ad',
            ),
    );

    /**
     * sends a message
     * @param  string $message_text      
     * @param  Model_User $user_from 
     * @param  Model_User $user_to 
     * @param  integer $id_ad        
     * @param  integer $id_message_parent 
     * @param  integer $price        negotiate price optionsl
     * @return bool / model_message              
     */
    private static function send($message_text, $user_from, $user_to, $id_ad = NULL, $id_message_parent = NULL, $price = NULL)
    {
        //cant be the same...
        if ($user_to->id_user!==$user_from->id_user)
        {
            $notify = TRUE;

            $message = new Model_Message();

            $message->message      = $message_text;
            $message->id_user_from = $user_from->id_user;
            $message->id_user_to   = $user_to->id_user;

            //message to an ad. we have verified before the ad, and pass the correct user
            if (is_numeric($id_ad))
                $message->id_ad = $id_ad;

            
            //we trust this since comes fom a function where we validate tihs user can post in that thread
            if (is_numeric($id_message_parent))
            {
                //set current message the correct thread, 
                $message->id_message_parent = $id_message_parent;

                //if user is the TO check status of first message to and if its deleted or spam do not mark it as unread, no email and no notification
                $message_parent = new Model_Message($id_message_parent);
                if ($user_to->id_user == $message_parent->id_user_to AND 
                    ($message_parent->status_to == Model_Message::STATUS_SPAM OR $message_parent->status_to == Model_Message::STATUS_DELETED) )
                {
                    $message->status_to = $message_parent->status_to;
                    $notify = FALSE;
                }
                
            }

            //has some price?
            if (is_numeric($price))
                $message->price = $price;

            try {
                $message->save();

                //didnt have a parent so we set the parent for the same
                if (!is_numeric($id_message_parent))
                {
                    $message->id_message_parent = $message->id_message;
                    $message->save();
                }
                
                if ($notify === TRUE)
                {
                    //notify user
                    $data = array('id_message' => $message->id_message_parent,
                                  'title'      => sprintf(__('New Message from %s'),$message->from->name));
                    $message->to->push_notification($message_text,$data);
                }
                
                return $message;

            } catch (Exception $e) {
                return FALSE;
            }
            
        }

        return FALSE;
    }

    /**
     * send message to a user
     * @param  string $message      
     * @param  Model_User $user_from 
     * @param  Model_User $user_to        
     * @return bool / model_message              
     */
    public static function send_user($message, $user_from, $user_to)
    {
        //check if we already have a thread for that user...then its a reply not a new message.
        $msg_thread = new Model_Message();

        $msg_thread ->where('id_message','=',DB::expr('id_message_parent'))
                    ->where('id_ad','is',NULL)
                    ->where('id_user_to','=',$user_to->id_user)
                    ->limit(1)->find();

        //actually reply not new thread....
        if ($msg_thread->loaded())
            return self::reply($message, $user_from, $msg_thread->id_message);
        else
        {
            $ret = self::send($message, $user_from, $user_to);
            //send email only if no device ID since he got the push notification already
            if ($ret !== FALSE AND !isset($user_to->device_id))
            {
                $user_to->email('messaging-user-contact', array(   '[FROM.NAME]'   => $user_from->name,
                                                                '[TO.NAME]'     => $user_to->name,
                                                                '[DESCRIPTION]' => $message,
                                                                '[URL.QL]'      => $user_to->ql('oc-panel', array( 'controller'    => 'messages',
                                                                                                                'action'        => 'message',
                                                                                                                'id'            => $ret->id_message)))
                            );
            }
            return $ret;
        }    
    }

    /**
     * send message to an advertisement
     * @param  string $message      
     * @param  Model_User $user_from
     * @param  integer $id_ad        
     * @param  integer $price        negotiate price optionsl
     * @return bool / model_message              
     */
    public static function send_ad($message, $user_from,$id_ad, $price = NULL)
    {
        //get the ad if its available, and user to who we need to contact
        $ad = new Model_Ad();
        $ad->where('id_ad','=',$id_ad)
            ->where('status','=',Model_Ad::STATUS_PUBLISHED)->find();
        //ad loaded and is not your ad....
        if ($ad->loaded() == TRUE AND $user_from->id_user!=$ad->id_user)
        {
            //check if we already have a thread for that ad and user...then its a reply not a new message.
            $msg_thread = new Model_Message();

            $msg_thread ->where('id_message','=',DB::expr('id_message_parent'))
                        ->where('id_ad','=',$id_ad)
                        ->where('id_user_from', '=',$user_from->id_user)
                        ->limit(1)->find();

            //actually reply not new thread....
            if ($msg_thread->loaded())
                return self::reply($message, $user_from, $msg_thread->id_message, $price);
            else
            {
                $ret = self::send($message, $user_from, $ad,$id_ad,NULL,$price);

                //send email only if no device ID since he got the push notification already
                if ($ret !== FALSE AND !isset($ad->user->device_id))
                {
                    $ad->user->email('messaging-ad-contact', array( '[AD.NAME]'     => $ad->title,
                                                                    '[FROM.NAME]'   => $user_from->name,
                                                                    '[TO.NAME]'     => $ad->user->name,
                                                                    '[DESCRIPTION]' => $message,
                                                                    '[URL.QL]'      => $ad->user->ql('oc-panel', array( 'controller'    => 'messages',
                                                                                                                        'action'        => 'message',
                                                                                                                        'id'            => $ret->id_message)))
                                        );
                }
                return $ret;
            }
        
        }
        return FALSE;
        
    }

    /**
     * replies to a thread
     * @param  string $message           
     * @param  Model_User $user_from      
     * @param  integer $id_message_parent 
     * @param  integer $price , optional , negotiation of price           
     * @return bool    / model_message                  
     */
    public static function reply($message, $user_from, $id_message_parent, $price = NULL)
    {
        $notify = TRUE;

        $msg_thread = new Model_Message();

        $msg_thread->where('id_message','=',$id_message_parent)
                    ->where('id_message_parent','=',$id_message_parent)
                    ->where_open()
                    ->where('id_user_from', '=',$user_from->id_user)
                    ->or_where('id_user_to','=',$user_from->id_user)
                    ->where_close()
                    ->find();

        if ($msg_thread->loaded())
        {
            //to who? if from is the same then send to TO, else to from
            $user_to = ($msg_thread->id_user_from == $user_from->id_user)? $msg_thread->id_user_to:$msg_thread->id_user_from;
            $user_to = new Model_User($user_to);

            $ret = self::send($message, $user_from, $user_to, $msg_thread->id_ad, $id_message_parent, $price);

            //do not notify!
            if ($user_to->id_user == $msg_thread->id_user_to AND 
                ($msg_thread->status_to == Model_Message::STATUS_SPAM OR $msg_thread->status_to == Model_Message::STATUS_DELETED) )
                $notify = FALSE;
            

            //send email only if no device ID since he got the push notification already
            if ($ret !== FALSE AND !isset($user_to->device_id) AND $notify === TRUE)
            {                
                //email title
                if ($msg_thread->id_ad !== NULL)
                    $email_title = $msg_thread->ad->title;
                else
                    $email_title = sprintf(__('Direct message from %s'), $user_from->name);
                
                $user_to->email('messaging-reply', array(   '[TITLE]'       => $email_title,
                                                            '[DESCRIPTION]' => core::post('message'),
                                                            '[AD.NAME]'     => isset($msg_thread->ad->title) ? $msg_thread->ad->title : NULL,
                                                            '[FROM.NAME]'   => $user_from->name,
                                                            '[TO.NAME]'     => $user_to->name,
                                                            '[URL.QL]'      => $user_to->ql('oc-panel', array(  'controller'    => 'messages',
                                                                                                                'action'        => 'message',
                                                                                                                'id'            => $id_message_parent)))
                                );
            }

            return $ret;
        }

        return FALSE;
    }

    /**
     * returns all the messages from a parent
     * @param  integer $id_message_thread 
     * @param  Model_User $user           
     * @return bool / array                    
     */
    public static function get_thread($id_message_thread,$user)
    {
        $msg_thread = new Model_Message();

        $msg_thread->where('id_message','=',$id_message_thread)
                    ->where('id_message_parent','=',$id_message_thread)
                    ->where_open()
                    ->where('id_user_from','=', $user->id_user)
                    ->or_where('id_user_to','=',$user->id_user)
                    ->where_close()
                    ->find();

        if ($msg_thread->loaded())
        {
            //get all the messages where parent = $is_msg order by created asc
            $messages = new Model_Message();
            $messages = $messages->where('id_message_parent','=',$id_message_thread)
                                ->order_by('created','asc')->find_all();
            
            foreach ($messages as $message)
                $m[$message->id_message] = $message->mark_read($user);

            return $m;
        }

        return FALSE;
    }

    /**
     * returns all the messages from a parent
     * @param  integer $id_message_thread 
     * @param  Model_User $user    
     * @param integer $status       
     * @return bool / array                    
     */
    public static function status_thread($id_message_thread,$user, $status)
    {
        $msg_thread = new Model_Message();

        $msg_thread->where('id_message','=',$id_message_thread)
                    ->where('id_message_parent','=',$id_message_thread)
                    ->where_open()
                    ->where('id_user_from','=', $user->id_user)
                    ->or_where('id_user_to','=',$user->id_user)
                    ->where_close()
                    ->find();

        if ($msg_thread->loaded() AND is_numeric($status))
        {
            //get all the messages where parent = $is_msg order by created asc
            $messages = new Model_Message();
            $messages = $messages->where('id_message_parent','=',$id_message_thread)
                                ->order_by('created','asc')->find_all();
            
            foreach ($messages as $message)
                $message->status($user,$status);

            return TRUE;
        }

        return FALSE;
    }

    /**
     * returns all the threads for a user
     * @param  Model_User $user 
     * @param  integer $status
     * @return Model_Message                    
     */
    public static function get_threads($user, $status = NULL)
    {

        

        //I get first the last message grouped by parent.
        
        /*SELECT m1.id_message FROM oc2_messages m1 
        LEFT JOIN oc2_messages m2 
        ON ( m1.id_message<m2.id_message and m1.id_message_parent=m2.id_message_parent )
        WHERE m2.id_message IS NULL AND (m1.id_user_from = 1 OR m1.id_user_to = 1)*/

        //I get first the last message grouped by parent.
        //we do this since I need to know if was written, the text and the creation date
        $query = DB::select('m1.id_message')
                ->from(array('messages','m1'))
                    ->join(array('messages','m2'),'LEFT')
                        ->on('m1.id_message','<','m2.id_message')
                        ->on('m1.id_message_parent','=','m2.id_message_parent')
                ->where('m2.id_message','IS',NULL);

        //filter by status
        if ($status!==NULL AND is_numeric($status))
        {
            switch ($status) {
                case Model_Message::STATUS_NOTREAD:
                    $query  ->where('m1.id_user_to','=',$user->id_user)
                            ->where('m1.status_to','=',Model_Message::STATUS_NOTREAD);
                    break;
                
                default:
                    $query  ->where_open()
                            ->where_open()
                            ->where('m1.id_user_to','=',$user->id_user)
                            ->where('m1.status_to','=',$status)
                            ->where_close()
                            ->or_where_open()
                            ->where('m1.id_user_from','=',$user->id_user)
                            ->where('m1.status_from','=',$status)
                            ->where_close()
                            ->where_close();

                    break;
            }
        }
        //all your messages
        else
        {
            $query  ->where_open()
                    ->where_open()
                    ->where('m1.id_user_to','=',$user->id_user)
                    ->where('m1.status_to','in',array(Model_Message::STATUS_NOTREAD,Model_Message::STATUS_READ))
                    ->where_close()
                    ->or_where_open()
                    ->where('m1.id_user_from','=',$user->id_user)
                    ->where('m1.status_from','in',array(Model_Message::STATUS_NOTREAD,Model_Message::STATUS_READ))
                    ->where_close()
                    ->where_close();
        }
            
        
        $ids = $query->execute()->as_array();
        
        //get the model ;)
        $messages = new Model_Message();

        //filter only if theres results
        if (count($ids)>0)
            $messages->where('id_message','IN',$ids);
        else
            $messages->where('id_message','=',0);
                 
        return $messages;
    }

    /**
     * returns all the unread threads for a user
     * @param  Model_User $user        
     * @return Model_Message                    
     */
    public static function get_unread_threads($user)
    {      
        return Model_Message::get_threads($user, Model_Message::STATUS_NOTREAD);
    }


    /**
     * mark message as read if user is the receiver and not read
     * @param  Model_User $user
     * @return Model_Message
     */
    public function mark_read($user)
    {
        if (!$this->loaded())
            return FALSE;

        if ($this->id_user_to == $user->id_user AND $this->status_to == Model_Message::STATUS_NOTREAD)
        {
            $this->read_date   = Date::unix2mysql();
            $this->status_to   = Model_Message::STATUS_READ;
            try {
                $this->save();
            } catch (Exception $e) {}
        }

        return $this;
    }


    /**
     * mark message as read if user is the receiver and not read
     * @param  Model_User $user
     * @param integer $status that the message gets
     * @return Model_Message
     */
    public function status($user,$status)
    {
        if (!$this->loaded())
            return FALSE;

        $save = FALSE;

        if ($this->id_user_to == $user->id_user AND $this->status_to != $status)
        {
            $this->status_to   = $status;
            $save = TRUE;
        }
        elseif ($this->id_user_from == $user->id_user AND $this->status_from != $status)
        {
            $this->status_from   = $status;
            $save = TRUE;
        }

        if ($save === TRUE)
        {
            try {
            $this->save();
            } catch (Exception $e) {}
        }
        

        return $this;
    }

}