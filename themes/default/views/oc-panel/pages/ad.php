<?php defined('SYSPATH') or die('No direct script access.');?>

<?=Alert::show()?>
<div class="page-header">
	<?if(Request::current()->query('define') == Model_Ad::STATUS_UNAVAILABLE):?>
		<? $current_url = Model_Ad::STATUS_UNAVAILABLE?>
		<h1><?=__('Unavailable')?></h1>
	<?elseif (Request::current()->query('define') == Model_Ad::STATUS_UNCONFIRMED):?>
		<? $current_url = Model_Ad::STATUS_UNCONFIRMED?>
		<h1><?=__('Unconfirmed')?></h1>
	<?elseif (Request::current()->query('define') == Model_Ad::STATUS_SPAM):?>
		<? $current_url = Model_Ad::STATUS_SPAM?>
		<h1><?=__('Spam')?></h1>
	<?else:?>
		<? $current_url = Model_Ad::STATUS_PUBLISHED?>
		<h1><?=__('Advertisements')?></h1>
		<a target='_blank' href='http://open-classifieds.com/2013/08/29/how-to-manage-advertisements/'><?=__('Read more')?></a>
		<form id="advert_search" method="GET" class="pull-right" enctype="multipart/form-data">
		  	<input type="text" name="search" id="search" class="form-control" placeholder="<?=__('Search')?>">
		</form>
	<?endif?>
</div>

<a class="btn btn-warning" type="submit" value="spam" href="<?=Route::url('oc-panel', array('directory'=>'panel', 'controller'=>'ad', 'action'=>'index')).'?define='.Model_Ad::STATUS_SPAM?>" rel="tooltip" title="<?=__('Spam Sort')?>">
	<i class="glyphicon   glyphicon-fire"></i><?=__('Spam')?>
</a>
<a class="btn btn-default" type="submit" value="unavailable" href="<?=Route::url('oc-panel', array('directory'=>'panel', 'controller'=>'ad', 'action'=>'index')).'?define='.Model_Ad::STATUS_UNAVAILABLE?>" rel="tooltip" title="<?=__('Unavailable Sort')?>">
	<i class=" glyphicon  glyphicon-exclamation-sign glyphicon"></i><?=__(' Unavailable')?>
</a>
<a class="btn btn-info" type="submit" value="unconfirmed" href="<?=Route::url('oc-panel', array('directory'=>'panel', 'controller'=>'ad', 'action'=>'index')).'?define='.Model_Ad::STATUS_UNCONFIRMED?>" rel="tooltip" title="<?=__('Unconfirmed Sort')?>">
	<i class="glyphicon glyphicon-plane glyphicon"></i><?=__(' Unconfirmed')?>
</a>

<?if(Request::current()->query('define') == Model_Ad::STATUS_UNAVAILABLE):?>
<a class="btn btn-danger pull-right" type="submit" value="unavailable" onclick="return confirm('<?=__('Delete All Unavailable?')?>');" href="<?=Route::url('oc-panel', array('controller'=>'ad','action'=>'delete_all')).'?define='.Model_Ad::STATUS_UNAVAILABLE?>" rel="tooltip" title="<?=__('Delete All Unavailable')?>">
	<?=__('Delete All')?>
</a>
<?elseif (Request::current()->query('define') == Model_Ad::STATUS_UNCONFIRMED):?>
<a class="btn btn-danger pull-right" type="submit" value="unconfirmed" onclick="return confirm('<?=__('Delete All Unconfirmed?')?>');" href="<?=Route::url('oc-panel', array('controller'=>'ad','action'=>'delete_all')).'?define='.Model_Ad::STATUS_UNCONFIRMED?>" rel="tooltip" title="<?=__('Delete All Unconfirmed')?>">
	<?=__('Delete All')?>
</a>
<?elseif (Request::current()->query('define') == Model_Ad::STATUS_SPAM):?>
<a class="btn btn-danger pull-right" type="submit" value="spam" onclick="return confirm('<?=__('Delete All Spam?')?>');" href="<?=Route::url('oc-panel', array('controller'=>'ad','action'=>'delete_all')).'?define='.Model_Ad::STATUS_SPAM?>" rel="tooltip" title="<?=__('Delete All Spam')?>">
	<?=__('Delete All')?>
</a>
<?endif?>


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
		<?if(isset($res)):?>
		<th>
			<?if(Request::current()->query('define') != Model_Ad::STATUS_SPAM):?>
			<a class="spam btn btn-warning  " 
				href="<?=Route::url('oc-panel', array('controller'=>'ad','action'=>'spam'))?>" 
				onclick="return confirm('<?=__('Spam?')?>');"
				rel="tooltip" title="<?=__('Spam')?>">
				<i class="glyphicon   glyphicon-fire"></i>
			</a>
			<?endif?>
			<?if(Request::current()->query('define') != Model_Ad::STATUS_UNAVAILABLE):?>
			<a class="deactivate btn btn-warning " 
				href="<?=Route::url('oc-panel', array('controller'=>'ad','action'=>'deactivate'))?>" 
				onclick="return confirm('<?=__('Deactivate?')?>');"
				rel="tooltip" title="<?=__('Deactivate')?>">
				<i class="glyphicon   glyphicon-remove"></i>
			</a>
			<?endif?>
			<?if($current_url != Model_Ad::STATUS_PUBLISHED):?>
			<a class="activate btn btn-success " 
					href="<?=Route::url('oc-panel', array('controller'=>'ad','action'=>'activate'))?>" 
					onclick="return confirm('<?=__('Activate?')?>');"
					rel="tooltip" title="<?=__('Activate')?>">
					<i class="glyphicon   glyphicon-ok-sign"></i>
			</a>
			<?endif?>
			<a class="delete btn btn-danger  " 
				href="<?=Route::url('oc-panel', array('controller'=>'ad','action'=>'delete'))?>"
				onclick="return confirm('<?=__('Delete?')?>');"
			    rel="tooltip" title="<?=__('Delete')?>" data-id="tr1" data-text="<?=__('Are you sure you want to delete?')?>">
				<i class="glyphicon   glyphicon-remove"></i>
			</a>
			<?if($current_url == Model_Ad::STATUS_PUBLISHED):?>
			<a class="featured btn btn-primary " 
				href="<?=Route::url('oc-panel', array('controller'=>'ad','action'=>'featured', 'current_url'=>$current_url))?>"
				onclick="return confirm('<?=__('Are you sure you want to make it featured?')?>');"
			    rel="tooltip" title="<?=__('Featured')?>" data-id="tr1" data-text="<?=__('Are you sure you want to make it featured?')?>">
				<i class="glyphicon   glyphicon-bookmark"></i>
			</a>
			<a class="to_top btn btn-info" 
				href="<?=Route::url('oc-panel', array('controller'=>'ad','action'=>'to_top', 'current_url'=>$current_url))?>"
				onclick="return confirm('<?=__('Are you sure you want to make it to_top?')?>');"
			    rel="tooltip" title="<?=__('to_top')?>" data-id="tr1" data-text="<?=__('Are you sure you want to make it to top?')?>">
				<i class="glyphicon   glyphicon-circle-arrow-up"></i>
			</a>
			<?endif?>

		</th>
		<?endif?>
	</tr>
<?if(isset($res)):?>
	<? $i = 0; foreach($res as $ad):?>
	<tbody>
		<tr>
			<td>
				<label class="checkbox">
					<input type="checkbox" id="<?= $ad->id_ad.'_'?>" class="checkbox">
				</label>
			</td>
			
			<?foreach($category[0] as $cat => $c){ if ($c['id'] == $ad->id_category) $cat_name = $c['seoname']; }?>
			<td><a href="<?=Route::url('ad', array('controller'=>'ad','category'=>$cat_name,'seotitle'=>$ad->seotitle))?>"><?=wordwrap($ad->title, 15, "<br />\n"); ?></a>
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
	    	
	    	<td><?= substr($ad->published, 0, 11)?></td>
			<td>
				<a class="btn btn-primary " 
					href="<?=Route::url('oc-panel', array('controller'=>'profile','action'=>'update','id'=>$ad->id_ad))?>" 
					rel="tooltip" title="<?=__('Update')?>">
					<i class="glyphicon   glyphicon-edit"></i>
				</a>
				<?if($ad->status != Model_Ad::STATUS_SPAM):?>
				
				<a class="btn btn-warning " 
					href="<?=Route::url('oc-panel', array('controller'=>'ad','action'=>'spam','id'=>$ad->id_ad, 'current_url'=>$current_url))?>" 
					onclick="return confirm('<?=__('Spam?')?>');"
					rel="tooltip" title="<?=__('Spam')?>">
					<i class="glyphicon   glyphicon-fire"></i>
				</a>
				<?endif?>
				<?if($ad->status != Model_Ad::STATUS_UNAVAILABLE):?>
				<a class="btn btn-warning " 
					href="<?=Route::url('oc-panel', array('controller'=>'ad','action'=>'deactivate','id'=>$ad->id_ad, 'current_url'=>$current_url))?>" 
					onclick="return confirm('<?=__('Deactivate?')?>');"
					rel="tooltip" title="<?=__('Deactivate')?>">
					<i class="glyphicon   glyphicon-remove"></i>
				</a>
				<?endif?>
				<?if( $ad->status != Model_Ad::STATUS_PUBLISHED ):?>
				<a class="btn btn-success " 
					href="<?=Route::url('oc-panel', array('controller'=>'ad','action'=>'activate','id'=>$ad->id_ad, 'current_url'=>$current_url))?>" 
					onclick="return confirm('<?=__('Activate?')?>');"
					rel="tooltip" title="<?=__('Activate')?>">
					<i class="glyphicon   glyphicon-ok-sign"></i>
				</a>
				<?endif?>
				<!-- sel_url_to_redirect is important because is quick selector. This works with dynamic check boxes, where we select href to build new url -->
				<a class="btn btn-danger  sel_url_to_redirect" 
					href="<?=Route::url('oc-panel', array('controller'=>'ad','action'=>'delete','id'=>$ad->id_ad, 'current_url'=>$current_url))?>" 
					onclick="return confirm('<?=__('Delete?')?>');"
				    rel="tooltip" title="<?=__('Delete')?>" data-id="tr1" data-text="<?=__('Are you sure you want to delete?')?>">
					<i class="glyphicon   glyphicon-remove"></i>
				</a>
				<?if($current_url == Model_Ad::STATUS_PUBLISHED):?>
				<?if($ad->featured == NULL):?>
				<a class="btn btn-primary " 
					href="<?=Route::url('oc-panel', array('controller'=>'ad','action'=>'featured','id'=>$ad->id_ad, 'current_url'=>$current_url))?>" 
					onclick="return confirm('<?=__('Make featured?')?>');"
				    rel="tooltip" title="<?=__('Featured')?>" data-id="tr1" data-text="<?=__('Are you sure you want to make it featured?')?>">
					<i class="glyphicon   glyphicon-bookmark"></i>
				</a>
				<?else:?>
				<a class="btn btn-default " 
					href="<?=Route::url('oc-panel', array('controller'=>'ad','action'=>'featured','id'=>$ad->id_ad, 'current_url'=>$current_url))?>" 
					onclick="return confirm('<?=__('Deactivate featured?')?>');"
				    rel="tooltip" title="<?=__('Deactivate Featured')?>" data-id="tr1" data-text="<?=__('Are you sure you want to deactivate featured advertisement?')?>">
					<i class="glyphicon   glyphicon-bookmark"></i>
				</a>
				<?endif?>
				<a class="btn btn-info" 
					href="<?=Route::url('oc-panel', array('controller'=>'ad','action'=>'to_top','id'=>$ad->id_ad, 'current_url'=>$current_url))?>" 
					onclick="return confirm('<?=__('Refresh listing, go to top?')?>');"
				    rel="tooltip" title="<?=__('Go to top')?>" data-id="tr1" data-text="<?=__('Are you sure you want to refresh listing and go to top?')?>">
					<i class="glyphicon   glyphicon-circle-arrow-up"></i>
				</a>
				<?endif?>
			</td>
		</tr>
	<?endforeach?>
	<?endif?>
	</tbody>
</table>
<?if(isset($pagination)):?>
<?=$pagination?>
<?endif?>
