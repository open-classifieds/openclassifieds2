<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Map extends Controller {

	public function action_index()
	{
        $this->before('/pages/maps');

        $this->template->title  = __('Map');

        $this->template->height = Core::get('height','100%');
        $this->template->width  = Core::get('width','100%');
        $this->template->zoom   = Core::get('zoom',core::config('advertisement.map_zoom'));
        $this->template->height_thumb = Core::config('image.height_thumb')/4;
        $this->template->width_thumb = Core::config('image.width_thumb')/4;

        if (Model_User::get_userlatlng())
        {
            $this->template->center_lon = $_COOKIE['mylng'];
            $this->template->center_lat = $_COOKIE['mylat'];
        }
        else
        {
            $this->template->center_lon = Core::get('lon',core::config('advertisement.center_lon'));
            $this->template->center_lat = Core::get('lat',core::config('advertisement.center_lat'));
        }

        $ads = new Model_Ad();

        $ads->where('status','=',Model_Ad::STATUS_PUBLISHED)
            ->where('address','IS NOT',NULL)
            ->where('latitude','IS NOT',NULL)
            ->where('longitude','IS NOT',NULL);

        //filter by category
        if (core::get('category')!==NULL)
        {
            $category = new Model_Category();
            $category->where('seoname','=',core::get('category'))->cached()->limit(1)->find();
            if ($category->loaded())
                $ads->where('id_category', 'IN', $category->get_siblings_ids());
        }

        //filter by location
        if (core::get('location')!==NULL)
        {
            $location = new Model_location();
            $location->where('seoname','=',core::get('location'))->cached()->limit(1)->find();
            if ($location->loaded())
                $ads->where('id_location', 'IN', $location->get_siblings_ids());
        }

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

        // filter by distance
        if (core::request('userpos') == 1 AND Model_User::get_userlatlng())
        {
            if (is_numeric(Core::cookie('mydistance')) AND Core::cookie('mydistance') <= 500)
                $location_distance = Core::config('general.measurement') == 'imperial' ? (Num::round(Core::cookie('mydistance') * 1.60934)) : Core::cookie('mydistance');
            else
                $location_distance = Core::config('general.measurement') == 'imperial' ? (Num::round(Core::config('advertisement.auto_locate_distance') * 1.60934)) : Core::config('advertisement.auto_locate_distance');
            $ads->where(DB::expr('degrees(acos(sin(radians('.$_COOKIE['mylat'].')) * sin(radians(`latitude`)) + cos(radians('.$_COOKIE['mylat'].')) * cos(radians(`latitude`)) * cos(radians(abs('.$_COOKIE['mylng'].' - `longitude`))))) * 111.321'),'<=',$location_distance);
        }

        //if only 1 ad
        if (is_numeric(core::get('id_ad')))
            $ads = $ads->where('id_ad','=',core::get('id_ad'));

        $ads = $ads->order_by('published','desc')
                ->limit(Core::config('advertisement.map_elements'))
                ->find_all();

        // if user
        if (is_numeric(core::get('id_user')))
        {
            $user = new Model_User();
            $user->where('id_user','=',core::get('id_user'))
                ->where('latitude','IS NOT',NULL)
                ->where('longitude','IS NOT',NULL)
                ->where('address','IS NOT',NULL)
                ->cached()
                ->limit(1)
                ->find();

            if ($user->loaded())
            {
                $this->template->user = $user;
            }
        }

        $this->template->ads = $ads;

	}

    /**
     * get geocode lat/lon points for given address from google
     *
     * @param string $address
     * @return bool|array false if can't be geocoded, array or geocdoes if successful
     */
    public static function address_coords($address)
    {
        $url = 'http://maps.google.com/maps/api/geocode/json?sensor=false&address='.rawurlencode($address);

        //try to get the json from the cache
        $coords = Core::cache($url);

            //not cached :(
        if ($coords === NULL)
        {
            $coords = FALSE;

            //get contents from google
            if($result = core::curl_get_contents($url))
            {
                $result = json_decode($result);

                //not found :()
                if($result->status!="OK")
                    $coords = FALSE;
                else
                {
                    $coords['lat'] = $result->results[0]->geometry->location->lat;
                    $coords['lon'] = $result->results[0]->geometry->location->lng;
                }
            }

            //save the json
            Core::cache($url,$coords,strtotime('+7 day'));
        }

        return $coords;
    }


    public function action_index2()
    {
        require_once Kohana::find_file('vendor', 'php-googlemap/GoogleMap','php');

        $this->before('/pages/maps');

        $this->template->title  = __('Map');

        $height = Core::get('height','100%');
        $width  = Core::get('width','100%');

        $map = new GoogleMapAPI();
        $map->setWidth($width);
        $map->setHeight($height);
        $map->disableSidebar();
        $map->setMapType('map');
        $map->setZoomLevel(Core::get('zoom',core::config('advertisement.map_zoom')));

        //$map->mobile = TRUE;
        $atributes = array("target='_blank'");
        if ( core::get('controls')==0 )
        {
            $map->disableMapControls();
            $map->disableTypeControls();
            $map->disableScaleControl();
            $map->disableZoomEncompass();
            $map->disableStreetViewControls();
            $map->disableOverviewControl();
        }

        //only 1 marker
        if ( core::get('address')!='' )
        {
            $map->addMarkerByAddress(core::get('address'), core::get('address'));
        }
        else
        {

            //last ads, you can modify this value at: advertisement.feed_elements
            $ads = DB::select('a.seotitle')
                    ->select(array('c.seoname','category'),'a.title','a.published','a.address')
                    ->from(array('ads', 'a'))
                    ->join(array('categories', 'c'),'INNER')
                    ->on('a.id_category','=','c.id_category')
                    ->where('a.status','=',Model_Ad::STATUS_PUBLISHED)
                    ->where('a.address','IS NOT',NULL)
                    ->order_by('published','desc')
                    ->limit(Core::config('advertisement.map_elements'))
                    ->as_object()
                    ->cached()
                    ->execute();


            foreach($ads as $a)
            {
                //d($a);
                if (strlen($a->address)>3)
                {
                    $url= Route::url('ad',  array('category'=>$a->category,'seotitle'=>$a->seotitle));
                    $map->addMarkerByAddress($a->address, $a->title, HTML::anchor($url, $a->title, $atributes) );
                }
            }

            //only center if not a single ad
            $map->setCenterCoords(Core::get('lon',core::config('advertisement.center_lon')),Core::get('lat',core::config('advertisement.center_lat')));
        }

        $this->template->map = $map;

    }



} // End map
