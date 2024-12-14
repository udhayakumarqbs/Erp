<?php

namespace App\Models\Erp;

use App\Models\Erp\Sale\EstimatesModel;
use CodeIgniter\Model;

class OverallClientModel extends Model
{
    protected $session;
    protected $db;

    protected $contractModel;
    protected $EstimatesModel;

    public function __construct()
    {
        $this->contractModel = new ContractModel();
        $this->estimate_model = new EstimatesModel();
        $this->db = \Config\Database::connect();
        $this->session = \Config\Services::session();
    }

    public function getContractList(){
        return $this->contractModel->builder()->select("content,datestart,dateend,contract_value,signed")->where('client',session('client_cust_id'))->get()->getResultArray();
    }

    public function getestimateList(){
        return $this->estimate_model->builder()->select("estimates.*, erp_users.name")->join('erp_users','estimates.created_by = erp_users.user_id','left')->where('estimates.cust_id',session('client_cust_id'))->get()->getResultArray();
    }




}