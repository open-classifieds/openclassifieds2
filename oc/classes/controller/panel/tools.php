<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Mixed tools for admin
 *
 * @package    OC
 * @category   Tools
 * @author     Chema <chema@open-classifieds.com>
 * @copyright  (c) 2009-2013 Open Classifieds Team
 * @license    GPL v3
 */
class Controller_Panel_Tools extends Controller_Panel_OC_Tools {

    public function action_export()
    {
        $csv_header = array('user_name','user_email','title','description','date','category','location',
                                    'price','address','phone','website','image_1','image_2','image_3','image_4');

        //the name of the file that user will download
        $file_name = 'export.csv';
        //name of the TMP file
        $output_file = tempnam('/tmp', $file_name);
        
        //writting
        $output = fopen($output_file, 'w');
        //header of the CSV
        fputcsv($output, $csv_header);

        //model ad
        $ads = new Model_Ad();
        $ads->where('status','=',Model_Ad::STATUS_PUBLISHED);
        $ads = $ads->find_all();

        //each element
        foreach($ads as $ad) 
        {
            $pic1 = NULL;
            $pic2 = NULL;
            $pic3 = NULL;
            $pic4 = NULL;
            $images = $ad->get_images();
            if (count($images)>0)
            {
                if (isset($images[1]))
                    $pic1 = $images[1]['image'];

                if (isset($images[2]))
                    $pic2 = $images[2]['image'];

                if (isset($images[3]))
                    $pic3 = $images[3]['image'];

                if (isset($images[4]))
                    $pic4 = $images[4]['image'];
            }

            $a = array($ad->user->name,
                       $ad->user->email,
                       $ad->title,
                       $ad->description,
                       $ad->published,
                       $ad->category->name,
                       $ad->location->name,
                       $ad->price,
                       $ad->address,
                       $ad->phone,
                       $ad->website,
                       $pic1,$pic2,$pic3,$pic4
                       );

            fputcsv($output, $a);
        }
        
        fclose($output);

        //returns the file to the browser as attachement and deletes the TMP file
        Response::factory()->send_file($output_file,$file_name,array('delete'=>TRUE));
    } 

    public function action_import_tool()
    {
        $this->template->title = __('Import tool for locations and categories');
        Breadcrumbs::add(Breadcrumb::factory()->set_title($this->template->title));

        //sending a CSV
        if($_POST)
        {
            foreach($_FILES as $file => $path) 
            {
                $csv = $path["tmp_name"];
                $csv_2[] = $file;

                if ($path['size'] > 1048576)
                {
                    Alert::set(Alert::ERROR, __('1 MB file'));
                    $this->redirect(Route::url('oc-panel',array('controller'=>'tools','action'=>'import_tool')));
                }

                if($file=='csv_file_categories' AND $csv != FALSE)
                {
                    $expected_header = array('name','category_parent','price');
                    
                    $cat_array = Core::csv_to_array($csv,$expected_header);

                    if (count($cat_array) > 10000)
                    {
                        Alert::set(Alert::ERROR, __('limited to 10.000 at a time'));
                        $this->redirect(Route::url('oc-panel',array('controller'=>'tools','action'=>'import_tool')));
                    }
                    
                    if ($cat_array===FALSE)
                    {
                        Alert::set(Alert::ERROR, __('Something went wrong, please check format of the file! Remove single quotes or strange characters, in case you have any.'));
                    }
                    else
                    {
                        foreach ($cat_array as $cat) 
                        {
                            
                            //category parent was sent?
                            if(isset($cat[1]))
                            {
                                $category_parent = new Model_Category();
                                $category_parent->where('name','=',$cat[1])->limit(1)->find();
                                
                                if ($category_parent->loaded())
                                    $cat[1] = $category_parent->id_category;
                                else
                                    $cat[1] = 1;
                            }
                            else
                                $cat[1] = 1;
                            
                            Model_Category::create_name($cat[0], 0, $cat[1], 0, $cat[2]);
                        }
                        
                        Core::delete_cache();
                        Alert::set(Alert::SUCCESS, __('Categories successfully imported.'));
                    }
                }
                elseif($file=='csv_file_locations' AND $csv != FALSE)
                {
                    $expected_header = array('name','location_parent','latitude','longitude');
                    
                    $loc_array = Core::csv_to_array($csv,$expected_header);

                    if (count($loc_array) > 10000)
                    {
                        Alert::set(Alert::ERROR, __('limited to 10.000 at a time'));
                        $this->redirect(Route::url('oc-panel',array('controller'=>'tools','action'=>'import_tool')));
                    }
                    
                    if ($loc_array===FALSE)
                    {
                        Alert::set(Alert::ERROR, __('Something went wrong, please check format of the file! Remove single quotes or strange characters, in case you have any.'));
                    }
                    else
                    {
                        foreach ($loc_array as $loc) 
                        {
                            //location parent was sent?
                            if(isset($loc[1]))
                            {
                                $location_parent = new Model_Location();
                                $location_parent->where('name','=',$loc[1])->limit(1)->find();
                                
                                if ($location_parent->loaded())
                                    $loc[1] = $location_parent->id_location;
                                else
                                    $loc[1] = 1;
                            }
                            else
                                $loc[1] = 1;
                            
                            Model_Location::create_name($loc[0], 0, $loc[1], 0, $loc[2], $loc[3]);
                        }
                        
                        Core::delete_cache();
                        Alert::set(Alert::SUCCESS, __('Locations successfully imported.'));
                    }
                }
                
            }
        } 

        $this->template->content = View::factory('oc-panel/pages/tools/import_tool');
    }

    public function action_migration()
    {
        //@todo improve
        //flow: ask for new connection, if success we store it ina  config as an array.
        //then we display the tables with how many rows --> new view, bottom load the db connection form in case they want to change it
        //in the form ask to do diet in current DB cleanins visits users posts inactive?
        //Migration button
            //on submit 
            // create config group migration to store in which ID was stuck (if happens)
            // save ids migration for maps in configs?
            // do migration using iframe this

        $this->template->title   = __('Open Classifieds migration');
        Breadcrumbs::add(Breadcrumb::factory()->set_title(Text::ucfirst(__('Migration'))));


        //force clean database from migration, not public, just internal helper
        if (Core::get('delete')==1)
        {
            // $this->clean_migration();
            // Alert::set(Alert::SUCCESS,__('Database cleaned'));
        }

        if ($this->request->post())
        {
            $db_config = array (
                'type' => 'mysqli',
                'connection' => 
                array (
                    'hostname' => Core::post('hostname'),
                    'database' => Core::post('database'),
                    'username' => Core::post('username'),
                    'password' => Core::post('password'),
                    'persistent' => false,
                ),
                'table_prefix' => Core::post('table_prefix'),
                'charset' => Core::post('charset'),
                'caching' => false,
                'profiling' => false,
            );

            try
            {
                //connect DB
                $db = Database::instance('migrate', $db_config);

                //verify tables in DB
                $pf = Core::post('table_prefix');
                $migration_tables = array($pf.'accounts',$pf.'categories',$pf.'locations',$pf.'posts',$pf.'postshits');
                
                $tables = $db->query(Database::SELECT, 'SHOW TABLES;');
                
            }
            catch (Exception $e)
            {
                Alert::set(Alert::ERROR, __('Review database connection parameters'));
                return;
            }   
           
            //verify tables in DB
            foreach ($tables as $table => $value) 
            {
                $val = array_values($value);
                $t[] = $val[0];                        
            }
            $tables = $t;

            $match_tables = TRUE;
            foreach ($migration_tables as $t) 
            {
                if( ! in_array($t, $tables))
                {
                    $match_tables = FALSE;
                    Alert::set(Alert::ERROR, sprintf(__('Table %s not found'),$t));
                }
                    
            }
            //end tables verification
            
            
            if ($match_tables)
            {
                //start migration
                $start_time = microtime(true);
                $this->migrate($db,$pf);
                Alert::set(Alert::SUCCESS, 'oh yeah! '.round((microtime(true)-$start_time),3).' '.__('seconds'));
            }
            
        }
        else
        {
            $db_config = core::config('database.default');
        }

        $this->template->content = View::factory('oc-panel/pages/tools/migration',array('db_config'=>$db_config));
    }


    private function clean_migration()
    {
        set_time_limit(0);

        DB::delete('ads')->execute();

        DB::delete('categories')->where('id_category','!=','1')->execute();

        DB::delete('locations')->where('id_location','!=','1')->execute();

        DB::delete('users')->where('id_user','!=','1')->execute();

        DB::delete('visits')->execute();

    }


    /**
     * does the DB migration
     * @param  pointer $db 
     * @param  string $pf db_prefix
     */
    private function migrate($db,$pf)
    {
        set_time_limit(0);

        $db_config = core::config('database.default');
        $prefix = $db_config['table_prefix'];
        //connect DB original/to where we migrate
        $dbo = Database::instance('default');

        
        //oc_accounts --> oc_users
        $users_map = array();
        $accounts = $db->query(Database::SELECT, 'SELECT * FROM `'.$pf.'accounts`');

        foreach ($accounts as $account) 
        {

            $user = new Model_User();

            $user->where('email','=',$account['email'])->limit(1)->find();

            if (!$user->loaded())
            {
                $user->name         = $account['name'];
                $user->email        = $account['email'];
                $user->password     = $account['password'];
                $user->created      = $account['createdDate'];
                $user->last_modified= $account['lastModifiedDate'];
                $user->last_login   = $account['lastSigninDate'];
                $user->status       = $account['active'];
                $user->id_role      = 1;
                $user->seoname      = $user->gen_seo_title($user->name);
                $user->save();
            }

            $users_map[$account['email']] = $user->id_user;
        }

        //categories --> categories
        $categories_map = array(0=>1);

        $categories = $db->query(Database::SELECT, 'SELECT * FROM `'.$pf.'categories` ORDER BY `idCategoryParent` ASC');

        foreach ($categories as $category) 
        {
            $cat = new Model_Category();
            $cat->name      = $category['name'];
            $cat->order     = $category['order'];
            $cat->created   = $category['created'];
            $cat->seoname   = $category['friendlyName'];
            $cat->price     = $category['price'];
            $cat->description = substr($category['description'],0,250);
            $cat->parent_deep = ($category['idCategoryParent']>0)? 1:0; //there's only 1 deep
            $cat->id_category_parent = (isset($categories_map[$category['idCategoryParent']]))?$categories_map[$category['idCategoryParent']]:1;
            $cat->save();

            //we save old_id stores the new ID, so later we know the category parent, and to changes the ADS category id
            $categories_map[$category['idCategory']] = $cat->id_category;

        }


        //locations --> locations
        $locations_map = array(0=>1);

        $locations = $db->query(Database::SELECT, 'SELECT * FROM `'.$pf.'locations` ORDER BY `idLocationParent` ASC');

        foreach ($locations as $location) 
        {
            $loc = new Model_Location();
            $loc->name      = $location['name'];
            $loc->seoname   = $location['friendlyName'];
            $loc->parent_deep = ($location['idLocationParent']>0)? 1:0; //there's only 1 deep
            $loc->id_location_parent = (isset($locations_map[$location['idLocationParent']]))?$locations_map[$location['idLocationParent']]:1;
            $loc->save();

            //we save old_id stores the new ID, so later we know the location parent, and to changes the ADS location id
            $locations_map[$location['idLocation']] = $loc->id_location;

        }

        //posts --> ads
        $ads_map = array();
        $ads = $db->query(Database::SELECT, 'SELECT * FROM `'.$pf.'posts`');

        foreach ($ads as $a) 
        {
            if (Valid::email($a['email']))
            {
                //gettin the id_user
                if(isset($users_map[$a['email']]))
                {
                    $id_user = $users_map[$a['email']]; 
                }
                //doesnt exits creating it
                else
                {
                    $user = Model_User::create_email($a['email'], $a['name']);
                    $id_user = $user->id_user;
                }
                    

                $ad = new Model_Ad();
                $ad->id_ad          = $a['idPost']; //so images still work
                $ad->id_user        = $id_user;
                $ad->id_category    = (isset($categories_map[$a['idCategory']]))?$categories_map[$a['idCategory']]:1;
                $ad->id_location    = (isset($locations_map[$a['idLocation']]))?$locations_map[$a['idLocation']]:1;
                $ad->title          = $a['title'];
                $ad->seotitle       = $ad->gen_seo_title($a['title']);
                $ad->description    = (!empty($a['description']))?Text::html2bb($a['description']):$a['title'];
                $ad->address        = $a['place'];
                $ad->price          = $a['price'];
                $ad->phone          = $a['phone'];
                $ad->has_images     = $a['hasImages'];
                $ad->ip_address     = ip2long($a['ip']);
                $ad->created        = $a['insertDate'];
                $ad->published      = $ad->created;

                //Status migration...big mess!
                if ($a['isAvailable']==0 AND $a['isConfirmed'] ==0)
                {
                    $ad->status = Model_Ad::STATUS_NOPUBLISHED;
                }
                elseif ($a['isAvailable']==1 AND   $a['isConfirmed'] ==0)
                {
                    $ad->status = Model_Ad::STATUS_NOPUBLISHED;
                }
                elseif ($a['isAvailable']==1 AND   $a['isConfirmed'] ==1)
                {
                    $ad->status = Model_Ad::STATUS_PUBLISHED;
                }
                elseif ($a['isAvailable']==0 AND   $a['isConfirmed'] ==1)
                {
                    $ad->status = Model_Ad::STATUS_UNAVAILABLE;
                }
                elseif ($a['isAvailable']==2 )
                {
                    $ad->status = Model_Ad::STATUS_SPAM;
                }
                else
                {
                    $ad->status = Model_Ad::STATUS_UNAVAILABLE;
                }

                try
                {
                    $ad->save();
                }
                catch (ORM_Validation_Exception $e)
                {
                    // d($e->errors(''));
                }

                $ads_map[$a['idPost']] = $ad->id_ad;
            }
            
        }

        //posthits --> visits, mass migration
        $insert = 'INSERT INTO `'.$prefix.'visits` ( `id_ad`, `created`, `ip_address`) VALUES';

        $step  = 5000;
        $total = $db->query(Database::SELECT, 'SELECT count(*) cont FROM `'.$pf.'postshits`')->as_array();
        $total = $total[0]['cont'];

        for ($i=0; $i < $total; $i+=$step) 
        { 
            $hits = $db->query(Database::SELECT, 'SELECT * FROM `'.$pf.'postshits` LIMIT '.$i.', '.$step);
            $values = '';
            foreach ($hits as $hit) 
            {
                //build insert query
                $values.= '('.$hit['idPost'].',  \''.$hit['hitTime'].'\', \''.ip2long($hit['ip']).'\'),';
            }

            $dbo->query(Database::INSERT, $insert.substr($values,0,-1));
        }
            //old way of migrating
            // $hits = $db->query(Database::SELECT, 'SELECT * FROM `'.$pf.'postshits` ');

            // foreach ($hits as $hit) 
            // {
            //     //build insert query
                
            //     $visit = new Model_Visit();
            //     $visit->id_ad       = (isset($ads_map[$hit['idPost']]))?$ads_map[$hit['idPost']]:NULL;
            //     $visit->created     = $hit['hitTime'];
            //     $visit->ip_address  = ip2long($hit['ip']);
            //     $visit->save();
            // }
       

    }

    /**
     * cleans old pictures
     * @return [type] [description]
     */
    public function action_cleanimages()
    {
        $count_deleted = 0;

        //loop for directory image
        $folder = DOCROOT.'images';

        //year
        foreach (new DirectoryIterator($folder) as $year)
        {
            if($year->isDir() AND !$year->isDot() AND is_numeric($year->getFilename()))
            {
                //month
                foreach (new DirectoryIterator($year->getPathname()) as $month)
                {
                    if($month->isDir() AND !$month->isDot() AND is_numeric($month->getFilename()))
                    {
                        //day
                        foreach (new DirectoryIterator($month->getPathname()) as $day)
                        {
                            if($day->isDir() AND !$day->isDot() AND is_numeric($day->getFilename()))
                            {                                
                                //id_ad
                                foreach (new DirectoryIterator($day->getPathname()) as $id_ad)
                                {
                                    if($id_ad->isDir() AND !$id_ad->isDot() AND is_numeric($id_ad->getFilename()))
                                    {   
                                        $delete = TRUE;

                                        //if ad is available leave it, if not delete folder ID
                                        $ad = new Model_Ad($id_ad->getFilename());
                                        if ($ad->loaded() AND $ad->status == Model_Ad::STATUS_PUBLISHED)
                                            $delete = FALSE;
                                        
                                        //ok lets get rid of it!
                                        if ($delete === TRUE)
                                        {
                                            echo '<br>Deleting: '.$id_ad->getFilename().'---'.$id_ad->getPathname();
                                            File::delete($id_ad->getPathname());
                                            
                                            //if the ad was loaded means had a different status, put it like he doesnt have images.
                                            if($ad->loaded() )
                                            {
                                                $ad->has_images = 0;
                                                $ad->save();
                                                //$ad->delete();//optional
                                            }

                                            $count_deleted++;
                                        }

                                    }
                                }


                            }
                        }

                    }
                }

               


            }
        }

        echo '<br>deleted '.$count_deleted;
        
            
    }
    
    /**
     * get geocode lat/lon points for given address from google
     * 
     * @param string $address
     * @return bool|array false if can't be geocoded, array or geocdoes if successful
     */
    public function action_get_ads_latlgn()
    {
        $ads = new Model_Ad();
        $ads = $ads->where('latitude','IS', NULL)
                   ->where('longitude','IS', NULL)
                   ->where('address','IS NOT',NULL)
                   ->where('address','!=','')
                   ->find_all();
                
        foreach ($ads as $ad)
        {
            $url = 'http://maps.google.com/maps/api/geocode/json?sensor=false&address='.urlencode($ad->address);
            
            //get contents from google
            if($result = core::curl_get_contents($url)) 
            {
                $result = json_decode($result);
            
                if($result AND $result->status=="OK") {
                    $ad->latitude  = $result->results[0]->geometry->location->lat;
                    $ad->longitude = $result->results[0]->geometry->location->lng;
                    
                    try 
                    {
                        $ad->save();
                    } 
                    catch (Exception $e) 
                    {
                        throw HTTP_Exception::factory(500,$e->getMessage());
                    }
                }
            }
        }
        
        Alert::set(Alert::SUCCESS, __('Successfully imported latitude and longitude info from your ads.'));
        
        $this->redirect(Route::url('oc-panel',array('controller'=>'import','action'=>'csv')));
    }

}