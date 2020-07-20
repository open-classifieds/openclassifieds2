<div class="row justify-content-center">
    <div class="col-12 col-md-9">
        <h3 class="h4 pb-4 mb-4 border-bottom">
            <?=_e('Frequently Asked Questions') ?>
        </h3>

        <? if(core::count($faqs)): ?>
            <? foreach ($faqs as $faq) : ?>
                <?= View::factory('faqs/_faq', compact('faq')) ?>
            <? endforeach ?>
        <? else: ?>
            <div class="jumbotron text-center">
                <p class="lead"><?=_e('We do not have any FAQ-s')?></p>
            </div>
        <? endif ?>
    </div>

    <div class="col-12 col-md-3">
        <? foreach (Widgets::render('sidebar') as $widget) : ?>
            <?= $widget ?>
        <? endforeach ?>
    </div>
</div>
