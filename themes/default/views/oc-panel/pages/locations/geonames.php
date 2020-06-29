<?php defined('SYSPATH') or die('No direct script access.');?>

<div class="md:flex md:items-center md:justify-between">
    <div class="flex-1 min-w-0">
        <h2 class="text-2xl font-bold leading-7 text-gray-900 sm:text-3xl sm:leading-9 sm:truncate">
            <?=($location AND $location->id_location > 1) ? $location->translate_name().' – ':NULL?> <?=__('Import Locations')?>
        </h2>
    </div>
</div>

<div class="bg-white overflow-hidden shadow rounded-lg mt-8">
    <div class="px-4 py-5 sm:p-6">
        <?= FORM::open(Route::url('oc-panel',array('controller'=>'location','action'=>'geonames')).'?id_location='.Core::get('id_location', 1), array('id'=>'auto_locations_form', 'enctype'=>'multipart/form-data'))?>
            <div class="grid grid-cols-1 row-gap-6 col-gap-4 sm:grid-cols-6">
                <div class="sm:col-span-4" id="group-continent">
                    <?= FORM::label('continent', __('Continent'), array('class'=>'block text-sm font-medium leading-5 text-gray-700', 'data-action' => __('Import continents')))?>
                    <div class="mt-1 rounded-md shadow-sm">
                        <select name="continent" id="continent" onchange="getPlaces(this.value,'country');" class="form-select block w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5">
                            <option value=""></option>
                        </select>
                    </div>
                </div>
                <div class="sm:col-span-4" id="group-country">
                    <?= FORM::label('country', __('Country'), array('class'=>'block text-sm font-medium leading-5 text-gray-700', 'data-action' => __('Import countries')))?>
                    <div class="mt-1 rounded-md shadow-sm">
                        <select name="country" id="country" onchange="getPlaces(this.value,'province');" class="form-select block w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5">
                            <option value=""></option>
                        </select>
                    </div>
                </div>
                <div class="sm:col-span-4" id="group-province">
                    <?= FORM::label('province', __('State/Province'), array('class'=>'block text-sm font-medium leading-5 text-gray-700', 'data-action' => __('Import states/provinces')))?>
                    <div class="mt-1 rounded-md shadow-sm">
                        <select name="province" id="province" onchange="getPlaces(this.value,'region');" class="form-select block w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5">
                            <option value=""></option>
                        </select>
                    </div>
                </div>
                <div class="sm:col-span-4" id="group-region">
                    <?= FORM::label('region', __('Region'), array('class'=>'block text-sm font-medium leading-5 text-gray-700', 'data-action' => __('Import counties/regions')))?>
                    <div class="mt-1 rounded-md shadow-sm">
                        <select name="region" id="region" onchange="getPlaces(this.value,'city');" class="form-select block w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5">
                            <option value=""></option>
                        </select>
                    </div>
                </div>
                <div class="sm:col-span-4" id="group-city">
                    <?= FORM::label('city', __('City'), array('class'=>'block text-sm font-medium leading-5 text-gray-700', 'data-action' => __('Import cities')))?>
                    <div class="mt-1 rounded-md shadow-sm">
                        <select name="city" id="city" class="form-select block w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5">
                            <option value=""></option>
                        </select>
                    </div>
                </div>

                <?if($location AND $location->id_geoname AND $location->fcodename_geoname):?>
                    <input type="hidden" id="current_location_id_geoname" value="<?=$location->id_geoname?>" name="current_location_id_geoname">
                    <input type="hidden" id="current_location_fcodename_geoname" value="<?=$location->fcodename_geoname?>" name="current_location_fcodename_geoname">
                <?endif?>
                <input type="hidden" id="auto_locations" value="" name="geonames_locations">
                <input type="hidden" id="auto_locations_lang" value="<?=substr(Core::config('i18n.locale'), 0, -3)?>" name="auto_locations_lang">
            </div>
            <div class="mt-8 border-t border-gray-200 pt-5">
                <div class="flex">
                    <span class="inline-flex rounded-md shadow-sm">
                        <?=FORM::button('submit', __('Save'), ['type'=>'submit', 'id'=>'auto_locations_import', 'class'=>'inline-flex justify-center py-2 px-4 border border-transparent text-sm leading-5 font-medium rounded-md text-white bg-blue-600 hover:bg-blue-500 focus:outline-none focus:border-blue-700 focus:shadow-outline-blue active:bg-blue-700 transition duration-150 ease-in-out'])?>
                    </span>
                    <span class="ml-3 inline-flex rounded-md shadow-sm">
                        <?= FORM::button('reset', __('Reset'), array('type'=>'button', 'id'=>'auto_locations_import_reset', 'class'=>'py-2 px-4 border border-gray-300 rounded-md text-sm leading-5 font-medium text-gray-700 hover:text-gray-500 focus:outline-none focus:border-blue-300 focus:shadow-outline-blue active:bg-gray-50 active:text-gray-800 transition duration-150 ease-in-out', 'id'=>'auto_locations_import_reset'))?>
                    </span>
                </div>
            </div>
        <?= Form::close() ?>
    </div>
</div>
