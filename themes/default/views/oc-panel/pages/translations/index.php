<?php defined('SYSPATH') or die('No direct script access.');?>

<div class="md:flex md:items-center md:justify-between">
    <div class="flex-1 min-w-0">
        <h2 class="text-2xl font-bold leading-7 text-gray-900 sm:text-3xl sm:leading-9 sm:truncate">
            <?= __('Translations') ?>
        </h2>
        <div class="mt-1 sm:mt-0">
            <?= View::factory('oc-panel/components/learn-more', ['url' => 'https://guides.yclas.com/#/Translations']) ?>
        </div>
    </div>
    <div class="mt-4 flex md:mt-0 md:ml-4">
        <span class="shadow-sm rounded-md">
            <a href="<?=Route::url('oc-panel',array('controller'=>'translations','action'=>'index'))?>?parse=1" class="inline-flex items-center px-4 py-2 border border-transparent text-sm leading-5 font-medium rounded-md text-white bg-blue-600 hover:bg-blue-500 focus:outline-none focus:shadow-outline-blue focus:border-blue-700 active:bg-blue-700 transition duration-150 ease-in-out">
                <?= __('Scan') ?>
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
                                <?= __('Language') ?>
                            </th>
                            <th class="px-6 py-3 border-b border-gray-200 bg-gray-50"></th>
                            <th class="px-6 py-3 border-b border-gray-200 bg-gray-50"></th>
                            <th class="px-6 py-3 border-b border-gray-200 bg-gray-50"></th>
                        </tr>
                    </thead>
                    <tbody class="bg-white">
                        <?foreach ($languages as $key => $language):?>
                            <? $last_item = $key === count($languages) - 1 ?>
                            <?= View::factory('oc-panel/pages/translations/_language', ['language' => $language, 'current_language' => $current_language, 'last_item' => $last_item]) ?>
                        <? endforeach ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?if (class_exists('IntlCalendar')) :?>
    <?=Form::open(Request::current()->url())?>
        <div class="bg-white shadow sm:rounded-lg mt-8">
            <div class="px-4 py-5 sm:p-6">
                <h3 class="text-lg leading-6 font-medium text-gray-900">
                    <?=__('New translation')?>
                </h3>
                <div class="mt-2 max-w-xl text-sm leading-5 text-gray-500">
                    <p>
                        <?=__('If your locale is not listed, be sure your hosting has your locale installed.')?>
                    </p>
                </div>
                <div class="mt-5 sm:flex sm:items-center">
                    <div class="max-w-xs w-full">
                        <?=Form::label('locale', 'Locale', ['class' => 'sr-only', 'for' => 'locale'])?>
                        <div class="relative rounded-md shadow-sm">
                            <?= FORM::select('locale', IntlCalendar::getAvailableLocales(), NULL, [
                                'class' => 'form-select block w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5',
                            ])?>
                        </div>
                    </div>
                    <span class="mt-3 w-ful inline-flex rounded-md shadow-sm sm:mt-0 sm:ml-3 sm:w-auto">
                        <?=Form::button('submit', __('Create'), ['type'=>'submit', 'class'=>'w-full inline-flex items-center justify-center px-4 py-2 border border-transparent font-medium rounded-md text-white bg-blue-600 hover:bg-blue-500 focus:outline-none focus:border-blue-700 focus:shadow-outline-blue active:bg-blue-700 transition ease-in-out duration-150 sm:w-auto sm:text-sm sm:leading-5'])?>
                    </span>
                </div>
            </div>
        </div>
    <?=Form::close()?>
<? endif ?>
