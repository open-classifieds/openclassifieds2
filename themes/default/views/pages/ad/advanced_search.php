<?php defined('SYSPATH') or die('No direct script access.');?>
<?=Form::errors()?>
<div class="well recomentadion clearfix">
    <h1><?=_e('Search')?></h1>
    <?= FORM::open(Route::url('search'), array('class'=>'form-inline', 'method'=>'GET', 'action'=>''))?>
        <fieldset>

                <div class="form-group">
                <?= FORM::label('advertisement', _e('Advertisement Title'), array('class'=>'', 'for'=>'advertisement'))?>
                <div class="control mr-30">
                    <div class="control mr-30">
                        <?if(Core::config('general.algolia_search') == 1):?>
                            <?=View::factory('pages/algolia/autocomplete_ad')?>
                        <?else:?>
                            <input type="text" id="title" name="title" class="form-control" value="<?=HTML::chars(core::get('title'))?>" placeholder="<?=__('Title')?>"><?=core::get('title')?>
                        <?endif?>
                    </div>
                </div>
                </div>

                <?if(core::count($categories) > 1):?>
                    <div class="form-group">
                        <?= FORM::label('category', _e('Category'), array('class'=>'', 'for'=>'category' ))?>
                        <div class="control mr-30">
                            <select <?=core::config('general.search_multi_catloc')? 'multiple':NULL?> name="category<?=core::config('general.search_multi_catloc')? '[]':NULL?>" id="category" class="form-control" data-placeholder="<?=__('Category')?>">
                            <?if ( ! core::config('general.search_multi_catloc')) :?>
                                <option value=""><?=__('Category')?></option>
                            <?endif?>
                            <?function lili($item, $key,$cats){?>
                                <?if (core::config('general.search_multi_catloc')):?>
                                    <option value="<?=$cats[$key]['seoname']?>" data-id="<?=$cats[$key]['id']?>" <?=(is_array(core::request('category')) AND in_array($cats[$key]['seoname'], core::request('category')))?"selected":''?> ><?=$cats[$key]['translate_name']?></option>
                                <?else:?>
                                    <option value="<?=$cats[$key]['seoname']?>" data-id="<?=$cats[$key]['id']?>" <?=(core::request('category') == $cats[$key]['seoname'])?"selected":''?> ><?=$cats[$key]['translate_name']?></option>
                                <?endif?>
                                <?if (core::count($item)>0):?>
                                <optgroup label="<?=$cats[$key]['translate_name']?>">
                                    <? if (is_array($item)) array_walk($item, 'lili', $cats);?>
                                    </optgroup>
                                <?endif?>
                            <?}array_walk($order_categories, 'lili',$categories);?>
                            </select>
                        </div>
                    </div>
                <?endif?>

                <?if(core::config('advertisement.location') != FALSE AND core::count($locations) > 1 AND core::count($locations) < 100 ):?>
                    <div class="form-group">
                        <?= FORM::label('location', _e('Location'), array('class'=>'', 'for'=>'location' , 'multiple'))?>
                        <div class="control mr-30">
                            <select <?=core::config('general.search_multi_catloc')? 'multiple':NULL?> name="location<?=core::config('general.search_multi_catloc')? '[]':NULL?>" id="location" class="form-control" data-placeholder="<?=__('Location')?>">
                            <?if ( ! core::config('general.search_multi_catloc')) :?>
                                <option value=""><?=__('Location')?></option>
                            <?endif?>
                            <?function lolo($item, $key,$locs){?>
                                <?if (core::config('general.search_multi_catloc')):?>
                                    <option value="<?=$locs[$key]['seoname']?>" <?=(is_array(core::request('location')) AND in_array($locs[$key]['seoname'], core::request('location')))?"selected":''?> ><?=$locs[$key]['translate_name']?></option>
                                <?else:?>
                                    <option value="<?=$locs[$key]['seoname']?>" <?=(core::request('location') == $locs[$key]['seoname'])?"selected":''?> ><?=$locs[$key]['translate_name']?></option>
                                <?endif?>
                                <?if (core::count($item)>0):?>
                                <optgroup label="<?=$locs[$key]['translate_name']?>">
                                    <? if (is_array($item)) array_walk($item, 'lolo', $locs);?>
                                    </optgroup>
                                <?endif?>
                            <?}array_walk($order_locations, 'lolo',$locations);?>
                            </select>
                        </div>
                    </div>
                <?elseif(core::config('advertisement.location') != FALSE AND core::count($locations) > 1):?>
                    <div class="form-group">
                        <?= FORM::label('location', _e('Location'), array('class'=>'', 'for'=>'location' , 'multiple'))?>
                        <div class="control mr-30">
                            <select <?=core::config('general.search_multi_catloc')? 'multiple':NULL?> name="location<?=core::config('general.search_multi_catloc')? '[]':NULL?>" id="location-search" class="form-control ajax-location-search" data-placeholder="<?=__('Location')?>" data-apiurl="<?=Route::url('api', array('version'=>'v1', 'format'=>'json', 'controller'=>'locations'))?>">
                                <? if ( ! core::config('general.search_multi_catloc')) : ?>
                                    <option value=""><?=__('Location')?></option>
                                <? endif ?>
                                <? if (is_array($location_filter)) : ?>
                                    <? foreach ($location_filter as $location) : ?>
                                        <option value="<?= $location->seoname ?>" selected><?= $location->name ?></option>
                                    <? endforeach ?>
                                <? elseif ($location_filter) : ?>
                                    <option value="<?= $location_filter->seoname ?>" selected><?= $location_filter->name ?></option>
                                <? endif ?>
                            </select>
                        </div>
                    </div>
                <?endif?>

                <? if(Core::config('general.multilingual') == 1): ?>
                    <div class="form-group">
                        <?= FORM::label('locale', _e('Language'), array('class'=>'', 'for'=>'locale'))?>
                        <div class="control mr-30">
                            <?= Form::select('locale', i18n::get_selectable_languages(), Core::request('locale', i18n::$locale), array('class' => 'form-control', 'id' => 'locale'))?>
                        </div>
                    </div>
                <? endif ?>

            	<?if(core::config('advertisement.price')):?>
                <div class="form-group">
                    <label class="" for="price-min"><?=_e('Price from')?> </label>
                    <div class="control mr-30">
                        <input type="text" id="price-min" name="price-min" class="form-control" value="<?=HTML::chars(core::get('price-min'))?>" placeholder="<?=__('Price from')?>">
                    </div>
                </div>

                <div class="form-group">
                    <label class="" for="price-max"><?=_e('Price to')?></label>
                    <div class="control mr-30">
                        <input type="text" id="price-max" name="price-max" class="form-control" value="<?=HTML::chars(core::get('price-max'))?>" placeholder="<?=__('to')?>">
                    </div>
                </div>
                <?endif?>

                <div class="form-group">
                    <label>&nbsp;</label>
                    <div>
                        <?= FORM::button('submit', __('Search'), array('type'=>'submit', 'class'=>'btn btn-primary pull-right', 'action'=>Route::url('search')))?>
                    </div>
                </div>
    </fieldset>
    <?= FORM::close()?>
</div>

<?if (Request::current()->query()):?>
    <?if (core::count($ads)>0):?>
        <h3>
            <?if (core::get('title')) :?>
                <?=($total_ads == 1) ? sprintf(__('%d advertisement for %s'), $total_ads, HTML::chars(core::get('title'))) : sprintf(__('%d advertisements for %s'), $total_ads, core::get('title'))?>
            <?else:?>
                <?=_e('Search results')?>
            <?endif?>
        </h3>
        <?=View::factory('pages/ad/listing',array('pagination'=>$pagination,'ads'=>$ads,'category'=>NULL, 'location'=>NULL, 'user'=>$user, 'featured'=>NULL))?>
    <?else:?>
        <h3><?=_e('Your search did not match any advertisement.')?></h3>
    <?endif?>
<?endif?>
