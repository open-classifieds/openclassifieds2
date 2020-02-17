<!DOCTYPE html>
<?= View::factory('layouts/_meta', compact(
    'title',
    'meta_keywords',
    'meta_description',
    'meta_copyright',
    'amphtml'
)) ?>

<?= Theme::styles($styles) ?>
<?= Theme::scripts($scripts) ?>
<?= Core::config('general.html_head') ?>
<?= View::factory('layouts/_analytics') ?>

<?= View::factory('layouts/_terms_modal') ?>

<?= View::factory('layouts/_header') ?>

<?= Breadcrumbs::render('breadcrumbs') ?>

<div class="content-wrapper pt-4">
    <div class="container">
        <?= View::factory('layouts/_offline') ?>

        <?= Alert::show() ?>

        <?= $content ?>
    </div>
</div>

<?= View::factory('layouts/_footer') ?>

<?= Theme::scripts($scripts,'footer') ?>
<?= Theme::scripts($scripts,'async_defer', 'default', ['async' => '', 'defer' => '']) ?>
<?= core::config('general.html_footer') ?>

<?= Kohana::$environment === Kohana::DEVELOPMENT ? View::factory('profiler') : '' ?>
