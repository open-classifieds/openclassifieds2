<?php defined('SYSPATH') OR die('No direct script access.');
/**
 * Extended functionality for Kohana View
 *
 * @package    OC
 * @category   View
 * @author     Chema <chema@garridodiaz.com>
 * @copyright  (c) 2009-2013 Open Classifieds Team
 * @license    GPL v3
 */
class View extends Kohana_View{
    
	// TODO: Delete this method and move his code into View::capture
    /**
     * gets a cached fragment view
     * @param  string $name name to use in the cache should be unique
     * @param  string $file file view
     * @param  array $data 
     * @return string       
     */
	public static function fragment($name, $file = NULL, array $data = NULL)
	{
		// If sending new data in View need recache View
		// $fragment_name = UTF8::substr($name, 40);
		$name = sha1(var_export($data, TRUE)).self::fragment_name($name);
		if ( ! empty($file))
		{
			if ( ! $fragment = Core::cache($name)) 
			{
				$fragment = View::capture($file, $data);
				Core::cache($name, $fragment);
			}
			return $fragment;
		}
	}

    /**
     * deletes from cache a fragment
     * @param  string $name 
     * @return bool       
     */
    public static function delete_fragment($name)
    {
        return Core::cache(self::fragment_name($name), NULL, 0);
    }

    
    /**
     * gets the fragment name, unique using i18n theme and skin and cat and loc
     * @param  string $name 
     * @return string       
     */
	public static function fragment_name($name)
	{
		if ( ! is_null(Controller::$category) AND Controller::$category->loaded())
		{
			$cat_seoname = '_category_'.Controller::$category->seoname;
		}
		else
		{
			$cat_seoname = '';
		}

		if ( ! is_null(Controller::$location) AND Controller::$location->loaded())
		{
			$loc_seoname = '_location_'.Controller::$location->seoname;
		}
		else
		{
			$loc_seoname = '';
		}
		
		return 'fragment_'.$name.'_'.i18n::lang().'_'.Theme::$theme.$cat_seoname.$loc_seoname; //.Theme::$skin
	}


    /**
     * Sets the view filename. Override from origianl to use from theme folder
     *
     *     $view->set_filename($file);
     *
     * @param   string  view filename
     * @return  View
     * @throws  View_Exception
     */
	public function set_filename($file)
	{
		// Folder loaded as module in the bootstrap :D
		if ( ! $path = Kohana::find_file(Theme::views_path(), $file))
		{
			// In case view not found try to read from default theme
			if ( ! $path = Kohana::find_file(Theme::default_views_path(), $file))
			{
				$path = $file;
			}
		}
		// Still not found :(, try from cascading system
		return parent::set_filename($path);
	}

}