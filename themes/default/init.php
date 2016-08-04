<?php defined('SYSPATH') or die('No direct access allowed.');
/**
  * Theme Name: Ocean Free
  * Description: Clean free theme that includes full admin. It has publicity. Do not delete this theme, all the views depend in this theme.
  * Tags: HTML5, Admin, Free
  * Version: 2.1.1
  * Author: Chema <chema@garridodiaz.com> , <slobodan.josifovic@gmail.com>
  * License: GPL v3
  */


/**
 * placeholders for this theme
 */
Widgets::$theme_placeholders	= array('footer', 'sidebar');


/**
 * styles and themes, loaded in this order
 */

Theme::$styles = array( 'http://netdna.bootstrapcdn.com/bootstrap/3.0.2/css/bootstrap.min.css' => 'screen',
                        'css/styles.css?v=2.1.1' => 'screen',
                        'http://cdn.jsdelivr.net/bootstrap.datepicker/0.1/css/datepicker.css' => 'screen',
                        'css/slider.css' => 'screen',
                        'http://cdn.jsdelivr.net/chosen/1.0.0/chosen.css' => 'screen',
                        'css/jquery.sceditor.min.css' => 'screen',
                        'http://blueimp.github.io/Gallery/css/blueimp-gallery.min.css' => 'screen', 
                        'css/bootstrap-image-gallery.min.css' => 'screen',
        				);


Theme::$scripts['footer']	= array('http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js',
                                    'http://netdna.bootstrapcdn.com/bootstrap/3.0.2/js/bootstrap.min.js',
                                    'js/bootstrap-slider.js',
                                    'http://cdn.jsdelivr.net/chosen/1.0.0/chosen.jquery.min.js',
                                    'js/jquery.sceditor.min.js',
                                    'http://blueimp.github.io/Gallery/js/jquery.blueimp-gallery.min.js',
                                    'js/bootstrap-image-gallery.min.js',
                                    'http://ajax.aspnetcdn.com/ajax/jquery.validate/1.11.1/jquery.validate.min.js',
                                    'http://cdn.jsdelivr.net/bootstrap.datepicker/0.1/js/bootstrap-datepicker.js',
                                    'js/theme.init.js?v=2.1.1',
                                    );


/**
 * custom options for the theme
 * @var array
 */
Theme::$options = array();


/**
 * custom error alerts
 */
Form::$errors_tpl 	= '<div class="alert alert-danger"><a class="close" data-dismiss="alert">×</a>
			       		<h4 class="alert-heading">%s</h4>
			        	<ul>%s</ul></div>';

Form::$error_tpl 	= '<div class="alert "><a class="close" data-dismiss="alert">×</a>%s</div>';


Alert::$tpl 	= 	'<div class="alert alert-%s">
					<a class="close" data-dismiss="alert" href="#">×</a>
					<h4 class="alert-heading">%s</h4>%s
					</div>';


/**
 * Theme Functions
 * 
 */


/**
 * nav_link generates a link for main nav-bar
 * @param  string $name       translated name in the A
 * @param  string $controller
 * @param  string $action  
 * @param  string $icon 		class name of bootstrap	icon to append with nav-link   
 * @param  string $route      
 * @param  string $style extra class div 
 */
function nav_link($name, $controller, $icon=NULL, $action='index', $route='default' , $style = NULL)
{	
	
 	?>
		<li alt="<?=$route?>" title="<?=$route?>" class="<?=(Request::current()->controller()==$controller 
				&& Request::current()->action()==$action)?'active':''?> <?=$style?>" >
			<a  href="<?=Route::url($route,array('controller'=>$controller,
												'action'=>$action))?>">
				<?if($icon!==NULL)?>
                    <i class="<?=$icon?>"></i>
                <?=$name?>
			</a>
		</li>
	<?
}
