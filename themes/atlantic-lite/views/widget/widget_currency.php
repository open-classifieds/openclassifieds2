<?php defined('SYSPATH') or die('No direct script access.');?>

<?if(array_key_exists('currency', Model_Field::get_all()) AND (strtolower(Request::current()->controller())=='ad' AND strtolower(Request::current()->action())=='view')):?>
    <div class="card card-currency mb-3">
        <? if($widget->currency_title!=''): ?>
            <div class="card-header">
                <span class="h6"><?= $widget->currency_title ?></span>
            </div>
        <? endif ?>

        <div class="card-body">
            <?if(isset(Model_Ad::current()->cf_currency) AND !empty(Model_Ad::current()->cf_currency)):?>
                <div
                    class="form-group curry"
                    data-locale="<?= Model_Ad::current()->currency() ?>"
                    data-currencies="<?= $widget->currencies ?>"
                    data-default="<?= $widget->default ?>"
                    data-apikey="<?= $widget->apikey ?>"
                >
                    <div class="my-future-ddm"></div>
                </div>
            <?else:?>
                <div
                    class="form-group curry"
                    data-locale="<?= core::config('general.number_format') ?>"
                    data-currencies="<?= $widget->currencies ?>"
                    data-default="<?= $widget->default ?>"
                    data-apikey="<?= $widget->apikey ?>"
                >
                    <div class="my-future-ddm"></div>
                </div>
            <?endif?>
        </div>
    </div>
<?elseif((!array_key_exists('currency', Model_Field::get_all()))):?>
    <div class="card card-coupon mb-3">
        <? if($widget->currency_title!=''): ?>
            <div class="card-header">
                <span class="h6"><?= $widget->currency_title ?></span>
            </div>
        <? endif ?>

        <div class="card-body">
            <div
                class="form-group curry"
                data-locale="<?= core::config('general.number_format') ?>"
                data-currencies="<?= $widget->currencies ?>"
                data-default="<?= $widget->default ?>"
                data-apikey="<?= $widget->apikey ?>"
            >
                <div class="my-future-ddm"></div>
            </div>
        </div>
    </div>
<?endif?>
