<div class="card mb-4">
    <div class="card-body">
        <h5 class="card-title">
            <?= _e('2 Step Authentication') ?>
        </h5>

        <? if($user->google_authenticator != ''): ?>
            <p>
                <img src="<?= $user->google_authenticator_qr() ?>">
            </p>
            <p><?=_e('Google Authenticator Code')?>: <?=$user->google_authenticator?></p>
            <p>
                <a class="btn btn-warning" href="<?=Route::url('oc-panel', ['controller' => 'profile', 'action' => '2step', 'id' => 'disable'])?>">
                    <span class="glyphicon glyphicon-minus" aria-hidden="true"></span> <?=_e('Disable')?>
                </a>
            </p>
        <? else: ?>
            <?
                require Kohana::find_file('vendor', 'GoogleAuthenticator');

                $ga = new PHPGangsta_GoogleAuthenticator();

                if(($ga_secret_temp = Session::instance()->get('ga_secret_temp')) == NULL)
                {
                    Session::instance()->set('ga_secret_temp',$ga->createSecret());
                }
            ?>

            <p>
                <img src="<?= $ga->getQRCodeGoogleUrl(Kohana::$base_url, Session::instance()->get('ga_secret_temp')) ?>">
            </p>

            <p>
                <a class="btn btn-primary" href="<?= Route::url('oc-panel', ['controller' => 'profile', 'action' => '2step', 'id' => 'enable']) ?>">
                    <i class="fa fa-check-circle" aria-hidden="true"></i> <?= _e('Enable') ?>
                </a>
            </p>
        <? endif ?>
    </div>

    <div class="card-body border-top">
        <h6 class="card-title">
            <?= _e('2 step authentication provided by Google Authenticator.') ?>
        </h6>

        <a class="btn btn-light" href="https://play.google.com/store/apps/details?id=com.google.android.apps.authenticator2"><i class="fab fa-android"></i> Android</a>
        <a class="btn btn-light" href="https://itunes.apple.com/us/app/google-authenticator/id388497605?mt=8"><i class="fab fa-apple"></i> iOS</a>
    </div>
</div>
