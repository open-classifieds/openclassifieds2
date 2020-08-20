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
class Model_Category extends ORM {


    /**
     * Table name to use
     *
     * @access  protected
     * @var     string  $_table_name default [singular model name]
     */
    protected $_table_name = 'categories';

    /**
     * Column to use as primary key
     *
     * @access  protected
     * @var     string  $_primary_key default [id]
     */
    protected $_primary_key = 'id_category';


    /**
     * @var  array  ORM Dependency/hirerachy
     */
    protected $_has_many = array(
        'ads' => array(
            'model'       => 'Ad',
            'foreign_key' => 'id_category',
        ),
    );

    protected $_belongs_to = array(
        'parent'   => array('model'       => 'Category',
                            'foreign_key' => 'id_category_parent'),
    );

    /**
     * global Model Category instance get from controller so we can access from anywhere like Model_Category::current()
     * @var Model_Location
     */
    protected static $_current = NULL;

    /**
     * returns the current category
     * @return Model_Category
     */
    public static function current()
    {
        //we don't have so let's retrieve
        if (self::$_current === NULL)
        {
            self::$_current = new self();
            if(Request::current()->param('category') != URL::title(__('all')))
            {
                self::$_current = self::$_current->where('seoname', '=', Request::current()->param('category'))
                                                    ->limit(1)->cached()->find();
            }
        }

        return self::$_current;
    }


    /**
     * creates a category by name
     * @param  string  $name
     * @param  integer $id_category_parent
     * @param  string  $description
     * @return Model_Category
     */
    public static function create_name($name,$order=0, $id_category_parent = 1, $parent_deep=0, $price=0, $description = NULL)
    {
        $cat = new self();
        $cat->where('name','=',$name)->where('id_category_parent','=',$id_category_parent)->limit(1)->find();

        //if doesnt exists create
        if (!$cat->loaded())
        {
            $cat->name        = $name;
            $cat->seoname     = $cat->gen_seoname($name);
            $cat->id_category_parent = $id_category_parent;
            $cat->order       = $order;
            $cat->parent_deep = $parent_deep;
            $cat->price       = $price;
            $cat->description = $description;

            try
            {
                $cat->save();
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

        return $cat;
    }


    /**
     * Rule definitions for validation
     *
     * @return array
     */
    public function rules()
    {
        return array('id_category'      => array(array('numeric')),
                    'name'              => array(array('not_empty'), array('max_length', array(':value', 145)), ),
                    'order'             => array(),
                    'id_category_parent'=> array(),
                    'parent_deep'       => array(),
                    'seoname'           => array(array('not_empty'), array('max_length', array(':value', 145)), ),
                    'description'       => array(),
                    'price'             => array(array('not_empty')),
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
        return array('id_category'          => __('Id'),
                    'name'                  => __('Name'),
                    'order'                 => __('Order'),
                    'created'               => __('Created'),
                    'id_category_parent'    => __('Parent'),
                    'parent_deep'           => __('Parent deep'),
                    'seoname'               => __('Seoname'),
                    'description'           => __('Description'),
                    'price'                 => __('Price'),
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
                'id_category_parent' => array(
                                array(array($this, 'check_parent'))
                              ),
        );
    }

    /**
     * we get the categories in an array
     * @return array
     */
    public static function get_as_array($limit = NULL)
    {
        $cache_name = 'cats_arr';

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
        //transform the cats to an array
        if ( ($cats_arr = Core::cache($cache_name))===NULL)
        {
            $cats = new self;
            $cats->order_by('id_category_parent','asc');
            $cats->order_by('order','asc');

            if (is_int($limit))
                $cats->limit($limit);

            $cats = $cats->find_all()->cached()->as_array('id_category');

            $cats_arr = array();
            foreach ($cats as $cat)
            {
                $cats_arr[$cat->id_category] =  array('name'               => $cat->name,
                                                      'translate_name'     => $cat->translate_name(),
                                                      'order'              => $cat->order,
                                                      'id_category_parent' => $cat->id_category_parent,
                                                      'parent_deep'        => $cat->parent_deep,
                                                      'seoname'            => $cat->seoname,
                                                      'price'              => $cat->price,
                                                      'id'                 => $cat->id_category,
                                                    );
            }
            Core::cache($cache_name, $cats_arr);
        }

        return $cats_arr;
    }

    /**
     * we get the categories in an array using as key the deep they are, perfect fro chained selects
     * @return array
     * @deprecated function DO NOT use, just here so we do not break the API to old themes
     */
    public static function get_by_deep()
    {
        $cache_name = 'cats_parent_deep';

        //cache by locale
        if (i18n::$locale != Core::config('i18n.locale'))
        {
          $cache_name = $cache_name . '_' . i18n::$locale;
        }

        self::cache_list($cache_name);
        // array by parent deep,
        // each parent deep is one array with categories of the same index
        if ( ($cats_parent_deep = Core::cache($cache_name))===NULL)
        {
            $cats = new self;
            $cats = $cats->order_by('order','asc')->find_all()->cached()->as_array('id_category');

            $cats_parent_deep = array();
            foreach ($cats as $cat)
            {
                $cats_parent_deep[$cat->parent_deep][$cat->id_category] =  array('name'                => $cat->name,
                                                                                  'translate_name'     => $cat->translate_name(),
                                                                                  'id_category_parent' => $cat->id_category_parent,
                                                                                  'parent_deep'        => $cat->parent_deep,
                                                                                  'seoname'            => $cat->seoname,
                                                                                  'price'              => $cat->price,
                                                                                  'id'                 => $cat->id_category,
                                                                        );
            }
            //sort by key, in case lover level is befor higher
            ksort($cats_parent_deep);
            Core::cache($cache_name,$cats_parent_deep);
        }

        return $cats_parent_deep;
    }


    /**
     * we get the categories in an array miltidimensional by deep.
     * @return array
     */
    public static function get_multidimensional($limit = NULL)
    {
        $cache_name = is_int($limit) ? 'cats_m'.'_'.$limit : 'cats_m';
        self::cache_list($cache_name);
        //multidimensional array
        if ( ($cats_m = Core::cache($cache_name))===NULL)
        {
            $cats = new self;
            $cats->order_by('id_category_parent','asc');
            $cats->order_by('order','asc');

            if (is_int($limit))
                $cats->limit($limit);

            $cats = $cats->find_all()->cached()->as_array('id_category');

            //for each category we get his siblings
            $cats_s = array();
            foreach ($cats as $cat)
                 $cats_s[$cat->id_category_parent][] = $cat->id_category;


            //last build multidimensional array
            if (core::count($cats_s)>1)
                $cats_m = self::multi_cats($cats_s);
            else
                $cats_m = array();
            Core::cache($cache_name,$cats_m);
        }

        return $cats_m;
    }

    /**
     * gets a multidimensional array wit the categories
     * @param  array  $cats_s      id_category->array(id_siblings)
     * @param  integer $id_category
     * @param  integer $deep
     * @return array
     */
    public static function multi_cats($cats_s,$id_category = 1, $deep = 0)
    {
        $ret = NULL;
        //we take all the siblings and try to set the grandsons...
        //we check that the id_category sibling has other siblings
        if (isset($cats_s[$id_category]))
        {
            foreach ($cats_s[$id_category] as $id_sibling)
            {
                //we check that the id_category sibling has other siblings
                if (isset($cats_s[$id_sibling]))
                {
                    if (is_array($cats_s[$id_sibling]))
                    {
                        $ret[$id_sibling] = self::multi_cats($cats_s,$id_sibling,$deep+1);
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
     * we get the categories in an array and a multidimensional array to know the deep @todo refactor this, is a mess
     * @deprecated function DO NOT use, just here so we do not break the API to old themes
     * @return array
     */
    public static function get_all()
    {
        //as array
        $cats_arr = self::get_as_array();

        //multidimensional array
        $cats_m = self::get_multidimensional();

        //array by deep
        $cats_parent_deep = self::get_by_deep();

        return array($cats_arr,$cats_m, $cats_parent_deep);
    }

    /**
     * counts how many ads have each category
     * @param  boolean $location_filter filters by location
     * @param  Model_Location $location
     * @return array
     */
    public static function get_category_count($location_filter = TRUE, $location = NULL)
    {
        //cache by location
        if ($location_filter === TRUE AND $location AND $location->loaded())
            $id_location = $location->id_location;
        elseif ($location_filter === TRUE AND Model_Location::current()->loaded())
            $id_location =  Model_Location::current()->id_location;
        else
            $id_location = 'all';

        //name used in the cache for storage
        $cache_name = 'get_category_count_'.$id_location;

        //cache by locale
        if (i18n::$locale != Core::config('i18n.locale'))
        {
          $cache_name = $cache_name . '_' . i18n::$locale;
        }

        self::cache_list($cache_name);
        if ( ($cats_count = Core::cache($cache_name))===NULL)
        {

            $expr_date = (is_numeric(core::config('advertisement.expire_date')))?core::config('advertisement.expire_date'):0;
            $db_prefix = Database::instance('default')->table_prefix();

            //get the categories that have ads id_category->num ads
            $count_ads = DB::select('c.id_category' , array(DB::expr('COUNT("a.id_ad")'),'count'))
                        ->from(array('categories', 'c'))
                        ->join(array('ads','a'))
                        ->using('id_category')
                        ->where('a.id_category','=',DB::expr($db_prefix.'c.id_category'))
                        ->where(DB::expr('IF('.$expr_date.' <> 0, DATE_ADD( published, INTERVAL '.$expr_date.' DAY), DATE_ADD( NOW(), INTERVAL 1 DAY))'), '>', Date::unix2mysql())
                        ->where('a.status','=',Model_Ad::STATUS_PUBLISHED);

            //filter the count by location
            if ($location_filter === TRUE AND $location AND $location->loaded() AND !Model_Location::current()->is_root())
                $count_ads = $count_ads->where('a.id_location', 'in', $location->get_siblings_ids());
            elseif ($location_filter === TRUE AND Model_Location::current()->loaded() AND !Model_Location::current()->is_root())
                $count_ads = $count_ads->where('a.id_location', 'in', Model_Location::current()->get_siblings_ids());

            // filter the count by language
            if (Core::config('general.multilingual') == 1)
            {
                $count_ads = $count_ads->where('a.locale', '=', i18n::$locale);
            }

            $count_ads = $count_ads->group_by('c.id_category')
                                   ->order_by('c.order','asc')
                                   ->cached()
                                   ->execute();

            $count_ads = $count_ads->as_array('id_category');

            //getting the count of ads into the parents
            $parents_count = array();
            foreach ($count_ads as $count_ad)
            {
                $id_category = $count_ad['id_category'];
                $count = $count_ad['count'];

                //adding himself if doesnt exists
                if (!isset($parents_count[$id_category]))
                {
                    $parents_count[$id_category]['count'] = $count;
                    $parents_count[$id_category]['has_siblings'] = FALSE;
                }
                else
                    $parents_count[$id_category]['count']+= $count;

                $category = new Model_Category($id_category);

                //for each parent of this category add the count
                $parents_ids = $category->get_parents_ids();
                if (core::count($parents_ids)>0)
                {
                    foreach ($parents_ids as $id )
                    {

                        if (isset($parents_count[$id]) AND isset($parents_count[$id]['count']) )
                            $parents_count[$id]['count']+= $count_ads[$category->id_category]['count'];
                        else
                            $parents_count[$id]['count'] = $count_ads[$category->id_category]['count'];

                        $parents_count[$id]['has_siblings'] = TRUE;
                    }
                }

            }

            //get all the categories with level 0 and 1
            $categories = new self();
            $categories = $categories->where('id_category','!=',1)->where('parent_deep','IN',array(0,1))->order_by('order','asc')->cached()->find_all();

            //generating the array
            $cats_count = array();
            foreach ($categories as $category)
            {
                $has_siblings = isset($parents_count[$category->id_category])?$parents_count[$category->id_category]['has_siblings']:FALSE;

                //they may not have counted the siblings since the count was 0 but he actually has siblings...
                if ($has_siblings===FALSE AND $category->has_siblings())
                    $has_siblings = TRUE;

                $cats_count[$category->id_category] = $category->as_array();

                $cats_count[$category->id_category] = array(   'id_category'   => $category->id_category,
                                                                'seoname'       => $category->seoname,
                                                                'name'          => $category->name,
                                                                'translate_name' => $category->translate_name(),
                                                                'description'          => $category->translate_description(),
                                                                'id_category_parent'        => $category->id_category_parent,
                                                                'parent_deep'   => $category->parent_deep,
                                                                'order'         => $category->order,
                                                                'price'         => $category->price,
                                                                'has_siblings'  => $has_siblings,
                                                                'count'         => isset($parents_count[$category->id_category])?$parents_count[$category->id_category]['count']:0,
                                                    );
            }

            //cache the result is expensive!
            Core::cache($cache_name,$cats_count);
        }

        return $cats_count;
    }

    /**
     * has this category siblings?
     * @return boolean             [description]
     */
    public function has_siblings($categories=NULL)
    {
        if ($this->loaded())
        {
            if ($categories===NULL)
            {
                $category = new self();
                $category->where('id_category_parent','=',$this->id_category)
                         ->where('id_category','!=',$this->id_category)
                         ->limit(1)
                         ->cached()->find();

                return $category->loaded();
            }
            else
            {

                //d($categories);
                foreach ($categories as $category)
                {
                    //d($category);
                    if ($category['id_category_parent'] == $this->id_category AND $category['id_category'] != $this->id_category)
                        return TRUE;
                }
            }

        }

        return FALSE;
    }

    /**
     * returns all the siblings ids+ the idcategory, used to filter the ads
     * @return array
     */
    public function get_siblings_ids()
    {
        if ($this->loaded())
        {
            //name used in the cache for storage
            $cache_name = 'get_siblings_ids_category_'.$this->id_category;
            self::cache_list($cache_name);
            if ( ($ids_siblings = Core::cache($cache_name))===NULL)
            {
                //array that contains all the siblings as keys (1,2,3,4,..)
                $ids_siblings = array();

                //we add himself as we use the clause IN on the where
                $ids_siblings[] = $this->id_category;

                $categories = new self();
                $categories = $categories->where('id_category_parent','=',$this->id_category)
                                        ->where('parent_deep','<',5)//we are limiting the recurrency....5 levels deep should be more than enough.
                                        ->cached()->find_all();

                foreach ($categories as $category)
                {
                    $ids_siblings[] = $category->id_category;

                    //adding his children recursevely if they have any
                    if ( core::count($siblings_cats = $category->get_siblings_ids())>1 )
                        $ids_siblings = array_merge($ids_siblings,$siblings_cats);
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
            $cache_name = 'get_parents_ids_category_'.$this->id_category;
            self::cache_list($cache_name);
            if ( ($ids_parents = Core::cache($cache_name))===NULL)
            {
                //array that contains all the parents as keys (1,2,3,4,..)
                $ids_parents = array();

                if ($this->id_category_parent!=1)
                {
                    //adding the parent only if loaded
                    if ($this->parent->loaded())
                    {
                        $ids_parents[] = $this->parent->id_category;
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
     *
     * formmanager definitions
     *
     */
    public function form_setup($form)
    {
        $form->fields['description']['display_as'] = 'textarea';

        $form->fields['id_category_parent']['display_as']   = 'select';
        $form->fields['id_category_parent']['caption']      = 'name';

        $form->fields['order']['display_as']   = 'select';
        $form->fields['order']['options']      = range(1, 100);

        $form->fields['price']['value']   = $this->price ?? 0;
    }


    public function exclude_fields()
    {
        return array('created','parent_deep','has_image','last_modified', 'translations');
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
                $seoname = __('category').'-'.$seoname;
        }

        $seoname = URL::title($seoname);

        //this are reserved categories names used in the routes.php
        $banned_names = array(__('category'),'category','blog','faq','forum','oc-panel','rss','oc-error',URL::title(__('all')),URL::title(__('reviews')),'user','stripe','paymill','bitpay','paypal');
        //same name as a route..shit!
        if (in_array($seoname, $banned_names))
            $seoname = URL::title(__('category')).'-'.$seoname;

        if ($seoname != $this->seoname)
        {
            $cat = new self;
            //find a user same seoname
            $s = $cat->where('seoname', '=', $seoname)->limit(1)->find();

            //found, increment the last digit of the seoname
            if ($s->loaded())
            {
                $cont = 2;
                $loop = TRUE;
                while($loop)
                {
                    $attempt = $seoname.'-'.$cont;
                    $cat = new self;
                    unset($s);
                    $s = $cat->where('seoname', '=', $attempt)->limit(1)->find();
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
     * rule to verify that we selected a parent if not put the root location
     * @param  integer $id_parent
     * @return integer
     */
    public function check_parent($id_parent)
    {
        return (is_numeric($id_parent))? $id_parent:1;
    }

    /**
     * returns the deep of parents of this category
     * @return integer
     */
    public function get_deep()
    {
        //initial deep
        $deep = 0;

        if ($this->loaded())
        {
            //getting all the cats as array
            $cats_arr = Model_Category::get_as_array();

            //getin the parent of this category
            $id_category_parent = $cats_arr[$this->id_category]['id_category_parent'];

            //counting till we find the begining
            while ($id_category_parent != 1 AND $id_category_parent != 0 AND $deep<100)
            {
                $id_category_parent = $cats_arr[$id_category_parent]['id_category_parent'];
                $deep++;
            }
        }

        return $deep;
    }

    /**
     * returns the url of the category icon
     * @return string url
     */
    public function get_icon()
    {
        if ($this->has_image) {
            if(core::config('image.aws_s3_active'))
            {
                $protocol = Core::is_HTTPS() ? 'https://' : 'http://';
                $version = $this->last_modified ? '?v='.Date::mysql2unix($this->last_modified) : NULL;

                return $protocol.core::config('image.aws_s3_domain').'images/categories/'.$this->seoname.'.png'.$version;
            }
            else
                return URL::base().'images/categories/'.$this->seoname.'.png'
                        .(($this->last_modified) ? '?v='.Date::mysql2unix($this->last_modified) : NULL);
        }

        return FALSE;
    }

    /**
     * Gets the icon font
     * @return string
     */
    public function get_icon_font()
    {
        if ($this->icon_font)
        {
            $attributes['class'] = $this->icon_font;

            return '<i'.HTML::attributes($attributes).'></i>';
        }

        return NULL;
    }

    /**
     * deletes the icon of the category
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

            $root = DOCROOT.'images/categories/'; //root folder

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
                    $s3->deleteObject(core::config('image.aws_s3_bucket'), 'images/categories/'.$this->seoname.'.png');

                // update category info
                $this->has_image = 0;
                $this->last_modified = Date::unix2mysql();
                $this->save();

            }
        }

        return TRUE;
    }

    /**
     * rename category icon
     * @param string $new_name
     * @return boolean
    */
    public function rename_icon($new_name)
    {
        if ( ! $this->_loaded)
            throw new Kohana_Exception('Cannot delete :model model because it is not loaded.', array(':model' => $this->_object_name));

        @rename('images/categories/'.$this->seoname.'.png', 'images/categories/'.$new_name.'.png');

        if (core::config('image.aws_s3_active'))
        {
            require_once Kohana::find_file('vendor', 'amazon-s3-php-class/S3','php');
            $s3 = new S3(core::config('image.aws_access_key'), core::config('image.aws_secret_key'));

            $s3->copyObject(core::config('image.aws_s3_bucket'), 'images/categories/'.$this->seoname.'.png', core::config('image.aws_s3_bucket'), 'images/categories/'.$new_name.'.png', S3::ACL_PUBLIC_READ);
            $s3->deleteObject(core::config('image.aws_s3_bucket'), 'images/categories/'.$this->seoname.'.png');
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
        DB::delete('subscribers')->where('id_category', '=',$this->id_category)->execute();

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
     * rule to verify that category loaded is a parent category
     * @return bool
     */
    public function is_parent()
    {
        if ($this->loaded())
        {
            return $this->id_category_parent == 1;
        }

        return FALSE;
    }

protected $_table_columns =
array (
  'id_category' =>
  array (
    'type' => 'int',
    'min' => '0',
    'max' => '4294967295',
    'column_name' => 'id_category',
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
    'character_maximum_length' => '145',
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
  'created' =>
  array (
    'type' => 'string',
    'column_name' => 'created',
    'column_default' => 'CURRENT_TIMESTAMP',
    'data_type' => 'timestamp',
    'is_nullable' => false,
    'ordinal_position' => 4,
    'comment' => '',
    'extra' => 'on update CURRENT_TIMESTAMP',
    'key' => '',
    'privileges' => 'select,insert,update,references',
  ),
  'id_category_parent' =>
  array (
    'type' => 'int',
    'min' => '0',
    'max' => '4294967295',
    'column_name' => 'id_category_parent',
    'column_default' => '0',
    'data_type' => 'int unsigned',
    'is_nullable' => false,
    'ordinal_position' => 5,
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
    'ordinal_position' => 6,
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
    'ordinal_position' => 7,
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
    'ordinal_position' => 8,
    'collation_name' => 'utf8_general_ci',
    'comment' => '',
    'extra' => '',
    'key' => '',
    'privileges' => 'select,insert,update,references',
  ),
  'icon_font' =>
  array (
    'type' => 'string',
    'column_name' => 'icon_font',
    'column_default' => NULL,
    'data_type' => 'varchar',
    'is_nullable' => false,
    'ordinal_position' => 7,
    'character_maximum_length' => '145',
    'collation_name' => 'utf8_general_ci',
    'comment' => '',
    'extra' => '',
    'key' => 'UNI',
    'privileges' => 'select,insert,update,references',
  ),
  'price' =>
  array (
    'type' => 'float',
    'exact' => true,
    'column_name' => 'price',
    'column_default' => '0.00',
    'data_type' => 'decimal',
    'is_nullable' => false,
    'ordinal_position' => 9,
    'numeric_precision' => '10',
    'numeric_scale' => '2',
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
    'ordinal_position' => 10,
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
    'ordinal_position' => 11,
    'display' => '1',
    'comment' => '',
    'extra' => '',
    'key' => '',
    'privileges' => 'select,insert,update,references',
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

} // END Model_Category
