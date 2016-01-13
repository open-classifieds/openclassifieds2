<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Model For Custom Fields, handles altering the table and the configs were we save extra data.
 *
 * @author      Chema <chema@open-classifieds.com>
 * @package     Core
 * @copyright   (c) 2009-2013 Open Classifieds Team
 * @license     GPL v3
 */

class Model_Field {

    private $_db_prefix = NULL; //db prefix
    private $_db        = NULL; //db instance
    private $_bs        = NULL; //blacksmith module instance
    private $_name_prefix = 'cf_'; //prefix used in front of the column name

    public function __construct()
    {
        $this->_db_prefix   = Database::instance('default')->table_prefix();
        $this->_db          = Database::instance();
        $this->_bs          = Blacksmith::alter();

    }

    /**
     * creates a new custom field on DB and config
     * @param  string $name    
     * @param  string $type    
     * @param  string $values  
     * @param  array  $options 
     * @return bool          
     */
    public function create($name, $type = 'string', $values = NULL, $categories = NULL, array $options)
    {
        if (!$this->field_exists($name))
        {

            $table = $this->_bs->table($this->_db_prefix.'ads');

            switch ($type) 
            {
                case 'textarea':
                    $table->add_column()
                        ->text($this->_name_prefix.$name);
                    break;

                case 'integer':
                    $table->add_column()
                        ->int($this->_name_prefix.$name);
                    break;

                case 'checkbox':
                    $table->add_column()
                        ->tiny_int($this->_name_prefix.$name,1);
                    break;

                case 'decimal':
                    $table->add_column()
                        ->float($this->_name_prefix.$name);
                    break;

                case 'date':
                    $table->add_column()
                        ->date($this->_name_prefix.$name);
                    break;
                
                case 'select': 
                    
                    $values = array_map('trim', explode(',', $values));

                    $table->add_column()
                        ->string($this->_name_prefix.$name, 256);
                    break;
                    
                case 'radio':    

                    $values = array_map('trim', explode(',', $values));
                    
                    $table->add_column()
                        ->tiny_int($this->_name_prefix.$name,1);
                    break;

                case 'email':
                    $table->add_column()
                        ->string($this->_name_prefix.$name, 145);
                    break;

                case 'string':            
                default:
                    $table->add_column()
                        ->string($this->_name_prefix.$name, 256);
                    break;
            }
            
            $this->_bs->forge($this->_db);

            //save configs
            $conf = new Model_Config();
            $conf->where('group_name','=','advertisement')
                 ->where('config_key','=','fields')
                 ->limit(1)->find();

            if ($conf->loaded())
            {
                //remove the key
                $fields = json_decode($conf->config_value,TRUE);

                if (!is_array($fields))
                    $fields = array();
                
                //add child categories of selected categories
                if (is_array($categories))
                {
                    // get category siblings
                    foreach ($categories as $category)
                    {
                        $category = new Model_Category($category);
                        $categories = array_merge($categories, $category->get_siblings_ids());
                    }
                    
                    // remove duplicated categories
                    $categories = array_unique($categories);
                }
                
                //save at config
                $fields[$name] = array(
                                'type'      => $type, 
                                'label'     => $options['label'],
                                'tooltip'   => $options['tooltip'],
                                'values'    => $values,
                                'categories'=> $categories,
                                'required'  => $options['required'],
                                'searchable'=> $options['searchable'],
                                'admin_privilege'   => $options['admin_privilege'],
                                'show_listing'      => $options['show_listing']
                                );

                $conf->config_value = json_encode($fields);
                $conf->save();
            }

            return TRUE;
        }
        else
            return FALSE;

    }

    /**
     * updates custom field option, not the name or the type
     * @param  string $name    
     * @param  string $values  
     * @param  array  $options 
     * @return bool          
     */
    public function update($name, $values = NULL, $categories = NULL, array $options)
    {
        if ($this->field_exists($name))
        {
            //save configs
            $conf = new Model_Config();
            $conf->where('group_name','=','advertisement')
                 ->where('config_key','=','fields')
                 ->limit(1)->find();
                        
            if ($conf->loaded())
            {
                $fields = json_decode($conf->config_value,TRUE);
                
                if (!empty($values) AND !is_array($values) AND ($fields[$name]['type'] == 'select' OR $fields[$name]['type'] == 'radio') )
                    $values = array_map('trim', explode(',', $values));
                
                //add child categories of selected categories
                if (is_array($categories))
                {
                    // get category siblings
                    foreach ($categories as $category)
                    {
                        $category = new Model_Category($category);
                        $categories = array_merge($categories, $category->get_siblings_ids());
                    }
                    
                    // remove duplicated categories
                    $categories = array_unique($categories);
                }
                
                //save at config
                $fields[$name] = array(
                                'type'      => $fields[$name]['type'], 
                                'label'     => $options['label'],
                                'tooltip'   => $options['tooltip'],
                                'values'    => $values,
                                'categories'=> $categories,
                                'required'  => $options['required'],
                                'searchable'=> $options['searchable'],
                                'admin_privilege' => $options['admin_privilege'],
                                'show_listing'    => $options['show_listing']
                                );

                $conf->config_value = json_encode($fields);
                $conf->save();
            }

            return TRUE;
        }
        else
            return FALSE;

    }

    /**
     * deletes a fields from DB and config
     * @param  string $name 
     * @return bool       
     */
    public function delete($name)
    {        
        if ($this->field_exists($name))
        {
            $table = $this->_bs->table($this->_db_prefix.'ads');
            $table->drop_column($this->_name_prefix.$name);
            $this->_bs->forge($this->_db);


            //save configs
            $conf = new Model_Config();
            $conf->where('group_name','=','advertisement')
                 ->where('config_key','=','fields')
                 ->limit(1)->find();
                        
            if ($conf->loaded())
            {
                //remove the key
                $fields = json_decode($conf->config_value, TRUE);
                unset($fields[$name]);

                $conf->config_value = json_encode($fields);
                $conf->save();
            }

            return TRUE;
        }
        else
            return FALSE;

        
    }

    /**
     * changes the order to display fields
     * @param  array  $order 
     * @return bool
     */
    public function change_order(array $order)
    {        
        $fields = self::get_all();

        $new_fields =  array();

        //using order they send us
        foreach ($order as $name)
        {
            if (isset($fields[$name]))
                $new_fields[$name] = $fields[$name];
        } 
       
        //save configs
        $conf = new Model_Config();
        $conf->where('group_name','=','advertisement')
             ->where('config_key','=','fields')
             ->limit(1)->find();
                    
        if ($conf->loaded())
        {
            try
            {
                $conf->config_value = json_encode($new_fields);
                $conf->save();
                return TRUE;
            }
            catch (Exception $e)
            {
                throw HTTP_Exception::factory(500,$e->getMessage());     
            }
        }
        return FALSE;
    }

    /**
     * get values for a field
     * @param  string $name 
     * @return array/bool    
     */
    public function get($name)
    {
        if ($this->field_exists($name))
        {
            $fields = self::get_all();
            return $fields[$name];
        }
        else
            return FALSE;
    }

    /**
     * get the custom fields for an ad
     * @return array/class
     */
    public static function get_all($as_array = TRUE)
    {
        if ( ! is_null($fields = json_decode(core::config('advertisement.fields'),$as_array)) )
            return $fields;
        else
            return array();
    }

    /**
     * get the custom fields for a category
     * @return array/class
     */
    public static function get_by_category($id_category)
    {
        $fields = array();
        $all_fields = self::get_all();
        if (is_array($all_fields))
        {
            foreach ($all_fields as $field => $values) 
            {
                if ((is_array($values['categories']) AND in_array($id_category,$values['categories']))
                    OR $values['categories'] === NULL)
                    $fields['cf_'.$field] = $values;
            }
        }

        return $fields;
    }

    /**
     * says if a field exists int he table ads
     * @param  string $name 
     * @return bool      
     */
    private function field_exists($name)
    {
        //@todo read from config file?
        $columns = Database::instance()->list_columns('ads');
        return (array_key_exists($this->_name_prefix.$name, $columns));
    }



}