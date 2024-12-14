<?php
namespace App\Models\Erp;

use CodeIgniter\Model;


class ContractRenewelModel extends Model{
    protected $table ="contract_renewals";
    protected $primarykey = "id";
    protected $allowedFields = [
        "contractid",
        "old_start_date",
        "new_start_date",
        "old_end_date",
        "new_end_date",
        "old_value",
        "new_value",
        "date_renewed",
        "renewed_by",
        "renewed_by_staff_id",
        "is_on_old_expiry_notified",
    ];
    protected $db;
 
    public function __construct()
    {
        $this->db = \Config\Database::connect();
    }

    public function renewal_add($data){
        if($this->builder()->insert($data)){
            return true;
        }
        else{
            return false;
        }
    }

    public function get_exist_data($id){
        $query = "SELECT a.id as id,a.contractid as c_id,b.name as f_name,b.last_name as l_name,a.new_start_date as s_date,a.new_end_date as e_date,a.date_renewed as r_date
        FROM contract_renewals as a 
        JOIN erp_users as b ON b.user_id = a.renewed_by_staff_id 
        WHERE a.contractid = $id
        ORDER BY a.date_renewed DESC";
        $result = $this->db->query($query)->getResultArray();
        return $result;  
    }
    public function renewal_delete($id){
        if($this->builder()->where("id ",$id)->delete()){
            return true;
        }
        else{
            return false;
        }
    }

}
?>