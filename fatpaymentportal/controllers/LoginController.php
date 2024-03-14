<?php

class LoginController extends BaseController
{
    private $view = "login";
    private $alert = "Please enter your birthday.";

    public function getView()
    {
         return $this->view;
    }

    public function getAlert()
    {
        return $this->alert;
    }

    public function getRedirect($controller)
    {
        return "http://".$_SERVER['HTTP_HOST']."/fatpaymentportal/index.php?controller=".$controller;
    }

    public function validateLogin()
    {
        if (!$this->getRequestParameter('birthday')) {
            $this->alert = "Please enter your birthday to proceed with your order.";
            return;
        }

        $birthday = $this->getRequestParameter('birthday');
        $birthday = new DateTime($birthday);
        $currentDate = new DateTime();
        $ageInterval = $birthday->diff($currentDate);
        $age = $ageInterval->y;

        if ($age < 18) {
            $this->alert = "You have to be 18 years or older to use FATRedirect.";
            return;
        }

        $_SESSION['lastLogin'] = time();
        header("Location: ".$this->getRedirect("checkout"));
    }

    public function getBirthday()
    {
        $birthday = $this->getRequestParameter('birthday');
        if ($birthday) {
            return $birthday;
        }
        return "";
    }
}