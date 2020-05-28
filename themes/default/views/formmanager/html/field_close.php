    <? if ($field['error'] && $field['error_text']): ?><span class="help-inline"><?= $field['error_text'] ?></span><? endif ?>
    <? if ($field['help']): ?><p class="help-block"><?= $field['help'] ?></p><? endif ?>
    <? if (isset($field['suffix'])) echo $field['suffix'] ?>
</div>
