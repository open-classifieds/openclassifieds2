<?php defined('SYSPATH') or die('No direct script access.');?>

<div class="row">
    <div class="col-lg-12 page-title-container">
        <h1 class="page-header page-title"><?=__('Categories')?></h1>
        <span class="page-description"><?=__('Change the order of your categories. Keep in mind that more than 2 levels nested probably won´t be displayed in the theme (it is not recommended).')?> <a target="_blank" href="https://docs.yclas.com/how-to-add-categories/"><?=__('Read more')?></a></span>
        <a class="btn btn-primary ajax-load pull-right new-btn btn-icon-left" href="<?=Route::url('oc-panel',array('controller'=>'category','action'=>'create'))?>" title="<?=__('New Category')?>">
            <i class="fa fa-plus-circle"></i><?=__('New Category')?>
        </a>
    </div>
</div>
<div class="row">
    <div class="col-md-7">
        <div class="panel panel-default">
            <div class="panel-heading"><?=__('Home')?></div>
            <div class="panel-body table-responsive">
                <?=FORM::open(Route::url('oc-panel',array('controller'=>'category','action'=>'delete')), array('class'=>'form-inline', 'enctype'=>'multipart/form-data'))?>
                    <ol class='plholder' id="ol_1" data-id="1">
                        <?function lili($item, $key,$cats){?>
                            <li data-id="<?=$key?>" id="li_<?=$key?>">
                                <div class="drag-item">
                                    <span class="drag-icon">
                                        <i class="fa fa-ellipsis-v"></i><i class="fa fa-ellipsis-v"></i>
                                    </span>
                                    <div class="drag-name">
                                        <?=$cats[$key]['name']?>
                                    </div>
                                    <a class="drag-action ajax-load" title="<?=__('Edit')?>"
                                        href="<?=Route::url('oc-panel',array('controller'=>'category','action'=>'update','id'=>$key))?>">
                                        <i class="fa fa-pencil-square-o"></i>
                                    </a>
                                    <a 
                                        href="<?=Route::url('oc-panel', array('controller'=> 'category', 'action'=>'delete','id'=>$key))?>" 
                                        class="drag-action index-delete" 
                                        title="<?=__('Are you sure you want to delete?')?>" 
                                        data-id="li_<?=$key?>" 
                                        data-text="<?=__('We will move the siblings categories and ads to the parent of this category.')?>"
                                        data-btnOkLabel="<?=__('Yes, definitely!')?>" 
                                        data-btnCancelLabel="<?=__('No way!')?>">
                                        <i class="glyphicon glyphicon-trash"></i>
                                    </a>
                                    <span class="drag-action">
                                        <div class="checkbox">
                                            <label>
                                                <input name="categories[]" value="<?=$key?>" type="checkbox">
                                            </label>
                                        </div>
                                    </span>
                                </div>
                    
                                <ol data-id="<?=$key?>" id="ol_<?=$key?>">
                                    <? if (is_array($item)) array_walk($item, 'lili', $cats);?>
                                </ol><!--ol_<?=$key?>-->
                    
                            </li><!--li_<?=$key?>-->
                        <?}
                        if(is_array($order))
                            array_walk($order, 'lili',$cats);?>
                    </ol><!--ol_1-->
                    <span id='ajax_result' data-url='<?=Route::url('oc-panel',array('controller'=>'category','action'=>'saveorder'))?>'></span>
                    <?if(count($cats) > 1) :?>
                        <p class="text-right">
                            <button type="button" data-toggle="modal" data-target="#delete-all" class="btn btn-danger">
                                <?=__('Delete all categories')?>
                            </button>

                            <button name="delete" type="submit" class="btn btn-danger">
                                <i class="glyphicon glyphicon-trash space-right"></i><?=__('Delete selected categories')?>
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
                        <span class="label label-info space-bottom"><?=__('Heads Up!')?> <?=__('Quick category creator.')?></span>
                        <p><?=__('Add names for multiple categories, for each one push enter.')?></p>
                        <?= FORM::open(Route::url('oc-panel',array('controller'=>'category','action'=>'multy_categories')), array('class'=>'form-horizontal', 'role'=>'form','enctype'=>'multipart/form-data'))?>
                            <div class="form-group">
                                <?= FORM::label('multy_categories', __('Name').':', array('class'=>'col-sm-3 control-label', 'for'=>'multy_categories'))?>
                                <div class="col-sm-5">                                    
                                    <?= FORM::input('multy_categories', '', array('placeholder' => __('Hit enter to confirm'), 'class' => 'form-control', 'id' => 'multy_categories', 'type' => 'text','data-role'=>'tagsinput'))?>
                                </div>
                                <div class="col-sm-2">
                                    <?= FORM::button('submit', __('Send'), array('type'=>'submit', 'class'=>'btn btn-primary', 'action'=>Route::url('oc-panel',array('controller'=>'category','action'=>'multy_categories'))))?>
                                </div>
                            </div>
                            
                        <?= FORM::close()?>
                    </div>
                </div>
            </div>
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading"><h4><?=__('Upload CSV file')?></h4><span class="page-description"><?=__('This panel shows how many visitors your website had the past month.')?> <a target="_blank" href="https://docs.yclas.com/use-import-tool-categories-locations/"><?=__('Read more')?></a></span></div>
                    <div class="panel-body">
                        <p>
                            <?=__('Please use the correct CSV format')?> <a href="https://docs.google.com/uc?id=0B60e9iwQucDwTm1NRGlqcEZwdGM&export=download"><?=__('download example')?>.</a>
                        </p>
                        <?= FORM::open(Route::url('oc-panel',array('controller'=>'tools','action'=>'import_tool')), array('class'=>'form-horizontal', 'enctype'=>'multipart/form-data'))?>
                            <div class="form-group">
                                <label class="col-sm-3 control-label" for="csv_file_categories"><?=__('Import Categories')?></label>
                                <div class="col-sm-8">
                                    <input type="file" name="csv_file_categories" id="csv_file_categories" class="form-control">
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-sm-offset-3 col-sm-8">
                                    <?= FORM::button('submit', __('Upload'), array('type'=>'submit', 'class'=>'btn btn-primary', 'action'=>Route::url('oc-panel',array('controller'=>'tools','action'=>'import_tool'))))?>
                                </div>
                            </div>
                        <?= FORM::close()?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="delete-all" tabindex="-1" role="dialog" aria-labelledby="deleteCategories" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <?= FORM::open(Route::url('oc-panel',array('controller'=>'category','action'=>'delete_all'), array('class'=>'form-horizontal', 'role'=>'form')))?>
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times-circle"></i></button>
                    <h4 id="deleteCategories" class="modal-title"><?=__('Are you sure you want to delete all the categories?')?></h4>
                </div>
                <div class="modal-body">
                    <div class="alert alert-danger">
                        <p><?=__('We will move all the ads to home category.')?> <?=__('This is permanent! No backups, no restores, no magic undo button. We warned you, ok?')?></p>
                    </div>
                </div>
                <div class="modal-body text-right">
                    <button type="button" class="btn btn-default" data-dismiss="modal"><?=__('Cancel')?></button>
                    <button type="submit" class="btn btn-danger" name="confirmation" value="1"><?=__('Delete')?></button>
                </div>
            <?= FORM::close()?>
        </div>
    </div>
</div>
