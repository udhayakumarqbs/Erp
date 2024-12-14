<?php

namespace App\Models\Erp;

use CodeIgniter\Model;

class CustomerContactsModel extends Model
{
    protected $table = 'customer_contacts';
    protected $primaryKey = 'contact_id';
    protected $allowedFields = [
        'firstname',
        'lastname',
        'email',
        'phone',
        'active',
        'position',
        'cust_id',
        'created_at',
        'created_by',
        'primary_contact',
        'password',
        'profile_image',
        'permission',
        'password_updated_at'
    ];
    protected $useTimestamps = false;
    protected $session;
    protected $db;
    public function __construct()
    {
        $this->db = \Config\Database::connect();
        $this->session = \Config\Services::session();
    }
    public function get_dtconfig_contacts()
    {
        $config = array(
            "columnNames" => ["sno", "name", "email", "position", "phone", "active", "action"],
            "sortable" => [0, 1, 0, 1, 0, 0, 0],
        );
        return json_encode($config);
    }

    public function get_primary_contact($email, $password, $remember_me)
    {

        $contact = $this->builder()->where('email', $email)->where('primary_contact', 1)->get()->getRowArray();
        $logger = \Config\Services::logger();
        if (empty($contact)) {
            return ['error' => 'Invalid email ID'];
        } elseif ($contact['active'] == 0) {
            return ['error' => 'Your account is inactive'];
        } elseif (!password_verify($password, $contact['password'])) {
            return ['error' => 'Password is wrong'];
        }

        // $logger->info($contact);

        if ($remember_me == true) {
            $this->doRememberMe($contact['cust_id']);
        } else {
            $this->updateLastLogin($contact['cust_id']);
        }

        //set session data
        $this->setSessionData($contact);

        //log activity

        $this->logActivity('Login', '[ User successfully logged in ]');

        return ['success' => true];
    }

    private function logActivity($title, $logText)
    {
        helper('erp');
        $config = ['title' => $title, 'log_text' => $logText, 'ref_link' => ''];
        log_activity($config);
    }

    private function doRememberMe($user_id)
    {
        $response = \Config\Services::response();
        $hash = sha1(bin2hex(random_bytes(10)) . time());
        $this->set(['remember' => $hash, 'last_login' => time()])->where('cust_id', $user_id);
        $response->setCookie("rhash", $hash, time() + 7 * 24 * 60 * 60);
    }

    private function updateLastLogin($user_id)
    {
        if (is_numeric($user_id)) {
            $this->set(['last_login' => time()])->where('cust_id', $user_id);
        } else {
            log_message('error', 'Invalid user ID provided for updating last login: ' . $user_id);
        }
    }

    private function setSessionData($result)
    {

        $session = \Config\Services::session(); // Load the session service

        $session->set('client_username', $result['firstname']);
        $session->set('client_cust_id', $result['cust_id']);
        $session->set('client_email', $result['email']);
        $session->set('contact_id', $result['contact_id']);
        // $session->set('erp_admin', $result['is_admin']);
        $session->set('client_rolename', "client");
        $perms = !empty($result['permission']) ? json_decode($result['permission'], true) : [];
        $session->set('permissions', $perms);
        $session->set('client_logged', true);
    }


    public function get_customercontact_datatable($cust_id)
    {

        $columns = array(
            "name" => "firstname",
            "email" => "email"
        );
        $limit = $_GET['limit'];
        $offset = $_GET['offset'];
        $search = $_GET['search'] ?? "";
        $orderby = isset($_GET['orderby']) ? strtoupper($_GET['orderby']) : "";
        $ordercol = isset($_GET['ordercol']) ? $columns[$_GET['ordercol']] : "";

        $query = "SELECT contact_id,firstname,lastname,email,phone,position,primary_contact,active,permission FROM customer_contacts WHERE cust_id=$cust_id";
        $count = "SELECT COUNT(contact_id) AS total FROM customer_contacts WHERE cust_id=$cust_id";
        $where = true;

        if (!empty($search)) {
            if (!$where) {
                $query .= " WHERE ( firstname LIKE '%" . $search . "%' OR email LIKE '%" . $search . "%' ) ";
                $count .= " WHERE ( firstname LIKE '%" . $search . "%' OR email LIKE '%" . $search . "%' ) ";
                $where = true;
            } else {
                $query .= " AND ( firstname LIKE '%" . $search . "%' OR email LIKE '%" . $search . "%' ) ";
                $count .= " AND ( firstname LIKE '%" . $search . "%' OR email LIKE '%" . $search . "%' ) ";
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
                                <li><a href="#" data-ajax-url="' . url_to('erp.crm.ajaxfetchcontact', $r['contact_id']) . '" title="Edit" class="bg-success modalBtn"><i class="fa fa-pencil"></i> </a></li>
                                <li><a href="' . url_to('erp.crm.customercontactdelete', $cust_id, $r['contact_id']) . '" title="Delete" class="bg-danger del-confirm"><i class="fa fa-trash"></i> </a></li>
                            </ul>
                        </div>
                    </div>';
            $active = '
                    <label class="toggle active-toggler-1" data-ajax-url="' . url_to('erp.crm.ajaxcontactactive', $r['contact_id']) . '" >
                        <input type="checkbox" ';
            if ($r['active'] == 1) {
                $active .= ' checked ';
            }
            $active .= ' />
                        <span class="togglebar"></span>
                    </label>';


            $name = "";

            if ($r["primary_contact"]) {
                $name = $r['firstname'] . ' ' . $r['lastname'] . ' ' . ' ' . '<span class="p-2" style="border-radius:12px;border: 1px solid #16b3ff;background-color: #e4f2fe;padding: 4px 15px !important;!i;!;font-size: 12px;color: #16b3ff;">Primary</span>';
            } else {
                $name = $r['firstname'] . ' ' . $r['lastname'];
            }

            array_push(
                $m_result,
                array(
                    $sno,
                    $r['contact_id'],
                    $name,
                    $r['email'],
                    $r['position'],
                    $r['phone'],
                    $active,
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

    public function get_customer_contacts_export($type)
    {
        $cust_id = $_GET["custid"];
        $columns = array(
            "name" => "firstname",
            "email" => "email"
        );
        $limit = $_GET['limit'];
        $offset = $_GET['offset'];
        $search = $_GET['search'] ?? "";
        $orderby = isset($_GET['orderby']) ? strtoupper($_GET['orderby']) : "";
        $ordercol = isset($_GET['ordercol']) ? $columns[$_GET['ordercol']] : "";

        $query = "SELECT firstname,lastname,email,phone,position,CASE WHEN active=1 THEN 'Yes' ELSE 'No' END AS active FROM customer_contacts WHERE cust_id=$cust_id";
        $where = true;

        if (!empty($search)) {
            if (!$where) {
                $query .= " WHERE ( firstname LIKE '%" . $search . "%' OR email LIKE '%" . $search . "%' ) ";
                $where = true;
            } else {
                $query .= " AND ( firstname LIKE '%" . $search . "%' OR email LIKE '%" . $search . "%' ) ";
            }
        }

        if (!empty($ordercol) && !empty($orderby)) {
            $query .= " ORDER BY " . $ordercol . " " . $orderby;
        }
        $result = $this->db->query($query)->getResultArray();
        return $result;
    }

    public function insert_update_customer_contact($cust_id, $post, $contact_id)
    {
        if (!empty($contact_id)) {
            $this->update_customer_contact($cust_id, $contact_id, $post);
        } else {
            $this->insert_customer_contact($cust_id, $post);
        }
    }

    private function update_customer_contact($cust_id, $contact_id, $post)
    {
        $firstname = $post["firstname"];
        $lastname = $post["lastname"];
        $email = $post["email"];
        $phone = $post["phone"];
        $position = $post["position"];
        $password = $post["password"];
        $primary_contact = $post["primary_contact"];
        $profile_image = $post["profile_image"] ?? '';
        $permission = $post["permission"];
        $created_by = get_user_id();
        $result = true;

        
        if ($profile_image) {

            $exist_image = $this->builder()->where('contact_id', $contact_id)->get()->getRowArray();

            if (!empty($exist_image['profile_image'])) {
                unlink(get_attachment_path("customer") . $exist_image['profile_image']);
            }
            if (empty($password)) {
                $query = "UPDATE customer_contacts SET firstname=? , lastname=? , email=? , position=? , phone=? , cust_id=?, primary_contact=?, profile_image=?, permission=? WHERE contact_id=$contact_id ";
                $otput = $this->db->query($query, array($firstname, $lastname, $email, $position, $phone, $cust_id, $primary_contact, $profile_image, $permission));
            } else {
                $query = "UPDATE customer_contacts SET firstname=? , lastname=? , email=? , position=? , phone=? , cust_id=?, password =?, primary_contact=?, profile_image=?, permission=? WHERE contact_id=$contact_id ";
                $otput = $this->db->query($query, array($firstname, $lastname, $email, $position, $phone, $cust_id, $password, $primary_contact, $profile_image, $permission));
            }

        } else {

            if (empty($password)) {
                

                $query = "UPDATE customer_contacts SET firstname=? , lastname=? , email=? , position=? , phone=? , cust_id=?, primary_contact=?, permission=? WHERE contact_id=$contact_id ";
                $otput = $this->db->query($query, array($firstname, $lastname, $email, $position, $phone, $cust_id, $primary_contact, $permission));

            } else {
                $query = "UPDATE customer_contacts SET firstname=? , lastname=? , email=? , position=? , phone=? , cust_id=?, password =?, primary_contact=?, permission=? WHERE contact_id=$contact_id ";
                $otput = $this->db->query($query, array($firstname, $lastname, $email, $position, $phone, $cust_id, $password, $primary_contact, $permission));
            }
        }

        $config['title'] = "Customer Contact Update";
        if ($otput) {
            if ($primary_contact == 1) {
                $getallcontact = $this->builder()->select()->where("cust_id", $cust_id)->where("primary_contact", 1)->where("contact_id !=", $contact_id)->get()->getResultArray();
                $array_id = array();
                foreach ($getallcontact as $key => $value) {
                    array_push($array_id, $value['contact_id']);
                }

                $count = 0;

                if (count($array_id) > 0) {
                    foreach ($array_id as $key => $v) {

                        $data_update['primary_contact'] = 0;
                        $res = $this->builder()->where('contact_id', $v)->update($data_update);
                        if (!$res) {
                            $result = false;
                        }

                        $count += 1;
                    }

                }
            }

            if (!$result) {
                $config['title'] = "Customer Contact Update";
                $config['log_text'] = "[ Customer Contact failed to update ]";
                $config['ref_link'] = url_to('erp.crm.customerview', $cust_id);
                $this->session->setFlashdata("op_error", "Customer Contact failed to update error : <Primary contact>");
            }

            $config['log_text'] = "[ Customer Contact successfully updated ]";
            $config['ref_link'] = url_to('erp.crm.customerview', $cust_id);
            $this->session->setFlashdata("op_success", "Customer Contact successfully updated");
        } else {
            
            if (!empty($profile_image)) {
                unlink(get_attachment_path("customer") . $profile_image);
            }

            $config['log_text'] = "[ Customer Contact failed to update ]";
            $config['ref_link'] = url_to('erp.crm.customerview', $cust_id);
            $this->session->setFlashdata("op_error", "Customer Contact failed to update");
        }
        log_activity($config);
    }

    private function insert_customer_contact($cust_id, $post)
    {
        $firstname = $post["firstname"];
        $lastname = $post["lastname"];
        $email = $post["email"];
        $phone = $post["phone"];
        $position = $post["position"];
        $password = $post["password"];
        $primary_contact = $post["primary_contact"];
        $profile_image = $post["profile_image"] ?? '';
        $permission = $post["permission"];
        $created_by = get_user_id();
        $result = true;

        $getallcount = $this->builder()->select()->where("cust_id", $cust_id)->where("primary_contact", 1)->get()->getResultArray();
        if(count($getallcount) == 0){
            $primary_contact = 1;
        }

        if (empty($password)) {
            $config['title'] = "Customer Contact Update";
            $config['log_text'] = "[ Customer Contact failed to update ]";
            $config['ref_link'] = url_to('erp.crm.customerview', $cust_id);
            $this->session->setFlashdata("op_error", "Password Field is Required!");
            return;
        }

        $query = "INSERT INTO customer_contacts(firstname,lastname,email,position,phone,cust_id,created_at,created_by,password, primary_contact, profile_image,permission) VALUES(?,?,?,?,?,?,?,?,?,?,?,?) ";
        $this->db->query($query, array($firstname, $lastname, $email, $position, $phone, $cust_id, time(), $created_by, $password, $primary_contact, $profile_image, $permission));
        $contact_id = $this->db->insertId();

        $config['title'] = "Customer Contact Insert";
        if (!empty($contact_id)) {
            if ($primary_contact == 1) {
                $getallcontact = $this->builder()->select()->where("cust_id", $cust_id)->where("primary_contact", 1)->get()->getResultArray();
                $array_id = array();
                foreach ($getallcontact as $key => $value) {
                    array_push($array_id, $value['contact_id']);
                }

                $count = 0;

                if (count($array_id) > 0) {
                    foreach ($array_id as $key => $v) {

                        $data_update['primary_contact'] = 0;
                        $res = $this->builder()->where('contact_id', $v)->update($data_update);
                        if (!$res) {
                            $result = false;
                        }

                        $count += 1;
                    }

                }
            }

            if (!$result) {
                $config['title'] = "Customer Contact Update";
                $config['log_text'] = "[ Customer Contact failed to update ]";
                $config['ref_link'] = url_to('erp.crm.customerview', $cust_id);
                $this->session->setFlashdata("op_error", "Customer Contact failed to update error : <Primary contact>");
            }

            $config['log_text'] = "[ Customer Contact successfully created ]";
            $config['ref_link'] = url_to('erp.crm.customerview', $cust_id);
            $this->session->setFlashdata("op_success", "Customer Contact successfully created");
        } else {
            if (!empty($profile_image)) {
                unlink(get_attachment_path("customer") . $profile_image);
            }

            $config['log_text'] = "[ Customer Contact failed to create ]";
            $config['ref_link'] = url_to('erp.crm.customerview', $cust_id);
            $this->session->setFlashdata("op_error", "Customer Contact failed to create");
        }
        log_activity($config);
    }

    //Contact delete

    public function get_customercontact_by_id($cust_id)
    {
        return $this->db->table('customer_contacts')->select()->where('cust_id', $cust_id)->get()->getRow();
    }

    public function delete_contact($cust_id)
    {
        $deleted = false;
        $customer = $this->get_customercontact_by_id($cust_id);
        $this->builder()->where('cust_id', $cust_id)->delete();

        if ($this->db->affectedRows() > 0) {
            $deleted = true;


            $config = [
                'title' => 'Customer Deletion',
                'log_text' => "[ Customer successfully deleted ]",
                'ref_link' => 'erp.crm.customercontactdelete' . $cust_id,
                'additional_info' => json_encode($customer),
            ];
            log_activity($config);
        } else {
            $config = [
                'title' => 'Customer Deletion',
                'log_text' => "[ Customer failed to delete ]",
                'ref_link' => '' . $cust_id,
            ];
            log_activity($config);
        }
        return $deleted;
    }

    public function getContactbyId($contact_id){
        $result = $this->builder()->where("contact_id",$contact_id)->get()->getRowArray();
        return $result;
    }
}
