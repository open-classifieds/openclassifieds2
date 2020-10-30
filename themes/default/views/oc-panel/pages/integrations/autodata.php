<div class="md:flex md:items-center md:justify-between">
    <div class="flex-1 min-w-0">
        <h2 class="text-2xl font-bold leading-7 text-gray-900 sm:text-3xl sm:leading-9 sm:truncate">
            <?= __('Auto-Data') ?>
        </h2>

        <div class="mt-1 sm:mt-0">
            <?= View::factory('oc-panel/components/learn-more', ['url' => 'https://guides.yclas.com/#/auto-data-api']) ?>
        </div>
    </div>
</div>

<?= Form::open(Route::url('oc-panel/integrations', ['controller' => 'autodata'])) ?>
    <div class="bg-white shadow sm:rounded-lg mt-8">
        <div class="px-4 py-5 sm:p-6">
            <h3 class="text-base leading-5 font-medium text-gray-900">
                <?= __('Enable Auto-Data') ?>
            </h3>
            <div class="mt-2 sm:flex sm:items-start sm:justify-between">
                <div class="max-w-xl text-sm leading-5 text-gray-500">
                    <p>
                        <?= __('Get vehicle data for your website.') ?>
                    </p>
                </div>
                <div class="mt-5 sm:mt-0 sm:ml-6 sm:flex-shrink-0 sm:flex sm:items-center">
                    <?=FORM::checkbox('is_active', 1, (bool) Core::post('is_active', $is_active), ['class' => 'form-checkbox h-6 w-6 text-blue-600 bg-gray-100 transition duration-150 ease-in-out'])?>
                </div>
            </div>
            <div class="mt-5 sm:flex sm:items-center">
                <span class="mt-3 w-ful inline-flex rounded-md shadow-sm sm:mt-0 sm:w-auto">
                    <?= Form::button('submit', __('Save'), ['type'=>'submit', 'class'=>'w-full inline-flex items-center justify-center px-4 py-2 border border-transparent font-medium rounded-md text-white bg-blue-600 hover:bg-blue-500 focus:outline-none focus:border-blue-700 focus:shadow-outline-blue active:bg-blue-700 transition ease-in-out duration-150 sm:w-auto sm:text-sm sm:leading-5'])?>
                </span>
            </div>
        </div>
    </div>
<?= Form::close() ?>

<? if (Core::is_selfhosted()) : ?>
    <?=FORM::open(Route::url('oc-panel/integrations', ['controller' => 'autodata', 'action' => 'import']), array('enctype'=>'multipart/form-data'))?>
        <div class="bg-white shadow sm:rounded-lg mt-8">
            <div class="px-4 py-5 sm:p-6">
                <h3 class="text-lg leading-6 font-medium text-gray-900">
                    <?=__('Import Data')?>
                </h3>
                <div class="mt-2 max-w-xl text-sm leading-5 text-gray-500">
                    <p>
                        <?=__('Please use the correct XML format from Auto-Data')?>. <a class="hover:underline" href="https://api.auto-data.net" target="_blank">Auto-Data API</a>.
                    </p>
                </div>
                <div class="mt-5 sm:flex sm:items-center">
                    <div class="max-w-xs w-full">
                        <?=Form::label('xml_file', __('xml_file'), ['class' => 'sr-only', 'for' => 'xml_file'])?>
                        <div class="relative rounded-md shadow-sm">
                            <input type="file" name="xml_file" id="xml_file" />
                        </div>
                    </div>
                    <span class="mt-3 w-ful inline-flex rounded-md shadow-sm sm:mt-0 sm:ml-3 sm:w-auto">
                        <?=Form::button('submit', __('Upload'), ['type'=>'submit', 'class'=>'w-full inline-flex items-center justify-center px-4 py-2 border border-transparent font-medium rounded-md text-white bg-blue-600 hover:bg-blue-500 focus:outline-none focus:border-blue-700 focus:shadow-outline-blue active:bg-blue-700 transition ease-in-out duration-150 sm:w-auto sm:text-sm sm:leading-5'])?>
                    </span>
                </div>
            </div>
        </div>
    <?=Form::close()?>
<? endif ?>
