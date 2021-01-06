<?php defined('SYSPATH') or die('No direct script access.');
/**
 * OC statistics!
 *
 * @package    OC
 * @category   Stats
 * @author     Chema <chema@open-classifieds.com>
 * @copyright  (c) 2009-2013 Open Classifieds Team
 * @license    GPL v3
 */
class Controller_Panel_Stats extends Auth_Controller {

    public function before($template = NULL)
    {
        parent::before();

        Breadcrumbs::add(Breadcrumb::factory()->set_title(__('Stats'))->set_url(Route::url('oc-panel',['controller' => 'stats']).'?'.http_build_query(['rel' => ''] + Request::current()->query())));

        $this->template->styles = [
            'css/datepicker.css' => 'screen',
            '//cdn.jsdelivr.net/npm/pikaday/css/pikaday.css' => 'screen'
        ];

        $this->template->scripts['footer'] = [
            '//cdnjs.cloudflare.com/ajax/libs/moment.js/2.5.1/moment.min.js',
            '//cdn.jsdelivr.net/npm/pikaday/pikaday.js',
            'js/chart.min.js',
            'js/chart.js-php.js',
        ];
    }

    public function action_index()
    {
        $this->template->title = __('Stats');

        $this->template->bind('content', $content);
        $content = View::factory('oc-panel/pages/stats/dashboard');
        $content->title = $this->template->title;

        // Getting the dates and range
        $from_date = Core::request('from_date', date('Y-m-d', strtotime('-1 month')));
        $to_date   = Core::request('to_date', date('Y-m-d', strtotime('+1 day')));

        // We assure is a proper time stamp if not we transform it
        if (is_string($from_date) === TRUE)
            $from_date = strtotime($from_date);
        if (is_string($to_date) === TRUE)
            $to_date   = strtotime($to_date);

        $from_datetime = new DateTime();
        $to_datetime   = new DateTime();

        // Dates displayed
        $content->from_date              = date('Y-m-d', $from_date);
        $content->to_date                = date('Y-m-d', $to_date);
        $content->days_ago               = $from_datetime->setTimestamp($from_date)->diff($to_datetime->setTimestamp($to_date))->format("%a");

        // Ads
        $content->ads                    = $this->ads_by_date($from_date, $to_date);
        $content->ads_total              = $this->ads_total($from_date, $to_date);
        $content->ads_total_past         = $this->ads_total($from_date, $to_date, TRUE);

        // Users
        $content->users                  = $this->users_by_date($from_date, $to_date);
        $content->users_total            = $this->users_total($from_date, $to_date);
        $content->users_total_past       = $this->users_total($from_date, $to_date, TRUE);

        // Visits
        $content->visits                 = $this->visits_by_date($from_date, $to_date);
        $content->visits_total           = $this->visits_total($from_date, $to_date);
        $content->visits_total_past      = $this->visits_total($from_date, $to_date, TRUE);

        // Contacts
        $content->contacts               = $this->contacts_by_date($from_date, $to_date);
        $content->contacts_total         = $this->contacts_total($from_date, $to_date);
        $content->contacts_total_past    = $this->contacts_total($from_date, $to_date, TRUE);

        // Paid Orders
        $content->paid_orders            = $this->paid_orders_by_date($from_date, $to_date);
        $content->paid_orders_total      = $this->paid_orders_total($from_date, $to_date);
        $content->paid_orders_total_past = $this->paid_orders_total($from_date, $to_date, TRUE);

        // Sales
        $content->sales                  = $this->sales_by_date($from_date, $to_date);
        $content->sales_total            = $this->sales_total($from_date, $to_date);
        $content->sales_total_past       = $this->sales_total($from_date, $to_date, TRUE);

        $content->chart_config           = array('height'  => 94,
                                                 'width'   => 378,
                                                 'options' => array('responsive' => true,
                                                                    'scales' => array('xAxes' => array(array('display' => false)),
                                                                                      'yAxes' => array(array('display' => false,
                                                                                                             'ticks'   => array('min' => 0)))),
                                                                    'legend' => array('display' => false)));
        $content->chart_colors           = array(array('fill'        => 'rgba(33,150,243,.1)',
                                                       'stroke'      => 'rgba(33,150,243,.8)',
                                                       'point'       => 'rgba(33,150,243,.8)',
                                                       'pointStroke' => 'rgba(33,150,243,.8)'));

    }

    /**
     * Ads Stats
     *
     */
    public function action_ads()
    {
        $this->template->title = __('Ads');
        Breadcrumbs::add(Breadcrumb::factory()->set_title($this->template->title)->set_url(Route::url('oc-panel',array('controller'  => 'stats', 'action' => 'ads')).'?'.http_build_query(['rel' => ''] + Request::current()->query())));

        $this->template->bind('content', $content);
        $content = View::factory('oc-panel/pages/stats/details');
        $content->title = $this->template->title;

        // Getting the dates and range
        $from_date = Core::post('from_date', Core::get('from_date', strtotime('-1 month')));
        $to_date   = Core::post('to_date', Core::get('to_date', time()));

        //we assure is a proper time stamp if not we transform it
        if (is_string($from_date) === TRUE)
            $from_date = strtotime($from_date);
        if (is_string($to_date) === TRUE)
            $to_date   = strtotime($to_date);

        $from_datetime = new DateTime();
        $to_datetime   = new DateTime();

        // Dates displayed
        $content->from_date                    = date('Y-m-d', $from_date);
        $content->to_date                      = date('Y-m-d', $to_date);
        $content->days_ago                     = $from_datetime->setTimestamp($from_date)->diff($to_datetime->setTimestamp($to_date))->format("%a");

        $content->current_by_date              = $this->ads_by_date($from_date, $to_date);

        $content->current_total                = $this->ads_total($from_date, $to_date);
        $content->past_total                   = $this->ads_total($from_date, $to_date, TRUE);

        $content->month_ago_total              = $this->ads_total(strtotime('-1 months'), time());
        $content->past_month_ago_total         = $this->ads_total(strtotime('-1 months'), time(), TRUE);

        $content->three_months_ago_total       = $this->ads_total(strtotime('-3 months'), time());
        $content->past_three_months_ago_total  = $this->ads_total(strtotime('-3 months'), time(), TRUE);

        $content->six_months_ago_total         = $this->ads_total(strtotime('-6 months'), time());
        $content->past_six_months_ago_total    = $this->ads_total(strtotime('-6 months'), time(), TRUE);

        $content->twelve_months_ago_total      = $this->ads_total(strtotime('-12 months'), time());
        $content->twelve_six_months_ago_total  = $this->ads_total(strtotime('-12 months'), time(), TRUE);

    }

    /**
     * Users Stats
     *
     */
    public function action_users()
    {
        $this->template->title = __('Users');
        Breadcrumbs::add(Breadcrumb::factory()->set_title($this->template->title)->set_url(Route::url('oc-panel',array('controller'  => 'stats', 'action' => 'users')).'?'.http_build_query(['rel' => ''] + Request::current()->query())));

        $this->template->bind('content', $content);
        $content = View::factory('oc-panel/pages/stats/details');
        $content->title = $this->template->title;

        // Getting the dates and range
        $from_date = Core::post('from_date', Core::get('from_date', strtotime('-1 month')));
        $to_date   = Core::post('to_date', Core::get('to_date', time()));

        //we assure is a proper time stamp if not we transform it
        if (is_string($from_date) === TRUE)
            $from_date = strtotime($from_date);
        if (is_string($to_date) === TRUE)
            $to_date   = strtotime($to_date);

        $from_datetime = new DateTime();
        $to_datetime   = new DateTime();

        // Dates displayed
        $content->from_date                    = date('Y-m-d', $from_date);
        $content->to_date                      = date('Y-m-d', $to_date);
        $content->days_ago                     = $from_datetime->setTimestamp($from_date)->diff($to_datetime->setTimestamp($to_date))->format("%a");

        $content->current_by_date              = $this->users_by_date($from_date, $to_date);

        $content->current_total                = $this->users_total($from_date, $to_date);
        $content->past_total                   = $this->users_total($from_date, $to_date, TRUE);

        $content->month_ago_total              = $this->users_total(strtotime('-1 months'), time());
        $content->past_month_ago_total         = $this->users_total(strtotime('-1 months'), time(), TRUE);

        $content->three_months_ago_total       = $this->users_total(strtotime('-3 months'), time());
        $content->past_three_months_ago_total  = $this->users_total(strtotime('-3 months'), time(), TRUE);

        $content->six_months_ago_total         = $this->users_total(strtotime('-6 months'), time());
        $content->past_six_months_ago_total    = $this->users_total(strtotime('-6 months'), time(), TRUE);

        $content->twelve_months_ago_total      = $this->users_total(strtotime('-12 months'), time());
        $content->twelve_six_months_ago_total  = $this->users_total(strtotime('-12 months'), time(), TRUE);

    }

    /**
     * Visits Stats
     *
     */
    public function action_visits()
    {
        $this->template->title = __('Visits');
        Breadcrumbs::add(Breadcrumb::factory()->set_title($this->template->title)->set_url(Route::url('oc-panel',array('controller'  => 'stats', 'action' => 'visits')).'?'.http_build_query(['rel' => ''] + Request::current()->query())));

        $this->template->bind('content', $content);
        $content = View::factory('oc-panel/pages/stats/details');
        $content->title = $this->template->title;

        // Getting the dates and range
        $from_date = Core::post('from_date', Core::get('from_date', strtotime('-1 month')));
        $to_date   = Core::post('to_date', Core::get('to_date', time()));

        //we assure is a proper time stamp if not we transform it
        if (is_string($from_date) === TRUE)
            $from_date = strtotime($from_date);
        if (is_string($to_date) === TRUE)
            $to_date   = strtotime($to_date);

        $from_datetime = new DateTime();
        $to_datetime   = new DateTime();

        // Dates displayed
        $content->from_date                    = date('Y-m-d', $from_date);
        $content->to_date                      = date('Y-m-d', $to_date);
        $content->days_ago                     = $from_datetime->setTimestamp($from_date)->diff($to_datetime->setTimestamp($to_date))->format("%a");

        $content->current_by_date              = $this->visits_by_date($from_date, $to_date);

        $content->current_total                = $this->visits_total($from_date, $to_date);
        $content->past_total                   = $this->visits_total($from_date, $to_date, TRUE);

        $content->month_ago_total              = $this->visits_total(strtotime('-1 months'), time());
        $content->past_month_ago_total         = $this->visits_total(strtotime('-1 months'), time(), TRUE);

        $content->three_months_ago_total       = $this->visits_total(strtotime('-3 months'), time());
        $content->past_three_months_ago_total  = $this->visits_total(strtotime('-3 months'), time(), TRUE);

        $content->six_months_ago_total         = $this->visits_total(strtotime('-6 months'), time());
        $content->past_six_months_ago_total    = $this->visits_total(strtotime('-6 months'), time(), TRUE);

        $content->twelve_months_ago_total      = $this->visits_total(strtotime('-12 months'), time());
        $content->twelve_six_months_ago_total  = $this->visits_total(strtotime('-12 months'), time(), TRUE);

    }

    /**
     * Contacts Stats
     *
     */
    public function action_contacts()
    {
        $this->template->title = __('Contacts');
        Breadcrumbs::add(Breadcrumb::factory()->set_title($this->template->title)->set_url(Route::url('oc-panel',array('controller'  => 'stats', 'action' => 'contacts')).'?'.http_build_query(['rel' => ''] + Request::current()->query())));

        $this->template->bind('content', $content);
        $content = View::factory('oc-panel/pages/stats/details');
        $content->title = $this->template->title;

        // Getting the dates and range
        $from_date = Core::post('from_date', Core::get('from_date', strtotime('-1 month')));
        $to_date   = Core::post('to_date', Core::get('to_date', time()));

        //we assure is a proper time stamp if not we transform it
        if (is_string($from_date) === TRUE)
            $from_date = strtotime($from_date);
        if (is_string($to_date) === TRUE)
            $to_date   = strtotime($to_date);

        $from_datetime = new DateTime();
        $to_datetime   = new DateTime();

        // Dates displayed
        $content->from_date                    = date('Y-m-d', $from_date);
        $content->to_date                      = date('Y-m-d', $to_date);
        $content->days_ago                     = $from_datetime->setTimestamp($from_date)->diff($to_datetime->setTimestamp($to_date))->format("%a");

        $content->current_by_date              = $this->contacts_by_date($from_date, $to_date);

        $content->current_total                = $this->contacts_total($from_date, $to_date);
        $content->past_total                   = $this->contacts_total($from_date, $to_date, TRUE);

        $content->month_ago_total              = $this->contacts_total(strtotime('-1 months'), time());
        $content->past_month_ago_total         = $this->contacts_total(strtotime('-1 months'), time(), TRUE);

        $content->three_months_ago_total       = $this->contacts_total(strtotime('-3 months'), time());
        $content->past_three_months_ago_total  = $this->contacts_total(strtotime('-3 months'), time(), TRUE);

        $content->six_months_ago_total         = $this->contacts_total(strtotime('-6 months'), time());
        $content->past_six_months_ago_total    = $this->contacts_total(strtotime('-6 months'), time(), TRUE);

        $content->twelve_months_ago_total      = $this->contacts_total(strtotime('-12 months'), time());
        $content->twelve_six_months_ago_total  = $this->contacts_total(strtotime('-12 months'), time(), TRUE);

    }

    /**
     * Paid Orders Stats
     *
     */
    public function action_paid_orders()
    {
        $this->template->title = __('Paid Orders');
        Breadcrumbs::add(Breadcrumb::factory()->set_title($this->template->title)->set_url(Route::url('oc-panel',array('controller'  => 'stats', 'action' => 'paid_orders')).'?'.http_build_query(['rel' => ''] + Request::current()->query())));

        $this->template->bind('content', $content);
        $content = View::factory('oc-panel/pages/stats/details');
        $content->title = $this->template->title;

        // Getting the dates and range
        $from_date = Core::post('from_date', Core::get('from_date', strtotime('-1 month')));
        $to_date   = Core::post('to_date', Core::get('to_date', time()));

        //we assure is a proper time stamp if not we transform it
        if (is_string($from_date) === TRUE)
            $from_date = strtotime($from_date);
        if (is_string($to_date) === TRUE)
            $to_date   = strtotime($to_date);

        $from_datetime = new DateTime();
        $to_datetime   = new DateTime();

        // Dates displayed
        $content->from_date                    = date('Y-m-d', $from_date);
        $content->to_date                      = date('Y-m-d', $to_date);
        $content->days_ago                     = $from_datetime->setTimestamp($from_date)->diff($to_datetime->setTimestamp($to_date))->format("%a");

        $content->current_by_date              = $this->paid_orders_by_date($from_date, $to_date);

        $content->current_total                = $this->paid_orders_total($from_date, $to_date);
        $content->past_total                   = $this->paid_orders_total($from_date, $to_date, TRUE);

        $content->month_ago_total              = $this->paid_orders_total(strtotime('-1 months'), time());
        $content->past_month_ago_total         = $this->paid_orders_total(strtotime('-1 months'), time(), TRUE);

        $content->three_months_ago_total       = $this->paid_orders_total(strtotime('-3 months'), time());
        $content->past_three_months_ago_total  = $this->paid_orders_total(strtotime('-3 months'), time(), TRUE);

        $content->six_months_ago_total         = $this->paid_orders_total(strtotime('-6 months'), time());
        $content->past_six_months_ago_total    = $this->paid_orders_total(strtotime('-6 months'), time(), TRUE);

        $content->twelve_months_ago_total      = $this->paid_orders_total(strtotime('-12 months'), time());
        $content->twelve_six_months_ago_total  = $this->paid_orders_total(strtotime('-12 months'), time(), TRUE);

    }

    /**
     * Sales Stats
     *
     */
    public function action_sales()
    {
        $this->template->title = __('Sales');
        Breadcrumbs::add(Breadcrumb::factory()->set_title($this->template->title)->set_url(Route::url('oc-panel',array('controller'  => 'stats', 'action' => 'sales')).'?'.http_build_query(['rel' => ''] + Request::current()->query())));

        $this->template->bind('content', $content);
        $content = View::factory('oc-panel/pages/stats/details');
        $content->title = $this->template->title;

        // Getting the dates and range
        $from_date = Core::post('from_date', Core::get('from_date', strtotime('-1 month')));
        $to_date   = Core::post('to_date', Core::get('to_date', time()));

        //we assure is a proper time stamp if not we transform it
        if (is_string($from_date) === TRUE)
            $from_date = strtotime($from_date);
        if (is_string($to_date) === TRUE)
            $to_date   = strtotime($to_date);

        $from_datetime = new DateTime();
        $to_datetime   = new DateTime();

        // Dates displayed
        $content->from_date                    = date('Y-m-d', $from_date);
        $content->to_date                      = date('Y-m-d', $to_date);
        $content->days_ago                     = $from_datetime->setTimestamp($from_date)->diff($to_datetime->setTimestamp($to_date))->format("%a");

        $content->current_by_date              = $this->sales_by_date($from_date, $to_date);

        $content->current_total                = $this->sales_total($from_date, $to_date);
        $content->past_total                   = $this->sales_total($from_date, $to_date, TRUE);

        $content->month_ago_total              = $this->sales_total(strtotime('-1 months'), time());
        $content->past_month_ago_total         = $this->sales_total(strtotime('-1 months'), time(), TRUE);

        $content->three_months_ago_total       = $this->sales_total(strtotime('-3 months'), time());
        $content->past_three_months_ago_total  = $this->sales_total(strtotime('-3 months'), time(), TRUE);

        $content->six_months_ago_total         = $this->sales_total(strtotime('-6 months'), time());
        $content->past_six_months_ago_total    = $this->sales_total(strtotime('-6 months'), time(), TRUE);

        $content->twelve_months_ago_total      = $this->sales_total(strtotime('-12 months'), time());
        $content->twelve_six_months_ago_total  = $this->sales_total(strtotime('-12 months'), time(), TRUE);

    }

    /**
     * Total Ads value between two dates
     * @param  timestamp  $from_date
     * @param  timestamp  $to_date
     * @param  boolean    $past_period Calculate past period (period = $to_date - $from_date)
     * @return integer
     */
    private function ads_total($from_date, $to_date, $past_period = FALSE)
    {
        if ($past_period)
        {
            $original_from_date = $from_date;
            $original_to_date   = $to_date;
            $from_date          = $original_from_date - ($original_to_date - $original_from_date);
            $to_date            = $original_to_date - ($original_to_date - $original_from_date);
        }

        $query = DB::select(DB::expr('COUNT(id_ad) total'))
            ->from('ads')
            ->where('status', '=', Model_Ad::STATUS_PUBLISHED)
            ->where('published', 'between', array(Date::unix2mysql($from_date), Date::unix2mysql($to_date)));

        $query = $query->execute();

        $result = $query->as_array();

        return (isset($result[0]['total'])) ? $result[0]['total'] : 0;
    }

    /**
     * Returns array with Ads by date formatted to generate charts
     * @param  timestamp $from_date
     * @param  timestamp $to_date
     * @return array
     */
    private function ads_by_date($from_date, $to_date)
    {
        // Dates range we are filtering
        $dates = $this->dates_range($from_date, $to_date);

        $query = DB::select(DB::expr('DATE(published) date'))
            ->select(DB::expr('COUNT(id_ad) total'))
            ->from('ads')
            ->where('status', '=', Model_Ad::STATUS_PUBLISHED)
            //->where(DB::expr('TIMESTAMPDIFF( DAY , published, NOW() )') ,'<=','30')
            ->where('published', 'between', array(Date::unix2mysql($from_date), Date::unix2mysql($to_date)));

        $query = $query->group_by(DB::expr('DATE( published )'))
            ->order_by('date','asc')
            ->execute();

        $result = $query->as_array('date');

        // print maxinum 30 date labels on charts
        $label_counter = 0;
        $label_breaker = core::count($dates) > 30 ? Num::round(core::count($dates)/30) : 1;

        $ret = array();

        foreach ($dates as $k => $date)
        {
            $count_sum = (isset($result[$date['date']]['total'])) ? $result[$date['date']]['total'] : 0;

            $ret[] = array('date' => ($label_counter % $label_breaker == 0) ? $date['date'] : '', '#' => $count_sum);

            $label_counter++;
        }

        return $ret;

    }

    /**
     * Total Users value between two dates
     * @param  timestamp  $from_date
     * @param  timestamp  $to_date
     * @param  boolean    $past_period Calculate past period (period = $to_date - $from_date)
     * @return integer
     */
    private function users_total($from_date, $to_date, $past_period = FALSE)
    {
        if ($past_period)
        {
            $original_from_date = $from_date;
            $original_to_date   = $to_date;
            $from_date          = $original_from_date - ($original_to_date - $original_from_date);
            $to_date            = $original_to_date - ($original_to_date - $original_from_date);
        }

        $query = DB::select(DB::expr('COUNT(id_user) total'))
            ->from('users')
            ->where('status', '=', Model_User::STATUS_ACTIVE)
            ->where('created', 'between', array(Date::unix2mysql($from_date), Date::unix2mysql($to_date)));

        $query = $query->execute();

        $result = $query->as_array();

        return (isset($result[0]['total'])) ? $result[0]['total'] : 0;
    }

    /**
     * Returns array with Users by date formatted to generate charts
     * @param  timestamp $from_date
     * @param  timestamp $to_date
     * @return array
     */
    private function users_by_date($from_date, $to_date)
    {
        // Dates range we are filtering
        $dates = $this->dates_range($from_date, $to_date);

        $query = DB::select(DB::expr('DATE(created) date'))
            ->select(DB::expr('COUNT(id_user) total'))
            ->from('users')
            ->where('status', '=', Model_User::STATUS_ACTIVE)
            ->where('created', 'between', array(Date::unix2mysql($from_date), Date::unix2mysql($to_date)));

        $query = $query->group_by(DB::expr('DATE(created)'))
            ->order_by('date', 'asc')
            ->execute();

        $result = $query->as_array('date');

        $ret = array();

        // print maxinum 30 date labels on charts
        $label_counter = 0;
        $label_breaker = core::count($dates) > 30 ? Num::round(core::count($dates)/30) : 1;

        foreach ($dates as $k => $date)
        {
            $count_sum = (isset($result[$date['date']]['total'])) ? $result[$date['date']]['total'] : 0;

            $ret[] = array('date' => ($label_counter % $label_breaker == 0) ? $date['date'] : '', '#' => $count_sum);

            $label_counter++;
        }

        return $ret;

    }

    /**
     * Total Visits value between two dates
     * @param  timestamp  $from_date
     * @param  timestamp  $to_date
     * @param  boolean    $past_period Calculate past period (period = $to_date - $from_date)
     * @return integer
     */
    private function visits_total($from_date, $to_date, $past_period = FALSE)
    {
        if ($past_period)
        {
            $original_from_date = $from_date;
            $original_to_date   = $to_date;
            $from_date          = $original_from_date - ($original_to_date - $original_from_date);
            $to_date            = $original_to_date - ($original_to_date - $original_from_date);
        }

        $query = DB::select(DB::expr('SUM(hits) total'))
            ->from('visits')
            ->where('created', 'between', array(Date::unix2mysql($from_date), Date::unix2mysql($to_date)));

        $query = $query->execute();

        $result = $query->as_array();

        return (isset($result[0]['total'])) ? $result[0]['total'] : 0;
    }

    /**
     * Returns array with Visits by date formatted to generate charts
     * @param  timestamp $from_date
     * @param  timestamp $to_date
     * @return array
     */
    private function visits_by_date($from_date, $to_date)
    {
        // Dates range we are filtering
        $dates = $this->dates_range($from_date, $to_date);

        $query = DB::select(DB::expr('created date'))
            ->select(DB::expr('SUM(hits) total'))
            ->from('visits')
            ->where('created', 'between', array(Date::unix2mysql($from_date), Date::unix2mysql($to_date)));

        $query = $query->group_by('created')
            ->order_by('date', 'asc')
            ->execute();

        $result = $query->as_array('date');

        $ret = array();

        // print maxinum 30 date labels on charts
        $label_counter = 0;
        $label_breaker = core::count($dates) > 30 ? Num::round(core::count($dates)/30) : 1;

        foreach ($dates as $k => $date)
        {
            $count_sum = (isset($result[$date['date']]['total'])) ? $result[$date['date']]['total'] : 0;

            $ret[] = array('date' => ($label_counter % $label_breaker == 0) ? $date['date'] : '', '#' => $count_sum);

            $label_counter++;
        }

        return $ret;

    }

    /**
     * Total Contacts value between two dates
     * @param  timestamp  $from_date
     * @param  timestamp  $to_date
     * @param  boolean    $past_period Calculate past period (period = $to_date - $from_date)
     * @return integer
     */
    private function contacts_total($from_date, $to_date, $past_period = FALSE)
    {
        if ($past_period)
        {
            $original_from_date = $from_date;
            $original_to_date   = $to_date;
            $from_date          = $original_from_date - ($original_to_date - $original_from_date);
            $to_date            = $original_to_date - ($original_to_date - $original_from_date);
        }

        $query = DB::select(DB::expr('SUM(contacts) total'))
            ->from('visits')
            ->where('created', 'between', array(Date::unix2mysql($from_date), Date::unix2mysql($to_date)));

        $query = $query->execute();

        $result = $query->as_array();

        return (isset($result[0]['total'])) ? $result[0]['total'] : 0;
    }

    /**
     * Returns array with Contacts by date formatted to generate charts
     * @param  timestamp $from_date
     * @param  timestamp $to_date
     * @return array
     */
    private function contacts_by_date($from_date, $to_date)
    {
        // Dates range we are filtering
        $dates = $this->dates_range($from_date, $to_date);

        $query = DB::select(DB::expr('created date'))
            ->select(DB::expr('SUM(contacts) total'))
            ->from('visits')
            ->where('created', 'between', array(Date::unix2mysql($from_date), Date::unix2mysql($to_date)));

        $query = $query->group_by('created')
            ->order_by('date', 'asc')
            ->execute();

        $result = $query->as_array('date');

        $ret = array();

        // print maxinum 30 date labels on charts
        $label_counter = 0;
        $label_breaker = core::count($dates) > 30 ? Num::round(core::count($dates)/30) : 1;

        foreach ($dates as $k => $date)
        {
            $count_sum = (isset($result[$date['date']]['total'])) ? $result[$date['date']]['total'] : 0;

            $ret[] = array('date' => ($label_counter % $label_breaker == 0) ? $date['date'] : '', '#' => $count_sum);

            $label_counter++;
        }

        return $ret;

    }

    /**
     * Total Paid Orders value between two dates
     * @param  timestamp  $from_date
     * @param  timestamp  $to_date
     * @param  boolean    $past_period Calculate past period (period = $to_date - $from_date)
     * @return integer
     */
    private function paid_orders_total($from_date, $to_date, $past_period = FALSE)
    {
        if ($past_period)
        {
            $original_from_date = $from_date;
            $original_to_date   = $to_date;
            $from_date          = $original_from_date - ($original_to_date - $original_from_date);
            $to_date            = $original_to_date - ($original_to_date - $original_from_date);
        }

        $query = DB::select(DB::expr('COUNT(id_order) total'))
            ->from('orders')
            ->where('status', '=', Model_Order::STATUS_PAID)
            ->where('id_product','!=',Model_Order::PRODUCT_AD_SELL)
            ->where('id_product','!=',Model_Order::PRODUCT_AD_CUSTOM)
            ->where('pay_date', 'between', array(Date::unix2mysql($from_date), Date::unix2mysql($to_date)));

        $query = $query->execute();

        $result = $query->as_array();

        return (isset($result[0]['total'])) ? $result[0]['total'] : 0;
    }

    /**
     * Returns array with Paid Orders by date formatted to generate charts
     * @param  timestamp $from_date
     * @param  timestamp $to_date
     * @return array
     */
    private function paid_orders_by_date($from_date, $to_date)
    {
        // Dates range we are filtering
        $dates = $this->dates_range($from_date, $to_date);

        $query = DB::select(DB::expr('DATE(pay_date) date'))
            ->select(DB::expr('COUNT(id_order) total'))
            ->from('orders')
            ->where('id_product','!=',Model_Order::PRODUCT_AD_SELL)
            ->where('status', '=', Model_Order::STATUS_PAID)
            ->where('pay_date', 'between', array(Date::unix2mysql($from_date), Date::unix2mysql($to_date)));

        $query = $query->group_by(DB::expr('DATE(pay_date)'))
            ->order_by('date', 'asc')
            ->execute();

        $result = $query->as_array('date');

        $ret = array();

        // print maxinum 30 date labels on charts
        $label_counter = 0;
        $label_breaker = core::count($dates) > 30 ? Num::round(core::count($dates)/30) : 1;

        foreach ($dates as $k => $date)
        {
            $count_sum = (isset($result[$date['date']]['total'])) ? $result[$date['date']]['total'] : 0;

            $ret[] = array('date' => ($label_counter % $label_breaker == 0) ? $date['date'] : '', '#' => $count_sum);

            $label_counter++;
        }

        return $ret;

    }

    /**
     * Total Sales value between two dates
     * @param  timestamp  $from_date
     * @param  timestamp  $to_date
     * @param  boolean    $past_period Calculate past period (period = $to_date - $from_date)
     * @return integer
     */
    private function sales_total($from_date, $to_date, $past_period = FALSE)
    {
        if ($past_period)
        {
            $original_from_date = $from_date;
            $original_to_date   = $to_date;
            $from_date          = $original_from_date - ($original_to_date - $original_from_date);
            $to_date            = $original_to_date - ($original_to_date - $original_from_date);
        }

        $query = DB::select(DB::expr('SUM(amount) total'))
            ->from('orders')
            ->where('id_product','!=',Model_Order::PRODUCT_AD_SELL)
            ->where('id_product','!=',Model_Order::PRODUCT_AD_CUSTOM)
            ->where('created', 'between', array(Date::unix2mysql($from_date), Date::unix2mysql($to_date)));

        $query = $query->execute();

        $result = $query->as_array();

        return (isset($result[0]['total'])) ? $result[0]['total'] : 0;
    }

    /**
     * Returns array with Sales by date formatted to generate charts
     * @param  timestamp $from_date
     * @param  timestamp $to_date
     * @return array
     */
    private function sales_by_date($from_date, $to_date)
    {
        // Dates range we are filtering
        $dates = $this->dates_range($from_date, $to_date);

        $query = DB::select(DB::expr('DATE(created) date'))
            ->select(DB::expr('SUM(amount) total'))
            ->from('orders')
            ->where('id_product','!=',Model_Order::PRODUCT_AD_SELL)
            ->where('id_product','!=',Model_Order::PRODUCT_AD_CUSTOM)
            ->where('created', 'between', array(Date::unix2mysql($from_date), Date::unix2mysql($to_date)));

        $query = $query->group_by(DB::expr('DATE(created)'))
            ->order_by('date', 'asc')
            ->execute();

        $result = $query->as_array('date');

        $ret = array();

        // print maxinum 30 date labels on charts
        $label_counter = 0;
        $label_breaker = core::count($dates) > 30 ? Num::round(core::count($dates)/30) : 1;

        foreach ($dates as $k => $date)
        {
            $count_sum = (isset($result[$date['date']]['total'])) ? $result[$date['date']]['total'] : 0;

            $ret[] = array('date' => ($label_counter % $label_breaker == 0) ? $date['date'] : '', '$' => $count_sum);

            $label_counter++;
        }

        return $ret;

    }

    /**
     * Dates range that we will be filtering
     * @param  integer $from_date
     * @param  integer $to_date
     * @return array
     */
    private function dates_range($from_date, $to_date)
    {
        return Date::range($from_date, $to_date, '+1 day', 'Y-m-d', array('date' => 0, 'total' => 0), 'date');
    }


}
