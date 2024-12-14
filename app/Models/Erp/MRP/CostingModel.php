<?php

namespace App\Models\Erp\MRP;

use CodeIgniter\Model;

class CostingModel extends Model
{

    protected $table = "costing";

    protected $primaryKey = "costing_id";

    protected $useAutoIncrement = true;

    protected $returnType = 'array';

    protected $useSoftDeletes = false;

    protected $protectFields = true;

    protected $allowedFields = [
        'raw_material_cost',
        'operating_cost',
        'scrap_cost',
        'total_cost',
        'planning_id'
    ];



    public function getCostingData($planning_id)
    {
        return $this->builder()->select('*')->where('planning_id', $planning_id)->get()->getRow();
    }


    /**
     * Used To Retrive The Operation Cost
     * @param int $planning_id Planning Id
     * @param int $bom_id Bom Id
     * @return int|float
     */
    public function getOperationCost($planning_id, $bom_id){
        $result_1 = $this->db->table('bom')->select('operation_per_unit_cost')
            ->where('bom_id', $bom_id)->where('related_id',$planning_id)->get()->getRowArray()['operation_per_unit_cost'];

        if($result_1 && $result_1 != 0 || $result_1 != '0'){
            return $result_1;
        }else{
            $result_2 = $this->db->table('bom_operation_list')->select('amount')->where('bom_id',$bom_id)
                        ->get()->getResultArray();

            $total = 0;
        
            foreach($result_2 as $rec){
                $total += $rec['amount'];
            }

            return $total;
        }
    }

  



    //     public function insertCostingData($data)
// {
//     $builder = $this->db->table('costing'); // Specify the table
//     $builder->insert($data); // Insert the data
//     return $this->db->insertID(); // Return the ID of the inserted row
// }

    public function insertCostingData($data)
    {
        return $this->db->table('costing')->insert($data);
    }


    


}

