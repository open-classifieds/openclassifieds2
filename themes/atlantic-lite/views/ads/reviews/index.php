<?php defined('SYSPATH') or die('No direct script access.');?>

<?if ($ad->status != Model_Ad::STATUS_PUBLISHED AND $permission === FALSE AND ($ad->id_user != $user) OR (Core::extra_features() == FALSE)):?>

    <div class="jumbotron">
        <h1 class="h2"><?= __('This advertisement doesn´t exist, or is not yet published!')?></h1>
    </div>

<?else:?>
    <?=Form::errors()?>

    <div>
        <h1>
            <?=$ad->title.' '.__("Reviews")?>
        </h1>

        <hr>

        <div>
            <?for ($i=0; $i < round($ad->rate,1); $i++):?>
                <span class="fa fa-star"></span>
            <?endfor?>(<?=round($ad->rate,1)?>/<?=Model_Review::RATE_MAX?>)<span class="separator"> | </span>
            <span class="fa fa-comment"></span> <?=core::count($reviews)?> <?=_e('reviews')?>
        </div>

        <?if (Auth::instance()->logged_in()):?>
            <a class="btn btn-success pull-right" data-toggle="modal" data-target="#review-modal" href="#">
        <?else:?>
            <a class="btn btn-success pull-right" data-toggle="modal" data-dismiss="modal"
            href="<?=Route::url('oc-panel',array('directory'=>'user','controller'=>'auth','action'=>'login'))?>#login-modal">
        <?endif?>
            <i class="fa fa-bullhorn"></i> <?=__('Add New Review')?>
        </a>
    </div>

    <?if (Auth::instance()->logged_in()):?>
        <div id="review-modal" class="modal fade">
            <div class="modal-dialog">
                <div class="modal-content">
                    <?= View::factory('ads/reviews/_form') ?>
                </div>
            </div>
        </div>
    <?endif?>

    <hr>

    <?if(core::count($reviews)):?>
        <?foreach ($reviews as $review):?>
            <?= View::factory('ads/reviews/_review', compact('review')) ?>
        <?endforeach?>

    <?elseif (core::count($reviews) == 0):?>
        <div class="jumbotron">
            <h1 class="h2"><?=__('We do not have any reviews for this product')?></h1>
        </div>
    <?endif?>
<?endif?>
