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
        if ($this->field_exists($name)) {
            return FALSE;
        }

        $table = $this->_bs->table($this->_db_prefix.'ads');

        switch ($type)
        {
            case 'textarea':
            case 'textarea_bbcode':
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

            case 'checkbox_group':

                $name = str_replace('_', '', $name);
                $values = array_map('trim', explode(',', $values));
                $grouped_values = [];

                foreach ($values as $key => $value) {
                    $value_name = URL::title($value, '_');

                    if (strlen($value_name) >= 60)
                        $value_name = Text::limit_chars($value_name, 60, '');

                    $value_name = UTF8::strtoupper($name) . '_' . $value_name;

                    $table->add_column()
                        ->tiny_int($this->_name_prefix . $value_name, 1);

                    $grouped_values[$value_name] = $value;

                }

                break;

            case 'decimal':
                $table->add_column()
                    ->float($this->_name_prefix.$name);
                break;

            case 'range':
                $table->add_column()
                    ->float($this->_name_prefix.$name);
                break;

            case 'money':
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

            case 'country':
                $table->add_column()
                    ->string($this->_name_prefix.$name, 145);
                break;

           case 'json':
                $table->add_column()
                    ->json($this->_name_prefix.$name);
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
                    if ( ($siblings = $category->get_siblings_ids())!=NULL )
                        $categories = array_merge($categories, $siblings);
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
                            'text_searchable'=> $options['text_searchable'],
                            'admin_privilege'   => $options['admin_privilege'],
                            'show_listing'      => $options['show_listing'],
                            'grouped_values'    => isset($grouped_values) ? $grouped_values : NULL
                            );

            $conf->config_value = json_encode($fields);
            $conf->save();
        }

        return TRUE;
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
        //save configs
        $config = new Model_Config();
        $config->where('group_name', '=', 'advertisement')
            ->where('config_key', '=', 'fields')
            ->limit(1)->find();

        if (!$config->loaded())
        {
            return FALSE;
        }

        $fields = json_decode($config->config_value, TRUE);

        if (!isset($fields[$name]))
        {
            return FALSE;
        }

        $field = $fields[$name];

        if (!$this->field_exists($name) AND $field['type'] != 'checkbox_group')
        {
            return FALSE;
        }

        if (!empty($values) AND !is_array($values) AND ($fields[$name]['type'] == 'select' OR $fields[$name]['type'] == 'radio' OR $fields[$name]['type'] == 'checkbox_group'))
            $values = array_map('trim', explode(',', $values));

        if ($field['type'] == 'checkbox_group')
        {
            $grouped_values = [];

            foreach ($values as $key => $value)
            {
                $value_name = URL::title($value, '_');

                if (strlen($value_name) >= 60)
                    $value_name = Text::limit_chars($value_name, 60, '');

                $value_name = UTF8::strtoupper($name) . '_' . $value_name;

                $grouped_values[$value_name] = $value;
            }

            // if a value is removed drop its column too
            foreach ($fields[$name]['grouped_values'] as $key => $value)
            {
                if (!isset($grouped_values[$key]))
                {
                    if (!$this->field_exists($key)) {
                        return FALSE;
                    }

                    $table = $this->_bs->table($this->_db_prefix . 'ads');
                    $table->drop_column($this->_name_prefix . $key);
                    $this->_bs->forge($this->_db);
                }
            }

            // if there is a new value add its column too
            foreach ($grouped_values as $key => $value)
            {
                if (!$this->field_exists($key))
                {
                    $table = $this->_bs->table($this->_db_prefix . 'ads');
                    $table->add_column()->tiny_int($this->_name_prefix . $key, 1);
                    $this->_bs->forge($this->_db);
                }
            }
        }

        //add child categories of selected categories
        if (is_array($categories)) {
            // get category siblings
            foreach ($categories as $category) {
                $category = new Model_Category($category);
                if (($siblings = $category->get_siblings_ids()) != NULL)
                    $categories = array_merge($categories, $siblings);
            }

            // remove duplicated categories
            $categories = array_unique($categories);
        }

        //save at config
        $fields[$name] = array(
            'type' => $fields[$name]['type'],
            'label' => $options['label'],
            'tooltip' => $options['tooltip'],
            'values' => $values,
            'categories' => $categories,
            'required' => $options['required'],
            'searchable' => $options['searchable'],
            'text_searchable'=> $options['text_searchable'],
            'admin_privilege' => $options['admin_privilege'],
            'show_listing' => $options['show_listing'],
            'grouped_values' => isset($fields[$name]['grouped_values']) ? $grouped_values : NULL
        );

        $config->config_value = json_encode($fields);
        $config->save();

        return TRUE;
    }

    /**
     * updates custom field option, not the name or the type
     * @param  string $name
     * @param  string $values
     * @param  array  $options
     * @return bool
     */
    public function update_translations($name, array $translations)
    {
        //save configs
        $config = new Model_Config();
        $config->where('group_name', '=', 'advertisement')
            ->where('config_key', '=', 'fields')
            ->limit(1)->find();

        if (!$config->loaded())
        {
            return FALSE;
        }

        $fields = json_decode($config->config_value, TRUE);

        if (!isset($fields[$name]))
        {
            return FALSE;
        }

        $field = $fields[$name];

        if (!$this->field_exists($name) AND $field['type'] != 'checkbox_group')
        {
            return FALSE;
        }

        foreach ($translations['values'] as $locale => $values) {
            if (!empty($values) AND
            !is_array($values) AND
            ($fields[$name]['type'] == 'select' OR $fields[$name]['type'] == 'radio')
            ) {
                $translations['values'][$locale] = array_map('trim', explode(',', $values));
            }
        }

        //save at config
        $fields[$name]['translations'] = json_encode($translations);

        $config->config_value = json_encode($fields);
        $config->save();

        return TRUE;
    }

    /**
     * deletes a fields from DB and config
     * @param  string $name
     * @return bool
     */
    public function delete($name)
    {
        //remove the keys from configs
        $config = (new Model_Config())
            ->where('group_name','=','advertisement')
            ->where('config_key','=','fields')
            ->limit(1)
            ->find();

        if (! $config->loaded())
        {
            return FALSE;
        }

        //remove the key
        $fields = json_decode($config->config_value, TRUE);

        if (! isset($fields[$name]))
        {
            return FALSE;
        }

        $field = $fields[$name];

        unset($fields[$name]);
        $config->config_value = json_encode($fields);
        $config->save();

        //remove all checkbox group columns
        if ($field['type'] == 'checkbox_group')
        {
            foreach ($field['grouped_values'] as $name => $value) {
                if (!$this->field_exists($name)) {
                    return FALSE;
                }

                $table = $this->_bs->table($this->_db_prefix . 'ads');
                $table->drop_column($this->_name_prefix . $name);
                $this->_bs->forge($this->_db);
            }

            return TRUE;
        }

        //remove column
        if (! $this->field_exists($name))
        {
            return FALSE;
        }

        $table = $this->_bs->table($this->_db_prefix.'ads');
        $table->drop_column($this->_name_prefix.$name);
        $this->_bs->forge($this->_db);

        return TRUE;
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

        if (!$conf->loaded())
        {
            return FALSE;
        }

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

    /**
     * get values for a field
     * @param  string $name
     * @return array/bool
     */
    public function get($name, $must_exist = TRUE)
    {
        if ($must_exist AND ! $this->field_exists($name))
        {
            return FALSE;
        }

        $fields = self::get_all();

        if (!isset($fields[$name]))
        {
            return FALSE;
        }

        return $fields[$name];
    }

    /**
     * get the custom fields for an ad
     * @return array/class
     */
    public static function get_all($as_array = TRUE)
    {
        if (is_null($fields = json_decode(core::config('advertisement.fields'), $as_array)))
        {
            return array();
        }

        // Pre-populate country select values
        if ($as_array === TRUE)
            foreach ($fields as $key => $field)
                if ($field['type'] == 'country')
                    $fields[$key]['values'] = EUVAT::countries();

        return $fields;
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
                {
                    $fields['cf_'.$field] = $values;
                    $fields['cf_'.$field]['translated_label'] = self::translate_label($values);
                    $fields['cf_'.$field]['translated_tooltip'] = self::translate_tooltip($values);
                    $fields['cf_'.$field]['translated_values'] = self::translate_values($values);
                }
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

    /**
     * list with fields we dont show to users
     * @return array
     */
    public static function fields_to_hide()
    {
        return array (
            'cf_buyer_instructions',
            'cf_paypalaccount',
            'cf_commentsdisabled',
            'cf_currency',
            'cf_bitcoinaddress',
        );
    }

    /**
     * returns a translation
     * @param string $field
     * @param string $key
     * @param string $locale
     * @return string
     */
    public static function get_translation($field, $key, $locale = '')
    {
        $locale = empty($locale) ? i18n::$locale : $locale;

        if ($locale == Core::config('i18n.locale'))
        {
            return $field[$key];
        }

        if (isset($field['translations']))
        {
            $field['translations'] = json_decode($field['translations']);
        }

        if (isset($field['translations']->$key->$locale))
        {
            return $field['translations']->$key->$locale;
        }

        return $field[$key];
    }

    /**
     * returns label translated
     * @param string $field
     * @param string $locale
     * @return string
     */
    public static function translate_label($field, $locale = '')
    {
        return self::get_translation($field, 'label', $locale);
    }

    /**
     * returns tooltip translated
     * @param string $field
     * @param string $locale
     * @return string
     */
    public static function translate_tooltip($field, $locale = '')
    {
        return self::get_translation($field, 'tooltip', $locale);
    }

    /**
     * returns values translated
     * @param string $field
     * @param string $locale
     * @return string
     */
    public static function translate_values($field, $locale = '')
    {
        return self::get_translation($field, 'values', $locale);
    }



}
