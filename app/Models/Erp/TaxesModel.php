<?php

namespace App\Models\Erp;

use CodeIgniter\Model;

class TaxesModel extends Model
{
    protected $table = 'taxes';
    protected $primaryKey = 'tax_id';

    protected $allowedFields = ['tax_name', 'percent', 'created_at', 'created_by'];

    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = '';
    public $db;
    public $session;

    public function __construct()
    {
        $this->db = \Config\Database::connect();
        $this->session = \Config\Services::session();
    }
    public function get_taxes()
    {
        $query = "SELECT tax_id,CONCAT(tax_name,' ',percent,'%') AS tax_name FROM taxes";
        $result = $this->db->query($query)->getResultArray();
        return $result;
    }

    public function insert_update_tax($tax_id, $post)
    {
        if (empty($tax_id)) {
            $this->insert_tax($post);
        } else {
            $this->update_tax($tax_id, $post);
        }
    }

    private function insert_tax($post)
    {
        $tax_name = $post["tax_name"];
        $tax_percent = $post["tax_percent"];
        $created_by = get_user_id();
        $query = "INSERT INTO taxes(tax_name,percent,created_at,created_by) VALUES(?,?,?,?) ";
        $this->db->query($query, array($tax_name, $tax_percent, time(), $created_by));

        $config['title'] = "Tax Insert";
        if (!empty($this->db->insertId())) {
            $config['log_text'] = "[ Tax successfully created ]";
            $config['ref_link'] = 'erp/finance/tax/';
            $this->session->setFlashdata("op_success", "Tax successfully created");
        } else {
            $config['log_text'] = "[ Tax failed to create ]";
            $config['ref_link'] = "";
            $this->session->setFlashdata("op_error", "Tax failed to create");
        }
        log_activity($config);
    }

    private function update_tax($tax_id, $post)
    {
        $tax_name = $post["tax_name"];
        $tax_percent = $post["tax_percent"];
        $query = "UPDATE taxes SET tax_name=? , percent=?  WHERE tax_id=$tax_id ";
        $this->db->query($query, array($tax_name, $tax_percent));

        $config['title'] = "Tax Update";
        if ($this->db->affectedRows() > 0) {
            $config['log_text'] = "[ Tax successfully updated ]";
            $config['ref_link'] = 'erp/finance/tax/';
            $this->session->setFlashdata("op_success", "Tax successfully updated");
        } else {
            $config['log_text'] = "[ Tax failed to update ]";
            $config['ref_link'] = "";
            $this->session->setFlashdata("op_error", "Tax failed to update");
        }
        log_activity($config);
    }

    public function get_dtconfig_taxes()
    {
        $config = array(
            "columnNames" => ["sno", "name", "percent", "action"],
            "sortable" => [0, 1, 0, 0],
        );
        return json_encode($config);
    }

    public function get_taxes_datatable()
    {
        $columns = array(
            "name" => "tax_name",
        );
        $limit = $_GET['limit'];
        $offset = $_GET['offset'];
        $search = $_GET['search'] ?? "";
        $orderby = isset($_GET['orderby']) ? strtoupper($_GET['orderby']) : "";
        $ordercol = isset($_GET['ordercol']) ? $columns[$_GET['ordercol']] : "";

        $query = "SELECT tax_id,tax_name,percent FROM taxes ";
        $count = "SELECT COUNT(tax_id) AS total FROM taxes ";
        $where = false;

        if (!empty($search)) {
            if (!$where) {
                $query .= " WHERE tax_name LIKE '%" . $search . "%' ";
                $count .= " WHERE tax_name LIKE '%" . $search . "%' ";
                $where = true;
            } else {
                $query .= " AND tax_name LIKE '%" . $search . "%' ";
                $count .= " AND tax_name LIKE '%" . $search . "%' ";
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
                                <li><a href="#" data-ajax-url="' . url_to('erp.finance.ajaxfetchtax', $r['tax_id']) . '" title="Edit" class="bg-success modalBtn"><i class="fa fa-pencil"></i> </a></li>
                                <li><a href="' . url_to('erp.finance.taxdelete', $r['tax_id']) . '" title="Delete" class="bg-danger del-confirm"><i class="fa fa-trash"></i> </a></li>
                            </ul>
                        </div>
                    </div>';
            array_push(
                $m_result,
                array(
                    $r['tax_id'],
                    $sno,
                    $r['tax_name'],
                    $r['percent'],
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

    public function get_taxes_export($type)
    {
        $columns = array(
            "name" => "tax_name",
        );
        $limit = $_GET['limit'];
        $offset = $_GET['offset'];
        $search = $_GET['search'] ?? "";
        $orderby = isset($_GET['orderby']) ? strtoupper($_GET['orderby']) : "";
        $ordercol = isset($_GET['ordercol']) ? $columns[$_GET['ordercol']] : "";

        $query = "SELECT tax_name,percent FROM taxes ";
        $where = false;

        if (!empty($search)) {
            if (!$where) {
                $query .= " WHERE tax_name LIKE '%" . $search . "%' ";
                $where = true;
            } else {
                $query .= " AND tax_name LIKE '%" . $search . "%' ";
            }
        }

        if (!empty($ordercol) && !empty($orderby)) {
            $query .= " ORDER BY " . $ordercol . " " . $orderby;
        }
        $result = $this->db->query($query)->getResultArray();
        return $result;
    }

    public function deleteTax($tax_id)
    {
        $query = "DELETE FROM taxes WHERE tax_id=$tax_id";
        $this->db->query($query);
        $config['title'] = "Tax Delete";
        if ($this->db->affectedRows() > 0) {
            $config['log_text'] = "[ Tax successfully deleted ]";
            $config['ref_link'] = 'erp/finance/tax/';
            $this->session->setFlashdata("op_success", "Tax successfully deleted");
        } else {
            $config['log_text'] = "[ Tax failed to delete ]";
            $config['ref_link'] = 'erp/finance/tax/';
            $this->session->setFlashdata("op_error", "Tax failed to delete");
        }
        log_activity($config);
    }

    public function Taxes()
    {
        return $this->builder()->select()->get()->getResultArray();
    }

    public function getTaxbyid($id)
    {
        return $this->builder()->select('percent')->where("tax_id", $id)->get()->getRow();

    }
}
