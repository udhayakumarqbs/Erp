<?php

namespace App\Models\Erp;

use CodeIgniter\Model;
use Exception;

class PropertiesModel extends Model
{
    protected $table      = 'properties';
    protected $primaryKey = 'property_id';

    protected $allowedFields = [
        'name', 'units', 'type_id', 'address', 'city', 'state', 'country', 'zipcode',
        'construct_start', 'construct_end', 'status', 'description', 'created_at', 'created_by'
    ];

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

    public function get_dtconfig_properties()
    {
        $config = array(
            "columnNames" => ["sno", "name", "units", "type", "status", "action"],
            "sortable" => [0, 1, 1, 0, 0, 0],
            "filters" => ["type", "status"]
        );
        return json_encode($config);
    }

    public function get_properties_datatable()
    {
        $columns = array(
            "name" => "name",
            "type" => "propertytype.type_id",
            "status" => "status",
            "units" => "units"
        );
        $limit = $_GET['limit'];
        $offset = $_GET['offset'];
        $search = $_GET['search'] ?? "";
        $orderby = isset($_GET['orderby']) ? strtoupper($_GET['orderby']) : "";
        $ordercol = isset($_GET['ordercol']) ? $columns[$_GET['ordercol']] : "";

        $query = "SELECT property_id,name,units,propertytype.type_name,status FROM properties JOIN propertytype ON properties.type_id=propertytype.type_id ";
        $count = "SELECT COUNT(property_id) AS total FROM properties JOIN propertytype ON properties.type_id=propertytype.type_id ";
        $where = false;

        $filter_1_col = isset($_GET['type']) ? $columns['type'] : "";
        $filter_1_val = $_GET['type'];

        $filter_2_col = isset($_GET['status']) ? $columns['status'] : "";
        $filter_2_val = $_GET['status'];

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

        if (!empty($search)) {
            if (!$where) {
                $query .= " WHERE name LIKE '%" . $search . "%' ";
                $count .= " WHERE name LIKE '%" . $search . "%' ";
                $where = true;
            } else {
                $query .= " AND name LIKE '%" . $search . "%' ";
                $count .= " AND name LIKE '%" . $search . "%' ";
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
                                <li><a href="' . url_to('erp.inventory.propertyview', $r['property_id']) . '" title="View" class="bg-warning" ><i class="fa fa-eye"></i> </a></li>
                                <li><a href="' . url_to('erp.inventory.propertyedit', $r['property_id']) . '" title="Edit" class="bg-success "><i class="fa fa-pencil"></i> </a></li>
                                <li><a href="' . url_to('erp.inventory.propertydelete', $r['property_id']) . '" title="Delete" class="bg-danger del-confirm"><i class="fa fa-trash"></i> </a></li>
                            </ul>
                        </div>
                    </div>';
            $status = "<span class='st " . $this->property_status_bg[$r['status']] . "' >" . $this->property_status[$r['status']] . "</span> ";
            array_push(
                $m_result,
                array(
                    $r['property_id'],
                    $sno,
                    $r['name'],
                    $r['units'],
                    $r['type_name'],
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

    public function get_property_by_id($property_id)
    {
        $query = "SELECT name,units,type_id,status,construct_start,construct_end,address,city,state,country,zipcode,description,GROUP_CONCAT(property_amenity.amenity_id SEPARATOR ',') AS amenities FROM properties LEFT JOIN property_amenity ON properties.property_id=property_amenity.property_id WHERE properties.property_id=$property_id ";
        $result = $this->db->query($query)->getRow();
        return $result;
    }

    public function get_property_export($type)
    {
        $columns = array(
            "name" => "name",
            "type" => "propertytype.type_id",
            "status" => "status",
            "units" => "units"
        );
        $limit = $_GET['limit'];
        $offset = $_GET['offset'];
        $search = $_GET['search'] ?? "";
        $orderby = isset($_GET['orderby']) ? strtoupper($_GET['orderby']) : "";
        $ordercol = isset($_GET['ordercol']) ? $columns[$_GET['ordercol']] : "";

        $query = "";
        if ($type == "pdf") {
            $query = "SELECT name,units,propertytype.type_name,CASE WHEN status=0 THEN 'Under Construction' ELSE 'Completed' END AS status FROM properties JOIN propertytype ON properties.type_id=propertytype.type_id ";
        } else {
            $query = "SELECT name,units,propertytype.type_name,CASE WHEN status=0 THEN 'Under Construction' ELSE 'Completed' END AS status,construct_start,construct_end,address,city,state,country,zipcode,description FROM properties JOIN propertytype ON properties.type_id=propertytype.type_id ";
        }
        $where = false;

        $filter_1_col = isset($_GET['type']) ? $columns['type'] : "";
        $filter_1_val = $_GET['type'];

        $filter_2_col = isset($_GET['status']) ? $columns['status'] : "";
        $filter_2_val = $_GET['status'];

        if (!empty($filter_1_col) && $filter_1_val !== "") {
            if ($where) {
                $query .= " AND " . $filter_1_col . "=" . $filter_1_val . " ";
            } else {
                $query .= " WHERE " . $filter_1_col . "=" . $filter_1_val . " ";
                $where = true;
            }
        }

        if (!empty($filter_2_col) && $filter_2_val !== "") {
            if ($where) {
                $query .= " AND " . $filter_2_col . "=" . $filter_2_val . " ";
            } else {
                $query .= " WHERE " . $filter_2_col . "=" . $filter_2_val . " ";
                $where = true;
            }
        }

        if (!empty($search)) {
            if (!$where) {
                $query .= " WHERE name LIKE '%" . $search . "%' ";
                $where = true;
            } else {
                $query .= " AND name LIKE '%" . $search . "%' ";
            }
        }

        if (!empty($ordercol) && !empty($orderby)) {
            $query .= " ORDER BY " . $ordercol . " " . $orderby;
        }
        $result = $this->db->query($query)->getResultArray();
        return $result;
    }

    public function get_property_for_view($property_id)
    {
        $query = "SELECT properties.property_id,name,units,propertytype.type_name,status,construct_start,construct_end,address,city,state,country,zipcode,description,GROUP_CONCAT(amenity.amenity_name SEPARATOR ',') AS amenities FROM properties JOIN propertytype ON properties.type_id=propertytype.type_id LEFT JOIN property_amenity ON properties.property_id=property_amenity.property_id LEFT JOIN amenity ON property_amenity.amenity_id=amenity.amenity_id WHERE properties.property_id=$property_id ";
        $result = $this->db->query($query)->getRow();
        return $result;
    }

    public function get_property_status_bg()
    {
        return $this->property_status_bg;
    }


    public function get_property_status()
    {
        return $this->property_status;
    }


    public function insert_property($post, $amenity)
    {
        $inserted = false;
        $name = $post["name"];
        $units = $post["units"];
        $status = $post["status"];
        $type_id = $post["type_id"];
        $address = $post["address"];
        $city = $post["city"];
        $state = $post["state"];
        $country = $post["country"];
        $zipcode = $post["zipcode"];
        $construct_start = $post["construct_start"];
        $construct_end = $post["construct_end"];
        $description = $post["description"];
        $created_by = get_user_id();

        $this->db->transBegin();
        $query = "INSERT INTO properties(name,units,status,type_id,address,city,state,country,zipcode,construct_start,construct_end,description,created_at,created_by) VALUES(?,?,?,?,?,?,?,?,?,?,?,?,?,?) ";
        $this->db->query($query, array($name, $units, $status, $type_id, $address, $city, $state, $country, $zipcode, $construct_start, $construct_end, $description, time(), $created_by));
        $property_id = $this->db->insertId();

        if (empty($property_id)) {
            $this->session->setFlashdata("op_error", "Property failed to create");
            return $inserted;
        }
        if (!$this->update_property_amenity($property_id, $amenity)) {
            $this->db->transRollback();
            $this->session->setFlashdata("op_error", "Property failed to create");
            return $inserted;
        }
        $this->db->transComplete();
        $config['title'] = "Property Insert";
        if ($this->db->transStatus() === FALSE) {
            $this->db->transRollback();
            $config['ref_link'] = "";
            $config['log_text'] = "[ Property failed to create ]";
            $this->session->setFlashdata("op_error", "Property failed to create");
        } else {
            $this->db->transCommit();
            $inserted = true;
            $config['ref_link'] = "erp/inventory/property-view/" . $property_id;
            $config['log_text'] = "[ Property successfully created ]";
            $this->session->setFlashdata("op_success", "Property successfully created");
        }
        log_activity($config);
        return $inserted;
    }

    private function update_property_amenity($property_id, $amenity)
    {
        $updated = true;
        $amenities = explode(",", $amenity);
        if (!empty($amenities)) {
            $result = $this->db->query("SELECT amenity_id FROM property_amenity WHERE property_id=$property_id")->getResultArray();
            $old_set = array();
            foreach ($result as $row) {
                array_push($old_set, $row['amenity_id']);
            }
            $intersect = array_intersect($amenities, $old_set);
            $del_set = array();
            $ins_set = array();
            foreach ($amenities as $value) {
                if (!in_array($value, $intersect)) {
                    array_push($ins_set, $value);
                }
            }
            foreach ($old_set as $value) {
                if (!in_array($value, $intersect)) {
                    array_push($del_set, $value);
                }
            }
            $query = "INSERT INTO property_amenity(property_id,amenity_id) VALUES(?,?) ";
            foreach ($ins_set as $amenity_id) {
                $this->db->query($query, array($property_id, $amenity_id));
                if (empty($this->db->insertId())) {
                    $updated = false;
                    break;
                }
            }
            if ($updated && !empty($del_set)) {
                $del_amenity_ids = implode(",", $del_set);
                $query = "DELETE FROM property_amenity WHERE property_id=$property_id AND amenity_id IN (" . $del_amenity_ids . ") ";
                $this->db->query($query);
                if ($this->db->affectedRows() <= 0) {
                    $updated = false;
                }
            }
        }
        return $updated;
    }

    public function update_property($property_id, $post, $amenity)
    {
        $updated = false;
        $name = $post["name"];
        $units = $post["units"];
        $status = $post["status"];
        $type_id = $post["type_id"];
        $address = $post["address"];
        $city = $post["city"];
        $state = $post["state"];
        $country = $post["country"];
        $zipcode = $post["zipcode"];
        $construct_start = $post["construct_start"];
        $construct_end = $post["construct_end"];
        $description = $post["description"];

        $this->db->transBegin();
        $query = "UPDATE properties SET name=? , units=? , status=? , type_id=? , address=? , city=? , state=? , country=? , zipcode=? , construct_start=? , construct_end=? , description=? WHERE property_id=$property_id ";
        $this->db->query($query, array($name, $units, $status, $type_id, $address, $city, $state, $country, $zipcode, $construct_start, $construct_end, $description));

        if (!$this->update_property_amenity($property_id, $amenity)) {
            $this->db->transRollback();
            $this->session->setFlashdata("op_error", "Property failed to update");
            return $updated;
        }
        $this->db->transComplete();
        $config['title'] = "Property Update";
        $config['ref_link'] = "erp/inventory/property-view/" . $property_id;
        if ($this->db->transStatus() === FALSE) {
            $this->db->transRollback();
            $config['log_text'] = "[ Property failed to update ]";
            $this->session->setFlashdata("op_error", "Property failed to update");
        } else {
            $this->db->transCommit();
            $updated = true;
            $config['log_text'] = "[ Property successfully updated ]";
            $this->session->setFlashdata("op_success", "Property successfully updated");
        }
        log_activity($config);
        return $updated;
    }

    public function propertyDelete($property_id)
    {
        try {
            $this->db->transStart();

            $this->db->table('properties')
                ->where('property_id', $property_id)
                ->delete();
            $this->db->table('property_unit')
                ->where('property_id', $property_id)
                ->delete();

            $attach_ids = $this->db->table('attachments')
                ->select('attach_id')
                ->where('related_id', $property_id)
                ->get()
                ->getResult();

            if (!empty($attach_ids)) {
                $attach_id = $attach_ids[0]->attach_id;
                $this->attachmentModel->delete_attachment("property", $attach_id);
            }

            $this->db->transComplete();

            if ($this->db->transStatus() === false) {
                throw new Exception("Transaction failed");
            }
            return $this->session->setFlashdata("op_success", "Property and related data deleted successfully");
        } catch (\Exception $e) {
            return $e;
            // return $this->session->setFlashdata("op_error", "Error deleting Property and related data");
        }
    }
}
