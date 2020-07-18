<?php defined('SYSPATH') or die('No direct script access.');?>

<div class="card card-seller mb-3">
    <?if ($widget->text_title!=''):?>
        <div class="card-header">
            <span class="h6"><?= $widget->text_title ?></span>
        </div>
    <?endif?>

    <div class="card-body text-center">
        <h4><?= $widget->user->name ?></h4>
        <?if (Core::config('advertisement.reviews') == 1): ?>
            <p>
                <? for ($j=0; $j < round($widget->user->rate,1); $j++): ?>
                    <span class="fa fa-star"></span>
                <? endfor ?>
            </p>
        <?endif?>
        <a title="<?= __('Visit profile') ?>" href="<?= Route::url('profile',  ['seoname' => $widget->user->seoname])?>" class="img-thumbnail">
            <img class="img-fluid" src="<?= Core::imagefly($widget->user->get_profile_image(), 200, 200) ?>" alt="<?=__('Profile Picture')?>">
        </a>
        <p>
            <a title="<?= __('Visit profile') ?>" href="<?= Route::url('profile',  ['seoname' => $widget->user->seoname]) ?>">
                <span class="label label-light"><?= $widget->user_ads ?> <?= _e('Ads') ?></span> - <?= _e('See All') ?>
            </a>
        </p>
        <? if($widget->description): ?>
            <p><?= $widget->user->description ?></p>
        <? endif ?>

        <? if($widget->last_login): ?>
            <p><b><?= _e('Last Login') ?>:</b> <?= $widget->user->last_login ?></p>
        <? endif ?>

        <? if($widget->custom_fields AND Core::extra_features() == TRUE): ?>
            <p>
                <ul class="list-unstyled">
                    <? foreach ($widget->user->custom_columns(TRUE) as $name => $value): ?>
                        <? if($value != ''): ?>
                            <? if($name!='Whatsapp' AND $name!='Skype' AND $name!='Telegram'): ?>
                                <li>
                                    <strong><?= $name ?>:</strong>
                                    <? if($value=='checkbox_1'): ?>
                                        <i class="fa fa-check"></i>
                                    <? elseif($value=='checkbox_0'): ?>
                                        <i class="fa fa-times"></i>
                                    <? else: ?>
                                        <?= $value ?>
                                    <? endif ?>
                                </li>
                            <? endif ?>
                        <? endif ?>
                    <?endforeach?>
                </ul>
                <? if(isset($widget->user->cf_whatsapp) AND $widget->user->cf_whatsapp != ''): ?>
                    <a
                        href="https://api.whatsapp.com/send?phone=<?= $widget->user->cf_whatsapp ?>"
                        title="Chat with <?= $widget->user->name ?>"
                        alt="Whatsapp"
                    ><i class="fa fa-2x fa-whatsapp" style="color:#43d854"></i></a>
                <? endif ?>

                <? if(isset($widget->user->cf_skype) AND $widget->user->cf_skype != ''): ?>
                    <a
                        href="skype:<?=$widget->user->cf_skype?>?chat"
                        title="Chat with <?=$widget->user->name?>"
                        alt="Skype"
                    ><i class="fa fa-2x fa-skype" style="color:#00aff0"></i></a>
                <? endif ?>

                <? if(isset($widget->user->cf_telegram) AND $widget->user->cf_telegram!=''): ?>
                    <a
                        href="tg://resolve?domain=<?=$widget->user->cf_telegram?>"
                        id="telegram"
                        title="Chat with <?=$widget->user->name?>"
                        alt="Telegram"
                    ><i class="fa fa-2x fa-telegram" style="color:#0088cc"></i></a>
                <? endif ?>
            </p>
        <?endif?>

        <br>

        <? if ($widget->ad->can_contact() AND $widget->contact): ?>
            <p>
                <?if ((Core::config('advertisement.login_to_contact') == TRUE OR core::config('general.messaging') == TRUE)
                    AND !Auth::instance()->logged_in()): ?>
                    <a
                        class="form-control btn btn-success"
                        data-toggle="modal"
                        data-dismiss="modal"
                        href="<?= Route::url('oc-panel', ['directory' => 'user', 'controller' => 'auth', 'action' => 'login']) ?>#login-modal"
                    >
                        <i class="fa fa-envelope"></i>&nbsp;&nbsp;<?=_e('Send Message')?>
                    </a>
                <? else: ?>
                    <button class="form-control btn btn-success" type="button" data-toggle="modal" data-target="#contact-modal">
                        <i class="fa fa-envelope"></i>&nbsp;&nbsp;<?=_e('Send Message')?>
                    </button>
                <? endif ?>
            </p>
        <? endif ?>

        <br>

        <? if ($widget->location): ?>
            <? if ($widget->location == 1 AND core::config('advertisement.gm_api_key')): ?>
                <? if($widget->user->address !== NULL AND $widget->user !== NULL AND $widget->user !== NULL): ?>
                    <p>
                        <h3><?= $widget->user->name ?><?= _e('\'s location') ?></h3>
                        <img
                            class="img-fluid"
                            src="//maps.googleapis.com/maps/api/staticmap?language=<?= i18n::get_gmaps_language(i18n::$locale) ?>&amp;zoom=<?= Core::config('advertisement.map_zoom') ?>&amp;scale=false&amp;size=200x200&amp;maptype=roadmap&amp;format=png&amp;visual_refresh=true&amp;markers=size:large%7Ccolor:red%7Clabel:·%7C<?= $widget->user->latitude ?>,<?= $widget->user->longitude ?>&amp;key=<?= Core::config('advertisement.gm_api_key') ?>"
                            alt="<?= HTML::chars($widget->user->name) ?> <?= _e('Map') ?>"
                            style="width: 100%;"
                        >
                    </p>
                    <p>
                        <a
                            class="btn btn-default btn-sm"
                            href="<?= Route::url('map') ?>?id_user=<?= $widget->user->id_user ?>"
                        >
                            <span class="fa fa-globe"></span> <?=_e('Map View')?>
                        </a>
                    </p>
                <? elseif(Auth::instance()->logged_in() AND Auth::instance()->get_user()->id_user == $widget->user->id_user): ?>
                    <p>
                        <div class="alert alert-danger" role="alert">
                            <a href="<?= Route::url('oc-panel', ['controller' => 'profile', 'action' => 'edit']) ?>" class="alert-link">
                                <?=__('Click here to enter your address.')?>
                            </a>
                        </div>
                    </p>
                <? endif ?>
            <? elseif($widget->location == 1 AND Auth::instance()->logged_in() AND Auth::instance()->get_user()->is_admin()): ?>
                <div class="alert alert-danger" role="alert">
                    <a href="<?= Route::url('oc-panel', ['controller' => 'settings', 'action' => 'form']) ?>" class="alert-link">
                        <?=__('Please set your Google API key on advertisement configuration.')?>
                    </a>
                </div>
            <? elseif($widget->location == 2 AND $widget->user->address != NULL) : ?>
                <p><?= _e('Address:') ?> <?= $widget->user->address ?></p>
            <? endif ?>
        <? endif ?>
    </div>
</div>
