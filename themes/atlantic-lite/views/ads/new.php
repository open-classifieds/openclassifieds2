<?php defined('SYSPATH') or die('No direct script access.');?>

<div class="mb-4">
    <h1 class="h2"><?=_e('Publish new advertisement')?></h1>
</div>

<div class="row">
    <div class="<?=core::count(Widgets::render('publish_new')) > 0 ? 'col-9' : 'col-12'?>">
        <?= View::factory('ads/_form', compact('id_category', 'id_location', 'form_show', 'selected_category', 'selected_location', 'fields')) ?>
    </div>

    <? if(core::count(Widgets::render('publish_new')) > 0) :?>
        <div class="col-12 col-md-3 col-sm-12">
            <?foreach ( Widgets::render('publish_new') as $widget):?>
                <div class="panel panel-sidebar <?=get_class($widget->widget)?>">
                    <?=$widget?>
                </div>
            <?endforeach?>
        </div>
    <?endif?>
</div>
