<?php defined('SYSPATH') or die('No direct script access.');?>

<a class="btn btn-success" href="<?=Route::url('default', ['controller' => 'mollie', 'action' => 'pay', 'id' => $order->id_order]) ?>"><?=__('Pay now')?></a>
