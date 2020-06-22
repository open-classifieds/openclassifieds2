<?php defined('SYSPATH') or die('No direct script access.');?>
<?=Alert::show()?>
<div id="page-my-dvertisements" class="page-header">
    <h1><?=_e('My Advertisements')?></h1>
</div>

<div class="panel panel-default">
    <div class="table-responsive">
        <table class="table table-bordered">
            <tr>
            <th><?=_e('Name')?></th>
                <th><?=_e('Category')?></th>
                <th><?=_e('Location')?></th>
                <th><?=_e('Status')?></th>
                <th><?=_e('Date')?></th>
                <?if( core::config('payment.to_featured')):?>
                <th><?=_e('Featured')?></th>
                <?endif?>
                <th></th>
            </tr>
            <? $i = 0; foreach($ads as $ad):?>
            <tbody>
                <tr>

                    <td>
                        <a href="<?= Route::url('ad', ['controller' => 'ad', 'category' => $ad->category->seoname, 'seotitle' => $ad->seotitle])?>">
                            <?= $ad->title ?>
                        </a>
                    </td>

                    <td>
                        <?= $ad->category->name ?>
                    </td>

                    <? if($ad->id_location): ?>
                        <td><?= $ad->location->name ?></td>
                    <? else: ?>
                        <td>n/a</td>
                    <? endif ?>

                    <td>
                        <?
                            $status = [
                                Model_Ad::STATUS_NOPUBLISHED => _e('Not published'),
                                Model_Ad::STATUS_PUBLISHED => _e('Published'),
                                Model_Ad::STATUS_SPAM => _e('Spam'),
                                Model_Ad::STATUS_UNAVAILABLE => _e('Unavailable'),
                                Model_Ad::STATUS_UNCONFIRMED => _e('Unconfirmed'),
                                Model_Ad::STATUS_SOLD => _e('Sold'),
                            ]
                        ?>

                        <?= $status[$ad->status] ?>

                        <?if( ($order = $ad->get_order())!==FALSE ):?>
                            <?if ($order->status==Model_Order::STATUS_CREATED AND $ad->status != Model_Ad::STATUS_PUBLISHED):?>
                                <a class="btn btn-warning" href="<?=Route::url('default', array('controller'=> 'ad','action'=>'checkout' , 'id' => $order->id_order))?>">
                                    <i class="fa fa-shopping-cart"></i> <?=_e('Pay')?>  <?=i18n::format_currency($order->amount,$order->currency)?> 
                                </a>
                            <?elseif ($order->status==Model_Order::STATUS_PAID):?>
                                (<?=_e('Paid')?>)
                            <?endif?>
                        <?endif?>
                    </td>

                    <td>
                        <? if($ad->published): ?>
                            <?= Date::format($ad->published, core::config('general.date_format'))?>
                        <? else : ?>
                            -
                        <? endif ?>
                    </td>

                    <?if( core::config('payment.to_featured')):?>
                    <td>
                        <?if($ad->featured == NULL):?>
                            <a class="btn btn-default"
                                href="<?=Route::url('default', array('controller'=>'ad','action'=>'to_featured','id'=>$ad->id_ad))?>"
                                onclick="return confirm('<?=__('Make featured?')?>');"
                                rel="tooltip" title="<?=__('Featured')?>" data-id="tr1" data-text="<?=__('Are you sure you want to make it featured?')?>">
                                <i class="glyphicon glyphicon-bookmark "></i> <?=_e('Featured')?>
                            </a>
                        <?else:?>
                            <?= Date::format($ad->featured, core::config('general.date_format'))?>
                        <?endif?>
                    </td>
                    <?endif?>

                    <td>
                        <?if(core::config('advertisement.count_visits')):?>
                            <a class="btn btn-primary"
                                href="<?=Route::url('oc-panel', array('controller'=>'myads','action'=>'stats','id'=>$ad->id_ad))?>"
                                rel="tooltip" title="<?=__('Stats')?>">
                                <i class="glyphicon glyphicon-align-left"></i>
                            </a>
                        <?endif?>
                        <a class="btn btn-primary"
                            href="<?=Route::url('oc-panel', array('controller'=>'myads','action'=>'update','id'=>$ad->id_ad))?>"
                            rel="tooltip" title="<?=__('Update')?>">
                            <i class="glyphicon glyphicon-edit"></i>
                        </a>
                        <?if($ad->status != Model_Ad::STATUS_SOLD AND $ad->status != Model_Ad::STATUS_UNCONFIRMED):?>
                            <button type="button" class="btn btn-warning" data-toggle="modal" data-target="#soldModal<?=$ad->id_ad?>">
                                <i class="glyphicon glyphicon-usd"></i>
                            </button>
                            <div class="modal fade" id="soldModal<?=$ad->id_ad?>" tabindex="-1" role="dialog">
                                <div class="modal-dialog modal-sm" role="document">
                                    <div class="modal-content">
                                        <?=FORM::open(Route::url('oc-panel', array('controller'=>'myads','action'=>'sold','id'=>$ad->id_ad)), array('enctype'=>'multipart/form-data'))?>
                                            <div class="modal-header">
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                                <h4 class="modal-title"><?=__('Mark as Sold')?></h4>
                                            </div>
                                            <div class="modal-body">
                                                <div class="form-group">
                                                    <label for="amount"><?=__('Amount')?></label>
                                                    <input name="amount" type="text" class="form-control" id="amount" placeholder="<?=i18n::format_currency(0,core::config('payment.paypal_currency'))?>">
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
                        <? if (in_array($ad->status, [Model_Ad::STATUS_UNAVAILABLE, Model_Ad::STATUS_SOLD])
                                    AND !in_array(core::config('general.moderation'), Model_Ad::$moderation_status)
                            ):?>
                            <?if ( ($order = $ad->get_order()) === FALSE OR ($order !== FALSE AND $order->status == Model_Order::STATUS_PAID) ):?>
                                <a
                                    href="<?=Route::url('oc-panel', array('controller'=>'myads','action'=>'activate','id'=>$ad->id_ad))?>"
                                    class="btn btn-success"
                                    title="<?=__('Activate?')?>"
                                    data-text="<?=__('Activate?')?>"
                                    data-toggle="confirmation"
                                    data-btnOkLabel="<?=__('Yes, definitely!')?>"
                                    data-btnCancelLabel="<?=__('No way!')?>">
                                    <i class="glyphicon glyphicon-ok"></i>
                                </a>
                            <?endif?>
                        <?elseif($ad->status != Model_Ad::STATUS_UNAVAILABLE AND $ad->status != Model_Ad::STATUS_UNCONFIRMED):?>
                            <a
                                href="<?=Route::url('oc-panel', array('controller'=>'myads','action'=>'deactivate','id'=>$ad->id_ad))?>"
                                class="btn btn-warning"
                                title="<?=__('Deactivate?')?>"
                                data-text="<?=__('Deactivate?')?>"
                                data-toggle="confirmation"
                                data-btnOkLabel="<?=__('Yes, definitely!')?>"
                                data-btnCancelLabel="<?=__('No way!')?>">
                                <i class="glyphicon glyphicon-remove"></i>
                            </a>
                        <?endif?>
                        <?if( core::config('payment.to_top') ):?>
                            <a
                                href="<?=Route::url('default', array('controller'=>'ad','action'=>'to_top','id'=>$ad->id_ad))?>"
                                class="btn btn-info"
                                title="<?=__('Refresh listing, go to top?')?>"
                                data-text="<?=__('Refresh listing, go to top?')?>"
                                data-toggle="confirmation"
                                data-btnOkLabel="<?=__('Yes, definitely!')?>"
                                data-btnCancelLabel="<?=__('No way!')?>">
                                <i class="glyphicon glyphicon-circle-arrow-up"></i>
                            </a>
                        <?endif?>
                        <?if(core::config('advertisement.delete_ad')==TRUE):?>
                            <a
                                href="<?=Route::url('oc-panel', array('controller'=>'myads','action'=>'delete','id'=>$ad->id_ad))?>"
                                class="btn btn-danger"
                                title="<?=__('Delete?')?>"
                                data-text="<?=__('Delete?')?>"
                                data-toggle="confirmation"
                                data-btnOkLabel="<?=__('Yes, definitely!')?>"
                                data-btnCancelLabel="<?=__('No way!')?>">
                                <i class="glyphicon glyphicon-minus"></i>
                            </a>
                        <?endif?>
                    </td>
                </tr>
            <?endforeach?>
            </tbody>
        </table>
    </div>
</div>
<div class="text-center"><?=$pagination?></div>
