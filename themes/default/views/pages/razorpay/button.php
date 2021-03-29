<form action="<?= Route::url('default', ['controller'=>'razorpay', 'action'=>'verify','id'=> $order->id_order]) ?>" method="POST">
    <script
        src="https://checkout.razorpay.com/v1/checkout.js"
        data-key="<?= $key_id ?>"
        data-amount="<?= $order->amount * 100 ?>"
        data-currency="INR"
        data-name="<?= Core::config('general.site_name') ?>"
        <? if (Theme::get('apple-touch-icon')) : ?>
            data-image="<?= Theme::get('apple-touch-icon') ?>"
        <? endif ?>
        data-description="<?= $order->description ?>"
        data-prefill.name="<?= $order->user->name ?>"
        data-prefill.email="<?= $order->user->email ?>"
        <? if ($order->user->phone) : ?>
            data-prefill.contact="<?= $order->user->phone ?>"
        <? endif ?>
        data-notes.order_id="<?= $order->id_order ?>"
        data-order_id="<?= $razorpay_order['id']?>"
        data-buttontext="<?= __('Pay Now') ?>"
    >
    </script>

    <input type="hidden" name="order_id" value="<?= $order->id_order ?>">
</form>
