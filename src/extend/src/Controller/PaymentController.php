<?php

namespace Fatchip\FatPay\extend\src\Controller;

use Fatchip\FatPay\Helper\Curl;
use OxidEsales\Eshop\Application\Model\Order;
use OxidEsales\Eshop\Core\Config;
use OxidEsales\Eshop\Core\Registry;
use OxidEsales\Eshop\Core\Request;
use OxidEsales\Eshop\Core\ShopVersion;
use OxidEsales\EshopCommunity\Internal\Container\ContainerFactory;
use OxidEsales\EshopCommunity\Internal\Framework\Module\Configuration\Bridge\ModuleSettingBridgeInterface;
use OxidEsales\EshopCommunity\Internal\Framework\Module\Configuration\Bridge\ShopConfigurationDaoBridgeInterface;

class PaymentController extends PaymentController_parent
{
    public function init()
    {
        if (Registry::getSession()->getVariable('fcIsRedirected')) {
            $this->fcCancelOrder();
        }
        parent::init();
    }

    public function fcCancelOrder()
    {
        $order = oxNew(\Fatchip\FatPay\extend\src\Model\Order::class);
        $order->fcCancelCurrentOrder();
        Registry::getSession()->deleteVariable('fcIsRedirected');
    }

    public function validatePayment()
    {
        $paymentId = $this->fcGetRequestParameter('paymentid');

        if ($paymentId == 'oxidfatpay') {
            $data = $this->fcGetPaymentData();

            $response = $this->fcValidateToApi($data);

            if ($response['status'] == 'ERROR') {
                $this->fcHandleFatPayError($response['errormessage']);
                return null;
            }
        }
        return $this->fcParentValidatePayment();
    }

    protected function fcParentValidatePayment()
    {
        return parent::validatePayment();
    }

    protected function fcValidateToApi($data)
    {
        $config = oxNew(Config::class);

        $apiLocation = $this->fcGetModuleSetting('fatPayApiLocation');
        $url = rtrim($config->getShopUrl(), "/").$apiLocation."/index.php";

        $curl = oxNew(Curl::class);
        $curl->setUrl($url);
        $curl->setPostField($data);

        $return = $curl->execute();

        $curl->close();

        return json_decode($return, true);
    }

    protected function fcGetModuleSetting($settingName)
    {
        $moduleSettingBridge = ContainerFactory::getInstance()
            ->getContainer()
            ->get(ModuleSettingBridgeInterface::class);
        return $moduleSettingBridge->get($settingName, 'fatpay');
    }

    public function fcGetPaymentData()
    {
        $session = $this->getSession();
        $basket = $session->getBasket();
        $user = $this->getUser();

        if (!$user) {
            $session->setVariable('payerror', 2);
            return;
        }

        $order = oxNew(Order::class);
        $delAddress = $order->getDelAddressInfo();

        $data['shopsystem'] = 'Oxid';
        $data['shopversion'] = ShopVersion::getVersion();
        $data['payment_type'] = 'oxidfatpay';
        $data['moduleversion'] = $this->fcGetFatpayVersion();
        $data['language'] = Registry::getLang()->getLanguageAbbr();
        $data['billing_firstname'] = $user->oxuser__oxfname->value;
        $data['billing_lastname'] = $user->oxuser__oxlname->value;
        $data['billing_street'] = $user->oxuser__oxstreet->value." ".$user->oxuser__oxstreetnr->value;
        $data['billing_zip'] = $user->oxuser__oxzip->value;
        $data['billing_city'] = $user->oxuser__oxcity->value;
        $data['billing_country'] = $user->oxuser__oxcountry->value;
        $data['shipping_firstname'] = $delAddress ? $delAddress->oxaddress__oxfname->value : $user->oxuser__oxfname->value;
        $data['shipping_lastname'] = $delAddress ? $delAddress->oxaddress__oxlname->value : $user->oxuser__oxlname->value;
        $data['shipping_street'] = $delAddress ? $delAddress->oxaddress__oxstreet->value." ".$delAddress->oxaddress__oxstreetnr->value : $user->oxuser__oxstreet->value." ".$user->oxuser__oxstreetnr->value;
        $data['shipping_zip'] = $delAddress ? $delAddress->oxaddress__oxzip->value : $user->oxuser__oxzip->value;
        $data['shipping_city'] = $delAddress ? $delAddress->oxaddress__oxcity->value : $user->oxuser__oxcity->value;
        $data['shipping_country'] = $delAddress ? $delAddress->oxaddress__oxcountry->value : $user->oxuser__oxcountry->value;
        $data['email'] = $user->oxuser__oxusername->value;
        $data['customer_nr'] = $user->oxuser__oxcustnr->value;
        $data['order_nr'] = 'order_nr';
        $data['order_sum'] = $basket->getPriceForPayment();
        $data['currency'] = $basket->getBasketCurrency()->name;

        return $data;
    }

    protected function fcGetFatpayVersion()
    {
        $container = ContainerFactory::getInstance()->getContainer()->get(ShopConfigurationDaoBridgeInterface::class)->get();
        return $container->getModuleConfiguration('fatpay')->getVersion();
    }

    protected function fcGetRequestParameter($param)
    {
        $request = oxNew(Request::class);
        return $request->getRequestEscapedParameter($param);
    }

    protected function fcHandleFatPayError($errormessage)
    {
        $session = $this->getSession();
        $session->setVariable('payerror', 'fatpay_error');
        $session->setVariable('payerrortext', $errormessage);
    }
}