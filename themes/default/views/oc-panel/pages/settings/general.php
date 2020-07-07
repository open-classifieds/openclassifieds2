<?php defined('SYSPATH') or die('No direct script access.');?>

<?=Form::errors()?>

<div class="md:flex md:items-center md:justify-between">
    <div class="flex-1 min-w-0">
        <h2 class="text-2xl font-bold leading-7 text-gray-900 sm:text-3xl sm:leading-9 sm:truncate">
            <?= __('General settings') ?>
        </h2>

        <div class="mt-1 sm:mt-0">
            <?= View::factory('oc-panel/components/learn-more', ['url' => 'https://guides.yclas.com/#/General-Settings']) ?>
        </div>
    </div>
    <? if (Core::is_selfhosted()) : ?>
        <div class="mt-4 flex md:mt-0 md:ml-4">
            <span class="ml-3 shadow-sm rounded-md">
                <a href="<?=Route::url('oc-panel',['controller'=>'config'])?>" class="inline-flex items-center px-4 py-2 border border-transparent text-sm leading-5 font-medium rounded-md text-white bg-blue-600 hover:bg-blue-500 focus:outline-none focus:shadow-outline-blue focus:border-blue-700 active:bg-blue-700 transition duration-150 ease-in-out">
                    <?=__('All configurations')?>
                </a>
            </span>
        </div>
    <? endif ?>
</div>

<div class="bg-white overflow-hidden shadow rounded-lg mt-8">
    <div class="px-4 py-5 sm:p-6">
        <?=FORM::open(Route::url('oc-panel/settings',['controller'=>'general']))?>
            <div>
                <div>
                    <div>
                        <h3 class="text-lg leading-6 font-medium text-gray-900">
                            <?= __('General') ?>
                        </h3>
                    </div>
                    <div class="mt-6 grid grid-cols-1 row-gap-6 col-gap-4 sm:grid-cols-6">
                        <div class="sm:col-span-6">
                            <div class="absolute flex items-center h-5">
                                <?=FORM::checkbox('maintenance', 1, (bool) Core::post('maintenance', Core::config('general.maintenance')), ['class' => 'form-checkbox h-4 w-4 text-blue-600 transition duration-150 ease-in-out'])?>
                            </div>
                            <div class="pl-7 text-sm leading-5">
                                <?=FORM::label('maintenance', __('Maintenance Mode'), ['class'=>'font-medium text-gray-700'])?>
                                <p class="text-gray-500"><?= __('Enables the site to maintenance') ?>.</p>
                            </div>
                        </div>
                        <div class="sm:col-span-4">
                            <?= FORM::label('site_name', __('Site name'), array('class'=>'block text-sm font-medium leading-5 text-gray-700'))?>
                            <div class="mt-1 rounded-md shadow-sm">
                                <?= FORM::input('site_name', Core::post('site_name', Core::config('general.site_name')), [
                                    'placeholder' => 'Yclas',
                                    'class' => 'form-input block w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5',
                                ])?>
                            </div>
                            <p class="mt-2 text-sm text-gray-500">
                                <?=__("Here you can declare your display name. This is seen by everyone!")?>
                            </p>
                        </div>
                        <div class="sm:col-span-6">
                            <?= FORM::label('site_description', __('Site description'), array('class'=>'block text-sm font-medium leading-5 text-gray-700'))?>
                            <div class="mt-1 rounded-md shadow-sm">
                                <?= FORM::textarea('site_description', Core::post('site_description', Core::config('general.site_description')), array(
                                    'placeholder' => __('Description of your site in no more than 160 characters.'),
                                    'rows' => 3,
                                    'cols' => 50,
                                    'class' => 'form-textarea block w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5',
                                ))?>
                            </div>
                            <p class="mt-2 text-sm text-gray-500">
                                <?=__("Description used for the Meta Description Tag of the home page. Might be used by Google as search result snippet. (max. 160 chars)")?>
                            </p>
                        </div>
                        <div class="sm:col-span-6">
                            <div class="absolute flex items-center h-5">
                                <?=FORM::checkbox('disallowbots', 1, (bool) Core::post('disallowbots', Core::config('general.disallowbots')), ['class' => 'form-checkbox h-4 w-4 text-blue-600 transition duration-150 ease-in-out'])?>
                            </div>
                            <div class="pl-7 text-sm leading-5">
                                <?=FORM::label('disallowbots', __('Disallows (blocks) Bots and Crawlers on this website'), ['class'=>'font-medium text-gray-700'])?>
                                <p class="text-gray-500"><?= __('Disallows Bots and Crawlers on the website') ?>.</p>
                            </div>
                        </div>
                        <div class="sm:col-span-6">
                            <div class="absolute flex items-center h-5">
                                <?=FORM::checkbox('private_site', 1, (bool) Core::post('private_site', Core::config('general.private_site')), ['class' => 'form-checkbox h-4 w-4 text-blue-600 transition duration-150 ease-in-out'])?>
                            </div>
                            <div class="pl-7 text-sm leading-5">
                                <?=FORM::label('private_site', __('Private Site'), ['class'=>'font-medium text-gray-700'])?>
                                <p class="text-gray-500"><?= __('Enables the site to private_site') ?>.</p>
                            </div>
                        </div>
                        <div class="sm:col-span-6">
                            <div class="absolute flex items-center h-5">
                                <?=FORM::checkbox('cookie_consent', 1, (bool) Core::post('cookie_consent', Core::config('general.cookie_consent')), ['class' => 'form-checkbox h-4 w-4 text-blue-600 transition duration-150 ease-in-out'])?>
                            </div>
                            <div class="pl-7 text-sm leading-5">
                                <?=FORM::label('cookie_consent', __('Cookie consent'), ['class'=>'font-medium text-gray-700'])?>
                                <p class="text-gray-500"><?= __('Enables an alert to accept cookies') ?>.</p>
                            </div>
                        </div>
                        <div class="sm:col-span-4">
                            <?= FORM::label('moderation', __('Moderation'), array('class'=>'block text-sm font-medium leading-5 text-gray-700'))?>
                            <div class="mt-1 rounded-md shadow-sm">
                                <?= FORM::select('moderation', array(0=>__("Post directly"),1=>__("Moderation on"),2=>__("Payment on"),3=>__("Email confirmation on"),4=>__("Email confirmation with Moderation"),5=>__("Payment with Moderation")), Core::post('moderation', Core::config('general.moderation')), array(
                                    'class' => 'form-select block w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5',
                                ))?>
                            </div>
                            <p class="mt-2 text-sm text-gray-500">
                                <?=__('Moderation is how you control newly created advertisements. You can set it up to fulfill your needs. For example, Post directly will enable new ads to be posted directly, and get published as soon they submit.')?>
                            </p>
                        </div>
                        <div class="sm:col-span-4">
                            <?= FORM::label('landing_page', __('Landing page'), array('class'=>'block text-sm font-medium leading-5 text-gray-700'))?>
                            <div class="mt-1 rounded-md shadow-sm">
                                <?= FORM::select('landing_page',
                                        array('{"controller":"home","action":"index"}'=>'HOME','{"controller":"ad","action":"listing"}'=>'LISTING','{"controller":"user","action":"index"}'=>'USERS'), Core::post('landing_page', Core::config('general.landing_page')), array(
                                    'class' => 'form-select block w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5',
                                ))?>
                            </div>
                            <p class="mt-2 text-sm text-gray-500">
                                <?=__('It changes landing page of website')?>
                            </p>
                        </div>
                        <div class="sm:col-span-4">
                            <?= FORM::label('alert_terms', __('Accept Terms Alert'), array('class'=>'block text-sm font-medium leading-5 text-gray-700'))?>
                            <div class="mt-1 rounded-md shadow-sm">
                                <?=FORM::select('alert_terms', $page_options, Core::post('alert_terms', Core::config('general.alert_terms')), array(
                                    'class' => 'form-select block w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5',
                                ))?>
                            </div>
                            <p class="mt-2 text-sm text-gray-500">
                                <?=__('It changes landing page of website')?>
                            </p>
                        </div>
                        <div class="sm:col-span-4">
                            <?= FORM::label('private_site_page', __('Private Site landing page content'), array('class'=>'block text-sm font-medium leading-5 text-gray-700'))?>
                            <div class="mt-1 rounded-md shadow-sm">
                                <?=FORM::select('private_site_page', $page_options, Core::post('private_site_page', Core::config('general.private_site_page')), array(
                                    'class' => 'form-select block w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5',
                                ))?>
                            </div>
                            <p class="mt-2 text-sm text-gray-500">
                                <?=__('Adds content to private site landing page')?>
                            </p>
                        </div>
                        <div class="sm:col-span-4">
                            <?= FORM::label('contact_page', __('Contact page content'), array('class'=>'block text-sm font-medium leading-5 text-gray-700'))?>
                            <div class="mt-1 rounded-md shadow-sm">
                                <?= FORM::select('contact_page', $page_options, Core::post('contact_page', Core::config('general.contact_page')), array(
                                    'class' => 'form-select block w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5',
                                ))?>
                            </div>
                            <p class="mt-2 text-sm text-gray-500">
                                <?=__('Adds content to contact page')?>
                            </p>
                        </div>
                        <div class="sm:col-span-4">
                            <?= FORM::label('email_domains', __('Allowed email domains'), array('class'=>'block text-sm font-medium leading-5 text-gray-700'))?>
                            <div class="mt-1 rounded-md shadow-sm">
                                <?= FORM::input('email_domains', Core::post('email_domains', Core::config('general.email_domains')), [
                                    'placeholder' => __('For email domain push enter.'),
                                    'class' => 'form-input block w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5',
                                ])?>
                            </div>
                            <p class="mt-2 text-sm text-gray-500">
                                <?=__("You need to write your email domains to enable the service.")?>
                            </p>
                        </div>
                        <div class="sm:col-span-4">
                            <?= FORM::label('disallowed_email_domains', __('Disallowed email domains'), array('class'=>'block text-sm font-medium leading-5 text-gray-700'))?>
                            <div class="mt-1 rounded-md shadow-sm">
                                <?= FORM::input('disallowed_email_domains', Core::post('disallowed_email_domains', Core::config('general.disallowed_email_domains')), [
                                    'placeholder' => __('For email domain push enter.'),
                                    'class' => 'form-input block w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5',
                                ])?>
                            </div>
                            <p class="mt-2 text-sm text-gray-500">
                                <?=__("You need to write your email domains to enable the service.")?>
                            </p>
                        </div>
                        <div class="sm:col-span-4">
                            <?= FORM::label('api_key', __('API Key'), array('class'=>'block text-sm font-medium leading-5 text-gray-700'))?>
                            <div class="mt-1 rounded-md shadow-sm">
                                <?= FORM::input('api_key', Core::post('api_key', Core::config('general.api_key')), [
                                    'class' => 'form-input block w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5',
                                ])?>
                            </div>
                            <p class="mt-2 text-sm text-gray-500">
                                <?=__("Integrate anything using your site API Key.")?>
                            </p>
                        </div>
                        <div class="sm:col-span-6">
                            <?= FORM::label('html_head', __('HTML in HEAD element'), array('class'=>'block text-sm font-medium leading-5 text-gray-700'))?>
                            <div class="mt-1 rounded-md shadow-sm">
                                <?= FORM::textarea('html_head', Core::post('html_head', Core::config('general.html_head')), array(
                                    'rows' => 3,
                                    'cols' => 50,
                                    'class' => 'form-textarea block w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5',
                                ))?>
                            </div>
                            <p class="mt-2 text-sm text-gray-500">
                                <?=__("To include your custom HTML code (validation metadata, reference to JS/CSS files, etc.) in the HEAD element of the rendered page.")?>
                            </p>
                        </div>
                        <div class="sm:col-span-6">
                            <?= FORM::label('html_footer', __('HTML in footer'), array('class'=>'block text-sm font-medium leading-5 text-gray-700'))?>
                            <div class="mt-1 rounded-md shadow-sm">
                                <?= FORM::textarea('html_footer', Core::post('html_footer', Core::config('general.html_footer')), array(
                                    'rows' => 3,
                                    'cols' => 50,
                                    'class' => 'form-textarea block w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5',
                                ))?>
                            </div>
                            <p class="mt-2 text-sm text-gray-500">
                                <?=__("To include your custom HTML code (reference to JS or CSS files, etc.) in the footer of the rendered page.")?>
                            </p>
                        </div>
                        <? if (Core::is_cloud()) : ?>
                            <div class="sm:col-span-6">
                                <?= FORM::label('adstxt', 'Ads.txt', array('class'=>'block text-sm font-medium leading-5 text-gray-700'))?>
                                <div class="mt-1 rounded-md shadow-sm">
                                    <?= FORM::textarea('adstxt', Core::post('adstxt', Core::config('general.adstxt')), array(
                                        'rows' => 3,
                                        'cols' => 50,
                                        'class' => 'form-textarea block w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5',
                                    ))?>
                                </div>
                                <p class="mt-2 text-sm text-gray-500">
                                    <a target="_blank" href="<?=Route::url('adstxt')?>"><?=__("Paste here the content of your ads.txt")?></a>
                                </p>
                            </div>
                            <div class="sm:col-span-6">
                                <?= FORM::label('robotstxt', 'Robots.txt', array('class'=>'block text-sm font-medium leading-5 text-gray-700'))?>
                                <div class="mt-1 rounded-md shadow-sm">
                                    <?= FORM::textarea('robotstxt', Core::post('robotstxt', Core::config('general.robotstxt')), array(
                                        'rows' => 3,
                                        'cols' => 50,
                                        'class' => 'form-textarea block w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5',
                                    ))?>
                                </div>
                                <p class="mt-2 text-sm text-gray-500">
                                    <a target="_blank" href="<?=Route::url('robots')?>"><?=__("Paste here the content of your robots.txt")?></a>
                                </p>
                            </div>
                        <? endif ?>
                    </div>
                </div>
                <div class="mt-8 border-t border-gray-200 pt-8">
                    <div>
                        <h3 class="text-lg leading-6 font-medium text-gray-900">
                            <?=__('Regional configuration')?>
                        </h3>
                    </div>
                    <div class="mt-6 grid grid-cols-1 row-gap-6 col-gap-4 sm:grid-cols-6">
                        <div class="sm:col-span-3">
                            <?= FORM::label('country', __('Country'), array('class'=>'block text-sm font-medium leading-5 text-gray-700'))?>
                            <div class="mt-1 rounded-md shadow-sm">
                                <?= Form::select('country', array_merge(['' => __('None')], EUVAT::countries()), Core::post('country', Core::config('general.country')), [
                                    'class' => 'form-select block w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5',
                                ])?>
                            </div>
                            <p class="mt-2 text-sm text-gray-500">
                                <?=__("Set an inital country for the phone field.")?>
                            </p>
                        </div>
                        <div class="sm:col-span-3">
                            <?= FORM::label('date_format', __('Date format'), array('class'=>'block text-sm font-medium leading-5 text-gray-700'))?>
                            <div class="mt-1 rounded-md shadow-sm">
                                <?= FORM::input('date_format', Core::post('date_format', Core::config('general.date_format')), [
                                    'placeholder' => "d/m/Y",
                                    'class' => 'form-input block w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5',
                                ])?>
                            </div>
                            <p class="mt-2 text-sm text-gray-500">
                                <?=__("Each advertisement has a publish date. By selecting format, you can change how it is shown on your website.")?>
                            </p>
                        </div>
                        <div class="sm:col-span-3">
                            <?= FORM::label('timezone', __('Time Zone'), array('class'=>'block text-sm font-medium leading-5 text-gray-700'))?>
                            <div class="mt-1 rounded-md shadow-sm">
                                <?= FORM::select('timezone', Date::get_timezones(), Core::post('timezone', date_default_timezone_get()), array(
                                    'class' => 'form-select block w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5',
                                ))?>
                            </div>
                        </div>
                        <div class="sm:col-span-3">
                            <?= FORM::label('number_format', __('Money format'), array('class'=>'block text-sm font-medium leading-5 text-gray-700'))?>
                            <div class="mt-1 rounded-md shadow-sm">
                                <?=Form::select('number_format', i18n::currencies_defaults(), Core::post('number_format', Core::config('general.number_format')), [
                                    'class' => 'form-select block w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5',
                                ])?>
                            </div>
                            <p class="mt-2 text-sm text-gray-500">
                                <?=__("Number format is how you want to display numbers related to advertisements. More specific advertisement price. Every country have a specific way of dealing with decimal digits.")?>
                            </p>
                        </div>
                        <div class="sm:col-span-3">
                            <?= FORM::label('measurement', __('Measurement Units'), array('class'=>'block text-sm font-medium leading-5 text-gray-700'))?>
                            <div class="mt-1 rounded-md shadow-sm">
                                <?=FORM::select('measurement', array('metric' => __("Metric"), 'imperial' => __("Imperial")), Core::post('measurement', Core::config('general.measurement')), array(
                                    'class' => 'form-select block w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5',
                                ))?>
                            </div>
                            <p class="mt-2 text-sm text-gray-500">
                                <?=__("Measurement units used by the system.")?>
                            </p>
                        </div>
                    </div>
                </div>
                <div class="mt-8 border-t border-gray-200 pt-8">
                    <div>
                        <h3 class="text-lg leading-6 font-medium text-gray-900">
                            <?=__('Multilingual configuration')?>
                        </h3>
                    </div>
                    <div class="mt-6 grid grid-cols-1 row-gap-6 col-gap-4 sm:grid-cols-6">
                        <div class="sm:col-span-6">
                            <div class="absolute flex items-center h-5">
                                <?=FORM::checkbox('multilingual', 1, (bool) Core::post('multilingual', Core::config('general.multilingual')), ['class' => 'form-checkbox h-4 w-4 text-blue-600 transition duration-150 ease-in-out'])?>
                            </div>
                            <div class="pl-7 text-sm leading-5">
                                <?=FORM::label('multilingual', __('Multilingual'), ['class'=>'font-medium text-gray-700'])?>
                                <p class="text-gray-500">
                                    <?=__("Enables the site to multilingual")?>
                                </p>
                            </div>
                        </div>
                        <div class="sm:col-span-4">
                            <?= FORM::label('languages', __('Languages'), array('class'=>'block text-sm font-medium leading-5 text-gray-700'))?>
                            <div class="mt-1 rounded-md shadow-sm">
                                <?= FORM::input('languages', Core::post('languages', Core::config('general.languages')), [
                                    'placeholder' => 'en_US,es_ES',
                                    'class' => 'form-input block w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5',
                                ])?>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="mt-8 border-t border-gray-200 pt-8">
                    <div>
                        <h3 class="text-lg leading-6 font-medium text-gray-900">
                            <?=__('Search configuration')?>
                        </h3>
                    </div>
                    <div class="mt-6 grid grid-cols-1 row-gap-6 col-gap-4 sm:grid-cols-6">
                        <div class="sm:col-span-6">
                            <div class="absolute flex items-center h-5">
                                <?=FORM::checkbox('search_by_description', 1, (bool) Core::post('search_by_description', Core::config('general.search_by_description')), ['class' => 'form-checkbox h-4 w-4 text-blue-600 transition duration-150 ease-in-out'])?>
                            </div>
                            <div class="pl-7 text-sm leading-5">
                                <?=FORM::label('search_by_description', __('Include search by description'), ['class'=>'font-medium text-gray-700'])?>
                                <p class="text-gray-500">
                                    <?=__("Once set to TRUE, enables search to look for key words in description")?>
                                </p>
                            </div>
                        </div>
                        <div class="sm:col-span-6">
                            <div class="absolute flex items-center h-5">
                                <?=FORM::checkbox('search_multi_catloc', 1, (bool) Core::post('search_multi_catloc', Core::config('general.search_multi_catloc')), ['class' => 'form-checkbox h-4 w-4 text-blue-600 transition duration-150 ease-in-out'])?>
                            </div>
                            <div class="pl-7 text-sm leading-5">
                                <?=FORM::label('search_multi_catloc', __('Multi select category and location search'), ['class'=>'font-medium text-gray-700'])?>
                                <p class="text-gray-500">
                                    <?=__("Once set to TRUE, enables multi select category and location search")?>
                                </p>
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
