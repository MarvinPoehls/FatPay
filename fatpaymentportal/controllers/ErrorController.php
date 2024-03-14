<?php

class ErrorController extends BaseController
{
    private $view = "error";

    public function getView()
    {
        return $this->view;
    }

    public function getErrorMessage()
    {
        return $this->getRequestParameter("errormessage");
    }
}