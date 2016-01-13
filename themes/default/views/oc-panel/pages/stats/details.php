<div class="row">
    <div class="col-lg-12 page-title-container">
        <h1 class="page-header page-title"><?=$title?></h1>
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
<div class="row">
    <div class="col-md-12">
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
                                                   'options' => array('animation'              => false,
                                                                      'responsive'             => true,
                                                                      'bezierCurve'            => true,
                                                                      'bezierCurveTension'     => '.25',
                                                                      'showScale'              => true,
                                                                      'pointDotRadius'         => 0,
                                                                      'pointDotStrokeWidth'    => 0,
                                                                      'pointDot'               => false,
                                                                      'maintainAspectRatio'    => true,
                                                                      'scaleShowVerticalLines' => false,
                                                                      'tooltipTemplate'        => '<%= datasetLabel %><%= value %>',
                                                                      'multiTooltipTemplate'   => '<%= datasetLabel %><%= value %>',
                                                                      'showTooltips'           => true)),
                                                   $chart_colors)?>
        </div>
    </div>
    <div class="col-md-12">
        <div class="statcard statcard-success">
            <ul class="nav nav-pills nav-justified text-center">
                <li role="presentation">
                    <div class="p-a">
                        <span class="statcard-desc"><?=__('Current')?></span>
                        <h2 class="statcard-number">
                            <?if ($current_total !== NULL) :?>
                                <?=Num::format($current_total, 0)?>
                                <small class="delta-indicator <?=Num::percent_change($current_total, $past_total) < 0 ? 'delta-negative' : 'delta-positive'?>"><?=Num::percent_change($current_total, $past_total)?></small>
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
                                <small class="delta-indicator <?=Num::percent_change($month_ago_total, $past_month_ago_total) < 0 ? 'delta-negative' : 'delta-positive'?>"><?=Num::percent_change($month_ago_total, $past_month_ago_total)?></small>
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
                                <small class="delta-indicator <?=Num::percent_change($three_months_ago_total, $past_three_months_ago_total) < 0 ? 'delta-negative' : 'delta-positive'?>"><?=Num::percent_change($three_months_ago_total, $past_three_months_ago_total)?></small>
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
                                <small class="delta-indicator <?=Num::percent_change($six_months_ago_total, $past_six_months_ago_total) < 0 ? 'delta-negative' : 'delta-positive'?>"><?=Num::percent_change($six_months_ago_total, $past_six_months_ago_total)?></small>
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
                                <small class="delta-indicator <?=Num::percent_change($twelve_months_ago_total, $twelve_six_months_ago_total) < 0 ? 'delta-negative' : 'delta-positive'?>"><?=Num::percent_change($twelve_months_ago_total, $twelve_six_months_ago_total)?></small>
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
