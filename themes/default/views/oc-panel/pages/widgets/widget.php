<?php defined('SYSPATH') or die('No direct script access.');?>

<div class="md:flex md:items-center md:justify-between">
    <div class="flex-1 min-w-0">
        <h2 class="text-2xl font-bold leading-7 text-gray-900 sm:text-3xl sm:leading-9 sm:truncate">
            <?=__('Widgets')?>
        </h2>
    </div>
</div>

<div class="mt-8">
    <div class="grid grid-cols-3 gap-6">
        <div class="col-span-2">
            <div class="grid grid-cols-2 gap-6">
                <?foreach ($widgets as $widget):?>
                    <?=$widget->form()?>
                <?endforeach?>
            </div>
        </div>
        <div class="space-y-6">
            <?foreach ($placeholders as $placeholder=>$widgets):?>
                <div class="bg-white overflow-hidden shadow rounded-lg">
                    <div class="border-b border-gray-200 px-4 py-5 sm:px-6">
                        <h3 class="text-lg leading-6 font-medium text-gray-900">
                            <?=$placeholder?>
                        </h3>
                    </div>
                    <ul class='sortable' id="<?=$placeholder?>" style="min-height: 53px;">
                        <?foreach ($widgets as $widget):?>
                            <?=$widget->form()?>
                        <? endforeach ?>
                    </ul>
                </div>
            <?endforeach?>
            <span id='ajax_result' data-url='<?=Route::url('oc-panel',array('controller'=>'widget','action'=>'saveplaceholders'))?>'></span>
        </div>
    </div>
</div>
