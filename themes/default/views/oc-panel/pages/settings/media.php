<?php defined('SYSPATH') or die('No direct script access.');?>

<?=Form::errors()?>

<div class="md:flex md:items-center md:justify-between">
    <div class="flex-1 min-w-0">
        <h2 class="text-2xl font-bold leading-7 text-gray-900 sm:text-3xl sm:leading-9 sm:truncate">
            <?= __('Media settings') ?>
        </h2>

        <div class="mt-1 sm:mt-0">
            <?= View::factory('oc-panel/components/learn-more', ['url' => 'https://guides.yclas.com/#/Media-settings']) ?>
        </div>
    </div>
</div>

<div class="bg-white overflow-hidden shadow rounded-lg mt-8">
    <div class="px-4 py-5 sm:p-6">
        <?=FORM::open(Route::url('oc-panel/settings',['controller'=>'media']))?>
            <div>
                <div>
                    <div>
                        <h3 class="text-lg leading-6 font-medium text-gray-900">
                            <?= __('Media') ?>
                        </h3>
                    </div>
                    <div class="mt-6 grid grid-cols-1 row-gap-6 col-gap-4 sm:grid-cols-6">
                        <div class="sm:col-span-4">
                            <?= FORM::label('allowed_formats', __('Allowed image formats'), array('class'=>'block text-sm font-medium leading-5 text-gray-700'))?>
                            <div class="mt-1 rounded-md shadow-sm">
                                <?=FORM::select('allowed_formats[]', array('jpeg'=>'jpeg','jpg'=>'jpg','png'=>'png','webp'=>'webp','gif'=>'gif'), explode(',', Core::config('image.allowed_formats')), array(
                                    'class' => 'form-multiselect block w-full sm:text-sm sm:leading-5',
                                    'multiple' => true
                                ))?>
                            </div>
                            <p class="mt-2 text-sm text-gray-500">
                                <?=__('Set this up to restrict image formats that are being uploaded to your server.')?>
                            </p>
                        </div>
                        <div class="sm:col-span-3">
                            <?= FORM::label('max_image_size', __('Max image size'), array('class'=>'block text-sm font-medium leading-5 text-gray-700'))?>
                            <div class="mt-1 flex rounded-md shadow-sm">
                                <?= FORM::input('max_image_size', Core::post('max_image_size', Core::config('image.max_image_size')), [
                                    'placeholder' => '5',
                                    'type' => 'number',
                                    'class' => 'form-input block w-full rounded-none rounded-l-md transition duration-150 ease-in-out sm:text-sm sm:leading-5',
                                ])?>
                                <span class="inline-flex items-center px-3 rounded-r-md border border-l-0 border-gray-300 bg-gray-50 text-gray-500 sm:text-sm">
                                    MB
                                </span>
                            </div>
                            <p class="mt-2 text-sm text-gray-500">
                                <?=__('Control the size of images being uploaded. Enter an integer value to set maximum image size in mega bites(Mb).')?>
                            </p>
                        </div>
                        <div class="sm:col-span-3">
                            <?= FORM::label('quality', __('Image quality'), array('class'=>'block text-sm font-medium leading-5 text-gray-700'))?>
                            <div class="mt-1 flex rounded-md shadow-sm">
                                <?= FORM::input('quality', Core::post('quality', Core::config('image.quality')), [
                                    'placeholder' => '95',
                                    'type' => 'number',
                                    'class' => 'form-input block w-full rounded-none rounded-l-md transition duration-150 ease-in-out sm:text-sm sm:leading-5',
                                ])?>
                                <span class="inline-flex items-center px-3 rounded-r-md border border-l-0 border-gray-300 bg-gray-50 text-gray-500 sm:text-sm">
                                    %
                                </span>
                            </div>
                            <p class="mt-2 text-sm text-gray-500">
                                <?=__('Choose the quality of the stored images (1-100% of the original).')?>
                            </p>
                        </div>
                        <div class="sm:col-span-3">
                            <?= FORM::label('height', __('Image height'), array('class'=>'block text-sm font-medium leading-5 text-gray-700'))?>
                            <div class="mt-1 flex rounded-md shadow-sm">
                                <?= FORM::input('height', Core::post('height', Core::config('image.height')), [
                                    'placeholder' => '700',
                                    'type' => 'number',
                                    'class' => 'form-input block w-full rounded-none rounded-l-md transition duration-150 ease-in-out sm:text-sm sm:leading-5',
                                ])?>
                                <span class="inline-flex items-center px-3 rounded-r-md border border-l-0 border-gray-300 bg-gray-50 text-gray-500 sm:text-sm">
                                    px
                                </span>
                            </div>
                            <p class="mt-2 text-sm text-gray-500">
                                <?=__('Each image is resized when uploaded. This is the height of big image. Note: you can leave this field blank to set AUTO height resize.')?>
                            </p>
                        </div>
                        <div class="sm:col-span-3">
                            <?= FORM::label('width', __('Image width'), array('class'=>'block text-sm font-medium leading-5 text-gray-700'))?>
                            <div class="mt-1 flex rounded-md shadow-sm">
                                <?= FORM::input('width', Core::post('width', Core::config('image.width')), [
                                    'placeholder' => '1024',
                                    'type' => 'number',
                                    'class' => 'form-input block w-full rounded-none rounded-l-md transition duration-150 ease-in-out sm:text-sm sm:leading-5',
                                ])?>
                                <span class="inline-flex items-center px-3 rounded-r-md border border-l-0 border-gray-300 bg-gray-50 text-gray-500 sm:text-sm">
                                    px
                                </span>
                            </div>
                            <p class="mt-2 text-sm text-gray-500">
                                <?=__('Each image is resized when uploaded. This is the width of big image.')?>
                            </p>
                        </div>
                        <div class="sm:col-span-3">
                            <?= FORM::label('height_thumb', __('Thumb height'), array('class'=>'block text-sm font-medium leading-5 text-gray-700'))?>
                            <div class="mt-1 flex rounded-md shadow-sm">
                                <?= FORM::input('height_thumb', Core::post('height_thumb', Core::config('image.height_thumb')), [
                                    'placeholder' => '200',
                                    'type' => 'number',
                                    'class' => 'form-input block w-full rounded-none rounded-l-md transition duration-150 ease-in-out sm:text-sm sm:leading-5',
                                ])?>
                                <span class="inline-flex items-center px-3 rounded-r-md border border-l-0 border-gray-300 bg-gray-50 text-gray-500 sm:text-sm">
                                    px
                                </span>
                            </div>
                            <p class="mt-2 text-sm text-gray-500">
                                <?=__('Thumb is a small image resized to fit certain elements. This is the height for this image.')?>
                            </p>
                        </div>
                        <div class="sm:col-span-3">
                            <?= FORM::label('width_thumb', __('Thumb width'), array('class'=>'block text-sm font-medium leading-5 text-gray-700'))?>
                            <div class="mt-1 flex rounded-md shadow-sm">
                                <?= FORM::input('width_thumb', Core::post('width_thumb', Core::config('image.width_thumb')), [
                                    'placeholder' => '200',
                                    'type' => 'number',
                                    'class' => 'form-input block w-full rounded-none rounded-l-md transition duration-150 ease-in-out sm:text-sm sm:leading-5',
                                ])?>
                                <span class="inline-flex items-center px-3 rounded-r-md border border-l-0 border-gray-300 bg-gray-50 text-gray-500 sm:text-sm">
                                    px
                                </span>
                            </div>
                            <p class="mt-2 text-sm text-gray-500">
                                <?=__('Thumb is a small image resized to fit certain elements. This is width of this image.')?>
                            </p>
                        </div>
                        <? if (Core::is_cloud()) : ?>
                            <div class="sm:col-span-4">
                                <?= FORM::label('watermark_text', __('Watermark text'), array('class'=>'block text-sm font-medium leading-5 text-gray-700'))?>
                                <div class="mt-1 rounded-md shadow-sm">
                                    <?= FORM::input('watermark_text', Core::post('watermark_text', Core::config('image.watermark_text')), [
                                        'class' => 'form-input block w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5',
                                    ])?>
                                </div>
                                <p class="mt-2 text-sm text-gray-500">
                                    <?=__('Text to use as watermark')?>
                                </p>
                            </div>
                            <div class="sm:col-span-4">
                                <?= FORM::label('watermark_text_size', __('Watermark text size'), array('class'=>'block text-sm font-medium leading-5 text-gray-700'))?>
                                <div class="mt-1 rounded-md shadow-sm">
                                    <?= FORM::input('watermark_text_size', Core::post('watermark_text_size', Core::config('image.watermark_text_size')), [
                                        'placeholder' => Core::config('image.watermark_text_size'),
                                        'class' => 'form-input block w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5',
                                    ])?>
                                </div>
                            </div>
                            <div class="sm:col-span-4">
                                <?= FORM::label('watermark_text_color', __('Watermark text color'), array('class'=>'block text-sm font-medium leading-5 text-gray-700'))?>
                                <div class="mt-1 rounded-md shadow-sm">
                                    <?= FORM::input('watermark_text_color', Core::post('watermark_text_color', Core::config('image.watermark_text_color')), [
                                        'class' => 'form-input block w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5',
                                    ])?>
                                </div>
                                <p class="mt-2 text-sm text-gray-500">
                                    <?=__('Text color to use as watermark')?>
                                </p>
                            </div>
                            <div class="sm:col-span-4">
                                <?= FORM::label('watermark_bg_color', __('Watermark background color'), array('class'=>'block text-sm font-medium leading-5 text-gray-700'))?>
                                <div class="mt-1 rounded-md shadow-sm">
                                    <?= FORM::input('watermark_bg_color', Core::post('watermark_bg_color', Core::config('image.watermark_bg_color')), [
                                        'class' => 'form-input block w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5',
                                    ])?>
                                </div>
                                <p class="mt-2 text-sm text-gray-500">
                                    <?=__('Background color to use as watermark')?>
                                </p>
                            </div>
                            <div class="sm:col-span-6">
                                <div class="absolute flex items-center h-5">
                                    <?=FORM::checkbox('watermark_bg_transparent', 1, (bool) Core::post('watermark_bg_transparent', Core::config('image.watermark_bg_transparent')), ['class' => 'form-checkbox h-4 w-4 text-blue-600 transition duration-150 ease-in-out'])?>
                                </div>
                                <div class="pl-7 text-sm leading-5">
                                    <?=FORM::label('watermark_bg_transparent', __('watermark_bg_transparent Mode'), ['class'=>'font-medium text-gray-700'])?>
                                    <p class="text-gray-500"><?= __('Background color to use as watermark') ?>.</p>
                                </div>
                            </div>
                        <? else : ?>
                            <div class="sm:col-span-4">
                                <?= FORM::label('watermark_path', __('Watermark path'), array('class'=>'block text-sm font-medium leading-5 text-gray-700'))?>
                                <div class="mt-1 rounded-md shadow-sm">
                                    <?= FORM::input('watermark_path', Core::post('watermark_path', Core::config('image.watermark_path')), [
                                        'placeholder' => 'images/watermark.png',
                                        'class' => 'form-input block w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5',
                                    ])?>
                                </div>
                                <p class="mt-2 text-sm text-gray-500">
                                    <?=__('Relative path to the image to use as watermark')?>
                                </p>
                            </div>
                        <? endif ?>
                        <div class="sm:col-span-6">
                            <div class="absolute flex items-center h-5">
                                <?=FORM::checkbox('watermark', 1, (bool) Core::post('watermark', Core::config('image.watermark')), ['class' => 'form-checkbox h-4 w-4 text-blue-600 transition duration-150 ease-in-out'])?>
                            </div>
                            <div class="pl-7 text-sm leading-5">
                                <?=FORM::label('watermark', __('Watermark'), ['class'=>'font-medium text-gray-700'])?>
                            </div>
                        </div>
                        <div class="sm:col-span-3">
                            <?= FORM::label('watermark_position', __('Watermark position'), array('class'=>'block text-sm font-medium leading-5 text-gray-700'))?>
                            <div class="mt-1 rounded-md shadow-sm">
                                <?=FORM::select('watermark_position', array(0=>"Center",1=>"Bottom",2=>"Top"), Core::config('image.watermark_position'), array(
                                    'class' => 'form-select block w-full sm:text-sm sm:leading-5',
                                ))?>
                            </div>
                        </div>
                        <div class="sm:col-span-6">
                            <div class="absolute flex items-center h-5">
                                <?=FORM::checkbox('disallow_nudes', 1, (bool) Core::post('disallow_nudes', Core::config('image.disallow_nudes')), ['class' => 'form-checkbox h-4 w-4 text-blue-600 transition duration-150 ease-in-out'])?>
                            </div>
                            <div class="pl-7 text-sm leading-5">
                                <?=FORM::label('disallow_nudes', __('Disallow nude pictures'), ['class'=>'font-medium text-gray-700'])?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="mt-8 border-t border-gray-200 pt-5">
                <div class="flex justify-end">
                    <span class="inline-flex rounded-md shadow-sm">
                        <a href="<?= Route::url('oc-panel', ['controller' => 'settings']) ?>" role="button" class="py-2 px-4 border border-gray-300 rounded-md text-sm leading-5 font-medium text-gray-700 hover:text-gray-500 focus:outline-none focus:border-blue-300 focus:shadow-outline-blue active:bg-gray-50 active:text-gray-800 transition duration-150 ease-in-out">
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
