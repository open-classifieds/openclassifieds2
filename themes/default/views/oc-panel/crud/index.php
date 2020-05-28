<?php defined('SYSPATH') or die('No direct script access.');?>

	<?if ($controller->allowed_crud_action('create')):?>
	<a class="inline-block align-middle text-center select-none border font-normal whitespace-no-wrap py-2 px-4 rounded text-base leading-normal  text-blue-100 bg-blue-500 hover:bg-blue-400 pull-right ajax-load" href="<?=Route::url($route, array('controller'=> Request::current()->controller(), 'action'=>'create')) ?>" title="<?=__('New')?>">
	<i class="fa fa-plus-circle"></i>&nbsp; <?=__('New')?>
	</a>				
	<?endif?>

<h1 class="page-header page-title"><?=Text::ucfirst(__($name))?></h1>
<hr>
	<?if($name == 'role'):?><p><a href="https://docs.yclas.com/roles-work-classified-ads-script/" target="_blank"><?=__('Read more')?></a></p><?endif;?>


<?if($name == "user") :?>
	<form class="form-horizontal" role="form" method="get" action="<?=URL::current();?>">
		<div class="mb-4 has-feedback">
			<label class="" for="search"><?=__('Search')?></label>
			<div class="md:w-1/3 pr-4 pl-4 col-md-offset-8">
				<input type="text" class="block appearance-none w-full py-1 px-2 mb-1 text-base leading-normal bg-white text-grey-800 border border-gray-500 rounded search-query" name="search" placeholder="<?=__('Search users by name or email')?>" value="<?=HTML::chars(core::get('search'))?>">
				<span class="glyphicon glyphicon-search form-control-feedback"></span>
			</div>
		</div>
	</form>
<?endif?>

<div class="panel panel-default">
	<div class="panel-body">
		<div class="block w-full overflow-auto scrolling-touch">
			<table class="w-full max-w-full mb-4 bg-transparent table-striped table-bordered">
				<thead>
					<tr>
						<?foreach($fields as $field):?>
							<th><?=Text::ucfirst((method_exists($orm = ORM::Factory($name), 'formo') ? Arr::path($orm->formo(), $field.'.label', __($field)) : __($field)))?></th>
						<?endforeach?>
						<?if ($controller->allowed_crud_action('delete') OR $controller->allowed_crud_action('update')):?>
						<th><?=__('Actions') ?></th>
						<?endif?>
					</tr>
				</thead>
				<tbody>
					<?foreach($elements as $element):?>
						<tr id="tr<?=$element->pk()?>">
							<?foreach($fields as $field):?>
								<td><?=HTML::chars($element->$field)?></td>
							<?endforeach?>
							<?if ($controller->allowed_crud_action('delete') OR $controller->allowed_crud_action('update')):?>
							<td width="80" style="width:80px;">
								<?if ($controller->allowed_crud_action('update')):?>
								<a title="<?=__('Edit')?>" class="inline-block align-middle text-center select-none border font-normal whitespace-no-wrap py-2 px-4 rounded text-base leading-normal  text-blue-100 bg-blue-500 hover:bg-blue-400 ajax-load" href="<?=Route::url($route, array('controller'=> Request::current()->controller(), 'action'=>'update','id'=>$element->pk()))?>">
									<i class="glyphicon   glyphicon-edit"></i>
								</a>
								<?endif?>
								<?if ($controller->allowed_crud_action('delete')):?>
								<a 
									href="<?=Route::url($route, array('controller'=> Request::current()->controller(), 'action'=>'delete','id'=>$element->pk()))?>" 
									class="inline-block align-middle text-center select-none border font-normal whitespace-no-wrap py-2 px-4 rounded text-base leading-normal  text-red-100 bg-red-500 hover:bg-red-400 index-delete" 
									title="<?=__('Are you sure you want to delete?')?>" 
									data-id="tr<?=$element->pk()?>" 
									data-btnOkLabel="<?=__('Yes, definitely!')?>" 
									data-btnCancelLabel="<?=__('No way!')?>">
									<i class="glyphicon glyphicon-trash"></i>
								</a>
								<?endif?>
							</td>
							<?endif?>
						</tr>
					<?endforeach?>
				</tbody>
			</table>
		</div>
	</div>
</div>

<div class="text-center"><?=$pagination?></div>


<?if ($controller->allowed_crud_action('export')):?>
<a class="inline-block align-middle text-center select-none border font-normal whitespace-no-wrap py-2 px-4 rounded text-base leading-normal  py-1 px-2 text-sm leading-tight text-green-100 bg-green-500 hover:bg-green-400 pull-right " href="<?=Route::url($route, array('controller'=> Request::current()->controller(), 'action'=>'export')) ?>" title="<?=__('Export')?>">
    <i class="glyphicon glyphicon-download"></i>
    <?=__('Export all')?>
</a>                
<?endif?>