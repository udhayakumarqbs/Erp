<?php

namespace App\Models\Erp;

use CodeIgniter\Model;

class StockAlertsModel extends Model
{
    protected $table      = 'stock_alerts';
    protected $primaryKey = 'stock_alert_id';

    protected $allowedFields = ['related_to', 'related_id', 'alert_qty_level', 'alert_before', 'recurring'];

    protected $useTimestamps = false;

    public $session;
    public $db;
    public function __construct()
    {
        $this->session = \Config\Services::session();
        $this->db = \Config\Database::connect();
    }


    public function get_stock_alert_for_view($type, $related_id)
    {
        $query = "SELECT * FROM stock_alerts WHERE related_to=? AND related_id=? ";
        $result = $this->db->query($query, array($type, $related_id))->getRow();
        return $result;
    }

    public function insert_update_stockalert($type, $related_id, $link, $post)
    {
        $check = $this->db->query("SELECT stock_alert_id FROM stock_alerts WHERE related_to='$type' AND related_id=$related_id ")->getRow();
        $alert_qty_level = $post["alert_qty_level"];
        $alert_before = $post["alert_before"];
        $recurring = $post["recurring"] ?? 0;
        $config = array();
        $config['ref_link'] = $link;
        if (!empty($check)) {
            $stock_alert_id = $check->stock_alert_id;
            $query = "UPDATE stock_alerts SET alert_qty_level=? , alert_before=? , recurring=? WHERE stock_alert_id=$stock_alert_id";
            $this->db->query($query, array($alert_qty_level, $alert_before, $recurring));
            $config['title'] = "Stock Alert Update";
            if ($this->db->affectedRows() > 0) {
                $config['log_text'] = "[ Stock Alert successfully updated ]";
                $this->session->setFlashdata("op_success", "Stock Alert successfully updated");
            } else {
                $config['log_text'] = "[ Stock Alert failed to update ]";
                $this->session->setFlashdata("op_error", "Stock Alert failed to update");
            }
        } else {
            $query = "INSERT INTO stock_alerts(alert_qty_level,alert_before,recurring,related_to,related_id) VALUES(?,?,?,?,?) ";
            $this->db->query($query, array($alert_qty_level, $alert_before, $recurring, $type, $related_id));
            $config['title'] = "Stock Alert Insert";
            if (!empty($this->db->insertId())) {
                $config['log_text'] = "[ Stock Alert successfully created ]";
                $this->session->setFlashdata("op_success", "Stock Alert successfully created");
            } else {
                $config['log_text'] = "[ Stock Alert failed to create ]";
                $this->session->setFlashdata("op_error", "Stock Alert failed to create");
            }
        }
        log_activity($config);
    }
}
