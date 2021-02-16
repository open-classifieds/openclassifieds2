<div class="card card-follow mb-3">
    <? if($widget->text_title != ''): ?>
        <div class="card-header">
            <span class="h6"><?= $widget->text_title ?></span>
        </div>
    <? endif ?>

    <div class="card-body">
        <? if($widget->facebook!=''): ?>
            <a href="<?= $widget->facebook?>" alt="" title="<?= __('Facebook') ?>">
                <i class="fab fa-facebook-square fa-3x" aria-hidden="true"></i>
            </a>
        <? endif ?>

        <? if($widget->twitter!=''): ?>
            <a href="<?= $widget->twitter?>" alt="" title="<?= __('Twitter') ?>">
                <i class="fab fa-twitter-square fa-3x" aria-hidden="true"></i>
            </a>
        <? endif ?>

        <? if($widget->instagram!=''): ?>
            <a href="<?= $widget->instagram?>" alt="" title="<?= __('Instagram') ?>">
                <i class="fab fa-instagram fa-3x" aria-hidden="true"></i>
            </a>
        <? endif ?>

        <? if($widget->pinterest!=''): ?>
            <a href="<?= $widget->pinterest?>" alt="" title="<?= __('Pinterest') ?>">
                <i class="fab fa-pinterest-square fa-3x" aria-hidden="true"></i>
            </a>
        <? endif ?>

        <? if($widget->googleplus!=''): ?>
            <a href="<?= $widget->googleplus?>" alt="" title="<?= __('Google+') ?>">
                <i class="fab fa-google-plus-square fa-3x" aria-hidden="true"></i>
            </a>
        <? endif ?>

        <? if($widget->linkedin!=''): ?>
            <a href="<?= $widget->linkedin?>" alt="" title="<?= __('LinkedIn') ?>">
                <i class="fab fa-linkedin fa-3x" aria-hidden="true"></i>
            </a>
        <? endif ?>

        <? if($widget->youtube!=''): ?>
            <a href="<?= $widget->youtube?>" alt="" title="<?= __('Youtube') ?>">
                <i class="fab fa-youtube-square fa-3x" aria-hidden="true"></i>
            </a>
        <? endif ?>
        <? if($widget->flickr!=''): ?>
            <a href="<?= $widget->flickr?>" alt="" title="<?= __('Flickr') ?>">
                <i class="fab fa-flickr fa-3x" aria-hidden="true"></i>
            </a>
        <? endif ?>
    </div>
</div>
