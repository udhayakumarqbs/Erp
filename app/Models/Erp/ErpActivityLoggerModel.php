<?php

namespace App\Models\Erp;

use CodeIgniter\Model;

class ErpActivityLoggerModel extends Model
{
    protected $table      = 'erp_log';
    protected $primaryKey = 'log_id';

    protected $allowedFields = [
        'title',
        'log_text',
        'ref_link',
        'done_by',
        'created_at'
    ];
    protected $db;
    protected $session;
    public function __construct()
    {

        $this->db = \Config\Database::connect();
        $this->session = \Config\Services::session();
    }
    protected $useTimestamps = false;

    public function getAllData()
    {
        return $this->builder()->select('*')->get()->getResultArray();
    }

    public function getDtConfig()
    {
        $config = array(
            "columnNames" => ["sno", "log", "done by", "time stamp"],
            "sortable" => [0, 1, 1, 1],
        );
        return json_encode($config);
    }

    public function deleteAllData()
    {
        $query = "DELETE FROM erp_log";
        return $this->db->query($query);
    }
}
