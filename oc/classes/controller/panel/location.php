<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Panel_Location extends Auth_Crud {



	/**
	 * @var $_index_fields ORM fields shown in index
	 */
	protected $_index_fields = array('id_location','name','id_location_parent');

	/**
	 * @var $_orm_model ORM model name
	 */
	protected $_orm_model = 'location';


    /**
     * overwrites the default crud index
     * @param  string $view nothing since we don't use it
     * @return void      
     */
    public function action_index($view = NULL)
    {
        //template header
        $this->template->title  = __('Locations');

        $this->template->styles  = array('css/sortable.css' => 'screen', 
                                         '//cdn.jsdelivr.net/bootstrap.tagsinput/0.3.9/bootstrap-tagsinput.css' => 'screen');
        $this->template->scripts['footer'][] = 'js/jquery-sortable-min.js';
        $this->template->scripts['footer'][] = 'js/oc-panel/locations.js';
        $this->template->scripts['footer'][] = '//cdn.jsdelivr.net/bootstrap.tagsinput/0.3.9/bootstrap-tagsinput.min.js';
        
        if (intval(Core::get('id_location', 1)) > 0)
        {
            $location = new Model_Location(intval(Core::get('id_location', 1)));
            
            if ($location->loaded())
            {
                if ($location->parent->loaded() AND $location->parent->id_location != 1)
                {
                    Breadcrumbs::add(Breadcrumb::factory()->set_title($location->parent->name)->set_url(Route::url('oc-panel',array('controller'=>'location','action'=>''.'?id_location='.$location->parent->id_location))));
                }
                
                $locs = new Model_Location();
                $locs = $locs->where('id_location_parent','=',Core::get('id_location', 1))->order_by('order','asc')->find_all()->cached()->as_array('id_location');
            }
            else
            {
                Alert::set(Alert::ERROR, __('You are selecting a location that does not exist'));
                $this->redirect(Route::get($this->_route_name)->uri(array('controller'=> Request::current()->controller())));
            }
        }
        
        $this->template->content = View::factory('oc-panel/pages/locations/index',array('locs' => $locs,'location' => $location));
    }

    /**
     * CRUD controller: CREATE
     */
    public function action_create()
    {

        $this->template->title = __('New').' '.__($this->_orm_model);
        
        $this->template->scripts['footer'][] = '//maps.google.com/maps/api/js?sensor=false&libraries=geometry&v=3.7';
        $this->template->scripts['footer'][] = '//cdn.jsdelivr.net/gmaps/0.4.15/gmaps.min.js';
        $this->template->scripts['footer'][] = 'js/oc-panel/locations-gmap.js';

        $form = new FormOrm($this->_orm_model);
        
        if ($this->request->post())
        {
            if ( $success = $form->submit() )
            {
                //location is different than himself, cant be his ow father!!!
                if ($form->object->id_location == $form->object->id_location_parent)
                {
                    Alert::set(Alert::INFO, __('You can not set as parent the same location'));
                    $this->redirect(Route::get($this->_route_name)->uri(array('controller'=> Request::current()->controller(),'action'=>'create')));
                }

                //check if the parent is loaded/exists avoiding errors
                $parent_loc = new Model_Location($form->object->id_location_parent);
                if (!$parent_loc->loaded())
                {
                    Alert::set(Alert::INFO, __('You are assigning a parent location that does not exist'));
                    $this->redirect(Route::get($this->_route_name)->uri(array('controller'=> Request::current()->controller(),'action'=>'create')));
                }

                $form->object->description = Kohana::$_POST_ORIG['formorm']['description'];
                
                try {
                    $form->object->save();
                } catch (Exception $e) {
                    throw HTTP_Exception::factory(500,$e->getMessage());  
                }

                $this->action_deep();
                Core::delete_cache();

                Alert::set(Alert::SUCCESS, __('Location created'));
            
                $this->redirect(Route::get($this->_route_name)->uri(array('controller'=> Request::current()->controller())));
            }
            else 
            {
                Alert::set(Alert::ERROR, __('Check form for errors'));
            }
        }
    
        return $this->render('oc-panel/pages/locations/create', array('form' => $form));

    }
    /**
     * CRUD controller: UPDATE
     */
    public function action_update()
    {
        $this->template->title = __('Update').' '.__($this->_orm_model).' '.$this->request->param('id');
    
        $this->template->scripts['footer'][] = 'js/oc-panel/locations-gmap.js';

        $form = new FormOrm($this->_orm_model,$this->request->param('id'));
        $location = new Model_Location($this->request->param('id'));
        
        if ($this->request->post())
        {
            if ( $success = $form->submit() )
            {
                if ($form->object->id_location == $form->object->id_location_parent)
                {
                    Alert::set(Alert::INFO, __('You can not set as parent the same location'));
                    $this->redirect(Route::get($this->_route_name)->uri(array('controller'=> Request::current()->controller(),'action'=>'update','id'=>$form->object->id_location)));
                }

                //check if the parent is loaded/exists avoiding errors
                $parent_loc = new Model_Location($form->object->id_location_parent);
                if (!$parent_loc->loaded())
                {
                    Alert::set(Alert::INFO, __('You are assigning a parent location that does not exist'));
                    $this->redirect(Route::get($this->_route_name)->uri(array('controller'=> Request::current()->controller(),'action'=>'create')));
                }

                $form->object->description = Kohana::$_POST_ORIG['formorm']['description'];

                try {
                    $form->object->save();
                } catch (Exception $e) {
                    throw HTTP_Exception::factory(500,$e->getMessage());  
                }

                $form->object->parent_deep =  $form->object->get_deep();

                try {
                    $form->object->save();
                } catch (Exception $e) {
                    throw HTTP_Exception::factory(500,$e->getMessage());  
                }

                $this->action_deep();

                //rename icon name
                if($location->has_image AND ($location->seoname != $form->object->seoname))
                    $location->rename_icon($form->object->seoname);

                Core::delete_cache();
                
                Alert::set(Alert::SUCCESS, __('Item updated'));
                $this->redirect(Route::get($this->_route_name)->uri(array('controller'=> Request::current()->controller())));
            }
            else
            {
                Alert::set(Alert::ERROR, __('Check form for errors'));
            }
        }
    
        return $this->render('oc-panel/pages/locations/update', array('form' => $form, 'location' => $location));
    }

    /**
     * saves the location in a specific order and change the parent
     * @return void 
     */
    public function action_saveorder()
    {
        $this->auto_render = FALSE;
        $this->template = View::factory('js');

        $loc = new Model_Location(core::get('id_location'));

        //check if the parent is loaded/exists avoiding errors
        $parent_loc = new Model_Location(core::get('id_location_parent'));

        if ($loc->loaded() AND $parent_loc->loaded())
        {
            //saves the current location
            $loc->id_location_parent = core::get('id_location_parent');
            $loc->parent_deep        = core::get('deep');
            

            //saves the locations in the same parent the new orders
            $order = 0;
            foreach (core::get('brothers') as $id_loc) 
            {
                $id_loc = substr($id_loc,3);//removing the li_ to get the integer

                //not the main location so loading and saving
                if ($id_loc!=core::get('id_location'))
                {
                    $c = new Model_Location($id_loc);
                    $c->parent_deep     = core::get('deep');
                    $c->order           = $order;
                    $c->save();
                }
                else
                {
                    //saves the main location
                    $loc->order  = $order;
                    $loc->save();
                }
                $order++;
            }

            //update deep for all the locations
            $this->action_deep();
            Core::delete_cache();
            $this->template->content = __('Saved');
        }
        else
            $this->template->content = __('Error');
    }

    /**
     * CRUD controller: DELETE
     */
    public function action_delete()
    {
        $this->auto_render = FALSE;

        $location = new Model_Location($this->request->param('id'));

        //update the elements related to that ad
        if ($location->loaded())
        {
            //check if the parent is loaded/exists avoiding errors, if doesnt exist to the root
            $parent_loc = new Model_Location($location->id_location_parent);
            if ($parent_loc->loaded())
                $id_location_parent = $location->id_location_parent;
            else
                $id_location_parent = 1;

            //update all the siblings this location has and set the location parent
            $query = DB::update('locations')
                        ->set(array('id_location_parent' => $id_location_parent))
                        ->where('id_location_parent','=',$location->id_location)
                        ->execute();

            //update all the ads this location has and set the location parent
            $query = DB::update('ads')
                        ->set(array('id_location' => $id_location_parent))
                        ->where('id_location','=',$location->id_location)
                        ->execute();
                        
            //delete icon_delete
            $root = DOCROOT.'images/locations/'; //root folder
            if (is_dir($root))
            {
                @unlink($root.$location->seoname.'.png');
            
                // delete icon from Amazon S3
                if(core::config('image.aws_s3_active'))
                    $s3->deleteObject(core::config('image.aws_s3_bucket'), 'images/locations/'.$location->seoname.'.png');
            
                // update location info
                $location->has_image = 0;
                $location->last_modified = Date::unix2mysql();
                $location->save();            
            }

            try
            {
                $location->delete();

                $this->template->content = 'OK';

                //recalculating the deep of all the categories
                $this->action_deep();
                Core::delete_cache();
                Alert::set(Alert::SUCCESS, __('Location deleted'));
                
            }
            catch (Exception $e)
            {
                 Alert::set(Alert::ERROR, $e->getMessage());
            }
        }
        else
             Alert::set(Alert::SUCCESS, __('Location not deleted'));

        
        HTTP::redirect(Route::url('oc-panel',array('controller'  => 'location','action'=>'index')));  

    }

    /**
     * Creates multiple locations just with name
     * @return void      
     */
    public function action_multy_locations()
    {
        $this->auto_render = FALSE;

        //update the elements related to that ad
        if ($_POST)
        {
            if(core::post('multy_locations') !== "")
            {
                $multy_cats = explode(',', core::post('multy_locations'));
                $obj_location = new Model_Location();

                $insert = DB::insert('locations', array('name', 'seoname', 'id_location_parent'));
                foreach ($multy_cats as $name)
                {
                    $insert = $insert->values(array($name,$obj_location->gen_seoname($name),Core::get('id_location', 1)));
                }
                // Insert everything with one query.
                $insert->execute();

                Core::delete_cache();
            }
            else
                Alert::set(Alert::INFO, __('Select some locations first.'));
        }
        
        HTTP::redirect(Route::url('oc-panel',array('controller'  => 'location','action'=>'index')).'?id_location='.Core::get('id_location', 1));
    }
    
    /**
     * Import multiple locations from geonames
     * @return void      
     */
    public function action_geonames_locations()
    {
        $this->auto_render = FALSE;
    
        //update the elements related to that ad
        if (core::post('geonames_locations') !== "")
        {

            $geonames_locations = json_decode(core::post('geonames_locations'));
            
            if (count($geonames_locations) > 0)
            {
                $obj_location = new Model_Location();
                $locations_array = array();

                $insert = DB::insert('locations', array('name', 'seoname', 'id_location_parent', 'latitude', 'longitude'));
                foreach ($geonames_locations as $location)
                {                    
                    if ( ! in_array($location->name,$locations_array))
                    {
                        $insert = $insert->values(array($location->name,$obj_location->gen_seoname($location->name),
                                                        Core::get('id_location', 1),
                                                        isset($location->lat)?$location->lat:NULL,
                                                        isset($location->long)?$location->long:NULL));
                        
                        $locations_array[] = $location->name;
                    }
                }
                // Insert everything with one query.
                $insert->execute();

                Core::delete_cache();
            }

        }
        else
            Alert::set(Alert::INFO, __('Select some locations first.'));
        
        
        HTTP::redirect(Route::url('oc-panel',array('controller'  => 'location','action'=>'index')).'?id_location='.Core::get('id_location', 1));
    }

    /**
     * recalculating the deep of all the locations
     * @return [type] [description]
     */
    public function action_deep()
    {
        //clean the cache so we get updated results
        Cache::instance()->delete_all();    

        //getting all the cats as array
        $locs_arr  = Model_Location::get_as_array();

        $locs = new Model_Location();
        $locs = $locs->order_by('order','asc')->find_all()->cached()->as_array('id_location');
        foreach ($locs as $loc) 
        {
            $deep = 0;

            //getin the parent of this location
            $id_location_parent = $locs_arr[$loc->id_location]['id_location_parent'];

            //counting till we find the begining
            while ($id_location_parent != 1 AND $id_location_parent != 0 AND $deep<10) 
            {
                $id_location_parent = $locs_arr[$id_location_parent]['id_location_parent'];
                $deep++;
            }

            //saving the location only if different deep
            if ($loc->parent_deep != $deep)
            {
                $loc->parent_deep = $deep;
                $loc->save();
            }
        }
        //Alert::set(Alert::INFO, __('Success'));
        //HTTP::redirect(Route::url('oc-panel',array('controller'  => 'location','action'=>'index'))); 
    }

	public function action_icon()
	{
        //get icon
        $icon = $_FILES['location_icon']; //file post
        
        $location = new Model_Location($this->request->param('id'));
        
        if (core::config('image.aws_s3_active'))
        {
            require_once Kohana::find_file('vendor', 'amazon-s3-php-class/S3','php');
            $s3 = new S3(core::config('image.aws_access_key'), core::config('image.aws_secret_key'));
        }

        if (core::post('icon_delete')  AND $location->delete_icon()==TRUE )
        {            
            Alert::set(Alert::SUCCESS, __('Icon deleted.'));
            $this->redirect(Route::get($this->_route_name)->uri(array('controller'=> Request::current()->controller(),'action'=>'update','id'=>$location->id_location)));
        }// end of icon delete

        if ( 
            ! Upload::valid($icon) OR
            ! Upload::not_empty($icon) OR
            ! Upload::type($icon, explode(',',core::config('image.allowed_formats'))) OR
            ! Upload::size($icon, core::config('image.max_image_size').'M'))
        {
        	if ( Upload::not_empty($icon) && ! Upload::type($icon, explode(',',core::config('image.allowed_formats'))))
            {
                Alert::set(Alert::ALERT, $icon['name'].' '.sprintf(__('Is not valid format, please use one of this formats "%s"'),core::config('image.allowed_formats')));
				$this->redirect(Route::get($this->_route_name)->uri(array('controller'=> Request::current()->controller(),'action'=>'update','id'=>$location->id_location)));
            } 
            if ( ! Upload::size($icon, core::config('image.max_image_size').'M'))
            {
                Alert::set(Alert::ALERT, $icon['name'].' '.sprintf(__('Is not of valid size. Size is limited to %s MB per image'),core::config('image.max_image_size')));
				$this->redirect(Route::get($this->_route_name)->uri(array('controller'=> Request::current()->controller(),'action'=>'update','id'=>$location->id_location)));
            }
            Alert::set(Alert::ALERT, $icon['name'].' '.__('Image is not valid. Please try again.'));
            $this->redirect(Route::get($this->_route_name)->uri(array('controller'=> Request::current()->controller(),'action'=>'update','id'=>$location->id_location)));
        }
        else
        {
            if ($icon != NULL) // sanity check 
            {   
                // saving/uploading img file to dir.
                $path = 'images/locations/';
                $root = DOCROOT.$path; //root folder
                $icon_name = $location->seoname.'.png';
                
                // if folder does not exist, try to make it
               	if ( ! file_exists($root) AND ! @mkdir($root, 0775, true)) { // mkdir not successful ?
                        Alert::set(Alert::ERROR, __('Image folder is missing and cannot be created with mkdir. Please correct to be able to upload images.'));
                        return; // exit function
                };
                
                // save file to root folder, file, name, dir
                if ($file = Upload::save($icon, $icon_name, $root))
                {
                    // put icon to Amazon S3
                    if (core::config('image.aws_s3_active'))
                        $s3->putObject($s3->inputFile($file), core::config('image.aws_s3_bucket'), $path.$icon_name, S3::ACL_PUBLIC_READ);
                    
                    // update location info
                    $location->has_image = 1;
                    $location->last_modified = Date::unix2mysql();
                    $location->save();
                    
                    Alert::set(Alert::SUCCESS, $icon['name'].' '.__('Icon is uploaded.'));
                }
                else
                    Alert::set(Alert::ERROR, $icon['name'].' '.__('Icon file could not been saved.'));
                
                $this->redirect(Route::get($this->_route_name)->uri(array('controller'=> Request::current()->controller(),'action'=>'update','id'=>$location->id_location)));
            }
            
        }
	}   
    
    /**
     * deletes all the locations
     * @return void 
     */
    public function action_delete_all()
    {
        if(core::post('confirmation'))
        {
            //delete location icons
            $locations = new Model_Location();
            $locations = $locations->where('id_location','!=','1')->find_all();
            
            foreach ($locations as $location)
            {
                $root = DOCROOT.'images/locations/'; //root folder
                if (is_dir($root))
                {
                    @unlink($root.$location->seoname.'.png');
                    
                    // delete icon from Amazon S3
                    if(core::config('image.aws_s3_active'))
                        $s3->deleteObject(core::config('image.aws_s3_bucket'), 'images/locations/'.$location->seoname.'.png');
                }
            }
            
            //set home location to all the ads
            $query = DB::update('ads')
                        ->set(array('id_location' => '1'))
                        ->execute();
            
            //delete all locations
            $query = DB::delete('locations')
                        ->where('id_location','!=','1')
                        ->execute();
            
            Core::delete_cache();
            
            Alert::set(Alert::SUCCESS, __('All locations were deleted.'));
            
        }
        else {
            Alert::set(Alert::ERROR, __('You did not confirmed your delete action.'));
        }
        
        HTTP::redirect(Route::url('oc-panel',array('controller'=>'location', 'action'=>'index')));
    }
}
