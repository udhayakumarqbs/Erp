<?php

namespace App\Models\Erp;

use CodeIgniter\Model;
class EmailTemplateModel extends Model {

    protected $table  = 'email_templates';

    protected $primaryKey = 'template_id';

    protected $allowedFields = [
        'name',
        'subject',
        'message',
        'status'
    ];

    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';


    public function __construct()
    {
        $this->logger = \Config\Services::logger();
        $this->db = \Config\Database::connect();
    }


    public function get_dtconfig_emailtemplate()
    {
        $config = array(
            "columnNames" => ["sno","name", "subject", "status", "action"],
            "sortable" => [1, 1, 0, 1, 0],
        );
        return json_encode($config);
    }

}