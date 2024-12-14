<?php

namespace App\Models\Erp\Sale;

use CodeIgniter\Model;

class SaleOrderModel extends Model
{
    protected $table = 'sale_order';
    protected $primaryKey = 'order_id';
    protected $allowedFields = [
        'code',
        'order_date',
        'order_expiry',
        'cust_id',
        'shippingaddr_id',
        'transport_req',
        'trans_charge',
        'discount',
        'terms_condition',
        'payment_terms',
        'status',
        'remarks',
        'type',
        'can_edit',
        'stock_pick',
        'created_at',
        'created_by',
    ];

    protected $useTimestamps = true;
    protected $session;
    protected $db;

    public function __construct()
    {
        $this->session = \Config\Services::session();
        $this->db = \Config\Database::connect();
    }
    public function convert_order_from_quote($quote_id, $post)
    {
        $created = false;
        $order_expiry = $post["order_expiry"];
        $code = $post["code"];
        $query = "SELECT * FROM quotations WHERE quote_id=$quote_id";
        $result = $this->db->query($query)->getRow();
        if (empty($result)) {
            $this->session->setFlashdata("op_error", "Conversion to order failed");
            return $created;
        }
        $created_by = get_user_id();
        $this->db->transBegin();
        $query = "INSERT INTO sale_order(code,order_date,order_expiry,cust_id,shippingaddr_id,transport_req,trans_charge,discount,terms_condition,payment_terms,created_at,created_by,type,can_edit) VALUES(?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
        $this->db->query($query, array($code, $result->quote_date, $order_expiry, $result->cust_id, $result->shippingaddr_id, $result->transport_req, $result->trans_charge, $result->discount, $result->terms_condition, $result->payment_terms, time(), $created_by, 1, 0));
        $order_id = $this->db->insertId();
        if (empty($order_id)) {
            $this->session->setFlashdata("op_error", "Order Conversion failed");
            return $created;
        }
        $this->db->query("UPDATE sale_order_items SET order_id=$order_id WHERE quote_id=$quote_id");
        $this->db->query("UPDATE quotations SET status=4 WHERE quote_id=$quote_id");
        $this->db->transComplete();
        $config['title'] = "Order Conversion";
        if ($this->db->transStatus() === FALSE) {
            $this->db->transRollback();
            $config['log_text'] = "[ Order Conversion failed ]";
            $config['ref_link'] = "";
            $this->session->setFlashdata("op_success", "Order Conversion failed");
        } else {
            $this->db->transCommit();
            $created = true;
            $config['log_text'] = "[ Order Conversion successfully ]";
            $config['ref_link'] = url_to('erp.sale.orders.view', $order_id);
            $this->session->setFlashdata("op_success", "Order Conversion successfully");
        }
        log_activity($config);
        return $created;
    }
}
