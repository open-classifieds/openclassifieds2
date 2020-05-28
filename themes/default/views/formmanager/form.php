<?= Form::open($form->action, $form->attributes); ?>

    <div class="shadow sm:rounded-md sm:overflow-hidden">
        <div class="px-4 py-5 bg-white sm:p-6">
            <div class="grid grid-cols-3 gap-6">
                <? if ($form->fieldsets): ?>
                    <? foreach ($form->fields as $field): if ($field['display_as'] == 'hidden'): ?>
                        <?= View::factory('formmanager/html/hidden', array('field' => $field))->render(); ?>
                    <? endif; endforeach; ?>
                    <?= View::factory('formmanager/html/fieldsets', array('fieldsets' => $form->fieldsets))->render(); ?>
                <? else: ?>
                    <?= View::factory('formmanager/html/fields', array('fields' => $form->fields))->render(); ?>
                <? endif; ?>
            </div>
        </div>
        <div class="px-4 py-3 bg-gray-50 text-right sm:px-6">
            <div class="flex justify-end">
                <?= View::factory('formmanager/html/buttons', array('buttons' => $form->buttons))->render(); ?>
            </div>
        </div>
    </div>

<?= Form::close(); ?>
