<div class="md:flex md:items-center md:justify-between">
    <div class="flex-1 min-w-0">
        <h2 class="text-2xl font-bold leading-7 text-gray-900 sm:text-3xl sm:leading-9 sm:truncate">
            <?= __('Site usage statistics') ?>
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
    <div class="mt-5 grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-3">
        <?= View::factory('oc-panel/pages/stats/_stat', [
            'label' => __('Ads'),
            'total' => $ads_total,
            'total_past' => $ads_total_past,
            'detail_action' => 'ads',
            'chart' => $ads,
            'chart_config' => $chart_config,
            'chart_colors' => $chart_colors,
            'days_ago' => $days_ago,
        ]) ?>

        <?= View::factory('oc-panel/pages/stats/_stat', [
            'label' => __('Users'),
            'total' => $users_total,
            'total_past' => $users_total_past,
            'detail_action' => 'users',
            'chart' => $users,
            'chart_config' => $chart_config,
            'chart_colors' => $chart_colors,
            'days_ago' => $days_ago,
        ]) ?>

        <?= View::factory('oc-panel/pages/stats/_stat', [
            'label' => __('Visits'),
            'total' => $visits_total,
            'total_past' => $visits_total_past,
            'detail_action' => 'visits',
            'chart' => $visits,
            'chart_config' => $chart_config,
            'chart_colors' => $chart_colors,
            'days_ago' => $days_ago,
        ]) ?>

        <?= View::factory('oc-panel/pages/stats/_stat', [
            'label' => __('Contacts'),
            'total' => $contacts_total,
            'total_past' => $contacts_total_past,
            'detail_action' => 'contacts',
            'chart' => $contacts,
            'chart_config' => $chart_config,
            'chart_colors' => $chart_colors,
            'days_ago' => $days_ago,
        ]) ?>

        <?= View::factory('oc-panel/pages/stats/_stat', [
            'label' => __('Paid Orders'),
            'total' => $paid_orders_total,
            'total_past' => $paid_orders_total_past,
            'detail_action' => 'paid_orders',
            'chart' => $paid_orders,
            'chart_config' => $chart_config,
            'chart_colors' => $chart_colors,
            'days_ago' => $days_ago,
        ]) ?>

        <?= View::factory('oc-panel/pages/stats/_stat', [
            'label' => __('Sales'),
            'total' => $sales_total,
            'total_past' => $sales_total_past,
            'detail_action' => 'sales',
            'chart' => $sales,
            'chart_config' => $chart_config,
            'chart_colors' => $chart_colors,
            'days_ago' => $days_ago,
        ]) ?>
    </div>
</div>
