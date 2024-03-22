<?php

namespace Fatchip\FatPay\Helper;

abstract class Payment
{
    const FATPAY = "FatPay";
    const FATREDIRECT = "FatRedirect";

    public static function getFatpayPayments()
    {
        return [self::FATPAY, self::FATREDIRECT];
    }

    public static function isFatPayPayment(string $paymentId): bool
    {
        return in_array($paymentId, self::getFatpayPayments());
    }
}