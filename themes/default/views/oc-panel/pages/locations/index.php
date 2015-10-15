<?php defined('SYSPATH') or die('No direct script access.');?>

<div class="page-header">
    <a class="btn btn-primary pull-right ajax-load" href="<?=Route::url('oc-panel',array('controller'=>'location','action'=>'create'))?>" title="<?=__('New Location')?>">
        <?=__('New Location')?>
    </a>
    <h1><?=($location->id_location > 1) ? $location->name.' – ':NULL?> <?=__('Locations')?></h1>
    <p><?=__("Change the order of your locations. Keep in mind that more than 2 levels nested probably won´t be displayed in the theme (it is not recommended).")?> <a href="http://docs.yclas.com/how-to-add-locations/" target="_blank"><?=__('Read more')?></a></p>
</div>

<div class="row">
    <div class="col-md-7">
        <div class="panel panel-default">
            <div class="panel-heading"><?=$location->name?></div>
            <div class="panel-body">
                <?=FORM::open(Route::url('oc-panel',array('controller'=>'location','action'=>'delete')), array('class'=>'form-inline', 'enctype'=>'multipart/form-data'))?>
                    <ol class='plholder' id="ol_<?=$location->id_location?>" data-id="<?=$location->id_location?>">
                        <?foreach ($locs as $loc) :?>
                            <li data-id="<?=$loc->id_location?>" id="li_<?=$loc->id_location?>">
                        
                                <div class="drag-item">
                                    <span class="drag-icon"><i class="fa fa-ellipsis-v"></i><i class="fa fa-ellipsis-v"></i></span>
                                    <div class="drag-name">
                                        <?=$loc->name?>
                                    </div>
                                    <a class="drag-action ajax-load" title="<?=__('Browse childs')?>"
                                        href="<?=Route::url('oc-panel',array('controller'=>'location','action'=>'index'))?>?id_location=<?=$loc->id_location?>">
                                        <?=__('Browse')?>
                                    </a>
                                    <a class="drag-action ajax-load" title="<?=__('Edit')?>"
                                        href="<?=Route::url('oc-panel',array('controller'=>'location','action'=>'update','id'=>$loc->id_location))?>">
                                        <i class="fa fa-pencil-square-o"></i>
                                    </a>
                                    <a 
                                        href="<?=Route::url('oc-panel', array('controller'=> 'location', 'action'=>'delete','id'=>$loc->id_location))?>" 
                                        class="drag-action index-delete" 
                                        title="<?=__('Are you sure you want to delete? We will move the siblings locations and ads to the parent of this location.')?>" 
                                        data-id="li_<?=$loc->id_location?>" 
                                        data-placement="left" 
                                        data-href="<?=Route::url('oc-panel', array('controller'=> 'location', 'action'=>'delete','id'=>$loc->id_location))?>" 
                                        data-btnOkLabel="<?=__('Yes, definitely!')?>" 
                                        data-btnCancelLabel="<?=__('No way!')?>">
                                        <i class="glyphicon glyphicon-trash"></i>
                                    </a>
                                    <span class="drag-action">
                                        <div class="checkbox">
                                            <label>
                                                <input name="locations[]" value="<?=$loc->id_location?>" type="checkbox">
                                            </label>
                                        </div>
                                    </span>
                                </div>
                        
                            </li><!--li_<?=$loc->id_location?>-->
                        <?endforeach?>
                    </ol><!--ol_1-->
                    
                    <span id='ajax_result' data-url='<?=Route::url('oc-panel',array('controller'=>'location','action'=>'saveorder'))?>'></span>
                    
                    <?if(count($locs) > 0) :?>
                        <p class="text-right">
                            <button data-toggle="modal" data-target="#delete-all" class="btn btn-danger">
                                <?=__('Delete all locations')?>
                            </button>

                            <button name="delete" type="submit" class="btn btn-danger">
                                <?=__('Delete selected locations')?>
                            </button>
                        </p>
                    <?endif?>
                <?=FORM::close()?>
            </div>
        </div>
    </div>
    <div class="col-md-5">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-body">
                        <span class="label label-info"><?=__('Heads Up!')?> <?=__('Quick location creator.')?></span>
                        <p><?=__('Add names for multiple locations, for each one push enter.')?></p>
                      
                        <?= FORM::open(Route::url('oc-panel',array('controller'=>'location','action'=>'multy_locations'.'?id_location='.Core::get('id_location', 1))), array('class'=>'form-horizontal', 'role'=>'form','enctype'=>'multipart/form-data'))?>
                            <div class="form-group">
                                <?= FORM::label('multy_locations', __('Name').':', array('class'=>'col-sm-3 control-label', 'for'=>'multy_locations'))?>
                                <div class="col-sm-8">
                                    <?= FORM::input('multy_locations', '', array('placeholder' => __('Hit enter to confirm'), 'class' => 'form-control', 'id' => 'multy_locations', 'type' => 'text','data-role'=>'tagsinput'))?>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-sm-offset-3 col-sm-8">
                                    <?= FORM::button('submit', __('Send'), array('type'=>'submit', 'class'=>'btn btn-primary', 'action'=>Route::url('oc-panel',array('controller'=>'location','action'=>'multy_locations'.'?id_location='.Core::get('id_location', 1)))))?>
                                </div>
                            </div>
                        <?= FORM::close()?>
                    </div>
                </div>
            </div>
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-body">
                        <?= FORM::open(Route::url('oc-panel',array('controller'=>'location','action'=>'geonames_locations'.'?id_location='.Core::get('id_location', 1))), array('id'=>'auto_locations_form', 'class'=>'form-horizontal', 'role'=>'form','enctype'=>'multipart/form-data'))?>
                            <div class="form-group" id="group-continent">
                                <label for="continent" class="col-sm-3 control-label" data-action="<?=__('Import continents')?>"><?=__('Continent')?></label>
                                <div class="col-sm-8">
                                    <select name="continent" id="continent" onchange="getPlaces(this.value,'country');" class="disable-chosen form-control">
                                        <option value=""></option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group" id="group-country">
                                <label for="country" class="col-sm-3 control-label" data-action="<?=__('Import countries')?>"><?=__('Country')?></label>
                                <div class="col-sm-8">
                                    <select name="country" id="country" onchange="getPlaces(this.value,'province');" class="disable-chosen form-control">
                                        <option value=""></option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group" id="group-province">
                                <label for="province" class="col-sm-3 control-label" data-action="<?=__('Import states/provinces')?>"><?=__('State')?> / <?=__('Province')?></label>
                                <div class="col-sm-8">
                                    <select name="province" id="province" onchange="getPlaces(this.value,'region');" class="disable-chosen form-control">
                                        <option value=""></option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group" id="group-region">
                                <label for="region" class="col-sm-3 control-label" data-action="<?=__('Import counties/regions')?>"><?=__('County')?> / <?=__('Region')?></label>
                                <div class="col-sm-8">
                                    <select name="region" id="region" onchange="getPlaces(this.value,'city');" class="disable-chosen form-control">
                                        <option value=""></option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group" id="group-city">
                                <label for="city" class="col-sm-3 control-label" data-action="<?=__('Import cities')?>"><?=__('City')?></label>
                                <div class="col-sm-8">
                                    <select name="city" id="city" class="disable-chosen form-control">
                                        <option value=""></option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-sm-offset-3 col-sm-8">
                                    <input type="hidden" id="auto_locations" value="" name="geonames_locations">
                                    <input type="hidden" id="auto_locations_lang" value="<?=substr(Core::config('i18n.locale'), 0, -3)?>" name="auto_locations_lang">
                                    <?= FORM::button('submit', __('Import'), array('type'=>'submit', 'class'=>'btn btn-primary', 'id'=>'auto_locations_import', 'action'=>Route::url('oc-panel',array('controller'=>'location','action'=>'geonames_locations')).'?id_location='.Core::get('id_location', 1)))?>
                                    <?= FORM::button('reset', __('Reset'), array('type'=>'button', 'class'=>'btn btn-default', 'id'=>'auto_locations_import_reset'))?>
                                </div>
                            </div>
                        <?= FORM::close()?>
                    </div>
                </div>
            </div>
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading"><?="<a target='_blank' href='http://docs.yclas.com/use-import-tool-categories-locations/'>"._('Upload CSV file')."</a>"?></div>
                    <div class="panel-body">
                        <p>
                            <?=__('Please use the correct CSV format')?> <a href="https://docs.google.com/uc?id=0B60e9iwQucDwa2VjRXAtV0FXVlk&export=download"><?=__('download example')?>.</a>
                        </p>
                        <hr>
                        <?= FORM::open(Route::url('oc-panel',array('controller'=>'tools','action'=>'import_tool'.'?id_parent='.Core::get('id_location', 1))), array('class'=>'form-horizontal', 'enctype'=>'multipart/form-data'))?>
                            <div class="form-group">
                                <label class="col-sm-3 control-label" for="csv_file_locations"><?=__('Import Locations')?></label>
                                <div class="col-sm-8">
                                    <input type="file" name="csv_file_locations" id="csv_file_locations" class="form-control">
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-sm-offset-3 col-sm-8">
                                    <?= FORM::button('submit', __('Upload'), array('type'=>'submit', 'class'=>'btn btn-primary', 'action'=>Route::url('oc-panel',array('controller'=>'tools','action'=>'import_tool'.'?id_parent='.Core::get('id_location', 1)))))?>
                                </div>
                            </div>
                        <?= FORM::close()?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="delete-all" tabindex="-1" role="dialog" aria-labelledby="deleteLocations" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <?= FORM::open(Route::url('oc-panel',array('controller'=>'location','action'=>'delete_all'), array('class'=>'form-horizontal', 'role'=>'form')))?>
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times-circle"></i></button>
                    <h4 id="deleteLocations" class="modal-title"><?=__('Are you sure you want to delete all the locations?')?></h4>
                </div>
                <div class="modal-body">
                    <div class="alert alert-danger">
                        <p><?=__('We will move all the ads to home location.')?> <?=__('This is permanent! No backups, no restores, no magic undo button. We warned you, ok?')?></p>
                    </div>
                </div>
                <div class="modal-body text-right">
                    <button type="button" class="btn btn-default" data-dismiss="modal"><?=__('Cancel')?></button>
                    <button type="submit" class="btn btn-danger" name="confirmation" value="1"><?=__('Delete')?></button>
                </div>
            <input type="hidden" name="id_location" value="<?=Core::get('id_location')?>"></div>
            <?= FORM::close()?>
        </div>
    </div>
</div>