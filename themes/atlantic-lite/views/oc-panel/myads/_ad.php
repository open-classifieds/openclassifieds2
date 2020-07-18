<?php defined('SYSPATH') or die('No direct script access.');?>

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
                <a class="btn btn-info"
                    href="<?=Route::url('default', array('controller'=>'ad','action'=>'to_featured','id'=>$ad->id_ad))?>"
                    onclick="return confirm('<?=__('Make featured?')?>');"
                    rel="tooltip" title="<?=__('Featured')?>" data-id="tr1" data-text="<?=__('Are you sure you want to make it featured?')?>">
                    <i class="fa fa-bookmark"></i> <?=_e('Featured')?>
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
                <i class="fa fa-align-left"></i>
            </a>
        <?endif?>

        <a class="btn btn-primary"
            href="<?=Route::url('oc-panel', array('controller'=>'myads','action'=>'update','id'=>$ad->id_ad))?>"
            rel="tooltip" title="<?=__('Update')?>">
            <i class="fa fa-edit"></i>
        </a>

        <?if($ad->status != Model_Ad::STATUS_SOLD AND $ad->status != Model_Ad::STATUS_UNCONFIRMED):?>
            <button type="button" class="btn btn-warning" data-toggle="modal" data-target="#soldModal<?=$ad->id_ad?>">
                <i class="fas fa-dollar-sign"></i>
            </button>

            <div class="modal fade" id="soldModal<?=$ad->id_ad?>" tabindex="-1" role="dialog">
                <div class="modal-dialog modal-sm" role="document">
                    <div class="modal-content">
                        <?=FORM::open(Route::url('oc-panel', ['controller'=>'myads', 'action'=>'sold', 'id'=> $ad->id_ad]))?>
                            <div class="modal-header">
                                <h5 class="modal-title"><?=__('Mark as Sold')?></h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
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
                <span x-data="{}">
                    <a
                        href="<?=Route::url('oc-panel', ['controller' => 'myads', 'action' => 'activate', 'id' => $ad->id_ad])?>"
                        class="btn btn-success"
                        @click.prevent="
                            swal({
                                title: '<?= __('Activate?') ?>',
                                text: '<?= __('Activate?') ?>',
                                type: 'info',
                                showCancelButton: true,
                                allowOutsideClick: true,
                                confirmButtonColor: '#DD6B55',
                                confirmButtonText: '<?= __('Yes, definitely!') ?>',
                                cancelButtonText: '<?= __('No way!') ?>',
                            },
                            function() {
                                window.open('<?= Route::url('oc-panel', ['controller' => 'myads', 'action' => 'activate', 'id'=> $ad->id_ad]) ?>', '_self');
                            })
                        ">
                        <i class="fas fa-check"></i>
                    </a>
                </span>
            <?endif?>
        <?elseif($ad->status != Model_Ad::STATUS_UNAVAILABLE AND $ad->status != Model_Ad::STATUS_UNCONFIRMED):?>
            <span x-data="{}">
                <a
                    href="<?=Route::url('oc-panel', ['controller' => 'myads', 'action' => 'deactivate', 'id' => $ad->id_ad])?>"
                    class="btn btn-warning"
                    @click.prevent="
                        swal({
                            title: '<?= __('Deactivate?') ?>',
                            text: '<?= __('Deactivate?') ?>',
                            type: 'info',
                            showCancelButton: true,
                            allowOutsideClick: true,
                            confirmButtonColor: '#DD6B55',
                            confirmButtonText: '<?= __('Yes, definitely!') ?>',
                            cancelButtonText: '<?= __('No way!') ?>',
                        },
                        function() {
                            window.open('<?= Route::url('oc-panel', ['controller' => 'myads', 'action' => 'deactivate', 'id'=> $ad->id_ad]) ?>', '_self');
                        })
                    ">
                    <i class="fas fa-minus-circle"></i>
                </a>
            </span>
        <?endif?>

        <?if( core::config('payment.to_top') ):?>
            <span x-data="{}">
                <a
                    href="<?=Route::url('oc-panel', ['controller' => 'ad', 'action' => 'to_top', 'id' => $ad->id_ad])?>"
                    class="btn btn-info"
                    @click.prevent="
                        swal({
                            title: '<?= __('Refresh listing, go to top?') ?>',
                            text: '<?= __('Refresh listing, go to top?') ?>',
                            type: 'info',
                            showCancelButton: true,
                            allowOutsideClick: true,
                            confirmButtonColor: '#DD6B55',
                            confirmButtonText: '<?= __('Yes, definitely!') ?>',
                            cancelButtonText: '<?= __('No way!') ?>',
                        },
                        function() {
                            window.open('<?= Route::url('oc-panel', ['controller' => 'ad', 'action' => 'to_top', 'id'=> $ad->id_ad]) ?>', '_self');
                        })
                    ">
                    <i class="fas fa-arrow-alt-circle-up"></i>
                </a>
            </span>
        <?endif?>

        <?if(core::config('advertisement.delete_ad')==TRUE):?>
            <span x-data="{}">
                <a
                    href="<?=Route::url('oc-panel', ['controller' => 'myads', 'action' => 'delete', 'id' => $ad->id_ad])?>"
                    class="btn btn-danger"
                    @click.prevent="
                        swal({
                            title: '<?= __('Delete?') ?>',
                            text: '<?= __('Delete?') ?>',
                            type: 'info',
                            showCancelButton: true,
                            allowOutsideClick: true,
                            confirmButtonColor: '#DD6B55',
                            confirmButtonText: '<?= __('Yes, definitely!') ?>',
                            cancelButtonText: '<?= __('No way!') ?>',
                        },
                        function() {
                            window.open('<?= Route::url('oc-panel', ['controller' => 'myads', 'action' => 'delete', 'id'=> $ad->id_ad]) ?>', '_self');
                        })
                    ">
                    <i class="fas fa-minus"></i>
                </a>
            </span>
        <?endif?>
    </td>
</tr>
