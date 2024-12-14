<?php

namespace App\Models\Erp;

use CodeIgniter\Model;

class PaymentModesModel extends Model
{
    protected $table      = 'payment_modes';
    protected $primaryKey = 'payment_id';

    protected $allowedFields = [
        'name',
        'description',
        'active',
        'created_at',
        'created_by',
    ];
    public $db;
    public $session;

    public function __construct()
    {
        $this->db = \Config\Database::connect();
        $this->session = \Config\Services::session();
    }
    protected $useTimestamps = false;

    public function get_dtconfig_payments()
    {
        $config = array(
            "columnNames" => ["sno", "payment mode", "amount", "paid on", "transaction id", "action"],
            "sortable" => [0, 1, 1, 1, 0, 0],
        );
        return json_encode($config);
    }

    public function get_all_paymentmodes()
    {
        $query = "SELECT name,payment_id FROM payment_modes WHERE active=1";
        $result = $this->db->query($query)->getResultArray();
        return $result;
    }

    public function insert_update_paymentmode($payment_id, $post)
    {

        if (empty($payment_id)) {
            $this->insert_paymentmode($post);
        } else {
            $this->update_paymentmode($payment_id, $post);
        }
    }

    private function insert_paymentmode($post)
    {
        $name = $post["mode_name"];
        $description = $post["description"];
        $created_by = get_user_id();
        $query = "INSERT INTO payment_modes(name,description,created_at,created_by) VALUES(?,?,?,?) ";
        $this->db->query($query, array($name, $description, time(), $created_by));

        $config['title'] = "Payment Mode Insert";
        if (!empty($this->db->insertId())) {
            $config['log_text'] = "[ Payment Mode successfully created ]";
            $config['ref_link'] = 'erp/finance/paymentmode/';
            $this->session->setFlashdata("op_success", "Payment Mode successfully created");
        } else {
            $config['log_text'] = "[ Payment Mode failed to create ]";
            $config['ref_link'] = "erp/finance/paymentmode/";
            $this->session->setFlashdata("op_error", "Payment Mode failed to create");
        }
        log_activity($config);
    }

    private function update_paymentmode($payment_id, $post)
    {
        $name = $post["mode_name"];
        $description = $post["description"];
        $query = "UPDATE payment_modes SET name=? , description=? WHERE payment_id=$payment_id";
        $this->db->query($query, array($name, $description));

        $config['title'] = "Payment Mode Update";
        if ($this->db->affectedRows() > 0) {
            $config['log_text'] = "[ Payment Mode successfully updated ]";
            $config['ref_link'] = 'erp/finance/paymentmode/';
            $this->session->setFlashdata("op_success", "Payment Mode successfully updated");
        } else {
            $config['log_text'] = "[ Payment Mode failed to update ]";
            $config['ref_link'] = "erp/finance/paymentmode/";
            $this->session->setFlashdata("op_error", "Payment Mode failed to update");
        }
        log_activity($config);
    }

    public function get_dtconfig_paymentmode()
    {
        $config = array(
            "columnNames" => ["sno", "payment mode name", "description", "active", "action"],
            "sortable" => [0, 1, 0, 0, 0]
        );
        return json_encode($config);
    }

    public function get_paymentmode_datatable()
    {
        $columns = array(
            "payment mode name" => "name"
        );
        $limit = $_GET['limit'];
        $offset = $_GET['offset'];
        $search = $_GET['search'] ?? "";
        $orderby = isset($_GET['orderby']) ? strtoupper($_GET['orderby']) : "";
        $ordercol = isset($_GET['ordercol']) ? $columns[$_GET['ordercol']] : "";

        $query = "SELECT payment_id,name,description,active FROM payment_modes ";
        $count = "SELECT COUNT(payment_id) AS total FROM payment_modes ";
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
                                <li><a href="#" data-ajax-url="' . url_to('erp.finance.ajaxfetchpaymentmode', $r['payment_id']) . '"  title="Edit" class="bg-success modalBtn"><i class="fa fa-pencil"></i> </a></li>
                                <li><a href="' . url_to('erp.finance.paymentmodedelete', $r['payment_id']) . '" title="Delete" class="bg-danger del-confirm"><i class="fa fa-trash"></i> </a></li>
                            </ul>
                        </div>
                    </div>';
            $active = '
                    <label class="toggle active-toggler-1" data-ajax-url="' . url_to('erp.finance.ajaxpaymentmodeactive', $r['payment_id']) . '" >
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
                    $r['payment_id'],
                    $sno,
                    $r['name'],
                    $r['description'],
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

    public function get_paymentmode_export($type)
    {
        $columns = array(
            "payment mode name" => "name"
        );
        $limit = $_GET['limit'];
        $offset = $_GET['offset'];
        $search = $_GET['search'] ?? "";
        $orderby = isset($_GET['orderby']) ? strtoupper($_GET['orderby']) : "";
        $ordercol = isset($_GET['ordercol']) ? $columns[$_GET['ordercol']] : "";

        $query = "SELECT name,description,CASE WHEN active=1 THEN 'Yes' ELSE 'No' END AS active FROM payment_modes ";
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

    public function deletePaymentmode($payment_id)
    {
        $query = "DELETE FROM payment_modes WHERE payment_id=$payment_id";
        $this->db->query($query);
        $config['title'] = "Payment Mode Delete";
        if ($this->db->affectedRows() > 0) {
            $config['log_text'] = "[ Payment Mode successfully deleted ]";
            $config['ref_link'] = "erp/finance/journalentry/";
            $this->session->setFlashdata("op_success", "Payment Mode successfully deleted");
        } else {
            $config['log_text'] = "[ Payment Mode failed to delete ]";
            $config['ref_link'] = "erp/finance/journalentry/";
            $this->session->setFlashdata("op_error", "Payment Mode failed to delete");
        }
        log_activity($config);
    }

    public function get_dtconfig_payments_list()
    {
        $config = array(
            "columnNames" => ["payment #", "invoice #", "payment mode", "transaction id", "customer", "amount", "paid on",  "action"],
            "sortable" => [1, 1, 1, 1, 1, 1, 1, 0],
        );
        return json_encode($config);
    }

    public function get_payment_list_datatable()
    {
        $columns = array(
            "payment mode name" => "name"
        );
        $limit = $_GET['limit'];
        $offset = $_GET['offset'];
        $search = $_GET['search'] ?? "";
        $orderby = isset($_GET['orderby']) ? strtoupper($_GET['orderby']) : "";
        $ordercol = isset($_GET['ordercol']) ? $columns[$_GET['ordercol']] : "";

        $query = "SELECT sale_payments.sale_pay_id, sale_invoice.code, payment_modes.name as payment_name, sale_payments.transaction_id, sale_payments.amount, sale_payments.paid_on, customers.company FROM sale_payments JOIN sale_invoice ON sale_payments.invoice_id =  sale_invoice.invoice_id JOIN payment_modes ON sale_payments.payment_id = payment_modes.payment_id JOIN customers ON sale_invoice.cust_id = customers.cust_id ";

        $where = false;

        if (!empty($search)) {
            if (!$where) {
                $query .= " WHERE payment_modes.name LIKE '%" . $search . "%' OR  sale_invoice.code LIKE '%" . $search . "%' OR customers.company LIKE '%" . $search . "%' OR sale_payments.transaction_id LIKE '%" . $search . "%'";
                $where = true;
            } else {
                $query .= " AND payment_modes.name LIKE '%" . $search . "%' OR  sale_invoice.code LIKE '%" . $search . "%' OR customers.company LIKE '%" . $search . "%' OR sale_payments.transaction_id LIKE '%" . $search . "%'";
            }
        }

        if (!empty($ordercol) && !empty($orderby)) {
            $query .= " ORDER BY " . $ordercol . " " . $orderby;
        }
        $query .= "LIMIT $offset, $limit ";
        $result = $this->db->query($query)->getResultArray();
        $m_result = array();
        foreach ($result as $r) {
            $action = '<div class="dropdown tableAction">
                        <a type="button" class="dropBtn HoverA "><i class="fa fa-ellipsis-v"></i></a>
                        <div class="dropdown_container">
                            <ul class="BB_UL flex">
                            <li><a href="' . url_to('erp.sale.payments.view', $r['sale_pay_id']) . '" title="View" class="bg-warning "><i class="fa fa-eye"></i> </a></li>
                            <li><a href="' . url_to('erp.sale.payments.edit', $r['sale_pay_id']) . '" title="Edit" class="bg-success "><i class="fa fa-pencil"></i> </a></li>
                            <li><a href="' . url_to('erp.sale.payments.delete', $r['sale_pay_id']) . '" title="Delete" class="bg-danger del-confirm"><i class="fa fa-trash"></i> </a></li>
                            </ul>
                        </div>
                    </div>';

            array_push(
                $m_result,
                array(
                    $r['sale_pay_id'],
                    $r['sale_pay_id'],
                    $r['code'],
                    $r['payment_name'],
                    $r['transaction_id'],
                    $r['company'],
                    $r['amount'],
                    $r['paid_on'],
                    $action
                )
            );
        }
        $response = array();
        // $response['total_rows'] = $this->db->query($count)->getRow();
        $response['data'] = $m_result;
        return $response;
    }

    public function getPaymentData($id)
    {
        $query = "SELECT customers.name, customers.address, customers.company, customers.city, customers.state, customers.country, customers.zip FROM sale_payments JOIN sale_invoice ON sale_payments.invoice_id = sale_invoice.invoice_id JOIN customers ON sale_invoice.cust_id = customers.cust_id WHERE sale_pay_id = $id";
        return $this->db->query($query)->getResultArray()[0];
    }

    public function getInvoiceData($id)
    {
        $query = "SELECT code, invoice_date, sale_invoice.invoice_id, (SUM(sale_order_items.amount + (sale_order_items.amount * (sale_order_items.tax1 + sale_order_items.tax2)) / 100)+ trans_charge - discount) AS total_amount, COALESCE(sale_payments.amount, 0) as paid_amount, payment_modes.name as payment_mode,
        COALESCE(credits_applied.amount, 0) as credited_amount, paid_on  FROM sale_payments JOIN sale_invoice ON sale_payments.invoice_id = sale_invoice.invoice_id JOIN sale_order_items ON sale_invoice.invoice_id = sale_order_items.invoice_id LEFT JOIN credits_applied ON sale_payments.invoice_id = credits_applied.invoice_id JOIN payment_modes ON sale_payments.payment_id = payment_modes.payment_id  WHERE sale_pay_id = $id";
        return $this->db->query($query)->getResultArray()[0];
    }

    public function getTotalPaidAmount($id)
    {
        $query = "SELECT COALESCE(SUM(sale_payments.amount), 0) as total_paid_amount FROM sale_payments WHERE invoice_id = $id";
        return $this->db->query($query)->getRow()->total_paid_amount;
    }

    public function getPaymentDataByPaymentId($id)
    {
        $query = "SELECT amount, paid_on, transaction_id, notes, payment_modes.name FROM sale_payments JOIN payment_modes ON sale_payments.payment_id = payment_modes.payment_id WHERE sale_pay_id = $id";
        return $this->db->query($query)->getResultArray()[0];
    }

    public function update_payment($data)
    {
        $builder = $this->db->table('sale_payments');
        $builder->where('sale_pay_id', $data['sale_pay_id']);
        $builder->set('amount', $data['amount']);
        $builder->set('paid_on', $data['paid_on']);
        $builder->set('payment_id', $data['payment_id']);
        $builder->set('transaction_id', $data['transaction_id']);
        $builder->set('notes', $data['notes']);
        $builder->update();
        return $builder;
    }

    public function payment_mode_fetch(){
        return $this->builder()->select()->where("active",1)->get()->getResultArray();
    }

    public function get_payment_data_for_finance($year = "")
    {
        if(empty($year)){
            $year = date('Y');
        }
        $query = "SELECT 
        SUM(amount) AS total_expenditure, MONTH(paid_on) AS month_number
        FROM sale_payments
        WHERE YEAR(paid_on) = $year
        GROUP BY YEAR(paid_on), MONTH(paid_on)
        ORDER BY month_number";
        return $this->db->query($query)->getResultArray();
    }
    
}
