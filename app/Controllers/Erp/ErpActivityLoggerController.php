<?php

namespace App\Controllers\Erp;

use App\Controllers\BaseController;

use App\Models\Erp\ErpActivityLoggerModel;

use CodeIgniter\HTTP\Exceptions\HTTPException;

class ErpActivityLoggerController extends BaseController
{
    protected $data;
    protected $db;
    protected $session;
    protected $ErpActivityLoggerModel;
   

    public function __construct()
    {
        $this->ErpActivityLoggerModel = new ErpActivityLoggerModel();
        $this->db = \Config\Database::connect();
        $this->session = \Config\Services::session();
        helper(['erp', 'form']);
    }

    public function ListLogger(){
        $this->data['allData'] = $this->ErpActivityLoggerModel->getAllData();
        $this->data['menu'] = "setting";
        $this->data['submenu'] = "activitylog";
        $this->data['page'] = "setting/activitylog";
        return view("erp/index", $this->data);
    }

    public function addLogger(){
        $this->data['allData'] = $this->ErpActivityLoggerModel->getAllData();

        if($this->ErpActivityLoggerModel->save()){
        }else{
        }

        $this->data['menu'] = "setting";
        $this->data['submenu'] = "activitylog";
        $this->data['page'] = "setting/activitylog";
        return view("erp/index", $this->data);
    }


}