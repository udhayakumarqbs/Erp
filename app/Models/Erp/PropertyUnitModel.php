<?php

namespace App\Models\Erp;

use CodeIgniter\Model;

class PropertyUnitModel extends Model
{
    protected $table      = 'property_unit';
    protected $primaryKey = 'prop_unit_id';

    protected $allowedFields = [
        'property_id', 'unit_name', 'floor_no', 'area_sqft', 'status',
        'price', 'tax1', 'tax2', 'direction', 'description'
    ];

    protected $useTimestamps = false;
    private $property_status = array(
        0 => "Under Construction",
        1 => "Completed"
    );
    private $property_status_bg = array(
        0 => "st_primary",
        1 => "st_success"
    );

    private $propertyunit_status = array(
        0 => "Not Sold",
        1 => "Sold"
    );

    private $propertyunit_status_bg = array(
        0 => "st_dark",
        1 => "st_success"
    );
    public $session;
    public $db;
    public function __construct()
    {
        $this->session = \Config\Services::session();
        $this->db = \Config\Database::connect();
    }

    public function get_property_status()
    {
        return $this->property_status;
    }

    public function get_propertyunit_status()
    {
        return $this->propertyunit_status;
    }

    public function get_property_status_bg()
    {
        return $this->property_status_bg;
    }

    public function get_dtconfig_propertyunit()
    {
        $config = array(
            "columnNames" => ["sno", "name", "floor no", "area sqft", "price", "tax1", "tax2", "status", "action"],
            "sortable" => [0, 1, 0, 0, 1, 0, 0, 0, 0],
            "filters" => ["status"]
        );
        return json_encode($config);
    }

    public function get_propertyunit_datatable($property_id)
    {

        $columns = array(
            "name" => "unit_name",
            "price" => "price",
            "status" => "status"
        );
        $limit = $_GET['limit'];
        $offset = $_GET['offset'];
        $search = $_GET['search'] ?? "";
        $orderby = isset($_GET['orderby']) ? strtoupper($_GET['orderby']) : "";
        $ordercol = isset($_GET['ordercol']) ? $columns[$_GET['ordercol']] : "";

        $query = "SELECT prop_unit_id,unit_name,floor_no,area_sqft,price,CONCAT(t1.tax_name,' ',t1.percent,'%') AS tax_1,CONCAT(t2.tax_name,' ',t2.percent,'%') AS tax_2,status FROM property_unit JOIN taxes t1 ON tax1=t1.tax_id LEFT JOIN taxes t2 ON tax2=t2.tax_id WHERE property_id=$property_id ";
        $count = "SELECT COUNT(prop_unit_id) AS total FROM property_unit JOIN taxes t1 ON tax1=t1.tax_id LEFT JOIN taxes t2 ON tax2=t2.tax_id WHERE property_id=$property_id ";
        $where = true;

        $filter_1_col = isset($_GET['status']) ? $columns['status'] : "";
        $filter_1_val = $_GET['status'];

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

        if (!empty($search)) {
            if (!$where) {
                $query .= " WHERE unit_name LIKE '%" . $search . "%' ";
                $count .= " WHERE unit_name LIKE '%" . $search . "%' ";
                $where = true;
            } else {
                $query .= " AND unit_name LIKE '%" . $search . "%' ";
                $count .= " AND unit_name LIKE '%" . $search . "%' ";
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
                                <li><a href="' . url_to('erp.inventory.propertyunitedit', $property_id, $r['prop_unit_id']) . '" title="Edit" class="bg-success "><i class="fa fa-pencil"></i> </a></li>
                                <li><a href="' . url_to('erp.inventory.propertyunitdelete', $r['prop_unit_id']) . '" title="Delete" class="bg-danger del-confirm"><i class="fa fa-trash"></i> </a></li></div>
                    </div>';
            $status = "<span class='st " . $this->propertyunit_status_bg[$r['status']] . "' >" . $this->propertyunit_status[$r['status']] . "</span> ";
            array_push(
                $m_result,
                array(
                    $r['prop_unit_id'],
                    $sno,
                    $r['unit_name'],
                    $r['floor_no'],
                    $r['area_sqft'],
                    $r['price'],
                    $r['tax_1'],
                    $r['tax_2'],
                    $status,
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

    public function insert_propertyunit($property_id, $post)
    {
        $inserted = false;
        $unit_name = $post["unit_name"];
        $floor_no = $post["floor_no"];
        $area_sqft = $post["area_sqft"];
        $direction = $post["direction"];
        $price = $post["price"];
        $tax1 = $post["tax1"];
        $tax2 = $post["tax2"];
        $description = $post["description"];

        $query = "INSERT INTO property_unit(unit_name,floor_no,area_sqft,direction,price,tax1,tax2,description,property_id) VALUES(?,?,?,?,?,?,?,?,?) ";
        $this->db->query($query, array($unit_name, $floor_no, $area_sqft, $direction, $price, $tax1, $tax2, $description, $property_id));

        $config['title'] = "Property Unit Insert";
        if (!empty($this->db->insertId())) {
            $inserted = true;
            $config['log_text'] = "[ Property Unit successfully created ]";
            $config['ref_link'] = 'erp/inventory/propertyview/' . $property_id;
            $this->session->setFlashdata("op_success", "Property Unit successfully created");
        } else {
            $config['log_text'] = "[ Property Unit failed to create ]";
            $config['ref_link'] = 'erp/inventory/propertyview/' . $property_id;
            $this->session->setFlashdata("op_error", "Property Unit failed to create");
        }
        log_activity($config);
        return $inserted;
    }

    public function update_propertyunit($property_id, $prop_unit_id, $post)
    {
        $updated = false;
        $unit_name = $post["unit_name"];
        $floor_no = $post["floor_no"];
        $area_sqft = $post["area_sqft"];
        $direction = $post["direction"];
        $price = $post["price"];
        $tax1 = $post["tax1"];
        $tax2 = $post["tax2"];
        $description = $post["description"];
        $query = "UPDATE property_unit SET unit_name=? , floor_no=? , area_sqft=? , direction=? , price=? , tax1=? , tax2=? , description=? , property_id=? WHERE prop_unit_id=$prop_unit_id ";
        $this->db->query($query, array($unit_name, $floor_no, $area_sqft, $direction, $price, $tax1, $tax2, $description, $property_id));

        $config['title'] = "Property Unit Update";
        if ($this->db->affectedRows() > 0) {
            $updated = true;
            $config['log_text'] = "[ Property Unit successfully updated ]";
            $config['ref_link'] = 'erp/inventory/property-view/' . $property_id;
            $this->session->setFlashdata("op_success", "Property Unit successfully updated");
        } else {
            $config['log_text'] = "[ Property Unit failed to update ]";
            $config['ref_link'] = 'erp/inventory/property-view/' . $property_id;
            $this->session->setFlashdata("op_error", "Property Unit failed to update");
        }
        log_activity($config);
        return $updated;
    }

    public function get_propertyunit_by_id($prop_unit_id)
    {
        $query = "SELECT * FROM property_unit WHERE prop_unit_id=$prop_unit_id";
        $result = $this->db->query($query)->getRow();
        return $result;
    }

    public function get_propertyunit_export($type,$property_id)
    {
        $columns = array(
            "name" => "unit_name",
            "price" => "price",
            "status" => "status"
        );
        $limit = $_GET['limit'];
        $offset = $_GET['offset'];
        $search = $_GET['search'] ?? "";
        $orderby = isset($_GET['orderby']) ? strtoupper($_GET['orderby']) : "";
        $ordercol = isset($_GET['ordercol']) ? $columns[$_GET['ordercol']] : "";
        $query = "";
        if ($type == "pdf") {
            $query = "SELECT unit_name,floor_no,area_sqft,price,CONCAT(t1.tax_name,' ',t1.percent,'%') AS tax_1,CONCAT(t2.tax_name,' ',t2.percent,'%') AS tax_2,CASE WHEN status=0 THEN 'Not Sold' ELSE 'Sold' END AS status FROM property_unit JOIN taxes t1 ON tax1=t1.tax_id LEFT JOIN taxes t2 ON tax2=t2.tax_id WHERE property_id=$property_id ";
        } else {
            $query = "SELECT unit_name,floor_no,area_sqft,direction,price,CONCAT(t1.tax_name,' ',t1.percent,'%') AS tax_1,CONCAT(t2.tax_name,' ',t2.percent,'%') AS tax_2,CASE WHEN status=0 THEN 'Not Sold' ELSE 'Sold' END AS status,description FROM property_unit JOIN taxes t1 ON tax1=t1.tax_id LEFT JOIN taxes t2 ON tax2=t2.tax_id WHERE property_id=$property_id ";
        }
        $where = true;

        $filter_1_col = isset($_GET['status']) ? $columns['status'] : "";
        $filter_1_val = $_GET['status'];
        if (!empty($filter_1_col) && $filter_1_val !== "") {
            if ($where) {
                $query .= " AND " . $filter_1_col . "=" . $filter_1_val . " ";
            } else {
                $query .= " WHERE " . $filter_1_col . "=" . $filter_1_val . " ";
                $where = true;
            }
        }

        if (!empty($search)) {
            if (!$where) {
                $query .= " WHERE unit_name LIKE '%" . $search . "%' ";
                $where = true;
            } else {
                $query .= " AND unit_name LIKE '%" . $search . "%' ";
            }
        }

        if (!empty($ordercol) && !empty($orderby)) {
            $query .= " ORDER BY " . $ordercol . " " . $orderby;
        }
        $result = $this->db->query($query)->getResultArray();
        return $result;
    }
}
