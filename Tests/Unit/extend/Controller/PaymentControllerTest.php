<?php

use Fatchip\FatPay\extend\src\Controller\PaymentController;
use Fatchip\FatPay\Helper\Curl;
use OxidEsales\Eshop\Core\Session;
use OxidEsales\Eshop\Core\UtilsObject;

class PaymentControllerTest extends \OxidEsales\TestingLibrary\UnitTestCase
{
    private $paymentController;
    private $curl;

    public function setUp(): void
    {
        $this->paymentController = $this->getMockBuilder(PaymentController::class)
            ->setMethods(['fcGetRequestParameter', 'fcGetPaymentData', 'fcParentValidatePayment', 'getSession', 'getUser'])
            ->getMock();

        $this->curl = $this->getMockBuilder(Curl::class)
            ->setMethods(['setUrl', 'setPostField', 'execute', 'close'])
            ->getMock();
    }

    public function testValidatePaymentWithValidateToApiReturnsApproved()
    {
        $this->paymentController->expects($this->once())
            ->method('fcGetRequestParameter')
            ->with('paymentid')
            ->willReturn('oxidfatpay');

        $this->paymentController->expects($this->once())
            ->method('fcGetPaymentData')
            ->willReturn([]);

        $this->paymentController->expects($this->once())
            ->method('fcParentValidatePayment')
            ->willReturn("order");

        $this->curl->expects($this->once())
            ->method('setUrl');

        $this->curl->expects($this->once())
            ->method('setPostField');

        $this->curl->expects($this->once())
            ->method('close');

        $this->curl->expects($this->once())
            ->method('execute')
            ->willReturn(['status' => 'APPROVED']);

        UtilsObject::setClassInstance(Curl::class, $this->curl);

        $this->assertEquals("order", $this->paymentController->validatePayment());
    }

    public function testValidatePaymentWithValidateToApiReturnsError()
    {
        $this->paymentController->expects($this->once())
            ->method('fcGetRequestParameter')
            ->with('paymentid')
            ->willReturn('oxidfatpay');

        $this->paymentController->expects($this->once())
            ->method('fcGetPaymentData')
            ->willReturn([]);

        $this->paymentController->expects($this->never())
            ->method('fcParentValidatePayment');

        $this->paymentController->expects($this->once())
            ->method('getSession')
            ->willReturn(oxNew(Session::class));

        $this->curl->expects($this->once())
            ->method('setUrl');

        $this->curl->expects($this->once())
            ->method('setPostField');

        $this->curl->expects($this->once())
            ->method('close');

        $this->curl->expects($this->once())
            ->method('execute')
            ->willReturn(json_encode(['status' => 'ERROR']));

        UtilsObject::setClassInstance(Curl::class, $this->curl);

        $this->assertEquals(null, $this->paymentController->validatePayment());
    }

    public function testGetPaymentData()
    {
        $paymentController = $this->getMockBuilder(PaymentController::class)
            ->setMethods(['getUser'])
            ->getMock();

        $paymentController->expects($this->once())
            ->method('getUser')
            ->willReturn(oxNew(\OxidEsales\Eshop\Application\Model\User::class));

        $paymentController->fcGetPaymentData();
    }
}