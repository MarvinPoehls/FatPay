<?php

namespace Fatchip\FatPay\Helper;

use OxidEsales\Eshop\Core\Config;
use OxidEsales\EshopCommunity\Internal\Container\ContainerFactory;
use OxidEsales\EshopCommunity\Internal\Framework\Module\Configuration\Bridge\ModuleSettingBridgeInterface;

class Curl
{
    public function sendDataToApi(array $data)
    {
        $config = oxNew(Config::class);

        $apiLocation = strtolower($this->getModuleSetting('fatPayApiLocation'));
        $url = $config->getShopUrl().$apiLocation."/index.php";

        $ch = curl_init($url);

        curl_setopt($ch, CURLOPT_POST, 1);

        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        return json_decode(curl_exec($ch));
    }

    protected function getModuleSetting($settingName)
    {
        $moduleSettingBridge = ContainerFactory::getInstance()
            ->getContainer()
            ->get(ModuleSettingBridgeInterface::class);
        return $moduleSettingBridge->get($settingName, 'fatpay');
    }
}