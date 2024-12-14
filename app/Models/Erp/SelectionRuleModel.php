<?php

namespace App\Models\Erp;

use CodeIgniter\Model;

class SelectionRuleModel extends Model
{
    protected $table = 'selection_rule';
    protected $primaryKey = 'rule_id';
    protected $allowedFields = ['rule_name', 'description', 'created_at', 'created_by'];

    protected $useTimestamps = true;
    protected $session;
    protected $db;
    public function __construct()
    {
        $this->session = \Config\Services::session();
        $this->db = \Config\Database::connect();
    }

    public function get_dtconfig_selectionrule()
    {
        $config = array(
            "columnNames" => ["sno", "rule name", "created on", "action"],
            "sortable" => [0, 1, 1, 0],
        );
        return json_encode($config);
    }

    public function get_selectionrule_datatable()
    {
        $columns = array(
            "rule name" => "rule_name",
            "created on" => "created_at"
        );
        $limit = $_GET['limit'];
        $offset = $_GET['offset'];
        $search = $_GET['search'] ?? "";
        $orderby = isset($_GET['orderby']) ? strtoupper($_GET['orderby']) : "";
        $ordercol = isset($_GET['ordercol']) ? $columns[$_GET['ordercol']] : "";

        $query = "SELECT rule_id,rule_name,FROM_UNIXTIME(created_at,'%Y-%m-%d') AS created_at FROM selection_rule ";
        $count = "SELECT COUNT(rule_id) AS total FROM selection_rule ";
        $where = false;

        if (!empty($search)) {
            if (!$where) {
                $query .= " WHERE rule_name LIKE '%" . $search . "%' ";
                $count .= " WHERE rule_name LIKE '%" . $search . "%' ";
                $where = true;
            } else {
                $query .= " AND rule_name LIKE '%" . $search . "%' ";
                $count .= " AND rule_name LIKE '%" . $search . "%' ";
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
                                <li><a href="' . base_url() . 'erp/supplier/selection-rules-edit/' . $r['rule_id'] . '" title="Edit" class="bg-success modalBtn"><i class="fa fa-pencil"></i> </a></li>
                                <li><a href="' . base_url() . 'erp/supplier/selection-rules-delete/' . $r['rule_id'] . '" title="Delete" class="bg-danger del-confirm"><i class="fa fa-trash"></i> </a></li>
                            </ul>
                        </div>
                    </div>';
            array_push(
                $m_result,
                array(
                    $r['rule_id'],
                    $sno,
                    $r['rule_name'],
                    $r['created_at'],
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

    public function insert_selection_rule($rule_name, $description)
    {
        $inserted = false;
        // $rule_name = $this->input->post("rule_name");
        // $description = $this->input->post("description");
        $created_by = get_user_id();
        $this->db->transBegin();
        $query = "INSERT INTO selection_rule(rule_name,description,created_at,created_by) VALUES(?,?,?,?) ";
        $this->db->query($query, array($rule_name, $description, time(), $created_by));
        $rule_id = $this->db->insertId();

        if (empty($rule_id)) {
            $this->session->setFlashdata("op_error", "Selection Rule failed to create");
            return $inserted;
        }

        $segment_ids = array();
        foreach ($_POST as $key => $value) {
            if (stripos($key, "segment_id_") === 0) {
                array_push($segment_ids, $value);
            }
        }

        $query = "INSERT INTO selection_rule_segment(rule_id,segment_id,segment_value_idx,above_below,exclude) VALUES(?,?,?,?,?) ";
        foreach ($segment_ids as $id) {
            $this->db->query($query, array($rule_id, $id, $_POST['segment_value_idx_' . $id], $_POST['above_below_' . $id], $_POST['exclude_' . $id] ?? 0));
            if (empty($this->db->insertId())) {
                $this->db->transRollback();
                break;
            }
        }
        $this->db->transComplete();
        $config['title'] = "Selection Rule Insert";
        if ($this->db->transStatus() === FALSE) {
            $this->db->transRollback();
            $config['log_text'] = "[ Selection Rule failed to create ]";
            $config['ref_link'] = "";
            $this->session->setFlashdata("op_error", "Selection Rule failed to create");
        } else {
            $inserted = true;
            $this->db->transCommit();
            $config['log_text'] = "[ Selection Rule successfully created ]";
            $config['ref_link'] = "";
            $this->session->setFlashdata("op_success", "Selection Rule successfully created");
        }
        log_activity($config);
        return $inserted;
    }


    public function get_selectionrule_export($type)
    {
        $columns = array(
            "rule name" => "rule_name",
            "created on" => "created_at"
        );
        $limit = $_GET['limit'];
        $offset = $_GET['offset'];
        $search = $_GET['search'] ?? "";
        $orderby = isset($_GET['orderby']) ? strtoupper($_GET['orderby']) : "";
        $ordercol = isset($_GET['ordercol']) ? $columns[$_GET['ordercol']] : "";

        $query = "SELECT rule_name,FROM_UNIXTIME(created_at,'%Y-%m-%d') AS created_at,description FROM selection_rule ";
        $where = false;

        if (!empty($search)) {
            if (!$where) {
                $query .= " WHERE rule_name LIKE '%" . $search . "%' ";
                $where = true;
            } else {
                $query .= " AND rule_name LIKE '%" . $search . "%' ";
            }
        }

        if (!empty($ordercol) && !empty($orderby)) {
            $query .= " ORDER BY " . $ordercol . " " . $orderby;
        }

        $result = $this->db->query($query)->getResultArray();
        return $result;
    }

    public function update_selection_rule($rule_id, $rule_name, $description)
    {
        $updated = false;
        $this->db->transBegin();
        $query = "UPDATE selection_rule SET rule_name=? , description=? WHERE rule_id=$rule_id";
        $this->db->query($query, array($rule_name, $description));

        $segment_ids = array();
        foreach ($_POST as $key => $value) {
            if (stripos($key, "segment_id_") === 0) {
                array_push($segment_ids, $value);
            }
        }

        $query = "SELECT selection_rule_segment(?,?,?,?,?) AS ret_code ";
        foreach ($segment_ids as $id) {
            $retcode = $this->db->query($query, array($rule_id, $id, $_POST['above_below_' . $id], $_POST['exclude_' . $id] ?? 0, $_POST['segment_value_idx_' . $id]))->getRow()->ret_code;
        }
        $this->db->transComplete();
        $config['title'] = "Selection Rule Update";
        if ($this->db->transStatus() === FALSE) {
            $this->db->transRollback();
            $config['log_text'] = "[ Selection Rule failed to update ]";
            $config['ref_link'] = "";
            $this->session->setFlashdata("op_error", "Selection Rule failed to update");
        } else {
            $updated = true;
            $this->db->transCommit();
            $config['log_text'] = "[ Selection Rule successfully updated ]";
            $config['ref_link'] = "";
            $this->session->setFlashdata("op_success", "Selection Rule successfully updated");
        }
        log_activity($config);
        return $updated;
    }

    public function get_all_segments()
    {
        $query = "SELECT segment_id,segment_key,segment_value FROM supplier_segments ";
        $result = $this->db->query($query)->getResultArray();
        return $result;
    }

    public function get_selectionrule_by_id($rule_id)
    {
        $query = "SELECT rule_id, rule_name, description FROM selection_rule WHERE rule_id = ?";
        $result = $this->db->query($query, [$rule_id])->getRow();
        return $result;
    }
}
