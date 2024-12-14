<?php


namespace App\Models\Erp;

use CodeIgniter\Model;
use Exception;

class FinishedGoodsModel extends Model
{
    protected $table = 'finished_goods';
    protected $primaryKey = 'finished_good_id';

    protected $allowedFields = ['name', 'short_desc', 'long_desc', 'group_id', 'code', 'unit_id', 'brand_id', 'created_at', 'created_by'];

    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = '';

    public $session;
    public $db;
    public $attachmentModel;
    public function __construct()
    {
        $this->attachmentModel = new AttachmentModel();
        $this->session = \Config\Services::session();
        $this->db = \Config\Database::connect();
    }


    public function get_finished_goods_groups()
    {
        $query = "SELECT group_id,group_name FROM erp_groups WHERE related_to='finished_good' ";
        $result = $this->db->query($query)->getResultArray();
        return $result;
    }

    public function get_dtconfig_finishedgoods()
    {
        $config = array(
            "columnNames" => ["sno", "name", "code", "group", "unit", "brand", "warehouses", "action"],
            "sortable" => [0, 1, 1, 0, 0, 0, 0, 0],
            "filters" => ["group", "unit", "brand", "warehouse"]
        );
        return json_encode($config);
    }

    public function get_finishedgoods_datatable()
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

        $query = "SELECT finished_good_id,finished_goods.name,code,erp_groups.group_name,units.unit_name,brands.brand_name,GROUP_CONCAT(warehouses.name SEPARATOR ',') AS warehouse FROM finished_goods JOIN erp_groups ON finished_goods.group_id=erp_groups.group_id JOIN units ON finished_goods.unit_id=units.unit_id JOIN brands ON finished_goods.brand_id=brands.brand_id JOIN inventory_warehouse ON finished_goods.finished_good_id=inventory_warehouse.related_id JOIN warehouses ON inventory_warehouse.warehouse_id=warehouses.warehouse_id WHERE inventory_warehouse.related_to='finished_good' ";
        $count = "SELECT COUNT(finished_good_id) AS total FROM finished_goods JOIN erp_groups ON finished_goods.group_id=erp_groups.group_id JOIN units ON finished_goods.unit_id=units.unit_id JOIN brands ON finished_goods.brand_id=brands.brand_id JOIN inventory_warehouse ON finished_goods.finished_good_id=inventory_warehouse.related_id JOIN warehouses ON inventory_warehouse.warehouse_id=warehouses.warehouse_id WHERE inventory_warehouse.related_to='finished_good' ";
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
                $query .= " WHERE ( finished_goods.name LIKE '%" . $search . "%' OR finished_goods.code LIKE '%" . $search . "%' ) ";
                $count .= " WHERE ( finished_goods.name LIKE '%" . $search . "%' OR finished_goods.code LIKE '%" . $search . "%' ) ";
                $where = true;
            } else {
                $query .= " AND ( finished_goods.name LIKE '%" . $search . "%' OR finished_goods.code LIKE '%" . $search . "%' ) ";
                $count .= " AND ( finished_goods.name LIKE '%" . $search . "%' OR finished_goods.code LIKE '%" . $search . "%' ) ";
            }
        }
        $query .= " GROUP BY finished_good_id ";
        $count .= " GROUP BY finished_good_id ";
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
                                <li><a href="' . url_to('erp.inventory.finishedgoodview', $r['finished_good_id']) . '" title="View" class="bg-warning "><i class="fa fa-eye"></i> </a></li>
                                <li><a href="' . url_to('erp.inventory.finishedgoodedit', $r['finished_good_id']) . '" title="Edit" class="bg-success "><i class="fa fa-pencil"></i> </a></li>
                                <li><a href="' . url_to('erp.inventory.finishedgooddelete', $r['finished_good_id']) . '" title="Delete" class="bg-danger del-confirm"><i class="fa fa-trash"></i> </a></li>
                            </ul>
                        </div>
                    </div>';
            array_push(
                $m_result,
                array(
                    $r['finished_good_id'],
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

    public function get_finishedgoods_export($type)
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

        $query = "SELECT finished_goods.name,code,erp_groups.group_name,units.unit_name,brands.brand_name,GROUP_CONCAT(warehouses.name SEPARATOR ',') AS warehouse,short_desc,long_desc FROM finished_goods JOIN erp_groups ON finished_goods.group_id=erp_groups.group_id JOIN units ON finished_goods.unit_id=units.unit_id JOIN brands ON finished_goods.brand_id=brands.brand_id JOIN inventory_warehouse ON finished_goods.finished_good_id=inventory_warehouse.related_id JOIN warehouses ON inventory_warehouse.warehouse_id=warehouses.warehouse_id WHERE inventory_warehouse.related_to='finished_good' ";
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
                $query .= " WHERE ( finished_goods.name LIKE '%" . $search . "%' OR finished_goods.code LIKE '%" . $search . "%' ) ";
                $where = true;
            } else {
                $query .= " AND ( finished_goods.name LIKE '%" . $search . "%' OR finished_goods.code LIKE '%" . $search . "%' ) ";
            }
        }
        $query .= " GROUP BY finished_good_id ";
        if (!empty($ordercol) && !empty($orderby)) {
            $query .= " ORDER BY " . $ordercol . " " . $orderby;
        }
        $result = $this->db->query($query)->getResultArray();
        return $result;
    }

    public function insert_finishedgood($post, $warehouses, $customfield_chkbx_counter)
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
        $query = "INSERT INTO finished_goods(name,code,unit_id,brand_id,group_id,short_desc,long_desc,created_at,created_by) VALUES(?,?,?,?,?,?,?,?,?) ";
        $this->db->query($query, array($name, $code, $unit, $brand, $group, $short_desc, $long_desc, time(), $created_by));

        // if (!$this->db->transStatus()) {

        //     $this->db->transRollback();
        //     log_message('error', 'Transaction failed: ' . $this->db->error()['message']);
        //     return false;

        // } else {

        //     $this->db->transCommit();

        // }

        $id = $this->db->insertId();

        if (empty($id)) {
            $this->session->setFlashdata("op_error", "Finished Good failed to create");
            return $inserted;
        }

        if (!$this->update_finishedgood_warehouse($id, $warehouses)) {
            $this->session->setFlashdata("op_error", "Finished Good failed to create");
            $this->db->transRollback();
            return $inserted;
        }

        if (!$this->update_finishedgood_cfv($id, $customfield_chkbx_counter)) {
            $this->session->setFlashdata("op_error", "Finished Good failed to create");
            $this->db->transRollback();
            return $inserted;
        }

        $config['title'] = " Finished Good Insert";
        $this->db->transComplete();
        if ($this->db->transStatus() === FALSE) {
            $this->db->transRollback();
            $config['log_text'] = "[ Finished Good failed to create ]";
            $config['ref_link'] = "";
            $this->session->setFlashdata("op_error", "Finished Good failed to create");
        } else {
            $this->db->transCommit();
            $inserted = true;
            $config['log_text'] = "[ Finished Good successfully created ]";
            $config['ref_link'] = "";
            $this->session->setFlashdata("op_success", "Finished Good successfully created");
        }
        log_activity($config);
        return $inserted;
    }

    public function getcode()
    {
        $query = "SELECT MAX(finished_good_id) as id FROM finished_goods ";
        $result = $this->db->query($query)->getRowArray();

        return $result;

    }

    private function update_finishedgood_cfv($id, $customfield_chkbx_counter)
    {
        $updated = true;
        $query = "DELETE cfv FROM custom_field_values cfv INNER JOIN custom_fields cf ON cfv.cf_id=cf.cf_id WHERE cf.field_related_to='finished_good' AND cfv.related_id=$id";
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

    private function update_finishedgood_warehouse($id, $warehouses)
    {
        $updated = true;
        $query = "DELETE FROM inventory_warehouse WHERE related_to='finished_good' AND related_id=$id";
        $this->db->query($query);
        $warehouses = explode(",", $warehouses);
        $query = "INSERT INTO inventory_warehouse(warehouse_id,related_id,related_to) VALUES(?,?,?) ";
        foreach ($warehouses as $warehouse) {
            $this->db->query($query, array($warehouse, $id, 'finished_good'));
            if (empty($this->db->insertId())) {
                $updated = false;
                break;
            }
        }
        return $updated;
    }

    public function update_finishedgood($finished_good_id, $post, $warehouses, $customfield_chkbx_counter)
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
        $query = "UPDATE finished_goods SET name=? , code=? , unit_id=? , brand_id=? , group_id=? , short_desc=? , long_desc=? WHERE finished_good_id=$finished_good_id ";
        $this->db->query($query, array($name, $code, $unit, $brand, $group, $short_desc, $long_desc));

        if (!$this->update_finishedgood_warehouse($finished_good_id, $warehouses)) {
            $this->session->setFlashdata("op_error", " Finished Good failed to update");
            $this->db->transRollback();
            return $updated;
        }

        if (!$this->update_finishedgood_cfv($finished_good_id, $customfield_chkbx_counter)) {
            $this->session->setFlashdata("op_error", "Finished Good failed to update");
            $this->db->transRollback();
            return $updated;
        }

        $config['title'] = "Finished Good Update";
        $this->db->transComplete();
        if ($this->db->transStatus() === FALSE) {
            $this->db->transRollback();
            $config['log_text'] = "[ Finished Good failed to update ]";
            $config['ref_link'] = "";
            $this->session->setFlashdata("op_error", "Finished Good failed to update");
        } else {
            $this->db->transCommit();
            $updated = true;
            $config['log_text'] = "[ Finished Good successfully updated ]";
            $config['ref_link'] = "";
            $this->session->setFlashdata("op_success", "Finished Good successfully updated");
        }
        log_activity($config);
        return $updated;
    }
    public function get_finishedgood_by_id($finished_good_id)
    {
        $query = " SELECT * FROM finished_goods WHERE finished_good_id=$finished_good_id ";
        $result = $this->db->query($query)->getRow();
        return $result;
    }

    public function get_finished_good_warehouse($finished_good_id)
    {
        $query = "SELECT warehouse_id FROM inventory_warehouse WHERE related_id=$finished_good_id AND related_to='finished_good' ";
        $result = $this->db->query($query)->getResultArray();
        return $result;
    }

    public function get_finishedgood_for_view($finished_good_id)
    {
        $query = "SELECT name,code,short_desc,long_desc,erp_groups.group_name,units.unit_name,brands.brand_name FROM finished_goods JOIN erp_groups ON finished_goods.group_id=erp_groups.group_id JOIN units ON finished_goods.unit_id=units.unit_id JOIN brands ON finished_goods.brand_id=brands.brand_id WHERE finished_goods.finished_good_id=$finished_good_id";
        $result = $this->db->query($query)->getRow();
        return $result;
    }

    public function finishedgoodDelete($finished_good_id)
    {
        try {
            $this->db->transStart();

            $this->db->table('finished_goods')
                ->where('finished_good_id', $finished_good_id)
                ->delete();

            $this->db->table('stock_alerts')
                ->where('related_id', $finished_good_id)
                ->delete();

            $attach_ids = $this->db->table('attachments')
                ->select('attach_id')
                ->where('related_id', $finished_good_id)
                ->where('related_to', 'finished_good')
                ->get()
                ->getResult();

            if (!empty($attach_ids)) {
                $attach_id = $attach_ids[0]->attach_id;
                $this->attachmentModel->delete_attachment("finished_good", $attach_id);
            }

            $this->db->transComplete();

            if ($this->db->transStatus() === false) {
                throw new Exception("Transaction failed");
            }
            return $this->session->setFlashdata("op_success", "Finished Good and related data deleted successfully");
        } catch (Exception $e) {
            return $this->session->setFlashdata("op_error", "Error deleting Finished Good and related data");
        }
    }
}
