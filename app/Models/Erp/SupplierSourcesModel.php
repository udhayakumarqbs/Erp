<?php

namespace App\Models\Erp;

use CodeIgniter\Model;

class SupplierSourcesModel extends Model
{
    protected $table = 'supplier_sources';
    protected $primaryKey = 'source_id';
    protected $allowedFields = ['source_name', 'created_at', 'created_by'];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = null;

    protected $session;
    protected $db;
    public function __construct()
    {
        $this->session = \Config\Services::session();
        $this->db = \Config\Database::connect();
    }
    public function get_supplier_sources()
    {
        return  $this->builder()->select('source_id, source_name')->get()->getResultArray();
    }

    public function get_dtconfig_sources()
    {
        $config = array(
            "columnNames" => ["sno", "source name", "action"],
            "sortable" => [0, 1, 0],
        );
        return json_encode($config);
    }

    public function insert_update_source($source_id, $source_name)
    {

        if (empty($source_id)) {
            $this->insert_source($source_name);
        } else {
            $this->update_source($source_id, $source_name);
        }
    }

    public function insert_source($source_name)
    {
        // $source_name = $this->request->getPost("source_name");
        $created_by = get_user_id();

        $query = "INSERT INTO supplier_sources(source_name, created_at, created_by) VALUES(?, ?, ?) ";
        $this->db->query($query, [$source_name, time(), $created_by]);

        $config['title'] = "Supplier Source Insert";
        if (!empty($this->db->insertID())) {
            $config['log_text'] = "[ Supplier Source successfully created ]";
            $config['ref_link'] = "erp/supplier/sources";
            $this->session->setFlashdata("op_success", "Supplier Source successfully created");
        } else {
            $config['log_text'] = "[ Supplier Source failed to create ]";
            $config['ref_link'] = "erp/supplier/sources";
            $this->session->setFlashdata("op_error", "Supplier Source failed to create");
        }
        log_activity($config);
    }

    public function update_source($source_id, $source_name)
    {
        // $source_name = $this->request->getPost("source_name");
        $created_by = get_user_id();

        $query = "UPDATE supplier_sources SET source_name=? WHERE source_id=?";
        $this->db->query($query, [$source_name, $source_id]);

        $config['title'] = "Supplier Source Update";
        if ($this->db->affectedRows() > 0) {
            $config['log_text'] = "[ Supplier Source successfully updated ]";
            $config['ref_link'] = "erp/supplier/sources";
            $this->session->setFlashdata("op_success", "Supplier Source successfully updated");
        } else {
            $config['log_text'] = "[ Supplier Source failed to update ]";
            $config['ref_link'] = "erp/supplier/sources";
            $this->session->setFlashdata("op_error", "Supplier Source failed to update");
        }
        log_activity($config);
    }

    public function get_datatable_sources()
    {
        $columns = array(
            "source name" => "source_name",
        );
        $limit = $_GET['limit'];
        $offset = $_GET['offset'];
        $search = $_GET['search'] ?? "";
        $orderby = isset($_GET['orderby']) ? strtoupper($_GET['orderby']) : "";
        $ordercol = isset($_GET['ordercol']) ? $columns[$_GET['ordercol']] : "";
        $query = "SELECT source_id,source_name FROM supplier_sources ";
        $count = "SELECT COUNT(source_id) AS total FROM supplier_sources ";

        $where = false;

        if (!empty($search)) {
            if ($where) {
                $query .= " AND source_name LIKE '%" . $search . "%' ";
                $count .= " AND source_name LIKE '%" . $search . "%' ";
            } else {
                $query .= " WHERE source_name LIKE '%" . $search . "%' ";
                $count .= " WHERE source_name LIKE '%" . $search . "%' ";
                $where = true;
            }
        }

        if (!empty($ordercol) && !empty($orderby)) {
            $query .= " ORDER BY " . $ordercol . " " . $orderby;
        }

        $query .= "  LIMIT $offset,$limit ";

        $result = $this->db->query($query)->getResultArray();
        $m_result = array();
        $sno = $offset + 1;
        foreach ($result as $r) {
            $action = '<div class="dropdown tableAction">
                        <a type="button" class="dropBtn HoverA "><i class="fa fa-ellipsis-v"></i></a>
                        <div class="dropdown_container">
                            <ul class="BB_UL flex">
                                <li><a href="#" data-ajax-url="' . base_url() . 'erp/supplier/sources-edit/' . $r['source_id'] . '" title="Edit" class="bg-success modalBtn "><i class="fa fa-pencil"></i> </a></li>
                                <li><a href="' . base_url() . 'erp/supplier/sources-delete/' . $r['source_id'] . '" title="Delete" class="bg-danger del-confirm"><i class="fa fa-trash"></i> </a></li>
                            </ul>
                        </div>
                    </div>';
            array_push(
                $m_result,
                array(
                    $r['source_id'],
                    $sno,
                    $r['source_name'],
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


    public function get_sources_export($type)
    {
        $columns = array(
            "source name" => "source_name",
        );
        $limit = $_GET['limit'];
        $offset = $_GET['offset'];
        $search = $_GET['search'] ?? "";
        $orderby = isset($_GET['orderby']) ? strtoupper($_GET['orderby']) : "";
        $ordercol = isset($_GET['ordercol']) ? $columns[$_GET['ordercol']] : "";
        $query = "SELECT source_name FROM supplier_sources ";
        $where = false;
        if (!empty($search)) {
            if ($where) {
                $query .= " AND source_name LIKE '%" . $search . "%' ";
            } else {
                $query .= " WHERE source_name LIKE '%" . $search . "%' ";
                $where = true;
            }
        }
        if (!empty($ordercol) && !empty($orderby)) {
            $query .= " ORDER BY " . $ordercol . " " . $orderby;
        }
        $result = $this->db->query($query)->getResultArray();
        return $result;
    }
}
