<?php

namespace App\Models\Erp;

use CodeIgniter\Model;

class ProjectRawMaterialsModel extends Model
{
    protected $table      = 'project_rawmaterials';
    protected $primaryKey = 'project_raw_id';

    protected $allowedFields = [
        'related_to',
        'related_id',
        'project_id',
        'req_qty',
        'req_for_dispatch',
    ];
    protected $session;
    protected $db;
    public function __construct()
    {
        $this->session = \Config\Services::session();
        $this->db = \Config\Database::connect();
    }
    protected $useTimestamps = false;

    public function get_dtconfig_project_rawmaterial()
    {
        $config = array(
            "columnNames" => ["sno", "raw material", "required qty", "requested for dispatch", "action"],
            "sortable" => [0, 1, 1, 0, 0]
        );
        return json_encode($config);
    }

    public function get_project_rawmaterial_datatable($project_id)
    {

        $columns = array(
            "raw material" => "raw_materials.name",
            "required qty" => "req_qty"
        );
        $limit = $_GET['limit'];
        $offset = $_GET['offset'];
        $search = $_GET['search'] ?? "";
        $orderby = isset($_GET['orderby']) ? strtoupper($_GET['orderby']) : "";
        $ordercol = isset($_GET['ordercol']) ? $columns[$_GET['ordercol']] : "";

        $query = "SELECT project_raw_id,CONCAT(raw_materials.name,' ',raw_materials.code) AS raw_material,req_qty,CASE WHEN req_for_dispatch=1 THEN 'Yes' ELSE 'No' END AS req_for_dispatch FROM project_rawmaterials JOIN raw_materials ON project_rawmaterials.related_id=raw_materials.raw_material_id WHERE project_id=$project_id ";
        $count = "SELECT COUNT(project_raw_id) AS total FROM project_rawmaterials JOIN raw_materials ON project_rawmaterials.related_id=raw_materials.raw_material_id WHERE project_id=$project_id ";
        $where = true;

        if (!empty($search)) {
            if (!$where) {
                $query .= " WHERE raw_materials.name LIKE '%" . $search . "%' ";
                $count .= " WHERE raw_materials.name LIKE '%" . $search . "%' ";
                $where = true;
            } else {
                $query .= " AND raw_materials.name LIKE '%" . $search . "%' ";
                $count .= " AND raw_materials.name LIKE '%" . $search . "%' ";
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
            if ($r['req_for_dispatch'] == "No") {
                $action .= '<li><a href="' . url_to('erp.project.reqdispatch', $project_id, $r['project_raw_id']) . '" title="Request Dispatch" class="bg-warning"><i class="fa fa-check"></i> </a></li>
                                        <li><a href="#" data-ajax-url="' . url_to('erp.project.fetchrawmaterialqty', $r['project_raw_id']) . '" title="Edit" class="bg-success modalBtn"><i class="fa fa-pencil"></i> </a></li>
                                        <li><a href="' . url_to('erp.project.rawmaterialdelete', $project_id, $r['project_raw_id']) . '" title="Delete" class="bg-danger del-confirm"><i class="fa fa-trash"></i> </a></li>';
            }
            $action .= '</ul>
                        </div>
                    </div>';
            array_push(
                $m_result,
                array(
                    $r['project_raw_id'],
                    $sno,
                    $r['raw_material'],
                    $r['req_qty'],
                    $r['req_for_dispatch'],
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

    public function insert_rawmaterial($project_id, $related_id, $req_qty)
    {
        $query = "SELECT project_rawmaterial_insert(?,?,?,?) AS error_flag ";
        $error = $this->db->query($query, array($related_id, $req_qty, $project_id, 1))->getRow()->error_flag;
        $config['title'] = "Project Rawmaterials Insert";
        if ($error == 1) {
            $config['log_text'] = "[ Project Rawmaterials failed to insert ]";
            $config['ref_link'] = url_to('erp.project.projectview', $project_id);
            $this->session->setFlashdata("op_error", "Project Rawmaterials failed to insert");
        } else {
            $config['log_text'] = "[ Project Rawmaterials successfully inserted ]";
            $config['ref_link'] = url_to('erp.project.projectview', $project_id);
            $this->session->setFlashdata("op_success", "Project Rawmaterials successfully inserted");
        }
        log_activity($config);
    }

    public function update_rawmaterial($project_id, $project_raw_id, $req_qty)
    {
        $query = "UPDATE project_rawmaterials SET req_qty=? WHERE project_raw_id=$project_raw_id ";
        $error = $this->db->query($query, array($req_qty));
        $config['title'] = "Project Rawmaterials Update";
        if ($this->db->affectedRows() > 0) {
            $config['log_text'] = "[ Project Rawmaterials successfully updated ]";
            $config['ref_link'] = url_to('erp.project.projectview', $project_id);
            $this->session->setFlashdata("op_success", "Project Rawmaterials successfully updated");
        } else {
            $config['log_text'] = "[ Project Rawmaterials failed to update ]";
            $config['ref_link'] = url_to('erp.project.projectview', $project_id);
            $this->session->setFlashdata("op_error", "Project Rawmaterials failed to update");
        }
        log_activity($config);
    }

    public function request_dispatch($project_id, $project_raw_id)
    {
        $query = "SELECT req_qty,related_id FROM project_rawmaterials WHERE project_raw_id=$project_raw_id ";
        $result = $this->db->query($query)->getRow();
        $req_qty = $result->req_qty;
        $related_id = $result->related_id;
        $query = "SELECT stock_id,quantity FROM stocks WHERE related_to='raw_material' AND related_id=$related_id AND quantity<>0 ORDER BY quantity ASC ";
        $result = $this->db->query($query)->getResultArray();
        if (!empty($result)) {
            $avail_stock = 0;
            $stock = array();
            foreach ($result as $row) {
                array_push($stock, array(
                    "stock_id" => $row['stock_id'],
                    "quantity" => $row['quantity'],
                    "need_to_update" => false,
                    "balance" => $row['quantity']
                ));
                $avail_stock += $row['quantity'];
            }
            if ($req_qty > $avail_stock) {
                $this->session->setFlashdata("op_error", "Rawmaterial is out of stock: Avail stock " . $avail_stock);
            } else {
                for ($i = 0; $i < count($stock); $i++) {
                    $qty = $stock[$i]['quantity'];
                    $req_qty -= $qty;
                    $stock[$i]['need_to_update'] = true;
                    if ($req_qty == 0) {
                        $stock[$i]['balance'] = 0;
                        break;
                    } else if ($req_qty <= -1) {
                        $stock[$i]['balance'] = -1 * $req_qty;
                        break;
                    } else {
                        $stock[$i]['balance'] = 0;
                    }
                }
                $this->db->transBegin();
                $query1 = "INSERT INTO stock_entry(entry_type,stock_id,qty,project_id,created_at) VALUES(?,?,?,?,?) ";
                $query2 = "UPDATE stocks SET quantity=? WHERE stock_id=? ";
                $op_failed = false;
                foreach ($stock as $s) {
                    if (!$s['need_to_update']) {
                        break;
                    } else {
                        $qty_to_pick = $s['quantity'] - $s['balance'];
                        $this->db->query($query1, array(3, $s['stock_id'], $qty_to_pick, $project_id, date('Y-m-d')));
                        if (empty($this->db->insertId())) {
                            $this->db->transRollback();
                            $op_failed = true;
                            break;
                        }
                        $this->db->query($query2, array($s['balance'], $s['stock_id']));
                        if ($this->db->affectedRows() <= 0) {
                            $this->db->transRollback();
                            $op_failed = true;
                            break;
                        }
                    }
                }
                if (!$op_failed) {
                    $query = "UPDATE project_rawmaterials SET req_for_dispatch=1 WHERE project_raw_id=$project_raw_id ";
                    $this->db->query($query);
                    $this->db->transComplete();
                    $config['title'] = "Project Rawmaterials Dispatch";
                    if ($this->db->transStatus() === FALSE) {
                        $this->db->transRollback();
                        $config['log_text'] = "[ Project Rawmaterials Dispatch failed ]";
                        $config['ref_link'] = url_to('erp.project.projectview', $project_id);
                        $this->session->setFlashdata("op_error", "Project Rawmaterials Dispatch failed");
                    } else {
                        $this->db->transCommit();
                        $config['log_text'] = "[ Project Rawmaterials Dispatch successfully done ]";
                        $config['ref_link'] = url_to('erp.project.projectview', $project_id);
                        $this->session->setFlashdata("op_success", "Project Rawmaterials Dispatch successfully done");
                    }
                    log_activity($config);
                } else {
                    $this->session->setFlashdata("op_error", "Project Rawmaterials Dispatch failed");
                }
            }
        } else {
            $this->session->setFlashdata("op_error", "Rawmaterial is out of stock");
        }
    }

    public function delete_rawmaterial($project_id, $project_raw_id)
    {
        $query = "DELETE FROM project_rawmaterials WHERE project_raw_id=$project_raw_id";
        $this->db->query($query);
        $config['title'] = "Project Rawmaterials Delete";
        if ($this->db->affectedRows() > 0) {
            $config['log_text'] = "[ Project Rawmaterials successfully deleted ]";
            $config['ref_link'] = url_to('erp.project.projectview', $project_id);
            $this->session->setFlashdata("op_success", "Project Rawmaterials successfully deleted");
        } else {
            $config['log_text'] = "[ Project Rawmaterials failed to delete ]";
            $config['ref_link'] = url_to('erp.project.projectview', $project_id);
            $this->session->setFlashdata("op_error", "Project Rawmaterials failed to delete");
        }
        log_activity($config);
    }
}
