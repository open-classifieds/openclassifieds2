<?php defined('SYSPATH') or die('No direct script access.');?>

<?if ($widget->text_title!=''):?>
    <div class="panel-heading">
        <h3 class="panel-title"><?=$widget->text_title?></h3>
    </div>
<?endif?>

<div class="panel-body">
    <ul class="list-unstyled" id="Widget_RecentlySearched" data-url="<?=Route::url('search')?>" data-max-items="<?=$widget->max_items?>">
    </ul>
</div>
