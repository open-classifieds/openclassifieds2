<?php defined('SYSPATH') or die('No direct script access.');?>
<div class="container">
	<div class="col-xs-12">
		<div class="jumbotron text-center">
			<h1>Oops!</h1>
				<p>Something went wrong with your request. The incident was logged and we've been notified about the issue.</p>
				<?if (Auth::instance()->get_user()):?>
					<?if (Auth::instance()->get_user()->id_role == Model_Role::ROLE_ADMIN):?>
						<br><hr><br>
							<p>Since you are loged in as admin only you can see this message:</p>
							<code><?=$message?></code>
							<p>It's been loged in <a href="<?php echo Route::url('oc-panel',array('controller'=>'tools','action'=>'logs')) ?>">Panel->Extra->Tools->Logs</a> for more information regarding this error.</p>
						<br><hr><br>
					<?endif?>
				<?endif?>
				<p>You can go <a href="javascript: history.go(-1)">Back</a> or to our <a href="<?php echo URL::site('/', TRUE) ?>">Home page</a>.</p>
		</div>
	</div>
</div>
