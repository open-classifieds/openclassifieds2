<?php defined('SYSPATH') or die('No direct script access.');?>

<h1 class="page-header page-title">
    <?=__('Black list')?>
    <a target="_blank" href="https://docs.yclas.com/activate-blacklist-works/">
        <i class="fa fa-question-circle"></i>
    </a>
</h1>

<hr>

<p>
    <?=__('This is a list of users marked as spammers.')?>
</p>

<div class="panel panel-default">
    <table class="w-full max-w-full mb-4 bg-transparent table-striped">
        <thead>
            <tr>
                <th><?=__('Name')?></th>
              	<th><?=__('Email')?></th>
              	<th><?=__('Action')?></th>
            </tr>
        </thead>
    	<tbody>
        	<?foreach ($black_list as $user):?>
                <tr>
                  	<td><?=$user->name?></td>
                  	<td><?=$user->email?></td>
                  	<td class="nowrap">
                        <a href="<?=Route::url('oc-panel', array('controller'=>'pool','action'=>'remove','id'=>$user->id_user))?>" 
                  		    class="inline-block align-middle text-center select-none border font-normal whitespace-no-wrap py-2 px-4 rounded text-base leading-normal  text-red-100 bg-red-500 hover:bg-red-400"
                            title="<?=__('Are you sure you want to remove?')?>" 
                            data-toggle="confirmation" 
                            data-btnOkLabel="<?=__('Yes, definitely!')?>" 
                            data-btnCancelLabel="<?=__('No way!')?>">
                            <i class="fa fa-trash"></i>
                        </a>
                    </td>
                </tr>
            <?endforeach?>
        </tbody>
    </table>
</div>