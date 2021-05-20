<?php

namespace Mollie\Api\Types;

class MandateMethod
{
    const DIRECTDEBIT = "directdebit";
    const CREDITCARD = "creditcard";
    const PAYPAL = "paypal";
    public static function getForFirstPaymentMethod($firstPaymentMethod)
    {
        if ($firstPaymentMethod === \Mollie\Api\Types\PaymentMethod::PAYPAL) {
            return static::PAYPAL;
        }
        if (\in_array($firstPaymentMethod, [\Mollie\Api\Types\PaymentMethod::APPLEPAY, \Mollie\Api\Types\PaymentMethod::CREDITCARD])) {
            return static::CREDITCARD;
        }
        return static::DIRECTDEBIT;
    }
}
