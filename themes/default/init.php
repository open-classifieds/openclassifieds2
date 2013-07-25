<?php defined('SYSPATH') or die('No direct access allowed.');
/**
  * Theme Name: Ocean Free
  * Description: Clean free theme that includes full admin. It has publicity. Do not delete this theme, all the views depend in this theme.
  * Tags: HTML5, Admin, Free
  * Version: 1.6
  * Author: Chema <chema@garridodiaz.com>
  * License: GPL v3
  */


/**
 * placeholders for this theme
 */
Widgets::$theme_placeholders	= array('footer', 'sidebar');


/**
 * styles and themes, loaded in this order
 */

Theme::$styles = array( 'http://netdna.bootstrapcdn.com/bootswatch/2.3.2/cerulean/bootstrap.min.css' => 'screen',
                        'css/styles.css?v=1.5' => 'screen',
                        'css/slider.css' => 'screen',
                        'css/chosen.css' => 'screen',
                        'css/jquery.sceditor.min.css' => 'screen', 
                        'css/bootstrap-image-gallery.css' => 'screen',
                        
        				);


Theme::$scripts['footer']	= array('http://code.jquery.com/jquery-1.9.1.min.js',
                                    'http://netdna.bootstrapcdn.com/twitter-bootstrap/2.3.2/js/bootstrap.min.js',
                                    'js/chosen.jquery.min.js',
                                    'js/bootstrap-slider.js',
                                    'js/jquery.sceditor.min.js',
                                    'js/bootstrap-slider.js',
                                    'js/jqBootstrapValidation.js',
                                    'js/load-image.min.js',
                                    'js/bootstrap-image-gallery.min.js',
                                    'js/theme.init.js?v=1.5',
                                    );


/**
 * custom options for the theme
 * @var array
 */
Theme::$options = array();


/**
 * custom error alerts
 */
Form::$errors_tpl 	= '<div class="alert alert-error"><a class="close" data-dismiss="alert">×</a>
			       		<h4 class="alert-heading">%s</h4>
			        	<ul>%s</ul></div>';

Form::$error_tpl 	= '<div class="alert"><a class="close" data-dismiss="alert">×</a>%s</div>';


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
		<li class="<?=(Request::current()->controller()==$controller 
				&& Request::current()->action()==$action)?'active':''?> <?=$style?>" >
			<a href="<?=Route::url($route,array('controller'=>$controller,
												'action'=>$action))?>">
				<?if($icon!==NULL)?>
                    <i class="<?=$icon?>"></i>
                <?=$name?>
			</a>
		</li>
	<?
}
