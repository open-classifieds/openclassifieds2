<?php defined('SYSPATH') or die('No direct script access.');?>

<div class="row">
    <div class="col-lg-12 page-title-container">
        <h1 class="page-header page-title"><?=__('Custom Fields for Users')?></h1>
        <span class="page-description"><?=__('This panel shows how many visitors your website had the past month.')?> <a target="_blank" href="https://docs.yclas.com/users-custom-fields/"><?=__('Read more')?></a></span>
        <a class="btn btn-primary pull-right ajax-load new-btn btn-icon-left" href="<?=Route::url('oc-panel',array('controller'=>'userfields','action'=>'new'))?>" title="<?=__('New field')?>">
            <i class="fa fa-plus-circle"></i><?=__('New field')?>
        </a>
    </div>
</div>

<?if (Theme::get('premium')!=1):?>
    <p class="well"><span class="label label-info"><?=__('Heads Up!')?></span> 
        <?=__('Custom fields are only available with premium themes!').' '.__('Upgrade your Open Classifieds site to activate this feature.')?>
        <a class="btn btn-success pull-right ajax-load" href="<?=Route::url('oc-panel',array('controller'=>'theme'))?>" title="<?=__('Browse Themes')?>"><?=__('Browse Themes')?></a>
    </p>
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
                                    <span class="label label-info label-sm"><?=$field['type']?></span>
                                    <span class="label label-info label-sm"><?=($field['required'])?__('required'):NULL?></span>
                                    <span class="label label-info label-sm"><?=(isset($field['searchable']) AND $field['searchable'])?__('Searchable'):NULL?></span>
                                    <span class="label label-info label-sm"><?=(isset($field['show_profile']) AND $field['show_profile'])?__('Show profile'):NULL?></span>
                                    <span class="label label-info label-sm"><?=(isset($field['show_register']) AND $field['show_register'])?__('Show register'):NULL?></span>
                                    <span class="label label-info label-sm"><?=(isset($field['admin_privilege']) AND $field['admin_privilege'])?__('Only Admin'):NULL?></span>
                                </div>
                                <a class="drag-action ajax-load" title="<?=__('Edit')?>"
                                    href="<?=Route::url('oc-panel',array('controller'=>'userfields','action'=>'update','id'=>$name))?>">
                                    <i class="fa fa-pencil-square-o"></i>
                                </a>
                                <a 
                                    href="<?=Route::url('oc-panel', array('controller'=> 'userfields', 'action'=>'delete','id'=>$name))?>" 
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
                <span id='ajax_result' data-url='<?=Route::url('oc-panel',array('controller'=>'userfields','action'=>'saveorder'))?>'></span>
                <div class="clearfix"></div>
            </div>
        </div>
    </div>
    

</div>
