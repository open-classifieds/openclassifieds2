<?php defined('SYSPATH') or die('No direct script access.');?>

<?if (Core::config('general.recaptcha_type') == 'invisible'):?>
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    <div
        id="<?= $id ?>"
        class="g-recaptcha"
        data-sitekey="<?= Core::config('general.recaptcha_sitekey') ?>"
        data-size="invisible"
        data-callback="recaptcha_submit"
    ></div>
    <input type="hidden" name="g-recaptcha-response" value="">
<?else:?>
    <?= Captcha::recaptcha_display() ?>

    <div id="<?= $id ?>"></div>
<?endif?>

