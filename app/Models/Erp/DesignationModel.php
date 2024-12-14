<?php

namespace App\Models\Erp;

use CodeIgniter\Model;

class DesignationModel extends Model
{
    protected $table = 'designation';
    protected $primaryKey = 'designation_id';

    protected $allowedFields = [
        'department_id',
        'name',
        'description',
        'created_at',
        'created_by'
    ];

    protected $data;
    protected $db;
    protected $session;

    public function __construct()
    {
        $this->db = \Config\Database::connect();
        $this->session = \Config\Services::session();
    }

    public function insert_update_designation($designation_id,$post)
    {
        if (empty($designation_id)) {
            $this->insert_designation($post);
        } else {
            $this->update_designation($designation_id,$post);
        }
    }

    private function insert_designation($post)
    {
        $name = $post["designation_name"];
        $desc = $post["description"];
        $department_id = $post["department_id"];
        $created_by = get_user_id();

        $query = "INSERT INTO designation(name,description,department_id,created_at,created_by) VALUES(?,?,?,?,?) ";
        $this->db->query($query, array($name, $desc, $department_id, time(), $created_by));

        $config['title'] = "Designation Insert";
        if (!empty($this->db->insertId())) {
            $config['log_text'] = "[ Designation successfully created ]";
            $config['ref_link'] = 'erp/hr/designation/';
            $this->session->setFlashdata("op_success", "Designation successfully created");
        } else {
            $config['log_text'] = "[ Designation failed to create ]";
            $config['ref_link'] = 'erp/hr/designation/';
            $this->session->setFlashdata("op_error", "Designation failed to create");
        }
        log_activity($config);
    }

    private function update_designation($designation_id,$post)
    {
        $name = $post["designation_name"];
        $desc = $post["description"];
        $department_id = $post["department_id"];
        $created_by = get_user_id();

        $query = "UPDATE designation SET name=? , description=? , department_id=? WHERE designation_id=$designation_id";
        $this->db->query($query, array($name, $desc, $department_id));

        $config['title'] = "Designation Update";
        if ($this->db->affectedRows() > 0) {
            $config['log_text'] = "[ Designation successfully updated ]";
            $config['ref_link'] = 'erp/hr/designation/';
            $this->session->setFlashdata("op_success", "Designation successfully updated");
        } else {
            $config['log_text'] = "[ Designation failed to update ]";
            $config['ref_link'] = 'erp/hr/designation/';
            $this->session->setFlashdata("op_error", "Designation failed to update");
        }
        log_activity($config);
    }

    public function get_dtconfig_designation()
    {
        $config = array(
            "columnNames" => ["sno", "name", "department", "description", "action"],
            "sortable" => [0, 1, 1, 0, 0],
        );
        return json_encode($config);
    }

    public function get_designation_datatable()
    {
        $columns = array(
            "name" => "name",
            "department" => "department.name"
        );
        $limit = $_GET['limit'];
        $offset = $_GET['offset'];
        $search = $_GET['search'] ?? "";
        $orderby = isset($_GET['orderby']) ? strtoupper($_GET['orderby']) : "";
        $ordercol = isset($_GET['ordercol']) ? $columns[$_GET['ordercol']] : "";

        $query = "SELECT designation_id,designation.name AS desig,department.name AS dept,designation.description AS description FROM designation JOIN department ON designation.department_id=department.department_id ";
        $count = "SELECT COUNT(designation_id) AS total FROM designation JOIN department ON designation.department_id=department.department_id ";
        $where = false;

        if (!empty($search)) {
            if (!$where) {
                $query .= " WHERE designation.name LIKE '%" . $search . "%' ";
                $count .= " WHERE designation.name LIKE '%" . $search . "%' ";
                $where = true;
            } else {
                $query .= " AND designation.name LIKE '%" . $search . "%' ";
                $count .= " AND designation.name LIKE '%" . $search . "%' ";
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
                                <li><a href="#" data-ajax-url="' . url_to('erp.hr.ajaxfetchdesignation' , $r['designation_id']) . '" title="Edit" class="bg-success modalBtn"><i class="fa fa-pencil"></i> </a></li>
                                <li><a href="' . url_to('erp.hr.designationdelete' , $r['designation_id']) . '" title="Delete" class="bg-danger del-confirm"><i class="fa fa-trash"></i> </a></li>
                            </ul>
                        </div>
                    </div>';
            array_push(
                $m_result,
                array(
                    $r['designation_id'],
                    $sno,
                    $r['desig'],
                    $r['dept'],
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

    public function get_designation_export($type)
    {
        $columns = array(
            "name" => "name",
            "department" => "department.name"
        );
        $limit = $_GET['limit'];
        $offset = $_GET['offset'];
        $search = $_GET['search'] ?? "";
        $orderby = isset($_GET['orderby']) ? strtoupper($_GET['orderby']) : "";
        $ordercol = isset($_GET['ordercol']) ? $columns[$_GET['ordercol']] : "";

        $query = "SELECT designation.name AS desig,department.name AS dept,designation.description AS description FROM designation JOIN department ON designation.department_id=department.department_id ";
        $where = false;

        if (!empty($search)) {
            if (!$where) {
                $query .= " WHERE designation.name LIKE '%" . $search . "%' ";
                $where = true;
            } else {
                $query .= " AND designation.name LIKE '%" . $search . "%' ";
            }
        }

        if (!empty($ordercol) && !empty($orderby)) {
            $query .= " ORDER BY " . $ordercol . " " . $orderby;
        }
        $result = $this->db->query($query)->getResultArray();
        return $result;
    }

    public function delete_designation($designation_id)
    {
        $check1 = $this->db->query("SELECT designation_id FROM employees WHERE designation_id=$designation_id LIMIT 1")->getRow();
        if (empty($check1)) {
            $query = "DELETE FROM designation WHERE designation_id=$designation_id";
            $this->db->query($query);
            $config['title'] = "Designation Delete";
            if ($this->db->affectedRows() > 0) {
                $config['log_text'] = "[ Designation successfully deleted ]";
                $config['ref_link'] = 'erp/hr/designation/';
                $this->session->setFlashdata("op_success", "Designation successfully deleted");
            } else {
                $config['log_text'] = "[ Designation failed to delete ]";
                $config['ref_link'] = 'erp/hr/designation/';
                $this->session->setFlashdata("op_error", "Designation failed to delete");
            }
            log_activity($config);
        } else {
            $this->session->setFlashdata("op_error", "Designation already in use");
        }
    }

    public function get_all_designations()
    {
        $query = "SELECT designation_id,CONCAT(department.name,' - ',designation.name) AS desig FROM designation JOIN department ON designation.department_id=department.department_id ";
        $result = $this->db->query($query)->getResultArray();
        return $result;
    }
}
