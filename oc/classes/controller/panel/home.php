<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Panel_Home extends Auth_Controller {


	public function action_index()
	{
        //if not god redirect him to the normal profile page
        if (Auth::instance()->get_user()->id_role!=Model_Role::ROLE_ADMIN)
            HTTP::redirect(Route::url('oc-panel',array('controller'  => 'myads','action'=>'index')));  
        
        Core::ocacu();

        $this->template->title = __('Welcome');
        Breadcrumbs::add(Breadcrumb::factory()->set_title($this->template->title));
        
        $this->template->bind('content', $content);        
        $content = View::factory('oc-panel/home');
        
        /////////////////////RSS////////////////////////////////
        
        //try to get the RSS from the cache
        $rss_url = 'http://feeds.feedburner.com/OpenClassifieds';
        $content->rss = Feed::parse($rss_url,10);
        

        /////////////////////ADS////////////////////////////////
        
        $content->res = new Model_Ad();
        
        //filter ads by status
        $content->res = $content->res->where('status', '=', Core::get('status',Model_Ad::STATUS_PUBLISHED));
        $content->res = $content->res->order_by('created','desc')->limit(10)->find_all();
        
        /////////////////////STATS////////////////////////////////
        
        //Getting the dates and range
        $from_date = Core::post('from_date',strtotime('-1 month'));
        $to_date   = Core::post('to_date',time());
        
        //we assure is a proper time stamp if not we transform it
        if (is_string($from_date) === TRUE) 
            $from_date = strtotime($from_date);
        if (is_string($to_date) === TRUE) 
            $to_date   = strtotime($to_date);
        
        //mysql formated dates
        $my_from_date = Date::unix2mysql($from_date);
        $my_to_date   = Date::unix2mysql($to_date);
        
        //dates range we are filtering
        $dates     = Date::range($from_date, $to_date,'+1 day','Y-m-d',array('date'=>0,'count'=> 0),'date');
        
        //dates displayed in the form
        $content->from_date = date('Y-m-d',$from_date);
        $content->to_date   = date('Y-m-d',$to_date) ;
        
        
        //ads published last XX days
        $query = DB::select(DB::expr('DATE(published) date'))
                        ->select(DB::expr('COUNT(id_ad) count'))
                        ->from('ads')
                        ->where('status','=',Model_Ad::STATUS_PUBLISHED)
                        //->where(DB::expr('TIMESTAMPDIFF( DAY , published, NOW() )') ,'<=','30')
                        ->where('published','between',array($my_from_date,$my_to_date))
                        ->group_by(DB::expr('DATE( published )'))
                        ->order_by('date','asc')
                        ->execute();
        
        $ads_dates = $query->as_array('date');
        
        
        //Today 
        $query = DB::select(DB::expr('COUNT(id_ad) count'))
                        ->from('ads')
                        ->where('status','=',Model_Ad::STATUS_PUBLISHED)
                        ->where(DB::expr('DATE( created )'),'=',DB::expr('CURDATE()'))
                        ->group_by(DB::expr('DATE( published )'))
                        ->order_by('published','asc')
                        ->execute();
        
        $ads = $query->as_array();
        $content->ads_today     = (isset($ads[0]['count']))?$ads[0]['count']:0;
        
        //Yesterday
        $query = DB::select(DB::expr('COUNT(id_ad) count'))
                        ->from('ads')
                        ->where('status','=',Model_Ad::STATUS_PUBLISHED)
                        ->where(DB::expr('DATE( created )'),'=',date('Y-m-d',strtotime('-1 day')))
                        ->group_by(DB::expr('DATE( published )'))
                        ->order_by('published','asc')
                        ->execute();
        
        $ads = $query->as_array();
        $content->ads_yesterday = (isset($ads[0]['count']))?$ads[0]['count']:0;
        
        
        //Last 30 days ads
        $query = DB::select(DB::expr('COUNT(id_ad) count'))
                        ->from('ads')
                        ->where('status','=',Model_Ad::STATUS_PUBLISHED)
                        ->where('published','between',array(date('Y-m-d',strtotime('-30 day')),date::unix2mysql()))
                        ->execute();
        
        $ads = $query->as_array();
        $content->ads_month = (isset($ads[0]['count']))?$ads[0]['count']:0;
        
        //total ads
        $query = DB::select(DB::expr('COUNT(id_ad) count'))
                        ->from('ads')
                        ->where('status','=',Model_Ad::STATUS_PUBLISHED)
                        ->execute();
        
        $ads = $query->as_array();
        $content->ads_total = (isset($ads[0]['count']))?$ads[0]['count']:0;
        
        /////////////////////VISITS STATS////////////////////////////////
        
        //visits created last XX days
        $query = DB::select(DB::expr('DATE(created) date'))
                        ->select(DB::expr('COUNT(id_visit) count'))
                        ->from('visits')
                        ->where('created','between',array($my_from_date,$my_to_date))
                        ->group_by(DB::expr('DATE( created )'))
                        ->order_by('date','asc')
                        ->execute();
        
        $visits = $query->as_array('date');
        
        
        $stats_daily = array();
        foreach ($dates as $date) 
        {
            $count_views = (isset($visits[$date['date']]['count']))?$visits[$date['date']]['count']:0;
            $count_ads = (isset($ads_dates[$date['date']]['count']))?$ads_dates[$date['date']]['count']:0;
            
            $stats_daily[] = array('date'=>$date['date'],'views'=> $count_views,'ads'=>$count_ads);
        } 
        
        $content->stats_daily =  $stats_daily;
        
        
         //Today 
        $query = DB::select(DB::expr('COUNT(id_visit) count'))
                        ->from('visits')
                        ->where(DB::expr('DATE( created )'),'=',DB::expr('CURDATE()'))
                        ->group_by(DB::expr('DATE( created )'))
                        ->order_by('created','asc')
                        ->execute();
        
        $ads = $query->as_array();
        $content->visits_today     = (isset($ads[0]['count']))?$ads[0]['count']:0;
        
        //Yesterday
        $query = DB::select(DB::expr('COUNT(id_visit) count'))
                        ->from('visits')
                        ->where(DB::expr('DATE( created )'),'=',date('Y-m-d',strtotime('-1 day')))
                        ->group_by(DB::expr('DATE( created )'))
                        ->order_by('created','asc')
                        ->execute();
        
        $ads = $query->as_array();
        $content->visits_yesterday= (isset($ads[0]['count']))?$ads[0]['count']:0;
        
        
        //Last 30 days visits
        $query = DB::select(DB::expr('COUNT(id_visit) count'))
                        ->from('visits')
                        ->where('created','between',array(date('Y-m-d',strtotime('-30 day')),date::unix2mysql()))
                        ->execute();
        
        $visits = $query->as_array();
        $content->visits_month = (isset($visits[0]['count']))?$visits[0]['count']:0;
        
        //total visits
        $query = DB::select(DB::expr('COUNT(id_visit) count'))
                        ->from('visits')
                        ->execute();
        
        $visits = $query->as_array();
        $content->visits_total = (isset($visits[0]['count']))?$visits[0]['count']:0;
        
        
        /////////////////////ORDERS STATS////////////////////////////////
        
        //orders created last XX days
        $query = DB::select(DB::expr('DATE(created) date'))
                        ->select(DB::expr('COUNT(id_order) count'))
                        ->select(DB::expr('SUM(amount) total'))
                        ->from('orders')
                        ->where('created','between',array($my_from_date,$my_to_date))
                        ->where('status','=',Model_Order::STATUS_PAID)
                        ->group_by(DB::expr('DATE( created )'))
                        ->order_by('date','asc')
                        ->execute();
        
        $orders = $query->as_array('date');
        
        
        $stats_orders = array();
        foreach ($dates as $date) 
        {
            $count_orders = (isset($orders[$date['date']]['count']))?$orders[$date['date']]['count']:0;
            $count_sum = (isset($orders[$date['date']]['total']))?$orders[$date['date']]['total']:0;
            
            $stats_orders[] = array('date'=>$date['date'],'#orders'=> $count_orders,'$'=>$count_sum);
        } 
        
        $content->stats_orders =  $stats_orders;
        
        
        //Today 
        $query = DB::select(DB::expr('COUNT(id_order) count'))
                        ->from('orders')
                        ->where(DB::expr('DATE( created )'),'=',DB::expr('CURDATE()'))
                        ->where('status','=',Model_Order::STATUS_PAID)
                        ->group_by(DB::expr('DATE( created )'))
                        ->order_by('created','asc')
                        ->execute();
        
        $ads = $query->as_array();
        $content->orders_yesterday     = (isset($ads[0]['count']))?$ads[0]['count']:0;
        
        //Yesterday
        $query = DB::select(DB::expr('COUNT(id_order) count'))
                        ->from('orders')
                        ->where(DB::expr('DATE( created )'),'=',date('Y-m-d',strtotime('-1 day')))
                        ->where('status','=',Model_Order::STATUS_PAID)
                        ->group_by(DB::expr('DATE( created )'))
                        ->order_by('created','asc')
                        ->execute();
        
        $ads = $query->as_array();
        $content->orders_today = (isset($ads[0]['count']))?$ads[0]['count']:0;
        
        
        //Last 30 days orders
        $query = DB::select(DB::expr('COUNT(id_order) count'))
                        ->from('orders')
                        ->where('created','between',array(date('Y-m-d',strtotime('-30 day')),date::unix2mysql()))
                        ->where('status','=',Model_Order::STATUS_PAID)
                        ->execute();
        
        $orders = $query->as_array();
        $content->orders_month = (isset($orders[0]['count']))?$orders[0]['count']:0;
        
        //total orders
        $query = DB::select(DB::expr('COUNT(id_order) count'))
                        ->from('orders')
                        ->where('status','=',Model_Order::STATUS_PAID)
                        ->execute();
        
        $orders = $query->as_array();
        $content->orders_total = (isset($orders[0]['count']))?$orders[0]['count']:0;
	}
    
    //marked email as subscribed
    public function action_subscribe()
    {
        $this->auto_render = FALSE;
        // Update subscribe config action
        Model_Config::set_value('general', 'subscribe', 1); 
        Core::delete_cache();

        die('OK');
    }
	

}
