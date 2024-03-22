<?php

namespace Fatchip\FatPay\extend\src\Model;

use Fatchip\FatPay\Helper\Payment;
use OxidEsales\Eshop\Core\Field;
use OxidEsales\Eshop\Core\Registry;
use OxidEsales\EshopCommunity\Internal\Container\ContainerFactory;
use OxidEsales\EshopCommunity\Internal\Framework\Database\QueryBuilderFactoryInterface;

class Order extends Order_parent
{
    public function fcCancelCurrentOrder()
    {
        $sSessChallenge = Registry::getSession()->getVariable('sess_challenge');

        $oOrder = oxNew(Order::class);
        $oOrder->load($sSessChallenge);

        if ($oOrder->isLoaded() === true) {
            if ($oOrder->oxorder__oxtransstatus->value != 'OK') {
                $oOrder->cancelOrder();
            }
        }
        Registry::getSession()->deleteVariable('sess_challenge');
    }

    public function fcSetOrderNumber()
    {
        if (!$this->oxorder__oxordernr->value) {
            $this->_setNumber();
        }
    }

    public function fcSetTransactionId()
    {
        $transactionId = uniqid();

        $queryBuilder = ContainerFactory::getInstance()->getContainer()
            ->get(QueryBuilderFactoryInterface::class)->create();

        $queryBuilder->update('oxorder')
            ->set('oxtransid', "'".$transactionId."'")
            ->where("oxid='".$this->getId()."'")
            ->execute();

        $this->oxorder__oxtransid = new Field($transactionId);
    }

    public function fcGetPaymentModel($sPaymentId)
    {
        if ($sPaymentId !== Payment::FATREDIRECT) {
            throw new \Exception('FatPay Payment method unknown - '.$sPaymentId);
        }

        $oPaymentModel = oxNew(Payment::class);
        return $oPaymentModel;
    }

    public function _checkOrderExist($sOxId = null)
    {
        if ($this->fcIsReturnAfterPayment()) {
            return false;
        }
        return parent::_checkOrderExist($sOxId);
    }

    public function fcIsReturnAfterPayment(): bool
    {
        $isReturn = Registry::getSession()->getVariable("fcIsRedirected");

        if ($isReturn == null) {
            return false;
        }
        return $isReturn;
    }
}