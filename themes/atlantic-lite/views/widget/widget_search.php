<?php defined('SYSPATH') or die('No direct script access.');?>

<div class="card card-search mb-3">
    <? if($widget->text_title != ''): ?>
        <div class="card-header">
            <span class="h6"><?= $widget->text_title ?></span>
        </div>
    <? endif ?>

    <div class="card-body">
        <?= Form::open(Route::url('search'), ['method' => 'GET']) ?>
            <div class="form-group">
                <?= Form::label('advertisement', _e('Advertisement Title'), ['for'=>'title'])?>
                <?= Form::input('title', '', [
                    'placeholder' => __('Search'),
                    'class' => 'form-control',
                    'id' => 'title',
                ]) ?>
            </div>

            <?if($widget->advanced != FALSE):?>
                <?if($widget->cat_items !== NULL):?>
                    <div class="form-group">
                        <?= Form::label('category', _e('Categories'), ['for' => 'category_widget_search']) ?>
                        <select
                            <?= Core::config('general.search_multi_catloc') ? 'multiple' : NULL ?>
                            name="category<?= Core::config('general.search_multi_catloc') ? '[]' : NULL ?>"
                            id="category_widget_search"
                            class="form-control"
                            data-placeholder="<?=__('Categories')?>"
                        >
                            <option></option>
                            <?function lili_search($item, $key, $params){?>
                                <?if (Core::config('general.search_multi_catloc')):?>
                                    <option value="<?=$params['cats'][$key]['seoname']?>" data-id="<?=$params['cats'][$key]['id']?>" <?=(is_array($params['selected_category']) AND in_array($params['cats'][$key]['seoname'], $params['selected_category']))?"selected":''?> ><?=$params['cats'][$key]['translate_name']?></option>
                                <?else:?>
                                    <option value="<?=$params['cats'][$key]['seoname']?>" data-id="<?=$params['cats'][$key]['id']?>" <?=($params['selected_category'] == $params['cats'][$key]['seoname'])?"selected":''?> ><?=$params['cats'][$key]['translate_name']?></option>
                                <?endif?>
                                <?if (Core::count($item)>0):?>
                                    <optgroup label="<?=$params['cats'][$key]['translate_name']?>">
                                        <? if (is_array($item)) array_walk($item, 'lili_search', ['cats' => $params['cats'], 'selected_category' => $params['selected_category']]);?>
                                    </optgroup>
                                <?endif?>
                            <?}
                            $cat_order = $widget->cat_order_items;
                            if (is_array($cat_order))
                                array_walk($cat_order , 'lili_search', ['cats' => $widget->cat_items, 'selected_category' => $widget->selected_category]);?>
                        </select>
                    </div>
                <?endif?>

                <?if($widget->loc_items !== NULL):?>
                    <?if(Core::count($widget->loc_items) > 1 AND Core::config('advertisement.location') != FALSE):?>
                        <div class="form-group">
                            <?= Form::label('location_widget_search', _e('Locations'), ['for' => 'location_widget_search'])?>
                            <select <?=Core::config('general.search_multi_catloc')? 'multiple':NULL?> name="location<?=Core::config('general.search_multi_catloc')? '[]':NULL?>" id="location_widget_search" class="form-control" data-placeholder="<?=__('Locations')?>">
                                <option></option>
                                <?function lolo_search($item, $key, $params){?>
                                    <?if (Core::config('general.search_multi_catloc')):?>
                                        <option value="<?=$params['locs'][$key]['seoname']?>" data-id="<?=$params['locs'][$key]['id']?>" <?=(is_array($params['selected_location']) AND in_array($params['locs'][$key]['seoname'], $params['selected_location']))?"selected":''?> ><?=$params['locs'][$key]['translate_name']?></option>
                                    <?else:?>
                                        <option value="<?=$params['locs'][$key]['seoname']?>" data-id="<?=$params['locs'][$key]['id']?>" <?=($params['selected_location'] == $params['locs'][$key]['seoname'])?"selected":''?> ><?=$params['locs'][$key]['translate_name']?></option>
                                    <?endif?>
                                    <?if (Core::count($item)>0):?>
                                        <optgroup label="<?=$params['locs'][$key]['translate_name']?>">
                                            <? if (is_array($item)) array_walk($item, 'lolo_search', ['locs' => $params['locs'], 'selected_location' => $params['selected_location']]);?>
                                        </optgroup>
                                    <?endif?>
                                <?}
                                $loc_order_search = $widget->loc_order_items;
                                if (is_array($loc_order_search))
                                    array_walk($loc_order_search , 'lolo_search', ['locs' => $widget->loc_items, 'selected_location' => $widget->selected_location]);?>
                            </select>
                        </div>
                    <?endif?>
                <?endif?>

                <?if(Core::config('advertisement.price')):?>
                    <div class="form-group">
                        <?= Form::label('price-min', _e('Price from'), ['for' => 'price-min']) ?>
                        <?= Form::input('price-min', HTML::chars(Core::get('price-min')), [
                            'placeholder' => __('Price from'),
                            'class' => 'form-control',
                            'id' => 'price-min',
                        ]) ?>
                    </div>

                    <div class="form-group">
                        <?= Form::label('price-max', _e('Price to'), ['for' => 'price-max']) ?>
                        <?= Form::input('price-max', HTML::chars(Core::get('price-max')), [
                            'placeholder' => __('to'),
                            'class' => 'form-control',
                            'id' => 'price-max',
                        ]) ?>
                    </div>
                <?endif?>
            <?endif?>

            <?if (Core::extra_features() == TRUE AND $widget->custom == TRUE) :?>
                <div
                    id="widget-custom-fields"
                    data-apiurl="<?=Route::url('api', ['version'=>'v1', 'format'=>'json', 'controller'=>'categories'])?>"
                    data-customfield-values='<?=json_encode(Request::current()->query())?>'
                >
                    <div id="widget-custom-field-template" class="form-group d-none">
                        <div>
                            <div data-label></div>
                            <div data-input></div>
                        </div>
                    </div>
                </div>

                <?foreach(Model_UserField::get_all() as $name=>$field):?>
                    <?if (isset($field['searchable']) AND $field['searchable']):?>
                        <div class="form-group">
                            <? $cf_name = 'cfuser_'.$name ?>
                            <?
                                if($field['type'] == 'select' OR $field['type'] == 'radio')
                                {
                                    $select = ['' => $field['label']];

                                    foreach ($field['values'] as $select_name)
                                    {
                                        $select[$select_name] = $select_name;
                                    }
                                }
                                else
                                {
                                    $select = $field['values'];
                                }
                            ?>
                            <?= Form::label('cfuser_' . $name, $field['label'], ['for' => 'cfuser_'.$name]) ?>
                            <?= Form::cf_form_field('cfuser_'.$name, [
                                'display' => $field['type'],
                                'label' => $field['label'],
                                'tooltip' => isset($field['tooltip']) ? $field['tooltip'] : '',
                                'default' => $field['values'],
                                'options' => (!is_array($field['values'])) ? $field['values'] : $select,
                            ], Core::get('cfuser_' . $name), FALSE, TRUE) ?>
                        </div>
                    <?endif?>
                <?endforeach?>
            <?endif?>

            <?= Form::button('submit', _e('Search'), [
                'type'=>'submit',
                'class'=>'btn btn-primary',
            ])?>
        <?= Form::close()?>
    </div>
</div>
