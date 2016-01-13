<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Api_Listings extends Api_Auth {


    /**
     * Handle GET requests.
     */
    public function action_index()
    {
        try
        {
            if (is_numeric($this->request->param('id')))
            {
                $this->action_get();
            }
            else
            {
                $output = array();

                $ads = new Model_Ad();

                $ads->where('status','=',Model_Ad::STATUS_PUBLISHED);

                //search with lat and long!! nice!
                if (isset($this->_params['latitude']) AND isset($this->_params['longitude']))
                {
                    $ads->select(array(DB::expr('degrees(acos(sin(radians('.$this->_params['latitude'].')) * sin(radians(`latitude`)) + cos(radians('.$this->_params['latitude'].')) * cos(radians(`latitude`)) * cos(radians(abs('.$this->_params['longitude'].' - `longitude`))))) * 69.172'), 'distance'))
                    ->where('latitude','IS NOT',NULL)
                    ->where('longitude','IS NOT',NULL);

                    //we unset the search by lat and long if not will be duplicated
                    unset($this->_filter_params['latitude']);
                    unset($this->_filter_params['longitude']);
                }

                //only published ads
                $ads->where('status', '=', Model_Ad::STATUS_PUBLISHED);

                //if ad have passed expiration time dont show 
                if(core::config('advertisement.expire_date') > 0)
                {
                    $ads->where(DB::expr('DATE_ADD( published, INTERVAL '.core::config('advertisement.expire_date').' DAY)'), '>', Date::unix2mysql());
                }

                //make a search with q? param
                if (isset($this->_params['q']) AND strlen($this->_params['q']))
                {
                    $ads->where_open()
                        ->where('title', 'like', '%'.$this->_params['q'].'%')
                        ->or_where('description', 'like', '%'.$this->_params['q'].'%')
                        ->where_close();
                }

                //getting all the ads of a category.
                if ( isset($this->_filter_params['id_category']) AND is_numeric($this->_filter_params['id_category']['value']))
                {
                    $category = new Model_Category($this->_filter_params['id_category']['value']);
                    if ($category->loaded())
                    {
                        $ads->where('id_category', 'in', $category->get_siblings_ids());
                        unset($this->_filter_params['id_category']);
                    }    
                }

                //getting all the ads of a location.
                if ( isset($this->_filter_params['id_location']) AND is_numeric($this->_filter_params['id_location']['value']))
                {
                    $location = new Model_Location($this->_filter_params['id_location']['value']);
                    if ($location->loaded())
                    {
                        $ads->where('id_location', 'in', $location->get_siblings_ids());
                        unset($this->_filter_params['id_location']);
                    }    
                }

                //filter results by param, verify field exists and has a value
                $ads->api_filter($this->_filter_params);

                //how many? used in header X-Total-Count
                $count = $ads->count_all();

                //by default sort by published date
                if(empty($this->_sort))
                    $this->_sort['published'] = 'desc';

                //after counting sort values
                $ads->api_sort($this->_sort);

                //we add the order by in case was specified, this is not a column so we need to do it manually
                if (isset($this->_sort['distance']) AND isset($this->_params['latitude']) AND isset($this->_params['longitude']))
                    $ads->order_by('distance',$this->_sort['distance']);

                //pagination with headers
                $pagination = $ads->api_pagination($count,$this->_params['items_per_page']);

                $ads = $ads->cached()->find_all();

                //as array
                foreach ($ads as $ad)
                {
                    $a = $ad->as_array();
                    $a['price'] = i18n::money_format($ad->price);
                    $a['thumb'] = $ad->get_first_image();
                    $a['customfields'] = Model_Field::get_by_category($ad->id_category);

                    //sorting by distance, lets add it!
                    if (isset($ad->distance))
                        $a['distance'] = i18n::format_measurement($ad->distance);
                    $a['url'] = Route::url('ad', array('category'=>$ad->category->seoname,'seotitle'=>$ad->seotitle));
                    $output[] = $a;
                }

                $this->rest_output(array('ads' => $output),200,$count,($pagination!==FALSE)?$pagination:NULL);
            }
        }
        catch (Kohana_HTTP_Exception $khe)
        {
            $this->_error($khe);
        }
    }

    //get single ad
    public function action_get()
    {
        try
        {
            if (is_numeric($id_ad = $this->request->param('id')))
            {
                $ad = new Model_Ad();

                //get distance to the ad
                if (isset($this->_params['latitude']) AND isset($this->_params['longitude']))
                    $ad->select(array(DB::expr('degrees(acos(sin(radians('.$this->_params['latitude'].')) * sin(radians(`latitude`)) + cos(radians('.$this->_params['latitude'].')) * cos(radians(`latitude`)) * cos(radians(abs('.$this->_params['longitude'].' - `longitude`))))) * 69.172'), 'distance'));
                
                $ad->where('id_ad','=',$id_ad)
                    ->where('status','=',Model_Ad::STATUS_PUBLISHED)
                    ->cached()->find();

                if ($ad->loaded())
                {
                    $a = $ad->as_array();
                    $a['price']  = i18n::money_format($ad->price);
                    $a['images'] = array_values($ad->get_images());
                    $a['category'] = $ad->category->as_array();
                    $a['location'] = $ad->location->as_array();
                    $a['user']     = Controller_Api_Users::get_user_array($ad->user);
                    $a['customfields'] = Model_Field::get_by_category($ad->id_category);
                    //sorting by distance, lets add it!
                    if (isset($ad->distance))
                        $a['distance'] = i18n::format_measurement($ad->distance);
                    $a['url'] = Route::url('ad', array('category'=>$ad->category->seoname,'seotitle'=>$ad->seotitle));
                    $this->rest_output(array('ad' => $a));
                }
                else
                    $this->_error(__('Advertisement not found'),404);
            }
            else
                $this->_error(__('Advertisement not found'),404);
           
        }
        catch (Kohana_HTTP_Exception $khe)
        {
            $this->_error($khe);
        }
       
    }


} // END
