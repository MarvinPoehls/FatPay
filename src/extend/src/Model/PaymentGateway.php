<?php

namespace Fatchip\FatPay\extend\src\Model;

use OxidEsales\Eshop\Application\Model\Order;
use OxidEsales\Eshop\Core\Registry;
use OxidEsales\Eshop\Core\ShopVersion;
use OxidEsales\EshopCommunity\Internal\Container\ContainerFactory;
use OxidEsales\EshopCommunity\Internal\Framework\Module\Configuration\Bridge\ShopConfigurationDaoBridgeInterface;

class PaymentGateway extends PaymentGateway_parent
{
    public function executePayment($amount,Order &$order)
    {
        if ($order->oxorder__oxpaymenttype->value != "oxidfatredirect") {
            return parent::executePayment($amount, $order);
        }
        return $this->handleFatRedirectPayment($amount, $order);
    }

    protected function handleFatRedirectPayment($amount, Order &$order)
    {
        if (!$order->fcIsReturnAfterPayment()) {
            $order->fcSetOrderNumber();
            $order->fcSetTransactionId();

            $this->fcRedirectToPaymentPortal($amount);
        }
        return true;
    }

    protected function fcRedirectToPaymentPortal($amount)
    {
        $paymentPortalLocation = "/modules/Fatchip/FatPay";
        
        $redirect = $this->fcGetUrl("order", $this->fcGetAdditionalParameters(), true);
        $cancelRedirect = $this->fcGetUrl("payment", ["fnc" => "fcCancelOrder"], true);

        Registry::getSession()->setVariable('fcIsRedirected', true);
        Registry::getSession()->setVariable('fcBasketPrice', Registry::getSession()->getBasket()->getPrice());
        Registry::getUtils()->redirect("$paymentPortalLocation/fatpaymentportal/index.php?checkoutPrice=$amount&redirect=$redirect&cancelRedirect=$cancelRedirect&data=".json_encode($this->fcGetPaymentData()), false);
    }

    protected function fcGetAdditionalParameters(): array
    {
        $session = Registry::getSession();
        $request = Registry::getRequest();

        $additionalParameters = [];

        $stoken = $request->getRequestEscapedParameter('stoken');
        if (!$stoken) {
            $stoken = $session->getSessionChallengeToken();
        }

        $additionalParameters['stoken'] = $stoken;
        $additionalParameters['sDeliveryAddressMD5'] = $request->getRequestEscapedParameter('sDeliveryAddressMD5');
        $additionalParameters['oxdownloadableproductsagreement'] = $request->getRequestEscapedParameter('oxdownloadableproductsagreement');
        $additionalParameters['oxserviceproductsagreement'] = $request->getRequestEscapedParameter('oxserviceproductsagreement');
        $additionalParameters['ord_agb'] = true;
        $additionalParameters['rtoken'] = $session->getRemoteAccessToken();

        $additionalParameters['fnc'] = "fcHandlePaymentPortalReturn";

        return $additionalParameters;
    }

    public function fcGetPaymentData()
    {
        $session = Registry::getSession();
        $basket = $session->getBasket();
        $user = Registry::getConfig()->getUser();

        if (!$user) {
            $session->setVariable('payerror', 2);
            return;
        }

        $order = oxNew(Order::class);
        $order->load(Registry::getSession()->getVariable('sess_challenge'));
        $delAddress = $order->getDelAddressInfo();

        $data['shopsystem'] = 'Oxid';
        $data['shopversion'] = ShopVersion::getVersion();
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
        $data['order_nr'] = $order->oxorder__oxordernr->value;
        $data['order_sum'] = $basket->getPriceForPayment();
        $data['currency'] = $basket->getBasketCurrency()->name;
        $data['payment_type'] = 'oxidfatredirect';

        return $data;
    }

    protected function fcGetFatpayVersion()
    {
        $container = ContainerFactory::getInstance()->getContainer()->get(ShopConfigurationDaoBridgeInterface::class)->get();
        return $container->getModuleConfiguration('fatpay')->getVersion();
    }

    private function fcGetUrl($cl, $params = [], $encode = false): string
    {
        $url = str_replace("redirected=1","",(empty($_SERVER['HTTPS']) ? 'http' : 'https') . "://$_SERVER[HTTP_HOST]/index.php?cl=$cl&");
        $url .= http_build_query($params);

        if ($encode) {
            $url = rawurlencode($url);
        }
        return $url;
    }
}
