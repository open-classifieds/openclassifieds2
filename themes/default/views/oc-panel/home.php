<?php defined('SYSPATH') or die('No direct script access.');?>

<div class="row">
    <div class="col-lg-12 page-title-container">
        <h1 class="page-header page-title"><?=core::config('general.site_name')?> <?=__('panel')?></h1>
        <span class="page-description"><?=__('This is the main overview page of your Open Classifieds website.')?> <a href=""><?=__('Read more')?></a>
    </div>
</div>

<div class="row" id="intro-panel">
    <div class="col-xs-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="page-header"><?=__('Welcome')?> <?=Auth::instance()->get_user()->name?></h3>
                <a class="close-panel"><i class="fa fa-close"></i><span><?=__('Hide')?></span></a>
            </div>
            <div class="panel-body">
                <p><?=__('Thanks for using Open Classifieds. If you have any questions you can you can click the help button in the upper right corner. <br />')?> <?=__('Your installation version is')?> <a class="ajax-load" href="<?=Route::url('oc-panel',array('controller'=>'update','action'=>'index'))?>?reload=1" title="<?=__('Check for updates')?>"><?=core::VERSION?> <?=__('(update available)')?></a>.</p>
                <h4 class="page-header"><?=__('Lets get started')?></h4><br />
                <a class="start-link ajax-load" href="<?=Route::url('oc-panel',array('controller'=>'content','action'=>'page'))?>"><i class="linecons li_note"></i><?=__('Create or edit content')?></a><a class="start-link ajax-load" href="<?=Route::url('oc-panel',array('controller'=>'theme','action'=>'options'))?>"><i class="linecons li_photo"></i><?=__('Change the theme options')?></a><a class="start-link ajax-load" href="<?=Route::url('oc-panel',array('controller'=>'settings','action'=>'general'))?>"><i class="linecons li_params"></i><?=__('Edit the settings of this website')?></a>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-xs-12 col-sm-6 col-md-3 block">
        <div class="panel panel-blue">
            <div class="panel-heading">
                <div class="row">
                    <div class="col-xs-3">
                        <i class="linecon li_eye"></i>
                    </div>
                    <div class="col-xs-9 text-right">
                        <div class="huge">27</div>
                        <div><?=__('New visits')?></div>
                    </div>
                </div>
            </div>
            <a class="ajax-load" href="<?=Route::url('oc-panel',array('controller'=>'stats','action'=>'index'))?>">
                <div class="panel-footer">
                    <span class="pull-left"><?=__('View details')?></span>
                    <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                    <div class="clearfix"></div>
                </div>
            </a>
        </div>
    </div>
    <div class="col-xs-12 col-sm-6 col-md-3 block">
        <div class="panel panel-green">
            <div class="panel-heading">
                <div class="row">
                    <div class="col-xs-3">
                        <i class="linecon li_user"></i>
                    </div>
                    <div class="col-xs-9 text-right">
                        <div class="huge">16</div>
                        <div><?=__('New users')?></div>
                    </div>
                </div>
            </div>
            <a class="ajax-load" href="<?=Route::url('oc-panel',array('controller'=>'stats','action'=>'index'))?>">
                <div class="panel-footer">
                    <span class="pull-left"><?=__('View details')?></span>
                    <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                    <div class="clearfix"></div>
                </div>
            </a>
        </div>
    </div>
    <div class="col-xs-12 col-sm-6 col-md-3 block">
        <div class="panel panel-yellow">
            <div class="panel-heading">
                <div class="row">
                    <div class="col-xs-3">
                        <i class="linecon li_note"></i>
                    </div>
                    <div class="col-xs-9 text-right">
                        <div class="huge">12</div>
                        <div><?=__('New advertisements')?></div>
                    </div>
                </div>
            </div>
            <a class="ajax-load" href="<?=Route::url('oc-panel',array('controller'=>'ad','action'=>'index'))?>">
                <div class="panel-footer">
                    <span class="pull-left"><?=__('View details')?></span>
                    <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                    <div class="clearfix"></div>
                </div>
            </a>
        </div>
    </div>
    <div class="col-xs-12 col-sm-6 col-md-3 block">
        <div class="panel panel-red">
            <div class="panel-heading">
                <div class="row">
                    <div class="col-xs-3">
                        <i class="linecon li_banknote"></i>
                    </div>
                    <div class="col-xs-9 text-right">
                        <div class="huge">4</div>
                        <div><?=__('New orders')?></div>
                    </div>
                </div>
            </div>
            <a class="ajax-load" href="<?=Route::url('oc-panel',array('controller'=>'order','action'=>'index'))?>">
                <div class="panel-footer">
                    <span class="pull-left"><?=__('View details')?></span>
                    <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                    <div class="clearfix"></div>
                </div>
            </a>
        </div>
    </div>
</div>


<div class="row">
    <div class="col-md-6">
        <div class="panel panel-default">
            <div class="panel-heading">
                <i class="fa fa-bar-chart-o fa-fw"></i><h4><?=__('Site Statistics')?></h4><span class="page-description"><?=__('This panel shows how many visitors your website had the past month.')?> <a href=""><?=__('Read more')?></a>
            </div>
            <div class="panel-body">
                <h4 class="empty text-center"><?=__('There are no site statistics yet ...')?></h4>
                <!--<table class="table table-bordered table-condensed">
                    <thead>
                        <tr>
                            <th></th>
                            <th><?=__('Today')?></th>
                            <th><?=__('Yesterday')?></th>
                            <th><?=__('Last 30 days')?></th>
                            <th><?=__('Total')?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><b><?=__('Ads')?></b></td>
                            <td><?=$ads_today?></td>
                            <td><?=$ads_yesterday?></td>
                            <td><?=$ads_month?></td>
                            <td><?=$ads_total?></td>
                        </tr>
                        <tr>
                            <td><b><?=__('Visits')?></b></td>
                            <td><?=$visits_today?></td>
                            <td><?=$visits_yesterday?></td>
                            <td><?=$visits_month?></td>
                            <td><?=$visits_total?></td>
                        </tr>
                        <tr>
                            <td><b><?=__('Sales')?></b></td>
                            <td><?=$orders_today?></td>
                            <td><?=$orders_yesterday?></td>
                            <td><?=$orders_month?></td>
                            <td><?=$orders_total?></td>
                        </tr>
                    </tbody>
                </table>-->
            </div>
        </div>
    </div><!-- /.col-md-6 -->
    <div class="col-md-6">
        <div class="panel panel-default">
            <div class="panel-heading">
                <i class="fa fa-file-text-o"></i><h4><?=__('Latest Published Ads')?></h4><span class="page-description"><?=__('This panel shows the latest published ads on your website.')?> <a href=""><?=__('Read more')?></a>
            </div>
            <div class="panel-body">
                <h4 class="empty text-center"><?=__('There are no published ads yet ...')?></h4>
                <!--<table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th><?=__('Name')?></th>
                            <th><?=__('Category')?></th>
                            <th><?=__('Location')?></th>
                            <?if(core::config('advertisement.count_visits')==1):?>
                            <th><?=__('Hits')?></th>
                            <?endif?>
                            <th><?=__('Date')?></th>
                        </tr>
                    </thead>
                    <?if(isset($res)):?>
                        <tbody>
                            <? $i = 0; foreach($res as $ad):?>
                                    <tr>
                                        <td><?=$ad->id_ad?>
                
                                        <td><a href="<?=Route::url('ad', array('controller'=>'ad','category'=>$ad->category->seoname,'seotitle'=>$ad->seotitle))?>"><?= wordwrap($ad->title, 15, "<br />\n"); ?></a>
                                        </td>
                
                                        <td><?= wordwrap($ad->category->name, 15, "<br />\n"); ?>
                
                                        <td>
                                            <?if($ad->location->loaded()):?>
                                                <?=wordwrap($ad->location->name, 15, "<br />\n");?>
                                            <?else:?>
                                                n/a
                                            <?endif?>
                                        </td>
                
                                        <?if(core::config('advertisement.count_visits')==1):?>
                                        <td><?=$ad->visits->count_all();?></td>
                                        <?endif?>
                
                                        <td><?= Date::format($ad->published, core::config('general.date_format'))?></td>
                                    </tr>
                            <?endforeach?>
                        </tbody>
                    <?endif?>
                </table>-->
            </div>
        </div>
    </div><!-- /.col-md-6 -->
</div><!-- /.row -->

<div class="row">
    <div class="col-md-4 col-sm-12 col-xs-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title">Open-Classifieds <?=__('Latest News')?></h3>
            </div>
            <div class="panel-body">
                <div class="list-group">
                    <?foreach ($rss as $item):?>
                        <a class="list-group-item" target="_blank" href="<?=$item['link']?>" title="<?=HTML::chars($item['title'])?>"><?=$item['title']?></a>
                    <?endforeach?>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4 col-sm-12 col-xs-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title">Tweets by @openclassifieds</h3>
            </div>
            <div class="panel-body">
                <a class="twitter-timeline" href="https://twitter.com/openclassifieds" data-widget-id="428842439499997185"></a>
                <script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+"://platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>
            </div>
        </div>
    </div>
    <div class="col-md-4 col-sm-12 col-xs-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title">Open-Classifieds on Facebook</h3>
            </div>
            <div class="panel-body">
                <iframe src="//www.facebook.com/plugins/likebox.php?href=http%3A%2F%2Fwww.facebook.com%2Fopenclassifieds&amp;width=350&amp;height=600&amp;colorscheme=dark&amp;show_faces=true&amp;header=true&amp;stream=false&amp;show_border=true&amp;appId=181472118540903" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:100%; height:600px;" allowTransparency="true"></iframe>
            </div>
        </div>
    </div>
</div>
