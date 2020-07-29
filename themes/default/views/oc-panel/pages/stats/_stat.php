<div class="bg-white overflow-hidden shadow rounded-lg">
    <div class="px-4 py-5 sm:p-6">
        <div class="flex items-center">
            <div class="w-0 flex-1">
                <dl>
                    <dt class="text-sm leading-5 font-medium text-gray-500 truncate">
                        <?= $label ?>
                    </dt>
                    <dd class="flex items-baseline">
                        <div class="text-2xl leading-8 font-semibold text-gray-900">
                            <?=Num::format($total, 0)?>
                        </div>
                        <? if (Num::percent_change($total, $total_past) < 0) : ?>
                            <div class="ml-2 flex items-baseline text-sm leading-5 font-semibold text-red-600">
                                <svg class="self-center flex-shrink-0 h-5 w-5 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M14.707 10.293a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 111.414-1.414L9 12.586V5a1 1 0 012 0v7.586l2.293-2.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                </svg>
                                <span class="sr-only">
                                    <?= __('Decreased by') ?>
                                </span>
                                <?=Num::percent_change($total, $total_past)?>
                            </div>
                        <? else : ?>
                            <div class="ml-2 flex items-baseline text-sm leading-5 font-semibold text-green-600">
                                <svg class="self-center flex-shrink-0 h-5 w-5 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 9.707a1 1 0 010-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 01-1.414 1.414L11 7.414V15a1 1 0 11-2 0V7.414L6.707 9.707a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
                                </svg>
                                <span class="sr-only">
                                    <?= __('Increased by') ?>
                                </span>
                                <?=Num::percent_change($total, $total_past)?>
                            </div>
                        <? endif ?>
                        <div class="ml-2 flex items-baseline text-sm leading-5 font-semibold text-gray-500">
                            <?=sprintf(__('%s days ago'), $days_ago)?>
                        </div>
                    </dd>
                </dl>
            </div>
        </div>
        <div>
            <?=Chart::line($chart, $chart_config, $chart_colors)?>
        </div>
    </div>
    <div class="bg-gray-50 px-4 py-4 sm:px-6">
        <div class="text-sm leading-5">
            <a href="<?=Route::url('oc-panel', ['controller' => 'stats', 'action'=> $detail_action])?>?<?=http_build_query(['rel' => ''] + Request::current()->query())?>" class="font-medium text-blue-600 hover:text-blue-500 transition ease-in-out duration-150">
                View all
            </a>
        </div>
    </div>
</div>
