<?php defined('SYSPATH') or die('No direct script access.');?>

 <div class="text-right">
    <form action="<?=Route::url('default',['controller' => 'yclpay', 'action' => 'pay', 'id' => $order->id_order])?>" method="post">
        <button type="submit" class="btn btn-info pay-btn full-w">
            <span class="glyphicon glyphicon-shopping-cart"></span> <?= _e('Pay Now') ?>
        </button>
    </form>
 </div>
