<?php defined('SYSPATH') or die('No direct script access.');?>

<div class="row">
    <div class="col-lg-12 page-title-container">
        <h1 class="page-header page-title"><?=__('Edit Custom Field')?></h1>
        <span class="page-description"><?=__('This panel shows how many visitors your website had the past month.')?> <a target="_blank" href=""><?=__('Read more')?></a></span>
    </div>
</div>
        
<form class="well form-horizontal"  method="post" action="<?=Route::url('oc-panel',array('controller'=>'userfields','action'=>'update','id'=>$name))?>">         
      <?=Form::errors()?>  
      
        <div class="form-group">
            <label class="control-label col-xs-1"><?=__('Name')?></label>
                <div class="col-sm-4">
                <input  DISABLED class="form-control" type="text" name="name" value="<?=$name?>" placeholder="<?=__('Name')?>" required>
            </div>
        </div>

        <div class="form-group">
            <label class="control-label col-xs-1"><?=__('Type')?></label>
                <div class="col-sm-4">
                <input  DISABLED class="form-control" type="text" id="cf_type_field_input" name="type" value="<?=$field_data['type']?>" placeholder="<?=__('Type')?>" required>
            </div>
        </div>
        
        <div class="form-group">
            <label class="control-label col-xs-1"><?=__('Label')?></label>
                <div class="col-sm-4">
                <input class="form-control" type="text" name="label" value="<?=$field_data['label']?>" placeholder="<?=__('Label')?>" required>
            </div>
        </div>

        <div class="form-group">
            <label class="control-label col-xs-1"><?=__('Tooltip')?></label>
                <div class="col-sm-4">
                <input class="form-control" type="text" name="tooltip" value="<?=(isset($field_data['tooltip']))?$field_data['tooltip']:""?>" placeholder="<?=__('Tooltip')?>">
            </div>
        </div>

        <div class="form-group">
            <label class="control-label col-xs-1"><?=__('Values')?></label>
                <div class="col-sm-4">
                <input class="form-control" type="text" id="cf_values_input" name="values" value="<?=(is_array($field_data['values']))? implode(",", $field_data['values']): $field_data['values']?>" placeholder="<?=__('Comma separated for select')?>">
            </div>
        </div>

        <div class="form-group">
            <div class="col-sm-4">
                <label class="checkbox col-xs-offset-4">
                  <input type="checkbox" name="required" <?=($field_data['required']==TRUE)?'checked':''?>> 
                   <?=__('Required')?>
                </label>
            <div class="help-block col-xs-offset-4"><?=__('Required field to register.')?></div></div>
        </div>

        <div class="form-group">
            <div class="col-sm-4">
                <label class="checkbox col-xs-offset-4">
                  <input type="checkbox" name="searchable" <?=(isset($field_data['searchable']) AND $field_data['searchable']==TRUE)?'checked':''?>> 
                   <?=__('Searchable')?>
                </label>
            <div class="help-block col-xs-offset-4"><?=__('Search in ads will include this field as well.')?></div></div>
        </div>

        <div class="form-group">
            <div class="col-sm-4">
                <label class="checkbox col-xs-offset-4">
                  <input type="checkbox" name="show_profile" <?=(isset($field_data['show_profile']) AND $field_data['show_profile']==TRUE)?'checked':''?>> 
                   <?=__('Show Profile')?>
                </label>
            <div class="help-block col-xs-offset-4"><?=__('Can be seen in the user profile.')?></div></div>
        </div>

        <div class="form-group">
            <div class="col-sm-4">
                <label class="checkbox col-xs-offset-4">
                  <input type="checkbox" name="show_register" <?=(isset($field_data['show_register']) AND $field_data['show_register']==TRUE)?'checked':''?>> 
                   <?=__('Show Register')?>
                </label>
            <div class="help-block col-xs-offset-4"><?=__('Appears when user registers.')?></div></div>
        </div>

        <div class="form-group">
            <div class="col-sm-4">
                <label class="checkbox col-xs-offset-4">
                  <input type="checkbox" name="admin_privilege" <?=(isset($field_data['admin_privilege']) AND $field_data['admin_privilege']==TRUE)?'checked':''?>> 
                   <?=__('Admin Privileged')?>
                </label>
            <div class="help-block col-xs-offset-4"><?=__('Can be seen and edited only by admin.')?></div></div>
        </div>
      
      <div class="form-actions">
        <a href="<?=Route::url('oc-panel',array('controller'=>'userfields','action'=>'index'))?>" class="btn btn-default ajax-load" title="<?=__('Cancel')?>"><?=__('Cancel')?></a>
        <button type="submit" class="btn btn-primary"><?=__('Save')?></button>
      </div>
</form>
