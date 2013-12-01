<?php defined('SYSPATH') or die('No direct script access.');?>
<?=Alert::show()?>
<div class="page-header">
	<h1><?=__('My Advertisements')?></h1>
</div>

<a class="btn btn-primary" href="<?=Route::url('post_new')?>" rel="tooltip" title="<?=__('New Advertisement')?>">
	<i class="glyphicon glyphicon-pencil"></i><?=__(' New')?>
</a>
<table class="table table-bordered">
	<tr>
		
		<th><?=__('Name')?></th>
		<th><?=__('Category')?></th>
		<th><?=__('Location')?></th>
		<th><?=__('Status')?></th>
		<th><?=__('Date')?></th>
		<th></th>
	</tr>
	<? $i = 0; foreach($ads as $ad):?>
	<tbody>
		<tr>
			
			<? foreach($category as $cat){ if ($cat->id_category == $ad->id_category) $cat_name = $cat->seoname; }?>
			<td><a href="<?=Route::url('ad', array('controller'=>'ad','category'=>$cat_name,'seotitle'=>$ad->seotitle))?>"><?= $ad->title; ?></a>
			</td>

			<? foreach($category as $cat):?>
				<? if ($cat->id_category == $ad->id_category): ?>
					<td><?= $cat->name ?>
				<?endif?>
	    	<?endforeach?>

	    	<?$locat_name = NULL;?>
            <?foreach($location as $loc):?>
                <? if ($loc->id_location == $ad->id_location): 
                    $locat_name=$loc->name;?>
                    <td><?=$locat_name?></td>
                <?endif?>
            <?endforeach?>
            <?if($locat_name == NULL):?>
                <td>n/a</td>
            <?endif?>
		
			
			<? if($ad->status == Model_Ad::STATUS_NOPUBLISHED):?>
				<td><?=__('Not published')?></td>
			<? elseif($ad->status == Model_Ad::STATUS_PUBLISHED):?>
				<td><?=__('Published')?></td>
			<? elseif($ad->status == Model_Ad::STATUS_SPAM):?>
				<td><?=__('Spam')?></td>
	    	<? elseif($ad->status == Model_Ad::STATUS_UNAVAILABLE):?>
				<td><?=__('Unavailable')?></td>
			<? elseif($ad->status == Model_Ad::STATUS_UNCONFIRMED):?>
				<td><?=__('Unconfirmed')?></td>
			<?endif?>
	    	
	    	<td><?= substr($ad->created, 0, 11)?></td>
			<td>
				<a class="btn btn-primary" 
					href="<?=Route::url('oc-panel', array('controller'=>'profile','action'=>'update','id'=>$ad->id_ad))?>" 
					rel="tooltip" title="<?=__('Update')?>">
					<i class="glyphicon glyphicon-edit"></i>
				</a>
				<? if(($user->id_role == 10) AND $ad->status == Model_Ad::STATUS_UNAVAILABLE):?>
				<a class="btn btn-success" 
					href="<?=Route::url('oc-panel', array('controller'=>'profile','action'=>'activate','id'=>$ad->id_ad))?>" 
					onclick="return confirm('<?=__('Activate?')?>');"
					rel="tooltip" title="<?=__('Activate')?>">
					<i class="glyphicon glyphicon-ok glyphicon"></i>
				</a>
				<?elseif($ad->status != Model_Ad::STATUS_UNAVAILABLE):?>
				<a class="btn btn-warning" 
					href="<?=Route::url('oc-panel', array('controller'=>'profile','action'=>'deactivate','id'=>$ad->id_ad))?>" 
					onclick="return confirm('<?=__('Deactivate?')?>');"
					rel="tooltip" title="<?=__('Deactivate')?>">
					<i class="glyphicon glyphicon-remove"></i>
				</a>
				<?endif?>
				<?if(($user->id_role == 10) OR (core::config('payment.to_top'))):?>
					<a class="btn btn-info" 
						href="<?=Route::url('default', array('controller'=>'ad','action'=>'to_top','id'=>$ad->id_ad))?>" 
						onclick="return confirm('<?=__('Refresh listing, go to top?')?>');"
					    rel="tooltip" title="<?=__('Go to top')?>" data-id="tr1" data-text="<?=__('Are you sure you want to refresh listing and go to top?')?>">
						<i class="glyphicon glyphicon-circle-arrow-up"></i>
					</a>
				<?endif?>
				<?if(($user->id_role == 10) OR (core::config('payment.to_featured'))):?>
					<?if($ad->featured == NULL):?>
					<a class="btn btn-default" 
						href="<?=Route::url('default', array('controller'=>'ad','action'=>'to_featured','id'=>$ad->id_ad))?>" 
						onclick="return confirm('<?=__('Make featured?')?>');"
					    rel="tooltip" title="<?=__('Featured')?>" data-id="tr1" data-text="<?=__('Are you sure you want to make it featured?')?>">
						<i class="glyphicon glyphicon-bookmark "></i>
					</a>
					<?else:?>
					<a class="btn btn-info" 
						href="<?=Route::url('oc-panel', array('controller'=>'ad','action'=>'featured','id'=>$ad->id_ad))?>" 
						onclick="return confirm('<?=__('Deactivate featured?')?>');"
					    rel="tooltip" title="<?=__('Deactivate featured')?>" data-id="tr1" data-text="<?=__('Are you sure you want to deactivate featured advertisement?')?>">
						<i class="glyphicon glyphicon-bookmark"></i>
					</a>
					<?endif?>
				<?endif?>
			</td>
		</tr>
	<?endforeach?>
	</tbody>
</table>
	 <?=$pagination?>
