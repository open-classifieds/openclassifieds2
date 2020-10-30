<?php
/**
 * Auto data brands
 *
 * @author      Oliver <oliver@open-classifieds.com>
 * @package     OC
 * @copyright   (c) 2009-2013 Open Classifieds Team
 * @license     GPL v3
 */

class Model_AutoDataBrand extends ORM {

    /**
     * @var  string  Table name
     */
    protected $_table_name = 'auto_data_brands';

    /**
     * @var  string  PrimaryKey field name
     */
    protected $_primary_key = 'id_brand';

    protected $_has_many = [
        'models' => [
            'model'       => 'Autodatamodel',
            'foreign_key' => 'id_brand',
        ],
    ];
}
