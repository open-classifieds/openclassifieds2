<div class="card mb-4 tw-shadow-lg">
    <div class="card-header">
        <h4 class="my-0 font-weight-normal tw-text-2xl"><?= $plan->name ?></h4>
    </div>
    <div class="card-body">
        <h1 class="card-title pricing-card-title tw-text-4xl">
            <?= i18n::format_currency($plan->price, Core::config('payment.paypal_currency')) ?>
            <small class="text-muted">
                <? if ($plan->days == 0 AND $plan->price>0): ?>
                    <?= _e('Pay once') ?>
                <? elseif ($plan->days == 365): ?>
                    <?= _e('Yearly') ?>
                <? elseif ($plan->days == 180): ?>
                    <?= _e('6 months') ?>
                <? elseif ($plan->days == 90): ?>
                    <?= _e('Quarterly') ?>
                <? elseif ($plan->days == 30): ?>
                    <?= _e('Monthly') ?>
                <? else: ?>
                    <?= sprintf(__('%u days'), $plan->days) ?>
                <? endif ?>
            </small>
        </h1>
        <p><?= Text::bb2html($plan->description, TRUE) ?></p>
        <ul class="list-unstyled mt-3 mb-4">
            <li>
                <?if ($plan->amount_ads > -1):?>
                    <?=sprintf(__('%u Ads'), $plan->amount_ads)?>
                <?else:?>
                    <?=__('Unlimited Ads')?>
                <?endif?>
            </li>
            <? if(Core::config('payment.stripe_connect')): ?>
                <li><?= sprintf(__('%s%% market place fee'), round($plan->marketplace_fee,1)) ?></li>
            <? endif ?>
        </ul>
        <? if($current_plan == TRUE): ?>
            <button type="button" class="btn btn-lg btn-block btn-outline-primary" disabled><?=_e('Current Plan')?></button>
        <? else: ?>
            <a
                href="<?= Route::url('default', array('controller'=>'plan','action'=>'buy','id'=>$plan->seoname)) ?>"
                class="btn btn-lg btn-block btn-primary"
            >
                <?=_e('Sign Up')?> <?= i18n::format_currency($plan->price, Core::config('payment.paypal_currency')) ?>
            </a>
        <? endif ?>
    </div>
</div>
