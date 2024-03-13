<?php

namespace Fatchip\FatPay\extend\src\Model;

use OxidEsales\EshopCommunity\Internal\Container\ContainerFactory;
use OxidEsales\EshopCommunity\Internal\Framework\Database\QueryBuilderFactoryInterface;

class Payment extends Payment_parent
{
    public static function fcGetDeliveryIds()
    {
        $queryBuilder = ContainerFactory::getInstance()
            ->getContainer()
            ->get(QueryBuilderFactoryInterface::class)
            ->create();

        $result = $queryBuilder
            ->select('oxid')
            ->from('oxdeliveryset')
            ->execute();

        return $result->fetchAll()[0];
    }
}