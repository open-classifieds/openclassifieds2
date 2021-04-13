<?php defined('SYSPATH') or die('No direct script access.');?>

<? if($subscription!==FALSE): ?>
    <div class="px-3 py-3 pt-md-5 pb-md-4 mx-auto text-center" style="max-width: 700px;">
        <p class="lead">
            <?if($subscription->amount_ads_left > -1 ):?>
                <?= sprintf(__('You are subscribed to the plan %s until %s with %u ads left'), $subscription->plan->name, $subscription->expire_date, $subscription->amount_ads_left) ?>
            <?else:?>
                <?= sprintf(__('You are subscribed to the plan %s until %s with unlimited ads'), $subscription->plan->name, $subscription->expire_date) ?>
            <?endif?>
        </p>
    </div>
<? endif ?>

<? if(Core::count($plans) > 0): ?>
    <div class="card-deck mb-3 text-center">
        <? foreach ($plans as $plan): ?>
            <?
                $current_plan = FALSE;

                if ($subscription !== FALSE AND $subscription->plan->id_plan == $plan->id_plan)
                {
                    $current_plan = TRUE;
                }
            ?>
            <?= View::factory('pricing/_plan', compact('plan', 'current_plan')) ?>
        <? endforeach ?>
    </div>
<?endif?>
