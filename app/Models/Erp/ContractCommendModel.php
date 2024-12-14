<?php
namespace App\Models\Erp;

use CodeIgniter\Model;


class ContractCommendModel extends Model{
    protected $table ="contract_commend";
    protected $primarykey = "discussion_id";
    protected $allowedFields = [
        "dicussion_note",
        "contract_id",
        "user_id",
        "created_at"
    ];
    protected $db;
 
    public function __construct()
    {
        $this->db = \Config\Database::connect();
    }

    public function insert_commend($data){
        if($this->builder()->insert($data)){
            return true;
        }
        else{
            return false;
        }
    }
    public function fetch_contract_commend($id){
        $query = "SELECT a.discussion_id as id ,c.name as f_name,c.last_name as l_name ,a.created_at as c_date,a.dicussion_note as commend 
                  FROM contract_commend AS a
                  JOIN erpcontract AS b ON a.contract_id = b.contracT_id
                  JOIN erp_users AS c ON c.user_id = a.user_id 
                  WHERE a.contract_id = $id
                  ORDER BY a.created_at DESC";
        return $this->db->query($query)->getResultArray();
    }

    public function update_commend($comment_id,$data){
       
        return $this->builder()->where("discussion_id",$comment_id)->update($data);
    }

    public function commend_delete($id){
        return $this->builder()->where("discussion_id",$id)->delete();
    }
}
?>