<?php

namespace App\Models\Erp;

use CodeIgniter\Model;

class ErpCompanyInformationModel extends Model
{
    protected $table = 'erp_companyinfo';
    protected $primaryKey = 'company_id';
    protected $allowedFields = [
        'company_name', 
        'company_logo',
        'address',
        'city',
        'state',
        'country',
        'zipcode',
        'phone_number',
        'vat_number',
        'license_number',
        'created_at',
        'updated_at'
];

    protected $db;

    public function getAllData(){
        return $this->builder()->select('*')->get()->getResultArray()[0];
    }

    public function company_name(){
        return $this->builder()->select('company_name')->get()->getRow();
    }

    public function updateCompanyData($data)
    {
        $builder = $this->db->table('erp_companyinfo');
        $builder->set($data);
        $builder->update();
        return true;
    }

    public function deleteLogo()
    {
        $row = $this->builder()->select("company_logo")->get()->getRow();
        if (!empty($row) && !empty($row->company_logo)) {
            @unlink(FCPATH . "assets/images/" . $row->company_logo);
        }
    }   
    
}

