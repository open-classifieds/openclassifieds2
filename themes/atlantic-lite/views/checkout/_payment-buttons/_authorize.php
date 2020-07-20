<form method="post" role="form" action="<?= Route::url('default', ['controller' => 'authorize', 'action' => 'pay', 'id' => $order->id_order]) ?>">
    <h4><?=_e('Pay with Credit Card')?></h4>

    <div class="form-group">
        <label for="card-number"><?=_e('Card Number')?></label>
        <input type="text" class="form-control" name="card-number" id="card-number" placeholder="<?=__('Card Number')?>">
    </div>

    <div class="form-group">
        <label for="expiry-month"><?=_e('Expiration Date')?></label>
        <div class="row">
            <div class="col-sm-4 col-xs-12">
                <select class="form-control col-sm-2" name="expiry-month" id="expiry-month">
                    <?foreach (Date::months(Date::MONTHS_SHORT) as $month=>$name):?>
                        <option value="<?=$month?>" ><?=$month?> - <?=$name?></option>
                    <?endforeach?>
                </select>
            </div>
            <div class="col-sm-3 col-xs-12">
                <select class="form-control" name="expiry-year">
                    <?foreach (range(date('y'),date('y')+10) as $year):?>
                        <option><?=$year?></option>
                    <?endforeach?>
                </select>
            </div>
        </div>
    </div>
    <div class="form-group">
        <button type="submit" class="btn btn-success btn-lg pull-right"><?=_e('Pay With Card')?> <span class="glyphicon glyphicon-chevron-right"></span></button>
    </div>
</form>
