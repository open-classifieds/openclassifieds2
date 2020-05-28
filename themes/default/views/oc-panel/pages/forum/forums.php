<?php defined('SYSPATH') or die('No direct script access.');?>

<div class="bg-white overflow-hidden shadow rounded-lg mt-8">
    <div class="bg-white px-4 py-5 border-b border-gray-200 sm:px-6">
        <div class="-ml-4 -mt-2 flex items-center justify-between flex-wrap sm:flex-no-wrap">
            <div class="ml-4 mt-2">
                <h3 class="text-lg leading-6 font-medium text-gray-900">
                    <?= __('Forums') ?>
                </h3>
            </div>
            <div class="ml-4 mt-2 flex-shrink-0">
                <span class="inline-flex rounded-md shadow-sm">
                    <a href="<?= Route::url('oc-panel', ['controller' => 'forum', 'action' => 'create']) ?>" role="button" class="relative inline-flex items-center px-4 py-2 border border-transparent text-sm leading-5 font-medium rounded-md text-white bg-blue-600 hover:bg-blue-500 focus:outline-none focus:shadow-outline-blue focus:border-blue-700 active:bg-blue-700">
                        <?= __('New forum') ?>
                    </a>
                </span>
            </div>
        </div>
    </div>

    <?if (Core::count($forums) > 0):?>
        <div class="bg-white shadow overflow-hidden sm:rounded-md">
            <ul class='sortable' id="ul_1" data-id="0">
                <?function lili2($item, $key, $forums){?>
                    <? $last_item = $key === count($forums) - 1 ?>
                    <li class="<?= $last_item ? '' : 'border-b' ?> border-gray-200" data-id="<?=$key?>" id="li_<?=$key?>">
                        <a href="<?=Route::url('oc-panel',['controller'=>'forum','action'=>'update','id'=>$key])?>" class="block hover:bg-gray-50 focus:outline-none focus:bg-gray-50 transition duration-150 ease-in-out">
                            <div class="flex items-center px-4 py-4 sm:px-6">
                                <div class="min-w-0 flex-1 flex items-center">
                                    <div class="flex-shrink-0">
                                        <svg class="h-4 w-4 text-gray-400 cursor-move" fill="currentColor" viewBox="0 0 32 32">
                                            <path d="M9.125 27.438h4.563v4.563H9.125zm9.188 0h4.563v4.563h-4.563zm-9.188-9.125h4.563v4.563H9.125zm9.188 0h4.563v4.563h-4.563zM9.125 9.125h4.563v4.563H9.125zm9.188 0h4.563v4.563h-4.563zM9.125 0h4.563v4.563H9.125zm9.188 0h4.563v4.563h-4.563z"></path>
                                        </svg>
                                    </div>
                                    <div class="min-w-0 flex-1 px-4 md:grid md:grid-cols-2 md:gap-4 items-center">
                                        <div>
                                            <div class="text-sm leading-5 text-gray-900 truncate"><?=$forums[$key]['name']?></div>
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

                        <ul data-id="<?=$key?>" id="ul_<?=$key?>">
                            <? if (is_array($item)) array_walk($item, 'lili2', $forums);?>
                        </ul>
                    </li>
                <?}array_walk($order, 'lili2',$forums);?>
            </ul>
        </div>
    <?endif?>
</div>

<div class="flex justify-end mt-4">
    <span id="ajax_result" data-url="<?=Route::url('oc-panel', ['controller'=>'forum','action'=>'saveorder'])?>"></span>
</div>
