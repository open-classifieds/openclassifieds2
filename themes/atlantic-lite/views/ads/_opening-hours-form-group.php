<? if (isset(Model_Field::get_all()['openinghours'])) : ?>
    <? $openning_hours = json_decode(isset($ad) ? $ad->cf_openinghours : '[]') ?? []; ?>
    <div class="form-row d-none" id="opening-hours-form-group">
        <? $i = 1; foreach (Date::day_labels() as $day => $day_name) : ?>
            <div class="col-sm-6">
                <div class="form-row">
                    <div class="form-group col-12 small">
                        <label><?= $day_name ?></label>
                        <div class="row">
                            <div class="col-6">
                                <div class="form-check">
                                    <?= Form::radio('cf_openinghours[' . $day . '][o]', 1, isset($openning_hours->{$day}) ? (bool) $openning_hours->{$day}->o : NULL, ['class' => 'form-check-input dayopen']) ?>
                                    <label class="form-check-label">
                                        <?= __('Open') ?>
                                    </label>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-check">
                                    <?= Form::radio('cf_openinghours[' . $day . '][o]', 0, isset($openning_hours->{$day}) ? (bool) !$openning_hours->{$day}->o : TRUE, ['class' => 'form-check-input dayopen']) ?>
                                    <label class="form-check-label">
                                        <?= __('Closed') ?>
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="openninghours mt-1 <?= (isset($openning_hours->{$day}) AND $openning_hours->{$day}->o) ? '' : 'd-none'?>">
                            <div class="row">
                                <div class="col-6">
                                    <?=FORM::select('cf_openinghours[' . $day . '][f]',
                                        Date::half_hours_range(),
                                        isset($openning_hours->{$day}) ? $openning_hours->{$day}->f : NULL, array(
                                        'class' => 'form-control disable-select2',
                                    ))?>
                                </div>
                                <div class="col-6">
                                    <?=FORM::select('cf_openinghours[' . $day . '][t]',
                                        Date::half_hours_range('08:30', 39),
                                        isset($openning_hours->{$day}) ? $openning_hours->{$day}->t : NULL, array(
                                        'class' => 'form-control disable-select2',
                                    ))?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <? if ($i % 2 === 0) : ?>
                <div class="clearfix"></div>
            <? endif ?>
        <? $i++; endforeach ?>
    </div>
<? endif ?>
