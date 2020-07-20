<div class="card card-follow mb-3">
    <? if($widget->text_title != ''): ?>
        <div class="card-header">
            <span class="h6"><?= $widget->text_title ?></span>
        </div>
    <? endif ?>

    <div class="card-body">
        <? if($widget->facebook!=''): ?>
            <a href="<?= $widget->facebook?>" alt="" title="<?= __('Facebook') ?>">
                <i class="fa fa-facebook-square fa-3x" aria-hidden="true"></i>
            </a>
        <? endif ?>

        <? if($widget->twitter!=''): ?>
            <a href="<?= $widget->twitter?>" alt="" title="<?= __('Twitter') ?>">
                <i class="fa fa-twitter-square fa-3x" aria-hidden="true"></i>
            </a>
        <? endif ?>

        <? if($widget->instagram!=''): ?>
            <a href="<?= $widget->instagram?>" alt="" title="<?= __('Instagram') ?>">
                <i class="fa fa-instagram fa-3x" aria-hidden="true"></i>
            </a>
        <? endif ?>

        <? if($widget->pinterest!=''): ?>
            <a href="<?= $widget->pinterest?>" alt="" title="<?= __('Pinterest') ?>">
                <i class="fa fa-pinterest-square fa-3x" aria-hidden="true"></i>
            </a>
        <? endif ?>

        <? if($widget->googleplus!=''): ?>
            <a href="<?= $widget->googleplus?>" alt="" title="<?= __('Google+') ?>">
                <i class="fa fa-google-plus-square fa-3x" aria-hidden="true"></i>
            </a>
        <? endif ?>

        <? if($widget->linkedin!=''): ?>
            <a href="<?= $widget->linkedin?>" alt="" title="<?= __('LinkedIn') ?>">
                <i class="fa fa-linkedin-square fa-3x" aria-hidden="true"></i>
            </a>
        <? endif ?>

        <? if($widget->youtube!=''): ?>
            <a href="<?= $widget->youtube?>" alt="" title="<?= __('Youtube') ?>">
                <i class="fa fa-youtube-square fa-3x" aria-hidden="true"></i>
            </a>
        <? endif ?>
        <? if($widget->flickr!=''): ?>
            <a href="<?= $widget->flickr?>" alt="" title="<?= __('Flickr') ?>">
                <i class="fa fa-flickr fa-3x" aria-hidden="true"></i>
            </a>
        <? endif ?>
    </div>
</div>
