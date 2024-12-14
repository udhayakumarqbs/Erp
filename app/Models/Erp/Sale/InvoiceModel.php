<?php

namespace App\Models\Erp\Sale;

use CodeIgniter\Model;
use DateTime;
use App\libraries\Scheduler;
use App\libraries\job\Notify;
use CodeIgniter\Database\Query;

class InvoiceModel extends Model
{
    protected $table = 'sale_invoice';
    protected $primaryKey = 'invoice_id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = [
        'code',
        'invoice_date',
        'invoice_expiry',
        'cust_id',
        'shippingaddr_id',
        'transport_req',
        'trans_charge',
        'discount',
        'terms_condition',
        'payment_terms',
        'status',
        'remarks',
        'paid_till',
        'type',
        'can_edit',
        'created_at',
        'created_by',
    ];

    // Dates
    protected $useTimestamps = false;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    protected $invoice_status = array(
        0 => "Created",
        1 => "Partially Paid",
        2 => "Paid",
        3 => "Overdue"
    );

    protected $invoice_status_bg = array(
        0 => "st_dark",
        1 => "st_violet",
        2 => "st_success",
        3 => "st_danger"
    );

    public function get_invoice_status()
    {
        return $this->invoice_status;
    }

    public function get_invoice_status_bg()
    {
        return $this->invoice_status_bg;
    }


    public function create_m_invoice($order_id, $data)
    {
        $query = "SELECT * FROM sale_order WHERE order_id=$order_id";
        $result = $this->db->query($query)->getRow();
        if (empty($result)) {
            session()->setFlashdata("op_error", "Invoice creation failed");
            return $data['created'];
        }
        $created_by = get_user_id();
        $this->db->transBegin();
        $query = "INSERT INTO sale_invoice(code,invoice_date,invoice_expiry,cust_id,shippingaddr_id,transport_req,trans_charge,discount,terms_condition,payment_terms,created_at,created_by,type,can_edit) VALUES(?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
        $this->db->query($query, array($data['code'], $data['invoice_date'], $data['invoice_expiry'], $result->cust_id, $result->shippingaddr_id, $result->transport_req, $result->trans_charge, $result->discount, $result->terms_condition, $result->payment_terms, time(), $created_by, 1, 0));
        $invoice_id = $this->db->insertID();
        if (empty($invoice_id)) {
            session()->setFlashdata("op_error", "Invoice creation failed");
            return $data['created'];
        }
        $this->db->query("UPDATE sale_order_items SET invoice_id=$invoice_id WHERE order_id=$order_id");
        $this->db->query("UPDATE sale_order SET status=1 , can_edit=0 WHERE order_id=$order_id");
        $this->db->transComplete();
        $config['title'] = "Invoice Creation";
        if ($this->db->transStatus() === FALSE) {
            $this->db->transRollback();
            $config['log_text'] = "[ Invoice Creation failed ]";
            $config['ref_link'] = "";
            session()->setFlashdata("op_error", "Invoice Creation failed");
        } else {
            $this->db->transCommit();
            $created = true;
            $config['log_text'] = "[ Invoice Creation successfully done ]";
            $config['ref_link'] = 'erp/sale/invoiceview/' . $invoice_id;
            session()->setFlashdata("op_success", "Invoice Creation successfully done");
        }
        log_activity($config);
        return $created;
    }

    public function get_dtconfig_m_invoices()
    {
        $config = array(
            "columnNames" => ["sno", "code", "customer", "invoice date", "invoice expiry", "status", "transport charge", "discount", "total amount", "action"],
            "sortable" => [0, 1, 0, 1, 1, 0, 0, 0, 1, 0],
            "filters" => ["status"]
        );
        return json_encode($config);
    }

    public function get_dtconfig_c_invoices()
    {
        $config = array(
            "columnNames" => ["sno", "code", "customer", "invoice date", "invoice expiry", "status", "discount", "total amount", "action"],
            "sortable" => [0, 1, 0, 1, 1, 0, 1, 0],
            "filters" => ["status"]
        );
        return json_encode($config);
    }

    public function get_m_invoice_datatable()
    {
        $columns = array(
            "code" => "code",
            "invoice date" => "invoice_date",
            "invoice expiry" => "invoice_expiry",
            "status" => "sale_invoice.status",
            "total amount" => "total_amount"
        );
        $limit = $_GET['limit'];
        $offset = $_GET['offset'];
        $search = $_GET['search'] ?? "";
        $orderby = isset($_GET['orderby']) ? strtoupper($_GET['orderby']) : "";
        $ordercol = isset($_GET['ordercol']) ? $columns[$_GET['ordercol']] : "";

        $query = "SELECT a.invoice_id as id,a.type as type,a.code as code,d.name as name, a.invoice_date as date,a.invoice_expiry as expiry,a.status as status,a.trans_charge as trans_charge,a.discount as discount,(SUM(b.amount + (b.amount * (b.tax1 + b.tax2)) / 100)+ a.trans_charge - a.discount) AS s_total_amount,
        a.can_edit ,(SUM(c.amount + (c.amount * (c.tax1 + c.tax2)) / 100)+ a.trans_charge) AS e_total_amount
        FROM sale_invoice as a JOIN customers as d ON a.cust_id=d.cust_id
        LEFT JOIN sale_order_items  as b ON a.invoice_id=b.invoice_id AND b.related_to = 'finished_good'
        LEFT JOIN expense_items as c ON  a.invoice_id = c.invoice_id AND c.related_to = 'expense'
        WHERE a.type=1 or a.type = 3";
        $count = "SELECT COUNT(sale_invoice.invoice_id) AS total FROM sale_invoice JOIN customers ON sale_invoice.cust_id=customers.cust_id JOIN sale_order_items ON sale_invoice.invoice_id=sale_order_items.invoice_id WHERE sale_invoice.type=1 ";
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
                $query .= " WHERE ( code LIKE '%" . $search . "%' OR d.name LIKE '%" . $search . "%' ) ";
                $count .= " WHERE ( code LIKE '%" . $search . "%' OR d.name LIKE '%" . $search . "%' ) ";
                $where = true;
            } else {
                $query .= " AND ( code LIKE '%" . $search . "%' OR d.name LIKE '%" . $search . "%' ) ";
                $count .= " AND ( code LIKE '%" . $search . "%' OR d.name LIKE '%" . $search . "%' ) ";
            }
        }
        $query .= " GROUP BY a.invoice_id ";
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
                                <li><a href="' . url_to('erp.sale.invoice.view', $r['id']) . '" title="View" class="bg-warning "><i class="fa fa-eye"></i> </a></li>';
            if ($r['can_edit'] == 1) {
                $action .= '<li><a href="' . url_to('erp.sale.invoice.edit', $r['id']) . '" title="Edit" class="bg-success "><i class="fa fa-pencil"></i> </a></li>';
            }
            $action .= '<li><a href="' . url_to('erp.sale.invoice.delete', $r['id']) . '" title="Delete" class="bg-danger del-confirm"><i class="fa fa-trash"></i> </a></li>
                            </ul>
                        </div>
                    </div>';
            $status = '<span class="st ' . $this->invoice_status_bg[$r['status']] . ' " >' . $this->invoice_status[$r['status']] . '</span>';
            if ($r["type"] == 3) {
                array_push(
                    $m_result,
                    array(
                        $r['id'],
                        $sno,
                        $r['code'],
                        $r['name'],
                        $r['date'],
                        $r['expiry'],
                        $status,
                        $r['trans_charge'],
                        $r['discount'],
                        number_format($r['e_total_amount'], 2, '.', ','),
                        $action
                    )
                );
            } else {
                array_push(
                    $m_result,
                    array(
                        $r['id'],
                        $sno,
                        $r['code'],
                        $r['name'],
                        $r['date'],
                        $r['expiry'],
                        $status,
                        $r['trans_charge'],
                        $r['discount'],
                        number_format($r['s_total_amount'], 2, '.', ','),
                        $action
                    )
                );
            }
            $sno++;
        }
        $response = array();
        $response['total_rows'] = $this->db->query($count)->getRow()->total;
        $response['data'] = $m_result;
        return $response;
    }

    public function create_c_invoice($order_id, $data)
    {
        $query = "SELECT * FROM sale_order WHERE order_id=$order_id";
        $result = $this->db->query($query)->getRow();
        if (empty($result)) {
            session()->setFlashdata("op_error", "Invoice creation failed");
            return $data['created'];
        }
        $created_by = get_user_id();
        $this->db->transBegin();
        $query = "INSERT INTO sale_invoice(code,invoice_date,invoice_expiry,cust_id,discount,terms_condition,payment_terms,created_at,created_by,type,can_edit) VALUES(?,?,?,?,?,?,?,?,?,?,?)";
        $this->db->query($query, array($data['code'], $data['invoice_date'], $data['invoice_expiry'], $result->cust_id, $result->discount, $result->terms_condition, $result->payment_terms, time(), $created_by, 0, 0));
        $invoice_id = $this->db->insertId();
        if (empty($invoice_id)) {
            $this->session()->setFlashdata("op_error", "Invoice creation failed");
            return $data['created'];
        }
        $this->db->query("UPDATE sale_order_items SET invoice_id=$invoice_id WHERE order_id=$order_id");
        $this->db->query("UPDATE sale_order SET status=1 , can_edit=0 WHERE order_id=$order_id");
        $this->db->transComplete();
        $config['title'] = "Invoice Creation";
        if ($this->db->transStatus() === FALSE) {
            $this->db->transrollback();
            $config['log_text'] = "[ Invoice Creation failed ]";
            $config['ref_link'] = 'erp/sale/invoiceview/' . $invoice_id;
            session()->setFlashdata("op_error", "Invoice Creation failed");
        } else {
            $this->db->transCommit();
            $created = true;
            $config['log_text'] = "[ Invoice Creation successfully done ]";
            $config['ref_link'] = 'erp/sale/invoiceview/' . $invoice_id;
            session()->setFlashdata("op_success", "Invoice Creation successfully done");
        }
        log_activity($config);
        return $created;
    }



    public function get_c_invoice_datatable()
    {
        $columns = array(
            "code" => "code",
            "invoice date" => "invoice_date",
            "invoice expiry" => "invoice_expiry",
            "status" => "sale_invoice.status",
            "total amount" => "total_amount"
        );
        $limit = $_GET['limit'];
        $offset = $_GET['offset'];
        $search = $_GET['search'] ?? "";
        $orderby = isset($_GET['orderby']) ? strtoupper($_GET['orderby']) : "";
        $ordercol = isset($_GET['ordercol']) ? $columns[$_GET['ordercol']] : "";

        $query = "SELECT sale_invoice.invoice_id,code,customers.name,invoice_date,invoice_expiry,sale_invoice.status,discount,(SUM(sale_order_items.amount)-discount) AS total_amount,can_edit FROM sale_invoice JOIN customers ON sale_invoice.cust_id=customers.cust_id JOIN sale_order_items ON sale_invoice.invoice_id=sale_order_items.invoice_id WHERE sale_invoice.type=0 ";
        $count = "SELECT COUNT(sale_invoice.invoice_id) AS total FROM sale_invoice JOIN customers ON sale_invoice.cust_id=customers.cust_id JOIN sale_order_items ON sale_invoice.invoice_id=sale_order_items.invoice_id WHERE sale_invoice.type=0 ";
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
        $query .= " GROUP BY sale_invoice.invoice_id ";
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
                                <li><a href="' . url_to('erp.sale.invoice.view', $r['invoice_id']) . '" title="View" class="bg-warning "><i class="fa fa-eye"></i> </a></li>';
            if ($r['can_edit'] == 1) {
                $action .= '<li><a href="' . url_to('erp.sale.invoice.edit', $r['invoice_id']) . '" title="Edit" class="bg-success "><i class="fa fa-pencil"></i> </a></li>';
            }
            $action .= '<li><a href="' . url_to('erp.sale.invoice.delete', $r['invoice_id']) . '" title="Delete" class="bg-danger del-confirm"><i class="fa fa-trash"></i> </a></li>
                            </ul>
                        </div>
                    </div>';
            $status = '<span class="st ' . $this->invoice_status_bg[$r['status']] . ' " >' . $this->invoice_status[$r['status']] . '</span>';
            array_push(
                $m_result,
                array(
                    $r['invoice_id'],
                    $sno,
                    $r['code'],
                    $r['name'],
                    $r['invoice_date'],
                    $r['invoice_expiry'],
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

    //view
    public function get_m_invoice_for_view($invoice_id)
    {
        $query = "SELECT sale_invoice.invoice_id,code,invoice_date,invoice_expiry,payment_terms,
        terms_condition,discount,(SUM(amount + (amount * (sale_order_items.tax1 + sale_order_items.tax2)) / 100)+ trans_charge-discount) AS total_amount,
        customers.name, customers.email, CONCAT(customer_shippingaddr.address,',',customer_shippingaddr.city,',',
        customer_shippingaddr.state,',',customer_shippingaddr.country,',',customer_shippingaddr.zipcode) AS shipping_addr,
        CONCAT(customer_billingaddr.address,',',customer_billingaddr.city,',',customer_billingaddr.state,',',
        customer_billingaddr.country,',',customer_billingaddr.zipcode) AS billing_addr,
        CASE WHEN transport_req=1 THEN 'Yes' ELSE 'No' END AS transport_req,trans_charge,sale_invoice.status,
        paid_till,(SUM(amount + (amount * (sale_order_items.tax1 + sale_order_items.tax2)) / 100)+trans_charge-discount-paid_till) AS amount_due FROM sale_invoice
        JOIN customers ON sale_invoice.cust_id=customers.cust_id
        JOIN sale_order_items ON sale_invoice.invoice_id=sale_order_items.invoice_id 
        LEFT JOIN customer_shippingaddr ON sale_invoice.cust_id=customer_shippingaddr.cust_id
        LEFT JOIN customer_billingaddr ON sale_invoice.cust_id=customer_billingaddr.cust_id WHERE sale_invoice.invoice_id=$invoice_id ";
        $result = $this->db->query($query)->getRow();
        // var_dump($result);
        // exit;
        return $result;
    }

    /**
     * Used to get the invoice of expenses
     * Summary of get_e_invoice_for_view
     * @param mixed $invoice_id
     * @return array
     */
    public function get_e_invoice_for_view($invoice_id)
    {
        $query = "SELECT sale_invoice.invoice_id,code,invoice_date,invoice_expiry,payment_terms,
        terms_condition,discount,(SUM(amount + (amount * (expense_items.tax1 + expense_items.tax2)) / 100 )+ trans_charge-discount)as total_amount,
        customers.name, customers.email, CONCAT(customer_shippingaddr.address,',',customer_shippingaddr.city,',',customer_shippingaddr.state,',',customer_shippingaddr.country,',',customer_shippingaddr.zipcode) AS shipping_addr,
        CONCAT(customer_billingaddr.address,',',customer_billingaddr.city,',',customer_billingaddr.state,',',customer_billingaddr.country,',',customer_billingaddr.zipcode) AS billing_addr,
        CASE WHEN transport_req=1 THEN 'Yes' ELSE 'No' END AS transport_req,trans_charge,sale_invoice.status,
        paid_till,(SUM(expense_items.amount + (expense_items.amount * (expense_items.tax1 + expense_items.tax2)) / 100)+trans_charge-discount-paid_till) AS amount_due FROM sale_invoice
        JOIN customers ON sale_invoice.cust_id=customers.cust_id
        JOIN expense_items ON sale_invoice.invoice_id=expense_items.invoice_id 
        LEFT JOIN customer_shippingaddr ON sale_invoice.cust_id=customer_shippingaddr.cust_id
        LEFT JOIN customer_billingaddr ON sale_invoice.cust_id=customer_billingaddr.cust_id WHERE sale_invoice.invoice_id=$invoice_id";
        $result = $this->db->query($query)->getRow();
        return $result;
    }


    public function getInvoiceStatus($invoice, $applied_credits)
    {
        $amount_due_after_credits = max(0, $invoice->amount_due - $applied_credits);

        if ($amount_due_after_credits == 0) {
            return 2; // Paid
        } elseif ($amount_due_after_credits > 0 && $amount_due_after_credits < $invoice->total_amount) {
            return 1; // Partially Paid
        } elseif ($amount_due_after_credits == $invoice->total_amount && strtotime($invoice->invoice_expiry) < time()) {
            return 3; // Overdue
        } else {
            return 0; // Created
        }
    }

    public function updateInvoiceStatus($invoice_id, $new_status)
    {
        $data = [
            'status' => $new_status,
        ];
        $this->db->table('sale_invoice')->where('invoice_id', $invoice_id)->update($data);
    }


    public function get_m_invoice_items_for_view($invoice_id)
    {
        $query = "SELECT CONCAT(finished_goods.name,' ',finished_goods.code) AS product,quantity,sale_order_items.unit_price,sale_order_items.amount FROM sale_order_items JOIN finished_goods ON related_id=finished_goods.finished_good_id WHERE invoice_id=$invoice_id";
        $result = $this->db->query($query)->getResultArray();
        return $result;
    }
    public function get_e_invoice_items_for_view($invoice_id)
    {
        $query = "SELECT ec.name as expensetype ,i.quantity  as quantity,SUM(i.amount + (i.amount * (i.tax1 + i.tax2)) / 100 ) as total_amount FROM expense_items as i
        JOIN sale_invoice as s ON i.invoice_id = s.invoice_id
        JOIN erpexpenses as e ON i.related_id = e.id
        LEFT JOIN erp_expenses_categories as ec ON e.category  = ec.id
        WHERE s.invoice_id = $invoice_id AND s.type = 3";
        $result = $this->db->query($query)->getResultArray();
        // var_dump($result);
        // exit;
        return $result;
    }

    public function get_c_invoice_for_view($invoice_id)
    {
        $query = "SELECT sale_invoice.invoice_id,code,invoice_date,invoice_expiry,payment_terms,terms_condition,discount,(SUM(sale_order_items.amount)-discount) AS total_amount,customers.name,sale_invoice.status,paid_till,(SUM(sale_order_items.amount)-discount-paid_till) AS amount_due FROM sale_invoice JOIN customers ON sale_invoice.cust_id=customers.cust_id JOIN sale_order_items ON sale_invoice.invoice_id=sale_order_items.invoice_id WHERE sale_invoice.invoice_id=$invoice_id ";
        $result = $this->db->query($query)->getRow();
        return $result;
    }

    public function get_c_invoice_items_for_view($invoice_id)
    {
        $query = "SELECT properties.name AS property,property_unit.unit_name,sale_order_items.amount FROM sale_order_items JOIN property_unit ON sale_order_items.related_id=property_unit.prop_unit_id JOIN properties ON property_unit.property_id=properties.property_id WHERE invoice_id=$invoice_id ";
        $result = $this->db->query($query)->getResultArray();
        return $result;
    }

    public function get_all_paymentmodes()
    {
        $query = "SELECT name,payment_id FROM payment_modes WHERE active=1";
        $result = $this->db->query($query)->getResultArray();
        return $result;
    }

    public function getpaymentmodeById($id)
    {
        $query = "SELECT payment_modes.name, payment_modes.payment_id FROM sale_payments JOIN payment_modes ON sale_payments.payment_id = payment_modes.payment_id WHERE active=1 AND sale_payments.sale_pay_id = $id";
        $result = $this->db->query($query)->getResultArray()[0];
        return $result;
    }

    public function get_dtconfig_payments()
    {
        $config = array(
            "columnNames" => ["sno", "payment mode", "amount", "paid on", "transaction id", "action"],
            "sortable" => [0, 1, 1, 1, 0, 0],
        );
        return json_encode($config);
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

    public function insert_update_invoicenotify($invoice_id)
    {

        if (empty($notify_id)) {
            $this->insert_invoicenotify($invoice_id);
        } else {
            $this->update_invoicenotify($invoice_id, $notify_id);
        }
    }

    private function insert_invoicenotify($invoice_id)
    {
        $notify_title = $_POST["notify_title"];
        $notify_desc = $_POST["notify_desc"];
        $notify_to = $_POST["notify_to"];
        $notify_at = $_POST["notify_at"];
        $notify_email = $_POST["notify_email"] ?? 0;
        $related_to = 'sale_invoice';
        $created_by = get_user_id();
        $datetime = DateTime::createFromFormat("Y-m-d\TH:i", $notify_at);
        $timestamp = $datetime->getTimestamp();

        $this->db->transBegin();
        $query = "INSERT INTO notification(title,notify_text,user_id,notify_email,notify_at,related_to,related_id,created_at,created_by) VALUES(?,?,?,?,?,?,?,?,?) ";
        $this->db->query($query, array($notify_title, $notify_desc, $notify_to, $notify_email, $timestamp, $related_to, $invoice_id, time(), $created_by));
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

        $config['title'] = "Sale Invoice Notification Insert";
        $this->db->transComplete();
        if ($this->db->transStatus() === FALSE) {
            $inserted = false;
            $this->db->transRollback();
            $config['log_text'] = "[ Sale Invoice Notification failed to create ]";
            $config['ref_link'] = 'erp/sale/invoiceview/' . $invoice_id;
            session()->setFlashdata("op_error", "Sale Invoice Notification failed to create");
        } else {
            $this->db->transCommit();
            $config['log_text'] = "[ Sale Invoice Notification successfully created ]";
            $config['ref_link'] = 'erp/sale/invoiceview/' . $invoice_id;
            session()->setFlashdata("op_success", "Sale Invoice Notification successfully created");
        }
        log_activity($config);
    }

    public function get_invoicenotify_datatable()
    {
        $invoice_id = $_GET['invoiceid'];
        $columns = array(
            "notify at" => "notify_at",
            "title" => "title"
        );
        $limit = $_GET['limit'];
        $offset = $_GET['offset'];
        $search = $_GET['search'] ?? "";
        $orderby = isset($_GET['orderby']) ? strtoupper($_GET['orderby']) : "";
        $ordercol = isset($_GET['ordercol']) ? $columns[$_GET['ordercol']] : "";

        $query = "SELECT notify_id,title,notify_at,status,erp_users.name AS notify_to,notify_text FROM notification JOIN erp_users ON notification.user_id=erp_users.user_id WHERE related_to='sale_invoice' AND related_id=$invoice_id ";
        $count = "SELECT COUNT(notify_id) AS total FROM notification JOIN erp_users ON notification.user_id=erp_users.user_id WHERE related_to='sale_invoice' AND related_id=$invoice_id ";
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
                                <li><a href="#" data-ajax-url="' . url_to('erp.crm.ajaxfetchnotify', $r['notify_id']) . '" title="Edit" class="bg-success modalBtn"><i class="fa fa-pencil"></i> </a></li>
                                <li><a href="' . url_to('sale.notify.invoice.delete', $invoice_id, $r['notify_id']) . '" title="Delete" class="bg-danger del-confirm"><i class="fa fa-trash"></i> </a></li>
                            </ul>
                        </div>
                    </div>';
            $notify_at = $r['notify_at'];
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

    public function delete_invoice_notify($invoice_id, $notify_id)
    {
        $jobresult = $this->db->query("SELECT job_id FROM notification WHERE notify_id=$notify_id")->getRow();
        $config['title'] = "Sale Invoice Notification Delete";
        if (!empty($jobresult)) {
            $jobid = $jobresult->job_id;
            $query = "DELETE FROM notification WHERE notify_id=$notify_id ";
            $this->db->query($query);
            Scheduler::safeDelete($jobid);
            $config['log_text'] = "[ Sale Invoice Notification successfully deleted ]";
            $config['ref_link'] = 'erp/sale/invoiceview/' . $invoice_id;
            session()->setFlashdata("op_success", "Sale Invoice Notification successfully deleted");
        } else {
            $config['log_text'] = "[ Sale Invoice Notification failed to delete ]";
            $config['ref_link'] = 'erp/sale/invoiceview/' . $invoice_id;
            session()->setFlashdata("op_success", "Sale Invoice Notification failed to delete");
        }
        log_activity($config);
    }

    public function get_invoicenotify_export($type)
    {
        $invoice_id = $_GET["invoiceid"];
        $columns = array(
            "notify at" => "notify_at",
            "title" => "title"
        );
        $limit = $_GET['limit'];
        $offset = $_GET['offset'];
        $search = $_GET['search'] ?? "";
        $orderby = isset($_GET['orderby']) ? strtoupper($_GET['orderby']) : "";
        $ordercol = isset($_GET['ordercol']) ? $columns[$_GET['ordercol']] : "";

        $query = "SELECT title,notify_text,FROM_UNIXTIME(notify_at,'%Y-%m-%d %h:%i:%s') AS notify_at,erp_users.name AS notify_to,CASE WHEN status=1 THEN 'Yes' ELSE 'No' END AS notified FROM notification JOIN erp_users ON notification.user_id=erp_users.user_id WHERE related_to='sale_invoice' AND related_id=$invoice_id ";
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


    public function insert_m_invoice($data)
    {
        $inserted = false;
        $this->db->transBegin();
        $query = "INSERT INTO sale_invoice(code,cust_id,invoice_date,shippingaddr_id, currency_id, currency_place, terms_condition, payment_terms,transport_req,trans_charge,discount,invoice_expiry,status,can_edit,type,created_at,created_by) VALUES(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?) ";
        $this->db->query($query, array($data['code'], $data['cust_id'], $data['invoice_date'], $data['shippingaddr_id'], $data['currency_id'], $data['currency_place'], $data['terms_condition'], $data['payment_terms'], $data['transport_req'], $data['trans_charge'], $data['discount'], $data['invoice_expiry'], $data['status'], 1, 1, time(), $data['created_by']));

        $invoice_id = $this->db->insertId();
        // return var_dump($invoice_id);
        if (empty($invoice_id)) {
            session()->setFlashdata("op_error", "Sale Invoice failed to create");
            return $inserted;
        }

        if (!$this->update_saleinvoice_items($invoice_id, $data)) {
            $this->db->transRollback();
            session()->setFlashdata("op_error", "Sale Invoice failed to create");
            return $inserted;
        }

        $this->db->transComplete();
        $config['title'] = "Sale Invoice Insert";
        if ($this->db->transStatus() === FALSE) {
            $this->db->transRollback();
            $config['log_text'] = "[ Sale Invoice failed to create ]";
            $config['ref_link'] = "";
            session()->setFlashdata("op_error", "Sale Invoice failed to create");
        } else {
            $inserted = true;
            $this->db->transCommit();
            $config['log_text'] = "[ Sale Invoice successfully created ]";
            $config['ref_link'] = "erp/sale/invoiceview/" . $invoice_id;
            session()->setFlashdata("op_success", "Sale Invoice successfully created");
        }
        log_activity($config);
        return $inserted;
    }
    public function insert_e_invoice($data, $id)
    {
        $inserted = false;
        $this->db->transBegin();
        $query = "INSERT INTO sale_invoice(code,cust_id,invoice_date,shippingaddr_id, currency_id, currency_place, terms_condition, payment_terms,transport_req,trans_charge,invoice_expiry,status,can_edit,type,created_at,created_by) VALUES(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?) ";
        $this->db->query($query, array($data['code'], $data['cust_id'], $data['invoice_date'], $data['shippingaddr_id'], $data['currency_id'], $data['currency_place'], $data['terms_condition'], $data['payment_terms'], $data['transport_req'], $data['trans_charge'], $data['invoice_expiry'], $data['status'], 0, 3, time(), $data['created_by']));

        $invoice_id = $this->db->insertId();
        // return var_dump($invoice_id);
        if (empty($invoice_id)) {
            session()->setFlashdata("op_error", "invoice_id");
            return $inserted;
        }

        if (!$this->add_expinvoice_items($invoice_id, $data)) {
            $this->db->transRollback();
            session()->setFlashdata("op_error", "item");
            return $inserted;
        }

        $this->db->transComplete();
        $config['title'] = "Sale Invoice Insert";
        if ($this->db->transStatus() === FALSE) {
            $this->db->transRollback();
            $config['log_text'] = "[ Sale Invoice failed to create ]";
            $config['ref_link'] = "";
            session()->setFlashdata("op_error", "Sale Invoice failed to create");
        } else {
            $inserted = true;
            $this->db->transCommit();
            $query = "UPDATE erpexpenses SET invoice_id = $invoice_id WHERE id = $id";
            $this->db->query($query);
            $config['log_text'] = "[ Sale Invoice successfully created ]";
            $config['ref_link'] = "erp/sale/invoiceview/" . $invoice_id;
            session()->setFlashdata("op_success", "Sale Invoice successfully created");
        }
        log_activity($config);
        return $inserted;
    }


    private function update_saleinvoice_items($invoice_id, $data)
    {
        $logger = \Config\Services::logger();
        $updated = true;

        if (empty($data['product_ids'])) {
            $logger->error('Empty product id array');
            return false;
        }

        $result = $this->db->query("SELECT related_id FROM sale_order_items WHERE invoice_id = ?", [$invoice_id])->getResultArray();
        
        $old_product_ids = array_column($result, 'related_id');

        // var_dump($data['product_ids']);
        // exit;

        $del_product_ids = array_diff($old_product_ids, $data['product_ids']);




        if (!empty($del_product_ids)) {
            $delProductIdsStr = implode(",", $del_product_ids);
            $query = "DELETE FROM sale_order_items WHERE related_id IN ($delProductIdsStr) AND invoice_id = ?";
            $this->db->query($query, [$invoice_id]);
        }

        $query = "SELECT m_sale_order_items(?,?,?,?,?) AS error ";

        foreach ($data['product_ids'] as $key => $value) {
            $error = $this->db->query($query, [
                "invoice",
                $invoice_id,
                $value,
                $data['quantities'][$key],
                $data['price_ids'][$key]
            ])->getRow()->error;

            if ($error == 1) {
                $updated = false;
                break;
            }
        }

        return $updated;
    }
    private function add_expinvoice_items($invoice_id, $data)
    {
        $logger = \Config\Services::logger();

        $query = "INSERT INTO expense_items(related_to,related_id,invoice_id,quantity,amount,tax1,tax2)  VALUES(?,?,?,?,?,?,?)";

        if ($this->db->query($query, array("expense", $data["related_id"], $invoice_id, 1, $data["expense_amount"], $data["tax_1"], $data["tax_2"]))) {
            return true;
        } else {
            return false;
        }
    }



    public function insert_c_invoice($data)
    {
        $inserted = false;
        $this->db->transBegin();
        $query = "INSERT INTO sale_invoice(code,cust_id,invoice_date,terms_condition,payment_terms,discount,invoice_expiry,status,can_edit,type,created_at,created_by) VALUES(?,?,?,?,?,?,?,?,?,?,?,?) ";
        $this->db->query($query, array($data['code'], $data['cust_id'], $data['invoice_date'], $data['terms_condition'], $data['payment_terms'], $data['discount'], $data['invoice_expiry'], $data['status'], 1, 0, time(), $data['created_by']));

        $invoice_id = $this->db->insertId();
        if (empty($invoice_id)) {
            session()->setFlashdata("op_error", "Sale Invoice failed to create");
            return $inserted;
        }

        if (!$this->update_c_invoice_items($invoice_id, $data)) {
            $this->db->transRollback();
            session()->setFlashdata("op_error", "Sale Invoice failed to create");
            return $inserted;
        }

        $this->db->transComplete();
        $config['title'] = "Sale Invoice Insert";
        if ($this->db->transStatus() === FALSE) {
            $this->db->transRollback();
            $config['log_text'] = "[ Sale Invoice failed to create ]";
            $config['ref_link'] = "";
            session()->setFlashdata("op_success", "Sale Invoice failed to create");
        } else {
            $inserted = true;
            $this->db->transCommit();
            $config['log_text'] = "[ Sale Invoice successfully created ]";
            $config['ref_link'] = "erp/sale/invoiceview/" . $invoice_id;
            session()->setFlashdata("op_success", "Sale Invoice successfully created");
        }
        log_activity($config);
        return $inserted;
    }

    private function update_c_invoice_items($invoice_id, $data)
    {
        $updated = true;
        if (empty($data['unit_ids'])) {
            return false;
        }
        $result = $this->db->query("SELECT related_id FROM sale_order_items WHERE invoice_id=$invoice_id")->getResultArray();
        $old_unit_ids = array();
        foreach ($result as $row) {
            array_push($old_unit_ids, $row['related_id']);
        }
        $del_unit_ids = array();
        foreach ($old_unit_ids as $old) {
            $mark = true;
            foreach ($data['unit_ids'] as $new) {
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
            $query = "DELETE FROM sale_order_items WHERE related_id IN (" . implode(",", $del_unit_ids) . ") AND invoice_id=$invoice_id";
            $this->db->query($query);
            $query = "UPDATE property_unit SET status=0 WHERE prop_unit_id IN (" . implode(",", $del_unit_ids) . ") ";
            $this->db->query($query);
        }
        $query = "SELECT c_sale_order_items(?,?,?) AS error ";
        foreach ($data['unit_ids'] as $key => $value) {
            $error = $this->db->query($query, array("invoice", $invoice_id, $value))->getRow()->error;
            if ($error == 1) {
                $updated = false;
                break;
            }
        }
        return $updated;
    }


    public function get_m_invoice_export($type)
    {
        $columns = array(
            "code" => "code",
            "invoice date" => "order_date",
            "invoice expiry" => "order_expiry",
            "status" => "sale_invoice.status",
            "total amount" => "total_amount"
        );
        $limit = $_GET['limit'];
        $offset = $_GET['offset'];
        $search = $_GET['search'] ?? "";
        $orderby = isset($_GET['orderby']) ? strtoupper($_GET['orderby']) : "";
        $ordercol = isset($_GET['ordercol']) ? $columns[$_GET['ordercol']] : "";
        $query = "";
        if ($type == "pdf") {
            $query = "SELECT code,customers.name,invoice_date,invoice_expiry,
            CASE ";
            foreach ($this->invoice_status as $key => $value) {
                $query .= " WHEN sale_invoice.status=$key THEN '$value' ";
            }
            $query .= " END AS status,CASE WHEN transport_req=1 THEN 'Yes' ELSE 'No' END AS transport_req,trans_charge,discount,(SUM(sale_order_items.amount)+trans_charge-discount) AS total_amount FROM sale_invoice JOIN customers ON sale_invoice.cust_id=customers.cust_id JOIN sale_order_items ON sale_invoice.invoice_id=sale_order_items.invoice_id WHERE type=1 ";
        } else {
            $query = "SELECT code,customers.name,CONCAT(customer_shippingaddr.address,',',customer_shippingaddr.city,',',customer_shippingaddr.state,',',customer_shippingaddr.country,',',customer_shippingaddr.zipcode) AS shipping_addr,invoice_date,invoice_expiry,
            CASE ";
            foreach ($this->invoice_status as $key => $value) {
                $query .= " WHEN sale_invoice.status=$key THEN '$value' ";
            }
            $query .= " END AS status,CASE WHEN transport_req=1 THEN 'Yes' ELSE 'No' END AS transport_req,trans_charge,discount,(SUM(sale_order_items.amount)+trans_charge-discount) AS total_amount,payment_terms,terms_condition FROM sale_invoice JOIN customers ON sale_invoice.cust_id=customers.cust_id JOIN sale_order_items ON sale_invoice.invoice_id=sale_order_items.invoice_id LEFT JOIN customer_shippingaddr ON sale_invoice.shippingaddr_id=customer_shippingaddr.shippingaddr_id WHERE type=1 ";
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
        $query .= " GROUP BY sale_invoice.invoice_id ";
        if (!empty($ordercol) && !empty($orderby)) {
            $query .= " ORDER BY " . $ordercol . " " . $orderby;
        }
        $result = $this->db->query($query)->getResultArray();
        return $result;
    }


    public function get_c_invoice_export($type)
    {
        $columns = array(
            "code" => "code",
            "invoice date" => "invoice_date",
            "invoice expiry" => "invoice_expiry",
            "status" => "sale_invoice.status",
            "total amount" => "total_amount"
        );
        $limit = $_GET['limit'];
        $offset = $_GET['offset'];
        $search = $_GET['search'] ?? "";
        $orderby = isset($_GET['orderby']) ? strtoupper($_GET['orderby']) : "";
        $ordercol = isset($_GET['ordercol']) ? $columns[$_GET['ordercol']] : "";
        $query = "";
        if ($type == "pdf") {
            $query = "SELECT code,customers.name,invoice_date,invoice_expiry, CASE ";
            foreach ($this->invoice_status as $key => $value) {
                $query .= " WHEN sale_invoice.status=$key THEN '$value' ";
            }
            $query .= " END AS status,discount,(SUM(sale_order_items.amount)-discount) AS total_amount FROM sale_invoice JOIN customers ON sale_invoice.cust_id=customers.cust_id JOIN sale_order_items ON sale_invoice.invoice_id=sale_order_items.invoice_id WHERE sale_invoice.type=0 ";
        } else {
            $query = "SELECT code,customers.name,invoice_date,invoice_expiry, CASE ";
            foreach ($this->invoice_status as $key => $value) {
                $query .= " WHEN sale_invoice.status=$key THEN '$value' ";
            }
            $query .= " END AS status,discount,(SUM(sale_order_items.amount)-discount) AS total_amount,payment_terms,terms_condition FROM sale_invoice JOIN customers ON sale_invoice.cust_id=customers.cust_id JOIN sale_order_items ON sale_invoice.invoice_id=sale_order_items.invoice_id WHERE sale_invoice.type=0 ";
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
        $query .= " GROUP BY sale_invoice.invoice_id ";
        if (!empty($ordercol) && !empty($orderby)) {
            $query .= " ORDER BY " . $ordercol . " " . $orderby;
        }
        $query .= " LIMIT $offset,$limit ";
        $result = $this->db->query($query)->getResultArray();
        return $result;
    }




    public function stock_pick($order_id)
    {
        $product_ids = $_POST("product_id");
        $query = "SELECT m_stock_pick(?,?,?,?) AS error ";
        $this->db->transbegin();
        $process = true;

        foreach ($product_ids as $id) {
            $product = $_POST("product_id_" . $id);
            $stock_id = $_POST("stock_id_" . $id);
            $qty = $_POST("warehouse_qty_" . $id);
            for ($i = 0; $i < count($stock_id); $i++) {
                $error = $this->db->query($query, array($stock_id[$i], $qty[$i], $product[$i], $order_id))->getRow()->error;
                if ($error == 1) {
                    $this->db->transrollback();
                    $process = false;
                    break;
                }
            }
        }
        if ($process) {
            $query = "UPDATE sale_order SET stock_pick=1 WHERE order_id=$order_id ";
            $this->db->query($query);
            $config['title'] = "Stock Pick Insert";
            $this->db->transcomplete();
            if ($this->db->transstatus() === FALSE) {
                $this->db->transrollback();
                $config['log_text'] = "[ Stock Pick failed to create ]";
                $config['ref_link'] = 'erp/sale/orderview/' . $order_id;
                session()->setFlashdata("op_error", "Stock Pick failed to create");
            } else {
                $this->db->transcommit();
                $config['log_text'] = "[ Stock Pick successfully created ]";
                $config['ref_link'] = 'erp/sale/orderview/' . $order_id;
                session()->setFlashdata("op_success", "Stock Pick successfully created");
            }
            log_activity($config);
        }
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
                // var_dump($id);
                // exit();
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

    //edit

    public function update_m_invoice($invoice_id, $data)
    {
        $updated = false;
        $this->db->transBegin();
        $query = "UPDATE sale_invoice SET code=? , cust_id=? , invoice_date=? , shippingaddr_id=? , billingaddr_id=?, currency_id=?, currency_place=?, terms_condition=? , payment_terms=? , transport_req=? , trans_charge=? , discount=? , invoice_expiry=? WHERE invoice_id= " . $invoice_id;
        $this->db->query($query, array($data['code'], $data['cust_id'], $data['invoice_date'], $data['shippingaddr_id'], $data['billingaddr_id'], $data['currency_id'], $data['currency_place'], $data['terms_condition'], $data['payment_terms'], $data['transport_req'], $data['trans_charge'], $data['discount'], $data['invoice_expiry']));

        if (!$this->update_saleinvoice_items($invoice_id, $data)) {
            $this->db->transRollback();
            session()->setFlashdata("op_error", "Sale Invoice failed to update");
            return $updated;
        }

        $this->db->transComplete();
        $config['title'] = "Sale Invoice Update";
        if ($this->db->transStatus() === FALSE) {
            $this->db->transRollback();
            $config['log_text'] = "[ Sale Invoice failed to update ]";
            $config['ref_link'] = "";
            session()->setFlashdata("op_error", "Sale Invoice failed to update");
        } else {
            $updated = true;
            $this->db->transCommit();
            $config['log_text'] = "[ Sale Invoice successfully updated ]";
            $config['ref_link'] = "erp/sale/invoiceview/" . $invoice_id;
            session()->setFlashdata("op_success", "Sale Invoice successfully updated");
        }
        log_activity($config);
        return $updated;
    }


    public function get_m_invoice_by_id($invoice_id)
    {
        $query = "SELECT invoice_id,code,customers.cust_id,customers.name,invoice_date,invoice_expiry,shippingaddr_id, billingaddr_id, transport_req,trans_charge,payment_terms,terms_condition,discount FROM sale_invoice JOIN customers ON sale_invoice.cust_id=customers.cust_id WHERE invoice_id=$invoice_id";
        $result = $this->db->query($query)->getRow();
        return $result;
    }

    public function get_m_shipping_addr($cust_id)
    {
        $query = "SELECT shippingaddr_id,CONCAT(address,',',city) AS addr FROM customer_shippingaddr WHERE cust_id=$cust_id";
        $result = $this->db->query($query)->getResultArray();
        return $result;
    }

    public function get_m_invoice_items($invoice_id)
    {
        $query = "SELECT related_id,CONCAT(finished_goods.name,' ',finished_goods.code) AS product,quantity,price_list.price_id,price_list.amount AS unit_price,
        price_list.tax1 as tax1, price_list.tax2 as tax2,(quantity * price_list.amount) AS amount,sale_order_items.tax1 as tax1_amount, sale_order_items.tax2 as tax2_amount, taxes1.tax_name AS tax1_rate, taxes2.tax_name AS tax2_rate FROM sale_order_items JOIN finished_goods ON related_id=finished_goods.finished_good_id JOIN price_list ON sale_order_items.price_id=price_list.price_id 
        LEFT JOIN 
            taxes AS taxes1 ON price_list.tax1 = taxes1.tax_id
        LEFT JOIN 
            taxes AS taxes2 ON price_list.tax2 = taxes2.tax_id WHERE invoice_id=$invoice_id GROUP BY price_list.price_id";
        $result = $this->db->query($query)->getResultArray();
        return $result;
    }

    public function getTaxNamePercentage($tax)
    {
        $taxIds = implode(',', $tax);
        $query = "SELECT tax_name, percent FROM taxes WHERE tax_id IN ($taxIds)";
        return $this->db->query($query)->getResultArray();
    }

    public function update_c_invoice($invoice_id, $data)
    {
        $updated = false;
        $this->db->transBegin();
        $query = "UPDATE sale_invoice SET code=? , cust_id=? , invoice_date=? , terms_condition=? , payment_terms=? , discount=? , invoice_expiry=? WHERE invoice_id= " . $invoice_id;
        $this->db->query($query, array($data['code'], $data['cust_id'], $data['invoice_date'], $data['terms_condition'], $data['payment_terms'], $data['discount'], $data['invoice_expiry']));

        if (!$this->update_c_invoice_items($invoice_id, $data)) {
            $this->db->transRollback();
            session()->setFlashdata("op_error", "Sale Invoice failed to update");
            return $data['inserted'];
        }

        $this->db->transComplete();
        $config['title'] = "Sale Invoice Update";
        if ($this->db->transStatus() === FALSE) {
            $this->db->transRollback();
            $config['log_text'] = "[ Sale Invoice failed to update ]";
            $config['ref_link'] = "erp/sale/invoiceview/" . $invoice_id;
            session()->setFlashdata("op_success", "Sale Invoice failed to update");
        } else {
            $inserted = true;
            $this->db->transCommit();
            $config['log_text'] = "[ Sale Invoice successfully updated ]";
            $config['ref_link'] = "erp/sale/invoiceview/" . $invoice_id;
            session()->setFlashdata("op_success", "Sale Invoice successfully updated");
        }
        log_activity($config);
        return $inserted;
    }

    public function get_c_invoice_by_id($invoice_id)
    {
        $query = "SELECT code,invoice_date,invoice_expiry,payment_terms,terms_condition,discount,sale_invoice.cust_id,customers.name FROM sale_invoice JOIN customers ON sale_invoice.cust_id=customers.cust_id WHERE invoice_id=$invoice_id";
        $result = $this->db->query($query)->getRow();
        return $result;
    }

    public function get_c_invoice_items($invoice_id)
    {
        $query = "SELECT properties.name AS property,property_unit.unit_name,properties.property_id,prop_unit_id,property_unit.price FROM sale_order_items JOIN property_unit ON sale_order_items.related_id=property_unit.prop_unit_id JOIN properties ON property_unit.property_id=properties.property_id WHERE invoice_id=$invoice_id ";
        $result = $this->db->query($query)->getResultArray();
        return $result;
    }

    public function invoiceCount()
    {
        $query = "SELECT status, COUNT(status) AS invoice_count, (SELECT COUNT(*) FROM sale_invoice WHERE status IN (0, 1, 2, 3, 4)) AS total FROM sale_invoice WHERE status IN (0, 1, 2, 3, 4) GROUP BY status";
        $result = $this->db->query($query)->getResultArray();
        return $result;
    }
    public function quotationCount()
    {
        $query = "SELECT status, COUNT(status) AS quotation_count, (SELECT COUNT(*) FROM quotations WHERE status IN (0, 1, 2, 3, 4)) AS total FROM quotations WHERE status IN (0, 1, 2, 3, 4) GROUP BY status";
        $result = $this->db->query($query)->getResultArray();
        return $result;
    }

    // public function EQI()
    // {
    //     $query = "SELECT status, COUNT(status) AS invoice_count, (SELECT COUNT(*) FROM sale_invoice WHERE status IN (0, 1, 2, 3, 4)) AS total FROM sale_invoice WHERE status IN (0, 1, 2, 3, 4) GROUP BY status";
    //     $result = $this->db->query($query)->getResultArray();
    //     return $result;
    // }

    public function getInvoice()
    {
        return $this->builder()->select("invoice_id,code as invoicecode")->get()->getResultArray();
    }

    public function getInvoiceName($relatedOptionValue)
    {
        return $this->builder()->select("code as name")->where('invoice_id', $relatedOptionValue)->get()->getRow();
    }

    public function delete_invoice($invoice_id)
    {
        $deleted = false;
        $invoice = $this->get_m_invoice_by_id($invoice_id);
        $this->builder()->where('invoice_id', $invoice_id)->delete();
        $this->db->table('sale_payments')->where('invoice_id', $invoice_id)->delete();
        if ($this->db->affectedRows() > 0) {
            $deleted = true;
            $config = [
                'title' => 'Invoice Deletion',
                'log_text' => "[ Invoice successfully deleted ]",
                'ref_link' => 'erp.sale.invoice.delete' . $invoice_id,
                'additional_info' => json_encode($invoice),
            ];
            log_activity($config);
        } else {
            $config = [
                'title' => 'Invoice Deletion',
                'log_text' => "[ Invoice failed to delete ]",
                'ref_link' => '' . $invoice_id,
            ];
            log_activity($config);
        }

        return $deleted;
    }

    public function getDataforItemsReport()
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

        $query = "SELECT quotations.quote_id,code,customers.name,quote_date,quotations.status,trans_charge,discount,(SUM(sale_order_items.amount)+trans_charge-discount) AS total_amount FROM quotations JOIN customers ON quotations.cust_id=customers.cust_id JOIN sale_order_items ON quotations.quote_id=sale_order_items.quote_id ";
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
            $action .= '<li><a href="' . base_url() . 'erp/sale/quotationdelete/' . $r['quote_id'] . '" title="Delete" class="bg-danger del-confirm"><i class="fa fa-trash"></i> </a></li>
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


    public function customerTitle()
    {
        $config = array(
            "columnNames" => ["customer", "total invoice", "amount", "amount with tax"],
            "sortable" => [1, 1, 1, 0]
        );
        return $config;
    }

    public function getCustomerData()
    {
        $query = "SELECT COUNT(DISTINCT sale_invoice.invoice_id) AS total_invoice, sale_invoice.created_at, customers.company AS customer_company, (SUM(sale_order_items.amount)+trans_charge-discount) AS total_amount,
                    (SUM(amount + (amount * (sale_order_items.tax1 + sale_order_items.tax2)) / 100)) AS amount_with_tax 
                    FROM sale_invoice JOIN customers ON sale_invoice.cust_id=customers.cust_id JOIN sale_order_items ON sale_invoice.invoice_id=sale_order_items.invoice_id";
        $query .= " GROUP BY customers.cust_id, customers.name ";
        $result = $this->db->query($query)->getResultArray();
        $m_result = array();
        foreach ($result as $r) {
            array_push(
                $m_result,
                array(
                    $r['customer_company'],
                    $r['total_invoice'],
                    number_format($r['total_amount'], 2, '.', ','),
                    number_format($r['amount_with_tax'], 2, '.', ','),
                    $r['created_at']
                )
            );
        }
        $response = array();
        $response['data'] = $m_result;
        return $response;
    }

    public function getCustomerDataByDate($period)
    {
        $dateList = implode("','", $period);
        $query = "SELECT COUNT(DISTINCT sale_invoice.invoice_id) AS total_invoice, sale_invoice.created_at, customers.company AS customer_company, (SUM(sale_order_items.amount)+ COALESCE(trans_charge,0)-discount) AS total_amount,
                    (SUM(amount + (amount * (sale_order_items.tax1 + sale_order_items.tax2)) / 100)) AS amount_with_tax 
                    FROM sale_invoice JOIN customers ON sale_invoice.cust_id=customers.cust_id JOIN sale_order_items ON sale_invoice.invoice_id=sale_order_items.invoice_id WHERE sale_invoice.created_at IN ('$dateList')";
        $query .= " GROUP BY customers.cust_id, customers.name ";
        $result = $this->db->query($query)->getResultArray();
        $m_result = array();
        foreach ($result as $r) {
            array_push(
                $m_result,
                array(
                    $r['customer_company'],
                    $r['total_invoice'],
                    $r['total_amount'],
                    $r['amount_with_tax'],
                    $r['created_at']
                )
            );
        }
        $response = array();
        $response['data'] = $m_result;
        return $response;
    }

    public function getTimePeriod()
    {
        return $this->builder()->select('created_at')->get()->getResultArray();
    }

    public function invoicePaymentTitle()
    {
        $config = array(
            "columnNames" => ["payment #", "date", "invoice #", "customer name", "payment mode", "transaction id", "amount"],
            "sortable" => [1, 1, 1, 1, 0, 1]
        );
        return $config;
    }

    public function quotationsTitle()
    {
        $config = array(
            "columnNames" => ["quotations code", "customer name", "date", "amount", "amount with tax", "total tax", "vat 5.00%", "discount", "status"],
            "sortable" => [1, 1, 1, 1, 1, 1, 1, 1, 1]
        );
        return $config;
    }

    public function getquotationsData()
    {
        $query = "SELECT quotations.code as quote_code, customers.name, quotations.quote_date,quotations.status, quotations.quote_id, (SUM((sale_order_items.amount)+ COALESCE(trans_charge,0)-discount)) AS total_amount, (SUM(sale_order_items.amount + (sale_order_items.amount * (sale_order_items.tax1 + sale_order_items.tax2)) / 100)) AS amount_with_tax, SUM((sale_order_items.tax1 + sale_order_items.tax2)/100) as total_tax, SUM((sale_order_items.tax1 + sale_order_items.tax2)/100) as VAT, quotations.discount FROM `quotations` JOIN customers ON customers.cust_id=quotations.cust_id JOIN sale_order_items ON quotations.quote_id=sale_order_items.quote_id";
        $query .= " GROUP BY quotations.quote_id";
        $result = $this->db->query($query)->getResultArray();
        $m_result = array();
        foreach ($result as $r) {
            if ($r['status'] == 0) {
                $status = '<button class="overdue-btn">Created</button>';
            } else if ($r['status'] == 1) {
                $status = '<button class="partial-btn">Sent</button>';
            } else if ($r['status'] == 2) {
                $status = '<button class="paid-btn">Accepted</button>';
            } else if ($r['status'] == 3) {
                $status = '<button class="unpaid-btn">Declined</button>';
            } else if ($r['status'] == 4) {
                $status = '<button class="unpaid-btn">Converted</button>';
            }
            array_push(
                $m_result,
                array(
                    $r['quote_code'],
                    $r['name'],
                    $r['quote_date'],
                    number_format($r['total_amount'], 2, '.', ','),
                    number_format($r['amount_with_tax'], 2, '.', ','),
                    number_format($r['total_tax'], 2, '.', ','),
                    number_format($r['VAT'], 2, '.', ','),
                    $r['discount'],
                    $status
                )
            );
        }
        $response = array();
        $response['data'] = $m_result;
        return $response;
    }

    public function getquotationsDataByDate($period)
    {
        $dateList = implode("','", $period);
        $query = "SELECT quotations.code as quote_code, customers.name, quotations.quote_date,quotations.status, quotations.quote_id, (SUM((sale_order_items.amount)+ COALESCE(trans_charge,0)-discount)) AS total_amount, (SUM(sale_order_items.amount + (sale_order_items.amount * (sale_order_items.tax1 + sale_order_items.tax2)) / 100)) AS amount_with_tax, SUM((sale_order_items.tax1 + sale_order_items.tax2)/100) as total_tax, SUM((sale_order_items.tax1 + sale_order_items.tax2)/100) as VAT, quotations.discount FROM `quotations` JOIN customers ON customers.cust_id=quotations.cust_id JOIN sale_order_items ON quotations.quote_id=sale_order_items.quote_id WHERE quotations.created_at IN ('$dateList') ";
        $query .= " GROUP BY quotations.quote_id";
        $result = $this->db->query($query)->getResultArray();
        $m_result = array();
        foreach ($result as $r) {
            if ($r['status'] == 0) {
                $status = '<button class="overdue-btn">Created</button>';
            } else if ($r['status'] == 1) {
                $status = '<button class="partial-btn">Sent</button>';
            } else if ($r['status'] == 2) {
                $status = '<button class="paid-btn">Accepted</button>';
            } else if ($r['status'] == 3) {
                $status = '<button class="unpaid-btn">Declined</button>';
            } else if ($r['status'] == 4) {
                $status = '<button class="unpaid-btn">Converted</button>';
            }
            array_push(
                $m_result,
                array(
                    $r['quote_code'],
                    $r['name'],
                    $r['quote_date'],
                    number_format($r['total_amount'], 2, '.', ','),
                    number_format($r['amount_with_tax'], 2, '.', ','),
                    number_format($r['total_tax'], 2, '.', ','),
                    number_format($r['VAT'], 2, '.', ','),
                    $r['discount'],
                    $status
                )
            );
        }
        $response = array();
        $response['data'] = $m_result;
        return $response;
    }


    public function EstimatesTitle()
    {
        $config = array(
            "columnNames" => ["estimate #", "customer name", "date", "amount", "amount with tax", "total tax", "vat 5.00%"],
            "sortable" => [1, 1, 1, 1, 1, 1, 1]
        );
        return $config;
    }

    public function getEstimatesData()
    {
        $query = "SELECT estimates.code as estimate_code, customers.name, estimates.estimate_date, estimates.estimate_id, SUM(estimate_items.amount) AS total_amount, (SUM(estimate_items.amount + (estimate_items.amount * (estimate_items.tax1 + estimate_items.tax2)) / 100)) AS amount_with_tax, SUM((estimate_items.tax1 + estimate_items.tax2)/100) as total_tax, SUM((estimate_items.tax1 + estimate_items.tax2)/100) as VAT FROM estimates JOIN customers ON customers.cust_id=estimates.cust_id JOIN estimate_items ON estimates.estimate_id=estimate_items.estimate_id";
        $query .= " GROUP BY estimates.estimate_id";
        $result = $this->db->query($query)->getResultArray();
        $m_result = array();
        foreach ($result as $r) {
            array_push(
                $m_result,
                array(
                    $r['estimate_code'],
                    $r['name'],
                    $r['estimate_date'],
                    number_format($r['total_amount'], 2, '.', ','),
                    number_format($r['amount_with_tax'], 2, '.', ','),
                    number_format($r['total_tax'], 2, '.', ','),
                    number_format($r['VAT'], 2, '.', ','),
                )
            );
        }
        $response = array();
        $response['data'] = $m_result;
        return $response;
    }

    public function getEstimatesDataByDate($period)
    {
        $dateList = implode("','", $period);
        $query = "SELECT estimates.code as estimate_code, customers.name, estimates.estimate_date, estimates.estimate_id, SUM(estimate_items.amount) AS total_amount, (SUM(estimate_items.amount + (estimate_items.amount * (estimate_items.tax1 + estimate_items.tax2)) / 100)) AS amount_with_tax, SUM((estimate_items.tax1 + estimate_items.tax2)/100) as total_tax, SUM((estimate_items.tax1 + estimate_items.tax2)/100) as VAT FROM estimates JOIN customers ON customers.cust_id=estimates.cust_id JOIN estimate_items ON estimates.estimate_id=estimate_items.estimate_id WHERE estimates.created_at IN ('$dateList')";
        $query .= "GROUP BY estimates.estimate_id";
        $result = $this->db->query($query)->getResultArray();
        $m_result = array();
        foreach ($result as $r) {
            array_push(
                $m_result,
                array(
                    $r['estimate_code'],
                    $r['name'],
                    $r['estimate_date'],
                    number_format($r['total_amount'], 2, '.', ','),
                    number_format($r['amount_with_tax'], 2, '.', ','),
                    number_format($r['total_tax'], 2, '.', ','),
                    number_format($r['VAT'], 2, '.', ','),
                )
            );
        }
        $response = array();
        $response['data'] = $m_result;
        return $response;
    }

    public function getPaymentData()
    {
        $query = "SELECT sale_invoice.code,sale_invoice.invoice_date,sale_invoice.invoice_expiry,customers.name,sale_invoice.status,sale_payments.amount,sale_payments.transaction_id,sale_payments.paid_on,sale_payments.sale_pay_id, payment_modes.name as payment_mode FROM `sale_invoice` JOIN customers ON sale_invoice.cust_id = customers.cust_id JOIN sale_payments ON sale_invoice.invoice_id=sale_payments.invoice_id JOIN payment_modes ON sale_payments.payment_id = payment_modes.payment_id";
        $query .= " GROUP BY sale_payments.sale_pay_id;";
        $result = $this->db->query($query)->getResultArray();
        $m_result = array();
        foreach ($result as $r) {
            array_push(
                $m_result,
                array(
                    $r['sale_pay_id'],
                    $r['paid_on'],
                    $r['code'],
                    $r['name'],
                    $r['payment_mode'],
                    $r['transaction_id'],
                    number_format($r['amount'], 2, '.', ',')
                )
            );
        }
        $response = array();
        $response['data'] = $m_result;
        return $response;
    }

    public function getPaymentDataByDate($period)
    {
        $dateList = implode("','", $period);
        $query = "SELECT sale_invoice.code,sale_invoice.invoice_date,sale_invoice.invoice_expiry,customers.name,sale_invoice.status, sale_payments.amount,sale_payments.transaction_id, sale_payments.paid_on, sale_payments.sale_pay_id FROM `sale_invoice` JOIN customers ON sale_invoice.cust_id = customers.cust_id JOIN sale_payments ON sale_invoice.invoice_id=sale_payments.invoice_id WHERE sale_payments.paid_on IN ('$dateList') GROUP BY sale_payments.sale_pay_id";
        $result = $this->db->query($query)->getResultArray();
        $m_result = array();
        foreach ($result as $r) {
            array_push(
                $m_result,
                array(
                    $r['sale_pay_id'],
                    $r['paid_on'],
                    $r['code'],
                    $r['name'],
                    $r['transaction_id'],
                    number_format($r['amount'], 2, '.', ',')
                )
            );
        }
        $response = array();
        $response['data'] = $m_result;
        return $response;
    }

    public function itemTitle()
    {
        $config = array(
            "columnNames" => [
                'related_to #',
                'unit_price',
                'amount',
                'total_qty',
                'total',
            ],
            "sortable" => [1, 1, 1, 1, 1]
        );
        return $config;
    }

    public function getItemData()
    {
        $query = "SELECT sale_order_items.related_to,sale_order_items.unit_price,sale_order_items.amount, SUM(sale_order_items.quantity) AS total_qty, SUM(sale_order_items.amount) AS total
        FROM sale_order_items
        RIGHT JOIN sale_order ON sale_order.order_id = sale_order_items.order_id
        WHERE sale_order.status IN (1, 2)
        GROUP BY sale_order_items.related_to, sale_order_items.related_to
        ORDER BY sale_order_items.related_to";
        $result = $this->db->query($query)->getResultArray();
        $i_result = array();
        foreach ($result as $i) {
            array_push(
                $i_result,
                array(
                    str_replace('-', ' ', $i['related_to']),
                    number_format($i['amount'], 2, '.', ','),
                    number_format($i['total_qty'], 2, '.', ','),
                    number_format($i['total'], 2, '.', ','),
                    number_format($i['unit_price'], 2, '.', ',')
                )
            );
        }
        $response = array();
        $response['data'] = $i_result;
        return $response;
    }

    public function getItemDataByMonth()
    {
        $query = "SELECT
            sale_order_items.related_to,
            sale_order_items.unit_price,
            sale_order_items.amount,
            SUM(sale_order_items.quantity) AS total_qty,
            SUM(sale_order_items.amount) AS total
        FROM
            sale_order_items
        RIGHT JOIN
            sale_order ON sale_order.order_id = sale_order_items.order_id
        WHERE
            sale_order.status IN (1, 2)
            AND EXTRACT(MONTH FROM FROM_UNIXTIME(sale_order.created_at)) = " . date('m') . "
        GROUP BY
            sale_order_items.related_to
        ORDER BY
            MONTH(sale_order.created_at);
        ";
        $result = $this->db->query($query)->getResultArray();
        $i_result = array();
        foreach ($result as $i) {
            array_push(
                $i_result,
                array(
                    str_replace('-', ' ', $i['related_to']),
                    number_format($i['amount'], 2, '.', ','),
                    number_format($i['total_qty'], 2, '.', ','),
                    number_format($i['total'], 2, '.', ','),
                    number_format($i['unit_price'], 2, '.', ',')
                )
            );
        }
        $response = array();
        $response['data'] = $i_result;
        return $response;
    }

    public function getItemDataBySpecific_period($fromDateTimestamp, $toDateTimestamp)
    {
        $query = "SELECT
            sale_order_items.related_to,
            sale_order_items.unit_price,
            sale_order_items.amount,
            SUM(sale_order_items.quantity) AS total_qty,
            SUM(sale_order_items.amount) AS total
        FROM
            sale_order_items
        RIGHT JOIN
            sale_order ON sale_order.order_id = sale_order_items.order_id
        WHERE
            sale_order.status IN (1, 2)
            AND sale_order.created_at BETWEEN " . $fromDateTimestamp . " AND " . $toDateTimestamp . "
        GROUP BY
            sale_order_items.related_to
        ORDER BY
            EXTRACT(MONTH FROM FROM_UNIXTIME(sale_order.created_at)),
            sale_order.created_at; -- You may adjust the ORDER BY clause as needed";
        $result = $this->db->query($query)->getResultArray();
        $i_result = array();
        foreach ($result as $i) {
            array_push(
                $i_result,
                array(
                    str_replace('-', ' ', $i['related_to']),
                    number_format($i['amount'], 2, '.', ','),
                    number_format($i['total_qty'], 2, '.', ','),
                    number_format($i['total'], 2, '.', ','),
                    number_format($i['unit_price'], 2, '.', ',')
                )
            );
        }
        $response = array();
        $response['data'] = $i_result;
        return $response;
    }

    public function invoiceTitle()
    {
        $config = array(
            "columnNames" => [
                "invoice #",
                "customer",
                "date",
                "due date",
                "amount",
                "amount with tax",
                "total tax",
                "vat 5.00%",
                "discount",
                "amount open",
                "status"
            ],
            "sortable" => [1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1]
        );
        return $config;
    }

    public function getInvoiceData()
    {
        $query = "SELECT code, customers.company as company, invoice_date, invoice_expiry, SUM(amount+ COALESCE(trans_charge,0)-discount) as amount, 
        SUM(amount + (amount * (tax1 + tax2)/100)+ COALESCE(trans_charge,0)) as amount_with_tax, SUM((tax1 + tax2)/100) as total_tax, SUM((tax1 + tax2)/100) as VAT, discount,
        SUM(amount + (amount * (tax1 + tax2)/100)+ COALESCE(trans_charge,0)-discount-paid_till) as amount_due, status
        FROM sale_invoice JOIN customers ON sale_invoice.cust_id = customers.cust_id 
        JOIN sale_order_items ON sale_invoice.invoice_id = sale_order_items.invoice_id GROUP BY sale_invoice.invoice_id";
        $result = $this->db->query($query)->getResultArray();
        $i_result = array();
        foreach ($result as $i) {

            if ($i['status'] == 0) {
                $status = '<button class="unpaid-btn">UNPAID</button>';
            } elseif ($i['status'] == 1) {
                $status = '<button class="partial-btn">PARTIALLY PAID</button>';
            } elseif ($i['status'] == 2) {
                $status = '<button class="paid-btn">PAID</button>';
            } elseif ($i['status'] == 3) {
                $status = '<button class="overdue-btn">Overdue</button>';
            }

            array_push(
                $i_result,
                array(
                    $i['code'],
                    $i['company'],
                    $i['invoice_date'],
                    $i['invoice_expiry'],
                    number_format($i['amount'], 2, '.', ','),
                    number_format($i['amount_with_tax'], 2, '.', ','),
                    number_format($i['total_tax'], 2, '.', ','),
                    number_format($i['VAT'], 2, '.', ','),
                    $i['discount'],
                    number_format($i['amount_due'], 2, '.', ','),
                    $status
                )
            );
        }
        $response = array();
        $response['data'] = $i_result;
        return $response;
    }

    public function getInvoiceDataByDate($period)
    {
        $dateList = implode("','", $period);
        $query = "SELECT code, customers.company as company, invoice_date, invoice_expiry, SUM(amount+ COALESCE(trans_charge,0)-discount) as amount, 
        SUM(amount + (amount * (tax1 + tax2)/100)+ COALESCE(trans_charge,0)) as amount_with_tax, SUM((tax1 + tax2)/100) as total_tax, SUM((tax1 + tax2)/100) as VAT, discount,
        SUM(amount + (amount * (tax1 + tax2)/100)+ COALESCE(trans_charge,0)-discount-paid_till) as amount_due, status
        FROM sale_invoice JOIN customers ON sale_invoice.cust_id = customers.cust_id 
        JOIN sale_order_items ON sale_invoice.invoice_id = sale_order_items.invoice_id WHERE sale_invoice.created_at IN ('$dateList') GROUP BY sale_invoice.invoice_id";
        $result = $this->db->query($query)->getResultArray();
        $i_result = array();
        foreach ($result as $i) {
            if ($i['status'] == 0) {
                $status = '<button class="unpaid-btn">UNPAID</button>';
            } elseif ($i['status'] == 1) {
                $status = '<button class="partial-btn">PARTIALLY PAID</button>';
            } elseif ($i['status'] == 2) {
                $status = '<button class="paid-btn">PAID</button>';
            } elseif ($i['status'] == 3) {
                $status = '<button class="overdue-btn">Overdue</button>';
            }

            array_push(
                $i_result,
                array(
                    $i['code'],
                    $i['company'],
                    $i['invoice_date'],
                    $i['invoice_expiry'],
                    number_format($i['amount'], 2, '.', ','),
                    number_format($i['amount_with_tax'], 2, '.', ','),
                    number_format($i['total_tax'], 2, '.', ','),
                    number_format($i['VAT'], 2, '.', ','),
                    $i['discount'],
                    number_format($i['amount_due'], 2, '.', ','),
                    $status
                )
            );
        }
        $response = array();
        $response['data'] = $i_result;
        return $response;
    }

    public function creditNoteTitle()
    {
        $config = array(
            "columnNames" => ["credit note #", "credit note date", "customer name", "invoice reference #", "amount", "remaining amount"],
            "sortable" => [1, 1, 1, 1, 1, 1]
        );
        return $config;
    }

    public function getCreditNoteData()
    {
        $query = "SELECT
        credit_notes.credit_id,
        credit_notes.code as credit_code,
        credit_notes.issued_date,
        customers.company as company,
        sale_invoice.code as invoice_code,
        credit_notes.other_charge as total_amount,credit_notes.balance_amount as remaining_amount FROM sale_invoice 
        JOIN customers ON sale_invoice.cust_id = customers.cust_id 
        JOIN credit_notes ON sale_invoice.invoice_id = credit_notes.invoice_id 
        GROUP BY credit_notes.credit_id";
        $result = $this->db->query($query)->getResultArray();
        $i_result = array();
        foreach ($result as $i) {
            array_push(
                $i_result,
                array(
                    $i['credit_code'],
                    $i['issued_date'],
                    $i['company'],
                    $i['invoice_code'],
                    number_format($i['total_amount'], 2, '.', ','),
                    $i['remaining_amount']
                )
            );
        }
        $response = array();
        $response['data'] = $i_result;
        return $response;
    }

    public function getCreditNoteDataByDate($period)
    {
        $dateList = implode("','", $period);
        $query = "SELECT
        credit_notes.credit_id,
        credit_notes.code as credit_code,
        credit_notes.issued_date,
        customers.company as company,
        sale_invoice.code as invoice_code,
        credit_notes.other_charge as total_amount,credit_notes.balance_amount as remaining_amount FROM sale_invoice 
        JOIN customers ON sale_invoice.cust_id = customers.cust_id 
        JOIN credit_notes ON sale_invoice.invoice_id = credit_notes.invoice_id 
        WHERE credit_notes.created_at IN ('$dateList')
        GROUP BY credit_notes.credit_id";
        $result = $this->db->query($query)->getResultArray();
        $i_result = array();
        foreach ($result as $i) {
            array_push(
                $i_result,
                array(
                    $i['credit_code'],
                    $i['issued_date'],
                    $i['company'],
                    $i['invoice_code'],
                    number_format($i['total_amount'], 2, '.', ','),
                    $i['remaining_amount']
                )
            );
        }
        $response = array();
        $response['data'] = $i_result;
        return $response;
    }



    // Credit notes Starts Here
    public function insertcredit($invoice_id, $data)
    {
        $inserted = false;
        $check = $this->db->query("SELECT credit_id FROM credit_notes WHERE invoice_id=" . $invoice_id)->getRow();
        if (!empty($check)) {
            session()->setFlashdata("op_error", "Duplicate Credit Note");
            return $inserted;
        }

        $this->db->transBegin();
        $query = "INSERT INTO credit_notes(code,invoice_id,issued_date,other_charge,payment_terms,terms_condition,remarks,created_at,created_by) VALUES(?,?,?,?,?,?,?,?,?) ";
        $this->db->query($query, array($data['code'], $invoice_id, $data['issued_date'], $data['other_charge'], $data['payment_terms'], $data['terms_condition'], $data['remarks'], time(), $data['created_by']));
        $credit_id = $this->db->insertID();

        if (empty($credit_id)) {
            session()->setFlashdata("op_error", "Credit Note failed to create");
            return $inserted;
        }

        $query = "INSERT credit_items(credit_id,related_to,related_id,qty,unit_price,amount) VALUES(?,?,?,?,?,?)";
        for ($i = 1; $i <= count($data['product_ids']); $i++) {
            $amount = round($data['qtys'][$i] * $data['unit_prices'][$i], 2);
            $this->db->query($query, array($credit_id, 'finished_good', $data['product_ids'][$i], $data['qtys'][$i], $data['unit_prices'][$i], $amount));
            if (empty($this->db->insertID())) {
                $this->db->transRollback();
                session()->setFlashdata("op_error", "Credit Note failed to create");
                return $inserted;
            }
        }

        $config['title'] = "Credit Note Insert";
        $this->db->transComplete();
        if ($this->db->transStatus() === FALSE) {
            $this->db->transRollback();
            $config['log_text'] = "[ Credit Note failed to create ]";
            $config['ref_link'] = "";
            session()->setFlashdata("op_error", "Credit Note failed to create");
        } else {
            $inserted = true;
            $this->db->transCommit();
            $config['log_text'] = "[ Credit Note successfully created ]";
            $config['ref_link'] = url_to('erp.sale.creditnotesview', $invoice_id);
            session()->setFlashdata("op_success", "Credit Note successfully created");
        }
        log_activity($config);
        return $inserted;
    }


    public function get_m_invoice_items_for_credit($invoice_id)
    {
        $query = "SELECT related_id,CONCAT(finished_goods.name,' ',finished_goods.code) AS product,quantity,sale_order_items.unit_price,sale_order_items.amount FROM sale_order_items JOIN finished_goods ON related_id=finished_goods.finished_good_id WHERE invoice_id=$invoice_id ";
        $result = $this->db->query($query)->getResultArray();
        return $result;
    }

    //New Credit Note Implementation
    public function insertCreditNote($data)
    {
        $inserted = false;

        $this->db->transBegin();
        $query = "INSERT INTO credit_notes(code,cust_id,issued_date,other_charge,payment_terms,terms_condition,remarks,created_at,created_by) VALUES(?,?,?,?,?,?,?,?,?) ";
        $this->db->query($query, array($data['code'], $data['cust_id'], $data['issued_date'], $data['other_charge'], $data['payment_terms'], $data['terms_condition'], $data['remarks'], time(), $data['created_by']));
        $credit_id = $this->db->insertID();

        if (empty($credit_id)) {
            session()->setFlashdata("op_error", "Credit Note failed to create");
            return $inserted;
        }

        $config['title'] = "Credit Note Insert";
        $this->db->transComplete();
        if ($this->db->transStatus() === FALSE) {
            $this->db->transRollback();
            $config['log_text'] = "[ Credit Note failed to create ]";
            $config['ref_link'] = url_to('erp.add.creditnoteadd');
            session()->setFlashdata("op_error", "Credit Note failed to create");
        } else {
            $inserted = true;
            $this->db->transCommit();
            $config['log_text'] = "[ Credit Note successfully created ]";
            $config['ref_link'] = url_to('erp.sale.creditnotes');
            session()->setFlashdata("op_success", "Credit Note successfully created");
        }
        log_activity($config);
        return $inserted;
    }

    public function getCustomerIdByInvoiceId($invoice_id)
    {
        $query = "SELECT cust_id FROM sale_invoice WHERE invoice_id=$invoice_id ";
        $result = $this->db->query($query)->getRow()->cust_id;
        return $result;
    }

    public function getTotalIncome()
    {
        $currentYear = date('Y');
        return $this->builder()->selectSum('sale_order_items.amount', 'total_income')
            ->join('sale_order_items', 'sale_invoice.invoice_id = sale_order_items.invoice_id')
            ->where('status', 2)->where("YEAR(FROM_UNIXTIME(created_at))", $currentYear)->get()->getRow()->total_income;
    }

    public function getPaymentDataChart()
    {
        $currentYear = date('Y');
        $result = $this->builder()->select('SUM(sale_payments.amount) as total_amount, payment_modes.name')
            ->join('sale_payments', 'sale_invoice.invoice_id = sale_payments.invoice_id')
            ->join('payment_modes', 'sale_payments.payment_id = payment_modes.payment_id')
            ->where("YEAR(sale_payments.paid_on)", $currentYear)
            ->groupBy('payment_modes.payment_id')
            ->get()
            ->getResultArray();
        return $result;
    }

    public function getTotalValueByCustomerGroupsChart()
    {
        $result = $this->builder()->select('distinct(group_name),sale_order_items.amount')
            ->join('customers', 'sale_invoice.cust_id = customers.cust_id')
            ->join('sale_order_items', 'sale_invoice.invoice_id = sale_order_items.invoice_id')
            ->join('erp_groups_map', 'erp_groups_map.related_id = customers.cust_id')
            ->join('erp_groups', 'erp_groups_map.group_id = erp_groups.group_id')
            ->where('erp_groups.related_to', 'customer')
            ->get()->getResultArray();
        $total = [];
        foreach ($result as $row) {
            $group_name = $row['group_name'];
            $amount = floatval($row['amount']);
            if (!array_key_exists($group_name, $total)) {
                $total[$group_name] = 0.0;
            }
            $total[$group_name] += $amount;
        }
        return $total;
    }

    public function getInvoiceByCustomerId($customerId)
    {
        return $this->builder()->select('invoice_id, code')->where('cust_id', $customerId)->get()->getResultArray();
    }

    public function getCreditedInvoiceData($credit_id)
    {
        $query = "SELECT credits_applied.invoice_id, SUM(credits_applied.amount) as amount, credits_applied.date_applied,
                (SELECT code FROM sale_invoice WHERE sale_invoice.invoice_id = credits_applied.invoice_id) AS invoice_code FROM credits_applied
                WHERE credit_id = $credit_id GROUP BY credits_applied.invoice_id";
        return $this->db->query($query)->getResultArray();
    }

    public function getTotalUsedAmount($credit_id)
    {
        $query = "SELECT SUM(amount) as amount FROM credits_applied WHERE credit_id = $credit_id";
        return $this->db->query($query)->getResultArray()[0];
    }

    public function getInvoiceDataById($invoice_id)
    {
        return $this->builder()->select('
            COALESCE(total_amount, 0) AS amount_with_tax,
            COALESCE(paid_amount, 0) AS paid,
            COALESCE(credit_amount, 0) AS credit,
            COALESCE(total_amount - COALESCE(paid_amount, 0) - COALESCE(credit_amount, 0), 0) AS remaining_amount
        ')->from('(SELECT 
                    sale_invoice.invoice_id,
                    SUM(sale_order_items.amount + (sale_order_items.amount * (sale_order_items.tax1 + sale_order_items.tax2)) / 100) AS total_amount
                FROM sale_invoice
                JOIN sale_order_items ON sale_invoice.invoice_id = sale_order_items.invoice_id
                GROUP BY sale_invoice.invoice_id) AS invoice_totals')
            ->join('(SELECT 
                    sale_payments.invoice_id,
                    COALESCE(SUM(sale_payments.amount), 0) AS paid_amount
                FROM sale_payments
                GROUP BY sale_payments.invoice_id) AS payment_totals', 'invoice_totals.invoice_id = payment_totals.invoice_id', 'left')
            ->join('(SELECT 
                    credits_applied.invoice_id,
                    COALESCE(SUM(credits_applied.amount), 0) AS credit_amount
                FROM credits_applied
                GROUP BY credits_applied.invoice_id) AS credit_totals', 'invoice_totals.invoice_id = credit_totals.invoice_id', 'left')
            ->where('invoice_totals.invoice_id', $invoice_id)
            ->get()
            ->getResultArray();
    }


    //New Implemented - credits Applied via invoice

    public function get_m_invoice_Credititems($invoice_id)
    {
        $query = "SELECT credits_applied.credit_id, SUM(credits_applied.amount) as amount, credits_applied.date_applied as date, credit_notes.code as credit_code FROM credits_applied JOIN credit_notes ON credits_applied.credit_id= credit_notes.credit_id WHERE credits_applied.invoice_id = $invoice_id GROUP BY credits_applied.credit_id";

        return $this->db->query($query)->getResultArray();
    }

    public function get_c_invoice_Credititems($invoice_id)
    {
        $query = "SELECT credits_applied.credit_id, SUM(credits_applied.amount) as amount, credits_applied.date_applied as date, credit_notes.code as credit_code FROM credits_applied JOIN credit_notes ON credits_applied.credit_id= credit_notes.credit_id WHERE credits_applied.invoice_id = $invoice_id GROUP BY credits_applied.invoice_id";
        return $this->db->query($query)->getResultArray();
    }

    public function getInvoiceCodeByInvoiceId($invoice_id)
    {
        return $this->builder()->select('code')->where('invoice_id', $invoice_id)
            ->get()->getRow()->code;
    }

    public function getInvoiceAmountById($invoice_id)
    {
        $query = "SELECT 
                COALESCE(total_amount, 0) AS amount_with_tax,
                COALESCE(paid_amount, 0) AS paid,
                COALESCE(credit_amount, 0) AS credit,
                COALESCE(total_amount - COALESCE(paid_amount ,0)- COALESCE(credit_amount, 0), 0) AS remaining_amount
            FROM 
                (SELECT 
                    sale_invoice.invoice_id,
                    SUM(sale_order_items.amount + (sale_order_items.amount * (sale_order_items.tax1 + sale_order_items.tax2)) / 100) + trans_charge - discount AS total_amount
                FROM 
                    sale_invoice
                JOIN 
                    sale_order_items ON sale_invoice.invoice_id = sale_order_items.invoice_id
                GROUP BY 
                    sale_invoice.invoice_id) AS invoice_totals
            LEFT JOIN 
                (SELECT 
                    sale_payments.invoice_id,
                    COALESCE(SUM(sale_payments.amount), 0) AS paid_amount
                FROM 
                    sale_payments
                GROUP BY 
                    sale_payments.invoice_id) AS payment_totals ON invoice_totals.invoice_id = payment_totals.invoice_id
            LEFT JOIN 
                (SELECT 
                    credits_applied.invoice_id,
                    COALESCE(SUM(credits_applied.amount), 0) AS credit_amount
                FROM 
                    credits_applied
                GROUP BY 
                    credits_applied.invoice_id) AS credit_totals ON invoice_totals.invoice_id = credit_totals.invoice_id
            WHERE 
                invoice_totals.invoice_id = $invoice_id";
        return $this->db->query($query)->getResultArray()[0];
    }
    public function getexpenseInvoiceAmountById($invoice_id)
    {
        $query = "SELECT 
                COALESCE(total_amount, 0) AS amount_with_tax,
                COALESCE(paid_amount, 0) AS paid,
                COALESCE(credit_amount, 0) AS credit,
                COALESCE(total_amount - COALESCE(paid_amount ,0)- COALESCE(credit_amount, 0), 0) AS remaining_amount
            FROM 
                (SELECT 
                    sale_invoice.invoice_id,
                    SUM(expense_items.amount + (expense_items.amount * (expense_items.tax1 + expense_items.tax2)) / 100) + trans_charge - discount AS total_amount
                FROM 
                    sale_invoice
                JOIN 
                    expense_items ON sale_invoice.invoice_id = expense_items.invoice_id
                GROUP BY 
                    sale_invoice.invoice_id) AS invoice_totals
            LEFT JOIN 
                (SELECT 
                    sale_payments.invoice_id,
                    COALESCE(SUM(sale_payments.amount), 0) AS paid_amount
                FROM 
                    sale_payments
                GROUP BY 
                    sale_payments.invoice_id) AS payment_totals ON invoice_totals.invoice_id = payment_totals.invoice_id
            LEFT JOIN 
                (SELECT 
                    credits_applied.invoice_id,
                    COALESCE(SUM(credits_applied.amount), 0) AS credit_amount
                FROM 
                    credits_applied
                GROUP BY 
                    credits_applied.invoice_id) AS credit_totals ON invoice_totals.invoice_id = credit_totals.invoice_id
            WHERE 
                invoice_totals.invoice_id = $invoice_id";
        return $this->db->query($query)->getResultArray()[0];
    }

    public function getInvoiceNumberById($invoice_id)
    {
        return $this->builder()->select('code')->where('invoice_id', $invoice_id)->get()->getRow()->code;
    }

    public function getInvoiceById($invoice_id)
    {
        return $this->builder()->select('invoice_date, invoice_expiry, status')->where('invoice_id', $invoice_id)->get()->getResultArray()[0];
    }

    public function getBillingAddressByInvocieId($invoice_id)
    {
        $query = "SELECT customers.name as customer_name, customer_billingaddr.address, customer_billingaddr.city, 
                    customer_billingaddr.state, customer_billingaddr.country, customer_billingaddr.zipcode FROM sale_invoice 
                    JOIN customer_billingaddr ON sale_invoice.cust_id = customer_billingaddr.cust_id
                    JOIN customers ON customer_billingaddr.cust_id = customers.cust_id
                    WHERE invoice_id = $invoice_id";
        return $this->db->query($query)->getResultArray()[0];
    }

    public function getShippingAddressByInvocieId($invoice_id)
    {
        $query = "SELECT customers.name as customer_name, customer_shippingaddr.address, customer_shippingaddr.city, 
                    customer_shippingaddr.state, customer_shippingaddr.country, customer_shippingaddr.zipcode FROM sale_invoice 
                    JOIN customer_shippingaddr ON sale_invoice.cust_id = customer_shippingaddr.cust_id
                    JOIN customers ON customer_shippingaddr.cust_id = customers.cust_id
                    WHERE invoice_id = $invoice_id";
        return $this->db->query($query)->getResultArray()[0];
    }

    public function getInvoiceItemsById($invoice_id)
    {
        $query = "SELECT CONCAT(finished_goods.name,' ',finished_goods.code) as product_name, 
                    sale_order_items.quantity, 
                    sale_order_items.unit_price, 
                    sale_order_items.amount as amount, 
                    ((sale_order_items.tax1 + sale_order_items.tax2)/100) as total_tax,
                    (sale_order_items.amount + (sale_order_items.amount * (sale_order_items.tax1 + sale_order_items.tax2)) / 100) AS amount_with_tax, trans_charge, discount, sale_order_items.tax1 as tax1_amount, sale_order_items.tax2 as tax2_amount,
                    taxes1.tax_name AS tax1_rate, taxes2.tax_name AS tax2_rate
                    FROM sale_invoice 
                    JOIN sale_order_items ON sale_invoice.invoice_id = sale_order_items.invoice_id
                    JOIN finished_goods ON sale_order_items.related_id = finished_goods.finished_good_id 
                    JOIN price_list ON sale_order_items.price_id=price_list.price_id
                    LEFT JOIN 
                        taxes AS taxes1 ON price_list.tax1 = taxes1.tax_id
                    LEFT JOIN 
                        taxes AS taxes2 ON price_list.tax2 = taxes2.tax_id
                    WHERE sale_invoice.invoice_id = $invoice_id";
        return $this->db->query($query)->getResultArray();
    }

    public function getPaymentDataById($invoice_id)
    {
        $query = "SELECT SUM(sale_payments.amount) as payment_amount, paid_on, transaction_id, payment_modes.name FROM sale_invoice 
                  JOIN sale_payments ON sale_invoice.invoice_id = sale_payments.invoice_id
                  JOIN payment_modes ON sale_payments.payment_id = payment_modes.payment_id
                  WHERE sale_invoice.invoice_id = $invoice_id GROUP BY payment_modes.name";
        return $this->db->query($query)->getResultArray();
    }

    public function getPaymentAmount($invoice_id)
    {
        $query = "SELECT SUM(sale_payments.amount) as payment_amount FROM sale_invoice 
                  JOIN sale_payments ON sale_invoice.invoice_id = sale_payments.invoice_id
                  WHERE sale_invoice.invoice_id = $invoice_id ";
        return $this->db->query($query)->getResultArray()[0];
    }

    public function getMaximumInvoiceNumber()
    {
        $query = "SELECT MAX(invoice_id) as max_code  FROM sale_invoice ";
        $result = $this->db->query($query)->getResultArray()[0];
        return ($result['max_code']) ? (int) $result['max_code'] : 0;
    }

    public function getMaximumCreditNoteNumber()
    {
        $query = "SELECT MAX(credit_id) as max_code FROM credit_notes";
        $result = $this->db->query($query)->getResultArray()[0];
        return ($result['max_code']) ? (int) $result['max_code'] : 0;
    }


    public function getCustomerContactById($invoice_id)
    {
        $query = "SELECT customer_contacts.email, customer_contacts.cust_id  FROM sale_invoice JOIN customer_contacts ON sale_invoice.cust_id = customer_contacts.cust_id WHERE invoice_id = $invoice_id";
        return $this->db->query($query)->getResultArray();
    }

    public function getCustomerMailById($invoice_id)
    {
        $query = "SELECT customers.email, customers.cust_id FROM sale_invoice JOIN customers ON sale_invoice.cust_id = customers.cust_id WHERE invoice_id = $invoice_id";
        return $this->db->query($query)->getResultArray();
    }

    public function updateShippingAddress($data)
    {
        $exist_check = "SELECT cust_id FROM customer_shippingaddr WHERE cust_id= " . $data['cust_id'] . " LIMIT 1";
        $result = $this->db->query($exist_check)->getRow();

        if ($result) {
            $query = "UPDATE customer_shippingaddr SET address = ?, city = ?, state = ?, zipcode = ?, country = ? WHERE cust_id = ?";
            $result = $this->db->query($query, [
                $data['address'],
                $data['city'],
                $data['state'],
                $data['zipcode'],
                $data['country'],
                $data['cust_id']
            ]);
        } else {
            $query = "INSERT INTO customer_shippingaddr (cust_id, address, city, state, zipcode, country, created_at, created_by) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
            $result = $this->db->query($query, [
                $data['cust_id'],
                $data['address'],
                $data['city'],
                $data['state'],
                $data['zipcode'],
                $data['country'],
                $data['created_at'],
                $data['created_by']
            ]);
        }

        return $result ? true : false;
    }

    public function updateBillingAddress($data)
    {
        $exist_check = "SELECT cust_id FROM customer_billingaddr WHERE cust_id= " . $data['cust_id'] . " LIMIT 1";
        $result = $this->db->query($exist_check)->getRow();

        if ($result) {
            $query = "UPDATE customer_billingaddr SET address = ?, city = ?, state = ?, zipcode = ?, country = ? WHERE cust_id = ?";
            $result = $this->db->query($query, [
                $data['address'],
                $data['city'],
                $data['state'],
                $data['zipcode'],
                $data['country'],
                $data['cust_id']
            ]);
        } else {
            $query = "INSERT INTO customer_billingaddr (cust_id, address, city, state, zipcode, country, created_at, created_by) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
            $result = $this->db->query($query, [
                $data['cust_id'],
                $data['address'],
                $data['city'],
                $data['state'],
                $data['zipcode'],
                $data['country'],
                $data['created_at'],
                $data['created_by']
            ]);
        }

        return $result ? true : false;
    }


    public function getShippingAddressByshipId($customerId)
    {
        $query = "SELECT * FROM customer_shippingaddr WHERE cust_id =" . $customerId;
        return $this->db->query($query)->getResultArray()[0] ?? null;
    }

    public function getBillingAddressBybillId($customerId)
    {
        $query = "SELECT * FROM customer_billingaddr WHERE cust_id =" . $customerId;
        return $this->db->query($query)->getResultArray()[0] ?? null;
    }

    public function getMobileEmail($customerId)
    {
        $query = "SELECT phone, email FROM customers WHERE cust_id=" . $customerId;
        return $this->db->query($query)->getResultArray()[0];
    }

    public function getCurrencyPlaceById($invoice_id)
    {
        $query = "SELECT currency_place FROM sale_invoice JOIN currencies ON sale_invoice.currency_id = currencies.currency_id WHERE invoice_id= $invoice_id";
        return $this->db->query($query)->getResultArray()[0];
    }

    public function getCurrencySymbolbyId($invoice_id)
    {
        $query = "SELECT currencies.symbol FROM sale_invoice JOIN currencies ON sale_invoice.currency_id = currencies.currency_id WHERE invoice_id= ?";
        return $this->db->query($query, [$invoice_id])->getResultArray()[0];
    }

    public function getSelectedCurrencyPlace($invoice_id)
    {
        $query = "SELECT currency_place FROM sale_invoice WHERE invoice_id = $invoice_id";
        return $this->db->query($query)->getResultArray()[0];
    }

    public function getSelectedCurrencyName($invoice_id, $currency_place)
    {
        $query = "SELECT iso_code, symbol, currencies.currency_id FROM sale_invoice JOIN currencies ON sale_invoice.currency_id = currencies.currency_id WHERE invoice_id = ? AND currency_place = ?";
        return $this->db->query($query, [$invoice_id, $currency_place])->getResultArray()[0];
    }

    public function get_invoicetype_by_id($invoice_id)
    {
        $query = "SELECT * FROM sale_invoice WHERE invoice_id = $invoice_id";
        $type = $this->db->query($query)->getResultArray()[0];
        return $type;
    }

    public function get_status($id)
    {
        return $this->builder()->select("status")->where("invoice_id", $id)->get()->getRowArray();
    }


    /**
     * Retrieves a summarized view of an invoice.
     * @auther Ashok kumar
     * This function dynamically calculates and fetches details of an invoice, 
     * including customer details, addresses, payment terms, transport charges, 
     * and total amounts (including taxes and discounts).
     *
     * @param int $invoice_id The ID of the invoice to retrieve.
     * @return object|null Returns an object containing the summarized invoice details, or null if no data is found.
     */
    public function get_invoice_total_summary($invoice_id)
    {
        $query = "
        SELECT 
            sale_invoice.invoice_id, 
            code, 
            invoice_date, 
            invoice_expiry, 
            payment_terms, 
            terms_condition, 
            discount,
            customers.name, 
            customers.email,
            CONCAT(customer_shippingaddr.address, ',', customer_shippingaddr.city, ',', customer_shippingaddr.state, ',', customer_shippingaddr.country, ',', customer_shippingaddr.zipcode) AS shipping_addr,
            CONCAT(customer_billingaddr.address, ',', customer_billingaddr.city, ',', customer_billingaddr.state, ',', customer_billingaddr.country, ',', customer_billingaddr.zipcode) AS billing_addr,
            CASE WHEN transport_req = 1 THEN 'Yes' ELSE 'No' END AS transport_req,
            trans_charge, 
            sale_invoice.status, 
            paid_till,
            (SUM(
                COALESCE(expense_items.amount, 0) + COALESCE(sale_order_items.amount, 0) +
                (COALESCE(expense_items.amount, 0) * (COALESCE(expense_items.tax1, 0) + COALESCE(expense_items.tax2, 0)) / 100) +
                (COALESCE(sale_order_items.amount, 0) * (COALESCE(sale_order_items.tax1, 0) + COALESCE(sale_order_items.tax2, 0)) / 100)
            ) + trans_charge - discount) AS total_amount,
            (SUM(
                COALESCE(expense_items.amount, 0) + COALESCE(sale_order_items.amount, 0) +
                (COALESCE(expense_items.amount, 0) * (COALESCE(expense_items.tax1, 0) + COALESCE(expense_items.tax2, 0)) / 100) +
                (COALESCE(sale_order_items.amount, 0) * (COALESCE(sale_order_items.tax1, 0) + COALESCE(sale_order_items.tax2, 0)) / 100)
            ) + trans_charge - discount - paid_till) AS amount_due
        FROM sale_invoice
        JOIN customers ON sale_invoice.cust_id = customers.cust_id
        LEFT JOIN expense_items ON sale_invoice.invoice_id = expense_items.invoice_id
        LEFT JOIN sale_order_items ON sale_invoice.invoice_id = sale_order_items.invoice_id
        LEFT JOIN customer_shippingaddr ON sale_invoice.cust_id = customer_shippingaddr.cust_id
        LEFT JOIN customer_billingaddr ON sale_invoice.cust_id = customer_billingaddr.cust_id
        WHERE sale_invoice.invoice_id = ?
        GROUP BY sale_invoice.invoice_id";

        // Execute the query
        $result = $this->db->query($query, [$invoice_id])->getRow();

        return $result;
    }

}
