<li x-data="{ open: false }" data-id="<?=$key?>" id="<?=$key?>">
    <a @click.prevent="open = true" href="#" class="block hover:bg-gray-50 focus:outline-none focus:bg-gray-50 transition duration-150 ease-in-out">
        <div class="flex items-center px-4 py-4 sm:px-6">
            <div class="min-w-0 flex-1 flex items-center">
                <div class="flex-shrink-0">
                    <svg class="h-4 w-4 text-gray-400 cursor-move" fill="currentColor" viewBox="0 0 32 32">
                        <path d="M9.125 27.438h4.563v4.563H9.125zm9.188 0h4.563v4.563h-4.563zm-9.188-9.125h4.563v4.563H9.125zm9.188 0h4.563v4.563h-4.563zM9.125 9.125h4.563v4.563H9.125zm9.188 0h4.563v4.563h-4.563zM9.125 0h4.563v4.563H9.125zm9.188 0h4.563v4.563h-4.563z"></path>
                    </svg>
                </div>
                <div class="min-w-0 flex-1 px-4 md:grid md:grid-cols-2 md:gap-4 items-center">
                    <div>
                        <div class="text-sm leading-5 text-gray-900 truncate">
                            <?if($data['icon']!=''):?><i class="<?=$data['icon']?>"></i><?endif?>
                            <?=$data['title']?>
                            <?=$data['url']?> (<?=$data['target']?>)
                        </div>
                    </div>
                </div>
            </div>
            <div>
                <svg class="h-5 w-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
                </svg>
            </div>
        </div>
    </a>

    <div x-show="open" class="fixed bottom-0 inset-x-0 px-4 pb-4 sm:inset-0 sm:flex sm:items-center sm:justify-center">
        <div x-show="open" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 transition-opacity">
            <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
        </div>
        <div x-show="open" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" class="bg-white rounded-lg overflow-hidden shadow-xl transform transition-all sm:max-w-lg sm:w-full">
            <form method="post" action="<?=Route::url('oc-panel',array('controller'=>'menu','action'=>'update','id'=>$key))?>">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left space-y-6">
                        <h3 class="text-lg leading-6 font-medium text-gray-900">
                            <?=__('Edit Menu')?> <?=$data['title']?>
                        </h3>
                        <div>
                            <?= Form::label('title', __('Title'), ['class' => 'block text-sm font-medium leading-5 text-gray-700']) ?>
                            <?= FORM::input('title', Core::post('title', $data['title']), [
                                'class' => 'mt-1 rounded-md shadow-sm form-input block w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5',
                            ])?>
                        </div>
                        <div>
                            <?= Form::label('url', __('URL'), ['class' => 'block text-sm font-medium leading-5 text-gray-700']) ?>
                            <?= FORM::input('url', Core::post('url', $data['url']), [
                                'placeholder' => Core::config('general.base_url'),
                                'class' => 'mt-1 rounded-md shadow-sm form-input block w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5',
                            ])?>
                        </div>
                        <div>
                            <?= Form::label('target', __('Target'), ['class' => 'block text-sm font-medium leading-5 text-gray-700']) ?>
                            <? $targets = [
                                '_self' => '_self',
                                '_blank' => '_blank',
                                '_parent' => '_parent',
                                '_top' => '_top',
                            ] ?>
                            <?= Form::select('target', $targets, $data['target'], [
                                'class' => 'mt-1 rounded-md shadow-sm form-select block w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5',
                            ])?>
                        </div>
                        <div>
                            <?= Form::label('icon', __('Icon'), ['class' => 'block text-sm font-medium leading-5 text-gray-700']) ?>
                            <?= FORM::input('icon', $data['icon'], [
                                'x-data' => '',
                                'x-init' => 'console.log($refs.input); $($refs.input).iconpicker();',
                                'x-ref' => 'input',
                                'class' => 'icon-picker mt-1 rounded-md shadow-sm form-input block w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5',
                            ])?>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex">
                    <div>
                        <span class="flex w-full rounded-md shadow-sm sm:ml-3 sm:w-auto">
                            <a href="<?=Route::url('oc-panel', array('controller'=> 'menu', 'action'=>'delete','id'=>$key))?>"
                                class="inline-flex justify-center w-full rounded-md border border-transparent px-4 py-2 bg-red-600 text-base leading-6 font-medium text-white shadow-sm hover:bg-red-500 focus:outline-none focus:border-red-700 focus:shadow-outline-red transition ease-in-out duration-150 sm:text-sm sm:leading-5",
                            >
                                <?= __('Delete') ?>
                            </a>
                        </span>
                    </div>
                    <div class="sm:flex sm:flex-1 sm:flex-row-reverse">
                        <span class="flex w-full rounded-md shadow-sm sm:ml-3 sm:w-auto">
                            <?= Form::button('cancel', __('Save'), [
                                'class' => 'inline-flex justify-center w-full rounded-md border border-transparent px-4 py-2 bg-blue-600 text-base leading-6 font-medium text-white shadow-sm hover:bg-blue-500 focus:outline-none focus:border-blue-700 focus:shadow-outline-blue transition ease-in-out duration-150 sm:text-sm sm:leading-5',
                                'type' => 'submit',
                                '@click' => "open = false",
                            ]) ?>
                        </span>
                        <span class="mt-3 flex w-full rounded-md shadow-sm sm:mt-0 sm:w-auto">
                            <?= Form::button('cancel', __('Cancel'), [
                                'class' => 'inline-flex justify-center w-full rounded-md border border-gray-300 px-4 py-2 bg-white text-base leading-6 font-medium text-gray-700 shadow-sm hover:text-gray-500 focus:outline-none focus:border-blue-300 focus:shadow-outline transition ease-in-out duration-150 sm:text-sm sm:leading-5',
                                'type' => 'button',
                                '@click' => "open = false",
                            ]) ?>
                        </span>
                    </div>
                </div>
            </form>
        </div>
    </div>
</li>
