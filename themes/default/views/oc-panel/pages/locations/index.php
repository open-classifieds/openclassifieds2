<?php defined('SYSPATH') or die('No direct script access.');?>

<div class="md:flex md:items-center md:justify-between">
    <div class="flex-1 min-w-0">
        <h2 class="text-2xl font-bold leading-7 text-gray-900 sm:text-3xl sm:leading-9 sm:truncate">
            <? if ($location->id_location == 1) : ?>
                <?= __('Locations') ?>
            <? else: ?>
                <?= $location->name ?>
            <? endif ?>
        </h2>

        <div class="mt-1 sm:mt-0">
            <?= View::factory('oc-panel/components/learn-more', ['url' => 'https://guides.yclas.com/#/Settings-location']) ?>
        </div>
    </div>
    <div class="mt-4 flex md:mt-0 md:ml-4">
        <span class="ml-3 shadow-sm rounded-md">
            <a href="<?=Route::url('oc-panel',['controller'=>'location','action'=>'geonames'])?><?=Core::get('id_location') ? '?id_location='.HTML::chars(Core::get('id_location')) : NULL?>" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm leading-5 font-medium rounded-md text-gray-700 bg-white hover:text-gray-500 focus:outline-none focus:shadow-outline-blue focus:border-blue-300 active:text-gray-800 active:bg-gray-50 transition duration-150 ease-in-out">
                <?= __('Import Geonames Locations') ?>
            </a>
        </span>
        <? if ($location->id_location != 1) : ?>
            <span class="ml-3 shadow-sm rounded-md">
                <a href="<?=Route::url('oc-panel',['controller'=>'location','action'=>'update','id'=>$location->id_location])?>" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm leading-5 font-medium rounded-md text-gray-700 bg-white hover:text-gray-500 focus:outline-none focus:shadow-outline-blue focus:border-blue-300 active:text-gray-800 active:bg-gray-50 transition duration-150 ease-in-out">
                    <?= __('Edit') ?>
                </a>
            </span>
        <? endif ?>
        <span class="ml-3 shadow-sm rounded-md">
            <a href="<?=Route::url('oc-panel',['controller'=>'location','action'=>'create'])?><?=Core::get('id_location') ? '?id_location_parent='.HTML::chars(Core::get('id_location')) : NULL?>" class="inline-flex items-center px-4 py-2 border border-transparent text-sm leading-5 font-medium rounded-md text-white bg-blue-600 hover:bg-blue-500 focus:outline-none focus:shadow-outline-blue focus:border-blue-700 active:bg-blue-700 transition duration-150 ease-in-out">
                <?= __('New location') ?>
            </a>
        </span>
    </div>
</div>

<div class="bg-white overflow-hidden shadow rounded-lg mt-8">
    <div class="bg-white shadow overflow-hidden sm:rounded-md">
        <ul class="sortable divide-y divide-gray-200" id="ol_<?=$location->id_location?>" data-id="<?=$location->id_location?>">
            <? foreach ($locs as $key => $loc): ?>
                <? $last_item = $key === count($locs) - 1 ?>
                <?= View::factory('oc-panel/pages/locations/_location', ['location' => $loc, 'last_item' => $last_item]) ?>
            <? endforeach ?>
        </ul>
    </div>
</div>

<div class="flex justify-end mt-4">
    <span id="ajax_result" data-url="<?=Route::url('oc-panel', ['controller'=>'location','action'=>'saveorder'])?>"></span>
</div>

<?=FORM::open(Route::url('oc-panel',array('controller'=>'location','action'=>'multy_locations')), array('role'=>'form','enctype'=>'multipart/form-data'))?>
    <div class="bg-white shadow sm:rounded-lg mt-8">
        <div class="px-4 py-5 sm:p-6">
            <h3 class="text-lg leading-6 font-medium text-gray-900">
                <?= __('Quick location creator') ?>
            </h3>
            <div class="mt-2 max-w-xl text-sm leading-5 text-gray-500">
                <p>
                    <?= __('Separate each location with a comma.') ?>
                </p>
            </div>
            <div class="mt-5 sm:flex sm:items-center">
                <div class="max-w-xs w-full">
                    <?=FORM::label('multy_locations', __('Location'), array('class'=>'sr-only'))?>
                    <div class="relative rounded-md shadow-sm">
                        <?=FORM::input('multy_locations', '', array('placeholder' => 'e.g. Location A, Location B, Location C', 'class' => 'form-input block w-full sm:text-sm sm:leading-5'))?>
                    </div>
                </div>
                <span class="mt-3 w-ful inline-flex rounded-md shadow-sm sm:mt-0 sm:ml-3 sm:w-auto">
                    <?=FORM::button('submit', __('Send'), array('type'=>'submit', 'class'=>'w-full inline-flex items-center justify-center px-4 py-2 border border-transparent font-medium rounded-md text-white bg-blue-600 hover:bg-blue-500 focus:outline-none focus:border-blue-700 focus:shadow-outline-blue active:bg-blue-700 transition ease-in-out duration-150 sm:w-auto sm:text-sm sm:leading-5'))?>
                </span>
            </div>
        </div>
    </div>
<?=FORM::close()?>

<?= FORM::open(Route::url('oc-panel',['controller'=>'tools','action'=>'import_tool'.'?id_parent='.HTML::chars(Core::get('id_location', 1))]), ['enctype'=>'multipart/form-data'])?>
    <div class="bg-white shadow sm:rounded-lg mt-8">
        <div class="px-4 py-5 sm:p-6">
            <h3 class="text-lg leading-6 font-medium text-gray-900">
                <?=__('Import locations')?>
            </h3>
            <div class="mt-2 max-w-xl text-sm leading-5 text-gray-500">
                <p>
                    <?=__('Please use the correct CSV format')?> <a class="hover:underline" href="https://docs.google.com/uc?id=0B60e9iwQucDwa2VjRXAtV0FXVlk&export=download"><?=__('download example')?></a>.
                </p>
            </div>
            <div class="mt-5 sm:flex sm:items-center">
                <div class="max-w-xs w-full">
                    <?=Form::label('csv_file_locations', __('csv_file_locations'), ['class' => 'sr-only', 'for' => 'csv_file_locations'])?>
                    <div class="relative rounded-md shadow-sm">
                        <?= FORM::select('country', Model_Location::valid_import_countries(), NULL, [
                            'class' => 'form-select block w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5',
                        ]) ?>
                    </div>
                </div>
                <span class="mt-3 w-ful inline-flex rounded-md shadow-sm sm:mt-0 sm:ml-3 sm:w-auto">
                    <?=Form::button('submit', __('Import'), ['type'=>'submit', 'class'=>'w-full inline-flex items-center justify-center px-4 py-2 border border-transparent font-medium rounded-md text-white bg-blue-600 hover:bg-blue-500 focus:outline-none focus:border-blue-700 focus:shadow-outline-blue active:bg-blue-700 transition ease-in-out duration-150 sm:w-auto sm:text-sm sm:leading-5'])?>
                </span>
            </div>
            <div class="mt-7 sm:flex sm:items-center">
                <div class="max-w-xs w-full">
                    <?=Form::label('csv_file_locations', __('csv_file_locations'), ['class' => 'sr-only', 'for' => 'csv_file_locations'])?>
                    <div class="relative rounded-md shadow-sm">
                        <input type="file" name="csv_file_locations" id="csv_file_locations" />
                    </div>
                </div>
                <span class="mt-3 w-ful inline-flex rounded-md shadow-sm sm:mt-0 sm:ml-3 sm:w-auto">
                    <?=Form::button('submit', __('Upload'), ['type'=>'submit', 'class'=>'w-full inline-flex items-center justify-center px-4 py-2 border border-transparent font-medium rounded-md text-white bg-blue-600 hover:bg-blue-500 focus:outline-none focus:border-blue-700 focus:shadow-outline-blue active:bg-blue-700 transition ease-in-out duration-150 sm:w-auto sm:text-sm sm:leading-5'])?>
                </span>
            </div>
        </div>
    </div>
<?=Form::close()?>

<?=Form::open(Route::url('oc-panel', ['controller' => 'location', 'action'=>'delete_all']))?>
    <div class="bg-white shadow sm:rounded-lg mt-8">
        <div class="px-4 py-5 sm:p-6">
            <h3 class="text-lg leading-6 font-medium text-gray-900">
                <?= __('Delete all locations') ?>
            </h3>
            <div class="mt-2 max-w-xl text-sm leading-5 text-gray-500">
                <p>
                    <?= __('This is permanent! No backups, no restores, no magic undo button. We warned you, ok?') ?>
                </p>
            </div>
            <div class="mt-5">
                <?= Form::hidden('confirmation', 1) ?>
                <button type="submit" class="inline-flex items-center justify-center px-4 py-2 border border-transparent font-medium rounded-md text-red-700 bg-red-100 hover:bg-red-50 focus:outline-none focus:border-red-300 focus:shadow-outline-red active:bg-red-200 transition ease-in-out duration-150 sm:text-sm sm:leading-5">
                    <?= __('Delete') ?>
                </button>
            </div>
        </div>
    </div>
<?=Form::close()?>
