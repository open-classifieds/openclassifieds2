<div class="md:flex md:items-center md:justify-between">
    <div class="flex-1 min-w-0">
        <h2 class="text-2xl font-bold leading-7 text-gray-900 sm:text-3xl sm:leading-9 sm:truncate">
            <?= __('Integrations') ?>
        </h2>

        <div class="mt-1 sm:mt-0">
            <?= View::factory('oc-panel/components/learn-more', ['url' => 'https://guides.yclas.com/#/Integrations?id=integrations']) ?>
        </div>
    </div>
</div>

<div class="mt-8">
    <div>
        <h3 class="text-xs leading-4 font-semibold text-gray-500 uppercase tracking-wider">
            <?= __('Popular') ?>
        </h3>
    </div>
    <div class="bg-white shadow overflow-hidden sm:rounded-md mt-2">
        <ul class="mt-2">
            <? foreach ($popular_integrations as $key => $integration) : ?>
                <? $last_item = $key === count($integrations) - 1 ?>
                <li class="<?= $last_item ? '' : 'border-b border-gray-200' ?>">
                    <a href="<?= Route::url('oc-panel/integrations', ['controller' => $integration['name']]) ?>" class="block hover:bg-gray-50 focus:outline-none focus:bg-gray-50 transition duration-150 ease-in-out">
                        <div class="px-4 py-4 flex items-center sm:px-6">
                            <? if($integration['logo']) : ?>
                                <div class="flex-shrink-0 mr-4">
                                    <img class="h-12 w-12" src="<?= $integration['logo'] ?>" />
                                </div>
                            <? endif ?>
                            <div class="min-w-0 flex-1 sm:flex sm:items-center sm:justify-between">
                                <div>
                                    <div class="text-base leading-5 font-medium text-gray-600 truncate">
                                        <?= $integration['label'] ?>
                                    </div>
                                    <div class="mt-2 flex">
                                        <div class="text-sm leading-5 text-gray-500">
                                            <?= $integration['description'] ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="mt-4 flex-shrink-0 sm:mt-0">
                                    <? if (Core::config($integration['config_name'])): ?>
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
</div>

<div class="mt-8">
    <div class="bg-white shadow overflow-hidden sm:rounded-md mt-2">
        <ul class="mt-2">
            <? foreach ($integrations as $key => $integration) : ?>
                <? $last_item = $key === count($integrations) - 1 ?>
                <li class="<?= $last_item ? '' : 'border-b border-gray-200' ?>">
                    <a href="<?= Route::url('oc-panel/integrations', ['controller' => $integration['name']]) ?>" class="block hover:bg-gray-50 focus:outline-none focus:bg-gray-50 transition duration-150 ease-in-out">
                        <div class="px-4 py-4 flex items-center sm:px-6">
                            <? if($integration['logo']) : ?>
                                <div class="flex-shrink-0 mr-4">
                                    <img class="h-12 w-12" src="<?= $integration['logo'] ?>" />
                                </div>
                            <? endif ?>
                            <div class="min-w-0 flex-1 sm:flex sm:items-center sm:justify-between">
                                <div>
                                    <div class="text-base leading-5 font-medium text-gray-600 truncate">
                                        <?= $integration['label'] ?>
                                    </div>
                                    <div class="mt-2 flex">
                                        <div class="text-sm leading-5 text-gray-500">
                                            <?= $integration['description'] ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="mt-4 flex-shrink-0 sm:mt-0">
                                    <? if (Core::config($integration['config_name'])): ?>
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
</div>

<div class="mt-8">
    <div>
        <h3 class="text-xs leading-4 font-semibold text-gray-500 uppercase tracking-wider">
            <?= __('Email') ?>
        </h3>
    </div>
    <div class="bg-white shadow overflow-hidden sm:rounded-md mt-2">
        <ul class="mt-2">
            <? foreach ($email_integrations as $key => $integration) : ?>
                <? $last_item = $key === count($integrations) - 1 ?>
                <li class="<?= $last_item ? '' : 'border-b border-gray-200' ?>">
                    <a href="<?= Route::url('oc-panel/integrations', ['controller' => $integration['name']]) ?>" class="block hover:bg-gray-50 focus:outline-none focus:bg-gray-50 transition duration-150 ease-in-out">
                        <div class="px-4 py-4 flex items-center sm:px-6">
                            <? if($integration['logo']) : ?>
                                <div class="flex-shrink-0 mr-4">
                                    <img class="h-12 w-12" src="<?= $integration['logo'] ?>" />
                                </div>
                            <? endif ?>
                            <div class="min-w-0 flex-1 sm:flex sm:items-center sm:justify-between">
                                <div>
                                    <div class="text-base leading-5 font-medium text-gray-600 truncate">
                                        <?= $integration['label'] ?>
                                    </div>
                                    <div class="mt-2 flex">
                                        <div class="text-sm leading-5 text-gray-500">
                                            <?= $integration['description'] ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="mt-4 flex-shrink-0 sm:mt-0">
                                    <? if (Core::config($integration['config_name'])): ?>
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
</div>

<div class="mt-8">
    <div>
        <h3 class="text-xs leading-4 font-semibold text-gray-500 uppercase tracking-wider">
            <?= __('Payments') ?>
        </h3>
    </div>
    <div class="bg-white shadow overflow-hidden sm:rounded-md mt-2">
        <ul class="mt-2">
            <? foreach ($payment_integrations as $key => $integration) : ?>
                <? $last_item = $key === count($integrations) - 1 ?>
                <li class="<?= $last_item ? '' : 'border-b border-gray-200' ?>">
                    <a href="<?= Route::url('oc-panel/integrations', ['controller' => $integration['name']]) ?>" class="block hover:bg-gray-50 focus:outline-none focus:bg-gray-50 transition duration-150 ease-in-out">
                        <div class="px-4 py-4 flex items-center sm:px-6">
                            <? if($integration['logo']) : ?>
                                <div class="flex-shrink-0 mr-4">
                                    <img class="h-12 w-12" src="<?= $integration['logo'] ?>" />
                                </div>
                            <? endif ?>
                            <div class="min-w-0 flex-1 sm:flex sm:items-center sm:justify-between">
                                <div>
                                    <div class="text-base leading-5 font-medium text-gray-600 truncate">
                                        <?= $integration['label'] ?>
                                    </div>
                                    <div class="mt-2 flex">
                                        <div class="text-sm leading-5 text-gray-500">
                                            <?= $integration['description'] ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="mt-4 flex-shrink-0 sm:mt-0">
                                    <? if (Core::config($integration['config_name'])): ?>
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
</div>

<div class="mt-8">
    <div>
        <h3 class="text-xs leading-4 font-semibold text-gray-500 uppercase tracking-wider">
            <?= __('Social') ?>
        </h3>
    </div>
    <div class="bg-white shadow overflow-hidden sm:rounded-md mt-2">
        <ul class="mt-2">
            <? foreach ($social_integrations as $key => $integration) : ?>
                <? $last_item = $key === count($integrations) - 1 ?>
                <li class="<?= $last_item ? '' : 'border-b border-gray-200' ?>">
                    <a href="<?= Route::url('oc-panel/integrations', ['controller' => $integration['name']]) ?>" class="block hover:bg-gray-50 focus:outline-none focus:bg-gray-50 transition duration-150 ease-in-out">
                        <div class="px-4 py-4 flex items-center sm:px-6">
                            <? if($integration['logo']) : ?>
                                <div class="flex-shrink-0 mr-4">
                                    <img class="h-12 w-12" src="<?= $integration['logo'] ?>" />
                                </div>
                            <? endif ?>
                            <div class="min-w-0 flex-1 sm:flex sm:items-center sm:justify-between">
                                <div>
                                    <div class="text-base leading-5 font-medium text-gray-600 truncate">
                                        <?= $integration['label'] ?>
                                    </div>
                                    <div class="mt-2 flex">
                                        <div class="text-sm leading-5 text-gray-500">
                                            <?= $integration['description'] ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="mt-4 flex-shrink-0 sm:mt-0">
                                    <? if (Core::config($integration['config_name'])): ?>
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
</div>
