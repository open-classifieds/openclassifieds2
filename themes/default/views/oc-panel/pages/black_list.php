<?php defined('SYSPATH') or die('No direct script access.');?>

<h1 class="page-header page-title">
    <?=__('Black list')?>
    <a target="_blank" href="https://docs.yclas.com/activate-blacklist-works/">
        <i class="fa fa-question-circle"></i>
    </a>
</h1>

<hr>

<p>
    <?=__('This is a list of users marked as spammers. To understand how this feature works, please read this article')?>
</p>

<div class="panel panel-default">
    <table class="table table-striped">
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
                  		    class="btn btn-danger"
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
