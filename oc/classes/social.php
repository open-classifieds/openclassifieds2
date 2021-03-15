<?php
/**
 * Social auth class
 *
 * @package    OC
 * @category   Core
 * @author     Chema <chema@open-classifieds.com>, Slobodan <slobodan@open-classifieds.com>
 * @copyright  (c) 2009-2013 Open Classifieds Team
 * @license    GPL v3
 */

class Social {

    public static function get()
    {
        $config = json_decode(core::config('social.config'), TRUE);

        if (!is_array($config))
        {
            return [];
        }

        // limit Facebook permission scope
        $config['providers']['Facebook']['scope'] = ['email', 'public_profile'];

        // limit Google permission scope
        $config['providers']['Google']['scope'] = 'https://www.googleapis.com/auth/userinfo.profile https://www.googleapis.com/auth/userinfo.email';

        $config['base_url'] = Route::url('default', ['controller'=>'social','action'=>'login','id'=> 1]);

        return $config;
    }

    public static function get_providers()
    {
        $providers = self::get();

        return (isset($providers['providers']))?$providers['providers']:array();
    }

    public static function enabled_providers()
    {
        $providers         = self::get();
        $enabled_providers = array();

        if (isset($providers['providers']))
        {
            foreach ($providers['providers'] as $k => $provider) {
                if ($provider['enabled'])
                {
                    $enabled_providers[$k] = $providers['providers'][$k];
                }
            }
        }

        return $enabled_providers;
    }

    public static function include_vendor()
    {
        require_once Kohana::find_file('vendor', 'hybridauth/hybridauth/Hybrid/Auth','php');
        require_once Kohana::find_file('vendor', 'hybridauth/hybridauth/Hybrid/Endpoint','php');
        require_once Kohana::find_file('vendor', 'hybridauth/hybridauth/Hybrid/Logger','php');
        require_once Kohana::find_file('vendor', 'hybridauth/hybridauth/Hybrid/Exception','php');
        // require_once Kohana::find_file('vendor', 'hybridauth/vendor/autoload','php'); will uncomment on upgrade
    }

    public static function include_vendor_twitter()
    {
        require_once Kohana::find_file('vendor/', 'codebird-php/codebird');
    }

    public static function social_post_featured_ad(Model_Ad $ad)
    {
        if($ad->status == Model_Ad::STATUS_PUBLISHED AND core::config('advertisement.social_post_only_featured') == TRUE)
        {
            if(core::config('advertisement.twitter'))
                self::twitter($ad);
        }
    }

    public static function post_ad(Model_Ad $ad)
    {
        if($ad->status == Model_Ad::STATUS_PUBLISHED AND core::config('advertisement.social_post_only_featured') == FALSE)
        {
            if(core::config('advertisement.twitter'))
                self::twitter($ad);
        }
    }

    public static function twitter(Model_Ad $ad)
    {
        self::include_vendor_twitter();

        \Codebird\Codebird::setConsumerKey(core::config('advertisement.twitter_consumer_key'), core::config('advertisement.twitter_consumer_secret'));
        $cb = \Codebird\Codebird::getInstance();
        $cb->setToken(core::config('advertisement.access_token'), core::config('advertisement.access_token_secret'));

        $message = Text::limit_chars($ad->title, 17, NULL, TRUE).', ';

        $message .= Text::limit_chars($ad->category->name, 17, NULL, TRUE);

        if($ad->id_location != 1 AND $ad->location->loaded())
        {
            $message .= ' - '.Text::limit_chars($ad->location->name, 17, NULL, TRUE);
        }

        if($ad->price>0)
            $message .= ', '.html_entity_decode(i18n::money_format($ad->price, $ad->currency()));

        $url_ad = Route::url('ad', array('category'=>$ad->category->seoname,'seotitle'=>$ad->seotitle));
        $message .= ' - '.$url_ad;
        $message .= ' - '.self::GenerateHashtags($ad);

        $params = array(
            'status' => $message
        );

        if(isset($ad->latitude) AND $ad->latitude!='' AND isset($ad->longitude) AND $ad->longitude!=''){
            $params['lat'] = $ad->latitude;
            $params['long'] = $ad->longitude;
        }

        $reply = $cb->statuses_update($params);

        if (isset($reply->errors))
        {
            Kohana::$log->add(Log::ERROR, 'Twitter auto-post: ' . $reply->errors[0]->message);
        }
    }

    public static function GetAccessToken()
    {
        if(core::config('advertisement.facebook') == 1
            OR !empty(core::config('advertisement.facebook_app_id'))
            OR !empty(core::config('advertisement.facebook_app_secret'))
            OR !empty(core::config('advertisement.facebook_access_token'))
            OR !empty(core::config('advertisement.facebook_id')))
        {
            $token_url = "https://graph.facebook.com/oauth/access_token?client_id=".core::config('advertisement.facebook_app_id')."&client_secret=".core::config('advertisement.facebook_app_secret')."&grant_type=fb_exchange_token&fb_exchange_token=".core::config('advertisement.facebook_access_token');

            $c = curl_init();
            curl_setopt($c, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($c, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($c, CURLOPT_URL, $token_url);
            $contents = curl_exec($c);
            $err  = curl_getinfo($c,CURLINFO_HTTP_CODE);
            curl_close($c);

            $paramsfb = null;
            parse_str($contents, $paramsfb);

            $paramsfb = json_decode($contents, true);

            if(isset($paramsfb['access_token']) AND !empty($paramsfb['access_token'])){
                Model_Config::set_value('advertisement','facebook_access_token',$paramsfb['access_token']);
            }
        }
    }

    public static function GenerateHashtags(Model_Ad $ad)
    {
        $hashtag1 = '#'.str_replace([' ', "'", '"', '!', '+', '$', '%', '^', '&', '*', '+', '.', ','], '', core::config('general.site_name'));
        $hashtag2 = '#'.str_replace([' ', "'", '"', '!', '+', '$', '%', '^', '&', '*', '+', '.', ','], '', $ad->category->name);
        $hashtag3 = '#'.str_replace([' ', "'", '"', '!', '+', '$', '%', '^', '&', '*', '+', '.', ','], '', $ad->location->name);

        return $hashtag1.' '.$hashtag2.' '.$hashtag3;
    }

}
