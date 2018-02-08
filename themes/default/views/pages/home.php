<?php defined('SYSPATH') or die('No direct script access.');?>

<?if(core::config('advertisement.homepage_map') == 1):?>
    <?=View::factory('pages/map/home')?>
<?endif?>

<?if(core::config('advertisement.ads_in_home') != 3):?>
    <div class="well">
        <h3>
            <?if(core::config('advertisement.ads_in_home') == 0):?>
                <?=_e('Latest Ads')?>
            <?elseif(core::config('advertisement.ads_in_home') == 1 OR core::config('advertisement.ads_in_home') == 4):?>
                <?=_e('Featured Ads')?>
            <?elseif(core::config('advertisement.ads_in_home') == 2):?>
                <?=_e('Popular Ads last month')?>
            <?endif?>
            <?if ($user_location) :?>
                <small><?=$user_location->name?></small>
            <?endif?>
        </h3>
        <div class="row">
            <?$i=0; foreach($ads as $ad):?>
                <div class="col-md-3">
                    <div class="thumbnail latest_ads">
                        <a href="<?=Route::url('ad', array('category'=>$ad->category->seoname,'seotitle'=>$ad->seotitle))?>"  class="min-h">
                            <?if($ad->get_first_image()!== NULL):?>
                                <?=HTML::picture($ad->get_first_image('image'), ['w' => 132, 'h' => 132], ['992px' => ['w' => '132', 'h' => '132'], '320px' => ['w' => '648', 'h' => '648']], ['alt' => HTML::chars($ad->title)])?>
                            <?elseif( ($icon_src = $ad->category->get_icon()) !== FALSE):?>
                                <?=HTML::picture($icon_src, ['w' => 132, 'h' => 132], ['992px' => ['w' => '132', 'h' => '132'], '320px' => ['w' => '648', 'h' => '648']], ['alt' => HTML::chars($ad->title)])?>
                            <?else:?>
                                <img data-src="holder.js/179x179?<?=str_replace('+', ' ', http_build_query(array('text' => $ad->category->name, 'size' => 14, 'auto' => 'yes')))?>" alt="<?=HTML::chars($ad->title)?>">  
                            <?endif?>
                        </a>
                        <div class="caption">
                            <h5><a href="<?=Route::url('ad', array('controller'=>'ad','category'=>$ad->category->seoname,'seotitle'=>$ad->seotitle))?>"><?=$ad->title?></a></h5>
                            <p ><?=Text::limit_chars(Text::removebbcode($ad->description), 30, NULL, TRUE)?></p>
                        </div>
                    </div>
                </div>     
            <?endforeach?>
        </div>
    </div>
<?endif?>
<div class='well'>
    <h3>
        <?=_e("Categories")?>
        <?if ($user_location) :?>
            <small><?=$user_location->name?></small>
        <?endif?>
    </h3>
    <div class="row">
        <?$i=0; foreach($categs as $c):?>
            <?if($c['id_category_parent'] == 1 AND $c['id_category'] != 1 AND ! in_array($c['id_category'], $hide_categories)):?>
                <div class="col-md-4">
                    <div class="panel panel-home-categories">
                        <div class="panel-heading">
                            <a title="<?=HTML::chars((strip_tags($c['description'])!=='')?strip_tags($c['description']):$c['name'])?>" href="<?=Route::url('list', array('category'=>$c['seoname'], 'location'=>$user_location ? $user_location->seoname : NULL))?>"><?=mb_strtoupper($c['name']);?></a>
                        </div>
                        <div class="panel-body">
                            <ul class="list-group">
                                <?$ci=0; foreach($categs as $chi):?>
                                    <?if($chi['id_category_parent'] == $c['id_category'] AND ! in_array($chi['id_category'], $hide_categories)):?>
                                        <?if ($ci < 15):?>
                                            <li class="list-group-item">
                                                <a title="<?=HTML::chars($chi['name'])?>" href="<?=Route::url('list', array('category'=>$chi['seoname'], 'location'=>$user_location ? $user_location->seoname : NULL))?>"><?=$chi['name'];?> 
                                                    <?if (Theme::get('category_badge')!=1) : ?>
                                                        <span class="pull-right badge badge-success"><?=number_format($chi['count'])?></span>
                                                    <?endif?>
                                                </a>
                                            </li>
                                        <?endif?>
                                        <?$ci++; if ($ci == 15):?>
                                            <li class="list-group-item">
                                                <a role="button" 
                                                    class="show-all-categories"
                                                    data-cat-id="<?=$c['id_category']?>">
                                                    <?=_e("See all categories")?> <span class="glyphicon glyphicon-chevron-right pull-right"></span>
                                                </a>
                                            </li>
                                        <?endif?>
                                    <?endif?>
                                 <?endforeach?>
                            </ul>
                        </div>
                    </div>
                </div>
                <? $i++; if ($i%3 == 0) echo '<div class="clear"></div>';?>
            <?endif?>
        <?endforeach?>
    </div>
</div>

<?if(core::config('advertisement.homepage_map') == 2):?>
    <?=View::factory('pages/map/home')?>
<?endif?>

<div id="modalAllCategories" class="modal fade" tabindex="-1" data-apiurl="<?=Route::url('api', array('version'=>'v1', 'format'=>'json', 'controller'=>'categories'))?>">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <div class="list-group">
                </div>
            </div>
        </div>
    </div>
</div>
<?if(core::config('general.auto_locate') AND ! Cookie::get('user_location') AND Core::is_HTTPS()):?>
    <input type="hidden" name="auto_locate" value="<?=core::config('general.auto_locate')?>">
    <?if(core::count($auto_locats) > 0):?>
        <div class="modal fade" id="auto-locations" tabindex="-1" role="dialog" aria-labelledby="autoLocations" aria-hidden="true">
            <div class="modal-dialog	modal-sm">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                        <h4 id="autoLocations" class="modal-title text-center"><?=_e('Please choose your closest location')?></h4>
                    </div>
                    <div class="modal-body">
                        <div class="list-group">
                            <?foreach($auto_locats as $loc):?>
                                <a href="<?=Route::url('default')?>" class="list-group-item" data-id="<?=$loc->id_location?>"><span class="pull-right"><span class="glyphicon glyphicon-chevron-right"></span></span> <?=$loc->name?> (<?=i18n::format_measurement($loc->distance)?>)</a>
                            <?endforeach?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?endif?>
<?endif?>