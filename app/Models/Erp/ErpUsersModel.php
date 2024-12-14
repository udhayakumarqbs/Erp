<?php

namespace App\Models\Erp;

use CodeIgniter\Model;

class ErpUsersModel extends Model
{
    protected $table      = 'erp_users';
    protected $primaryKey = 'user_id';

    protected $allowedFields = [
        'name',
        'last_name',
        'email',
        'phone',
        'position',
        'password',
        'active',
        'expired',
        'is_admin',
        'last_login',
        'role_id',
        'remember',
        'description',
        'created_at',
    ];
    protected $db;
    protected $session;
    public function __construct()
    {

        $this->db = \Config\Database::connect();
        $this->session = \Config\Services::session();
    }
    protected $useTimestamps = false;

    public function get_all_users()
    {
        $query = "SELECT user_id,name FROM erp_users WHERE active=1 ";
        $result = $this->db->query($query)->getResultArray();
        return $result;
    }

    public function get_dtconfig_users()
    {
        $config = array(
            "columnNames" => ["sno", "name", "email", "role name", "phone", "position", "action"],
            "sortable" => [0, 1, 1, 1, 0, 0, 0],
            "filters" => ["role"]
        );
        return json_encode($config);
    }

    public function get_users_datatable()
    {
        $columns = array(
            "role name" => "role_name",
            "name" => "name",
            "email" => "email",
            "role" => "erp_roles.role_id"
        );
        $limit = $_GET['limit'];
        $offset = $_GET['offset'];
        $search = $_GET['search'] ?? "";
        $orderby = isset($_GET['orderby']) ? strtoupper($_GET['orderby']) : "";
        $ordercol = isset($_GET['ordercol']) ? $columns[$_GET['ordercol']] : "";
        $query = "SELECT user_id AS id , name,email,IFNULL(role_name,'') AS role_name,phone,position,is_admin FROM erp_users LEFT JOIN erp_roles ON erp_users.role_id=erp_roles.role_id WHERE active=1 ";
        $count = "SELECT COUNT(user_id) AS total FROM erp_users LEFT JOIN erp_roles ON erp_users.role_id=erp_roles.role_id WHERE active=1 ";

        $filter_1_col = isset($_GET['role']) ? $columns['role'] : "";
        $filter_1_val = $_GET['role'];


        if (!empty($filter_1_col) && $filter_1_val !== "") {
            $query .= " AND " . $filter_1_col . "=" . $filter_1_val . " ";
            $count .= " AND " . $filter_1_col . "=" . $filter_1_val . " ";
        }

        if (!empty($search)) {
            $query .= " AND ( role_name LIKE '%" . $search . "%' OR name LIKE '%" . $search . "%' OR email LIKE '%" . $search . "%' ) ";
            $count .= " AND ( role_name LIKE '%" . $search . "%' OR name LIKE '%" . $search . "%' OR email LIKE '%" . $search . "%' ) ";
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
                                <li><a href="' . url_to('erp.setting.useredit', $r['id']) . '" title="Edit" class="bg-success"><i class="fa fa-pencil"></i> </a></li>
                            </ul>
                        </div>
                    </div>';
            $rolename = "<span class='st st_violet' >Anonymous</span>";
            if ($r['is_admin'] == "1") {
                $rolename = "<span class='st st_success' >Admin</span>";
            } else if (!empty($r['role_name'])) {
                $rolename = "<span class='st st_dark' >" . $r['role_name'] . "</span>";
            }
            array_push(
                $m_result,
                array(
                    $r['id'],
                    $sno,
                    $r['name'],
                    $r['email'],
                    $rolename,
                    $r['phone'],
                    $r['position'],
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

    public function get_users_export($type)
    {
        $columns = array(
            "role name" => "role_name",
            "name" => "name",
            "email" => "email",
            "role" => "erp_roles.role_id"
        );
        $search = $_GET['search'] ?? "";

        $orderby = isset($_GET['orderby']) ? strtoupper($_GET['orderby']) : "";
        $ordercol = isset($_GET['ordercol']) ? $columns[$_GET['ordercol']] : "";
        $query = "";
        if ($type == "pdf") {
            $query = "SELECT name,email,phone,position,
            CASE 
            WHEN ISNULL(role_name) AND is_admin=1 THEN 'Admin' 
            WHEN ISNULL(role_name) AND is_admin=0 THEN 'Anonymous'
            ELSE role_name
            END AS role_name FROM erp_users LEFT JOIN erp_roles ON erp_users.role_id=erp_roles.role_id WHERE active=1 ";
        } else {
            $query = "SELECT name,email,phone,position,
            CASE 
            WHEN ISNULL(role_name) AND is_admin=1 THEN 'Admin' 
            WHEN ISNULL(role_name) AND is_admin=0 THEN 'Anonymous'
            ELSE role_name
            END AS role_name FROM erp_users LEFT JOIN erp_roles ON erp_users.role_id=erp_roles.role_id WHERE active=1 ";
        }

        $filter_1_col = isset($_GET['role']) ? $columns['role'] : "";
        $filter_1_val = $_GET['role'];


        if (!empty($filter_1_col) && $filter_1_val !== "") {
            $query .= " AND " . $filter_1_col . "=" . $filter_1_val . " ";
            // $count .= " AND " . $filter_1_col . "=" . $filter_1_val . " ";
        }

        if (!empty($search)) {
            $query .= " AND ( role_name LIKE '%" . $search . "%' OR name LIKE '%" . $search . "%' OR email LIKE '%" . $search . "%' ) ";
            // $count .= " AND ( role_name LIKE '%" . $search . "%' OR name LIKE '%" . $search . "%' OR email LIKE '%" . $search . "%' ) ";
        }

        if (!empty($ordercol) && !empty($orderby)) {
            $query .= " ORDER BY " . $ordercol . " " . $orderby;
        }

        $result = $this->db->query($query)->getResultArray();
        return $result;
    }

    public function get_all_roles()
    {
        $query = "SELECT role_name,role_id FROM erp_roles WHERE can_be_purged=0";
        $result = $this->db->query($query)->getResultArray();
        return $result;
    }

    public function get_user_by_id($id)
    {
        $query = "SELECT user_id,erp_roles.role_id,role_name,name,last_name,email,phone,position,is_admin,description FROM erp_users LEFT JOIN erp_roles ON erp_users.role_id=erp_roles.role_id WHERE user_id=$id AND active=1 ";
        $result = $this->db->query($query)->getRow();
        return $result;
    }

    public function update_user($id, $post)
    {
        $firstname = $post["name"];
        $lastname = $post["last_name"];
        $email = $post["email"];
        $phone = $post["phone"];
        $role = $post["role_id"];
        $position = $post["position"];
        $is_admin = $post["is_admin"];
        $desc = $post["description"];

        $password = $post["password"];
        $query = "UPDATE erp_users SET name=? , last_name=? , email=? , phone=? , role_id=? , description=? , position=? , is_admin=? ";
        $saved = false;
        if (!empty($password)) {
            $password = password_hash($password, PASSWORD_DEFAULT);
            $query .= " , password=? WHERE user_id=?";
            $this->db->query($query, array($firstname, $lastname, $email, $phone, $role, $desc, $position, $is_admin, $password, $id));
        } else {
            $query .= " WHERE user_id=?";
            $this->db->query($query, array($firstname, $lastname, $email, $phone, $role, $desc, $position, $is_admin, $id));
        }
        $config['title'] = "User Update";
        if ($this->db->affectedRows() > 0) {
            $saved = true;
            $config['log_text'] = "[ User successfully updated ]";
            $config['ref_link'] = url_to('erp.setting.useredit', $id);
            $this->session->setFlashdata("op_success", "User successfully updated");
        } else {
            $config['log_text'] = "[ User failed to update ]";
            $config['ref_link'] = url_to('erp.setting.useredit', $id);
            $this->session->setFlashdata("op_error", "User failed to update");
        }
        log_activity($config);
        return $saved;
    }
}
