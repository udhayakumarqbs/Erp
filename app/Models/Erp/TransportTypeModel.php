<?php

namespace App\Models\Erp;

use CodeIgniter\Model;

class TransportTypeModel extends Model
{
    protected $table = 'transport_type';
    protected $primaryKey = 'type_id';
    protected $allowedFields = ['type_name', 'created_at', 'created_by'];
    protected $db;
    protected $session;
    public function __construct()
    {
        $this->db = \Config\Database::connect();
        $this->session = \Config\Services::session();
        helper(['erp', 'form']);
    }
    public function get_all_types()
    {
        $query = "SELECT type_id,type_name FROM transport_type";
        $result = $this->db->query($query)->getResultArray();
        return $result;
    }

    public function insert_update_type($type_id, $type_name)
    {

        if (empty($type_id)) {
            $this->insert_type($type_name);
        } else {
            $this->update_type($type_id, $type_name);
        }
    }

    private function insert_type($type_name)
    {
        $created_by = get_user_id();
        $query = "INSERT INTO transport_type(type_name,created_at,created_by) VALUES(?,?,?) ";
        $this->db->query($query, array($type_name, time(), $created_by));
        $id = $this->db->insertId();

        $config['title'] = "Transport Type Insert";
        if (!empty($id)) {
            $config['log_text'] = "[ Transport Type successfully created ]";
            $config['ref_link'] = '';
            $this->session->setFlashdata("op_success", "Transport Type successfully created");
        } else {
            $config['log_text'] = "[ Transport Type failed to create ]";
            $config['ref_link'] = '';
            $this->session->setFlashdata("op_error", "Transport Type failed to create");
        }
        log_activity($config);
    }

    private function update_type($type_id, $type_name)
    {
        $query = "UPDATE transport_type SET type_name=? WHERE type_id=$type_id ";
        $this->db->query($query, array($type_name));

        $config['title'] = "Transport Type Update";
        if ($this->db->affectedRows() > 0) {
            $config['log_text'] = "[ Transport Type successfully updated ]";
            $config['ref_link'] = '';
            $this->session->setFlashdata("op_success", "Transport Type successfully updated");
        } else {
            $config['log_text'] = "[ Transport Type failed to update ]";
            $config['ref_link'] = '';
            $this->session->setFlashdata("op_error", "Transport Type failed to update");
        }
        log_activity($config);
    }

    public function get_dtconfig_types()
    {
        $config = array(
            "columnNames" => ["sno", "type name", "action"],
            "sortable" => [0, 1, 0],
        );
        return json_encode($config);
    }

    public function get_datatable_types()
    {
        $columns = array(
            "type name" => "type_name",
        );
        $limit = $_GET['limit'];
        $offset = $_GET['offset'];
        $search = $_GET['search'] ?? "";
        $orderby = isset($_GET['orderby']) ? strtoupper($_GET['orderby']) : "";
        $ordercol = isset($_GET['ordercol']) ? $columns[$_GET['ordercol']] : "";
        $query = "SELECT type_id,type_name FROM transport_type ";
        $count = "SELECT COUNT(type_id) AS total FROM transport_type ";
        $where = false;

        if (!empty($search)) {
            if ($where) {
                $query .= " AND type_name LIKE '%" . $search . "%' ";
                $count .= " AND type_name LIKE '%" . $search . "%' ";
            } else {
                $query .= " WHERE type_name LIKE '%" . $search . "%' ";
                $count .= " WHERE type_name LIKE '%" . $search . "%' ";
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
                                <li><a href="#" data-ajax-url="' . url_to('erp.transport.ajaxfetchtype', $r['type_id']) . '" title="Edit" class="bg-success modalBtn "><i class="fa fa-pencil"></i> </a></li>
                                <li><a href="' . url_to('erp.transport.typedelete', $r['type_id']) . '" title="Delete" class="bg-danger del-confirm"><i class="fa fa-trash"></i> </a></li>
                            </ul>
                        </div>
                    </div>';
            array_push(
                $m_result,
                array(
                    $r['type_id'],
                    $sno,
                    $r['type_name'],
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
    public function get_type_export($type)
    {
        $columns = array(
            "type name" => "type_name",
        );
        $limit = $_GET['limit'];
        $offset = $_GET['offset'];
        $search = $_GET['search'] ?? "";
        $orderby = isset($_GET['orderby']) ? strtoupper($_GET['orderby']) : "";
        $ordercol = isset($_GET['ordercol']) ? $columns[$_GET['ordercol']] : "";
        $query = "SELECT type_name FROM transport_type ";
        $where = false;

        if (!empty($search)) {
            if ($where) {
                $query .= " AND type_name LIKE '%" . $search . "%' ";
            } else {
                $query .= " WHERE type_name LIKE '%" . $search . "%' ";
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
