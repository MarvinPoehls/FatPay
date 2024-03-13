<?php

use Fatchip\FatPay\Core\Events;
use Fatchip\FatPay\extend\src\Model\Payment;
use OxidEsales\EshopCommunity\Internal\Container\ContainerFactory;
use OxidEsales\EshopCommunity\Internal\Framework\Database\QueryBuilderFactoryInterface;

class EventsTest extends \OxidEsales\TestingLibrary\UnitTestCase
{
    public function testAddingPaymentMethod()
    {
        $oxid = 'oxidfatpay';

        $expectedRow = [
            'OXACTIVE' => 1,
            'OXDESC' => 'FatPay',
            'OXADDSUM' => 0,
            'OXADDSUMTYPE' => 'abs',
            'OXFROMBONI' => 0,
            'OXFROMAMOUNT' => 0,
            'OXTOAMOUNT' => 10000,
        ];

        $queryBuilder = ContainerFactory::getInstance()
            ->getContainer()
            ->get(QueryBuilderFactoryInterface::class)
            ->create();

        $condition = $queryBuilder->expr()->eq('oxid', $queryBuilder->createNamedParameter($oxid));

        $queryBuilder
            ->delete('oxpayments')
            ->where($condition)
            ->execute();

        Events::onActivate();

        $fatpay = oxNew(Payment::class);
        $fatpay->load($oxid);

        $row = [
            'OXACTIVE' => $fatpay->oxpayments__oxactive->value,
            'OXDESC' => $fatpay->oxpayments__oxdesc->value,
            'OXADDSUM' => $fatpay->oxpayments__oxaddsum->value,
            'OXADDSUMTYPE' => $fatpay->oxpayments__oxaddsumtype->value,
            'OXFROMBONI' => $fatpay->oxpayments__oxfromboni->value,
            'OXFROMAMOUNT' => $fatpay->oxpayments__oxfromamount->value,
            'OXTOAMOUNT' => $fatpay->oxpayments__oxtoamount->value,
        ];

        $this->assertEquals($expectedRow, $row);
    }
}