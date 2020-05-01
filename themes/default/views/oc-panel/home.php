<?php defined('SYSPATH') or die('No direct script access.');?>

<?=View::factory('pwa/_alert')?>

<h1 class="page-header page-title" id="page-welcome">
    <?=core::config('general.site_name')?> <?=__('panel')?>
</h1>

<hr>

<? if (Auth::instance()->get_user()->is_admin() AND Core::config('license.number') == NULL) : ?>
    <div class="panel panel-default">
        <div class="panel-heading">
            <div class="panel-title"><span class="text-info">Lite version</span></div>
        </div>
        <div class="panel-body">
            <div class="alert alert-info fade in">
                <p>
                    <strong><?=__('Heads Up!')?></strong>
                    <?=__('You are using our Lite version, to enable all the features and to get support upgrade to PRO')?>
                </p>
                <p>
                    <a class="btn btn-info" href="https://yclas.com/self-hosted.html">
                        <?=__('Upgrade to PRO now')?>
                    </a>
                </p>
            </div>
            <hr>
            <form action="<?= Route::url('oc-panel',array('controller'=>'home','action'=> 'license'))?>" method="post">
                <div class="form-group">
                    <label class="control-label text-info"><?=__('Please insert here your license')?></label>
                    <input class="form-control" type="text" name="license" value="" placeholder="<?=__('License')?>">
                </div>
                <button
                    type="button"
                    class="btn btn-primary submit"
                    title="<?=__('Are you sure?')?>"
                    data-text="<?=sprintf(__('License will be activated in %s domain. Once activated, your license cannot be changed to another domain.'), parse_url(URL::base(), PHP_URL_HOST))?>"
                    data-btnOkLabel="<?=__('Yes, definitely!')?>"
                    data-btnCancelLabel="<?=__('No way!')?>">
                    <?=__('Check')?>
                </button>
            </form>
        </div>
    </div>
<?elseif (Auth::instance()->get_user()->is_admin()):?>
    <div class="panel panel-default">
        <div class="panel-heading">
            <div class="panel-title"><span class="text-info">Yclas Self-Hosted Pro Version</span></div>
        </div>
    </div>
<? endif ?>

<p><?=__('This is the main overview page of your website.')?></p>

<?if (core::cookie('intro_panel')!=1):?>
    <div class="row" id="intro-panel">
        <div class="col-xs-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <div class="panel-title"><?=__('Welcome')?> <?=Auth::instance()->get_user()->name?></div>
                    <a href="#" class="close-panel"><i class="fa fa-close"></i><span class="sr-only"><?=__('Hide')?></span></a>
                </div>
                <div class="panel-body">
                    <p>
                        <?=__('Thanks for using Yclas. If you have any questions you can you can click the help button in the upper right corner.')?>
                    </p>
                    <p>
                        <?=__('Your installation version is')?>
                        <a class="ajax-load" href="<?=Route::url('oc-panel',array('controller'=>'update','action'=>'index'))?>?reload=1"><span class="label label-info"><?=core::VERSION?></span></a>
                    </p>
                    <h4 class="page-header"><?=__('Lets get started')?></h4>
                    <hr>
                    <a class="start-link ajax-load" href="<?=Route::url('oc-panel',array('controller'=>'content','action'=>'page'))?>"><i class="linecons li_note"></i><?=__('Create or edit content')?></a><a class="start-link ajax-load" href="<?=Route::url('oc-panel',array('controller'=>'theme','action'=>'options'))?>"><i class="linecons li_photo"></i><?=__('Change the theme options')?></a><a class="start-link ajax-load" href="<?=Route::url('oc-panel',array('controller'=>'settings','action'=>'general'))?>"><i class="linecons li_params"></i><?=__('Edit the settings of this website')?></a>
                </div>
            </div>
        </div>
    </div>
<?endif?>

<?if (in_array(core::config('general.moderation'), Model_Ad::$moderation_status)):?>
    <? $current_url = Model_Ad::STATUS_NOPUBLISHED ?>
    <div class="row">
        <div class="col-xs-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <div class="panel-title"><i class="fa fa-file-text-o"></i> <?=__('Moderation')?></div>
                </div>
                <?if(Core::count($moderate_ads) > 0):?>
                    <div>
                        <form method="GET" enctype="multipart/form-data">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th class="sorting_disabled">
                                            <div class="checkbox check-success">
                                                <input type="checkbox" id="select-all">
                                                <label for="select-all"></label>
                                            </div>
                                        </th>
                                        <th class="sorting_disabled"><?=__('Activate')?></th>
                                        <th class="sorting_disabled"><?=__('Name')?></th>
                                        <th class="sorting_disabled"><?=__('Category')?></th>
                                        <th class="sorting_disabled"><?=__('Location')?></th>
                                        <?if(core::config('advertisement.count_visits')==1):?>
                                            <th class="sorting_disabled"><?=__('Hits')?></th>
                                        <?endif?>
                                        <th class="sorting_disabled"><?=__('Date')?></th>
                                        <!-- in case there are no ads we dont show buttons -->
                                        <?if(isset($moderate_ads)):?>
                                            <th class="sorting_disabled nowrap">
                                                <div class="dropdown">
                                                    <button class="btn btn-default dropdown-toggle" type="button" data-toggle="dropdown">
                                                        <i class="fa fa-cog"></i>
                                                    </button>
                                                    <ul class="dropdown-menu dropdown-menu-right">
                                                        <li>
                                                            <button class="btn btn-block btn-link activate"
                                                                    data-toggle="confirmation"
                                                                    formaction="<?=Route::url('oc-panel', array('controller'=>'ad','action'=>'activate'))?>"
                                                                    data-btnOkLabel="<?=__('Yes, definitely!')?>"
                                                                    data-btnCancelLabel="<?=__('No way!')?>"
                                                                    title="<?=__('Activate?')?>">
                                                                <i class="fa fa-check"></i> <?=__('Activate')?></span>
                                                            </button>
                                                        </li>
                                                        <?if(Core::get('status') != Model_Ad::STATUS_SPAM):?>
                                                            <li>
                                                                <button class="btn btn-block btn-link spam"
                                                                        data-toggle="confirmation"
                                                                        formaction="<?=Route::url('oc-panel', array('controller'=>'ad','action'=>'spam'))?>"
                                                                        data-btnOkLabel="<?=__('Yes, definitely!')?>"
                                                                        data-btnCancelLabel="<?=__('No way!')?>"
                                                                        title="<?=__('Spam?')?>">
                                                                    <i class="fa fa-fw fa-fire"></i> <?=__('Spam')?></span>
                                                                </button>
                                                            </li>
                                                        <?endif?>
                                                        <li>
                                                            <button class="btn btn-block btn-link delete"
                                                                    data-toggle="confirmation"
                                                                    formaction="<?=Route::url('oc-panel', array('controller'=>'ad','action'=>'delete'))?>"
                                                                    data-btnOkLabel="<?=__('Yes, definitely!')?>"
                                                                    data-btnCancelLabel="<?=__('No way!')?>"
                                                                    title="<?=__('Delete?')?>" data-text="<?=__('Are you sure you want to delete?')?>">
                                                                <i class="fa fa-fw fa-times"></i> <?=__('Delete')?></span>
                                                            </button>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </th>
                                        <?endif?>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?foreach($moderate_ads as $ad):?>
                                        <tr id="tr<?=$ad->id_ad?>">
                                            <td>
                                                <div class="checkbox check-success">
                                                    <input name="id_ads[]" value="<?=$ad->id_ad?>" type="checkbox" id="ad_<?=$ad->id_ad?>">
                                                    <label for="ad_<?=$ad->id_ad?>"></label>
                                                </div>
                                            </td>
                                            <td>
                                                <a
                                                    href="<?=Route::url('oc-panel', array('controller'=>'ad','action'=>'activate','id'=>$ad->id_ad, 'current_url'=>$current_url))?>"
                                                    class="btn btn-success index-moderation"
                                                    title="<?=__('Activate?')?>"
                                                    data-id="tr<?=$ad->id_ad?>"
                                                    data-btnOkLabel="<?=__('Yes, definitely!')?>"
                                                    data-btnCancelLabel="<?=__('No way!')?>">
                                                    <i class="glyphicon glyphicon-ok-sign"></i> <?=$ad->id_ad?>
                                                </a>
                                            </td>

                                            <td><a href="<?=Route::url('oc-panel', array('controller'=>'myads','action'=>'update','id'=>$ad->id_ad))?>"><?= wordwrap($ad->title, 45, "<br />\n"); ?></a>
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
                                                <td><?=$ad->count_ad_hit();?></td>
                                            <?endif?>
                                            <td><?= Date::format($ad->created, core::config('general.date_format'))?></td>
                                            <td class="nowrap">
                                                <div class="btn-group">
                                                    <a class="btn btn-primary"
                                                       href="<?=Route::url('oc-panel', array('controller'=>'myads','action'=>'update','id'=>$ad->id_ad))?>"
                                                       rel="tooltip" title="<?=__('Edit')?>">
                                                        <i class="fa fa-pencil"></i>
                                                    </a>
                                                    <div class="btn-group">
                                                        <button class="btn btn-default dropdown-toggle" type="button" data-toggle="dropdown">
                                                            <i class="fa fa-cog"></i>
                                                        </button>
                                                        <ul class="dropdown-menu dropdown-menu-right">
                                                            <li>
                                                                <a
                                                                    href="<?=Route::url('oc-panel', array('controller'=>'ad','action'=>'spam','id'=>$ad->id_ad, 'current_url'=>$current_url))?>"
                                                                    class="index-moderation"
                                                                    title="<?=__('Spam')?>"
                                                                    data-id="tr<?=$ad->id_ad?>"
                                                                    data-btnOkLabel="<?=__('Yes, definitely!')?>"
                                                                    data-btnCancelLabel="<?=__('No way!')?>">
                                                                    <i class="fa fa-fw fa-fire"></i> <?=__('Spam')?>
                                                                </a>
                                                            </li>
                                                            <li>
                                                                <a
                                                                    href="<?=Route::url('oc-panel', array('controller'=>'ad','action'=>'delete','id'=>$ad->id_ad, 'current_url'=>$current_url))?>"
                                                                    class="index-moderation"
                                                                    title="<?=__('Delete')?>"
                                                                    data-id="tr<?=$ad->id_ad?>"
                                                                    data-btnOkLabel="<?=__('Yes, definitely!')?>"
                                                                    data-btnCancelLabel="<?=__('No way!')?>">
                                                                    <i class="fa fa-fw fa-trash"></i> <?=__('Delete')?>
                                                                </a>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    <?endforeach?>
                                </tbody>
                            </table>
                        </form>
                    </div>
                <? else : ?>
                    <div class="panel-body">
                        <h4 class="empty text-center"><?=__('There are no ads to moderate yet ...')?></h4>
                    </div>
                <? endif ?>
            </div>
        </div>
    </div>
<?endif?>

<div class="row">
    <div class="col-xs-12 col-sm-6 col-md-3 block">
        <div class="panel panel-blue">
            <div class="panel-heading">
                <div class="row">
                    <div class="col-xs-3">
                        <i class="linecon li_eye"></i>
                    </div>
                    <div class="col-xs-9 text-right">
                        <div class="huge"><?=$visits_today?></div>
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
                        <div class="huge"><?=$users_today?></div>
                        <div><?=__('New users')?></div>
                    </div>
                </div>
            </div>
            <a class="ajax-load" href="<?=Route::url('oc-panel',array('controller'=>'user','action'=>'index'))?>">
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
                        <div class="huge"><?=$ads_today?></div>
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
                        <div class="huge"><?=$orders_today?></div>
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
                <div class="panel-title"><i class="fa fa-bar-chart-o fa-fw"></i> <?=__('Site Statistics')?></div>
            </div>
            <div class="panel-body">
                <p><?=__('This panel shows how many visitors your website had the past month.')?></p>
                <?if ($ads_total == 0) :?>
                    <h4 class="empty text-center"><?=__('There are no site statistics yet ...')?></h4>
                <?endif?>
            </div>
            <?if ($ads_total > 0) :?>
                <table class="table">
                    <thead>
                        <tr>
                            <th class="hidden-xs"></th>
                            <th><?=__('Today')?></th>
                            <th><?=__('Yesterday')?></th>
                            <th class="hidden-xs"><?=__('Last 30 days')?></th>
                            <th><?=__('Total')?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="hidden-xs"><b><?=__('Ads')?></b></td>
                            <td><?=$ads_today?></td>
                            <td><?=$ads_yesterday?></td>
                            <td class="hidden-xs"><?=$ads_month?></td>
                            <td><?=$ads_total?></td>
                        </tr>
                        <tr>
                            <td class="hidden-xs"><b><?=__('Visits')?></b></td>
                            <td><?=$visits_today?></td>
                            <td><?=$visits_yesterday?></td>
                            <td class="hidden-xs"><?=$visits_month?></td>
                            <td><?=$visits_total?></td>
                        </tr>
                        <tr>
                            <td class="hidden-xs"><b><?=__('Sales')?></b></td>
                            <td><?=$orders_today?></td>
                            <td><?=$orders_yesterday?></td>
                            <td class="hidden-xs"><?=$orders_month?></td>
                            <td><?=$orders_total?></td>
                        </tr>
                        <tr>
                            <td class="hidden-xs"><b><?=__('Users')?></b></td>
                            <td><?=$users_today?></td>
                            <td><?=$users_yesterday?></td>
                            <td class="hidden-xs"><?=$users_month?></td>
                            <td><?=$users_total?></td>
                        </tr>
                    </tbody>
                </table>
            <?endif?>
        </div>
    </div><!-- /.col-md-6 -->
    <div class="col-md-6">
        <div class="panel panel-default">
            <div class="panel-heading">
                <div class="panel-title"><i class="fa fa-file-text-o"></i> <?=__('Latest Published Ads')?></div>
            </div>
            <div class="panel-body">
                <p><?=__('This panel shows the latest published ads on your website.')?></p>
                <?if ($ads_total == 0) :?>
                    <h4 class="empty text-center"><?=__('There are no published ads yet ...')?></h4>
                <?endif?>
            </div>
            <?if ($ads_total > 0) :?>
                <table class="table table-condensed">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th><?=__('Name')?></th>
                            <th class="hidden-sm hidden-xs"><?=__('Category')?></th>
                            <th class="hidden-sm hidden-xs"><?=__('Location')?></th>
                            <?if(core::config('advertisement.count_visits')==1):?>
                                <th class="hidden-xs"><?=__('Hits')?></th>
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

                                    <td class="hidden-sm hidden-xs"><?= wordwrap($ad->category->name, 15, "<br />\n"); ?>

                                    <td class="hidden-sm hidden-xs">
                                        <?if($ad->location->loaded()):?>
                                            <?=wordwrap($ad->location->name, 15, "<br />\n");?>
                                        <?else:?>
                                            n/a
                                        <?endif?>
                                    </td>

                                    <?if(core::config('advertisement.count_visits')==1):?>
                                        <td class="hidden-xs"><?=$ad->count_ad_hit();?></td>
                                    <?endif?>

                                    <td><?= Date::format($ad->published, core::config('general.date_format'))?></td>

                                </tr>
                            <?endforeach?>
                        </tbody>
                    <?endif?>
                </table>
            <?endif?>
        </div>
    </div><!-- /.col-md-6 -->
</div><!-- /.row -->
