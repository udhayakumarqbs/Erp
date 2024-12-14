<?php

namespace App\Models\Erp;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table            = 'erp_users';
    protected $primaryKey       = 'user_id';
    protected $useAutoIncrement = true;
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
        'description'
    ];


    // Dates
    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    public function authenticate($email, $password, $remember_me)
    {
        $result = $this->where('email', $email)
            ->select('user_id, name, email, is_admin, active, role_name, permissions, password')
            ->join('erp_roles', 'erp_users.role_id = erp_roles.role_id', 'left')
            ->get()
            ->getRowArray();

        if (empty($result)) {
            return ['error' => 'Invalid email ID'];
        } elseif ($result['active'] == 0) {
            return ['error' => 'Your account is inactive'];
        } elseif (!password_verify($password, $result['password'])) {
            return ['error' => 'Password is wrong'];
        }

        if ($remember_me == true) {
            $this->doRememberMe($result['user_id']);
        } else {
            $this->updateLastLogin($result['user_id']);
        }

        // Set session data
        $this->setSessionData($result);

        // Log activity
        $this->logActivity('Login', '[ User successfully logged in ]');

        return ['success' => true];
    }

    public function get_users_count(){
        $result = count($this->where('active',1)->get()->getResultArray());
        return $result;
    }

    private function doRememberMe($user_id)
    {
        $response = \Config\Services::response();
        $hash = sha1(bin2hex(random_bytes(10)) . time());
        $this->set(['remember' => $hash, 'last_login' => time()])->where('user_id', $user_id);
        $response->setCookie("rhash", $hash, time() + 7 * 24 * 60 * 60);
    }

    private function updateLastLogin($user_id)
    {
        if (is_numeric($user_id)) {
            $this->set(['last_login' => time()])->where('user_id', $user_id);
        } else {
            log_message('error', 'Invalid user ID provided for updating last login: ' . $user_id);
        }
    }

    private function setSessionData($result)
    {

        $session = \Config\Services::session(); // Load the session service

        $session->set('erp_username', $result['name']);
        $session->set('erp_userid', $result['user_id']);
        $session->set('erp_email', $result['email']);
        $session->set('erp_admin', $result['is_admin']);
        $session->set('erp_rolename', $result['role_name']);
        $session->set('erp_userid', $result['user_id']);
        $perms = !empty($result['permissions']) ? json_decode($result['permissions'], true) : [];
        $session->set('erp_perms', $perms);
        $session->set('erp_logged', true);
    }

    private function logActivity($title, $logText)
    {
        helper('erp');
        $config = ['title' => $title, 'log_text' => $logText, 'ref_link' => ''];
        log_activity($config);
    }

    public function addusers($data){
        // $log = \Config\Services::logger($this->builder()->insert($data));
        if($this->builder()->insert($data)){
            return true ;
        }else{
            return false ;
        }
    }

    public function update_user($id, $post)
    {
        $firstname = $post["name"];
        $lastname = $post["last_name"];
        $email = $post["email"];
        $phone = $post["phone"];
        $role = $post["role_id"] ?? 0;
        $position = $post["position"];
        $is_admin = $post["is_admin"] ?? 0;
        $desc = $post["description"];

        $password = $this->input->post("password");
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
                                <li><a href="' . url_to('erp.setting.useredit' , $r['id']) . '" title="Edit" class="bg-success"><i class="fa fa-pencil"></i> </a></li>
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

    public function getStaffId($user_mail){
        return $this->builder()->select('user_id')->where('email', $user_mail)->get()->getRow()->user_id;
    }
}
