<?php defined('SYSPATH') or die('No direct script access.');?>

<div class="md:flex md:items-center md:justify-between">
    <div class="flex-1 min-w-0">
        <h2 class="text-2xl font-bold leading-7 text-gray-900 sm:text-3xl sm:leading-9 sm:truncate">
            <?= __('Cache') ?>
        </h2>
    </div>
    <div class="mt-4 flex md:mt-0 md:ml-4">
        <span class="shadow-sm rounded-md">
            <a href="<?=Route::url('oc-panel',array('controller'=>'tools','action'=>'cache'))?>?force=1" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm leading-5 font-medium rounded-md text-gray-700 bg-white hover:text-gray-500 focus:outline-none focus:shadow-outline-blue focus:border-blue-300 active:text-gray-800 active:bg-gray-50 transition duration-150 ease-in-out">
                <?= __('Delete all') ?>
            </a>
        </span>

        <? if (Core::is_selfhosted()) : ?>
            <span class="ml-3 shadow-sm rounded-md">
                <a href="<?=Route::url('oc-panel',array('controller'=>'tools','action'=>'cache'))?>?force=2" class="inline-flex items-center px-4 py-2 border border-transparent text-sm leading-5 font-medium rounded-md text-white bg-blue-600 hover:bg-blue-500 focus:outline-none focus:shadow-outline-blue focus:border-blue-700 active:bg-blue-700 transition duration-150 ease-in-out">
                    <?= __('Delete expired') ?>
                </a>
            </span>
        <? endif ?>
    </div>
</div>

<? if (Core::is_selfhosted()) : ?>
    <div class="bg-white overflow-hidden shadow rounded-lg mt-8">
        <div class="flex flex-col">
            <div class="-my-2 py-2 overflow-x-auto sm:-mx-6 sm:px-6 lg:-mx-8 lg:px-8">
                <div class="align-middle inline-block min-w-full shadow overflow-hidden sm:rounded-lg border-b border-gray-200">
                    <table class="min-w-full">
                        <tbody class="bg-white">
                            <tr>
                                <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200">
                                    <div class="text-sm leading-5 text-gray-900">
                                        <?=__('Config file')?>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200">
                                    <div class="text-sm leading-5 text-gray-900">
                                        <?=APPPATH?>config/cache.php
                                    </div>
                                </td>
                            </tr>
                            <?foreach ($cache_config as $key => $value):?>
                                <? $last_item = $key === count($cache_config) - 1 ?>
                                <tr>
                                    <td class="px-6 py-4 whitespace-no-wrap <?= $last_item ? '' : 'border-b' ?> border-gray-200">
                                        <div class="text-sm leading-5 text-gray-900">
                                            <?= $key ?>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-no-wrap <?= $last_item ? '' : 'border-b' ?> border-gray-200">
                                        <div class="text-sm leading-5 text-gray-900">
                                            <?=print_r($value,1)?>
                                        </div>
                                    </td>
                                </tr>
                            <? endforeach ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <? if (Core::config('cache.default') == 'apcu'): ?>
        <div class="bg-white overflow-hidden shadow rounded-lg mt-8">
            <div class="flex flex-col">
                <div class="-my-2 py-2 overflow-x-auto sm:-mx-6 sm:px-6 lg:-mx-8 lg:px-8">
                    <div class="align-middle inline-block min-w-full shadow overflow-hidden sm:rounded-lg border-b border-gray-200">
                        <table class="min-w-full">
                            <tbody class="bg-white">
                                <?foreach ($cache_config = array_merge(apcu_cache_info(),apcu_sma_info()) as $key => $value):?>
                                    <? $last_item = $key === count($cache_config) - 1 ?>
                                    <?if ( (!empty($value) OR is_numeric($value)) AND $key!='block_lists' ):?>
                                        <tr>
                                            <td class="px-6 py-4 whitespace-no-wrap <?= $last_item ? '' : 'border-b' ?> border-gray-200">
                                                <div class="text-sm leading-5 text-gray-900">
                                                    <?= $key ?>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-no-wrap <?= $last_item ? '' : 'border-b' ?> border-gray-200">
                                                <div class="text-sm leading-5 text-gray-900">
                                                    <?
                                                        switch ($key) {
                                                            case 'start_time':
                                                                echo Date::unix2mysql($value);
                                                                break;
                                                            case 'seg_size':
                                                            case 'avail_mem':
                                                            case 'mem_size':
                                                                echo Text::bytes($value);
                                                                break;
                                                            default:
                                                                print_r($value);
                                                                break;
                                                        }
                                                    ?>
                                                </div>
                                            </td>
                                        </tr>
                                    <? endif ?>
                                <? endforeach ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    <? endif ?>
<? else: ?>
    <div class="bg-white shadow sm:rounded-lg mt-8">
        <div class="px-4 py-5 sm:p-6">
            <h3 class="text-lg leading-6 font-medium text-gray-900">
                <?= __('Delete your website cache') ?>
            </h3>
            <div class="mt-5">
                <button type="button" class="inline-flex items-center justify-center px-4 py-2 border border-transparent font-medium rounded-md text-blue-700 bg-blue-100 hover:bg-blue-50 focus:outline-none focus:border-blue-300 focus:shadow-outline-blue active:bg-blue-200 transition ease-in-out duration-150 sm:text-sm sm:leading-5">
                    <?= __('Delete cache') ?>
                </button>
            </div>
        </div>
    </div>
<? endif ?>
