<?php defined('SYSPATH') or die('No direct script access.');?>

<div class="card card-map mb-3">
    <? if ($widget->map_title != '') : ?>
        <div class="card-header">
            <span class="h6"><?= $widget->map_title ?></span>
        </div>
    <? endif ?>

    <div class="card-body py-3">
        <iframe
            frameborder="0"
            noresize="noresize"
            height="<?=intval($widget->map_height)+(intval($widget->map_height)*0.10)?>px" width="100%"
            src="<?=Route::url('map')?>?height=<?=intval($widget->map_height)?>&controls=0&zoom=<?=$widget->map_zoom?><?=(Model_Category::current()->loaded())?'&category='.Model_Category::current()->seoname:''?><?=(Model_Location::current()->loaded())?'&location='.Model_Location::current()->seoname:''?>"
        ></iframe>
    </div>
</div>
