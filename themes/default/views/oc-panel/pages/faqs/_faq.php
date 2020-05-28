<?php defined('SYSPATH') or die('No direct script access.');?>

<li class="<?= $last_item ? '' : 'border-b' ?> border-gray-200" data-id="<?=$faq->id_content?>" id="<?=$faq->id_content?>">
    <a href="<?=Route::url('oc-panel', ['controller' => 'faqs', 'action'=>'update','id' => $faq->id_content])?>" class="block hover:bg-gray-50 focus:outline-none focus:bg-gray-50 transition duration-150 ease-in-out">
        <div class="flex items-center px-4 py-4 sm:px-6">
            <div class="min-w-0 flex-1 flex items-center">
                <div class="flex-shrink-0">
                    <svg class="h-4 w-4 text-gray-400 cursor-move" fill="currentColor" viewBox="0 0 32 32">
                        <path d="M9.125 27.438h4.563v4.563H9.125zm9.188 0h4.563v4.563h-4.563zm-9.188-9.125h4.563v4.563H9.125zm9.188 0h4.563v4.563h-4.563zM9.125 9.125h4.563v4.563H9.125zm9.188 0h4.563v4.563h-4.563zM9.125 0h4.563v4.563H9.125zm9.188 0h4.563v4.563h-4.563z"></path>
                    </svg>
                </div>
                <div class="min-w-0 flex-1 px-4 md:grid md:grid-cols-2 md:gap-4 items-center">
                    <div>
                        <div class="text-sm leading-5 text-gray-900 truncate"><?= $faq->title ?></div>
                    </div>
                    <div class="hidden md:block">
                        <?if ($faq->status==1):?>
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                <?= __('Active') ?>
                            </span>
                        <?else:?>
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                <?= __('Inactive') ?>
                            </span>
                        <?endif?>
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
</li>
