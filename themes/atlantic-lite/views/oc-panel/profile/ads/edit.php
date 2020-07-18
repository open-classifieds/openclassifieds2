<?php defined('SYSPATH') or die('No direct script access.');?>

<?
    $ad_status = [
        Model_Ad::STATUS_NOPUBLISHED => _e('NOPUBLISHED'),
        Model_Ad::STATUS_PUBLISHED => _e('PUBLISHED'),
        Model_Ad::STATUS_UNCONFIRMED => _e('UNCONFIRMED'),
        Model_Ad::STATUS_SPAM => _e('SPAM'),
        Model_Ad::STATUS_UNAVAILABLE => _e('UNAVAILABLE'),
        Model_Ad::STATUS_SOLD => _e('SOLD'),
    ];
?>

<?= Form::errors() ?>

<div class="mb-4">
    <h1 class="h2"><?=$ad->title?> <small><?=_e('Edit Advertisement')?></small></h1>
    <p>
        <span class="badge badge-warning"><?= array_key_exists($ad->status, $ad_status) ? $ad_status[$ad->status] : '' ?></span>
    </p>

    <a class="btn btn-primary" target="_blank" href="<?=Route::url('ad', array('controller'=>'ad','category'=>$ad->category->seoname,'seotitle'=>$ad->seotitle))?>">
        <?=_e('View Advertisement')?>
    </a>

    <? if (in_array($ad->status, [Model_Ad::STATUS_UNAVAILABLE, Model_Ad::STATUS_SOLD]) AND !in_array(core::config('general.moderation'), Model_Ad::$moderation_status)
        ):?>
        <a
            href="<?=Route::url('oc-panel', array('controller'=>'myads','action'=>'activate','id'=>$ad->id_ad))?>"
            class="btn btn-success"
            title="<?=__('Activate?')?>"
            data-toggle="confirmation"
            data-btnOkLabel="<?=__('Yes, definitely!')?>"
            data-btnCancelLabel="<?=__('No way!')?>">
            <i class="fa fa-check"></i> <?=_e('Activate')?>
        </a>
    <?endif?>
</div>

<?if((core::config('payment.pay_to_go_on_top') > 0
        AND core::config('payment.to_top') != FALSE )
        OR (core::config('payment.to_featured') != FALSE AND $ad->featured < Date::unix2mysql() )):?>
    <?= View::factory('ads/_promotion', compact('ad')) ?>
<?endif?>

<?if (core::count($orders) > 0) :?>
    <div class="card bg-warning mb-4 border-0">
        <div class="card-body">
            <?foreach ($orders as $order):?>
                <a class="btn btn-light" href="<?=Route::url('default', array('controller'=> 'ad','action'=>'checkout' , 'id' => $order->id_order))?>">
                    <i class="fa fa-shopping-cart"></i> <?=_e('Pay')?> <?=$order->description?>  
                </a>
            <?endforeach?>
        </div>
    </div>
<?endif?>

<?if(Auth::instance()->get_user()->is_admin()):?>
    <? $owner = new Model_User($ad->id_user)?>
    <table class="table table-bordered admin-table-user">
        <tr>
            <th><?=_e('ID User')?></th>
            <th><?=_e('Profile')?></th>
            <th><?=_e('Name')?></th>
            <th><?=_e('Email')?></th>
            <th><?=_e('Status')?></th>
        </tr>
        <tbody>
            <tr>
                <td><?= $ad->id_user?></td>
                <td>
                    <a href="<?=Route::url('profile', array('seoname'=>$owner->seoname))?>" alt="<?=HTML::chars($owner->seoname)?>"><?= $owner->seoname?></a>
                </td>
                <td><?= $owner->name?></td>
                <td>
                    <a href="<?=Route::url('contact')?>"><?= $owner->email?></a>
                </td>
                <td>
                    <strong><?= array_key_exists($ad->status, $ad_status) ? $ad_status[$ad->status] : '' ?></strong>
                </td>
            </tr>
        </tbody>
    </table>
<?endif?>

<?= View::factory('oc-panel/profile/ads/_form', compact('ad')) ?>

<?= View::factory('oc-panel/profile/ads/_images-form', compact('ad')) ?>

<div class="modal modal-statc fade" id="processing-modal" data-backdrop="static" data-keyboard="false">
    <div class="modal-body">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title"><?=_e('Processing...')?></h4>
                </div>
                <div class="modal-body">
                    <div class="progress progress-striped active">
                        <div class="progress-bar" style="width: 100%"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?=View::factory('pages/ad/new_scripts')?>
