<?php

namespace Fatchip\FatPay\Core;

use OxidEsales\Eshop\Application\Model\Payment;
use OxidEsales\Eshop\Core\Field;
use OxidEsales\Eshop\Core\Model\BaseModel;
use OxidEsales\Eshop\Core\Registry;

class Events
{
    private static $paymentIds = [
    'oxidfatpay',
    'oxidfatredirect',
    ];

    public static function onActivate()
    {
        self::addPaymentMethods();
        self::activatePayments(self::$paymentIds);
    }

    public static function onDeactivate()
    {
        self::deactivatePayments(self::$paymentIds);
    }

    protected static function deactivatePayments(array $paymentIds)
    {
        foreach ($paymentIds as $paymentId) {
            $payment = oxNew(Payment::class);
            if ($payment->load($paymentId)) {
                $payment->oxpayments__oxactive = new Field(0);
                $payment->save();
            }
        }
    }

    protected static function activatePayments(array $paymentIds)
    {
        foreach ($paymentIds as $paymentId) {
            $payment = oxNew(Payment::class);
            if ($payment->load($paymentId)) {
                $payment->oxpayments__oxactive = new Field(1);
                $payment->save();
            }
        }
    }

    protected static function addPaymentMethods()
    {
        self::addFatPay();
        self::addFatRedirect();
    }

    protected static function addFatPay()
    {
        $paymentLanguages = ['de', 'en'];

        $payment = oxNew(Payment::class);
        if (!$payment->load('oxidfatpay')) {
            $payment->setId('oxidfatpay');
            $payment->oxpayments__oxactive = new Field(1);
            $payment->oxpayments__oxdesc = new Field('FatPay');
            $payment->oxpayments__oxaddsum = new Field(0);
            $payment->oxpayments__oxaddsumtype = new Field('abs');
            $payment->oxpayments__oxfromboni = new Field(0);
            $payment->oxpayments__oxfromamount = new Field(0);
            $payment->oxpayments__oxtoamount = new Field(10000);

            $language = Registry::getLang();
            $languages = $language->getLanguageIds();
            foreach ($paymentLanguages as $languageAbbreviation) {
                $languageId = array_search($languageAbbreviation, $languages);
                if ($languageId !== false) {
                    $payment->setLanguage($languageId);
                    $payment->save();
                }
            }

            self::setDelivery('oxidfatpay');
        }
    }

    protected static function addFatRedirect()
    {
        $paymentLanguages = ['de', 'en'];

        $payment = oxNew(Payment::class);
        if ($payment->load('oxidfatredirect')) {
            $payment->oxpayments__oxactive = new Field(1);
            $payment->save();
        } else {
            $payment->setId('oxidfatredirect');
            $payment->oxpayments__oxactive = new Field(1);
            $payment->oxpayments__oxdesc = new Field('FATRedirect');
            $payment->oxpayments__oxaddsum = new Field(0);
            $payment->oxpayments__oxaddsumtype = new Field('abs');
            $payment->oxpayments__oxfromboni = new Field(0);
            $payment->oxpayments__oxfromamount = new Field(0);
            $payment->oxpayments__oxtoamount = new Field(10000);

            $language = Registry::getLang();
            $languages = $language->getLanguageIds();
            foreach ($paymentLanguages as $languageAbbreviation) {
                $languageId = array_search($languageAbbreviation, $languages);
                if ($languageId !== false) {
                    $payment->setLanguage($languageId);
                    $payment->save();
                }
            }

            self::setDelivery('oxidfatredirect');
        }
    }

    protected static function setDelivery($id)
    {
        $deliveryIds = \Fatchip\FatPay\extend\src\Model\Payment::fcGetDeliveryIds();
        if (!empty($deliveryIds)) {
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
}