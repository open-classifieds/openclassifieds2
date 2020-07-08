<?php defined('SYSPATH') or die('No direct script access.');?>

<div class="md:flex md:items-center md:justify-between">
    <div class="flex-1 min-w-0">
        <h2 class="text-2xl font-bold leading-7 text-gray-900 sm:text-3xl sm:leading-9 sm:truncate">
            <?= __('Import ads') ?>
        </h2>
        <div class="mt-1 sm:mt-0">
            <?= View::factory('oc-panel/components/learn-more', ['url' => 'https://guides.yclas.com/#/Extras-how-to-import-advertisements']) ?>
        </div>
    </div>
    <? if (Core::is_selfhosted()) : ?>
        <div class="mt-4 flex md:mt-0 md:ml-4">
            <span class="ml-3 shadow-sm rounded-md">
                <a href="<?=Route::url('oc-panel', array('controller' => 'tools', 'action' => 'export'))?>" class="inline-flex items-center px-4 py-2 border border-transparent text-sm leading-5 font-medium rounded-md text-white bg-blue-600 hover:bg-blue-500 focus:outline-none focus:shadow-outline-blue focus:border-blue-700 active:bg-blue-700 transition duration-150 ease-in-out">
                    <?= __('Export') ?>
                </a>
            </span>
        </div>
    <? endif ?>
</div>

<? if (Core::extra_features() == FALSE) : ?>
    <?= View::factory('oc-panel/components/pro-alert') ?>
<? endif ?>

<div class="grid grid-cols-2 gap-4">
    <div>
        <?=FORM::open(Route::url('oc-panel',array('controller'=>'import','action'=>'csv')), array('class'=>'', 'enctype'=>'multipart/form-data'))?>
            <div class="bg-white shadow sm:rounded-lg mt-8">
                <div class="px-4 py-5 sm:p-6">
                    <h3 class="text-lg leading-6 font-medium text-gray-900">
                        <?=__('Upload CSV file')?>
                    </h3>
                    <div class="mt-2 max-w-xl text-sm leading-5 text-gray-500">
                        <p>
                            <?=__('Please use the correct CSV format')?> <a class="hover:underline" href="https://cdn.rawgit.com/yclas/yclas/master/install/samples/import/ads.csv"><?=__('download example')?></a>.
                        </p>
                    </div>
                    <div class="rounded-md bg-yellow-50 p-4 mt-2">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                            <div class="ml-3">
                                <h3 class="text-sm leading-5 font-medium text-yellow-800">
                                    <?= __('Hosting limit') ?>
                                </h3>
                                <div class="mt-2 text-sm leading-5 text-yellow-700">
                                    <ul class="list-disc">
                                        <li>
                                            upload_max_filesize: <?=ini_get('upload_max_filesize')?>
                                        </li>
                                        <li>
                                            max_execution_time: <?=ini_get('max_execution_time')?> <?=__('seconds')?> <?=__('limited to 10.000 at a time')?>, <?=__('1 MB file')?>.
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="mt-5 sm:flex sm:items-center">
                        <div class="max-w-xs w-full">
                            <?=Form::label('csv_file_ads', __('Import Ads'), ['class' => 'sr-only', 'for' => 'csv_file_ads'])?>
                            <div class="relative rounded-md shadow-sm">
                                <input type="file" name="csv_file_ads" id="csv_file_ads" />
                            </div>
                        </div>
                        <span class="mt-3 w-ful inline-flex rounded-md shadow-sm sm:mt-0 sm:ml-3 sm:w-auto">
                            <?=Form::button('submit', __('Upload'), ['type'=>'submit', 'class'=>'w-full inline-flex items-center justify-center px-4 py-2 border border-transparent font-medium rounded-md text-white bg-blue-600 hover:bg-blue-500 focus:outline-none focus:border-blue-700 focus:shadow-outline-blue active:bg-blue-700 transition ease-in-out duration-150 sm:w-auto sm:text-sm sm:leading-5'])?>
                        </span>
                    </div>
                </div>
            </div>
        <?=Form::close()?>
    </div>
    <div>
        <div class="bg-white shadow sm:rounded-lg mt-8">
            <div class="px-4 py-5 sm:p-6">
                <h3 class="text-lg leading-6 font-medium text-gray-900">
                    <?=__('Process Queue')?>
                </h3>
                <div class="mt-2 max-w-xl text-sm leading-5 text-gray-500">
                    <?if($ads_import>0):?>
                        <p id="count_import"><?=sprintf(__('You got %d ads to get processed'),$ads_import)?></p>
                        <p>
                            <a class="inline-block align-middle text-center select-none border font-normal whitespace-no-wrap py-2 px-4 rounded text-base leading-normal  text-green-100 bg-green-500 hover:bg-green-400" id="import_process" href="<?=Route::url('oc-panel',array('controller'=>'import','action'=>'process'))?>">
                                <?=__('Process')?>
                            </a>
                            <a class="inline-block align-middle text-center select-none border font-normal whitespace-no-wrap py-2 px-4 rounded text-base leading-normal  text-red-100 bg-red-500 hover:bg-red-400 btn-xs" id="delete_queue" href="<?=Route::url('oc-panel',array('controller'=>'import','action'=>'deletequeue'))?>">
                                <?=__('Delete')?>
                            </a>
                        <p>
                    <?else:?>
                        <?=__('Not any ads to be processed')?>
                    <?endif?>
                </div>
            </div>
        </div>
    </div>
</div>
