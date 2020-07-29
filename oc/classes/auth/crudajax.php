<?php defined('SYSPATH') or die('No direct script access.');
/**
 * CRUD Ajax controller for the admin interface.
 *
 * @package    OC
 * @category   Controller
 * @author     Chema <chema@open-classifieds.com>
 * @copyright  (c) 2009-2015 Open Classifieds Team
 * @license    GPL v3
 * @see https://github.com/colinbm/kohana-formmanager
 * @see http://www.jquery-bootgrid.com/
 */

class Auth_CrudAjax extends Auth_Crud
{
    /**
     * @var $_search_fields ORM fields we allow to search on bootgrid with typeahead, in case not specified will search in all the fields, so you can limit the search using this array
     * Example: protected $_search_fields = array('name','period','callback');
     */
    protected $_search_fields = array();

    /**
     * @var $_filter_fields ORM fields we allow to filter, exact match, will render a search form on the indexcrud with this fields
     * Currently supported filter types while rendering:
     * DATE, will create 2 inputs from/to dates with datepicker and do a between
     * RANGE, will create 2 inputs to do a between, perfect for pricing
     * INPUT, normal search, needs to be exact match
     * array(1,2,3,4), will render the select with this fields
     * array('key1'=>'val1','key2'=>'val2','key3'=>'val3',), will render the selct with key=>values
     * array('type'=>'DISTINCT','table'=>'roles','field'=>'name'), will do a DISTINCT from the table and the field
     * array('type'=>'SELECT','table'=>'roles','key'=>'id_role','value'=>'name'), will get from table roles an array using as key id_role and vlaue the name.
     * array('type'=>'SEARCH','table'=>'users','key'=>'id_user','field'=>'name'), will do a DISTINCT from the table and the field
     * Example: TODO
     */
    protected $_filter_fields = array();


    /**
     * @var $_buttons_actions by default the TD have, update, delete.
     * With this array you can add extra buttons in a TD.
     * Needs to be done in the construct if you want to use dynamic urls if not can be done as static on the top of the class
     * Used in view bootgrid.php
     * Example:
     * function __construct(Request $request, Response $response)
     * {
     *    parent::__construct($request, $response);
     *    $this->_buttons_actions = array( array( 'url'   => Route::url($this->_route_name, array('controller'=> Request::current()->controller(), 'action'=>'export')) ,
     *                                            'title' => 'test aaa',
     *                                            'class' => 'btn btn-xs btn-primary_key',
     *                                            'icon'  => 'glyphicon glyphicon-download'
     *                                            ),
     *                            );
     * }
     */
    protected $_buttons_actions = array();

    /**
     * will use this array to change the caption of a field using a belongs_to or has one.
     * Example: protected $_fields_caption = array( 'id_role'   => array('model'=>'role','caption'=>'name') );
     * when rendering id_role instead of $object-id_role will do $object->role->name
     * @var array
     */
    protected $_fields_caption = array();

     /**
     * @var _extra_info_view an extra view we will render in the indexajax view so you can add extra info or links.
     * Example: protected $_extra_info_view = 'oc-panel/pages/cron/index';
     */
    protected $_extra_info_view = NULL;

    /**
     * @var $_filter_post is the array of data we receive on POST/GET
     */
    protected $_filter_post = array();

    /**
     *
     * Contruct that checks you are loged in before nothing else happens!
     */
    function __construct(Request $request, Response $response)
    {
        parent::__construct($request, $response);

        //filter fields filling the data for arrays
        foreach($this->_filter_fields as $field=>$value)
        {
            if (is_array($value) AND isset($value['table']) AND isset($value['type']))
            {
                if ($value['type']=='DISTINCT')
                {
                    $result = array();
                    $query = DB::select($value['field'])->distinct(TRUE)->from($value['table'])->execute();
                    foreach ($query->as_array() as $k => $v)
                        $result[current($v)] = current($v);
                }
                elseif ($value['type']=='SELECT')
                {
                    $key   = $value['key'];
                    $val   = $value['value'];
                    $result = array();
                    $query = DB::select($key)->select($val)->from($value['table'])->execute();

                    foreach ($query->as_array() as $k => $v)
                    {
                        $k = $v[$key];
                        $result[$k] = $v[$val];
                    }
                }
                $this->_filter_fields[$field] = $result;
            }
        }

        //get the filters
        foreach (array_merge($_GET,$_POST) as $key => $value)
        {
            //with values
            if (isset($value) AND $value!='')
            {
                //date between
                if( strpos($key,'filter__from__')!==FALSE  )//OR strpos($key,'filter__to__date')!==FALSE
                {
                    $var  = str_replace('filter__from__', '',$key);
                    $from = Core::request('filter__from__'.$var);
                    $to   = Core::request('filter__to__'.$var);

                    //add it to the filter
                    if ($from!=NULL AND $to!=NULL)
                        $this->_filter_post[$var] = array($from,$to);

                }
                //any other value that is not a date
                elseif ( strpos($key,'filter__')!==FALSE
                    AND strpos($key,'filter__to__')===FALSE )
                {
                    $this->_filter_post[str_replace('filter__', '', $key)] = $value;
                }
            }
        }
    }

    /**
     *
     * Loads a basic list info
     * @param string $view template to render
     */
    public function action_index($view = NULL)//, $extra_info_view = NULL)
    {
        $this->template->title = __($this->_orm_model);

        $this->template->styles = [
            'css/jquery.bootgrid.min.css' => 'screen',
            '//cdn.jsdelivr.net/npm/pikaday/css/pikaday.css' => 'screen'
        ];

        $this->template->scripts['footer'] = [
            Route::url($this->_route_name, ['controller' => Request::current()->controller(), 'action' => 'bootgrid']),
            '//cdnjs.cloudflare.com/ajax/libs/moment.js/2.5.1/moment.min.js',
            '//cdn.jsdelivr.net/npm/pikaday/pikaday.js',
        ];

        $elements = ORM::Factory($this->_orm_model);//->find_all();

        if ($view === NULL)
            $view = 'oc-panel/crud/indexajax';

        if ($this->_extra_info_view!==NULL)
        {
            $this->_extra_info_view = View::factory($this->_extra_info_view)->render();
        }

        $data = array('elements' => $elements,
                      'filters'  => $this->_filter_fields,
                      'extra_info_view' => $this->_extra_info_view,
                      'captions' => $this->_fields_caption);
        $this->render($view, $data);
    }

    /**
     * gets the info used from index
     * @return string json
     */
    public function action_ajax()
    {
        $elements = ORM::Factory($this->_orm_model);

        //search searchPhrase: from an array specified in the controller. If none search does not appear. do in bootdrig action
        if (Core::post('searchPhrase')!==NULL AND core::count($this->_search_fields) > 0)
        {
            foreach ($this->_search_fields as $field)
                $elements->or_where($field,'LIKE','%'.Core::post('searchPhrase').'%');
        }

        //extra filters
        foreach ($this->_filter_post as $field => $value)
        {
            //forced null value
            if ($value === NULL)
            {
                $elements->where($field,'IS',NULL);
            }
            elseif ($value === 'NOT NULL')
            {
                $elements->where($field,'IS NOT',NULL);
            }
            //range search or date
            elseif (is_array($value))
            {
                $elements->where($field,'BETWEEN',$value);
            }
            //search by caption we try to resolve
            elseif (isset($this->_fields_caption[$field]))
            {
                if(is_numeric($value))
                    $elements->where($field,'=',$value);
                else
                {
                    $data   = $this->_fields_caption[$field];
                    $search = ORM::Factory($data['model']);
                    $search = $search->where($data['caption'],'LIKE','%'.$value.'%')->find_all();
                    if (core::count($search)>0)
                    {
                        $result = array();
                        foreach ($search as $res)
                            $result[] = $res->$field;
                        $elements->where($field,'IN',$result);
                    }
                    else//not found normal search then ;)
                        $elements->where($field,'LIKE','%'.$value.'%');
                }
            }
            elseif(is_numeric($value))
                $elements->where($field,'=',$value);
            else
                $elements->where($field,'LIKE','%'.$value.'%');
        }
        //dr($elements);

        //sort by sort[group_name]:asc
        if (Core::post('sort')!==NULL)
        {
            $sort   = Core::post('sort');
            $field  = key($sort);
            $dir    = current($sort);
            if(in_array($field,(array_keys($elements->table_columns()))))
                $elements->order_by($field,$dir);
        }
        //by default by id
        else
            $elements->order_by($elements->primary_key(),'DESC');

        $pagination = Pagination::factory(array(
                    'view'           => 'oc-panel/crud/pagination',
                    'current_page'   => array('page'=>Core::post('current',1)),
                    'total_items'    => $elements->count_all(),
                    'items_per_page' => Core::post('rowCount',10)
        ))->route_params(array(
                    'controller' => $this->request->controller(),
                    'action'     => $this->request->action(),
        ));

        $elements = $elements->limit($pagination->items_per_page)
        ->offset($pagination->offset)
        ->find_all();

        $rows = array();
        foreach ($elements as $element)
        {
            foreach($this->_index_fields as $field)
            {

                //captions for fields....
                if (isset($this->_fields_caption[$field]))
                {
                    //callable function
                    if (exec::is_callable($function = $this->_fields_caption[$field]))
                    {
                        $result[$field] = call_user_func($function,$element->$field);
                    }
                    else
                    {
                        $model   = $this->_fields_caption[$field]['model'];
                        $caption = $this->_fields_caption[$field]['caption'];
                        $result[$field] = $element->$model->$caption;
                    }
                }
                else
                    $result[$field] = Text::limit_chars(strip_tags($element->$field));
            }

            $rows[]  = $result;
            $result  = array();
        }

        //the json returned
        $result = array( 'current'   => $pagination->current_page,
                         'rowCount'  => $pagination->items_per_page,
                         'rows'      => $rows,
                         'total'     => $pagination->total_items
                        );

        $this->auto_render = FALSE;
        //$this->response->headers('Content-type','application/javascript');//why the heck doesnt work with this???
        $this->template = View::factory('js');
        $this->template->content = json_encode($result);
    }

    /**
     * returns the JS with the config of bootgrid specific for this model
     * @return string JS/jquery
     */
    public function action_bootgrid()
    {
        $element = ORM::Factory($this->_orm_model);

        $this->auto_render = FALSE;
        $this->response->headers('Content-type','application/javascript');
        $this->template = View::factory('js');
        $data = array(  'element'   => $element,
                        'route'     => $this->_route_name,
                        'search_fields' => $this->_search_fields,
                        'controller'    => $this,
                        'buttons'       => $this->_buttons_actions);
        $this->template->content = View::factory('oc-panel/crud/bootgrid',$data)->render();
    }

}
