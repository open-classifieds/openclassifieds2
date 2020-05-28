<div class="md:flex md:items-center md:justify-between">
    <div class="flex-1 min-w-0">
        <h2 class="text-2xl font-bold leading-7 text-gray-900 sm:text-3xl sm:leading-9 sm:truncate">
            <?=__('Translations')?> "<?=$edit_language?>" "<?=$translation_file?>"
        </h2>
        <div class="mt-1 sm:mt-0">
            <div class="mt-2 items-center text-sm leading-5 text-gray-500">
                <p><?=__('Here you can modify any text you find in your web.')?></p>
            </div>
        </div>
    </div>
</div>

<div class="mt-8">
    <p><?=sprintf("Total of %u strings. %u strings already translated", $total_items, $total_items-$cont_untranslated)?>. <span class="text-red-600"><?=sprintf("%u strings yet to translate",$cont_untranslated)?>.</span></p>
</div>

<div class="grid grid-cols-3 gap-4">
    <?= Form::open(Route::url('oc-panel',['controller'=>'translations','action'=>'edit','id'=>$edit_language]), ['method' => 'get']) ?>
        <div class="mt-8">
            <div class="flex rounded-md shadow-sm">
                <?= FORM::input('search', HTML::chars(core::request('search')), [
                    'placeholder' => __('Search'),
                    'class' => 'form-input block w-full rounded-none rounded-l-md transition ease-in-out duration-150 sm:text-sm sm:leading-5',
                ])?>
                <?= Form::hidden('translation_file', $translation_file) ?>
                <?= Form::button(NULL, __('Search'), ['type'=>'submit', 'class'=>'-ml-px relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm leading-5 font-medium rounded-r-md text-gray-700 bg-gray-50 hover:text-gray-500 hover:bg-white focus:outline-none focus:shadow-outline-blue focus:border-blue-300 active:bg-gray-100 active:text-gray-700 transition ease-in-out duration-150'])?>
            </div>
        </div>
    <?= Form::close() ?>

    <div class="col-span-2">
        <?= Form::open(Route::url('oc-panel',array('controller'=>'translations','action'=>'replace','id'=>$edit_language))) ?>
            <div class="mt-8">
                <div class="flex rounded-md shadow-sm">
                    <?= FORM::input('search', HTML::chars(core::request('search')), [
                        'placeholder' => __('Search'),
                        'class' => 'form-input block w-full rounded-none rounded-l-md transition ease-in-out duration-150 sm:text-sm sm:leading-5',
                    ])?>
                    <?= FORM::input('replace', HTML::chars(core::request('replace')), [
                        'placeholder' => __('Replace'),
                        'class' => 'form-input block w-full rounded-none border-l-0 transition ease-in-out duration-150 sm:text-sm sm:leading-5',
                    ])?>
                    <?= FORM::select('where', [
                            'original' => __('Replace Original'),
                            'translation' => __('Replace Translation'),
                        ], core::request('where'), [
                        'placeholder' => __('Replace'),
                        'class' => 'form-select block w-full rounded-none border-l-0 transition ease-in-out duration-150 sm:text-sm sm:leading-5',
                    ])?>
                    <?= Form::hidden('translation_file', $translation_file) ?>
                    <?= Form::button(NULL, __('Replace'), ['type'=>'submit', 'class'=>'-ml-px relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm leading-5 font-medium rounded-r-md text-gray-700 bg-gray-50 hover:text-gray-500 hover:bg-white focus:outline-none focus:shadow-outline-blue focus:border-blue-300 active:bg-gray-100 active:text-gray-700 transition ease-in-out duration-150'])?>
                </div>
            </div>
        <?= Form::close() ?>
    </div>
</div>

<?= Form::open(URL::current(), ['enctype' => 'multipart/form-data']) ?>
    <div class="bg-white overflow-hidden shadow rounded-lg mt-8">
        <div class="bg-white px-4 py-5 border-b border-gray-200 sm:px-6">
            <div class="-ml-4 -mt-2 flex items-center justify-between flex-wrap sm:flex-no-wrap">
                <div class="ml-4 mt-2"></div>
                <div class="ml-4 mt-2 flex-shrink-0">
                    <span class="inline-flex rounded-md shadow-sm">
                        <a  class="inline-flex items-center px-3 py-2 border border-gray-300 text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:text-gray-500 focus:outline-none focus:border-blue-300 focus:shadow-outline-blue active:text-gray-800 active:bg-gray-50 transition ease-in-out duration-150" href="<?=Request::current()->url()?>?translated=1&translation_file=<?=$translation_file?>">
                            <?=__('Hide translated texts')?>
                        </a>

                        <a  class="ml-3 inline-flex items-center px-3 py-2 border border-gray-300 text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:text-gray-500 focus:outline-none focus:border-blue-300 focus:shadow-outline-blue active:text-gray-800 active:bg-gray-50 transition ease-in-out duration-150" href="<?=Request::current()->url()?>?translation_file=<?=$translation_file?>">
                            <?=__('Show translated texts')?>
                        </a>

                        <?=FORM::button('submit', __('Save'), ['type'=>'submit', 'class'=>'ml-3 relative inline-flex items-center px-4 py-2 border border-transparent text-sm leading-5 font-medium rounded-md text-white bg-blue-600 hover:bg-blue-500 focus:outline-none focus:shadow-outline-blue focus:border-blue-700 active:bg-blue-700'])?>
                    </span>
                </div>
            </div>
        </div>
        <div class="bg-white overflow-hidden">
            <div class="flex flex-col">
                <div class="-my-2 py-2 overflow-x-auto sm:-mx-6 sm:px-6 lg:-mx-8 lg:px-8">
                    <div class="align-middle inline-block min-w-full overflow-hidden border-gray-200">
                        <table class="min-w-full">
                            <thead>
                                <tr>
                                    <th class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                                        #
                                    </th>
                                    <th class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                                        <?=__('Original Translation')?>
                                    </th>
                                    <th class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                                        <?=__('Translation')?> <?=$edit_language?>
                                    </th>
                                    <th class="px-6 py-3 border-b border-gray-200 bg-gray-50"></th>
                                </tr>
                            </thead>
                            <tbody class="bg-white">
                                <?foreach($translation_array as $key => $values):?>
                                    <? list($id, $original,$translated) = array_values($values) ?>
                                    <? $last_item = $key === count($translation_array) - 1 ?>
                                    <tr x-data="{ translated: '<?= $translated ?>', original: '<?= $original ?>' }" id="tr_<?=$id?>" class="<?=(strlen($translated)>0)? 'bg-green-50': 'bg-red-50'?>">
                                        <td class="px-6 py-4 whitespace-no-wrap <?= $last_item ? '' : 'border-b' ?> border-gray-200">
                                            <div class="text-sm leading-5 text-gray-900">
                                                <?= $id ?>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-no-wrap text-right <?= $last_item ? '' : 'border-b' ?> border-gray-200 text-sm leading-5 font-medium">
                                            <textarea id="orig_<?=$id?>" class="form-textarea mt-1 block w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5" disabled><?=$original?></textarea>
                                        </td>
                                        <td class="px-6 py-4 whitespace-no-wrap text-right <?= $last_item ? '' : 'border-b' ?> border-gray-200 text-sm leading-5 font-medium">
                                            <textarea x-model="translated" id="dest_<?=$id?>" class="form-textarea mt-1 block w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5" name="translations[<?=$id?>]"></textarea>
                                        </td>
                                        <td class="px-6 py-4 whitespace-no-wrap text-right <?= $last_item ? '' : 'border-b' ?> border-gray-200 text-sm leading-5 font-medium">
                                            <?=FORM::button('submit', __('Copy text'), [
                                                '@click' => 'translated = original',
                                                'type'=>'button',
                                                'class'=>'inline-flex items-center px-3 py-2 border border-gray-300 text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:text-gray-500 focus:outline-none focus:border-blue-300 focus:shadow-outline-blue active:text-gray-800 active:bg-gray-50 transition ease-in-out duration-150'
                                            ])?>
                                            <a href="http://translate.google.com/#en/<?=substr($edit_language,0,2)?>/<?=urlencode($original)?>" target="_blank" class="inline-flex items-center px-3 py-2 border border-gray-300 text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:text-gray-500 focus:outline-none focus:border-blue-300 focus:shadow-outline-blue active:text-gray-800 active:bg-gray-50 transition ease-in-out duration-150" href="<?=Request::current()->url()?>?translated=1&translation_file=<?=$translation_file?>">
                                                <?=__('Open Google Translator')?>
                                            </a>
                                        </td>
                                    </tr>
                                <? endforeach ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="border-t border-gray-200 px-4 py-4 sm:px-6 text-right">
            <?=Form::button('submit', __('Save'), ['type'=>'submit', 'class'=>'ml-3 relative inline-flex items-center px-4 py-2 border border-transparent text-sm leading-5 font-medium rounded-md text-white bg-blue-600 hover:bg-blue-500 focus:outline-none focus:shadow-outline-blue focus:border-blue-700 active:bg-blue-700'])?>
        </div>
    </div>
<? Form::close() ?>

<div>
    <?=$pagination?>
</div>
