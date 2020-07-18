<ul class="navbar-nav">
    <? foreach ($menus as $menu) : ?>
        <li class="nav-item <?= (Request::current()->uri() == $menu['url']) ? 'active' : '' ?>" >
            <a class="nav-link" href="<?= $menu['url'] ?>" target="<?= $menu['target'] ?>">
                <? if ($menu['icon'] != '') : ?>
                    <i class="<?= $menu['icon'] ?>"></i>
                <? endif ?>
                <?= $menu['title'] ?>
            </a>
        </li>
    <? endforeach ?>
</ul>
