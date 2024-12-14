<?php

namespace App\Models\Erp;

use CodeIgniter\Model;
use Exception;

class RawMaterialModel extends Model
{
    protected $table = 'raw_materials';
    protected $primaryKey = 'raw_material_id';

    protected $allowedFields = [
        'name',
        'short_desc',
        'long_desc',
        'group_id',
        'code',
        'unit_id',
        'brand_id',
        'created_at',
        'created_by',
    ];

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


    public function get_dtconfig_rawmaterials()
    {
        $config = array(
            "columnNames" => ["sno", "name", "code", "group", "unit", "brand", "warehouses", "action"],
            "sortable" => [0, 1, 1, 0, 0, 0, 0, 0],
            "filters" => ["group", "unit", "brand", "warehouse"]
        );
        return json_encode($config);
    }

    // public function get_rawmaterials_datatable()
    // {
    //     $columns = array(
    //         "name" => "name",
    //         "code" => "code",
    //         "group" => "erp_groups.group_id",
    //         "unit" => "units.unit_id",
    //         "brand" => "brands.brand_id",
    //         "warehouse" => "warehouses.warehouse_id"
    //     );
    //     // Logger
    //     $logger = \Config\Services::logger();
    //     $limit = $_GET['limit'] ?? 10;
    //     $offset = $_GET['offset'] ?? 10;
    //     $search = $_GET['search'] ?? "";
    //     $orderby = isset($_GET['orderby']) ? strtoupper($_GET['orderby']) : "";
    //     $ordercol = isset($_GET['ordercol']) ? $columns[$_GET['ordercol']] : "";

    //     $query = "SELECT raw_material_id,raw_materials.name,code,erp_groups.group_name,units.unit_name,brands.brand_name,GROUP_CONCAT(warehouses.name SEPARATOR ',') AS warehouse FROM raw_materials JOIN erp_groups ON raw_materials.group_id=erp_groups.group_id JOIN units ON raw_materials.unit_id=units.unit_id JOIN brands ON raw_materials.brand_id=brands.brand_id JOIN inventory_warehouse ON raw_materials.raw_material_id=inventory_warehouse.related_id JOIN warehouses ON inventory_warehouse.warehouse_id=warehouses.warehouse_id WHERE inventory_warehouse.related_to='raw_material' ";
    //     $count = "SELECT COUNT(raw_material_id) AS total FROM raw_materials JOIN erp_groups ON raw_materials.group_id=erp_groups.group_id JOIN units ON raw_materials.unit_id=units.unit_id JOIN brands ON raw_materials.brand_id=brands.brand_id JOIN inventory_warehouse ON raw_materials.raw_material_id=inventory_warehouse.related_id JOIN warehouses ON inventory_warehouse.warehouse_id=warehouses.warehouse_id WHERE inventory_warehouse.related_to='raw_material' ";
    //     $where = true;

    //     $filter_1_col = isset($_GET['group']) ? $columns['group'] : "";
    //     $filter_1_val = isset($_GET['group']);

    //     if (!empty($filter_1_col) && $filter_1_val !== "") {
    //         if ($where) {
    //             $query .= " AND " . $filter_1_col . "=" . $filter_1_val . " ";
    //             $count .= " AND " . $filter_1_col . "=" . $filter_1_val . " ";
    //         } else {
    //             $query .= " WHERE " . $filter_1_col . "=" . $filter_1_val . " ";
    //             $count .= " WHERE " . $filter_1_col . "=" . $filter_1_val . " ";
    //             $where = true;
    //         }
    //     }

    //     $filter_2_col = isset($_GET['unit']) ? $columns['unit'] : "";
    //     $filter_2_val = isset($_GET['unit']);

    //     if (!empty($filter_2_col) && $filter_2_val !== "") {
    //         if ($where) {
    //             $query .= " AND " . $filter_2_col . "=" . $filter_2_val . " ";
    //             $count .= " AND " . $filter_2_col . "=" . $filter_2_val . " ";
    //         } else {
    //             $query .= " WHERE " . $filter_2_col . "=" . $filter_2_val . " ";
    //             $count .= " WHERE " . $filter_2_col . "=" . $filter_2_val . " ";
    //             $where = true;
    //         }
    //     }

    //     $filter_3_col = isset($_GET['brand']) ? $columns['brand'] : "";
    //     $filter_3_val = isset($_GET['brand']);

    //     if (!empty($filter_3_col) && $filter_3_val !== "") {
    //         if ($where) {
    //             $query .= " AND " . $filter_3_col . "=" . $filter_3_val . " ";
    //             $count .= " AND " . $filter_3_col . "=" . $filter_3_val . " ";
    //         } else {
    //             $query .= " WHERE " . $filter_3_col . "=" . $filter_3_val . " ";
    //             $count .= " WHERE " . $filter_3_col . "=" . $filter_3_val . " ";
    //             $where = true;
    //         }
    //     }

    //     $filter_4_col = isset($_GET['warehouse']) ? $columns['warehouse'] : "";
    //     $filter_4_val = isset($_GET['warehouse']);

    //     if (!empty($filter_4_col) && $filter_4_val !== "") {
    //         if (!$where) {
    //             $query .= " AND " . $filter_4_col . " IN (" . $filter_4_val . ") ";
    //             $count .= " AND " . $filter_4_col . " IN (" . $filter_4_val . ") ";
    //         } else {
    //             $query .= " WHERE " . $filter_4_col . " IN (" . $filter_4_val . ") ";
    //             $count .= " WHERE " . $filter_4_col . " IN (" . $filter_4_val . ") ";
    //             $where = true;
    //         }
    //     }

    //     if (!empty($search)) {
    //         if (!$where) {
    //             $query .= " WHERE ( raw_materials.name LIKE '%" . $search . "%' OR code LIKE '%" . $search . "%' ) ";
    //             $count .= " WHERE ( raw_materials.name LIKE '%" . $search . "%' OR code LIKE '%" . $search . "%' ) ";
    //             $where = true;
    //         } else {
    //             $query .= " AND ( raw_materials.name LIKE '%" . $search . "%' OR code LIKE '%" . $search . "%' ) ";
    //             $count .= " AND ( raw_materials.name LIKE '%" . $search . "%' OR code LIKE '%" . $search . "%' ) ";
    //         }
    //     }
    //     $query .= " GROUP BY raw_material_id ";
    //     $count .= " GROUP BY raw_material_id ";
    //     if (!empty($ordercol) && !empty($orderby)) {
    //         $query .= " ORDER BY " . $ordercol . " " . $orderby;
    //     }
    //     $query .= " LIMIT $offset,$limit ";
    //     $result = $this->db->query($query)->getResultArray();
    //     $logger->error($result);
    //     $m_result = array();
    //     $sno = $offset + 1;
    //     foreach ($result as $r) {
    //         $action = '<div class="dropdown tableAction">
    //                     <a type="button" class="dropBtn HoverA "><i class="fa fa-ellipsis-v"></i></a>
    //                     <div class="dropdown_container">
    // <ul class="BB_UL flex">
    //     <li><a href="' . url_to('erp.inventory.rawmaterialview', $r['raw_material_id']) . '" title="View" class="bg-warning "><i class="fa fa-eye"></i> </a></li>
    //     <li><a href="' . url_to('erp.inventory.rawmaterialedit', $r['raw_material_id']) . '" title="Edit" class="bg-success "><i class="fa fa-pencil"></i> </a></li>
    //     <li><a href="' . url_to('erp.inventory.rawmaterialdelete', $r['raw_material_id']) . '" title="Delete" class="bg-danger del-confirm"><i class="fa fa-trash"></i> </a></li>
    // </ul>
    //                     </div>
    //                 </div>';
    //         array_push(
    //             $m_result,
    //             array(
    //                 $r['raw_material_id'],
    //                 $sno,
    //                 $r['name'],
    //                 $r['code'],
    //                 $r['group_name'],
    //                 $r['unit_name'],
    //                 $r['brand_name'],
    //                 $r['warehouse'],
    //                 $action
    //             )
    //         );
    //         $sno++;
    //     }
    //     $response = array();
    //     $response['total_rows'] = count($this->db->query($count)->getResultArray());
    //     $response['data'] = $m_result;
    //     return $response;
    // }

    public function get_rawmaterials_datatable()
    {
        $logger = \Config\Services::logger();
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

        $logger->error($offset);
        $logger->error($limit);
        $query = "SELECT raw_material_id,raw_materials.name,code,erp_groups.group_name,units.unit_name,brands.brand_name,GROUP_CONCAT(warehouses.name SEPARATOR ',') AS warehouse FROM raw_materials JOIN erp_groups ON raw_materials.group_id=erp_groups.group_id JOIN units ON raw_materials.unit_id=units.unit_id JOIN brands ON raw_materials.brand_id=brands.brand_id JOIN inventory_warehouse ON raw_materials.raw_material_id=inventory_warehouse.related_id JOIN warehouses ON inventory_warehouse.warehouse_id=warehouses.warehouse_id WHERE inventory_warehouse.related_to='raw_material' ";
        $count = "SELECT COUNT(raw_material_id) AS total FROM raw_materials JOIN erp_groups ON raw_materials.group_id=erp_groups.group_id JOIN units ON raw_materials.unit_id=units.unit_id JOIN brands ON raw_materials.brand_id=brands.brand_id JOIN inventory_warehouse ON raw_materials.raw_material_id=inventory_warehouse.related_id JOIN warehouses ON inventory_warehouse.warehouse_id=warehouses.warehouse_id WHERE inventory_warehouse.related_to='raw_material' ";
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
                $query .= " WHERE ( raw_materials.name LIKE '%" . $search . "%' OR code LIKE '%" . $search . "%' ) ";
                $count .= " WHERE ( raw_materials.name LIKE '%" . $search . "%' OR code LIKE '%" . $search . "%' ) ";
                $where = true;
            } else {
                $query .= " AND ( raw_materials.name LIKE '%" . $search . "%' OR code LIKE '%" . $search . "%' ) ";
                $count .= " AND ( raw_materials.name LIKE '%" . $search . "%' OR code LIKE '%" . $search . "%' ) ";
            }
        }
        $query .= " GROUP BY raw_material_id ";
        $count .= " GROUP BY raw_material_id ";
        if (!empty($ordercol) && !empty($orderby)) {
            $query .= " ORDER BY " . $ordercol . " " . $orderby;
        }
        $query .= " LIMIT " . $offset . "," . $limit . " ";

        $logger->error($query);
        $result = $this->db->query($query)->getResultArray();
        $m_result = array();
        $sno = $offset + 1;
        foreach ($result as $r) {
            $action = '<div class="dropdown tableAction">
                        <a type="button" class="dropBtn HoverA "><i class="fa fa-ellipsis-v"></i></a>
                        <div class="dropdown_container">
                        <ul class="BB_UL flex">
                            <li><a href="' . url_to('erp.inventory.rawmaterialview', $r['raw_material_id']) . '" title="View" class="bg-warning "><i class="fa fa-eye"></i> </a></li>
                            <li><a href="' . url_to('erp.inventory.rawmaterialedit', $r['raw_material_id']) . '" title="Edit" class="bg-success "><i class="fa fa-pencil"></i> </a></li>
                            <li><a href="' . url_to('erp.inventory.rawmaterialdelete', $r['raw_material_id']) . '" title="Delete" class="bg-danger del-confirm"><i class="fa fa-trash"></i> </a></li>
                        </ul>
                        </div>
                    </div>';
            array_push(
                $m_result,
                array(
                    $r['raw_material_id'],
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

    public function get_rawmaterials_export($type)
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

        $query = "SELECT raw_materials.name,code,erp_groups.group_name,units.unit_name,brands.brand_name,GROUP_CONCAT(warehouses.name SEPARATOR ',') AS warehouse,short_desc,long_desc FROM raw_materials JOIN erp_groups ON raw_materials.group_id=erp_groups.group_id JOIN units ON raw_materials.unit_id=units.unit_id JOIN brands ON raw_materials.brand_id=brands.brand_id JOIN inventory_warehouse ON raw_materials.raw_material_id=inventory_warehouse.related_id JOIN warehouses ON inventory_warehouse.warehouse_id=warehouses.warehouse_id WHERE inventory_warehouse.related_to='raw_material' ";
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
                $query .= " WHERE ( raw_materials.name LIKE '%" . $search . "%' OR code LIKE '%" . $search . "%' ) ";
                $where = true;
            } else {
                $query .= " AND ( raw_materials.name LIKE '%" . $search . "%' OR code LIKE '%" . $search . "%' ) ";
            }
        }
        $query .= " GROUP BY raw_material_id ";
        if (!empty($ordercol) && !empty($orderby)) {
            $query .= " ORDER BY " . $ordercol . " " . $orderby;
        }
        $result = $this->db->query($query)->getResultArray();
        return $result;
    }

    public function insert_rawmaterial($customfield_chkbx_counter, $warehouses, $data)
    {
        $this->db->transBegin();
        $query = "INSERT INTO raw_materials(name,code,unit_id,brand_id,group_id,short_desc,long_desc,created_at,created_by) VALUES(?,?,?,?,?,?,?,?,?) ";
        $this->db->query($query, array($data['name'], $data['code'], $data['unit'], $data['brand'], $data['group'], $data['short_desc'], $data['long_desc'], time(), $data['created_by']));
        $id = $this->db->insertId();

        if (empty($id)) {
            $this->session->setFlashdata("op_error", "Raw Material failed to create");
            return $data['inserted'];
        }

        if (!$this->update_rawmaterial_warehouse($id, $warehouses)) {
            $this->session->setFlashdata("op_error", "Raw Material failed to create");
            $this->db->transRollback();
            return $data['inserted'];
        }

        if (!$this->update_rawmaterial_cfv($id, $customfield_chkbx_counter)) {
            $this->session->setFlashdata("op_error", "Raw Material failed to create");
            $this->db->transRollback();
            return $data['inserted'];
        }

        $config['title'] = "Raw Material Insert";
        $this->db->transComplete();
        if ($this->db->transStatus() === FALSE) {
            $this->db->transRollback();
            $config['log_text'] = "[ Raw Material failed to create ]";
            $config['ref_link'] = "";
            $this->session->setFlashdata("op_error", "Raw Material failed to create");
        } else {
            $this->db->transCommit();
            $inserted = true;
            $config['log_text'] = "[ Raw Material successfully created ]";
            $config['ref_link'] = "";
            $this->session->setFlashdata("op_success", "Raw Material successfully created");
        }
        log_activity($config);
        return $inserted;
    }

    private function update_rawmaterial_cfv($id, $customfield_chkbx_counter)
    {
        $updated = true;
        $query = "DELETE cfv FROM custom_field_values cfv INNER JOIN custom_fields cf ON cfv.cf_id=cf.cf_id WHERE cf.field_related_to='raw_material' AND cfv.related_id=$id";
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

    private function update_rawmaterial_warehouse($id, $warehouses)
    {
        $updated = true;
        $query = "DELETE FROM inventory_warehouse WHERE related_to='raw_material' AND related_id=$id";
        $this->db->query($query);
        $warehouses = explode(",", $warehouses);
        $query = "INSERT INTO inventory_warehouse(warehouse_id,related_id,related_to) VALUES(?,?,?) ";
        foreach ($warehouses as $warehouse) {
            $this->db->query($query, array($warehouse, $id, 'raw_material'));
            if (empty($this->db->insertId())) {
                $updated = false;
                break;
            }
        }
        return $updated;
    }

    public function update_rawmaterial($raw_material_id, $customfield_chkbx_counter, $warehouses,$post)
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
        $query = "UPDATE raw_materials SET name=? , code=? , unit_id=? , brand_id=? , group_id=? , short_desc=? , long_desc=? WHERE raw_material_id=$raw_material_id ";
        $this->db->query($query, array($name, $code, $unit, $brand, $group, $short_desc, $long_desc));

        if (!$this->update_rawmaterial_warehouse($raw_material_id, $warehouses)) {
            $this->session->setFlashdata("op_error", "Raw Material failed to update");
            $this->db->transRollback();
            return $updated;
        }

        if (!$this->update_rawmaterial_cfv($raw_material_id, $customfield_chkbx_counter)) {
            $this->session->setFlashdata("op_error", "Raw Material failed to update");
            $this->db->transRollback();
            return $updated;
        }

        $config['title'] = "Raw Material Update";
        $this->db->transComplete();
        if ($this->db->transStatus() === FALSE) {
            $this->db->transRollback();
            $config['log_text'] = "[ Raw Material failed to update ]";
            $config['ref_link'] = "";
            $this->session->setFlashdata("op_error", "Raw Material failed to update");
        } else {
            $this->db->transCommit();
            $updated = true;
            $config['log_text'] = "[ Raw Material successfully updated ]";
            $config['ref_link'] = "";
            $this->session->setFlashdata("op_success", "Raw Material successfully updated");
        }
        log_activity($config);
        return $updated;
    }

    public function get_rawmaterial_by_id($raw_material_id)
    {
        $query = "SELECT * FROM raw_materials WHERE raw_material_id=$raw_material_id";
        $result = $this->db->query($query)->getRow();
        return $result;
    }

    public function get_rawmaterial_for_view($raw_material_id)
    {
        $query = "SELECT name,code,short_desc,long_desc,erp_groups.group_name,units.unit_name,brands.brand_name FROM raw_materials JOIN erp_groups ON raw_materials.group_id=erp_groups.group_id JOIN units ON raw_materials.unit_id=units.unit_id JOIN brands ON raw_materials.brand_id=brands.brand_id WHERE raw_materials.raw_material_id=$raw_material_id";
        $result = $this->db->query($query)->getRow();
        return $result;
    }

    public function rawmaterialDelete($raw_material_id)
    {
        try {
            $this->db->transStart();

            $this->db->table('raw_materials')
                ->where('raw_material_id', $raw_material_id)
                ->delete();

            $this->db->table('stock_alerts')
                ->where('related_id', $raw_material_id)
                ->delete();

            $attach_ids = $this->db->table('attachments')
                ->select('attach_id')
                ->where('related_id', $raw_material_id)
                ->get()
                ->getResult();

            if (!empty($attach_ids)) {
                $attach_id = $attach_ids[0]->attach_id;
                $this->attachmentModel->delete_attachment("raw_material", $attach_id);
            }

            $this->db->transComplete();

            if ($this->db->transStatus() === false) {
                $this->db->transRollback();
                throw new Exception("Transaction failed");
            }
            return $this->session->setFlashdata("op_success", "Raw Material and related data deleted successfully");
        } catch (Exception $e) {
            return $this->session->setFlashdata("op_error", "Error deleting Raw Material and related data");
        }
    }

    public function get_raw_materials_count()
    {
        $query = "SELECT raw_material_id FROM raw_materials";
        $result =  $this->db->query($query)->getNumRows();
        return empty($result) ? 1 : $result + 1 ;
    }

}
