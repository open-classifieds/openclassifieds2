<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Cron for digest mails
 *
 * @author      Oliver <oliver@open-classifieds.com>
 * @package     Cron
 * @copyright   (c) 2009-2021 Open Classifieds Team
 * @license     GPL v3
 *
 */
class Cron_Digestmail {

    public static function dispatch_daily_digest()
    {
        Cron_Digestmail::dispatch_digest('daily');
    }

    public static function dispatch_weekly_digest()
    {
        Cron_Digestmail::dispatch_digest('weekly');
    }

    public static function dispatch_monthly_digest()
    {
        Cron_Digestmail::dispatch_digest('monthly');
    }

    public static function dispatch_digest($interval = 'weekly')
    {
        if (! Core::config('email.digest'))
        {
            return;
        }

        $ads = (new Model_Ad())
            ->where('status', '=', Model_Ad::STATUS_PUBLISHED)
            ->where('published', '>', Cron_Digestmail::get_interval_expr_for($interval));

        if (Core::config('email.digest_ad_type') === 'featured')
        {
            $ads->where('featured','IS NOT', NULL)
                ->where('featured', '>=', Date::unix2mysql());
        }

        $ads = $ads->limit(Core::config('email.digest_ad_limit'))
            ->find_all();

        if (! Core::count($ads) > 0)
        {
            return;
        }

        $recipients = DB::select('email')
            ->select('name')
            ->from('users')
            ->where('status', '=', Model_User::STATUS_ACTIVE)
            ->where('digest_interval', '=', $interval)
            ->execute()
            ->as_array();

        if (! Core::count($recipients) > 0)
        {
            return;
        }

        Email::send_digest_mail($recipients, $ads);
    }

    public static function get_interval_expr_for($interval = 'weekly')
    {
        if ($interval === 'daily')
        {
            return DB::expr('NOW() - INTERVAL 1 DAY');
        }

        if ($interval === 'monthly')
        {
            return DB::expr('NOW() - INTERVAL 1 MONTH');
        }

        return DB::expr('NOW() - INTERVAL 1 WEEK');
    }

}
