<?php defined('SYSPATH') or die('No direct script access.');?>

<script src="https://js.stripe.com/v3/"></script>
<div class="text-right">
    <button class="btn btn-primary" id="checkout-button"><i class="fa fa-credit-card"></i> <?= __('Pay with Card') ?></button>
</div>

<script>
    var stripe = Stripe('<?= Core::config('payment.stripe_public') ?>');

    var checkoutButton = document.querySelector('#checkout-button');
    checkoutButton.addEventListener('click', function () {
        stripe.redirectToCheckout({
            sessionId: '<?= $session_id ?>',
        });
    });
</script>
