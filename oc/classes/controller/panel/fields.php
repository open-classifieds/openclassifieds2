<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Panel_Fields extends Auth_Controller {


    public function __construct($request, $response)
    {
        parent::__construct($request, $response);

        Breadcrumbs::add(Breadcrumb::factory()->set_title(__('Custom Fields'))->set_url(Route::url('oc-panel',array('controller'  => 'fields'))));

    }

	public function action_index()
	{
        Breadcrumbs::add(Breadcrumb::factory()->set_title(__('Custom Fields for Advertisements')));
		$this->template->title = __('Custom Fields');

		//find all, for populating form select fields
		$categories         = Model_Category::get_as_array();
		$order_categories   = Model_Category::get_multidimensional();

        $this->template->styles              = array('css/sortable.css' => 'screen');
        $this->template->scripts['footer'][] = 'js/jquery-sortable-min.js';
        $this->template->scripts['footer'][] = 'js/oc-panel/fields.js';

        //retrieve fields
        $fields = Model_Field::get_all();
        if ( core::count($fields) > 65 ) //upper bound for custom fields
            Alert::set(Alert::WARNING,__('You have reached the maximum number of custom fields allowed.'));

		$this->template->content = View::factory('oc-panel/pages/fields/index',array('fields' => $fields, 'categories' => $categories,'order_categories' => $order_categories));
	}

    public function action_new()
    {
        if (Core::extra_features() == FALSE)
        {
            Alert::set(Alert::WARNING, __('This feature is only available in the PRO version!') . ' ' . __('Upgrade your Yclas site to activate this feature.'));
            $this->redirect(Route::url('oc-panel', ['controller' => 'fields']));
        }

        Breadcrumbs::add(Breadcrumb::factory()->set_title(__('New field')));
        $this->template->title = __('New Custom Field for Advertisement');

        //find all, for populating form select fields
        $categories         = Model_Category::get_as_array();
        $order_categories   = Model_Category::get_multidimensional();
        $errors             = '';

        if ($_POST)
        {
            if ( core::count(Model_Field::get_all()) > 65 ) //upper bound for custom fields
            {
                Alert::set(Alert::ERROR,__('You have reached the maximum number of custom fields allowed.'));
                HTTP::redirect(Route::url('oc-panel',array('controller'  => 'fields','action'=>'index')));
            }

            $validation =   Validation::factory($this->request->post())
                                                    ->rule('name', 'alpha_dash')
                                                    ->rule('name', 'not_empty')
                                                    ->rule('name', 'min_length', array(':value', 3))
                                                    ->rule('name', 'max_length', array(':value', 60));
            if ($validation->check())
            {

                $name   = URL::title(Core::post('name'),'_');

                if (strlen($name)>=60)
                    $name = Text::limit_chars($name,60,'');

                $field = new Model_Field();

                try {

                    $options = array(
                                    'label'             => Core::post('label'),
                                    'tooltip'           => Core::post('tooltip'),
                                    'required'          => (Core::post('required')=='on')?TRUE:FALSE,
                                    'searchable'        => (Core::post('searchable')=='on')?TRUE:FALSE,
                                    'admin_privilege'   => (Core::post('admin_privilege')=='on')?TRUE:FALSE,
                                    'show_listing'      => (Core::post('show_listing')=='on')?TRUE:FALSE,
                                    );

                    if ($field->create($name,Core::post('type'),Core::post('values'),Core::post('categories'),$options))
                    {
                        Core::delete_cache();
                        Alert::set(Alert::SUCCESS,sprintf(__('Field %s created'),$name));
                    }
                    else
                        Alert::set(Alert::WARNING,sprintf(__('Field %s already exists'),$name));


                } catch (Exception $e) {
                    throw HTTP_Exception::factory(500,$e->getMessage());
                }

                HTTP::redirect(Route::url('oc-panel',array('controller'  => 'fields','action'=>'index')));

            }
            else
                $errors = $validation->errors('field');


        }


        $this->template->content = View::factory('oc-panel/pages/fields/new',array('categories' => $categories,
                                                                                   'order_categories' => $order_categories,
                                                                                   'errors' => $errors
        																			));
    }

    public function action_update()
    {
        if (Core::extra_features() == FALSE)
        {
            Alert::set(Alert::WARNING, __('This feature is only available in the PRO version!') . ' ' . __('Upgrade your Yclas site to activate this feature.'));
            $this->redirect(Route::url('oc-panel', ['controller' => 'fields']));
        }

        $name   = $this->request->param('id');
        $field  = new Model_Field();
        $field_data  = $field->get($name, FALSE);

        if (! $field_data)
        {
            throw HTTP_Exception::factory(404, __('Page not found'));
        }

        Breadcrumbs::add(Breadcrumb::factory()->set_title(__('Edit').' '.$name));
        $this->template->title = __('Edit Custom Field for Advertisement');

        //find all, for populating form select fields
        $categories  = Model_Category::get_as_array();

        if ($_POST)
        {

            try {

                $options = array(
                                'label'             => Core::post('label'),
                                'tooltip'           => Core::post('tooltip'),
                                'required'          => (Core::post('required')=='on')?TRUE:FALSE,
                                'searchable'        => (Core::post('searchable')=='on')?TRUE:FALSE,
                                'admin_privilege'   => (Core::post('admin_privilege')=='on')?TRUE:FALSE,
                                'show_listing'      => (Core::post('show_listing')=='on')?TRUE:FALSE,
                                );

                if ($field->update($name,Core::post('values'),Core::post('categories'),$options))
                {
                    Core::delete_cache();
                    Alert::set(Alert::SUCCESS,sprintf(__('Field %s edited'),$name));
                }
                else
                    Alert::set(Alert::ERROR,sprintf(__('Field %s cannot be edited'),$name));

            } catch (Exception $e) {
                throw HTTP_Exception::factory(500,$e->getMessage());
            }

            HTTP::redirect(Route::url('oc-panel',array('controller'  => 'fields','action'=>'index')));
        }

        $this->template->content = View::factory('oc-panel/pages/fields/update',array('field_data'=>$field_data,'name'=>$name,'categories'=>$categories));
    }

    /**
     * Updates translations
     * @return void
     */
    public function action_update_translations()
    {
        $name   = $this->request->param('id');
        $field  = new Model_Field();

        if (Core::extra_features() == FALSE)
        {
            Alert::set(Alert::WARNING, __('This feature is only available in the PRO version!') . ' ' . __('Upgrade your Yclas site to activate this feature.'));
            $this->redirect(Route::url('oc-panel', ['controller' => 'fields']));
        }

        if ($_POST)
        {
            try {
                $translations = [
                    'label'     => Core::post('translations')['label'],
                    'tooltip'   => Core::post('translations')['tooltip'],
                    'values'    => Core::post('translations')['values'],
                ];

                if ($field->update_translations($name, $translations))
                {
                    Core::delete_cache();
                    Alert::set(Alert::SUCCESS,sprintf(__('Field %s edited'), $name));
                }
                else
                {
                    Alert::set(Alert::ERROR,sprintf(__('Field %s cannot be edited'), $name));
                }

            } catch (Exception $e) {
                throw HTTP_Exception::factory(500,$e->getMessage());
            }
        }

        $this->redirect(Route::url('oc-panel', ['controller' => 'fields', 'action' => 'update', 'id' => $name]));
    }

    public function action_delete()
    {
        //get name of the param, get the name of the custom fields, deletes from config array and alters table
        $this->auto_render = FALSE;
        $name   = $this->request->param('id');
        $field  = new Model_Field();

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
        $field  = new Model_Field();

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

    // load custom fields from definied templates
    public function action_template()
    {
        if (Core::extra_features() == FALSE)
        {
            Alert::set(Alert::WARNING, __('This feature is only available in the PRO version!') . ' ' . __('Upgrade your Yclas site to activate this feature.'));
            $this->redirect(Route::url('oc-panel', ['controller' => 'fields']));
        }

        if ($_POST)
        {
            $cf_templates = [
                'cars' => [
                    [
                        'name' => 'forsaleby',
                        'type' => 'select',
                        'label' => __('For sale by'),
                        'tooltip' => __('For sale by'),
                        'required' => TRUE,
                        'searchable' => TRUE,
                        'admin_privilege' => FALSE,
                        'show_listing' => TRUE,
                        'values' => implode(',', [
                            __('Owner'),
                            __('Dealer')])
                    ],
                    [
                        'name' => 'adtype',
                        'type' => 'select',
                        'label' => __('Ad type'),
                        'tooltip' => __('Ad type'),
                        'required' => TRUE,
                        'searchable' => TRUE,
                        'admin_privilege' => FALSE,
                        'show_listing' => FALSE,
                        'values' => implode(',', [
                            __('I’m selling my car'),
                            __('I’m looking for a car to buy')])
                    ],
                    [
                        'name' => 'year',
                        'type' => 'select',
                        'label' => __('Year'),
                        'tooltip' => __('Year'),
                        'required' => TRUE,
                        'searchable' => TRUE,
                        'admin_privilege' => FALSE,
                        'show_listing' => TRUE,
                        'values' => FALSE
                    ],
                    [
                        'name' => 'make',
                        'type' => 'select',
                        'label' => __('Make'),
                        'tooltip' => __('Make'),
                        'required' => TRUE,
                        'searchable' => TRUE,
                        'admin_privilege' => FALSE,
                        'show_listing' => TRUE,
                        'values' => FALSE
                    ],
                    [
                        'name' => 'model',
                        'type' => 'select',
                        'label' => __('Model'),
                        'tooltip' => __('Model'),
                        'required' => TRUE,
                        'searchable' => TRUE,
                        'admin_privilege' => FALSE,
                        'show_listing' => TRUE,
                        'values' => FALSE
                    ],
                    [
                        'name' => 'othermake',
                        'type' => 'string',
                        'label' => __('Other make'),
                        'tooltip' => __('Other make'),
                        'required' => FALSE,
                        'searchable' => TRUE,
                        'admin_privilege' => FALSE,
                        'show_listing' => FALSE,
                        'values' => FALSE
                    ],
                    [
                        'name' => 'kilometers',
                        'type' => 'integer',
                        'label' => __('Kilometers'),
                        'tooltip' => __('Kilometers'),
                        'required' => TRUE,
                        'searchable' => TRUE,
                        'admin_privilege' => FALSE,
                        'show_listing' => TRUE,
                        'values' => FALSE
                    ],
                    [
                        'name' => 'bodytype',
                        'type' => 'select',
                        'label' => __('Body type'),
                        'tooltip' => __('Body type'),
                        'required' => TRUE,
                        'searchable' => TRUE,
                        'admin_privilege' => FALSE,
                        'show_listing' => TRUE,
                        'values' => implode(',', [
                            __('Convertible'),
                            __('Coupe (2 door)'),
                            __('Hatchback'),
                            __('Minivan or Van'),
                            __('Pickup Truck'),
                            __('Sedan'),
                            __('SUV. crossover'),
                            __('Wagon'),
                            __('Other')])
                    ],
                    [
                        'name' => 'transmission',
                        'type' => 'select',
                        'label' => __('Transmission'),
                        'tooltip' => __('Transmission'),
                        'required' => TRUE,
                        'searchable' => TRUE,
                        'admin_privilege' => FALSE,
                        'show_listing' => TRUE,
                        'values' => implode(',',[
                            __('Automatic'),
                            __('Manual'),
                            __('Other')])
                    ],
                    [
                        'name' => 'drivetrain',
                        'type' => 'select',
                        'label' => ('Drivetrain'),
                        'tooltip' => ('Drivetrain'),
                        'required' => TRUE,
                        'searchable' => TRUE,
                        'admin_privilege' => FALSE,
                        'show_listing' => TRUE,
                        'values' => implode(',', [
                            __('4 x 4'),
                            __('All-wheel drive (AWD)'),
                            __('Front-wheel drive (FWD)'),
                            __('Rear-wheel drive (RWD)'),
                            __('Other')])
                    ],
                    [
                        'name' => 'color',
                        'type' => 'select',
                        'label' => __('Color'),
                        'tooltip' => __('Color'),
                        'required' => TRUE,
                        'searchable' => TRUE,
                        'admin_privilege' => FALSE,
                        'show_listing' => TRUE,
                        'values' => implode(',', [
                            __('Black'),
                            __('Blue'),
                            __('Brown'),
                            __('Burgundy'),
                            __('Gold'),
                            __('Green'),
                            __('Grey'),
                            __('Orange'),
                            __('Pink'),
                            __('Purple'),
                            __('Red'),
                            __('Silver'),
                            __('Tan'),
                            __('Teal'),
                            __('White'),
                            __('Yellow'),
                            __('Other')])
                    ],
                    [
                        'name' => 'fueltype',
                        'type' => 'select',
                        'label' => __('Fuel Type'),
                        'tooltip' => __('Fuel Type'),
                        'required' => TRUE,
                        'searchable' => TRUE,
                        'admin_privilege' => FALSE,
                        'show_listing' => TRUE,
                        'values' => implode(',', [
                            __('Diesel'),
                            __('Gasoline'),
                            __('Hybrid-Electric'),
                            __('Other')])
                    ],
                    [
                        'name' => 'type',
                        'type' => 'select',
                        'label' => __('Type'),
                        'tooltip' => __('Type'),
                        'required' => TRUE,
                        'searchable' => TRUE,
                        'admin_privilege' => FALSE,
                        'show_listing' => TRUE,
                        'values' => implode(',', [
                            __('Damaged'),
                            __('Lease Takeover'),
                            __('New'),
                            __('Used')])
                    ]
                ],
                'houses' => [
                    [
                        'name' => 'furnished',
                        'type' => 'select',
                        'label' => __('Furnished'),
                        'tooltip' => __('Furnished'),
                        'required' => TRUE,
                        'searchable' => TRUE,
                        'admin_privilege' => FALSE,
                        'show_listing' => TRUE,
                        'values' => implode(',', [
                            __('Yes'),
                            __('No')])
                    ],
                    [
                        'name' => 'bedrooms',
                        'type' => 'select',
                        'label' => __('Bedrooms'),
                        'tooltip' => __('Bedrooms'),
                        'required' => TRUE,
                        'searchable' => TRUE,
                        'admin_privilege' => FALSE,
                        'show_listing' => TRUE,
                        'values' => implode(',', [
                            'Studio',
                            '1',
                            '2',
                            '3',
                            '4',
                            '5',
                            '6',
                            '7',
                            '8',
                            '9',
                            '10'])
                    ],
                    [
                        'name' => 'bathrooms',
                        'type' => 'select',
                        'label' => __('Bathrooms'),
                        'tooltip' => __('Bathrooms'),
                        'required' => TRUE,
                        'searchable' => TRUE,
                        'admin_privilege' => FALSE,
                        'show_listing' => TRUE,
                        'values' => implode(',', [
                            '1',
                            '2',
                            '3',
                            '4',
                            '5',
                            '6',
                            '7',
                            '8',
                            '9',
                            '10'])
                    ],
                    [
                        'name' => 'pets',
                        'type' => 'select',
                        'label' => __('Pets'),
                        'tooltip' => __('Pets'),
                        'required' => FALSE,
                        'searchable' => TRUE,
                        'admin_privilege' => FALSE,
                        'show_listing' => TRUE,
                        'values' => implode(',', [
                            'Yes',
                            'No'])
                    ],
                    [
                        'name' => 'agencybrokerfee',
                        'type' => 'select',
                        'label' => __('Agency/broker fee'),
                        'tooltip' => __('Agency/broker fee'),
                        'required' => FALSE,
                        'searchable' => TRUE,
                        'admin_privilege' => FALSE,
                        'show_listing' => TRUE,
                        'values' => implode(',', [
                            __('Yes'),
                            __('No')])
                    ],
                    [
                        'name' => 'squaremeters',
                        'type' => 'string',
                        'label' => __('Square meters'),
                        'tooltip' => __('Square meters'),
                        'required' => FALSE,
                        'searchable' => TRUE,
                        'admin_privilege' => FALSE,
                        'show_listing' => TRUE,
                        'values' => FALSE
                    ],
                    [
                        'name' => 'pricenegotiable',
                        'type' => 'checkbox',
                        'label' => __('Price negotiable'),
                        'tooltip' => __('Price negotiable'),
                        'required' => FALSE,
                        'searchable' => TRUE,
                        'admin_privilege' => FALSE,
                        'show_listing' => TRUE,
                        'values' => FALSE
                    ]
                ],
                'jobs' => [
                    [
                        'name' => 'jobtype',
                        'type' => 'select',
                        'label' => __('Job type'),
                        'tooltip' => __('Job type'),
                        'required' => TRUE,
                        'searchable' => TRUE,
                        'admin_privilege' => FALSE,
                        'show_listing' => TRUE,
                        'values' => implode(',', [
                            ('Full-time'),
                            ('Part-time')])
                    ],
                    [
                        'name' => 'experienceinyears',
                        'type' => 'select',
                        'label' => __('Experience in Years'),
                        'tooltip' => __('Experience in Years'),
                        'required' => FALSE,
                        'searchable' => TRUE,
                        'admin_privilege' => FALSE,
                        'show_listing' => TRUE,
                        'values' => implode(',', [
                            __('Less than 1'),
                            '2',
                            '3',
                            '4',
                            '5',
                            '6',
                            '7',
                            '8',
                            '9',
                            '10',
                            '11',
                            '12',
                            '13',
                            '14',
                            '15',
                            '16',
                            '17',
                            '18',
                            '19',
                            '20',
                            __('More than 20')])
                    ],
                    [
                        'name' => 'salary',
                        'type' => 'integer',
                        'label' => __('Salary'),
                        'tooltip' => __('Salary'),
                        'required' => FALSE,
                        'searchable' => TRUE,
                        'admin_privilege' => FALSE,
                        'show_listing' => FALSE,
                        'values' => FALSE
                    ],
                    [
                        'name' => 'salarytype',
                        'type' => 'select',
                        'label' => 'Salary type',
                        'tooltip' => 'Salary type',
                        'required' => FALSE,
                        'searchable' => TRUE,
                        'admin_privilege' => FALSE,
                        'show_listing' => FALSE,
                        'values' => implode(',', [
                            __('Hourly'),
                            __('Daily'),
                            __('Weekly'),
                            __('Monthly'),
                            __('Quarterly'),
                            __('Yearly')])
                    ],
                    [
                        'name' => 'extrainformation',
                        'type' => 'textarea',
                        'label' => __('Extra information'),
                        'tooltip' => __('Extra information'),
                        'required' => FALSE,
                        'searchable' => TRUE,
                        'admin_privilege' => FALSE,
                        'show_listing' => FALSE,
                        'values' => FALSE
                    ],
                    [
                        'name' => 'company',
                        'type' => 'string',
                        'label' => __('Company'),
                        'tooltip' => __('Company name'),
                        'required' => TRUE,
                        'searchable' => TRUE,
                        'admin_privilege' => FALSE,
                        'show_listing' => TRUE,
                        'values' => FALSE
                    ],
                    [
                        'name' => 'companydescription',
                        'type' => 'textarea',
                        'label' => __('Company description'),
                        'tooltip' => __('Company description'),
                        'required' => FALSE,
                        'searchable' => FALSE,
                        'admin_privilege' => FALSE,
                        'show_listing' => FALSE,
                        'values' => FALSE
                    ]
                ],
                'dating' => [
                    [
                        'name' => 'age',
                        'type' => 'integer',
                        'label' => __('Age'),
                        'tooltip' => __('Age'),
                        'required' => FALSE,
                        'searchable' => TRUE,
                        'admin_privilege' => FALSE,
                        'show_listing' => TRUE,
                        'values' => FALSE
                    ],
                    [
                        'name' => 'body',
                        'type' => 'select',
                        'label' => __('Body'),
                        'tooltip' => __('Body'),
                        'required' => FALSE,
                        'searchable' => TRUE,
                        'admin_privilege' => FALSE,
                        'show_listing' => TRUE,
                        'values' => implode(',', [
                            '-',
                            __('Athletic'),
                            __('Average'),
                            __('big'),
                            __('Curvy'),
                            __('Fit'),
                            __('Heavy'),
                            __('HWP'),
                            __('Skinny'),
                            __('Thin')])
                    ],
                    [
                        'name' => 'height',
                        'type' => 'select',
                        'label' => __('Height'),
                        'tooltip' => __('Height'),
                        'required' => FALSE,
                        'searchable' => TRUE,
                        'admin_privilege' => FALSE,
                        'show_listing' => TRUE,
                        'values' => implode(',', [
                            __('Taller than 6.8 (203 cm)'),
                            '6.7 (200 cm)',
                            '6.6 (198 cm)',
                            '6.5 (195 cm)',
                            '6.4 (194cm)',
                            '6.3 (190 cm)',
                            '6.2 (187 cm)',
                            '6.1 (185 cm)',
                            '6.0 (182 cm)',
                            '5.11 (180 cm)',
                            '5.10 (177 cm)',
                            '5.9 (175 cm)',
                            '5.8 (172 cm)',
                            '5.7 (170 cm)',
                            '5.6 (167 cm)',
                            '5.5 (165 cm)',
                            '5.4 (162 cm)',
                            '5.3 (160 cm)',
                            '5.2 (157 cm)',
                            '5.1 (154 cm)',
                            '5.0 (152 cm)',
                            '4.11 (150 cm)',
                            '4.10 (147 cm)',
                            '4.9 (145 cm)',
                            '4.8 (142 cm) or less'])
                    ],
                    [
                        'name' => 'status',
                        'type' => 'select',
                        'label' => __('Status'),
                        'tooltip' => __('Status'),
                        'required' => FALSE,
                        'searchable' => TRUE,
                        'admin_privilege' => FALSE,
                        'show_listing' => FALSE,
                        'values' => implode(',', [
                            __('Single'),
                            __('In a Relationship'),
                            __('Engaged'),
                            __('Married'),
                            __('Separated'),
                            __('Divorced'),
                            __('Widowed')])
                    ],
                    [
                        'name' => 'occupation',
                        'type' => 'string',
                        'label' => __('Occupation'),
                        'tooltip' => __('Occupation'),
                        'required' => FALSE,
                        'searchable' => TRUE,
                        'admin_privilege' => FALSE,
                        'show_listing' => FALSE,
                        'values' => FALSE
                    ],
                    [
                        'name' => 'hair',
                        'type' => 'string',
                        'label' => __('Hair'),
                        'tooltip' => __('Hair'),
                        'required' => FALSE,
                        'searchable' => TRUE,
                        'admin_privilege' => FALSE,
                        'show_listing' => FALSE,
                        'values' => FALSE
                    ],
                    [
                        'name' => 'eyecolor',
                        'type' => 'string',
                        'label' => __('Eye color'),
                        'tooltip' => __('Eye color'),
                        'required' => FALSE,
                        'searchable' => TRUE,
                        'admin_privilege' => FALSE,
                        'show_listing' => FALSE,
                        'values' => FALSE
                    ]
                ]
            ];

            $field  = new Model_Field();

            foreach ($cf_templates[Core::post('type')] as $custom_field) {
                try {

                    $name = $custom_field['name'];

                    $options = array(
                                    'label'             => $custom_field['label'],
                                    'tooltip'           => $custom_field['tooltip'],
                                    'required'          => $custom_field['required'],
                                    'searchable'        => $custom_field['searchable'],
                                    'admin_privilege'   => $custom_field['admin_privilege'],
                                    'show_listing'      => $custom_field['show_listing'],
                                    );

                    if ($field->create($name,$custom_field['type'],$custom_field['values'],Core::post('categories'),$options))
                    {
                        Core::delete_cache();
                        Alert::set(Alert::SUCCESS,sprintf(__('Field %s created'),$name));
                    }
                    else
                        Alert::set(Alert::WARNING,sprintf(__('Field %s already exists'),$name));


                } catch (Exception $e) {
                    throw HTTP_Exception::factory(500,$e->getMessage());
                }
            }

            if(Core::post('type') == 'cars'){

                $config_key = 'carquery';
                $group_name = 'general';
                $config_value = 1;

                Model_Config::set_value($group_name, $config_key, $config_value);
                Core::delete_cache();
            }

            HTTP::redirect(Route::url('oc-panel',array('controller'  => 'fields','action'=>'index')));
        }
        else
            HTTP::redirect(Route::url('oc-panel',array('controller'  => 'fields','action'=>'index')));
    }

    /**
    * add category to custom field
    * @return void
    */
    public function action_add_category()
    {
        if (Core::get('id_category'))
        {
            $name        = $this->request->param('id');
            $field       = new Model_Field();
            $field_data  = $field->get($name);
            $category    = new Model_Category(Core::get('id_category'));

            // category or custom field not found
            if ( ! $category->loaded() OR ! $field_data)
                $this->redirect(Route::get('oc-panel')->uri(array('controller'=> Request::current()->controller(), 'action'=>'index')));

            // append category to custom field categories
            $field_data['categories'][] = $category->id_category;

            try {
                // update custom field categories
                if ($field->update($name, $field_data['values'], $field_data['categories'], $field_data))
                {
                    Core::delete_cache();
                    Alert::set(Alert::SUCCESS,sprintf(__('Field %s added'), $name));
                }
                else
                    Alert::set(Alert::ERROR,sprintf(__('Field %s cannot be added'), $name));

            } catch (Exception $e) {
                throw HTTP_Exception::factory(500,$e->getMessage());
            }

            $this->redirect(Route::get('oc-panel')->uri(array('controller'=> 'category', 'action'=>'update', 'id'=>$category->id_category)));
        }

        $this->redirect(Route::get('oc-panel')->uri(array('controller'=> Request::current()->controller(), 'action'=>'index')));
    }

    /**
    * remove category from custom field
    * @return void
    */
    public function action_remove_category()
    {
        if (Core::get('id_category'))
        {
            $name        = $this->request->param('id');
            $field       = new Model_Field();
            $field_data  = $field->get($name);
            $category    = new Model_Category(Core::get('id_category'));

            // category or custom field not found
            if ( ! $category->loaded() OR ! $field_data)
                $this->redirect(Route::get('oc-panel')->uri(array('controller'=> Request::current()->controller(), 'action'=>'index')));

            // remove current category from custom field categories
            if ( is_array($field_data['categories']) AND ($key = array_search($category->id_category, $field_data['categories'])) !== FALSE )
                unset($field_data['categories'][$key]);

            try {
                // update custom field categories
                if ($field->update($name, $field_data['values'], $field_data['categories'], $field_data))
                {
                    Core::delete_cache();
                    Alert::set(Alert::SUCCESS,sprintf(__('Field %s removed'), $name));
                }
                else
                    Alert::set(Alert::ERROR,sprintf(__('Field %s cannot be removed'), $name));

            } catch (Exception $e) {
                throw HTTP_Exception::factory(500,$e->getMessage());
            }

            $this->redirect(Route::get('oc-panel')->uri(array('controller'=> 'category', 'action'=>'update', 'id'=>$category->id_category)));
        }

        $this->redirect(Route::get('oc-panel')->uri(array('controller'=> Request::current()->controller(), 'action'=>'index')));
    }

}
