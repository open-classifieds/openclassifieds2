<?php
/**
 * CRUD controller for the admin interface.
 *
 * @package    OC
 * @category   Controller
 * @author     Chema <chema@open-classifieds.com>
 * @copyright  (c) 2009-2013 Open Classifieds Team
 * @license    GPL v3
 * @see https://github.com/colinbm/kohana-formmanager
 */

class Auth_Crud extends Auth_Controller
{

    /**
     * @var $_index_fields ORM fields shown in index, in case not specified will show all
     */
    protected $_index_fields = array();

    /**
     * @var $_orm_model ORM model name
     */
    protected $_orm_model = NULL;

    /**
     * @var $_route_name Route to be used for actions (default: user, check /oc/config/routes.php)
     */
    protected $_route_name = 'oc-panel';

    /**
     *
     * list of actions for the crud
     * @var array
     */
    protected $_crud_actions = array('delete','create','update','export');

    /**
     *
     * list of possible actions for the crud, you can modify it to allow access or deny, by default all
     * @var array
     */
    public $crud_actions = array('delete','create','update','export');

    /**
     *
     * Contruct that checks you are loged in before nothing else happens!
     */
    function __construct(Request $request, Response $response)
    {
        parent::__construct($request, $response);

        //we check if that action can be done, if not redirected to the index
        if (!$this->allowed_crud_action())
        {
            $url = Route::get('oc-panel')->uri(array(
                                                        'controller'  => $this->request->controller(),
                                                        'action'      => 'index'));
            $this->redirect($url);
        }

        $element = ORM::Factory($this->_orm_model);

        //in case we did not specify what to show
        if (empty($this->_index_fields))
            $this->_index_fields = array_keys($element->list_columns());

        //we need the PK always to work on the grid...
        if (!in_array($element->primary_key(),$this->_index_fields))
            array_unshift($this->_index_fields, $element->primary_key());

        //url used in the breadcrumb
        $url_bread = Route::url('oc-panel',array('controller'  => $this->request->controller()));
        Breadcrumbs::add(Breadcrumb::factory()->set_title(Text::ucfirst(__($this->_orm_model)))->set_url($url_bread));
        //action
        Breadcrumbs::add(Breadcrumb::factory()->set_title(Text::ucfirst(__($this->request->action()))));
    }


    /**
     *
     * Loads a basic list info
     * @param string $view template to render
     */
    public function action_index($view = NULL)
    {
        $this->template->title = __($this->_orm_model);
        $this->template->scripts['footer'][] = 'js/oc-panel/crud/index.js';

        $elements = ORM::Factory($this->_orm_model);//->find_all();

        $pagination = Pagination::factory(array(
                    'view'           => 'oc-panel/crud/pagination',
                    'total_items'    => $elements->count_all(),
        //'items_per_page' => 10// @todo from config?,
        ))->route_params(array(
                    'controller' => $this->request->controller(),
                    'action'     => $this->request->action(),
        ));

        $pagination->title($this->template->title);

        $elements = $elements->limit($pagination->items_per_page)
        ->offset($pagination->offset)
        ->find_all();

        $pagination = $pagination->render();

        if ($view === NULL)
            $view = 'oc-panel/crud/index';

        $this->render($view, array('elements' => $elements,'pagination'=>$pagination));
    }

    /**
     * CRUD controller: DELETE
     */
    public function action_delete()
    {
        $this->auto_render = FALSE;
        $this->template = View::factory('js');
        $element = ORM::Factory($this->_orm_model, $this->request->param('id'));

        if ($element->loaded())
        {
            try
            {
                $element->delete();
                $this->template->content = 'OK';
            }
            catch (Exception $e)
            {
                d($e);
                $this->template->content = $e->getMessage();
            }
            HTTP::redirect(Route::url('oc-panel', array('controller'=>$this->request->controller())));
        }
        else
            $this->template->content = 'KO';


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
                Alert::set(Alert::SUCCESS, __('Item created').'. '.__('Please to see the changes delete the cache')
                    .'<br><a class="btn btn-primary btn-mini ajax-load" href="'.Route::url('oc-panel',array('controller'=>'tools','action'=>'cache')).'?force=1" title="'.__('Delete All').'">'
                    .__('Delete All').'</a>');

                $this->redirect(Route::get($this->_route_name)->uri(array('controller'=> Request::current()->controller())));
            }
            else
            {
                Alert::set(Alert::ERROR, __('Check form for errors'));
            }
        }

        return $this->render('oc-panel/crud/create', array('form' => $form));
    }


    /**
     * CRUD controller: UPDATE
     */
    public function action_update()
    {
        $this->template->title = __('Update').' '.__($this->_orm_model).' '.$this->request->param('id');

        $form = new FormOrm($this->_orm_model,$this->request->param('id'));

        if ($this->request->post())
        {
            if ( $success = $form->submit() )
            {
                $form->save_object();
                Alert::set(Alert::SUCCESS, __('Item updated').'. '.__('Please to see the changes delete the cache')
                    .'<br><a class="btn btn-primary btn-mini ajax-load" href="'.Route::url('oc-panel',array('controller'=>'tools','action'=>'cache')).'?force=1" title="'.__('Delete All').'">'
                    .__('Delete All').'</a>');
                $this->redirect(Route::get($this->_route_name)->uri(array('controller'=> Request::current()->controller())));
            }
            else
            {
                Alert::set(Alert::ERROR, __('Check form for errors'));
            }
        }

        return $this->render('oc-panel/crud/update', array('form' => $form));
    }

    /**
     * This method is a wrapper for templating system.
     *
     * This allows use of eiter View, Haml, Mustache or any other templating
     * system you'd like to use.
     * It defaults to the basic View though.
     *
     * @param string $view View name
     * @param mixed $data View data
     * @return View
     */
    protected function render($view = null, $data = null)
    {
        if (empty($this->_index_fields))
        {
            $element = ORM::Factory($this->_orm_model);
            $this->_index_fields = array_keys($element->list_columns());
        }

        $data = array('fields' => $this->_index_fields, 'name' => $this->_orm_model, 'route' => $this->_route_name,'controller'=>$this) + $data;

        $this->template->content = View::factory($view,$data);
    }


    /**
     *
     * tells you if the crud action it's allowed in the controller
     * @param array $action
     * @return boolean
     */
    public function allowed_crud_action($action = NULL)
    {
        $notify = FALSE;

        if ($action === NULL)
        {
            $action = $this->request->action();
            $notify = TRUE;
        }


        //its a crud request? check whitelist
        if (in_array($action, $this->_crud_actions) )
        {
            //its not in the whitelist?
            if (!in_array($action, $this->crud_actions) )
            {
                //access not allowed
                if ($notify==TRUE)
                    Alert::set(Alert::ERROR, __('Access not allowed'));

                return FALSE;
            }
        }

        return TRUE;

    }

    /**
     *
     * Loads a basic list info
     * @param string $view template to render
     */
    public function action_export($view = NULL)
    {
        //the name of the file that user will download
        $file_name = $this->_orm_model.'_export.csv';

        //name of the TMP file
        $output_file = tempnam(sys_get_temp_dir(), $file_name);
        if (Core::is_cloud())
        {
            $output_file = tempnam('/tmp', $file_name);
        }

        //instance of the crud
        $elements = ORM::Factory($this->_orm_model);

        //writting
        $output = fopen($output_file, 'w');
        //header of the CSV
        fputcsv($output, array_keys($elements->table_columns()));

        //getting all the elements
        $elements = $elements->find_all();

        //each element
        foreach($elements as $element)
            fputcsv($output, $element->as_array());

        fclose($output);

        //returns the file to the browser as attachement and deletes the TMP file
        Response::factory()->send_file($output_file,$file_name,array('delete'=>TRUE));
    }


}
