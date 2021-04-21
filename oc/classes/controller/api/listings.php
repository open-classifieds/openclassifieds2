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
                if((New Model_Field())->get('expiresat'))
                {
                    $ads->where_open()
                    ->or_where(DB::expr('DATE(cf_expiresat)'), '>', Date::unix2mysql())
                    ->or_where('cf_expiresat','IS',NULL)
                    ->where_close();
                }
                elseif(core::config('advertisement.expire_date') > 0)
                {
                    $ads->where(DB::expr('DATE_ADD( published, INTERVAL '.core::config('advertisement.expire_date').' DAY)'), '>', Date::unix2mysql());
                }

                //if the ad has passed event date don't show
                if((New Model_Field())->get('eventdate'))
                {
                    $ads->where_open()
                    ->or_where(DB::expr('cf_eventdate'), '>', Date::unix2mysql())
                    ->or_where('cf_eventdate','IS',NULL)
                    ->where_close();
                }

                //make a search with q? param
                if (isset($this->_params['q']) AND strlen($this->_params['q']))
                {
                    $ads->where_open();

                    $ads->where('title', 'like', '%'.$this->_params['q'].'%');

                    $ads = $this->search_with_synonyms($ads, 'title', $this->_params['q']);

                    if(core::config('general.search_by_description') == TRUE)
                    {
                        $ads = $this->search_with_synonyms($ads, 'description', $this->_params['q']);
                    }

                    // text searchable custom fields
                    foreach (Model_Field::get_all() as $field_name => $field_options)
                    {
                        if (isset($field_options['text_searchable']) AND $field_options['text_searchable'])
                        {
                            $ads = $this->search_with_synonyms($ads, "cf_{$field_name}", $this->_params['q']);
                        }
                    }

                    $ads->where_close();
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

                //search with lat and long, duplicated, somehow count_all is reseting the query....
                if (isset($this->_params['latitude']) AND isset($this->_params['longitude']))
                {
                    $ads->select(array(DB::expr('degrees(acos(sin(radians('.$this->_params['latitude'].')) * sin(radians(`latitude`)) + cos(radians('.$this->_params['latitude'].')) * cos(radians(`latitude`)) * cos(radians(abs('.$this->_params['longitude'].' - `longitude`))))) * 69.172'), 'distance'))
                    ->where('latitude','IS NOT',NULL)
                    ->where('longitude','IS NOT',NULL);

                    //we add the order by in case was specified, this is not a column so we need to do it manually
                    if (isset($this->_sort['distance']))
                        $ads->order_by('distance',$this->_sort['distance']);
                }

                //by default sort by published date
                if(empty($this->_sort))
                    $this->_sort['published'] = 'desc';

                //after counting sort values
                $ads->api_sort($this->_sort);

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
                    foreach ($a['customfields'] as $key => $values)
                    {
                        if($values['type'] == 'checkbox_group')
                        {
                            foreach ($values['grouped_values'] as $grouped_key => $grouped_value) {
                                $a['customfields']['cf_' . $grouped_key] = $values;
                                $a['customfields']['cf_' . $grouped_key]['label'] = $grouped_value;
                                $a['customfields']['cf_' . $grouped_key]['parent'][$key] = $values;
                                $a['customfields']['cf_' . $grouped_key]['value'] = $a['cf_'. $grouped_key];
                            }

                            unset($a['customfields'][$key]);

                            continue;
                        }

                        $a['customfields'][$key]['value'] = $a[$key];
                    }

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
                    foreach ($a['customfields'] as $key => $values)
                    {
                        if($values['type'] == 'checkbox_group')
                        {
                            foreach ($values['grouped_values'] as $grouped_key => $grouped_value) {
                                $a['customfields']['cf_' . $grouped_key] = $values;
                                $a['customfields']['cf_' . $grouped_key]['label'] = $grouped_value;
                                $a['customfields']['cf_' . $grouped_key]['parent'][$key] = $values;
                                $a['customfields']['cf_' . $grouped_key]['value'] = $a['cf_'. $grouped_key];
                            }

                            unset($a['customfields'][$key]);

                            continue;
                        }

                        $a['customfields'][$key]['value'] = $a[$key];
                    }
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

    protected function search_with_synonyms(Kohana_ORM $ads, $field, $text)
    {
        /**
         * Expected word_synonyms theme option value:
         *
         * car,auto,automobile,coche,van
         * bike,bycicle,scooter
         */

        $synonym_groups = str_replace("\r", '', Theme::get('word_synonyms')); // remove carriage returns

        if (empty($synonym_groups))
        {
            return $ads;
        }

        $synonym_groups = explode(PHP_EOL, $synonym_groups);

        if (! is_array($synonym_groups))
        {
            return $ads;
        }

        $ads->or_where($field, 'like', "%{$text}%");

        foreach ($synonym_groups as $synonym_group)
        {
            $synonyms = explode(',', $synonym_group);

            if (! is_array($synonyms))
            {
                continue;
            }

            foreach ($synonyms as $synonym)
            {
                if (empty($synonym))
                {
                    continue;
                }

                if (strpos($text, $synonym) !== FALSE)
                {
                    foreach ($synonyms as $replace_synonym)
                    {
                        $text_with_replaced_synonym = str_replace($synonym, $replace_synonym, $text);

                        $ads->or_where($field, 'like', "%{$text_with_replaced_synonym}%");
                    }
                }
            }
        }

        return $ads;
    }


} // END
