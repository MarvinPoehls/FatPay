<?php

namespace Fatchip\FatPay\extend\src\Controller;

use OxidEsales\Eshop\Application\Model\Order;
use OxidEsales\Eshop\Core\Field;
use OxidEsales\Eshop\Core\Registry;

class OrderController extends OrderController_parent
{
    public function fcHandlePaymentPortalReturn()
    {
        $payment = $this->getPayment();
        if ($payment && $payment->oxpayments__oxid->value == "oxidfatredirect") {

            $order = $this->fcGetOrder();
            if (!$order) {
                return $this->fcRedirectWithError('FC_ERROR_ORDER_NOT_FOUND');
            }

            $transactionId = $order->oxorder__oxtransid->value;
            if (empty($transactionId)) {
                return $this->fcRedirectWithError('FC_ERROR_TRANSACTIONID_NOT_FOUND');
            }

            if (Registry::getSession()->getVariable('fcBasketPrice') != Registry::getSession()->getBasket()->getPrice()) {
                return $this->fcRedirectWithError('FC_ERROR_WRONG_BASKET');
            }

            $this->fcProcessTransaction($order);
        }
        $return = parent::execute();
        Registry::getSession()->deleteVariable("fcIsRedirected");
        return $return;
    }

    protected function fcRedirectWithError(string $errorLangId)
    {
        Registry::getSession()->setVariable('payerror', -50);
        Registry::getSession()->setVariable('payerrortext', Registry::getLang()->translateString($errorLangId));
        Registry::getUtils()->redirect(Registry::getConfig()->getCurrentShopUrl().'index.php?cl=payment');
        return false;
    }

    protected function fcGetOrder()
    {
        $orderId = Registry::getSession()->getVariable('sess_challenge');
        if (!empty($orderId)) {
            $order = oxNew(Order::class);
            $order->load($orderId);
            if ($order->isLoaded() === true) {
                return $order;
            }
        }
        return false;
    }

    protected function fcProcessTransaction(Order &$order)
    {
        $order->oxorder__oxpaid = new Field(date('Y-m-d H:i:s'));
        $order->save();
    }
}