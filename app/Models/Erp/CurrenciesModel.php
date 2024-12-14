<?php

namespace App\Models\Erp;

use CodeIgniter\Model;

class CurrenciesModel extends Model
{
    protected $table = 'currencies';
    protected $primaryKey = 'currency_id';
    protected $allowedFields = [
        'iso_code',
        'symbol',
        'decimal_sep',
        'thousand_sep',
        'place',
        'is_default',
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

    public function insert_update_currency($currency_id, $post)
    {

        if (empty($currency_id)) {
            $this->insert_currency($post);
        } else {
            $this->update_currency($currency_id, $post);
        }
    }

    private function insert_currency($post)
    {
        $iso_code = $post["iso_code"];
        $symbol = trim($post["symbol"]);
        $decimal_sep = $post["decimal_sep"];
        $thousand_sep = $post["thousand_sep"];
        $place = $post["place"];
        $created_by = get_user_id();
        $query = "INSERT INTO currencies(iso_code,symbol,decimal_sep,thousand_sep,place,created_at,created_by) VALUES(?,?,?,?,?,?,?) ";
        $this->db->query($query, array($iso_code, $symbol, $decimal_sep, $thousand_sep, $place, time(), $created_by));

        $config['title'] = "Currency Insert";
        if (!empty($this->db->insertId())) {
            $config['log_text'] = "[ Currency successfully created ]";
            $config['ref_link'] = 'erp/finance/currency/';
            $this->session->setFlashdata("op_success", "Currency successfully created");
        } else {
            $config['log_text'] = "[ Currency failed to create ]";
            $config['ref_link'] = "";
            $this->session->setFlashdata("op_error", "Currency failed to create");
        }
        log_activity($config);
    }

    private function update_currency($currency_id, $post)
    {
        $iso_code = $post["iso_code"];
        $symbol = trim($post["symbol"]);
        $decimal_sep = $post["decimal_sep"];
        $thousand_sep = $post["thousand_sep"];
        $place = $post["place"];
        $created_by = get_user_id();
        $query = "UPDATE currencies SET iso_code=? , symbol=? , decimal_sep=? , thousand_sep=? , place=? WHERE currency_id=$currency_id";
        $this->db->query($query, array($iso_code, $symbol, $decimal_sep, $thousand_sep, $place));

        $config['title'] = "Currency Update";
        if ($this->db->affectedRows() > 0) {
            $config['log_text'] = "[ Currency successfully updated ]";
            $config['ref_link'] = 'erp/finance/currency/';
            $this->session->setFlashdata("op_success", "Currency successfully updated");
        } else {
            $config['log_text'] = "[ Currency failed to update ]";
            $config['ref_link'] = "";
            $this->session->setFlashdata("op_error", "Currency failed to update");
        }
        log_activity($config);
    }

    public function get_dtconfig_currencies()
    {
        $config = array(
            "columnNames" => ["sno", "iso code", "symbol", "place", "action"],
            "sortable" => [0, 1, 0, 0, 0],
        );
        return json_encode($config);
    }

    public function get_currencies_datatable()
    {
        $columns = array(
            "iso code" => "iso_code",
        );
        $limit = $_GET['limit'];
        $offset = $_GET['offset'];
        $search = $_GET['search'] ?? "";
        $orderby = isset($_GET['orderby']) ? strtoupper($_GET['orderby']) : "";
        $ordercol = isset($_GET['ordercol']) ? $columns[$_GET['ordercol']] : "";

        $query = "SELECT currency_id,iso_code,symbol,place,is_default FROM currencies ";
        $count = "SELECT COUNT(currency_id) AS total FROM currencies ";
        $where = false;

        if (!empty($search)) {
            if (!$where) {
                $query .= " WHERE iso_code LIKE '%" . $search . "%' ";
                $count .= " WHERE iso_code LIKE '%" . $search . "%' ";
                $where = true;
            } else {
                $query .= " AND iso_code LIKE '%" . $search . "%' ";
                $count .= " AND iso_code LIKE '%" . $search . "%' ";
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
                            <ul class="BB_UL flex">';
            if ($r['is_default'] == 0) {
                $action .= '<li><a href="' . url_to('erp.finance.currencybaseset', $r['currency_id']) . '" title="Base" class="bg-primary"><i class="fa fa-star"></i> </a></li>';
            }
            $action .= '<li><a href="#" data-ajax-url="' . url_to('erp.finance.ajaxfetchcurrency', $r['currency_id']) . '" title="Edit" class="bg-success modalBtn"><i class="fa fa-pencil"></i> </a></li>
                                <li><a href="' . url_to('erp.finance.currencydelete', $r['currency_id']) . '" title="Delete" class="bg-danger del-confirm"><i class="fa fa-trash"></i> </a></li>
                            </ul>
                        </div>
                    </div>';
            array_push(
                $m_result,
                array(
                    $r['currency_id'],
                    $sno,
                    $r['iso_code'],
                    $r['symbol'],
                    ucfirst($r['place']),
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

    public function get_currencies_export($type)
    {
        $columns = array(
            "iso code" => "iso_code",
        );
        $limit = $_GET['limit'];
        $offset = $_GET['offset'];
        $search = $_GET['search'] ?? "";
        $orderby = isset($_GET['orderby']) ? strtoupper($_GET['orderby']) : "";
        $ordercol = isset($_GET['ordercol']) ? $columns[$_GET['ordercol']] : "";

        $query = "SELECT iso_code,symbol,decimal_sep,thousand_sep,place,CASE WHEN is_default=1 THEN 'Yes' ELSE 'No' END AS base_currency FROM currencies ";
        $where = false;

        if (!empty($search)) {
            if (!$where) {
                $query .= " WHERE iso_code LIKE '%" . $search . "%' ";
                $where = true;
            } else {
                $query .= " AND iso_code LIKE '%" . $search . "%' ";
            }
        }

        if (!empty($ordercol) && !empty($orderby)) {
            $query .= " ORDER BY " . $ordercol . " " . $orderby;
        }
        $result = $this->db->query($query)->getResultArray();
        return $result;
    }

    public function deleteCurrency($currency_id)
    {
        $query = "DELETE FROM currencies WHERE currency_id=$currency_id";
        $this->db->query($query);
        $config['title'] = "Currency Delete";
        if ($this->db->affectedRows() > 0) {
            $config['log_text'] = "[ Currency successfully deleted ]";
            $config['ref_link'] = "erp/finance/journalentry/";
            $this->session->setFlashdata("op_success", "Currency successfully deleted");
        } else {
            $config['log_text'] = "[ Currency failed to delete ]";
            $config['ref_link'] = "erp/finance/journalentry/";
            $this->session->setFlashdata("op_error", "Currency failed to delete");
        }
        log_activity($config);
    }

    public function getCurrencyPlace(){
        return $this->builder()->distinct()->select('place')->get()->getResultArray();
    }

    public function getCurrencySymbolById($currency_id){
        return $this->builder()->select('symbol')->where('currency_id', $currency_id)->get()->getRow();
    }

    public function get_all_currency(){
        $query = "SELECT currency_id as id,iso_code as code,symbol as symbol  FROM currencies GROUP BY symbol";
        $result = $this->db->query($query)->getResultArray();
        return $result;
    }

 
}
