<?php

namespace Fatchip\FatPay\extend\src\Controller;

use Fatchip\FatPay\extend\src\Model\Order;
use Fatchip\FatPay\Helper\Payment;
use OxidEsales\Eshop\Core\Registry;

class PaymentController extends PaymentController_parent
{
    public function init()
    {
        if (Registry::getSession()->getVariable('fcIsRedirected')) {
            $this->fcCancelOrder();
        }
        parent::init();
    }

    public function fcCancelOrder()
    {
        $order = oxNew(Order::class);
        $order->fcCancelCurrentOrder();
        Registry::getSession()->deleteVariable('fcIsRedirected');
    }

    public function fcIsFatPayPayment(string $paymentId): bool
    {
        return Payment::isFatPayPayment($paymentId);
    }
}