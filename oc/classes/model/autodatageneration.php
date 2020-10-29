<?php
/**
 * Auto data generations
 *
 * @author      Oliver <oliver@open-classifieds.com>
 * @package     OC
 * @copyright   (c) 2009-2013 Open Classifieds Team
 * @license     GPL v3
 */

class Model_AutoDataGeneration extends ORM {

    /**
     * @var  string  Table name
     */
    protected $_table_name = 'auto_data_generations';

    /**
     * @var  string  PrimaryKey field name
     */
    protected $_primary_key = 'id_generation';

    protected $_belongs_to = [
        'model' => [
            'model' => 'Autodatamodel',
            'foreign_key' => 'id_model',
        ],
    ];

    protected $_has_many = [
        'generations' => [
            'model'       => 'Autodatageneration',
            'foreign_key' => 'id_generation',
        ],
    ];
}
