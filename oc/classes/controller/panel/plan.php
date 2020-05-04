<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Panel_Plan extends Auth_CrudAjax {

    /**
     * @var $_orm_model ORM model name
     */
    protected $_orm_model = 'plan';

    /**
     *
     * Contruct that checks you are loged in before nothing else happens!
     */
    function __construct(Request $request, Response $response)
    {
        if (Core::extra_features() == FALSE)
        {
            Alert::set(Alert::INFO,  __('Upgrade your Yclas site to PRO to activate this feature.'));
        }
       
        parent::__construct($request,$response);
    }

    /**
     * CRUD controller: CREATE
     */
    public function action_create()
    {

        $this->template->title = __('New').' '.__($this->_orm_model);
        
        $form = new FormOrm($this->_orm_model);
            
        if ($this->request->post())
        {
            if ( $success = $form->submit() )
            {
                $form->save_object();
                
                $form->object->id_plan = $form->object->check_id($form->object->id_plan);
                $form->object->save();

                Alert::set(Alert::SUCCESS, __('Item created').'. '.__('Please to see the changes delete the cache')
                    .'<br><a class="btn btn-primary btn-mini ajax-load" href="'.Route::url('oc-panel',array('controller'=>'tools','action'=>'cache')).'?force=1" title="'.__('Delete cache').'">'
                    .__('Delete cache').'</a>');
            
                $this->redirect(Route::get($this->_route_name)->uri(array('controller'=> Request::current()->controller())));
            }
            else 
            {
                Alert::set(Alert::ERROR, __('Check form for errors'));
            }
        }
    
        return $this->render('oc-panel/crud/create', array('form' => $form));
    }
}