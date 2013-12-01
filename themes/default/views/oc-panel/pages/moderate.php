<?php defined('SYSPATH') or die('No direct script access.');?>

<?=Alert::show()?>
<div class="page-header">
	<h1><?=__('Moderation')?></h1>
</div>
<? $current_url = Model_Ad::STATUS_NOPUBLISHED?>
<table class="table table-bordered">
	<tr>
		<th>
			<label class="checkbox">
					<input type="checkbox" id="select-all" onclick="check_all();">
			</label>
		</th>
		<th><?=__('Name')?></th>
		<th><?=__('Category')?></th>
		<th><?=__('Location')?></th>
		<th><?=__('Hits')?></th>
		<th><?=__('Status')?></th>
		<th><?=__('Date')?></th>
		<!-- in case there are no ads we dont show buttons -->
		<?if(isset($ads)):?>
		<th>
			<a class="spam btn btn-warning" 
				href="<?=Route::url('oc-panel', array('controller'=>'ad','action'=>'spam'))?>" 
				onclick="return confirm('<?=__('Spam?')?>');"
				rel="tooltip" title="<?=__('Spam')?>">
				<i class="glyphicon   glyphicon-fire"></i>
			</a>
			<a class="deactivate btn btn-warning" 
				href="<?=Route::url('oc-panel', array('controller'=>'ad','action'=>'deactivate'))?>" 
				onclick="return confirm('<?=__('Deactivate?')?>'));"
				rel="tooltip" title="<?=__('Deactivate')?>">
				<i class="glyphicon   glyphicon-remove"></i>
			</a>
			<a class="activate btn btn-success" 
					href="<?=Route::url('oc-panel', array('controller'=>'ad','action'=>'activate'))?>" 
					onclick="return confirm('<?=__('Activate?')?>');"
					rel="tooltip" title="<?=__('Activate')?>">
					<i class="glyphicon   glyphicon-ok-sign"></i>
			</a>
			<a class="delete btn btn-danger " 
				href="<?=Route::url('oc-panel', array('controller'=>'ad','action'=>'delete'))?>"
				onclick="return confirm('<?=__('Delete?')?>');"
			    rel="tooltip" title="<?=__('Delete')?>" data-id="tr1" data-text="<?=__('Are you sure you want to delete?')?>">
				<i class="glyphicon   glyphicon-remove"></i>
			</a>
		</th>
		<?endif?>
	</tr>
<? if($ads != NULL):?>
	<? $i = 0; foreach($ads as $ad):?>	
	<tbody>
		<tr>
			<td>
				<label class="checkbox">
					<input type="checkbox" id="<?= $ad->id_ad.'_'?>" class="checkbox">
				</label>
			</td>
			<?foreach($category[0] as $cat => $c){ if ($c['id'] == $ad->id_category) $cat_name = $c['seoname']; }?>
			<td><a href="<?=Route::url('ad', array('controller'=>'ad','category'=>$cat_name,'seotitle'=>$ad->seotitle))?>"><?= wordwrap($ad->title, 15, "<br />\n"); ?></a>
			</td>

			<? foreach($category[0] as $cat => $c ):?>
				<? if ($c['id'] == $ad->id_category): ?>
					<td><?= wordwrap($c['name'], 15, "<br />\n"); ?>
				<?endif?>
	    	<?endforeach?>
			
            <?$locat_name = NULL;?>
			<?foreach($location[0] as $loc => $l):?>
				<? if ($l['id'] == $ad->id_location): 
                    $locat_name=$l['name'];?>
					<td><?=wordwrap($locat_name, 15, "<br />\n");?></td>
				<?endif?>
	    	<?endforeach?>
            <?if($locat_name == NULL):?>
                <td>n/a</td>
            <?endif?>

			<td><?= $hits[$i++];?></td>
			<?if($ad->status == Model_Ad::STATUS_NOPUBLISHED):?>
				<td><?=__('Not published')?></td>
			<? elseif($ad->status == Model_Ad::STATUS_PUBLISHED):?>
				<td><?=__('Published')?></td>
			<? elseif($ad->status == Model_Ad::STATUS_SPAM):?>
				<td><?=__('Spam')?></td>
	    	<? elseif($ad->status == Model_Ad::STATUS_UNAVAILABLE):?>
				<td><?=__('Unavailable')?></td>
			<?endif?>
	    	<td><?= substr($ad->created, 0, 11)?></td>
			<td>
				<a class="btn btn-primary" 
					href="<?=Route::url('oc-panel', array('controller'=>'profile','action'=>'update','id'=>$ad->id_ad))?>" 
					rel="tooltip" title="<?=__('Update')?>">
					<i class="glyphicon   glyphicon-edit"></i>
				</a>
				<!-- sel_url_to_redirect is important because is quick selector or $current_url. 
					This works with dynamic check boxes, where we select href to build new url -->
				<a class="btn btn-warning sel_url_to_redirect" 
					href="<?=Route::url('oc-panel', array('controller'=>'ad','action'=>'deactivate','id'=>$ad->id_ad, 'current_url'=>$current_url))?>" 
					onclick="return confirm('<?=__('Deactivate?')?>');"
					rel="tooltip" title="<?=__('Deactivate')?>">
					<i class="glyphicon   glyphicon-remove"></i>
				</a>
                <a class=" btn btn-warning" 
                    href="<?=Route::url('oc-panel', array('controller'=>'ad','action'=>'spam','id'=>$ad->id_ad, 'current_url'=>$current_url))?>" 
                    onclick="return confirm('<?=__('Spam?')?>');"
                    rel="tooltip" title="<?=__('Spam')?>">
                    <i class="glyphicon   glyphicon-fire"></i>
                </a>
				<a class="btn btn-success" 
					href="<?=Route::url('oc-panel', array('controller'=>'ad','action'=>'activate','id'=>$ad->id_ad, 'current_url'=>$current_url))?>" 
					onclick="return confirm('<?=__('Activate?')?>');"
					rel="tooltip" title="<?=__('Activate')?>">
					<i class="glyphicon   glyphicon-ok-sign"></i>
				</a>
				<a class="btn btn-danger " 
					href="<?=Route::url('oc-panel', array('controller'=>'ad','action'=>'delete','id'=>$ad->id_ad, 'current_url'=>$current_url))?>" 
					onclick="return confirm('<?=__('Delete?')?>');"
				    rel="tooltip" title="<?=__('Delete')?>" data-id="tr1" data-text="<?=__('Are you sure you want to delete?')?>">
					<i class="glyphicon   glyphicon-remove"></i>
				</a>
			</td>
		</tr>
	<?endforeach?>
	<?endif?>
	</tbody>
</table>
<?if(isset($pagination)):?>
<?=$pagination?>
<?endif?>
	

