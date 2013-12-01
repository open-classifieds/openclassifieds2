<?php defined('SYSPATH') or die('No direct script access.');?>

<div class="page-header">
	<h1><?=__('Translations')?></h1>
    <p><?=__('Translations files available in the system.')?><a href="http://open-classifieds.com/2013/08/20/how-to-change-language/" target="_blank"><?=__('Read more')?></a></p>

    <a class="btn btn-warning pull-right" href="<?=Route::url('oc-panel',array('controller'=>'translations','action'=>'index'))?>?parse=1" >
        <?=__('Scan')?></a>
    <a class="btn btn-primary pull-right" href="http://open-classifieds.com/documentation/translate/"><?=__('New translation')?></a>

</div>

<table class="table table-bordered">
    <tr>
        <th><?=__('Language')?></th>
        <th></th>
        <th></th>
    </tr>
<?foreach ($languages as $language):?>
    <tr>
        <td><?=$language?></td>
        <td width="5%">
            
            <a class="btn btn-warning" 
                href="<?=Route::url('oc-panel', array('controller'=>'translations','action'=>'edit','id'=>$language))?>" 
                rel="tooltip" title="<?=__('Edit')?>">
                <i class="glyphicon   glyphicon-pencil"></i>
            </a>

        </td>
        <td width="10%">
            <?if ($language!=$current_language):?>
            <a class="btn btn-default" 
                href="<?=Route::url('oc-panel', array('controller'=>'translations','action'=>'index','id'=>$language))?>" 
                rel="tooltip" title="<?=__('Activate')?>">
                <?=__('Activate')?>
            </a>
            <?else:?>
                <span class="label label-info"><?=__('Active')?></span>
            <?endif?>
        </td>
    </tr>
<?endforeach?>
</table>