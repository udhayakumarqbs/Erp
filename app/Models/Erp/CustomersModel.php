<?php

namespace App\Models\Erp;

use CodeIgniter\Model;

class CustomersModel extends Model
{
    protected $table = 'customers';
    protected $primaryKey = 'cust_id';
    protected $allowedFields = [
        'name',
        'position',
        'address',
        'city',
        'state',
        'country',
        'zip',
        'email',
        'phone',
        'fax_num',
        'office_num',
        'company',
        'gst',
        'website',
        'description',
        'remarks',
        'created_at',
        'created_by'
    ];
    protected $useTimestamps = false;
    protected $session;
    protected $db;
    public function __construct()
    {
        $this->db = \Config\Database::connect();
        $this->session = \Config\Services::session();
    }

    public function get_dtconfig_customers()
    {
        $config = array(
            "columnNames" => ["sno", "name", "company", "email", "position", "phone", "group", "action"],
            "sortable" => [0, 1, 0, 1, 0, 0, 0, 0],
            "filters" => ["group"]
        );
        return json_encode($config);
    }

    public function get_customers_datatable()
    {
        $columns = array(
            "name" => "name",
            "email" => "email",
            "group" => "erp_groups.group_id"
        );
        $limit = $_GET['limit'];
        $offset = $_GET['offset'];
        $search = $_GET['search'] ?? "";

        $orderby = isset($_GET['orderby']) ? strtoupper($_GET['orderby']) : "";
        $ordercol = isset($_GET['ordercol']) ? $columns[$_GET['ordercol']] : "";

        $query = "SELECT cust_id,name,email,position,company,phone, IFNULL(GROUP_CONCAT(erp_groups.group_name SEPARATOR ',' ),'') AS groups FROM customers LEFT JOIN erp_groups_map ON customers.cust_id=erp_groups_map.related_id JOIN erp_groups ON erp_groups_map.group_id=erp_groups.group_id WHERE erp_groups.related_to='customer' ";
        $count = "SELECT COUNT(cust_id) AS total FROM customers LEFT JOIN erp_groups_map ON customers.cust_id=erp_groups_map.related_id JOIN erp_groups ON erp_groups_map.group_id=erp_groups.group_id WHERE erp_groups.related_to='customer' ";

        $where = true;
        $filter_1_col = isset($_GET['group']) ? $columns['group'] : "";
        $filter_1_val = $_GET['group'];

        if (!empty($filter_1_col) && $filter_1_val !== "") {
            if ($where) {
                $query .= " AND " . $filter_1_col . " IN (" . $filter_1_val . ") ";
                $count .= " AND " . $filter_1_col . " IN (" . $filter_1_val . ") ";
            } else {
                $query .= " WHERE " . $filter_1_col . " IN (" . $filter_1_val . ") ";
                $count .= " WHERE " . $filter_1_col . " IN (" . $filter_1_val . ") ";
                $where = true;
            }
        }

        if (!empty($search)) {
            if (!$where) {
                $query .= " WHERE ( company LIKE '%" . $search . "%' OR name LIKE '%" . $search . "%' OR email LIKE '%" . $search . "%' ) ";
                $count .= " WHERE ( company LIKE '%" . $search . "%' OR name LIKE '%" . $search . "%' OR email LIKE '%" . $search . "%' ) ";
                $where = true;
            } else {
                $query .= " AND ( company LIKE '%" . $search . "%' OR name LIKE '%" . $search . "%' OR email LIKE '%" . $search . "%' ) ";
                $count .= " AND ( company LIKE '%" . $search . "%' OR name LIKE '%" . $search . "%' OR email LIKE '%" . $search . "%' ) ";
            }
        }
        $query .= " GROUP BY cust_id";
        $count .= " GROUP BY cust_id";
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
                                <li><a target="_BLANK" href="' . url_to('erp.crm.customerview', $r['cust_id']) . '" title="View" class="bg-warning"><i class="fa fa-eye"></i> </a></li>
                                <li><a href="' . url_to('erp.crm.customeredit', $r['cust_id']) . '" title="Edit" class="bg-success"><i class="fa fa-pencil"></i> </a></li>
                                <li><a href="' . url_to('erp.crm.customerdelete', $r['cust_id']) . '" title="Delete" class="bg-danger del-confirm"><i class="fa fa-trash"></i> </a></li>
                            </ul>
                        </div>
                    </div>';
            array_push(
                $m_result,
                array(
                    $r['cust_id'],
                    $sno,
                    $r['name'],
                    $r['company'],
                    $r['email'],
                    $r['position'],
                    $r['phone'],
                    $r['groups'],
                    $action
                )
            );
            $sno++;
        }
        $response = array();
        $response['total_rows'] = count($this->db->query($count)->getResultArray());
        $response['data'] = $m_result;
        return $response;
    }

    public function get_clients_count(){
        $query = "SELECT cust_id,name,email,position,company,phone, IFNULL(GROUP_CONCAT(erp_groups.group_name SEPARATOR ',' ),'') AS groups FROM customers LEFT JOIN erp_groups_map ON customers.cust_id=erp_groups_map.related_id JOIN erp_groups ON erp_groups_map.group_id=erp_groups.group_id WHERE erp_groups.related_to='customer' ";
        $result = count($this->db->query($query)->getResultArray());

        return $result;
    }

    public function get_customers_export($type)
    {
        $columns = array(
            "name" => "customers.name",
            "email" => "customers.email",
            "group" => "erp_groups.group_id"
        );

        $search = $_GET['search'] ?? "";
        $orderby = isset($_GET['orderby']) ? strtoupper($_GET['orderby']) : "";
        $ordercol = isset($_GET['ordercol']) ? $columns[$_GET['ordercol']] : "";

        $query = "";
        if ($type == "pdf") {
            $query = "SELECT customers.name,customers.email,customers.phone,customers.position,customers.company,customers.gst,GROUP_CONCAT(erp_groups.group_name SEPARATOR ',' ) AS groups FROM customers JOIN erp_groups_map ON customers.cust_id=erp_groups_map.related_id JOIN erp_groups ON erp_groups_map.group_id=erp_groups.group_id WHERE erp_groups.related_to='customer' ";
        } else {
            $query = "SELECT customers.name,customers.email,customers.phone,customers.fax_num,customers.office_num,customers.position,customers.company,customers.gst,GROUP_CONCAT(erp_groups.group_name SEPARATOR ',' ) AS groups,customers.address,customers.city,customers.state,customers.country,customers.zip,customers.website FROM customers JOIN erp_groups_map ON customers.cust_id=erp_groups_map.related_id JOIN erp_groups ON erp_groups_map.group_id=erp_groups.group_id WHERE erp_groups.related_to='customer' ";
        }

        $where = true;
        $filter_1_col = isset($_GET['group']) ? $columns['group'] : "";
        $filter_1_val = $_GET['group'];

        if (!empty($filter_1_col) && $filter_1_val !== "") {
            if ($where) {
                $query .= " AND " . $filter_1_col . " IN (" . $filter_1_val . ") ";
            } else {
                $query .= " WHERE " . $filter_1_col . " IN (" . $filter_1_val . ") ";
                $where = true;
            }
        }

        if (!empty($search)) {
            if (!$where) {
                $query .= " WHERE ( customers.company LIKE '%" . $search . "%' OR customers.name LIKE '%" . $search . "%' OR customers.email LIKE '%" . $search . "%' ) ";
                $where = true;
            } else {
                $query .= " AND ( customers.company LIKE '%" . $search . "%' OR customers.name LIKE '%" . $search . "%' OR customers.email LIKE '%" . $search . "%' ) ";
            }
        }
        $query .= " GROUP BY cust_id";
        if (!empty($ordercol) && !empty($orderby)) {
            $query .= " ORDER BY " . $ordercol . " " . $orderby;
        }
        $result = $this->db->query($query)->getResultArray();
        return $result;
    }

    public function insert_customer($post)
    {
        $inserted = false;
        $name = $post["name"];
        $position = $post["position"];
        $address = $post["address"];
        $state = $post["state"];
        $city = $post["city"];
        $country = $post["country"];
        $zip = $post["zip"];
        $website = $post["website"];
        $company = $post["company"];
        $description = $post["description"];
        $remarks = $post["remarks"];
        $email = $post["email"];
        $phone = $post["phone"];
        $fax_number = $post["fax_number"];
        $office_number = $post["office_number"];
        $gst = $post["gst"];

        $created_by = get_user_id();
        $this->db->transBegin();
        $query = "INSERT INTO customers(name,position,address,state,city,country,zip,website,company,description,remarks,email,phone,fax_num,office_num,gst,created_at,created_by) VALUES(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?) ";
        $this->db->query($query, array($name, $position, $address, $state, $city, $country, $zip, $website, $company, $description, $remarks, $email, $phone, $fax_number, $office_number, $gst, time(), $created_by));
        $id = $this->db->insertId();

        if (empty($id)) {
            $this->session->setFlashdata("op_error", "Customer failed to create");
            $this->db->transRollback();
            return $inserted;
        }

        if (!$this->update_customer_groups($id)) {
            $this->session->setFlashdata("op_error", "Customer failed to create");
            $this->db->transRollback();
            return $inserted;
        }

        if (!$this->update_customer_cfv($id)) {
            $this->session->setFlashdata("op_error", "Customer failed to create");
            $this->db->transRollback();
            return $inserted;
        }

        $config['title'] = "Customer Insert";
        $this->db->transComplete();
        if ($this->db->transStatus() === FALSE) {
            $this->db->transRollback();
            $config['log_text'] = "[ Customer failed to create ]";
            $config['ref_link'] = "";
            $this->session->setFlashdata("op_error", "Customer failed to create");
        } else {
            $this->db->transCommit();
            $inserted = true;
            $config['log_text'] = "[ Customer successfully created ]";
            $config['ref_link'] = 'erp/crm/customerview/' . $id;
            $this->session->setFlashdata("op_success", "Customer successfully created");
        }
        log_activity($config);
        return $inserted;
    }

    private function update_customer_cfv($id)
    {
        $updated = true;
        $query = "DELETE cfv FROM custom_field_values cfv INNER JOIN custom_fields cf ON cfv.cf_id=cf.cf_id WHERE cf.field_related_to='customer' AND cfv.related_id=$id";
        $this->db->query($query);
        $checkbox = array();
        $query = "INSERT INTO custom_field_values(cf_id,related_id,field_value) VALUES(?,?,?) ";
        foreach ($_POST as $key => $value) {
            if (stripos($key, "cf_checkbox_") === 0) {
                $checkbox[$key] = $value;
            } else if (stripos($key, "cf_") === 0) {
                $cf_id = substr($key, strlen("cf_"));
                $this->db->query($query, array($cf_id, $id, $value));
                if (empty($this->db->insertId())) {
                    $updated = false;
                    break;
                }
            }
        }
        $checkbox_counter = intval($_POST["customfield_chkbx_counter"]);
        for ($i = 0; $i <= $checkbox_counter; $i++) {
            $values = array();
            $cf_id = 0;
            foreach ($checkbox as $key => $value) {
                if (stripos($key, "cf_checkbox_" . $i . "_") === 0) {
                    array_push($values, $value);
                    $cf_id = explode("_", $key)[3];
                    //$cf_id=substr($key,strlen("cf_checkbox_".$i."_"),stripos($key,"_",strlen("cf_checkbox_".$i."_")+1));
                }
            }
            if (!empty($cf_id)) {
                $this->db->query($query, array($cf_id, $id, implode(",", $values)));
                if (empty($this->db->insertId())) {
                    $updated = false;
                    break;
                }
            }
        }
        return $updated;
    }

    private function update_customer_groups($id)
    {
        $updated = true;
        $query = "DELETE g2 FROM erp_groups_map g2 INNER JOIN erp_groups g1 ON g2.group_id=g1.group_id WHERE g1.related_to='customer' AND g2.related_id=$id";
        $this->db->query($query);
        $groups = explode(",", $_POST["groups"]);
        $query = "INSERT INTO erp_groups_map(group_id,related_id) VALUES(?,?) ";
        foreach ($groups as $group) {
            $this->db->query($query, array($group, $id));
            if (empty($this->db->insertId())) {
                $updated = false;
                break;
            }
        }
        return $updated;
    }

    public function update_customer($id, $post)
    {
        $updated = false;
        $name = $post["name"];
        $position = $post["position"];
        $address = $post["address"];
        $state = $post["state"];
        $city = $post["city"];
        $country = $post["country"];
        $zip = $post["zip"];
        $website = $post["website"];
        $company = $post["company"];
        $description = $post["description"];
        $remarks = $post["remarks"];
        $email = $post["email"];
        $phone = $post["phone"];
        $fax_number = $post["fax_number"];
        $office_number = $post["office_number"];
        $gst = $post["gst"];

        $this->db->transBegin();
        $query = "UPDATE customers SET name=? , position=? , address=? , state=? , city=? , country=? , zip=? , website=? , company=? , description=? , remarks=? , email=? , phone=? , fax_num=? , office_num=? , gst=? WHERE cust_id=$id";
        $this->db->query($query, array($name, $position, $address, $state, $city, $country, $zip, $website, $company, $description, $remarks, $email, $phone, $fax_number, $office_number, $gst));

        if ($this->db->affectedRows() < 0) {
            $this->session->setFlashdata("op_error", "Customer failed to update");
            $this->db->transRollback();
            return $updated;
        }

        if (!$this->update_customer_groups($id)) {
            $this->session->setFlashdata("op_error", "Customer failed to update");
            $this->db->transRollback();
            return $updated;
        }

        if (!$this->update_customer_cfv($id)) {
            $this->session->setFlashdata("op_error", "Customer failed to update");
            $this->db->transRollback();
            return $updated;
        }

        $config['title'] = "Customer Update";
        $this->db->transComplete();
        if ($this->db->transStatus() === FALSE) {
            $this->db->transRollback();
            $config['log_text'] = "[ Customer failed to update ]";
            $config['ref_link'] = "";
            $this->session->setFlashdata("op_error", "Customer failed to update");
        } else {
            $this->db->transCommit();
            $updated = true;
            $config['log_text'] = "[ Customer successfully updated ]";
            $config['ref_link'] = 'erp/crm/customerview/' . $id;
            $this->session->setFlashdata("op_success", "Customer successfully updated");
        }
        log_activity($config);
        return $updated;
    }

    public function get_customer_by_id($id)
    {
        $query = "SELECT cust_id,name,email,phone,fax_num,office_num,company,position,website,description,remarks,address,city,state,country,zip,gst,GROUP_CONCAT(erp_groups.group_id SEPARATOR ',') AS groups FROM customers JOIN erp_groups_map ON customers.cust_id=erp_groups_map.related_id JOIN erp_groups ON erp_groups_map.group_id=erp_groups.group_id  WHERE erp_groups.related_to='customer' AND cust_id=$id";
        $result = $this->db->query($query)->getRow();
        return $result;
    }



    public function get_customer_for_view($id)
    {
        $query = "SELECT cust_id,name,email,phone,fax_num,office_num,company,position,website,description,remarks,address,city,state,country,zip,gst,GROUP_CONCAT(erp_groups.group_name SEPARATOR ',') AS groups FROM customers JOIN erp_groups_map ON customers.cust_id=erp_groups_map.related_id JOIN erp_groups ON erp_groups_map.group_id=erp_groups.group_id  WHERE erp_groups.related_to='customer' AND cust_id=$id";
        $result = $this->db->query($query)->getRow();
        return $result;
    }

    public function getCustomer()
    {
        return $this->builder()->select("cust_id,company")->get()->getResultArray();
    }
    public function getCustomerName($relatedOptionValue)
    {
        return $this->builder()->select("company as name")->where('cust_id', $relatedOptionValue)->get()->getRow();
    }

    //customer delete

    public function get_crm_customer_by_id($cust_id)
    {
        return $this->db->table('customers')->select()->where('cust_id', $cust_id)->get()->getRow();
    }

    public function delete_customer($cust_id)
    {
        $deleted = false;
        $customer = $this->get_crm_customer_by_id($cust_id);
        $this->builder()->where('cust_id', $cust_id)->delete();

        if ($this->db->affectedRows() > 0) {
            $deleted = true;


            $config = [
                'title' => 'Customer Deletion',
                'log_text' => "[ Customer successfully deleted ]",
                'ref_link' => 'erp.crm.customerdelete' . $cust_id,
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

    public function getCustomersCompanyName()
    {
        return $this->builder()->select('company, cust_id')->get()->getResultArray();
    }
    public function fetch_customer()
    {
        return $this->builder()->select('cust_id,company')->get()->getResultArray();
    }
    public function getCustomerById($id, $type = 'array')
    {
        return $this->builder()->select()->where("cust_id ", $id)->get()->getRow(0, $type);
    }


    public function getCustomerContactInfo($id)
    {
        $contactModel = new CustomerContactsModel();

        $contact = $contactModel->get_customercontact_by_id($id);
        $client = $this->getCustomerById($id, 'object');

        return [$contact, $client];
    }

    public function dashboard(){
        
    }

}
