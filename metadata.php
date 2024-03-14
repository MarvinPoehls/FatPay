<?php

/**
 * Metadata version
 */

use Fatchip\FatPay\extend\src\Controller\PaymentController;
use Fatchip\FatPay\extend\src\Model\Payment;
use Fatchip\FatPay\extend\src\Controller\OrderController;
use Fatchip\FatPay\extend\src\Model\Order;
use Fatchip\FatPay\extend\src\Model\PaymentGateway;

$sMetadataVersion = '2.1';

/**
 * Module information
 */
$aModule = [
    'id'           => 'fatpay',
    'title'        => [
        'de' => 'FATPay OXID Payment-Plugin',
        'en' => 'FATPay OXID Payment-Plugin',
    ],
    'description'  => [
        'de' => 'Das FATPay Payment-Plugin für OXID',
        'en' => 'The FATPay Payment-Plugin for OXID',
    ],
    'thumbnail'    => 'FatPay.png',
    'version'      => '1.0',
    'author'       => 'Fatchip',
    'url'          => 'https://www.fatchip.de/',
    'email'        => 'support@fatchip.de',
    'controllers'  => [],
    'extend'       => [
        \OxidEsales\Eshop\Application\Controller\PaymentController::class => PaymentController::class,
        \OxidEsales\Eshop\Application\Controller\OrderController::class => OrderController::class,
        \OxidEsales\Eshop\Application\Model\Payment::class => Payment::class,
        \OxidEsales\Eshop\Application\Model\Order::class => Order::class,
        \OxidEsales\Eshop\Application\Model\PaymentGateway::class => PaymentGateway::class
    ],
    'blocks'       => [
        [
            'template' => 'page/checkout/inc/payment_other.tpl',
            'block'    => 'checkout_payment_longdesc',
            'file'     => 'fatpay_payment_other.tpl'
        ],
        [
            'template' => 'page/checkout/payment.tpl',
            'block'    => 'checkout_payment_errors',
            'file'     => 'fatpay_payment_checkout_payment_errors.tpl'
        ],
    ],
    'settings'     => [
        [
            'group' => 'SETTINGS_FATPAY',
            'name' => 'fatPayApiLocation',
            'type' => 'str',
            'value' => 'modules/Fatchip/FatPay/fatpayapi'
        ],
    ],
    'events'       => [
        'onActivate' => 'Fatchip\FatPay\Core\Events::onActivate',
        'onDeactivate' => 'Fatchip\FatPay\Core\Events::onDeactivate'
    ]
];
