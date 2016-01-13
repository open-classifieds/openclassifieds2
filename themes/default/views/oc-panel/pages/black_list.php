<?php defined('SYSPATH') or die('No direct script access.');?>

<div class="row">
    <div class="col-lg-12 page-title-container">
        <h1 class="page-header page-title"><?=__('Black list')?></h1>
        <span class="page-description"><?=__('This is a list of users marked as spammers. To understand how this feature works, please read this article')?> <a target="_blank" href="https://docs.yclas.com/activate-blacklist-works/"><?=__('Read more')?></a></span>
    </div>
</div>

<div class="panel panel-default">
    <div class="panel-body">
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
                  	<td><a href="<?=Route::url('oc-panel', array('controller'=>'pool','action'=>'remove','id'=>$user->id_user))?>" 
                  		   class="btn btn-info"><?=__('Remove')?></a></td>
                </tr>
                <?endforeach?>
            </tbody>
        </table>
    </div>
</div>