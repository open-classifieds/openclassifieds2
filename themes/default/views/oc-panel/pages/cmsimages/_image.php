<tr>
    <td class="px-6 py-4 whitespace-no-wrap <?= $last_item ? '' : 'border-b' ?> border-gray-200">
        <a href="<?=$image['url']?>" target="_blank"><img src="<?=$image['url']?>"></a>
    </td>
    <td class="px-6 py-4 whitespace-no-wrap <?= $last_item ? '' : 'border-b' ?> border-gray-200">
        <div class="text-sm leading-5 text-gray-900">
            <?=$image['name']?>
        </div>
    </td>
    <td class="px-6 py-4 whitespace-no-wrap <?= $last_item ? '' : 'border-b' ?> border-gray-200">
        <div class="text-sm leading-5 text-gray-900">
            <?=$image['url']?>
        </div>
    </td>
    <td class="px-6 py-4 whitespace-no-wrap text-right <?= $last_item ? '' : 'border-b' ?> border-gray-200 text-sm leading-5 font-medium">
        <a href="<?=Route::url('oc-panel', ['controller'=> Request::current()->controller(), 'action'=>'delete'])?>?name=<?=$image['name']?>" class="text-red-600 hover:text-red-900">
            <?= __('Delete') ?>
        </a>
    </td>
</tr>
