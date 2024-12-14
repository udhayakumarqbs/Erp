<?php

namespace App\Models\Erp;

use CodeIgniter\Model;

class PurchaseOrderModel extends Model
{
    protected $table = 'purchase_order';
    protected $primaryKey = 'order_id';
    protected $allowedFields = [
        'order_code',
        'req_id',
        'selection_rule',
        'supplier_id',
        'internal_transport',
        'transport_id',
        'transport_unit',
        'transport_charge',
        'rfq_id',
        'supp_location_id',
        'delivery_date',
        'status',
        'warehouse_id',
        'terms_condition',
        'notes',
        'grn_created',
        'invoice_created',
        'created_at',
        'created_by'
    ];

    protected $db;
    protected $session;
    public function __construct()
    {
        $this->db = \Config\Database::connect();
        $this->session = \Config\Services::session();
    }
    //NEED TO ADD CHECK2 ONCE SALES ORDER TABLE CREATED
    public function delete_transport($transport_id)
    {
        $check1 = $this->db->query("SELECT COUNT(transport_id) AS total FROM purchase_order WHERE transport_id=$transport_id ")->getRow();
        if ($check1->total > 0) {
            $this->session->setFlashdata("op_error", "Transport is used in Purchase Order");
        } else {
            $query = "DELETE FROM transports WHERE transport_id=$transport_id";
            $this->db->query($query);
            $config['title'] = "Transport Delete";
            if ($this->db->affectedRows() > 0) {
                $config['log_text'] = "[ Transport successfully deleted ]";
                $config['ref_link'] = '';
                $this->session->setFlashdata("op_success", "Transport successfully deleted");
            } else {
                $config['log_text'] = "[ Transport failed to delete ]";
                $config['ref_link'] = '';
                $this->session->setFlashdata("op_error", "Transport failed to delete");
            }
            log_activity($config);
        }
    }
}
