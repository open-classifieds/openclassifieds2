<div class="md:flex md:items-center md:justify-between">
    <div class="flex-1 min-w-0">
        <h2 class="text-2xl font-bold leading-7 text-gray-900 sm:text-3xl sm:leading-9 sm:truncate">
            <?= __('Cloudinary') ?>
        </h2>

        <div class="mt-1 sm:mt-0">
            <?= View::factory('oc-panel/components/learn-more', ['url' => 'https://guides.yclas.com/#/Integrations-cloudinary']) ?>
        </div>
    </div>
</div>

<?= Form::open(Route::url('oc-panel/integrations', ['controller' => 'cloudinary'])) ?>
    <div class="bg-white shadow sm:rounded-lg mt-8">
        <div class="px-4 py-5 sm:p-6">
            <div>
                <div class="grid grid-cols-1 row-gap-6 col-gap-4 sm:grid-cols-6">
                    <div class="sm:col-span-4">
                        <?= FORM::label('cloudinary_api_key', __('Cloudinary API Key'), array('class'=>'block text-sm font-medium leading-5 text-gray-700'))?>
                        <div class="mt-1 rounded-md shadow-sm">
                            <?= FORM::input('cloudinary_api_key', Core::post('cloudinary_api_key', Core::config('advertisement.cloudinary_api_key')), [
                                'class' => 'form-input block w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5',
                            ])?>
                        </div>
                        <p class="mt-2 text-sm text-gray-500">
                            <?=__('Cloudinary API Key')?>
                        </p>
                    </div>
                    <div class="sm:col-span-4">
                        <?= FORM::label('cloudinary_api_secret', __('Cloudinary API Secret'), array('class'=>'block text-sm font-medium leading-5 text-gray-700'))?>
                        <div class="mt-1 rounded-md shadow-sm">
                            <?= FORM::input('cloudinary_api_secret', Core::post('cloudinary_api_secret', Core::config('advertisement.cloudinary_api_secret')), [
                                'class' => 'form-input block w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5',
                            ])?>
                        </div>
                        <p class="mt-2 text-sm text-gray-500">
                            <?=__('Cloudinary API Secret')?>
                        </p>
                    </div>
                    <div class="sm:col-span-4">
                        <?= FORM::label('cloudinary_cloud_name', __('Cloudinary Cloud Name'), array('class'=>'block text-sm font-medium leading-5 text-gray-700'))?>
                        <div class="mt-1 rounded-md shadow-sm">
                            <?= FORM::input('cloudinary_cloud_name', Core::post('cloudinary_cloud_name', Core::config('advertisement.cloudinary_cloud_name')), [
                                'class' => 'form-input block w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5',
                            ])?>
                        </div>
                        <p class="mt-2 text-sm text-gray-500">
                            <?=__('Cloudinary Cloud Name')?>
                        </p>
                    </div>
                    <div class="sm:col-span-4">
                        <?= FORM::label('cloudinary_cloud_preset', __('Cloudinary Cloud Preset'), array('class'=>'block text-sm font-medium leading-5 text-gray-700'))?>
                        <div class="mt-1 rounded-md shadow-sm">
                            <?= FORM::input('cloudinary_cloud_preset', Core::post('cloudinary_cloud_preset', Core::config('advertisement.cloudinary_cloud_preset')), [
                                'class' => 'form-input block w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5',
                            ])?>
                        </div>
                        <p class="mt-2 text-sm text-gray-500">
                            <?=__('Cloudinary Cloud Preset')?>
                        </p>
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
