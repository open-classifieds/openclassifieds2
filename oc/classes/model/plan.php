<?php defined('SYSPATH') or die('No direct script access.');
/**
 * plan for memberships
 *
 * @author		Chema <chema@open-classifieds.com>
 * @package		OC
 * @copyright	(c) 2009-2013 Open Classifieds Team
 * @license		GPL v3
 * *
 */
class Model_Plan extends ORM {
	
    /**
     * Table name to use
     *
     * @access	protected
     * @var		string	$_table_name default [singular model name]
     */
    protected $_table_name = 'plans';

    /**
     * Column to use as primary key
     *
     * @access	protected
     * @var		string	$_primary_key default [id]
     */
    protected $_primary_key = 'id_plan';

    /**
     * Status constants
     */
    const STATUS_INACTIVE = 0;
    const STATUS_ACTIVE = 1;

    /**
     * @var  array  Available statuses array
     */
    public static $statuses = [
        self::STATUS_INACTIVE =>  'Inactive',
        self::STATUS_ACTIVE =>  'Active',
    ];

    public static function status()
    {
        return [
            self::STATUS_INACTIVE => __('Inactive'),
            self::STATUS_ACTIVE => __('Active'),
        ];
    }

    public static function get_status_label($status)
    {
        return self::status()[$status] ?? NULL;
    }

    /**
     * Rule definitions for validation
     *
     * @return array
     */
    public function rules()
    {
    	return array(
			        'price'     => array(array('price')),
                    'days'      => array(array('numeric'),array('range',array(':value',1,10000000000))),
                    'amount_ads'=> array(array('numeric'),array('range',array(':value',-1,10000000000))),
                    'seoname'   => array(   array(array($this, 'unique'), array('seoname', ':value')),
                                            array('not_empty'),
                                            array('max_length', array(':value', 145)), 
                                    ),
                    'name'      => array(   array('not_empty'),
                                            array('max_length', array(':value', 145)), 
                                    ),
			    );
    }

    public function exclude_fields()
    {
        return array('created');
    }

    /**
     * rule to verify that plan id is bigger than 100
     * @param  integer $id_plan 
     * @return integer                     
     */
    public function check_id($id_plan)
    {        
        if ($id_plan > 100)
            return $id_plan;

        return $id_plan + 100;
    }

    protected $_table_columns = array (
        'id_plan' => 
            array (
                'type' => 'int',
                'min' => '0',
                'max' => '4294967295',
                'column_name' => 'id_plan',
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
        'name' => 
            array (
                'type' => 'string',
                'column_name' => 'name',
                'column_default' => NULL,
                'data_type' => 'varchar',
                'is_nullable' => false,
                'ordinal_position' => 2,
                'character_maximum_length' => '145',
                'collation_name' => 'utf8_general_ci',
                'comment' => '',
                'extra' => '',
                'key' => '',
                'privileges' => 'select,insert,update,references',
            ),
        'seoname' => 
            array (
                'type' => 'string',
                'column_name' => 'seoname',
                'column_default' => NULL,
                'data_type' => 'varchar',
                'is_nullable' => false,
                'ordinal_position' => 3,
                'character_maximum_length' => '145',
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
                'is_nullable' => false,
                'ordinal_position' => 4,
                'collation_name' => 'utf8_general_ci',
                'comment' => '',
                'extra' => '',
                'key' => '',
                'privileges' => 'select,insert,update,references',
            ),
        'price' => 
            array (
                'type' => 'float',
                'exact' => true,
                'column_name' => 'price',
                'column_default' => '0.000',
                'data_type' => 'decimal',
                'is_nullable' => false,
                'ordinal_position' => 5,
                'numeric_precision' => '14',
                'numeric_scale' => '3',
                'comment' => '',
                'extra' => '',
                'key' => '',
                'privileges' => 'select,insert,update,references',
            ),
        'days' => 
            array (
                'type' => 'int',
                'min' => '-2147483648',
                'max' => '2147483647',
                'column_name' => 'days',
                'column_default' => '1',
                'data_type' => 'int',
                'is_nullable' => true,
                'ordinal_position' => 6,
                'display' => '10',
                'comment' => '',
                'extra' => '',
                'key' => '',
                'privileges' => 'select,insert,update,references',
            ),
        'amount_ads' => 
            array (
                'type' => 'int',
                'min' => '-2147483648',
                'max' => '2147483647',
                'column_name' => 'amount_ads',
                'column_default' => '1',
                'data_type' => 'int',
                'is_nullable' => true,
                'ordinal_position' => 7,
                'display' => '10',
                'comment' => '',
                'extra' => '',
                'key' => '',
                'privileges' => 'select,insert,update,references',
            ),
        'marketplace_fee' => 
            array (
                'type' => 'float',
                'exact' => true,
                'column_name' => 'marketplace_fee',
                'column_default' => '0.000',
                'data_type' => 'decimal',
                'is_nullable' => false,
                'ordinal_position' => 8,
                'numeric_precision' => '14',
                'numeric_scale' => '3',
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

} // END Model_Plan
