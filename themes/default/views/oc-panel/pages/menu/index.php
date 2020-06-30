<?php defined('SYSPATH') or die('No direct script access.');?>

<div class="md:flex md:items-center md:justify-between">
    <div class="flex-1 min-w-0">
        <h2 class="text-2xl font-bold leading-7 text-gray-900 sm:text-3xl sm:leading-9 sm:truncate">
            <?=__('Custom menu')?>
        </h2>
    </div>
    <div class="mt-4 flex md:mt-0 md:ml-4">
        <span x-data="{ open: false }" class="shadow-sm rounded-md">
            <button @click="open = true" type="button" class="inline-flex items-center px-4 py-2 border border-transparent text-sm leading-5 font-medium rounded-md text-white bg-blue-600 hover:bg-blue-500 focus:outline-none focus:shadow-outline-blue focus:border-blue-700 active:bg-blue-700 transition duration-150 ease-in-out">
                <?= __('Create a menu item') ?>
            </button>

            <div x-show="open" class="fixed bottom-0 inset-x-0 px-4 pb-4 sm:inset-0 sm:flex sm:items-center sm:justify-center">
                <div x-show="open" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 transition-opacity">
                    <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
                </div>
                <div x-show="open" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" class="bg-white rounded-lg overflow-hidden shadow-xl transform transition-all sm:max-w-lg sm:w-full">
                    <form method="post" action="<?=Route::url('oc-panel',array('controller'=>'menu','action'=>'new'))?>">
                        <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                            <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left space-y-6">
                                <h3 class="text-lg leading-6 font-medium text-gray-900">
                                    <?=__('Create menu item')?>
                                </h3>
                                <div>
                                    <?= Form::label('title', __('Title'), ['class' => 'block text-sm font-medium leading-5 text-gray-700']) ?>
                                    <?= FORM::input('title', Core::post('title', Core::post('title')), [
                                        'class' => 'mt-1 rounded-md shadow-sm form-input block w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5',
                                    ])?>
                                </div>
                                <div>
                                    <?= Form::label('url', __('URL'), ['class' => 'block text-sm font-medium leading-5 text-gray-700']) ?>
                                    <?= FORM::input('url', Core::post('url', Core::post('url')), [
                                        'placeholder' => Core::config('general.base_url'),
                                        'class' => 'mt-1 rounded-md shadow-sm form-input block w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5',
                                    ])?>
                                </div>
                                <div>
                                    <?= Form::label('target', __('Target'), ['class' => 'block text-sm font-medium leading-5 text-gray-700']) ?>
                                    <? $targets = [
                                        '_self' => '_self',
                                        '_blank' => '_blank',
                                        '_parent' => '_parent',
                                        '_top' => '_top',
                                    ] ?>
                                    <?= Form::select('target', $targets, Core::post('target'), [
                                        'class' => 'mt-1 rounded-md shadow-sm form-select block w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5',
                                    ])?>
                                </div>
                                <div>
                                    <?= Form::label('icon', __('Icon'), ['class' => 'block text-sm font-medium leading-5 text-gray-700']) ?>
                                    <?= FORM::input('icon', Core::post('icon'), [
                                        'x-data' => '',
                                        'x-init' => 'console.log($refs.input); $($refs.input).iconpicker();',
                                        'x-ref' => 'input',
                                        'class' => 'mt-1 rounded-md shadow-sm form-input block w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5',
                                    ])?>
                                </div>
                            </div>
                        </div>
                        <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                            <span class="flex w-full rounded-md shadow-sm sm:ml-3 sm:w-auto">
                                <?= Form::button('cancel', __('Save'), [
                                    'class' => 'inline-flex justify-center w-full rounded-md border border-transparent px-4 py-2 bg-blue-600 text-base leading-6 font-medium text-white shadow-sm hover:bg-blue-500 focus:outline-none focus:border-blue-700 focus:shadow-outline-blue transition ease-in-out duration-150 sm:text-sm sm:leading-5',
                                    'type' => 'submit',
                                    '@click' => "open = false",
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
                    </form>
                </div>
            </div>
        </span>
    </div>
</div>

<?if (is_array($menu)):?>
    <div class="bg-white overflow-hidden shadow rounded-lg mt-8">
        <div class="bg-white shadow overflow-hidden sm:rounded-md">
            <ul class="sortable divide-y divide-gray-200" id="ol_1" data-id="1">
                <?foreach($menu as $key=>$data):?>
                    <? $last_item = $key === count($menu) - 1 ?>
                    <?= View::factory('oc-panel/pages/menu/_item', ['key' => $key, 'data' => $data, 'last_item' => $last_item]) ?>
                <? endforeach ?>
            </ul>
        </div>
    </div>

    <span id='ajax_result' data-url='<?=Route::url('oc-panel',array('controller'=>'menu','action'=>'saveorder'))?>'></span>
<? endif ?>
