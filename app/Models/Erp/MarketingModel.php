<?php

namespace App\Models\Erp;

use App\Libraries\Finance\AbstractAutomation;

use App\Libraries\FinanceAutomation;
use CodeIgniter\Model;

class MarketingModel extends Model
{
    protected $table = 'marketing';
    protected $primaryKey = 'marketing_id';
    protected $allowedFields = [
        'name', 'related_to', 'related_id', 'amount', 'done_by',
        'company', 'address', 'active', 'email', 'phone1', 'phone2',
        'description', 'created_at', 'created_by'
    ];
    protected $useTimestamps = false;
    protected $db;
    protected $session;
    public function __construct()
    {
        $this->db = \Config\Database::connect();
        $this->session = \Config\Services::session();
    }
    private $marketing_products = array(
        "finished_good" => "Finished Goods",
        "semi_finished" => "Semi Finished",
        "trading_good" => "Trading Goods",
        "inventory_service" => "Services"
    );
    private $marketing_products_link = array(
        "finished_good" => 'erp/crm/get_fetchfinishedgoods',
        "semi_finished" => 'erp/crm/ajaxfetchsemifinished',
        "trading_good" => 'erp/crm/ajaxfetchtradinggoods',
        "inventory_service" => 'erp/crm/ajaxfetchinventoryservices'
    );
    public function get_marketing_products_link()
    {
        return $this->marketing_products_link;
    }
    public function get_marketing_products()
    {
        return $this->marketing_products;
    }

    public function get_dtconfig_marketing()
    {
        $config = array(
            "columnNames" => ["sno", "name", "product type", "product", "amount", "done by", "active", "action"],
            "sortable" => [0, 1, 0, 0, 1, 0, 0, 0],
            "filters" => ["related_to"]
        );
        return json_encode($config);
    }

    public function get_marketing_datatable()
    {
        $columns = array(
            "name" => "marketing.name",
            "amount" => "amount",
            "related_to" => "related_to"
        );
        $limit = $_GET['limit'];
        $offset = $_GET['offset'];
        $search = $_GET['search'] ?? "";

        $orderby = isset($_GET['orderby']) ? strtoupper($_GET['orderby']) : "";
        $ordercol = isset($_GET['ordercol']) ? $columns[$_GET['ordercol']] : "";
        $query = "SELECT marketing_id,marketing.name AS name,related_to,amount,done_by,active, COALESCE(CONCAT(finished_goods.name,' ',finished_goods.code),CONCAT(semi_finished.name,' ',semi_finished.code)) AS product FROM marketing LEFT JOIN finished_goods ON related_id=finished_goods.finished_good_id AND related_to='finished_good' LEFT JOIN semi_finished ON related_id=semi_finished.semi_finished_id AND related_to='semi_finished' ";
        $count = "SELECT COUNT(marketing_id) AS total FROM marketing LEFT JOIN finished_goods ON related_id=finished_goods.finished_good_id AND related_to='finished_good' LEFT JOIN semi_finished ON related_id=semi_finished.semi_finished_id AND related_to='semi_finished' ";
        $where = false;
        $filter_1_col = isset($_GET['related_to']) ? $columns['related_to'] : "";
        $filter_1_val = $_GET['related_to'];

        if (!empty($filter_1_col) && $filter_1_val !== "") {
            $query .= " WHERE " . $filter_1_col . "='" . $filter_1_val . "' ";
            $count .= " WHERE " . $filter_1_col . "='" . $filter_1_val . "' ";
            $where = true;
        }

        if (!empty($search)) {
            if (!$where) {
                $query .= " WHERE marketing.name LIKE '%" . $search . "%' ";
                $count .= " WHERE marketing.name LIKE '%" . $search . "%' ";
                $where = true;
            } else {
                $query .= " AND marketing.name LIKE '%" . $search . "%' ";
                $count .= " AND marketing.name LIKE '%" . $search . "%' ";
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
                                <li><a href="' . url_to('erp.crm.marketingedit', $r['marketing_id']) . '" title="Edit" class="bg-success"><i class="fa fa-pencil"></i> </a></li>
                                <li><a href="' . url_to('erp.crm.marketingdelete', $r['marketing_id']) . '" title="Delete" class="bg-danger del-confirm"><i class="fa fa-trash"></i> </a></li>
                            </ul>
                        </div>
                    </div>';
            $active = '
                    <label class="toggle active-toggler-1" data-ajax-url="' . url_to('erp.crm.ajaxmarketingactive', $r['marketing_id']) . '" >
                        <input type="checkbox" ';
            if ($r['active'] == 1) {
                $active .= ' checked ';
            }
            $active .= ' />
                        <span class="togglebar"></span>
                    </label>';
            array_push(
                $m_result,
                array(
                    $r['marketing_id'],
                    $sno,
                    $r['name'],
                    $this->marketing_products[$r['related_to']],
                    $r['product'],
                    $r['amount'],
                    $r['done_by'],
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

    public function get_marketing_by_id($marketing_id)
    {
        $query = "SELECT * FROM marketing WHERE marketing_id=$marketing_id";
        $result = $this->db->query($query)->getRow();
        return $result;
    }

    public function update_marketing($marketing_id, $post)
    {
        $updated = false;
        $name = $post["name"];
        $amount = $post["amount"];
        $related_to = $post["related_to"];
        $related_id = $post["related_id"];
        $done_by = $post["done_by"];
        $email = $post["email"];
        $phone1 = $post["phone1"];
        $phone2 = $post["phone2"];
        $company = $post["company"];
        $address = $post["address"];
        $description = $post["description"];

        $query = "UPDATE marketing SET name=? , amount=? , related_to=? , related_id=? , done_by=? , email=? , phone1=? , phone2=? , company=? , address=? , description=? WHERE marketing_id=$marketing_id ";
        $this->db->query($query, array($name, $amount, $related_to, $related_id, $done_by, $email, $phone1, $phone2, $company, $address, $description));
        $affected_rows1 = $this->db->affectedRows();
        $query = "UPDATE lead_source SET source_name=? WHERE marketing_id=$marketing_id ";
        $this->db->query($query, array($name));
        $affected_rows2 = $this->db->affectedRows();

        $config['title'] = "Marketing Update";
        if ($affected_rows1 > 0 || $affected_rows2 > 0) {
            $updated = true;
            $config['log_text'] = "[ Marketing successfully updated ]";
            $config['ref_link'] = url_to('erp.crm.marketing');
            $this->session->setFlashdata("op_success", "Marketing successfully updated");
        } else {
            $config['log_text'] = "[ Marketing failed to update ]";
            $config['ref_link'] = url_to('erp.crm.marketing');
            $this->session->setFlashdata("op_error", "Marketing failed to update");
        }
        log_activity($config);
        return $updated;
    }

    public function get_marketing_export($type)
    {
        $columns = array(
            "name" => "marketing.name",
            "amount" => "amount",
            "related_to" => "related_to"
        );
        $limit = $_GET['limit'];
        $offset = $_GET['offset'];
        $search = $_GET['search'] ?? "";

        $orderby = isset($_GET['orderby']) ? strtoupper($_GET['orderby']) : "";
        $ordercol = isset($_GET['ordercol']) ? $columns[$_GET['ordercol']] : "";
        $query = "";
        if ($type == "pdf") {
            $query = "SELECT marketing.name AS name,CASE ";
            foreach ($this->marketing_products as $key => $value) {
                $query .= " WHEN related_to='$key' THEN '$value' ";
            }
            $query .= " END AS related_to, COALESCE(CONCAT(finished_goods.name,' ',finished_goods.code),CONCAT(semi_finished.name,' ',semi_finished.code)) AS product, amount,done_by, CASE WHEN active=1 THEN 'Yes' ELSE 'No' END AS active FROM marketing LEFT JOIN finished_goods ON related_id=finished_goods.finished_good_id AND related_to='finished_good' LEFT JOIN semi_finished ON related_id=semi_finished.semi_finished_id AND related_to='semi_finished' ";
        } else {
            $query = "SELECT marketing.name AS name,CASE ";
            foreach ($this->marketing_products as $key => $value) {
                $query .= " WHEN related_to='$key' THEN '$value' ";
            }
            $query .= " END AS related_to, COALESCE(CONCAT(finished_goods.name,' ',finished_goods.code),CONCAT(semi_finished.name,' ',semi_finished.code)) AS product, amount,done_by,company,address,email,phone1,phone2,description, CASE WHEN active=1 THEN 'Yes' ELSE 'No' END AS active FROM marketing LEFT JOIN finished_goods ON related_id=finished_goods.finished_good_id AND related_to='finished_good' LEFT JOIN semi_finished ON related_id=semi_finished.semi_finished_id AND related_to='semi_finished' ";
        }

        $where = false;
        $filter_1_col = isset($_GET['related_to']) ? $columns['related_to'] : "";
        $filter_1_val = $_GET['related_to'];

        if (!empty($filter_1_col) && $filter_1_val !== "") {
            $query .= " WHERE " . $filter_1_col . "='" . $filter_1_val . "' ";
            $where = true;
        }

        if (!empty($search)) {
            if (!$where) {
                $query .= " WHERE marketing.name LIKE '%" . $search . "%' ";
                $where = true;
            } else {
                $query .= " AND marketing.name LIKE '%" . $search . "%' ";
            }
        }

        if (!empty($ordercol) && !empty($orderby)) {
            $query .= " ORDER BY " . $ordercol . " " . $orderby;
        }

        $result = $this->db->query($query)->getResultArray();
        return $result;
    }

    public function insert_marketing($post)
    {
        $inserted = false;
        $name = $post["name"];
        $amount = $post["amount"];
        $related_to = $post["related_to"];
        $related_id = $post["related_id"];
        $done_by = $post["done_by"];
        $email = $post["email"];
        $phone1 = $post["phone1"];
        $phone2 = $post["phone2"];
        $company = $post["company"];
        $address = $post["address"];
        $description = $post["description"];
        $created_by = get_user_id();

        $this->db->transBegin();
        $query = "INSERT INTO marketing(name,amount,related_to,related_id,done_by,email,phone1,phone2,company,address,description,created_at,created_by) VALUES(?,?,?,?,?,?,?,?,?,?,?,?,?) ";
        $this->db->query($query, array($name, $amount, $related_to, $related_id, $done_by, $email, $phone1, $phone2, $company, $address, $description, time(), $created_by));

        $marketing_id = $this->db->insertId();
        if (empty($marketing_id)) {
            $this->session->setFlashdata("op_error", "Marketing failed to create");
            return $inserted;
        } else {
            $query = "INSERT INTO lead_source(source_name,marketing_id) VALUES(?,?) ";
            $this->db->query($query, array($name, $marketing_id));
            if (empty($this->db->insertId())) {
                $this->db->transRollback();
                $this->session->setFlashdata("op_error", "Marketing failed to create");
                return $inserted;
            }
        }

        $this->db->transComplete();
        $config['title'] = "Marketing Insert";
        if ($this->db->transStatus() === FALSE) {
            $this->db->transRollback();
            $config['log_text'] = "[ Marketing failed to create ]";
            $config['ref_link'] = url_to('erp.crm.marketing');
            $this->session->setFlashdata("op_error", "Marketing failed to create");
        } else {
            $inserted = true;
            $this->db->transCommit();
            $config['log_text'] = "[ Marketing successfully created ]";
            $config['ref_link'] = url_to('erp.crm.marketing');
            $this->session->setFlashdata("op_success", "Marketing successfully created");

            FinanceAutomation::doTransaction($marketing_id, "marketing", AbstractAutomation::AFTER_INSERT);
        }
        log_activity($config);
        return $inserted;
    }

    public function marketing_delete($marketing_id)
    {
        $jobresult = $this->builder()->where('marketing_id', $marketing_id)->get()->getRow();

        $config['title'] = "Marketing Delete";

        if (!empty($jobresult)) {

            $this->db->table('marketing')->where('marketing_id', $marketing_id)->delete();
            $config['log_text'] = "[ Marketing successfully deleted ]";
            $config['ref_link'] = url_to('erp.crm.marketing');
            $this->session->setFlashdata("op_success", "Marketing successfully deleted");
        } else {
            $config['log_text'] = "[ Marketing failed to delete ]";
            $config['ref_link'] = url_to('erp.crm.marketing');
            $this->session->setFlashdata("op_success", "Marketing failed to delete");
        }

        log_activity($config);
    }
}
