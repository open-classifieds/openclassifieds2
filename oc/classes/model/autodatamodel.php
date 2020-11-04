<?php
/**
 * Auto data models
 *
 * @author      Oliver <oliver@open-classifieds.com>
 * @package     OC
 * @copyright   (c) 2009-2013 Open Classifieds Team
 * @license     GPL v3
 */

class Model_AutoDataModel extends ORM {

    /**
     * @var  string  Table name
     */
    protected $_table_name = 'auto_data_models';

    /**
     * @var  string  PrimaryKey field name
     */
    protected $_primary_key = 'id_model';

    protected $_belongs_to = [
        'brand' => [
            'model' => 'Autodatabrand',
            'foreign_key' => 'id_brand',
        ],
    ];
}
