<div class="card card-follow mb-3">
    <? if($widget->text_title != ''): ?>
        <div class="card-header">
            <span class="h6"><?= $widget->text_title ?></span>
        </div>
    <? endif ?>

    <div class="card-body">
        <? if($widget->redirect_url != ''): ?>
            <a href="<?= $widget->redirect_url ?>">
                <img src="<?= $widget->image_url ?>" class="img-fluid">
            </a>
        <? else: ?>
            <img src="<?= $widget->image_url ?>" class="img-fluid">
        <? endif ?>
    </div>
</div>
