<?php defined('SYSPATH') or die('No direct script access.');?>

<div class="card mb-3">
    <? if ($widget->subscribe_title != '') : ?>
        <div class="card-header">
            <span class="h6"><?= $widget->subscribe_title ?></span>
        </div>
    <? endif ?>

    <div class="card-body py-3">
        <?= FORM::open(Route::url('default', array('controller'=>'subscribe', 'action'=>'index','id'=>$widget->user_id)), array('enctype'=>'multipart/form-data'))?>
            <?if($widget->cat_items !== NULL):?>
                <div class="form-group">
                    <?= FORM::label('category_subscribe', _e('Categories'), array('class'=>'', 'for'=>'category_subscribe'))?>
                    <select data-placeholder="<?=__('Categories')?>" name="category_subscribe[]" id="category_subscribe" class="form-control" multiple required>
                        <option></option>
                        <?if (! function_exists('lili_subscribe')):?>
                            <?function lili_subscribe($item, $key,$cats){?>
                                <?if ( core::count($item)==0 AND $cats[$key]['id_category_parent'] != 1):?>
                                    <option value="<?=$key?>"><?=$cats[$key]['translate_name']?></option>
                                <?endif?>
                                <?if ($cats[$key]['id_category_parent'] == 1 OR core::count($item)>0):?>
                                    <option value="<?=$key?>"> <?=$cats[$key]['translate_name']?> </option>
                                    <? if (is_array($item)) array_walk($item, 'lili_subscribe', $cats)?>
                                <?endif?>
                            <?}?>
                        <?endif?>
                        <?
                            $cat_order = $widget->cat_order_items;
                            if (is_array($cat_order))
                                array_walk($cat_order , 'lili_subscribe', $widget->cat_items)
                        ?>
                    </select>
                </div>
            <?endif?>

            <?if($widget->loc_items !== NULL):?>
                <?if(core::count($widget->loc_items) > 1 AND core::config('advertisement.location') != FALSE):?>
                    <div class="form-group">
                        <?= FORM::label('location_subscribe', _e('Location'), array('class'=>'', 'for'=>'location_subscribe' ))?>
                        <select data-placeholder="<?=__('Location')?>" name="location_subscribe[]" id="location_subscribe" class="form-control" required>
                        <option></option>
                        <?if (! function_exists('lolo_subscribe')):?>
                            <?function lolo_subscribe($item, $key,$locs){?>
                                <option value="<?=$key?>"><?=$locs[$key]['translate_name']?></option>
                                <?if (core::count($item)>0):?>
                                    <optgroup label="<?=$locs[$key]['translate_name']?>">
                                        <?if (is_array($item)) array_walk($item, 'lolo_subscribe', $locs)?>
                                    </optgroup>
                                <?endif?>
                            <?}?>
                        <?endif?>
                        <?
                            $loc_order_subscribe = $widget->loc_order_items;
                            if (is_array($loc_order_subscribe))
                                array_walk($loc_order_subscribe , 'lolo_subscribe',$widget->loc_items)
                        ?>
                        </select>
                    </div>
                <?endif?>
            <?endif?>

            <?if($widget->user_email == NULL):?>
                <div class="form-group">
                    <?= FORM::label('email_subscribe', _e('Email'), array('class'=>'', 'for'=>'email_subscribe'))?>
                    <?= FORM::input('email_subscribe', Request::current()->post('email_subscribe'), array('class'=>'form-control', 'id'=>'email_subscribe', 'type'=>'email' ,'required','placeholder'=>__('Email')))?>
                </div>
            <?else:?>
                <div class="form-group">
                    <?= FORM::input('email_subscribe', $widget->user_email, array('class'=>'form-control', 'id'=>'email_subscribe', 'type'=>'hidden', 'placeholder'=>__('Email')))?>
                </div>
            <?endif?>

            <?if($widget->price != FALSE):?>
                <div class="form-group">
                    <?= FORM::label('price_subscribe', _e('Price'), array('class'=>'', 'for'=>'price_subscribe'))?>
                    <input type="text" class="slider_subscribe" value="<?=$widget->min_price?>,<?=$widget->max_price?>"
                            data-slider-min='<?=$widget->min_price?>' data-slider-max="<?=$widget->max_price?>"
                            data-slider-step="50" data-slider-value='[<?=$widget->min_price?>,<?=$widget->max_price?>]'
                            data-slider-orientation="horizontal" data-slider-selection="before" data-slider-tooltip="show" name='price_subscribe' >
                </div>
            <?else:?>
                <input type="hidden" value='0,0'>
            <?endif?>

            <?if (core::config('advertisement.captcha') != FALSE):?>
                <div class="form-group">
                    <?if (Core::config('general.recaptcha_active')):?>
                        <?=View::factory('recaptcha', ['id' => 'recaptcha2'])?>
                    <?else:?>
                        <?=_e('Captcha')?>*:<br />
                        <?=captcha::image_tag('subscribe')?><br />
                        <?=FORM::input('captcha', "", array('class' => 'form-control', 'id' => 'captcha', 'required'))?>
                    <?endif?>
                </div>
            <?endif?>

            <?= FORM::button(NULL, __('Subscribe'), array('type'=>'submit', 'class'=>'btn btn-success', 'action'=>Route::url('default', array('controller'=>'subscribe', 'action'=>'index','id'=>$widget->user_id))))?>

            <?if($widget->subscriber):?>
                <a class="btn btn-light" href="<?=Route::url('default', array('controller'=>'subscribe', 'action'=>'unsubscribe', 'id'=>$widget->user_id))?>"><?=_e('Unsubscribe')?></a>
            <?endif?>

        <?= FORM::close()?>
    </div>
</div>
