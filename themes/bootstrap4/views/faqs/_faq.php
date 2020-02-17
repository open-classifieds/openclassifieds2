<div class="mb-5">
    <h2 class="h4 mb-3">
        <a title="<?= HTML::chars($faq->title) ?>" href="<?= Route::url('faq', ['seotitle' => $faq->seotitle]) ?>">
            <?= $faq->title ?>
        </a>
    </h2>

    <div class="mb-1">
        <?= Text::limit_chars(Text::removebbcode($faq->description), 400, NULL, TRUE) ?>
    </div>

    <p>
        <a title="<?= HTML::chars($faq->title) ?>"
            href="<?= Route::url('faq', ['seotitle' => $faq->seotitle]) ?>"
        ><?=_e('Read more')?></a>
    </p>
</div>
