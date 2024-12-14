<?php

namespace App\Models\Erp\Sale;

use CodeIgniter\Model;
use DateTime;


class SalePaymentModel extends Model
{
    protected $table            = 'sale_payments';
    protected $primaryKey       = 'sale_pay_id';
    protected $allowedFields    = [
        'invoice_id',
        'payment_id',
        'amount',
        'paid_on',
        'transaction_id',
        'notes'

    ];

    protected $db;
    protected $session;

    public function __construct()
    {
        $this->db = \Config\Database::connect();
        $this->session = \Config\Services::session();
    }

    public function get_payment_datatable($invoice_id)
    {

        $columns = array(
            "payment mode" => "pyament_modes.name",
            "amount" => "amount",
            "paid on" => "paid_on"
        );

        $limit = $_GET['limit'];
        $offset = $_GET['offset'];
        $search = $_GET['search'] ?? "";

        $orderby = isset($_GET['orderby']) ? strtoupper($_GET['orderby']) : "";
        $ordercol = isset($_GET['ordercol']) ? $columns[$_GET['ordercol']] : "";
        $query = "SELECT sale_pay_id,payment_modes.name,amount,paid_on,transaction_id FROM sale_payments JOIN payment_modes ON sale_payments.payment_id=payment_modes.payment_id WHERE invoice_id=$invoice_id ";
        $count = "SELECT COUNT(sale_pay_id) AS total FROM sale_payments JOIN payment_modes ON sale_payments.payment_id=payment_modes.payment_id WHERE invoice_id=$invoice_id ";
        $where = true;

        if (!empty($search)) {
            if (!$where) {
                $query .= " WHERE payment_modes.name LIKE '%" . $search . "%' ";
                $count .= " WHERE payment_modes.name LIKE '%" . $search . "%' ";
                $where = true;
            } else {
                $query .= " AND payment_modes.name LIKE '%" . $search . "%' ";
                $count .= " AND payment_modes.name LIKE '%" . $search . "%' ";
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
                                <li><a href="#" data-ajax-url="' . url_to('erp.sale.ajaxfetchinvoicepayment', $r['sale_pay_id']) . '" title="Edit" class="bg-success modalBtn"><i class="fa fa-pencil"></i> </a></li>
                                <li><a href="' . url_to('erp.sale.invoicepaymentdelete', $invoice_id, $r['sale_pay_id']) . '" title="Delete" class="bg-danger del-confirm"><i class="fa fa-trash"></i> </a></li>
                            </ul>
                        </div>
                    </div>';
            array_push(
                $m_result,
                array(
                    $r['sale_pay_id'],
                    $sno,
                    $r['name'],
                    $r['amount'],
                    $r['paid_on'],
                    $r['transaction_id'],
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


    public function insert_update_payment($invoice_id, $sale_pay_id, $data)
    {

        if (empty($sale_pay_id)) {
            $this->insert_payment($invoice_id, $data);
        } else {
            $this->update_payment($invoice_id, $sale_pay_id, $data);
        }
    }

    private function insert_payment($invoice_id, $data)
    {
        $this->db->transbegin();
        $query = "INSERT INTO sale_payments(invoice_id,payment_id,amount,paid_on,transaction_id,notes) VALUES(?,?,?,?,?,?) ";
        $this->db->query($query, array($invoice_id, $data['payment_id'], $data['amount'], $data['paid_on'], $data['transaction_id'], $data['notes']));
        if (empty($this->db->insertid())) {
            $this->db->transrollback();
            $this->session->setFlashdata("op_error", "Sale Payment failed to create");
            return;
        }
        $query = "SELECT * FROM sale_invoice WHERE invoice_id = $invoice_id";
        $type = $this->db->query($query)->getResultArray()[0];
        if ($type["type"] == 3) {
            $sale_amount = $this->db->query("SELECT (SUM(b.amount)+a.trans_charge) AS amount FROM sale_invoice as a JOIN expense_items as b ON a.invoice_id=b.invoice_id WHERE a.invoice_id=$invoice_id")->getRow()->amount;
            $query = "UPDATE sale_invoice SET status=
            CASE 
            WHEN (paid_till+?)<=0 THEN 0
            WHEN $sale_amount <= (paid_till+?) THEN 2
            WHEN $sale_amount > (paid_till+?) THEN 1
            END, paid_till=paid_till+? , can_edit=0 WHERE sale_invoice.invoice_id=? ";
        } else {
            $sale_amount = $this->db->query("SELECT (SUM(amount)+trans_charge-discount) AS amount FROM sale_invoice JOIN sale_order_items ON sale_invoice.invoice_id=sale_order_items.invoice_id WHERE sale_invoice.invoice_id=$invoice_id")->getRow()->amount;
            $query = "UPDATE sale_invoice SET status=
            CASE 
            WHEN (paid_till+?)<=0 THEN 0
            WHEN $sale_amount <= (paid_till+?) THEN 2
            WHEN $sale_amount > (paid_till+?) THEN 1
            END, paid_till=paid_till+? , can_edit=0 WHERE sale_invoice.invoice_id=? ";
        }
        $this->db->query($query, array($data['amount'], $data['amount'], $data['amount'], $data['amount'], $invoice_id));
        $this->db->transcomplete();

        $config['title'] = "Sale Payment Insert";
        if ($this->db->transstatus() === FALSE) {
            $this->db->transrollback();
            $config['log_text'] = "[ Sale Payment failed to create ]";
            $config['ref_link'] = "erp/sale/invoice_view/" . $invoice_id;
            session()->setFlashdata("op_error", "Sale Payment failed to create");
        } else {
            $this->db->transcommit();
            $config['log_text'] = "[ Sale Payment successfully created ]";
            $config['ref_link'] = "erp/sale/invoice_view/" . $invoice_id;
            session()->setFlashdata("op_success", "Sale Payment successfully created");
        }
        log_activity($config);
    }

    private function update_payment($invoice_id, $sale_pay_id, $data)
    {
        $this->db->transbegin();
        $query = "SELECT * FROM sale_invoice WHERE invoice_id = $invoice_id";
        $type = $this->db->query($query)->getResultArray()[0];
        if($type["type"] == 3){
            $sale_amount = $this->db->query("SELECT SUM(b.amount)+a.trans_charge AS amount FROM sale_invoice as a JOIN expense_items as b ON a.invoice_id=b.invoice_id WHERE a.invoice_id=$invoice_id")->getRow()->amount;
        }
        else{
            $sale_amount = $this->db->query("SELECT (SUM(amount)+trans_charge-discount) AS amount FROM sale_invoice JOIN sale_order_items ON sale_invoice.invoice_id=sale_order_items.invoice_id WHERE sale_invoice.invoice_id=$invoice_id")->getRow()->amount;
        }
        $old_amount = $this->db->query("SELECT amount FROM sale_payments WHERE sale_pay_id=" . $sale_pay_id)->getRow()->amount;
        $diff = $data['amount'] - $old_amount;
        $query = "UPDATE sale_payments SET invoice_id=? , payment_id=? , amount=? , paid_on=? , transaction_id=? , notes=? WHERE sale_pay_id= " . $sale_pay_id;
        $this->db->query($query, array($invoice_id, $data['payment_id'], $data['amount'], $data['paid_on'], $data['transaction_id'], $data['notes']));
        if ($diff != 0) {
            $query = " UPDATE sale_invoice SET status=
            CASE 
            WHEN (paid_till+?)<=0 THEN 0
            WHEN $sale_amount <= (paid_till+?) THEN 2
            WHEN $sale_amount > (paid_till+?) THEN 1
            END, paid_till=paid_till+?  WHERE invoice_id=? ";
            $this->db->query($query, array($diff, $diff, $diff, $diff, $invoice_id));
        }
        $this->db->transcomplete();

        $config['title'] = "Sale Payment Update";
        if ($this->db->transstatus() === FALSE) {
            $this->db->transrollback();
            $config['log_text'] = "[ Sale Payment failed to update ]";
            $config['ref_link'] = "erp/sale/manage_view/" . $invoice_id;
            session()->setFlashdata("op_error", "Sale Payment failed to update");
        } else {
            $this->db->transcommit();
            $config['log_text'] = "[ Sale Payment successfully updated ]";
            $config['ref_link'] = "erp/sale/manage_view/" . $invoice_id;
            session()->setFlashdata("op_success", "Sale Payment successfully updated");
        }
        log_activity($config);
    }

    public function delete_payment($invoice_id, $sale_pay_id)
    {

        $this->db->transbegin();
        $old_amount = $this->db->query("SELECT amount FROM sale_payments WHERE sale_pay_id=$sale_pay_id")->getRow()->amount;
        $query = "DELETE FROM sale_payments WHERE sale_pay_id=$sale_pay_id ";
        $this->db->query($query);
        $query = "SELECT * FROM sale_invoice WHERE invoice_id = $invoice_id";
        $type = $this->db->query($query)->getResultArray()[0];
        if($type["type"] == 3){
            $sale_amount = $this->db->query("SELECT SUM(b.amount)+a.trans_charge AS amount FROM sale_invoice  as a JOIN expense_items as b ON a.invoice_id=b.invoice_id WHERE a.invoice_id=$invoice_id")->getRow()->amount;
        }
        else{
            $sale_amount = $this->db->query("SELECT (SUM(amount)+trans_charge-discount) AS amount FROM sale_invoice JOIN sale_order_items ON sale_invoice.invoice_id=sale_order_items.invoice_id WHERE sale_invoice.invoice_id=$invoice_id")->getRow()->amount;
        }
        $query = " UPDATE sale_invoice SET status=
            CASE 
            WHEN (paid_till-?)<=0 THEN 0
            WHEN $sale_amount <= (paid_till-?) THEN 2
            WHEN $sale_amount > (paid_till-?) THEN 1
            END, paid_till=paid_till-?  WHERE invoice_id=? ";
        $this->db->query($query, array($old_amount, $old_amount, $old_amount, $old_amount, $invoice_id));
        $this->db->transcomplete();

        $config['title'] = "Sale Payment Delete";
        if ($this->db->transstatus() === FALSE) {
            $this->db->transrollback();
            $config['log_text'] = "[ Sale Payment failed to delete ]";
            $config['ref_link'] = "erp/sale/manage_view/" . $invoice_id;
            session()->setFlashdata("op_error", "Sale Payment failed to delete");
        } else {
            $this->db->transcommit();
            $config['log_text'] = "[ Sale Payment successfully deleted ]";
            $config['ref_link'] = "erp/sale/manage_view/" . $invoice_id;
            session()->setFlashdata("op_success", "Sale Payment successfully deleted");
        }
        log_activity($config);
    }

    public function get_invoicepayment_export()
    {
        $invoice_id = $_GET["invoiceid"];
        $columns = array(
            "payment mode" => "payment_modes.name",
            "amount" => "amount",
            "paid on" => "paid_on"
        );

        $limit = $_GET['limit'];
        $offset = $_GET['offset'];
        $search = $_GET['search'] ?? "";

        $orderby = isset($_GET['orderby']) ? strtoupper($_GET['orderby']) : "";
        $ordercol = isset($_GET['ordercol']) ? $columns[$_GET['ordercol']] : "";
        $query = "SELECT payment_modes.name,amount,paid_on,transaction_id,notes FROM sale_payments JOIN payment_modes ON sale_payments.payment_id=payment_modes.payment_id WHERE invoice_id=$invoice_id ";
        $where = true;

        if (!empty($search)) {
            if (!$where) {
                $query .= " WHERE payment_modes.name LIKE '%" . $search . "%' ";
                $where = true;
            } else {
                $query .= " AND payment_modes.name LIKE '%" . $search . "%' ";
            }
        }

        if (!empty($ordercol) && !empty($orderby)) {
            $query .= " ORDER BY " . $ordercol . " " . $orderby;
        }

        $result = $this->db->query($query)->getResultArray();
        return $result;
    }

    public function orderStatus()
    {
        $query = $this->db->table('sale_order');
        $query->select('
        sale_order.code,
        sale_order.order_date,
        sale_order.order_expiry,
        sale_order.status,
        sale_order_items.amount,
        ROUND((sale_order_items.amount * (sale_order_items.tax1 + sale_order_items.tax2) / 100)) AS tax
    ');
        $query->join('sale_order_items', 'sale_order.order_id = sale_order_items.order_id');

        return $result = $query->limit(100)->get()->getResultArray();
    }

    public function getAllTime()
    {
        return $this->builder()->select('paid_on')->get()->getResultArray();
    }

    public function getCurrenySymbolByid($id)
    {
        return $this->builder()->select('currencies.symbol as currency_symbol, place')->join('sale_invoice', 'sale_payments.invoice_id = sale_invoice.invoice_id')
            ->join('currencies', 'sale_invoice.currency_id = currencies.currency_id')
            ->where('sale_payments.sale_pay_id', $id)->get()->getResultArray()[0];
    }
}
