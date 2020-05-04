<?php defined('SYSPATH') or die('No direct script access.');?>

<footer>
	<div class="container">
		<div class="row footer-widgets">
			<?$i=0; foreach ( Widgets::render('footer') as $widget):?>
				<div class="col-md-3">
					<?=$widget?>
				</div>
				<? $i++; if ($i%4 == 0) echo '<div class="clearfix"></div>';?>
			<?endforeach?>
		</div>

		<div class="main-footer-content">
			<? if (Core::config('general.multilingual')) : ?>
				<div class="dropdown dropup pull-right">
					<button type="button" class="btn btn-xs btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
						<i class="fa fa-language"></i> <?=i18n::get_display_language(i18n::$locale)?> <span class="caret"></span>
					</button>
					<ul class="dropdown-menu dropdown-menu-right">
						<? foreach (i18n::get_selectable_languages() as $locale => $language) : ?>
							<? if (i18n::$locale != $locale) : ?>
								<li>
									<a href="<?= Route::url('default') ?>?language=<?= $locale ?>">
									<?= $language ?></a>
								</li>
							<? endif ?>
						<? endforeach ?>
					</ul>
				</div>
			<? endif ?>
			<!-- IMPORTANT! This is the license for the Open Classifieds software, do not remove!-->
				<p class="ocLicence text-center">&copy;
				<?if (Core::extra_features() == FALSE):?>
					Web Powered by <a href="https://yclas.com?utm_source=<?=URL::base()?>&utm_medium=oc_footer&utm_campaign=<?=date('Y-m-d')?>" title="Best PHP Script Classifieds Software">Yclas</a>
					2009 - <?=date('Y')?>
				<?else:?>
					<?=core::config('general.site_name')?> <?=date('Y')?>
				<?endif?>
				</p>
			<!-- IMPORTANT! This is the license for the Open Classifieds software, do not remove!-->

			<?if(Cookie::get('user_location')):?>
				<p class="text-center">
					<a href="<?=Route::url('default')?>?user_location=0"><span class="glyphicon glyphicon-globe"></span> <?=_e('Change Location')?></a>
				</p>
			<?endif?>
		</div>
	</div>
</footer>