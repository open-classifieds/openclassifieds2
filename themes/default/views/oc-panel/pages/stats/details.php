<div class="flex flex-wrap">
    <div class="lg:w-full pr-4 pl-4 page-title-container">
        <h1 class="page-header page-title"><?=$title?></h1>
    </div>
</div>
<div class="flex flex-wrap">
    <div class="sm:w-full pr-4 pl-4 space-bottom">
        <form name="date" class="flex items-center pull-left form-hidden-elements" method="post" action="<?=URL::current()?>">
            <div class="mb-4">
                <div class="relative flex items-stretch w-full">
                    <div class="py-1 px-2 mb-1 text-base font-normal leading-normal text-grey-900 text-center bg-grey-400 border border-4 border-grey-200 rounded"><?=__('From')?></div>
                    <input type="text" class="block appearance-none w-full py-1 px-2 mb-1 text-base leading-normal bg-white text-grey-800 border border-gray-500 rounded" id="from_date" name="from_date" value="<?=$from_date?>" data-date="<?=$from_date?>" data-date-format="yyyy-mm-dd">
                    <span class="py-1 px-2 mb-1 text-base font-normal leading-normal text-grey-900 text-center bg-grey-400 border border-4 border-grey-200 rounded">
                        <span class="glyphicon glyphicon-calendar"></span>
                    </span>
                </div>
            </div>
            <span>-</span>
            <div class="mb-4">
                <div class="relative flex items-stretch w-full">
                    <div class="py-1 px-2 mb-1 text-base font-normal leading-normal text-grey-900 text-center bg-grey-400 border border-4 border-grey-200 rounded"><?=__('To')?></div>
                    <input type="text" class="block appearance-none w-full py-1 px-2 mb-1 text-base leading-normal bg-white text-grey-800 border border-gray-500 rounded" id="to_date" name="to_date" value="<?=$to_date?>" data-date="<?=$to_date?>" data-date-format="yyyy-mm-dd">
                    <span class="py-1 px-2 mb-1 text-base font-normal leading-normal text-grey-900 text-center bg-grey-400 border border-4 border-grey-200 rounded">
                        <span class="glyphicon glyphicon-calendar"></span>
                    </span>
                </div>
            </div>
        </form>
        <div class="relative pull-left">
            <button class="inline-block align-middle text-center select-none border font-normal whitespace-no-wrap py-2 px-4 rounded text-base leading-normal  btn-default  inline-block w-0 h-0 ml-1 align border-b-0 border-t-1 border-r-1 border-l-1 btn-in-group" type="button" id="datesMenu" data-toggle="relative" aria-haspopup="true" aria-expanded="true">
                <span class="fa fa-tasks"></span>
            </button>
            <ul class=" absolute left-0 z-50 float-left hidden list-none p-0	 py-2 mt-1 text-base bg-white border border-grey-400 rounded" aria-labelledby="datesMenu">
                <li><a href="?<?=http_build_query(['rel' => ''] + ['from_date' => date('Y-m-d', strtotime('-30 days'))] + ['to_date' => date('Y-m-d', strtotime('now'))] + Request::current()->query())?>"><?=__('Last 30 days')?></a></li>
                <li><a href="?<?=http_build_query(['rel' => ''] + ['from_date' => date('Y-m-d', strtotime('-1 month'))] + ['to_date' => date('Y-m-d', strtotime('now'))] + Request::current()->query())?>"><?=__('Last month')?></a></li>
                <li><a href="?<?=http_build_query(['rel' => ''] + ['from_date' => date('Y-m-d', strtotime('-3 months'))] + ['to_date' => date('Y-m-d', strtotime('now'))] + Request::current()->query())?>"><?=__('Last 3 months')?></a></li>
                <li><a href="?<?=http_build_query(['rel' => ''] + ['from_date' => date('Y-m-d', strtotime('-6 months'))] + ['to_date' => date('Y-m-d', strtotime('now'))] + Request::current()->query())?>"><?=__('Last 6 months')?></a></li>
                <li><a href="?<?=http_build_query(['rel' => ''] + ['from_date' => date('Y-m-d', strtotime('-1 year'))] + ['to_date' => date('Y-m-d', strtotime('now'))] + Request::current()->query())?>"><?=__('Last year')?></a></li>
                <li><a href="?<?=http_build_query(['rel' => ''] + ['from_date' => '2014-11-01'] + ['to_date' => date('Y-m-d', strtotime('now'))] + Request::current()->query())?>"><?=__('All time')?></a></li>
            </ul>
        </div>
    </div>
</div>
<div class="flex flex-wrap">
    <div class="md:w-full pr-4 pl-4">
        <div class="statcard statcard-success">
            <div class="p-a">
                <h2 class="statcard-number">
                    <?if ($current_total !== NULL) :?>
                        <?=Num::format($current_total, 0)?>
                    <?else :?>
                         --
                    <?endif?>
                </h2>
                <hr class="statcard-hr">
            </div>
            <?
                $chart_colors = array(array('fill'        => 'rgba(33,150,243,.1)',
                                            'stroke'      => 'rgba(33,150,243,.8)',
                                            'point'       => 'rgba(33,150,243,.8)',
                                            'pointStroke' => 'rgba(33,150,243,.8)'),
               );
            ?>
            <?=Chart::line($current_by_date, array('height'  => 94,
                                                   'width'   => 378,
                                                   'options' => array('responsive'             => true,
                                                                      'maintainAspectRatio'    => true,
                                                                      'scaleShowVerticalLines' => false,
                                                                      'scales'                 => array('xAxes' => array(array('gridLines'=> array('display' => false))),
                                                                                                        'yAxes' => array(array('ticks'=> array('min' => 0)))),
                                                                      'legend'                 => array('display' => false),
                                                                      'tooltipTemplate'        => '<%= datasetLabel %><%= value %>',
                                                                      'multiTooltipTemplate'   => '<%= datasetLabel %><%= value %>',
                                                                      )
                                                   ),
                                                   $chart_colors)?>
        </div>
    </div>
    <div class="md:w-full pr-4 pl-4">
        <div class="statcard statcard-success">
            <ul class="flex flex-wrap list-none p-0 pl-0 mb-0   text-center">
                <li role="presentation">
                    <div class="p-a">
                        <span class="statcard-desc"><?=__('Current')?></span>
                        <h2 class="statcard-number">
                            <?if ($current_total !== NULL) :?>
                                <?=Num::format($current_total, 0)?>
                                <small class="delta-indicator <?=:percent_change($current_total, $past_total) < 0 ? 'delta-negative' : 'delta-positive'?>"><?=Num::percent_change($current_total, $past_total)?></small>
                            <?else :?>
                                --
                            <?endif?>
                        </h2>
                    </div>
                </li>
                <li role="presentation">
                    <div class="p-a">
                        <span class="statcard-desc"><?=__('1 Month Ago')?></span>
                        <h2 class="statcard-number">
                            <?if ($month_ago_total !== NULL) :?>
                                <?=Num::format($month_ago_total, 0)?>
                                <small class="delta-indicator <?=:percent_change($month_ago_total, $past_month_ago_total) < 0 ? 'delta-negative' : 'delta-positive'?>"><?=Num::percent_change($month_ago_total, $past_month_ago_total)?></small>
                            <?else:?>
                                --
                            <?endif?>
                        </h2>
                    </div>
                </li>
                <li role="presentation">
                    <div class="p-a">
                        <span class="statcard-desc"><?=__('3 Months Ago')?></span>
                        <h2 class="statcard-number">
                            <?if ($three_months_ago_total !== NULL) :?>
                                <?=Num::format($three_months_ago_total, 0)?>
                                <small class="delta-indicator <?=:percent_change($three_months_ago_total, $past_three_months_ago_total) < 0 ? 'delta-negative' : 'delta-positive'?>"><?=Num::percent_change($three_months_ago_total, $past_three_months_ago_total)?></small>
                            <?else:?>
                                --
                            <?endif?>
                        </h2>
                    </div>
                </li>
                <li role="presentation">
                    <div class="p-a">
                        <span class="statcard-desc"><?=__('6 Months Ago')?></span>
                        <h2 class="statcard-number">
                            <?if ($six_months_ago_total !== NULL) :?>
                                <?=Num::format($six_months_ago_total, 0)?>
                                <small class="delta-indicator <?=:percent_change($six_months_ago_total, $past_six_months_ago_total) < 0 ? 'delta-negative' : 'delta-positive'?>"><?=Num::percent_change($six_months_ago_total, $past_six_months_ago_total)?></small>
                            <?else:?>
                                --
                            <?endif?>
                        </h2>
                    </div>
                </li>
                <li role="presentation">
                    <div class="p-a">
                        <span class="statcard-desc"><?=__('12 Months Ago')?></span>
                        <h2 class="statcard-number">
                            <?if ($six_months_ago_total !== NULL) :?>
                                <?=Num::format($twelve_months_ago_total, 0)?>
                                <small class="delta-indicator <?=:percent_change($twelve_months_ago_total, $twelve_six_months_ago_total) < 0 ? 'delta-negative' : 'delta-positive'?>"><?=Num::percent_change($twelve_months_ago_total, $twelve_six_months_ago_total)?></small>
                            <?else:?>
                                --
                            <?endif?>
                        </h2>
                    </div>
                </li>
            </ul>
        </div>
    </div>
</div>

<hr>
