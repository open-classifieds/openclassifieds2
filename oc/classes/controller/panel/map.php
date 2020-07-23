<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Panel_Map extends Auth_Controller {


	public function action_index()
	{

        Breadcrumbs::add(Breadcrumb::factory()->set_title(__('Interactive map')));
        $this->template->title = __('Interactive map');

        $this->template->styles              = array('css/map-generator.css' => 'screen');
        $this->template->scripts['footer'][] = '//www.gstatic.com/charts/loader.js';
        $this->template->scripts['footer'][] = '//www.google.com/jsapi';
        $this->template->scripts['footer'][] = '//maps.google.com/maps/api/js?sensor=false';
        $this->template->scripts['footer'][] = 'js/jscolor/jscolor.js';
        $this->template->scripts['footer'][] = 'js/oc-panel/map/map-generator.js';

        $map_active   = Core::post('map_active', Core::config('appearance.map_active') ?? 0);
        $map_settings = Core::post('current_settings', Core::config('appearance.map_settings'));

        // change map
        if( Core::extra_features() == TRUE AND Core::post('jscode') )
        {
            Model_Config::set_value('appearance', 'map_active', Core::post('map_active') ?? 0);
            Model_Config::set_value('appearance','map_settings', Core::post('current_settings'));
            Model_Config::set_value('appearance','map_jscode', Kohana::$_POST_ORIG['jscode']);

            Core::delete_cache();
            Alert::set(Alert::SUCCESS, __('Map saved.'));
        }

		$this->template->content = View::factory('oc-panel/pages/map',array(  'map_active'   => $map_active,
		                                                                      'map_settings' => $map_settings,
		                                                                      ));
	}

}
