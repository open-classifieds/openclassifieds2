<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Panel_Subscription extends Auth_CrudAjax {

    /**
    * @var $_index_fields ORM fields shown in index
    */
    protected $_index_fields = array('id_subscription','id_user','id_plan', 'amount_ads','amount_ads_left','expire_date','created','status');
    
    /**
     * @var $_orm_model ORM model name
     */
    protected $_orm_model = 'subscription';

    /**
     *
     * list of possible actions for the crud, you can modify it to allow access or deny, by default all
     * @var array
     */
    public $crud_actions = array('update');

    protected $_fields_caption = array( 'id_user'       => array('model'=>'user','caption'=>'email'),
                                        'id_plan'       => array('model'=>'plan','caption'=>'name'),
                                         );

    function __construct(Request $request, Response $response)
    {
        if (Core::extra_features() == FALSE)
        {
            Alert::set(Alert::INFO,  __('Upgrade your Yclas site to activate this feature.'));
        }

        $this->_filter_fields = array(  'id_user'    => 'INPUT', 
                                        'expire_date'=> 'DATE', 
                                        'created'    => 'DATE', 
                                        'id_plan'    => array('type'=>'SELECT','table'=>'plans','key'=>'id_plan','value'=>'seoname'),
                                        'status'     => array(0=>'Inactive',1=>'Active'),
                                        );
        
        parent::__construct($request, $response);


    } 

}