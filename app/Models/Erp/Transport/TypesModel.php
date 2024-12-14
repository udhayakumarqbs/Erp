<?php

namespace App\Models\Erp\Transport;

use CodeIgniter\Model;

class TypesModel extends Model
{
    protected $table            = 'transport_type';
    protected $primaryKey       = 'type_id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'type_name',
    ];

    // Dates
    protected $useTimestamps = false;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'created_by';


    private $delivery_type = array(
        0 => "Inside",
        1 => "Outside"
    );

    private $delivery_type_bg = array(
        0 => "st_success",
        1 => "st_violet"
    );
    public function get_delivery_type()
    {
        return $this->delivery_type;
    }

    public function get_delivery_type_bg()
    {
        return $this->delivery_type_bg;
    }
    public function get_dtconfig_types()
    {
        $config = array(
            "columnNames" => ["sno", "type name", "action"],
            "sortable" => [0, 1, 0],
        );
        return json_encode($config);
    }
}
