<?php

namespace App\Models\Erp;

use CodeIgniter\Model;
use Exception;

class SemiFinishedModel extends Model
{
    protected $table      = 'semi_finished';
    protected $primaryKey = 'semi_finished_id';

    protected $allowedFields = ['name', 'short_desc', 'long_desc', 'group_id', 'code', 'unit_id', 'brand_id', 'created_at', 'created_by'];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = '';


    public $session;
    public $db;
    public $attachmentModel;
    public function __construct()
    {
        $this->attachmentModel = new AttachmentModel();
        $this->session = \Config\Services::session();
        $this->db = \Config\Database::connect();
    }


    public function get_dtconfig_semifinished()
    {
        $config = array(
            "columnNames" => ["sno", "name", "code", "group", "unit", "brand", "warehouses", "action"],
            "sortable" => [0, 1, 1, 0, 0, 0, 0, 0],
            "filters" => ["group", "unit", "brand", "warehouse"]
        );
        return json_encode($config);
    }




    public function get_semifinished_datatable()
    {
        $columns = array(
            "name" => "name",
            "code" => "code",
            "group" => "erp_groups.group_id",
            "unit" => "units.unit_id",
            "brand" => "brands.brand_id",
            "warehouse" => "warehouses.warehouse_id"
        );
        $limit = $_GET['limit'];
        $offset = $_GET['offset'];
        $search = $_GET['search'] ?? "";
        $orderby = isset($_GET['orderby']) ? strtoupper($_GET['orderby']) : "";
        $ordercol = isset($_GET['ordercol']) ? $columns[$_GET['ordercol']] : "";

        $query = "SELECT semi_finished_id,semi_finished.name,code,erp_groups.group_name,units.unit_name,brands.brand_name,GROUP_CONCAT(warehouses.name SEPARATOR ',') AS warehouse FROM semi_finished JOIN erp_groups ON semi_finished.group_id=erp_groups.group_id JOIN units ON semi_finished.unit_id=units.unit_id JOIN brands ON semi_finished.brand_id=brands.brand_id JOIN inventory_warehouse ON semi_finished.semi_finished_id=inventory_warehouse.related_id JOIN warehouses ON inventory_warehouse.warehouse_id=warehouses.warehouse_id WHERE inventory_warehouse.related_to='semi_finished' ";

        $count = "SELECT COUNT(semi_finished_id) AS total FROM semi_finished JOIN erp_groups ON semi_finished.group_id=erp_groups.group_id JOIN units ON semi_finished.unit_id=units.unit_id JOIN brands ON semi_finished.brand_id=brands.brand_id JOIN inventory_warehouse ON semi_finished.semi_finished_id=inventory_warehouse.related_id JOIN warehouses ON inventory_warehouse.warehouse_id=warehouses.warehouse_id WHERE inventory_warehouse.related_to='semi_finished' ";
        $where = true;


        $filter_1_col = isset($_GET['group']) ? $columns['group'] : "";
        $filter_1_val = $_GET['group'];

        if (!empty($filter_1_col) && $filter_1_val !== "") {
            if ($where) {
                $query .= " AND " . $filter_1_col . "=" . $filter_1_val . " ";
                $count .= " AND " . $filter_1_col . "=" . $filter_1_val . " ";
            } else {
                $query .= " WHERE " . $filter_1_col . "=" . $filter_1_val . " ";
                $count .= " WHERE " . $filter_1_col . "=" . $filter_1_val . " ";
                $where = true;
            }
        }
        

        $filter_2_col = isset($_GET['unit']) ? $columns['unit'] : "";
        $filter_2_val = $_GET['unit'];

        if (!empty($filter_2_col) && $filter_2_val !== "") {
            if ($where) {
                $query .= " AND " . $filter_2_col . "=" . $filter_2_val . " ";
                $count .= " AND " . $filter_2_col . "=" . $filter_2_val . " ";
            } else {
                $query .= " WHERE " . $filter_2_col . "=" . $filter_2_val . " ";
                $count .= " WHERE " . $filter_2_col . "=" . $filter_2_val . " ";
                $where = true;
            }
        }

        $filter_3_col = isset($_GET['brand']) ? $columns['brand'] : "";
        $filter_3_val = $_GET['brand'];

        if (!empty($filter_3_col) && $filter_3_val !== "") {
            if ($where) {
                $query .= " AND " . $filter_3_col . "=" . $filter_3_val . " ";
                $count .= " AND " . $filter_3_col . "=" . $filter_3_val . " ";
            } else {
                $query .= " WHERE " . $filter_3_col . "=" . $filter_3_val . " ";
                $count .= " WHERE " . $filter_3_col . "=" . $filter_3_val . " ";
                $where = true;
            }
        }

        $filter_4_col = isset($_GET['warehouse']) ? $columns['warehouse'] : "";
        $filter_4_val = $_GET['warehouse'];

        if (!empty($filter_4_col) && $filter_4_val !== "") {
            if ($where) {
                $query .= " AND " . $filter_4_col . " IN (" . $filter_4_val . ") ";
                $count .= " AND " . $filter_4_col . " IN (" . $filter_4_val . ") ";
            } else {
                $query .= " WHERE " . $filter_4_col . " IN (" . $filter_4_val . ") ";
                $count .= " WHERE " . $filter_4_col . " IN (" . $filter_4_val . ") ";
                $where = true;
            }
        }

        if (!empty($search)) {
            if (!$where) {
                $query .= " WHERE ( semi_finished.name LIKE '%" . $search . "%' OR semi_finished.code LIKE '%" . $search . "%' ) ";
                $count .= " WHERE ( semi_finished.name LIKE '%" . $search . "%' OR semi_finished.code LIKE '%" . $search . "%' ) ";
                $where = true;
            } else {
                $query .= " AND ( semi_finished.name LIKE '%" . $search . "%' OR semi_finished.code LIKE '%" . $search . "%' ) ";
                $count .= " AND ( semi_finished.name LIKE '%" . $search . "%' OR semi_finished.code LIKE '%" . $search . "%' ) ";
            }
        }
        $query .= " GROUP BY semi_finished_id ";
        $count .= " GROUP BY semi_finished_id ";
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
                                <li><a href="' . url_to('erp.inventory.semifinishedview', $r['semi_finished_id']) . '" title="View" class="bg-warning "><i class="fa fa-eye"></i> </a></li>
                                <li><a href="' . url_to('erp.inventory.semifinishededit', $r['semi_finished_id']) . '" title="Edit" class="bg-success "><i class="fa fa-pencil"></i> </a></li>
                                <li><a href="' . url_to('erp.inventory.semifinisheddelete', $r['semi_finished_id']) . '" title="Delete" class="bg-danger del-confirm"><i class="fa fa-trash"></i> </a></li>
                            </ul>
                        </div>
                    </div>';
            array_push(
                $m_result,
                array(
                    $r['semi_finished_id'],
                    $sno,
                    $r['name'],
                    $r['code'],
                    $r['group_name'],
                    $r['unit_name'],
                    $r['brand_name'],
                    $r['warehouse'],
                    $action
                )
            );
            $sno++;
        }
        $response = array();
        $response['total_rows'] = count($this->db->query($count)->getResultArray());
        $response['data'] = $m_result;
        return $response;
    }

    public function get_semifinished_export()
    {
        $columns = array(
            "name" => "name",
            "code" => "code",
            "group" => "erp_groups.group_id",
            "unit" => "units.unit_id",
            "brand" => "brands.brand_id",
            "warehouse" => "warehouses.warehouse_id"
        );
        $limit = $_GET['limit'];
        $offset = $_GET['offset'];
        $search = $_GET['search'] ?? "";
        $orderby = isset($_GET['orderby']) ? strtoupper($_GET['orderby']) : "";
        $ordercol = isset($_GET['ordercol']) ? $columns[$_GET['ordercol']] : "";

        $query = "SELECT semi_finished.name,code,erp_groups.group_name,units.unit_name,brands.brand_name,GROUP_CONCAT(warehouses.name SEPARATOR ',') AS warehouse,short_desc,long_desc FROM semi_finished JOIN erp_groups ON semi_finished.group_id=erp_groups.group_id JOIN units ON semi_finished.unit_id=units.unit_id JOIN brands ON semi_finished.brand_id=brands.brand_id JOIN inventory_warehouse ON semi_finished.semi_finished_id=inventory_warehouse.related_id JOIN warehouses ON inventory_warehouse.warehouse_id=warehouses.warehouse_id WHERE inventory_warehouse.related_to='semi_finished' ";
        $where = true;

        $filter_1_col = isset($_GET['group']) ? $columns['group'] : "";
        $filter_1_val = $_GET['group'];

        if (!empty($filter_1_col) && $filter_1_val !== "") {
            if ($where) {
                $query .= " AND " . $filter_1_col . "=" . $filter_1_val . " ";
            } else {
                $query .= " WHERE " . $filter_1_col . "=" . $filter_1_val . " ";
                $where = true;
            }
        }

        $filter_2_col = isset($_GET['unit']) ? $columns['unit'] : "";
        $filter_2_val = $_GET['unit'];

        if (!empty($filter_2_col) && $filter_2_val !== "") {
            if ($where) {
                $query .= " AND " . $filter_2_col . "=" . $filter_2_val . " ";
            } else {
                $query .= " WHERE " . $filter_2_col . "=" . $filter_2_val . " ";
                $where = true;
            }
        }

        $filter_3_col = isset($_GET['brand']) ? $columns['brand'] : "";
        $filter_3_val = $_GET['brand'];

        if (!empty($filter_3_col) && $filter_3_val !== "") {
            if ($where) {
                $query .= " AND " . $filter_3_col . "=" . $filter_3_val . " ";
            } else {
                $query .= " WHERE " . $filter_3_col . "=" . $filter_3_val . " ";
                $where = true;
            }
        }

        $filter_4_col = isset($_GET['warehouse']) ? $columns['warehouse'] : "";
        $filter_4_val = $_GET['warehouse'];

        if (!empty($filter_4_col) && $filter_4_val !== "") {
            if ($where) {
                $query .= " AND " . $filter_4_col . " IN (" . $filter_4_val . ") ";
            } else {
                $query .= " WHERE " . $filter_4_col . " IN (" . $filter_4_val . ") ";
                $where = true;
            }
        }

        if (!empty($search)) {
            if (!$where) {
                $query .= " WHERE ( semi_finished.name LIKE '%" . $search . "%' OR semi_finished.code LIKE '%" . $search . "%' ) ";
                $where = true;
            } else {
                $query .= " AND ( semi_finished.name LIKE '%" . $search . "%' OR semi_finished.code LIKE '%" . $search . "%' ) ";
            }
        }
        $query .= " GROUP BY semi_finished_id ";
        if (!empty($ordercol) && !empty($orderby)) {
            $query .= " ORDER BY " . $ordercol . " " . $orderby;
        }
        $result = $this->db->query($query)->getResultArray();
        return $result;
    }

    public function insert_semifinished($post, $warehouses, $customfield_chkbx_counter)
    {
        $inserted = false;
        $name = $post["name"];
        $code = $post["code"];
        $unit = $post["unit"];
        $brand = $post["brand"];
        $group = $post["group"];
        $short_desc = $post["short_desc"];
        $long_desc = $post["long_desc"];
        $created_by = get_user_id();

        $this->db->transBegin();
        $query = "INSERT INTO semi_finished(name,code,unit_id,brand_id,group_id,short_desc,long_desc,created_at,created_by) VALUES(?,?,?,?,?,?,?,?,?) ";
        $this->db->query($query, array($name, $code, $unit, $brand, $group, $short_desc, $long_desc, time(), $created_by));
        $id = $this->db->insertId();

        if (empty($id)) {
            $this->session->setFlashdata("op_error", "Semi Finished failed to create");
            return $inserted;
        }

        if (!$this->update_semifinished_warehouse($id, $warehouses)) {
            $this->session->setFlashdata("op_error", "Semi Finished failed to create");
            $this->db->transRollback();
            return $inserted;
        }

        if (!$this->update_semifinished_cfv($id, $customfield_chkbx_counter)) {
            $this->session->setFlashdata("op_error", "Semi Finished failed to create");
            $this->db->transRollback();
            return $inserted;
        }

        $config['title'] = "Semi Finished Insert";
        $this->db->transComplete();
        if ($this->db->transStatus() === FALSE) {
            $this->db->transRollback();
            $config['log_text'] = "[ Semi Finished failed to create ]";
            $config['ref_link'] = "";
            $this->session->setFlashdata("op_error", "Semi Finished failed to create");
        } else {
            $this->db->transCommit();
            $inserted = true;
            $config['log_text'] = "[ Semi Finished successfully created ]";
            $config['ref_link'] = "";
            $this->session->setFlashdata("op_success", "Semi Finished successfully created");
        }
        log_activity($config);
        return $inserted;
    }

    public function update_semifinished($semi_finished_id, $post, $warehouses, $customfield_chkbx_counter)
    {
        $updated = false;
        $name = $post["name"];
        $code = $post["code"];
        $unit = $post["unit"];
        $brand = $post["brand"];
        $group = $post["group"];
        $short_desc = $post["short_desc"];
        $long_desc = $post["long_desc"];

        $this->db->transBegin();
        $query = "UPDATE semi_finished SET name=? , code=? , unit_id=? , brand_id=? , group_id=? , short_desc=? , long_desc=? WHERE semi_finished_id=$semi_finished_id ";
        $this->db->query($query, array($name, $code, $unit, $brand, $group, $short_desc, $long_desc));

        if (!$this->update_semifinished_warehouse($semi_finished_id, $warehouses)) {
            $this->session->setFlashdata("op_error", "Semi Finished failed to update");
            $this->db->transRollback();
            return $updated;
        }

        if (!$this->update_semifinished_cfv($semi_finished_id, $customfield_chkbx_counter)) {
            $this->session->setFlashdata("op_error", "Semi Finished failed to update");
            $this->db->transRollback();
            return $updated;
        }

        $config['title'] = "Semi Finished Update";
        $this->db->transComplete();
        if ($this->db->transStatus() === FALSE) {
            $this->db->transRollback();
            $config['log_text'] = "[ Semi Finished failed to update ]";
            $config['ref_link'] = "";
            $this->session->setFlashdata("op_error", "Semi Finished failed to update");
        } else {
            $this->db->transCommit();
            $updated = true;
            $config['log_text'] = "[ Semi Finished successfully updated ]";
            $config['ref_link'] = "";
            $this->session->setFlashdata("op_success", "Semi Finished successfully updated");
        }
        log_activity($config);
        return $updated;
    }

    private function update_semifinished_cfv($id, $customfield_chkbx_counter)
    {
        $updated = true;
        $query = "DELETE cfv FROM custom_field_values cfv INNER JOIN custom_fields cf ON cfv.cf_id=cf.cf_id WHERE cf.field_related_to='semi_finished' AND cfv.related_id=$id";
        $this->db->query($query);
        $checkbox = array();
        $query = "INSERT INTO custom_field_values(cf_id,related_id,field_value) VALUES(?,?,?) ";
        foreach ($_POST as $key => $value) {
            if (stripos($key, "cf_checkbox_") === 0) {
                $checkbox[$key] = $value;
            } else if (stripos($key, "cf_") === 0) {
                $cf_id = substr($key, strlen("cf_"));
                $this->db->query($query, array($cf_id, $id, $value));
                if (empty($this->db->insertId())) {
                    $updated = false;
                    break;
                }
            }
        }
        $checkbox_counter = intval($customfield_chkbx_counter);
        for ($i = 0; $i <= $checkbox_counter; $i++) {
            $values = array();
            $cf_id = 0;
            foreach ($checkbox as $key => $value) {
                if (stripos($key, "cf_checkbox_" . $i . "_") === 0) {
                    array_push($values, $value);
                    $cf_id = explode("_", $key)[3];
                    //$cf_id=substr($key,strlen("cf_checkbox_".$i."_"),stripos($key,"_",strlen("cf_checkbox_".$i."_")+1));
                }
            }
            if (!empty($cf_id)) {
                $this->db->query($query, array($cf_id, $id, implode(",", $values)));
                if (empty($this->db->insertId())) {
                    $updated = false;
                    break;
                }
            }
        }
        return $updated;
    }

    private function update_semifinished_warehouse($id, $warehouses)
    {
        $updated = true;
        $query = "DELETE FROM inventory_warehouse WHERE related_to='semi_finished' AND related_id=$id";
        $this->db->query($query);
        $warehouses = explode(",", $warehouses);
        $query = "INSERT INTO inventory_warehouse(warehouse_id,related_id,related_to) VALUES(?,?,?) ";
        foreach ($warehouses as $warehouse) {
            $this->db->query($query, array($warehouse, $id, 'semi_finished'));
            if (empty($this->db->insertId())) {
                $updated = false;
                break;
            }
        }
        return $updated;
    }

    public function get_semifinished_by_id($semi_finished_id)
    {
        $query = "SELECT * FROM semi_finished WHERE semi_finished_id=$semi_finished_id";
        $result = $this->db->query($query)->getRow();
        return $result;
    }

    public function get_semifinished_for_view($semi_finished_id)
    {
        $query = "SELECT name,code,short_desc,long_desc,erp_groups.group_name,units.unit_name,brands.brand_name FROM semi_finished JOIN erp_groups ON semi_finished.group_id=erp_groups.group_id JOIN units ON semi_finished.unit_id=units.unit_id JOIN brands ON semi_finished.brand_id=brands.brand_id WHERE semi_finished.semi_finished_id=$semi_finished_id";
        $result = $this->db->query($query)->getRow();
        return $result;
    }

    public function semifinishedDelete($semi_finished_id)
    {
        try {
            $this->db->transStart();

            $this->db->table('semi_finished')
                ->where('semi_finished_id', $semi_finished_id)
                ->delete();

            $this->db->table('stock_alerts')
                ->where('related_id', $semi_finished_id)
                ->delete();

            $attach_ids = $this->db->table('attachments')
                ->select('attach_id')
                ->where('related_id', $semi_finished_id)
                ->where('related_to','semi_finished')
                ->get()
                ->getResult();

            if (!empty($attach_ids)) {
                $attach_id = $attach_ids[0]->attach_id;
                $this->attachmentModel->delete_attachment("semi_finished", $attach_id);
            }

            $this->db->transComplete();

            if ($this->db->transStatus() === false) {
                throw new Exception("Transaction failed");
            }
            return $this->session->setFlashdata("op_success", "Semi Finished and related data deleted successfully");
        } catch (Exception $e) {
            return $this->session->setFlashdata("op_error", "Error deleting Semi Finished and related data");
        }
    }

    public function get_semiFinished_count()
    {
        $query = "SELECT semi_finished_id FROM semi_finished";
        $result =  $this->db->query($query)->getNumRows();
        return empty($result) ? 1 : $result + 1 ;
    }

    
}
