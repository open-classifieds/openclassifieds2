<div class="nav-wrapper bg-white" style="border-bottom: 1px solid #e5e5e5;">
    <nav class="navbar navbar-expand-lg navbar-light">
        <div class="container">
            <a class="navbar-brand" href="<?= Route::url('default') ?>">
                <?= core::config('general.site_name') ?>
            </a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse justify-content-between" id="navbarNav">
                <? if (core::count($menus = Menu::get()) > 0) : ?>
                    <?= View::factory('layouts/_navbar-nav-user', compact('menus')) ?>
                <? else : ?>
                    <?= View::factory('layouts/_navbar-nav-default') ?>
                <? endif ?>

                <?= View::factory('layouts/_navbar-nav-auth') ?>
            </div>
        </div>
    </nav>
</div>

<? if (! Auth::instance()->logged_in()) : ?>
    <?= View::factory('components/modal', [
        'modal_id' => 'login-modal',
        'modal_title' => __('Login'),
        'modal_body' => View::factory('auth/_login-form')
    ]) ?>

    <?= View::factory('components/modal', [
        'modal_id' => 'forgot-modal',
        'modal_title' => __('Forgot password'),
        'modal_body' => View::factory('auth/_forgot-form')
    ]) ?>

    <?= View::factory('components/modal', [
        'modal_id' => 'register-modal',
        'modal_title' => __('Register'),
        'modal_body' => View::factory('auth/_register-form', ['recaptcha_placeholder' => 'recaptcha4'])
    ]) ?>
<?endif?>

<? if (Auth::instance()->logged_in() AND
    Core::config('general.pusher_notifications')) : ?>
    <?= View::factory('layouts/_pusher') ?>
<? endif ?>
