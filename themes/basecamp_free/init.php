<?php defined('SYSPATH') or die('No direct access allowed.');
/**
  * Theme Name: Basecamp free
  * Description: The Basecamp free theme offers basic views and options. For more options consider buying the Basecamp premium theme which offers extended customization.
  * Tags: HTML5, Responsive, Mobile
  * Version: 4.0.1
  * Author: Christopher
  * License: Free
  * Parent Theme: default
  * Skins: default, mint, charcoal/gold, plumb
  */


/**
 * placeholders for this theme
 */
Widgets::$theme_placeholders  = array('footer', 'sidebar', 'publish_new');

/**
 * custom options for the theme
 * @var array
 */
Theme::$options = Theme::get_options();

//we load earlier the theme since we need some info
Theme::load();

/**
 * styles and themes, loaded in this order
 */
Theme::$skin = Theme::get('theme');

/**
 * styles and themes, loaded in this order
 */

//local files

$theme_css =  array(      '//cdn.jsdelivr.net/npm/bootstrap@3.4.0/dist/css/bootstrap.min.css' => 'screen',
                          '//use.fontawesome.com/releases/v5.9.0/css/all.css' => 'screen',
                          '//use.fontawesome.com/releases/v5.9.0/css/v4-shims.css' => 'screen',
                          '//cdn.jsdelivr.net/bootstrap.datepicker/0.1/css/datepicker.css' => 'screen',
                          '//cdn.jsdelivr.net/select2/4.0.3/css/select2.min.css' => 'screen',
                          'css/blueimp-gallery.min.css' => 'screen',
                          'css/slider.css' => 'screen',
                          'css/styles.css?v='.Core::VERSION => 'screen',
                  );

if ($body_font = explode('|', Theme::get('body_font'))[0])
  $theme_css = $theme_css + [$body_font => 'screen'];

if (Theme::$skin!='default')
      $theme_css = array_merge($theme_css, array('css/color-'.Theme::$skin.'.css' => 'screen'));

Theme::$styles = $theme_css;

Theme::$scripts['footer'] = array(  '//cdn.jsdelivr.net/combine/npm/jquery@1.12.4,npm/bootstrap@3.4.0/dist/js/bootstrap.min.js,npm/select2@4.0.3,npm/holderjs@2.9.3,npm/jquery-validation@1.15.0',
                                    'js/jquery.blueimp-gallery.min.js',
                                    Route::url('jslocalization', array('controller'=>'jslocalization', 'action'=>'select2')),
                                    Route::url('jslocalization', array('controller'=>'jslocalization', 'action'=>'validate')),
                                    'js/bootstrap-slider.js',
                                    'js/favico.min.js',
                                    'js/curry.js',
                                    'js/bootstrap-datepicker.js',
                                    'js/default.init.js?v='.Core::VERSION,
                                    'js/theme.init.js?v='.Core::VERSION,
                            );

if (Auth::instance()->logged_in() AND
    (Auth::instance()->get_user()->is_admin() OR
        Auth::instance()->get_user()->is_moderator() OR
        Auth::instance()->get_user()->is_translator()))
{
    Theme::$styles['css/bootstrap-editable.css'] = 'screen';
    Theme::$scripts['footer'][] = 'js/bootstrap-editable.min.js';
    Theme::$scripts['footer'][] = 'js/oc-panel/live-translator.js';
}

if (Core::config('general.pusher_notifications')){
    Theme::$styles['//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css'] = 'screen';
    Theme::$scripts['footer'][] = '//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js';
    Theme::$scripts['footer'][] = '//js.pusher.com/4.0/pusher.min.js';
}

if (Core::config('general.algolia_search')){
    Theme::$styles['css/algolia/algolia-autocomplete.css'] = 'screen';
    Theme::$scripts['footer'][] = '//cdn.jsdelivr.net/algoliasearch/3/algoliasearch.min.js';
    Theme::$scripts['footer'][] = '//cdn.jsdelivr.net/autocomplete.js/0/autocomplete.jquery.min.js';
}

if (Core::config('general.sms_auth')){
    Theme::$styles['css/intlTelInput.css'] = 'screen';
    Theme::$scripts['footer'][] = 'js/intlTelInput.min.js';
    Theme::$scripts['footer'][] = 'js/utils.js';
    Theme::$scripts['footer'][] = 'js/phone-auth.js';
}

if (core::config('general.carquery'))
{
    Theme::$scripts['footer'][] = '//www.carqueryapi.com/js/carquery.0.3.4.js';
}

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
