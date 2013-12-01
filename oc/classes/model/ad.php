<?php defined('SYSPATH') or die('No direct script access.');
/**
 * description...
 *
 * @author		Chema <chema@garridodiaz.com>
 * @package		OC
 * @copyright	(c) 2009-2013 Open Classifieds Team
 * @license		GPL v3
 * 
 */
class Model_Ad extends ORM {

    /**
     * Table name to use
     *
     * @access	protected
     * @var		string	$_table_name default [singular model name]
     */
    protected $_table_name = 'ads';

    /**
     * Column to use as primary key
     *
     * @access	protected
     * @var		string	$_primary_key default [id_ad]
     */
    protected $_primary_key = 'id_ad';

    protected $_belongs_to = array(
        'user'		 => array('foreign_key' => 'id_user'),
        'category'	 => array('foreign_key' => 'id_category'),
    	'location'	 => array('foreign_key' => 'id_location'),
    );

    /**
     * status constants
     */
    const STATUS_NOPUBLISHED = 0; //first status of the item, not published. This status send ad to moderation always. Until it gets his status changed 
    const STATUS_PUBLISHED   = 1; // ad it's available and published
    const STATUS_UNCONFIRMED = 20; // this status is for advertisements that need to be confirmed by email,
    const STATUS_SPAM        = 30; // mark as spam
    const STATUS_UNAVAILABLE = 50; // item unavailable but previously was
    

    /**
     * moderation status
     */
    const POST_DIRECTLY         = 0; // create new ad directly 
    const MODERATION_ON         = 1; // new ad after creation goes to moderation
    const PAYMENT_ON            = 2; // redirects to payment and after paying there is no moderation
    const EMAIL_CONFIRMATION    = 3; // sends email to confirm ad, until then is in moderation 
    const EMAIL_MODERATION      = 4; // sends email to confirm, but admin needs also to validate
    const PAYMENT_MODERATION    = 5; // even after payment, admin still needs to validate
    
    /**
     * Rule definitions for validation
     *
     * @return array
     */
    public function rules()
    {
    	return array(
				        'id_ad'		=> array(array('numeric')),
				        'id_user'		=> array(array('numeric')),
				        'id_category'	=> array(array('numeric')),
				        'id_location'	=> array(),
				        'type'			=> array(),
				        'title'			=> array(array('not_empty'), array('max_length', array(':value', 145)), ),
				        'seotitle'		=> array(array('not_empty'), array('max_length', array(':value', 145)), ),
				        'description'	=> array(array('not_empty'), array('max_length', array(':value', 65535)), ),
				        'address'		=> array(array('max_length', array(':value', 145)), ),
				        'phone'			=> array(array('max_length', array(':value', 30)), ),
				        'status'		=> array(array('numeric')),
				        'has_images'	=> array(array('numeric')),
				    );
    }

    /**
     * Label definitions for validation
     *
     * @return array
     */
    public function labels()
    {
    	return array(
			        'id_ad'		=> 'Id ad',
			        'id_user'		=> __('User'),
			        'id_category'	=> __('Category'),
			        'id_location'	=> __('Location'),
			        'type'			=> __('Type'),
			        'title'			=> __('Title'),
			        'seotitle'		=> __('SEO title'),
			        'description'	=> __('Description'),
			        'address'		=> __('Address'),
			        'price'			=> __('Price'),
			        'phone'			=> __('Phone'),
			        'ip_address'	=> __('Ip address'),
			        'created'		=> __('Created'),
			        'published'		=> __('Published'),
			        'status'		=> __('Status'),
			        'has_images'	=> __('Has images'),
			    );
    }
    
    /**
     * 
     * formmanager definitions
     * @param $form
     * @return $insert
     */
    public function form_setup($form)
    {
        $insert = DB::insert('ads', array('title', 'description'))
                            ->values(array($form['title'], $form['description']))
                            ->execute();
                            return $insert;
    }


    /**
     * generate seo title. return the title formatted for the URL
     *
     * @param string title
     * @return $seotitle (unique string)  
     */
    
    public function gen_seo_title($title)
    {

        $ad = new self;

        $title = URL::title($title);
        $seotitle = $title;

        //find a ad same seotitle
        $a = $ad->where('seotitle', '=', $seotitle)->and_where('id_ad', '!=', $this->id_ad)->limit(1)->find();
        
        if($a->loaded())
        {
            $cont = 1;
            $loop = TRUE;
            do {
                $attempt = $title.'-'.$cont;
                $ad = new self;
                unset($a);
                $a = $ad->where('seotitle', '=', $attempt)->limit(1)->find();

                if(!$a->loaded())
                {
                    $loop = FALSE;
                    $seotitle = $attempt;
                }
                else $cont++;
            } while ( $loop );
        }

        return $seotitle;
    }

   


    /**
     *
     *  Create single table for each advertisement hit
     *  
     *  @param $id_ad (model_ad), $id_user(model_user) 
     */
    public function count_ad_hit($id_ad, $visitor_id, $ip_address){
        
        //inser new table, as a hit
        $new_hit = DB::insert('visits', array('id_ad', 'id_user', 'ip_address'))
                                ->values(array($id_ad, $visitor_id, $ip_address))
                                ->execute();

    }
    /**
     * Gets all images
     * @return [array] [array with image names]
     */
    public function get_images()
    {
        $image_path = array();
       
        if($this->loaded())
        {  
            $route = $this->gen_img_path($this->id_ad, $this->created);
            $folder = DOCROOT.$route;

            if(is_dir($folder))
            { 
                foreach (new DirectoryIterator($folder) as $file) 
                {   

                    if(!$file->isDot())
                    {   

                        $key = explode('_', $file->getFilename());
                        $key = end($key);
                        $key = explode('.', $key);
                        $key = (isset($key[0])) ? $key[0] : NULL ;

                        if(is_numeric($key))
                        {
                            $type = (strpos($file->getFilename(), 'thumb_') === 0) ? 'thumb' : 'image' ;
                            $image_path[$key][$type] = $route.$file->getFilename();
                        }
                    }
                }
            }
        }

        return $image_path;
    }

    /**
     * Gets the first image, and checks type of $type
     * @param  string $type [type of image (image or thumb) ]
     * @return string       [image path]
     */
    public function get_first_image($type = 'thumb')
    {
      
        $images = $this->get_images();
        sort($images);
        if(count($images) >= 1)
        {
            $first_image = reset($images);
        }

        return (isset($first_image[$type])) ? $first_image[$type] : NULL ;
    }

    /**
     * [gen_img_path] Generate image path with a given parameters $seotitle and 
     * date of advertisement creation 
     * @param  [string] $id         [id of advert ]
     * @param  [date]   $created     [date of creation]
     * @return [string]             [directory path]
     */
    public function gen_img_path($id, $created)
    { 
        
        $obj_date = date_parse($created); // convert date to array 
        
            $year = $obj_date['year']; // take last 2 integers 
        
        // check for length, because original path is with 2 integers 
        if(strlen($obj_date['month']) != 2)
            $month = '0'.$obj_date['month'];
        else
            $month = $obj_date['month'];
        
        if(strlen($obj_date['day']) != 2)
            $day = '0'.$obj_date['day'];
        else
            $day = $obj_date['day'];

        $directory = 'images/'.$year.'/'.$month.'/'.$day.'/'.$id.'/';
       
        return $directory;
    }

    /**
     * save_image upload images with given path
     * 
     * @param  [array]  $image      [image $_FILE-s ]
     * @param  [string] $seotitle   [unique id, and folder name]
     * @return [bool]               [return true if 1 or more images uploaded, false otherwise]
     */
    public function save_image($image, $id, $created, $seotitle)
    {
        if ( 
        ! Upload::valid($image) OR
        ! Upload::not_empty($image) OR
        ! Upload::type($image, explode(',',core::config('image.allowed_formats'))) OR
        ! Upload::size($image, core::config('image.max_image_size').'M'))
        {
            if (Upload::not_empty($image) && ! Upload::type($image, explode(',',core::config('image.allowed_formats'))))
                Alert::set(Alert::ALERT, $image['name'].' '.__('Is not valid format, please use one of this formats "'.core::config('image.allowed_formats').'"'));
            if(!Upload::size($image, core::config('image.max_image_size').'M'))
                Alert::set(Alert::ALERT, $image['name'].' '.__('Is not of valid size. Size is limited on '.core::config('image.max_image_size').'MB per image'));
            if(!Upload::not_empty($image))
                return;
        }
          
        if ($image !== NULL)
        {
            $path           = $this->image_path($id , $created);
            $directory      = DOCROOT.$path;
            $image_quality  = core::config('image.quality');
            $width          = core::config('image.width');
            $width_thumb    = core::config('image.width_thumb');
            $height_thumb   = core::config('image.height_thumb');
            $height         = core::config('image.height');

            if(!is_numeric($height)) // when installing this field is empty, to avoid crash we check here
                $height         = NULL;
            if(!is_numeric($height_thumb))
                $height_thumb   = NULL;    
            
            // count how many files are saved 
            if (glob($directory . "*.jpg") != false)
            {
                $filecount = count(glob($directory . "*.jpg"));

                $counter = ($filecount / 2) + 1;
                
                if(file_exists($directory.$seotitle.'_'.$counter.'.jpg')) // in case we update image, we have to find available number to replace
                {
                    for($i=1; $i<=core::config('advertisement.num_images'); $i++)
                    {
                        $counter = $i;
                        if(!file_exists($directory.$seotitle.'_'.$counter.'.jpg'))
                        {
                            break;
                        }
                    }
                }
            }
            else
                $counter = 1;
            
            if ($file = Upload::save($image, NULL, $directory))
            {
                $filename_thumb     = 'thumb_'.$seotitle.'_'.$counter.'.jpg';
                $filename_original  = $seotitle.'_'.$counter.'.jpg';
                 
                /*WATERMARK*/
                if(core::config('image.watermark'))
                {
                    $mark = Image::factory(core::config('image.watermark_path')); // watermark image object
                    $size_watermark = getimagesize(core::config('image.watermark_path')); // size of watermark
                  
                    if(core::config('image.watermark_position') == 0) // position center
                    {
                        $wm_left_x = $width/2-$size_watermark[0]/2; // x axis , from left
                        $wm_top_y = $height/2-$size_watermark[1]/2; // y axis , from top
                    }
                    elseif (core::config('image.watermark_position') == 1) // position bottom
                    {
                        $wm_left_x = $width/2-$size_watermark[0]/2; // x axis , from left
                        $wm_top_y = $height-10; // y axis , from top
                    }
                    elseif(core::config('image.watermark_position') == 2) // position top
                    {
                        $wm_left_x = $width/2-$size_watermark[0]/2; // x axis , from left
                        $wm_top_y = 10; // y axis , from top
                    }
                }   
                /*end WATERMARK variables*/

                //if original image is bigger that our constants we resize
                $image_size_orig    = getimagesize($file);
                
                    if($image_size_orig[0] > $width || $image_size_orig[1] > $height)
                    {
                        if(core::config('image.watermark')) // watermark ON
                            Image::factory($file)
                                ->resize($width, $height, Image::AUTO)
                                ->watermark( $mark, $wm_left_x, $wm_top_y) // CUSTOM FUNCTION (kohana)
                                ->save($directory.$filename_original,$image_quality); 
                        else 
                            Image::factory($file)
                                ->resize($width, $height, Image::AUTO)
                                ->save($directory.$filename_original,$image_quality);    
                    }
                    //we just save the image changing the quality and different name
                    else
                    {
                        if(core::config('image.watermark'))
                            Image::factory($file)
                                ->watermark( $mark, $wm_left_x, $wm_top_y) // CUSTOM FUNCTION (kohana)
                                ->save($directory.$filename_original,$image_quality);
                        else
                            Image::factory($file)
                                ->save($directory.$filename_original,$image_quality); 
                    }
                

                //creating the thumb and resizing using the the biggest side INVERSE
                Image::factory($directory.$filename_original)
                    ->resize($width_thumb, $height_thumb, Image::INVERSE)
                    ->save($directory.$filename_thumb,$image_quality);

                //check if the height or width of the thumb is bigger than default then crop
                if ($height_thumb!==NULL)
                {
                    $image_size_orig = getimagesize($directory.$filename_thumb);
                    if ($image_size_orig[1] > $height_thumb || $image_size_orig[0] > $width_thumb)
                    Image::factory($directory.$filename_thumb)
                                ->crop($width_thumb, $height_thumb)
                                ->save($directory.$filename_thumb); 
                }
                // Delete the temporary file
                unlink($file);
                return TRUE;
            }
            else 
            {
                Alert::set(Alert::ALERT, __('Something went wrong with uploading pictures, please check format'));
                return FALSE;
            }
        }   
    }

    /**
     * image_path make unique dir path with a given date and id
     * 
     * @param  [int] $id   [unique id, and folder name]
     * @return [string]             [directory path]
     */
    public function image_path($id, $created)
    { 
        if ($created !== NULL)
        {
            $obj_ad = new Model_Ad();
            $path = $obj_ad->gen_img_path($id, $created);
        }
        else
        {
            $date = Date::format(time(), 'Y/m/d');

            $parse_data = explode("/", $date);          // make array with date values
        
            $path = "images/"; // root upload folder

            for ($i=0; $i < count($parse_data); $i++) 
            { 
                $path .= $parse_data[$i].'/';           // append, to create path 
                
            }
                $path .= $id.'/';
        }
        
        

        if(!is_dir($path)){         // check if path exists 
                mkdir($path, 0755, TRUE);
            }

        return $path;
    }

    public function delete_images($img_path)
    {
        // Loop through the folder
        $dir = dir($img_path);

        while (false !== $entry = $dir->read()) {
        // Skip pointers
          if ($entry == '.' || $entry == '..') {
            continue;
          }
          unlink($img_path.$entry);
        }
        
        rmdir($img_path);
        return TRUE;
    }

    /**
     * tells us if this ad can be contacted
     * @return bool 
     */
    public function can_contact()
    {
        if($this->loaded())
        {
            if ($this->status == self::STATUS_PUBLISHED AND core::config('advertisement.contact') != FALSE )
            {
                return TRUE;
            }
        }
    
        return FALSE;
    }

    /**
     * Receives a description as a string to replace all baned word
     * with replacement provided.
     * array of baned words and replacement is get fromconfig
     * @param $decription string
     * @return string 
     */
    public static function banned_words($text)
    {

        if (core::config('advertisement.banned_words') != NULL AND core::config('advertisement.banned_words') != '')
        {
            $banned_words = explode(',',core::config('advertisement.banned_words'));
            $banned_words = array_map('trim', $banned_words);
            
            // with provided array of baned words, replacement and string to be replaced
            // returns string with replaced words
            return str_replace($banned_words, core::config('advertisement.banned_words_replacement'), $text);
        }
        else
            return $text;
    }

    /**
     * returns true if file is of valid type.
     * Its used to check file sent to user from advert usercontact
     * @return BOOL 
     */
    public function is_valid_file($file)
    {
        //catch file
        $file = $_FILES['file'];
        //validate file
        if( $file !== NULL)
        {     
            if ( 
                ! Upload::valid($file) OR
                ! Upload::not_empty($file) OR
                ! Upload::type($file, array('jpg', 'jpeg', 'png', 'pdf','doc','docx')) OR
                ! Upload::size($file, core::config('image.max_image_size').'M'))
                {
                    return FALSE;
                }
            return TRUE;
        }
    }

    /**
     * prints the map script from the view
     * @return string HTML or false in case not loaded
     */
    public function map()
    {
        if($this->loaded())
        {
            if (strlen($this->address)>5 AND core::config('advertisement.map')==1 )
            {
                return View::factory('pages/ad/map',array('address'=>$this->address))->render();
            }
        }
    
        return FALSE;
    }

    /**
     * prints the disqus script from the view
     * @return string HTML or false in case not loaded
     */
    public function disqus()
    {
        if($this->loaded())
        {
            if ($this->status == self::STATUS_PUBLISHED AND strlen(core::config('advertisement.disqus'))>0 )
            {
                return View::factory('pages/ad/disqus',
                                array('disqus'=>core::config('advertisement.disqus')))
                        ->render();
            }
        }
    
        return FALSE;
    }
    
    /**
    * returns a list with custom field values
    * @return array else false 
    */
    public function custom_columns()
    {
        if($this->loaded())
        {
            $custom_columns = array();
            foreach($this->_table_columns as $value)
            {   
                if(strpos($value['column_name'],'cf_') !== false) // take only custom columns
                    $custom_columns[$value['column_name']] = array('value'=>$this->$value['column_name'], 'parameters' => $value);
            }
            return $custom_columns;
        }
        return FALSE;
    }

} // END Model_ad
