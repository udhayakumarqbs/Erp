<?php

namespace App\Models\Erp\MRP;
use CodeIgniter\Model;

class BomscrapitemsModel extends Model
{
    protected $table = 'bom_scrap_items';
    protected $primaryKey = 'id';
    protected $protectFields = true;
    protected $allowedFields = ['item_id', 'quantity', 'unit_price', 'bom_id', 'amount', 'product_type', 'created_at'];
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

    public function get_bom_scrap_items($bom_id)
    {
        $result = $this->builder()->where("bom_id", $bom_id)->get()->getResultArray();
        $m_array = array();
        foreach ($result as $r) {
            $query = "";
            $name = "";
            if ($r["product_type"] == "rawmaterial") {
                $query = "select name from raw_materials where raw_material_id = ?";
                $row = $this->db->query($query, [$r['item_id']])->getRowArray();
                $name = $row["name"];
            } else {
                $query = "select name from semi_finished where semi_finished_id = ?";
                $row = $this->db->query($query, [$r['item_id']])->getRowArray();
                $name = $row["name"];
            }

            array_push($m_array, array(
                "product_type" => $r["product_type"],
                "item_id" => $r["item_id"],
                "name" => $name,
                "quantity" => $r["quantity"],
                "unit_price" => $r["unit_price"],
                "amount" => $r["amount"],
            ));
        }

        return $m_array;
    }


    public function totalScrapCost($bom_id)
    {
        $query = "SELECT SUM(amount) AS total_scrap_cost FROM bom_scrap_items WHERE bom_id = ?";
        return $this->db->query($query,[$bom_id])->getRowArray();
    }




}