<?= FORM::open(Route::url('oc-panel', array('controller' => 'myads', 'action' => 'update', 'id' => $ad->id_ad)), array('class' => 'edit_ad_photos_form', 'enctype' => 'multipart/form-data')) ?>
    <div class="card">
        <div class="card-body">
            <h5 class="card-title"><?= _e('Manage Images') ?></h5>

            <? $images = $ad->get_images() ?>
            <? if ($images) : ?>
                <div id="gallery">
                    <div class="row">
                        <? foreach ($images as $key => $value) : ?>
                            <? if (isset($value['thumb'])) : // only formated images (not originals)?>
                                <div id="img<?= $key ?>" class="col col-md-4 edit-image">
                                    <a href="<?=$value['image']?>" class="gallery-item d-block mb-2" data-gallery>
                                        <img src="<?= $value['thumb'] ?>" class="img-thumbnail rounded img-fluid">
                                    </a>
                                    <button
                                        class="btn btn-danger index-delete img-delete"
                                        data-title="<?= __('Are you sure you want to delete?') ?>"
                                        data-btnOkLabel="<?= __('Yes, definitely!') ?>"
                                        data-btnCancelLabel="<?= __('No way!') ?>"
                                        type="submit"
                                        name="img_delete"
                                        value="<?= $key ?>"
                                        href="<?= Route::url('oc-panel', array('controller' => 'myads', 'action' => 'update', 'id' => $ad->id_ad)) ?>"
                                    >
                                        <?= _e('Delete') ?>
                                    </button>
                                    <? if ($key > 1) : ?>
                                        <button
                                            class="btn btn-info img-primary"
                                            type="submit"
                                            name="primary_image"
                                            value="<?= $key ?>"
                                            href="<?= Route::url('oc-panel', array('controller' => 'myads', 'action' => 'update', 'id' => $ad->id_ad)) ?>"
                                            action="<?= Route::url('oc-panel', array('controller' => 'myads', 'action' => 'update', 'id' => $ad->id_ad)) ?>"
                                        >
                                            <?= _e('Primary image') ?>
                                        </button>
                                    <? endif ?>
                                </div>
                            <? endif ?>
                        <? endforeach ?>
                    </div>
                </div>
            <? endif ?>

            <hr>

            <div
                class="form-group images"
                data-max-files="<?= (core::config("advertisement.num_images") - core::count($images)) ?>"
                data-max-image-size="<?=core::config('image.max_image_size')?>"
                data-image-width="<?=core::config('image.width')?>"
                data-image-height="<?=core::config('image.height') ? core::config('image.height') : ''?>"
            >
                <label><?=_e('Add image')?></label>
                <div class="dropzone" id="images-dropzone"></div>
            </div>

            <div class="form-group">
                <?= FORM::button('submit_btn', _e('Upload'), array('id' => 'upload-photos-btn', 'type' => 'submit', 'class' => 'btn btn-primary', 'action' => Route::url('oc-panel', array('controller' => 'myads', 'action' => 'update', 'id' => $ad->id_ad)))) ?>
            </div>
        </div>
    </div>
<?= FORM::close() ?>

<div id="blueimp-gallery" class="blueimp-gallery blueimp-gallery-controls">
    <div class="slides"></div>
    <h3 class="title"></h3>
    <a class="prev">‹</a>
    <a class="next">›</a>
    <a class="close">×</a>
    <a class="play-pause"></a>
    <ol class="indicator"></ol>
</div>
