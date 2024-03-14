<?php

class CheckoutController extends BaseController
{
    private $view = "checkout";

    public function getView()
    {
        return $this->view;
    }

    public function getCheckoutPrice()
    {
        if (!isset($_SESSION['checkoutPrice'])) {
            $_SESSION['checkoutPrice'] = 0;
        }

        return number_format((float)$_SESSION['checkoutPrice'], 2, '.', '')."€";
    }

    public function getData()
    {
        return $_SESSION['data'];
    }
}