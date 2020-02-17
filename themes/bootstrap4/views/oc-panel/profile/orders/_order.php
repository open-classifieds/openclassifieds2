<?php defined('SYSPATH') or die('No direct script access.');?>

<tr id="tr<?=$order->pk()?>">

    <td><?=$order->pk()?></td>

    <td><?=Model_Order::$statuses[$order->status]?></td>

    <td><?=Model_Order::product_desc($order->id_product)?></td>

    <td><?=i18n::format_currency($order->amount, $order->currency)?></td>

    <td><a href="<?=Route::url('ad', array('category'=> $order->ad->category->seoname,'seotitle'=>$order->ad->seotitle)) ?>" title="<?=HTML::chars($order->ad->title)?>">
        <?=Text::limit_chars($order->ad->title, 30, NULL, TRUE)?></a></td>

    <td><?=$order->created?></td>

    <td><?=$order->pay_date?></td>

    <td>
        <?if ($order->status == Model_Order::STATUS_CREATED AND $order->paymethod != 'escrow'):?>
            <a class="btn btn-warning" href="<?=Route::url('default', array('controller'=> 'ad','action'=>'checkout' , 'id' => $order->id_order))?>">
            <i class="fas fa-shopping-cart"></i> <?=_e('Pay')?>
            </a>
        <?elseif ($order->status == Model_Order::STATUS_CREATED AND $order->paymethod == 'escrow'):?>
            <? $transaction = json_decode($order->txn_id) ?>
            <a class="btn btn-warning" href="<?= $transaction->landing_page ?>">
                <i class="fas fa-shopping-cart"></i> <?=_e('Pay')?>
            </a>
            <a class="btn btn-default" href="<?= Route::url('default', ['controller'=>'escrow', 'action'=>'paid', 'id' => $order->id_order]) ?>">
                <i class="fas fa-check"></i> <?=_e('Mark as paid')?>
            </a>
        <?else:?>
            <a class="btn btn-default" href="<?=Route::url('oc-panel', array('controller'=>'profile', 'action'=>'order', 'id' => $order->id_order))?>">
                <i class="fas fa-search"></i> <?=_e('View')?>
            </a>
        <?endif?>

        <?if ($order->paymethod == 'escrow'):?>
            <? $transaction = json_decode($order->txn_id) ?>

            <?if (isset($transaction->status) AND $transaction->status->shipped AND ! $transaction->status->received):?>
                <a class="btn btn-default" href="<?= Route::url('oc-panel', ['controller'=>'escrow', 'action'=>'receive', 'id' => $order->id_order]) ?>">
                    <i class="fas fa-check"></i> <?=_e('Mark as received')?>
                </a>
            <?endif?>

            <?if (isset($transaction->status) AND $transaction->status->received AND ! $transaction->status->accepted):?>
                <a class="btn btn-default" href="<?= Route::url('oc-panel', ['controller'=>'escrow', 'action'=>'accept', 'id' => $order->id_order]) ?>">
                    <i class="fas fa-check"></i> <?=_e('Mark as accepted')?>
                </a>
            <?endif?>
        <?endif?>
    </td>

</tr>
