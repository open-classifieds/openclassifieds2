<?php defined('SYSPATH') or die('No direct script access.');?>

<?if ($widget->text_title!=''):?>
    <div class="panel-heading">
        <h3 class="panel-title"><?=$widget->text_title?></h3>
    </div>
<?endif?>

<div class="panel-body">
    <?if($widget->location !== FALSE) :?>
        <p><a href="<?=Route::url('list', array('location'=>$widget->location->seoname))?>"><?=$widget->location->name?></a> <small>(<a href="<?=Route::url('default')?>?user_location=0"><?=__('Change Location')?></a>)</small></p>
    <?endif?>
</div>
