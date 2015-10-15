<?php defined('SYSPATH') or die('No direct script access.');


class Controller_Api_Favorites extends Api_User {


    /**
     * Handle GET requests.
     */
    public function action_index()
    {
        try
        {
            $favs = new Model_Favorite();

            $favs = $favs->where('id_user','=',$this->user->id_user)
                        ->order_by('created','desc')
                        ->find_all();

            //as array
            $output = array();
            foreach ($favs as $fav)
            {
                $title = $fav->ad->title;
                $fav = $fav->as_array();
                $fav['ad'] = $title;
                $output[] = $fav;
            }

            $this->rest_output(array('favorites' => $output));
           
        }
        catch (Kohana_HTTP_Exception $khe)
        {
            $this->_error($khe);
        }
    }

    public function action_create()
    {
        try
        {
            if (is_numeric($id_ad = $this->request->param('id')))
            {
                if (Model_Favorite::favorite($this->user->id_user,$id_ad)===TRUE)
                    $this->rest_output(__('Favorite'));
                else
                    $this->_error(__('Something went wrong'),501);
            }
            else
                $this->_error(__('Ad not provided'),404);

        }
        catch (Kohana_HTTP_Exception $khe)
        {
            $this->_error($khe);
        }
    }

    public function action_delete()
    {
        try
        {
            if (is_numeric($id_ad = $this->request->param('id')))
            {
                if (Model_Favorite::unfavorite($this->user->id_user,$id_ad)===TRUE)
                {
                    $this->rest_output(__('Deleted'));
                }
                else
                    $this->_error(__('Favorite not found'),404);
            }
            else
                $this->_error(__('Favorite not found'),404);

        }
        catch (Kohana_HTTP_Exception $khe)
        {
            $this->_error($khe);
        }
    }


} // END