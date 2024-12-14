<?php

namespace App\Models\Erp;

use CodeIgniter\Model;


class ContractTaskModel extends Model
{

    protected $table = 'contract_tasks';
    protected $primaryKey = 'task_id';
    protected $allowedFields = [
        'contract_id',
        'name',
        'status',
        'start_date',
        'due_date',
        'project',
        'priority',
        'assignees',
        'followers',
        'task_description',
        'created_by',
        'created_at'
    ];

    protected $db;
    protected $logger;
    private $taskPriority_status = array(
        0 => "Low",
        1 => "Medium",
        2 => "high",
        3 => "Urgent",
    );
    private $task_priority_bg = array(
        0 => "st_dark",
        1 => "st_primary",
        2 => "st_violet",
        3 => "st_danger"
    );
    private $task_status = array(
        0 => "Not Started",
        1 => "In progress",
        2 => "Testing",
        3 => "Complete",
    );
    private $task_status_bg = array(
        0 => "st_violet",
        1 => "st_primary",
        2 => "st_success",
        3 => "st_danger"
    );

    public function __construct()
    {
        $this->db = \Config\Database::connect();
        $this->logger = \Config\Services::logger();
    }

    public function task_add($data)
    {
        return $this->builder()->insert($data);
    }

    public function get_task_datatable($id)
    {
        $limit = $_GET["limit"];
        $offset = $_GET["offset"];
        $search = $_GET['search'] ?? "";

        $query = "SELECT a.task_id as id,a.name as name,a.status as status,a.start_date as start_date
        ,a.due_date as due_date,a.priority as priority, CONCAT(b.first_name,' ',b.last_name) as assigned FROM 
        contract_tasks as a 
        JOIN employees as b ON a.assignees = b.employee_id
        WHERE contract_id = $id ";

        $count = "SELECT * FROM contract_tasks as a JOIN employees as b ON a.assignees = b.employee_id
        WHERE contract_id = $id ";

        if (!empty($search)) {
            $query .= " AND ( a.name LIKE '%" . $search . "%') ";
            $count .= " AND ( a.name LIKE '%" . $search . "%') ";
        }


        $query .= " ORDER BY a.created_at DESC LIMIT $offset,$limit";
        $result = $this->db->query($query)->getResultArray();
        $r_result = array();

        $sno = $offset + 1;

        foreach ($result as $r) {
            $action = '<div class="dropdown tableAction">
            <a type="button" class="dropBtn HoverA "><i class="fa fa-ellipsis-v"></i></a>
            <div class="dropdown_container">
                <ul class="BB_UL flex justify-content-around">
                    <li><a href="#" onclick =update_task(' . $r["id"] . ') title="Edit" class="bg-success" ><i class="fa fa-pencil"></i> </a></li>
                    <li><a href="#" onclick = "delete_task(' . $r["id"] . ')" title="Delete" class="bg-danger del-confirm"><i class="fa fa-trash"></i> </a></li>
                </ul>
            </div>
           </div>';


            if ($r["priority"] == 0) {
                $r["priority"] = "<span class='st " . $this->task_priority_bg[$r['priority']] . "'>" . $this->taskPriority_status[$r['priority']] . " </span>";
            } elseif ($r["priority"] == 1) {
                $r["priority"] = "<span class='st " . $this->task_priority_bg[$r['priority']] . "'>" . $this->taskPriority_status[$r['priority']] . " </span>";
            } elseif ($r["priority"] == 2) {
                $r["priority"] = "<span class='st " . $this->task_priority_bg[$r['priority']] . "'>" . $this->taskPriority_status[$r['priority']] . " </span>";
            } else {
                $r["priority"] = "<span class='st " . $this->task_priority_bg[$r['priority']] . "'>" . $this->taskPriority_status[$r['priority']] . " </span>";
            }

            if ($r["status"] == 0) {
                $r["status"] = "<span class='st " . $this->task_status_bg[$r['status']] . "'>" . $this->task_status[$r['status']] . " </span>";
            } elseif ($r["status"] == 1) {
                $r["status"] = "<span class='st " . $this->task_status_bg[$r['status']] . "'>" . $this->task_status[$r['status']] . " </span>";
            } elseif ($r["status"] == 2) {
                $r["status"] = "<span class='st " . $this->task_status_bg[$r['status']] . "'>" . $this->task_status[$r['status']] . " </span>";
            } else {
                $r["status"] = "<span class='st " . $this->task_status_bg[$r['status']] . "'>" . $this->task_status[$r['status']] . " </span>";
            }

            array_push($r_result, array(
                "",
                $sno,
                $r["name"],
                $r["status"],
                $r["start_date"],
                $r["due_date"],
                $r["priority"],
                $r["assigned"],
                $action
            ));
            $sno++;
        }
        $response["data"] = $r_result;
        $response["total_rows"] = count($this->db->query($count)->getResultArray());

        return $response;
    }

    public function get_exist_data($id)
    {
        // $result =  $this->builder()->select()->where("task_id", $id)->get()->getRowArray();
        $query = "SELECT a.task_id as task_id,a.contract_id as contract_id,b.subject as contract_name,a.name as name,
        a.status as status,a.start_date as start_date,a.due_date as due_date,a.related_id as related_id,
        a.priority as priority,a.assignees as assignees,CONCAT(c.first_name,' ',c.last_name) as assigness_name ,
        a.followers as followers,CONCAT(d.first_name,' ',d.last_name) as Followers_name,a.task_description as task_description,
        a.created_by as created_by FROM contract_tasks as a
        JOIN  erpcontract as b ON a.related_id = b.contract_id
        JOIN  employees as c ON a.assignees =  c.employee_id 
        JOIN  employees as d ON a.followers =  d.employee_id 
        WHERE a.task_id = $id";

        $result = $this->db->query($query)->getRowArray();
        return $result;
    }
    public function contract_task_update($id, $data)
    {
        return $this->builder()->where("task_id", $id)->update($data);
    }
    public function task_delete($id)
    {
        return $this->builder()->where("task_id ", $id)->delete();
    }

    public function Contract_task_count($id)
    {
        $query = "SELECT COUNT(contract_id) as total FROM contract_tasks WHERE contract_id=$id";
        $total = $this->db->query($query)->getRow()->total;

        return $total;
    }

}
