<?php

namespace App\Models\Erp;

use CodeIgniter\Model;

class UnitModel extends Model
{
    protected $table      = 'units';
    protected $primaryKey = 'unit_id';

    protected $allowedFields = ['unit_name', 'created_at', 'created_by'];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = '';


    public $session;
    public $db;
    public function __construct()
    {
        $this->session = \Config\Services::session();
        $this->db = \Config\Database::connect();
    }

    public function get_units()
    {
        $query = "SELECT unit_id,unit_name FROM units";
        $result = $this->db->query($query)->getResultArray();
        return $result;
    }

    public function insert_update_unit($unit_id,$unit_name)
    {
        if (empty($unit_id)) {
            $this->insert_unit($unit_name);
        } else {
            $this->update_unit($unit_id,$unit_name);
        }
    }

    private function insert_unit($unit_name)
    {
        $created_by = get_user_id();
        $query = "INSERT INTO units(unit_name,created_at,created_by) VALUES(?,?,?) ";
        $this->db->query($query, array($unit_name, time(), $created_by));

        $config['title'] = "Unit Insert";
        if (!empty($this->db->insertId())) {
            $config['log_text'] = "[ Unit successfully created ]";
            $config['ref_link'] = 'erp/inventory/units';
            $this->session->setFlashdata("op_success", "Unit successfully created");
        } else {
            $config['log_text'] = "[ Unit failed to create ]";
            $config['ref_link'] = 'erp/inventory/units';
            $this->session->setFlashdata("op_error", "Unit failed to create");
        }
        log_activity($config);
    }

    private function update_unit($unit_id,$unit_name)
    {

        $query = "UPDATE units SET unit_name=? WHERE unit_id=$unit_id ";
        $this->db->query($query, array($unit_name));

        $config['title'] = "Unit Update";
        if ($this->db->affectedRows() > 0) {
            $config['log_text'] = "[ Unit successfully updated ]";
            $config['ref_link'] = 'erp/inventory/units';
            $this->session->setFlashdata("op_success", "Unit successfully updated");
        } else {
            $config['log_text'] = "[ Unit failed to update ]";
            $config['ref_link'] = 'erp/inventory/units';
            $this->session->setFlashdata("op_error", "Unit failed to update");
        }
        log_activity($config);
    }


    public function get_dtconfig_units()
    {
        $config = array(
            "columnNames" => ["sno", "unit name", "action"],
            "sortable" => [0, 1, 0]
        );
        return json_encode($config);
    }

    public function get_units_datatable()
    {
        $columns = array(
            "unit name" => "unit_name",
        );
        $limit = $_GET['limit'];
        $offset = $_GET['offset'];
        $search = $_GET['search'] ?? "";
        $orderby = isset($_GET['orderby']) ? strtoupper($_GET['orderby']) : "";
        $ordercol = isset($_GET['ordercol']) ? $columns[$_GET['ordercol']] : "";

        $query = "SELECT unit_id,unit_name FROM units ";
        $count = "SELECT COUNT(unit_id) AS total FROM units ";
        $where = false;

        if (!empty($search)) {
            if (!$where) {
                $query .= " WHERE  unit_name LIKE '%" . $search . "%' ";
                $count .= " WHERE  unit_name LIKE '%" . $search . "%' ";
                $where = true;
            } else {
                $query .= " AND  unit_name LIKE '%" . $search . "%' ";
                $count .= " AND  unit_name LIKE '%" . $search . "%' ";
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
                                <li><a href="#" data-ajax-url="' . url_to('erp.inventory.ajaxfetchunit', $r['unit_id']) . '" title="Edit" class="bg-success modalBtn"><i class="fa fa-pencil"></i> </a></li>
                                <li><a href="' . url_to('erp.inventory.unitdelete', $r['unit_id']) . '" title="Delete" class="bg-danger del-confirm"><i class="fa fa-trash"></i> </a></li>
                            </ul>
                        </div>
                    </div>';
            array_push(
                $m_result,
                array(
                    $r['unit_id'],
                    $sno,
                    $r['unit_name'],
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

    public function delete_unit($unit_id)
    {
        $check1 = $this->db->query("SELECT unit_id FROM raw_materials WHERE unit_id=$unit_id LIMIT 1")->getRow();
        if (empty($check1)) {
            $check2 = $this->db->query("SELECT unit_id FROM semi_finished WHERE unit_id=$unit_id LIMIT 1")->getRow();
            if (empty($check2)) {
                $check3 = $this->db->query("SELECT unit_id FROM finished_goods WHERE unit_id=$unit_id LIMIT 1")->getRow();
                if (empty($check3)) {
                    $query = "DELETE FROM units WHERE unit_id=$unit_id";
                    $this->db->query($query);
                    $config['title'] = "Unit Delete";
                    if ($this->db->affectedRows() > 0) {
                        $config['log_text'] = "[ Unit successfully deleted ]";
                        $config['ref_link'] = 'erp/inventory/units';
                        $this->session->setFlashdata("op_success", "Unit successfully deleted");
                    } else {
                        $config['log_text'] = "[ Unit failed to delete ]";
                        $config['ref_link'] = 'erp/inventory/units';
                        $this->session->setFlashdata("op_error", "Unit failed to delete");
                    }
                    log_activity($config);
                } else {
                    $this->session->setFlashdata("op_error", "Unit is already in use");
                }
            } else {
                $this->session->setFlashdata("op_error", "Unit is already in use");
            }
        } else {
            $this->session->setFlashdata("op_error", "Unit is already in use");
        }
    }

    public function get_units_export($type)
    {
        $columns = array(
            "unit name" => "unit_name",
        );
        $limit = $_GET['limit'];
        $offset = $_GET['offset'];
        $search = $_GET['search'] ?? "";
        $orderby = isset($_GET['orderby']) ? strtoupper($_GET['orderby']) : "";
        $ordercol = isset($_GET['ordercol']) ? $columns[$_GET['ordercol']] : "";

        $query = "SELECT unit_name FROM units ";
        $where = false;

        if (!empty($search)) {
            if (!$where) {
                $query .= " WHERE  unit_name LIKE '%" . $search . "%' ";
                $where = true;
            } else {
                $query .= " AND  unit_name LIKE '%" . $search . "%' ";
            }
        }

        if (!empty($ordercol) && !empty($orderby)) {
            $query .= " ORDER BY " . $ordercol . " " . $orderby;
        }
        $result = $this->db->query($query)->getResultArray();
        return $result;
    }
}
