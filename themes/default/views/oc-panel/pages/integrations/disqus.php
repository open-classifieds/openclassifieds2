<div class="md:flex md:items-center md:justify-between">
    <div class="flex-1 min-w-0">
        <h2 class="text-2xl font-bold leading-7 text-gray-900 sm:text-3xl sm:leading-9 sm:truncate">
            <?= __('Disqus') ?>
        </h2>

        <div class="mt-1 sm:mt-0">
            <?= View::factory('oc-panel/components/learn-more', ['url' => 'https://guides.yclas.com/#/Publish-options-active-comments-with-disquse']) ?>
        </div>
    </div>
</div>

<?= Form::open(Route::url('oc-panel/integrations', ['controller' => 'disqus'])) ?>
    <div class="bg-white shadow sm:rounded-lg mt-8">
        <div class="px-4 py-5 sm:p-6">
            <div>
                <div class="grid grid-cols-1 row-gap-6 col-gap-4 sm:grid-cols-6">
                    <div class="sm:col-span-4">
                        <?= FORM::label('advertisement_disqus', __('Disqus for advertisements'), array('class'=>'block text-sm font-medium leading-5 text-gray-700'))?>
                        <div class="mt-1 rounded-md shadow-sm">
                            <?= FORM::input('advertisement_disqus', Core::post('advertisement_disqus', Core::config('advertisement.disqus')), [
                                'class' => 'form-input block w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5',
                                'id' => 'advertisement_disqus',
                            ])?>
                        </div>
                        <p class="mt-2 text-sm text-gray-500">
                            <?=__("You need to write your disqus ID to enable the service.")?>
                        </p>
                    </div>
                    <div class="sm:col-span-4">
                        <?= FORM::label('blog_disqus', __('Disqus for blog'), array('class'=>'block text-sm font-medium leading-5 text-gray-700'))?>
                        <div class="mt-1 rounded-md shadow-sm">
                            <?= FORM::input('blog_disqus', Core::post('blog_disqus', Core::config('general.blog_disqus')), [
                                'class' => 'form-input block w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5',
                                'id' => 'blog_disqus',
                            ])?>
                        </div>
                        <p class="mt-2 text-sm text-gray-500">
                            <?=__("You need to write your disqus ID to enable the service.")?>
                        </p>
                    </div>
                    <div class="sm:col-span-4">
                        <?= FORM::label('faq_disqus', __('Disqus for FAQ'), array('class'=>'block text-sm font-medium leading-5 text-gray-700'))?>
                        <div class="mt-1 rounded-md shadow-sm">
                            <?= FORM::input('faq_disqus', Core::post('faq_disqus', Core::config('general.faq_disqus')), [
                                'class' => 'form-input block w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5',
                                'id' => 'faq_disqus',
                            ])?>
                        </div>
                        <p class="mt-2 text-sm text-gray-500">
                            <?=__("You need to write your disqus ID to enable the service.")?>
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
