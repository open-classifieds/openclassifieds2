<?php defined('SYSPATH') or die('No direct script access.');?>
<div class="pad_10tb">
	<div class="container">
		<div class="col-xs-12">
			<div class="page-header">
				<h3><?=_e('Orders')?></h3>
			</div>

			<div class="panel panel-default">
				<ul class="list-group orders-list">
					<?foreach($orders as $order):?>
						<li class="list-group-item" id="tr<?=$order->pk()?>">
							<div class="order-item">
								<?if ($order->status == Model_Order::STATUS_CREATED):?>
									<a href="#" data-toggle="modal" data-target="#viewOrderID<?=$order->id_order?>" title="<?=HTML::chars($order->ad->title)?>">
										<?=Text::limit_chars($order->description, 50, NULL, TRUE)?>
									 	<i class="fa fa-share-square-o"></i>
									</a>
								<?else:?>
									<a href="<?=Route::url('oc-panel', array('controller'=>'profile', 'action'=>'order', 'id' => $order->id_order))?>" title="<?=HTML::chars($order->ad->title)?>">
										<?=Text::limit_chars($order->description, 30, NULL, TRUE)?>
									 	<i class="fa fa-search"></i>
									</a>
								<?endif?>
							</div>
							<div class="order-info pad_5tb">
							<span class="badge"><?=Model_Order::product_desc($order->id_product)?></span>
								<?if ($order->status == Model_Order::STATUS_CREATED):?>
									<span class="badge badge-warning"> <?=i18n::format_currency($order->amount, $order->currency)?> <i class="fa fa-clock-o"></i></span>
								<?else:?>
									<span class="badge badge-success"> <?=i18n::format_currency($order->amount, $order->currency)?> <i class="fa fa-check"></i></span>
								<?endif?>
							</div>
							<div class="order-date text-right">
								<?=$order->created?>
							</div>
						<div class="modal fade" id="viewOrderID<?=$order->id_order?>" tabindex="-1" role="dialog" aria-labelledby="viewOrder">
							<div class="modal-dialog" role="document">
								<div class="modal-content">
									<div class="modal-header">
										<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
										<h4 class="modal-title" id="viewOrder">#<?=$order->pk()?></h4>
									</div>
									<div class="modal-body">
										<p><a href="<?=Route::url('ad', array('controller'=>'ad','category'=>$order->ad->category->seoname,'seotitle'=>$order->ad->seotitle))?>">
											<?=HTML::chars($order->ad->title)?>
											</a></p>
										<p><b><?=_e('Product') ?> :</b> <?=Model_Order::product_desc($order->id_product)?></p>
										<p><b><?=_e('Date') ?> :</b> <?=$order->created?></p>
										<p><b><?=_e('Date Paid') ?> :</b> <?=$order->pay_date?></p>
										<hr>
										<p  class="text-right">
											<b><?=_e('Total') ?>:</b>
											<?if ($order->status == Model_Order::STATUS_CREATED):?>
												<span class="order-unpaid"><?=i18n::format_currency($order->amount, $order->currency)?> <i class="fa fa-clock-o"></i></span>
											<?else:?>
												<span class="order-paid"><?=i18n::format_currency($order->amount, $order->currency)?> <i class="fa fa-check"></i></span>
											<?endif?>
										</p>
									</div>
									<div class="modal-footer">
									<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                    <?if ($order->status == Model_Order::STATUS_CREATED AND $order->paymethod != 'escrow'):?>
                                        <a class="btn btn-success" href="<?=Route::url('default', array('controller'=> 'ad','action'=>'checkout' , 'id' => $order->id_order))?>">
                                            <i class="glyphicon glyphicon-shopping-cart"></i> <?=_e('Pay')?>   
                                        </a>
                                    <?elseif ($order->status == Model_Order::STATUS_CREATED AND $order->paymethod == 'escrow'):?>
                                        <? $transaction = json_decode($order->txn_id) ?>
                                        <a class="btn btn-warning" href="<?= $transaction->landing_page ?>">
                                            <i class="glyphicon glyphicon-shopping-cart"></i> <?=_e('Pay')?>   
                                        </a>
                                        <a class="btn btn-default" href="<?= Route::url('default', ['controller'=>'escrow', 'action'=>'paid', 'id' => $order->id_order]) ?>">
                                            <i class="glyphicon glyphicon-check"></i> <?=_e('Mark as paid')?>   
                                        </a>
                                    <?else:?>
                                        <a class="btn btn-success disabled" href="#" disabled><?=Model_Order::$statuses[$order->status]?></a>
                                    <?endif?>

                                    <?if (core::config('general.ewallet') AND $order->received === NULL AND in_array($order->id_product, [Model_Order::PRODUCT_AD_SELL, Model_Order::PRODUCT_AD_CUSTOM])):?>
                                        <a class="btn btn-warning" href="<?=Route::url('oc-panel', array('controller'=> 'profile','action'=>'order_received' , 'id' => $order->id_order))?>">
                                            <i class="fa fa-hands"></i> <?=_e('Mark as received')?>   
                                        </a>
                                    <?endif?>

                                    <?if ($order->paymethod == 'escrow'):?>
                                        <? $transaction = json_decode($order->txn_id) ?>

                                        <?if (isset($transaction->status) AND $transaction->status->shipped AND ! $transaction->status->received):?>
                                            <a class="btn btn-default" href="<?= Route::url('oc-panel', ['controller'=>'escrow', 'action'=>'receive', 'id' => $order->id_order]) ?>">
                                                <i class="glyphicon glyphicon-check"></i> <?=_e('Mark as received')?>
                                            </a>
                                        <?endif?>

                                        <?if (isset($transaction->status) AND $transaction->status->received AND ! $transaction->status->accepted):?>
                                            <a class="btn btn-default" href="<?= Route::url('oc-panel', ['controller'=>'escrow', 'action'=>'accept', 'id' => $order->id_order]) ?>">
                                                <i class="glyphicon glyphicon-check"></i> <?=_e('Mark as accepted')?>
                                            </a>
                                        <?endif?>
                                    <?endif?>
									</div>
								</div>
							</div>
						</div>
						</li>
					<?endforeach?>
				</ul>
			</div>

			<div class="text-center">
				<?=$pagination?>
			</div>
		</div>
	</div>
</div>
