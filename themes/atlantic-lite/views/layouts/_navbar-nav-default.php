<?
$categories = Model_Category::get_category_count();
$current_location = (New Model_Location());

if (Model_Location::current()->loaded())
{
    $current_location = Model_Location::current();
}
?>
<ul class="navbar-nav">
    <?= Theme::nav_link(__('Listing'), 'ad', 'fas fa-list', 'listing', 'list') ?>

    <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" href="#" id="categoriesDropdown" role="button" data-toggle="dropdown"><?= __('Categories') ?></a>
        <div class="dropdown-menu" aria-labelledby="categoriesDropdown">
            <? foreach ($categories as $category) : ?>
                <? if ($category['id_category_parent'] == 1 AND $category['id_category'] != 1) : ?>
                    <a class="dropdown-item" title="<?= HTML::chars($category['seoname']) ?>" href="<?= Route::url('list', array('category' => $category['seoname'], 'location' => $current_location->seoname)) ?>">
                        <?= $category['name'] ?>
                    </a>
                    <? if ($category['id_category_parent'] == 1) : ?>
                        <? $i = 0;
                        foreach ($categories as $child_category) : ?>
                            <? if ($child_category['id_category_parent'] == $category['id_category']) : ?>
                                <? $i++;
                                if ($i == 1) : ?>
                                    <div class="dropdown-menu" aria-labelledby="categoriesDropdown">
                                <? endif ?>
                                <a class="dropdown-item" title="<?= HTML::chars($child_category['name']) ?>" href="<?= Route::url('list', array('category' => $child_category['seoname'], 'location' => $current_location->seoname)) ?>">
                                    <? if (Theme::get('category_badge') != 1) : ?>
                                        <span class="pull-right badge badge-success"><?= number_format($child_category['count']) ?></span>
                                    <? endif ?>
                                    <span class="<?= Theme::get('category_badge') != 1 ? 'badged-name' : NULL ?>"><?= $child_category['name'] ?></span>
                                </a>
                            <? endif ?>
                        <? endforeach ?>
                        <? if ($i > 0) : ?>
                            </div>
                        <? endif ?>
                    <? endif ?>
                <? endif ?>
            <? endforeach ?>
        </div>
    </li>
    <? if (core::config('general.blog') == 1) : ?>
        <?= Theme::nav_link(__('Blog'), 'blog', '', 'index', 'blog') ?>
    <? endif ?>
    <? if (core::config('general.faq') == 1) : ?>
        <?= Theme::nav_link(__('FAQ'), 'faq', '', 'index', 'faq') ?>
    <? endif ?>
    <? if (core::config('general.forums') == 1) : ?>
        <?= Theme::nav_link(__('Forum'), 'forum', '', 'index', 'forum-home') ?>
    <? endif ?>
    <?= Theme::nav_link(__('Search'), 'ad', '', 'advanced_search', 'search') ?>
    <? if (core::config('advertisement.map') == 1) : ?>
        <?= Theme::nav_link(__('Map'), 'map', '', 'index', 'map') ?>
    <? endif ?>
    <?= Theme::nav_link(__('Contact'), 'contact', '', 'index', 'contact') ?>
</ul>
