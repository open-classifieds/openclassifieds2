<?php defined('SYSPATH') or die('No direct script access.');?>

<?=Form::errors()?>

<div class="md:flex md:items-center md:justify-between">
    <div class="flex-1 min-w-0">
        <h2 class="text-2xl font-bold leading-7 text-gray-900 sm:text-3xl sm:leading-9 sm:truncate">
            <?= __('Payment settings') ?>
        </h2>
        <div class="mt-1 sm:mt-0">
            <p class="mt-2 items-center text-sm leading-5 text-gray-500">
                <?= __('Manage your marketplace currency, feature plans and payment options.') ?>
                <a href="https://guides.yclas.com/#/Payment" target="_blank" class="text-blue-600 hover:text-blue-900"><?= __('Learn more about payments') ?></a>
            </p>
        </div>
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
                        <div class="sm:col-span-4">
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
                <div class="mt-8 border-t border-gray-200 pt-8">
                    <div>
                        <h3 class="text-lg leading-6 font-medium text-gray-900">
                            <?=__('Featured ads')?>
                        </h3>
                    </div>
                    <div class="mt-6 grid grid-cols-1 row-gap-6 col-gap-4 sm:grid-cols-6">
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
                        <div class="sm:col-span-4">
                            <?= FORM::label('featured_plans', __('Featured plans'), array('class'=>'block text-sm font-medium leading-5 text-gray-700'))?>
                            <?if (is_array($featured_plans)):?>
                                <div class="mt-1">
                                    <div class="flex flex-col">
                                        <div class="-my-2 py-2 overflow-x-auto sm:-mx-6 sm:px-6 lg:-mx-8 lg:px-8">
                                            <div class="align-middle inline-block min-w-full overflow-hidden sm:rounded-lg border border-gray-200">
                                                <table class="min-w-full">
                                                    <thead>
                                                        <tr>
                                                            <th class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                                                                <?= __('Days') ?>
                                                            </th>
                                                            <th class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                                                                <?= __('Price') ?>
                                                            </th>
                                                            <th class="px-6 py-3 border-b border-gray-200 bg-gray-50"></th>
                                                        </tr>
                                                    </thead>
                                                    <tbody class="bg-white">
                                                        <?$i=0; foreach ($featured_plans as $days => $price):?>
                                                            <tr>
                                                                <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200">
                                                                    <div class="text-sm leading-5 text-gray-900"><?=$days?> <?=__('Days')?></div>
                                                                </td>
                                                                <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200">
                                                                    <div class="text-sm leading-5 text-gray-900"><?=i18n::format_currency($price,core::config('payment.paypal_currency'))?></div>
                                                                </td>
                                                                <td class="px-6 py-4 whitespace-no-wrap text-right border-b border-gray-200 text-sm leading-5 font-medium">
                                                                    <button
                                                                        @click="$dispatch('updated-plan', { open: true, key: <?= $days ?>, days: <?= $days ?>, price: <?= $price ?> })"
                                                                        type="button"
                                                                        class="text-blue-600 hover:text-blue-900"
                                                                    >
                                                                        <?= __('Edit') ?>
                                                                    </button>
                                                                    <a href="<?=Route::url('oc-panel',array('controller'=>'settings/payment', 'action'=>'delete_featured_plan'))?>?delete_plan=<?=$days?>" class="ml-3 text-gray-400 hover:text-red-900">
                                                                        <?= __('Delete') ?>
                                                                    </a>
                                                                </td>
                                                            </tr>
                                                        <? endforeach ?>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <? endif ?>
                            <p class="mt-2 text-sm text-gray-500">
                                <span class="inline-flex rounded-md shadow-sm">
                                    <button
                                        @click="$dispatch('updated-plan', { open: true, days: '', price: '', key: '' })"
                                        type="button"
                                        class="inline-flex items-center px-2.5 py-1.5 border border-transparent text-xs leading-4 font-medium rounded text-white bg-blue-600 hover:bg-blue-500 focus:outline-none focus:border-blue-700 focus:shadow-outline-blue active:bg-blue-700 transition ease-in-out duration-150"
                                    >
                                        <?= __('Add a plan') ?>
                                    </button>
                                </span>
                            </p>
                        </div>
                    </div>
                </div>
                <div class="mt-8 border-t border-gray-200 pt-8">
                    <div>
                        <h3 class="text-lg leading-6 font-medium text-gray-900">
                            <?=__('Bring to top ad')?>
                        </h3>
                    </div>
                    <div class="mt-6 grid grid-cols-1 row-gap-6 col-gap-4 sm:grid-cols-6">
                        <div class="sm:col-span-6">
                            <div class="absolute flex items-center h-5">
                                <?=FORM::checkbox('to_top', 1, (bool) Core::post('to_top', Core::config('payment.to_top')), ['class' => 'form-checkbox h-4 w-4 text-blue-600 transition duration-150 ease-in-out'])?>
                            </div>
                            <div class="pl-7 text-sm leading-5">
                                <?=FORM::label('to_top', __('Bring to top ad'), ['class'=>'font-medium text-gray-700'])?>
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
                    </div>
                </div>
                <div class="mt-8 border-t border-gray-200 pt-8">
                    <div>
                        <h3 class="text-lg leading-6 font-medium text-gray-900">
                            <?=__('Pay to post')?>
                        </h3>
                        <p class="mt-2 text-sm text-gray-500">
                            <?=__('"Payment on" and your users will be asked to pay. You can also choose Payment with moderation, and users will pay to publish an ad, but you will still have the possibility to moderate the ad before it becomes visible on your site.')?>
                        </p>
                    </div>
                    <div class="mt-6 grid grid-cols-1 row-gap-6 col-gap-4 sm:grid-cols-6">
                        <div class="sm:col-span-4">
                            <div class="mt-1 rounded-md shadow-sm">
                                <?= FORM::select('moderation', array(0=>__("Disabled"), 2=>__("Payment on"),5=>__("Payment with moderation")), Core::post('moderation', Core::config('general.moderation')), array(
                                    'class' => 'form-select block w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5',
                                ))?>
                            </div>
                            <p class="mt-2 text-sm text-gray-500">
                                <?= __('You can set categoy prices on the categories page.')?>
                                <a class="underline" href="<?= Route::url('oc-panel', ['controller' => 'category']) ?>">
                                    <?= __('Go to categories') ?> →
                                </a>
                            </p>
                        </div>
                    </div>
                </div>
                <div class="mt-8 border-t border-gray-200 pt-8">
                    <div>
                        <h3 class="text-lg leading-6 font-medium text-gray-900">
                            <?=__('VAT')?>
                        </h3>
                    </div>
                    <div class="mt-6 grid grid-cols-1 row-gap-6 col-gap-4 sm:grid-cols-6">
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

<?= Form::open(Route::url('oc-panel/settings',['controller'=>'payment', 'action'=>'update_featured_plan']), ['class'=>'config']) ?>
    <div
        x-data="{ open: false, days: '', price: '', key: '' }"
        x-show="open"
        class="fixed bottom-0 inset-x-0 px-4 pb-4 sm:inset-0 sm:flex sm:items-center sm:justify-center"
        x-on:updated-plan.window="open = $event.detail.open; days = $event.detail.days; price = $event.detail.price; key = $event.detail.key;"
    >
        <div x-show="open" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 transition-opacity">
            <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
        </div>
        <div x-show="open" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" class="bg-white rounded-lg overflow-hidden shadow-xl transform transition-all sm:max-w-lg sm:w-full">
            <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                    <h3 class="text-lg leading-6 font-medium text-gray-900">
                        <?= __('Featured plan') ?>
                    </h3>
                    <div class="mt-2">
                        <div class="mb-4">
                            <?= Form::label('featured_days', __('Days'), ['class' => 'block text-sm font-medium leading-5 text-gray-700']) ?>
                            <?= Form::input('featured_days', NULL, [
                                'x-model' => 'days',
                                'class' => 'mt-1 rounded-md shadow-sm form-input block w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5',
                            ])?>
                        </div>

                        <div class="mb-4">
                            <?= Form::label('featured_price', __('Price'), ['class' => 'block text-sm font-medium leading-5 text-gray-700']) ?>
                            <?= Form::input('featured_price', NULL, [
                                'x-model' => 'price',
                                'class' => 'mt-1 rounded-md shadow-sm form-input block w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5',
                            ])?>
                        </div>

                        <?= Form::hidden('featured_days_key', NULL, [
                            'x-model' => 'key',
                        ])?>
                    </div>
                </div>
            </div>
            <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                <span class="flex w-full rounded-md shadow-sm sm:ml-3 sm:w-auto">
                    <button @click="open = false" type="submit" class="inline-flex justify-center w-full rounded-md border border-transparent px-4 py-2 bg-blue-600 text-base leading-6 font-medium text-white shadow-sm hover:bg-blue-500 focus:outline-none focus:border-blue-700 focus:shadow-outline-blue transition ease-in-out duration-150 sm:text-sm sm:leading-5">
                        <?= __('Save') ?>
                    </button>
                </span>
                <span class="mt-3 flex w-full rounded-md shadow-sm sm:mt-0 sm:w-auto">
                    <button @click="open = false" type="button" class="inline-flex justify-center w-full rounded-md border border-gray-300 px-4 py-2 bg-white text-base leading-6 font-medium text-gray-700 shadow-sm hover:text-gray-500 focus:outline-none focus:border-blue-300 focus:shadow-outline transition ease-in-out duration-150 sm:text-sm sm:leading-5">
                        <?= __('Cancel') ?>
                    </button>
                </span>
            </div>
        </div>
    </div>
<?= Form::close() ?>

<div class="bg-white shadow sm:rounded-lg mt-8">
    <div class="px-4 py-5 sm:p-6">
        <h3 class="text-lg leading-6 font-medium text-gray-900">
            <?= __('Payment gateways') ?>
        </h3>
        <div class="mt-2 max-w-xl text-sm leading-5 text-gray-500">
            <p>
                <?= __('Configure your payment gateways on the integrations page.') ?>
            </p>
        </div>
        <div class="mt-3 text-sm leading-5">
            <a href="<?= Route::url('oc-panel', ['controller' => 'integrations']) ?>" class="font-medium text-blue-600 hover:text-blue-500 focus:outline-none focus:underline transition ease-in-out duration-150">
                <?= __('Go to integrations') ?> &rarr;
            </a>
        </div>
    </div>
</div>
