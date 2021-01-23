<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Panel_Transaction extends Auth_CrudAjax {

    /**
    * @var $_index_fields ORM fields shown in index
    */
    protected $_index_fields = array('id_transaction','id_user','id_user_from','id_order','amount');

    /**
     * @var $_orm_model ORM model name
     */
    protected $_orm_model = 'transaction';

    /**
     *
     * list of possible actions for the crud, you can modify it to allow access or deny, by default all
     * @var array
     */
    public $crud_actions = [];

    protected $_fields_caption = [
        'id_user' => ['model' => 'user', 'caption' => 'email'],
        'id_user_from' => ['model' => 'user_from', 'caption' => 'email'],
    ];

    function __construct(Request $request, Response $response)
    {
        $this->_filter_fields = [
            'id_user' => 'INPUT',
            'created' => 'DATE',
        ];

        parent::__construct($request, $response);
    }

    public function action_index($view = NULL)
    {
        parent::action_index('oc-panel/pages/transaction/index');
    }
}
