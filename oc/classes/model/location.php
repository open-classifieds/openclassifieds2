<?php defined('SYSPATH') or die('No direct script access.');
/**
 * description...
 *
 * @author      Chema <chema@open-classifieds.com>
 * @package     OC
 * @copyright   (c) 2009-2013 Open Classifieds Team
 * @license     GPL v3
 * *
 */
class Model_Location extends ORM {

    /**
     * Table name to use
     *
     * @access  protected
     * @var     string  $_table_name default [singular model name]
     */
    protected $_table_name = 'locations';

    /**
     * Column to use as primary key
     *
     * @access  protected
     * @var     string  $_primary_key default [id]
     */
    protected $_primary_key = 'id_location';


    protected $_belongs_to = array(
        'parent'   => array('model'       => 'Location',
                            'foreign_key' => 'id_location_parent'),
    );


    /**
     * global Model Location instance get from controller so we can access from anywhere like Model_Location::current()
     * @var Model_Location
     */
    protected static $_current = NULL;

    /**
     * returns the current location
     * @return Model_Location
     */
    public static function current()
    {
        //we don't have so let's retrieve
        if (self::$_current === NULL)
        {
            self::$_current = new self();

            if (Model_Ad::current()!=NULL AND Model_Ad::current()->loaded() AND Model_Ad::current()->location->loaded())
            {
                self::$_current = Model_Ad::current()->location;
            }
            elseif(Request::current()->param('location') != NULL || Request::current()->param('location') != URL::title(__('all')))
            {
                self::$_current = self::$_current->where('seoname', '=', Request::current()->param('location'))
                                                    ->limit(1)->cached()->find();
            }
        }

        return self::$_current;
    }

    /**
     * creates a location by name
     * @param  string  $name
     * @param  integer $id_location_parent
     * @param  string  $description
     * @return Model_Location
     */
    public static function create_name($name,$order=0, $id_location_parent = 1, $parent_deep=0, $latitude=NULL, $longitude=NULL, $description = NULL)
    {
        $loc = new self();
        $loc->where('name','=',$name)->where('id_location_parent','=',$id_location_parent)->limit(1)->find();

        //if doesnt exists create
        if (!$loc->loaded())
        {
            $loc->name        = $name;
            $loc->seoname     = $loc->gen_seoname($name);
            $loc->id_location_parent = $id_location_parent;
            $loc->order       = $order;
            $loc->parent_deep = $parent_deep;
            $loc->latitude    = $latitude;
            $loc->longitude   = $longitude;
            $loc->description = $description;

            try
            {
                $loc->save();
            }
            catch (ORM_Validation_Exception $e)
            {
                throw HTTP_Exception::factory(500,$e->errors(''));
            }
            catch (Exception $e)
            {
                throw HTTP_Exception::factory(500,$e->getMessage());
            }
        }

        return $loc;
    }

    /**
     * Rule definitions for validation
     *
     * @return array
     */
    public function rules()
    {
        return array(
                        'id_location'       => array(array('numeric')),
                        'name'              => array(array('not_empty'), array('max_length', array(':value', 64)), ),
                        'id_location_parent'=> array(),
                        'parent_deep'       => array(),
                        'seoname'           => array(array('not_empty'), array('max_length', array(':value', 145)), ),
                        'description'       => array(),
                        'last_modified'     => array(),
                        'has_images'            => array(array('numeric')),
        );
    }

    /**
     * Label definitions for validation
     *
     * @return array
     */
    public function labels()
    {
        return  array(
            'id_location'           => 'Id',
            'name'                  => __('Name'),
            'id_location_parent'    => __('Parent'),
            'parent_deep'           => __('Parent deep'),
            'seoname'               => __('Seoname'),
            'description'           => __('Description'),
            'last_modified'         => __('Last modified'),
            'has_image'             => __('Has image'),
        );
    }

    /**
     * Filters to run when data is set in this model. The password filter
     * automatically hashes the password when it's set in the model.
     *
     * @return array Filters
     */
    public function filters()
    {
        return array(
                'seoname' => array(
                                array(array($this, 'gen_seoname'))
                              ),
                'id_location_parent' => array(
                                array(array($this, 'check_parent'))
                              ),
        );
    }


    /**
     * we get the locations in an array
     * @return array
     */
    public static function get_as_array($limit = NULL)
    {
        $cache_name = 'locs_arr';

        if (is_int($limit))
        {
          $cache_name = $cache_name . '_' . $limit;
        }

        //cache by locale
        if (i18n::$locale != Core::config('i18n.locale'))
        {
          $cache_name = $cache_name . '_' . i18n::$locale;
        }

        self::cache_list($cache_name);
        if ( ($locs_arr = Core::cache($cache_name))===NULL)
        {
            $locs = new self;
            $locs->order_by('id_location_parent','asc');
            $locs->order_by('order','asc');

            if (is_int($limit))
                $locs->limit($limit);

            $locs = $locs->find_all()->cached()->as_array('id_location');

            //transform the locs to an array
            $locs_arr = array();
            foreach ($locs as $loc)
            {
                $locs_arr[$loc->id_location] =  array('name'               => $loc->name,
                                                      'translate_name'     => $loc->translate_name(),
                                                      'order'              => $loc->order,
                                                      'id_location_parent' => $loc->id_location_parent,
                                                      'parent_deep'        => $loc->parent_deep,
                                                      'seoname'            => $loc->seoname,
                                                      'id'                 => $loc->id_location,
                                                    );
            }
            Core::cache($cache_name,$locs_arr);
        }

        return $locs_arr;
    }

    /**
     * we get the locations in an array using as key the deep they are, perfect fro chained selects
     * @return array
     * @deprecated function DO NOT use, just here so we do not break the API to old themes
     */
    public static function get_by_deep()
    {
        $cache_name = 'locs_parent_deep';

        //cache by locale
        if (i18n::$locale != Core::config('i18n.locale'))
        {
          $cache_name = $cache_name . '_' . i18n::$locale;
        }

        // array by parent deep,
        // each parent deep is one array with locations of the same index
        self::cache_list($cache_name);
        if ( ($locs_parent_deep = Core::cache($cache_name))===NULL)
        {
            $locs = new self;
            $locs = $locs->order_by('order','asc')->find_all()->cached()->as_array('id_location');

            // array by parent deep,
            // each parent deep is one array with locations of the same index
            $locs_parent_deep = array();
            foreach ($locs as $loc)
            {
                $locs_parent_deep[$loc->parent_deep][$loc->id_location] =  array('name'               => $loc->name,
                                                                                  'translate_name'     => $loc->translate_name(),
                                                                                  'id_location_parent' => $loc->id_location_parent,
                                                                                  'parent_deep'        => $loc->parent_deep,
                                                                                  'seoname'            => $loc->seoname,
                                                                                  'id'                 => $loc->id_location,
                                                                        );
            }
            //sort by key, in case lover level is befor higher
            ksort($locs_parent_deep);
            Core::cache($cache_name,$locs_parent_deep);
        }

        return $locs_parent_deep;
    }


    /**
     * we get the locations in an array miltidimensional by deep.
     * @return array
     */
    public static function get_multidimensional($limit = NULL)
    {
        $cache_name = is_int($limit) ? 'locs_m'.'_'.$limit : 'locs_m';
        self::cache_list($cache_name);
        if ( ($locs_m = Core::cache($cache_name))===NULL)
        {
            $locs = new self;
            $locs->order_by('id_location_parent','asc');
            $locs->order_by('order','asc');

            if (is_int($limit))
                $locs->limit($limit);

            $locs = $locs->find_all()->cached()->as_array('id_location');

            //for each location we get his siblings
            $locs_s = array();
            foreach ($locs as $loc)
                 $locs_s[$loc->id_location_parent][] = $loc->id_location;


            //last build multidimensional array
            if (core::count($locs_s)>1)
                $locs_m = self::multi_locs($locs_s);
            else
                $locs_m = array();
            Core::cache($cache_name, $locs_m);
        }
        return $locs_m;
    }

    /**
     * gets a multidimensional array wit the locations
     * @param  array  $locs_s      id_location->array(id_siblings)
     * @param  integer $id_location
     * @param  integer $deep
     * @return array
     */
    public static function multi_locs($locs_s,$id_location = 1, $deep = 0)
    {
        $ret = NULL;
        //we take all the siblings and try to set the grandsons...
        //we check that the id_location sibling has other siblings
        if (isset($locs_s[$id_location]))
        {
            foreach ($locs_s[$id_location] as $id_sibling)
            {
                //we check that the id_location sibling has other siblings
                if (isset($locs_s[$id_sibling]))
                {
                    if (is_array($locs_s[$id_sibling]))
                    {
                        $ret[$id_sibling] = self::multi_locs($locs_s,$id_sibling,$deep+1);
                    }
                }
                //no siblings we only set the key
                else
                    $ret[$id_sibling] = NULL;

            }
        }

        return $ret;
    }


    /**
     * we get the locations in an array and a multidimensional array to know the deep @todo refactor this, is a mess
     * @deprecated function DO NOT use, just here so we do not break the API to old themes
     * @return array
     */
    public static function get_all()
    {
        //as array
        $locs_arr = self::get_as_array();

        //multidimensional array
        $locs_m = self::get_multidimensional();

        //array by deep
        $locs_parent_deep = self::get_by_deep();

        return array($locs_arr,$locs_m, $locs_parent_deep);
    }


    /**
     * counts how many ads have each location
     * @return array
     */
    public static function get_location_count()
    {
        //name used in the cache for storage
        $cache_name = 'get_location_count';

        //cache by locale
        if (i18n::$locale != Core::config('i18n.locale'))
        {
          $cache_name = $cache_name . '_' . i18n::$locale;
        }

        self::cache_list($cache_name);
        if ( ($locs_count = Core::cache($cache_name))===NULL)
        {

            $expr_date = (is_numeric(core::config('advertisement.expire_date')))?core::config('advertisement.expire_date'):0;
            $db_prefix = Database::instance('default')->table_prefix();

            //get the locations that have ads id_location->num ads
            $count_ads = DB::select('l.id_location' , array(DB::expr('COUNT("a.id_ad")'),'count'))
                        ->from(array('locations', 'l'))
                        ->join(array('ads','a'))
                        ->using('id_location')
                        ->where(DB::expr('IF('.$expr_date.' <> 0, DATE_ADD( published, INTERVAL '.$expr_date.' DAY), DATE_ADD( NOW(), INTERVAL 1 DAY))'), '>', Date::unix2mysql())
                        ->where('a.status','=',Model_Ad::STATUS_PUBLISHED);

            // filter the count by language
            if (Core::config('general.multilingual') == 1)
            {
                $count_ads = $count_ads->where('a.locale', '=', i18n::$locale);
            }

            $count_ads = $count_ads->group_by('l.id_location')
                                   ->order_by('l.order','asc')
                                   ->cached()
                                   ->execute();

            $count_ads = $count_ads->as_array('id_location');

            //getting the count of ads into the parents
            $parents_count = array();
            foreach ($count_ads as $count_ad)
            {
                $id_location = $count_ad['id_location'];
                $count = $count_ad['count'];

                //adding himself if doesnt exists
                if (!isset($parents_count[$id_location]))
                {
                    $parents_count[$id_location]['count'] = $count;
                    $parents_count[$id_location]['has_siblings'] = FALSE;
                }
                else
                    $parents_count[$id_location]['count']+=$count;

                $location = new Model_Location($id_location);

                //for each parent of this location add the count
                $parents_ids = $location->get_parents_ids();

                if (core::count($parents_ids)>0)
                {
                    foreach ($parents_ids as $id )
                    {
                        if (isset($parents_count[$id]))
                            $parents_count[$id]['count']+= $count_ads[$location->id_location]['count'];
                        else
                            $parents_count[$id]['count'] = $count_ads[$location->id_location]['count'];

                        $parents_count[$id]['has_siblings'] = TRUE;
                    }
                }

            }

            //get all the locations with level 0 and 1
            $locations = new self();
            $locations = $locations->where('id_location','!=',1)->where('parent_deep','IN',array(0,1))->order_by('order','asc')->cached()->find_all();

            //generating the array
            $locs_count = array();
            foreach ($locations as $location)
            {
                $has_siblings = isset($parents_count[$location->id_location])?$parents_count[$location->id_location]['has_siblings']:FALSE;

                //they may not have counted the siblings since the count was 0 but he actually has siblings...
                if ($has_siblings===FALSE AND $location->has_siblings())
                    $has_siblings = TRUE;

                $locs_count[$location->id_location] = array(    'id_location'         => $location->id_location,
                                                                'seoname'             => $location->seoname,
                                                                'name'                => $location->name,
                                                                'translate_name'      => $location->translate_name(),
                                                                'id_location_parent'  => $location->id_location_parent,
                                                                'parent_deep'         => $location->parent_deep,
                                                                'order'               => $location->order,
                                                                'has_siblings'        => $has_siblings,
                                                                'count'               => isset($parents_count[$location->id_location])?$parents_count[$location->id_location]['count']:0,
                                                                );
                //counting the ads the parent have
            }

            //cache the result is expensive!
            Core::cache($cache_name,$locs_count);
        }

        return $locs_count;
    }


    /**
     * has this location siblings?
     * @return boolean             [description]
     */
    public function has_siblings($locations=NULL)
    {
        if ($this->loaded())
        {
            if ($locations===NULL)
            {
                $location = new self();
                $location->where('id_location_parent','=',$this->id_location)
                         ->where('id_location','!=',$this->id_location)
                         ->limit(1)
                         ->cached()->find();

                return $location->loaded();
            }
            else
            {
                foreach ($locations as $key=>$location)
                {
                    //d($location);
                    if ($location['id_location_parent'] == $this->id_location AND $key != $this->id_location)
                        return TRUE;
                }
            }

        }

        return FALSE;
    }

    public function form_setup($form)
    {
        $form->fields['description']['display_as'] = 'textarea';

        $form->fields['id_location_parent']['display_as']   = 'select';
        $form->fields['id_location_parent']['caption']      = 'name';

        $form->fields['order']['display_as']   = 'select';
        $form->fields['order']['options']      = range(1, 100);

        // $form->fields['id_location_parent']['display_as'] = 'hidden';
        // $form->fields['parent_deep']['display_as'] = 'hidden';
        // $form->fields['order']['display_as'] = 'hidden';
    }

    public function exclude_fields()
    {
      return array('created','parent_deep','has_image','last_modified', 'translations');
    }

     /**
     * returns all the siblings ids+ the idlocation, used to filter the ads
     * @return array
     */
    public function get_siblings_ids()
    {
        if ($this->loaded())
        {
            //name used in the cache for storage
            $cache_name = 'get_siblings_ids_lcoations_'.$this->id_location;
            self::cache_list($cache_name);
            if ( ($ids_siblings = Core::cache($cache_name))===NULL)
            {
                //array that contains all the siblings as keys (1,2,3,4,..)
                $ids_siblings = array();

                //we add himself as we use the clause IN on the where
                $ids_siblings[] = $this->id_location;

                $locations = new self();
                $locations = $locations->where('id_location_parent','=',$this->id_location)
                                        ->where('parent_deep','<',5)//we are limiting the recurrency....5 levels deep should be more than enough.
                                        ->cached()->find_all();

                foreach ($locations as $location)
                {
                    $ids_siblings[] = $location->id_location;

                    //adding his children recursevely if they have any
                    if ( core::count($siblings_locs = $location->get_siblings_ids())>1 )
                        $ids_siblings = array_merge($ids_siblings,$siblings_locs);
                }

                //removing repeated values
                $ids_siblings = array_unique($ids_siblings);

                //cache the result is expensive!
                Core::cache($cache_name,$ids_siblings);
            }

            return $ids_siblings;
        }

        //not loaded
        return NULL;
    }

    /**
     * returns all the parents ids, used to count ads
     * @return array
     */
    public function get_parents_ids()
    {
        if ($this->loaded())
        {
            //name used in the cache for storage
            $cache_name = 'get_parents_ids_location_'.$this->id_location;
            self::cache_list($cache_name);
            if ( ($ids_parents = Core::cache($cache_name))===NULL)
            {
                //array that contains all the parents as keys (1,2,3,4,..)
                $ids_parents = array();

                if ($this->id_location_parent!=1)
                {
                    //adding the parent only if loaded
                    if ($this->parent->loaded())
                    {
                        $ids_parents[] = $this->parent->id_location;
                        $ids_parents = array_merge($ids_parents,$this->parent->get_parents_ids()); //recursive
                    }
                    //removing repeated values
                    $ids_parents = array_unique($ids_parents);
                }

                //cache the result is expensive!
                Core::cache($cache_name,$ids_parents);
            }

            return $ids_parents;
        }

        //not loaded
        return NULL;
    }


    /**
     * return the title formatted for the URL
     *
     * @param  string $title
     *
     */
    public function gen_seoname($seoname)
    {
        //in case seoname is really small or null
        if (strlen($seoname)<3)
        {
            if (strlen($this->name)>=3)
                $seoname = $this->name;
            else
                $seoname = __('location').'-'.$seoname;
        }

        $seoname = URL::title($seoname);

        //this are reserved locations names used in the routes.php
        $banned_names = array('location',__('location'));
        //same name as a route..shit!
        if (in_array($seoname, $banned_names))
            $seoname = URL::title(__('location')).'-'.$seoname;

        if ($seoname != $this->seoname)
        {
            $loc = new self;
            //find a user same seoname
            $s = $loc->where('seoname', '=', $seoname)->limit(1)->find();

            //found, increment the last digit of the seoname
            if ($s->loaded())
            {
                $cont = 2;
                $loop = TRUE;
                while($loop)
                {
                    $attempt = $seoname.'-'.$cont;
                    $loc = new self;
                    unset($s);
                    $s = $loc->where('seoname', '=', $attempt)->limit(1)->find();
                    if(!$s->loaded())
                    {
                        $loop = FALSE;
                        $seoname = $attempt;
                    }
                    else
                  {
                        $cont++;
                    }
                }
            }
        }

        return $seoname;
    }

    /**
     * returns the deep of parents of this location
     * @return integer
     */
    public function get_deep()
    {
        //initial deep
        $deep = 0;

        if ($this->loaded())
        {
            //getting all the cats as array
            $locs_arr = Model_Location::get_as_array();

            //getin the parent of this location
            $id_location_parent = $locs_arr[$this->id_location]['id_location_parent'];

            //counting till we find the begining
            while ($id_location_parent != 1 AND $id_location_parent != 0 AND $deep<100)
            {
                $id_location_parent = $locs_arr[$id_location_parent]['id_location_parent'];
                $deep++;
            }
        }

        return $deep;
    }

    /**
     * rule to verify that we selected a parent if not put the root location
     * @param  integer $id_parent
     * @return integer
     */
    public function check_parent($id_parent)
    {
        return (is_numeric($id_parent))? $id_parent:1;
    }

    /**
     * returns the url of the location icon
     * @return string url
     */
    public function get_icon()
    {
        if ($this->has_image) {
            if(core::config('image.aws_s3_active'))
            {
                $protocol = Core::is_HTTPS() ? 'https://' : 'http://';
                $version = $this->last_modified ? '?v='.Date::mysql2unix($this->last_modified) : NULL;

                return $protocol.core::config('image.aws_s3_domain').'images/locations/'.$this->seoname.'.png'.$version;
            }
            else
                return URL::base().'images/locations/'.$this->seoname.'.png'
                        .(($this->last_modified) ? '?v='.Date::mysql2unix($this->last_modified) : NULL);
        }

        return FALSE;
    }

    /**
     * deletes the icon of the location
     * @return boolean
     */
    public function delete_icon()
    {
        if ( ! $this->_loaded)
            throw new Kohana_Exception('Cannot delete :model model because it is not loaded.', array(':model' => $this->_object_name));


        if ($this->has_image)
        {
            if (core::config('image.aws_s3_active'))
            {
                require_once Kohana::find_file('vendor', 'amazon-s3-php-class/S3','php');
                $s3 = new S3(core::config('image.aws_access_key'), core::config('image.aws_secret_key'));
            }

            $root = DOCROOT.'images/locations/'; //root folder

            if (!is_dir($root))
            {
                return FALSE;
            }
            else
            {
                //delete icon
                @unlink($root.$this->seoname.'.png');

                // delete icon from Amazon S3
                if(core::config('image.aws_s3_active'))
                    $s3->deleteObject(core::config('image.aws_s3_bucket'), 'images/locations/'.$this->seoname.'.png');

                // update location info
                $this->has_image = 0;
                $this->last_modified = Date::unix2mysql();
                $this->save();

            }
        }

        return TRUE;
    }

    /**
     * rename location icon
     * @param string $new_name
     * @return boolean
    */
    public function rename_icon($new_name)
    {
        if ( ! $this->_loaded)
            throw new Kohana_Exception('Cannot delete :model model because it is not loaded.', array(':model' => $this->_object_name));

        @rename('images/locations/'.$this->seoname.'.png', 'images/locations/'.$new_name.'.png');

        if (core::config('image.aws_s3_active'))
        {
            require_once Kohana::find_file('vendor', 'amazon-s3-php-class/S3','php');
            $s3 = new S3(core::config('image.aws_access_key'), core::config('image.aws_secret_key'));

            $s3->copyObject(core::config('image.aws_s3_bucket'), 'images/locations/'.$this->seoname.'.png', core::config('image.aws_s3_bucket'), 'images/locations/'.$new_name.'.png', S3::ACL_PUBLIC_READ);
            $s3->deleteObject(core::config('image.aws_s3_bucket'), 'images/locations/'.$this->seoname.'.png');
        }
    }

    /**
     * Deletes a single record while ignoring relationships.
     *
     * @chainable
     * @throws Kohana_Exception
     * @return ORM
     */
    public function delete()
    {
        if ( ! $this->_loaded)
            throw new Kohana_Exception('Cannot delete :model model because it is not loaded.', array(':model' => $this->_object_name));

        //remove image
        $this->delete_icon();

        //delete subscribtions
        DB::delete('subscribers')->where('id_location', '=',$this->id_location)->execute();

        parent::delete();
    }

    /**
     * returns a translation
     * @param string $key
     * @param string $locale
     * @return string
     */
    public function get_translation($key, $locale = '')
    {
        $locale = empty($locale) ? i18n::$locale : $locale;
        $translations = json_decode($this->translations);

        if ($locale == Core::config('i18n.locale'))
        {
            return $this->$key;
        }

        if (isset($translations->$key->$locale))
        {
            return $translations->$key->$locale;
        }

        return $this->$key;
    }

    /**
     * returns name translated
     * @param string $locale
     * @return string
     */
    public function translate_name($locale = '')
    {
        return $this->get_translation('name', $locale);
    }

    /**
     * returns description translated
     * @param string $locale
     * @return string
     */
    public function translate_description($locale = '')
    {
        return $this->get_translation('description', $locale);
    }

    /**
     * gets an array of valid import countries, stores file for 1 week
     * @return array
     */
    public static function valid_import_countries()
    {
        $file = APPPATH.'valid_import_countries.json';

        //return cached data if the file exists and is newer than 1 week
        if (file_exists($file) AND filemtime($file) > strtotime('-1 week'))
        {
            return json_decode(File::read($file), TRUE);
        }

        $data = Core::curl_get_contents('https://cdn.jsdelivr.net/gh/yclas/geo@master/countries.json', 5);

        if ($data)
        {
            File::write($file, $data);
        }

        return json_decode(File::read($file), TRUE);
    }

protected $_table_columns =
array (
  'id_location' =>
  array (
    'type' => 'int',
    'min' => '0',
    'max' => '4294967295',
    'column_name' => 'id_location',
    'column_default' => NULL,
    'data_type' => 'int unsigned',
    'is_nullable' => false,
    'ordinal_position' => 1,
    'display' => '10',
    'comment' => '',
    'extra' => 'auto_increment',
    'key' => 'PRI',
    'privileges' => 'select,insert,update,references',
  ),
  'name' =>
  array (
    'type' => 'string',
    'column_name' => 'name',
    'column_default' => NULL,
    'data_type' => 'varchar',
    'is_nullable' => false,
    'ordinal_position' => 2,
    'character_maximum_length' => '64',
    'collation_name' => 'utf8_general_ci',
    'comment' => '',
    'extra' => '',
    'key' => '',
    'privileges' => 'select,insert,update,references',
  ),
  'order' =>
  array (
    'type' => 'int',
    'min' => '0',
    'max' => '4294967295',
    'column_name' => 'order',
    'column_default' => '0',
    'data_type' => 'int unsigned',
    'is_nullable' => false,
    'ordinal_position' => 3,
    'display' => '2',
    'comment' => '',
    'extra' => '',
    'key' => '',
    'privileges' => 'select,insert,update,references',
  ),
  'id_location_parent' =>
  array (
    'type' => 'int',
    'min' => '0',
    'max' => '4294967295',
    'column_name' => 'id_location_parent',
    'column_default' => '0',
    'data_type' => 'int unsigned',
    'is_nullable' => false,
    'ordinal_position' => 4,
    'display' => '10',
    'comment' => '',
    'extra' => '',
    'key' => '',
    'privileges' => 'select,insert,update,references',
  ),
  'parent_deep' =>
  array (
    'type' => 'int',
    'min' => '0',
    'max' => '4294967295',
    'column_name' => 'parent_deep',
    'column_default' => '0',
    'data_type' => 'int unsigned',
    'is_nullable' => false,
    'ordinal_position' => 5,
    'display' => '2',
    'comment' => '',
    'extra' => '',
    'key' => '',
    'privileges' => 'select,insert,update,references',
  ),
  'seoname' =>
  array (
    'type' => 'string',
    'column_name' => 'seoname',
    'column_default' => NULL,
    'data_type' => 'varchar',
    'is_nullable' => false,
    'ordinal_position' => 6,
    'character_maximum_length' => '145',
    'collation_name' => 'utf8_general_ci',
    'comment' => '',
    'extra' => '',
    'key' => 'UNI',
    'privileges' => 'select,insert,update,references',
  ),
  'description' =>
  array (
    'type' => 'string',
    'character_maximum_length' => '65535',
    'column_name' => 'description',
    'column_default' => NULL,
    'data_type' => 'text',
    'is_nullable' => true,
    'ordinal_position' => 7,
    'collation_name' => 'utf8_general_ci',
    'comment' => '',
    'extra' => '',
    'key' => '',
    'privileges' => 'select,insert,update,references',
  ),
  'last_modified' =>
  array (
    'type' => 'string',
    'column_name' => 'last_modified',
    'column_default' => NULL,
    'data_type' => 'datetime',
    'is_nullable' => true,
    'ordinal_position' => 8,
    'comment' => '',
    'extra' => '',
    'key' => '',
    'privileges' => 'select,insert,update,references',
  ),
  'has_image' =>
  array (
    'type' => 'int',
    'min' => '-128',
    'max' => '127',
    'column_name' => 'has_image',
    'column_default' => '0',
    'data_type' => 'tinyint',
    'is_nullable' => false,
    'ordinal_position' => 9,
    'display' => '1',
    'comment' => '',
    'extra' => '',
    'key' => '',
    'privileges' => 'select,insert,update,references',
  ),
  'latitude' =>
  array (
    'type' => 'float',
    'column_name' => 'latitude',
    'column_default' => NULL,
    'data_type' => 'float',
    'is_nullable' => true,
    'ordinal_position' => 10,
    'numeric_precision' => '10',
    'numeric_scale' => '6',
    'comment' => '',
    'extra' => '',
    'key' => '',
    'privileges' => 'select,insert,update,references',
  ),
  'longitude' =>
  array (
    'type' => 'float',
    'column_name' => 'longitude',
    'column_default' => NULL,
    'data_type' => 'float',
    'is_nullable' => true,
    'ordinal_position' => 11,
    'numeric_precision' => '10',
    'numeric_scale' => '6',
    'comment' => '',
    'extra' => '',
    'key' => '',
    'privileges' => 'select,insert,update,references',
  ),
  'id_geoname' =>
  array (
    'type' => 'int',
    'min' => '0',
    'max' => '4294967295',
    'column_name' => 'id_geoname',
    'column_default' => NULL,
    'data_type' => 'int unsigned',
    'is_nullable' => true,
    'ordinal_position' => 12,
    'display' => '10',
    'comment' => '',
    'extra' => '',
    'key' => '',
    'privileges' => 'select,insert,references',
  ),
  'fcodename_geoname' =>
  array (
    'type' => 'string',
    'column_name' => 'fcodename_geoname',
    'column_default' => NULL,
    'data_type' => 'varchar',
    'is_nullable' => false,
    'ordinal_position' => 13,
    'character_maximum_length' => '145',
    'collation_name' => 'utf8_general_ci',
    'comment' => '',
    'extra' => '',
    'key' => '',
    'privileges' => 'select,insert,references',
  ),
  'translations' =>
  array (
    'type' => 'string',
    'character_maximum_length' => '65535',
    'column_name' => 'translations',
    'column_default' => NULL,
    'data_type' => 'text',
    'is_nullable' => true,
    'ordinal_position' => 8,
    'collation_name' => 'utf8_general_ci',
    'comment' => '',
    'extra' => '',
    'key' => '',
    'privileges' => 'select,insert,update,references',
  ),
);
} // END Model_Location
