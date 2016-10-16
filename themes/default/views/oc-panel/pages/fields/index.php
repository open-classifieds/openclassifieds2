<?php defined('SYSPATH') or die('No direct script access.');?>

<ul class="list-inline pull-right">
    <li>
        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#cf-template">
            <?=__('Templates')?>
        </button>
    </li>
    <li>
        <a class="btn btn-primary ajax-load" href="<?=Route::url('oc-panel',array('controller'=>'fields','action'=>'new'))?>" title="<?=__('New field')?>">
            <i class="fa fa-plus-circle"></i>&nbsp; <?=__('New field')?>
        </a>
    </li>
</ul>

<h1 class="page-header page-title">
    <?=__('Custom Fields')?>
    <a target="_blank" href="https://docs.yclas.com/how-to-create-custom-fields/">
        <i class="fa fa-question-circle"></i>
    </a>
</h1>
<hr>

<?if (Theme::get('premium')!=1):?>
    <div class="alert alert-info fade in">
        <p>
            <strong><?=__('Heads Up!')?></strong> 
            <?=__('Custom fields are only available with premium themes!').'<br>'.__('Upgrade your Open Classifieds site to activate this feature.')?>
        </p>
        <p>
            <a class="btn btn-info" href="<?=Route::url('oc-panel',array('controller'=>'theme'))?>">
                <?=__('Browse Themes')?>
            </a>
        </p>
    </div>
<?endif?>

<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-body">
                <ol class='plholder' id="ol_1" data-id="1">
                <?if (is_array($fields)):?>
                    <?foreach($fields as $name=>$field):?>
                        <li data-id="<?=$name?>" id="li_<?=$name?>">
                            <div class="drag-item">
                                <span class="drag-icon"><i class="fa fa-ellipsis-v"></i><i class="fa fa-ellipsis-v"></i></span>
                                <div class="drag-name">
                                    <?=$name?>
                                    <span class="label label-info "><?=$field['type']?></span>
                                    <span class="label label-info "><?=($field['searchable'])?__('searchable'):NULL?></span>
                                    <span class="label label-info "><?=($field['required'])?__('required'):NULL?></span>
                                    <span class="label label-info "><?=(isset($field['admin_privilege']) AND $field['admin_privilege'])?__('Only Admin'):NULL?></span>
                                    <span class="label label-info "><?=(isset($field['show_listing']) AND $field['show_listing'])?__('Show listing'):NULL?></span>
                                </div>
                                <a class="drag-action ajax-load" title="<?=__('Edit')?>"
                                    href="<?=Route::url('oc-panel',array('controller'=>'fields','action'=>'update','id'=>$name))?>">
                                    <i class="fa fa-pencil-square-o"></i>
                                </a>
                                <a 
                                    href="<?=Route::url('oc-panel', array('controller'=> 'fields', 'action'=>'delete','id'=>$name))?>" 
                                    class="drag-action index-delete" 
                                    title="<?=__('Are you sure you want to delete? All data contained in this field will be deleted.')?>" 
                                    data-id="li_<?=$name?>" 
                                    data-placement="left" 
                                    data-href="<?=Route::url('oc-panel', array('controller'=> 'fields', 'action'=>'delete','id'=>$name))?>" 
                                    data-btnOkLabel="<?=__('Yes, definitely!')?>" 
                                    data-btnCancelLabel="<?=__('No way!')?>">
                                    <i class="glyphicon glyphicon-trash"></i>
                                </a>
                            </div>
                        </li>
                    <?endforeach?>
                <?endif?>
                </ol><!--ol_1-->
                <span id='ajax_result' data-url='<?=Route::url('oc-panel',array('controller'=>'fields','action'=>'saveorder'))?>'></span>
                <div class="clearfix"></div>
            </div>
        </div>
    </div>
</div>

<div class="modal" id="cf-template" tabindex="-1" role="dialog" aria-labelledby="cfTemplate" aria-hidden="true">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <form method="post" action="<?=Route::url('oc-panel',array('controller'=>'fields','action'=>'template'))?>">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times-circle"></i></button>
                    <h4 id="cfTemplate" class="modal-title"><?=__('Custom Fields Templates')?></h4>
                </div>
                <div class="modal-body">
                    <p><?=__('Create custom fields among predefined templates.')?></p>
                    <div class="form-group">
                        <label class="control-label" for="date"><?=__('Type')?></label>      
                        <select name="type" class="form-control" id="cf_type_fileds" required>
                            <option value="cars"><?=__('Cars')?></option>
                            <option value="houses"><?=__('Real State')?></option>
                            <option value="jobs"><?=__('Jobs')?></option>
                            <option value="dating"><?=__('Friendship and Dating')?></option>  
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="control-label"><?=__('Categories')?></label>
                        <select id="categories" name="categories[]" multiple data-placeholder="<?=__('Choose 1 or several categories')?>">
                            <option></option>
                            <?function lili12($item, $key,$cats){?>
                                <?if($cats[$key]['id'] != 1):?>
                                    <option value="<?=$cats[$key]['id']?>"><?=$cats[$key]['name']?></option>
                                <?endif?>
                                <?if (count($item)>0):?>
                                    <? if (is_array($item)) array_walk($item, 'lili12', $cats);?>
                                <?endif?>
                            <?}array_walk($order_categories, 'lili12',$categories);?>
                        </select>
                    </div>
                </div>
                <div class="modal-footer text-right">
                    <button type="button" class="btn btn-default" data-dismiss="modal"><?=__('Cancel')?></button>
                    <button type="submit" class="btn btn-primary"><?=__('Create')?></button>
                </div>
            <?=FORM::close()?>
        </div>
    </div>
</div>
