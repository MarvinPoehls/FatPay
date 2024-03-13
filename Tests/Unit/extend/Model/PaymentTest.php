<?php

use Fatchip\FatPay\extend\src\Model\Payment;
use OxidEsales\EshopCommunity\Internal\Container\ContainerFactory;
use OxidEsales\EshopCommunity\Internal\Framework\Database\QueryBuilderFactoryInterface;

class PaymentTest extends \OxidEsales\TestingLibrary\UnitTestCase
{
    public function testGetDeliveryIds()
    {
        $queryBuilder = ContainerFactory::getInstance()
            ->getContainer()
            ->get(QueryBuilderFactoryInterface::class)
            ->create();

        $queryBuilder->getConnection()->executeUpdate("TRUNCATE TABLE oxdeliveryset");

        $deliveryIds = [
            'oxid' => 0,
        ];

        foreach ($deliveryIds as $deliveryId) {
            $sql = "INSERT INTO oxdeliveryset (OXID) VALUES (:deliveryId)";

            $params = ['deliveryId' => $deliveryId];

            $queryBuilder->getConnection()->executeUpdate($sql, $params);
        }

        $this->assertEquals($deliveryIds, Payment::fcGetDeliveryIds());
    }
}