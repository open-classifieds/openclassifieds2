<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Api_Ads extends Api_User {

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

                //any status but needs to see your ads ;)
                $ads->where('id_user','=',$this->user->id_user);

                //by default sort by published date
                if(empty($this->_sort))
                    $this->_sort['published'] = 'desc';
                
                //filter results by param, verify field exists and has a value and sort the results
                $ads->api_filter($this->_filter_params)->api_sort($this->_sort);

                //how many? used in header X-Total-Count
                $count = $ads->count_all();

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

    public function action_get()
    {
        try
        {
            if (is_numeric($id_ad = $this->request->param('id')))
            {
                $ad = new Model_Ad($id_ad);
                if ($ad->loaded())
                {
                    if ($ad->id_user == $this->user->id_user)
                    {
                        $a = $ad->as_array();
                        $a['price']  = i18n::money_format($ad->price);
                        $a['images'] = array_values($ad->get_images());
                        $a['category'] = $ad->category->as_array();
                        $a['location'] = $ad->location->as_array();
                        $a['customfields'] = Model_Field::get_by_category($ad->id_category);
                        $a['url'] = Route::url('ad', array('category'=>$ad->category->seoname,'seotitle'=>$ad->seotitle));
                        $this->rest_output(array('ad' => $a));
                    }
                    else
                        $this->_error(__('Not your advertisement'),401);
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

    /**
     * Handle POST requests.
     */
    public function action_create()
    {
        try
        {
            $return = Model_Ad::new_ad($this->_post_params,$this->user);
            
            //there was an error on the validation
            if (isset($return['validation_errors']) AND is_array($return['validation_errors']))
            {
                $errors = '';

                foreach ($return['validation_errors'] as $f => $err) 
                    $errors.=$err.' - ';

                $this->_error($errors);
            }
            elseif (isset($return['error']))
            {
                $this->_error($return['error']);
            }
            //all went good!
            elseif (isset($return['message']) AND isset($return['ad']))
            {
                $ad = $return['ad']->as_array();
                $this->rest_output(array('message'=>$return['message'],'checkout_url'=>$return['checkout_url'],'ad'=>$ad));
            }
                    
        }
        catch (Kohana_HTTP_Exception $khe)
        {
            $this->_error($khe);
        }
       
    }


    /**
     * Handle PUT requests.
     */
    public function action_update()
    {
        try
        {
            if (is_numeric($id_ad = $this->request->param('id')))
            {
                $ad = new Model_Ad();
                $ad->where('id_ad','=',$id_ad)->where('id_user','=',$this->user->id_user)->find();

                if ($ad->loaded())
                {
                    $return = $ad->save_ad($this->_post_params);
            
                    //there was an error on the validation
                    if (isset($return['validation_errors']) AND is_array($return['validation_errors']))
                    {
                        $errors = '';

                        foreach ($return['validation_errors'] as $f => $err) 
                            $errors.=$err.' - ';

                        $this->_error($errors);
                    }
                    elseif (isset($return['error']))
                    {
                        $this->_error($return['error']);
                    }
                    elseif (isset($return['message']))
                    {
                        $this->rest_output($return);
                    }
         
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

    /**
     * Handle DELETE requests.
     * actually just disables the ad ;)
     */
    public function action_delete()
    {
        try
        {
            if (is_numeric($id_ad = $this->request->param('id')))
            {
                $ad = new Model_Ad();
                $ad->where('id_ad','=',$id_ad)->where('id_user','=',$this->user->id_user)->find();

                if ($ad->loaded())
                {
                    if ($ret = $ad->deactivate())
                        $this->rest_output($ret);
                    else
                        $this->_error($ret);         
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

    public function action_image()
    {
        try
        {
            //get image
            $image = $_FILES['image']; //file post

            if (is_numeric($id_ad = $this->request->param('id')))
            {
                $ad = new Model_Ad();
                $ad->where('id_ad','=',$id_ad)->where('id_user','=',$this->user->id_user)->find();

                if ($ad->loaded())
                {
                    if ($ret = $ad->save_image($image))
                        $this->rest_output($ret);
                    else
                        $this->_error($ret);         
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

    public function action_delete_image()
    {
        try
        {

            if (is_numeric($id_ad = $this->request->param('id')) AND is_numeric($num_image = $this->_post_params['num_image']))
            {
                $ad = new Model_Ad();
                $ad->where('id_ad','=',$id_ad)->where('id_user','=',$this->user->id_user)->find();

                if ($ad->loaded())
                {
                    if ($ret = $ad->delete_image($num_image))
                        $this->rest_output($ret);
                    else
                        $this->_error($ret);         
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
