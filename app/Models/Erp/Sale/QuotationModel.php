<?php

namespace App\Models\Erp\Sale;

use App\Models\Erp\ContractorsModel;
use App\Models\Erp\CustomersModel;
use App\Models\Erp\LeadsModel;
use App\Models\Erp\ProjectsModel;
use App\Models\Erp\TicketsModel;
use CodeIgniter\Model;

class QuotationModel extends Model
{
    protected $table            = 'quotations';
    protected $primaryKey       = 'quote_id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'code',
        'quote_date',
        'cust_id',
        'shippingaddr_id',
        'transport_req',
        'trans_charge',
        'discount',
        'terms_condition',
        'payment_terms',
        'status',
        'created_at',
        'created_by',
    ];
    protected $session;
    protected $db;
    protected $logger;
    public function __construct()
    {
        $this->db = \Config\Database::connect();
        $this->session = \Config\Services::session();
        $this->logger = \Config\Services::logger();
    }
    // Dates
    protected $useTimestamps = false;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    private $quote_status = array(
        0 => "Created",
        1 => "Sent",
        2 => "Accepted",
        3 => "Declined",
        4 => "Converted"
    );

    private $quote_status_bg = array(
        0 => "st_dark",
        1 => "st_violet",
        2 => "st_primary",
        3 => "st_danger",
        4 => "st_success"
    );

    public function get_quote_status()
    {
        return $this->quote_status;
    }

    public function get_quote_status_bg()
    {
        return $this->quote_status_bg;
    }

    public function get_dtconfig_quotations()
    {
        $config = array(
            "columnNames" => ["sno", "code", "customer", "quotation date", "status", "transport charge", "discount", "total amount", "action"],
            "sortable" => [0, 1, 0, 1, 0, 0, 0, 1, 0],
            "filters" => ["status"]
        );
        return json_encode($config);
    }

    public function get_quotation_by_id($quote_id)
    {
        $query = "SELECT quote_id,code,subject,customers.cust_id,customers.name,quote_date,currency_id, currency_place, email, phone, transport_req,trans_charge,payment_terms,terms_condition,discount FROM quotations JOIN customers ON quotations.cust_id=customers.cust_id WHERE quote_id=$quote_id";
        $result = $this->db->query($query)->getRow();
        return $result;
    }

    public function get_quotation_items($quote_id)
    {
        $query = "SELECT related_id,CONCAT(finished_goods.name,' ',finished_goods.code) AS product,quantity,price_list.price_id,price_list.amount AS unit_price,(quantity * price_list.amount) AS amount, price_list.tax1 as tax1, price_list.tax2 as tax2,(quantity * price_list.amount) AS amount,sale_order_items.tax1 as tax1_amount, sale_order_items.tax2 as tax2_amount, taxes1.tax_name AS tax1_rate, taxes2.tax_name AS tax2_rate FROM sale_order_items JOIN finished_goods ON related_id=finished_goods.finished_good_id JOIN price_list ON sale_order_items.price_id=price_list.price_id
          LEFT JOIN 
            taxes AS taxes1 ON price_list.tax1 = taxes1.tax_id
        LEFT JOIN 
            taxes AS taxes2 ON price_list.tax2 = taxes2.tax_id WHERE quote_id=$quote_id ";
        $result = $this->db->query($query)->getResultArray();
        return $result;
    }

    public function get_m_shipping_addr($cust_id)
    {
        $query = "SELECT shippingaddr_id,CONCAT(address,',',city) AS addr FROM customer_shippingaddr WHERE cust_id=$cust_id";
        $result = $this->db->query($query)->getResultArray();
        return $result;
    }

    public function get_quotation_for_view($quote_id)
    {
        $query = "SELECT quotations.quote_id,code,customers.name,customers.email,CONCAT(customer_shippingaddr.address,',',customer_shippingaddr.city,',',customer_shippingaddr.state,',',customer_shippingaddr.country,',',customer_shippingaddr.zipcode) AS shipping_addr, CONCAT(customer_billingaddr.address,',',customer_billingaddr.city,',',customer_billingaddr.state,',',customer_billingaddr.country,',',customer_billingaddr.zipcode) AS billing_addr,quote_date,quotations.status,CASE WHEN transport_req=1 THEN 'Yes' ELSE 'No' END AS transport_req,trans_charge,discount,(SUM(amount + (amount * (sale_order_items.tax1 + sale_order_items.tax2)) / 100)+ trans_charge - discount) AS total_amount,payment_terms,terms_condition FROM quotations JOIN customers ON quotations.cust_id=customers.cust_id JOIN sale_order_items ON quotations.quote_id=sale_order_items.quote_id LEFT JOIN customer_shippingaddr ON quotations.cust_id=customer_shippingaddr.cust_id
        LEFT JOIN customer_billingaddr ON quotations.cust_id=customer_billingaddr.cust_id WHERE quotations.quote_id=$quote_id";
        $result = $this->db->query($query)->getRow();
        return $result;
    }

    public function accept_quotation($quote_id)
    {
        $query = "UPDATE quotations SET status=2 WHERE status <=1 AND quote_id=$quote_id ";
        $this->db->query($query);
        $config['title'] = "Quotation Accepted";
        if ($this->db->affectedRows() > 0) {
            $config['log_text'] = "[ Quotation Accepted successfully ]";
            $config['ref_link'] = 'erp/sale/quotationview/' . $quote_id;
            session()->setFlashdata("op_success", "Quotation Accepted successfully");
        } else {
            $config['log_text'] = "[ Quotation Accepted failed ]";
            $config['ref_link'] = 'erp/sale/quotationview/' . $quote_id;
            session()->setFlashdata("op_error", "Quotation Accepted failed");
        }
        log_activity($config);
    }

    public function decline_quotation($quote_id)
    {
        $query = "UPDATE quotations SET status=3 WHERE status <=1 AND quote_id=$quote_id ";
        $this->db->query($query);
        $config['title'] = "Quotation Declined";
        if ($this->db->affectedRows() > 0) {
            $config['log_text'] = "[ Quotation Declined successfully ]";
            $config['ref_link'] = 'erp/sale/quotationview/' . $quote_id;
            session()->setFlashdata("op_success", "Quotation Declined successfully");
        } else {
            $config['log_text'] = "[ Quotation Declined failed ]";
            $config['ref_link'] = 'erp/sale/quotationview/' . $quote_id;
            session()->setFlashdata("op_error", "Quotation Declined failed");
        }
        log_activity($config);
    }

    public function get_quotation_datatable()
    {
        $columns = array(
            "code" => "code",
            "quotation date" => "quote_date",
            "status" => "quotations.status",
            "total amount" => "total_amount"
        );
        $limit = $_GET['limit'];
        $offset = $_GET['offset'];
        $search = $_GET['search'] ?? "";
        $orderby = isset($_GET['orderby']) ? strtoupper($_GET['orderby']) : "";
        $ordercol = isset($_GET['ordercol']) ? $columns[$_GET['ordercol']] : "";

        $query = "SELECT quotations.quote_id,code,customers.name,quote_date,quotations.status,trans_charge,discount,(SUM(amount + (amount * (sale_order_items.tax1 + sale_order_items.tax2)) / 100)+ trans_charge - discount) AS total_amount FROM quotations JOIN customers ON quotations.cust_id=customers.cust_id JOIN sale_order_items ON quotations.quote_id=sale_order_items.quote_id ";
        $count = "SELECT COUNT(quotations.quote_id) AS total FROM quotations JOIN customers ON quotations.cust_id=customers.cust_id JOIN sale_order_items ON quotations.quote_id=sale_order_items.quote_id ";
        $where = false;
        $filter_1_col = isset($_GET['status']) ? $columns['status'] : "";
        $filter_1_val = $_GET['status'];

        if (!empty($filter_1_col) && $filter_1_val !== "") {
            $query .= " WHERE " . $filter_1_col . "=" . $filter_1_val . " ";
            $count .= " WHERE " . $filter_1_col . "=" . $filter_1_val . " ";
            $where = true;
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
        $query .= " GROUP BY quotations.quote_id ";
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
                                <li><a href="' . url_to('erp.sale.quotations.view', $r['quote_id']) . '" title="View" class="bg-warning "><i class="fa fa-eye"></i> </a></li>';
            if ($r['status'] <= 1) {
                $action .= '<li><a href="' . url_to('erp.sale.quotations.edit', $r['quote_id']) . '" title="Edit" class="bg-success "><i class="fa fa-pencil"></i> </a></li>';
            }
            $action .= '<li><a href="' . url_to('erp.sale.quotation.delete', $r['quote_id']) . '" title="Delete" class="bg-danger del-confirm"><i class="fa fa-trash"></i> </a></li>
                            </ul>
                        </div>
                    </div>';
            $status = '<span class="st ' . $this->quote_status_bg[$r['status']] . ' " >' . $this->quote_status[$r['status']] . '</span>';
            array_push(
                $m_result,
                array(
                    $r['quote_id'],
                    $sno,
                    $r['code'],
                    $r['name'],
                    $r['quote_date'],
                    $status,
                    $r['trans_charge'],
                    $r['discount'],
                    number_format($r['total_amount'], 2, '.', ','),
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

    // Export
    public function get_quotation_export($type)
    {
        $columns = array(
            "code" => "code",
            "quotation date" => "quote_date",
            "status" => "quotations.status",
            "total amount" => "total_amount"
        );
        $limit = $_GET['limit'];
        $offset = $_GET['offset'];
        $search = $_GET['search'] ?? "";
        $orderby = isset($_GET['orderby']) ? strtoupper($_GET['orderby']) : "";
        $ordercol = isset($_GET['ordercol']) ? $columns[$_GET['ordercol']] : "";
        $query = "";
        if ($type == "pdf") {
            $query = "SELECT code,customers.name,quote_date,
            CASE ";
            foreach ($this->quote_status as $key => $value) {
                $query .= " WHEN quotations.status=$key THEN '$value' ";
            }
            $query .= " END AS status,CASE WHEN transport_req=1 THEN 'Yes' ELSE 'No' END AS transport_req,trans_charge,discount,(SUM(sale_order_items.amount)+trans_charge-discount) AS total_amount FROM quotations JOIN customers ON quotations.cust_id=customers.cust_id JOIN sale_order_items ON quotations.quote_id=sale_order_items.quote_id ";
        } else {
            $query = "SELECT code,customers.name,CONCAT(customer_shippingaddr.address,',',customer_shippingaddr.city,',',customer_shippingaddr.state,',',customer_shippingaddr.country,',',customer_shippingaddr.zipcode) AS shipping_addr,quote_date,
            CASE ";
            foreach ($this->quote_status as $key => $value) {
                $query .= " WHEN quotations.status=$key THEN '$value' ";
            }
            $query .= " END AS status,CASE WHEN transport_req=1 THEN 'Yes' ELSE 'No' END AS transport_req,trans_charge,discount,(SUM(sale_order_items.amount)+trans_charge-discount) AS total_amount,payment_terms,terms_condition FROM quotations JOIN customers ON quotations.cust_id=customers.cust_id JOIN sale_order_items ON quotations.quote_id=sale_order_items.quote_id LEFT JOIN customer_shippingaddr ON quotations.shippingaddr_id=customer_shippingaddr.shippingaddr_id ";
        }
        $where = false;
        $filter_1_col = isset($_GET['status']) ? $columns['status'] : "";
        $filter_1_val = $_GET['status'];

        if (!empty($filter_1_col) && $filter_1_val !== "") {
            $query .= " WHERE " . $filter_1_col . "=" . $filter_1_val . " ";
            $where = true;
        }

        if (!empty($search)) {
            if (!$where) {
                $query .= " WHERE ( code LIKE '%" . $search . "%' OR customers.name LIKE '%" . $search . "%' ) ";
                $where = true;
            } else {
                $query .= " AND ( code LIKE '%" . $search . "%' OR customers.name LIKE '%" . $search . "%' ) ";
            }
        }
        $query .= " GROUP BY quotations.quote_id ";
        if (!empty($ordercol) && !empty($orderby)) {
            $query .= " ORDER BY " . $ordercol . " " . $orderby;
        }
        $result = $this->db->query($query)->getResultArray();
        return $result;
    }


    // Updated/Edit
    private function update_quotation_items($quote_id)
    {
        $updated = true;
        $product_ids = $_POST["product_id"];
        $quantities = $_POST["quantity"];
        $price_ids = $_POST["price_id"];

        if (empty($product_ids)) {
            return false;
        }
        $result = $this->db->query("SELECT related_id FROM sale_order_items WHERE quote_id=$quote_id")->getResultArray();
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
            $query = "DELETE FROM sale_order_items WHERE related_id IN (" . implode(",", $del_product_ids) . ") AND quote_id=$quote_id";
            $this->db->query($query);
        }
        $query = "SELECT m_sale_order_items(?,?,?,?,?) AS error ";
        foreach ($product_ids as $key => $value) {
            $error = $this->db->query($query, array("quotation", $quote_id, $value, $quantities[$key], $price_ids[$key]))->getRow()->error;
            if ($error == 1) {
                $updated = false;
                break;
            }
        }
        return $updated;
    }

    public function update_quotation($quote_id)
    {
        $updated = false;
        $code = $_POST["code"];
        $cust_id = $_POST["cust_id"];
        $quote_subject = $_POST["quote_subject"];
        $estimate_date = $_POST["quote_date"];
        $quote_expiry_date = $_POST["quote_exp_date"];
        $currency_id = $_POST["currency_id"];
        $currency_place = $_POST["currency_place"];
        // $shippingaddr_id = $_POST["shippingaddr_id"];
        $terms_condition = $_POST["terms_condition"];
        $payment_terms = $_POST["payment_terms"];
        $transport_req = $_POST["transport_req"] ?? 0;
        $trans_charge = $_POST["trans_charge"];
        $discount = $_POST["discount"];

        $this->db->transBegin();
        $query = "UPDATE quotations SET code=? , cust_id=? , subject=? , quote_date=? , expiry_date=? ,currency_id=? , currency_place=?, terms_condition=? , payment_terms=? , transport_req=? , trans_charge=? , discount=? WHERE quote_id=$quote_id ";
        $this->db->query($query, array($code, $cust_id, $quote_subject, $estimate_date, $quote_expiry_date, $currency_id, $currency_place, $terms_condition, $payment_terms, $transport_req, $trans_charge, $discount));

        if (!$this->update_quotation_items($quote_id)) {
            $this->db->transRollback();
            session()->setFlashdata("op_error", "Quotation failed to update");
            return $updated;
        }

        $this->db->transComplete();
        $config['title'] = "Quotation Update";
        if ($this->db->transStatus() === FALSE) {
            $this->db->transRollback();
            $config['log_text'] = "[ Quotation failed to update ]";
            $config['ref_link'] = "erp/sale/quotationview/" . $quote_id;
            session()->setFlashdata("op_error", "Quotation failed to update");
        } else {
            $updated = true;
            $this->db->transCommit();
            $config['log_text'] = "[ Quotation successfully updated ]";
            $config['ref_link'] = "erp/sale/quotationview/" . $quote_id;
            session()->setFlashdata("op_success", "Quotation successfully updated");
        }
        log_activity($config);
        return $updated;
    }

    // View
    public function get_quotation_items_for_view($quote_id)
    {
        $query = "SELECT CONCAT(finished_goods.name,' ',finished_goods.code) AS product,quantity,amount,unit_price FROM sale_order_items JOIN finished_goods ON related_id=finished_goods.finished_good_id WHERE quote_id=$quote_id ";
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

    public function get_attachments($type, $related_id)
    {
        $query = "SELECT filename,attach_id FROM attachments WHERE related_to='$type' AND related_id=$related_id";
        $result = $this->db->query($query)->getResultArray();
        return $result;
    }

    // Upload
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
                    $attach_id = $this->db->insertID();
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
                if ($this->db->affectedRows() > 0) {
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


    public function get_quotationnotify_datatable()
    {
        $quote_id = $_GET["quoteid"];
        $columns = array(
            "notify at" => "notify_at",
            "title" => "title"
        );
        $limit = $_GET['limit'] ?? 10;
        $offset = $_GET['offset'] ?? 0;
        $search = $_GET['search'] ?? "";
        $orderby = isset($_GET['orderby']) ? strtoupper($_GET['orderby']) : "";
        $ordercol = isset($_GET['ordercol']) ? $columns[$_GET['ordercol']] : "";

        $query = "SELECT notify_id,title,notify_at,status,erp_users.name AS notify_to,notify_text FROM notification JOIN erp_users ON notification.user_id=erp_users.user_id WHERE related_to='quotation' AND related_id=$quote_id ";
        $count = "SELECT COUNT(notify_id) AS total FROM notification JOIN erp_users ON notification.user_id=erp_users.user_id WHERE related_to='quotation' AND related_id=$quote_id ";
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
                                <li><a href="' . url_to('erp.sale.quotation.quotationnotifydelete', $quote_id, $r['notify_id']) . '" title="Delete" class="bg-danger del-confirm"><i class="fa fa-trash"></i> </a></li>
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

    public function insert_quotation($data)
    {
        $logger = \Config\Services::logger();
        $logger->error($data);
        $this->db->transBegin();
        $inserted = false;

        $query = "INSERT INTO quotations(code, cust_id, subject, quote_date, expiry_date, currency_id, currency_place, terms_condition, payment_terms, transport_req, trans_charge, discount, created_at, created_by) VALUES(?,?,?,?,?,?,?,?,?,?,?,?,?,?) ";
        $this->db->query($query, array($data['code'], $data['cust_id'], $data['subject'], $data['quote_date'], $data['expiry_date'], $data['currency_id'], $data['currency_place'], $data['terms_condition'], $data['payment_terms'], $data['transport_req'], $data['trans_charge'], $data['discount'], time(), $data['created_by']));

        //update the customer's email and phone no when its new
        // $cust_id = $data['cust_id'];
        // $logger->error($cust_id);
        // $query = "UPDATE customers SET email = '" . $data['email'] . "', phone = '" . $data['phone'] . "' WHERE cust_id = " . $cust_id;
        // $this->db->query($query);

        $quote_id = $this->db->insertID();
        if (empty($quote_id)) {
            session()->setFlashdata("op_error", "Quotation failed to create");
            return false;
        }

        if (!$this->update_quotation_items($quote_id)) {
            $this->db->transRollback();
            session()->setFlashdata("op_error", "Quotation failed to create");
            return false;
        }

        $this->db->transComplete();
        $config['title'] = "Quotation Insert";
        if ($this->db->transStatus() === FALSE) {
            $this->db->transRollback();
            $config['log_text'] = "[ Quotation failed to create ]";
            $config['ref_link'] = "erp.sale.quotation.add";
            session()->setFlashdata("op_error", "Quotation failed to create");
        } else {
            $inserted = true;
            $this->db->transCommit();
            $config['log_text'] = "[ Quotation successfully created ]";
            $config['ref_link'] = "erp/sale/quotationview/" . $quote_id;
            session()->setFlashdata("op_success", "Quotation successfully created");
        }
        log_activity($config);
        return $inserted;
    }

    public function getQuotation()
    {
        return $this->builder()->select("quote_id,code")->get()->getResultArray();
    }

    public function getQuotationName($relatedOptionValue)
    {
        return $this->builder()->select("code as name")->where('quote_id', $relatedOptionValue)->get()->getRow();
    }

    //$quotation delete

    public function get_quote_by_id($quote_id)
    {
        return $this->db->table('quotations')->select()->where('quote_id', $quote_id)->get()->getRow();
    }

    public function delete_Quotation($quote_id)
    {
        $deleted = false;

        $quotation = $this->get_quote_by_id($quote_id);

        $this->db->table('quotations')->where('quote_id', $quote_id)->delete();

        if ($this->db->affectedRows() > 0) {
            $deleted = true;

            $config = [
                'title' => 'Quotation Deletion',
                'log_text' => "[ Quotation successfully deleted ]",
                'ref_link' => 'erp.sale.quotation.delete' . $quote_id,
                'additional_info' => json_encode($quotation),
            ];
            log_activity($config);
        } else {
            $config = [
                'title' => 'Quotation Deletion',
                'log_text' => "[ Quotation failed to delete ]",
                'ref_link' => '' .  $quote_id,
            ];
            log_activity($config);
        }
        return $deleted;
    }

    public function relatedEditData($relatedOptionValue, $relateToOptionValue)
    {
        // $this->logger->error($relatedOptionValue . ' HIIII ' . $relateToOptionValue);
        $LeadsModel = new LeadsModel();
        $ProjectsModel = new ProjectsModel();
        $InvoiceModel = new InvoiceModel();
        $CustomersModel = new CustomersModel();
        $EstimatesModel = new EstimatesModel();
        $ContractorsModel = new ContractorsModel();
        $TicketsModel = new TicketsModel();
        $QuotationModel = new QuotationModel();
        $returnData = [];
        if ($relateToOptionValue === "lead") {
            $this->logger->error(1);
            return $returnData = $LeadsModel->getLeadDataName($relatedOptionValue);
        } else if ($relateToOptionValue === "project") {
            $this->logger->error(1);
            return $returnData = $ProjectsModel->getProjectName($relatedOptionValue);
        } else if ($relateToOptionValue === "invoice") {
            $this->logger->error(2);
            return $returnData = $InvoiceModel->getInvoiceName($relatedOptionValue);
        } else if ($relateToOptionValue === "customer") {
            $this->logger->error(3);
            return $returnData = $CustomersModel->getCustomerName($relatedOptionValue);
        } else if ($relateToOptionValue === "estimates") {
            $this->logger->error(4);
            return $returnData = $EstimatesModel->getEstimatesName($relatedOptionValue);
        } else if ($relateToOptionValue === "contract") {
            $this->logger->error(5);
            return $returnData = $ContractorsModel->getContractorsName($relatedOptionValue);
        } else if ($relateToOptionValue === "ticket") {
            $this->logger->error(6);
            return $returnData = $TicketsModel->getTicketName($relatedOptionValue);
        } else if ($relateToOptionValue === "quotation") {
            $this->logger->error(7);
            return $returnData = $QuotationModel->getQuotationName($relatedOptionValue);
        }
        return $returnData;
        $this->logger->error($returnData);
    }

    public function quotationStatus()
    {
        $query = "SELECT status,COUNT(status) FROM `quotations` WHERE status IN (0,1,2,3,4) GROUP BY status";
        $result = $this->db->query($query)->getResultArray();
        return $result;
    }

    public function getAllTime()
    {
        return $this->builder()->select('created_at')->get()->getResultArray();
    }

    // public function getMaximumQuotationNumber(){
    //     $query = "SELECT MAX(quote_id) as max_code  FROM quotations";
    //     return $this->db->query($query)->getRowArray();
    // }

    public function getMaximumQuotationNumber()
    {
        $query = "SELECT MAX(quote_id) as max_code FROM quotations";
        $result = $this->db->query($query)->getRowArray();
        return ($result['max_code']) ? (int)$result['max_code'] : 0;
    }

    public function getCustomerContactById($quote_id)
    {
        $query = "SELECT customer_contacts.email FROM quotations JOIN customer_contacts ON quotations.cust_id = customer_contacts.cust_id WHERE quote_id = $quote_id";
        return $this->db->query($query)->getResultArray();
    }

    public function getCustomerMailById($quote_id)
    {
        $query = "SELECT customers.email FROM quotations JOIN customers ON quotations.cust_id = customers.cust_id WHERE quote_id = $quote_id";
        return $this->db->query($query)->getResultArray();
    }

    public function getQuotationNumberById($quote_id)
    {
        $query = "SELECT code, subject, expiry_date, quote_date FROM quotations WHERE quote_id = $quote_id";
        return $this->db->query($query)->getResultArray()[0];
    }

    public function getSelectedCurrencyPlace($quote_id)
    {
        $query = "SELECT currency_place FROM quotations WHERE quote_id = $quote_id";
        return $this->db->query($query)->getResultArray()[0];
    }

    public function getSelectedCurrencyName($quote_id, $currency_place)
    {
        $query = "SELECT iso_code, symbol, currencies.currency_id FROM quotations JOIN currencies ON quotations.currency_id = currencies.currency_id WHERE quote_id = ? AND currency_place = ?";
        return $this->db->query($query, [$quote_id, $currency_place])->getResultArray()[0];
    }

    public function getBillingAddressByQuotationId($quote_id)
    {
        $query = "SELECT customers.name as customer_name, customer_billingaddr.address, customer_billingaddr.city,customer_billingaddr.state, customer_billingaddr.country, customer_billingaddr.zipcode FROM quotations 
        JOIN customer_billingaddr ON quotations.cust_id = customer_billingaddr.cust_id
        JOIN customers ON customer_billingaddr.cust_id = customers.cust_id
        WHERE quote_id = $quote_id";
        return $this->db->query($query)->getResultArray()[0];
    }

    public function getShippingAddressByQuotationId($quote_id)
    {
        $query = "SELECT customers.name as customer_name, customer_shippingaddr.address, customer_shippingaddr.city, 
        customer_shippingaddr.state, customer_shippingaddr.country, customer_shippingaddr.zipcode FROM quotations 
        JOIN customer_shippingaddr ON quotations.cust_id = customer_shippingaddr.cust_id
        JOIN customers ON customer_shippingaddr.cust_id = customers.cust_id
        WHERE quote_id = $quote_id";
        return $this->db->query($query)->getResultArray()[0];
    }

    public function getQuotationItemsById($quote_id)
    {
        $query = "SELECT CONCAT(finished_goods.name,' ',finished_goods.code) as product_name, 
        sale_order_items.quantity, 
        sale_order_items.unit_price, 
        sale_order_items.amount as amount, 
        ((sale_order_items.tax1 + sale_order_items.tax2)/100) as total_tax,
        (sale_order_items.amount + (sale_order_items.amount * (sale_order_items.tax1 + sale_order_items.tax2)) / 100) AS amount_with_tax, trans_charge, discount, sale_order_items.tax1 as tax1_amount, sale_order_items.tax2 as tax2_amount,
        taxes1.tax_name AS tax1_rate, taxes2.tax_name AS tax2_rate
        FROM quotations 
        JOIN sale_order_items ON quotations.quote_id = sale_order_items.quote_id
        JOIN finished_goods ON sale_order_items.related_id = finished_goods.finished_good_id 
        JOIN price_list ON sale_order_items.price_id=price_list.price_id
          LEFT JOIN 
            taxes AS taxes1 ON price_list.tax1 = taxes1.tax_id
        LEFT JOIN 
            taxes AS taxes2 ON price_list.tax2 = taxes2.tax_id WHERE quotations.quote_id=$quote_id";
        return $this->db->query($query)->getResultArray();
    }

    public function getCustomerDataByQuotationId($quote_id)
    {
        $query = "SELECT customers.name, customers.email, customers.phone, customers.address, customers.state, customers.city, customers.country, customers.zip, customers.company FROM quotations JOIN customers ON quotations.cust_id = customers.cust_id WHERE quote_id= $quote_id";
        return $this->db->query($query)->getResultArray()[0];
    }

    public function getCurrencyPlaceById($quote_id)
    {
        $query = "SELECT currency_place FROM quotations JOIN currencies ON quotations.currency_id = currencies.currency_id WHERE quote_id= $quote_id";
        return $this->db->query($query)->getResultArray()[0];
    }

    public function getCurrencySymbolbyId($quote_id)
    {
        $query = "SELECT currencies.symbol FROM quotations JOIN currencies ON quotations.currency_id = currencies.currency_id WHERE quote_id= ?";
        return $this->db->query($query, [$quote_id])->getResultArray()[0];
    }
}
