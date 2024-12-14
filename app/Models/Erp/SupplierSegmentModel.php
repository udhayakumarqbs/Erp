<?php

namespace App\Models\Erp;

use CodeIgniter\Model;

class SupplierSegmentModel extends Model
{
    protected $table = 'supplier_segments';
    protected $primaryKey = 'segment_id';

    protected $allowedFields = [
        'segment_key',
        'segment_value',
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

    public function update_supplier_segment($supplier_id)
    {
        $session = \Config\Services::session();
        $query = "SELECT supplier_segment_insert_update(?,?) AS ret_code ";
        $this->db->transBegin();

        $segmentJson = [];
        foreach ($_POST as $key => $value) {
            if (stripos($key, "segment_value_idx_") === 0) {
                $segmentId = substr($key, strlen("segment_value_idx_"));
                $segmentJson[$segmentId] = $value;
            }
        }
        $segmentJson = json_encode($segmentJson, JSON_FORCE_OBJECT);

        $retCode = $this->db->query($query, [$supplier_id, $segmentJson])->getRow()->ret_code;

        if ($this->db->transStatus() === false) {
            $config = [
                'title' => 'Segments For Supplier Update',
                'log_text' => '[ Segments For Supplier failed to update ]',
                'ref_link' => 'erp/supplier/supplier-view/' . $supplier_id,
            ];

            $session->setFlashdata('op_error', 'Segments For Supplier failed to update');
            $this->db->transRollback();
        } else {
            $config = [
                'title' => 'Segments For Supplier Update',
                'log_text' => '[ Segments For Supplier successfully updated ]',
                'ref_link' => 'erp/supplier/supplier-view/' . $supplier_id,
            ];

            $session->setFlashdata('op_success', 'Segments For Supplier successfully updated');
            $this->db->transCommit();
        }

        log_activity($config);
        return $retCode;
    }

    public function get_dtconfig_segments()
    {
        $config = array(
            "columnNames" => ["sno", "segment key", "segment values", "action"],
            "sortable" => [0, 1, 0, 0],
        );
        return json_encode($config);
    }

    public function insert_segment($key, $values)
    {
        $inserted = false;
        array_unshift($values, "Not Applicable");
        $tmp = array();
        $index = 0;
        foreach ($values as $val) {
            $tmp[$index] = $val;
            $index++;
        }
        $json_values = json_encode($tmp, JSON_FORCE_OBJECT);
        $created_by = get_user_id();
        $query = "INSERT INTO supplier_segments(segment_key,segment_value,created_at,created_by) VALUES(?,?,?,?) ";
        $this->db->query($query, array($key, $json_values, time(), $created_by));

        $config['title'] = "Supplier Segment Insert";
        if (!empty($this->db->insertId())) {
            $inserted = true;
            $config['log_text'] = "[ Supplier Segment successfully created ]";
            $config['ref_link'] = "erp/supplier/segments";
            $this->session->setFlashdata("op_success", "Supplier Segment successfully created");
        } else {
            $config['log_text'] = "[ Supplier Segment failed to create ]";
            $config['ref_link'] = "erp/supplier/segments";
            $this->session->setFlashdata("op_error", "Supplier Segment failed to create");
        }
        log_activity($config);
        return $inserted;
    }

    public function update_segment($segment_id, $key, $values)
    {
        $updated = false;
        array_unshift($values, "Not Applicable");
        $tmp = array();
        $index = 0;
        foreach ($values as $val) {
            $tmp[$index] = $val;
            $index++;
        }
        $json_values = json_encode($tmp, JSON_FORCE_OBJECT);
        $this->db->transBegin();
        $query = "CALL supplier_segment_update(?,?,?)";
        $this->db->query($query, array($segment_id, $key, $json_values));
        $this->db->transComplete();

        $config['title'] = "Supplier Segment Update";
        if ($this->db->transStatus() === FALSE) {
            $this->db->transRollback();
            $config['log_text'] = "[ Supplier Segment failed to update ]";
            $config['ref_link'] = "erp/supplier/segments";
            $this->session->setFlashdata("op_error", "Supplier Segment failed to update");
        } else {
            $updated = true;
            $this->db->transCommit();
            $config['log_text'] = "[ Supplier Segment successfully updated ]";
            $config['ref_link'] = "erp/supplier/segments";
            $this->session->setFlashdata("op_success", "Supplier Segment successfully updated");
        }
        log_activity($config);
        return $updated;
    }

    // public function segmentdelete($segment_id)
    // {
    //     if (empty($segment_id)) {
    //         return false;
    //     }
    //     $this->where('segment_id', $segment_id)->delete();
    //     return true;
    // }

    public function segmentdelete($segment_id)
    {
        if (empty($segment_id)) {
            $this->session->setFlashdata('op_error', 'Invalid segment ID');
            return false;
        }

        $this->where('segment_id', $segment_id)->delete();

        if ($this->db->affectedRows() > 0) {
            $this->session->setFlashdata('op_success', 'Segment successfully deleted');
            return true;
        } else {
            $this->session->setFlashdata('op_error', 'Failed to delete segment');
            return false;
        }
    }

    public function get_segment_by_id($segment_id)
    {
        $query = "SELECT segment_id, segment_key, segment_value FROM supplier_segments WHERE segment_id = ?";
        $result = $this->db->query($query, [$segment_id])->getRow();
        return $result;
    }

    public function get_datatable_segments()
    {
        $columns = array(
            "segment key" => "segment_key",
        );
        $limit = $_GET['limit'];
        $offset = $_GET['offset'];
        $search = $_GET['search'] ?? "";
        $orderby = isset($_GET['orderby']) ? strtoupper($_GET['orderby']) : "";
        $ordercol = isset($_GET['ordercol']) ? $columns[$_GET['ordercol']] : "";
        $query = "SELECT segment_id,segment_key,segment_value FROM supplier_segments ";
        $count = "SELECT COUNT(segment_id) AS total FROM supplier_segments";
        $where = false;
        if (!empty($search)) {
            if ($where) {
                $query .= " AND segment_key LIKE '%" . $search . "%' ";
                $count .= " AND segment_key LIKE '%" . $search . "%' ";
            } else {
                $query .= " WHERE segment_key LIKE '%" . $search . "%' ";
                $count .= " WHERE segment_key LIKE '%" . $search . "%' ";
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
                                <li><a href="' . base_url() . 'erp/supplier/segments-edit/' . $r['segment_id'] . '" title="Edit" class="bg-success"><i class="fa fa-pencil"></i> </a></li>
                                <li><a href="' . base_url() . 'erp/supplier/segments-delete/' . $r['segment_id'] . '" title="Delete" class="bg-danger del-confirm"><i class="fa fa-trash"></i> </a></li>
                            </ul>
                        </div>
                    </div>';
            $segment_values = json_decode($r['segment_value'], true);
            $segment_value = "[ " . implode(" , ", $segment_values) . " ]";
            array_push(
                $m_result,
                array(
                    $r['segment_id'],
                    $sno,
                    $r['segment_key'],
                    $segment_value,
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

    public function get_segment_export($type)
    {
        $columns = array(
            "segment key" => "segment_key",
        );
        $limit = $_GET['limit'];
        $offset = $_GET['offset'];
        $search = $_GET['search'] ?? "";
        $orderby = isset($_GET['orderby']) ? strtoupper($_GET['orderby']) : "";
        $ordercol = isset($_GET['ordercol']) ? $columns[$_GET['ordercol']] : "";
        $query = "SELECT segment_key,REPLACE(JSON_EXTRACT(segment_value,'$.*'),'\"','') AS segment_value FROM supplier_segments ";
        $where = false;
        if (!empty($search)) {
            if ($where) {
                $query .= " AND segment_key LIKE '%" . $search . "%' ";
            } else {
                $query .= " WHERE segment_key LIKE '%" . $search . "%' ";
                $where = true;
            }
        }

        if (!empty($ordercol) && !empty($orderby)) {
            $query .= " ORDER BY " . $ordercol . " " . $orderby;
        }

        $result = $this->db->query($query)->getResultArray();
        return $result;
    }

    public function get_all_segments()
    {
        $query = "SELECT segment_id,segment_key,segment_value FROM supplier_segments ";
        $result = $this->db->query($query)->getResultArray();
        return $result;
    }
}
