<?php


namespace App\Models\Erp;

use CodeIgniter\Model;

class PriceListModel extends Model
{
    protected $table      = 'price_list';
    protected $primaryKey = 'price_id';
    protected $allowedFields = ['name', 'amount', 'tax1', 'tax2', 'description', 'active', 'created_at', 'created_by'];
    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = '';


    public $session;
    public $db;

    public function __construct()
    {
        $this->session = \Config\Services::session();
        $this->db = \Config\Database::connect();
    }
    
    public function insert_pricelist($post)
    {
        $inserted = false;
        $name = $post["name"];
        $amount = $post["amount"];
        $tax1 = $post["tax1"];
        $tax2 = $post["tax2"];
        $description = $post["description"];
        $created_by = get_user_id();

        $query = "INSERT INTO price_list(name,amount,tax1,tax2,description,created_at,created_by) VALUES(?,?,?,?,?,?,?) ";
        $this->db->query($query, array($name, $amount, $tax1, $tax2, $description, time(), $created_by));

        $config['title'] = "Price List Insert";
        if (!empty($this->db->insertId())) {
            $inserted = true;
            $config['log_text'] = "[ Price List successfully created ]";
            $config['ref_link'] = 'erp/inventory/pricelist';
            $this->session->setFlashdata("op_success", "Price List successfully created");
        } else {
            $config['log_text'] = "[ Price List failed to create ]";
            $config['ref_link'] = 'erp/inventory/pricelist';
            $this->session->setFlashdata("op_error", "Price List failed to create");
        }
        log_activity($config);
        return $inserted;
    }

    public function get_dtconfig_pricelist()
    {
        $config = array(
            "columnNames" => ["sno", "name", "amount", "tax", "active", "action"],
            "sortable" => [0, 1, 1, 0, 0, 0]
        );
        return json_encode($config);
    }

    public function get_pricelist_datatable()
    {
        $columns = array(
            "name" => "name",
            "amount" => "amount"
        );
        $limit = $_GET['limit'];
        $offset = $_GET['offset'];
        $search = $_GET['search'] ?? "";
        $orderby = isset($_GET['orderby']) ? strtoupper($_GET['orderby']) : "";
        $ordercol = isset($_GET['ordercol']) ? $columns[$_GET['ordercol']] : "";

        $query = "SELECT price_id,name,amount,active, CONCAT(t1.tax_name,' ',t1.percent,' , ',IFNULL(t2.tax_name,''),' ',IFNULL(t2.percent,'')) AS tax FROM price_list LEFT JOIN taxes t1 ON price_list.tax1=t1.tax_id LEFT JOIN taxes t2 ON price_list.tax2=t2.tax_id";
        $count = "SELECT COUNT(price_id) AS total FROM price_list ";
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
                                <li><a href="' . url_to('erp.inventory.pricelistedit', $r['price_id']) . '" title="Edit" class="bg-success modalBtn"><i class="fa fa-pencil"></i> </a></li>
                                <li><a href="' . url_to('erp.inventory.pricelistdelete', $r['price_id']) . '" title="Delete" class="bg-danger del-confirm"><i class="fa fa-trash"></i> </a></li>
                            </ul>
                        </div>
                    </div>';
            $active = '
                    <label class="toggle active-toggler-1" data-ajax-url="' . url_to('erp.inventory.ajaxpricelistactive', $r['price_id']) . '" >
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
                    $r['price_id'],
                    $sno,
                    $r['name'],
                    $r['amount'],
                    $r['tax'],
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

    public function get_pricelist_by_id($price_id)
    {
        $query = "SELECT * FROM price_list WHERE price_id=$price_id";
        $result = $this->db->query($query)->getRow();
        return $result;
    }

    public function update_pricelist($price_id, $post)
    {
        $updated = false;
        $name = $post["name"];
        $amount = $post["amount"];
        $tax1 = $post["tax1"];
        $tax2 = $post["tax2"];
        $description = $post["description"];

        $query = "UPDATE price_list SET name=? , amount=? , tax1=? , tax2=? , description=? WHERE price_id=$price_id ";
        $this->db->query($query, array($name, $amount, $tax1, $tax2, $description));

        $config['title'] = "Price List Update";
        if ($this->db->affectedRows() > 0) {
            $updated = true;
            $config['log_text'] = "[ Price List successfully updated ]";
            $config['ref_link'] = 'erp/inventory/pricelist';
            $this->session->setFlashdata("op_success", "Price List successfully updated");
        } else {
            $config['log_text'] = "[ Price List failed to update ]";
            $config['ref_link'] = 'erp/inventory/pricelist';
            $this->session->setFlashdata("op_error", "Price List failed to update");
        }
        log_activity($config);
        return $updated;
    }

    public function delete_pricelist($price_id)
    {
        $check1 = $this->db->query("SELECT price_id FROM purchase_order_items WHERE price_id=$price_id LIMIT 1")->getRow();
        if (empty($check1)) {
            $check2 = $this->db->query("SELECT price_id FROM stocks WHERE price_id=$price_id LIMIT 1")->getRow();
            if (empty($check2)) {
                $query = "DELETE FROM price_list WHERE price_id=$price_id ";
                $this->db->query($query);
                $config['title'] = "Price List Delete";
                if ($this->db->affectedRows() > 0) {
                    $updated = true;
                    $config['log_text'] = "[ Price List successfully updated ]";
                    $config['ref_link'] = 'erp/inventory/pricelist';
                    $this->session->setFlashdata("op_success", "Price List successfully deleted");
                } else {
                    $config['log_text'] = "[ Price List failed to update ]";
                    $config['ref_link'] = 'erp/inventory/pricelist';
                    $this->session->setFlashdata("op_error", "Price List failed to deleted");
                }
                log_activity($config);
            } else {
                $this->session->setFlashdata("op_error", "Price List is already in use");
            }
        } else {
            $this->session->setFlashdata("op_error", "Price List is already in use");
        }
    }

    public function get_pricelist_export($type)
    {
        $columns = array(
            "name" => "name",
            "amount" => "amount"
        );
        $limit = $_GET['limit'];
        $offset = $_GET['offset'];
        $search = $_GET['search'] ?? "";
        $orderby = isset($_GET['orderby']) ? strtoupper($_GET['orderby']) : "";
        $ordercol = isset($_GET['ordercol']) ? $columns[$_GET['ordercol']] : "";

        $query = "SELECT name,amount,CONCAT(t1.tax_name,' ',t1.percent,' , ',IFNULL(t2.tax_name,''),' ',IFNULL(t2.percent,'')) AS tax , description, CASE WHEN active=1 THEN 'Yes' ELSE 'No' END AS active FROM price_list LEFT JOIN taxes t1 ON price_list.tax1=t1.tax_id LEFT JOIN taxes t2 ON price_list.tax2=t2.tax_id";
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
