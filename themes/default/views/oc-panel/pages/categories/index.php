<?php defined('SYSPATH') or die('No direct script access.');?>

<div class="md:flex md:items-center md:justify-between">
    <div class="flex-1 min-w-0">
        <h2 class="text-2xl font-bold leading-7 text-gray-900 sm:text-3xl sm:leading-9 sm:truncate">
            <?= __('Categories') ?>
        </h2>

        <div class="mt-1 sm:mt-0">
            <?= View::factory('oc-panel/components/learn-more', ['url' => 'https://guides.yclas.com/#/Settings-categories']) ?>
        </div>
    </div>
    <div class="mt-4 flex md:mt-0 md:ml-4">
        <span class="ml-3 shadow-sm rounded-md">
            <a href="<?=Route::url('oc-panel',array('controller'=>'category','action'=>'create'))?>" title="<?=__('New')?>" class="inline-flex items-center px-4 py-2 border border-transparent text-sm leading-5 font-medium rounded-md text-white bg-blue-600 hover:bg-blue-500 focus:outline-none focus:shadow-outline-blue focus:border-blue-700 active:bg-blue-700 transition duration-150 ease-in-out">
                <?= __('New category') ?>
            </a>
        </span>
    </div>
</div>

<div class="bg-white overflow-hidden shadow rounded-lg mt-8">
    <div class="bg-white shadow overflow-hidden sm:rounded-md">
        <ul class="sortable divide-y divide-gray-200" id="ul_1" data-id="1">
            <?function lili2($item, $key, $cats){?>
                <? $last_item = $key === count($cats) - 1 ?>
                <li data-id="<?=$key?>" id="li_<?=$key?>">
                    <a href="<?=Route::url('oc-panel',['controller'=>'category','action'=>'update','id'=>$key])?>" class="block hover:bg-gray-50 focus:outline-none focus:bg-gray-50 transition duration-150 ease-in-out <?= is_array($item) ? 'border-gray-200 border-b' : '' ?>">
                        <div class="flex items-center px-4 py-4 sm:px-6">
                            <div class="min-w-0 flex-1 flex items-center">
                                <div class="flex-shrink-0">
                                    <svg class="h-4 w-4 text-gray-400 cursor-move" fill="currentColor" viewBox="0 0 32 32">
                                        <path d="M9.125 27.438h4.563v4.563H9.125zm9.188 0h4.563v4.563h-4.563zm-9.188-9.125h4.563v4.563H9.125zm9.188 0h4.563v4.563h-4.563zM9.125 9.125h4.563v4.563H9.125zm9.188 0h4.563v4.563h-4.563zM9.125 0h4.563v4.563H9.125zm9.188 0h4.563v4.563h-4.563z"></path>
                                    </svg>
                                </div>
                                <div class="min-w-0 flex-1 px-4 md:grid md:grid-cols-2 md:gap-4 items-center">
                                    <div>
                                        <div class="text-sm leading-5 text-gray-900 truncate"><?=$cats[$key]['name']?></div>
                                    </div>
                                </div>
                            </div>
                            <div>
                                <svg class="h-5 w-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                        </div>
                    </a>

                    <ul class="divide-y divide-gray-200" data-id="<?=$key?>" id="ul_<?=$key?>">
                        <? if (is_array($item)) array_walk($item, 'lili2', $cats);?>
                    </ul>
                </li>
            <?}array_walk($order, 'lili2',$cats);?>
        </ul>
    </div>
</div>

<div class="flex justify-end mt-4">
    <span id="ajax_result" data-url="<?=Route::url('oc-panel', ['controller'=>'category','action'=>'saveorder'])?>"></span>
</div>

<?=FORM::open(Route::url('oc-panel',array('controller'=>'category','action'=>'multy_categories')), array('role'=>'form','enctype'=>'multipart/form-data'))?>
    <div class="bg-white shadow sm:rounded-lg mt-8">
        <div class="px-4 py-5 sm:p-6">
            <h3 class="text-lg leading-6 font-medium text-gray-900">
                <?= __('Quick category creator') ?>
            </h3>
            <div class="mt-2 max-w-xl text-sm leading-5 text-gray-500">
                <p>
                    <?= __('Separate each category with a comma.') ?>
                </p>
            </div>
            <div class="mt-5 sm:flex sm:items-center">
                <div class="max-w-xs w-full">
                    <?=FORM::label('multy_categories', __('Categories'), array('class'=>'sr-only'))?>
                    <div class="relative rounded-md shadow-sm">
                        <?=FORM::input('multy_categories', '', array('placeholder' => 'e.g. Category A, Category B, Category C', 'class' => 'form-input block w-full sm:text-sm sm:leading-5'))?>
                    </div>
                </div>
                <span class="mt-3 w-ful inline-flex rounded-md shadow-sm sm:mt-0 sm:ml-3 sm:w-auto">
                    <?=FORM::button('submit', __('Send'), array('type'=>'submit', 'class'=>'w-full inline-flex items-center justify-center px-4 py-2 border border-transparent font-medium rounded-md text-white bg-blue-600 hover:bg-blue-500 focus:outline-none focus:border-blue-700 focus:shadow-outline-blue active:bg-blue-700 transition ease-in-out duration-150 sm:w-auto sm:text-sm sm:leading-5'))?>
                </span>
            </div>
        </div>
    </div>
<?=FORM::close()?>

<?=FORM::open(Route::url('oc-panel',array('controller'=>'category','action'=>'hide_homepage_categories')), array('role'=>'form','enctype'=>'multipart/form-data'))?>
    <div class="bg-white shadow sm:rounded-lg mt-8">
        <div class="px-4 py-5 sm:p-6">
            <h3 class="text-lg leading-6 font-medium text-gray-900">
                <?= __('Hide Categories') ?>
            </h3>
            <div class="mt-2 max-w-xl text-sm leading-5 text-gray-500">
                <p>
                    <?= __('Hide categories from homepage.') ?>
                </p>
            </div>
            <div class="mt-5 sm:flex sm:items-start">
                <div class="max-w-xs w-full">
                    <?$categories = []?>
                    <?
                        foreach ((new Model_Category)->where('id_category','!=','1')->order_by('order','asc')->find_all()->cached() as $category) {
                            $categories[$category->id_category] = $category->translate_name();
                        }
                    ?>
                    <?=FORM::label('hide_homepage_categories', __('Categories'), array('class'=>'sr-only'))?>
                    <div class="relative rounded-md shadow-sm">
                        <?=FORM::hidden('hide_homepage_categories[]', NULL)?>
                        <?=FORM::select('hide_homepage_categories[]', $categories, $hide_homepage_categories, array(
                            'class' => 'form-multiselect block w-full sm:text-sm sm:leading-5',
                            'id' => 'hide_homepage_categories',
                        ))?>
                    </div>
                </div>
                <span class="mt-3 w-ful inline-flex rounded-md shadow-sm sm:mt-0 sm:ml-3 sm:w-auto">
                    <?=FORM::button('submit', __('Save'), array('type'=>'submit', 'class'=>'w-full inline-flex items-center justify-center px-4 py-2 border border-transparent font-medium rounded-md text-white bg-blue-600 hover:bg-blue-500 focus:outline-none focus:border-blue-700 focus:shadow-outline-blue active:bg-blue-700 transition ease-in-out duration-150 sm:w-auto sm:text-sm sm:leading-5'))?>
                </span>
            </div>
        </div>
    </div>
<?=FORM::close()?>

<?=FORM::open(Route::url('oc-panel',array('controller'=>'tools','action'=>'import_tool')), array('enctype'=>'multipart/form-data'))?>
    <div class="bg-white shadow sm:rounded-lg mt-8">
        <div class="px-4 py-5 sm:p-6">
            <h3 class="text-lg leading-6 font-medium text-gray-900">
                <?=__('Import Categories')?>
            </h3>
            <div class="mt-2 max-w-xl text-sm leading-5 text-gray-500">
                <p>
                    <?=__('Please use the correct CSV format')?> <a class="hover:underline" href="https://docs.google.com/uc?id=0B60e9iwQucDwTm1NRGlqcEZwdGM&export=download"><?=__('download example')?></a>.
                </p>
            </div>
            <div class="mt-5 sm:flex sm:items-center">
                <div class="max-w-xs w-full">
                    <?=Form::label('csv_file_categories', __('csv_file_categories'), ['class' => 'sr-only', 'for' => 'csv_file_categories'])?>
                    <div class="relative rounded-md shadow-sm">
                        <input type="file" name="csv_file_categories" id="csv_file_categories" />
                    </div>
                </div>
                <span class="mt-3 w-ful inline-flex rounded-md shadow-sm sm:mt-0 sm:ml-3 sm:w-auto">
                    <?=Form::button('submit', __('Upload'), ['type'=>'submit', 'class'=>'w-full inline-flex items-center justify-center px-4 py-2 border border-transparent font-medium rounded-md text-white bg-blue-600 hover:bg-blue-500 focus:outline-none focus:border-blue-700 focus:shadow-outline-blue active:bg-blue-700 transition ease-in-out duration-150 sm:w-auto sm:text-sm sm:leading-5'])?>
                </span>
            </div>
        </div>
    </div>
<?=Form::close()?>

<?=Form::open(Route::url('oc-panel', ['controller' => 'category', 'action'=>'delete_all']))?>
    <div class="bg-white shadow sm:rounded-lg mt-8">
        <div class="px-4 py-5 sm:p-6">
            <h3 class="text-lg leading-6 font-medium text-gray-900">
                <?= __('Delete all categories') ?>
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
