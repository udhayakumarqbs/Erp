<?php

namespace App\Models\Erp\MRP;

use CodeIgniter\Model;


class WorkstationModel extends Model
{

    protected $table = 'workstation';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'name',
        'workstationtype_id',
        'warehose_id',
        'electricity_cost',
        'rent_cost',
        'consumable_cost',
        'wages_cost',
        'work_hour_start',
        'work_hour_end',
        'description',
        'status',
        'created_at',
        'updated_at'
    ];

    protected $useTimestamps = false;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    protected $db;
    public $session;
    public $logger;

    public $status = [
        0 => "Production",
        1 => "Off",
        2 => "Idle",
        3 => "Problem",
        4 => "Maintenance",
        5 => "Setup",
    ];

    private $status_bg = array(
        0 => "st_success",
        1 => "st_secondary",
        2 => "st_warning",
        3 => "st_danger",
        4 => "st_violet",
        5 => "st_primary",
    );

    public function __construct()
    {
        $this->db = \Config\Database::connect();
        $this->session = \Config\Services::session();
        $this->logger = \Config\Services::logger();
    }

    public function get_worstation_status()
    {
        return $this->status;
    }

    public function get_dtconfig_planning()
    {
        $config = array(
            "columnNames" => ["sno", "Name", "workstation type", "warehouse", "status", "created at"],
            "sortable" => [1, 1, 0, 0, 0, 0, 0],
            "filters" => ["status"]
        );

        return json_encode($config);
    }

    public function datatable()
    {
        $limit = $_GET['limit'];
        $offset = $_GET['offset'];
        $search = $_GET['search'] ?? "";
        $orderby = isset($_GET['orderby']) ? strtoupper($_GET['orderby']) : "";
        // $ordercol = isset($_GET['ordercol']) ? $columns[$_GET['ordercol']] : "";
        $where = true;
        $query = "SELECT w.id as id, w.name as name,wt.name as workstationtype,wh.name as warhouse,w.created_at as created_at,w.status as status FROM workstation as w JOIN workstationtype as wt ON wt.id = w.workstationtype_id JOIN warehouses as wh ON wh.warehouse_id = w.warehose_id ";
        $count = "SELECT COUNT(*) as total FROM workstation as w JOIN workstationtype as wt ON wt.id = w.workstationtype_id JOIN warehouses as wh ON wh.warehouse_id = w.warehose_id ";

        if (!empty($search)) {
            if (!$where) {
                $query .= " WHERE w.name  LIKE '%" . $search . "%'  OR  wt.name LIKE '%" . $search . "%' OR wh.name  LIKE '%" . $search . "%' ";
                $count .= " WHERE w.name  LIKE '%" . $search . "%'  OR  wt.name LIKE '%" . $search . "%' OR wh.name  LIKE '%" . $search . "%' ";
                $where = true;
            } else {
                $query .= " WHERE w.name  LIKE '%" . $search . "%'  OR  wt.name LIKE '%" . $search . "%' OR wh.name  LIKE '%" . $search . "%' ";
                $count .= " WHERE w.name  LIKE '%" . $search . "%'  OR  wt.name LIKE '%" . $search . "%' OR wh.name  LIKE '%" . $search . "%' ";
            }
        }

        $query .= " ORDER BY created_at DESC LIMIT $offset,$limit ";

        $result = $this->db->query($query)->getResultArray();
        $m_result = array();
        $sno = 1;

        foreach ($result as $r) {

            $action = '<div class="dropdown tableAction">
            <a type="button" class="dropBtn HoverA "><i class="fa fa-ellipsis-v"></i></a>
            <div class="dropdown_container">
                <ul class="BB_UL flex">';

            $action .= '<li><a href="' . url_to("erp.mrp.workstationedit.view", $r['id']) . '" title="Edit"  class="bg-success "><i class="fa fa-pencil"></i></a></li>';

            $action .= '<li><a href="' . url_to("erp.mrp.workstation.delete", $r['id']) . '" title="Delete" class="bg-danger del-confirm"><i class="fa fa-trash"></i> </a></li>
                </ul>
            </div>
            </div>';

            $status = "<span class='st " . $this->status_bg[$r['status']] . "'>" . $this->status[$r['status']] . " </span>";

            array_push(
                $m_result,
                array(
                    "",
                    $sno,
                    $r['name'],
                    $r['workstationtype'] ?? '-',
                    $r['warhouse'],
                    $status,
                    $r['created_at'],
                    $action,
                )
            );
            $sno++;
        }
        $response = array();
        $response['total_rows'] = $this->db->query($count)->getRow()->total;
        $response['data'] = $m_result;
        return $response;
    }

    public function Workstationadd($data)
    {

        if ($this->builder()->insert($data)) {
            return true;
        } else {
            return false;
        }

    }

    public function Workstationedit($id, $data)
    {
        if ($this->builder()->update($data,["id"=>$id])) {
            return true;
        } else {
            return false;

        }

    }

    public function remove($id){
        if($this->builder()->delete(["id"=> $id])){
            return  true;
        }else{
            return  false;
        }
    }
    public function get_workstation_data_by_id($workstation_id)
    {
        return $this->db->table("workstation")->join("workstationtype", "workstationtype.id = workstation.workstationtype_id")->select("workstation.*,workstationtype.name as workstationtype_name")->where("workstation.id", $workstation_id)->get()->getRowArray();
    }

}


?>