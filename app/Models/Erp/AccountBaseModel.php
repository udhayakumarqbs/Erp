<?php

namespace App\Models\Erp;

use CodeIgniter\Model;

class AccountBaseModel extends Model
{
    protected $table = 'accountbase';
    protected $primaryKey = 'base_id';
    protected $allowedFields = [
        'base_name',
        'general_name',
    ];

    public function get_account_bases()
    {
        $query = "SELECT base_id,base_name FROM accountbase ";
        $result = $this->db->query($query)->getResultArray();
        return $result;
    }

    private $cashflow = array(
        0 => "No effect on cash flow",
        1 => "Operating activity",
        2 => "Investing activity",
        3 => "Financing activity",
        4 => "Cash or cash equivalent"
    );
    public function get_cash_flow()
    {
        return $this->cashflow;
    }
}
