<?php

namespace App\Models\Erp;

use App\Models\Erp\Sale\EstimatesModel;
use App\Models\Erp\Sale\InvoiceModel;
use App\Models\Erp\ProjectsModel;
use CodeIgniter\Model;

class OverallClientModel extends Model
{
    protected $session;
    protected $db;

    protected $contractModel;
    protected $EstimatesModel;

    protected $project_model;

    public function __construct()
    {
        $this->contractModel = new ContractModel();
        $this->estimate_model = new EstimatesModel();
        $this->project_model = new ProjectsModel();
        $this->invoice_model = new InvoiceModel();
        $this->db = \Config\Database::connect();
        $this->session = \Config\Services::session();
    }

    public function getContractList()
    {
        return $this->contractModel->builder()->select("content,datestart,dateend,contract_value,signed")->where('client', session('client_cust_id'))->get()->getResultArray();
    }

    public function getEstimateList()
    {
        return $this->estimate_model->builder()->select("estimates.*, erp_users.name")->join('erp_users', 'estimates.created_by = erp_users.user_id', 'left')->where('estimates.cust_id', session('client_cust_id'))->get()->getResultArray();
    }


    public function getProjectList()
    {
        $status = $this->project_model->get_project_status();
        $status_bg = $this->project_model->get_project_status_bg();

        $query = $this->project_model->builder()
            ->select("project_id,name,related_to,start_date,end_date,type,status,budget")
            ->where('cust_id', session('client_cust_id'))->get()
            ->getResultArray();


        $m_result = array();

        foreach ($query as $r) {

            $s = "<span class='st " . $status_bg[$r['status']] . "'>" . $status[$r['status']] . " </span>";
            array_push($m_result, array(
                "project_id"=> $r["project_id"],
                "name"=>$r["name"],
                "related_to"=>$r["related_to"],
                "start_date"=>$r["start_date"],
                "end_date"=>$r["end_date"],
                "type"=>$r["type"],
                "status"=>$s,
                "budget"=>$r["budget"],
            ));

        }
    
        return $m_result;
    }

    public function getSpecificProjectList()
    {
        $query = $this->project_model->builder()
            ->select("project_id,name,related_to,start_date,end_date,type,status,budget")
            ->where('projects.cust_id', session('client_cust_id'))
            ->get()->getResultArray();
    }



    public function getInvoiceList()
    {
        $output = [];
        $data = $this->invoice_model->builder()->select("invoice_id,code,invoice_date,status,paid_till")->get()->getResultArray();
        $invoice_status = $this->invoice_model->get_invoice_status();
        foreach ($data as $dt) {
            $dt['status'] = $invoice_status[$dt['status']];
            array_push($output, $dt);
        }
        return $output;
    }



    public function get_count_by_status()
    {
        $status = $this->project_model->get_project_status();

        $query = $this->project_model->builder()->select("status,COUNT(status) as status_count")
            ->where('cust_id', session('client_cust_id'))
            ->groupBy("status")->get()->getResultArray();
        $newarray = array();
        foreach ($query as $key) {
            foreach ($status as $k => $v) {
                if ($k == $key["status"]) {
                    $newarray[$v] = $key["status_count"];
                }
            }
        }

        return $newarray;
    }







}