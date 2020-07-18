<div class="pb-4">
    <div class="container">
        <hr>
        <nav class="row justify-content-between align-items-center">
            <div class="col-auto text-sm-right">
                <ul class="list-inline">
                    <? if (Cookie::get('user_location')) : ?>
                        <li class="list-inline-item">
                            <a href="<?= Route::url('default') ?>?user_location=0">
                                <?= _e('Change Location') ?>
                            </a>
                        </li>
                    <? endif ?>

                    <? if (Core::config('general.multilingual')) : ?>
                        <li class="list-inline-item dropdown dropup">
                            <a class="dropdown-toggle" href="#" id="languages-dropdown" role="button" data-toggle="dropdown">
                                <i class="fas fa-language"></i> <?=i18n::get_display_language(i18n::$locale)?>
                            </a>

                            <div class="dropdown-menu" aria-labelledby="languages-dropdown">
                                <? foreach (i18n::get_selectable_languages() as $locale => $language) : ?>
                                    <? if (i18n::$locale != $locale) : ?>
                                        <a class="dropdown-item" href="<?= Route::url('default') ?>?language=<?= $locale ?>">
                                            <?= $language ?>
                                        </a>
                                    <? endif ?>
                                <? endforeach ?>
                            </div>
                        </li>
                    <? endif ?>
                </ul>
            </div>
        </nav>

        <!--This is the license for Open Classifieds, do not remove -->
        <div class="row">
            <div class="col">
                <small>
                    <? if (Core::extra_features() == FALSE) : ?>
                        Web Powered by <a href="https://yclas.com?utm_source=<?= URL::base() ?>&utm_medium=oc_footer&utm_campaign=<?= date('Y-m-d') ?>" title="Best PHP Script Classifieds Software">Yclas</a>
                        2009 - <?= date('Y') ?>
                    <? else : ?>
                        <?= core::config('general.site_name') ?> <?= date('Y') ?>
                    <? endif ?>
                </small>
            </div>
        </div>
    </div>
</div>
