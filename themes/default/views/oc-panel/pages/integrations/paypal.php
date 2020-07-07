<div class="md:flex md:items-center md:justify-between">
    <div class="flex-1 min-w-0">
        <h2 class="text-2xl font-bold leading-7 text-gray-900 sm:text-3xl sm:leading-9 sm:truncate">
            <?= __('Paypal') ?>
        </h2>

        <div class="mt-1 sm:mt-0">
            <?= View::factory('oc-panel/components/learn-more', ['url' => 'https://guides.yclas.com/#/Payment-pay-directly-from-the-ad-option']) ?>
        </div>
    </div>
</div>

<?= Form::open(Route::url('oc-panel/integrations', ['controller' => 'paypal'])) ?>
    <div class="bg-white shadow sm:rounded-lg mt-8">
        <div class="px-4 py-5 sm:p-6">
            <div>
                <div class="grid grid-cols-1 row-gap-6 col-gap-4 sm:grid-cols-6">
                    <div class="sm:col-span-4">
                        <?= FORM::label('paypal_account', 'Paypal account', array('class'=>'block text-sm font-medium leading-5 text-gray-700'))?>
                        <div class="mt-1 rounded-md shadow-sm">
                            <?= FORM::input('paypal_account', Core::post('paypal_account', Core::config('payment.paypal_account')), [
                                'class' => 'form-input block w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5',
                            ])?>
                        </div>
                        <p class="mt-2 text-sm text-gray-500">
                            <?=__('To include your custom HTML code (reference to JS or CSS files, etc.) in the footer of the rendered page.')?>
                        </p>
                    </div>
                    <div class="sm:col-span-6">
                        <div class="absolute flex items-center h-5">
                            <?=FORM::checkbox('sandbox', 1, (bool) Core::post('sandbox', Core::config('payment.sandbox')), ['class' => 'form-checkbox h-4 w-4 text-blue-600 transition duration-150 ease-in-out'])?>
                        </div>
                        <div class="pl-7 text-sm leading-5">
                            <?=FORM::label('sandbox', __('Sandbox'), ['class'=>'font-medium text-gray-700'])?>
                        </div>
                    </div>
                    <div class="sm:col-span-6">
                        <div class="absolute flex items-center h-5">
                            <?=FORM::checkbox('paypal_seller', 1, (bool) Core::post('paypal_seller', Core::config('payment.paypal_seller')), ['class' => 'form-checkbox h-4 w-4 text-blue-600 transition duration-150 ease-in-out'])?>
                        </div>
                        <div class="pl-7 text-sm leading-5">
                            <?=FORM::label('paypal_seller', __('Buy Now button'), ['class'=>'font-medium text-gray-700'])?>
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
