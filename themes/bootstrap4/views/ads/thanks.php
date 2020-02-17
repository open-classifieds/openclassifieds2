<?php defined('SYSPATH') or die('No direct script access.');?>

<div class="mb-4">
    <?if ($page->loaded()):?>
        <h1 class="h2"><?=$page->title?></h1>
        <div><?=$page->description?></div>
    <?else:?>
        <h2 class="h2"><?=__('Thanks for submitting your advertisement')?></h2>
    <?endif?>
</div>

<?if ($page->loaded()):?>
    <div>
        <?=$page->description?>
    </div>
<?endif?>

<p class="text-center">
    <?if(core::config('general.moderation') == Model_Ad::POST_DIRECTLY) :?>
        <a class="btn btn-success" href="<?=Route::url('ad', array('controller'=>'ad','category'=>$ad->category->seoname,'seotitle'=>$ad->seotitle))?>"><?=__('Go to Your Ad')?></a>
    <?endif?>

    <?if(core::config('payment.to_featured') != FALSE AND $ad->featured < Date::unix2mysql()):?>
        <a class="btn btn-primary" type="button" href="<?=Route::url('default', array('action'=>'to_featured','controller'=>'ad','id'=>$ad->id_ad))?>">
            <?=__('Go Featured!')?> <?=i18n::format_currency(Model_Order::get_featured_price(),core::config('payment.paypal_currency'))?>
        </a>
    <?endif?>
</p>
