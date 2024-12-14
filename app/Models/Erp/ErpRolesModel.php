<?php

namespace App\Models\Erp;

use CodeIgniter\Model;

class ErpRolesModel extends Model
{
    protected $table = 'erp_roles';
    protected $primaryKey = 'role_id';
    protected $allowedFields = ['role_name', 'role_desc', 'permissions', 'created_at', 'referrer_role', 'can_be_purged'];
    protected $db;
    protected $session;
    public function __construct()
    {
        
        $this->db = \Config\Database::connect();
        $this->session = \Config\Services::session();
    }
    public function get_dtconfig_roles()
    {
        $config = array(
            "columnNames" => ["sno", "role name", "role description", "action"],
            "sortable" => [0, 1, 0, 0]
        );
        return json_encode($config);
    }

    public function get_datatable_roles()
    {
        $columns = array(
            "role name" => "role_name"
        );
        $limit = $_GET['limit'];
        $offset = $_GET['offset'];
        $search = $_GET['search'] ?? "";
        $orderby = isset($_GET['orderby']) ? strtoupper($_GET['orderby']) : "";
        $ordercol = isset($_GET['ordercol']) ? $columns[$_GET['ordercol']] : "";
        $query = "SELECT role_id AS id,role_name,role_desc FROM erp_roles WHERE can_be_purged=0 ";
        $count = "SELECT COUNT(role_id) AS total FROM erp_roles WHERE can_be_purged=0 ";
        if (!empty($search)) {
            $query .= " AND role_name LIKE '%" . $search . "%' ";
            $count .= " AND role_name LIKE '%" . $search . "%' ";
        }
        if (!empty($ordercol) && !empty($orderby)) {
            $query .= " ORDER BY " . $ordercol . " " . $orderby;
        }
        $query .= "  LIMIT $offset,$limit ";

        $result = $this->db->query($query)->getResultArray();
        $m_result = array();
        $sno = $offset + 1;
        foreach ($result as $r) {
            $action = '<div class="dropdown tableAction">
                        <a type="button" class="dropBtn HoverA "><i class="fa fa-ellipsis-v"></i></a>
                        <div class="dropdown_container">
                            <ul class="BB_UL flex">
                                <li><a target="_BLANK" href="' . url_to('erp.setting.roleview' , $r['id']) . '" title="View" class="bg-warning"><i class="fa fa-eye"></i> </a></li>
                                <li><a href="' . url_to('erp.setting.roleedit' , $r['id']) . '" title="Edit" class="bg-success"><i class="fa fa-pencil"></i> </a></li>
                                <li><a href="' . url_to('erp.setting.roledelete' , $r['id']) . '" title="Delete" class="bg-danger del-confirm"><i class="fa fa-trash"></i> </a></li>
                            </ul>
                        </div>
                    </div>';
            array_push(
                $m_result,
                array(
                    $r['id'],
                    $sno,
                    $r['role_name'],
                    $r['role_desc'],
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

    public function get_roles_export($type)
    {
        $columns = array(
            "role name" => "role_name"
        );
        $search = $_GET['search'] ?? "";
        $orderby = isset($_GET['orderby']) ? strtoupper($_GET['orderby']) : "";
        $ordercol = isset($_GET['ordercol']) ? $columns[$_GET['ordercol']] : "";

        $query = "SELECT role_id AS id,role_name,role_desc FROM erp_roles WHERE can_be_purged=0 ";

        if (!empty($search)) {
            $query .= " AND role_name LIKE '%" . $search . "%' ";
            $count .= " AND role_name LIKE '%" . $search . "%' ";
        }

        if (!empty($ordercol) && !empty($orderby)) {
            $query .= " ORDER BY " . $ordercol . " " . $orderby;
        }

        $result = $this->db->query($query)->getResultArray();
        return $result;
    }

    public function get_role_by_id($id)
    {
        $query = "SELECT role_id,role_name,role_desc,permissions FROM erp_roles WHERE role_id=$id AND can_be_purged=0";
        $result = $this->db->query($query)->getRow();
        return $result;
    }

    public function insert_role($post)
    {
        $role_name = $post["role_name"];
        $role_desc = $post["role_desc"];
        $created_at = time();
        $permissions = array();
        foreach ($_POST as $key => $value) {
            if ($key != "role_name" && $key != "role_desc") {
                array_push($permissions, $key);
            }
        }
        $query = "INSERT INTO erp_roles(role_name,role_desc,created_at,permissions) VALUES(?,?,?,?)";
        $saved = $this->db->query($query, array($role_name, $role_desc, $created_at, json_encode($permissions)));
        $id = $this->db->insertId();
        $config['title'] = "Role Insert";
        if ($saved) {
            $config['log_text'] = "[ Role successfully created ]";
            $config['ref_link'] = url_to("erp.setting.roleview" , $id);
            $this->session->setFlashdata("op_success", "Role successfully created");
        } else {
            $config['log_text'] = "[ Role failed to create ]";
            $config['ref_link'] = "";
            $this->session->setFlashdata("op_error", "Role failed to create");
        }
        log_activity($config);
        return $saved;
    }

    public function update_role($role_id,$post)
    {
        $role_name = $post["role_name"];
        $role_desc = $post["role_desc"];
        $do_reflect = $post["do_reflect"];
        $permissions = array();

        foreach ($_POST as $key => $value) {
            if ($key != "role_name" && $key != "role_desc") {
                array_push($permissions, $key);
            }
        }

        if ($do_reflect == 0) {
            return $this->update_role_asreferer($role_id, $role_name, $role_desc, $permissions);
        } else {
            return $this->update_role_asdirect($role_id, $role_name, $role_desc, $permissions);
        }
    }

    private function update_role_asreferer($role_id, $role_name, $role_desc, $permissions)
    {
        $this->db->transBegin();
        $saved = false;
        $query = "UPDATE erp_roles SET can_be_purged=1 WHERE role_id=$role_id";
        $this->db->query($query);
        $created_at = time();
        $query = "INSERT INTO erp_roles(role_name,role_desc,created_at,permissions,referrer_role) VALUES(?,?,?,?,?)";
        $this->db->query($query, array($role_name, $role_desc, $created_at, json_encode($permissions), $role_id));
        $id = $this->db->insertId();
        $config['title'] = "Role Update";
        $this->db->transComplete();
        if ($this->db->transStatus() === FALSE) {
            $this->db->transRollback();
            $this->session->setFlashdata("op_success", "Role failed to update");
            $config['ref_link'] = url_to("erp.setting.roleview" , $id);
            $config['log_text'] = "[ Role failed to update ]";
        } else {
            $this->db->transCommit();
            $saved = true;
            $this->session->setFlashdata("op_success", "Role successfully updated");
            $config['ref_link'] = url_to("erp.setting.roleview" , $id);
            $config['log_text'] = "[ Role successfully updated ]";
        }
        log_activity($config);
        return $saved;
    }

    private function update_role_asdirect($role_id, $role_name, $role_desc, $permissions)
    {
        $saved = false;
        $config['title'] = "Role Update";
        $query = "UPDATE erp_roles SET role_name=? , role_desc=? , permissions=? WHERE role_id=?";
        $this->db->query($query, array($role_name, $role_desc, json_encode($permissions), $role_id));
        if ($this->db->affectedRows() > 0) {
            $saved = true;
            $this->session->setFlashdata("op_success", "Role successfully updated");
            $config['ref_link'] = url_to("erp.setting.roleview" , $role_id);
            $config['log_text'] = "[ Role successfully updated ]";
        } else {
            $this->session->setFlashdata("op_success", "Role failed to update");
            $config['ref_link'] = url_to("erp.setting.roleview" , $role_id);
            $config['log_text'] = "[ Role failed to update ]";
        }
        log_activity($config);
        return $saved;
    }

    public function delete_role($role_id)
    {
        $deleted = false;
        $query = "UPDATE erp_roles SET can_be_purged=1 WHERE role_id=$role_id";
        $this->db->query($query);
        $config['title'] = "Role Delete";
        $config['ref_link'] = "";
        if ($this->db->affectedRows() > 0) {
            $deleted = true;
            $this->session->setFlashdata("op_success", "Role successfully deleted");
            $config['log_text'] = "[ Role successfully deleted ; ID=" . $role_id . " ]";
        } else {
            $this->session->setFlashdata("op_success", "Role failed to delete");
            $config['ref_link'] = url_to("erp.setting.roleview" , $role_id);
            $config['log_text'] = "[ Role failed to delete ; ID=" . $role_id . " ]";
        }
        log_activity($config);
        return $deleted;
    }
}
