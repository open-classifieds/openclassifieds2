<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Controllers user access
 *
 * @author      Chema <chema@open-classifieds.com>, Slobodan <slobodan@open-classifieds.com>
 * @package     Core
 * @copyright   (c) 2009-2013 Open Classifieds Team
 * @license     GPL v3
 */

class Model_Subscribe extends ORM {

    /**
     * @var  string  Table name
     */
    protected $_table_name = 'subscribers';

    /**
     * @var  string  PrimaryKey field name
     */
    protected $_primary_key = 'id_subscribe';

    /**
     * @var  array  ORM Dependency/hirerachy
     */
    protected $_belongs_to = array(
        'user' => array(
                'model'       => 'user',
                'foreign_key' => 'id_user',
            ),
        'category' => array(
                'model'       => 'category',
                'foreign_key' => 'id_category',
            ),
        'location' => array(
                'model'       => 'location',
                'foreign_key' => 'id_location',
            ),
    );

    public function form_setup($form){}

    public function exclude_fields(){}


    /**
     * Function to notify subscribers
     */
    public static function notify(Model_Ad $ad)
    {
        $subscribers = DB::select(DB::expr('id_user'))
            ->distinct('id_user')
            ->from('subscribers');

        if($ad->price > 0)
        {
            $subscribers->where_open()
                        ->where(DB::EXPR((int)$ad->price),'BETWEEN',array(DB::expr('min_price'),DB::expr('max_price')))
                        ->or_where('max_price', '=', 0)
                        ->where_close();
        }

        //location is set
        if($ad->location->loaded())
            $subscribers->where('id_location', 'in', array($ad->id_location,0));

        //filter by category, 0 means all the cats, in case was not set
        $subscribers->where('id_category', 'in', array($ad->id_category,0));

        $subscribers = $subscribers->order_by('id_user', 'ASC')
            ->execute()
            ->as_array(NULL, 'id_user');

        // query for getting users, transform it to array and pass to email function
        if(core::count($subscribers) > 0)
        {
            $users = DB::select('email')->select('name')
                        ->from('users')
                        ->where('id_user', 'IN', $subscribers)
                        ->where('status','=',Model_User::STATUS_ACTIVE)
                        ->where('subscriber','=',1)
                        ->execute();

            $users = $users->as_array();

            // Send mails like in newsletter, to multiple users simultaneously
            if (core::count($users)>0)
            {

                $url_ad = Route::url('ad', array('category'=>$ad->category->seoname,'seotitle'=>$ad->seotitle));

                $replace = array('[URL.AD]'        =>$url_ad,
                                 '[AD.TITLE]'      =>$ad->title);

                Email::content($users,'',
                                    core::config('email.notify_email'),
                                    core::config('general.site_name'),
                                    'ads-subscribers',
                                    $replace);

            }
        }

    }


 protected $_table_columns =
array (
  'id_subscribe' =>
  array (
    'type' => 'int',
    'min' => '0',
    'max' => '4294967295',
    'column_name' => 'id_subscribe',
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
  'id_category' =>
  array (
    'type' => 'int',
    'min' => '0',
    'max' => '4294967295',
    'column_name' => 'id_category',
    'column_default' => '0',
    'data_type' => 'int unsigned',
    'is_nullable' => false,
    'ordinal_position' => 3,
    'display' => '10',
    'comment' => '',
    'extra' => '',
    'key' => 'MUL',
    'privileges' => 'select,insert,update,references',
  ),
  'id_location' =>
  array (
    'type' => 'int',
    'min' => '0',
    'max' => '4294967295',
    'column_name' => 'id_location',
    'column_default' => '0',
    'data_type' => 'int unsigned',
    'is_nullable' => false,
    'ordinal_position' => 4,
    'display' => '10',
    'comment' => '',
    'extra' => '',
    'key' => '',
    'privileges' => 'select,insert,update,references',
  ),
  'min_price' =>
  array (
    'type' => 'float',
    'exact' => true,
    'column_name' => 'min_price',
    'column_default' => '0.000',
    'data_type' => 'decimal',
    'is_nullable' => false,
    'ordinal_position' => 10,
    'numeric_scale' => '3',
    'numeric_precision' => '14',
    'comment' => '',
    'extra' => '',
    'key' => '',
    'privileges' => 'select,insert,update,references',
  ),
  'max_price' =>
  array (
    'type' => 'float',
    'exact' => true,
    'column_name' => 'max_price',
    'column_default' => '0.000',
    'data_type' => 'decimal',
    'is_nullable' => false,
    'ordinal_position' => 10,
    'numeric_scale' => '3',
    'numeric_precision' => '14',
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
    'ordinal_position' => 9,
    'comment' => '',
    'extra' => '',
    'key' => '',
    'privileges' => 'select,insert,update,references',
  ),
);

}
