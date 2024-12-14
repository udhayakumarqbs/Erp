<?php

namespace App\Controllers\Client;

use App\Controllers\BaseController;
use App\Models\Erp\CustomerContractModel;

class ContractController extends BaseController
{

    protected $customerContractModel;
    protected $data;
    public function __construct()
    {
        $this->customerContractModel = new CustomerContractModel();
    }
    public function index()
    {
        $data['pageFolder'] = "contract/index";
        $data['contracts'] = $this->customerContractModel->get_index_data();
        return view('client/index', $data);
    }

    public function contract(){
        return "hello dude";
    }
}