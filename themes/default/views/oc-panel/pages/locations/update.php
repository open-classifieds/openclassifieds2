<?php defined('SYSPATH') or die('No direct script access.');?>

<div class="md:flex md:items-center md:justify-between">
    <div class="flex-1 min-w-0">
        <h2 class="text-2xl font-bold leading-7 text-gray-900 sm:text-3xl sm:leading-9 sm:truncate">
            <?= $location->name ?>
        </h2>
    </div>
</div>

<div class="mt-8">
    <?=$form->render()?>
</div>

<? if (Core::config('general.multilingual')) : ?>
    <div class="bg-white overflow-hidden shadow rounded-lg mt-8">
        <div class="bg-white px-4 py-5 border-b border-gray-200 sm:px-6">
            <div class="-ml-4 -mt-2 flex items-center justify-between flex-wrap sm:flex-no-wrap">
                <div class="ml-4 mt-2">
                    <h3 class="text-lg leading-6 font-medium text-gray-900">
                        <?= __('Translations') ?>
                    </h3>
                </div>
            </div>
        </div>
        <div class="px-4 py-5 sm:p-6">
            <?= Form::open(Route::url('oc-panel', array('controller' => 'location', 'action' => 'update_translations', 'id' => $form->object->id_location)), ['class'=>'config ajax-load', 'enctype'=>'multipart/form-data'])?>
                <div>
                    <? foreach ($languages = i18n::get_selectable_languages() as $locale => $language) : ?>
                        <? $last_item = $locale === count($languages) - 1 ?>
                        <div class="<?= $last_item ? '' : 'mb-8 border-b border-gray-200 pb-8' ?>">
                            <div>
                                <h3 class="text-base leading-5 font-medium text-gray-900">
                                    <?= $locale ?>
                                </h3>
                            </div>
                            <div class="mt-6 grid grid-cols-1 row-gap-6 col-gap-4 sm:grid-cols-6">
                                <div class="sm:col-span-4">
                                    <?= FORM::label('translations_name_' . $locale, _e('Name'), array('class' => 'block text-sm font-medium leading-5 text-gray-700', 'for' => 'translations_name_' . $locale)) ?>
                                    <div class="mt-1 rounded-md shadow-sm">
                                        <?= FORM::input('translations[name][' . $locale . ']', $location->translate_name($locale), array(
                                            'class' => 'form-input block w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5',
                                        )) ?>
                                    </div>
                                </div>
                                <div class="sm:col-span-4">
                                    <?= Form::label('translations_description_' . $locale, _e('Description'), array('class' => 'block text-sm font-medium leading-5 text-gray-700', 'for' => 'translations_description_' . $locale)) ?>
                                    <div class="mt-1 rounded-md shadow-sm">
                                        <?= FORM::textarea('translations[description][' . $locale . ']', $location->translate_description($locale), array(
                                            'rows' => 3,
                                            'cols' => 50,
                                            'class' => 'form-textarea block w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5',
                                            'id' => 'translations_description_' . $locale,
                                        )) ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <? endforeach ?>
                </div>
                <div>
                    <span class="inline-flex rounded-md shadow-sm">
                        <?=FORM::button('submit', __('Save'), ['type'=>'submit', 'class'=>'inline-flex justify-center py-2 px-4 border border-transparent text-sm leading-5 font-medium rounded-md text-white bg-blue-600 hover:bg-blue-500 focus:outline-none focus:border-blue-700 focus:shadow-outline-blue active:bg-blue-700 transition duration-150 ease-in-out'])?>
                    </span>
                </div>
            <?= Form::close() ?>
        </div>
    </div>
<? endif ?>

<?=FORM::open(Route::url('oc-panel',array('controller'=>'location','action'=>'icon','id'=>$form->object->id_location)), array('enctype'=>'multipart/form-data'))?>
    <div class="bg-white shadow sm:rounded-lg mt-8">
        <?=Form::errors()?>

        <div class="px-4 py-5 sm:p-6">
            <h3 class="text-lg leading-6 font-medium text-gray-900">
                <?=__('Upload location icon')?>
            </h3>
            <div class="mt-2 max-w-xl text-sm leading-5 text-gray-500">
                <p>
                    <?=__('Select a file')?>
                </p>
            </div>
            <?if (( $icon_src = $location->get_icon() )!==FALSE ):?>
                <div class="flex flex-wrap">
                    <div class="md:w-1/3 pr-4 pl-4">
                        <a class="thumbnail">
                            <img src="<?=$icon_src?>" class="img-rounded" alt="<?=__('Location icon')?>" height='200px'>
                        </a>
                    </div>
                </div>
                <?if (( $icon_src = $location->get_icon() )!==FALSE ):?>
                    <button type="submit"
                        class="inline-block align-middle text-center select-none border font-normal whitespace-no-wrap py-2 px-4 rounded text-base leading-normal  text-red-100 bg-red-500 hover:bg-red-400 index-delete index-delete-inline"
                        type="submit"
                        name="icon_delete"
                        value="1">
                        <?=__('Delete icon')?>
                    </button>
                <?endif?>
            <?endif?>
            <div class="mt-5 sm:flex sm:items-center">
                <div class="max-w-xs w-full">
                    <?=Form::label('location_icon', 'Location icon', ['class' => 'sr-only', 'for' => 'location_icon'])?>
                    <div class="relative rounded-md shadow-sm">
                        <input type="file" name="location_icon" id="location_icon" />
                    </div>
                </div>
                <span class="mt-3 w-ful inline-flex rounded-md shadow-sm sm:mt-0 sm:ml-3 sm:w-auto">
                    <?=Form::button('submit', __('Upload'), ['type'=>'submit', 'class'=>'w-full inline-flex items-center justify-center px-4 py-2 border border-transparent font-medium rounded-md text-white bg-blue-600 hover:bg-blue-500 focus:outline-none focus:border-blue-700 focus:shadow-outline-blue active:bg-blue-700 transition ease-in-out duration-150 sm:w-auto sm:text-sm sm:leading-5'])?>
                </span>
            </div>
        </div>
    </div>
<?=Form::close()?>

<div class="bg-white shadow sm:rounded-lg mt-8">
    <div class="px-4 py-5 sm:p-6">
        <h3 class="text-lg leading-6 font-medium text-gray-900">
            <?= __('Delete this :object', [':object' => __('location')]) ?>
        </h3>
        <div class="mt-2 max-w-xl text-sm leading-5 text-gray-500">
            <p>
                <?= __('You\'re about to delete ":object". This is permanent!', [':object' => $location->name]) ?>
            </p>
            <p class="mt-2">
                <?=__('We will move the siblings locations and ads to the parent of this location.')?>
            </p>
        </div>
        <div class="mt-5">
            <a href="<?=Route::url('oc-panel', ['controller'=> 'location', 'action'=>'delete','id'=>$location->id_location])?>" role="button" class="inline-flex items-center justify-center px-4 py-2 border border-transparent font-medium rounded-md text-red-700 bg-red-100 hover:bg-red-50 focus:outline-none focus:border-red-300 focus:shadow-outline-red active:bg-red-200 transition ease-in-out duration-150 sm:text-sm sm:leading-5">
                <?= __('Delete :object', [':object' => __('location')]) ?>
            </a>
        </div>
    </div>
</div>
