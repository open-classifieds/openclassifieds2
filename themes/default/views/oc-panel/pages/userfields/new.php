<?php defined('SYSPATH') or die('No direct script access.');?>

<div class="row">
    <div class="col-lg-12 page-title-container">
        <h1 class="page-header page-title"><?=__('New Custom Field')?></h1>
        <span class="page-description"><?=__('This panel shows how many visitors your website had the past month.')?> <a target="_blank" href="https://docs.yclas.com/users-custom-fields/"><?=__('Read more')?></a></span>
    </div>
</div>
        
<form class="well form-horizontal"  method="post" action="<?=Route::url('oc-panel',array('controller'=>'userfields','action'=>'new'))?>">         
      <?=Form::errors()?>  
      
        <div class="form-group">
                <label class="control-label col-xs-1"><?=__('Name')?></label>
                <div class="col-sm-4">
                
                <input class="form-control" type="text" name="name" placeholder="<?=__('Name')?>" required>
            </div>
        </div>

        <div class="form-group">
                <label class="control-label col-xs-1"><?=__('Label')?></label>
                <div class="col-sm-4">
                
                <input class="form-control" type="text" name="label" placeholder="<?=__('Label')?>" required>
            </div>
        </div>

        <div class="form-group">
                <label class="control-label col-xs-1"><?=__('Tooltip')?></label>
                <div class="col-sm-4">
                
                <input class="form-control" type="text" name="tooltip" placeholder="<?=__('Tooltip')?>" >
            </div>
        </div>

        <div class="form-group">
            <label class="control-label col-xs-1" for="date"><?=__('Type')?></label>      
            <div class="col-sm-4">
                       
                <select name="type"  class="form-control" id="cf_type_fileds" required>
                    <option value="string"><?=__('Text 256 Chars')?></option>
                    <option value="textarea"><?=__('Text Long')?></option>
                    <option value="integer"><?=__('Number')?></option>  
                    <option value="decimal"><?=__('Number Decimal')?></option>
                    <option value="date"><?=__('Date')?></option>
                    <option value="select"><?=__('Select')?></option>
                    <option value="radio"><?=__('Radio')?></option>
                    <option value="email"><?=__('Email')?></option>
                    <option value="checkbox"><?=__('Checkbox')?></option>
                </select>
            </div>
        </div>

        <div class="form-group">
            <label class="control-label col-xs-1"><?=__('Values')?></label>
            <div class="col-sm-4">
                
                <input class="form-control" id="cf_values_input" type="text" name="values" placeholder="<?=__('Comma separated for select')?>">
            </div>
        </div>

        <div class="form-group">
            <div class="col-sm-4">
                <label class="checkbox col-xs-offset-4">
                  <input type="checkbox" name="required"> 
                   <?=__('Required')?>
                </label>
            <div class="help-block col-xs-offset-4"><?=__('Required field to register.')?></div></div>
        </div>

        <div class="form-group">
            <div class="col-sm-4">
                <label class="checkbox col-xs-offset-4">
                  <input type="checkbox" name="searchable"> 
                   <?=__('Searchable')?>
                </label>
            <div class="help-block col-xs-offset-4"><?=__('Search in ads will include this field as well.')?></div></div>
        </div>

        <div class="form-group">
            <div class="col-sm-4">
                <label class="checkbox col-xs-offset-4">
                  <input type="checkbox" name="show_profile"> 
                   <?=__('Show Profile')?>
                </label>
            <div class="help-block col-xs-offset-4"><?=__('Can be seen in the user profile.')?></div></div>
        </div>

        <div class="form-group">
            <div class="col-sm-4">
                <label class="checkbox col-xs-offset-4">
                  <input type="checkbox" name="show_register"> 
                   <?=__('Show Register')?>
                </label>
            <div class="help-block col-xs-offset-4"><?=__('Appears when user registers.')?></div></div>
        </div>

        <div class="form-group">
            <div class="col-sm-4">
                <label class="checkbox col-xs-offset-4">
                  <input type="checkbox" name="admin_privilege"> 
                   <?=__('Admin Privileged')?>
                </label>
            <div class="help-block col-xs-offset-4"><?=__('Can be seen and edited only by admin.')?></div></div>
        </div>
      
      <div class="form-actions">
        <a href="<?=Route::url('oc-panel',array('controller'=>'userfields','action'=>'index'))?>" class="btn btn-default ajax-load" title="<?=__('Cancel')?>"><?=__('Cancel')?></a>
        <button type="submit" class="btn btn-primary"><?=__('Create')?></button>
      </div>
</form>
