<?php defined('SYSPATH') or die('No direct access allowed.');
/**
  * Theme Name: Atlantic Lite
  * Description: Clean free theme that includes full admin. It has publicity. Do not delete this theme, all the views depend in this theme.
  * Tags: HTML5, Free
  * Version: 4.0.1
  * Author: Oliver <oliver@open-classifieds.com>
  * License: GPL v3
  * Skins: default
 */

/**
 * placeholders for this theme
 */
Widgets::$theme_placeholders = ['footer', 'sidebar', 'publish_new'];

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

Theme::$styles = [
    'css/theme.css?v='.Core::VERSION => 'screen',
];

Theme::$scripts['footer'] = [
    'js/theme.js?v='.Core::VERSION,
    Route::url('jslocalization', array('controller'=>'jslocalization', 'action'=>'select2')),
    Route::url('jslocalization', array('controller'=>'jslocalization', 'action'=>'validate')),
    'js/bootstrap-slider.js',
    'js/favico.min.js',
    'js/curry.js',
    'js/bootstrap-datepicker.js',
    'js/default.init.js?v='.Core::VERSION,
    'js/theme.init.js?v='.Core::VERSION,
];

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
Form::$errors_tpl = '
    <div class="alert alert-danger">
        <a class="close" data-dismiss="alert">×</a>
   		<p><strong>%s</strong></p>
    	<ul>%s</ul>
    </div>
';

Form::$error_tpl = '
    <div class="alert ">
        <a class="close" data-dismiss="alert">×</a>
        %s
    </div>';

Alert::$tpl = '
    <div class="alert alert-%s">
		<a class="close" data-dismiss="alert" href="#">×</a>
		<strong>%s:</strong> %s
	</div>';
