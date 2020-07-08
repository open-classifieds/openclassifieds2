<?php defined('SYSPATH') or die('No direct script access.');?>

<div class="lg:flex lg:items-center lg:justify-between">
    <div class="flex-1 min-w-0">
        <h2 class="text-2xl font-bold leading-7 text-gray-900 sm:text-3xl sm:leading-9 sm:truncate">
            <?= __('Optimize database') ?>
        </h2>
        <div class="mt-1 sm:mt-0">
            <?= View::factory('oc-panel/components/learn-more', ['url' => 'https://guides.yclas.com/#/Optimizde-database']) ?>
        </div>
        <div class="mt-1 flex flex-col sm:mt-0 sm:flex-row sm:flex-wrap">
            <div class="mt-2 flex items-center text-sm leading-5 text-gray-500 sm:mr-6">
                <?=__('Database space')?>: <?=round($total_space, 2)?> KB
            </div>
            <div class="mt-2 flex items-center text-sm leading-5 text-gray-500 sm:mr-6">
                <?=__('Space to optimize')?>: <?=round($total_gain, 2)?> KB
            </div>
        </div>
    </div>
    <div class="mt-5 flex lg:mt-0 lg:ml-4">
        <span class="shadow-sm rounded-md">
            <a href="<?=Route::url('oc-panel',array('controller'=>'tools','action'=>'optimize'))?>?force=1" class="inline-flex items-center px-4 py-2 border border-transparent text-sm leading-5 font-medium rounded-md text-white bg-blue-600 hover:bg-blue-500 focus:outline-none focus:shadow-outline-blue focus:border-blue-700 active:bg-blue-700 transition duration-150 ease-in-out">
                <?= __('Optimize') ?>
            </a>
        </span>
    </div>
</div>

<div class="bg-white overflow-hidden shadow rounded-lg mt-8">
    <div class="flex flex-col">
        <div class="-my-2 py-2 overflow-x-auto sm:-mx-6 sm:px-6 lg:-mx-8 lg:px-8">
            <div class="align-middle inline-block min-w-full shadow overflow-hidden sm:rounded-lg border-b border-gray-200">
                <table class="min-w-full">
                    <thead>
                        <tr>
                            <th class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                                <?= __('Table') ?>
                            </th>
                            <th class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                                <?= __('Rows') ?>
                            </th>
                            <th class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                                <?= __('Size') ?> Kb
                            </th>
                            <th class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                                <?= __('Save size') ?> Kb
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white">
                        <?foreach ($tables as $key => $table):?>
                            <? $last_item = $key === count($tables) - 1 ?>
                                <tr class="<?= $table['gain']>0 ? 'bg-yellow-50' : '' ?>">
                                    <td class="px-6 py-4 whitespace-no-wrap <?= $last_item ? '' : 'border-b' ?> border-gray-200">
                                        <div class="text-sm leading-5 text-gray-900">
                                            <?=$table['name']?>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-no-wrap <?= $last_item ? '' : 'border-b' ?> border-gray-200">
                                        <div class="text-sm leading-5 text-gray-900">
                                            <?=$table['rows']?>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-no-wrap <?= $last_item ? '' : 'border-b' ?> border-gray-200">
                                        <div class="text-sm leading-5 text-gray-900">
                                            <?=$table['space']?>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-no-wrap <?= $last_item ? '' : 'border-b' ?> border-gray-200">
                                        <div class="text-sm leading-5 text-gray-900">
                                            <?=$table['gain']?>
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
