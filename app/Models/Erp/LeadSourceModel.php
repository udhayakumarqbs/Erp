<?php

namespace App\Models\Erp;

use CodeIgniter\Model;

class LeadSourceModel extends Model
{
    protected $table = 'lead_source';
    protected $primaryKey = 'source_id';
    protected $allowedFields = ['source_name', 'marketing_id'];
    protected $useTimestamps = false;

    public function get_lead_source()
    {
        $query = "SELECT source_id,source_name FROM lead_source";
        $result = $this->db->query($query)->getResultArray();
        return $result;
    }
}
