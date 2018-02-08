<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Update controllers
 *
 * @package    OC
 * @category   Update
 * @author     Chema <chema@open-classifieds.com>, Slobodan <slobodan@open-classifieds.com>
 * @copyright  (c) 2009-2014 Open Classifieds Team
 * @license    GPL v3
 */
class Controller_Panel_Update extends Auth_Controller {

    public function action_340()
    {
        //new configs
        $configs = array(

            array( 'config_key'     => 'zenith_testing',
                   'group_name'     => 'payment',
                   'config_value'   => '0'),
            array( 'config_key'     => 'zenith_merchantid',
                   'group_name'     => 'payment',
                   'config_value'   => ''),
            array( 'config_key'     => 'zenith_uid',
                   'group_name'     => 'payment',
                   'config_value'   => ''),
            array( 'config_key'     => 'zenith_pwd',
                   'group_name'     => 'payment',
                   'config_value'   => ''),
            array( 'config_key'     => 'zenith_merchant_name',
                   'group_name'     => 'payment',
                   'config_value'   => ''),
            array( 'config_key'     => 'zenith_merchant_phone',
                   'group_name'     => 'payment',
                   'config_value'   => ''),
            array( 'config_key'     => 'carquery',
                   'group_name'     => 'general',
                   'config_value'   => '0'),
            array( 'config_key'     => 'payline_testing',
                   'group_name'     => 'payment',
                   'config_value'   => '0'),
            array( 'config_key'     => 'payline_merchant_id',
                   'group_name'     => 'payment',
                   'config_value'   => ''),
            array( 'config_key'     => 'payline_access_key',
                   'group_name'     => 'payment',
                   'config_value'   => ''),
            array( 'config_key'     => 'payline_contract_number',
                   'group_name'     => 'payment',
                   'config_value'   => ''),
            array( 'config_key'     => 'oauth2_enabled',
                   'group_name'     => 'social',
                   'config_value'   => 0),
            array( 'config_key'     => 'oauth2_client_id',
                   'group_name'     => 'social',
                   'config_value'   => ''),
            array( 'config_key'     => 'oauth2_client_secret',
                   'group_name'     => 'social',
                   'config_value'   => ''),
            array( 'config_key'     => 'oauth2_url_authorize',
                   'group_name'     => 'social',
                   'config_value'   => ''),
            array( 'config_key'     => 'oauth2_url_access_token',
                   'group_name'     => 'social',
                   'config_value'   => ''),
            array( 'config_key'     => 'oauth2_url_resource_owner_details',
                   'group_name'     => 'social',
                   'config_value'   => ''),
            array( 'config_key'     => 'homepage_map',
                   'group_name'     => 'advertisement',
                   'config_value'   => '0'),
            array( 'config_key'     => 'homepage_map_height',
                   'group_name'     => 'advertisement',
                   'config_value'   => ''),
            array( 'config_key'     => 'homepage_map_allowfullscreen',
                   'group_name'     => 'advertisement',
                   'config_value'   => '1'),
            );



        Model_Config::config_array($configs);

        try
        {
            DB::query(Database::UPDATE,"ALTER TABLE  `".self::$db_prefix."users` ADD  `latitude`  float(10,6) DEFAULT NULL")->execute();
            DB::query(Database::UPDATE,"ALTER TABLE  `".self::$db_prefix."users` ADD  `longitude`  float(10,6) DEFAULT NULL")->execute();
            DB::query(Database::UPDATE,"ALTER TABLE  `".self::$db_prefix."users` ADD  `address`  varchar(145) DEFAULT NULL")->execute();
            DB::query(Database::UPDATE,"ALTER TABLE  `".self::$db_prefix."ads` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;")->execute();
            DB::query(Database::UPDATE,"ALTER TABLE  `".self::$db_prefix."ads` CHANGE  `price`  `price` DECIMAL(28,8) NOT NULL DEFAULT '0.000'")->execute();
            DB::query(Database::UPDATE,"ALTER TABLE  `".self::$db_prefix."categories` CHANGE  `price`  `price` DECIMAL(28,8) NOT NULL DEFAULT '0'")->execute();
            DB::query(Database::UPDATE,"ALTER TABLE  `".self::$db_prefix."orders` CHANGE  `amount`  `amount` DECIMAL(28,8) NOT NULL DEFAULT '0'")->execute();
            DB::query(Database::UPDATE,"ALTER TABLE  `".self::$db_prefix."subscribers` CHANGE  `min_price`  `min_price` DECIMAL(28,8) NOT NULL DEFAULT '0'")->execute();
            DB::query(Database::UPDATE,"ALTER TABLE  `".self::$db_prefix."subscribers` CHANGE  `max_price`  `max_price` DECIMAL(28,8) NOT NULL DEFAULT '0'")->execute();
            DB::query(Database::UPDATE,"ALTER TABLE  `".self::$db_prefix."messages` CHANGE  `price`  `price` DECIMAL(28,8) NOT NULL DEFAULT '0'")->execute();
            DB::query(Database::UPDATE,"ALTER TABLE  `".self::$db_prefix."coupons` CHANGE  `discount_amount`  `discount_amount` DECIMAL(28,8) NOT NULL DEFAULT '0'")->execute();
            DB::query(Database::UPDATE,"ALTER TABLE  `".self::$db_prefix."coupons` CHANGE  `discount_percentage`  `discount_percentage` DECIMAL(28,8) NOT NULL DEFAULT '0'")->execute();
            DB::query(Database::UPDATE,"ALTER TABLE  `".self::$db_prefix."plans` CHANGE  `price`  `price` DECIMAL(28,8) NOT NULL DEFAULT '0'")->execute();
            DB::query(Database::UPDATE,"ALTER TABLE  `".self::$db_prefix."plans` CHANGE  `marketplace_fee`  `marketplace_fee` DECIMAL(28,8) NOT NULL DEFAULT '0'")->execute();
        }catch (exception $e) {}


        //delete bitcoin from stripe
        try
        {
            DB::query(Database::DELETE,"DELETE FROM ".self::$db_prefix."config WHERE `config_key` = 'stripe_bitcoin'")->execute();
        }catch (exception $e) {}

        File::replace_file(APPPATH.'config/database.php',"'utf8'","'utf8mb4'");

    }

    public function action_330()
    {
        //new configs
        $configs = array(

                        array( 'config_key'     => 'subscriptions_expire',
                               'group_name'     => 'general',
                               'config_value'   => '0'),
                        array( 'config_key'     => 'pusher_notifications_cluster',
                               'group_name'     => 'general',
                               'config_value'   => 'eu'),
                        array( 'config_key'     => 'sms_auth',
                               'group_name'     => 'general',
                               'config_value'   => '0'),
                        array( 'config_key'     => 'sms_clickatell_api',
                               'group_name'     => 'general',
                               'config_value'   => ''),
                        array( 'config_key'     => 'login_to_view_ad',
                               'group_name'     => 'advertisement',
                               'config_value'   => 0),
                        array( 'config_key'     => 'delete_ad',
                               'group_name'     => 'advertisement',
                               'config_value'   => 0),
                        array( 'config_key'     => 'upload_from_url',
                               'group_name'     => 'image',
                               'config_value'   => 0),
                        array( 'config_key'     => 'country',
                               'group_name'     => 'general',
                               'config_value'   => ''),
                        );

        Model_Config::config_array($configs);

        //user phone number
        try
        {
            DB::query(Database::UPDATE,"ALTER TABLE  `".self::$db_prefix."users` ADD  `phone` varchar(30) DEFAULT NULL")->execute();
        }catch (exception $e) {}

    }

    public function action_320()
    {
        File::delete(DOCROOT.'oc/classes/database/mysqli');
        File::delete(DOCROOT.'oc/classes/database/query.php');
        File::delete(DOCROOT.'oc/classes/image');
        File::delete(DOCROOT.'oc/common');


        $email_service = (Core::config('email.elastic_active') == 1 ? 'elastic': ( Core::config('email.smtp_active') == 1?'smtp':'mail' ) );

        //new configs
        $configs = array(
                        array( 'config_key'     => 'service',
                               'group_name'     => 'email',
                               'config_value'   => $email_service),
                        array( 'config_key'     => 'instagram',
                               'group_name'     => 'advertisement',
                               'config_value'   => '0'),
                        array( 'config_key'     => 'instagram_username',
                               'group_name'     => 'advertisement',
                               'config_value'   => ''),
                        array( 'config_key'     => 'instagram_password',
                               'group_name'     => 'advertisement',
                               'config_value'   => ''),
                        array( 'config_key'     => 'pinterest',
                               'group_name'     => 'advertisement',
                               'config_value'   => '0'),
                        array( 'config_key'     => 'pinterest_app_id',
                               'group_name'     => 'advertisement',
                               'config_value'   => ''),
                        array( 'config_key'     => 'pinterest_app_secret',
                               'group_name'     => 'advertisement',
                               'config_value'   => ''),
                        array( 'config_key'     => 'pinterest_access_token',
                               'group_name'     => 'advertisement',
                               'config_value'   => ''),
                        array( 'config_key'     => 'pinterest_board',
                               'group_name'     => 'advertisement',
                               'config_value'   => ''),
                        array( 'config_key'     => 'paytabs_merchant_email',
                               'group_name'     => 'payment',
                               'config_value'   => ''),
                        array( 'config_key'     => 'paytabs_secret_key',
                               'group_name'     => 'payment',
                               'config_value'   => ''),
                        array( 'config_key'     => 'payfast_merchant_id',
                               'group_name'     => 'payment',
                               'config_value'   => ''),
                        array( 'config_key'     => 'payfast_merchant_key',
                               'group_name'     => 'payment',
                               'config_value'   => ''),
                        array( 'config_key'     => 'payfast_sandbox',
                               'group_name'     => 'payment',
                               'config_value'   => '0'),
                        array( 'config_key'     => 'pusher_notifications',
                               'group_name'     => 'general',
                               'config_value'   => '0'),
                        array( 'config_key'     => 'pusher_notifications_app_id',
                               'group_name'     => 'general',
                               'config_value'   => ''),
                        array( 'config_key'     => 'pusher_notifications_key',
                               'group_name'     => 'general',
                               'config_value'   => ''),
                        array( 'config_key'     => 'pusher_notifications_secret',
                               'group_name'     => 'general',
                               'config_value'   => ''),
                        array( 'config_key'     => 'algolia_search',
                               'group_name'     => 'general',
                               'config_value'   => '0'),
                        array( 'config_key'     => 'algolia_search_application_id',
                               'group_name'     => 'general',
                               'config_value'   => ''),
                        array( 'config_key'     => 'algolia_search_admin_key',
                               'group_name'     => 'general',
                               'config_value'   => ''),
                        array( 'config_key'     => 'algolia_search_only_key',
                               'group_name'     => 'general',
                               'config_value'   => ''),
                        array( 'config_key'     => 'algolia_powered_by_enabled',
                               'group_name'     => 'general',
                               'config_value'   => '1'),
                        );

        Model_Config::config_array($configs);

        //modify only the plans that are wrong
        try
        {
            DB::query(Database::UPDATE,"UPDATE ".self::$db_prefix."plans SET id_plan=id_plan+100 WHERE id_plan < 100")->execute();
            DB::query(Database::UPDATE,"ALTER TABLE  `".self::$db_prefix."plans` AUTO_INCREMENT=100")->execute();
        }catch (exception $e) {}

        //crontab re-index algolia indices
        try
        {
            DB::query(Database::UPDATE,"INSERT INTO `".self::$db_prefix."crontab` (`name`, `period`, `callback`, `params`, `description`, `active`) VALUES
                                    ('Algolia Search re-index', '0 * * * *', 'Cron_Algolia::reindex', NULL, 'Re-index everything', 1);")->execute();
        }catch (exception $e) {}
    }


    /**
     * This function will upgrade DB that didn't existed in versions prior to 3.1.0
     */
    public function action_310()
    {
        //new configs
        $configs = array(
                        array( 'config_key'     => 'elastic_listname',
                               'group_name'     => 'email',
                               'config_value'   => ''),
                        array( 'config_key'     => 'dropbox_app_key',
                               'group_name'     => 'advertisement',
                               'config_value'   => ''),
                        array( 'config_key'     => 'expire_reactivation',
                               'group_name'     => 'advertisement',
                               'config_value'   => '1'),
                        array( 'config_key'     => 'social_post_only_featured',
                               'group_name'     => 'advertisement',
                               'config_value'   => ''),
                        array( 'config_key'     => 'twitter_consumer_key',
                               'group_name'     => 'advertisement',
                               'config_value'   => ''),
                        array( 'config_key'     => 'twitter_consumer_secret',
                               'group_name'     => 'advertisement',
                               'config_value'   => ''),
                        array( 'config_key'     => 'access_token',
                               'group_name'     => 'advertisement',
                               'config_value'   => ''),
                        array( 'config_key'     => 'access_token_secret',
                               'group_name'     => 'advertisement',
                               'config_value'   => ''),
                        array( 'config_key'     => 'twitter',
                               'group_name'     => 'advertisement',
                               'config_value'   => '0'),
                        array( 'config_key'     => 'facebook_app_id',
                               'group_name'     => 'advertisement',
                               'config_value'   => ''),
                        array( 'config_key'     => 'facebook_app_secret',
                               'group_name'     => 'advertisement',
                               'config_value'   => ''),
                        array( 'config_key'     => 'facebook_access_token',
                               'group_name'     => 'advertisement',
                               'config_value'   => ''),
                        array( 'config_key'     => 'facebook',
                               'group_name'     => 'advertisement',
                               'config_value'   => '0'),
                        array( 'config_key'     => 'facebook_id',
                               'group_name'     => 'advertisement',
                               'config_value'   => ''),
                        array( 'config_key'     => 'picker_api_key',
                               'group_name'     => 'advertisement',
                               'config_value'   => ''),
                        array( 'config_key'     => 'picker_client_id',
                               'group_name'     => 'advertisement',
                               'config_value'   => ''),
                        );

        Model_Config::config_array($configs);

        //crontab generate FB access token
        try
        {
            DB::query(Database::UPDATE,"INSERT INTO `".self::$db_prefix."crontab` (`name`, `period`, `callback`, `params`, `description`, `active`) VALUES
                                    ('Generate Access Token', '10 9 1 * *', 'Social::GetAccessToken', NULL, 'Generate Facebook long-lived Access Token.', 1);")->execute();
        }catch (exception $e) {}

        //visits table tmp
        try
        {
            DB::query(Database::UPDATE,"CREATE TABLE IF NOT EXISTS ".self::$db_prefix."visits_tmp (
                                        id_visit int(10) unsigned NOT NULL AUTO_INCREMENT,
                                        id_ad int(10) unsigned DEFAULT NULL,
                                        hits int(10) NOT NULL DEFAULT '0',
                                        contacts int(10) NOT NULL DEFAULT '0',
                                        created DATE NOT NULL,
                                        PRIMARY KEY (id_visit),
                                        UNIQUE KEY ".self::$db_prefix."visits_IK_id_ad_AND_created (id_ad,created)
                                        ) ENGINE=InnoDB;")->execute();
        }catch (exception $e) {}

        //move to tempo table
        try
        {
            DB::query(Database::UPDATE,"INSERT INTO ".self::$db_prefix."visits_tmp (id_ad, hits, contacts, created)
                                        SELECT id_ad, count(id_ad) hits,sum(contacted) contacts, DATE(created) created
                                        FROM ".self::$db_prefix."visits
                                        GROUP BY id_ad, DATE(created)
                                        HAVING hits>0
                                        ORDER BY DATE(created) ASC;")->execute();
        }catch (exception $e) {}

        //rename tables, we keep old one...just in case!
        try
        {
            DB::query(Database::UPDATE,"RENAME TABLE ".self::$db_prefix."visits TO ".self::$db_prefix."visits_old;")->execute();
        }catch (exception $e) {}

        try
        {
            DB::query(Database::UPDATE,"RENAME TABLE ".self::$db_prefix."visits_tmp TO ".self::$db_prefix."visits;")->execute();
        }catch (exception $e) {}



    }

    /**
     * This function will upgrade DB that didn't existed in versions prior to 3.1.0
     */
    public function action_300()
    {
        //new configs
        $configs = array(

                        array( 'config_key'     => 'hide_homepage_categories',
                               'group_name'     => 'general',
                               'config_value'   => '{}'),
                        array( 'config_key'     => 'paguelofacil_cclw',
                               'group_name'     => 'payment',
                               'config_value'   => ''),
                        array( 'config_key'     => 'paguelofacil_testing',
                               'group_name'     => 'payment',
                               'config_value'   => '0'),
                        array( 'config_key'     => 'mercadopago_client_id',
                               'group_name'     => 'payment',
                               'config_value'   => ''),
                        array( 'config_key'     => 'mercadopago_client_secret',
                               'group_name'     => 'payment',
                               'config_value'   => ''),
                        array( 'config_key'     => 'contact_price',
                               'group_name'     => 'advertisement',
                               'config_value'   => '1'),
                        array( 'config_key'     => 'report',
                               'group_name'     => 'advertisement',
                               'config_value'   => '1'),
                        array( 'config_key'     => 'stripe_3d_secure',
                               'group_name'     => 'payment',
                               'config_value'   => '0'),
                        array( 'config_key'     => 'vat_country',
                               'group_name'     => 'payment',
                               'config_value'   => ''),
                        array( 'config_key'     => 'vat_number',
                               'group_name'     => 'payment',
                               'config_value'   => ''),
                        );

        //get theme license and add it to the config
        if (Theme::get('license')!==NULL)
        {
            $configs[]= array( 'config_key'     => 'date',
                               'group_name'     => 'license',
                               'config_value'   => Theme::get('license_date')
                               );

            $configs[]= array( 'config_key'     => 'number',
                               'group_name'     => 'license',
                               'config_value'   => Theme::get('license')
                               );
        }

        try
        {
            DB::query(Database::UPDATE,"UPDATE ".self::$db_prefix."content SET description='Hello Admin,\n\n [EMAIL.SENDER]: [EMAIL.FROM], have a message for you:\n\n [EMAIL.SUBJECT]\n\n [EMAIL.BODY] \n\n Regards!' WHERE seotitle='contact-admin'")->execute();
        }catch (exception $e) {}

        //crontab renew subscription
        try
        {
            DB::query(Database::UPDATE,"INSERT INTO `".self::$db_prefix."crontab` (`name`, `period`, `callback`, `params`, `description`, `active`) VALUES
                                    ('Notify new updates', '0 9 * * 1', 'Cron_Update::notify', NULL, 'Notify by email of new site updates.', 1);")->execute();
        }catch (exception $e) {}

        //stripe agreement
        try
        {
            DB::query(Database::UPDATE,"ALTER TABLE  `".self::$db_prefix."users` ADD `stripe_agreement` varchar(40) DEFAULT NULL")->execute();
        }catch (exception $e) {}

        //VAT
        try
        {
            DB::query(Database::UPDATE,"ALTER TABLE  `".self::$db_prefix."orders` ADD `VAT` varchar(20) DEFAULT NULL")->execute();
            DB::query(Database::UPDATE,"ALTER TABLE  `".self::$db_prefix."orders` ADD `VAT_country` varchar(20) DEFAULT NULL")->execute();
            DB::query(Database::UPDATE,"ALTER TABLE  `".self::$db_prefix."orders` ADD `VAT_number` varchar(20) DEFAULT NULL")->execute();
        }catch (exception $e) {}

        Model_Config::config_array($configs);
    }

    /**
     * This function will upgrade DB that didn't existed in versions prior to 2.9.0
     */
    public function action_290()
    {

        //new configs
        $configs = array(

                        array( 'config_key'     => 'robokassa_login',
                               'group_name'     => 'payment',
                               'config_value'   => ''),
                        array( 'config_key'     => 'robokassa_pass1',
                               'group_name'     => 'payment',
                               'config_value'   => ''),
                        array( 'config_key'     => 'robokassa_pass2',
                               'group_name'     => 'payment',
                               'config_value'   => ''),
                        array( 'config_key'     => 'robokassa_testing',
                               'group_name'     => 'payment',
                               'config_value'   => '0'),
                        array( 'config_key'     => 'notify_name',
                               'group_name'     => 'email',
                               'config_value'   => 'no-reply '.core::config('general.site_name')),
                        );

        //adds Vkontakte login
        try
        {
            DB::query(Database::UPDATE,"UPDATE `".self::$db_prefix."config` SET `config_value`= REPLACE(`config_value`,'},\"base_url\"',',\"Vkontakte\":{\"enabled\":\"0\",\"keys\":{\"id\":\"\",\"secret\":\"\"}}},\"base_url\"') WHERE `group_name` = 'social' AND `config_key`='config'")->execute();
        }catch (exception $e) {}

        Model_Config::config_array($configs);
    }

    /**
     * This function will upgrade DB that didn't existed in versions prior to 2.8.0
     */
    public function action_280()
    {
        //google 2 step auth
        try
        {
            DB::query(Database::UPDATE,"ALTER TABLE  `".self::$db_prefix."users` ADD `google_authenticator` varchar(40) DEFAULT NULL")->execute();
        }catch (exception $e) {}

        //fixes yahoo login
        try
        {
            DB::query(Database::UPDATE,"UPDATE `".self::$db_prefix."config` SET `config_value`= REPLACE(`config_value`,',\"Yahoo\":{\"enabled\":\"0\",\"keys\":{\"id\":',',\"Yahoo\":{\"enabled\":\"0\",\"keys\":{\"key\":') WHERE `group_name` = 'social' AND `config_key`='config' AND `config_value` LIKE '%,\"Yahoo\":{\"enabled\":\"0\",\"keys\":{\"id\":%'")->execute();
            DB::query(Database::UPDATE,"UPDATE `".self::$db_prefix."config` SET `config_value`= REPLACE(`config_value`,',\"Yahoo\":{\"enabled\":\"1\",\"keys\":{\"id\":',',\"Yahoo\":{\"enabled\":\"1\",\"keys\":{\"key\":') WHERE `group_name` = 'social' AND `config_key`='config' AND `config_value` LIKE '%,\"Yahoo\":{\"enabled\":\"1\",\"keys\":{\"id\":%'")->execute();
        }catch (exception $e) {}

        //new configs
        $configs = array(
                        array( 'config_key'     => 'rich_snippets',
                               'group_name'     => 'advertisement',
                               'config_value'   => '0'),
                        array( 'config_key'     => 'google_authenticator',
                               'group_name'     => 'general',
                               'config_value'   => '0'),
                        array( 'config_key'     => 'private_site',
                               'group_name'     => 'general',
                               'config_value'   => '0'),
                        array( 'config_key'     => 'private_site_page',
                               'group_name'     => 'general',
                               'config_value'   => ''),
                        array( 'config_key'     => 'securepay_merchant',
                               'group_name'     => 'payment',
                               'config_value'   => ''),
                        array( 'config_key'     => 'securepay_password',
                               'group_name'     => 'payment',
                               'config_value'   => ''),
                        array( 'config_key'     => 'securepay_testing',
                               'group_name'     => 'payment',
                               'config_value'   => '0'),
                        array( 'config_key'     => 'gm_api_key',
                               'group_name'     => 'advertisement',
                               'config_value'   => ''),
                        );

        Model_Config::config_array($configs);
    }

    /**
     * This function will upgrade DB that didn't existed in versions prior to 2.7.0
     */
    public function action_270()
    {
        //plans
        try
        {
            DB::query(Database::UPDATE,"CREATE TABLE IF NOT EXISTS `".self::$db_prefix."plans` (
                                      `id_plan` int(10) unsigned NOT NULL AUTO_INCREMENT,
                                      `name` varchar(145) NOT NULL,
                                      `seoname` varchar(145) NOT NULL,
                                      `description` longtext NOT NULL,
                                      `price` decimal(14,3) NOT NULL DEFAULT '0',
                                      `days` int(10) DEFAULT 1,
                                      `amount_ads` int(10) DEFAULT 1,
                                      `marketplace_fee` decimal(14,3) NOT NULL DEFAULT '0',
                                      `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
                                      `status` tinyint(1) NOT NULL DEFAULT '0',
                                      PRIMARY KEY (`id_plan`),
                                      UNIQUE KEY `".self::$db_prefix."plan_UK_seoname` (`seoname`)
                                    ) ENGINE=MyISAM AUTO_INCREMENT=100;")->execute();
        }catch (exception $e) {}

        //subscriptions
        try
        {
            DB::query(Database::UPDATE,"CREATE TABLE IF NOT EXISTS `".self::$db_prefix."subscriptions` (
                                      `id_subscription` int(10) unsigned NOT NULL AUTO_INCREMENT,
                                      `id_order` int(10) unsigned NOT NULL,
                                      `id_user` int(10) unsigned NOT NULL,
                                      `id_plan` int(10) unsigned NOT NULL,
                                      `amount_ads` int(10) DEFAULT 1,
                                      `amount_ads_left` int(10) DEFAULT 0,
                                      `expire_date` DATETIME  NULL,
                                      `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
                                      `status` tinyint(1) NOT NULL DEFAULT '0',
                                      PRIMARY KEY (`id_subscription`)
                                    ) ENGINE=MyISAM ;")->execute();
        }catch (exception $e) {}

        //crontab renew subscription
        try
        {
            DB::query(Database::UPDATE,"INSERT INTO `".self::$db_prefix."crontab` (`name`, `period`, `callback`, `params`, `description`, `active`) VALUES
                                    ('Renew subscription', '*/5 * * * *', 'Cron_Subscription::renew', NULL, 'Notify by email user subscription will expire. Deactivates current subscription', 1);")->execute();
        }catch (exception $e) {}


        //SMTP ssl
        try
        {
            DB::query(Database::UPDATE,"INSERT INTO `".self::$db_prefix."config` (`group_name`, `config_key`, `config_value`) VALUES ('email', 'smtp_secure', (SELECT IF(config_value=0,'','ssl') as config_value FROM `".self::$db_prefix."config`as oconf WHERE `config_key` = 'smtp_ssl' AND `group_name`='email' LIMIT 1) );")->execute();
        }catch (exception $e) {}

        try
        {
            DB::query(Database::UPDATE,"DELETE FROM `".self::$db_prefix."config` WHERE `config_key` = 'smtp_ssl' AND `group_name`='email' LIMIT 1;")->execute();
        }catch (exception $e) {}

        //stripe connect
        try
        {
            DB::query(Database::UPDATE,"ALTER TABLE  `".self::$db_prefix."users` ADD `stripe_user_id` varchar(140) DEFAULT NULL")->execute();
        }catch (exception $e) {}

        // update buyer instructions
        try
        {
            DB::query(Database::UPDATE,"UPDATE `".self::$db_prefix."content` SET description=CONCAT(description,'\n\n[BUYER.INSTRUCTIONS]') WHERE `seotitle` = 'ads-purchased' AND `description` NOT LIKE '%[BUYER.INSTRUCTIONS]'")->execute();
        }catch (exception $e) {}

        //location.id_geoname column
        try
        {
            DB::query(Database::UPDATE,"ALTER TABLE  `".self::$db_prefix."locations` ADD `id_geoname` int(10) UNSIGNED NULL DEFAULT NULL")->execute();
        }catch (exception $e) {}

        //location.fcodename_geoname column
        try
        {
            DB::query(Database::UPDATE,"ALTER TABLE  `".self::$db_prefix."locations` ADD `fcodename_geoname` varchar(140) NULL DEFAULT NULL")->execute();
        }catch (exception $e) {}

        //new configs
        $configs = array(

                        array( 'config_key'     => 'stripe_bitcoin',
                               'group_name'     => 'payment',
                               'config_value'   => '0'),
                        array( 'config_key'     => 'stripe_appfee',
                               'group_name'     => 'payment',
                               'config_value'   => '0'),
                        array( 'config_key'     => 'stripe_connect',
                               'group_name'     => 'payment',
                               'config_value'   => '0'),
                        array( 'config_key'     => 'stripe_clientid',
                               'group_name'     => 'payment',
                               'config_value'   => ''),
                        array( 'config_key'     => 'free',
                               'group_name'     => 'advertisement',
                               'config_value'   => '0'),
                        array( 'config_key'     => 'subscriptions',
                               'group_name'     => 'general',
                               'config_value'   => '0'),
                        );

        Model_Config::config_array($configs);

         //new mails
        $contents = array(array('order'=>0,
                                'title'=>'There is a new reply on the forum',
                               'seotitle'=>'new-forum-answer',
                               'description'=>"There is a new reply on a forum post where you participated.<br><br><a target=\"_blank\" href=\"[FORUM.LINK]\">Check it here</a><br><br>[FORUM.LINK]<br>",
                               'from_email'=>core::config('email.notify_email'),
                               'type'=>'email',
                               'status'=>'1'),
                            array('order'=>0,
                                'title'=>'Your plan [PLAN.NAME] has expired',
                               'seotitle'=>'plan-expired',
                               'description'=>"Hello [USER.NAME],Your plan [PLAN.NAME] has expired \n\nPlease renew your plan here [URL.CHECKOUT]",
                               'from_email'=>core::config('email.notify_email'),
                               'type'=>'email',
                               'status'=>'1'),
                        );

        Model_Content::content_array($contents);


    }

    /**
     * This function will upgrade DB that didn't existed in versions prior to 2.6.1
     */
    public function action_261()
    {
        //remove innodb
        try
        {
            DB::query(Database::UPDATE,"ALTER TABLE `".self::$db_prefix."ads` DROP FOREIGN KEY `".self::$db_prefix."ads_FK_id_user_AT_users`")->execute();
            DB::query(Database::UPDATE,"ALTER TABLE `".self::$db_prefix."ads` DROP FOREIGN KEY `".self::$db_prefix."ads_FK_id_category_AT_categories`")->execute();
        }catch (exception $e) {}
        try
        {
            DB::query(Database::UPDATE,"ALTER TABLE `".self::$db_prefix."ads` ENGINE = MyISAM")->execute();
        }catch (exception $e) {}
        try
        {
            DB::query(Database::UPDATE,"ALTER TABLE `".self::$db_prefix."locations` ENGINE = MyISAM")->execute();
        }catch (exception $e) {}
        try
        {
            DB::query(Database::UPDATE,"ALTER TABLE `".self::$db_prefix."categories` ENGINE = MyISAM")->execute();
        }catch (exception $e) {}
        try
        {
            DB::query(Database::UPDATE,"ALTER TABLE `".self::$db_prefix."users` ENGINE = MyISAM")->execute();
        }catch (exception $e) {}


        //new configs
        $configs = array(
                        array( 'config_key'     => 'email_domains',
                               'group_name'     => 'general',
                               'config_value'   => ''),
                        array( 'config_key'     => 'cron',
                               'group_name'     => 'general',
                               'config_value'   => '0'),
                        array( 'config_key'     => 'paysbuy',
                               'group_name'     => 'payment',
                               'config_value'   => ''),
                        array( 'config_key'     => 'paysbuy_sandbox',
                               'group_name'     => 'payment',
                               'config_value'   => '0'),
                        array( 'config_key'     => 'validate_banned_words',
                               'group_name'     => 'advertisement',
                               'config_value'   => '0'),
                        );

        Model_Config::config_array($configs);
    }

    /**
     * This function will upgrade DB that didn't existed in versions prior to 2.6.0
     */
    public function action_260()
    {
        //Cron update
        try
        {
            DB::query(Database::UPDATE,"UPDATE `".self::$db_prefix."crontab` SET period='30 */1 * * *' WHERE callback='Cron_Ad::expired_featured' LIMIT 1")->execute();
        }catch (exception $e) {}

        //improve performance table visits
        try
        {
            DB::query(Database::UPDATE,"ALTER TABLE `".self::$db_prefix."visits` DROP `ip_address`")->execute();
        }catch (exception $e) {}
        try
        {
            DB::query(Database::UPDATE,"ALTER TABLE `".self::$db_prefix."visits` DROP INDEX oc2_visits_IK_id_user")->execute();
        }catch (exception $e) {}

        //redo users rates
        try
        {
            DB::query(Database::UPDATE,"UPDATE ".self::$db_prefix."users u SET rate=(SELECT AVG(".self::$db_prefix."reviews.rate) rates
                                                                                            FROM ".self::$db_prefix."reviews
                                                                                            RIGHT JOIN ".self::$db_prefix."ads
                                                                                            USING (id_ad)
                                                                                            WHERE ".self::$db_prefix."ads.id_user = u.id_user AND ".self::$db_prefix."reviews.status = 1
                                                                                            GROUP BY ".self::$db_prefix."reviews.id_ad);")->execute();
        }catch (exception $e) {}

        //make posts bigger description
        try
        {
            DB::query(Database::UPDATE,"ALTER TABLE `".self::$db_prefix."posts` CHANGE `description` `description` LONGTEXT;")->execute();
        }catch (exception $e) {}

        try
        {
            DB::query(Database::UPDATE,"ALTER TABLE `".self::$db_prefix."content` CHANGE `description` `description` LONGTEXT;")->execute();
        }catch (exception $e) {}

        //bigger configs
        try
        {
            DB::query(Database::UPDATE,"ALTER TABLE `".self::$db_prefix."config` CHANGE `config_value` `config_value` LONGTEXT;")->execute();
        }catch (exception $e) {}

        //new configs
        $configs = array(
                        array( 'config_key'     => 'description',
                               'group_name'     => 'advertisement',
                               'config_value'   => '1'),
                        array( 'config_key'     => 'social_auth',
                               'group_name'     => 'general',
                               'config_value'   => '1'),
                        array( 'config_key'     => 'map_style',
                               'group_name'     => 'advertisement',
                               'config_value'   => ''),
                        array( 'config_key'     => 'adblock',
                               'group_name'     => 'general',
                               'config_value'   => '0'),
                        array( 'config_key'     => 'stripe_alipay',
                               'group_name'     => 'payment',
                               'config_value'   => '0'),
                        array( 'config_key'     => 'auto_locate_distance',
                               'group_name'     => 'advertisement',
                               'config_value'   => '100'),
                        );

        Model_Config::config_array($configs);
    }

    /**
     * This function will upgrade DB that didn't existed in versions prior to 2.5.1
     */
    public function action_251()
    {
        //CF users searchable admin privilege option to false if didnt exists
        $cf_users = Model_UserField::get_all();
        foreach ($cf_users as $name => $options)
        {
            $modified = FALSE;
            if(!isset($options['searchable']))
            {
                $options['searchable'] = FALSE;
                $modified = TRUE;
            }
            if(!isset($options['admin_privilege']))
            {
                $options['admin_privilege'] = FALSE;
                $modified = TRUE;
            }
            if ($modified === TRUE)
            {
                $field  = new Model_UserField();
                $field->update($name, ($options['values'] ? implode(',',$options['values']) : null), $options);
            }
        }

        //change latitude/longitude data type length
        try
        {
            DB::query(Database::UPDATE,"ALTER TABLE  `".self::$db_prefix."ads` CHANGE `latitude` `latitude` FLOAT(10, 6) NULL DEFAULT NULL")->execute();
        }catch (exception $e) {}

        try
        {
            DB::query(Database::UPDATE,"ALTER TABLE  `".self::$db_prefix."ads` CHANGE `longitude` `longitude` FLOAT(10, 6) NULL DEFAULT NULL")->execute();
        }catch (exception $e) {}

        try
        {
            DB::query(Database::UPDATE,"ALTER TABLE  `".self::$db_prefix."locations` CHANGE `latitude` `latitude` FLOAT(10, 6) NULL DEFAULT NULL")->execute();
        }catch (exception $e) {}

        try
        {
            DB::query(Database::UPDATE,"ALTER TABLE  `".self::$db_prefix."locations` CHANGE `longitude` `longitude` FLOAT(10, 6) NULL DEFAULT NULL")->execute();
        }catch (exception $e) {}

        // set to NULL latitude and longitude ads with longitude and longitude equal to 0
        try
        {
            DB::query(Database::UPDATE,"UPDATE ".self::$db_prefix."ads SET latitude=NULL, longitude=NULL WHERE latitude='0' AND longitude='0'")->execute();
        }catch (exception $e) {}

        //messages status
        try
        {
            DB::query(Database::UPDATE,"ALTER TABLE  `".self::$db_prefix."messages` ADD `status_to` tinyint(1) NOT NULL DEFAULT '0'")->execute();
        }catch (exception $e) {}

        try
        {
            DB::query(Database::UPDATE,"ALTER TABLE  `".self::$db_prefix."messages` ADD `status_from` tinyint(1) NOT NULL DEFAULT '0'")->execute();
        }catch (exception $e) {}

        //do something with status to migrate to status_from

        try
        {
            DB::query(Database::UPDATE,"UPDATE `".self::$db_prefix."messages` SET `status_from`=`status` , `status_to`=`status`")->execute();
        }catch (exception $e) {}

        try
        {
            DB::query(Database::UPDATE,"ALTER TABLE  `".self::$db_prefix."messages` DROP `status`")->execute();
        }catch (exception $e) {}

        //new configs
        $configs = array(
                        array( 'config_key'     => 'measurement',
                               'group_name'     => 'general',
                               'config_value'   => 'metric'),
                        array( 'config_key'     => 'leave_alert',
                               'group_name'     => 'advertisement',
                               'config_value'   => '1'),
                        );

        Model_Config::config_array($configs);
    }

    /**
     * This function will upgrade DB that didn't existed in versions prior to 2.5.0
     */
    public function action_250()
    {
        //htaccess remove old redirects for API
        if (is_writable($htaccess_file = DOCROOT.'.htaccess'))
        {
            //get the entire htaccess
            $htaccess = file_get_contents($htaccess_file);

            //get from and to we want to delete form the file
            $search_header  = '# Redirects from 1.x to 2.0.x structure';
            $search_footer  = '# End redirects';

            //its in the file?
            if (strpos($htaccess,$search_header)!==FALSE AND strpos($htaccess,$search_footer)!==FALSE)
            {
                //get unique lines in an array
                $lines = explode(PHP_EOL,$htaccess);

                //we remove lines between header and footer
                if (is_array($lines) AND core::count($lines)>5)
                {
                    //which KEY int he array is its of the items?
                    $header_line = array_search($search_header, $lines);
                    $footer_line = array_search($search_footer, $lines);

                    //remove each line....
                    foreach (range($header_line,$footer_line) as $key => $number)
                        unset($lines[$number]);

                    //generate the new file from the array
                    File::write($htaccess_file,implode(PHP_EOL,$lines));

                }//we could get the lines as array

            }//end found strings

        }//end if is_writable


        //new configs
        $configs = array(
                        array( 'config_key'     =>'api_key',
                               'group_name'     =>'general',
                               'config_value'   => Text::random('alnum', 32)),
                        array( 'config_key'     =>'twocheckout_sid',
                               'group_name'     =>'payment',
                               'config_value'   => ''),
                        array( 'config_key'     =>'twocheckout_secretword',
                               'group_name'     =>'payment',
                               'config_value'   => ''),
                        array( 'config_key'     =>'twocheckout_sandbox',
                               'group_name'     =>'payment',
                               'config_value'   => 0),
                        array( 'config_key'     =>'messaging',
                               'group_name'     =>'general',
                               'config_value'   => 0),
                        array( 'config_key'     =>'gcm_apikey',
                               'group_name'     =>'general',
                               'config_value'   => ''),
                        array( 'config_key'     =>'fraudlabspro',
                               'group_name'     =>'payment',
                               'config_value'   => ''),
                        array( 'config_key'     =>'contact_page',
                               'group_name'     =>'general',
                               'config_value'   => ''),
                        array( 'config_key'     =>'description_bbcode',
                               'group_name'     =>'advertisement',
                               'config_value'   => '1'),
                        );

        Model_Config::config_array($configs);


        //api token
        try
        {
            DB::query(Database::UPDATE,"ALTER TABLE  `".self::$db_prefix."users` ADD `api_token` varchar(40) DEFAULT NULL")->execute();
        }catch (exception $e) {}

        try
        {
            DB::query(Database::UPDATE,"ALTER TABLE  `".self::$db_prefix."users` ADD CONSTRAINT `oc2_users_UK_api_token` UNIQUE (`api_token`)")->execute();
        }catch (exception $e) {}

        //notification date
        try
        {
            DB::query(Database::UPDATE,"ALTER TABLE  `".self::$db_prefix."users` ADD `notification_date` DATETIME NULL DEFAULT NULL ;")->execute();
        }catch (exception $e) {}

        //device ID
        try
        {
            DB::query(Database::UPDATE,"ALTER TABLE  `".self::$db_prefix."users` ADD `device_id` varchar(255) DEFAULT NULL")->execute();
        }catch (exception $e) {}

        //favorited counter
        try
        {
            DB::query(Database::UPDATE,"ALTER TABLE  `".self::$db_prefix."ads` ADD `favorited` INT(10) UNSIGNED NOT NULL DEFAULT '0'")->execute();
        }catch (exception $e) {}

        //crontab ad to expire
        try
        {
            DB::query(Database::UPDATE,"INSERT INTO `".self::$db_prefix."crontab` (`name`, `period`, `callback`, `params`, `description`, `active`) VALUES
                                    ('About to Expire Ad', '05 9 * * *', 'Cron_Ad::to_expire', NULL, 'Notify by email your ad is about to expire', 1);")->execute();
        }catch (exception $e) {}


        //new mails
        $contents = array(array('order'=>0,
                                'title'=>'Your ad [AD.NAME] is going to expire',
                               'seotitle'=>'ad-to-expire',
                               'description'=>"Hello [USER.NAME],Your ad [AD.NAME] will expire soon \n\nPlease check your ad here [URL.EDITAD]",
                               'from_email'=>core::config('email.notify_email'),
                               'type'=>'email',
                               'status'=>'1'),
                          array('order'=>0,
                                'title'=>'Password Changed [SITE.NAME]',
                                'seotitle'=>'password-changed',
                                'description'=>"Hello [USER.NAME],\n\nYour password has been changed.\n\nThese are now your user details:\nEmail: [USER.EMAIL]\nPassword: [USER.PWD]\n\nWe do not have your original password anymore.\n\nRegards!",
                                'from_email'=>core::config('email.notify_email'),
                                'type'=>'email',
                                'status'=>'1'),
                          array('order'=>0,
                                'title'=>'New reply: [TITLE]',
                                'seotitle'=>'messaging-reply',
                                'description'=>'[URL.QL]\n\n[DESCRIPTION]',
                                'from_email'=>core::config('email.notify_email'),
                                'type'=>'email',
                                'status'=>'1'),
                          array('order'=>0,
                                'title'=>'[FROM.NAME] sent you a direct message',
                                'seotitle'=>'messaging-user-contact',
                                'description'=>'Hello [TO.NAME],\n\n[FROM.NAME] have a message for you:\n\n[DESCRIPTION]\n\n[URL.QL]\n\nRegards!',
                                'from_email'=>core::config('email.notify_email'),
                                'type'=>'email',
                                'status'=>'1'),
                          array('order'=>0,
                                'title'=>'Hello [TO.NAME]!',
                                'seotitle'=>'messaging-ad-contact',
                                'description'=>'You have been contacted regarding your advertisement:\n\n`[AD.NAME]`.\n\nUser [FROM.NAME], have a message for you:\n\n[DESCRIPTION]\n\n[URL.QL]\n\nRegards!',
                                'from_email'=>core::config('email.notify_email'),
                                'type'=>'email',
                                'status'=>'1'),
                        );

        Model_Content::content_array($contents);

        //messages
        try
        {
            DB::query(Database::UPDATE,"CREATE TABLE IF NOT EXISTS ".self::$db_prefix."messages (
                                      `id_message` int(10) unsigned NOT NULL AUTO_INCREMENT,
                                      `id_ad` int(10) unsigned DEFAULT NULL,
                                      `id_message_parent` int(10) unsigned DEFAULT NULL,
                                      `id_user_from` int(10) unsigned NOT NULL,
                                      `id_user_to` int(10) unsigned NOT NULL,
                                      `message` text NOT NULL,
                                      `price` decimal(14,3) NOT NULL DEFAULT '0',
                                      `read_date` datetime  DEFAULT NULL,
                                      `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
                                      `status` tinyint(1) NOT NULL DEFAULT 0,
                                      PRIMARY KEY (id_message) USING BTREE
                                    ) ENGINE=MyISAM ;")->execute();
        }catch (exception $e) {}


        //coupons
        try
        {
            DB::query(Database::UPDATE,"CREATE TABLE IF NOT EXISTS `".self::$db_prefix."coupons` (
                                      `id_coupon` int(10) unsigned NOT NULL AUTO_INCREMENT,
                                      `id_product` int(10) unsigned NULL DEFAULT NULL,
                                      `name` varchar(145) NOT NULL,
                                      `notes` varchar(245) DEFAULT NULL,
                                      `discount_amount` decimal(14,3) NOT NULL DEFAULT '0',
                                      `discount_percentage` decimal(14,3) NOT NULL DEFAULT '0',
                                      `number_coupons` int(10) DEFAULT NULL,
                                      `valid_date` DATETIME  NULL,
                                      `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
                                      `status` tinyint(1) NOT NULL DEFAULT '0',
                                      PRIMARY KEY (`id_coupon`),
                                      UNIQUE KEY `".self::$db_prefix."coupons_UK_name` (`name`)
                                    ) ENGINE=MyISAM")->execute();
        }catch (exception $e) {}

        try
        {
            DB::query(Database::UPDATE,"ALTER TABLE  `".self::$db_prefix."orders` ADD `id_coupon` INT NULL DEFAULT NULL")->execute();
        }catch (exception $e) {}
        //end coupons


        //myads access
        try
        {
            DB::query(Database::UPDATE,"INSERT INTO  `".self::$db_prefix."access` (`id_role`, `access`) VALUES
                                                                         (1, 'myads.*'),(5, 'myads.*'),(7, 'myads.*')")->execute();
        }catch (exception $e) {}

        //messages access
        try
        {
            DB::query(Database::UPDATE,"INSERT INTO  `".self::$db_prefix."access` (`id_role`, `access`) VALUES
                                                                         (1, 'messages.*'),(5, 'messages.*'),(7, 'messages.*')")->execute();
        }catch (exception $e) {}

        //set favorites count
        $ads = new Model_Ad();
        $ads = $ads->find_all();

        if (core::count($ads))
        {
            foreach ($ads as $ad)
            {
                $ad->favorited = $ad->favorites->count_all();

                try
                {
                    $ad->save();
                }
                catch (Exception $e)
                {
                    throw HTTP_Exception::factory(500,$e->getMessage());
                }
            }
        }

    }

    /**
     * This function will upgrade DB that didn't existed in versions prior to 2.4.1
     */
    public function action_241()
    {
    }

    /**
     * This function will upgrade DB that didn't existed in versions prior to 2.4.0
     */
    public function action_240()
    {
        //new configs
        $configs = array(
                        array( 'config_key'     =>'subscribe',
                               'group_name'     =>'general',
                               'config_value'   => 0),
                        array( 'config_key'     =>'cookie_consent',
                               'group_name'     =>'general',
                               'config_value'   => 0),
                        array( 'config_key'     =>'sharing',
                               'group_name'     =>'advertisement',
                               'config_value'   => 0),
                        array( 'config_key'     =>'logbee',
                               'group_name'     =>'advertisement',
                               'config_value'   => 0),
                        array( 'config_key'     =>'thanks_page',
                               'group_name'     =>'advertisement',
                               'config_value'   => ''),
                        array( 'config_key'     =>'auto_locate',
                               'group_name'     =>'general',
                               'config_value'   => 0),
                        array( 'config_key'     =>'search_multi_catloc',
                               'group_name'     =>'general',
                               'config_value'   => 0),
                        array( 'config_key'     =>'featured_plans',
                               'group_name'     =>'payment',
                               'config_value'   => '{"5":"10"}'),
                        array( 'config_key'     =>'user_fields',
                               'group_name'     =>'user',
                               'config_value'   => '{}'),
                        );

        Model_Config::config_array($configs);

        //locations latitude/longitude
        try
        {
            DB::query(Database::UPDATE,"ALTER TABLE  `".self::$db_prefix."locations` ADD `latitude` DOUBLE NULL , ADD `longitude` DOUBLE NULL ;")->execute();
        }catch (exception $e) {}

        //ads latitude/longitude
        try
        {
            DB::query(Database::UPDATE,"ALTER TABLE  `".self::$db_prefix."ads` ADD `latitude` DOUBLE NULL , ADD `longitude` DOUBLE NULL ;")->execute();
        }catch (exception $e) {}

        //featured days on orders
        try
        {
            DB::query(Database::UPDATE,"ALTER TABLE  `".self::$db_prefix."orders` ADD `featured_days` int(10) unsigned DEFAULT 0")->execute();
        }catch (exception $e) {}

        //update pay as feature, create one in the array
        $price = core::config('payment.pay_to_go_on_feature');
        $days  = core::config('payment.featured_days');

        Model_Order::set_featured_plan($days,$price);

        Model_Config::set_value('payment','pay_to_go_on_feature',1);


    }

    /**
     * This function will upgrade DB that didn't existed in versions prior to 2.3.1
     */
    public function action_231()
    {
        //deleted classes moved to common
        File::delete(DOCROOT.'oc/classes/bitpay.php');
        File::delete(DOCROOT.'oc/classes/paymill.php');
        File::delete(DOCROOT.'oc/classes/stripeko.php');
        File::delete(DOCROOT.'themes/default/views/pages/authorize/button.php');
        File::delete(DOCROOT.'themes/default/views/pages/bitpay/button_loged.php');
        File::delete(DOCROOT.'themes/default/views/pages/paymill/button_loged.php');


    }

    /**
     * This function will upgrade DB that didn't existed in versions prior to 2.3.0
     */
    public function action_230()
    {
        //Cron update
        try
        {
            DB::query(Database::UPDATE,"UPDATE `".self::$db_prefix."crontab` SET period='00 3 * * *' WHERE callback='Sitemap::generate' LIMIT 1")->execute();
            DB::query(Database::UPDATE,"UPDATE `".self::$db_prefix."crontab` SET period='00 5 * * *' WHERE callback='Core::delete_cache' LIMIT 1")->execute();
            DB::query(Database::UPDATE,"UPDATE `".self::$db_prefix."crontab` SET period='00 4 1 * *' WHERE callback='Core::optimize_db' LIMIT 1")->execute();
            DB::query(Database::UPDATE,"UPDATE `".self::$db_prefix."crontab` SET period='00 7 * * *' WHERE callback='Cron_Ad::unpaid' LIMIT 1")->execute();
            DB::query(Database::UPDATE,"UPDATE `".self::$db_prefix."crontab` SET period='00 8 * * *' WHERE callback='Cron_Ad::expired_featured' LIMIT 1")->execute();
            DB::query(Database::UPDATE,"UPDATE `".self::$db_prefix."crontab` SET period='00 9 * * *' WHERE callback='Cron_Ad::expired' LIMIT 1")->execute();

        }catch (exception $e) {}

        //control login attempts
        try
        {
            DB::query(Database::UPDATE,"ALTER TABLE  `".self::$db_prefix."users` ADD `last_failed` DATETIME NULL DEFAULT NULL ;")->execute();
        }catch (exception $e) {}

        try
        {
            DB::query(Database::UPDATE,"ALTER TABLE  `".self::$db_prefix."users` ADD `failed_attempts` int(10) unsigned DEFAULT 0")->execute();
        }catch (exception $e) {}

        //categories/locations/users/ads has_image/last_modified
        try
        {
            DB::query(Database::UPDATE,"ALTER TABLE  `".self::$db_prefix."categories` ADD `last_modified` DATETIME NULL DEFAULT NULL ;")->execute();
        }catch (exception $e) {}

        try
        {
            DB::query(Database::UPDATE,"ALTER TABLE  `".self::$db_prefix."categories` ADD `has_image` TINYINT( 1 ) NOT NULL DEFAULT '0' ;")->execute();
        }catch (exception $e) {}

        try
        {
            DB::query(Database::UPDATE,"ALTER TABLE  `".self::$db_prefix."locations` ADD `last_modified` DATETIME NULL DEFAULT NULL ;")->execute();
        }catch (exception $e) {}

        try
        {
            DB::query(Database::UPDATE,"ALTER TABLE  `".self::$db_prefix."locations` ADD `has_image` TINYINT( 1 ) NOT NULL DEFAULT '0' ;")->execute();
        }catch (exception $e) {}

        try
        {
            DB::query(Database::UPDATE,"ALTER TABLE  `".self::$db_prefix."users` ADD `has_image` TINYINT( 1 ) NOT NULL DEFAULT '0' ;")->execute();
        }catch (exception $e) {}

        try
        {
            DB::query(Database::UPDATE,"ALTER TABLE  `".self::$db_prefix."ads` ADD `last_modified` DATETIME NULL DEFAULT NULL ;")->execute();
        }catch (exception $e) {}

        //new configs
        $configs = array(
                        array( 'config_key'     =>'aws_s3_active',
                               'group_name'     =>'image',
                               'config_value'   => 0),
                        array( 'config_key'     =>'aws_access_key',
                               'group_name'     =>'image',
                               'config_value'   =>''),
                        array( 'config_key'     =>'aws_secret_key',
                               'group_name'     =>'image',
                               'config_value'   =>''),
                        array( 'config_key'     =>'aws_s3_bucket',
                               'group_name'     =>'image',
                               'config_value'   =>''),
                        array( 'config_key'     =>'aws_s3_domain',
                               'group_name'     =>'image',
                               'config_value'   =>0),
                        array( 'config_key'     =>'disallow_nudes',
                               'group_name'     =>'image',
                               'config_value'   =>0),
                        array( 'config_key'     =>'html_head',
                               'group_name'     =>'general',
                               'config_value'   =>''),
                        array( 'config_key'     =>'html_footer',
                               'group_name'     =>'general',
                               'config_value'   =>''),
                        array( 'config_key'     =>'login_to_contact',
                               'group_name'     =>'advertisement',
                               'config_value'   => 0),
                        array( 'config_key'     =>'custom_css',
                               'group_name'     =>'appearance',
                               'config_value'   => 0),
                        array( 'config_key'     =>'custom_css_version',
                               'group_name'     =>'appearance',
                               'config_value'   => 0),
                        array( 'config_key'     =>'only_admin_post',
                               'group_name'     =>'advertisement',
                               'config_value'   => 0),
                        array( 'config_key'     =>'map_active',
                               'group_name'     =>'appearance',
                               'config_value'   => 1),
                        array( 'config_key'     =>'map_jscode',
                               'group_name'     =>'appearance',
                               'config_value'   =>''),
                        array( 'config_key'     =>'map_settings',
                               'group_name'     =>'appearance',
                               'config_value'   =>''),
                        array( 'config_key'     =>'recaptcha_active',
                               'group_name'     =>'general',
                               'config_value'   =>''),
                        array( 'config_key'     =>'recaptcha_secretkey',
                               'group_name'     =>'general',
                               'config_value'   =>''),
                        array( 'config_key'     =>'recaptcha_sitekey',
                               'group_name'     =>'general',
                               'config_value'   =>''),
                        );

        Model_Config::config_array($configs);

        //upgrade has_image field to use it as images count
        $ads = new Model_Ad();
        $ads = $ads->where('has_images','>',0)->find_all();

        if(core::count($ads))
        {
            foreach ($ads as $ad)
            {
                $ad->has_images = 0;//begin with 0 images
                $route = $ad->image_path();
                $folder = DOCROOT.$route;
                $image_keys = array();

                if(is_dir($folder))
                {
                    //retrive ad pictures
                    foreach (new DirectoryIterator($folder) as $file)
                    {
                        if(!$file->isDot())
                        {
                            $key = explode('_', $file->getFilename());
                            $key = end($key);
                            $key = explode('.', $key);
                            $key = (isset($key[0])) ? $key[0] : NULL ;
                            if(is_numeric($key))
                            {
                                if (strpos($file->getFilename(), 'thumb_') === 0)
                                {
                                    $image_keys[] = $key;
                                }
                            }
                        }
                    }

                    //count images and reordering file names
                    if (core::count($image_keys))
                    {
                        asort($image_keys);

                        foreach ($image_keys as $image_key)
                        {
                            $ad->has_images++;

                            @rename($folder.$ad->seotitle.'_'.$image_key.'.jpg', $folder.$ad->seotitle.'_'.$ad->has_images.'.jpg');
                            @rename($folder.'thumb_'.$ad->seotitle.'_'.$image_key.'.jpg', $folder.'thumb_'.$ad->seotitle.'_'.$ad->has_images.'.jpg');
                        }
                    }
                }

                //update has_images count
                try
                {
                    $ad->save();
                }
                catch (Exception $e)
                {
                    throw HTTP_Exception::factory(500,$e->getMessage());
                }
            }
        }

        //upgrade categories has_image
        $images_path = DOCROOT.'images/categories';
        if(is_dir($images_path))
        {
            //retrive cat pictures
            foreach (new DirectoryIterator($images_path) as $file)
            {
                if($file->isFile())
                {
                    $cat_name =  str_replace('.png','', $file->getFilename());
                    $cat = new Model_Category();
                    $cat->where('seoname','=',$cat_name)->find();
                    if ($cat->loaded())
                    {
                        $cat->has_image = 1;
                        $cat->save();
                    }
                }
            }
        }


        //upgrade locations has_image
        $images_path = DOCROOT.'images/locations';
        if(is_dir($images_path))
        {
            //retrive loc pictures
            foreach (new DirectoryIterator($images_path) as $file)
            {
                if($file->isFile())
                {
                    $loc_name =  str_replace('.png','', $file->getFilename());
                    $loc = new Model_Location();
                    $loc->where('seoname','=',$loc_name)->find();
                    if ($loc->loaded())
                    {
                        $loc->has_image = 1;
                        $loc->save();
                    }
                }
            }
        }

        //upgrade users has_image
        $images_path = DOCROOT.'images/users';
        if(is_dir($images_path))
        {
            //retrive user pictures
            foreach (new DirectoryIterator($images_path) as $file)
            {
                if($file->isFile() AND is_numeric($id_user =  str_replace('.png','', $file->getFilename())))
                {
                    $user = new Model_User($id_user);
                    if ($user->loaded())
                    {
                        $user->has_image = 1;
                        $user->save();
                    }
                }
            }
        }


    }

    /**
     * This function will upgrade DB that didn't existed in versions prior to 2.2.1
     */
    public function action_221()
    {
        $configs = array(
                        array( 'config_key'     =>'count_visits',
                               'group_name'     =>'advertisement',
                               'config_value'   => 1),
                        array( 'config_key'     =>'disallowbots',
                               'group_name'     =>'general',
                               'config_value'   => 0),

                        );

        Model_Config::config_array($configs);
    }

    /**
     * This function will upgrade DB that didn't existed in versions prior to 2.2.0
     */
    public function action_220()
    {
        //updating contents replacing . for _
        try
        {
            DB::query(Database::UPDATE,"UPDATE ".self::$db_prefix."content SET seotitle=REPLACE(seotitle,'.','-') WHERE type='email'")->execute();
        }catch (exception $e) {}

        //cleaning emails not in use
        try
        {
            DB::query(Database::DELETE,"DELETE FROM ".self::$db_prefix."content WHERE seotitle='user.new' AND type='email'")->execute();
        }catch (exception $e) {}

        //updating contents bad names
        try
        {
            DB::query(Database::UPDATE,"UPDATE ".self::$db_prefix."content SET seotitle='ads-sold' WHERE seotitle='adssold' AND type='email'")->execute();
        }catch (exception $e) {}

        try
        {
            DB::query(Database::UPDATE,"UPDATE ".self::$db_prefix."content SET seotitle='out-of-stock' WHERE seotitle='outofstock' AND type='email'")->execute();
        }catch (exception $e) {}

        try
        {
            DB::query(Database::UPDATE,"UPDATE ".self::$db_prefix."content SET seotitle='ads-purchased' WHERE seotitle='adspurchased' AND type='email'")->execute();
        }catch (exception $e) {}

        try
        {
            DB::query(Database::UPDATE,"UPDATE ".self::$db_prefix."content SET seotitle='ads-purchased' WHERE seotitle='adspurchased' AND type='email'")->execute();
        }catch (exception $e) {}
        //end updating emails


        //order transaction
        try
        {
            DB::query(Database::UPDATE,"ALTER TABLE  `".self::$db_prefix."orders` ADD  `txn_id` VARCHAR( 255 ) NULL DEFAULT NULL")->execute();
        }catch (exception $e) {}


        //ip_address from float to bigint
        try
        {
            DB::query(Database::UPDATE,"ALTER TABLE  `".self::$db_prefix."users` CHANGE last_ip last_ip BIGINT NULL DEFAULT NULL ")->execute();
        }catch (exception $e) {}
        try
        {
            DB::query(Database::UPDATE,"ALTER TABLE  `".self::$db_prefix."visits` CHANGE ip_address ip_address BIGINT NULL DEFAULT NULL ")->execute();
        }catch (exception $e) {}
        try
        {
            DB::query(Database::UPDATE,"ALTER TABLE  `".self::$db_prefix."ads` CHANGE ip_address ip_address BIGINT NULL DEFAULT NULL ")->execute();
        }catch (exception $e) {}
        try
        {
            DB::query(Database::UPDATE,"ALTER TABLE  `".self::$db_prefix."posts` CHANGE ip_address ip_address BIGINT NULL DEFAULT NULL ")->execute();
        }catch (exception $e) {}

        //crontab table
        try
        {
            DB::query(Database::UPDATE,"CREATE TABLE IF NOT EXISTS `".self::$db_prefix."crontab` (
                    `id_crontab` int(10) unsigned NOT NULL AUTO_INCREMENT,
                      `name` varchar(50) NOT NULL,
                      `period` varchar(50) NOT NULL,
                      `callback` varchar(140) NOT NULL,
                      `params` varchar(255) DEFAULT NULL,
                      `description` varchar(255) DEFAULT NULL,
                      `date_created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
                      `date_started` datetime  DEFAULT NULL,
                      `date_finished` datetime  DEFAULT NULL,
                      `date_next` datetime  DEFAULT NULL,
                      `times_executed`  bigint DEFAULT '0',
                      `output` varchar(50) DEFAULT NULL,
                      `running` tinyint(1) NOT NULL DEFAULT '0',
                      `active` tinyint(1) NOT NULL DEFAULT '1',
                      PRIMARY KEY (`id_crontab`),
                      UNIQUE KEY `".self::$db_prefix."crontab_UK_name` (`name`)
                  ) ENGINE=MyISAM;")->execute();
        }catch (exception $e) {}

        //crontabs
        try
        {
            DB::query(Database::UPDATE,"INSERT INTO `".self::$db_prefix."crontab` (`name`, `period`, `callback`, `params`, `description`, `active`) VALUES
                                    ('Sitemap', '00 3 * * *', 'Sitemap::generate', NULL, 'Regenerates the sitemap everyday at 3am',1),
                                    ('Clean Cache', '00 5 * * *', 'Core::delete_cache', NULL, 'Once day force to flush all the cache.', 1),
                                    ('Optimize DB', '00 4 1 * *', 'Core::optimize_db', NULL, 'once a month we optimize the DB', 1),
                                    ('Unpaid Orders', '00 7 * * *', 'Cron_Ad::unpaid', NULL, 'Notify by email unpaid orders 2 days after was created', 1),
                                    ('Expired Featured Ad', '00 8 * * *', 'Cron_Ad::expired_featured', NULL, 'Notify by email of expired featured ad', 1),
                                    ('Expired Ad', '00 9 * * *', 'Cron_Ad::expired', NULL, 'Notify by email of expired ad', 1);")->execute();
        }catch (exception $e) {}

        //delete old sitemap config
        try
        {
            DB::query(Database::DELETE,"DELETE FROM ".self::$db_prefix."config WHERE (config_key='expires' OR config_key='on_post') AND  group_name='sitemap'")->execute();
        }catch (exception $e) {}

        //categories description to HTML
        try
        {
            DB::query(Database::UPDATE,"ALTER TABLE  `".self::$db_prefix."categories` CHANGE  `description`  `description` TEXT NULL DEFAULT NULL;")->execute();
        }catch (exception $e) {}

        $categories = new Model_Category();
        $categories = $categories->find_all();
        foreach ($categories as $category)
        {
            $category->description = Text::bb2html($category->description,TRUE, FALSE);
            try {
                $category->save();
            } catch (Exception $e) {}
        }

        //locations description to HTML
        try
        {
            DB::query(Database::UPDATE,"ALTER TABLE  `".self::$db_prefix."locations` CHANGE  `description`  `description` TEXT NULL DEFAULT NULL;")->execute();
        }catch (exception $e) {}

        $locations = new Model_Location();
        $locations = $locations->find_all();
        foreach ($locations as $location)
        {
            $location->description = Text::bb2html($location->description,TRUE, FALSE);
            try {
                $location->save();
            } catch (Exception $e) {}
        }

        //content description to HTML

        $contents = new Model_Content();
        $contents = $contents->find_all();
        foreach ($contents as $content)
        {
            $content->description = Text::bb2html($content->description,TRUE, FALSE);
            try {
                $content->save();
            } catch (Exception $e) {}
        }

        //blog description to HTML

        $posts =  new Model_Post();
    $posts = $posts->where('id_forum','IS',NULL)->find_all();
        foreach ($posts as $post)
        {
            $post->description = Text::bb2html($post->description,TRUE, FALSE);
            try {
                $post->save();
            } catch (Exception $e) {}
        }

        //Reviews
        try
        {
            DB::query(Database::UPDATE,"ALTER TABLE  `".self::$db_prefix."users` ADD `rate` FLOAT( 4, 2 ) NULL DEFAULT NULL ;")->execute();
        }catch (exception $e) {}

        try
        {
            DB::query(Database::UPDATE,"ALTER TABLE  `".self::$db_prefix."ads` ADD `rate` FLOAT( 4, 2 ) NULL DEFAULT NULL ;")->execute();
        }catch (exception $e) {}

        try
        {
            DB::query(Database::UPDATE,"CREATE TABLE IF NOT EXISTS ".self::$db_prefix."reviews (
                id_review int(10) unsigned NOT NULL AUTO_INCREMENT,
                id_user int(10) unsigned NOT NULL,
                id_ad int(10) unsigned NOT NULL,
                rate int(2) unsigned NOT NULL DEFAULT '0',
                description varchar(1000) NOT NULL,
                created timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
                ip_address float DEFAULT NULL,
                status tinyint(1) NOT NULL DEFAULT '0',
                PRIMARY KEY (id_review) USING BTREE,
                KEY ".self::$db_prefix."reviews_IK_id_user (id_user),
                KEY ".self::$db_prefix."reviews_IK_id_ad (id_ad)
                ) ENGINE=MyISAM;")->execute();
        } catch (Exception $e) {}

        //User description About
        try
        {
            DB::query(Database::UPDATE,"ALTER TABLE  `".self::$db_prefix."users`  ADD  `description` TEXT NULL DEFAUlT NULL AFTER  `password` ")->execute();
        }catch (exception $e) {}

        //Favorites table
        try
        {
            DB::query(Database::UPDATE,"CREATE TABLE IF NOT EXISTS ".self::$db_prefix."favorites (
                                        id_favorite int(10) unsigned NOT NULL AUTO_INCREMENT,
                                        id_user int(10) unsigned NOT NULL,
                                        id_ad int(10) unsigned NOT NULL,
                                        created timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
                                        PRIMARY KEY (id_favorite) USING BTREE,
                                        KEY ".self::$db_prefix."favorites_IK_id_user_AND_id_ad (id_user,id_ad)
                                        ) ENGINE=MyISAM;")->execute();
        } catch (Exception $e) {}

        //new mails
        $contents = array(array('order'=>0,
                                'title'=>'Receipt for [ORDER.DESC] #[ORDER.ID]',
                               'seotitle'=>'new-order',
                               'description'=>"Hello [USER.NAME],Thanks for buying [ORDER.DESC].\n\nPlease complete the payment here [URL.CHECKOUT]",
                               'from_email'=>core::config('email.notify_email'),
                               'type'=>'email',
                               'status'=>'1'),
                            array('order'=>0,
                                'title'=>'Your ad [AD.NAME] has expired',
                               'seotitle'=>'ad-expired',
                               'description'=>"Hello [USER.NAME],Your ad [AD.NAME] has expired \n\nPlease check your ad here [URL.EDITAD]",
                               'from_email'=>core::config('email.notify_email'),
                               'type'=>'email',
                               'status'=>'1'),
                            array('order'=>'0',
                               'title'=>'New review for [AD.TITLE] [RATE]',
                               'seotitle'=>'ad-review',
                               'description'=>'[URL.QL]\n\n[RATE]\n\n[DESCRIPTION]',
                               'from_email'=>core::config('email.notify_email'),
                               'type'=>'email',
                               'status'=>'1'),
                        );

        Model_Content::content_array($contents);

        //new configs...
        $configs = array(
                         array('config_key'     =>'bitpay_apikey',
                               'group_name'     =>'payment',
                               'config_value'   =>''),
                         array('config_key'     =>'paymill_private',
                               'group_name'     =>'payment',
                               'config_value'   =>''),
                         array('config_key'     =>'paymill_public',
                               'group_name'     =>'payment',
                               'config_value'   =>''),
                         array('config_key'     =>'stripe_public',
                               'group_name'     =>'payment',
                               'config_value'   =>''),
                         array('config_key'     =>'stripe_private',
                               'group_name'     =>'payment',
                               'config_value'   =>''),
                         array('config_key'     =>'stripe_address',
                               'group_name'     =>'payment',
                               'config_value'   =>'0'),
                         array('config_key'     =>'alternative',
                               'group_name'     =>'payment',
                               'config_value'   =>''),
                         array('config_key'     =>'authorize_sandbox',
                               'group_name'     =>'payment',
                               'config_value'   =>'0'),
                         array('config_key'     =>'authorize_login',
                               'group_name'     =>'payment',
                               'config_value'   =>''),
                         array('config_key'     =>'authorize_key',
                               'group_name'     =>'payment',
                               'config_value'   =>''),
                         array('config_key'     =>'elastic_active',
                               'group_name'     =>'email',
                               'config_value'   =>0),
                         array('config_key'     =>'elastic_username',
                               'group_name'     =>'email',
                               'config_value'   =>''),
                         array('config_key'     =>'elastic_password',
                               'group_name'     =>'email',
                               'config_value'   =>''),
                         array('config_key'     =>'reviews',
                               'group_name'     =>'advertisement',
                               'config_value'   =>'0'),
                         array('config_key'     =>'reviews_paid',
                               'group_name'     =>'advertisement',
                               'config_value'   =>'0'),
                        );

        Model_Config::config_array($configs);

        //delete old files from 323, no need they need to update manually
        // File::delete(APPPATH.'ko323');
        // File::delete(APPPATH.'classes/image/');

        // //delete modules since now they are part of module common
        // File::delete(MODPATH.'pagination');
        // File::delete(MODPATH.'breadcrumbs');
        // File::delete(MODPATH.'formmanager');
        // File::delete(MODPATH.'mysqli');

    //assign new group_name to configs
        try
        {
            DB::query(Database::UPDATE,"UPDATE ".self::$db_prefix."config SET group_name='advertisement' WHERE config_key = 'advertisements_per_page' OR config_key = 'feed_elements' OR config_key = 'map_elements' OR config_key = 'sort_by'")->execute();
        }catch (exception $e) {}
            DB::query(Database::UPDATE,"UPDATE ".self::$db_prefix."content SET seotitle=REPLACE(seotitle,'.','-') WHERE type='email'")->execute();

    }

    /**
     * This function will upgrade DB that didn't existed in versions prior to 2.1.8
     */
    public function action_218()
    {


        try
        {
            DB::query(Database::UPDATE,"ALTER TABLE ".self::$db_prefix."config DROP INDEX ".self::$db_prefix."config_IK_group_name_AND_config_key")->execute();
        }catch (exception $e) {}

        try
        {
            DB::query(Database::UPDATE,"ALTER TABLE ".self::$db_prefix."config ADD PRIMARY KEY (config_key);")->execute();
        }catch (exception $e) {}

        try
        {
            DB::query(Database::UPDATE,"CREATE UNIQUE INDEX ".self::$db_prefix."config_UK_group_name_AND_config_key ON ".self::$db_prefix."config(`group_name` ,`config_key`)")->execute();
        }catch (exception $e) {}

        $configs = array(
                         array('config_key'     =>'login_to_post',
                               'group_name'     =>'advertisement',
                               'config_value'   =>'0'),
                        );

        // returns TRUE if some config is saved
        $return_conf = Model_Config::config_array($configs);

        //delete old files from 322
        File::delete(APPPATH.'ko322');
        File::delete(MODPATH.'auth');
        File::delete(MODPATH.'cache');
        File::delete(MODPATH.'database');
        File::delete(MODPATH.'image');
        File::delete(MODPATH.'orm');
        File::delete(MODPATH.'unittest');

    }

    /**
     * This function will upgrade DB that didn't existed in versions prior to 2.1.7
     */
    public function action_217()
    {

        try
        {
            DB::query(Database::UPDATE,"ALTER TABLE  `".self::$db_prefix."posts` ADD  `id_post_parent` INT NULL DEFAULT NULL AFTER  `id_user`")->execute();
        }catch (exception $e) {}
        try
        {
            DB::query(Database::UPDATE,"ALTER TABLE  `".self::$db_prefix."posts` ADD  `ip_address` FLOAT NULL DEFAULT NULL AFTER  `created`")->execute();
        }catch (exception $e) {}
        try
        {
            DB::query(Database::UPDATE,"ALTER TABLE  `".self::$db_prefix."posts` ADD  `id_forum` INT NULL DEFAULT NULL AFTER  `id_post_parent`")->execute();
        }catch (exception $e) {}
        try
        {
            DB::query(Database::UPDATE,"ALTER TABLE  `".self::$db_prefix."posts` ENGINE = MYISAM ")->execute();
        }catch (exception $e) {}


        DB::query(Database::UPDATE,"CREATE TABLE IF NOT EXISTS  `".self::$db_prefix."forums` (
                      `id_forum` int(10) unsigned NOT NULL AUTO_INCREMENT,
                      `name` varchar(145) NOT NULL,
                      `order` int(2) unsigned NOT NULL DEFAULT '0',
                      `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                      `id_forum_parent` int(10) unsigned NOT NULL DEFAULT '0',
                      `parent_deep` int(2) unsigned NOT NULL DEFAULT '0',
                      `seoname` varchar(145) NOT NULL,
                      `description` varchar(255) NULL,
                      PRIMARY KEY (`id_forum`) USING BTREE,
                      UNIQUE KEY `".self::$db_prefix."forums_IK_seo_name` (`seoname`)
                    ) ENGINE=MyISAM")->execute();

        // build array with new (missing) configs

        //set sitemap to 0
        Model_Config::set_value('sitemap','on_post',0);

        $configs = array(
                         array('config_key'     =>'forums',
                               'group_name'     =>'general',
                               'config_value'   =>'0'),
                         array('config_key'     =>'ocacu',
                               'group_name'     =>'general',
                               'config_value'   =>'0'),
                        );

        // returns TRUE if some config is saved
        $return_conf = Model_Config::config_array($configs);

    }

    /**
     * This function will upgrade DB that didn't existed in versions prior to 2.1.5
     */
    public function action_215()
    {
        // build array with new (missing) configs
        $configs = array(array('config_key'     =>'qr_code',
                               'group_name'     =>'advertisement',
                               'config_value'   =>'0'),
                         array('config_key'     =>'black_list',
                               'group_name'     =>'general',
                               'config_value'   =>'1'),
                         array('config_key'     =>'stock',
                               'group_name'     =>'payment',
                               'config_value'   =>'0'),
                         array('config_key'     =>'fbcomments',
                               'group_name'     =>'advertisement',
                               'config_value'   =>''),
                        );
        $contents = array(array('order'=>'0',
                               'title'=>'Advertisement `[AD.TITLE]` is sold on [SITE.NAME]!',
                               'seotitle'=>'ads-sold',
                               'description'=>"Order ID: [ORDER.ID]\n\nProduct ID: [PRODUCT.ID]\n\nPlease check your bank account for the incoming payment.\n\nClick here to visit [URL.AD]", // @FIXME i18n ?
                               'from_email'=>core::config('email.notify_email'),
                               'type'=>'email',
                               'status'=>'1'),
                          array('order'=>'0',
                               'title'=>'Advertisement `[AD.TITLE]` is purchased on [SITE.NAME]!',
                               'seotitle'=>'ads-purchased',
                               'description'=>"Order ID: [ORDER.ID]\n\nProduct ID: [PRODUCT.ID]\n\nFor any inconvenience please contact administrator of [SITE.NAME], with a details provided above.\n\nClick here to visit [URL.AD]", // @FIXME i18n ?
                               'from_email'=>core::config('email.notify_email'),
                               'type'=>'email',
                               'status'=>'1'),
                          array('order'=>'0',
                               'title'=>'Advertisement `[AD.TITLE]` is out of stock on [SITE.NAME]!',
                               'seotitle'=>'out-of-stock',
                               'description'=>"Hello [USER.NAME],\n\nWhile your ad is out of stock, it is unavailable for others to see. If you wish to increase stock and activate, please follow this link [URL.EDIT].\n\nRegards!", // @FIXME i18n ?
                               'from_email'=>core::config('email.notify_email'),
                               'type'=>'email',
                               'status'=>'1'),);

        // returns TRUE if some config is saved
        $return_conf = Model_Config::config_array($configs);
        $return_cont = Model_Content::content_array($contents);


        try
        {
            DB::query(Database::UPDATE,"ALTER TABLE  `".self::$db_prefix."users` ADD `subscriber` tinyint(1) NOT NULL DEFAULT '1'")->execute();
        }catch (exception $e) {}
        try
        {
            DB::query(Database::UPDATE,"ALTER TABLE  `".self::$db_prefix."ads` ADD `stock` int(10) unsigned DEFAULT NULL")->execute();
        }catch (exception $e) {}
        try
        {
            DB::query(Database::UPDATE,"INSERT INTO  `".self::$db_prefix."roles` (`id_role`, `name`, `description`) VALUES (7, 'moderator', 'Limited access')")->execute();
        }catch (exception $e) {}
        try
        {
            DB::query(Database::UPDATE,"INSERT INTO  `".self::$db_prefix."access` (`id_access`, `id_role`, `access`) VALUES
                                                                         (17, 7, 'location.*'),(16, 7, 'profile.*'),(15, 7, 'content.*'),(14, 7, 'stats.user'),
                                                                         (13, 7, 'blog.*'),(12, 7, 'translations.*'),(11, 7, 'ad.*'),
                                                                         (10, 7, 'widgets.*'),(9, 7, 'menu.*'),(8, 7, 'category.*')")->execute();
        }catch (exception $e) {}

    }

    /**
     * This function will upgrade DB that didn't existed in versions prior to 2.1.3
     */
    public function action_214()
    {
        // build array with new (missing) configs
        $configs = array(array('config_key'     =>'sort_by',
                               'group_name'     =>'general',
                               'config_value'   =>'published-desc'),
                         array('config_key'     =>'map_pub_new',
                               'group_name'     =>'advertisement',
                               'config_value'   =>'0'),
                        );

        // returns TRUE if some config is saved
        $return_conf = Model_Config::config_array($configs);
    }

    /**
     * This function will upgrade DB that didn't existed in versions prior to 2.1
     */
    public function action_211()
    {
      // build array with new (missing) configs
        $configs = array(array('config_key'     =>'related',
                               'group_name'     =>'advertisement',
                               'config_value'   =>'5'),
                        array('config_key'     =>'faq',
                               'group_name'     =>'general',
                               'config_value'   =>'0'),
                         array('config_key'     =>'faq_disqus',
                               'group_name'     =>'general',
                               'config_value'   =>''),
                         );

        // returns TRUE if some config is saved
        $return_conf = Model_Config::config_array($configs);

    }

    /**
     * This function will upgrade DB that didn't existed in versions prior to 2.0.7
     * changes added: config for advanced search by description
     */
    public function action_210()
    {
        try
        {
            DB::query(Database::UPDATE,"ALTER TABLE  `".self::$db_prefix."users` ADD  `hybridauth_provider_name` VARCHAR( 40 ) NULL DEFAULT NULL ,ADD  `hybridauth_provider_uid` VARCHAR( 245 ) NULL DEFAULT NULL")->execute();
        }catch (exception $e) {}
        try
        {
            DB::query(Database::UPDATE,"CREATE UNIQUE INDEX ".self::$db_prefix."users_UK_provider_AND_uid on ".self::$db_prefix."users (hybridauth_provider_name, hybridauth_provider_uid)")->execute();
        }catch (exception $e) {}

        try
        {
            DB::query(Database::UPDATE,"CREATE TABLE IF NOT EXISTS  `".self::$db_prefix."posts` (
                  `id_post` int(10) unsigned NOT NULL AUTO_INCREMENT,
                  `id_user` int(10) unsigned NOT NULL,
                  `title` varchar(245) NOT NULL,
                  `seotitle` varchar(245) NOT NULL,
                  `description` text NOT NULL,
                  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
                  `status` tinyint(1) NOT NULL DEFAULT '0',
                  PRIMARY KEY (`id_post`) USING BTREE,
                  UNIQUE KEY `".self::$db_prefix."posts_UK_seotitle` (`seotitle`)
                ) ENGINE=InnoDB DEFAULT CHARSET=".self::$db_charset.";")->execute();
        }catch (exception $e) {}


        // build array with new (missing) configs
        $configs = array(array('config_key'     =>'search_by_description',
                               'group_name'     =>'general',
                               'config_value'   => 0),
                        array('config_key'     =>'blog',
                               'group_name'     =>'general',
                               'config_value'   => 0),
                        array('config_key'     =>'minify',
                               'group_name'     =>'general',
                               'config_value'   => 0),
                        array('config_key'     =>'parent_category',
                               'group_name'     =>'advertisement',
                               'config_value'   => 1),
                        array('config_key'     =>'blog_disqus',
                               'group_name'     =>'general',
                               'config_value'   => ''),
                        array('config_key'     =>'config',
                               'group_name'     =>'social',
                               'config_value'   =>'{"debug_mode":"0","providers":{
                                                          "OpenID":{"enabled":"1"},
                                                          "Yahoo":{"enabled":"0","keys":{"id":"","secret":""}},
                                                          "AOL":{"enabled":"1"}
                                                          ,"Google":{"enabled":"0","keys":{"id":"","secret":""}},
                                                          "Facebook":{"enabled":"0","keys":{"id":"","secret":""}},
                                                          "Twitter":{"enabled":"0","keys":{"key":"","secret":""}},
                                                          "Live":{"enabled":"0","keys":{"id":"","secret":""}},
                                                          "MySpace":{"enabled":"0","keys":{"key":"","secret":""}},
                                                          "LinkedIn":{"enabled":"0","keys":{"key":"","secret":""}},
                                                          "Foursquare":{"enabled":"0","keys":{"id":"","secret":""}}},
                                                      "base_url":"",
                                                      "debug_file":""}'));

        // returns TRUE if some config is saved
        $return_conf = Model_Config::config_array($configs);


    }

    /**
     * This function will upgrade DB that didn't existed in versions prior to 2.0.6
     * changes added: config for custom field
     */
    public function action_207()
    {
      // build array with new (missing) configs
        $configs = array(array('config_key'     =>'fields',
                               'group_name'     =>'advertisement',
                               'config_value'   =>''),
                         array('config_key'     =>'alert_terms',
                               'group_name'     =>'general',
                               'config_value'   =>''),
                         );

        // returns TRUE if some config is saved
        $return_conf = Model_Config::config_array($configs);
    }

    /**
     * This function will upgrade DB that didn't existed in versions prior to 2.0.5
     * changes added: config for landing page, etc..
     */
    public function action_206()
    {
      // build array with new (missing) configs
        $configs = array(array('config_key'     =>'landing_page',
                               'group_name'     =>'general',
                               'config_value'   =>'{"controller":"home","action":"index"}'),
                         array('config_key'     =>'banned_words',
                               'group_name'     =>'advertisement',
                               'config_value'   =>''),
                         array('config_key'     =>'banned_words_replacement',
                               'group_name'     =>'advertisement',
                               'config_value'   =>''),
                         array('config_key'     =>'akismet_key',
                               'group_name'     =>'general',
                               'config_value'   =>''));

        // returns TRUE if some config is saved
        $return_conf = Model_Config::config_array($configs);


    }

    /**
     * This function will upgrade DB that didn't existed in versions prior to 2.0.5
     * changes added: subscription widget, new email content, map zoom, paypal seller etc..
     */
    public function action_205()
    {
        // build array with new (missing) configs
        $configs = array(array('config_key'     =>'paypal_seller',
                               'group_name'     =>'payment',
                               'config_value'   =>'0'),
                         array('config_key'     =>'map_zoom',
                               'group_name'     =>'advertisement',
                               'config_value'   =>'16'),
                         array('config_key'     =>'center_lon',
                               'group_name'     =>'advertisement',
                               'config_value'   =>'3'),
                         array('config_key'     =>'center_lat',
                               'group_name'     =>'advertisement',
                               'config_value'   =>'40'),
                         array('config_key'     =>'new_ad_notify',
                               'group_name'     =>'email',
                               'config_value'   =>'0'));

        $contents = array(array('order'=>'0',
                               'title'=>'Advertisement `[AD.TITLE]` is created on [SITE.NAME]!',
                               'seotitle'=>'ads_subscribers',
                               'description'=>"Hello,\n\nYou may be interested in this one [AD.TITLE]!\n\nYou can visit this link to see advertisement [URL.AD]",
                               'from_email'=>core::config('email.notify_email'),
                               'type'=>'email',
                               'status'=>'1'),
                          array('order'=>'0',
                               'title'=>'Advertisement `[AD.TITLE]` is created on [SITE.NAME]!',
                               'seotitle'=>'ads-to-admin',
                               'description'=>"Click here to visit [URL.AD]",
                               'from_email'=>core::config('email.notify_email'),
                               'type'=>'email',
                               'status'=>'1'));

        // returns TRUE if some config is saved
        $return_conf = Model_Config::config_array($configs);
        $return_cont = Model_Content::content_array($contents);



        try
        {
            DB::query(Database::UPDATE,"CREATE TABLE IF NOT EXISTS `".self::$db_prefix."subscribers` (
                    `id_subscribe` int(10) unsigned NOT NULL AUTO_INCREMENT,
                    `id_user` int(10) unsigned NOT NULL,
                    `id_category` int(10) unsigned NOT NULL DEFAULT '0',
                    `id_location` int(10) unsigned NOT NULL DEFAULT '0',
                    `min_price` decimal(14,3) NOT NULL DEFAULT '0',
                    `max_price` decimal(14,3) NOT NULL DEFAULT '0',
                    `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                    PRIMARY KEY (`id_subscribe`)
                  ) ENGINE=MyISAM DEFAULT CHARSET=".self::$db_charset.";")->execute();
        }catch (exception $e) {}

        // remove INDEX from content table
        try
        {
            DB::query(Database::UPDATE,"ALTER TABLE `".self::$db_prefix."content` DROP INDEX `".self::$db_prefix."content_UK_seotitle`")->execute();
        }catch (exception $e) {}
    }


    /**
     * This function will upgrade configs that didn't existed in versions prior to 2.0.3
     */
    public function action_203()
    {
        // build array with new (missing) configs
        $configs = array(array('config_key'     =>'watermark',
                               'group_name'     =>'image',
                               'config_value'   =>'0'),
                         array('config_key'     =>'watermark_path',
                               'group_name'     =>'image',
                               'config_value'   =>''),
                         array('config_key'     =>'watermark_position',
                               'group_name'     =>'image',
                               'config_value'   =>'0'),
                         array('config_key'     =>'ads_in_home',
                               'group_name'     =>'advertisement',
                               'config_value'   =>'0'));

        $contents = array(array('order'=>'0',
                               'title'=>'Hello [USER.NAME]!',
                               'seotitle'=>'user-profile-contact',
                               'description'=>"User [EMAIL.SENDER] [EMAIL.FROM], have a message for you: \n\n [EMAIL.SUBJECT] \n\n[EMAIL.BODY]. \n\n Regards!",
                               'from_email'=>core::config('email.notify_email'),
                               'type'=>'email',
                               'status'=>'1'));

        // returns TRUE if some config is saved
        $return_conf = Model_Config::config_array($configs);
        $return_cont = Model_Content::content_array($contents);

    }



    static $db_prefix     = NULL;
    static $db_charset    = NULL;

    //list of files to ignore the copy, TODO ignore languages folder?
    static $update_ignore_list = array('robots.txt',
                                        'oc/config/auth.php',
                                        'oc/config/database.php',
                                        '.htaccess',
                                        'sitemap.xml.gz',
                                        'sitemap.xml',
                                        'install/',
                                        );


    public function __construct($request, $response)
    {
        ignore_user_abort(TRUE);
        parent::__construct($request, $response);

        self::$db_prefix  = Database::instance('default')->table_prefix();
        self::$db_charset = Core::config('database.default.charset');
    }

    public function action_index()
    {

        //force update check reload
        if (Core::get('reload')==1 )
        {
            Core::get_updates(TRUE);
            Alert::set(Alert::INFO,__('Checked for new versions.'));
        }

        $versions = core::config('versions');

        if (Core::get('json')==1)
        {
            $this->auto_render = FALSE;
            $this->template = View::factory('js');
            $this->template->content = json_encode($versions);
        }
        else
        {
            $this->template->title = __('Updates');
            Breadcrumbs::add(Breadcrumb::factory()->set_title($this->template->title));

            //version numbers in a key value
            $version_nums = array();
            foreach ($versions as $version=>$values)
                $version_nums[] = $version;

            $latest_version = current($version_nums);
            $latest_version_update = next($version_nums);


            //pass to view from local versions.php
            $this->template->content = View::factory('oc-panel/pages/update/index',array('versions'       =>$versions,
                                                                                           'latest_version' =>$latest_version));
        }

    }

    /**
     * STEP 0
     * Confirm you want to update!
     */
    public function action_confirm()
    {
        //force update check reload so we are sure he has latest version
        Core::get_updates(TRUE);

        $versions = core::config('versions');


        $this->template->title = __('Updates');
        Breadcrumbs::add(Breadcrumb::factory()->set_title($this->template->title));
        $this->template->scripts['footer'][] = 'js/oc-panel/update.js';

        //version numbers in a key value
        $version_nums = array();
        foreach ($versions as $version=>$values)
            $version_nums[] = $version;

        //latest version available
        $latest_version = current($version_nums);

        //info from the latest version available
        $version = $versions[$latest_version];

        //this is the version we allow to update from. Only the one before latest
        $latest_version_update = (int) str_replace('.', '',next($version_nums));

        //current installation version
        $current_version = (int) str_replace('.', '',core::VERSION);

        $can_update = FALSE;

        if ($current_version == $latest_version_update)
            $can_update = TRUE;

        //pass to view from local versions.php
        $this->template->content = View::factory('oc-panel/pages/update/confirm',array('latest_version'=>$latest_version,
                                                                                       'version' =>$version,
                                                                                       'can_update'=>$can_update));

    }

    /**
     * STEP 1
     * Downloads and extracts latest version
     */
    public function action_latest()
    {
        //save in a session the current version so we can selective update the DB later
        Session::instance()->set('update_from_version', Core::VERSION);

        $versions       = core::config('versions'); //loads OC software version array
        $last_version   = key($versions); //get latest version
        $download_link  = $versions[$last_version]['download']; //get latest download link
        $update_src_dir = DOCROOT.'update'; // update dir
        $file_name      = $update_src_dir.'/'.$last_version.'.zip'; //full file name


        //check if exists already the download, if does delete
        if (file_exists($file_name))
            unlink($file_name);

        //create update dir if doesnt exists
        if (!is_dir($update_src_dir))
            mkdir($update_src_dir, 0775);

        //verify we could get the zip file
        $file_content = core::curl_get_contents($download_link);
        if ($file_content == FALSE)
        {
            Alert::set(Alert::ALERT, __('We had a problem downloading latest version, try later please.'));
            $this->redirect(Route::url('oc-panel',array('controller'=>'update', 'action'=>'index')));
        }

        //Write the file
        file_put_contents($file_name, $file_content);

        //unpack zip
        $zip = new ZipArchive;
        // open zip file, and extract to dir
        if ($zip_open = $zip->open($file_name))
        {
            $zip->extractTo($update_src_dir);
            $zip->close();
        }
        else
        {
            Alert::set(Alert::ALERT, $file_name.' '.__('Zip file failed to extract, please try again.'));
            $this->redirect(Route::url('oc-panel',array('controller'=>'update', 'action'=>'index')));
        }

        //delete downloaded file
        unlink($file_name);

        //move files in different request so more time
        $this->redirect(Route::url('oc-panel', array('controller'=>'update', 'action'=>'files')));

    }

    /**
     * STEP 2
     * this controller moves the extracted files
     */
    public function action_files()
    {
        $update_src_dir = DOCROOT.'update'; // update dir

        //getting the directory where the zip was uncompressed
        foreach (new DirectoryIterator($update_src_dir) as $file)
        {
            if($file->isDir() AND !$file->isDot())
            {
                $folder_udpate = $file->getFilename();
                break;
            }
        }

        $from = $update_src_dir.'/'.$folder_udpate;

        //can we access the folder?
        if (is_dir($from))
        {
            //so we just simply delete the ignored files ;)
            foreach (self::$update_ignore_list as $file)
                File::delete($from.'/'.$file);

            //activate maintenance mode since we are moving files...
            Model_Config::set_value('general','maintenance',1);

            //copy from update to docroot only if files different size
            File::copy($from, DOCROOT, 1);
        }
        else
        {
            Alert::set(Alert::ALERT, $from.' '.sprintf(__('Update folder `%s` not found.'),$from));
            $this->redirect(Route::url('oc-panel',array('controller'=>'update', 'action'=>'index')));
        }

        //delete update files when all finished
        File::delete($update_src_dir);

        //clean cache
        Core::delete_cache();

        //deactivate maintenance mode
        Model_Config::set_value('general','maintenance',0);

        //update the DB in different request
        $this->redirect(Route::url('oc-panel', array('controller'=>'update', 'action'=>'database')));
    }


    /**
     *  STEP 3
     *  Updates the DB using the functions action_XX
     *  they are actions, just in case you want to launch the update of a specific release like /oc-panel/update/218 for example
     */
    public function action_database()
    {
        //activate maintenance mode
        Model_Config::set_value('general','maintenance',1);

        //getting the version from where we are upgrading
        $from_version = Session::instance()->get('update_from_version', Core::get('from_version',Core::VERSION));
        $from_version = str_replace('.', '',$from_version);//getting the integer
        //$from_version = substr($from_version,0,3);//we allow only 3 digits updates, if update has more than 3 its a minor release no DB changes?
        $from_version = (int) $from_version;

        //we get all the DB updates available
        $db_updates   = $this->get_db_action_methods();

        foreach ($db_updates as $version)
        {
            //we only execute those that are newer or same
            if ($version >= $from_version)
            {
                call_user_func(array($this, (string)'action_'.$version));
                Alert::set(Alert::INFO, __('Updated to ').$version);
            }

        }

        //deactivate maintenance mode
        Model_Config::set_value('general','maintenance',0);

        Alert::set(Alert::SUCCESS, __('Software DB Updated to latest version!'));

        //clean cache
        Core::delete_cache();

        //TODO maybe a setting that forces the update of the themes?
        $this->redirect(Route::url('oc-panel', array('controller'=>'update', 'action'=>'themes')));
    }

    /**
     * STEP 4 and last
     * updates all themes to latest version from API license
     * @return void
     */
    public function action_themes()
    {
        //only if theres work to do ;)
        if (Core::config('license.number')!='')
        {
            //activate maintenance mode
            Model_Config::set_value('general','maintenance',1);

            //store the theme he is using now
            $current_theme = Core::config('appearance.theme');

            //activate default theme
            Model_Config::set_value('appearance','theme','default');

            Theme::download(Core::config('license.number'));

            //activate original theme
            Model_Config::set_value('appearance','theme',$current_theme);

            //deactivate maintenance mode
            Model_Config::set_value('general','maintenance',0);

            //clean cache
            Core::delete_cache();
        }

        //finished the entire update process
        $this->redirect(Route::url('oc-panel', array('controller'=>'update', 'action'=>'index')));
    }

    /**
     * we get all the DB updates available
     * @return array
     */
    private function get_db_action_methods()
    {
        $updates = array();

        $class      = new ReflectionClass($this);
        $methods    = $class->getMethods();
        foreach ($methods as $obj => $val)
        {
            //only if they are actions and numeric ;)
            if ( is_numeric($version = str_replace('action_', '', $val->name)) )
                $updates[] = $version;
        }

        //from less to more, so they are executed in order for sure
        sort($updates);

        return $updates;
    }


}
