<div class="panel panel-default">
    <div class="panel-heading" id="page-edit-profile">
        <h3 class="panel-title"><?=_e('Subscription')?></h3>
    </div>
    <div class="panel-body">
        <div class="row">
            <div class="col-md-8">
                <?if ($user->subscription()->loaded()):?>
                    <p>
                        <?if($user->subscription()->amount_ads_left > -1 ):?>
                            <?=sprintf(__('You are subscribed to the plan %s until %s with %u ads left'),$user->subscription()->plan->name,$user->subscription()->expire_date,$user->subscription()->amount_ads_left)?>
                        <?else:?>
                            <?=sprintf(__('You are subscribed to the plan %s until %s with unlimited ads'),$user->subscription()->plan->name,$user->subscription()->expire_date)?>
                        <?endif?>
                    </p>
                    <?if ($user->stripe_agreement!=NULL):?>
                        <a href="<?=Route::url('oc-panel',array('controller'=>'profile','action'=>'cancelsubscription'))?>"
                                  class="btn btn-danger"
                                  onclick="return confirm('<?=__('Cancel Subscription?')?>');"
                                  title="<?=__('Cancel Subscription')?>">
                                  <?=_e('Cancel Subscription')?>
                        </a>
                    <?endif?>
                <?else:?>
                    <a class="btn btn-primary" href="<?=Route::url('pricing')?>">
                        <span class="glyphicon glyphicon-ok" aria-hidden="true"></span> <?=_e('Choose a Plan')?>
                    </a>
                <?endif?>
            </div>
        </div>
    </div>
</div>
