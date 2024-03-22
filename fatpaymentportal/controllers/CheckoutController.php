<?php

class CheckoutController extends BaseController
{
    private $view = "checkout";
    private $data;

    public function __construct()
    {
        $this->data = $this->getDataFromApi($_SESSION['id']);
    }

    public function getView()
    {
        return $this->view;
    }

    public function getData($key)
    {
        return $this->data->$key;
    }

    public function getDataFromApi(string $id)
    {
        $url = "http://".$_SERVER['HTTP_HOST'].strtok($_SERVER['REQUEST_URI'], '?')."/../../fatpayapi/index.php";

        $curl = curl_init($url);

        curl_setopt($curl, CURLOPT_POST, 1);

        curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query(['id' => $id]));

        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

        return json_decode(curl_exec($curl));
    }
}