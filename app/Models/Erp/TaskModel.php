<?php

namespace App\Models\Erp;

use CodeIgniter\Model;
use DateTime;
use Faker\Core\DateTime as CoreDateTime;

class TaskModel extends Model
{

    protected $table = 'tasks';
    protected $primaryKey = 'task_id';
    protected $allowedFields = [

        'name',
        'start_date',
        'due_date',
        'related_to',
        'project',
        'priority',
        'assignees',
        'followers',
        'task_description',
        'created_by'
    ];

    // protected $useTimestamps = false;
    // protected $createdField  = 'created_at';
    // protected $updatedField  = 'updated_at';

    protected $session;
    protected $db;
    protected $logger;
    public function __construct()
    {
        $this->session = \Config\Services::session();
        $this->db = \Config\Database::connect();
        $this->logger = \Config\Services::logger();
    }

        private $task_status = array(
            0 => "Not Started",
            1 => "In progress",
            2 => "Testing",
            3 => "Complete",    
        );

    private $taskPriority_status = array(
        0 => "Low",
        1 => "Medium",
        2 => "high",
        3 => "Urgent",
    );

    private $task_status_bg = array(
        0 => "st_violet",
        1 => "st_primary",
        2 => "st_success",
        3 => "st_danger"
    );

    private $taskRelated_status = array(
        "lead",
        "project",
        "invoice",
        "customer",
        "estimates",
        "contract",
        "ticket",
        "quotation",
    );
    private $task_priority_bg = array(
        0 => "st_dark",
        1 => "st_primary",
        2 => "st_violet",
        3 => "st_danger"
    );

    public function get_task_priority_bg()
    {
        return $this->task_priority_bg;
    }
    public function get_taskRelated_status()
    {
        return $this->taskRelated_status;
    }

    public function get_task_status()
    {
        return $this->task_status;
    }
    public function get_taskpriority_status()
    {
        return $this->taskPriority_status;
    }
    public function get_task_status_bg()
    {
        return $this->task_status_bg;
    }
    public function get_dtconfig_task()
    {
        $config = array(
            "columnNames" => ["sno", "name", "status", "start date", "due date", "priority", "assigned to", "action"],
            "sortable" => [0, 1, 0, 1, 0, 1, 1, 0],
            "filters" => ["priority", "status"]
        );
        return json_encode($config);
    }

    public function get_task_datatable()
    {
        $columns = array(
            "name" => "name",
            "status" => "tasks.status",
            "start date" => "tasks.start_date",
            "due date" => "tasks.due_date",
            "priority" => "priority",
            "assigned to" => "erp_users.name",
        );
        $limit = $_GET['limit'] ?? " ";
        $offset = $_GET['offset'] ?? " ";
        $search = $_GET['search'] ?? " ";

        $orderby = isset($_GET['orderby']) ? strtoupper($_GET['orderby']) : "";
        $ordercol = isset($_GET['ordercol']) ? $columns[$_GET['ordercol']] : "";

        $query = "SELECT tasks.task_id,tasks.name,tasks.status,tasks.start_date,tasks.due_date,tasks.priority,erp_users.name AS assigned FROM tasks JOIN erp_users ON tasks.assignees=erp_users.user_id";
        $count = "SELECT COUNT(task_id) AS total FROM tasks JOIN erp_users ON tasks.assignees=erp_users.user_id";
        $where = false;

        $filter_1_col = isset($_GET['priority']) ? $columns['priority'] : "";
        $filter_1_val = $_GET['priority'];

        $filter_2_col = isset($_GET['status']) ? $columns['status'] : "";
        $filter_2_val = $_GET['status'];

        if (!empty($filter_1_col) && $filter_1_val !== "") {
            $query .= " WHERE " . $filter_1_col . "=" . $filter_1_val . " ";
            $count .= " WHERE " . $filter_1_col . "=" . $filter_1_val . " ";
            $where = true;
        }

        if (!empty($filter_2_col) && $filter_2_val !== "") {
            if (!$where) {
                $query .= " WHERE " . $filter_2_col . "=" . $filter_2_val . " ";
                $count .= " WHERE " . $filter_2_col . "=" . $filter_2_val . " ";
                $where = true;
            } else {
                $query .= " AND " . $filter_2_col . "=" . $filter_2_val . " ";
                $count .= " AND " . $filter_2_col . "=" . $filter_2_val . " ";
            }
        }

        if (!empty($search)) {
            if (!$where) {
                $query .= " WHERE ( tasks.name LIKE '%" . $search . "%' OR erp_users.name LIKE '%" . $search . "%' )";
                $count .= " WHERE ( tasks.name LIKE '%" . $search . "%' OR erp_users.name LIKE '%" . $search . "%' )";
                $where = true;
            } else {
                $query .= " AND ( tasks.name LIKE '%" . $search . "%' OR erp_users.name LIKE '%" . $search . "%' )";
                $count .= " AND ( tasks.name LIKE '%" . $search . "%' OR erp_users.name LIKE '%" . $search . "%' )";
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
                                <li><a href="' . url_to('erp.crm.taskview', $r['task_id']) . '" title="View" class="bg-warning"><i class="fa fa-eye"></i> </a></li>
                                <li><a href="' . url_to('erp.crm.taskedit', $r['task_id']) . '" title="Edit" class="bg-success"><i class="fa fa-pencil"></i> </a></li>
                                <li><a href="' . url_to('erp.crm.taskdelete', $r['task_id']) . '" title="Delete" class="bg-danger del-confirm"><i class="fa fa-trash"></i> </a></li>
                            </ul>
                        </div>
                    </div>';
            $priority = "<span class='st " . $this->task_status_bg[$r['priority']] . "'>" . $this->taskPriority_status[$r['priority']] . " </span>";
            $status = "<span class='st " . $this->task_status_bg[$r['status']] . "'>" . $this->task_status[$r['status']] . " </span>";
            array_push(
                $m_result,
                array(
                    $r['task_id'],
                    $sno,
                    $r['name'],
                    $status,
                    $r['start_date'],
                    $r['due_date'],
                    $priority,
                    $r['assigned'],
                    $action
                )
            );
            $sno++;
        }
        $response = array();
        $response['total_rows'] = $this->db->query($count)->getRow()->total;
        $response['data'] = $m_result;
        return $response;
    }

    public function insert_task($post)
    {

        $inserted = false;
        $name = $post["name"];
        $status = $post["status"];
        $start_date = $post["start_date"];
        $due_date = $post["due_date"];
        $related_to = $post["related_to"];
        $related_id = $post["related_id"];
        $priority = $post["priority"];
        $assignees = $post["assignees"];
        $followers = $post["followers"];
        $task_description = $post["task_description"];
        $created_by = get_user_id();


        $query = "INSERT INTO tasks(name,status,start_date,due_date,related_to,related_id,priority,assignees,followers,task_description,created_by) VALUES(?,?,?,?,?,?,?,?,?,?,?)";
        $this->db->query($query, array($name, $status, $start_date, $due_date, $related_to, $related_id, $priority, $assignees, $followers, $task_description, $created_by));

        $config['title'] = "Task Insert";
        if (!empty($this->db->insertId())) {
            $inserted = true;
            $config['log_text'] = "[ Task successfully created ]";
            $config['ref_link'] = 'erp/crm/task';
            $this->session->setFlashdata("op_success", "Task successfully created");
        } else {
            $config['log_text'] = "[ Task failed to create ]";
            $config['ref_link'] = 'erp/crm/task';
            $this->session->setFlashdata("op_error", "Task failed to create");
        }
        log_activity($config);
        return $inserted;
    }

    public function update_task($task_id, $post)
    {
        $updated = false;
        $name = $post["name"];
        $start_date = $post["start_date"];
        $due_date = $post["due_date"];
        $related_to = $post["related_to"];
        $related_id = $post["related_id"];
        $priority = $post["priority"];
        $status = $post["status"];
        $assignees = $post["assignees"];
        $followers = $post["followers"];
        $task_description = $post["task_description"];

        $query = "UPDATE tasks SET name=? ,status=?,start_date=?,due_date=?,related_to=?,related_id=?, priority=? , assignees=? ,followers=? ,task_description=?WHERE task_id=$task_id";
        $this->db->query($query, array($name, $status, $start_date, $due_date, $related_to, $related_id, $priority, $assignees, $followers, $task_description));

        $config['title'] = "Task Update";
        if ($this->db->affectedRows() > 0) {
            $updated = true;
            $config['log_text'] = "[ Task successfully updated ]";
            $config['ref_link'] = 'erp/crm/task';
            $this->session->setFlashdata("op_success", "Task successfully updated");
        } else {
            $config['log_text'] = "[ Task failed to update ]";
            $config['ref_link'] = 'erp/crm/task';
            $this->session->setFlashdata("op_error", "Task failed to update");
        }
        log_activity($config);
        return $updated;
    }

    public function get_task_by_id($task_id)
    {
        $query = "SELECT * FROM `tasks`  WHERE task_id=$task_id";
        $result = $this->db->query($query)->getRow();
        return $result;
    }
    public function get_tasks_by_id($task_id)
    {
        $query = "SELECT erp_users.name FROM `tasks` JOIN erp_users ON tasks.assignees=erp_users.user_id WHERE task_id=$task_id";
        $result = $this->db->query($query)->getRow();
        return $result;
    }
    public function get_tasks1_by_id($task_id)
    {
        $query = "SELECT erp_users.name FROM `tasks` JOIN erp_users ON tasks.followers=erp_users.user_id WHERE task_id=$task_id";
        $result = $this->db->query($query)->getRow();
        return $result;
    }

    public function get_task_for_view($task_id)
    {
        $query = "SELECT task_id,name,status,start_date,due_date,related_to,related_id,priority,assignees,followers,task_description,erp_users.name AS assigned,tasks.created_at,tickets.assigned_to,tickets.status,tickets.remarks,tickets.created_by FROM tickets JOIN customers ON tickets.cust_id=customers.cust_id JOIN erp_users ON tasks.assigned=erp_users.user_id WHERE task_id=$task_id";
        $result = $this->db->query($query)->getRow();
        return $result;
    }

    public function taskDelete($task_id)
    {
        $jobresult = $this->builder()->where('task_id', $task_id)->get()->getRow();

        $config['title'] = "Task Delete";

        if (!empty($jobresult)) {

            $this->db->table('tasks')->where('task_id', $task_id)->delete();
            $config['log_text'] = "[ Task successfully deleted ]";
            $config['ref_link'] = url_to('erp.crm.task');
            $this->session->setFlashdata("op_success", "Task successfully deleted");
        } else {
            $config['log_text'] = "[ Task failed to delete ]";
            $config['ref_link'] = url_to('erp.crm.task');
            $this->session->setFlashdata("op_success", "Task failed to delete");
        }

        log_activity($config);
    }

    public function taskReportBox()
    {
        $taskTotal = $this->taskTotal();
        $taskCompletedCount = $this->taskCompletedCount();
        $totalRecode = [
            "total" => $taskTotal,
            "completed_total" => $taskCompletedCount,
        ];
        return $totalRecode;
    }


    private function taskTotal()
    {
        $query = "SELECT COUNT(task_id) as total FROM `tasks`";
        $rusult = $this->db->query($query)->getResult();
        return $rusult;
    }

    private function taskCompletedCount()
    {
        $query = "SELECT COUNT(status)as count FROM `tasks` WHERE status=3";
        $result = $this->db->query($query)->getResult();
        return $result;
    }
}
