<?php

class BaseController
{
    private $view;

    public function getRequestParameter($key, $default = false)
    {
        if (isset($_REQUEST[$key])) {
            return $_REQUEST[$key];
        }
        return $default;
    }

    public function render()
    {

    }

    public function getRedirectToStore()
    {
        return $_SESSION['redirect'];
    }

    public function getCancelRedirectToStore()
    {
        return $_SESSION['cancelRedirect'];
    }
}