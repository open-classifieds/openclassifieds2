<?php defined('SYSPATH') or die('No direct script access.');?>

<div class="page-header">
    <?if($type == 'page'):?>
        <h1><?=__('Page')?></h1>
    <?else:?>
        <h1><?=__('Email')?></h1>
    <?endif?>
</div>
<a class="btn btn-primary pull-right" 
    href="<?=Route::url('oc-panel', array('controller'=>'content','action'=>'create')).'?type='.$type ?>" 
    rel="tooltip" title="<?=__('Edit')?>">
    <?=__('New')?>
</a>
<?= FORM::open(Route::url('oc-panel',array('controller'=>'content','action'=>'list')), array('method'=>'GET','class'=>'form-horizontal', 'id'=>'locale_form','enctype'=>'multipart/form-data'))?>
    <div class="form-group">
        
        <div class="col-sm-4">
            <?= FORM::label('locale', __('Locale'), array('class'=>'control-label', 'for'=>'locale'))?>
            <?$ls = (isset($_REQUEST['locale_select']))?$_REQUEST['locale_select']:'';?>
            <?= FORM::select('locale_select', $locale_list, $ls )?> 
        </div>
        <div class="col-sm-4">
            <?= FORM::hidden('type', $type )?> 
        </div>
    </div>
<?= FORM::close()?>
<table class="table table-bordered">
    <tr>
        <th><?=__('Title')?></th>
        <th><?=__('locale')?></th>
        <th><?=__('created')?></th>
        <th><?=__('seotitle')?></th>
        <th><?=__('status')?></th>
        <th></th>
    </tr>
<?foreach ($contents as $content):?>
 
    <tr>
        <td><?=$content->title?></td>
        <td><?=$content->locale?></td>
        <td><?=$content->created?></td>
        <td><?=$content->seotitle?></td>
        <td><?=$content->status?></td>
        <td width="5%">
            
            <a class="btn btn-primary" 
                href="<?=Route::url('oc-panel', array('controller'=>'content','action'=>'edit','id'=>$content))?>" 
                rel="tooltip" title="<?=__('Edit')?>">
                <i class="glyphicon   glyphicon-edit"></i>
            </a>
            <a class="btn btn-danger" 
                href="<?=Route::url('oc-panel', array('controller'=>'content','action'=>'delete','id'=>$content))?>" 
                rel="tooltip" title="<?=__('Delete')?>">
                <i class="glyphicon   glyphicon-trash"></i>
            </a>

        </td>
    </tr>
    
<?endforeach?>
</table>