<div class="md:flex md:items-center md:justify-between">
    <div class="flex-1 min-w-0">
        <h2 class="text-2xl font-bold leading-7 text-gray-900 sm:text-3xl sm:leading-9 sm:truncate">
            <?= __('Addons') ?>
        </h2>

        <div class="mt-1 sm:mt-0">
            <?= View::factory('oc-panel/components/learn-more', ['url' => 'https://guides.yclas.com/#/Addons?id=addons']) ?>
        </div>
    </div>
</div>

<div class="bg-white shadow overflow-hidden sm:rounded-md mt-8">
    <ul>
        <? foreach ($addons as $key => $addon) : ?>
            <? $last_item = $key === count($addons) - 1 ?>
            <li class="<?= $last_item ? '' : 'border-b border-gray-200' ?>">
                <a href="<?= Route::url('oc-panel/addons', ['controller' => $addon['name']]) ?>" class="block hover:bg-gray-50 focus:outline-none focus:bg-gray-50 transition duration-150 ease-in-out">
                    <div class="px-4 py-4 flex items-center sm:px-6">
                        <div class="min-w-0 flex-1 sm:flex sm:items-center sm:justify-between">
                            <div>
                                <div class="text-base leading-5 font-medium text-gray-600 truncate">
                                    <?= $addon['label'] ?>
                                    <? if ($addon['is_pro']): ?>
                                        <?= View::factory('oc-panel/components/pro-badge') ?>
                                    <? endif ?>
                                </div>
                                <div class="mt-2 flex">
                                    <div class="text-sm leading-5 text-gray-500">
                                        <?= $addon['description'] ?>
                                    </div>
                                </div>
                            </div>
                            <div class="mt-4 flex-shrink-0 sm:mt-0">
                                <? if (Core::config($addon['config_name'])): ?>
                                    <div class="px-2 inline-flex text-sm leading-5 truncate rounded-full bg-green-100 text-green-800">
                                        <?= __('Active') ?>
                                    </div>
                                <? else: ?>
                                    <div class="text-sm leading-5 text-gray-500 truncate">
                                        <?= __('Configure') ?>
                                    </div>
                                <? endif ?>
                            </div>
                        </div>
                        <div class="ml-5 flex-shrink-0">
                            <svg class="h-5 w-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                    </div>
                </a>
            </li>
        <? endforeach ?>
    </ul>
</div>
