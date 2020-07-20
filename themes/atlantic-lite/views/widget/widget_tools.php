<?php defined('SYSPATH') or die('No direct script access.');?>

<div class="card mb-3">
    <? if($widget->ad != FALSE): ?>
        <div class="card-body">
            <div>
                <? if(core::config('payment.pay_to_go_on_top') > 0 AND core::config('payment.to_top') != FALSE): ?>
                    <a
                        class="btn btn-danger btn-block"
                        type="button"
                        href="<?= Route::url('default', ['action' => 'to_top', 'controller' => 'ad', 'id' => $widget->ad->id_ad]) ?>"
                    >
                        <?= _e('Go Top!') ?> <?= i18n::money_format(Core::config('payment.pay_to_go_on_top'), Core::config('payment.paypal_currency')) ?>
                    </a>
                <? endif ?>

                <? if(Core::config('payment.to_featured') != FALSE AND $widget->ad->featured < Date::unix2mysql()): ?>
                    <a
                        class="btn btn-danger btn-block"
                        type="button"
                        href="<?= Route::url('default', ['action' => 'to_featured', 'controller' => 'ad', 'id' => $widget->ad->id_ad]) ?>"
                    >
                        <?= _e('Go Featured!') ?> <?= i18n::money_format(Model_Order::get_featured_price(), Core::config('payment.paypal_currency')) ?>
                    </a>
                <? endif ?>
            </div>

            <div>
                <a
                    class="btn btn-primary"
                    href="<?= Route::url('oc-panel', ['controller' => 'myads', 'action' => 'update', 'id' => $widget->ad->id_ad]) ?>"
                >
                    <i class="fa fa-edit"></i> <?= _e("Edit") ?>
                </a>

                <a
                    class="btn btn-primary"
                    href="<?= Route::url('oc-panel', ['controller' => 'ad', 'action' => 'deactivate', 'id' => $widget->ad->id_ad]) ?>"
                    onclick="return confirm('<?= __('Deactivate?') ?>');"
                >
                    <i class="fa fa-off"></i> <?= _e("Deactivate") ?>
                </a>

                <? if(Auth::instance()->logged_in() AND Auth::instance()->get_user()->is_admin()): ?>
                    <a
                        class="btn btn-primary"
                        href="<?=Route::url('oc-panel', ['controller' => 'ad', 'action' => 'spam', 'id' => $widget->ad->id_ad])?>"
                        onclick="return confirm('<?= __('Spam?') ?>');"
                    >
                        <i class="fa fa-fire"></i> <?= _e("Spam") ?>
                    </a>
                    <a
                        class="btn btn-primary"
                        href="<?= Route::url('oc-panel', ['controller' => 'ad', 'action' => 'delete', 'id' => $widget->ad->id_ad]) ?>"
                        onclick="return confirm('<?= __('Delete?') ?>');"
                    >
                        <i class="fa fa-remove"></i> <?= _e("Delete") ?>
                    </a>
                <? endif ?>
            </div>

            <hr>

            <ul>
                <?foreach($widget->user_ads as $ad):?>
                    <li>
                        <a
                            title="<?= Html::chars($ad->title) ?>"
                            alt="<?= Html::chars($ad->title) ?>"
                            href="<?= Route::url('ad', ['category' => $ad->category->seoname, 'seotitle' => $ad->seotitle]) ?>"
                        >
                            <?= $ad->title ?>
                        </a>
                    </li>
                <?endforeach?>
            </ul>
        </div>
    <? endif ?>
</div>
