<?php

namespace FatPayApi\Controller;

use FatPayApi\Config;
use FatPayApi\TransactionGateway;

class TransactionController
{
    protected TransactionGateway $gateway;

    public function __construct($gateway)
    {
        $this->gateway = $gateway;
    }

    public function processRequest()
    {
        if (isset($_REQUEST['id'])) {
            $return = $this->getData($_REQUEST['id']);
        } else {
            $return = $this->saveData($_REQUEST);
        }

        echo json_encode($return);
        exit;
    }

    protected function saveData(array $data)
    {
        if (!isset($data["billing_lastname"])) {
            $errorMessage = "No billing lastname provided.";
            return ["status" => "ERROR", "errormessage" => $errorMessage];
        }

        if ($data["billing_lastname"] === "Failed") {
            $data["status"] = "ERROR";
            $data["errormessage"] = "Lastname is 'Failed'.";
        } else {
            $data["status"] = $this->getSuccessResponse($data['payment_type']);
            $data["errormessage"] = null;
        }

        $id = $this->gateway->create($data);

        return ["status" => $data['status'], "errormessage" => $data['errormessage'], "id" => $id];
    }

    protected function getData(string $id)
    {
        return $this->gateway->get($id);
    }

    public function getSuccessResponse($type)
    {
        switch ($type) {
            case Config::FATREDIRECT:
                return "REDIRECT";
            case Config::FATPAY:
            default:
                return "APPROVED";
        }
    }
}