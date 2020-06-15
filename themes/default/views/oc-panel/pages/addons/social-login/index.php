<div class="md:flex md:items-center md:justify-between">
    <div class="flex-1 min-w-0">
        <h2 class="text-2xl font-bold leading-7 text-gray-900 sm:text-3xl sm:leading-9 sm:truncate">
            <?= __('Social login') ?>
        </h2>
        <div class="mt-2 text-sm leading-5 text-gray-500">
            <p>
                <?= __('Learn more about') ?>
                <a href="https://guides.yclas.com/#/Plugins-login-using-social-auth" target="_blank" class="text-blue-700 hover:text-blue-800 hover:underline">
                    social login
                    <svg class="inline-block h-3 w-3 text-blue-700" fill="none" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24" stroke="currentColor"><path d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
                </a>
            </p>
        </div>
    </div>
</div>

<? if (Core::extra_features() == FALSE) : ?>
    <?= View::factory('oc-panel/components/pro-alert') ?>
<? endif ?>

<?= Form::open(Route::url('oc-panel/addons', ['controller' => 'sociallogin'])) ?>
    <div class="bg-white shadow sm:rounded-lg mt-8">
        <div class="px-4 py-5 sm:p-6">
            <h3 class="text-base leading-5 font-medium text-gray-900">
                <?= __('Enable Social login') ?>
            </h3>
            <div class="mt-2 sm:flex sm:items-start sm:justify-between">
                <div class="max-w-xl text-sm leading-5 text-gray-500">
                    <p>
                        <?= __('Login with Facebook, Google and OAuth2') ?>
                    </p>
                </div>
                <div class="mt-5 sm:mt-0 sm:ml-6 sm:flex-shrink-0 sm:flex sm:items-center">
                    <?=FORM::checkbox('is_active', 1, (bool) Core::post('is_active', $is_active), ['class' => 'form-checkbox h-6 w-6 text-blue-600 bg-gray-100 transition duration-150 ease-in-out'])?>
                </div>
            </div>
            <div class="mt-8 border-t border-gray-200 pt-8">
                <div>
                    <h3 class="text-lg leading-6 font-medium text-gray-900">
                        Facebook
                    </h3>
                </div>
                <div class="mt-6 grid grid-cols-1 row-gap-6 col-gap-4 sm:grid-cols-6">
                    <div class="sm:col-span-6">
                        <div class="absolute flex items-center h-5">
                            <?=FORM::checkbox('facebook_enabled', 1, (bool) Core::post('facebook_enabled', $config['providers']['Facebook']['enabled']), ['class' => 'form-checkbox h-4 w-4 text-blue-600 transition duration-150 ease-in-out'])?>
                        </div>
                        <div class="pl-7 text-sm leading-5">
                            <?=FORM::label('facebook_enabled', __('Enabled'), ['class'=>'font-medium text-gray-700'])?>
                        </div>
                    </div>
                    <div class="sm:col-span-4">
                        <?= FORM::label('facebook_id', __('Id'), array('class'=>'block text-sm font-medium leading-5 text-gray-700'))?>
                        <div class="mt-1 rounded-md shadow-sm">
                            <?= FORM::input('facebook_id', Core::post('facebook_id', $config['providers']['Facebook']['keys']['id']), [
                                'class' => 'form-input block w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5',
                                'id' => 'facebook_id',
                            ])?>
                        </div>
                    </div>
                    <div class="sm:col-span-4">
                        <?= FORM::label('facebook_secret', __('Secret'), array('class'=>'block text-sm font-medium leading-5 text-gray-700'))?>
                        <div class="mt-1 rounded-md shadow-sm">
                            <?= FORM::input('facebook_secret', Core::post('facebook_secret', $config['providers']['Facebook']['keys']['secret']), [
                                'class' => 'form-input block w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5',
                                'id' => 'facebook_secret',
                            ])?>
                        </div>
                    </div>
                </div>
            </div>
            <div class="mt-8 border-t border-gray-200 pt-8">
                <div>
                    <h3 class="text-lg leading-6 font-medium text-gray-900">
                        Google
                    </h3>
                </div>
                <div class="mt-6 grid grid-cols-1 row-gap-6 col-gap-4 sm:grid-cols-6">
                    <div class="sm:col-span-6">
                        <div class="absolute flex items-center h-5">
                            <?=FORM::checkbox('google_enabled', 1, (bool) Core::post('google_enabled', $config['providers']['Google']['enabled']), ['class' => 'form-checkbox h-4 w-4 text-blue-600 transition duration-150 ease-in-out'])?>
                        </div>
                        <div class="pl-7 text-sm leading-5">
                            <?=FORM::label('google_enabled', __('Enabled'), ['class'=>'font-medium text-gray-700'])?>
                        </div>
                    </div>
                    <div class="sm:col-span-4">
                        <?= FORM::label('google_id', __('Id'), array('class'=>'block text-sm font-medium leading-5 text-gray-700'))?>
                        <div class="mt-1 rounded-md shadow-sm">
                            <?= FORM::input('google_id', Core::post('google_id', $config['providers']['Google']['keys']['id']), [
                                'class' => 'form-input block w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5',
                                'id' => 'google_id',
                            ])?>
                        </div>
                    </div>
                    <div class="sm:col-span-4">
                        <?= FORM::label('google_secret', __('Secret'), array('class'=>'block text-sm font-medium leading-5 text-gray-700'))?>
                        <div class="mt-1 rounded-md shadow-sm">
                            <?= FORM::input('google_secret', Core::post('google_secret', $config['providers']['Google']['keys']['secret']), [
                                'class' => 'form-input block w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5',
                                'id' => 'google_secret',
                            ])?>
                        </div>
                    </div>
                </div>
            </div>
            <div class="mt-8 border-t border-gray-200 pt-8">
                <div>
                    <h3 class="text-lg leading-6 font-medium text-gray-900">
                        OAuth2
                    </h3>
                </div>
                <div class="mt-6 grid grid-cols-1 row-gap-6 col-gap-4 sm:grid-cols-6">
                    <div class="sm:col-span-6">
                        <div class="absolute flex items-center h-5">
                            <?=FORM::checkbox('oauth2_enabled', 1, (bool) Core::post('oauth2_enabled', Core::config('social.oauth2_enabled')), ['class' => 'form-checkbox h-4 w-4 text-blue-600 transition duration-150 ease-in-out'])?>
                        </div>
                        <div class="pl-7 text-sm leading-5">
                            <?=FORM::label('oauth2_enabled', __('Enabled'), ['class'=>'font-medium text-gray-700'])?>
                        </div>
                    </div>
                    <div class="sm:col-span-4">
                        <?= FORM::label('oauth2_client_id', __('Client id'), array('class'=>'block text-sm font-medium leading-5 text-gray-700'))?>
                        <div class="mt-1 rounded-md shadow-sm">
                            <?= FORM::input('oauth2_client_id', Core::post('oauth2_client_id', Core::config('social.oauth2_client_id')), [
                                'class' => 'form-input block w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5',
                                'id' => 'oauth2_client_id',
                            ])?>
                        </div>
                    </div>
                    <div class="sm:col-span-4">
                        <?= FORM::label('oauth2_client_secret', __('Client secret'), array('class'=>'block text-sm font-medium leading-5 text-gray-700'))?>
                        <div class="mt-1 rounded-md shadow-sm">
                            <?= FORM::input('oauth2_client_secret', Core::post('oauth2_client_secret', Core::config('social.oauth2_client_secret')), [
                                'class' => 'form-input block w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5',
                                'id' => 'oauth2_client_secret',
                            ])?>
                        </div>
                    </div>
                    <div class="sm:col-span-4">
                        <?= FORM::label('oauth2_url_authorize', __('URL authorize'), array('class'=>'block text-sm font-medium leading-5 text-gray-700'))?>
                        <div class="mt-1 rounded-md shadow-sm">
                            <?= FORM::input('oauth2_url_authorize', Core::post('oauth2_url_authorize', Core::config('social.oauth2_url_authorize')), [
                                'class' => 'form-input block w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5',
                                'id' => 'oauth2_url_authorize',
                            ])?>
                        </div>
                    </div>
                    <div class="sm:col-span-4">
                        <?= FORM::label('oauth2_url_access_token', __('URL access token'), array('class'=>'block text-sm font-medium leading-5 text-gray-700'))?>
                        <div class="mt-1 rounded-md shadow-sm">
                            <?= FORM::input('oauth2_url_access_token', Core::post('oauth2_url_access_token', Core::config('social.oauth2_url_access_token')), [
                                'class' => 'form-input block w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5',
                                'id' => 'oauth2_url_access_token',
                            ])?>
                        </div>
                    </div>
                    <div class="sm:col-span-4">
                        <?= FORM::label('oauth2_url_resource_owner_details', __('URL resource owner details'), array('class'=>'block text-sm font-medium leading-5 text-gray-700'))?>
                        <div class="mt-1 rounded-md shadow-sm">
                            <?= FORM::input('oauth2_url_resource_owner_details', Core::post('oauth2_url_resource_owner_details', Core::config('social.oauth2_url_resource_owner_details')), [
                                'class' => 'form-input block w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5',
                                'id' => 'oauth2_url_resource_owner_details',
                            ])?>
                        </div>
                    </div>
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
