<div class="row">
    <div class="col-lg-12 page-title-container">
        <h1 class="page-header page-title"><?=__('Site usage statistics')?></h1>
        <span class="page-description"><?=__('This panel shows how many visitors your website had the past month.')?> <a target="_blank" href=""><?=__('Read more')?></a></span>
    </div>
</div>
<div class="row">
    <div class="col-xs-12 space-bottom">
        <form name="date" class="form-inline pull-left form-hidden-elements" method="post" action="<?=URL::current()?>">
            <div class="form-group">
                <div class="input-group">
                    <div class="input-group-addon"><?=__('From')?></div>
                    <input type="text" class="form-control" id="from_date" name="from_date" value="<?=$from_date?>" data-date="<?=$from_date?>" data-date-format="yyyy-mm-dd">
                    <span class="input-group-addon">
                        <span class="glyphicon glyphicon-calendar"></span>
                    </span>
                </div>
            </div>
            <span>-</span>
            <div class="form-group">
                <div class="input-group">
                    <div class="input-group-addon"><?=__('To')?></div>
                    <input type="text" class="form-control" id="to_date" name="to_date" value="<?=$to_date?>" data-date="<?=$to_date?>" data-date-format="yyyy-mm-dd">
                    <span class="input-group-addon">
                        <span class="glyphicon glyphicon-calendar"></span>
                    </span>
                </div>
            </div>
        </form>
        <div class="dropdown pull-left">
            <button class="btn btn-default dropdown-toggle btn-in-group" type="button" id="datesMenu" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                <span class="fa fa-tasks"></span>
            </button>
            <ul class="dropdown-menu" aria-labelledby="datesMenu">
                <li><a href="?from_date=<?=date('Y-m-d', strtotime('-30 days'))?>&amp;to_date=<?=date('Y-m-d', strtotime('now'))?>"><?=__('Last 30 days')?></a></li>
                <li><a href="?from_date=<?=date('Y-m-d', strtotime('-1 month'))?>&amp;to_date=<?=date('Y-m-d', strtotime('now'))?>"><?=__('Last month')?></a></li>
                <li><a href="?from_date=<?=date('Y-m-d', strtotime('-3 months'))?>&amp;to_date=<?=date('Y-m-d', strtotime('now'))?>"><?=__('Last 3 months')?></a></li>
                <li><a href="?from_date=<?=date('Y-m-d', strtotime('-6 months'))?>&amp;to_date=<?=date('Y-m-d', strtotime('now'))?>"><?=__('Last 6 months')?></a></li>
                <li><a href="?from_date=<?=date('Y-m-d', strtotime('-1 year'))?>&amp;to_date=<?=date('Y-m-d', strtotime('now'))?>"><?=__('Last year')?></a></li>
                <li><a href="?from_date=2014-11-01&amp;to_date=<?=date('Y-m-d', strtotime('now'))?>"><?=__('All time')?></a></li>
            </ul>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-sm-6 col-md-3">
        <div class="statcard statcard-success">
            <div class="p-a">
                <h4 class="statcard-desc"><?=__('Ads')?></h4><a href="<?=Route::url('oc-panel', array('controller'=> Request::current()->controller(), 'action'=>'ads'))?>?<?=http_build_query(['rel' => ''] + Request::current()->query())?>" class="btn btn-primary btn-sm pull-right"><?=__('More')?></a>
                <h2 class="statcard-number">
                    <?=Num::format($ads_total, 0)?>
                    <small class="delta-indicator <?=Num::percent_change($ads_total, $ads_total_past) < 0 ? 'delta-negative' : 'delta-positive'?>"><?=Num::percent_change($ads_total, $ads_total_past)?></small> 
                    <small class="ago"><?=sprintf(__('%s days ago'), $days_ago)?></small>
                </h2>
                <hr class="statcard-hr">
            </div>
            <div>
                <?=Chart::line($ads, $chart_config, $chart_colors)?>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-md-3">
        <div class="statcard statcard-success">
            <div class="p-a">
                <h4 class="statcard-desc"><?=__('Users')?></h4><a href="<?=Route::url('oc-panel', array('controller'=> Request::current()->controller(), 'action'=>'users'))?>?<?=http_build_query(['rel' => ''] + Request::current()->query())?>" class="btn btn-primary btn-sm pull-right"><?=__('More')?></a>
                <h2 class="statcard-number">
                    <?=Num::format($users_total, 0)?>
                    <small class="delta-indicator <?=Num::percent_change($users_total, $users_total_past) < 0 ? 'delta-negative' : 'delta-positive'?>"><?=Num::percent_change($users_total, $users_total_past)?></small> 
                    <small class="ago"><?=sprintf(__('%s days ago'), $days_ago)?></small>
                </h2>
                <hr class="statcard-hr">
            </div>
            <div>
                <?=Chart::line($users, $chart_config, $chart_colors)?>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-md-3">
        <div class="statcard statcard-success">
            <div class="p-a">
                <h4 class="statcard-desc"><?=__('Visits')?></h4><a href="<?=Route::url('oc-panel', array('controller'=> Request::current()->controller(), 'action'=>'visits'))?>?<?=http_build_query(['rel' => ''] + Request::current()->query())?>" class="btn btn-primary btn-sm pull-right"><?=__('More')?></a>
                <h2 class="statcard-number">
                    <?=Num::format($visits_total, 0)?>
                    <small class="delta-indicator <?=Num::percent_change($visits_total, $visits_total_past) < 0 ? 'delta-negative' : 'delta-positive'?>"><?=Num::percent_change($visits_total, $visits_total_past)?></small> 
                    <small class="ago"><?=sprintf(__('%s days ago'), $days_ago)?></small>
                </h2>
                <hr class="statcard-hr">
            </div>
            <div>
                <?=Chart::line($visits, $chart_config, $chart_colors)?>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-md-3">
        <div class="statcard statcard-success">
            <div class="p-a">
                <h4 class="statcard-desc"><?=__('Contacts')?></h4><a href="<?=Route::url('oc-panel', array('controller'=> Request::current()->controller(), 'action'=>'contacts'))?>?<?=http_build_query(['rel' => ''] + Request::current()->query())?>" class="btn btn-primary btn-sm pull-right"><?=__('More')?></a>
                <h2 class="statcard-number">
                    <?=Num::format($contacts_total, 0)?>
                    <small class="delta-indicator <?=Num::percent_change($contacts_total, $contacts_total_past) < 0 ? 'delta-negative' : 'delta-positive'?>"><?=Num::percent_change($contacts_total, $contacts_total_past)?></small> 
                    <small class="ago"><?=sprintf(__('%s days ago'), $days_ago)?></small>
                </h2>
                <hr class="statcard-hr">
            </div>
            <div>
                <?=Chart::line($contacts, $chart_config, $chart_colors)?>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-md-3">
        <div class="statcard statcard-success">
            <div class="p-a">
                <h4 class="statcard-desc"><?=__('Paid Orders')?></h4><a href="<?=Route::url('oc-panel', array('controller'=> Request::current()->controller(), 'action'=>'paid_orders'))?>?<?=http_build_query(['rel' => ''] + Request::current()->query())?>" class="btn btn-primary btn-sm pull-right"><?=__('More')?></a>
                <h2 class="statcard-number">
                    <?=Num::format($paid_orders_total, 0)?>
                    <small class="delta-indicator <?=Num::percent_change($paid_orders_total, $paid_orders_total_past) < 0 ? 'delta-negative' : 'delta-positive'?>"><?=Num::percent_change($paid_orders_total, $paid_orders_total_past)?></small> 
                    <small class="ago"><?=sprintf(__('%s days ago'), $days_ago)?></small>
                </h2>
                <hr class="statcard-hr">
            </div>
            <div>
                <?=Chart::line($paid_orders, $chart_config, $chart_colors)?>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-md-3">
        <div class="statcard statcard-success">
            <div class="p-a">
                <h4 class="statcard-desc"><?=__('Sales')?></h4><a href="<?=Route::url('oc-panel', array('controller'=> Request::current()->controller(), 'action'=>'sales'))?>?<?=http_build_query(['rel' => ''] + Request::current()->query())?>" class="btn btn-primary btn-sm pull-right"><?=__('More')?></a>
                <h2 class="statcard-number">
                    <?=i18n::format_currency($sales_total)?>
                    <small class="delta-indicator <?=Num::percent_change($sales_total, $sales_total_past) < 0 ? 'delta-negative' : 'delta-positive'?>"><?=Num::percent_change($sales_total, $sales_total_past)?></small> 
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
