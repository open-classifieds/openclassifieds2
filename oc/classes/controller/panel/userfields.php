<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Panel_UserFields extends Auth_Controller {


    public function __construct($request, $response)
    {
        parent::__construct($request, $response);

        Breadcrumbs::add(Breadcrumb::factory()->set_title(__('Custom Fields for users'))->set_url(Route::url('oc-panel',array('controller'  => 'userfields'))));

    }

	public function action_index()
	{

        Breadcrumbs::add(Breadcrumb::factory()->set_title(__('Custom Fields for Users')));
		$this->template->title = __('Custom Fields for Users');


        $this->template->styles              = array('css/sortable.css' => 'screen');
        $this->template->scripts['footer'][] = 'js/jquery-sortable-min.js';
        $this->template->scripts['footer'][] = 'js/oc-panel/fields.js';

        //retrieve fields
        $fields = Model_UserField::get_all(FALSE);
        if ( core::count($fields) > 65 ) //upper bound for custom fields
            Alert::set(Alert::WARNING,__('You have reached the maximum number of custom fields allowed.'));

		$this->template->content = View::factory('oc-panel/pages/userfields/index',array('fields' => $fields));
	}


    public function action_new()
    {
        if (Core::extra_features() == FALSE)
        {
            Alert::set(Alert::WARNING, __('This feature is only available in the PRO version!') . ' ' . __('Upgrade your Yclas site to activate this feature.'));
            $this->redirect(Route::url('oc-panel', ['controller' => 'fields']));
        }

        Breadcrumbs::add(Breadcrumb::factory()->set_title(__('New field')));
        $this->template->title = __('New Custom Field for Users');
        $errors             = '';

        if ($_POST)
        {
            if ( core::count(Model_UserField::get_all(FALSE)) > 65 ) //upper bound for custom fields
            {
                Alert::set(Alert::ERROR,__('You have reached the maximum number of custom fields allowed.'));
                HTTP::redirect(Route::url('oc-panel',array('controller'  => 'userfields','action'=>'index')));
            }


            $validation =   Validation::factory($this->request->post())
                                                    ->rule('name', 'alpha_dash')
                                                    ->rule('name', 'not_empty')
                                                    ->rule('name', 'min_length', array(':value', 3))
                                                    ->rule('name', 'max_length', array(':value', 60));
            if ($validation->check())
            {

                $name   = URL::title(Core::post('name'));

                if (strlen($name)>=60)
                    $name = Text::limit_chars($name,60,'');

                $field = new Model_UserField();

                try {

                    $options = array(
                                    'label'             => Core::post('label'),
                                    'tooltip'           => Core::post('tooltip'),
                                    'required'          => (Core::post('required')=='on')?TRUE:FALSE,
                                    'searchable'        => (Core::post('searchable')=='on')?TRUE:FALSE,
                                    'show_profile'      => (Core::post('show_profile')=='on')?TRUE:FALSE,
                                    'show_register'     => (Core::post('show_register')=='on')?TRUE:FALSE,
                                    'admin_privilege'   => (Core::post('admin_privilege')=='on')?TRUE:FALSE,
                                    );

                    if ($field->create($name,Core::post('type'),Core::post('values'),$options))
                    {
                        Core::delete_cache();
                        Alert::set(Alert::SUCCESS,sprintf(__('Field %s created'),$name));
                    }
                    else
                        Alert::set(Alert::ERROR,sprintf(__('Field %s already exists'),$name));


                } catch (Exception $e) {
                    throw HTTP_Exception::factory(500,$e->getMessage());
                }

                HTTP::redirect(Route::url('oc-panel',array('controller'  => 'userfields','action'=>'index')));
            }
            else
                $errors = $validation->errors('field');
        }

        $this->template->content = View::factory('oc-panel/pages/userfields/new',array('errors' => $errors));
    }

    public function action_update()
    {
        if (Core::extra_features() == FALSE)
        {
            Alert::set(Alert::WARNING, __('This feature is only available in the PRO version!') . ' ' . __('Upgrade your Yclas site to activate this feature.'));
            $this->redirect(Route::url('oc-panel', ['controller' => 'fields']));
        }

        $name   = $this->request->param('id');
        $field  = new Model_UserField();
        $field_data  = $field->get($name);

        Breadcrumbs::add(Breadcrumb::factory()->set_title(__('Edit').' '.$name));
        $this->template->title = __('Edit Custom Field for Advertisement');

        if ($_POST)
        {

            try {

                $options = array(
                                'label'             => Core::post('label'),
                                'tooltip'           => Core::post('tooltip'),
                                'required'          => (Core::post('required')=='on')?TRUE:FALSE,
                                'searchable'        => (Core::post('searchable')=='on')?TRUE:FALSE,
                                'show_profile'      => (Core::post('show_profile')=='on')?TRUE:FALSE,
                                'show_register'     => (Core::post('show_register')=='on')?TRUE:FALSE,
                                'admin_privilege'   => (Core::post('admin_privilege')=='on')?TRUE:FALSE,
                                );

                if ($field->update($name,Core::post('values'),$options))
                {
                    Core::delete_cache();
                    Alert::set(Alert::SUCCESS,sprintf(__('Field %s edited'),$name));
                }
                else
                    Alert::set(Alert::ERROR,sprintf(__('Field %s cannot be edited'),$name));

            } catch (Exception $e) {
                throw HTTP_Exception::factory(500,$e->getMessage());
            }

            HTTP::redirect(Route::url('oc-panel',array('controller'  => 'userfields','action'=>'index')));
        }

        $this->template->content = View::factory('oc-panel/pages/userfields/update',array('field_data'=>$field_data,'name'=>$name));
    }


    public function action_delete()
    {
        //get name of the param, get the name of the custom fields, deletes from config array and alters table
        $this->auto_render = FALSE;
        $name   = $this->request->param('id');
        $field  = new Model_UserField();

        try {
            $this->template->content = ($field->delete($name))?sprintf(__('Field %s deleted'),$name):sprintf(__('Field %s does not exists'),$name);
        } catch (Exception $e) {
            //throw 500
            throw HTTP_Exception::factory(500,$e->getMessage());
        }

    }

    /**
     * used for the ajax request to reorder the fields
     * @return string
     */
    public function action_saveorder()
    {
        $field  = new Model_UserField();

        $this->auto_render = FALSE;
        $this->template = View::factory('js');


        $order = Core::get('order');

        array_walk($order, function(&$item, $key){
                $item = str_replace('li_', '', $item);
        });

        if ($field->change_order($order))

            $this->template->content = __('Saved');
        else
            $this->template->content = __('Error');
    }



}
