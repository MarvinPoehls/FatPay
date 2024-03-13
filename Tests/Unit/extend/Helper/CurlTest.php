<?php

use Fatchip\FatPay\Helper\Curl;

class CurlTest extends \OxidEsales\TestingLibrary\UnitTestCase
{
    public function testCurl()
    {
        $testArray = [
            'billing_lastname' => 'Failed'
        ];

        $curl = oxNew(Curl::class);
        $curl->setUrl(__DIR__ . '/curlTestResponse.php');
        $curl->setPostField($testArray);
        $response = $curl->execute();
        $curl->close();

        $this->assertTrue($response == false);
    }
}