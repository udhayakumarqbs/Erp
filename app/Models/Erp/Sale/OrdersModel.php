<?php

namespace App\Models\Erp\Sale;

use CodeIgniter\Model;
use App\libraries\Scheduler;
use DateTime;
use App\libraries\job\Notify;

class OrdersModel extends Model
{
    protected $table            = 'orders';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [];

    // Dates
    protected $useTimestamps = false;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    private $order_status = array(
        0 => "Created",
        1 => "Invoiced",
        2 => "Completed",
        3 => "Cancelled"
    );

    private $order_status_bg = array(
        0 => "st_dark",
        1 => "st_violet",
        2 => "st_primary",
        3 => "st_danger"
    );


    public function get_order_status()
    {
        return $this->order_status;
    }

    public function get_order_status_bg()
    {
        return $this->order_status_bg;
    }

    public function get_dtconfig_m_orders()
    {
        $config = array(
            "columnNames" => ["sno", "code", "customer", "order date", "order expiry", "status", "transport charge", "discount", "total amount", "action"],
            "sortable" => [0, 1, 0, 1, 1, 0, 0, 0, 1, 0],
            "filters" => ["status"]
        );
        return json_encode($config);
    }


    public function get_dtconfig_c_orders()
    {
        $config = array(
            "columnNames" => ["sno", "code", "customer", "order date", "order expiry", "status", "discount", "total amount", "action"],
            "sortable" => [0, 1, 0, 1, 1, 0, 1, 0],
            "filters" => ["status"]
        );
        return json_encode($config);
    }

    public function get_m_order_datatable()
    {
        $columns = array(
            "code" => "code",
            "order date" => "order_date",
            "order expiry" => "order_expiry",
            "status" => "sale_order.status",
            "total amount" => "total_amount"
        );
        $limit = $_GET['limit'];
        $offset = $_GET['offset'];
        $search = $_GET['search'] ?? "";
        $orderby = isset($_GET['orderby']) ? strtoupper($_GET['orderby']) : "";
        $ordercol = isset($_GET['ordercol']) ? $columns[$_GET['ordercol']] : "";

        $query = "SELECT sale_order.order_id,code,customers.name,order_date,order_expiry,sale_order.status,trans_charge,discount,(SUM(amount + (amount * (sale_order_items.tax1 + sale_order_items.tax2)) / 100)+ trans_charge - discount) AS total_amount,can_edit FROM sale_order JOIN customers ON sale_order.cust_id=customers.cust_id JOIN sale_order_items ON sale_order.order_id=sale_order_items.order_id WHERE sale_order.type=1 ";
        $count = "SELECT COUNT(sale_order.order_id) AS total FROM sale_order JOIN customers ON sale_order.cust_id=customers.cust_id JOIN sale_order_items ON sale_order.order_id=sale_order_items.order_id WHERE sale_order.type=1 ";
        $where = true;
        $filter_1_col = isset($_GET['status']) ? $columns['status'] : "";
        $filter_1_val = $_GET['status'];

        if (!empty($filter_1_col) && $filter_1_val !== "") {
            if (!$where) {
                $query .= " WHERE " . $filter_1_col . "=" . $filter_1_val . " ";
                $count .= " WHERE " . $filter_1_col . "=" . $filter_1_val . " ";
                $where = true;
            } else {
                $query .= " AND " . $filter_1_col . "=" . $filter_1_val . " ";
                $count .= " AND " . $filter_1_col . "=" . $filter_1_val . " ";
            }
        }

        if (!empty($search)) {
            if (!$where) {
                $query .= " WHERE ( code LIKE '%" . $search . "%' OR customers.name LIKE '%" . $search . "%' ) ";
                $count .= " WHERE ( code LIKE '%" . $search . "%' OR customers.name LIKE '%" . $search . "%' ) ";
                $where = true;
            } else {
                $query .= " AND ( code LIKE '%" . $search . "%' OR customers.name LIKE '%" . $search . "%' ) ";
                $count .= " AND ( code LIKE '%" . $search . "%' OR customers.name LIKE '%" . $search . "%' ) ";
            }
        }
        $query .= " GROUP BY sale_order.order_id ";
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
                                    <li><a href="' . url_to('erp.sale.orders.view', $r['order_id']) . '" title="View" class="bg-warning "><i class="fa fa-eye"></i> </a></li>';
            if ($r['can_edit'] == 1) {
                $action .= '<li><a href="' . url_to('erp.sale.order.edit', $r['order_id']) . '" title="Edit" class="bg-success "><i class="fa fa-pencil"></i> </a></li>';
            }
            $action .= '<li><a href="' . url_to('erp.sale.orderdelete', $r['order_id']) . '" title="Delete" class="bg-danger del-confirm"><i class="fa fa-trash"></i> </a></li>
                                </ul>
                            </div>
                        </div>';
            $status = '<span class="st ' . $this->order_status_bg[$r['status']] . ' " >' . $this->order_status[$r['status']] . '</span>';
            array_push(
                $m_result,
                array(
                    $r['order_id'],
                    $sno,
                    $r['code'],
                    $r['name'],
                    $r['order_date'],
                    $r['order_expiry'],
                    $status,
                    $r['trans_charge'],
                    $r['discount'],
                    number_format($r['total_amount'],2, '.', ','),
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

    public function get_c_order_datatable()
    {
        $columns = array(
            "code" => "code",
            "order date" => "order_date",
            "order expiry" => "order_expiry",
            "status" => "sale_order.status",
            "total amount" => "total_amount"
        );
        $limit = $_GET['limit'];
        $offset = $_GET['offset'];
        $search = $_GET['search'] ?? "";
        $orderby = isset($_GET['orderby']) ? strtoupper($_GET['orderby']) : "";
        $ordercol = isset($_GET['ordercol']) ? $columns[$_GET['ordercol']] : "";

        $query = "SELECT sale_order.order_id,code,customers.name,order_date,order_expiry,sale_order.status,discount,(SUM(sale_order_items.amount)-discount) AS total_amount,can_edit FROM sale_order JOIN customers ON sale_order.cust_id=customers.cust_id JOIN sale_order_items ON sale_order.order_id=sale_order_items.order_id WHERE sale_order.type=0 ";
        $count = "SELECT COUNT(sale_order.order_id) AS total FROM sale_order JOIN customers ON sale_order.cust_id=customers.cust_id JOIN sale_order_items ON sale_order.order_id=sale_order_items.order_id WHERE sale_order.type=0 ";
        $where = true;
        $filter_1_col = isset($_GET['status']) ? $columns['status'] : "";
        $filter_1_val = $_GET['status'];

        if (!empty($filter_1_col) && $filter_1_val !== "") {
            if (!$where) {
                $query .= " WHERE " . $filter_1_col . "=" . $filter_1_val . " ";
                $count .= " WHERE " . $filter_1_col . "=" . $filter_1_val . " ";
                $where = true;
            } else {
                $query .= " AND " . $filter_1_col . "=" . $filter_1_val . " ";
                $count .= " AND " . $filter_1_col . "=" . $filter_1_val . " ";
            }
        }

        if (!empty($search)) {
            if (!$where) {
                $query .= " WHERE ( code LIKE '%" . $search . "%' OR customers.name LIKE '%" . $search . "%' ) ";
                $count .= " WHERE ( code LIKE '%" . $search . "%' OR customers.name LIKE '%" . $search . "%' ) ";
                $where = true;
            } else {
                $query .= " AND ( code LIKE '%" . $search . "%' OR customers.name LIKE '%" . $search . "%' ) ";
                $count .= " AND ( code LIKE '%" . $search . "%' OR customers.name LIKE '%" . $search . "%' ) ";
            }
        }
        $query .= " GROUP BY sale_order.order_id ";
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
                                    <li><a href="' . url_to('erp.sale.orders.view', $r['order_id']) . '" title="View" class="bg-warning "><i class="fa fa-eye"></i> </a></li>';
            if ($r['can_edit'] == 1) {
                $action .= '<li><a href="' . url_to('erp.sale.order.edit', $r['order_id']) . '" title="Edit" class="bg-success "><i class="fa fa-pencil"></i> </a></li>';
            }
            $action .= '<li><a href="' . url_to('erp.sale.orderdelete', $r['order_id']) . '" title="Delete" class="bg-danger del-confirm"><i class="fa fa-trash"></i> </a></li>
                                </ul>
                            </div>
                        </div>';
            $status = '<span class="st ' . $this->order_status_bg[$r['status']] . ' " >' . $this->order_status[$r['status']] . '</span>';
            array_push(
                $m_result,
                array(
                    $r['order_id'],
                    $sno,
                    $r['code'],
                    $r['name'],
                    $r['order_date'],
                    $r['order_expiry'],
                    $status,
                    $r['discount'],
                    $r['total_amount'],
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

    public function insert_m_order()
    {
        $inserted = false;
        $code = $_POST["code"];
        $cust_id = $_POST["cust_id"];
        $order_date = $_POST["order_date"];
        $shippingaddr_id = $_POST["shippingaddr_id"];
        $terms_condition = $_POST["terms_condition"];
        $payment_terms = $_POST["payment_terms"];
        $transport_req = $_POST["transport_req"] ?? 0;
        $trans_charge = $_POST["trans_charge"];
        $discount = $_POST["discount"];
        $order_expiry = $_POST["order_expiry"];
        $created_by = get_user_id();

        $this->db->transBegin();
        $query = "INSERT INTO sale_order(code,cust_id,order_date,shippingaddr_id,terms_condition,payment_terms,transport_req,trans_charge,discount,order_expiry,can_edit,type,created_at,created_by) VALUES(?,?,?,?,?,?,?,?,?,?,?,?,?,?) ";
        $this->db->query($query, array($code, $cust_id, $order_date, $shippingaddr_id, $terms_condition, $payment_terms, $transport_req, $trans_charge, $discount, $order_expiry, 1, 1, time(), $created_by));

        $order_id = $this->db->insertID();
        if (empty($order_id)) {
            session()->setFlashdata("op_error", "Sale Order failed to create");
            return $inserted;
        }

        if (!$this->update_saleorder_items($order_id)) {
            $this->db->transRollback();
            session()->setFlashdata("op_error", "Sale Order failed to create");
            return $inserted;
        }

        $this->db->transComplete();
        $config['title'] = "Sale Order Insert";
        if ($this->db->transStatus() === FALSE) {
            $this->db->transRollback();
            $config['log_text'] = "[ Sale Order failed to create ]";
            $config['ref_link'] = "";
            session()->setFlashdata("op_success", "Sale Order failed to create");
        } else {
            $inserted = true;
            $this->db->transCommit();
            $config['log_text'] = "[ Sale Order successfully created ]";
            $config['ref_link'] = "erp/sale/orderview/" . $order_id;
            session()->setFlashdata("op_success", "Sale Order successfully created");
        }
        log_activity($config);
        return $inserted;
    }




    private function update_saleorder_items($order_id)
    {
        $updated = true;
        $product_ids = $_POST["product_id"];
        $quantities = $_POST["quantity"];
        $price_ids = $_POST["price_id"];

        if (empty($product_ids)) {
            return false;
        }
        $result = $this->db->query("SELECT related_id FROM sale_order_items WHERE order_id=$order_id")->getResultArray();
        $old_product_ids = array();
        foreach ($result as $row) {
            array_push($old_product_ids, $row['related_id']);
        }
        $del_product_ids = array();
        foreach ($old_product_ids as $old) {
            $mark = true;
            foreach ($product_ids as $new) {
                if ($old == $new) {
                    $mark = false;
                    break;
                }
            }
            if ($mark) {
                array_push($del_product_ids, $old);
            }
        }
        if (!empty($del_product_ids)) {
            $query = "DELETE FROM sale_order_items WHERE related_id IN (" . implode(",", $del_product_ids) . ") AND order_id=$order_id";
            $this->db->query($query);
        }
        $query = "SELECT m_sale_order_items(?,?,?,?,?) AS error ";
        // Insert Query for MRP Forecast DataSet
        $mrp_forecast = "INSERT INTO `mrp_dataset_forecast` 
        (`related_to`, `related_id`, `quantity`, `current_stocks_on_inventory`) 
        VALUES (?,?,?,?)";
        foreach ($product_ids as $key => $value) {
            $error = $this->db->query($query, array("order", $order_id, $value, $quantities[$key], $price_ids[$key]))->getRow()->error;
            $current_stock_query = "SELECT SUM(`quantity`) AS total_quantity
            FROM `stocks`
            WHERE `related_to` = 'finished_good' AND `related_id` = $value";

            $current_stock = $this->db->query($current_stock_query)->getRow()->total_quantity;

            $forecast = $this->db->query($mrp_forecast, array("finished_good", $value, $quantities[$key], $current_stock ?? 0));
            if ($error == 1) {
                $updated = false;
                break;
            }
        }
        return $updated;
    }

    public function insert_c_order()
    {
        $inserted = false;
        $code = $_POST["code"];
        $cust_id = $_POST["cust_id"];
        $order_date = $_POST["order_date"];
        $terms_condition = $_POST["terms_condition"];
        $payment_terms = $_POST["payment_terms"];
        $discount = $_POST["discount"];
        $order_expiry = $_POST["order_expiry"];
        $created_by = get_user_id();

        $this->db->transBegin();
        $query = "INSERT INTO sale_order(code,cust_id,order_date,terms_condition,payment_terms,discount,order_expiry,can_edit,type,created_at,created_by) VALUES(?,?,?,?,?,?,?,?,?,?,?) ";
        $this->db->query($query, array($code, $cust_id, $order_date, $terms_condition, $payment_terms, $discount, $order_expiry, 1, 0, time(), $created_by));

        $order_id = $this->db->insertID();
        if (empty($order_id)) {
            session()->setFlashdata("op_error", "Sale Order failed to create");
            return $inserted;
        }

        if (!$this->update_c_saleorder_items($order_id)) {
            $this->db->transRollback();
            session()->setFlashdata("op_error", "Sale Order failed to create");
            return $inserted;
        }

        $this->db->transComplete();
        $config['title'] = "Sale Order Insert";
        if ($this->db->transStatus() === FALSE) {
            $this->db->transRollback();
            $config['log_text'] = "[ Sale Order failed to create ]";
            $config['ref_link'] = "";
            session()->setFlashdata("op_success", "Sale Order failed to create");
        } else {
            $inserted = true;
            $this->db->transCommit();
            $config['log_text'] = "[ Sale Order successfully created ]";
            $config['ref_link'] = "erp/sale/orderview/" . $order_id;
            session()->setFlashdata("op_success", "Sale Order successfully created");
        }
        log_activity($config);
        return $inserted;
    }

    private function update_c_saleorder_items($order_id)
    {
        $updated = true;
        $property_ids = $_POST["property_id"] ?? 0;
        $unit_ids = $_POST["unit_id"] ?? 0;

        if (empty($unit_ids)) {
            return false;
        }
        $result = $this->db->query("SELECT related_id FROM sale_order_items WHERE order_id=$order_id")->getResultArray();
        $old_unit_ids = array();
        foreach ($result as $row) {
            array_push($old_unit_ids, $row['related_id']);
        }
        $del_unit_ids = array();
        foreach ($old_unit_ids as $old) {
            $mark = true;
            foreach ($unit_ids as $new) {
                if ($old == $new) {
                    $mark = false;
                    break;
                }
            }
            if ($mark) {
                array_push($del_unit_ids, $old);
            }
        }
        if (!empty($del_unit_ids)) {
            $query = "DELETE FROM sale_order_items WHERE related_id IN (" . implode(",", $del_unit_ids) . ") AND order_id=$order_id";
            $this->db->query($query);
            $query = "UPDATE property_unit SET status=0 WHERE prop_unit_id IN (" . implode(",", $del_unit_ids) . ") ";
            $this->db->query($query);
        }
        $query = "SELECT c_sale_order_items(?,?,?) AS error ";
        foreach ($unit_ids as $key => $value) {
            $error = $this->db->query($query, array("order", $order_id, $value))->getRow()->error;
            if ($error == 1) {
                $updated = false;
                break;
            }
        }
        return $updated;
    }

    public function get_m_order_for_view($order_id)
    {
        $query = "SELECT sale_order.order_id,code,order_date,order_expiry,payment_terms,terms_condition,discount,(SUM(amount + (amount * (sale_order_items.tax1 + sale_order_items.tax2)) / 100)+ trans_charge - discount) AS total_amount,customers.name,customers.email,customers.cust_id,CONCAT(customer_shippingaddr.address,',',customer_shippingaddr.city,',',customer_shippingaddr.state,',',customer_shippingaddr.country,',',customer_shippingaddr.zipcode) AS shipping_addr, CONCAT(customer_billingaddr.address,',',customer_billingaddr.city,',',customer_billingaddr.state,',',customer_billingaddr.country,',',customer_billingaddr.zipcode) AS billing_addr,CASE WHEN transport_req=1 THEN 'Yes' ELSE 'No' END AS transport_req,trans_charge,sale_order.status,sale_order.stock_pick FROM sale_order JOIN customers ON sale_order.cust_id=customers.cust_id JOIN sale_order_items ON sale_order.order_id=sale_order_items.order_id LEFT JOIN customer_shippingaddr ON sale_order.cust_id=customer_shippingaddr.cust_id
        LEFT JOIN customer_billingaddr ON sale_order.cust_id=customer_billingaddr.cust_id WHERE sale_order.order_id=$order_id ";
        $result = $this->db->query($query)->getRow();
        return $result;
    }

    public function get_m_order_items_for_view($order_id)
    {
        $query = "SELECT CONCAT(finished_goods.name,' ',finished_goods.code) AS product,quantity,sale_order_items.unit_price,sale_order_items.amount FROM sale_order_items JOIN finished_goods ON related_id=finished_goods.finished_good_id WHERE order_id=$order_id ";
        $result = $this->db->query($query)->getResultArray();
        return $result;
    }

    public function get_c_order_for_view($order_id)
    {
        $query = "SELECT sale_order.order_id,code,order_date,order_expiry,payment_terms,terms_condition,discount,(SUM(sale_order_items.amount)-discount) AS total_amount,customers.name,customers.email,sale_order.status FROM sale_order JOIN customers ON sale_order.cust_id=customers.cust_id JOIN sale_order_items ON sale_order.order_id=sale_order_items.order_id WHERE sale_order.order_id=$order_id";
        $result = $this->db->query($query)->getResultArray();
        return $result;
    }

    public function get_c_order_items_for_view($order_id)
    {
        $query = "SELECT properties.name AS property,property_unit.unit_name,sale_order_items.amount FROM sale_order_items JOIN property_unit ON sale_order_items.related_id=property_unit.prop_unit_id JOIN properties ON property_unit.property_id=properties.property_id WHERE order_id=$order_id ";
        $result = $this->db->query($query)->getResultArray();
        return $result;
    }

    public function get_m_stock_pick($order_id)
    {
        $query = "SELECT stocks.stock_id,stocks.related_id,CONCAT(finished_goods.name,' ',finished_goods.code) AS product,sale_order_items.quantity AS qty_to_pick,stocks.warehouse_id,warehouses.name AS warehouse,stocks.quantity AS warehouse_stock FROM sale_order_items JOIN stocks ON sale_order_items.related_to=stocks.related_to AND sale_order_items.related_id=stocks.related_id AND sale_order_items.price_id=stocks.price_id JOIN warehouses ON stocks.warehouse_id=warehouses.warehouse_id JOIN finished_goods ON stocks.related_id=finished_goods.finished_good_id WHERE order_id=$order_id";
        $result = $this->db->query($query)->getResultArray();
        $m_result = array();
        foreach ($result as $row) {
            if (isset($m_result[$row['related_id']])) {
                array_push($m_result[$row['related_id']]['warehouses'], array(
                    "id" => $row['stock_id'],
                    "name" => $row['warehouse'],
                    "stock" => $row['warehouse_stock'],
                ));
            } else {
                $m_result[$row['related_id']] = array(
                    "product" => $row['product'],
                    "qty_to_pick" => $row['qty_to_pick'],
                    "warehouses" => []
                );
                array_push($m_result[$row['related_id']]['warehouses'], array(
                    "id" => $row['stock_id'],
                    "name" => $row['warehouse'],
                    "stock" => $row['warehouse_stock'],
                ));
            }
        }
        return $m_result;
    }

    public function get_stock_pick_items($order_id)
    {
        $query = "SELECT CONCAT(finished_goods.name,' ',finished_goods.code) AS product,warehouses.name AS warehouse,qty FROM stock_entry JOIN stocks ON stock_entry.stock_id=stocks.stock_id JOIN warehouses ON stocks.warehouse_id=warehouses.warehouse_id JOIN finished_goods ON stocks.related_id=finished_goods.finished_good_id WHERE order_id=$order_id ";
        $result = $this->db->query($query)->getResultArray();
        return $result;
    }

    public function get_dtconfig_notify()
    {
        $config = array(
            "columnNames" => ["sno", "title", "description", "notify at", "notify to", "notified", "action"],
            "sortable" => [0, 1, 0, 1, 0, 0, 0]
        );
        return json_encode($config);
    }

    public function get_m_order_export($type)
    {
        $columns = array(
            "code" => "code",
            "order date" => "order_date",
            "order expiry" => "order_expiry",
            "status" => "sale_order.status",
            "total amount" => "total_amount"
        );
        $limit = $_GET['limit'];
        $offset = $_GET['offset'];
        $search = $_GET['search'] ?? "";
        $orderby = isset($_GET['orderby']) ? strtoupper($_GET['orderby']) : "";
        $ordercol = isset($_GET['ordercol']) ? $columns[$_GET['ordercol']] : "";
        $query = "";
        if ($type == "pdf") {
            $query = "SELECT code,customers.name,order_date,order_expiry,
            CASE ";
            foreach ($this->order_status as $key => $value) {
                $query .= " WHEN sale_order.status=$key THEN '$value' ";
            }
            $query .= " END AS status,CASE WHEN transport_req=1 THEN 'Yes' ELSE 'No' END AS transport_req,trans_charge,discount,(SUM(sale_order_items.amount)+trans_charge-discount) AS total_amount FROM sale_order JOIN customers ON sale_order.cust_id=customers.cust_id JOIN sale_order_items ON sale_order.order_id=sale_order_items.order_id WHERE type=1 ";
        } else {
            $query = "SELECT code,customers.name,CONCAT(customer_shippingaddr.address,',',customer_shippingaddr.city,',',customer_shippingaddr.state,',',customer_shippingaddr.country,',',customer_shippingaddr.zipcode) AS shipping_addr,order_date,order_expiry,
            CASE ";
            foreach ($this->order_status as $key => $value) {
                $query .= " WHEN sale_order.status=$key THEN '$value' ";
            }
            $query .= " END AS status,CASE WHEN transport_req=1 THEN 'Yes' ELSE 'No' END AS transport_req,trans_charge,discount,(SUM(sale_order_items.amount)+trans_charge-discount) AS total_amount,payment_terms,terms_condition FROM sale_order JOIN customers ON sale_order.cust_id=customers.cust_id JOIN sale_order_items ON sale_order.order_id=sale_order_items.order_id LEFT JOIN customer_shippingaddr ON sale_order.shippingaddr_id=customer_shippingaddr.shippingaddr_id WHERE type=1 ";
        }
        $where = true;
        $filter_1_col = isset($_GET['status']) ? $columns['status'] : "";
        $filter_1_val = $_GET['status'];

        if (!empty($filter_1_col) && $filter_1_val !== "") {
            if (!$where) {
                $query .= " WHERE " . $filter_1_col . "=" . $filter_1_val . " ";
                $where = true;
            } else {
                $query .= " AND " . $filter_1_col . "=" . $filter_1_val . " ";
            }
        }

        if (!empty($search)) {
            if (!$where) {
                $query .= " WHERE ( code LIKE '%" . $search . "%' OR customers.name LIKE '%" . $search . "%' ) ";
                $where = true;
            } else {
                $query .= " AND ( code LIKE '%" . $search . "%' OR customers.name LIKE '%" . $search . "%' ) ";
            }
        }
        $query .= " GROUP BY sale_order.order_id ";
        if (!empty($ordercol) && !empty($orderby)) {
            $query .= " ORDER BY " . $ordercol . " " . $orderby;
        }
        $result = $this->db->query($query)->getResultArray();
        return $result;
    }

    public function get_c_order_export($type)
    {
        $columns = array(
            "code" => "code",
            "order date" => "order_date",
            "order expiry" => "order_expiry",
            "status" => "sale_order.status",
            "total amount" => "total_amount"
        );
        $limit = $_GET['limit'];
        $offset = $_GET['offset'];
        $search = $_GET['search'] ?? "";
        $orderby = isset($_GET['orderby']) ? strtoupper($_GET['orderby']) : "";
        $ordercol = isset($_GET['ordercol']) ? $columns[$_GET['ordercol']] : "";
        $query = "";
        if ($type == "pdf") {
            $query = "SELECT code,customers.name,order_date,order_expiry, CASE ";
            foreach ($this->order_status as $key => $value) {
                $query .= " WHEN sale_order.status=$key THEN '$value' ";
            }
            $query .= " END AS status,discount,(SUM(sale_order_items.amount)-discount) AS total_amount FROM sale_order JOIN customers ON sale_order.cust_id=customers.cust_id JOIN sale_order_items ON sale_order.order_id=sale_order_items.order_id WHERE sale_order.type=0 ";
        } else {
            $query = "SELECT code,customers.name,order_date,order_expiry, CASE ";
            foreach ($this->order_status as $key => $value) {
                $query .= " WHEN sale_order.status=$key THEN '$value' ";
            }
            $query .= " END AS status,discount,(SUM(sale_order_items.amount)-discount) AS total_amount,payment_terms,terms_condition FROM sale_order JOIN customers ON sale_order.cust_id=customers.cust_id JOIN sale_order_items ON sale_order.order_id=sale_order_items.order_id WHERE sale_order.type=0 ";
        }
        $where = true;
        $filter_1_col = isset($_GET['status']) ? $columns['status'] : "";
        $filter_1_val = $_GET['status'];

        if (!empty($filter_1_col) && $filter_1_val !== "") {
            if (!$where) {
                $query .= " WHERE " . $filter_1_col . "=" . $filter_1_val . " ";
                $where = true;
            } else {
                $query .= " AND " . $filter_1_col . "=" . $filter_1_val . " ";
            }
        }

        if (!empty($search)) {
            if (!$where) {
                $query .= " WHERE ( code LIKE '%" . $search . "%' OR customers.name LIKE '%" . $search . "%' ) ";
                $where = true;
            } else {
                $query .= " AND ( code LIKE '%" . $search . "%' OR customers.name LIKE '%" . $search . "%' ) ";
            }
        }
        $query .= " GROUP BY sale_order.order_id ";
        if (!empty($ordercol) && !empty($orderby)) {
            $query .= " ORDER BY " . $ordercol . " " . $orderby;
        }
        $query .= " LIMIT $offset,$limit ";
        $result = $this->db->query($query)->getResultArray();
        return $result;
    }


    public function upload_attachment($type)
    {
        $path = get_attachment_path($type);
        $ext = pathinfo($_FILES['attachment']['name'], PATHINFO_EXTENSION);
        $filename = preg_replace('/[\/:"*?<>| ]+/i', "", $_FILES['attachment']['name']);
        $filepath = $path . $filename;
        $response = array();
        if (file_exists($filepath)) {
            $response['error'] = 1;
            $response['reason'] = "File already exists";
        } else {
            if (move_uploaded_file($_FILES['attachment']['tmp_name'], $filepath)) {
                $query = "INSERT INTO attachments(filename,related_to,related_id) VALUES(?,?,?) ";
                $id = $_GET["id"] ?? 0;
                if (empty($id)) {
                    $response['error'] = 1;
                    $response['reason'] = "Internal Error";
                } else {
                    $this->db->query($query, array($filename, $type, $id));
                    $attach_id = $this->db->insertId();
                    if (!empty($attach_id)) {
                        $response['error'] = 0;
                        $response['reason'] = "File uploaded successfully";
                        $response['filename'] = $filename;
                        $response['insert_id'] = $attach_id;
                        $response['filelink'] = get_attachment_link($type) . $filename;
                    } else {
                        unlink($filepath);
                        $response['error'] = 1;
                        $response['reason'] = "Internal Error";
                    }
                }
            } else {
                $response['error'] = 1;
                $response['reason'] = "File upload Error";
            }
        }
        return $response;
    }

    public function delete_attachment($type)
    {
        $attach_id = $_GET["id"] ?? 0;
        $response = array();
        if (!empty($attach_id)) {
            $filename = $this->db->query("SELECT filename FROM attachments WHERE attach_id=$attach_id")->getRow();
            if (!empty($filename)) {
                $filepath = get_attachment_path($type) . $filename->filename;
                $this->db->query("DELETE FROM attachments WHERE attach_id=$attach_id");
                if ($this->db->affectedrows() > 0) {
                    unlink($filepath);
                    $response['error'] = 0;
                    $response['reason'] = "File successfully deleted";
                } else {
                    $response['error'] = 1;
                    $response['reason'] = "File delete error";
                }
            } else {
                $response['error'] = 1;
                $response['reason'] = "File delete error";
            }
        } else {
            $response['error'] = 1;
            $response['reason'] = "File delete error";
        }
        return $response;
    }

    public function get_attachments($type, $related_id)
    {
        $query = "SELECT filename,attach_id FROM attachments WHERE related_to='$type' AND related_id=$related_id";
        $result = $this->db->query($query)->getResultArray();
        return $result;
    }

    public function get_ordernotify_export($type)
    {
        $order_id = $_GET["orderid"];
        $columns = array(
            "notify at" => "notify_at",
            "title" => "title"
        );
        $limit = $_GET['limit'];
        $offset = $_GET['offset'];
        $search = $_GET['search'] ?? "";
        $orderby = isset($_GET['orderby']) ? strtoupper($_GET['orderby']) : "";
        $ordercol = isset($_GET['ordercol']) ? $columns[$_GET['ordercol']] : "";

        $query = "SELECT title,notify_text,FROM_UNIXTIME(notify_at,'%Y-%m-%d %h:%i:%s') AS notify_at,erp_users.name AS notify_to,CASE WHEN status=1 THEN 'Yes' ELSE 'No' END AS notified FROM notification JOIN erp_users ON notification.user_id=erp_users.user_id WHERE related_to='sale_order' AND related_id=$order_id ";
        $where = true;

        if (!empty($search)) {
            if (!$where) {
                $query .= " WHERE ( title LIKE '%" . $search . "%' OR erp_users.name LIKE '%" . $search . "%' ) ";
                $where = true;
            } else {
                $query .= " AND ( title LIKE '%" . $search . "%' OR erp_users.name LIKE '%" . $search . "%' ) ";
            }
        }

        if (!empty($ordercol) && !empty($orderby)) {
            $query .= " ORDER BY " . $ordercol . " " . $orderby;
        }
        $query .= " LIMIT $offset,$limit ";
        $result = $this->db->query($query)->getResultArray();
        return $result;
    }

    public function insert_update_ordernotify($order_id)
    {
        $notify_id = $_POST["notify_id"] ?? 0;
        if (empty($notify_id)) {
            $this->insert_ordernotify($order_id);
        } else {
            $this->update_ordernotify($order_id, $notify_id);
        }
    }

    private function insert_ordernotify($order_id)
    {
        $notify_title = $_POST["notify_title"];
        $notify_desc = $_POST["notify_desc"];
        $notify_to = $_POST["notify_to"];
        $notify_at = $_POST["notify_at"];
        $notify_email = $_POST["notify_email"] ?? 0;
        $related_to = 'sale_order';
        $created_by = get_user_id();
        $datetime = DateTime::createFromFormat("Y-m-d\TH:i", $notify_at);
        $timestamp = $datetime->getTimestamp();

        $this->db->transBegin();
        $query = "INSERT INTO notification(title,notify_text,user_id,notify_email,notify_at,related_to,related_id,created_at,created_by) VALUES(?,?,?,?,?,?,?,?,?) ";
        $this->db->query($query, array($notify_title, $notify_desc, $notify_to, $notify_email, $timestamp, $related_to, $order_id, time(), $created_by));
        $notify_id = $this->db->insertId();
        if (empty($notify_id)) {
            session()->setFlashdata("op_error", "Notification failed to create");
            return;
        }

        $param['notify_id'] = $notify_id;
        $job = new Notify();
        $job_id = Scheduler::dispatch($job, $param, $timestamp);
        if (empty($job_id)) {
            $this->db->transRollback();
        } else {
            $query = "UPDATE notification SET job_id=? WHERE notify_id=$notify_id";
            $this->db->query($query, array($job_id));
            if ($this->db->affectedrows() > 0) {
                $inserted = true;
            } else {
                $this->db->transRollback();
            }
        }

        $config['title'] = "Sale Order Notification Insert";
        $this->db->transComplete();
        if ($this->db->transStatus() === FALSE) {
            $inserted = false;
            $this->db->transRollback();
            $config['log_text'] = "[ Sale Order Notification failed to create ]";
            $config['ref_link'] = 'erp/sale/orderview/' . $order_id;
            session()->setFlashdata("op_error", "Sale Order Notification failed to create");
        } else {
            $this->db->transCommit();
            $config['log_text'] = "[ Sale Order Notification successfully created ]";
            $config['ref_link'] = 'erp/sale/orderview/' . $order_id;
            session()->setFlashdata("op_success", "Sale Order Notification successfully created");
        }
        log_activity($config);
    }


    private function update_ordernotify($order_id, $notify_id)
    {
        $notify_title = $_POST["notify_title"];
        $notify_desc = $_POST["notify_desc"];
        $notify_to = $_POST["notify_to"];
        $notify_at = $_POST["notify_at"];
        $notify_email = $_POST["notify_email"] ?? 0;
        $datetime = DateTime::createFromFormat("Y-m-d\TH:i", $notify_at);
        $timestamp = $datetime->getTimestamp();
        $jobresult = $this->db->query("SELECT job_id FROM notification WHERE notify_id=$notify_id")->getRow();
        $this->db->transBegin();
        $query = "UPDATE notification SET title=? , notify_text=? , user_id=? , notify_email=? , notify_at=? WHERE notify_id=$notify_id ";
        $this->db->query($query, array($notify_title, $notify_desc, $notify_to, $notify_email, $timestamp));

        if ($this->db->affectedrows() > 0) {
            $param['notify_id'] = $notify_id;
            $job = new Notify();
            $jobid = Scheduler::replaceOldJob($jobresult->job_id, $job, $param, $timestamp);
            if (empty($jobid)) {
                $this->db->transRollback();
            } else {
                $query = "UPDATE notification SET job_id=? WHERE notify_id=$notify_id";
                $this->db->query($query, array($jobid));
                if ($this->db->affectedrows() > 0) {
                    $updated = true;
                } else {
                    $this->db->transRollback();
                }
            }
        }

        $config['title'] = "Sale Order Notification Update";
        $this->db->transComplete();
        if ($this->db->transStatus() === FALSE) {
            $inserted = false;
            $this->db->transRollback();
            $config['log_text'] = "[ Sale Order Notification failed to update ]";
            $config['ref_link'] = 'erp/sale/orderview/' . $order_id;
            session()->setFlashdata("op_error", "Sale Order Notification failed to update");
        } else {
            $this->db->transCommit();
            $config['log_text'] = "[ Sale Order Notification successfully updated ]";
            $config['ref_link'] = 'erp/sale/orderview/' . $order_id;
            session()->setFlashdata("op_success", "Sale Order Notification successfully updated");
        }
        log_activity($config);
    }

    public function delete_order_notify($order_id, $notify_id)
    {
        $jobresult = $this->db->query("SELECT job_id FROM notification WHERE notify_id=$notify_id")->getRow();
        $config['title'] = "Sale Order Notification Delete";
        if (!empty($jobresult)) {
            $jobid = $jobresult->job_id;
            $query = "DELETE FROM notification WHERE notify_id=$notify_id ";
            $this->db->query($query);
            Scheduler::safeDelete($jobid);
            $config['log_text'] = "[ Sale Order Notification successfully deleted ]";
            $config['ref_link'] = 'erp/sale/orderview/' . $order_id;
            session()->setFlashdata("op_success", "Sale Order Notification successfully deleted");
        } else {
            $config['log_text'] = "[ Sale Order Notification failed to delete ]";
            $config['ref_link'] = 'erp/sale/orderview/' . $order_id;
            session()->setFlashdata("op_success", "Sale Order Notification failed to delete");
        }
        log_activity($config);
    }



    public function get_ordernotify_datatable()
    {
        $order_id = $_GET["orderid"];
        $columns = array(
            "notify at" => "notify_at",
            "title" => "title"
        );
        $limit = $_GET['limit'];
        $offset = $_GET['offset'];
        $search = $_GET['search'] ?? "";
        $orderby = isset($_GET['orderby']) ? strtoupper($_GET['orderby']) : "";
        $ordercol = isset($_GET['ordercol']) ? $columns[$_GET['ordercol']] : "";

        $query = "SELECT notify_id,title,notify_at,status,erp_users.name AS notify_to,notify_text FROM notification JOIN erp_users ON notification.user_id=erp_users.user_id WHERE related_to='sale_order' AND related_id=$order_id ";
        $count = "SELECT COUNT(notify_id) AS total FROM notification JOIN erp_users ON notification.user_id=erp_users.user_id WHERE related_to='sale_order' AND related_id=$order_id ";
        $where = true;

        if (!empty($search)) {
            if (!$where) {
                $query .= " WHERE ( title LIKE '%" . $search . "%' OR erp_users.name LIKE '%" . $search . "%' ) ";
                $count .= " WHERE ( title LIKE '%" . $search . "%' OR erp_users.name LIKE '%" . $search . "%' ) ";
                $where = true;
            } else {
                $query .= " AND ( title LIKE '%" . $search . "%' OR erp_users.name LIKE '%" . $search . "%' ) ";
                $count .= " AND ( title LIKE '%" . $search . "%' OR erp_users.name LIKE '%" . $search . "%' ) ";
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
                                <li><a href="#" data-ajax-url="' . url_to('erp.sale.ajaxFetchNotify', $r['notify_id']) . '" title="Edit" class="bg-success modalBtn"><i class="fa fa-pencil"></i> </a></li>
                                <li><a href="' . url_to('sale.notify.order.delete', $order_id, $r['notify_id']) . '" title="Delete" class="bg-danger del-confirm"><i class="fa fa-trash"></i> </a></li>
                            </ul>
                        </div>
                    </div>';
            $notify_at = date("Y-m-d H:i:s", $r['notify_at']);
            $notified = '<span class="st  ';
            if ($r['status'] == 1) {
                $notified .= 'st_violet" >Yes</span>';
            } else {
                $notified .= 'st_dark" >No</span>';
            }
            array_push(
                $m_result,
                array(
                    $r['notify_id'],
                    $sno,
                    $r['title'],
                    $r['notify_text'],
                    $notify_at,
                    $r['notify_to'],
                    $notified,
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


    //Edit Order

    public function update_m_order($order_id, $data)
    {
        $updated = false;
        $this->db->transBegin();
        $query = "UPDATE sale_order SET code=? , cust_id=? , order_date=? , shippingaddr_id=? , terms_condition=? , payment_terms=? , transport_req=? , trans_charge=? , discount=? , order_expiry=? WHERE order_id=$order_id";
        $this->db->query($query, array($data['code'], $data['cust_id'], $data['order_date'], $data['shippingaddr_id'], $data['terms_condition'], $data['payment_terms'], $data['transport_req'], $data['trans_charge'], $data['discount'], $data['order_expiry']));

        if (!$this->update_saleorder_items($order_id)) {
            $this->db->transRollback();
            session()->setFlashdata("op_error", "Sale Order failed to update");
            return $updated;
        }

        $this->db->transComplete();
        $config['title'] = "Sale Order Update";
        if ($this->db->transStatus() === FALSE) {
            $this->db->transRollback();
            $config['log_text'] = "[ Sale Order failed to update ]";
            $config['ref_link'] = "erp/sale/orderview/" . $order_id;
            session()->setFlashdata("op_error", "Sale Order failed to update");
        } else {
            $updated = true;
            $this->db->transCommit();
            $config['log_text'] = "[ Sale Order successfully updated ]";
            $config['ref_link'] = "erp/sale/orderview/" . $order_id;
            session()->setFlashdata("op_success", "Sale Order successfully updated");
        }
        log_activity($config);
        return $updated;
    }

    public function get_m_order_by_id($order_id)
    {
        $query = "SELECT order_id,code,customers.cust_id,customers.name,order_date,order_expiry,shippingaddr_id,transport_req,trans_charge,payment_terms,terms_condition,discount FROM sale_order JOIN customers ON sale_order.cust_id=customers.cust_id WHERE order_id=$order_id";
        $result = $this->db->query($query)->getRow();
        return $result;
    }

    public function get_m_shipping_addr($cust_id)
    {
        $query = "SELECT shippingaddr_id,CONCAT(address,',',city) AS addr FROM customer_shippingaddr WHERE cust_id=$cust_id";
        $result = $this->db->query($query)->getResultArray();
        return $result;
    }

    public function get_m_order_items($order_id)
    {
        $query = "SELECT related_id,CONCAT(finished_goods.name,' ',finished_goods.code) AS product,quantity,price_list.price_id,price_list.amount AS unit_price,(quantity * price_list.amount) AS amount, sale_order_items.tax1 as tax1_amount, sale_order_items.tax2 as tax2_amount, taxes1.tax_name AS tax1_rate, taxes2.tax_name AS tax2_rate FROM sale_order_items JOIN finished_goods ON related_id=finished_goods.finished_good_id JOIN price_list ON sale_order_items.price_id=price_list.price_id
        LEFT JOIN 
            taxes AS taxes1 ON price_list.tax1 = taxes1.tax_id
        LEFT JOIN 
            taxes AS taxes2 ON price_list.tax2 = taxes2.tax_id
        WHERE order_id=$order_id ";
        $result = $this->db->query($query)->getResultArray();
        return $result;
    }

    public function update_c_order($order_id, $data)
    {
        $this->db->transBegin();
        $query = "UPDATE sale_order SET code=? , cust_id=? , order_date=? , terms_condition=? , payment_terms=? , discount=? , order_expiry=? WHERE order_id=$order_id ";
        $this->db->query($query, array($data['code'], $data['cust_id'], $data['order_date'], $data['terms_condition'], $data['payment_terms'], $data['discount'], $data['order_expiry']));

        if (!$this->update_c_saleorder_items($order_id)) {
            $this->db->transRollback();
            session()->setFlashdata("op_error", "Sale Order failed to update");
            return false;
        }

        $this->db->transComplete();
        $config['title'] = "Sale Order Update";
        if ($this->db->transStatus() === FALSE) {
            $this->db->transRollback();
            $config['log_text'] = "[ Sale Order failed to update ]";
            $config['ref_link'] = "erp/sale/orderview/" . $order_id;
            session()->setFlashdata("op_success", "Sale Order failed to update");
        } else {
            $updated = true;
            $this->db->transCommit();
            $config['log_text'] = "[ Sale Order successfully updated ]";
            $config['ref_link'] = "erp/sale/orderview/" . $order_id;
            session()->setFlashdata("op_success", "Sale Order successfully updated");
        }
        log_activity($config);
        return $updated;
    }

    public function get_c_order_by_id($order_id)
    {
        $query = "SELECT code,order_date,order_expiry,payment_terms,terms_condition,discount,sale_order.cust_id,customers.name FROM sale_order JOIN customers ON sale_order.cust_id=customers.cust_id WHERE order_id=$order_id";
        $result = $this->db->query($query)->getRow();
        return $result;
    }

    public function get_c_order_items($order_id)
    {
        $query = "SELECT properties.name AS property,property_unit.unit_name,properties.property_id,prop_unit_id,property_unit.price FROM sale_order_items JOIN property_unit ON sale_order_items.related_id=property_unit.prop_unit_id JOIN properties ON property_unit.property_id=properties.property_id WHERE order_id=$order_id ";
        $result = $this->db->query($query)->getResultArray();
        return $result;
    }

    //order delete

    public function get_order_by_id($order_id)
    {
        return $this->db->table('sale_order')->select()->where('order_id', $order_id)->get()->getRow();
    }

    public function delete_orders($order_id)
    {
        $deleted = false;

        // Retrieve equipment details before deletion for logging purposes
        $order = $this->get_order_by_id($order_id);

        // Delete the equipment
        $this->db->table('sale_order')->where('order_id', $order_id)->delete();

        if ($this->db->affectedRows() > 0) {
            $deleted = true;

            // Log the deletion
            $config = [
                'title' => 'Order Deletion',
                'log_text' => "[ Order successfully deleted ]",
                'ref_link' => 'erp.sale.orderdelete' . $order_id,
                'additional_info' => json_encode($order), // Log order details before deletion
            ];
            log_activity($config);
        } else {
            $config = [
                'title' => 'Order Deletion',
                'log_text' => "[ Order failed to delete ]",
                'ref_link' => '' .  $order_id,
            ];
            log_activity($config);
        }

        return $deleted;
    }

    public function getMaximumOrderNumber()
    {
        $query = "SELECT MAX(order_id) as max_code  FROM sale_order ";
        $result = $this->db->query($query)->getResultArray()[0];
        return ($result['max_code']) ? (int)$result['max_code'] : 0;
    }


    public function getCustomerContactById($order_id)
    {
        $query = "SELECT customer_contacts.email FROM sale_order JOIN customer_contacts ON sale_order.cust_id = customer_contacts.cust_id WHERE order_id = $order_id";
        return $this->db->query($query)->getResultArray();
    }

    public function getCustomerMailById($order_id)
    {
        $query = "SELECT customers.email FROM sale_order JOIN customers ON sale_order.cust_id = customers.cust_id WHERE order_id = $order_id";
        return $this->db->query($query)->getResultArray();
    }
}
