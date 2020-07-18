<div class="modal fade" id="<?= $modal_id ?>" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <? if (isset($modal_title)) : ?>
                <div class="modal-header">
                    <h5 class="modal-title"><?= $modal_title ?></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            <?endif?>
            <div class="modal-body">
                <?= $modal_body ?>
            </div>
            <? if (isset($modal_footer)) : ?>
                <div class="modal-footer">
                    <?= $modal_footer ?>
                </div>
            <? endif ?>
        </div>
    </div>
</div>
