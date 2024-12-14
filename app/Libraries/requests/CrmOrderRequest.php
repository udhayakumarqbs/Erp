<?php

namespace App\Libraries\Requests;

defined('BASEPATH') or exit('No direct script access allowed');

class CrmOrderRequest extends AbstractRequest
{
    public function __construct($attachments = false)
    {
        parent::__construct();
        $this->attachments = $attachments;
        $this->from_m = "CRM";
        $this->to_m = "Sales";
        $this->purpose = "Order Request";
    }

    public function createRequest()
    {
        return parent::createRequest();
    }
}
