<? if (isset(Model_Field::get_all()['openinghours'])) : ?>
    <? $openning_hours = json_decode(isset($ad) ? $ad->cf_openinghours : '[]') ?? []; ?>
    <div class="form-group hidden" id="opening-hours-form-group">
        <? $i = 1; foreach (Date::day_labels() as $day => $day_name) : ?>
            <div class="col-md-6">
                <div class="form-group small">
                    <label class="col-md-6"><?= $day_name ?></label>
                    <div class="col-md-6 text-right">
                        <label class="radio-inline" style="padding-top: 0;">
                            <?= Form::radio('cf_openinghours[' . $day . '][o]', 1, isset($openning_hours->{$day}) ? (bool) $openning_hours->{$day}->o : NULL, ['class' => 'dayopen']) ?>
                            <?= __('Open') ?>
                        </label>
                        <label class="radio-inline" style="padding-top: 0;">
                            <?= Form::radio('cf_openinghours[' . $day . '][o]', 0, isset($openning_hours->{$day}) ? (bool) !$openning_hours->{$day}->o : TRUE, ['class' => 'dayopen']) ?>
                            <?= __('Closed') ?>
                        </label>
                    </div>
                    <div class="col-xs-12 openninghours <?= (isset($openning_hours->{$day}) AND $openning_hours->{$day}->o) ? '' : 'hidden'?>">
                        <div class="row">
                            <div class="col-xs-6 col-md-6">
                                <?=FORM::select('cf_openinghours[' . $day . '][f]',
                                    Date::half_hours_range(),
                                    isset($openning_hours->{$day}) ? $openning_hours->{$day}->f : NULL, array(
                                    'class' => 'form-control disable-select2',
                                ))?>
                            </div>
                            <div class="col-xs-6 col-md-6">
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
            <? if ($i % 2 === 0) : ?>
                <div class="clearfix"></div>
            <? endif ?>
        <? $i++; endforeach ?>
    </div>
<? endif ?>
