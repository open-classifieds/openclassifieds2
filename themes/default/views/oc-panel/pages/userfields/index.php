<?php defined('SYSPATH') or die('No direct script access.');?>

<div class="md:flex md:items-center md:justify-between">
    <div class="flex-1 min-w-0">
        <h2 class="text-2xl font-bold leading-7 text-gray-900 sm:text-3xl sm:leading-9 sm:truncate">
            <?= __('User custom fields') ?>
        </h2>

        <div class="mt-1 sm:mt-0">
            <?= View::factory('oc-panel/components/learn-more', ['url' => 'https://guides.yclas.com/#/Users-create-custom-field-for-users']) ?>
        </div>
    </div>
    <div class="mt-4 flex md:mt-0 md:ml-4">
        <span class="ml-3 shadow-sm rounded-md">
            <a
                href="<?=Route::url('oc-panel',array('controller'=>'userfields','action'=>'new'))?>"
                class="inline-flex items-center px-4 py-2 border border-transparent text-sm leading-5 font-medium rounded-md text-white bg-blue-600 hover:bg-blue-500 focus:outline-none focus:shadow-outline-blue focus:border-blue-700 active:bg-blue-700 transition duration-150 ease-in-out"
            >
                <?= __('New field') ?>
            </a>
        </span>
    </div>
</div>

<? if (Core::extra_features() == FALSE) : ?>
    <?= View::factory('oc-panel/components/pro-alert') ?>
<? endif ?>

<? if (is_array($fields)): ?>
    <div class="bg-white overflow-hidden shadow rounded-lg mt-8">
        <div class="bg-white shadow overflow-hidden sm:rounded-md">
            <ul class="sortable divide-y divide-gray-200" id="ol_1" data-id="1">
                <? foreach($fields as $name=> $field): ?>
                    <? $last_item = $name === count($fields) - 1 ?>
                    <?= View::factory('oc-panel/pages/userfields/_field', ['name' => $name, 'field' => $field, 'last_item' => $last_item]) ?>
                <? endforeach ?>
            </ul>
        </div>
    </div>

    <div class="flex justify-end mt-4">
        <span id="ajax_result" data-url="<?=Route::url('oc-panel',['controller'=>'userfields','action'=>'saveorder'])?>"></span>
    </div>
<? endif ?>
