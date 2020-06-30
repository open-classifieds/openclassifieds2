<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Import tools for admin
 *
 * @package    OC
 * @category   Tools
 * @author     Chema <chema@open-classifieds.com>
 * @copyright  (c) 2009-2013 Open Classifieds Team
 * @license    GPL v3
 */
class Controller_Panel_Import extends Controller_Panel_Tools {



        /*
        1- uploads CSV limit size, done
        2- verify correct format, done
        3- insert in table migration (create if doesnt exists), done

        Once this is done we display to the user:
        You have X ads to migrate. Click here to start

        That action will:
        1- Read X ads from the DB
        2- Row by row of the ads convert:
            - email to id_user
            - category name to id_category
            - location name to id_location
            - generate seoname
            - fields, title, description, date published
            - Ad images ** read below
        3- Mark Ad as migrated




        How many rows can we process each time? 5? add a redirect every 5? how they cancel the transformation? ajax call?
         */

    public function action_index()
    {
        $this->template->title = __('Import tool for ads');
        Breadcrumbs::add(Breadcrumb::factory()->set_title($this->template->title));
        $this->template->scripts['footer'][] = 'js/oc-panel/import.js';

        $this->template->content = View::factory('oc-panel/pages/tools/import_ads',array('ads_import'=>$this->amount_ads_import()));

    }


    /**
     * action for form CSV import
     * @return redirect
     */
    public function action_csv()
    {
        if (Core::extra_features() == FALSE)
        {
            Alert::set(Alert::WARNING, __('This feature is only available in the PRO version!') . ' ' . __('Upgrade your Yclas site to activate this feature.'));
            $this->redirect(Route::url('oc-panel', ['controller' => 'import']));
        }

        //sending a CSV
        if($_POST AND isset($_FILES['csv_file_ads']))
        {
            if ($_FILES['csv_file_ads']['size'] > 1048576)
            {
                Alert::set(Alert::ERROR, __('1 MB file'));
                $this->redirect(Route::url('oc-panel',array('controller'=>'import','action'=>'index')));
            }

            $header_expected = self::get_expected_columns();
            $header_expected_with_custom_fields = self::get_expected_columns(TRUE);

            $csv = $_FILES['csv_file_ads']["tmp_name"];

            //check if wants to import custom fields too
            if (Core::csv_is_valid($csv, $header_expected_with_custom_fields))
            {
                $header_expected = $header_expected_with_custom_fields;
                $ads = Core::csv_to_array($csv, $header_expected_with_custom_fields);
            }
            else
            {
                $ads = Core::csv_to_array($csv, $header_expected);
            }

            if (core::count($ads) > 10000)
            {
                Alert::set(Alert::ERROR, __('limited to 10.000 at a time'));
                $this->redirect(Route::url('oc-panel',array('controller'=>'import','action'=>'index')));
            }

            if ($ads === FALSE OR core::count($ads) === 0 OR ($ads_imported = $this->insert_into_import($ads, $header_expected)) === FALSE)
            {
                Alert::set(Alert::ERROR, __('Something went wrong, please check format of the file! Remove single quotes or strange characters, in case you have any. Make sure your CSV file has these headers: :headers', [':headers' => implode(', ', $header_expected)]));
            }
            else
            {
                Alert::set(Alert::SUCCESS, sprintf(__('%d Ads imported, click process to start migration'), $ads_imported));
            }

        }

        $this->redirect(Route::url('oc-panel',array('controller'=>'import','action'=>'index')));
    }

    public function action_process()
    {
        $this->auto_render = FALSE;
        $this->template = View::factory('js');

        //finished?
        if ($this->amount_ads_import()===0)
        {
            /*Alert::set(Alert::SUCCESS,__('All ads are processed! Congrats!'));
            $this->redirect(Route::url('oc-panel',array('controller'=>'import','action'=>'index')));*/
            $this->template->content = json_encode('OK');
        }
        //keep going!
        else
        {
            //how many ads we process in each request? pass get/post
            $limit_process = Core::request('limit',5);

            $ads_import = DB::select()
                            ->from('adsimport')
                            ->where('processed','=','0')
                            ->limit($limit_process)
                            ->as_object()
                            ->execute();

            $i=0;
            foreach ($ads_import as $adi)
            {
                if ($this->create_ad($adi)===TRUE)
                    $i++;
            }

            $todo  = $this->amount_ads_import();
            $done  = $this->amount_ads_import(1);
            $total = $todo + $done;

            $this->template->content = json_encode(round(100-($todo*100/$total)));
            //$this->redirect(Route::url('oc-panel',array('controller'=>'import','action'=>'process')));

        }

    }

    public function action_deletequeue()
    {
        //finished?
        if ($this->amount_ads_import()>0)
        {

            //TRUNCATE oc2_adsimport

            //check if in the table other users with same email set the id_user, then gets faster ;)
            try {
                DB::delete('adsimport')->execute()  ;
            } catch (Exception $e) {

            }

            Alert::set(Alert::SUCCESS, __('Queue cleaned'));


        }

        $this->redirect(Route::url('oc-panel',array('controller'=>'import','action'=>'index')));

    }


    /**
     * creates an ad from a row of import
     * @param  class adsimport $adi
     * @return boolean
     */
    private function create_ad($adi)
    {
        if (Core::is_cloud() AND Model_Domain::current()->num_max_ads > 0 AND Model_Domain::current()->num_ads >= Model_Domain::current()->num_max_ads)
        {
            Alert::set(Alert::INFO, sprintf(__('You have reached the limit of %d ads that your Yclas Plan allows.'),Model_Domain::current()->num_max_ads));
            $this->redirect(Route::url('oc-panel',array('controller'=>'import','action'=>'index')));
        }

        //new advertisement
        $ad = new Model_Ad();

        //create user?
        if ($adi->id_user==NULL OR !is_numeric($adi->id_user))
        {
            //create the user
            $user = Model_User::create_user($adi->user_email,$adi->user_name);

            //check if in the table other users with same email set the id_user, then gets faster ;)
            try {
                DB::update('adsimport')->set(array('id_user' => $user->id_user))
                ->where('user_email', '=', $adi->user_email)->execute();;
            } catch (Exception $e) {

            }

            //set id user to the new ad
            $ad->id_user = $user->id_user;

        }
        //user was already in the import DB
        else
        {
            $ad->id_user = $adi->id_user;
        }

        try {
            $cat = DB::select(DB::expr('id_category'))
            ->from('categories')
            ->where('name', '=', $adi->category)
            ->execute()
            ->as_array();

        } catch (Exception $e) {}

        //create category?
        if(empty($cat))
        {
            //create the category
            $cat = Model_Category::create_name($adi->category);

            //check if in the table other cats with same name set the category name, then gets faster ;)
            try {
                DB::update('adsimport')->set(array('id_category' => $cat->id_category))
                ->where('category', '=', $adi->category)->execute();
            } catch (Exception $e) {}

            //set id_category to the new ad
            $ad->id_category = $cat->id_category;

        }
        //category already exists
        else
        {
            $ad->id_category = $cat['0']['id_category'];
        }

        try {
            $loc = DB::select(DB::expr('id_location'))
            ->from('locations')
            ->where('name', '=', $adi->location)
            ->execute()
            ->as_array();

        } catch (Exception $e) {}

        //create location?
        if(! empty($adi->location))
        {
            if(empty($loc))
            {
                //create the location
                $loc = Model_Location::create_name($adi->location);

                //check if in the table other locs with same name set the id_location, then gets faster ;)
                try {
                    DB::update('adsimport')->set(array('id_location' => $loc->id_location))
                    ->where('location', '=', $adi->location)->execute();
                } catch (Exception $e) {}

                //set id_location to the new ad
                $ad->id_location = $loc->id_location;

            }
            //id_location already exists
            else
            {
                $ad->id_location = $loc['0']['id_location'];
            }
        }

        $ad->title      = $adi->title;
        $ad->seotitle   = $ad->gen_seo_title($adi->title);
        $ad->description= Text::html2bb($adi->description);
        $ad->published  = $adi->date;
        $ad->created    = $adi->date;
        $ad->price      = $adi->price;
        $ad->address    = $adi->address;
        $ad->phone      = $adi->phone;
        $ad->website    = $adi->website;
        $ad->stock      = $adi->stock;
        $ad->locale     = $adi->locale;
        $ad->status     = Model_Ad::STATUS_PUBLISHED;

        foreach (Model_Field::get_all() as $name => $custom_field)
        {
            $name = 'cf_' . $name;
            if($adi->$name != ''){
                $ad->$name = $adi->$name;
            }
        }

        try {
            $ad->save();
        } catch (Exception $e) {
            return FALSE;
        }

        //save images
        if (($has_images = $this->process_images($ad,$adi))>0)
        {
            $ad->has_images = $has_images;
            try {
                $ad->save();
            } catch (Exception $e) {
                return FALSE;
            }
        }

        // Post on social media
        Social::post_ad($ad, $ad->get_first_image('image'));

        //mark it as done
        try {
            DB::update('adsimport')->set(array('processed' => 1))
            ->where('id_import', '=', $adi->id_import)->execute();

            return TRUE;

        } catch (Exception $e) {
            return FALSE;
        }
    }


    /***Adding images
        image_1
        image_2
        image_3
        image_4
        - image_X needs to be a varchar.
        - If has protocol will download image,
        - if its a route will read formt he path
        - once we have the image, we change size and create thumb
        - if was downloaded we delete it*/

    private function process_images($ad,$adi)
    {
        //pattern in row CSV
        $image_pattern = 'image_';
        //amount images in CSV
        $num_images = core::config('advertisement.num_images');

        //how many images has the ad, return
        $ad_images  = 0;

        for ($i=1; $i <=$num_images ; $i++)
        {
            $image = $adi->{$image_pattern.$i};
            //trying save image
            if ($this->process_image($ad,$image,$ad_images+1)===TRUE)
                $ad_images++;
        }

        return $ad_images;
    }


    private function process_image($ad,$image,$num)
    {
        //from URL
        if (Valid::URL($image))
        {
            //download it, if takes more than 10 seconds...bad deal!
            $image_content = Core::curl_get_contents($image);

            //store if retrieved
            if ($image_content!==FALSE)
            {
                if (Core::is_cloud())
                {
                    $file = DOCROOT.'images/import/import_'.Model_Domain::current()->id_domain.'_'.$ad->id_ad.'_'.$num.'.jpg';
                }
                else
                {
                    $file = DOCROOT.'images/import_'.$ad->id_ad.'_'.$num.'.jpg';
                }

                if (!File::write($file, $image_content))
                    return FALSE;
            }
            else
                return FALSE;
        }
        //already in server
        elseif(Core::is_selfhosted() AND file_exists(DOCROOT.$image))
        {
            $file = DOCROOT.$image;
        }
        else
        {
            return FALSE;
        }

        try {
            $is_image = getimagesize($file);
        } catch (Exception $e) {
            $is_image = FALSE;
        }

        //only if its image will be returned
        return ($is_image!==FALSE)? $ad->save_image_file($file,$num):FALSE;
    }

    /**
     * inserts into table adsimport
     * @param  array $ads_array from csv
     * @param array $expected_header
     * @return bool/integer
     */
    private function insert_into_import($ads_array,$expected_header)
    {
        $prefix = Database::instance()->table_prefix();

        $columns = [
            "`id_import` int(10) unsigned NOT NULL AUTO_INCREMENT",
            "`id_user` int(10) unsigned  NULL",
            "`user_name` varchar(145) NOT NULL",
            "`user_email` varchar(145) NOT NULL",
            "`id_category` int(10) unsigned  NULL",
            "`category` varchar(145) NOT NULL",
            "`id_location` int(10) unsigned  NULL",
            "`location` varchar(145) NOT NULL",
            "`title` varchar(145) NOT NULL",
            "`description` text NOT NULL",
            "`address` varchar(145) DEFAULT '0'",
            "`price` decimal(14,3) NOT NULL DEFAULT '0.000'",
            "`phone` varchar(30) DEFAULT NULL",
            "`date` datetime DEFAULT NULL",
            "`website` varchar(200) DEFAULT NULL",
            "`locale` varchar(5) DEFAULT NULL",
            "`stock` int DEFAULT NULL",
            "`processed` tinyint(1) NOT NULL DEFAULT '0'"
        ];

        for ($i=1; $i <=core::config('advertisement.num_images') ; $i++)
            $columns[] = '`image_' . $i . '` varchar(200) DEFAULT NULL';


        foreach (Model_Field::get_all() as $name => $custom_field) {
            $name = 'cf_' . $name;
            switch ($custom_field['type']) {
                case 'textarea':
                    $columns[] = "`" . $name ."` text DEFAULT NULL";
                    break;

                case 'integer':
                    $columns[] = '`' . $name . '` int DEFAULT NULL';
                    break;

                case 'checkbox':
                case 'radio':
                    $columns[] = '`' . $name . '` tinyint(1) DEFAULT NULL';
                    break;

                case 'decimal':
                case 'range':
                    $columns[] = '`' . $name . '` float DEFAULT NULL';
                    break;

                case 'date':
                    $columns[] = '`' . $name . '` date DEFAULT NULL';
                    break;

                case 'select':
                case 'email':
                case 'country':
                    $columns[] = '`' . $name . '` varchar(145) DEFAULT NULL';

                    break;

                case 'string':
                default:
                    $columns[] = '`' . $name . '` varchar(256) DEFAULT NULL';

                    break;
            }
        }

        //create table import if doesnt exists
        $query = DB::query(Database::INSERT, 'CREATE TABLE IF NOT EXISTS `' . $prefix . 'adsimport` (
                                              ' . implode(',', $columns) . '
                                            , PRIMARY KEY (`id_import`) ) ENGINE=InnoDB DEFAULT CHARSET=utf8;');

        try
        {
           $query->execute();
        }
        catch (Exception $e)
        {
            return FALSE;
        }

        $adsimport_columns = array_keys(Database::instance()->list_columns('adsimport'));
        $adsimport_expected_columns = self::get_expected_columns(TRUE);
        sort($adsimport_columns);
        sort($adsimport_expected_columns);

        //drop and create table if not same columns
        if ($adsimport_columns !== $adsimport_expected_columns)
        {
            $drop_table = DB::query(Database::INSERT, 'DROP TABLE IF EXISTS `' . $prefix . 'adsimport`;');
            $create_table = DB::query(Database::INSERT, 'CREATE TABLE IF NOT EXISTS `' . $prefix . 'adsimport` (
                                                ' . implode(',', $columns) . '
                                                , PRIMARY KEY (`id_import`) ) ENGINE=InnoDB DEFAULT CHARSET=utf8;');

            try
            {
                $drop_table->execute();
                $create_table->execute();
            }
            catch (Exception $e)
            {
                return FALSE;
            }
        }

        //insert into table import
        foreach ($ads_array as $ad=>$values)
        {
            // Set NULL to 'NULL' value strings
            array_walk_recursive($values, function(&$value){
                $value = $value === 'NULL' ? NULL : $value;
            });

            $query = DB::insert('adsimport', $expected_header)->values($values);
            try
            {
               $query->execute();
            }
            catch (Exception $e)
            {
                return FALSE;
            }
        }

        return core::count($ads_array);
    }

    /**
     * amount of ads left to iport
     * @return integer
     */
    private function amount_ads_import($processed = 0)
    {
        //how many ads left to import?
        try {
            $ads_import =  DB::select(array(DB::expr('COUNT(`id_import`)'), 'total'))
                            ->from('adsimport')
                            ->where('processed','=',$processed)
                            ->execute()->as_array('total');
            $ads_import = key($ads_import);

        }
        //in case table doesnt exists...
        catch (Exception $e)
        {
            $ads_import = 0;
        }

        return $ads_import;
    }

    /*FROM OC DB to CSV

        SELECT u.name user_name,u.email user_email,a.title,a.description,a.published `date`,
                c.name category, l.name location,a.price,a.address,a.phone,a.website,
                '' image_1, '' image_2, '' image_3, '' image_4
        FROM `oc2_ads` as a
        INNER JOIN oc2_users u
        USING(id_user)
        INNER JOIN oc2_categories c
        USING (id_category)
        INNER JOIN oc2_locations l
        ON l.id_location=a.id_location
        WHERE a.status=1

    */

    /**
     * returns the expected cloumns to import
     * @param  boolean $with_cf false
     * @return array
     */
    private static function get_expected_columns($with_cf = FALSE)
    {
        //header that I expect
        $columns = array('user_name','user_email','title','description','date','category','location',
                                    'price','address','phone','website');

        if (Core::config('general.multilingual') == 1)
            $columns[] = 'locale';

        if (core::config('payment.stock')==1)
            $columns[] = 'stock';

        for ($i=1; $i <=core::config('advertisement.num_images') ; $i++)
            $columns[] = 'image_'.$i;

        if ($with_cf === TRUE)
            $columns = array_merge($columns, preg_filter('/^/', 'cf_', array_keys(Model_Field::get_all())));

        return $columns;
    }


}
