<?php defined('SYSPATH') or die('No direct script access.');?>

<tr id="tr<?=$sale->pk()?>">

    <td><?=$sale->pk()?></td>

    <td><?=i18n::format_currency($sale->amount, $sale->currency)?></td>

    <td><a href="<?=Route::url('profile', array('seoname'=> $sale->user->seoname)) ?>" ><?=$sale->user->name?></a></td>

    <td><?=$sale->pay_date?></td>

    <td>
        <a
            href="<?=Route::url('ad', array('category'=> $sale->ad->category->seoname,'seotitle'=>$sale->ad->seotitle)) ?>"
            title="<?=HTML::chars($sale->ad->title)?>"
        >
            <?=Text::limit_chars($sale->ad->title, 30, NULL, TRUE)?>
        </a>

        <?if (isset($sale->ad->cf_file_download)):?>
            <a class="btn btn-sm btn-success" href="<?=$sale->ad->cf_file_download?>">
                <?=_e('Download')?>
            </a>
        <?endif?>

        <?if ($sale->paymethod == 'escrow'):?>
            <? $transaction = json_decode($sale->txn_id) ?>

            <?if (isset($transaction->status) AND ! $transaction->status->shipped):?>
                <a
                    class="btn btn-secondary"
                    href="<?= Route::url('oc-panel', ['controller'=>'escrow', 'action'=>'ship', 'id' => $sale->id_order]) ?>"
                >
                    <i class="fa fa-check"></i> <?=_e('Mark as shipped')?>
                </a>
            <?endif?>
        <?endif?>
    </td>
</tr>
