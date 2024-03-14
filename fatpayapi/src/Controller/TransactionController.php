<?php

namespace Src\Controller;

class TransactionController
{
    protected $gateway;

    public function __construct($gateway)
    {
        $this->gateway = $gateway;
    }

    public function processRequest()
    {
        $data = $_REQUEST;

        switch ($data['payment_type']) {
            case 'oxidfatpay':
                $this->processFatpayRequest($data);
                break;
            case 'oxidfatredirect':
                $this->processFatRedirectRequest($data);
                break;
            default:
        }
    }

    protected function processFatpayRequest($data)
    {
        if (!array_key_exists("billing_lastname", $data)) {
            echo json_encode([
                "status" => "ERROR",
                "errormessage" => "No lastname given."
            ]);
            exit;
        }

        if ($data["billing_lastname"] === "Failed") {
            $data["status"] = "ERROR";
            $data["errormessage"] = "Lastname is 'Failed'.";
        }
        else
        {
            $data["status"] = "APPROVED";
            $data['errormessage'] = null;
        }

        $id = $this->gateway->create($data);

        echo json_encode([
            "status" => $data['status'],
            "errormessage" => $data['errormessage'],
            "id" => $id
        ]);
    }

    protected function processFatredirectRequest($data)
    {
        if (!isset($data["billing_lastname"])) {
            echo json_encode([
                "status" => "ERROR",
                "errormessage" => "No lastname given."
            ]);
            exit;
        }

        if ($data["billing_lastname"] === "Failed") {
            $data["status"] = "ERROR";
            $data["errormessage"] = "Lastname is 'Failed'.";
        }
        else
        {
            $data["status"] = "REDIRECT";
            $data['errormessage'] = null;
        }

        $id = $this->gateway->create($data);

        echo json_encode([
            "status" => $data['status'],
            "errormessage" => $data['errormessage'],
            "id" => $id,
        ]);
    }
}