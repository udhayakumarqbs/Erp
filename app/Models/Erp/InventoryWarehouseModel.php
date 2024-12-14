<?php

namespace App\Models\Erp;

use CodeIgniter\Model;

class InventoryWarehouseModel extends Model
{
    protected $table      = 'inventory_warehouse';
    protected $primaryKey = 'invent_house_id';

    protected $allowedFields = ['warehouse_id', 'related_to', 'related_id'];

    protected $useTimestamps = false;

    public $session;
    public $db;
    public function __construct()
    {
        $this->session = \Config\Services::session();
        $this->db = \Config\Database::connect();
    }


    
    public function get_raw_material_warehouse($raw_material_id)
    {
        $query = "SELECT warehouse_id FROM inventory_warehouse WHERE related_id=$raw_material_id AND related_to='raw_material' ";
        $result = $this->db->query($query)->getResultArray();
        return $result;
    }

    public function get_semi_finished_warehouse($semi_finished_id)
    {
        $query = "SELECT warehouse_id FROM inventory_warehouse WHERE related_id=$semi_finished_id AND related_to='semi_finished' ";
        $result = $this->db->query($query)->getResultArray();
        return $result;
    }
}
