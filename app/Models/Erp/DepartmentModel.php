<?php

namespace App\Models\Erp;

use CodeIgniter\Model;

class DepartmentModel extends Model
{
    protected $table = 'department';
    protected $primaryKey = 'department_id';
    protected $data;
    protected $db;
    protected $session;

    public function __construct()
    {
        $this->db = \Config\Database::connect();
        $this->session = \Config\Services::session();
    }
    protected $allowedFields = [
        'name',
        'description',
        'created_at',
        'created_by'
    ];
    private $gender = array(
        0 => "Male",
        1 => "Female"
    );
    public function get_gender()
    {
        return $this->gender;
    }
    private $emp_status = array(
        0 => "Working",
        1 => "Resigned"
    );
    public function get_emp_status()
    {
        return $this->emp_status;
    }
    private $marital_status = array(
        0 => "Not married",
        1 => "Married",
        2 => "Divorced"
    );

    private $amt_type = array(
        0 => "Percent",
        1 => "Amount"
    );
    public function get_amt_type()
    {
        return $this->amt_type;
    }
    public function get_marital_status()
    {
        return $this->marital_status;
    }

    public function get_dtconfig_department()
    {
        $config = array(
            "columnNames" => ["sno", "name", "description", "action"],
            "sortable" => [0, 1, 0, 0],
        );
        return json_encode($config);
    }

    public function insert_update_department($department_id,$post)
    {
        if (empty($department_id)) {
            $this->insert_department($post);
        } else {
            $this->update_department($department_id,$post);
        }
    }

    private function insert_department($post)
    {
        $name = $post["department_name"];
        $desc = $post["description"];
        $created_by = get_user_id();

        $query = "INSERT INTO department(name,description,created_at,created_by) VALUES(?,?,?,?) ";
        $this->db->query($query, array($name, $desc, time(), $created_by));

        $config['title'] = "Department Insert";
        if (!empty($this->db->insertId())) {
            $config['log_text'] = "[ Department successfully created ]";
            $config['ref_link'] = 'erp/hr/departments/';
            $this->session->setFlashdata("op_success", "Department successfully created");
        } else {
            $config['log_text'] = "[ Department failed to create ]";
            $config['ref_link'] = 'erp/hr/departments/';
            $this->session->setFlashdata("op_error", "Department failed to create");
        }
        log_activity($config);
    }

    private function update_department($department_id,$post)
    {
        $name = $post["department_name"];
        $desc = $post["description"];

        $query = "UPDATE department SET name=? , description=? WHERE department_id=$department_id";
        $this->db->query($query, array($name, $desc));

        $config['title'] = "Department Update";
        if ($this->db->affectedRows() > 0) {
            $config['log_text'] = "[ Department successfully updated ]";
            $config['ref_link'] = 'erp/hr/departments/';
            $this->session->setFlashdata("op_success", "Department successfully updated");
        } else {
            $config['log_text'] = "[ Department failed to update ]";
            $config['ref_link'] = 'erp/hr/departments/';
            $this->session->setFlashdata("op_error", "Department failed to update");
        }
        log_activity($config);
    }

    public function get_department_datatable()
    {
        $columns = array(
            "name" => "name"
        );
        $limit = $_GET['limit'];
        $offset = $_GET['offset'];
        $search = $_GET['search'] ?? "";
        $orderby = isset($_GET['orderby']) ? strtoupper($_GET['orderby']) : "";
        $ordercol = isset($_GET['ordercol']) ? $columns[$_GET['ordercol']] : "";

        $query = "SELECT department_id,name,description FROM department ";
        $count = "SELECT COUNT(department_id) AS total,name,description FROM department ";
        $where = false;

        if (!empty($search)) {
            if (!$where) {
                $query .= " WHERE name LIKE '%" . $search . "%' ";
                $count .= " WHERE name LIKE '%" . $search . "%' ";
                $where = true;
            } else {
                $query .= " AND name LIKE '%" . $search . "%' ";
                $count .= " AND name LIKE '%" . $search . "%' ";
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
                                <li><a href="#" data-ajax-url="' . url_to('erp.hr.ajaxfetchdepartment' , $r['department_id']) . '" title="Edit" class="bg-success modalBtn"><i class="fa fa-pencil"></i> </a></li>
                                <li><a href="' . url_to('erp.hr.departmentdelete' , $r['department_id']) . '" title="Delete" class="bg-danger del-confirm"><i class="fa fa-trash"></i> </a></li>
                            </ul>
                        </div>
                    </div>';
            array_push(
                $m_result,
                array(
                    $r['department_id'],
                    $sno,
                    $r['name'],
                    $r['description'],
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

    public function delete_department($department_id)
    {
        $check1 = $this->db->query("SELECT department_id FROM designation WHERE department_id=$department_id LIMIT 1")->getRow();
        if (empty($check1)) {
            $query = "DELETE FROM department WHERE department_id=$department_id";
            $this->db->query($query);
            $config['title'] = "Department Delete";
            if ($this->db->affectedRows() > 0) {
                $config['log_text'] = "[ Department successfully deleted ]";
                $config['ref_link'] = 'erp/hr/departments/';
                $this->session->setFlashdata("op_success", "Department successfully deleted");
            } else {
                $config['log_text'] = "[ Department failed to delete ]";
                $config['ref_link'] = 'erp/hr/departments/';
                $this->session->setFlashdata("op_error", "Department failed to delete");
            }
            log_activity($config);
        } else {
            $this->session->setFlashdata("op_error", "Department already in use");
        }
    }


    public function get_all_departments()
    {
        $query = "SELECT department_id,name FROM department ";
        $result = $this->db->query($query)->getResultArray();
        return $result;
    }
    public function get_department_export($type)
    {
        $columns = array(
            "name" => "name"
        );
        $limit = $_GET['limit'];
        $offset = $_GET['offset'];
        $search = $_GET['search'] ?? "";
        $orderby = isset($_GET['orderby']) ? strtoupper($_GET['orderby']) : "";
        $ordercol = isset($_GET['ordercol']) ? $columns[$_GET['ordercol']] : "";

        $query = "SELECT name,description FROM department ";
        $where = false;

        if (!empty($search)) {
            if (!$where) {
                $query .= " WHERE name LIKE '%" . $search . "%' ";
                $where = true;
            } else {
                $query .= " AND name LIKE '%" . $search . "%' ";
            }
        }
        if (!empty($ordercol) && !empty($orderby)) {
            $query .= " ORDER BY " . $ordercol . " " . $orderby;
        }
        $result = $this->db->query($query)->getResultArray();
        return $result;
    }
}
