<div class="md:flex md:items-center md:justify-between">
    <div class="flex-1 min-w-0">
        <h2 class="text-2xl font-bold leading-7 text-gray-900 sm:text-3xl sm:leading-9 sm:truncate">
            <?= __('Google Maps') ?>
        </h2>

        <div class="mt-1 sm:mt-0">
            <?= View::factory('oc-panel/components/learn-more', ['url' => 'https://guides.yclas.com/#/Publish-options-configure-google-maps-settings']) ?>
        </div>
    </div>
</div>

<?= Form::open(Route::url('oc-panel/integrations', ['controller' => 'googlemaps'])) ?>
    <div class="bg-white shadow sm:rounded-lg mt-8">
        <div class="px-4 py-5 sm:p-6">
            <div>
                <div class="grid grid-cols-1 row-gap-6 col-gap-4 sm:grid-cols-6">
                    <div class="sm:col-span-4">
                        <?= FORM::label('gm_api_key', __('Google Maps API Key'), array('class'=>'block text-sm font-medium leading-5 text-gray-700'))?>
                        <div class="mt-1 rounded-md shadow-sm">
                            <?= FORM::input('gm_api_key', Core::post('gm_api_key', Core::config('advertisement.gm_api_key')), [
                                'class' => 'form-input block w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5',
                            ])?>
                        </div>
                        <p class="mt-2 text-sm text-gray-500">
                            <?=__("Google maps API Key")?>
                        </p>
                    </div>
                    <div class="sm:col-span-4">
                        <?= FORM::label('map_style', __('Google map style'), array('class'=>'block text-sm font-medium leading-5 text-gray-700'))?>
                        <div class="mt-1 rounded-md shadow-sm">
                            <?= FORM::select('map_style', $map_styles, Core::post('map_style', Core::config('advertisement.map_style')), array(
                                'class' => 'form-select block w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5',
                            ))?>
                        </div>
                        <p class="mt-2 text-sm text-gray-500">
                            <?=__("Custom Google Maps styling")?>
                        </p>
                    </div>
                    <div class="sm:col-span-3">
                        <?= FORM::label('map_zoom', __('Google map zoom level'), array('class'=>'block text-sm font-medium leading-5 text-gray-700'))?>
                        <div class="mt-1 rounded-md shadow-sm">
                            <?= FORM::input('map_zoom', Core::post('map_zoom', Core::config('advertisement.map_zoom')), [
                                'class' => 'form-input block w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5',
                                'type' => 'number',
                                'placeholder' => '16',
                            ])?>
                        </div>
                        <p class="mt-2 text-sm text-gray-500">
                            <?=__("Google map default zoom level")?>
                        </p>
                    </div>
                    <div class="sm:col-span-3">
                        <?= FORM::label('center_lat', __('Map latitude coordinates'), array('class'=>'block text-sm font-medium leading-5 text-gray-700'))?>
                        <div class="mt-1 rounded-md shadow-sm">
                            <?= FORM::input('center_lat', Core::post('center_lat', Core::config('advertisement.center_lat')), [
                                'class' => 'form-input block w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5',
                                'placeholder' => '40',
                            ])?>
                        </div>
                        <p class="mt-2 text-sm text-gray-500">
                            <?=__("Google map default latitude coordinates")?>
                        </p>
                    </div>
                    <div class="sm:col-span-3">
                        <?= FORM::label('center_lon', __('Map longitude coordinates'), array('class'=>'block text-sm font-medium leading-5 text-gray-700'))?>
                        <div class="mt-1 rounded-md shadow-sm">
                            <?= FORM::input('center_lon', Core::post('center_lon', Core::config('advertisement.center_lon')), [
                                'class' => 'form-input block w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5',
                                'placeholder' => '3',
                            ])?>
                        </div>
                        <p class="mt-2 text-sm text-gray-500">
                            <?=__("Google map default longitude coordinates")?>
                        </p>
                    </div>
                </div>
            </div>
            <div class="mt-8 border-t border-gray-200 pt-8">
                <div class="grid grid-cols-1 row-gap-6 col-gap-4 sm:grid-cols-6">
                    <div class="sm:col-span-3">
                        <div class="absolute flex items-center h-5">
                            <?=FORM::checkbox('auto_locate', 1, (bool) Core::post('auto_locate', Core::config('general.auto_locate')), ['class' => 'form-checkbox h-4 w-4 text-blue-600 transition duration-150 ease-in-out'])?>
                        </div>
                        <div class="pl-7 text-sm leading-5">
                            <?=FORM::label('auto_locate', __('Auto Locate Visitors'), ['class'=>'font-medium text-gray-700'])?>
                            <p class="text-gray-500"><?= __('Get the geographical position of a user. Requires setting up SSL on your website.') ?></p>
                        </div>
                    </div>
                    <div class="sm:col-span-3">
                        <?= FORM::label('auto_locate_distance', __('Auto locate distance'), array('class'=>'block text-sm font-medium leading-5 text-gray-700'))?>
                        <div class="mt-1 rounded-md shadow-sm">
                            <?= FORM::input('auto_locate_distance', Core::post('auto_locate_distance', Core::config('advertisement.auto_locate_distance')), [
                                'class' => 'form-input block w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5',
                                'placeholder' => '100',
                            ])?>
                        </div>
                        <p class="mt-2 text-sm text-gray-500">
                            <?=__("Sets maximum distance of closest suggested locations to the visitor.")?>
                        </p>
                    </div>
                </div>
            </div>
            <div class="mt-8 border-t border-gray-200 pt-8">
                <div class="grid grid-cols-1 row-gap-6 col-gap-4 sm:grid-cols-6">
                    <div class="sm:col-span-3">
                        <?= FORM::label('homepage_map', __('Homepage Map'), array('class'=>'block text-sm font-medium leading-5 text-gray-700'))?>
                        <div class="mt-1 rounded-md shadow-sm">
                            <?= FORM::select('homepage_map', $homepage_positions, Core::post('homepage_map', Core::config('advertisement.homepage_map')), array(
                                'class' => 'form-select block w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5',
                            ))?>
                        </div>
                        <p class="mt-2 text-sm text-gray-500">
                            <?=__('Select where to show the map in the homepage.')?>
                        </p>
                    </div>
                    <div class="sm:col-span-3">
                        <?= FORM::label('homepage_map_height', __('Homepage Map height'), array('class'=>'block text-sm font-medium leading-5 text-gray-700'))?>
                        <div class="mt-1 rounded-md shadow-sm">
                            <?= FORM::input('homepage_map_height', Core::post('homepage_map_height', Core::config('advertisement.homepage_map_height')), [
                                'class' => 'form-input block w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5',
                                'type' => 'number',
                                'placeholder' => '400',
                            ])?>
                        </div>
                        <p class="mt-2 text-sm text-gray-500">
                            <?=__("Enter the height of the homepage map.")?>
                        </p>
                    </div>
                    <div class="sm:col-span-6">
                        <div class="absolute flex items-center h-5">
                            <?=FORM::checkbox('homepage_map_allowfullscreen', 1, (bool) Core::post('homepage_map_allowfullscreen', Core::config('advertisement.homepage_map_allowfullscreen')), ['class' => 'form-checkbox h-4 w-4 text-blue-600 transition duration-150 ease-in-out'])?>
                        </div>
                        <div class="pl-7 text-sm leading-5">
                            <?=FORM::label('homepage_map_allowfullscreen', __('Homepage Map full screen option'), ['class'=>'font-medium text-gray-700'])?>
                            <p class="text-gray-500"><?= __('Enable full screen option.') ?>.</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="mt-8 border-t border-gray-200 pt-8">
                <div class="grid grid-cols-1 row-gap-6 col-gap-4 sm:grid-cols-6">
                    <div class="sm:col-span-3">
                        <div class="absolute flex items-center h-5">
                            <?=FORM::checkbox('map_pub_new', 1, (bool) Core::post('map_pub_new', Core::config('advertisement.map_pub_new')), ['class' => 'form-checkbox h-4 w-4 text-blue-600 transition duration-150 ease-in-out'])?>
                        </div>
                        <div class="pl-7 text-sm leading-5">
                            <?=FORM::label('map_pub_new', __('Google Maps in Publish New'), ['class'=>'font-medium text-gray-700'])?>
                            <p class="text-gray-500"><?= __('Displays the google maps in the Publish new form.') ?></p>
                        </div>
                    </div>
                    <div class="sm:col-span-3">
                        <div class="absolute flex items-center h-5">
                            <?=FORM::checkbox('map', 1, (bool) Core::post('map', Core::config('advertisement.map')), ['class' => 'form-checkbox h-4 w-4 text-blue-600 transition duration-150 ease-in-out'])?>
                        </div>
                        <div class="pl-7 text-sm leading-5">
                            <?=FORM::label('map', __('Google Maps in Ad and Profile page'), ['class'=>'font-medium text-gray-700'])?>
                            <p class="text-gray-500"><?= __('Displays the google maps in the Ad.') ?></p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="mt-8 border-t border-gray-200 pt-5">
                <span class="inline-flex rounded-md shadow-sm">
                    <?=FORM::button('submit', __('Save'), ['type'=>'submit', 'class'=>'inline-flex justify-center py-2 px-4 border border-transparent text-sm leading-5 font-medium rounded-md text-white bg-blue-600 hover:bg-blue-500 focus:outline-none focus:border-blue-700 focus:shadow-outline-blue active:bg-blue-700 transition duration-150 ease-in-out'])?>
                </span>
            </div>
        </div>
    </div>
<?= Form::close() ?>
