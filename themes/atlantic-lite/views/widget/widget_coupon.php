<? if(Core::extra_features() == TRUE AND Model_Coupon::available()): ?>
    <div class="card card-coupon mb-3">
        <div class="card-header">
            <span class="h6"><?= $widget->text_title ?></span>
        </div>

        <div class="card-body">
            <?= Form::open(URL::current()) ?>
                <?if (Model_Coupon::current()->loaded()):?>
                    <?=Form::hidden('coupon_delete', Model_Coupon::current()->name)?>

                    <?= Form::button(NULL, _e('Delete') . Model_Coupon::current()->name, [
                        'type'=>'submit',
                        'class'=>'btn btn-block btn-success',
                    ])?>

                    <p>
                        <?= sprintf(__('Discount off %s'), (Model_Coupon::current()->discount_amount==0)?round(Model_Coupon::current()->discount_percentage,0).'%':i18n::money_format((Model_Coupon::current()->discount_amount)))?><br>
                        <?= sprintf(__('%s coupons left'), Model_Coupon::current()->number_coupons)?>, <?=sprintf(__('valid until %s'), Date::format(Model_Coupon::current()->valid_date, core::config('general.date_format')))?>.
                        <? if(Model_Coupon::current()->id_product!=NULL): ?>
                            <?= sprintf(__('only valid for %s'), Model_Order::product_desc(Model_Coupon::current()->id_product)) ?>
                        <? endif ?>
                    </p>
                <? else: ?>
                <div class="input-group">
                    <?= Form::input('coupon', Core::get('coupon'), [
                        'placeholder' => __('Coupon Name'),
                        'class' => 'form-control',
                        'id' => 'coupon',
                        'type' => 'text'
                    ])?>

                    <div class="input-group-append">
                        <?= Form::button(NULL, _e('Add'), [
                            'type'=>'submit',
                            'class'=>'btn btn-primary',
                        ])?>
                    </div>
                </div>
                <? endif ?>
            <?= Form::close() ?>
        </div>
    </div>
<? endif ?>
