<input type="hidden" name="auto_locate" value="<?= core::config('general.auto_locate') ?>">

<? if (core::count($auto_located_locations) > 0) : ?>
    <div class="modal fade" id="auto-locations" tabindex="-1" role="dialog" aria-labelledby="autoLocations" aria-hidden="true">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 id="autoLocations" class="modal-title"><?= _e('Please choose your closest location') ?></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="<?= __('Close') ?>">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="list-group">
                        <? foreach ($auto_located_locations as $location) : ?>
                            <a href="<?= Route::url('default') ?>" class="list-group-item list-group-item-action" data-id="<?= $location->id_location ?>">
                                <span class="pull-right"><span class="fas fa-angle-right"></span></span>

                                <?= $location->name ?> (<?= i18n::format_measurement($location->distance) ?>)
                            </a>
                        <? endforeach ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
<? endif ?>
