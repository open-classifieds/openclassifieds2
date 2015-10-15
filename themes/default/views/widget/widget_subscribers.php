<?php defined('SYSPATH') or die('No direct script access.');?>

<?if ($widget->subscribe_title!=''):?>
    <div class="panel-heading">
        <h3 class="panel-title"><?=$widget->subscribe_title?></h3>
    </div>
<?endif?>

<div class="panel-body">
    <?= FORM::open(Route::url('default', array('controller'=>'subscribe', 'action'=>'index','id'=>$widget->user_id)), array('class'=>'form-horizontal ', 'enctype'=>'multipart/form-data'))?>
        <!-- if categories on show selector of categories -->
        <?if($widget->cat_items !== NULL):?>
            <div class="form-group">
                <div class="col-xs-10">
                    <?= FORM::label('category_subscribe', __('Categories'), array('class'=>'', 'for'=>'category_subscribe'))?>
                    <select data-placeholder="<?=__('Categories')?>" name="category_subscribe[]" id="category_subscribe" class="form-control" multiple required>
                        <option></option>
                        <?function lili_subscribe($item, $key,$cats){?>
                            <?if ( count($item)==0 AND $cats[$key]['id_category_parent'] != 1):?>
                                <option value="<?=$key?>"><?=$cats[$key]['name']?></option>
                            <?endif?>
                            <?if ($cats[$key]['id_category_parent'] == 1 OR count($item)>0):?>
                                <option value="<?=$key?>"> <?=$cats[$key]['name']?> </option>  
                                <? if (is_array($item)) array_walk($item, 'lili_subscribe', $cats);?>
                            <?endif?>
                        <?}
                        $cat_order = $widget->cat_order_items; 
                        if (is_array($cat_order))
                          array_walk($cat_order , 'lili_subscribe', $widget->cat_items);?>
                    </select> 
                </div>
            </div>
        <?endif?>
        <!-- end categories/ -->
        
        <!-- locations -->
        <?if($widget->loc_items !== NULL):?>
            <?if(count($widget->loc_items) > 1 AND core::config('advertisement.location') != FALSE):?>
                <div class="form-group">
                    <div class="col-xs-10">
                        <?= FORM::label('location_subscribe', __('Location'), array('class'=>'', 'for'=>'location_subscribe' ))?>
                        <select data-placeholder="<?=__('Location')?>" name="location_subscribe[]" id="location_subscribe" class="form-control" required>
                        <option></option>
                        <?function lolo_subscribe($item, $key,$locs){?>
                        <option value="<?=$key?>"><?=$locs[$key]['name']?></option>
                            <?if (count($item)>0):?>
                            <optgroup label="<?=$locs[$key]['name']?>">    
                                <? if (is_array($item)) array_walk($item, 'lolo_subscribe', $locs);?>
                                </optgroup>
                            <?endif?>
                        <?}
                        $loc_order_subscribe = $widget->loc_order_items; 
                        if (is_array($loc_order_subscribe))
                            array_walk($loc_order_subscribe , 'lolo_subscribe',$widget->loc_items);?>
                        </select>
                    </div>
                </div>
            <?endif?>
        <?endif?>
        <!-- end locations -->
        
        <?if($widget->user_email == NULL):?>
            <div class="form-group">
                <div class="col-xs-10">
                    <?= FORM::label('email_subscribe', __('Email'), array('class'=>'', 'for'=>'email_subscribe'))?>
                    <?= FORM::input('email_subscribe', Request::current()->post('email_subscribe'), array('class'=>'form-control', 'id'=>'email_subscribe', 'type'=>'email' ,'required','placeholder'=>__('Email')))?>
                </div>
            </div>
        <?else:?>
            <div class="form-group">
                <div class="col-xs-10">
                    <?= FORM::input('email_subscribe', $widget->user_email, array('class'=>'form-control', 'id'=>'email_subscribe', 'type'=>'hidden', 'placeholder'=>__('Email')))?>
                </div>
            </div>
        <?endif?>
        
        <?if($widget->price != FALSE):?>
            <!-- slider -->
            <div class="form-group">
                <div class="col-xs-10">
                    <?= FORM::label('price_subscribe', __('Price Range'), array('class'=>'', 'for'=>'price_subscribe'))?>
                    <input type="text" class="slider_subscribe" value="<?=$widget->min_price?>,<?=$widget->max_price?>" 
                            data-slider-min='<?=$widget->min_price?>' data-slider-max="<?=$widget->max_price?>" 
                            data-slider-step="50" data-slider-value='[<?=$widget->min_price?>,<?=$widget->max_price?>]' 
                            data-slider-orientation="horizontal" data-slider-selection="before" data-slider-tooltip="show" name='price_subscribe' >
                </div>
            </div>
        <?else:?>
            <input type="hidden" value='0,0'>
        <?endif?>
        
        <div class="">
            <?= FORM::button('submit', __('Subscribe'), array('type'=>'submit', 'class'=>'btn btn-success', 'action'=>Route::url('default', array('controller'=>'subscribe', 'action'=>'index','id'=>$widget->user_id))))?>
        </div>
        
        <?if($widget->subscriber):?>
            <a href="<?=Route::url('default', array('controller'=>'subscribe', 'action'=>'unsubscribe', 'id'=>$widget->user_id))?>"><?=__('Unsubscribe')?></a>
        <?endif?>
        
    <?= FORM::close()?>
</div>