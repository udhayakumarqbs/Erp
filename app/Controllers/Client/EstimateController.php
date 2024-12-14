<?php

namespace App\Controllers\Client;

use App\Controllers\BaseController;
use App\Models\Erp\CustomerEstimateModel;

class EstimateController extends BaseController
{

    protected $customerEstimateModel;
    protected $data;
    public function __construct()
    {
        $this->customerEstimateModel = new CustomerEstimateModel();
    }
    public function index()
    {
        $data['pageFolder'] = "estimate/index";
        $data['estimates'] = $this->customerEstimateModel->get_index_data();
        return view('client/index', $data);
    }

    public function estimate(){
        
    }
}