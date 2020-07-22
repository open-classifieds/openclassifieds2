<?php defined('SYSPATH') or die('No direct script access.');?>

<?if (!$widget->loaded):?>
    <div x-data="{ open: false }">
        <div class="bg-white shadow sm:rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <div class="sm:flex sm:items-start sm:justify-between">
                    <div>
                        <h3 class="text-lg leading-6 font-medium text-gray-900">
                            <?=$widget->title?>
                        </h3>
                        <?if(Core::is_cloud() AND get_class($widget) == 'Widget_Text' AND Model_Domain::current()->old_domain === NULL):?>
                            <div class="alert alert-warning" role="alert">
                                <?=__('If you want to use Google Adsense banners, they will not be displayed if you use our free domain Yclas.com')?>
                                &nbsp;
                                <a href="https://yclas.com/faq/custom-banners.html" target="_blank">
                                    <?=__('Read more')?> <i class="fa fa-external-link"></i>
                                </a>
                            </div>
                        <?endif?>
                        <div class="mt-2 max-w-xl text-sm leading-5 text-gray-500">
                            <p>
                                <?=$widget->description?>
                            </p>
                        </div>
                    </div>
                    <div class="mt-5 sm:mt-0 sm:ml-6 sm:flex-shrink-0 sm:flex sm:items-center">
                        <span class="inline-flex rounded-md shadow-sm">
                            <button @click="open = true" type="button" class="inline-flex items-center px-4 py-2 border border-transparent text-sm leading-5 font-medium rounded-md text-white bg-blue-600 hover:bg-blue-500 focus:outline-none focus:border-blue-700 focus:shadow-outline-blue active:bg-blue-700 transition ease-in-out duration-150">
                                <?=__('Create')?>
                            </button>
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <?= View::factory('oc-panel/pages/widgets/_modal', ['widget' => $widget, 'tags' => $tags]) ?>
    </div>
<?else:?>
    <li x-data="{ open: false }" class="border-b border-gray-200 liholder" id="<?=$widget->id_name()?>">
        <a @click.prevent="open = true" href="#" class="hover:bg-gray-50 focus:outline-none focus:bg-gray-50 transition duration-150 ease-in-out">
            <div class="flex items-center px-4 py-4 sm:px-6">
                <div class="min-w-0 flex-1 flex items-center">
                    <div class="flex-shrink-0">
                        <svg class="h-4 w-4 text-gray-400 cursor-move" fill="currentColor" viewBox="0 0 32 32">
                            <path d="M9.125 27.438h4.563v4.563H9.125zm9.188 0h4.563v4.563h-4.563zm-9.188-9.125h4.563v4.563H9.125zm9.188 0h4.563v4.563h-4.563zM9.125 9.125h4.563v4.563H9.125zm9.188 0h4.563v4.563h-4.563zM9.125 0h4.563v4.563H9.125zm9.188 0h4.563v4.563h-4.563z"></path>
                        </svg>
                    </div>
                    <div class="min-w-0 flex-1 px-4 md:grid md:grid-cols-2 md:gap-4 items-center">
                        <div>
                            <div class="text-sm leading-5 text-gray-900 truncate"><?=$widget->title()?> <span class="muted"><?=$widget->title?></div>
                        </div>
                    </div>
                </div>
                <div>
                    <svg class="h-5 w-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
                    </svg>
                </div>
            </div>
        </a>

        <?= View::factory('oc-panel/pages/widgets/_modal', ['widget' => $widget, 'tags' => $tags]) ?>
    </li>
<?endif?>
