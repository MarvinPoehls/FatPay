<?php

namespace Fatchip\FatPay\Core;

use Fatchip\FatPay\Helper\Payment as PaymentHelper;
use OxidEsales\Eshop\Application\Model\Payment;
use OxidEsales\Eshop\Core\Field;
use OxidEsales\Eshop\Core\Model\BaseModel;
use OxidEsales\Eshop\Core\Registry;

class Events
{
    public static function onActivate()
    {
        foreach (PaymentHelper::getFatpayPayments() as $paymentId) {
            self::addPayment($paymentId);
            self::setPaymentActive($paymentId, true);
        }
    }

    public static function onDeactivate()
    {
        foreach (PaymentHelper::getFatpayPayments() as $paymentId) {
            self::setPaymentActive($paymentId, false);
        }
    }

    protected static function setPaymentActive(string $id, bool $active)
    {
        $payment = oxNew(Payment::class);
        if ($payment->load($id)) {
            $payment->oxpayments__oxactive = new Field((int)$active);
            $payment->save();
        }
    }

    protected static function addPayment(string $id)
    {
        $paymentLanguages = ['de', 'en'];

        $payment = oxNew(Payment::class);
        if (!$payment->load($id)) {
            $payment->setId($id);
            $payment->oxpayments__oxactive = new Field(1);
            $payment->oxpayments__oxaddsum = new Field(0);
            $payment->oxpayments__oxaddsumtype = new Field('abs');
            $payment->oxpayments__oxfromboni = new Field(0);
            $payment->oxpayments__oxfromamount = new Field(0);
            $payment->oxpayments__oxtoamount = new Field(10000);
            $payment->oxpayments__oxdesc = new Field($id);

            $language = Registry::getLang();
            $languages = $language->getLanguageIds();
            foreach ($paymentLanguages as $languageAbbreviation) {
                $languageId = array_search($languageAbbreviation, $languages);
                if ($languageId !== false) {
                    $payment->setLanguage($languageId);
                    $payment->save();
                }
            }

            self::setDelivery($id);
        }
    }

    protected static function setDelivery($id)
    {
        $deliveryIds = \Fatchip\FatPay\extend\src\Model\Payment::fcGetDeliveryIds();
        foreach ($deliveryIds as $deliveryId) {
            $model = oxNew(BaseModel::class);
            $model->init('oxobject2payment');
            $model->assign(
                [
                    'oxpaymentid' => $id,
                    'oxobjectid'  => $deliveryId,
                    'oxtype' => 'oxdelset'
                ]
            );
            $model->save();
        }
    }
}