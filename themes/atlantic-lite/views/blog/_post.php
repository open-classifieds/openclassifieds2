<div class="mb-5">
    <h2 class="h2 mb-1"><?= $post->title ?></h2>

    <p class="text-muted mb-3">
        <?= Date::format($post->created, core::config('general.date_format')) ?>
    </p>

    <?= Text::truncate_html($post->description, 255, NULL) ?>

    <p>
        <a title="<?= HTML::chars($post->title) ?>"
            href="<?= Route::url('blog', ['seotitle' => $post->seotitle]) ?>"
        ><i class="fas fa-share"></i> <?=_e('Read more')?> </a>
    </p>

    <?if ($user !== NULL AND $user!=FALSE AND $user->is_admin()):?>
        <hr>
        <a href="<?= Route::url('oc-panel', ['controller' => 'blog', 'action' => 'update', 'id' => $post->id_post]) ?>">
            <?= _e("Edit") ?>
        </a> |
        <a href="<?= Route::url('oc-panel', ['controller' => 'blog', 'action' => 'delete', 'id' => $post->id_post]) ?>"
            onclick="return confirm('<?= __('Delete?') ?>');">
            <?= _e("Delete") ?>
        </a>
    <?endif?>
</div>
