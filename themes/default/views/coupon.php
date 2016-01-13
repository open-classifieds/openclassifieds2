<?php defined('SYSPATH') or die('No direct script access.');?>

<?if(Model_Coupon::available() AND Theme::get('premium')==1):?>
<form class="form-inline"  method="post" action="<?=URL::current()?>">         
    <?if (Model_Coupon::current()->loaded()):?>
        <?=Form::hidden('coupon_delete',Model_Coupon::current()->name)?>
        <button type="submit" class="btn btn-warning"><?=__('Delete')?> <?=Model_Coupon::current()->name?></button>
        <p>
            <?=sprintf(__('Discount off %s'), (Model_Coupon::current()->discount_amount==0)?round(Model_Coupon::current()->discount_percentage,0).'%':i18n::money_format((Model_Coupon::current()->discount_amount)))?><br>
            <?=sprintf(__('%s coupons left'), Model_Coupon::current()->number_coupons)?>, <?=sprintf(__('valid until %s'), Date::format(Model_Coupon::current()->valid_date, core::config('general.date_format')))?>.
            <?if(Model_Coupon::current()->id_product!=NULL):?>
                <?=sprintf(__('only valid for %s'), Model_Order::product_desc(Model_Coupon::current()->id_product))?>
            <?endif?>
        </p>
    <?else:?>
    <div class="form-group">
        <div class="input-group">
            <input class="form-control" type="text" name="coupon" value="<?=Core::get('coupon')?><?=Core::get('coupon')?>" placeholder="<?=__('Coupon Name')?>">
            <span class="input-group-btn">
                <button type="submit" class="btn btn-default locateme"><?=__('Add')?></button>
            </span>
        </div>
    </div>
    <?endif?>
</form>
<?endif?>