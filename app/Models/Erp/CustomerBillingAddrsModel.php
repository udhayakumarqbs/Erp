<?php

namespace App\Models\Erp;

use App\Libraries\Scheduler;
use CodeIgniter\Model;

class CustomerBillingAddrsModel extends Model
{
    protected $table = 'customer_billingaddr';
    protected $primaryKey = 'billingaddr_id';
    protected $allowedFields = [
        'address', 'city', 'state', 'country', 'zipcode',
        'cust_id', 'created_at', 'created_by'
    ];
    protected $useTimestamps = false;
    protected $session;
    protected $db;
    public function __construct()
    {
        $this->db = \Config\Database::connect();
        $this->session = \Config\Services::session();
    }
    public function get_dtconfig_billing()
    {
        $config = array(
            "columnNames" => ["sno", "address", "city", "state", "country", "zipcode", "action"],
            "sortable" => [0, 0, 1, 1, 1, 0, 0],
        );
        return json_encode($config);
    }

    public function get_customerbilling_datatable($cust_id)
    {
        $columns = array(
            "city" => "city",
            "state" => "state",
            "country" => "country"
        );
        $limit = $_GET['limit'];
        $offset = $_GET['offset'];
        $search = $_GET['search'] ?? "";
        $orderby = isset($_GET['orderby']) ? strtoupper($_GET['orderby']) : "";
        $ordercol = isset($_GET['ordercol']) ? $columns[$_GET['ordercol']] : "";

        $query = "SELECT billingaddr_id,address,city,state,country,zipcode FROM customer_billingaddr WHERE cust_id=$cust_id";
        $count = "SELECT COUNT(billingaddr_id) AS total FROM customer_billingaddr WHERE cust_id=$cust_id";
        $where = true;

        if (!empty($search)) {
            if (!$where) {
                $query .= " WHERE ( city LIKE '%" . $search . "%' OR state LIKE '%" . $search . "%' OR country LIKE '%" . $search . "%' ) ";
                $count .= " WHERE ( city LIKE '%" . $search . "%' OR state LIKE '%" . $search . "%' OR country LIKE '%" . $search . "%' ) ";
                $where = true;
            } else {
                $query .= " AND ( city LIKE '%" . $search . "%' OR state LIKE '%" . $search . "%' OR country LIKE '%" . $search . "%' ) ";
                $count .= " AND ( city LIKE '%" . $search . "%' OR state LIKE '%" . $search . "%' OR country LIKE '%" . $search . "%' ) ";
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
                                <li><a href="#" data-ajax-url="' . url_to('erp.crm.ajaxfetchbilling', $r['billingaddr_id']) . '" title="Edit" class="bg-success modalBtn"><i class="fa fa-pencil"></i> </a></li>
                                <li><a href="' . url_to('erp.crm.customerbillingdelete', $cust_id, $r['billingaddr_id']) . '" title="Delete" class="bg-danger del-confirm"><i class="fa fa-trash"></i> </a></li>
                            </ul>
                        </div>
                    </div>';
            array_push(
                $m_result,
                array(
                    $r['billingaddr_id'],
                    $sno,
                    $r['address'],
                    $r['city'],
                    $r['state'],
                    $r['country'],
                    $r['zipcode'],
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


    public function get_customer_billing_export($type, $cust_id)
    {

        $columns = array(
            "city" => "city",
            "state" => "state",
            "country" => "country"
        );
        $limit = $_GET['limit'];
        $offset = $_GET['offset'];
        $search = $_GET['search'] ?? "";
        $orderby = isset($_GET['orderby']) ? strtoupper($_GET['orderby']) : "";
        $ordercol = isset($_GET['ordercol']) ? $columns[$_GET['ordercol']] : "";

        $query = "SELECT address,city,state,country,zipcode FROM customer_billingaddr WHERE cust_id=$cust_id";
        $where = true;

        if (!empty($search)) {
            if (!$where) {
                $query .= " WHERE ( city LIKE '%" . $search . "%' OR state LIKE '%" . $search . "%' OR country LIKE '%" . $search . "%' ) ";
                $where = true;
            } else {
                $query .= " AND ( city LIKE '%" . $search . "%' OR state LIKE '%" . $search . "%' OR country LIKE '%" . $search . "%' ) ";
            }
        }

        if (!empty($ordercol) && !empty($orderby)) {
            $query .= " ORDER BY " . $ordercol . " " . $orderby;
        }
        $query .= " LIMIT $offset,$limit ";
        $result = $this->db->query($query)->getResultArray();
        return $result;
    }

    
    public function deleteByCustId($custId, $billingaddr_id)
    {
        $jobresult = $this->builder()->where('billingaddr_id', $billingaddr_id)->get()->getRow();
        $config['title'] = "Customer Billing Address Delete";
        if (!empty($jobresult)) {
            $this->db->table('customer_billingaddr')->where('billingaddr_id', $billingaddr_id)->delete();
            $config['log_text'] = "[ Customer Billing Address successfully deleted ]";
            $config['ref_link'] = route_to('erp.crm.customerview', $custId);
            $this->session->setFlashdata("op_success", "Customer Billing Address successfully deleted");
        } else {
            $config['log_text'] = "[ Customer Billing Address failed to delete ]";
            $config['ref_link'] = route_to('erp.crm.customerview', $custId);
            $this->session->setFlashdata("op_success", "Customer Billing Address failed to delete");
        }

        log_activity($config);
    }

    public function insert_update_customer_billingaddr($cust_id, $billingaddr_id, $post)
    {
        if (empty($billingaddr_id)) {
            $this->insert_customer_billingaddr($cust_id, $post);
        } else {
            $this->update_customer_billingaddr($cust_id, $billingaddr_id, $post);
        }
    }

    private function insert_customer_billingaddr($cust_id, $post)
    {
        $address = $post["address"];
        $city = $post["city"];
        $state = $post["state"];
        $country = $post["country"];
        $zip = $post["zip"];

        $created_by = get_user_id();
        $query = "INSERT INTO customer_billingaddr(address,city,state,country,zipcode,cust_id,created_at,created_by) VALUES(?,?,?,?,?,?,?,?) ";
        $this->db->query($query, array($address, $city, $state, $country, $zip, $cust_id, time(), $created_by));
        $billingaddr_id = $this->db->insertId();

        $config['title'] = "Customer Billing Address Insert";
        if (!empty($billingaddr_id)) {
            $config['log_text'] = "[ Customer Billing Address successfully created ]";
            $config['ref_link'] = url_to('erp.crm.customerview', $cust_id);
            $this->session->setFlashdata("op_success", "Customer Billing Address successfully created");
        } else {
            $config['log_text'] = "[ Customer Billing Address failed to create ]";
            $config['ref_link'] = url_to('erp.crm.customerview', $cust_id);
            $this->session->setFlashdata("op_error", "Customer Billing Address failed to create");
        }
        log_activity($config);
    }

    private function update_customer_billingaddr($cust_id, $billingaddr_id, $post)
    {
        $address = $post["address"];
        $city = $post["city"];
        $state = $post["state"];
        $country = $post["country"];
        $zip = $post["zip"];

        $query = "UPDATE customer_billingaddr SET address=? , city=? , state=? , country=? , zipcode=? , cust_id=? WHERE billingaddr_id=$billingaddr_id";
        $this->db->query($query, array($address, $city, $state, $country, $zip, $cust_id));

        $config['title'] = "Customer Billing Address Update";
        if ($this->db->affectedRows() > 0) {
            $config['log_text'] = "[ Customer Billing Address successfully updated ]";
            $config['ref_link'] = url_to('erp.crm.customerview', $cust_id);
            $this->session->setFlashdata("op_success", "Customer Billing Address successfully updated");
        } else {
            $config['log_text'] = "[ Customer Billing Address failed to update ]";
            $config['ref_link'] = url_to('erp.crm.customerview', $cust_id);
            $this->session->setFlashdata("op_error", "Customer Billing Address failed to update");
        }
        log_activity($config);
    }

    public function getBillingData($cust_id){
        return $this->builder()->where('cust_id', $cust_id)->countAllResults();
    }
}
