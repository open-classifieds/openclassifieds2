<div class="card mb-4">
    <?= FORM::open(Route::url('oc-panel', ['controller' => 'profile', 'action' => 'image']), ['method' => 'post', 'class' => 'upload_image', 'enctype'=>'multipart/form-data']) ?>
        <div class="card-body">
            <?=Form::errors()?>

            <h5 class="card-title"><?= _e('Profile pictures') ?></h5>

            <div
                class="form-group images"
                data-max-image-size="<?= Core::config('image.max_image_size') ?>"
                data-image-width="<?= Core::config('image.width') ?>"
                data-image-height="<?= Core::config('image.height') ? Core::config('image.height') : 0 ?>"
                data-image-quality="<?= Core::config('image.quality') ?>"
                data-swaltext="<?= sprintf(__('Is not of valid size. Size is limited to %s MB per image'), Core::config('image.max_image_size')) ?>"
            >
                <? $images = $user->get_profile_images() ?>

                <? if($images): ?>
                    <div class="row">
                        <? foreach ($images as $key => $image): ?>
                            <div id="img<?= $key ?>" class="col-6 col-md-4">
                                <p>
                                    <img src="<?= $image ?>" class="img-thumbnail img-fluid">
                                </p>

                                <?if ($key > 0) : ?>
                                    <button
                                        class="btn btn-danger index-delete img-delete"
                                        data-title="<?= __('Are you sure you want to delete?') ?>"
                                        data-btnOkLabel="<?= __('Yes, definitely!') ?>"
                                        data-btnCancelLabel="<?= __('No way!') ?>"
                                        type="submit"
                                        name="img_delete"
                                        value="<?= $key ?>"
                                        href="<?= Route::url('oc-panel', ['controller' => 'profile', 'action' => 'image']) ?>"
                                    >
                                            <?= _e('Delete') ?>
                                    </button>
                                <?endif ?>

                                <?if ($key > 1) : ?>
                                    <button
                                        class="btn btn-info img-primary"
                                        type="submit"
                                        name="primary_image"
                                        value="<?= $key ?>"
                                        href="<?= Route::url('oc-panel', ['controller' => 'profile', 'action' => 'image']) ?>"
                                        action="<?= Route::url('oc-panel', ['controller' => 'profile', 'action' => 'image']) ?>"
                                    >
                                        <?=_e('Primary image') ?>
                                    </button>
                                <?endif ?>
                            </div>
                        <?endforeach ?>
                    </div>
                <?endif ?>
            </div>
        </div>

        <? if(Core::config('advertisement.num_images') > Core::count($images)): ?>
            <div class="card-body border-top">
                <div class="form-group">
                    <h6 class="card-title"><?= _e('Add image') ?></h6>

                    <div>
                        <? for ($i = 0; $i < (Core::config('advertisement.num_images') - core::count($images)); $i++): ?>
                            <div class="fileinput fileinput-new <?= ($i >= 1) ? 'hidden' : NULL ?>" data-provides="fileinput">
                                <div class="fileinput-preview img-thumbnail" data-trigger="fileinput" style="width: 200px; height: 150px;"></div>

                                <div>
                                    <span class="btn btn-light btn-file">
                                        <span class="fileinput-new"><?= _e('Select') ?></span>
                                        <span class="fileinput-exists"><?= _e('Edit') ?></span>
                                        <input type="file" name="<?= 'image'.$i ?>" id="<?= 'fileInput'.$i ?>" accept="<?= 'image/'.str_replace(',', ', image/', rtrim(Core::config('image.allowed_formats'),',')) ?>">
                                    </span>

                                    <a href="#" class="btn btn-light fileinput-exists" data-dismiss="fileinput"><?=_e('Delete')?></a>
                                </div>
                            </div>
                        <? endfor ?>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary"><?= _e('Upload') ?></button>
            </div>
        <? endif ?>
    <?= FORM::close() ?>
</div>
