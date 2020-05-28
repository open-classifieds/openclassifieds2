<div class="pull-right">
    <form name="date" class="flex items-center pull-left form-hidden-elements" method="post" action="<?=URL::current()?>">
        <div class="mb-4">
            <div class="relative flex items-stretch w-full">
                <input type="text" class="block appearance-none w-full py-1 px-2 mb-1 text-base leading-normal bg-white text-grey-800 border border-gray-500 rounded" id="from_date" name="from_date" value="<?=$from_date?>" data-date="<?=$from_date?>" data-date-format="yyyy-mm-dd">
                <div class="py-1 px-2 mb-1 text-base font-normal leading-normal text-grey-900 text-center bg-grey-400 border border-4 border-grey-200 rounded"><?=__('To')?></div>
                <input type="text" class="block appearance-none w-full py-1 px-2 mb-1 text-base leading-normal bg-white text-grey-800 border border-gray-500 rounded" id="to_date" name="to_date" value="<?=$to_date?>" data-date="<?=$to_date?>" data-date-format="yyyy-mm-dd">
            </div>
        </div>
    </form>
    <div class="relative pull-left">
        <button class="inline-block align-middle text-center select-none border font-normal whitespace-no-wrap py-2 px-4 rounded text-base leading-normal  btn-default  inline-block w-0 h-0 ml-1 align border-b-0 border-t-1 border-r-1 border-l-1 btn-in-group" type="button" id="datesMenu" data-toggle="relative" aria-haspopup="true" aria-expanded="true">
            <span class="fa fa-tasks"></span>
        </button>
        <ul class=" absolute left-0 z-50 float-left hidden list-none p-0	 py-2 mt-1 text-base bg-white border border-grey-400 rounded" aria-labelledby="datesMenu">
            <li><a href="?from_date=<?=date('Y-m-d', strtotime('-30 days'))?>&amp;to_date=<?=date('Y-m-d', strtotime('now'))?>"><?=__('Last 30 days')?></a></li>
            <li><a href="?from_date=<?=date('Y-m-d', strtotime('-1 month'))?>&amp;to_date=<?=date('Y-m-d', strtotime('now'))?>"><?=__('Last month')?></a></li>
            <li><a href="?from_date=<?=date('Y-m-d', strtotime('-3 months'))?>&amp;to_date=<?=date('Y-m-d', strtotime('now'))?>"><?=__('Last 3 months')?></a></li>
            <li><a href="?from_date=<?=date('Y-m-d', strtotime('-6 months'))?>&amp;to_date=<?=date('Y-m-d', strtotime('now'))?>"><?=__('Last 6 months')?></a></li>
            <li><a href="?from_date=<?=date('Y-m-d', strtotime('-1 year'))?>&amp;to_date=<?=date('Y-m-d', strtotime('now'))?>"><?=__('Last year')?></a></li>
            <li><a href="?from_date=2014-11-01&amp;to_date=<?=date('Y-m-d', strtotime('now'))?>"><?=__('All time')?></a></li>
        </ul>
    </div>
</div>

<h1 class="page-header page-title">
    <?=__('Site usage statistics')?>
</h1>

<hr>

<p>
    <?=__('This panel shows how many visitors your website had the past month.')?>
</p>


<div class="flex flex-wrap">
    
</div>
<div class="flex flex-wrap">
    <div class="sm:w-1/2 pr-4 pl-4 md:w-1/4 pr-4 pl-4">
        <div class="statcard statcard-success">
            <div class="p-a">
                <h4 class="statcard-desc"><?=__('Ads')?></h4><a href="<?=Route::url('oc-panel', array('controller'=> Request::current()->controller(), 'action'=>'ads'))?>?<?=http_build_query(['rel' => ''] + Request::current()->query())?>" class="inline-block align-middle text-center select-none border font-normal whitespace-no-wrap py-2 px-4 rounded text-base leading-normal  text-blue-100 bg-blue-500 hover:bg-blue-400 py-1 px-2 text-sm leading-tight pull-right"><?=__('More')?></a>
                <h2 class="statcard-number">
                    <?=Num::format($ads_total, 0)?>
                    <small class="delta-indicator <?=:percent_change($ads_total, $ads_total_past) < 0 ? 'delta-negative' : 'delta-positive'?>"><?=Num::percent_change($ads_total, $ads_total_past)?></small> 
                    <small class="ago"><?=sprintf(__('%s days ago'), $days_ago)?></small>
                </h2>
                <hr class="statcard-hr">
            </div>
            <div>
                <?=Chart::line($ads, $chart_config, $chart_colors)?>
            </div>
        </div>
    </div>
    <div class="sm:w-1/2 pr-4 pl-4 md:w-1/4 pr-4 pl-4">
        <div class="statcard statcard-success">
            <div class="p-a">
                <h4 class="statcard-desc"><?=__('Users')?></h4><a href="<?=Route::url('oc-panel', array('controller'=> Request::current()->controller(), 'action'=>'users'))?>?<?=http_build_query(['rel' => ''] + Request::current()->query())?>" class="inline-block align-middle text-center select-none border font-normal whitespace-no-wrap py-2 px-4 rounded text-base leading-normal  text-blue-100 bg-blue-500 hover:bg-blue-400 py-1 px-2 text-sm leading-tight pull-right"><?=__('More')?></a>
                <h2 class="statcard-number">
                    <?=Num::format($users_total, 0)?>
                    <small class="delta-indicator <?=:percent_change($users_total, $users_total_past) < 0 ? 'delta-negative' : 'delta-positive'?>"><?=Num::percent_change($users_total, $users_total_past)?></small> 
                    <small class="ago"><?=sprintf(__('%s days ago'), $days_ago)?></small>
                </h2>
                <hr class="statcard-hr">
            </div>
            <div>
                <?=Chart::line($users, $chart_config, $chart_colors)?>
            </div>
        </div>
    </div>
    <div class="sm:w-1/2 pr-4 pl-4 md:w-1/4 pr-4 pl-4">
        <div class="statcard statcard-success">
            <div class="p-a">
                <h4 class="statcard-desc"><?=__('Visits')?></h4><a href="<?=Route::url('oc-panel', array('controller'=> Request::current()->controller(), 'action'=>'visits'))?>?<?=http_build_query(['rel' => ''] + Request::current()->query())?>" class="inline-block align-middle text-center select-none border font-normal whitespace-no-wrap py-2 px-4 rounded text-base leading-normal  text-blue-100 bg-blue-500 hover:bg-blue-400 py-1 px-2 text-sm leading-tight pull-right"><?=__('More')?></a>
                <h2 class="statcard-number">
                    <?=Num::format($visits_total, 0)?>
                    <small class="delta-indicator <?=:percent_change($visits_total, $visits_total_past) < 0 ? 'delta-negative' : 'delta-positive'?>"><?=Num::percent_change($visits_total, $visits_total_past)?></small> 
                    <small class="ago"><?=sprintf(__('%s days ago'), $days_ago)?></small>
                </h2>
                <hr class="statcard-hr">
            </div>
            <div>
                <?=Chart::line($visits, $chart_config, $chart_colors)?>
            </div>
        </div>
    </div>
    <div class="sm:w-1/2 pr-4 pl-4 md:w-1/4 pr-4 pl-4">
        <div class="statcard statcard-success">
            <div class="p-a">
                <h4 class="statcard-desc"><?=__('Contacts')?></h4><a href="<?=Route::url('oc-panel', array('controller'=> Request::current()->controller(), 'action'=>'contacts'))?>?<?=http_build_query(['rel' => ''] + Request::current()->query())?>" class="inline-block align-middle text-center select-none border font-normal whitespace-no-wrap py-2 px-4 rounded text-base leading-normal  text-blue-100 bg-blue-500 hover:bg-blue-400 py-1 px-2 text-sm leading-tight pull-right"><?=__('More')?></a>
                <h2 class="statcard-number">
                    <?=Num::format($contacts_total, 0)?>
                    <small class="delta-indicator <?=:percent_change($contacts_total, $contacts_total_past) < 0 ? 'delta-negative' : 'delta-positive'?>"><?=Num::percent_change($contacts_total, $contacts_total_past)?></small> 
                    <small class="ago"><?=sprintf(__('%s days ago'), $days_ago)?></small>
                </h2>
                <hr class="statcard-hr">
            </div>
            <div>
                <?=Chart::line($contacts, $chart_config, $chart_colors)?>
            </div>
        </div>
    </div>
    <div class="sm:w-1/2 pr-4 pl-4 md:w-1/4 pr-4 pl-4">
        <div class="statcard statcard-success">
            <div class="p-a">
                <h4 class="statcard-desc"><?=__('Paid Orders')?></h4><a href="<?=Route::url('oc-panel', array('controller'=> Request::current()->controller(), 'action'=>'paid_orders'))?>?<?=http_build_query(['rel' => ''] + Request::current()->query())?>" class="inline-block align-middle text-center select-none border font-normal whitespace-no-wrap py-2 px-4 rounded text-base leading-normal  text-blue-100 bg-blue-500 hover:bg-blue-400 py-1 px-2 text-sm leading-tight pull-right"><?=__('More')?></a>
                <h2 class="statcard-number">
                    <?=Num::format($paid_orders_total, 0)?>
                    <small class="delta-indicator <?=:percent_change($paid_orders_total, $paid_orders_total_past) < 0 ? 'delta-negative' : 'delta-positive'?>"><?=Num::percent_change($paid_orders_total, $paid_orders_total_past)?></small> 
                    <small class="ago"><?=sprintf(__('%s days ago'), $days_ago)?></small>
                </h2>
                <hr class="statcard-hr">
            </div>
            <div>
                <?=Chart::line($paid_orders, $chart_config, $chart_colors)?>
            </div>
        </div>
    </div>
    <div class="sm:w-1/2 pr-4 pl-4 md:w-1/4 pr-4 pl-4">
        <div class="statcard statcard-success">
            <div class="p-a">
                <h4 class="statcard-desc"><?=__('Sales')?></h4><a href="<?=Route::url('oc-panel', array('controller'=> Request::current()->controller(), 'action'=>'sales'))?>?<?=http_build_query(['rel' => ''] + Request::current()->query())?>" class="inline-block align-middle text-center select-none border font-normal whitespace-no-wrap py-2 px-4 rounded text-base leading-normal  text-blue-100 bg-blue-500 hover:bg-blue-400 py-1 px-2 text-sm leading-tight pull-right"><?=__('More')?></a>
                <h2 class="statcard-number">
                    <?=i18n::format_currency($sales_total)?>
                    <small class="delta-indicator <?=:percent_change($sales_total, $sales_total_past) < 0 ? 'delta-negative' : 'delta-positive'?>"><?=Num::percent_change($sales_total, $sales_total_past)?></small> 
                    <small class="ago"><?=sprintf(__('%s days ago'), $days_ago)?></small>
                </h2>
                <hr class="statcard-hr">
            </div>
            <div>
                <?=Chart::line($sales, $chart_config, $chart_colors)?>
            </div>
        </div>
    </div>
</div>
