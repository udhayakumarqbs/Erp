<?php

namespace App\Libraries;

use App\Libraries\Requests\CrmOrderRequest;
use App\Libraries\Requests\DummyRequest;

class Requestor
{
    private $requestor;
    private $ci;

    public function __construct($basename = "")
    {
        $this->ci = service('request');

        $this->getRequestor($basename);
    }

    private function getRequestor($basename)
    {
        $this->requestor = new DummyRequest();

        switch ($basename) {
            case "orderrequest":
                $this->requestor = new CrmOrderRequest();
                break;
            default:
                $this->requestor = new DummyRequest();
                break;
        }
    }

    public function getMaxFileSize()
    {
        return $this->requestor->getMaxFileSize();
    }

    public function getAllowedFileTypes()
    {
        return $this->requestor->getAllowedFileTypes();
    }

    public function getRequestTypes($modulename)
    {
        $types = [];

        switch ($modulename) {
            case "CRM":
                $types = [
                    "orderrequest" => "Order Request",
                    "supportrequest" => "Support Request"
                ];
                break;
            case "Sales":
                $types = [
                    "itemcheckrequest" => "Item Check Request",
                    "itemproducerequest" => "Item Product Request",
                    "supportrequest" => "Support Request"
                ];
            default:
                break;
        }

        return $types;
    }

    public function getRequestStatus()
    {
        return [
            0 => "Pending",
            1 => "Read",
            2 => "Processing",
            3 => "Responded",
            4 => "Closed"
        ];
    }

    public function request($attachment = false)
    {
        $request_created = false;

        if (isset($this->requestor)) {
            $this->requestor->setAttachments($attachment);
            $request_created = $this->requestor->createRequest();
        }

        return $request_created;
    }
}
