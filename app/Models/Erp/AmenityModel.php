<?php

namespace App\Models\Erp;

use CodeIgniter\Model;

class AmenityModel extends Model
{
    protected $table      = 'amenity';
    protected $primaryKey = 'amenity_id';

    protected $allowedFields = ['amenity_name', 'created_at', 'created_by'];

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
    public function insert_update_amenity($amenity_id, $amenity_name)
    {
        if (empty($amenity_id)) {
            $this->insert_amenity($amenity_name);
        } else {
            $this->update_amenity($amenity_id, $amenity_name);
        }
    }

    private function insert_amenity($amenity_name)
    {
        $created_by = get_user_id();
        $query = "INSERT INTO amenity(amenity_name,created_at,created_by) VALUES(?,?,?) ";
        $this->db->query($query, array($amenity_name, time(), $created_by));

        $config['title'] = "Amenity Insert";
        if (!empty($this->db->insertId())) {
            $config['log_text'] = "[ Amenity successfully created ]";
            $config['ref_link'] = 'erp/inventory/amenity';
            $this->session->setFlashdata("op_success", "Amenity successfully created");
        } else {
            $config['log_text'] = "[ Amenity failed to create ]";
            $config['ref_link'] = 'erp/inventory/amenity';
            $this->session->setFlashdata("op_error", "Amenity failed to create");
        }
        log_activity($config);
    }

    private function update_amenity($amenity_id, $amenity_name)
    {
        // $amenity_name = $this->input->post("amenity_name");
        $query = "UPDATE amenity SET amenity_name=? WHERE amenity_id=$amenity_id";
        $this->db->query($query, array($amenity_name));

        $config['title'] = "Amenity Update";
        if ($this->db->affectedRows() > 0) {
            $config['log_text'] = "[ Amenity successfully updated ]";
            $config['ref_link'] = 'erp/inventory/amenity';
            $this->session->setFlashdata("op_success", "Amenity successfully updated");
        } else {
            $config['log_text'] = "[ Amenity failed to update ]";
            $config['ref_link'] = 'erp/inventory/amenity';
            $this->session->setFlashdata("op_error", "Amenity failed to update");
        }
        log_activity($config);
    }

    public function delete_amenity($amenity_id)
    {
        $this->where('amenity_id', $amenity_id)->delete();
    }

    public function get_dtconfig_amenity()
    {
        $config = array(
            "columnNames" => ["sno", "amenity name", "action"],
            "sortable" => [0, 1, 0]
        );
        return json_encode($config);
    }

    public function get_amenity_datatable()
    {
        $columns = array(
            "amenity name" => "amenity_name",
        );
        $limit = $_GET['limit'];
        $offset = $_GET['offset'];
        $search = $_GET['search'] ?? "";
        $orderby = isset($_GET['orderby']) ? strtoupper($_GET['orderby']) : "";
        $ordercol = isset($_GET['ordercol']) ? $columns[$_GET['ordercol']] : "";

        $query = "SELECT amenity_id,amenity_name FROM amenity ";
        $count = "SELECT COUNT(amenity_id) AS total FROM amenity ";
        $where = false;

        if (!empty($search)) {
            if (!$where) {
                $query .= " WHERE amenity_name LIKE '%" . $search . "%' ";
                $count .= " WHERE amenity_name LIKE '%" . $search . "%' ";
                $where = true;
            } else {
                $query .= " AND amenity_name LIKE '%" . $search . "%' ";
                $count .= " AND amenity_name LIKE '%" . $search . "%' ";
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
                                <li><a href="#" data-ajax-url="' . base_url() . 'erp/inventory/amenity-fetch/' . $r['amenity_id'] . '" title="Edit" class="bg-success modalBtn"><i class="fa fa-pencil"></i> </a></li>
                                <li><a href="' . base_url() . 'erp/inventory/amenity-delete/' . $r['amenity_id'] . '" title="Delete" class="bg-danger del-confirm"><i class="fa fa-trash"></i> </a></li>
                            </ul>
                        </div>
                    </div>';
            array_push(
                $m_result,
                array(
                    $r['amenity_id'],
                    $sno,
                    $r['amenity_name'],
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

    public function get_property_status()
    {
        return $this->property_status;
    }

    public function get_all_amenities()
    {
        $query = "SELECT amenity_id,amenity_name FROM amenity";
        $result = $this->db->query($query)->getResultArray();
        return $result;
    }


    public function get_amenity_export($type)
    {
        $columns = array(
            "amenity name" => "amenity_name",
        );
        $limit = $_GET['limit'];
        $offset = $_GET['offset'];
        $search = $_GET['search'] ?? "";
        $orderby = isset($_GET['orderby']) ? strtoupper($_GET['orderby']) : "";
        $ordercol = isset($_GET['ordercol']) ? $columns[$_GET['ordercol']] : "";

        $query = "SELECT amenity_name FROM amenity ";
        $where = false;

        if (!empty($search)) {
            if (!$where) {
                $query .= " WHERE amenity_name LIKE '%" . $search . "%' ";
                $where = true;
            } else {
                $query .= " AND amenity_name LIKE '%" . $search . "%' ";
            }
        }

        if (!empty($ordercol) && !empty($orderby)) {
            $query .= " ORDER BY " . $ordercol . " " . $orderby;
        }
        $result = $this->db->query($query)->getResultArray();
        return $result;
    }
}
