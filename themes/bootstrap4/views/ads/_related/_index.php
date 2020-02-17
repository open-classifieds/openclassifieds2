<?if(core::count($ads)):?>
    <hr>

    <h3 class="h4 mb-3"><?=_e('Related ads')?></h3>

    <div class="card">
        <ul class="list-group list-group-flush">
            <?foreach($ads as $ad ):?>
                <?= View::factory('ads/_related/_ad', compact('ad')) ?>
            <?endforeach?>
        </ul>
    </div>
<?endif?>
