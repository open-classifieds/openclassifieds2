<? if ($field['field_name'] == 'formorm[description]') : ?>
    <?= Form::textarea($field['field_name'], $field['value'], [
        'x-data' => '',
        'x-init' => '$($refs.textarea).summernote(summernoteSettings())',
        'x-ref' => 'textarea',
        'class' => 'form-textarea mt-1 block w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5'] + $field['attributes']
    ) ?>
<? else : ?>
    <?= Form::textarea($field['field_name'], $field['value'], [
        'class' => 'form-textarea mt-1 block w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5'] + $field['attributes']
    ) ?>
<? endif ?>
