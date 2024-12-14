<?php

namespace App\Models\Erp\MRP;

use CodeIgniter\Model;

class OperationModel extends Model
{

    protected $table = "bom_operation";

    protected $primaryKey = "id";

    protected $useAutoIncrement = true;

    protected $returnType = 'array';

    protected $useSoftDeletes = false;

    protected $protectFields = true;

    protected $allowedFields = [
        'name',
        'default_workstation_id',
        'is_corrective_operation',
        'description',
        'time',
        'hour_rate',
    ];


    protected $useTimestamps = true;

    protected $createdField = 'created_at';


    public function get_dtconfig_bom_operation()
    {
        $config = array(
            "columnNames" => ["sno", "Name", "workstation", "workstation type", "action"],
            "sortable" => [1, 1, 0, 1, 1, 0]
        );
        return json_encode($config);
    }

    public function get_all_workstation()
    {
        $query = "SELECT id,name,workstationtype_id FROM workstation";
        return $this->db->query($query)->getResultArray();
    }


    public function delete_operation($id)
    {
        $query = "delete workstation where id =".$id;
        return $this->db->query($query);
    }

    public function get_edit_page_data($id)
    {
        $query = "SELECT bom_operation.id, bom_operation.name as operation_name,
        workstation.id as workstation_id,
        workstation.name as workstation_name,
        bom_operation.is_corrective_operation,
        bom_operation.description FROM bom_operation 
        LEFT JOIN workstation ON bom_operation.default_workstation_id = workstation.id 
        WHERE bom_operation.id = ".$id;
        return $this->db->query($query)->getRowArray();
    }

   

    public function get_operation_datatable()
    {
        $columns = array(
            "bom_name" => "bom_operation.name",
            "workstation_name" => "workstation.name",
        );
        $limit = $_GET['limit'] ?? "";
        $offset = $_GET['offset'] ?? "";
        $search = $_GET['search'] ?? "";

        $orderby = isset($_GET['orderby']) ? strtoupper($_GET['orderby']) : "";
        $ordercol = isset($_GET['ordercol']) ? $columns[$_GET['ordercol']] : "";

        $query = "SELECT bom_operation.id, bom_operation.name as operation_name, bom_operation.is_corrective_operation, 
          bom_operation.created_at, workstation.name as workstation_name, workstationtype.name as workstationtype  FROM bom_operation 
          LEFT JOIN workstation ON bom_operation.default_workstation_id = workstation.id 
          LEFT JOIN workstationtype ON workstation.workstationtype_id = workstationtype.id ";
        $count = "SELECT COUNT(id) AS total FROM bom_operation";
        $where = false;


        if (!empty($search)) {
            if (!$where) {
                $query .= " WHERE ( bom_operation.name LIKE '%" . $search . "%')";
                $count .= " WHERE ( bom_operation.name LIKE '%" . $search . "%')";
                $where = true;
            } else {
                $query .= " AND ( workstation.name LIKE '%" . $search . "%')";
                $count .= " AND ( workstation.name LIKE '%" . $search . "%')";
            }
        }
        if (!empty($ordercol) && !empty($orderby)) {
            $query .= " ORDER BY " . $ordercol . " " . $orderby;
        }
        $query .= " LIMIT $offset,$limit ";

        $result = $this->db->query($query)->getResultArray();
        // $this->logger->error($result);
        $m_result = array();
        $sno = $offset + 1;
        foreach ($result as $r) {
            $action = '<div class="dropdown tableAction">
            <a type="button" class="dropBtn HoverA "><i class="fa fa-ellipsis-v"></i></a>
            <div class="dropdown_container">
                <ul class="BB_UL flex">
                    <li><a href="' . url_to('erp.mrp.operation.edit', $r['id']) . '" title="Edit" class="bg-success"><i class="fa fa-pencil"></i> </a></li>
                    <li><a href="' . url_to('erp.mrp.operation.delete', $r['id']) . '" title="Delete" class="bg-danger del-confirm"><i class="fa fa-trash"></i> </a></li>
                </ul>
            </div>
            </div>';
            array_push(
                $m_result,
                array(
                    $r['id'],
                    $sno,
                    $r['operation_name'],
                    $r['workstation_name'],
                    $r['workstationtype'],
                    $action
                )
            );
            $sno++;
        }
        $response = array();
        $response['total_rows'] = $this->db->query($count)->getRow()->total;
        $response['data'] = $m_result;
        // $this->logger->error($response);
        return $response;
    }

    public function getListExport($type)
    {
        $columns = array(
            "payment mode name" => "name"
        );
        $limit = $_GET['limit'];
        $offset = $_GET['offset'];
        $search = $_GET['search'] ?? "";
        $orderby = isset($_GET['orderby']) ? strtoupper($_GET['orderby']) : "";
        $ordercol = isset($_GET['ordercol']) ? $columns[$_GET['ordercol']] : "";

        $query = "SELECT service.code, service.name,
        CASE 
            WHEN service.priority=0 THEN 'Low'  
            WHEN service.priority=1 THEN 'Medium'  
            WHEN service.priority=2 THEN 'High'  
            WHEN service.priority=3 THEN 'Urgent'  
        END AS priority,
        erp_users.name AS assigned_to,
        CONCAT(employees.first_name, ' ', employees.last_name) as employee,
        CASE 
            WHEN service.status=0 THEN 'Not Started'  
            WHEN service.status=1 THEN 'Ongoing'  
            WHEN service.status=2 THEN 'Complete'  
            WHEN service.status=3 THEN 'Cancelled'  
        END AS status
    FROM service 
    JOIN erp_users ON service.assigned_to = erp_users.user_id 
    JOIN employees ON service.employee_id = employees.employee_id";

        $where = false;

        if (!empty($search)) {
            if (!$where) {
                $query .= " WHERE service.name LIKE '%" . $search . "%' ";
                $where = true;
            } else {
                $query .= " AND service.name LIKE '%" . $search . "%' ";
            }
        }
        if (!empty($ordercol) && !empty($orderby)) {
            $query .= " ORDER BY " . $ordercol . " " . $orderby;
        }
        $result = $this->db->query($query)->getResultArray();
        return $result;
    }


}

