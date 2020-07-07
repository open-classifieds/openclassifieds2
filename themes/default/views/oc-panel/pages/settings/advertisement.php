<?php defined('SYSPATH') or die('No direct script access.');?>

<?=Form::errors()?>

<div class="md:flex md:items-center md:justify-between">
    <div class="flex-1 min-w-0">
        <h2 class="text-2xl font-bold leading-7 text-gray-900 sm:text-3xl sm:leading-9 sm:truncate">
            <?= __('Advertisement settings') ?>
        </h2>

        <div class="mt-1 sm:mt-0">
            <?= View::factory('oc-panel/components/learn-more', ['url' => 'https://guides.yclas.com/#/Advertisement']) ?>
        </div>
    </div>
</div>

<div class="bg-white overflow-hidden shadow rounded-lg mt-8">
    <div class="px-4 py-5 sm:p-6">
        <?=FORM::open(Route::url('oc-panel/settings',['controller'=>'advertisement']))?>
            <div>
                <div>
                    <div>
                        <h3 class="text-lg leading-6 font-medium text-gray-900">
                            <?= __('Advertisement') ?>
                        </h3>
                    </div>
                    <div class="mt-6 grid grid-cols-1 row-gap-6 col-gap-4 sm:grid-cols-6">
                        <div class="sm:col-span-3">
                            <?= FORM::label('advertisements_per_page', __('Advertisements per page'), ['class'=>'block text-sm font-medium leading-5 text-gray-700'])?>
                            <div class="mt-1 rounded-md shadow-sm">
                                <?= FORM::input('advertisements_per_page', Core::post('advertisements_per_page', Core::config('advertisement.advertisements_per_page')), [
                                    'placeholder' => '20',
                                    'type' => 'number',
                                    'class' => 'form-input block w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5',
                                ])?>
                            </div>
                            <p class="mt-2 text-sm text-gray-500">
                                <?=__('This is to control how many advertisements are being displayed per page. Insert an integer value, as a number limit.')?>
                            </p>
                        </div>
                        <div class="sm:col-span-3">
                            <?= FORM::label('feed_elements', __('Advertisements in RSS'), ['class'=>'block text-sm font-medium leading-5 text-gray-700'])?>
                            <div class="mt-1 rounded-md shadow-sm">
                                <?= FORM::input('feed_elements', Core::post('feed_elements', Core::config('advertisement.feed_elements')), [
                                    'placeholder' => '20',
                                    'type' => 'number',
                                    'class' => 'form-input block w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5',
                                ])?>
                            </div>
                            <p class="mt-2 text-sm text-gray-500">
                                <?=__('How many ads are going to appear in the RSS of your site.')?>
                            </p>
                        </div>
                        <div class="sm:col-span-3">
                            <?= FORM::label('map_elements', __('Advertisements in Map'), ['class'=>'block text-sm font-medium leading-5 text-gray-700'])?>
                            <div class="mt-1 rounded-md shadow-sm">
                                <?= FORM::input('map_elements', Core::post('map_elements', Core::config('advertisement.map_elements')), [
                                    'placeholder' => '20',
                                    'type' => 'number',
                                    'class' => 'form-input block w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5',
                                ])?>
                            </div>
                            <p class="mt-2 text-sm text-gray-500">
                                <?=__('How many ads are going to appear in the map of your site.')?>
                            </p>
                        </div>
                        <div class="sm:col-span-4">
                            <?= FORM::label('sort_by', __('Sort by in listing'), ['class'=>'block text-sm font-medium leading-5 text-gray-700'])?>
                            <div class="mt-1 rounded-md shadow-sm">
                                <?= FORM::select('sort_by', ['title-asc'=>"Name (A-Z)",
                                                                                 'title-desc'=>"Name (Z-A)",
                                                                                 'price-asc'=>"Price (Low)",
                                                                                 'price-desc'=>"Price (High)",
                                                                                 'featured'=>"Featured",
                                                                                 'rating'=>"Rating",
                                                                                 'favorited'=>"Favorited",
                                                                                 'published-desc'=>"Newest",
                                                                                 'published-asc'=>"Oldest",
                                                                                 'distance'=>"Distance",
                                                                                 'event-date'=>"Event date"], Core::post('sort_by', Core::config('advertisement.sort_by')), [
                                    'class' => 'form-select block w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5',
                                ])?>
                            </div>
                            <p class="mt-2 text-sm text-gray-500">
                                <?=__('Sort by in listing')?>
                            </p>
                        </div>
                        <div class="sm:col-span-4">
                            <?
                                $ads_in_home = [0=>__('Latest Ads'),
                                                        1=>__('Featured Ads'),
                                                        4=>__('Featured Ads Random'),
                                                        2=>__('Popular Ads last month'),
                                                        3=>__('None')];

                                if(core::config('advertisement.count_visits')==0)
                                {
                                    unset($ads_in_home[2]);
                                }
                            ?>
                            <?= FORM::label('ads_in_home', __('Advertisements in home'), ['class'=>'block text-sm font-medium leading-5 text-gray-700'])?>
                            <div class="mt-1 rounded-md shadow-sm">
                                <?= FORM::select('ads_in_home', $ads_in_home, Core::post('ads_in_home', Core::config('advertisement.ads_in_home')), [
                                    'class' => 'form-select block w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5',
                                ])?>
                            </div>
                            <p class="mt-2 text-sm text-gray-500">
                                <?=__('You can choose what ads you want to display in home.')?>
                            </p>
                        </div>
                        <div class="sm:col-span-6">
                            <div class="absolute flex items-center h-5">
                                <?=FORM::checkbox('delete_ad', 1, (bool) Core::post('delete_ad', Core::config('advertisement.delete_ad')), ['class' => 'form-checkbox h-4 w-4 text-blue-600 transition duration-150 ease-in-out'])?>
                            </div>
                            <div class="pl-7 text-sm leading-5">
                                <?=FORM::label('delete_ad', __('Delete ads'), ['class'=>'font-medium text-gray-700'])?>
                                <p class="text-gray-500">
                                    <?=__('With this option enabled your customers will be able to delete permanently his ads.')?>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="mt-8 border-t border-gray-200 pt-8">
                    <div>
                        <h3 class="text-lg leading-6 font-medium text-gray-900">
                            <?=__('Publish Options')?>
                        </h3>
                    </div>
                    <div class="mt-6 grid grid-cols-1 row-gap-6 col-gap-4 sm:grid-cols-6">
                        <div class="sm:col-span-6">
                            <div class="absolute flex items-center h-5">
                                <?=FORM::checkbox('login_to_post', 1, (bool) Core::post('login_to_post', Core::config('advertisement.login_to_post')), ['class' => 'form-checkbox h-4 w-4 text-blue-600 transition duration-150 ease-in-out'])?>
                            </div>
                            <div class="pl-7 text-sm leading-5">
                                <?=FORM::label('login_to_post', __('Require login to post'), ['class'=>'font-medium text-gray-700'])?>
                                <p class="text-gray-500">
                                    <?=__('Require only the logged in users to post.')?>
                                </p>
                            </div>
                        </div>
                        <div class="sm:col-span-6">
                            <div class="absolute flex items-center h-5">
                                <?=FORM::checkbox('only_admin_post', 1, (bool) Core::post('only_admin_post', Core::config('advertisement.only_admin_post')), ['class' => 'form-checkbox h-4 w-4 text-blue-600 transition duration-150 ease-in-out'])?>
                            </div>
                            <div class="pl-7 text-sm leading-5">
                                <?=FORM::label('only_admin_post', __('Only administrators can publish'), ['class'=>'font-medium text-gray-700'])?>
                                <p class="text-gray-500">
                                    <?=__('Only administrators can publish')?>
                                </p>
                            </div>
                        </div>
                        <div class="sm:col-span-3">
                            <?= FORM::label('expire_date', __('Ad expiration date'), ['class'=>'block text-sm font-medium leading-5 text-gray-700'])?>
                            <div class="mt-1 rounded-md shadow-sm">
                                <?= FORM::input('expire_date', Core::post('expire_date', Core::config('advertisement.expire_date')), [
                                    'type' => 'number',
                                    'class' => 'form-input block w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5',
                                ])?>
                            </div>
                            <p class="mt-2 text-sm text-gray-500">
                                <?=__('"After how many days an Ad will expire. 0 for never')?>
                            </p>
                        </div>
                        <div class="sm:col-span-6">
                            <div class="absolute flex items-center h-5">
                                <?=FORM::checkbox('expire_reactivation', 1, (bool) Core::post('expire_reactivation', Core::config('advertisement.expire_reactivation')), ['class' => 'form-checkbox h-4 w-4 text-blue-600 transition duration-150 ease-in-out'])?>
                            </div>
                            <div class="pl-7 text-sm leading-5">
                                <?=FORM::label('expire_reactivation', __('Allow ad reactivation'), ['class'=>'font-medium text-gray-700'])?>
                                <p class="text-gray-500">
                                    <?=__('Allows reactivate ad after is expired.')?>
                                </p>
                            </div>
                        </div>
                        <div class="sm:col-span-6">
                            <div class="absolute flex items-center h-5">
                                <?=FORM::checkbox('parent_category', 1, (bool) Core::post('parent_category', Core::config('advertisement.parent_category')), ['class' => 'form-checkbox h-4 w-4 text-blue-600 transition duration-150 ease-in-out'])?>
                            </div>
                            <div class="pl-7 text-sm leading-5">
                                <?=FORM::label('parent_category', __('Parent category'), ['class'=>'font-medium text-gray-700'])?>
                                <p class="text-gray-500">
                                    <?=__('Use parent categories')?>
                                </p>
                            </div>
                        </div>
                        <div class="sm:col-span-6">
                            <div class="absolute flex items-center h-5">
                                <?=FORM::checkbox('description_bbcode', 1, (bool) Core::post('description_bbcode', Core::config('advertisement.description_bbcode')), ['class' => 'form-checkbox h-4 w-4 text-blue-600 transition duration-150 ease-in-out'])?>
                            </div>
                            <div class="pl-7 text-sm leading-5">
                                <?=FORM::label('description_bbcode', __('BBCODE editor on description field'), ['class'=>'font-medium text-gray-700'])?>
                                <p class="text-gray-500">
                                    <?=__('BBCODE editor appears in description field.')?>
                                </p>
                            </div>
                        </div>
                        <div class="sm:col-span-6">
                            <div class="absolute flex items-center h-5">
                                <?=FORM::checkbox('captcha', 1, (bool) Core::post('captcha', Core::config('advertisement.captcha')), ['class' => 'form-checkbox h-4 w-4 text-blue-600 transition duration-150 ease-in-out'])?>
                            </div>
                            <div class="pl-7 text-sm leading-5">
                                <?=FORM::label('captcha', __('Captcha'), ['class'=>'font-medium text-gray-700'])?>
                                <p class="text-gray-500">
                                    <?=__('Captcha appears in the form.')?>
                                </p>
                            </div>
                        </div>
                        <div class="sm:col-span-6">
                            <div class="absolute flex items-center h-5">
                                <?=FORM::checkbox('leave_alert', 1, (bool) Core::post('leave_alert', Core::config('advertisement.leave_alert')), ['class' => 'form-checkbox h-4 w-4 text-blue-600 transition duration-150 ease-in-out'])?>
                            </div>
                            <div class="pl-7 text-sm leading-5">
                                <?=FORM::label('leave_alert', __('Leave alert before submitting form'), ['class'=>'font-medium text-gray-700'])?>
                                <p class="text-gray-500">
                                    <?=__('Leave alert before submitting publish new form')?>
                                </p>
                            </div>
                        </div>
                        <div class="sm:col-span-4">
                            <?$pages = [''=>__('Deactivated')]?>
                            <?foreach (Model_Content::get_pages() as $key => $value) {
                                $pages[$value->seotitle] = $value->title;
                            }?>
                            <?= FORM::label('tos', __('Terms of Service'), ['class'=>'block text-sm font-medium leading-5 text-gray-700'])?>
                            <div class="mt-1 rounded-md shadow-sm">
                                <?= FORM::select('tos', $pages, Core::post('tos', Core::config('advertisement.tos')), [
                                    'class' => 'form-select block w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5',
                                ])?>
                            </div>
                            <p class="mt-2 text-sm text-gray-500">
                                <?=__("If you choose to use terms of service, you can select activate. And to edit content, select link 'Content' on your admin panel sidebar. Find page named 'Terms of service' click 'Edit'. In section 'Description' add content that suits you.")?>
                                <a href="ttps://www.shareasale.com/r.cfm?b=854385&u=1782794&m=65338">If you need to generate your terms of service or privacy policy click here.</a>
                            </p>
                        </div>
                        <div class="sm:col-span-4">
                            <?= FORM::label('thanks_page', __('Thanks page'), ['class'=>'block text-sm font-medium leading-5 text-gray-700'])?>
                            <div class="mt-1 rounded-md shadow-sm">
                                <?= FORM::select('thanks_page', $pages, Core::post('thanks_page', Core::config('advertisement.thanks_page')), [
                                    'class' => 'form-select block w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5',
                                ])?>
                            </div>
                            <p class="mt-2 text-sm text-gray-500">
                                <?=__("Content that will be displayed to the user after he publishes an ad")?>
                            </p>
                        </div>
                        <div class="sm:col-span-3">
                            <?= FORM::label('banned_words', __('Banned words'), ['class'=>'block text-sm font-medium leading-5 text-gray-700'])?>
                            <div class="mt-1 rounded-md shadow-sm">
                                <?= FORM::input('banned_words', Core::post('banned_words', Core::config('advertisement.banned_words')), [
                                    'class' => 'form-input block w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5',
                                ])?>
                            </div>
                            <p class="mt-2 text-sm text-gray-500">
                                <?=__('You need to write your banned words to enable the service.')?>
                            </p>
                        </div>
                        <div class="sm:col-span-6">
                            <div class="absolute flex items-center h-5">
                                <?=FORM::checkbox('validate_banned_words', 1, (bool) Core::post('validate_banned_words', Core::config('advertisement.validate_banned_words')), ['class' => 'form-checkbox h-4 w-4 text-blue-600 transition duration-150 ease-in-out'])?>
                            </div>
                            <div class="pl-7 text-sm leading-5">
                                <?=FORM::label('validate_banned_words', __('Validate banned words'), ['class'=>'font-medium text-gray-700'])?>
                                <p class="text-gray-500">
                                    <?=__('Enables banned words validation')?>
                                </p>
                            </div>
                        </div>
                        <div class="sm:col-span-6">
                            <div class="absolute flex items-center h-5">
                                <?=FORM::checkbox('banned_words_among', 1, (bool) Core::post('banned_words_among', Core::config('advertisement.banned_words_among')), ['class' => 'form-checkbox h-4 w-4 text-blue-600 transition duration-150 ease-in-out'])?>
                            </div>
                            <div class="pl-7 text-sm leading-5">
                                <?=FORM::label('banned_words_among', __('Banned words among each word'), ['class'=>'font-medium text-gray-700'])?>
                                <p class="text-gray-500">
                                    <?=__('Enables to ban words among each word')?>
                                </p>
                            </div>
                        </div>
                        <div class="sm:col-span-3">
                            <?= FORM::label('banned_words_replacement', __('Banned words replacement'), ['class'=>'block text-sm font-medium leading-5 text-gray-700'])?>
                            <div class="mt-1 rounded-md shadow-sm">
                                <?= FORM::input('banned_words_replacement', Core::post('banned_words_replacement', Core::config('advertisement.banned_words_replacement')), [
                                    'class' => 'form-input block w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5',
                                ])?>
                            </div>
                            <p class="mt-2 text-sm text-gray-500">
                                <?=__('Banned word replacement replaces selected array with the string you provided')?>
                            </p>
                        </div>
                    </div>
                </div>
                <div class="mt-8 border-t border-gray-200 pt-8">
                    <div>
                        <h3 class="text-lg leading-6 font-medium text-gray-900">
                            <?=__('Advertisement Fields')?>
                        </h3>
                    </div>
                    <div class="mt-6 grid grid-cols-1 row-gap-6 col-gap-4 sm:grid-cols-6">
                        <div class="sm:col-span-3">
                            <div class="absolute flex items-center h-5">
                                <?=FORM::checkbox('description', 1, (bool) Core::post('description', Core::config('advertisement.description')), ['class' => 'form-checkbox h-4 w-4 text-blue-600 transition duration-150 ease-in-out'])?>
                            </div>
                            <div class="pl-7 text-sm leading-5">
                                <?=FORM::label('description', __('Description'), ['class'=>'font-medium text-gray-700'])?>
                                <p class="text-gray-500">
                                    <?=__('Displays the field Description in the Ad form.')?>
                                </p>
                            </div>
                        </div>
                        <div class="sm:col-span-3">
                            <div class="absolute flex items-center h-5">
                                <?=FORM::checkbox('address', 1, (bool) Core::post('address', Core::config('advertisement.address')), ['class' => 'form-checkbox h-4 w-4 text-blue-600 transition duration-150 ease-in-out'])?>
                            </div>
                            <div class="pl-7 text-sm leading-5">
                                <?=FORM::label('address', __('Address'), ['class'=>'font-medium text-gray-700'])?>
                                <p class="text-gray-500">
                                    <?=__('Displays the field Address in the Ad form.')?>
                                </p>
                            </div>
                        </div>
                        <div class="sm:col-span-3">
                            <div class="absolute flex items-center h-5">
                                <?=FORM::checkbox('phone', 1, (bool) Core::post('phone', Core::config('advertisement.phone')), ['class' => 'form-checkbox h-4 w-4 text-blue-600 transition duration-150 ease-in-out'])?>
                            </div>
                            <div class="pl-7 text-sm leading-5">
                                <?=FORM::label('phone', __('Phone'), ['class'=>'font-medium text-gray-700'])?>
                                <p class="text-gray-500">
                                    <?=__('Displays the field Phone in the Ad form.')?>
                                </p>
                            </div>
                        </div>
                        <div class="sm:col-span-3">
                            <div class="absolute flex items-center h-5">
                                <?=FORM::checkbox('website', 1, (bool) Core::post('website', Core::config('advertisement.website')), ['class' => 'form-checkbox h-4 w-4 text-blue-600 transition duration-150 ease-in-out'])?>
                            </div>
                            <div class="pl-7 text-sm leading-5">
                                <?=FORM::label('website', __('Website'), ['class'=>'font-medium text-gray-700'])?>
                                <p class="text-gray-500">
                                    <?=__('Displays the field Website in the Ad form.')?>
                                </p>
                            </div>
                        </div>
                        <div class="sm:col-span-3">
                            <div class="absolute flex items-center h-5">
                                <?=FORM::checkbox('location', 1, (bool) Core::post('location', Core::config('advertisement.location')), ['class' => 'form-checkbox h-4 w-4 text-blue-600 transition duration-150 ease-in-out'])?>
                            </div>
                            <div class="pl-7 text-sm leading-5">
                                <?=FORM::label('location', __('Location'), ['class'=>'font-medium text-gray-700'])?>
                                <p class="text-gray-500">
                                    <?=__('Displays the Select Location in the Ad form.')?>
                                </p>
                            </div>
                        </div>
                        <div class="sm:col-span-3">
                            <div class="absolute flex items-center h-5">
                                <?=FORM::checkbox('price', 1, (bool) Core::post('price', Core::config('advertisement.price')), ['class' => 'form-checkbox h-4 w-4 text-blue-600 transition duration-150 ease-in-out'])?>
                            </div>
                            <div class="pl-7 text-sm leading-5">
                                <?=FORM::label('price', __('Price'), ['class'=>'font-medium text-gray-700'])?>
                                <p class="text-gray-500">
                                    <?=__('Displays the field Price in the Ad form.')?>
                                </p>
                            </div>
                        </div>
                        <div class="sm:col-span-3">
                            <?= FORM::label('num_images', __('Number of images'), ['class'=>'block text-sm font-medium leading-5 text-gray-700'])?>
                            <div class="mt-1 rounded-md shadow-sm">
                                <?= FORM::input('num_images', Core::post('num_images', Core::config('advertisement.num_images')), [
                                    'placeholder' => '20',
                                    'type' => 'number',
                                    'class' => 'form-input block w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5',
                                ])?>
                            </div>
                            <p class="mt-2 text-sm text-gray-500">
                                <?=__('Number of images displayed')?>
                            </p>
                        </div>
                        <?if (!Core::config('advertisement.messaging')) :?>
                            <div class="sm:col-span-3">
                                <div class="absolute flex items-center h-5">
                                    <?=FORM::checkbox('upload_file', 1, (bool) Core::post('upload_file', Core::config('advertisement.upload_file')), ['class' => 'form-checkbox h-4 w-4 text-blue-600 transition duration-150 ease-in-out'])?>
                                </div>
                                <div class="pl-7 text-sm leading-5">
                                    <?=FORM::label('upload_file', __('Upload file'), ['class'=>'font-medium text-gray-700'])?>
                                    <p class="text-gray-500">
                                        <?=__('Allows buyer to upload a file in the Ad contact form.')?>
                                    </p>
                                </div>
                            </div>
                        <?endif?>
                    </div>
                </div>
                <div class="mt-8 border-t border-gray-200 pt-8">
                    <div>
                        <h3 class="text-lg leading-6 font-medium text-gray-900">
                            <?=__('Advertisement Display Options')?>
                        </h3>
                    </div>
                    <div class="mt-6 grid grid-cols-1 row-gap-6 col-gap-4 sm:grid-cols-6">
                        <div class="sm:col-span-6">
                            <div class="absolute flex items-center h-5">
                                <?=FORM::checkbox('contact', 1, (bool) Core::post('contact', Core::config('advertisement.contact')), ['class' => 'form-checkbox h-4 w-4 text-blue-600 transition duration-150 ease-in-out'])?>
                            </div>
                            <div class="pl-7 text-sm leading-5">
                                <?=FORM::label('contact', __('Contact form'), ['class'=>'font-medium text-gray-700'])?>
                                <p class="text-gray-500">
                                    <?=__('Contact form appears in the ad.')?>
                                </p>
                            </div>
                        </div>
                        <div class="sm:col-span-6">
                            <div class="absolute flex items-center h-5">
                                <?=FORM::checkbox('login_to_view_ad', 1, (bool) Core::post('login_to_view_ad', Core::config('advertisement.login_to_view_ad')), ['class' => 'form-checkbox h-4 w-4 text-blue-600 transition duration-150 ease-in-out'])?>
                            </div>
                            <div class="pl-7 text-sm leading-5">
                                <?=FORM::label('login_to_view_ad', __('Require login to contact'), ['class'=>'font-medium text-gray-700'])?>
                                <p class="text-gray-500">
                                    <?=__('Require only the logged in users to contact.')?>
                                </p>
                            </div>
                        </div>
                        <div class="sm:col-span-6">
                            <div class="absolute flex items-center h-5">
                                <?=FORM::checkbox('contact_price', 1, (bool) Core::post('contact_price', Core::config('advertisement.contact_price')), ['class' => 'form-checkbox h-4 w-4 text-blue-600 transition duration-150 ease-in-out'])?>
                            </div>
                            <div class="pl-7 text-sm leading-5">
                                <?=FORM::label('contact_price', __('Price on contact form'), ['class'=>'font-medium text-gray-700'])?>
                                <p class="text-gray-500">
                                    <?=__('Show price field on contact form.')?>
                                </p>
                            </div>
                        </div>
                        <div class="sm:col-span-6">
                            <div class="absolute flex items-center h-5">
                                <?=FORM::checkbox('qr_code', 1, (bool) Core::post('qr_code', Core::config('advertisement.qr_code')), ['class' => 'form-checkbox h-4 w-4 text-blue-600 transition duration-150 ease-in-out'])?>
                            </div>
                            <div class="pl-7 text-sm leading-5">
                                <?=FORM::label('qr_code', __('Show QR code'), ['class'=>'font-medium text-gray-700'])?>
                                <p class="text-gray-500">
                                    <?=__('Show QR code')?>
                                </p>
                            </div>
                        </div>
                        <div class="sm:col-span-6">
                            <div class="absolute flex items-center h-5">
                                <?=FORM::checkbox('count_visits', 1, (bool) Core::post('count_visits', Core::config('advertisement.count_visits')), ['class' => 'form-checkbox h-4 w-4 text-blue-600 transition duration-150 ease-in-out'])?>
                            </div>
                            <div class="pl-7 text-sm leading-5">
                                <?=FORM::label('count_visits', __('Count visits ads'), ['class'=>'font-medium text-gray-700'])?>
                                <p class="text-gray-500">
                                    <?=__('You can choose if you wish to display amount of visits at each advertisement.')?>
                                </p>
                            </div>
                        </div>
                        <div class="sm:col-span-6">
                            <div class="absolute flex items-center h-5">
                                <?=FORM::checkbox('sharing', 1, (bool) Core::post('sharing', Core::config('advertisement.sharing')), ['class' => 'form-checkbox h-4 w-4 text-blue-600 transition duration-150 ease-in-out'])?>
                            </div>
                            <div class="pl-7 text-sm leading-5">
                                <?=FORM::label('sharing', __('Show sharing buttons'), ['class'=>'font-medium text-gray-700'])?>
                                <p class="text-gray-500">
                                    <?=__('You can choose if you wish to display sharing buttons at each advertisement.')?>
                                </p>
                            </div>
                        </div>
                        <div class="sm:col-span-6">
                            <div class="absolute flex items-center h-5">
                                <?=FORM::checkbox('report', 1, (bool) Core::post('report', Core::config('advertisement.report')), ['class' => 'form-checkbox h-4 w-4 text-blue-600 transition duration-150 ease-in-out'])?>
                            </div>
                            <div class="pl-7 text-sm leading-5">
                                <?=FORM::label('report', __('Show Report this ad button'), ['class'=>'font-medium text-gray-700'])?>
                                <p class="text-gray-500">
                                    <?=__('You can choose if you wish to display Report this ad button at each advertisement.')?>
                                </p>
                            </div>
                        </div>
                        <div class="sm:col-span-3">
                            <?= FORM::label('related', __('Related ads'), ['class'=>'block text-sm font-medium leading-5 text-gray-700'])?>
                            <div class="mt-1 rounded-md shadow-sm">
                                <?= FORM::input('related', Core::post('related', Core::config('advertisement.related')), [
                                    'type' => 'number',
                                    'class' => 'form-input block w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5',
                                ])?>
                            </div>
                            <p class="mt-2 text-sm text-gray-500">
                                <?=__('You can choose if you wish to display random related ads at each advertisement')?>
                            </p>
                        </div>
                        <div class="sm:col-span-6">
                            <div class="absolute flex items-center h-5">
                                <?=FORM::checkbox('free', 1, (bool) Core::post('free', Core::config('advertisement.free')), ['class' => 'form-checkbox h-4 w-4 text-blue-600 transition duration-150 ease-in-out'])?>
                            </div>
                            <div class="pl-7 text-sm leading-5">
                                <?=FORM::label('free', __('Show Free tag'), ['class'=>'font-medium text-gray-700'])?>
                                <p class="text-gray-500">
                                    <?=__('You can choose if you wish to display free tag when price is equal to zero.')?>
                                </p>
                            </div>
                        </div>
                        <div class="sm:col-span-6">
                            <div class="absolute flex items-center h-5">
                                <?=FORM::checkbox('rich_snippets', 1, (bool) Core::post('rich_snippets', Core::config('advertisement.rich_snippets')), ['class' => 'form-checkbox h-4 w-4 text-blue-600 transition duration-150 ease-in-out'])?>
                            </div>
                            <div class="pl-7 text-sm leading-5">
                                <?=FORM::label('rich_snippets', __('Rich Snippets'), ['class'=>'font-medium text-gray-700'])?>
                                <p class="text-gray-500">
                                    <?=__('Enables rich snippets for products')?>
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
