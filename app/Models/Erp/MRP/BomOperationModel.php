<?php

namespace App\Models\Erp\MRP;
use CodeIgniter\Model;

class BomOperationModel extends Model
{
    protected $table = 'bom_operation_list';
    protected $primaryKey = 'id';
    protected $protectFields = true;
    protected $allowedFields = ['operation_id', 'per_hour_cost','bom_id', 'opertaion_time', 'amount', 'created_at'];
    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    protected $db;
    protected $logger;

    public function __construct()
    {
        $this->db = \Config\Database::connect();
        $this->logger = \Config\Services::logger();
    }

    public function get_bom_operation_items($bom_id){
        $result  = $this->table("bom_operation_list")->select("bom_operation_list.id as id,bom_operation_list.operation_id as op_id,bom_operation.name as op_name,workstation.name as w_name,bom_operation_list.per_hour_cost as per_cost,bom_operation_list.opertaion_time as op_time,bom_operation_list.amount as amount")->join("bom_operation","bom_operation.id = bom_operation_list.operation_id")->join("workstation","workstation.id = bom_operation.default_workstation_id")->where("bom_id",$bom_id)->get()->getResultArray();
        $m_array = array();
        foreach($result as $r){
            array_push($m_array,array(
                "id"=>$r["id"],
                "operation_name"=>$r["op_name"],
                "workstation_name" => $r["w_name"],
                "op_id"=>$r["op_id"],
                "per_hour_cost"=>$r["per_cost"],
                "opertaion_time"=>$r["op_time"],
                "amount"=>$r["amount"],
            ));
        }
        return $m_array;
    }


    public function totalMaterialCost($bom_id)
    {
        $query = "SELECT SUM(amount) AS total_material_cost FROM bom_items WHERE bom_id = ?";
        return $this->db->query($query, [$bom_id])->getRowArray();
    }
    


}