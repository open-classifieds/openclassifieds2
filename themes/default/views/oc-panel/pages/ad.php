<?php defined('SYSPATH') or die('No direct script access.');?>
<?=Alert::show()?>
<div class="row">
    <div class="col-lg-12 page-title-container">
        <h1 class="page-header page-title"><?=__('Advertisements')?></h1>
        <span class="page-description"><?=__('This panel shows how many visitors your website had the past month.')?> <a target="_blank" href="https://docs.yclas.com/how-to-manage-advertisements/"><?=__('Read more')?></a></span>
    </div>
</div>

<div class="row">
	<div class="col-xs-12">
		<form id="advert_search" method="GET" class="pull-right" enctype="multipart/form-data">
          <input type="text" name="search" id="search" class="form-control" placeholder="<?=__('Search')?>">
    	</form>
    	<div class="pull-left">
			<?if(Core::get('status') == Model_Ad::STATUS_UNAVAILABLE):?>
				<? $current_url = Model_Ad::STATUS_UNAVAILABLE?>
				<h4><?=__('Unavailable')?></h4>
			<?elseif (Core::get('status') == Model_Ad::STATUS_UNCONFIRMED):?>
				<? $current_url = Model_Ad::STATUS_UNCONFIRMED?>
				<h4><?=__('Unconfirmed')?></h4>
			<?elseif (Core::get('status') == Model_Ad::STATUS_SPAM):?>
				<? $current_url = Model_Ad::STATUS_SPAM?>
				<h4><?=__('Spam')?></h4>
			<?else:?>
				<? $current_url = Model_Ad::STATUS_PUBLISHED?>
				<h4><?=__('All ads')?> <small></h4>
			<?endif?>
		</div>
	</div>
</div>		

<div class="btn-group pull-right space-bottom">
    <a href="<?=Route::url('oc-panel', array('directory'=>'panel', 'controller'=>'ad', 'action'=>'index'))?>" class="btn btn-success btn-icon-left">
        <i class="glyphicon glyphicon-globe"></i> <?=__('All ads')?>
    </a>
    <a class="btn btn-warning ajax-load btn-icon-left" type="submit" value="spam" href="<?=Route::url('oc-panel', array('directory'=>'panel', 'controller'=>'ad', 'action'=>'index')).'?status='.Model_Ad::STATUS_SPAM?>" rel="tooltip" title="<?=__('Spam Sort')?>">
    <i class="glyphicon glyphicon-fire"></i> <?=__('Spam')?>
    </a>
    <a class="btn btn-danger ajax-load btn-icon-left" type="submit" value="unavailable" href="<?=Route::url('oc-panel', array('directory'=>'panel', 'controller'=>'ad', 'action'=>'index')).'?status='.Model_Ad::STATUS_UNAVAILABLE?>" rel="tooltip" title="<?=__('Unavailable Sort')?>">
        <i class="glyphicon glyphicon-exclamation-sign"></i> <?=__(' Unavailable')?>
    </a>
    <a class="btn btn-info ajax-load btn-icon-left" type="submit" value="unconfirmed" href="<?=Route::url('oc-panel', array('directory'=>'panel', 'controller'=>'ad', 'action'=>'index')).'?status='.Model_Ad::STATUS_UNCONFIRMED?>" rel="tooltip" title="<?=__('Unconfirmed Sort')?>">
        <i class="glyphicon glyphicon-plane"></i> <?=__(' Unconfirmed')?>
    </a>

    <?if(Core::get('status') == Model_Ad::STATUS_UNAVAILABLE OR Core::get('status') == Model_Ad::STATUS_UNCONFIRMED OR Core::get('status') == Model_Ad::STATUS_SPAM):?>
    <a
        href="<?=Route::url('oc-panel', array('controller'=>'ad','action'=>'delete_all')).'?status='.Core::get('status')?>" 
        class="btn btn-danger pull-right" 
        title="<?=__('Delete All?')?>" 
        data-toggle="confirmation" 
        data-btnOkLabel="<?=__('Yes, definitely!')?>" 
        data-btnCancelLabel="<?=__('No way!')?>">
        <i class="glyphicon glyphicon-minus"></i> <?=__('Delete All')?>
    </a>
    <?endif?>
    <button type="button" class="btn btn-default dropdown-toggle pull-right" data-toggle="dropdown" aria-expanded="false">
    	<?=Core::get('order','Sort')?> <?=Core::get('sort')?>  <span class="caret"></span>
  	</button>
  	<ul class="dropdown-menu" role="menu">
	    <?foreach($fields as $field):?>
	        <li><a class="ajax-load" href="<?=Route::url('oc-panel', array('controller'=> 'ad', 'action'=>'index')) ?>?order=<?=$field?>&sort=desc&status=<?=Core::get('status')?>"><?=$field?> - Desc</a></li>
	        <li><a class="ajax-load" href="<?=Route::url('oc-panel', array('controller'=> 'ad', 'action'=>'index')) ?>?order=<?=$field?>&sort=asc&status=<?=Core::get('status')?>"><?=$field?> - Asc</a></li>
	    <?endforeach?>
	  </ul>
</div>

<div class="clearfix"></div>
<div class="panel panel-default">
    <div class="panel-body">
    	<div class="table-responsive">
			<table class="table table-bordered">
				<tr>
					<th>
						<div class="checkbox">
							<label>
								<input type="checkbox" id="select-all" onclick="check_all();">
							</label>
						</div>
					</th>
			        <th>#</th>
					<th><?=__('Name')?></th>
					<th><?=__('Category')?></th>
					<th><?=__('Location')?></th>
			        <?if(core::config('advertisement.count_visits')==1):?>
					<th><?=__('Hits')?></th>
			        <?endif?>
					<th><?=__('Status')?></th>
					<th><?=__('Published')?></th>
			        <th><?=__('Created')?></th>
					<!-- in case there are no ads we dont show buttons -->
					<?if(isset($res)):?>
					<th>
						<div class="toolbar btn btn-default btn-sm"><i class="glyphicon glyphicon-cog"></i>
						<div id="user-toolbar-options-all" class="hide user-toolbar-options">
							<?if(Core::get('status') != Model_Ad::STATUS_SPAM):?>
							<a class="spam btn btn-warning btn-sm" 
								href="<?=Route::url('oc-panel', array('controller'=>'ad','action'=>'spam'))?>" 
								onclick="return confirm('<?=__('Spam?')?>');"
								rel="tooltip" title="<?=__('Spam')?>">
								<i class="glyphicon   glyphicon-fire"></i>
							</a>
							<?endif?>
							<?if(Core::get('status') != Model_Ad::STATUS_UNAVAILABLE):?>
							<a class="deactivate btn btn-warning btn-sm" 
								href="<?=Route::url('oc-panel', array('controller'=>'ad','action'=>'deactivate'))?>" 
								onclick="return confirm('<?=__('Deactivate?')?>');"
								rel="tooltip" title="<?=__('Deactivate')?>">
								<i class="glyphicon   glyphicon-remove"></i>
							</a>
							<?endif?>
							<?if($current_url != Model_Ad::STATUS_PUBLISHED):?>
							<a class="activate btn btn-success btn-sm" 
									href="<?=Route::url('oc-panel', array('controller'=>'ad','action'=>'activate'))?>" 
									onclick="return confirm('<?=__('Activate?')?>');"
									rel="tooltip" title="<?=__('Activate')?>">
									<i class="glyphicon   glyphicon-ok-sign"></i>
							</a>
							<?endif?>
							<a class="delete btn btn-danger btn-sm" 
								href="<?=Route::url('oc-panel', array('controller'=>'ad','action'=>'delete'))?>"
								onclick="return confirm('<?=__('Delete?')?>');"
							    rel="tooltip" title="<?=__('Delete')?>" data-id="tr1" data-text="<?=__('Are you sure you want to delete?')?>">
								<i class="glyphicon   glyphicon-remove"></i>
							</a>
						</div>
						</div>
					</th>
					<?endif?>
				</tr>
			<?if(isset($res)):?>
				<? $i = 0; foreach($res as $ad):?>
				<tbody>
					<tr>
						<td>
							<div class="checkbox">
							    <label>
							        <input type="checkbox" id="<?= $ad->id_ad.'_'?>" class="checkbox">
							    </label>
							</div>
						</td>

			            <td><?=$ad->id_ad?>
						
						<td><a href="<?=Route::url('ad', array('controller'=>'ad','category'=>$ad->category->seoname,'seotitle'=>$ad->seotitle))?>"><?= wordwrap($ad->title, 15, "<br />\n"); ?></a>
			            </td>

			            <td><?= wordwrap($ad->category->name, 15, "<br />\n"); ?>
			                 
			            <td>
			                <?if($ad->location->loaded()):?>
			                    <?=wordwrap($ad->location->name, 15, "<br />\n");?>
			                <?else:?>
			                    n/a
			                <?endif?>
			            </td>

			            <?if(core::config('advertisement.count_visits')==1):?>
			            <td><?=$ad->visits->count_all();?></td>
						<?endif?>

						<td>
			            <?if($ad->status == Model_Ad::STATUS_NOPUBLISHED):?>
			                <?=__('Not published')?>
			            <? elseif($ad->status == Model_Ad::STATUS_PUBLISHED):?>
			                <?=__('Published')?>
			            <? elseif($ad->status == Model_Ad::STATUS_SPAM):?>
			                <?=__('Spam')?>
			            <? elseif($ad->status == Model_Ad::STATUS_UNAVAILABLE):?>
			                <?=__('Unavailable')?>
			            <?endif?>

			            <?if( ($order = $ad->get_order())!==FALSE ):?>
			                <a class="label <?=($order->status==Model_Order::STATUS_PAID)?'label-success':'label-warning'?> " 
			                    href="<?=Route::url('oc-panel', array('controller'=> 'order','action'=>'index'))?>?email=<?=$order->user->email?>">
			                <?if ($order->status==Model_Order::STATUS_CREATED):?>
			                    <?=__('Not paid')?>
			                <?elseif ($order->status==Model_Order::STATUS_PAID):?>
			                    <?=__('Paid')?>
			                <?endif?>
			                    <?=i18n::format_currency($order->amount,$order->currency)?>
			                </a>
			            <?endif?>
			            </td>
				    	
				    	<td>
				    		<? if ($ad->status == Model_Ad::STATUS_PUBLISHED):?>	    	
						    	<?=Date::format($ad->published, core::config('general.date_format')) ?>		    	
						     <? endif ?>
				    	</td>
			            <td>
			                <?= Date::format($ad->created, core::config('general.date_format')) ?>
			            </td>
			            
						<td width="90" style="width:90px">
							<a class="btn btn-primary btn-sm" 
								href="<?=Route::url('oc-panel', array('controller'=>'myads','action'=>'update','id'=>$ad->id_ad))?>" 
								rel="tooltip" title="<?=__('Update')?>">
								<i class="glyphicon   glyphicon-edit"></i>
							</a>
							<div class="toolbar btn btn-default btn-sm"><i class="glyphicon glyphicon-cog"></i>
							<div id="user-toolbar-options<?=$ad->id_ad?>" class="hide user-toolbar-options">
								
								<?if($ad->status != Model_Ad::STATUS_SPAM):?>
								
								<a class="btn btn-warning btn-sm" 
									href="<?=Route::url('oc-panel', array('controller'=>'ad','action'=>'spam','id'=>$ad->id_ad))?>?current_url=<?=$current_url?>" 
									onclick="return confirm('<?=__('Spam?')?>');"
									rel="tooltip" title="<?=__('Spam')?>">
									<i class="glyphicon   glyphicon-fire"></i>
								</a>
								<?endif?>
								<?if($ad->status != Model_Ad::STATUS_UNAVAILABLE):?>
								<a class="btn btn-warning btn-sm" 
									href="<?=Route::url('oc-panel', array('controller'=>'ad','action'=>'deactivate','id'=>$ad->id_ad))?>?current_url=<?=$current_url?>" 
									onclick="return confirm('<?=__('Deactivate?')?>');"
									rel="tooltip" title="<?=__('Deactivate')?>">
									<i class="glyphicon   glyphicon-remove"></i>
								</a>
								<?endif?>
								<?if( $ad->status != Model_Ad::STATUS_PUBLISHED ):?>
								<a class="btn btn-success btn-sm" 
									href="<?=Route::url('oc-panel', array('controller'=>'ad','action'=>'activate','id'=>$ad->id_ad))?>?current_url=<?=$current_url?>" 
									onclick="return confirm('<?=__('Activate?')?>');"
									rel="tooltip" title="<?=__('Activate')?>">
									<i class="glyphicon   glyphicon-ok-sign"></i>
								</a>
								<?endif?>
								<!-- sel_url_to_redirect is important because is quick selector. This works with dynamic check boxes, where we select href to build new url -->
								<a class="btn btn-danger btn-sm sel_url_to_redirect" 
									href="<?=Route::url('oc-panel', array('controller'=>'ad','action'=>'delete','id'=>$ad->id_ad))?>?current_url=<?=$current_url?>" 
									onclick="return confirm('<?=__('Delete?')?>');"
								    rel="tooltip" title="<?=__('Delete')?>" data-id="tr1" data-text="<?=__('Are you sure you want to delete?')?>">
									<i class="glyphicon   glyphicon-remove"></i>
								</a>
								<?if($current_url == Model_Ad::STATUS_PUBLISHED):?>
			                        <?if(core::config('payment.to_featured') != FALSE):?>
			                            <?if($ad->featured==NULL OR Date::mysql2unix($ad->featured) < time()):?>
			                            <a class="btn btn-primary btn-sm" 
			                                href="<?=Route::url('default', array('controller'=>'ad','action'=>'to_featured','id'=>$ad->id_ad))?>" 
			                                onclick="return confirm('<?=__('Make featured?')?>');"
			                                rel="tooltip" title="<?=__('Featured')?>" data-id="tr1" data-text="<?=__('Are you sure you want to make it featured?')?>">
			                                <i class="glyphicon   glyphicon-bookmark"></i>
			                            </a>
			                            <?elseif(Date::mysql2unix($ad->featured) > time()):?>
			                            <a class="btn btn-primary btn-sm" 
			                                href="<?=Route::url('oc-panel', array('controller'=>'ad','action'=>'unfeature','id'=>$ad->id_ad))?>" 
			                                onclick="return confirm('<?=__('Remove featured?')?>');"
			                                rel="tooltip" title="<?=__('Remove Featured')?>" data-id="tr1" data-text="<?=__('Are you sure you want to remove featured ad?')?>">
			                                <i class="glyphicon   glyphicon-minus"></i> <i class="glyphicon   glyphicon-bookmark"></i>
			                            </a>
			                            <?endif?>
			                        <?endif?>
			                        <?if(core::config('payment.pay_to_go_on_top') > 0 AND core::config('payment.to_top') != FALSE):?>
			    					<a class="btn btn-info btn-sm" 
			    						href="<?=Route::url('default', array('controller'=>'ad','action'=>'to_top','id'=>$ad->id_ad))?>" 
			    						onclick="return confirm('<?=__('Refresh listing, go to top?')?>');"
			    					    rel="tooltip" title="<?=__('Go to top')?>" data-id="tr1" data-text="<?=__('Are you sure you want to refresh listing and go to top?')?>">
			    						<i class="glyphicon   glyphicon-circle-arrow-up"></i>
			    					</a>
			                        <?endif?>
			                        <a class="btn btn-primary btn-sm" 
			                            href="<?=Route::url('oc-panel', array('controller'=>'myads','action'=>'stats','id'=>$ad->id_ad))?>" 
			                            rel="tooltip" title="<?=__('Stats')?>">
			                            <i class="glyphicon glyphicon-align-left"></i>
			                        </a>
								<?endif?>
							</div>
							</div>
						</td>
					</tr>
				<?endforeach?>
				<?endif?>
				</tbody>
			</table>
		</div>
    </div>
</div>
<?if(isset($pagination)):?>
    <div class="text-center"><?=$pagination?></div>
<?endif?>

