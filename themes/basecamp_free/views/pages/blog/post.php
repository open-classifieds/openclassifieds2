<?php defined('SYSPATH') or die('No direct script access.');?>
<div class="pad_10tb">
	<div class="container">
		<div class="row">
			<div class="col-xs-12">
				<div class="page-header">
					<h3><?= $post->title;?></h3>
				</div>
				<div class="blog-full">
					<p class="post-info">
						<span class="glyphicon glyphicon-user"></span> <?=$post->user->name?> &nbsp&nbsp <span class="glyphicon glyphicon-calendar"></span> <?=Date::format($post->created, core::config('general.date_format'))?>
					</p>

					<div class="blog-content pad_10">
						<?=$post->description?>
					</div>

					<div class="text-center pad_10">
						<?if($previous->loaded()):?>
							<a class="btn btn-default m-3" href="<?=Route::url('blog',  array('seotitle'=>$previous->seotitle))?>" title="<?=HTML::chars($previous->title)?>">
							<i class="glyphicon glyphicon-chevron-left"></i> <?=Text::truncate_html($previous->title, 28, NULL)?>...</a>
						<?endif?>
						<?if($next->loaded()):?>
							<a class="btn btn-default m-3" href="<?=Route::url('blog',  array('seotitle'=>$next->seotitle))?>" title="<?=HTML::chars($next->title)?>">
							<?=Text::truncate_html($next->title, 28, NULL)?>... <i class="glyphicon glyphicon-chevron-right"></i></a>
						<?endif?>
					</div>
				</div>

				<?= $post->comments() ?>
			</div>
		</div>
	</div>
</div>
