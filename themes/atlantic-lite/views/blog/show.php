<div class="row justify-content-center">
    <div class="col-12 col-md-9">
        <h1 class="h2 mb-1"><?= $post->title ?></h1>

        <p class="text-muted mb-3">
            <?= Date::format($post->created, core::config('general.date_format')) ?>
            |
            <?= $post->user->name?> <?=$post->user->is_verified_user() ?>
        </p>

        <?= $post->description ?>

        <?= $post->disqus() ?>

        <nav class="mb-5">
            <?if($previous->loaded()):?>
                <a class="btn btn-outline-primary"
                    href="<?= Route::url('blog', ['seotitle'=>$previous->seotitle])?>"
                    title="<?=HTML::chars($previous->title) ?>"
                ><?= $previous->title ?></a>
            <?endif?>

            <?if($next->loaded()):?>
                <a class="btn btn-outline-primary"
                    href="<?= Route::url('blog', ['seotitle'=>$next->seotitle])?>"
                    title="<?=HTML::chars($next->title) ?>"
                ><?= $next->title ?></a>
            <?endif?>
        </nav>
    </div>

    <div class="col-12 col-md-3">
        <? foreach (Widgets::render('sidebar') as $widget) : ?>
            <?= $widget ?>
        <? endforeach ?>
    </div>
</div>
