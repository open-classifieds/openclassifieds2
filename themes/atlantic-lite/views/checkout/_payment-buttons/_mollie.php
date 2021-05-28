<?php defined('SYSPATH') or die('No direct script access.');?>

<a class="btn btn-primary ml-2" href="<?=Route::url('default', ['controller' => 'mollie', 'action' => 'pay', 'id' => $order->id_order]) ?>">
    <?=_e('Pay Now')?>
</a>
