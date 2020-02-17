<div class="row justify-content-center">
    <div class="col-12 col-md-9">
        <h1 class="h2 mb-3"><?= $faq->title ?></h1>

        <?= Text::bb2html($faq->description, TRUE, FALSE) ?>

        <?= $disqus ?>
    </div>

    <div class="col-12 col-md-3">
        <? foreach (Widgets::render('sidebar') as $widget) : ?>
            <?= $widget ?>
        <? endforeach ?>
    </div>
</div>
