<?php defined('SYSPATH') or die('No direct script access.');?>

<div class="page-header">
    <h1><?=_e('Sales')?></h1>
</div>

<div class="panel panel-default">
    <div class="table-responsive">
        <table class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th>#</th>
                    <th><?=_e('Amount') ?></th>
                    <th><?=_e('Buyer') ?></th>
                    <th><?=_e('Date') ?></th>
                    <th><?=_e('Ad') ?></th>
                </tr>
            </thead>
            <tbody>
                <?foreach($orders as $order):?>
                    <tr id="tr<?=$order->pk()?>">

                        <td><?=$order->pk()?></td>

                        <td><?=i18n::format_currency($order->amount, $order->currency)?></td>

                        <td><a href="<?=Route::url('profile', array('seoname'=> $order->user->seoname)) ?>" ><?=$order->user->name?></a></td>

                        <td><?=$order->pay_date?></td>

                        <td>
                            <a href="<?=Route::url('ad', array('category'=> $order->ad->category->seoname,'seotitle'=>$order->ad->seotitle)) ?>" title="<?=HTML::chars($order->ad->title)?>">
                                <?=Text::limit_chars($order->ad->title, 30, NULL, TRUE)?>
                            </a>

                            <?if (core::config('general.ewallet') AND $order->received !== NULL AND in_array($order->id_product, [Model_Order::PRODUCT_AD_SELL, Model_Order::PRODUCT_AD_CUSTOM])):?>
                                <span class="badge badge-success"><?=_e('Received')?></span> 
                            <?elseif (core::config('general.ewallet') AND $order->received === NULL AND in_array($order->id_product, [Model_Order::PRODUCT_AD_SELL, Model_Order::PRODUCT_AD_CUSTOM])):?>
                                <span class="badge badge-info"><?=_e('Not received yet')?></span> 
                            <?endif?>

                            <?if (isset($order->ad->cf_file_download)):?>
                                <a class="btn btn-sm btn-success" href="<?=$order->ad->cf_file_download?>">
                                    <?=_e('Download')?>
                                </a>
                            <?endif?>

                            <?if ($order->paymethod == 'escrow'):?>
                                <? $transaction = json_decode($order->txn_id) ?>

                                <?if (isset($transaction->status) AND ! $transaction->status->shipped):?>
                                    <a class="btn btn-default" href="<?= Route::url('oc-panel', ['controller'=>'escrow', 'action'=>'ship', 'id' => $order->id_order]) ?>">
                                        <i class="glyphicon glyphicon-check"></i> <?=_e('Mark as shipped')?>
                                    </a>
                                <?endif?>
                            <?endif?>

                            <?if (core::config('payment.stripe_escrow')):?>
                                <?if ($order->shipped === NULL AND $order->cancelled === NULL):?>
                                    <button type="button" class="btn btn-default" data-toggle="modal" data-target="#shippedModal<?=$order->id_order?>">
                                        <i class="fas fa-check"></i> <?=_e('Mark as shipped')?>
                                    </button>

                                    <div class="modal fade" id="shippedModal<?=$order->id_order?>" tabindex="-1" role="dialog">
                                        <div class="modal-dialog modal-sm" role="document">
                                            <div class="modal-content">
                                                <?=FORM::open(Route::url('oc-panel', ['controller'=>'profile', 'action'=>'order_shipped', 'id'=> $order->id_order]))?>
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

                                <?if ($order->cancelled === NULL AND $order->shipped === NULL):?>
                                    <a class="ml-2 btn btn-default" href="<?= Route::url('oc-panel', ['controller'=>'profile', 'action'=>'cancel_order', 'id' => $order->id_order]) ?>">
                                        <i class="fas fa-times"></i> <?=_e('Cancel order')?>
                                    </a>
                                <?endif?>
                            <?endif?>
                        </td>

                    </tr>
                <?endforeach?>
            </tbody>
        </table>
    </div>
</div>
<div class="text-center"><?=$pagination?></div>
