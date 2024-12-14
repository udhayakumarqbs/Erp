<?php

namespace App\Models\Erp;

use CodeIgniter\Model;

class ProjectTestingModel extends Model
{
    protected $table      = 'project_testing';
    protected $primaryKey = 'project_test_id';

    protected $allowedFields = [
        'project_id',
        'name',
        'assigned_to',
        'complete_before',
        'result',
        'completed_at',
        'description',
        'remarks',
        'created_at',
        'created_by',
    ];
    protected $session;
    protected $db;

    public function __construct()
    {
        $this->session = \Config\Services::session();
        $this->db = \Config\Database::connect();
    }

    protected $useTimestamps = false;
    private $testing_status = array(
        0 => "Created",
        1 => "Passed",
        2 => "Failed"
    );

    private $testing_status_bg = array(
        0 => "st_dark",
        1 => "st_success",
        2 => "st_danger"
    );
    public function get_testing_status_bg()
    {
        return $this->testing_status_bg;
    }
    public function get_testing_status()
    {
        return $this->testing_status;
    }

    public function get_dtconfig_project_testing()
    {
        $config = array(
            "columnNames" => ["sno", "name", "assigned", "complete before", "result", "action"],
            "sortable" => [0, 1, 0, 0, 1, 0]
        );
        return json_encode($config);
    }

    public function get_project_testing_datatable($project_id)
    {
        $columns = array(
            "name" => "project_testing.name",
            "result" => "result"
        );
        $limit = $_GET['limit'];
        $offset = $_GET['offset'];
        $search = $_GET['search'] ?? "";
        $orderby = isset($_GET['orderby']) ? strtoupper($_GET['orderby']) : "";
        $ordercol = isset($_GET['ordercol']) ? $columns[$_GET['ordercol']] : "";

        $query = "SELECT project_test_id,project_testing.name AS test_name,erp_users.name AS assigned,complete_before,result FROM project_testing JOIN erp_users ON project_testing.assigned_to=erp_users.user_id WHERE project_id=$project_id ";
        $count = "SELECT COUNT(project_test_id) AS total FROM project_testing JOIN erp_users ON project_testing.assigned_to=erp_users.user_id WHERE project_id=$project_id ";
        $where = true;

        if (!empty($search)) {
            if (!$where) {
                $query .= " WHERE project_testing.name LIKE '%" . $search . "%' ";
                $count .= " WHERE project_testing.name LIKE '%" . $search . "%' ";
                $where = true;
            } else {
                $query .= " AND project_testing.name LIKE '%" . $search . "%' ";
                $count .= " AND project_testing.name LIKE '%" . $search . "%' ";
            }
        }

        if (!empty($ordercol) && !empty($orderby)) {
            $query .= " ORDER BY " . $ordercol . " " . $orderby;
        }
        $query .= " LIMIT $offset,$limit ";
        $result = $this->db->query($query)->getResultArray();
        $m_result = array();
        $sno = $offset + 1;
        foreach ($result as $r) {
            $action = '<div class="dropdown tableAction">
                        <a type="button" class="dropBtn HoverA "><i class="fa fa-ellipsis-v"></i></a>
                        <div class="dropdown_container">
                            <ul class="BB_UL flex">
                                <li><a href="' . url_to('erp.project.testingview', $project_id, $r['project_test_id']) . '" title="View" class="bg-warning"><i class="fa fa-eye"></i> </a></li>
                                <li><a href="' . url_to('erp.project.testingedit', $project_id, $r['project_test_id']) . '" title="Edit" class="bg-success"><i class="fa fa-pencil"></i> </a></li>
                                <li><a href="' . url_to('erp.project.testingdelete',$r['project_test_id'], $project_id) . '" title="Delete" class="bg-danger del-confirm"><i class="fa fa-trash"></i> </a></li>
                            </ul>
                        </div>
                    </div>';
            $status = "<span class='st " . $this->testing_status_bg[$r['result']] . "'>" . $this->testing_status[$r['result']] . " </span>";
            array_push(
                $m_result,
                array(
                    $r['project_test_id'],
                    $sno,
                    $r['test_name'],
                    $r['assigned'],
                    $r['complete_before'],
                    $status,
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

    public function get_project_testing_export($type, $project_id)
    {
        $columns = array(
            "name" => "project_testing.name",
            "result" => "result"
        );
        $limit = $_GET['limit'];
        $offset = $_GET['offset'];
        $search = $_GET['search'] ?? "";
        $orderby = isset($_GET['orderby']) ? strtoupper($_GET['orderby']) : "";
        $ordercol = isset($_GET['ordercol']) ? $columns[$_GET['ordercol']] : "";
        $query = "";
        if ($type == "pdf") {
            $query = "SELECT project_testing.name AS test_name,FROM_UNIXTIME(project_testing.created_at,'%Y-%m-%d') AS created_on,erp_users.name AS assigned,complete_before,
            CASE ";
            foreach ($this->testing_status as $key => $value) {
                $query .= " WHEN result=$key THEN '$value' ";
            }
            $query .= " END AS result FROM project_testing JOIN erp_users ON project_testing.assigned_to=erp_users.user_id WHERE project_id=$project_id ";
        } else {
            $query = "SELECT project_testing.name AS test_name,project_testing.description,FROM_UNIXTIME(project_testing.created_at,'%Y-%m-%d') AS created_on,erp_users.name AS assigned,complete_before,
            CASE ";
            foreach ($this->testing_status as $key => $value) {
                $query .= " WHEN result=$key THEN '$value' ";
            }
            $query .= " END AS result,completed_at,remarks FROM project_testing JOIN erp_users ON project_testing.assigned_to=erp_users.user_id WHERE project_id=$project_id ";
        }

        $where = true;
        if (!empty($search)) {
            if (!$where) {
                $query .= " WHERE project_testing.name LIKE '%" . $search . "%' ";
                $where = true;
            } else {
                $query .= " AND project_testing.name LIKE '%" . $search . "%' ";
            }
        }
        if (!empty($ordercol) && !empty($orderby)) {
            $query .= " ORDER BY " . $ordercol . " " . $orderby;
        }
        $result = $this->db->query($query)->getResultArray();
        return $result;
    }


    public function update_project_testing($project_id, $project_test_id, $post)
    {
        $updated = false;
        $name = $post["name"];
        $complete_before = $post["complete_before"];
        $assigned_to = $post["assigned_to"];
        $description = $post["description"];

        $query = "UPDATE project_testing SET name=? , assigned_to=? , complete_before=? , description=? WHERE project_test_id=$project_test_id ";
        $this->db->query($query, array($name, $assigned_to, $complete_before, $description));
        $config['title'] = "Project Testing Update";
        if ($this->db->affectedRows() > 0) {
            $updated = true;
            $config['log_text'] = "[ Project Testing successfully updated ]";
            $config['ref_link'] = url_to('erp.project.projectview', $project_id);
            $this->session->setFlashdata("op_success", "Project Testing successfully updated");
        } else {
            $config['log_text'] = "[ Project Testing failed to update ]";
            $config['ref_link'] = url_to('erp.project.projectview', $project_id);
            $this->session->setFlashdata("op_error", "Project Testing failed to update");
        }
        log_activity($config);
        return $updated;
    }

    public function get_project_testing_by_id($project_test_id)
    {
        $query = "SELECT erp_users.user_id,project_test_id,project_testing.name AS test_name,erp_users.name AS assigned,complete_before,project_testing.description FROM project_testing JOIN erp_users ON project_testing.assigned_to=erp_users.user_id WHERE project_test_id=$project_test_id ";
        $result = $this->db->query($query)->getRow();
        return $result;
    }

    public function get_project_testing_for_view($project_test_id)
    {
        $query = "SELECT project_testing.name AS test_name,erp_users.name AS assigned,complete_before,project_testing.description,result,completed_at,project_testing.created_at,remarks FROM project_testing JOIN erp_users ON project_testing.assigned_to=erp_users.user_id WHERE project_test_id=$project_test_id ";
        $result = $this->db->query($query)->getRow();
        return $result;
    }

    public function testing_update($project_test_id, $post)
    {
        $remarks = $post["remarks"];
        $result = $post["result"];
        $completed_at = date('Y-m-d');
        $query = "UPDATE project_testing SET remarks=? , result=? , completed_at=? WHERE project_test_id=$project_test_id ";
        $this->db->query($query, array($remarks, $result, $completed_at));
        $config['title'] = "Project Testing Result Update";
        if ($this->db->affectedRows() > 0) {
            $updated = true;
            $config['log_text'] = "[ Project Testing Result successfully updated ]";
            $config['ref_link'] = url_to('erp.project.projectview',$project_test_id);
            $this->session->setFlashdata("op_success", "Project Testing Result successfully updated");
        } else {
            $config['log_text'] = "[ Project Testing Result failed to update ]";
            $config['ref_link'] = url_to('erp.project.projectview',$project_test_id);
            $this->session->setFlashdata("op_error", "Project Testing Result failed to update");
        }
        log_activity($config);
    }

    public function delete_testing($project_test_id)
    {
        $query = "DELETE FROM project_testing WHERE project_test_id = $project_test_id";
        $logger = \Config\Services:: logger();

        $logger->error($query);
        $this->db->query($query);
        $config['title'] = "Project Testing Delete";
        if ($this->db->affectedRows() > 0) {
            $config['log_text'] = "[ Project Testing successfully deleted ]";
            $config['ref_link'] = url_to('erp.project.projectview', $project_test_id);
            $this->session->setFlashdata("op_success", "Project Testing successfully deleted");
            log_activity($config);
            return true;
        } else {
            $config['log_text'] = "[ Project Testing failed to delete ]";
            $config['ref_link'] = url_to('erp.project.projectview', $project_test_id);
            $this->session->setFlashdata("op_error", "Project Testing failed to delete");
            log_activity($config);
        }
        
    }


    // public function delete_testing($project_test_id)
    // {
    //     $query = "DELETE FROM project_testing
    //     WHERE project_test_id =".$project_test_id;
    //     if($this->db->query($query)){
            
    //         return true;
    //     }
    // }

}
