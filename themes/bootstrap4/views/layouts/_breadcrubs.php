<? if (core::count($breadcrumbs) > 0) : ?>
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mt-4 mb-0">
                <? foreach ($breadcrumbs as $breadcrumb) : ?>
                    <? if ($breadcrumb->get_url() !== NULL) :  ?>
                        <li class="breadcrumb-item">
                            <a title="<?=HTML::chars($breadcrumb->get_title())?>" href="<?=$breadcrumb->get_url()?>"><?=$breadcrumb->get_title()?></a>
                        </li>
                    <? else : ?>
                        <li class="breadcrumb-item active"><?=$breadcrumb->get_title()?></li>
                    <? endif ?>
                <? endforeach ?>
            </ol>
        </nav>
    </div>
<? endif ?>
