<ul class="navbar-nav">
    <? if (Auth::instance()->logged_in()) : ?>
        <li class="nav-item dropdown mr-2">
            <a class="nav-link dropdown-toggle" href="#" id="dropdown-auth" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <? if (Auth::instance()->get_user()->get_profile_images()) :?>
                    <img src="<?= Auth::instance()->get_user()->get_profile_image() ?>" class="tw-w-6 tw-inline tw-rounded-full">
                <? else : ?>
                    <?= Auth::instance()->get_user()->name ?>
                <? endif ?>
            </a>
            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdown-auth">
                <a class="dropdown-item" href="<?= Route::url('oc-panel', ['controller' => 'home', 'action' => 'index']) ?>">
                    <?= _e('Panel') ?>
                </a>
                <a class="dropdown-item" href="<?= Route::url('oc-panel', ['controller' => 'myads', 'action' => 'index']) ?>">
                    <?= _e('My Advertisements') ?>
                </a>
                <a class="dropdown-item" href="<?= Route::url('oc-panel', ['controller' => 'profile', 'action' => 'favorites']) ?>">
                    <?= _e('My Favorites') ?>
                </a>
                <? if (core::config('payment.paypal_seller') == TRUE OR Core::config('payment.stripe_connect') == TRUE): ?>
                    <a class="dropdown-item" href="<?= Route::url('oc-panel', ['controller' => 'profile', 'action' => 'sales']) ?>">
                        <?= _e('My Sales') ?>
                    </a>
                <? endif ?>
                <? if (Model_Order::by_user(Auth::instance()->get_user())->count_all() > 0) : ?>
                    <a class="dropdown-item" href="<?= Route::url('oc-panel', ['controller' => 'profile', 'action' => 'orders']) ?>">
                        <?= _e('My Payments') ?>
                    </a>
                <? endif ?>
                <? if (core::config('general.messaging') == TRUE): ?>
                    <a class="dropdown-item" href="<?= Route::url('oc-panel', ['controller' => 'messages', 'action' => 'index']) ?>">
                        <?= _e('Messages') ?>
                    </a>
                <? endif ?>
                <a class="dropdown-item" href="<?= Route::url('oc-panel', ['controller' => 'profile', 'action' => 'subscriptions']) ?>">
                    <?= _e('Subscriptions') ?>
                </a>
                <a class="dropdown-item" href="<?= Route::url('oc-panel', ['controller' => 'profile', 'action' => 'edit']) ?>">
                    <?= _e('Edit profile') ?>
                </a>
                <a class="dropdown-item" href="<?= Route::url('oc-panel', ['controller' => 'profile', 'action' => 'public']) ?>">
                    <?= _e('Public profile') ?>
                </a>
                <div class="dropdown-divider"></div>
                <a class="dropdown-item" href="<?= Route::url('oc-panel', ['directory' => 'user', 'controller' => 'auth', 'action' => 'logout']) ?>">
                    <?= _e('Logout') ?>
                </a>
                <a class="dropdown-item" href="<?= Route::url('default') ?>">
                    <?= _e('Visit Site') ?>
                </a>
                <? if (Auth::instance()->get_user()->is_admin()
                    OR Auth::instance()->get_user()->is_moderator()
                    OR Auth::instance()->get_user()->is_translator()): ?>
                    <div class="dropdown-divider"></div>
                    <h6 class="dropdown-header"><?= _e('Live translator') ?></h6>
                    <? if (Core::request('edit_translation') == '1'): ?>
                        <a class="dropdown-item" href="?edit_translation=0">
                            <?=__('Disable')?>
                        </a>
                    <? else: ?>
                        <a class="dropdown-item" href="?edit_translation=1">
                            <?=__('Enable')?>
                        </a>
                    <? endif ?>
                <? endif ?>
            </div>
        </li>
    <? else : ?>
        <li class="nav-item mr-2">
            <a class="btn btn-outline-secondary"
                data-toggle="modal"
                href="<?= Route::url('oc-panel', ['directory' => 'user', 'controller' => 'auth', 'action' => 'login']) ?>"
                data-target="#login-modal">
                <?= __('Login') ?>
            </a>
        </li>
    <? endif ?>

    <? if ((Core::config('advertisement.only_admin_post') != 1)
        OR (Core::config('advertisement.only_admin_post') == 1
            AND Auth::instance()->logged_in()
            AND (Auth::instance()->get_user()->is_admin()
                OR Auth::instance()->get_user()->is_moderator()))) : ?>
        <li class="nav-item nav-item tw-mb-2 md:tw-mb-0">
            <a class="btn btn-primary" href="<?= Route::url('post_new') ?>"><?= __('Publish new ') ?></a>
        </li>
    <? endif ?>
</ul>
