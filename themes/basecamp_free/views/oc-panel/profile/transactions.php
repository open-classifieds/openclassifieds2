<?php defined('SYSPATH') or die('No direct script access.');?>
<div class="pad_10tb">
	<div class="container">
		<div class="col-xs-12">
			<div class="page-header">
                <? if (Core::config('general.ewallet_add_money')) : ?>
                    <a
                        href="<?= Route::url('oc-panel', ['controller' => 'ewallet', 'action' => 'add_money']) ?>"
                        class="pull-right btn btn-primary">
                        <?= __('Add money') ?>
                    </a>
                <? endif ?>

                <h3><?=_e('Transactions')?></h3>
			</div>

			<?foreach($transactions as $transaction):?>
				<div class="my_ad_item">
					<div class="my_ad_body clearfix">
						<div class="ad_dcoll">
							<div class="pad_10">
								<p><b># : </b><?= $transaction->id_transaction ?></p>
								<p><b><?=_e('Amount')?> : </b><?= i18n::money_format($transaction->amount, 'YCL') ?></p>
								<? if ($transaction->user_from->loaded()) : ?>
                                    <p><b><?=_e('From')?> : </b><?= $transaction->user_from->name ?></p>
                                <? endif ?>
							</div>
						</div>
					</div>
				</div>
			<?endforeach?>

			<div class="text-center">
				<?=$pagination?>
			</div>
		</div>
	</div>
</div>
