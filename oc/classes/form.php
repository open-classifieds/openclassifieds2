<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Form helper class.
 * Modifies the Kohana_Form methods to force the addition of "form_" prefix to the "id" fields attributes.
 *
 * @package    OC
 * @category   Helpers
 * @author     Chema <chema@open-classifieds.com>
 * @copyright  (c) 2009-2013 Open Classifieds Team
 * @license    GPL v3
 */

class Form extends Kohana_Form {

    /**
     * @var  array  Internal list of errors
     */
    private static $_errors = array();

    /**
     * @var  string  Template for a single error message
     */
    public static $error_tpl       = '<span class="error">%s</span>';

    /**
     * @var  string  Template for a error message block
     */
    public static $errors_tpl      = '<div class="alert full error"><h2>%s</h2><ul>%s</ul></div>';

    /**
     * @var  string  Template for each item in a error message block
     */
    public static $errors_item_tpl = '<li>%s</li>';

    /**
     * Returns a formatted error for a field name (if it exists)
     * @param   string  $name Field name
     * @return  string  HTML formatted error
     */
    public static function error($name)
    {
        $out = NULL;
        if (isset(self::$_errors[$name]))
        {
            $out = sprintf(self::$error_tpl, self::$_errors[$name]);
        }
        else
        {
            // Searchs for the error in any inner error array
            if( core::count(self::$_errors) )
            {
                foreach(self::$_errors as $k=>$v)
                {
                    if(is_array($v)){
                        if (isset($v[$name]))
                        {
                            $out = sprintf(self::$error_tpl, $v[$name]);
                            break;
                        }
                    }
                }
            }
        }

        return $out;
    }

    /**
     * Returns a formatted error block for all errors
     * @param   array  $errors
     * @return  string  HTML formatted error
     */
    public static function errors($errors = NULL)
    {
        //Log::instance()->add(LOG_DEBUG, 'TM_Form::errors('.print_r($errors,1).')');
        $out = NULL;

        // Assigns the view errors to the Form Helper
        if ( ! core::count(self::$_errors) AND $errors !== NULL)
        {
            self::set_errors($errors);
        }

        // Searchs for the error in any inner error array
        if (self::$_errors)
        {
            if ( ! is_array(self::$_errors))
            {
                self::$_errors = array(self::$_errors);
            }

            foreach (self::$_errors as $k=>$v)
            {
                if (is_array($v))
                {
                    foreach ($v as $k2=>$v2)
                    {
                        $out .= sprintf(self::$errors_item_tpl, $v2);
                    }
                }
                else
                {
                    $out .= sprintf(self::$errors_item_tpl, $v);
                }
            }
        }


        if (strlen($out))
        {
            $out = sprintf(self::$errors_tpl, __('Some errors in the form'),$out);
        }

        return $out;
    }

    /**
     * Assigns an error array to a static local reference
     */
    public static function set_errors($array)
    {
        self::$_errors = $array;
    }

    /**
     *
     * Creates a hidden input for the CSRF prevention
     * @param string $namespace
     * @return string
     */
    public static function CSRF($namespace=NULL)
    {
        if ($namespace===NULL)
            $namespace = URL::title(Request::current()->uri());

        return CSRF::form($namespace);
    }

    /**
     * Generates the redirect form input
     * @uses    Form
     * @param   string  url to redirect optional
     * @return  string  generated HTML
     */
    public static function redirect($url = NULL)
    {
        if ($url == NULL)
            $url = Core::request('auth_redirect',URL::current());

        if (Request::current()->controller()=='auth')
            $url = Request::current()->referrer();

        //if (Session::instance()->get('auth_redirect')==NULL)
            Session::instance()->set('auth_redirect', $url);

        return Form::hidden('auth_redirect',$url);
    }

    /**
     * Generates a token to avoid duplicates, if second parameter set says if its validated.
     * @uses    Form
     * @param   string  $namespace
     * @return  string  generated HTML
     */
    public static function token($namespace='default', $validate = FALSE)
    {
        //generate form tag
        if ($validate===FALSE)
        {
            $token = Text::random('alnum', rand(20, 30));
            Session::instance()->set('form_token_'.$namespace, $token);
            return Form::hidden('form_token_'.$namespace, $token);
        }

        //validate!
        return (Session::instance()->get('form_token_'.$namespace) == core::post('form_token_'.$namespace));
    }


    /**
     * Returns the html tag code for a field
     * @param  string $name input name
     * @param  array  $options as defined
     * @param  mixed $value value of the field, optional.
     * @return string        HTML of the tag
     */
    public static function form_tag($name, $options, $value = NULL)
    {
        if ($options['display'] != 'hidden')
            $label = FORM::label($name, (isset($options['label']))?$options['label']:$name, array('class'=>'block text-sm font-medium leading-5 text-gray-700', 'for'=>$name));
        else
            $label = '';

        //$out = '';
        if ($value === NULL)
            $value = (isset($options['default'])) ? $options['default']:NULL;


        $attributes = array('placeholder' => (isset($options['label'])) ? $options['label']:$name,
                            'class'       => 'mt-1 rounded-md shadow-sm form-input block w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5',
                            'id'          => $name,
                            (isset($options['required']) AND $options['required'])?'required':''
                    );

        switch ($options['display'])
        {
            case 'select':
                $input = FORM::select($name, $options['options'], $value,  ['class' => 'mt-1 rounded-md shadow-sm form-select block w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5'] + $attributes);
                break;
            case 'textarea':
                $input = FORM::textarea($name, $value, ['class' => 'mt-1 rounded-md shadow-sm form-textarea block w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5'] + $attributes);
                break;
            case 'hidden':
                $input = FORM::hidden($name, $value, $attributes);
                break;
            case 'logo':
                $input = FORM::file($name, $attributes);
                if (!empty($value))
                {
                    $input.= HTML::image($value, array('class' => 'img-responsive thumbnail'));
                    $input.= Form::button('delete_'.$name, __('Delete'), array('type' => 'submit', 'value' => $value));
                }
                break;
            case 'color':
                $attributes['class'] = 'mt-1 rounded-md shadow-sm form-input block w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5 color {hash:true, required:false}';
                $input = FORM::input($name, $value, $attributes);
                break;
            case 'text':
            default:
                $input = FORM::input($name, $value, $attributes);
                break;
        }

        $out = $label.$input;

        return $out;

    }

    /**
     * get the html tag code for a field for a custom field
     * @param  string $name input name
     * @param  array  $options as defined
     * @param  mixed $value value of the field, optional.
     * @param  boolean  $old =TRUE renders bs2 styles
     * @param  boolean  $inline renders inline form
     * @return string        HTML
     */
    public static function cf_form_tag($name, $options, $value = NULL, $old = FALSE, $inline = FALSE)
    {
        if ($options['display'] != 'hidden')
            $label = FORM::label($name, (isset($options['label']))?$options['label']:$name, array('class'=>(!$old)?'control-label':'', 'for'=>$name));
        else
            $label = '';

        if ($value === NULL)
            $value = (isset($options['default'])) ? $options['default']:NULL;

        // dependent classes on type
        $class = 'form-control '.'cf_'.$options['display'].'_fields data-custom ';
        switch ($options['display']) {
            case 'textarea':
                $class .= 'span6';
                break;
            case 'checkbox':
                $class = 'cf_'.$options['display'].'_fields data-custom';
                break;
            case 'radio':
                $class = 'cf_'.$options['display'].'fields data-custom';
                $required = (isset($options['required']) AND $options['required']== TRUE)?'required':NULL;
                $data_categories = (isset($options['categories'])) ? json_encode($options['categories']):NULL;
                $title = (isset($options['tooltip'])) ? $options['tooltip']:NULL;
                break;
            default:
                $class .= " ";
                break;
        }
        $attributes = array('placeholder'       => (isset($options['placeholder'])) ? $options['placeholder'] : ((isset($options['label'])) ? $options['label'] : $name),
                            'data-placeholder'       => (isset($options['data-placeholder'])) ? $options['data-placeholder'] : ((isset($options['label'])) ? $options['label'] : $name),
                            'title'             => (isset($options['tooltip'])) ? $options['tooltip']:NULL,
                            'data-categories'   => (isset($options['categories'])) ? json_encode($options['categories']):NULL,
                            'class'             => $class,
                            'id'                => $name,
                            (isset($options['required']) AND $options['required']== TRUE)?'required':NULL
                    );

        switch ($options['display'])
        {
            case 'select':
                $input = FORM::select($name, $options['options'], (!is_array($value))?$value:NULL, $attributes);
                break;
            case 'text':
                $input = FORM::input($name, $value, $attributes);
                break;
            case 'textarea':
                $input = FORM::textarea($name, $value, $attributes);
                break;
            case 'hidden':
                $input = FORM::hidden($name, $value, $attributes);
                break;
            case 'date':
                $attributes['data-date'] = "data-date";
                $attributes['data-date-format'] = "yyyy-mm-dd";

                $input = FORM::input($name, $value, $attributes);
                break;
            case 'checkbox':
                $checked = in_array($value, ['1', 'on']); // value can be 1 or 'on'

                $input = Form::hidden($name, 0).'<div class="checkbox"><label>'.FORM::checkbox($name, 1, $checked, $attributes).'</label></div>';

                break;
            case 'radio':
                $input = '';
                $label = '<b>'.$options['label'].'</b>';
                $index = 0;

                foreach($options['options'] as $id => $radio_name)
                {
                    $checked = ($value == $index) ? TRUE : FALSE ;
                    if($id !== "")
                        $input .= '<div class="radio"><label>'.Form::radio($name, $index, $checked, $attributes).$radio_name.'</label></div>';

                    $index++;
                }
                break;
            case 'email':
                $attributes['type'] = 'email';
                $input = FORM::input($name, $value, $attributes);
                break;
            case 'string':
            default:
                $input = FORM::input($name, $value, $attributes);
                break;
        }

        if(!$old){
            (!$inline)?$is_inline = "class='col-md-5 col-sm-8 col-xs-11'":$is_inline = "";
            $out = '<div '.$is_inline.'>'.$label.'<div class="control mr-30">'.$input.'</div></div>';
        }
        else{
            $out = $label.'<div>'.$input.'</div>';
        }

        return $out;
    }

    /**
     * get field html code for a custom field
     * @param  string $name input name
     * @param  array  $options as defined
     * @param  mixed $value value of the field, optional.
     * @return string        HTML
     */
    public static function cf_form_field($name, $options, $value = NULL)
    {
        if ($value === NULL)
            $value = (isset($options['default'])) ? $options['default']:NULL;

        // dependent classes on type
        $class = 'form-control '.'cf_'.$options['display'].'_fields data-custom ';

        switch ($options['display']) {
            case 'checkbox':
                $class = 'cf_'.$options['display'].'_fields data-custom';
                break;
            case 'radio':
                $class = 'cf_'.$options['display'].'fields data-custom';
                $required = (isset($options['required']) AND $options['required']== TRUE)?'required':NULL;
                $data_categories = (isset($options['categories'])) ? json_encode($options['categories']):NULL;
                $title = (isset($options['tooltip'])) ? $options['tooltip']:NULL;
                break;
            default:
                $class .= " ";
                break;
        }
        $attributes = array('placeholder'       => (isset($options['placeholder'])) ? $options['placeholder'] : ((isset($options['label'])) ? $options['label'] : $name),
                            'data-placeholder'  => (isset($options['data-placeholder'])) ? $options['data-placeholder'] : ((isset($options['label'])) ? $options['label'] : $name),
                            'title'             => (isset($options['tooltip'])) ? $options['tooltip']:NULL,
                            'data-categories'   => (isset($options['categories'])) ? json_encode($options['categories']):NULL,
                            'class'             => $class,
                            'id'                => $name,
                            (isset($options['required']) AND $options['required']== TRUE)?'required':NULL
                    );

        switch ($options['display'])
        {
            case 'select':
                $input = FORM::select($name, $options['options'], (!is_array($value))?$value:NULL, $attributes);
                break;
            case 'text':
                $input = FORM::input($name, $value, $attributes);
                break;
            case 'textarea':
                $input = FORM::textarea($name, $value, $attributes);
                break;
            case 'hidden':
                $input = FORM::hidden($name, $value, $attributes);
                break;
            case 'date':
                $attributes['data-date'] = "data-date";
                $attributes['data-date-format'] = "yyyy-mm-dd";

                $input = FORM::input($name, $value, $attributes);
                break;
            case 'checkbox':
                $checked = isset($value); // value can be 1 or 'on'

                $input = '<div class="checkbox"><label>'.FORM::checkbox($name, NULL, $checked, $attributes).'</label></div>';

                break;
            case 'radio':
                $input = '';
                $index = 0;

                foreach($options['options'] as $id => $radio_name)
                {
                    $checked = ($value == $index) ? TRUE : FALSE ;
                    if($id !== "")
                        $input .= '<div class="radio"><label>'.Form::radio($name, $index, $checked, $attributes).' '.$radio_name.'</label></div>';

                    $index++;
                }
                break;
            case 'email':
                $attributes['type'] = 'email';
                $input = FORM::input($name, $value, $attributes);
                break;
            case 'country':
                $input = FORM::select($name, EUVAT::countries(), (! is_array($value)) ? $value : NULL, $attributes);
                break;
            case 'file':
                $input = FORM::hidden($name, $value, $attributes);
                break;
            case 'string':
            default:
                $input = FORM::input($name, $value, $attributes);
                break;
        }

        return $input;
    }

} // End OC_Form
