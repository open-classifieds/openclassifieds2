<div class="md:flex md:items-center md:justify-between">
    <div class="flex-1 min-w-0">
        <h2 class="text-2xl font-bold leading-7 text-gray-900 sm:text-3xl sm:leading-9 sm:truncate">
            <?= __('eWallet') ?>
        </h2>
    </div>
</div>

<? if (Core::extra_features() == FALSE) : ?>
    <?= View::factory('oc-panel/components/pro-alert') ?>
<? endif ?>

<?= Form::open(Route::url('oc-panel/addons', ['controller' => 'ewallet'])) ?>
    <div class="bg-white shadow sm:rounded-lg mt-8">
        <div class="px-4 py-5 sm:p-6">
            <h3 class="text-base leading-5 font-medium text-gray-900">
                <?= __('Enable eWallet') ?>
            </h3>
            <div class="mt-2 sm:flex sm:items-start sm:justify-between">
                <div class="max-w-xl text-sm leading-5 text-gray-500">
                    <p>
                        <?= __('An eWallet system for your marketplace.') ?>
                    </p>
                </div>
                <div class="mt-5 sm:mt-0 sm:ml-6 sm:flex-shrink-0 sm:flex sm:items-center">
                    <?=FORM::checkbox('is_active', 1, (bool) Core::post('is_active', $is_active), ['class' => 'form-checkbox h-6 w-6 text-blue-600 bg-gray-100 transition duration-150 ease-in-out'])?>
                </div>
            </div>
            <div class="mt-8 border-t border-gray-200 pt-8">
                <div class="grid grid-cols-1 row-gap-6 col-gap-4 sm:grid-cols-6">
                    <div class="sm:col-span-4">
                        <?= FORM::label('money_symbol', __('Money symbol'), array('class'=>'block text-sm font-medium leading-5 text-gray-700'))?>
                        <div class="mt-1 rounded-md shadow-sm">
                            <?= FORM::input('money_symbol', Core::post('money_symbol', Core::config('general.ewallet_money_symbol')), [
                                'placeholder' => '$',
                                'class' => 'form-input block w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5',
                            ])?>
                        </div>
                    </div>
                    <div class="sm:col-span-4">
                        <?= FORM::label('mark_as_received_reminder_after_n_days', __('Mark as received reminder'), array('class'=>'block text-sm font-medium leading-5 text-gray-700'))?>
                        <div class="mt-1 rounded-md shadow-sm">
                            <?= FORM::input('mark_as_received_reminder_after_n_days', Core::post('mark_as_received_reminder_after_n_days', Core::config('general.ewallet_mark_as_received_reminder_after_n_days')), [
                                'placeholder' => '7',
                                'class' => 'form-input block w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5',
                            ])?>
                        </div>
                        <p class="mt-2 text-sm text-gray-500"><?= __('The number of days to wait before sending an email reminder to mark the order as received.') ?></p>
                    </div>
                    <div class="sm:col-span-4">
                        <?= FORM::label('mark_as_received_after_n_days', __('Auto mark unreceived orders as received'), array('class'=>'block text-sm font-medium leading-5 text-gray-700'))?>
                        <div class="mt-1 rounded-md shadow-sm">
                            <?= FORM::input('mark_as_received_after_n_days', Core::post('mark_as_received_after_n_days', Core::config('general.ewallet_mark_as_received_after_n_days')), [
                                'placeholder' => '14',
                                'class' => 'form-input block w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5',
                            ])?>
                        </div>
                        <p class="mt-2 text-sm text-gray-500"><?= __('The number of days to wait before automatically mark the order as received.') ?></p>
                    </div>
                    <div class="sm:col-span-6">
                        <div class="absolute flex items-center h-5">
                            <?=FORM::checkbox('buy_now', 1, (bool) Core::post('buy_now', Core::config('payment.paypal_seller')), ['class' => 'form-checkbox h-4 w-4 text-blue-600 transition duration-150 ease-in-out'])?>
                        </div>
                        <div class="pl-7 text-sm leading-5">
                            <?=FORM::label('buy_now', __('Buy Now button'), ['class'=>'font-medium text-gray-700'])?>
                        </div>
                    </div>
                </div>
            </div>
            <div class="mt-8 border-t border-gray-200 pt-8">
                <div>
                    <h3 class="text-lg leading-6 font-medium text-gray-900">
                        <?=__('Add money')?>
                    </h3>
                </div>
                <div class="mt-6 grid grid-cols-1 row-gap-6 col-gap-4 sm:grid-cols-6">
                    <div class="sm:col-span-6">
                        <div class="absolute flex items-center h-5">
                            <?=FORM::checkbox('add_money', 1, (bool) Core::post('add_money', Core::config('general.ewallet_add_money')), ['class' => 'form-checkbox h-4 w-4 text-blue-600 transition duration-150 ease-in-out'])?>
                        </div>
                        <div class="pl-7 text-sm leading-5">
                            <?=FORM::label('add_money', __('Add money'), ['class'=>'font-medium text-gray-700'])?>
                            <p class="text-gray-500">
                                <?= __('Enable users to add money to their wallets.') ?>
                            </p>
                        </div>
                    </div>
                    <div class="sm:col-span-4">
                        <?= FORM::label('money_packages', __('Money packages'), array('class'=>'block text-sm font-medium leading-5 text-gray-700'))?>
                        <?if (is_array($money_packages)):?>
                            <div class="mt-1">
                                <div class="flex flex-col">
                                    <div class="-my-2 py-2 overflow-x-auto sm:-mx-6 sm:px-6 lg:-mx-8 lg:px-8">
                                        <div class="align-middle inline-block min-w-full overflow-hidden sm:rounded-lg border border-gray-200">
                                            <table class="min-w-full">
                                                <thead>
                                                    <tr>
                                                        <th class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                                                            <?= __('Money') ?>
                                                        </th>
                                                        <th class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                                                            <?= __('Price') ?>
                                                        </th>
                                                        <th class="px-6 py-3 border-b border-gray-200 bg-gray-50"></th>
                                                    </tr>
                                                </thead>
                                                <tbody class="bg-white">
                                                    <?$i=0; foreach ($money_packages as $money => $price):?>
                                                        <tr>
                                                            <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200">
                                                                <div class="text-sm leading-5 text-gray-900"><?= i18n::money_format($money, 'YCL') ?></div>
                                                            </td>
                                                            <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200">
                                                                <div class="text-sm leading-5 text-gray-900"><?=i18n::format_currency($price, core::config('payment.paypal_currency'))?></div>
                                                            </td>
                                                            <td class="px-6 py-4 whitespace-no-wrap text-right border-b border-gray-200 text-sm leading-5 font-medium">
                                                                <button
                                                                    @click="$dispatch('updated-package', { open: true, key: <?= $money ?>, money: <?= $money ?>, price: <?= $price ?> })"
                                                                    type="button"
                                                                    class="text-blue-600 hover:text-blue-900"
                                                                >
                                                                    <?= __('Edit') ?>
                                                                </button>
                                                                <a href="<?=Route::url('oc-panel',array('controller'=>'addons/ewallet', 'action'=>'delete_money_package'))?>?delete_package=<?=$money?>" class="ml-3 text-gray-400 hover:text-red-900">
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
                                    @click="$dispatch('updated-package', { open: true, money: '', price: '', key: '' })"
                                    type="button"
                                    class="inline-flex items-center px-2.5 py-1.5 border border-transparent text-xs leading-4 font-medium rounded text-white bg-blue-600 hover:bg-blue-500 focus:outline-none focus:border-blue-700 focus:shadow-outline-blue active:bg-blue-700 transition ease-in-out duration-150"
                                >
                                    <?= __('Add a package') ?>
                                </button>
                            </span>
                        </p>
                    </div>
                </div>
            </div>
            <div class="mt-8 border-t border-gray-200 pt-8">
                <div>
                    <h3 class="text-lg leading-6 font-medium text-gray-900">
                        <?=__('Gamification')?>
                    </h3>
                </div>
                <div class="mt-6 grid grid-cols-1 row-gap-6 col-gap-4 sm:grid-cols-6">
                    <div class="sm:col-span-6">
                        <div class="absolute flex items-center h-5">
                            <?=FORM::checkbox('gamification', 1, (bool) Core::post('gamification', Core::config('general.ewallet_gamification')), ['class' => 'form-checkbox h-4 w-4 text-blue-600 transition duration-150 ease-in-out'])?>
                        </div>
                        <div class="pl-7 text-sm leading-5">
                            <?=FORM::label('gamification', __('Enable gamification'), ['class'=>'font-medium text-gray-700'])?>
                            <p class="text-gray-500">
                                <?= __('Enable users to earn e-money for specific actions within your marketplace.') ?>
                            </p>
                        </div>
                    </div>

                    <div class="sm:col-span-3">
                        <?= FORM::label('ewallet_gamification_earn_on_sign_up', __('On sign up'), ['class'=>'block text-sm font-medium leading-5 text-gray-700'])?>
                        <div class="mt-1 rounded-md shadow-sm">
                            <?= FORM::input('ewallet_gamification_earn_on_sign_up', Core::post('ewallet_gamification_earn_on_sign_up', Core::config('general.ewallet_gamification_earn_on_sign_up')), [
                                'placeholder' => '20',
                                'type' => 'number',
                                'class' => 'form-input block w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5',
                            ])?>
                        </div>
                        <p class="mt-2 text-sm text-gray-500">
                            <?=__('How much a user can earn for sign up.')?> <a class="underline" href="https://guides.yclas.com/#/General-user-must-verify-email"><?= __('"User must verify email" feature must be enabled.') ?></a>
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

<?= Form::open(Route::url('oc-panel/addons', ['controller' => 'ewallet', 'action' => 'update_money_package'])) ?>
    <div
        x-data="{ open: false, money: '', price: '', key: '' }"
        x-show="open"
        class="fixed bottom-0 inset-x-0 px-4 pb-4 sm:inset-0 sm:flex sm:items-center sm:justify-center"
        x-on:updated-package.window="open = $event.detail.open; money = $event.detail.money; price = $event.detail.price; key = $event.detail.key;"
    >
        <div x-show="open" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 transition-opacity">
            <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
        </div>
        <div x-show="open" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" class="bg-white rounded-lg overflow-hidden shadow-xl transform transition-all sm:max-w-lg sm:w-full">
            <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                    <h3 class="text-lg leading-6 font-medium text-gray-900">
                        <?= __('Money package') ?>
                    </h3>
                    <div class="mt-2">
                        <div class="mb-4">
                            <?= Form::label('money', __('Money'), ['class' => 'block text-sm font-medium leading-5 text-gray-700']) ?>
                            <?= Form::input('money', NULL, [
                                'x-model' => 'money',
                                'class' => 'mt-1 rounded-md shadow-sm form-input block w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5',
                            ])?>
                        </div>

                        <div class="mb-4">
                            <?= Form::label('price', __('Price'), ['class' => 'block text-sm font-medium leading-5 text-gray-700']) ?>
                            <?= Form::input('price', NULL, [
                                'x-model' => 'price',
                                'class' => 'mt-1 rounded-md shadow-sm form-input block w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5',
                            ])?>
                        </div>

                        <?= Form::hidden('money_key', NULL, [
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
