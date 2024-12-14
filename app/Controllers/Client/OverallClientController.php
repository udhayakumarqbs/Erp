<?php

namespace App\Controllers\Client;

use App\Controllers\BaseController;
use App\Models\Erp\OverallClientModel;


class OverallClientController extends BaseController
{

    protected $OverAllClientModel;
    protected $data;

    public function __construct()
    {
        $this->OverAllClientModel = new OverallClientModel();

    }

    public function contractList()
    {
        $data['pageFolder'] = "contract/index";
        $data['contracts'] = $this->OverAllClientModel->getContractList();
        return view('client/index', $data);
    }

    public function estimateList()
    {
        $data['pageFolder'] = "estimate/index";
        $data['estimates'] = $this->OverAllClientModel->getEstimateList();
        return view('client/index', $data);
    }

    public function projectList()
    {
        $data['pageFolder'] = "project/index";
        $data['projects'] = $this->OverAllClientModel->getProjectList();
        $data["projectSummary"] = $this->OverAllClientModel->get_count_by_status();
        return view('client/index', $data);
    }

    public function projectListView()
    {
        $data['pageFolder'] = "project/view";
        $data['projects_overview'] = $this->OverAllClientModel->getSpecificProjectList();
        return view('client/index', $data);
    }




    public function invoiceList()
    {
        $data['pageFolder'] = "invoice/index";
        $data['invoices'] = $this->OverAllClientModel->getInvoiceList();
        
        return view('client/index', $data);
    }


}