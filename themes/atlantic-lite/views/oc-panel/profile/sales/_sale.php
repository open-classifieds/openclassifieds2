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

        <?if (core::config('general.ewallet') AND $sale->received !== NULL AND in_array($sale->id_product, [Model_Order::PRODUCT_AD_SELL, Model_Order::PRODUCT_AD_CUSTOM])):?>
            <span class="badge badge-success"><?=_e('Received')?></span> 
        <?elseif (core::config('general.ewallet') AND $sale->received === NULL AND in_array($sale->id_product, [Model_Order::PRODUCT_AD_SELL, Model_Order::PRODUCT_AD_CUSTOM])):?>
            <span class="badge badge-info"><?=_e('Not received yet')?></span> 
        <?endif?>

        <?if (isset($sale->ad->cf_file_download)):?>
            <a class="btn btn-sm btn-success" href="<?=$sale->ad->cf_file_download?>">
                <?=_e('Download')?>
            </a>
        <?endif?>

        <?if ($sale->paymethod == 'escrow'):?>
            <? $transaction = json_decode($sale->txn_id) ?>

            <?if (isset($transaction->status) AND ! $transaction->status->shipped):?>
                <a
                    class="btn btn-sm btn-secondary"
                    href="<?= Route::url('oc-panel', ['controller'=>'escrow', 'action'=>'ship', 'id' => $sale->id_order]) ?>"
                >
                    <i class="fa fa-check"></i> <?=_e('Mark as shipped')?>
                </a>
            <?endif?>
        <?endif?>

        <?if (core::config('payment.stripe_escrow')):?>
            <?if ($sale->shipped === NULL AND $sale->cancelled === NULL):?>
                <button type="button" class="btn btn-sm btn-link" data-toggle="modal" data-target="#shippedModal<?=$sale->id_order?>">
                    <i class="fas fa-check"></i> <?=_e('Mark as shipped')?>
                </button>

                <div class="modal fade" id="shippedModal<?=$sale->id_order?>" tabindex="-1" role="dialog">
                    <div class="modal-dialog modal-sm" role="document">
                        <div class="modal-content">
                            <?=FORM::open(Route::url('oc-panel', ['controller'=>'profile', 'action'=>'order_shipped', 'id'=> $sale->id_order]))?>
                                <div class="modal-header">
                                    <h5 class="modal-title"><?=__('Shipment details')?></h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <div class="form-group">
                                        <label for="provider_name"><?=__('Shipment provider name')?></label>
                                        <input name="provider_name" type="text" class="form-control" id="provider_name">
                                    </div>

                                    <div class="form-group">
                                        <label for="tracking_code"><?=__('Shipment tracking code')?></label>
                                        <input name="tracking_code" type="text" class="form-control" id="tracking_code">
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="submit" class="btn btn-primary"><?=__('Submit')?></button>
                                </div>
                            <?=FORM::close()?>
                        </div>
                    </div>
                </div>
            <?endif?>

            <?if ($sale->cancelled === NULL AND $sale->shipped === NULL):?>
                <a class="ml-2 btn btn-sm btn-link" href="<?= Route::url('oc-panel', ['controller'=>'profile', 'action'=>'cancel_order', 'id' => $sale->id_order]) ?>">
                    <i class="fas fa-times"></i> <?=_e('Cancel order')?>
                </a>
            <?endif?>
        <?endif?>
    </td>
</tr>
