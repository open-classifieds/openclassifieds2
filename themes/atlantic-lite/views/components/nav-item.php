<li title="<?= HTML::chars($route) ?>" class="nav-item <?= (strtolower(Request::current()->controller()) == $controller and Request::current()->action() == $action) ? 'active' : '' ?> <?= $style ?>" >
    <a class="nav-link" href="<?= Route::url($route, array('controller' => $controller, 'action' => $action, 'id' => $id)) ?>">
        <? if ($icon !== null) : ?>
            <i class="<?= $icon ?>"></i>
        <? endif ?>
        <?= $name ?>
    </a>
</li>
