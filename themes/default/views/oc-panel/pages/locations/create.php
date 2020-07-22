<?php defined('SYSPATH') or die('No direct script access.');?>

<div class="md:flex md:items-center md:justify-between">
    <div class="flex-1 min-w-0">
        <h2 class="text-2xl font-bold leading-7 text-gray-900 sm:text-3xl sm:leading-9 sm:truncate">
            <?= __('New location') ?>
        </h2>
    </div>
</div>

<div class="bg-white overflow-hidden shadow rounded-lg mt-8">
    <div class="px-4 py-5 sm:p-6">
        <div class="grid sm:grid-cols-2 sm:gap-8">
            <?= FORM::open(Route::url('oc-panel',['controller'=>'location','action'=>'create']), ['class'=>'form-horizontal', 'enctype'=>'multipart/form-data'])?>
                <div>
                    <div>
                        <div class="grid grid-cols-1 row-gap-6 col-gap-4 sm:grid-cols-6">
                            <div class="sm:col-span-6">
                                <?= FORM::label('name', __('Name'), array('class'=>'block text-sm font-medium leading-5 text-gray-700'))?>
                                <div class="mt-1 rounded-md shadow-sm">
                                    <?= FORM::input('name', Core::post('name'), [
                                        'class' => 'form-input block w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5',
                                    ])?>
                                </div>
                            </div>
                            <div class="sm:col-span-6">
                                <?= FORM::label('id_location_parent', __('Parent'), array('class'=>'block text-sm font-medium leading-5 text-gray-700'))?>
                                <div class="mt-1 rounded-md shadow-sm">
                                    <?= FORM::select('id_location_parent', $locations, core::request('id_location_parent'), array(
                                        'class' => 'form-select block w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5',
                                    ))?>
                                </div>
                            </div>
                            <div class="sm:col-span-6">
                                <?= FORM::label('seoname', __('Seoname'), array('class'=>'block text-sm font-medium leading-5 text-gray-700'))?>
                                <div class="mt-1 rounded-md shadow-sm">
                                    <?= FORM::input('seoname', Core::request('seoname'), [
                                        'class' => 'form-input block w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5',
                                    ])?>
                                </div>
                            </div>
                            <div class="sm:col-span-6">
                                <?= FORM::label('description', __('Description'), array('class'=>'block text-sm font-medium leading-5 text-gray-700'))?>
                                <div class="mt-1 rounded-md shadow-sm">
                                    <?= FORM::textarea('description', Core::request('description'), [
                                        'x-data' => '',
                                        'x-init' => '$($refs.textarea).summernote(summernoteSettings())',
                                        'x-ref' => 'textarea',
                                        'class' => 'form-textarea block w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5',
                                    ])?>
                                </div>
                            </div>
                            <div class="sm:col-span-6">
                                <?= FORM::label('latitude', __('Latitude'), array('class'=>'block text-sm font-medium leading-5 text-gray-700'))?>
                                <div class="mt-1 rounded-md shadow-sm">
                                    <?= FORM::input('latitude', Core::request('latitude'), [
                                        'class' => 'form-input block w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5',
                                    ])?>
                                </div>
                            </div>
                            <div class="sm:col-span-6">
                                <?= FORM::label('longitude', __('Longitude'), array('class'=>'block text-sm font-medium leading-5 text-gray-700'))?>
                                <div class="mt-1 rounded-md shadow-sm">
                                    <?= FORM::input('longitude', Core::request('longitude'), [
                                        'class' => 'form-input block w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5',
                                    ])?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-8 border-t border-gray-200 pt-5">
                    <div class="flex">
                        <span class="inline-flex rounded-md shadow-sm">
                            <?=FORM::button('submit', __('Save'), ['type'=>'submit', 'class'=>'inline-flex justify-center py-2 px-4 border border-transparent text-sm leading-5 font-medium rounded-md text-white bg-blue-600 hover:bg-blue-500 focus:outline-none focus:border-blue-700 focus:shadow-outline-blue active:bg-blue-700 transition duration-150 ease-in-out'])?>
                        </span>
                    </div>
                </div>
            <?= Form::close() ?>
            <div>
                <div>
                    <div>
                        <div class="grid grid-cols-1 row-gap-6 col-gap-4 sm:grid-cols-6">
                            <div class="sm:col-span-6">
                                <div class="mt-6 rounded-md shadow-sm">
                                    <?= FORM::input('address', Core::post('address'), [
                                        'placeholder' => 'Type address',
                                        'id' => 'address',
                                        'class' => 'form-input block w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5',
                                    ])?>
                                </div>
                                <div class="mt-2 text-sm text-gray-500">
                                    <div class="popin-map-container">
                                        <div class="map-inner" id="map"
                                            data-lat="<?=core::config('advertisement.center_lat')?>"
                                            data-lon="<?=core::config('advertisement.center_lon')?>"
                                            data-zoom="<?=core::config('advertisement.map_zoom')?>"
                                            style="height:200px;width:100%">
                                        </div>
                                    </div>
                                    <ul class="inline">
                                        <li><?=__('Latitude')?>: <span id="preview_lat">0</span></li>
                                        <li><?=__('Longitude')?>: <span id="preview_lon">0</span></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
