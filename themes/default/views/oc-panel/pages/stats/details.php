<div class="md:flex md:items-center md:justify-between">
    <div class="flex-1 min-w-0">
        <h2 class="text-2xl font-bold leading-7 text-gray-900 sm:text-3xl sm:leading-9 sm:truncate">
            <?= $title ?>
        </h2>
    </div>
    <div>
        <form
            method="post"
            action="<?= URL::current() ?>"
            x-data
            x-init="
                new Pikaday({
                    field: $refs.fromDate,
                    toString(date, format) {
                        return moment(date).format('YYYY-MM-DD');
                    },
                });

                new Pikaday({
                    field: $refs.toDate,
                    toString(date, format) {
                        return moment(date).format('YYYY-MM-DD');
                    },
                });
            "
        >
            <div class="flex space-x-1 mt-4">
                <div class="flex rounded-md shadow-sm">
                    <span class="inline-flex items-center px-3 rounded-l-md border border-r-0 border-gray-300 bg-gray-50 text-gray-500 sm:text-sm">
                        <?= __('From') ?>
                    </span>
                    <input
                        x-ref="fromDate"
                        x-on:change="$el.submit()"
                        name="from_date"
                        value="<?= $from_date ?>"
                        class="form-input flex-1 block w-full px-3 py-2 rounded-none rounded-r-md sm:text-sm sm:leading-5"
                    />
                </div>
                <div class="flex rounded-md shadow-sm">
                    <span class="inline-flex items-center px-3 rounded-l-md border border-r-0 border-gray-300 bg-gray-50 text-gray-500 sm:text-sm">
                        <?= __('To') ?>
                    </span>
                    <input
                        x-ref="toDate"
                        x-on:change="$el.submit()"
                        name="to_date"
                        value="<?= $to_date ?>"
                        class="form-input flex-1 block w-full px-3 py-2 rounded-none rounded-r-md sm:text-sm sm:leading-5"
                    />
                </div>
                <div x-data="{ open: false }" @keydown.window.escape="open = false" @click.away="open = false" class="relative inline-block text-left">
                    <div>
                        <span class="rounded-md shadow-sm">
                            <button @click="open = !open" type="button" class="inline-flex justify-center w-full rounded-md border border-gray-300 px-4 py-2 bg-white text-sm leading-5 font-medium text-gray-700 hover:text-gray-500 focus:outline-none focus:border-blue-300 focus:shadow-outline-blue active:bg-gray-50 active:text-gray-800 transition ease-in-out duration-150">
                                <svg class="-mr-1 -ml-1 h-5 w-5" fill="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24" stroke="currentColor"><path d="M4 6h16M4 12h16m-7 6h7"></path></svg>
                            </button>
                        </span>
                    </div>
                    <div x-show="open" x-transition:enter="transition ease-out duration-100" x-transition:enter-start="transform opacity-0 scale-95" x-transition:enter-end="transform opacity-100 scale-100" x-transition:leave="transition ease-in duration-75" x-transition:leave-start="transform opacity-100 scale-100" x-transition:leave-end="transform opacity-0 scale-95" class="origin-top-right absolute right-0 mt-2 w-56 rounded-md shadow-lg">
                        <div class="rounded-md bg-white shadow-xs">
                            <div class="py-1">
                                <a class="block px-4 py-2 text-sm leading-5 text-gray-700 hover:bg-gray-100 hover:text-gray-900 focus:outline-none focus:bg-gray-100 focus:text-gray-900" href="?from_date=<?=date('Y-m-d', strtotime('-30 days'))?>&amp;to_date=<?=date('Y-m-d', strtotime('now'))?>"><?=__('Last 30 days')?></a>
                                <a class="block px-4 py-2 text-sm leading-5 text-gray-700 hover:bg-gray-100 hover:text-gray-900 focus:outline-none focus:bg-gray-100 focus:text-gray-900" href="?from_date=<?=date('Y-m-d', strtotime('-1 month'))?>&amp;to_date=<?=date('Y-m-d', strtotime('now'))?>"><?=__('Last month')?></a>
                                <a class="block px-4 py-2 text-sm leading-5 text-gray-700 hover:bg-gray-100 hover:text-gray-900 focus:outline-none focus:bg-gray-100 focus:text-gray-900" href="?from_date=<?=date('Y-m-d', strtotime('-3 months'))?>&amp;to_date=<?=date('Y-m-d', strtotime('now'))?>"><?=__('Last 3 months')?></a>
                                <a class="block px-4 py-2 text-sm leading-5 text-gray-700 hover:bg-gray-100 hover:text-gray-900 focus:outline-none focus:bg-gray-100 focus:text-gray-900" href="?from_date=<?=date('Y-m-d', strtotime('-6 months'))?>&amp;to_date=<?=date('Y-m-d', strtotime('now'))?>"><?=__('Last 6 months')?></a>
                                <a class="block px-4 py-2 text-sm leading-5 text-gray-700 hover:bg-gray-100 hover:text-gray-900 focus:outline-none focus:bg-gray-100 focus:text-gray-900" href="?from_date=<?=date('Y-m-d', strtotime('-1 year'))?>&amp;to_date=<?=date('Y-m-d', strtotime('now'))?>"><?=__('Last year')?></a>
                                <a class="block px-4 py-2 text-sm leading-5 text-gray-700 hover:bg-gray-100 hover:text-gray-900 focus:outline-none focus:bg-gray-100 focus:text-gray-900" href="?from_date=2014-11-01&amp;to_date=<?=date('Y-m-d', strtotime('now'))?>"><?=__('All time')?></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="mt-8">
    <div class="bg-white overflow-hidden shadow rounded-lg">
        <div class="px-4 py-5 sm:p-6">
            <div class="mt-1 text-3xl leading-9 font-semibold text-gray-900">
                <? if ($current_total !== NULL) : ?>
                    <?= Num::format($current_total, 0) ?>
                <? else : ?>
                    --
                <? endif ?>
            </div>
            <div class="mt-5">
                <?
                    $chart_colors = [[
                        'fill' => 'rgba(33,150,243,.1)',
                        'stroke' => 'rgba(33,150,243,.8)',
                        'point' => 'rgba(33,150,243,.8)',
                        'pointStroke' => 'rgba(33,150,243,.8)'],
                   ];
                ?>
                <?= Chart::line($current_by_date, [
                    'height' => 94,
                    'width' => 378,
                    'options' => [
                        'responsive' => true,
                        'maintainAspectRatio' => true,
                        'scaleShowVerticalLines' => false,
                        'scales' => [
                            'xAxes' => [['gridLines'=> ['display' => false]]],
                            'yAxes' => [['ticks'=> ['min' => 0]]]],
                            'legend' => ['display' => false],
                            'tooltipTemplate' => '<%= datasetLabel %><%= value %>',
                            'multiTooltipTemplate' => '<%= datasetLabel %><%= value %>',
                        ]], $chart_colors) ?>
            </div>
        </div>
    </div>
    <div class="mt-5 grid grid-cols-1 gap-5 sm:grid-cols-3">
        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <div class="flex items-center">
                    <div class="w-0 flex-1">
                        <dl>
                            <dt class="text-sm leading-5 font-medium text-gray-500 truncate">
                                <?=__('Current')?>
                            </dt>
                            <dd class="flex items-baseline">
                                <div class="text-2xl leading-8 font-semibold text-gray-900">
                                    <?if ($current_total !== NULL) :?>
                                        <?= Num::format($current_total, 0) ?>
                                    <?else :?>
                                        --
                                    <?endif?>
                                </div>
                                <?if ($current_total !== NULL) :?>
                                    <? if (Num::percent_change($current_total, $past_total) < 0) : ?>
                                        <div class="ml-2 flex items-baseline text-sm leading-5 font-semibold text-red-600">
                                            <svg class="self-center flex-shrink-0 h-5 w-5 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M14.707 10.293a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 111.414-1.414L9 12.586V5a1 1 0 012 0v7.586l2.293-2.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                            </svg>
                                            <span class="sr-only">
                                                <?= __('Decreased by') ?>
                                            </span>
                                            <?=Num::percent_change($current_total, $past_total)?>
                                        </div>
                                    <? else : ?>
                                        <div class="ml-2 flex items-baseline text-sm leading-5 font-semibold text-green-600">
                                            <svg class="self-center flex-shrink-0 h-5 w-5 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M5.293 9.707a1 1 0 010-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 01-1.414 1.414L11 7.414V15a1 1 0 11-2 0V7.414L6.707 9.707a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
                                            </svg>
                                            <span class="sr-only">
                                                <?= __('Increased by') ?>
                                            </span>
                                            <?=Num::percent_change($current_total, $past_total)?>
                                        </div>
                                    <? endif ?>
                                <? endif ?>
                            </dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <div class="flex items-center">
                    <div class="w-0 flex-1">
                        <dl>
                            <dt class="text-sm leading-5 font-medium text-gray-500 truncate">
                                <?=__('1 Month Ago')?>
                            </dt>
                            <dd class="flex items-baseline">
                                <div class="text-2xl leading-8 font-semibold text-gray-900">
                                    <?if ($month_ago_total !== NULL) :?>
                                        <?= Num::format($month_ago_total, 0) ?>
                                    <?else :?>
                                        --
                                    <?endif?>
                                </div>
                                <?if ($month_ago_total !== NULL) :?>
                                    <? if (Num::percent_change($month_ago_total, $past_month_ago_total) < 0) : ?>
                                        <div class="ml-2 flex items-baseline text-sm leading-5 font-semibold text-red-600">
                                            <svg class="self-center flex-shrink-0 h-5 w-5 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M14.707 10.293a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 111.414-1.414L9 12.586V5a1 1 0 012 0v7.586l2.293-2.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                            </svg>
                                            <span class="sr-only">
                                                <?= __('Decreased by') ?>
                                            </span>
                                            <?=Num::percent_change($month_ago_total, $past_month_ago_total)?>
                                        </div>
                                    <? else : ?>
                                        <div class="ml-2 flex items-baseline text-sm leading-5 font-semibold text-green-600">
                                            <svg class="self-center flex-shrink-0 h-5 w-5 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M5.293 9.707a1 1 0 010-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 01-1.414 1.414L11 7.414V15a1 1 0 11-2 0V7.414L6.707 9.707a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
                                            </svg>
                                            <span class="sr-only">
                                                <?= __('Increased by') ?>
                                            </span>
                                            <?=Num::percent_change($month_ago_total, $past_month_ago_total)?>
                                        </div>
                                    <? endif ?>
                                <? endif ?>
                            </dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <div class="flex items-center">
                    <div class="w-0 flex-1">
                        <dl>
                            <dt class="text-sm leading-5 font-medium text-gray-500 truncate">
                                <?=__('3 Months Ago')?>
                            </dt>
                            <dd class="flex items-baseline">
                                <div class="text-2xl leading-8 font-semibold text-gray-900">
                                    <?if ($three_months_ago_total !== NULL) :?>
                                        <?= Num::format($three_months_ago_total, 0) ?>
                                    <?else :?>
                                        --
                                    <?endif?>
                                </div>
                                <?if ($three_months_ago_total !== NULL) :?>
                                    <? if (Num::percent_change($three_months_ago_total, $past_three_months_ago_total) < 0) : ?>
                                        <div class="ml-2 flex items-baseline text-sm leading-5 font-semibold text-red-600">
                                            <svg class="self-center flex-shrink-0 h-5 w-5 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M14.707 10.293a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 111.414-1.414L9 12.586V5a1 1 0 012 0v7.586l2.293-2.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                            </svg>
                                            <span class="sr-only">
                                                <?= __('Decreased by') ?>
                                            </span>
                                            <?=Num::percent_change($three_months_ago_total, $past_three_months_ago_total)?>
                                        </div>
                                    <? else : ?>
                                        <div class="ml-2 flex items-baseline text-sm leading-5 font-semibold text-green-600">
                                            <svg class="self-center flex-shrink-0 h-5 w-5 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M5.293 9.707a1 1 0 010-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 01-1.414 1.414L11 7.414V15a1 1 0 11-2 0V7.414L6.707 9.707a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
                                            </svg>
                                            <span class="sr-only">
                                                <?= __('Increased by') ?>
                                            </span>
                                            <?=Num::percent_change($three_months_ago_total, $past_three_months_ago_total)?>
                                        </div>
                                    <? endif ?>
                                <? endif ?>
                            </dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <div class="flex items-center">
                    <div class="w-0 flex-1">
                        <dl>
                            <dt class="text-sm leading-5 font-medium text-gray-500 truncate">
                                <?=__('6 Months Ago')?>
                            </dt>
                            <dd class="flex items-baseline">
                                <div class="text-2xl leading-8 font-semibold text-gray-900">
                                    <?if ($six_months_ago_total !== NULL) :?>
                                        <?= Num::format($six_months_ago_total, 0) ?>
                                    <?else :?>
                                        --
                                    <?endif?>
                                </div>
                                <?if ($six_months_ago_total !== NULL) :?>
                                    <? if (Num::percent_change($six_months_ago_total, $past_six_months_ago_total) < 0) : ?>
                                        <div class="ml-2 flex items-baseline text-sm leading-5 font-semibold text-red-600">
                                            <svg class="self-center flex-shrink-0 h-5 w-5 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M14.707 10.293a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 111.414-1.414L9 12.586V5a1 1 0 012 0v7.586l2.293-2.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                            </svg>
                                            <span class="sr-only">
                                                <?= __('Decreased by') ?>
                                            </span>
                                            <?=Num::percent_change($six_months_ago_total, $past_six_months_ago_total)?>
                                        </div>
                                    <? else : ?>
                                        <div class="ml-2 flex items-baseline text-sm leading-5 font-semibold text-green-600">
                                            <svg class="self-center flex-shrink-0 h-5 w-5 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M5.293 9.707a1 1 0 010-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 01-1.414 1.414L11 7.414V15a1 1 0 11-2 0V7.414L6.707 9.707a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
                                            </svg>
                                            <span class="sr-only">
                                                <?= __('Increased by') ?>
                                            </span>
                                            <?=Num::percent_change($six_months_ago_total, $past_six_months_ago_total)?>
                                        </div>
                                    <? endif ?>
                                <? endif ?>
                            </dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <div class="flex items-center">
                    <div class="w-0 flex-1">
                        <dl>
                            <dt class="text-sm leading-5 font-medium text-gray-500 truncate">
                                <?=__('12 Months Ago')?>
                            </dt>
                            <dd class="flex items-baseline">
                                <div class="text-2xl leading-8 font-semibold text-gray-900">
                                    <?if ($twelve_months_ago_total !== NULL) :?>
                                        <?= Num::format($twelve_months_ago_total, 0) ?>
                                    <?else :?>
                                        --
                                    <?endif?>
                                </div>
                                <?if ($twelve_months_ago_total !== NULL) :?>
                                    <? if (Num::percent_change($twelve_months_ago_total, $twelve_six_months_ago_total) < 0) : ?>
                                        <div class="ml-2 flex items-baseline text-sm leading-5 font-semibold text-red-600">
                                            <svg class="self-center flex-shrink-0 h-5 w-5 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M14.707 10.293a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 111.414-1.414L9 12.586V5a1 1 0 012 0v7.586l2.293-2.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                            </svg>
                                            <span class="sr-only">
                                                <?= __('Decreased by') ?>
                                            </span>
                                            <?=Num::percent_change($twelve_months_ago_total, $twelve_six_months_ago_total)?>
                                        </div>
                                    <? else : ?>
                                        <div class="ml-2 flex items-baseline text-sm leading-5 font-semibold text-green-600">
                                            <svg class="self-center flex-shrink-0 h-5 w-5 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M5.293 9.707a1 1 0 010-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 01-1.414 1.414L11 7.414V15a1 1 0 11-2 0V7.414L6.707 9.707a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
                                            </svg>
                                            <span class="sr-only">
                                                <?= __('Increased by') ?>
                                            </span>
                                            <?=Num::percent_change($twelve_months_ago_total, $twelve_six_months_ago_total)?>
                                        </div>
                                    <? endif ?>
                                <? endif ?>
                            </dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
