<div class="row justify-content-center">
    <div class="col-12 col-md-9">
        <form action="<?= Route::URL('blog') ?>" method="get">
            <div class="btn-toolbar justify-content-end" role="toolbar" aria-label="Toolbar with button groups">
                <div class="input-group">
                    <input type="text" class="form-control" placeholder="<?=__('Search')?>"
                        type="text" value="<?= HTML::chars(core::get('search')) ?>" name="search"
                    >
                    <div class="input-group-append">
                        <button class="btn btn-outline-secondary" type="submit" placeholder="<?= __('Search') ?>">
                            <?= _e('Search') ?>
                        </button>
                    </div>
                </div>
            </div>
        </form>

        <h3 class="h4 pb-4 mb-4 border-bottom">
            <?= Core::config('general.site_name')?> <?=_e('Blog') ?>
        </h3>

        <? if(core::count($posts)): ?>
            <? foreach ($posts as $post) : ?>
                <?= View::factory('blog/_post', compact('post', 'user')) ?>
            <? endforeach ?>

            <?= $pagination ?>
        <? else: ?>
            <div class="jumbotron text-center">
                <p class="lead"><?=_e('We do not have any blog posts')?></p>
            </div>
        <? endif ?>
    </div>

    <div class="col-12 col-md-3">
        <? foreach (Widgets::render('sidebar') as $widget) : ?>
            <?= $widget ?>
        <? endforeach ?>
    </div>
</div>
