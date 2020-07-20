<?php defined('SYSPATH') or die('No direct script access.');?>

<?= Alert::show() ?>

<?= Form::errors() ?>

<table class="table table-bordered">
    <tr>
        <th><?=_e('Category')?></th>
        <th><?=_e('Location')?></th>
        <th><?=_e('Min Price')?></th>
        <th><?=_e('Max Price')?></th>
        <th><?=_e('Created')?></th>
        <th>
            <a
                href="<?=Route::url('default', array('controller'=>'subscribe','action'=>'unsubscribe', 'id'=>Auth::instance()->get_user()->id_user))?>"
                class="btn btn-danger"
                title="<?=__('Unsubscribe to all?')?>"
                data-toggle="confirmation"
                data-placement="left"
                data-href="<?=Route::url('default', array('controller'=>'subscribe','action'=>'unsubscribe', 'id'=>Auth::instance()->get_user()->id_user))?>"
                data-btnOkLabel="<?=__('Yes, definitely!')?>"
                data-btnCancelLabel="<?=__('No way!')?>"
            >
                <i class="fa fa-minus-circle"></i>
            </a>
        </th>
    </tr>
    <tbody>
        <?foreach($subscriptions as $subscription):?>
            <tr>
                <td>
                    <p><?= $subscription->category ? $subscription->category->name : '' ?></p>
                </td>

                <td>
                    <p><?= $subscription->location ? $subscription->location->name : '' ?></p>
                </td>

                <td>
                    <p><?= $subscription->min_price ?></p>
                </td>
                <td>
                    <p><?= $subscription->max_price ?></p>
                </td>
                <td>
                    <p><?= Date::format($subscription->created, core::config('general.date_format'))?></p>
                </td>
                <td>
                    <a
                        href="<?= Route::url('oc-panel', ['controller' => 'profile', 'action' => 'unsubscribe', 'id'=> $subscription->id_subscribe]) ?>"
                        class="btn btn-warning"
                        title="<?=__('Unsubscribe?')?>"
                        data-toggle="confirmation"
                        data-btnOkLabel="<?=__('Yes, definitely!')?>"
                        data-btnCancelLabel="<?=__('No way!')?>"
                    >
                        <i class="fa fa-minus-circle"></i>
                    </a>
                </td>
            </tr>
        <?endforeach?>
    </tbody>
</table>

<div class="text-center"><?=$pagination?></div>
