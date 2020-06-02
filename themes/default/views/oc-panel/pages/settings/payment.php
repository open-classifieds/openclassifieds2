<?php defined('SYSPATH') or die('No direct script access.');?>

<?=Form::errors()?>

<div class="md:flex md:items-center md:justify-between">
    <div class="flex-1 min-w-0">
        <h2 class="text-2xl font-bold leading-7 text-gray-900 sm:text-3xl sm:leading-9 sm:truncate">
            <?= __('Payment settings') ?>
        </h2>
    </div>
</div>

<div class="bg-white overflow-hidden shadow rounded-lg mt-8">
    <div class="px-4 py-5 sm:p-6">
        <?=FORM::open(Route::url('oc-panel/settings',['controller'=>'payment']))?>
            <div>
                <div>
                    <div>
                        <h3 class="text-lg leading-6 font-medium text-gray-900">
                            <?= __('Payment') ?>
                        </h3>
                    </div>
                    <div class="mt-6 grid grid-cols-1 row-gap-6 col-gap-4 sm:grid-cols-6">
                        <div class="sm:col-span-3">
                            <?= FORM::label('paypal_currency', __('Payment currency'), array('class'=>'block text-sm font-medium leading-5 text-gray-700'))?>
                            <div class="mt-1 rounded-md shadow-sm">
                                <?=FORM::select('paypal_currency', i18n::currencies(), Core::post('paypal_currency', Core::config('payment.paypal_currency')), array(
                                    'class' => 'form-select block w-full sm:text-sm sm:leading-5',
                                ))?>
                            </div>
                            <p class="mt-2 text-sm text-gray-500">
                                <?=__('Please be sure you are using a currency that your payment gateway supports.')?>
                            </p>
                        </div>
                        <div class="sm:col-span-6">
                            <div class="absolute flex items-center h-5">
                                <?=FORM::checkbox('to_featured', 1, (bool) Core::post('to_featured', Core::config('payment.to_featured')), ['class' => 'form-checkbox h-4 w-4 text-blue-600 transition duration-150 ease-in-out'])?>
                            </div>
                            <div class="pl-7 text-sm leading-5">
                                <?=FORM::label('to_featured', __('Featured ads'), ['class'=>'font-medium text-gray-700'])?>
                                <p class="text-gray-500">
                                    <?= __('Featured ads will be highlighted for a defined number of days.') ?>
                                </p>
                            </div>
                        </div>
                        <div class="sm:col-span-6">
                            <div class="absolute flex items-center h-5">
                                <?=FORM::checkbox('to_top', 1, (bool) Core::post('to_top', Core::config('payment.to_top')), ['class' => 'form-checkbox h-4 w-4 text-blue-600 transition duration-150 ease-in-out'])?>
                            </div>
                            <div class="pl-7 text-sm leading-5">
                                <?=FORM::label('to_top', __('Bring to top Ad'), ['class'=>'font-medium text-gray-700'])?>
                            </div>
                        </div>
                        <div class="sm:col-span-3">
                            <?= FORM::label('pay_to_go_on_top', __('To top price'), array('class'=>'block text-sm font-medium leading-5 text-gray-700'))?>
                            <div class="mt-1 flex rounded-md shadow-sm">
                                <?= FORM::input('pay_to_go_on_top', Core::post('pay_to_go_on_top', Core::config('payment.pay_to_go_on_top')), [
                                    'class' => 'form-input block w-full rounded-none rounded-l-md transition duration-150 ease-in-out sm:text-sm sm:leading-5',
                                ])?>
                                <span class="inline-flex items-center px-3 rounded-r-md border border-l-0 border-gray-300 bg-gray-50 text-gray-500 sm:text-sm">
                                    <?=core::config('payment.paypal_currency')?>
                                </span>
                            </div>
                            <p class="mt-2 text-sm text-gray-500">
                                <?=__('How much the user needs to pay to top up an Ad')?>
                            </p>
                        </div>
                        <div class="sm:col-span-4">
                            <?= FORM::label('alternative', __('Alternative Payment'), array('class'=>'block text-sm font-medium leading-5 text-gray-700'))?>
                            <div class="mt-1 rounded-md shadow-sm">
                                <?=FORM::select('alternative', $page_options, Core::post('alternative', Core::config('payment.alternative')), array(
                                    'class' => 'form-select block w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5',
                                ))?>
                            </div>
                            <p class="mt-2 text-sm text-gray-500">
                                <?=__('A button with the page title appears next to other pay button, onclick model opens with description.')?>
                            </p>
                        </div>
                        <div class="sm:col-span-4">
                            <?= FORM::label('vat_country', __('VAT Country'), array('class'=>'block text-sm font-medium leading-5 text-gray-700'))?>
                            <div class="mt-1 rounded-md shadow-sm">
                                <?=FORM::select('vat_country', EUVAT::countries(), Core::post('vat_country', Core::config('payment.vat_country')), array(
                                    'class' => 'form-select block w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5',
                                ))?>
                            </div>
                        </div>
                        <div class="sm:col-span-4">
                            <?= FORM::label('vat_number', __('VAT Number'), array('class'=>'block text-sm font-medium leading-5 text-gray-700'))?>
                            <div class="mt-1 rounded-md shadow-sm">
                                <?= FORM::input('vat_number', Core::post('vat_number', Core::config('payment.vat_number')), [
                                    'class' => 'form-input block w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5',
                                ])?>
                            </div>
                            <p class="mt-2 text-sm text-gray-500">
                                <?=__('Enter the VAT number without the two-letter country code.')?>
                            </p>
                        </div>
                        <div class="sm:col-span-4">
                            <?= FORM::label('vat_non_eu', __('VAT rate only for Non-EU countries'), array('class'=>'block text-sm font-medium leading-5 text-gray-700'))?>
                            <div class="mt-1 rounded-md shadow-sm">
                                <?= FORM::input('vat_non_eu', Core::post('vat_non_eu', Core::config('payment.vat_non_eu')), [
                                    'class' => 'form-input block w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5',
                                ])?>
                            </div>
                            <p class="mt-2 text-sm text-gray-500">
                                <?=__('Enter the VAT rate, only if VAT Country is not an EU country. VAT rate needs to be a decimal number, with or without a decimal point.')?>
                            </p>
                        </div>
                        <div class="sm:col-span-6">
                            <div class="absolute flex items-center h-5">
                                <?=FORM::checkbox('stock', 1, (bool) Core::post('stock', Core::config('payment.stock')), ['class' => 'form-checkbox h-4 w-4 text-blue-600 transition duration-150 ease-in-out'])?>
                            </div>
                            <div class="pl-7 text-sm leading-5">
                                <?=FORM::label('stock', __('Stock control'), ['class'=>'font-medium text-gray-700'])?> <?= View::factory('oc-panel/components/pro-badge') ?>
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
