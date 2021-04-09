<div class="md:flex md:items-center md:justify-between">
    <div class="flex-1 min-w-0">
        <h2 class="text-2xl font-bold leading-7 text-gray-900 sm:text-3xl sm:leading-9 sm:truncate">
            <?= __('2Factor') ?>
        </h2>
    </div>
</div>

<? if (! empty($errors)) : ?>
    <div class="mt-8">
        <?= View::factory('oc-panel/components/form-errors', ['errors' => $errors]) ?>
    </div>
<? endif ?>

<?= Form::open(Route::url('oc-panel/integrations', ['controller' => '2factorin'])) ?>
    <div class="bg-white shadow sm:rounded-lg mt-8">
        <div class="px-4 py-5 sm:p-6">
            <h3 class="text-base leading-5 font-medium text-gray-900">
                <?= __('Enable 2 Step SMS Authentication') ?>
            </h3>
            <div class="mt-2 sm:flex sm:items-start sm:justify-between">
                <div class="max-w-xl text-sm leading-5 text-gray-500">
                    <p>
                        <?= __('2 step SMS authentication.') ?>
                    </p>
                </div>
                <div class="mt-5 sm:mt-0 sm:ml-6 sm:flex-shrink-0 sm:flex sm:items-center">
                    <?=FORM::checkbox('is_active', 1, (bool) Core::post('is_active', $is_active), ['class' => 'form-checkbox h-6 w-6 text-blue-600 bg-gray-100 transition duration-150 ease-in-out'])?>
                </div>
            </div>
            <div class="mt-8 border-t border-gray-200 pt-8">
                <div class="grid grid-cols-1 row-gap-6 col-gap-4 sm:grid-cols-6">
                    <div class="sm:col-span-4">
                        <?= FORM::label('sms_2factorin_api', __('2Factor API Key'), array('class'=>'block text-sm font-medium leading-5 text-gray-700'))?>
                        <div class="mt-1 rounded-md shadow-sm">
                            <?= FORM::input('sms_2factorin_api', Core::post('sms_2factorin_api', Core::config('general.sms_2factorin_api')), [
                                'class' => 'form-input block w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5',
                                'id' => 'sms_2factorin_api',
                            ])?>
                        </div>
                        <p class="mt-2 text-sm text-gray-500">
                            <?=__("2Factor SMS API Key, needed for SMS Authentication to work.")?>
                        </p>
                    </div>
                </div>
            </div>
            <div class="mt-8 border-t border-gray-200 pt-8">
                <div>
                    <h3 class="text-lg leading-6 font-medium text-gray-900">
                        <?=__('Transactional SMS')?>
                    </h3>
                    <p class="mt-2 text-sm text-gray-500">
                        <a href="https://2fa.api-docs.io/v1/send-transactional-sms/create-new-sender-id-for-transactional-sms" class="font-medium text-blue-600 hover:text-blue-500 focus:outline-none focus:underline transition ease-in-out duration-150" target="_blank" rel="nofollow">
                            <?= __('Requesing custom sender Id for transactional messaging.') ?>
                        </a>
                    </p>
                </div>
                <div class="mt-6 grid grid-cols-1 row-gap-6 col-gap-4 sm:grid-cols-6">
                    <div class="sm:col-span-4">
                        <?= FORM::label('sms_2factorin_sender_id', 'Sender Id', ['class'=>'block text-sm font-medium leading-5 text-gray-700'])?>
                        <div class="mt-1 rounded-md shadow-sm">
                            <?= FORM::input('sms_2factorin_sender_id', Core::post('sms_2factorin_sender_id', Core::config('general.sms_2factorin_sender_id')), [
                                'class' => 'form-input block w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5',
                            ])?>
                        </div>
                        <p class="mt-2 text-sm text-gray-500">
                            <?=__('This is the name with which SMS it delivered to the end user.')?>
                        </p>
                    </div>
                    <div class="sm:col-span-4">
                        <?= FORM::label('sms_2factorin_subscription_payment_template', 'Subscription Payment Template Name', ['class'=>'block text-sm font-medium leading-5 text-gray-700'])?>
                        <div class="mt-1 rounded-md shadow-sm">
                            <?= FORM::input('sms_2factorin_subscription_payment_template', Core::post('sms_2factorin_subscription_payment_template', Core::config('general.sms_2factorin_subscription_payment_template')), [
                                'class' => 'form-input block w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5',
                            ])?>
                        </div>
                        <p class="mt-2 text-sm text-gray-500">
                            <?=__('SMS template name for the end-user notification once payment for a subscription is received.')?>
                        </p>
                    </div>
                    <div class="sm:col-span-4">
                        <?= FORM::label('sms_2factorin_expiring_subscription_template', 'Expiring Subscription Template Name', ['class'=>'block text-sm font-medium leading-5 text-gray-700'])?>
                        <div class="mt-1 rounded-md shadow-sm">
                            <?= FORM::input('sms_2factorin_expiring_subscription_template', Core::post('sms_2factorin_expiring_subscription_template', Core::config('general.sms_2factorin_expiring_subscription_template')), [
                                'class' => 'form-input block w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5',
                            ])?>
                        </div>
                        <p class="mt-2 text-sm text-gray-500">
                            <?=__('SMS template name for the end-user notification when the account expires in 2 days.')?>
                        </p>
                    </div>
                    <div class="sm:col-span-4">
                        <?= FORM::label('sms_2factorin_featured_ad_payment_template', 'Featured Ad Payment Template Name', ['class'=>'block text-sm font-medium leading-5 text-gray-700'])?>
                        <div class="mt-1 rounded-md shadow-sm">
                            <?= FORM::input('sms_2factorin_featured_ad_payment_template', Core::post('sms_2factorin_featured_ad_payment_template', Core::config('general.sms_2factorin_featured_ad_payment_template')), [
                                'class' => 'form-input block w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5',
                            ])?>
                        </div>
                        <p class="mt-2 text-sm text-gray-500">
                            <?=__('SMS template name for the end-user notification once payment for a featured ad is received.')?>
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
