<tr>
    <td class="px-6 py-4 whitespace-no-wrap <?= $last_item ? '' : 'border-b' ?> border-gray-200">
        <div class="text-sm leading-5 text-gray-900"><?= $page->title ?></div>
        <div class="text-sm leading-5 text-gray-500">
            <?if ($page->status==1):?>
                <a title="<?=HTML::chars($page->title)?>" href="<?=Route::url('page', ['seotitle'=>$page->seotitle])?>">
                    <?=Route::url('page', ['seotitle'=>$page->seotitle])?>
                </a>
            <?else:?>
                <?=Route::url('page', ['seotitle'=>$page->seotitle])?>
            <?endif?>
        </div>
    </td>
    <td class="px-6 py-4 whitespace-no-wrap <?= $last_item ? '' : 'border-b' ?> border-gray-200">
        <div class="text-sm leading-5 text-gray-900"><?= $page->seotitle ?></div>
    </td>
    <td class="px-6 py-4 whitespace-no-wrap <?= $last_item ? '' : 'border-b' ?> border-gray-200">
        <?if ($page->status==1):?>
            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                <?= __('Active') ?>
            </span>
        <?else:?>
            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                <?= __('Inactive') ?>
            </span>
        <?endif?>
    </td>
    <td class="px-6 py-4 whitespace-no-wrap text-right <?= $last_item ? '' : 'border-b' ?> border-gray-200 text-sm leading-5 font-medium">
        <a href="<?=Route::url('oc-panel', ['controller' => 'pages', 'action'=>'update','id' => $page->id_content])?>" class="text-blue-600 hover:text-blue-900">
            <?= __('Edit') ?>
        </a>
    </td>
</tr>
