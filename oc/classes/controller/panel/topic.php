<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Panel_Topic extends Auth_CrudAjax {

    /**
     * @var $_index_fields ORM fields shown in index
     */
    protected $_index_fields = array('title','id_forum','created','status');

    /**
     * @var $_orm_model ORM model name
     */
    protected $_orm_model = 'topic';

    protected $_search_fields = array('title');

    protected $_filter_fields = array(   
                                        'status' => array(0=>'Inactive',1=>'Active'),
                                        'id_forum' => array('type'=>'SELECT','table'=>'forums','key'=>'id_forum','value'=>'name'),
                                        );

    protected $_fields_caption = array( 'id_forum'     => array('model'=>'forum','caption'=>'name'),
                                     );

    function __construct(Request $request, Response $response)
    {
        parent::__construct($request, $response);
        //forcing a filter
        if (Core::request('filter__id_forum')===NULL)
            $this->_filter_post['id_forum'] = 'NOT NULL';
    }

    /**
     * Update new forum
     */
    public function action_update()
    {
        Breadcrumbs::add(Breadcrumb::factory()->set_title(__('Edit Topic')));

        $form = new FormOrm($this->_orm_model,$this->request->param('id'));
        $topic = $form->object;
        
        if ($_POST)
        {

            $topic->title = core::post('title');
            $topic->id_forum = core::post('id_forum');
            $topic->description = core::post('description');
            if(core::post('seotitle') != $topic->seotitle)
                $topic->seotitle = $topic->gen_seotitle(core::post('seotitle'));

            if(core::post('status') == 'on')
                $topic->status = 1;
            else
                $topic->status = 0;


            try {
                $topic->save();
                Alert::set(Alert::SUCCESS, __('Topic is updated.'));
            } catch (Exception $e) {
                Alert::set(Alert::ERROR, $e->getMessage());
            }

            HTTP::redirect(Route::url('oc-panel',array('controller'  => 'topic','action'=>'index')));
        }

        $this->render('oc-panel/crud/update', array('form'=>$form));
    }
}
