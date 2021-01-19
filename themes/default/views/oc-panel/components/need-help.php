<? if (Core::extra_features() OR Core::is_cloud()) : ?>
    <a href="https://yclas.com/panel/support/index" target="_blank" class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium leading-4 bg-gray-200 shadow-sm text-gray-600 hover:text-gray-900">
        <?= __('Need help?') ?>
    </a>
<? else : ?>
    <a href="https://guides.yclas.com" target="_blank" class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium leading-4 bg-gray-200 shadow-sm text-gray-600 hover:text-gray-900">
        <?= __('Need help?') ?>
    </a>
<? endif ?>
