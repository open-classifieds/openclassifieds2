<?php defined('SYSPATH') or die('No direct script access.');?>
<?if(core::config('advertisement.ads_in_home') != 3):?>
    <div class="well">
        <h3>
            <?if(core::config('advertisement.ads_in_home') == 0):?>
                <?=__('Latest Ads')?>
            <?elseif(core::config('advertisement.ads_in_home') == 1 OR core::config('advertisement.ads_in_home') == 4):?>
                <?=__('Featured Ads')?>
            <?elseif(core::config('advertisement.ads_in_home') == 2):?>
                <?=__('Popular Ads last month')?>
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
                                <img src="<?=$ad->get_first_image()?>" alt="<?=HTML::chars($ad->title)?>">
                            <?else:?>
                                <?if(( $icon_src = $ad->category->get_icon() )!==FALSE ):?>
                                    <img src="<?=$icon_src?>" alt="<?=HTML::chars($ad->title)?>" >
                                <?elseif(( $icon_src = $ad->location->get_icon() )!==FALSE ):?>
                                    <img src="<?=$icon_src?>" alt="<?=HTML::chars($ad->title)?>" >
                                <?else:?>
                                    <img data-src="holder.js/<?=core::config('image.width_thumb')?>x<?=core::config('image.height_thumb')?>?text=<?=HTML::entities($ad->category->name)?>&amp;size=14&amp;auto=yes" alt="<?=HTML::chars($ad->title)?>"> 
                                <?endif?> 
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
        <?=__("Categories")?>
        <?if ($user_location) :?>
            <small><?=$user_location->name?></small>
        <?endif?>
    </h3>
    <div class="row">
        <?$i=0; foreach($categs as $c):?>
            <?if($c['id_category_parent'] == 1 && $c['id_category'] != 1):?>
                <div class="col-md-4">
                    <div class="panel panel-home-categories">
                        <div class="panel-heading">
                            <a title="<?=HTML::chars($c['name'])?>" href="<?=Route::url('list', array('category'=>$c['seoname'], 'location'=>$user_location ? $user_location->seoname : NULL))?>"><?=mb_strtoupper($c['name']);?></a>
                        </div>
                        <div class="panel-body">
                            <ul class="list-group">
                                <?foreach($categs as $chi):?>
                                    <?if($chi['id_category_parent'] == $c['id_category']):?>
                                        <li class="list-group-item">
                                            <a title="<?=HTML::chars($chi['name'])?>" href="<?=Route::url('list', array('category'=>$chi['seoname'], 'location'=>$user_location ? $user_location->seoname : NULL))?>"><?=$chi['name'];?> 
                                                <?if (Theme::get('category_badge')!=1) : ?>
                                                    <span class="pull-right badge badge-success"><?=$chi['count']?></span>
                                                <?endif?>
                                            </a>
                                        </li>
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
<?if(core::config('general.auto_locate') AND ! Cookie::get('user_location')):?>
    <input type="hidden" name="auto_locate" value="<?=core::config('general.auto_locate')?>">
    <?if(count($auto_locats) > 0):?>
        <div class="modal fade" id="auto-locations" tabindex="-1" role="dialog" aria-labelledby="autoLocations" aria-hidden="true">
            <div class="modal-dialog	modal-sm">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                        <h4 id="autoLocations" class="modal-title text-center"><?=__('Please choose your closest location')?></h4>
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