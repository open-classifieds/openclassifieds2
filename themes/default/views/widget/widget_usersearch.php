<?php defined('SYSPATH') or die('No direct script access.');?>

<?if ($widget->text_title!=''):?>
    <div class="panel-heading">
        <h3 class="panel-title"><?=$widget->text_title?></h3>
    </div>
<?endif?>

<div class="panel-body">
    <?= FORM::open(Route::url('profiles'), array('class'=>'form-horizontal', 'method'=>'GET', 'action'=>''))?>
        <!-- if categories on show selector of categories -->
        <div class="form-group">
            <div class="col-xs-12">  
                <?= FORM::label('search', __('Search'), array('class'=>'', 'for'=>'search'))?>
                <input type="text" id="search" name="search" class="form-control" value="" placeholder="<?=__('Search')?>">
            </div>
        </div>
        
        <?if (Theme::get('premium')==1) :?>
            <!-- Fields coming from custom fields feature -->
            <div id="widget-custom-fields" data-apiurl="<?=Route::url('api', array('version'=>'v1', 'format'=>'json', 'controller'=>'categories'))?>" data-customfield-values='<?=json_encode(Request::current()->query())?>'>
                <div id="widget-custom-field-template" class="form-group hidden">
                    <div class="col-xs-12">
                        <div data-label></div>
                        <div data-input></div>
                    </div>
                </div>
            </div>
            <?if ($widget->custom != FALSE) :?>
                <!-- Fields coming from user custom fields feature -->
                <?foreach($widget->custom_fields as $name=>$field):?>
                    <?if (isset($field['searchable']) AND $field['searchable']):?>
                        <div class="form-group">
                            <?$cf_name = 'cf_'.$name?>
                            <?if($field['type'] == 'select' OR $field['type'] == 'radio') {
                                $select = array('' => $field['label']);
                                foreach ($field['values'] as $select_name) {
                                    $select[$select_name] = $select_name;
                                }
                            } else $select = $field['values']?>
                            <div class="col-xs-12">
                                <?= FORM::label('cf_'.$name, $field['label'], array('for'=>'cf_'.$name))?>
                                <?=Form::cf_form_field('cf_'.$name, array(
                                'display'   => $field['type'],
                                'label'     => $field['label'],
                                'tooltip'   => (isset($field['tooltip']))? $field['tooltip'] : "",
                                'default'   => $field['values'],
                                'options'   => (!is_array($field['values']))? $field['values'] : $select,
                                ),core::get('cf_'.$name), FALSE, TRUE)?> 
                            </div>
                        </div>
                    <?endif?>
                <?endforeach?>
            <?endif?>
            <!-- /endcustom fields -->
        <?endif?>
        <div class="clearfix"></div>
    
        <?= FORM::button('submit', __('Search'), array('type'=>'submit', 'class'=>'btn btn-primary'))?> 
    <?= FORM::close()?>
</div>
