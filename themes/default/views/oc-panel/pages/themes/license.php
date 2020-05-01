<?php defined('SYSPATH') or die('No direct script access.');?>

<?=Form::errors()?>
<h1 class="page-header page-title"><?=__('Pro License')?></h1>
<hr>
    <p><?=__('Please insert here your Pro License.')?></p>

<div class="row">
    <div class="col-md-12">
        <form action="<?=URL::base()?><?=Request::current()->uri()?>" method="post"> 
            <div class="panel panel-default">
                <div class="panel-body">
                    <div class="form-group">
                        <label class="control-label"><?=__('License')?></label>
                        <input class="form-control" type="text" name="license" value="" placeholder="<?=__('License')?>">
                    </div>
                    <button 
                        type="button" 
                        class="btn btn-primary submit" 
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
