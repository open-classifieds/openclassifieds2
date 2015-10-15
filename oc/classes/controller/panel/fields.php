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
        if ( count($fields) > 65 ) //upper bound for custom fields
            Alert::set(Alert::WARNING,__('You have reached the maximum number of custom fields allowed.'));

		$this->template->content = View::factory('oc-panel/pages/fields/index',array('fields' => $fields, 'categories' => $categories,'order_categories' => $order_categories));
	}
    

    public function action_new()
    {
     
        Breadcrumbs::add(Breadcrumb::factory()->set_title(__('New field')));
        $this->template->title = __('New Custom Field for Advertisement');

        //find all, for populating form select fields 
        $categories         = Model_Category::get_as_array();  
        $order_categories   = Model_Category::get_multidimensional();

        if ($_POST)
        {
            if ( count(Model_Field::get_all()) > 65 ) //upper bound for custom fields
            {
                Alert::set(Alert::ERROR,__('You have reached the maximum number of custom fields allowed.'));
                HTTP::redirect(Route::url('oc-panel',array('controller'  => 'fields','action'=>'index')));  
            }
            
            $name   = URL::title(Core::post('name'));

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

        $this->template->content = View::factory('oc-panel/pages/fields/new',array('categories' => $categories,
                                                                                   'order_categories' => $order_categories,
        																			));
    }

    public function action_update()
    {
        $name   = $this->request->param('id');
        $field  = new Model_Field();
        $field_data  = $field->get($name);

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
     
        if ($_POST)
        {            
            $cf_templates = '
                            {
                                "cars": [
                                    {
                                        "name": "forsaleby",
                                        "type": "select",
                                        "label": "For sale by",
                                        "tooltip": "For sale by",
                                        "required": true,
                                        "searchable": true,
                                        "admin_privilege": false,
                                        "show_listing": true,
                                        "values": "Owner,Dealer"
                                    },
                                    {
                                        "name": "adtype",
                                        "type": "select",
                                        "label": "Ad type",
                                        "tooltip": "Ad type",
                                        "required": true,
                                        "searchable": true,
                                        "admin_privilege": false,
                                        "show_listing": false,
                                        "values": "I’m selling my car,I’m looking for a car to buy"
                                    },
                                    {
                                        "name": "make",
                                        "type": "select",
                                        "label": "Make",
                                        "tooltip": "Make",
                                        "required": true,
                                        "searchable": true,
                                        "admin_privilege": false,
                                        "show_listing": true,
                                        "values": "Acura,Alfa Romeo,AM General,AMC,Aston Martin,Audi,Austin-Healey,Bently,BMW,Bricklin,Bugatti,Buick,Cadillac,Chevrolet,Chrysler,Daewoo,Datsun,Diahatsu,Dodge,Eagle,Ferrari,Fiat,Ford,Geo,GMC,Honda,HUMMER,Hyundai,Infiniti,International Harvester,Isuzu,Jaguar,Jeep,Kia,Lamborghini,Land Rover,Lexus,Lincoln,Lotus,Maserati,Maybach,Mazda,Mercedes-Benz,Mercury,MG,MINI,Mitsubishi,Nissan,Oldsmobile,Opel,Peugeot,Plymouth,Pontiac,Porsche,Ram,Renault,Rolls-Royce,Saab,Saturn,Scion,Shelby,Smart,Subaru,Suzuki,Toyota,Triumph,Volkswagen,Volvo,Other"
                                    },
                                    {
                                        "name": "othermake",
                                        "type": "string",
                                        "label": "Other make",
                                        "tooltip": "Other make",
                                        "required": false,
                                        "searchable": true,
                                        "admin_privilege": false,
                                        "show_listing": false,
                                        "values": ""
                                    },
                                    {
                                        "name": "model",
                                        "type": "string",
                                        "label": "Model",
                                        "tooltip": "Model",
                                        "required": true,
                                        "searchable": true,
                                        "admin_privilege": false,
                                        "show_listing": true,
                                        "values": ""
                                    },
                                    {
                                        "name": "year",
                                        "type": "integer",
                                        "label": "Year",
                                        "tooltip": "Year",
                                        "required": true,
                                        "searchable": true,
                                        "admin_privilege": false,
                                        "show_listing": true,
                                        "values": ""
                                    },
                                    {
                                        "name": "kilometers",
                                        "type": "integer",
                                        "label": "Kilometers",
                                        "tooltip": "Kilometers",
                                        "required": true,
                                        "searchable": true,
                                        "admin_privilege": false,
                                        "show_listing": true,
                                        "values": ""
                                    },
                                    {
                                        "name": "bodytype",
                                        "type": "select",
                                        "label": "Body type",
                                        "tooltip": "Body type",
                                        "required": true,
                                        "searchable": true,
                                        "admin_privilege": false,
                                        "show_listing": true,
                                        "values": "Convertible,Coupe (2 door),Hatchback,Minivan or Van,Pickup Truck,Sedan,SUV. crossover,Wagon,Other"
                                    },
                                    {
                                        "name": "transmission",
                                        "type": "select",
                                        "label": "Transmission",
                                        "tooltip": "Transmission",
                                        "required": true,
                                        "searchable": true,
                                        "admin_privilege": false,
                                        "show_listing": true,
                                        "values": "Automatic,Manual,Other"
                                    },
                                    {
                                        "name": "drivetrain",
                                        "type": "select",
                                        "label": "Drivetrain",
                                        "tooltip": "Drivetrain",
                                        "required": true,
                                        "searchable": true,
                                        "admin_privilege": false,
                                        "show_listing": true,
                                        "values": "4 x 4,All-wheel drive (AWD),Front-wheel drive (FWD),Rear-wheel drive (RWD),Other"
                                    },
                                    {
                                        "name": "color",
                                        "type": "select",
                                        "label": "Color",
                                        "tooltip": "Color",
                                        "required": true,
                                        "searchable": true,
                                        "admin_privilege": false,
                                        "show_listing": true,
                                        "values": "Black,Blue,Brown,Burgundy,Gold,Green,Grey,Orange,Pink,Purple,Red,Silver,Tan,Teal,White,Yellow,Other"
                                    },
                                    {
                                        "name": "fueltype",
                                        "type": "select",
                                        "label": "Fuel Type",
                                        "tooltip": "Fuel Type",
                                        "required": true,
                                        "searchable": true,
                                        "admin_privilege": false,
                                        "show_listing": true,
                                        "values": "Diesel,Gasoline,Hybrid-Electric,Other"
                                    },
                                    {
                                        "name": "type",
                                        "type": "select",
                                        "label": "Type",
                                        "tooltip": "Type",
                                        "required": true,
                                        "searchable": true,
                                        "admin_privilege": false,
                                        "show_listing": true,
                                        "values": "Damaged,Lease Takeover,New,Used"
                                    }
                                ],
                                "houses": [
                                    {
                                        "name": "furnished",
                                        "type": "select",
                                        "label": "Furnished",
                                        "tooltip": "Furnished",
                                        "required": true,
                                        "searchable": true,
                                        "admin_privilege": false,
                                        "show_listing": true,
                                        "values": "Yes,No"
                                    },
                                    {
                                        "name": "bedrooms",
                                        "type": "select",
                                        "label": "Bedrooms",
                                        "tooltip": "Bedrooms",
                                        "required": true,
                                        "searchable": true,
                                        "admin_privilege": false,
                                        "show_listing": true,
                                        "values": "Studio,1,2,3,4,5,6,7,8,9,10"
                                    },
                                    {
                                        "name": "bathrooms",
                                        "type": "select",
                                        "label": "Bathrooms",
                                        "tooltip": "Bathrooms",
                                        "required": true,
                                        "searchable": true,
                                        "admin_privilege": false,
                                        "show_listing": true,
                                        "values": "1,2,3,4,5,6,7,8,9,10"
                                    },
                                    {
                                        "name": "pets",
                                        "type": "select",
                                        "label": "Pets",
                                        "tooltip": "Pets",
                                        "required": false,
                                        "searchable": true,
                                        "admin_privilege": false,
                                        "show_listing": true,
                                        "values": "yes,No"
                                    },
                                    {
                                        "name": "agencybrokerfee",
                                        "type": "select",
                                        "label": "Agency\/broker fee",
                                        "tooltip": "Agency\/broker fee",
                                        "required": false,
                                        "searchable": true,
                                        "admin_privilege": false,
                                        "show_listing": true,
                                        "values": "Yes,No"
                                    },
                                    {
                                        "name": "squaremeters",
                                        "type": "string",
                                        "label": "Square meters",
                                        "tooltip": "Square meters",
                                        "required": false,
                                        "searchable": true,
                                        "admin_privilege": false,
                                        "show_listing": true,
                                        "values": ""
                                    },
                                    {
                                        "name": "pricenegotiable",
                                        "type": "checkbox",
                                        "label": "Price negotiable",
                                        "tooltip": "Price negotiable",
                                        "required": false,
                                        "searchable": true,
                                        "admin_privilege": false,
                                        "show_listing": true,
                                        "values": ""
                                    }
                                ],
                                "jobs": [
                                    {
                                        "name": "jobtype",
                                        "type": "select",
                                        "label": "Job type",
                                        "tooltip": "Job type",
                                        "required": true,
                                        "searchable": true,
                                        "admin_privilege": false,
                                        "show_listing": true,
                                        "values": "Full-time,Part-time"
                                    },
                                    {
                                        "name": "experienceinyears",
                                        "type": "select",
                                        "label": "Experience in Years",
                                        "tooltip": "Experience in Years",
                                        "required": false,
                                        "searchable": true,
                                        "admin_privilege": false,
                                        "show_listing": true,
                                        "values": "Less than 1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,More than 20"
                                    },
                                    {
                                        "name": "salary",
                                        "type": "integer",
                                        "label": "Salary",
                                        "tooltip": "Salary",
                                        "required": false,
                                        "searchable": true,
                                        "admin_privilege": false,
                                        "show_listing": false,
                                        "values": ""
                                    },
                                    {
                                        "name": "salarytype",
                                        "type": "select",
                                        "label": "Salary type",
                                        "tooltip": "Salary type",
                                        "required": false,
                                        "searchable": true,
                                        "admin_privilege": false,
                                        "show_listing": false,
                                        "values": "Hourly,Daily,Weekly,Monthly,Quarterly,Yearly"
                                    },
                                    {
                                        "name": "extrainformation",
                                        "type": "textarea",
                                        "label": "Extra information",
                                        "tooltip": "Extra information",
                                        "required": false,
                                        "searchable": true,
                                        "admin_privilege": false,
                                        "show_listing": false,
                                        "values": ""
                                    },
                                    {
                                        "name": "company",
                                        "type": "string",
                                        "label": "Company",
                                        "tooltip": "Company name",
                                        "required": true,
                                        "searchable": true,
                                        "admin_privilege": false,
                                        "show_listing": true,
                                        "values": ""
                                    },
                                    {
                                        "name": "companydescription",
                                        "type": "textarea",
                                        "label": "Company description",
                                        "tooltip": "Company description",
                                        "required": false,
                                        "searchable": false,
                                        "admin_privilege": false,
                                        "show_listing": false,
                                        "values": ""
                                    }
                                ],
                                "dating": [
                                    {
                                        "name": "age",
                                        "type": "integer",
                                        "label": "Age",
                                        "tooltip": "Age",
                                        "required": false,
                                        "searchable": true,
                                        "admin_privilege": false,
                                        "show_listing": true,
                                        "values": ""
                                    },
                                    {
                                        "name": "body",
                                        "type": "select",
                                        "label": "Body",
                                        "tooltip": "Body",
                                        "required": false,
                                        "searchable": true,
                                        "admin_privilege": false,
                                        "show_listing": true,
                                        "values": "-,Athletic,Average,big,Curvy,Fit,Heavy,HWP,Skinny,Thin,"
                                    },
                                    {
                                        "name": "height",
                                        "type": "select",
                                        "label": "Height",
                                        "tooltip": "Height",
                                        "required": false,
                                        "searchable": true,
                                        "admin_privilege": false,
                                        "show_listing": true,
                                        "values": "Taller than 6.8 (203 cm),6.7 (200 cm),6.6 (198 cm),6.5 (195 cm),6.4 (194cm),6.3 (190 cm),6.2 (187 cm),6.1 (185 cm),6.0 (182 cm),5.11 (180 cm),5.10 (177 cm),5.9 (175 cm),5.8 (172 cm),5.7 (170 cm),5.6 (167 cm),5.5 (165 cm),5.4 (162 cm),5.3 (160 cm),5.2 (157 cm),5.1 (154 cm),5.0 (152 cm),4.11 (150 cm),4.10 (147 cm),4.9 (145 cm),4.8 (142 cm) or less"
                                    },
                                    {
                                        "name": "status",
                                        "type": "select",
                                        "label": "Status",
                                        "tooltip": "Status",
                                        "required": false,
                                        "searchable": true,
                                        "admin_privilege": false,
                                        "show_listing": false,
                                        "values": "Single,In a Relationship,Engaged,Married,Separated,Divorced,Widowed"
                                    },
                                    {
                                        "name": "occupation",
                                        "type": "string",
                                        "label": "Occupation",
                                        "tooltip": "Occupation",
                                        "required": false,
                                        "searchable": true,
                                        "admin_privilege": false,
                                        "show_listing": false,
                                        "values": ""
                                    },
                                    {
                                        "name": "hair",
                                        "type": "string",
                                        "label": "Hair",
                                        "tooltip": "Hair",
                                        "required": false,
                                        "searchable": true,
                                        "admin_privilege": false,
                                        "show_listing": false,
                                        "values": ""
                                    },
                                    {
                                        "name": "eyecolor",
                                        "type": "string",
                                        "label": "Eye color",
                                        "tooltip": "Eye color",
                                        "required": false,
                                        "searchable": true,
                                        "admin_privilege": false,
                                        "show_listing": false,
                                        "values": ""
                                    }
                                ]
                            }
                            ';

            $cf_templates = json_decode($cf_templates, TRUE);
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
