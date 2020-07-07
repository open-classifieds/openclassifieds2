<?php defined('SYSPATH') or die('No direct script access.'); ?>

<?= Form::errors() ?>

<div class="md:flex md:items-center md:justify-between">
    <div class="flex-1 min-w-0">
        <h2 class="text-2xl font-bold leading-7 text-gray-900 sm:text-3xl sm:leading-9 sm:truncate">
            <?= __('Email settings') ?>
        </h2>

        <div class="mt-1 sm:mt-0">
            <?= View::factory('oc-panel/components/learn-more', ['url' => 'https://guides.yclas.com/#/Email-settings']) ?>
        </div>
    </div>
    <div class="mt-4 flex md:mt-0 md:ml-4">
        <span class="ml-3 shadow-sm rounded-md">
            <a href="<?= Route::url('oc-panel/settings', array('controller' => 'email', 'action' => 'test')) ?>" class="inline-flex items-center px-4 py-2 border border-transparent text-sm leading-5 font-medium rounded-md text-white bg-blue-600 hover:bg-blue-500 focus:outline-none focus:shadow-outline-blue focus:border-blue-700 active:bg-blue-700 transition duration-150 ease-in-out">
                <?= __('Send Email Test') . ' - ' . core::config('email.service') ?>
            </a>
        </span>
    </div>
</div>

<div class="bg-white overflow-hidden shadow rounded-lg mt-8">
    <div class="px-4 py-5 sm:p-6">
        <?=FORM::open(Route::url('oc-panel/settings',['controller'=>'email']))?>
            <div>
                <div>
                    <div>
                        <h3 class="text-lg leading-6 font-medium text-gray-900">
                            <?= __('Email') ?>
                        </h3>
                    </div>
                    <div class="mt-6 grid grid-cols-1 row-gap-6 col-gap-4 sm:grid-cols-6">
                        <div class="sm:col-span-4">
                            <?= FORM::label('notify_email', __('Notify email'), array('class'=>'block text-sm font-medium leading-5 text-gray-700'))?>
                            <div class="mt-1 rounded-md shadow-sm">
                                <?= FORM::input('notify_email', Core::post('notify_email', Core::config('email.notify_email')), [
                                    'placeholder' => "youremail@mail.com",
                                    'class' => 'form-input block w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5',
                                ])?>
                            </div>
                            <p class="mt-2 text-sm text-gray-500">
                                <?=__('Email from where we send the emails, also used for software communications.')?>
                            </p>
                        </div>
                        <div class="sm:col-span-4">
                            <?= FORM::label('notify_name', __('Notify name'), array('class'=>'block text-sm font-medium leading-5 text-gray-700'))?>
                            <div class="mt-1 rounded-md shadow-sm">
                                <?= FORM::input('notify_name', Core::post('notify_name', Core::config('email.notify_name')), [
                                    'placeholder' => "no-reply " . core::config('email.site_name'),
                                    'class' => 'form-input block w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5',
                                ])?>
                            </div>
                            <p class="mt-2 text-sm text-gray-500">
                                <?=__('Name from where we send the emails, also used for software communications.')?>
                            </p>
                        </div>
                        <div class="sm:col-span-6">
                            <div class="absolute flex items-center h-5">
                                <?=FORM::checkbox('new_ad_notify', 1, (bool) Core::post('new_ad_notify', Core::config('email.new_ad_notify')), ['class' => 'form-checkbox h-4 w-4 text-blue-600 transition duration-150 ease-in-out'])?>
                            </div>
                            <div class="pl-7 text-sm leading-5">
                                <?=FORM::label('new_ad_notify', __('Notify me on new ad'), ['class'=>'font-medium text-gray-700'])?>
                            </div>
                        </div>
                    </div>
                    <fieldset class="mt-6">
                        <legend class="text-base leading-6 font-medium text-gray-900"><?= __('Email service') ?></legend>
                        <div class="mt-4">
                            <div class="flex items-center">
                                <?= Form::radio('service', 'elasticemail', in_array(Core::config('email.service'), ['elasticemail', 'elastic']), ['class' => 'form-radio h-4 w-4 text-blue-600 transition duration-150 ease-in-out']) ?>
                                <label for="<?= 'elasticemail' ?>" class="ml-3">
                                    <span class="block text-sm leading-5 font-medium text-gray-700">
                                        <?= 'Elastic Email' ?>
                                    </span>
                                </label>
                            </div>
                        </div>
                        <div class="mt-4">
                            <div class="flex items-center">
                                <?= Form::radio('service', 'mailgun', in_array(Core::config('email.service'), ['mailgun']), ['class' => 'form-radio h-4 w-4 text-blue-600 transition duration-150 ease-in-out']) ?>
                                <label class="ml-3">
                                    <span class="block text-sm leading-5 font-medium text-gray-700">
                                        <?= 'Mailgun' ?>
                                    </span>
                                </label>
                            </div>
                        </div>
                        <div class="mt-4">
                            <div class="flex items-center">
                                <?= Form::radio('service', 'smtp', in_array(Core::config('email.service'), ['smtp', 'gmail', 'outlook', 'yahoo', 'zoho']), ['class' => 'form-radio h-4 w-4 text-blue-600 transition duration-150 ease-in-out']) ?>
                                <label class="ml-3">
                                    <span class="block text-sm leading-5 font-medium text-gray-700">
                                        <?= 'SMTP' ?>
                                    </span>
                                </label>
                            </div>
                        </div>
                        <div class="mt-4">
                            <div class="flex items-center">
                                <?= Form::radio('service', 'mail', in_array(Core::config('email.service'), ['mail', null, '']), ['class' => 'form-radio h-4 w-4 text-blue-600 transition duration-150 ease-in-out']) ?>
                                <label class="ml-3">
                                    <span class="block text-sm leading-5 font-medium text-gray-700">
                                        <?= __('None') ?>
                                    </span>
                                </label>
                            </div>
                        </div>
                    </fieldset>
                </div>
                <div class="mt-8 border-t border-gray-200 pt-8">
                    <div>
                        <h3 class="text-lg leading-6 font-medium text-gray-900">
                            <?=__('SMTP')?>
                        </h3>
                    </div>
                    <div class="mt-6 grid grid-cols-1 row-gap-6 col-gap-4 sm:grid-cols-6">
                        <div class="sm:col-span-3">
                            <?= FORM::label('smtp_host', __('SMTP host'), array('class'=>'block text-sm font-medium leading-5 text-gray-700'))?>
                            <div class="mt-1 rounded-md shadow-sm">
                                <?= FORM::input('smtp_host', Core::post('smtp_host', Core::config('email.smtp_host')), [
                                    'class' => 'form-input block w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5',
                                ])?>
                            </div>
                        </div>
                        <div class="sm:col-span-3">
                            <?= FORM::label('smtp_port', __('SMTP port'), array('class'=>'block text-sm font-medium leading-5 text-gray-700'))?>
                            <div class="mt-1 rounded-md shadow-sm">
                                <?= FORM::input('smtp_port', Core::post('smtp_port', Core::config('email.smtp_port')), [
                                    'class' => 'form-input block w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5',
                                    'type' => 'number',
                                ])?>
                            </div>
                        </div>
                        <div class="sm:col-span-3">
                            <?= FORM::label('smtp_user', __('SMTP user'), array('class'=>'block text-sm font-medium leading-5 text-gray-700'))?>
                            <div class="mt-1 rounded-md shadow-sm">
                                <?= FORM::input('smtp_user', Core::post('smtp_user', Core::config('email.smtp_user')), [
                                    'class' => 'form-input block w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5',
                                ])?>
                            </div>
                        </div>
                        <div class="sm:col-span-3">
                            <?= FORM::label('smtp_pass', __('SMTP password'), array('class'=>'block text-sm font-medium leading-5 text-gray-700'))?>
                            <div class="mt-1 rounded-md shadow-sm">
                                <?= FORM::input('smtp_pass', Core::post('smtp_pass', Core::config('email.smtp_pass')), [
                                    'class' => 'form-input block w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5',
                                    'type' => 'password',
                                ])?>
                            </div>
                        </div>
                        <div class="sm:col-span-3">
                            <?= FORM::label('smtp_secure', __('SMTP secure'), array('class'=>'block text-sm font-medium leading-5 text-gray-700'))?>
                            <div class="mt-1 rounded-md shadow-sm">
                                <?= Form::select('smtp_secure', array('' => __("None"), 'ssl' => 'SSL', 'tls' => 'TLS'), Core::post('smtp_secure', Core::config('email.smtp_secure')), [
                                    'class' => 'form-select block w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5',
                                ])?>
                            </div>
                        </div>
                        <div class="sm:col-span-3">
                            <?= FORM::label('smtp_auth', __('SMTP auth'), array('class'=>'block text-sm font-medium leading-5 text-gray-700'))?>
                            <div class="mt-1 rounded-md shadow-sm">
                                <?= Form::select('smtp_auth', array('1' => __('Enabled'), '0' => __('Disabled')), Core::post('smtp_auth', Core::config('email.smtp_auth')), [
                                    'class' => 'form-select block w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5',
                                ])?>
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
        <? Form::close()?>
    </div>
</div>
