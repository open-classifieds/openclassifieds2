<div class="md:flex md:items-center md:justify-between">
    <div class="flex-1 min-w-0">
        <h2 class="text-2xl font-bold leading-7 text-gray-900 sm:text-3xl sm:leading-9 sm:truncate">
            <?= __('Stripe') ?>
        </h2>
    </div>
</div>

<?= Form::open(Route::url('oc-panel/integrations', ['controller' => 'stripe'])) ?>
    <div class="bg-white shadow sm:rounded-lg mt-8">
        <div class="px-4 py-5 sm:p-6">
            <div>
                <div class="grid grid-cols-1 row-gap-6 col-gap-4 sm:grid-cols-6">
                    <div class="sm:col-span-4">
                        <?= FORM::label('stripe_private', 'Stripe private key', array('class'=>'block text-sm font-medium leading-5 text-gray-700'))?>
                        <div class="mt-1 rounded-md shadow-sm">
                            <?= FORM::input('stripe_private', Core::post('stripe_private', Core::config('payment.stripe_private')), [
                                'class' => 'form-input block w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5',
                            ])?>
                        </div>
                    </div>
                    <div class="sm:col-span-4">
                        <?= FORM::label('stripe_public', 'Stripe public key', array('class'=>'block text-sm font-medium leading-5 text-gray-700'))?>
                        <div class="mt-1 rounded-md shadow-sm">
                            <?= FORM::input('stripe_public', Core::post('stripe_public', Core::config('payment.stripe_public')), [
                                'class' => 'form-input block w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5',
                            ])?>
                        </div>
                    </div>
                    <div class="sm:col-span-6">
                        <div class="absolute flex items-center h-5">
                            <?=FORM::checkbox('stripe_address', 1, (bool) Core::post('stripe_address', Core::config('payment.stripe_address')), ['class' => 'form-checkbox h-4 w-4 text-blue-600 transition duration-150 ease-in-out'])?>
                        </div>
                        <div class="pl-7 text-sm leading-5">
                            <?=FORM::label('stripe_address', __('Requires address to pay for extra security'), ['class'=>'font-medium text-gray-700'])?>
                        </div>
                    </div>
                    <div class="sm:col-span-6">
                        <div class="absolute flex items-center h-5">
                            <?=FORM::checkbox('stripe_alipay', 1, (bool) Core::post('stripe_alipay', Core::config('payment.stripe_alipay')), ['class' => 'form-checkbox h-4 w-4 text-blue-600 transition duration-150 ease-in-out'])?>
                        </div>
                        <div class="pl-7 text-sm leading-5">
                            <?=FORM::label('stripe_alipay', __('Accept Alipay payments'), ['class'=>'font-medium text-gray-700'])?>
                        </div>
                    </div>
                    <div class="sm:col-span-6">
                        <div class="absolute flex items-center h-5">
                            <?=FORM::checkbox('stripe_3d_secure1', 1, (bool) Core::post('stripe_3d_secure1', Core::config('payment.stripe_3d_secure1')), ['class' => 'form-checkbox h-4 w-4 text-blue-600 transition duration-150 ease-in-out'])?>
                        </div>
                        <div class="pl-7 text-sm leading-5">
                            <?=FORM::label('stripe_3d_secure1', __('Requires 3D security - BETA'), ['class'=>'font-medium text-gray-700'])?>
                        </div>
                    </div>
                    <div class="sm:col-span-6">
                        <div class="absolute flex items-center h-5">
                            <?=FORM::checkbox('stripe_legacy', 1, (bool) Core::post('stripe_legacy', Core::config('payment.stripe_legacy')), ['class' => 'form-checkbox h-4 w-4 text-blue-600 transition duration-150 ease-in-out'])?>
                        </div>
                        <div class="pl-7 text-sm leading-5">
                            <?=FORM::label('stripe_legacy', __('Legacy Checkout'), ['class'=>'font-medium text-gray-700'])?>
                        </div>
                    </div>
                </div>
            </div>
            <div class="mt-8 border-t border-gray-200 pt-8">
                <div>
                    <h3 class="text-lg leading-6 font-medium text-gray-900">
                        <?=__('Stripe Connect')?>
                        <?= View::factory('oc-panel/components/pro-badge') ?>
                    </h3>
                    <? if (Core::extra_features() == FALSE) : ?>
                        <?= View::factory('oc-panel/components/pro-alert') ?>
                    <? endif ?>
                </div>
                <div class="mt-6 grid grid-cols-1 row-gap-6 col-gap-4 sm:grid-cols-6">
                    <div class="sm:col-span-6">
                        <div class="absolute flex items-center h-5">
                            <?=FORM::checkbox('stripe_connect', 1, (bool) Core::post('stripe_connect', Core::config('payment.stripe_connect')), ['class' => 'form-checkbox h-4 w-4 text-blue-600 transition duration-150 ease-in-out'])?>
                        </div>
                        <div class="pl-7 text-sm leading-5">
                            <?=FORM::label('stripe_connect', __('Activate Stripe Connect'), ['class'=>'font-medium text-gray-700'])?>
                        </div>
                    </div>
                    <div class="sm:col-span-4">
                        <?= FORM::label('stripe_clientid', 'Stripe client id', array('class'=>'block text-sm font-medium leading-5 text-gray-700'))?>
                        <div class="mt-1 rounded-md shadow-sm">
                            <?= FORM::input('stripe_clientid', Core::post('stripe_clientid', Core::config('payment.stripe_clientid')), [
                                'class' => 'form-input block w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5',
                            ])?>
                        </div>
                        <p class="mt-2 text-sm text-gray-500">
                            <?=__("Stripe client id").' Redirect URL: '.Route::url('default', array('controller'=>'stripe','action'=>'connect','id'=>'now'))?>
                        </p>
                    </div>
                    <div class="sm:col-span-4">
                        <?= FORM::label('stripe_appfee', 'Application fee %', array('class'=>'block text-sm font-medium leading-5 text-gray-700'))?>
                        <div class="mt-1 rounded-md shadow-sm">
                            <?= FORM::input('stripe_appfee', Core::post('stripe_appfee', Core::config('payment.stripe_appfee')), [
                                'class' => 'form-input block w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5',
                            ])?>
                        </div>
                        <p class="mt-2 text-sm text-gray-500">
                            <?= __('How much you charge the seller in percentage.') ?>
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
