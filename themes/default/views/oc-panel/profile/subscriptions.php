<?php defined('SYSPATH') or die('No direct script access.');?>
<?=Alert::show()?>
<?=Form::errors()?>

<table class="table table-bordered">
	<tr>
		
		<th><?=__('Category')?></th>
		<th><?=__('Location')?></th>
		<th><?=__('Min Price')?></th>
		<th><?=__('Max Price')?></th>
		<th><?=__('Created')?></th>
		<th>
			<a class=" btn btn-danger  " 
				href="<?=Route::url('default', array('controller'=>'subscribe','action'=>'unsubscribe', 'id'=>Auth::instance()->get_user()->id_user))?>" 
				onclick="return confirm('<?=__('Unsubscribe to all?')?>');"
				rel="tooltip" title="<?=__('Unsubscribe to all')?>">
				<i class="glyphicon   glyphicon-remove"></i>
			</a>
		</th>
	</tr>
	<?foreach($list as $l):?>
	<tbody>
		<tr>
			<td>
			<!-- category -->
				<p><?=$l['category']?></p>
			</td>
			
			<td>
				<!-- locations -->
				<p><?=$l['location']?></p>
			</td>
	    	
	    	<td>
	    		<!-- Min price -->
	    		<p><?=$l['min_price']?></p>
	    	</td>
			<td>
				<!-- Max Price -->
				<p><?=$l['max_price']?></p>
			</td>
			<td>
				<!-- Created -->
				<p><?=substr($l['created'], 0, 11)?></p>
			</td>
			<td>
				<!-- unsubscribe one entry button -->
				<a class="btn btn-warning" 
					href="<?=Route::url('oc-panel', array('controller'=>'profile','action'=>'unsubscribe','id'=>$l['id']))?>" 
					onclick="return confirm('<?=__('Unsubscribe?')?>');"
					rel="tooltip" title="<?=__('Unsubscribe')?>">
					<i class="glyphicon   glyphicon-remove"></i>
				</a>
			</td>
		</tr>
	<?endforeach?>
	</tbody>
</table>
	 