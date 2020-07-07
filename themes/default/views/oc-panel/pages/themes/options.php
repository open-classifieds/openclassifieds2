<?php defined('SYSPATH') or die('No direct script access.');?>

<?=Form::errors()?>

<?=Form::errors()?>

<div class="md:flex md:items-center md:justify-between">
    <div class="flex-1 min-w-0">
        <h2 class="text-2xl font-bold leading-7 text-gray-900 sm:text-3xl sm:leading-9 sm:truncate">
            <?=__('Theme Options')?> <?=(Request::current()->param('id')!==NULL)?Request::current()->param('id'):Theme::$theme?>
        </h2>

        <div class="mt-1 sm:mt-0">
            <?= View::factory('oc-panel/components/learn-more', ['url' => 'https://guides.yclas.com/#/Themes-how-to-change-a-theme']) ?>
        </div>
    </div>
</div>

<? if (Core::is_cloud() AND Model_Domain::current()->old_domain === NULL) : ?>
    <div class="alert alert-warning" role="alert">
        <?=__('If you want to use Google Adsense banners, they will not be displayed if you use our free domain Yclas.com')?>
        &nbsp;
        <a href="https://yclas.com/faq/custom-banners.html" target="_blank">
            <?=__('Read more')?> <i class="fa fa-external-link"></i>
        </a>
    </div>
<? endif ?>

<div class="bg-white overflow-hidden shadow rounded-lg mt-8">
    <div class="px-4 py-5 sm:p-6">
        <?=FORM::open(URL::base() . Request::current()->uri(), ['enctype' => 'multipart/form-data'])?>
            <?  //get field categories
                $field_categories = array();
                foreach ($options as $field => $attributes)
                {
                    if (isset($attributes['category']) AND ! in_array($attributes['category'], $field_categories))
                        $field_categories[] = $attributes['category'];
                }
            ?>
            <?if (! empty($field_categories)):?>
                <? $i = 1; foreach ($field_categories as $key => $field_category):?>
                    <div class="<?= $i > 1 ? 'mt-8 border-t border-gray-200 pt-8' : '' ?>">
                        <h3 class="text-lg leading-6 font-medium text-gray-900">
                            <?=__(':name Options', [':name' => $field_category])?>
                        </h3>
                    </div>
                    <div class="mt-6 grid grid-cols-1 row-gap-6 col-gap-4 sm:grid-cols-6">
                        <?foreach ($options as $field => $attributes):?>
                            <?if (isset($attributes['category']) AND $attributes['category'] == $field_category):?>
                                <?if ($attributes['display'] == 'hidden'):?>
                                    <?=Form::form_tag($field, $attributes, (isset($data[$field])) ? $data[$field] : NULL)?>
                                <? else : ?>
                                    <div class="sm:col-span-4">
                                        <?=Form::form_tag($field, $attributes, (isset($data[$field])) ? $data[$field] : NULL)?>
                                    </div>
                                <? endif ?>
                            <?endif?>
                        <?endforeach?>
                    </div>
                <? $i++; endforeach?>
            <? else : ?>
                <div class="mt-6 grid grid-cols-1 row-gap-6 col-gap-4 sm:grid-cols-6">
                    <?foreach ($options as $field => $attributes):?>
                        <div class="sm:col-span-4">
                            <?=FORM::form_tag($field, $attributes, (isset($data[$field]))?$data[$field]:NULL)?>
                        </div>
                    <?endforeach?>
                </div>
            <? endif ?>
            <div class="mt-8 border-t border-gray-200 pt-5">
                <div class="flex justify-end">
                    <span class="inline-flex rounded-md shadow-sm">
                        <a href="<?= Route::url('oc-panel', ['controller' => 'design']) ?>" role="button" class="py-2 px-4 border border-gray-300 rounded-md text-sm leading-5 font-medium text-gray-700 hover:text-gray-500 focus:outline-none focus:border-blue-300 focus:shadow-outline-blue active:bg-gray-50 active:text-gray-800 transition duration-150 ease-in-out">
                            <?= __('Cancel') ?>
                        </a>
                    </span>
                    <span class="ml-3 inline-flex rounded-md shadow-sm">
                        <?=FORM::button('submit', __('Save'), ['type'=>'submit', 'class'=>'inline-flex justify-center py-2 px-4 border border-transparent text-sm leading-5 font-medium rounded-md text-white bg-blue-600 hover:bg-blue-500 focus:outline-none focus:border-blue-700 focus:shadow-outline-blue active:bg-blue-700 transition duration-150 ease-in-out'])?>
                    </span>
                </div>
            </div>
        <?= Form::close() ?>
    </div>
</div>
