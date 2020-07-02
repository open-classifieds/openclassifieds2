<?php defined('SYSPATH') or die('No direct script access.');?>

<div x-data="{ open: false }">
    <?if (!$widget->loaded):?>
        <div class="bg-white shadow sm:rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <div class="sm:flex sm:items-start sm:justify-between">
                    <div>
                        <h3 class="text-lg leading-6 font-medium text-gray-900">
                            <?=$widget->title?>
                        </h3>
                        <?if(Core::is_cloud() AND get_class($widget) == 'Widget_Text' AND Model_Domain::current()->old_domain === NULL):?>
                            <div class="alert alert-warning" role="alert">
                                <?=__('If you want to use Google Adsense banners, they will not be displayed if you use our free domain Yclas.com')?>
                                &nbsp;
                                <a href="https://yclas.com/faq/custom-banners.html" target="_blank">
                                    <?=__('Read more')?> <i class="fa fa-external-link"></i>
                                </a>
                            </div>
                        <?endif?>
                        <div class="mt-2 max-w-xl text-sm leading-5 text-gray-500">
                            <p>
                                <?=$widget->description?>
                            </p>
                        </div>
                    </div>
                    <div class="mt-5 sm:mt-0 sm:ml-6 sm:flex-shrink-0 sm:flex sm:items-center">
                        <span class="inline-flex rounded-md shadow-sm">
                            <button @click="open = true" type="button" class="inline-flex items-center px-4 py-2 border border-transparent text-sm leading-5 font-medium rounded-md text-white bg-blue-600 hover:bg-blue-500 focus:outline-none focus:border-blue-700 focus:shadow-outline-blue active:bg-blue-700 transition ease-in-out duration-150">
                                <?=__('Create')?>
                            </button>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    <?else:?>
        <li class="border-b border-gray-200 liholder" id="<?=$widget->id_name()?>">
            <a @click.prevent="open = true" href="#" class="hover:bg-gray-50 focus:outline-none focus:bg-gray-50 transition duration-150 ease-in-out">
                <div class="flex items-center px-4 py-4 sm:px-6">
                    <div class="min-w-0 flex-1 flex items-center">
                        <div class="flex-shrink-0">
                            <svg class="h-4 w-4 text-gray-400 cursor-move" fill="currentColor" viewBox="0 0 32 32">
                                <path d="M9.125 27.438h4.563v4.563H9.125zm9.188 0h4.563v4.563h-4.563zm-9.188-9.125h4.563v4.563H9.125zm9.188 0h4.563v4.563h-4.563zM9.125 9.125h4.563v4.563H9.125zm9.188 0h4.563v4.563h-4.563zM9.125 0h4.563v4.563H9.125zm9.188 0h4.563v4.563h-4.563z"></path>
                            </svg>
                        </div>
                        <div class="min-w-0 flex-1 px-4 md:grid md:grid-cols-2 md:gap-4 items-center">
                            <div>
                                <div class="text-sm leading-5 text-gray-900 truncate"><?=$widget->title()?> <span class="muted"><?=$widget->title?></div>
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
    <?endif?>

    <div x-show="open" class="fixed bottom-0 inset-x-0 px-4 pb-4 sm:inset-0 sm:flex sm:items-center sm:justify-center">
        <div x-show="open" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 transition-opacity">
            <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
        </div>
        <div x-show="open" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" class="bg-white rounded-lg overflow-hidden shadow-xl transform transition-all sm:max-w-lg sm:w-full">
            <form id="form_widget_<?=$widget->id_name()?>" name="form_widget_<?=$widget->id_name()?>" method="post" action="<?=Route::url('oc-panel',array('controller'=>'widget','action'=>'save'))?>" >
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left overflow-y-auto max-h-96">
                        <h3 class="text-lg leading-6 font-medium text-gray-900">
                            <?=$widget->title?>
                        </h3>
                        <?if(Core::is_cloud() AND get_class($widget) == 'Widget_Text' AND Model_Domain::current()->old_domain === NULL):?>
                            <div class="alert alert-warning" role="alert">
                                <?=__('If you want to use Google Adsense banners, they will not be displayed if you use our free domain Yclas.com')?>
                                &nbsp;
                                <a href="https://yclas.com/faq/custom-banners.html" target="_blank">
                                    <?=__('Read more')?> <i class="fa fa-external-link"></i>
                                </a>
                            </div>
                        <?endif?>
                        <div class="mt-2">
                            <p class="text-sm leading-5 text-gray-500">
                                <?=$widget->description?>
                            </p>
                        </div>
                        <div class="mt-2">
                            <div class="mb-4">
                                <?= Form::label('placeholder_form', __('Where do you want the widget displayed?'), ['class' => 'block text-sm font-medium leading-5 text-gray-700']) ?>
                                <?= Form::select('placeholder', array_combine(widgets::get_placeholders(TRUE), widgets::get_placeholders(TRUE)), $widget->placeholder, [
                                    'class' => 'mt-1 rounded-md shadow-sm form-select block w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5',
                                ])?>
                            </div>

                            <?foreach ($tags as $tag):?>
                                <div class="mb-4">
                                    <?=$tag?>
                                </div>
                            <?endforeach?>

                            <?if ($widget->loaded):?>
                                <?= Form::hidden('widget_name', $widget->widget_name) ?>
                            <?endif?>

                            <?= Form::hidden('widget_class', get_class($widget)) ?>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex">
                    <div>
                        <?if ($widget->loaded):?>
                            <span class="flex w-full rounded-md shadow-sm sm:ml-3 sm:w-auto">
                                <a href="<?=Route::url('oc-panel',array('controller'=>'widget','action'=>'remove','id'=>$widget->widget_name))?>"
                                    class="inline-flex justify-center w-full rounded-md border border-transparent px-4 py-2 bg-red-600 text-base leading-6 font-medium text-white shadow-sm hover:bg-red-500 focus:outline-none focus:border-red-700 focus:shadow-outline-red transition ease-in-out duration-150 sm:text-sm sm:leading-5",
                                >
                                    <?= __('Delete') ?>
                                </a>
                            </span>
                        <? endif ?>
                    </div>
                    <div class="sm:flex sm:flex-1 sm:flex-row-reverse">
                        <span class="flex w-full rounded-md shadow-sm sm:ml-3 sm:w-auto">
                            <?= Form::button('cancel', __('Save'), [
                                'class' => 'inline-flex justify-center w-full rounded-md border border-transparent px-4 py-2 bg-blue-600 text-base leading-6 font-medium text-white shadow-sm hover:bg-blue-500 focus:outline-none focus:border-blue-700 focus:shadow-outline-blue transition ease-in-out duration-150 sm:text-sm sm:leading-5',
                                'type' => 'submit',
                            ]) ?>
                        </span>
                        <span class="mt-3 flex w-full rounded-md shadow-sm sm:mt-0 sm:w-auto">
                            <?= Form::button('cancel', __('Cancel'), [
                                'class' => 'inline-flex justify-center w-full rounded-md border border-gray-300 px-4 py-2 bg-white text-base leading-6 font-medium text-gray-700 shadow-sm hover:text-gray-500 focus:outline-none focus:border-blue-300 focus:shadow-outline transition ease-in-out duration-150 sm:text-sm sm:leading-5',
                                'type' => 'button',
                                '@click' => "open = false",
                            ]) ?>
                        </span>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
