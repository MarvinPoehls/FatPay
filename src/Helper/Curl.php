<?php

namespace Fatchip\FatPay\Helper;

class Curl
{
    protected $ch;

    public function __construct($url = null)
    {
        $this->ch = curl_init($url);
        curl_setopt($this->ch, CURLOPT_POST, 1);
    }

    public function setUrl($url)
    {
        curl_setopt($this->ch, CURLOPT_URL, $url);
    }

    public function setPostField($array)
    {
        curl_setopt($this->ch, CURLOPT_POSTFIELDS, http_build_query($array));
    }

    public function execute()
    {
        curl_setopt($this->ch, CURLOPT_RETURNTRANSFER, true);
        return curl_exec($this->ch);
    }

    public function close()
    {
        curl_close($this->ch);
    }
}