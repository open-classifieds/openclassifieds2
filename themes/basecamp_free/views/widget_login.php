<?php defined('SYSPATH') or die('No direct script access.');?>
<?if (Auth::instance()->logged_in()):?>
	<?=View::factory('widget_notification')?>
	<div class="btn-group">
		<a class="btn btn-base-light" href="<?=Route::url('oc-panel',array('controller'=>'home','action'=>'index'))?>">
			<i class="glyphicon glyphicon-user"></i>
		</a>
		<button type="button" class="btn btn-base-light" data-toggle="dropdown">
			<span class="caret"></span>
		</button>
		<ul class="dropdown-menu">
            <?if (core::config('general.ewallet')):?>
                <li class="dropdown-header"><?= __('eWallet balance') ?></li>
                <li>
                    <a href="<?= Route::url('oc-panel', ['controller' => 'ewallet', 'action' => 'index']) ?>">
                        <?= i18n::money_format(Auth::instance()->get_user()->ewallet_balance, 'YCL') ?>
                    </a>
                </li>
                <li role="separator" class="divider"></li>
            <?endif?>
			<li>
				<a href="<?=Route::url('oc-panel',array('controller'=>'home','action'=>'index'))?>">
					<i class="glyphicon glyphicon-cog"></i> <?=_e('Panel')?>
				</a>
			</li>
			<li>
				<a href="<?=Route::url('oc-panel',array('controller'=>'myads','action'=>'index'))?>">
					<i class="glyphicon glyphicon-edit"></i> <?=_e('My Advertisements')?>
				</a>
			</li>
			<li>
				<a href="<?=Route::url('oc-panel',array('controller'=>'profile','action'=>'favorites'))?>">
					<i class="glyphicon glyphicon-star"></i> <?=_e('My Favorites')?>
				</a>
			</li>
            <? if (Model_Order::by_user(Auth::instance()->get_user())->count_all() > 0) : ?>
                <li>
                    <a href="<?=Route::url('oc-panel',array('controller'=>'profile','action'=>'orders'))?>">
                        <i class="glyphicon glyphicon-shopping-cart"></i> <?=_e('My Payments')?>
                    </a>
                </li>
            <?endif?>
            <?if(core::config('payment.paypal_seller') == TRUE OR Core::config('payment.stripe_connect')==TRUE OR Core::config('payment.escrow_pay')==TRUE):?>
				<li>
					<a href="<?=Route::url('oc-panel',array('controller'=>'profile','action'=>'sales'))?>">
						<i class="fas fa-money-bill"></i> <?=_e('My Sales')?>
					</a>
				</li>
			<?endif?>
			<?if (core::config('general.messaging') == TRUE):?>
				<li>
					<a href="<?=Route::url('oc-panel',array('controller'=>'messages','action'=>'index'))?>">
						<i class="fa fa-inbox"></i> <?=_e('Messages')?>
					</a>
				</li>
			<?endif?>
			<li>
				<a href="<?=Route::url('oc-panel',array('controller'=>'profile','action'=>'subscriptions'))?>">
					<i class="glyphicon glyphicon-envelope"></i> <?=_e('Notifications')?>
				</a>
			</li>
			<li>
				<a href="<?=Route::url('oc-panel',array('controller'=>'profile','action'=>'edit'))?>">
					<i class="glyphicon glyphicon-lock"></i> <?=_e('Edit profile')?>
				</a>
			</li>
			<li>
				<a href="<?=Route::url('oc-panel',array('controller'=>'profile','action'=>'public'))?>">
					<i class="glyphicon glyphicon-eye-open"></i> <?=_e('Public profile')?>
				</a>
			</li>
			<li class="divider"></li>
			<li>
				<a href="<?=Route::url('oc-panel',array('directory'=>'user','controller'=>'auth','action'=>'logout'))?>">
					<i class="glyphicon glyphicon-off"></i> <?=_e('Logout')?>
				</a>
			</li>
            <?if (Auth::instance()->get_user()->is_admin() OR Auth::instance()->get_user()->is_moderator() OR Auth::instance()->get_user()->is_translator()):?>
                <li class="divider"></li>
                <li class="dropdown-header"><?=_e('Live translator')?></li>
                <?if (Core::request('edit_translation') == '1'):?>
                    <li>
                        <a href="?edit_translation=0">
                            <i class="fa fa-globe"></i> <?=__('Disable')?>
                        </a>
                    </li>
                <?else:?>
                    <li>
                        <a href="?edit_translation=1">
                            <i class="fa fa-globe"></i> <?=__('Enable')?>
                        </a>
                    </li>
                <?endif?>
            <?endif?>
		</ul>
	</div>
<?else:?>
	<a class="btn btn-base-light" data-toggle="modal" href="<?=Route::url('oc-panel',array('directory'=>'user','controller'=>'auth','action'=>'login'))?>#login-modal">
		<i class="glyphicon glyphicon-user"></i> <?=_e('Login')?>
	</a>
<?endif?>
