<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Blog/Forum Posts
 *
 * @author      Chema <chema@open-classifieds.com>
 * @package     Core
 * @copyright   (c) 2009-2013 Open Classifieds Team
 * @license     GPL v3
 */

class Model_Post extends ORM {

    /**
     * @var  string  Table name
     */
    protected $_table_name = 'posts';

    /**
     * @var  string  PrimaryKey field name
     */
    protected $_primary_key = 'id_post';

    /**
     * status constants
     */
    const STATUS_NOACTIVE = 0;
    const STATUS_ACTIVE   = 1;

    /**
     * @var  array  ORM Dependency/hirerachy
     */
    protected $_has_many = array(
        'replies' => array(
            'model'       => 'post',
            'foreign_key' => 'id_post_parent',
        ),
    );

    protected $_belongs_to = array(
        'user'     => array('model'       => 'user', 'foreign_key' => 'id_user'),
        'forum'    => array('model'       => 'forum','foreign_key' => 'id_forum'),
        'parent'   => array('model'       => 'Post', 'foreign_key' => 'id_post_parent'),
    );

    public function filters()
    {
        return array(
                'seotitle' => array(
                                array(array($this, 'gen_seotitle'))
                              ),

        );
    }


    /**
     *
     * formmanager definitions
     *
     */
    public function form_setup($form)
    {
        $form->fields['id_user']['value']       =  auth::instance()->get_user()->id_user;
        $form->fields['id_user']['display_as']  = 'hidden';

        $form->fields['seotitle']['display_as']  = 'hidden';

    }


    public function exclude_fields()
    {
        return array('created','ip_address','id_forum','id_post_parent');
    }


    /**
     * return the title formatted for the URL
     *
     * @param  string $title
     *
     */
    public function gen_seotitle($seotitle)
    {
        //in case seotitle is really small or null
        if (strlen($seotitle)<3)
            $seotitle = $this->title;

        $seotitle = URL::title($seotitle);

        if ($seotitle != $this->seotitle)
        {
            $post = new self;
            //find a user same seotitle
            $s = $post->where('seotitle', '=', $seotitle)->limit(1)->find();

            //found, increment the last digit of the seotitle
            if ($s->loaded())
            {
                $cont = 2;
                $loop = TRUE;
                while($loop)
                {
                    $attempt = $seotitle.'-'.$cont;
                    $post = new self;
                    unset($s);
                    $s = $post->where('seotitle', '=', $attempt)->limit(1)->find();
                    if(!$s->loaded())
                    {
                        $loop = FALSE;
                        $seotitle = $attempt;
                    }
                    else
                    {
                        $cont++;
                    }
                }
            }
        }


        return $seotitle;
    }



    /**
     * prints the disqus script from the view for blogs!
     * @return string HTML or false in case not loaded
     */
    public function disqus()
    {
        if($this->loaded())
        {
            if ($this->status == self::STATUS_ACTIVE AND strlen(core::config('general.blog_disqus'))>0 )
            {
                return View::factory('pages/disqus',
                                array('disqus'=>core::config('general.blog_disqus')))
                        ->render();
            }
        }

        return FALSE;
    }

    /**
     * return replies of the topic for notification purposes
     * @return array list of emails to send a notification of new response
     */
    public function get_repliers()
    {
        $user = Auth::instance()->get_user();

        $repliers   = array();
        $id_users   = array();

        //adding the owner of the topic to the replies, in case not his answer
        if ($user->id_user != $this->id_user)
        {
            $repliers[] = array('name' => $this->user->name, 'email' => $this->user->email);
            $id_users[] = $this->id_user;
        }

        //get all repliers
        $replies = $this->replies->find_all();
        foreach($replies as $reply)
        {
            //not duplicated and not the user that replied
            if( ! in_array($reply->id_user, $id_users) AND $reply->id_user!=$user->id_user)
            {
                $repliers[] = array('name' => $reply->user->name, 'email' => $reply->user->email);
                $id_users[] = $reply->id_user;
            }
        }

        return $repliers;
    }

    /**
     * send notification of new answer to the repliers of a topic
     */
    public function notify_repliers()
    {
        $data = array(
            '[FORUM.LINK]' => Route::url('forum-topic',array('forum'=>$this->forum->seoname,'seotitle'=>$this->seotitle))
            );

        Email::content($this->get_repliers(), '', NULL, NULL, 'new-forum-answer', $data);
    }

    public function fbcomments()
    {
        if(! $this->loaded())
        {
            return FALSE;
        }

        if($this->status != 1)
        {
            return FALSE;
        }

        if(strlen(core::config('advertisement.fbcomments')) == 0)
        {
            return FALSE;
        }

        return View::factory('pages/ad/fbcomments', [
            'fbcomments' => core::config('advertisement.fbcomments'),
            'datahref' => Route::url('blog', ['seotitle' => $this->seotitle]),
        ])->render();
    }

    public function get_first_image()
    {
        if (preg_match('/< *img[^>]*src *= *["\']?([^"\']*)/i', $this->description, $images))
        {
            return $images[1];
        }

        return FALSE;
    }

    public function comments()
    {
        if(! $this->loaded())
        {
            return FALSE;
        }

        return $this->fbcomments().$this->disqus();
    }

    protected $_table_columns =
array (
  'id_post' =>
  array (
    'type' => 'int',
    'min' => '0',
    'max' => '4294967295',
    'column_name' => 'id_post',
    'column_default' => NULL,
    'data_type' => 'int unsigned',
    'is_nullable' => false,
    'ordinal_position' => 1,
    'display' => '10',
    'comment' => '',
    'extra' => 'auto_increment',
    'key' => 'PRI',
    'privileges' => 'select,insert,update,references',
  ),
  'id_user' =>
  array (
    'type' => 'int',
    'min' => '0',
    'max' => '4294967295',
    'column_name' => 'id_user',
    'column_default' => NULL,
    'data_type' => 'int unsigned',
    'is_nullable' => false,
    'ordinal_position' => 2,
    'display' => '10',
    'comment' => '',
    'extra' => '',
    'key' => 'MUL',
    'privileges' => 'select,insert,update,references',
  ),
  'id_post_parent' =>
  array (
    'type' => 'int',
    'min' => '0',
    'max' => '4294967295',
    'column_name' => 'id_post_parent',
    'column_default' => NULL,
    'data_type' => 'int unsigned',
    'is_nullable' => true,
    'ordinal_position' => 3,
    'display' => '10',
    'comment' => '',
    'extra' => '',
    'key' => 'MUL',
    'privileges' => 'select,insert,update,references',
  ),
  'id_forum' =>
  array (
    'type' => 'int',
    'min' => '0',
    'max' => '4294967295',
    'column_name' => 'id_forum',
    'column_default' => NULL,
    'data_type' => 'int unsigned',
    'is_nullable' => true,
    'ordinal_position' => 4,
    'display' => '10',
    'comment' => '',
    'extra' => '',
    'key' => 'MUL',
    'privileges' => 'select,insert,update,references',
  ),
  'title' =>
  array (
    'type' => 'string',
    'column_name' => 'title',
    'column_default' => NULL,
    'data_type' => 'varchar',
    'is_nullable' => false,
    'ordinal_position' => 5,
    'character_maximum_length' => '245',
    'collation_name' => 'utf8_general_ci',
    'comment' => '',
    'extra' => '',
    'key' => '',
    'privileges' => 'select,insert,update,references',
  ),
  'seotitle' =>
  array (
    'type' => 'string',
    'column_name' => 'seotitle',
    'column_default' => NULL,
    'data_type' => 'varchar',
    'is_nullable' => false,
    'ordinal_position' => 6,
    'character_maximum_length' => '245',
    'collation_name' => 'utf8_general_ci',
    'comment' => '',
    'extra' => '',
    'key' => 'UNI',
    'privileges' => 'select,insert,update,references',
  ),
  'description' =>
  array (
    'type' => 'string',
    'character_maximum_length' => '4294967295',
    'column_name' => 'description',
    'column_default' => NULL,
    'data_type' => 'longtext',
    'is_nullable' => true,
    'ordinal_position' => 7,
    'collation_name' => 'utf8_general_ci',
    'comment' => '',
    'extra' => '',
    'key' => '',
    'privileges' => 'select,insert,update,references',
  ),
  'created' =>
  array (
    'type' => 'string',
    'column_name' => 'created',
    'column_default' => 'CURRENT_TIMESTAMP',
    'data_type' => 'timestamp',
    'is_nullable' => false,
    'ordinal_position' => 8,
    'comment' => '',
    'extra' => '',
    'key' => '',
    'privileges' => 'select,insert,update,references',
  ),
  'ip_address' =>
  array (
    'type' => 'int',
    'min' => '-9223372036854775808',
    'max' => '9223372036854775807',
    'column_name' => 'ip_address',
    'column_default' => NULL,
    'data_type' => 'bigint',
    'is_nullable' => true,
    'ordinal_position' => 9,
    'display' => '20',
    'comment' => '',
    'extra' => '',
    'key' => '',
    'privileges' => 'select,insert,update,references',
  ),
  'status' =>
  array (
    'type' => 'int',
    'min' => '-128',
    'max' => '127',
    'column_name' => 'status',
    'column_default' => '0',
    'data_type' => 'tinyint',
    'is_nullable' => false,
    'ordinal_position' => 10,
    'display' => '1',
    'comment' => '',
    'extra' => '',
    'key' => '',
    'privileges' => 'select,insert,update,references',
  ),
);
}
