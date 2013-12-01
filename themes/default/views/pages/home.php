<?php defined('SYSPATH') or die('No direct script access.');?>
<div class="well row">
    <?if(core::config('advertisement.ads_in_home') == 0):?>
    <h3><?=__('Latest Ads')?></h3>
    <?elseif(core::config('advertisement.ads_in_home') == 1):?>
    <h3><?=__('Featured Ads')?></h3>
    <?elseif(core::config('advertisement.ads_in_home') == 2):?>
    <h3><?=__('Popular Ads last month')?></h3>
    <?endif?>
    <div class="col-md-12">
    <?$i=0; foreach($ads as $ad):
        $esc_html_adTitle = HTML::chars($ad->title);?>
        <div class="col-md-3">
            <div class="thumbnail latest_ads">
                <a href="<?=Route::url('ad', array('category'=>$ad->category->seoname,'seotitle'=>$ad->seotitle))?>" title="<?=HTML::chars($ad->category->name)?>">
                <?if($ad->get_first_image()!== NULL):?>
                    <img src="<?=URL::base('http')?><?=$ad->get_first_image()?>" alt="<?=$esc_html_adTitle?>" title="<?=$esc_html_adTitle?>" />
                <?else:?>
                    <img src="http://www.placehold.it/200x200&text=<?=URL::title($ad->category->name)?>" alt="<?=$esc_html_adTitle?>" title="<?=$esc_html_adTitle?>" /> 
                <?endif?>
                </a>
                <div class="caption">
                    <h5><a href="<?=Route::url('ad', array('controller'=>'ad','category'=>$ad->category->seoname,'seotitle'=>$ad->seotitle))?>" title="<?=$esc_html_adTitle?>"><?=$ad->title?></a></h5>

                    <p><?=substr(Text::removebbcode($ad->description), 0, 30)/** @todo truncate between words **/?></p>

                </div>
            </div>
        </div>     
        <?endforeach?>
    </div>
</div>

<div class='well row'>
    <h3><?=__("Categories")?></h3>
    <div class="col-md-12">
        <?foreach($categs as $c):?>
        <?if($c['id_category_parent'] == 1 && $c['id_category'] != 1):?>
        <div class="col-md-4">
            <div class="category_box_title">
                <p><a title="<?=$c['name']?>" <?=(core::config('advertisement.parent_category'))?'href="'.Route::url('list', array('category'=>$c['seoname'])).'"':""?>><?=strtoupper($c['name']);?></a></p>
            </div>  
            <div class="well custom_box_content" style="padding: 8px 0;">
                <ul class="nav nav-list">
                    <?foreach($categs as $chi):?>
                        <?if($chi['id_category_parent'] == $c['id_category']):?>
                        <li><a title="<?=$chi['name']?>" href="<?=Route::url('list', array('category'=>$chi['seoname']))?>"><?=$chi['name'];?> <span class="count_ads"><span class="badge badge-success"><?=$chi['count']?></span></span></a></li>
                        <?endif?>
                     <?endforeach?>
                </ul>
            </div>
        </div>
        <?
        $i++;
            if ($i%3 == 0) echo '<div class="clear"></div>';
            ?>
        <?endif?>
        <?endforeach?>
    </div>
</div>