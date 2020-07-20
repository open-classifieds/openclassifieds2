<div class="card">
    <a href="<?= Route::url('ad', ['category' => $ad->category->seoname, 'seotitle' => $ad->seotitle]) ?>">
        <? if ($ad->get_first_image() !== NULL) : ?>
            <?= HTML::picture($ad->get_first_image('image'), ['w' => 132, 'h' => 132], ['992px' => ['w' => '132', 'h' => '132'], '320px' => ['w' => '648', 'h' => '648']], ['alt' => HTML::chars($ad->title), 'class' => 'card-img-top']) ?>
        <? elseif (($icon_src = $ad->category->get_icon()) !== false) : ?>
            <?= HTML::picture($icon_src, ['w' => 132, 'h' => 132], ['992px' => ['w' => '132', 'h' => '132'], '320px' => ['w' => '648', 'h' => '648']], ['alt' => HTML::chars($ad->title), 'class' => 'card-img-top']) ?>
        <? else : ?>
            <img data-src="holder.js/179x179?<?= str_replace('+', ' ', http_build_query(['text' => $ad->category->name, 'size' => 14, 'auto' => 'yes'])) ?>" alt="<?= HTML::chars($ad->title) ?>">
        <? endif ?>
    </a>
    <div class="card-body">
        <h5 class="card-title">
            <a href="<?= Route::url('ad', ['controller' => 'ad', 'category' => $ad->category->seoname, 'seotitle' => $ad->seotitle]) ?>">
                <?= $ad->title ?>
            </a>
        </h5>
        <p class="card-text">
            <?= Text::limit_chars(Text::removebbcode($ad->description), 30, NULL, TRUE) ?>
        </p>
    </div>
</div>
