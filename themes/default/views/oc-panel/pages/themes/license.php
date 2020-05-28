<?php defined('SYSPATH') or die('No direct script access.');?>

<?=Form::errors()?>
<h1 class="page-header page-title"><?=__('Pro License')?></h1>
<hr>
    <p><?=__('Please insert here your Pro License.')?></p>

<div class="flex flex-wrap">
    <div class="md:w-full pr-4 pl-4">
        <form action="<?=URL::base()?><?=Request::current()->uri()?>" method="post"> 
            <div class="panel panel-default">
                <div class="panel-body">
                    <div class="mb-4">
                        <label class="control-label"><?=__('License')?></label>
                        <input class="block appearance-none w-full py-1 px-2 mb-1 text-base leading-normal bg-white text-grey-800 border border-gray-500 rounded" type="text" name="license" value="" placeholder="<?=__('License')?>">
                    </div>
                    <button 
                        type="button" 
                        class="inline-block align-middle text-center select-none border font-normal whitespace-no-wrap py-2 px-4 rounded text-base leading-normal  text-blue-100 bg-blue-500 hover:bg-blue-400 submit" 
                        title="<?=__('Are you sure?')?>" 
                        data-text="<?=sprintf(__('License will be activated in %s domain. Once activated, your license cannot be changed to another domain.'), parse_url(URL::base(), PHP_URL_HOST))?>"
                        data-btnOkLabel="<?=__('Yes, definitely!')?>" 
                        data-btnCancelLabel="<?=__('No way!')?>">
                        <?=__('Check')?>
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
