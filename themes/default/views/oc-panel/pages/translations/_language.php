<tr>
    <td class="px-6 py-4 whitespace-no-wrap <?= $last_item ? '' : 'border-b' ?> border-gray-200">
        <div class="text-sm leading-5 text-gray-900">
            <?= $language ?>
        </div>
    </td>
    <td class="px-6 py-4 whitespace-no-wrap text-right <?= $last_item ? '' : 'border-b' ?> border-gray-200 text-sm leading-5 font-medium">
        <a href="<?=Route::url('oc-panel', array('controller'=>'translations','action'=>'edit','id'=>$language))?>?translation_file=apps" class="text-blue-600 hover:text-blue-900">
            Apps
        </a>
    </td>
    <td class="px-6 py-4 whitespace-no-wrap text-right <?= $last_item ? '' : 'border-b' ?> border-gray-200 text-sm leading-5 font-medium">
        <a href="<?=Route::url('oc-panel', array('controller'=>'translations','action'=>'edit','id'=>$language))?>" class="text-blue-600 hover:text-blue-900">
            Web
        </a>
    </td>
    <td class="px-6 py-4 whitespace-no-wrap text-right <?= $last_item ? '' : 'border-b' ?> border-gray-200 text-sm leading-5 font-medium">
        <?if ($language!=$current_language):?>
            <a href="<?=Route::url('oc-panel', array('controller'=>'translations','action'=>'index','id'=>$language))?>" class="text-blue-600 hover:text-blue-900">
                <?= __('Activate') ?>
            </a>
        <? else: ?>
            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                <?= __('Active') ?>
            </span>
        <?endif?>
    </td>
</tr>
