<?php defined('SYSPATH') or die('No direct script access.');?>

<div class="py-4">
    <div class="bg-white overflow-hidden shadow rounded-lg">
        <div class="px-4 py-5 sm:p-6">
            <div class="py-8 bg-white">
                <div class="max-w-xl mx-auto px-4 sm:px-6 lg:max-w-screen-xl lg:px-8">
                    <div class="grid grid-cols-1 lg:grid lg:grid-cols-3 lg:gap-4">
                        <a href="<?=Route::url('oc-panel',array('controller'=>'theme', 'action'=>'index'))?>" class="group px-4 py-4 text-sm leading-6 text-gray-600 rounded-md hover:text-gray-900 hover:bg-gray-50 focus:outline-none focus:bg-gray-100 transition ease-in-out duration-150">
                            <h5 class="text-base leading-6 font-medium text-blue-600"><?= __('Themes') ?></h5>
                            <p class="mt-2 text-sm leading-6 text-gray-500">
                                <?= __("Manage your website's themes.") ?>
                            </p>
                        </a>
                        <a href="<?=Route::url('oc-panel',array('controller'=>'theme', 'action'=>'options'))?>" class="group px-4 py-4 text-sm leading-6 text-gray-600 rounded-md hover:text-gray-900 hover:bg-gray-50 focus:outline-none focus:bg-gray-100 transition ease-in-out duration-150">
                            <h5 class="text-base leading-6 font-medium text-blue-600"><?= __('Theme Options') ?></h5>
                            <p class="mt-2 text-sm leading-6 text-gray-500">
                                <?= __("Change your websites's appearance.") ?>
                            </p>
                        </a>
                        <a href="<?=Route::url('oc-panel',array('controller'=>'widget'))?>" class="group px-4 py-4 text-sm leading-6 text-gray-600 rounded-md hover:text-gray-900 hover:bg-gray-50 focus:outline-none focus:bg-gray-100 transition ease-in-out duration-150">
                            <h5 class="text-base leading-6 font-medium text-blue-600"><?= __('Widgets') ?></h5>
                            <p class="mt-2 text-sm leading-6 text-gray-500">
                                <?= __("Manage your websites's widgets.") ?>
                            </p>
                        </a>
                        <a href="<?=Route::url('oc-panel',array('controller'=>'menu'))?>" class="group px-4 py-4 text-sm leading-6 text-gray-600 rounded-md hover:text-gray-900 hover:bg-gray-50 focus:outline-none focus:bg-gray-100 transition ease-in-out duration-150">
                            <h5 class="text-base leading-6 font-medium text-blue-600"><?= __('Menu') ?></h5>
                            <p class="mt-2 text-sm leading-6 text-gray-500">
                                <?= __("Manage your website's header menu link items.") ?>
                            </p>
                        </a>
                        <a href="<?=Route::url('oc-panel',array('controller'=>'theme', 'action'=>'css'))?>" class="group px-4 py-4 text-sm leading-6 text-gray-600 rounded-md hover:text-gray-900 hover:bg-gray-50 focus:outline-none focus:bg-gray-100 transition ease-in-out duration-150">
                            <h5 class="text-base leading-6 font-medium text-blue-600"><?= __('Custom CSS') ?></h5>
                            <p class="mt-2 text-sm leading-6 text-gray-500">
                                <?= __('Finetune the visual aspects of your website with custom CSS rules.') ?>
                            </p>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
