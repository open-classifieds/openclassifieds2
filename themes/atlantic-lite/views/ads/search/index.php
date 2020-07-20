<?php defined('SYSPATH') or die('No direct script access.');?>

<?=Form::errors()?>

<div class="mb-4">
    <h1 class="h2"><?=_e('Search')?></h1>
</div>

<?= Form::open(Route::url('search'), array('class'=>'form-row mb-4', 'method'=>'GET', 'action'=>''))?>
    <div class="form-group col-md-4">
        <?= Form::label('advertisement', _e('Advertisement Title'), array('for' => 'advertisement'))?>
        <?if(Core::config('general.algolia_search') == 1):?>
            <?=View::factory('pages/algolia/autocomplete_ad')?>
        <?else:?>
            <input type="text" id="title" name="title" class="form-control" value="<?=HTML::chars(core::get('title'))?>" placeholder="<?=__('Title')?>">
        <?endif?>
    </div>

    <?if(core::count($categories) > 1):?>
        <div class="form-group col-md-4">
            <?= Form::label('category', _e('Category'), array('for'=>'category'))?>
            <select <?=core::config('general.search_multi_catloc')? 'multiple':NULL?> name="category<?=core::config('general.search_multi_catloc')? '[]':NULL?>" id="category" class="form-control mr-sm-2 tw-max-w-4" data-placeholder="<?=__('Category')?>" style="width: 4rem;">
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
    <?endif?>

    <?if(core::config('advertisement.location') != FALSE AND core::count($locations) > 1):?>
        <div class="form-group col-md-4">
            <?= Form::label('location', _e('Location'), array('for'=>'location' , 'multiple'))?>
            <select <?=core::config('general.search_multi_catloc')? 'multiple':NULL?> name="location<?=core::config('general.search_multi_catloc')? '[]':NULL?>" id="location" class="form-control mr-sm-2" data-placeholder="<?=__('Location')?>">
                <?if (! core::config('general.search_multi_catloc')) :?>
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
    <?endif?>

    <? if(Core::config('general.multilingual') == 1): ?>
        <div class="form-group col-md-4">
            <?= Form::label('locale', _e('Language'), array('for'=>'locale'))?>
            <?= Form::select('locale', i18n::get_selectable_languages(), Core::request('locale', i18n::$locale), array('class' => 'form-control', 'id' => 'locale'))?>
        </div>
    <? endif ?>

    <?if(core::config('advertisement.price')):?>
        <div class="form-group col-md-2">
            <label for="price-min"><?=_e('Price from')?> </label>
            <input type="text" id="price-min" name="price-min" class="form-control" value="<?=HTML::chars(core::get('price-min'))?>" placeholder="<?=__('Price from')?>">
        </div>
        <div class="form-group col-md-2">
            <label for="price-max"><?=_e('Price to')?></label>
            <input type="text" id="price-max" name="price-max" class="form-control" value="<?=HTML::chars(core::get('price-max'))?>" placeholder="<?=__('to')?>">
        </div>
    <?endif?>

    <div class="form-group col-md-2 tw-flex tw-items-end">
        <?= Form::button('submit', __('Search'), array('type'=>'submit', 'class'=>'btn btn-primary'))?>
    </div>
<?= Form::close()?>

<? if(Request::current()->query()) : ?>
    <? if (core::count($ads)>0): ?>
        <div class="mb-4">
            <h3 class="h3">
                <?if (core::get('title')) :?>
                    <?=($total_ads == 1) ? sprintf(__('%d advertisement for %s'), $total_ads, HTML::chars(core::get('title'))) : sprintf(__('%d advertisements for %s'), $total_ads, core::get('title'))?>
                <?else:?>
                    <?=_e('Search results')?>
                <?endif?>
            </h3>
        </div>

        <?=View::factory('pages/ad/listing',array('pagination'=>$pagination,'ads'=>$ads,'category'=>NULL, 'location'=>NULL, 'user'=>$user, 'featured'=>NULL))?>
    <? else: ?>
        <div class="jumbotron text-center">
            <p class="lead"><?=_e('Your search did not match any advertisement.')?></p>
        </div>
    <? endif ?>
<?endif?>
