<tr x-data="{ removed: false }" x-show="!removed">
    <td><a target="_blank" href="<?=Route::url('ad', array('controller'=>'ad','category'=>$favorite->ad->category->seoname,'seotitle'=>$favorite->ad->seotitle))?>"><?= wordwrap($favorite->ad->title, 150, "<br />\n"); ?></a></td>
    <td><?= Date::format($favorite->created, core::config('general.date_format'))?></td>
    <td style="width: 1%">
        <a
            href="<?=Route::url('oc-panel', ['controller' => 'profile', 'action' => 'favorites', 'id' => $favorite->id_ad])?>"
            class="btn btn-danger"
            @click.prevent="
                swal({
                    title: '<?=__('Are you sure you want to delete?')?>',
                    type: 'info',
                    showCancelButton: true,
                    allowOutsideClick: true,
                    confirmButtonColor: '#DD6B55',
                    confirmButtonText: '<?= __('Yes, definitely!') ?>',
                    cancelButtonText: '<?= __('No way!') ?>',
                },
                function() {
                    fetch('<?=Route::url('oc-panel', ['controller' => 'profile', 'action' => 'favorites', 'id' => $favorite->id_ad])?>')
                        .then(response => response.text())
                        .then(html => { removed = true })
                })
            ">
            <i class="fas fa-trash"></i>
        </a>
    </td>
</tr>
