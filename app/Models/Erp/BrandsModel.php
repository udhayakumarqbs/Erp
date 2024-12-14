<?php

namespace App\Models\Erp;

use CodeIgniter\Model;

class BrandsModel extends Model

{
    protected $table      = 'brands';
    protected $primaryKey = 'brand_id';

    protected $allowedFields = ['brand_name', 'created_at', 'created_by'];

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


    public function get_brands()
    {
        $query = "SELECT brand_id,brand_name FROM brands";
        $result = $this->db->query($query)->getResultArray();
        return $result;
    }

    public function insert_update_brand($brand_id, $brand_name)
    {
        if (empty($brand_id)) {
            $this->insert_brand($brand_name);
        } else {
            $this->update_brand($brand_id, $brand_name);
        }
    }

    private function insert_brand($brand_name)
    {
        $created_by = get_user_id();
        $query = "INSERT INTO brands(brand_name,created_at,created_by) VALUES(?,?,?) ";
        $this->db->query($query, array($brand_name, time(), $created_by));

        $config['title'] = "Brand Insert";
        if (!empty($this->db->insertId())) {
            $config['log_text'] = "[ Brand successfully created ]";
            $config['ref_link'] = 'erp/inventory/brands';
            $this->session->setFlashdata("op_success", "Brand successfully created");
        } else {
            $config['log_text'] = "[ Brand failed to create ]";
            $config['ref_link'] = 'erp/inventory/brands';
            $this->session->setFlashdata("op_error", "Brand failed to create");
        }
        log_activity($config);
    }

    private function update_brand($brand_id, $brand_name)
    {
        $query = "UPDATE brands SET brand_name=? WHERE brand_id=$brand_id ";
        $this->db->query($query, array($brand_name));

        $config['title'] = "Brand Update";
        if ($this->db->affectedRows() > 0) {
            $config['log_text'] = "[ Brand successfully updated ]";
            $config['ref_link'] = 'erp/inventory/brands';
            $this->session->setFlashdata("op_success", "Brand successfully updated");
        } else {
            $config['log_text'] = "[ Brand failed to update ]";
            $config['ref_link'] = 'erp/inventory/brands';
            $this->session->setFlashdata("op_error", "Brand failed to update");
        }
        log_activity($config);
    }

    public function get_dtconfig_brands()
    {
        $config = array(
            "columnNames" => ["sno", "brand name", "action"],
            "sortable" => [0, 1, 0]
        );
        return json_encode($config);
    }

    public function get_brands_datatable()
    {
        $columns = array(
            "brand name" => "brand_name",
        );
        $limit = $_GET['limit'];
        $offset = $_GET['offset'];
        $search = $_GET['search'] ?? "";
        $orderby = isset($_GET['orderby']) ? strtoupper($_GET['orderby']) : "";
        $ordercol = isset($_GET['ordercol']) ? $columns[$_GET['ordercol']] : "";

        $query = "SELECT brand_id,brand_name FROM brands ";
        $count = "SELECT COUNT(brand_id) AS total FROM brands ";
        $where = false;

        if (!empty($search)) {
            if (!$where) {
                $query .= " WHERE brand_name LIKE '%" . $search . "%' ";
                $count .= " WHERE brand_name LIKE '%" . $search . "%' ";
                $where = true;
            } else {
                $query .= " AND brand_name LIKE '%" . $search . "%' ";
                $count .= " AND brand_name LIKE '%" . $search . "%' ";
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
                                <li><a href="#" data-ajax-url="' . url_to('erp.inventory.ajaxfetchbrand' , $r['brand_id']) . '" title="Edit" class="bg-success modalBtn"><i class="fa fa-pencil"></i> </a></li>
                                <li><a href="' . url_to('erp.inventory.branddelete' , $r['brand_id']) . '" title="Delete" class="bg-danger del-confirm"><i class="fa fa-trash"></i> </a></li>
                            </ul>
                        </div>
                    </div>';
            array_push(
                $m_result,
                array(
                    $r['brand_id'],
                    $sno,
                    $r['brand_name'],
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

    public function delete_brand($brand_id)
    {
        $check1 = $this->db->query("SELECT brand_id FROM raw_materials WHERE brand_id=$brand_id LIMIT 1")->getRow();
        if (empty($check1)) {
            $check2 = $this->db->query("SELECT brand_id FROM semi_finished WHERE brand_id=$brand_id LIMIT 1")->getRow();
            if (empty($check2)) {
                $check3 = $this->db->query("SELECT brand_id FROM finished_goods WHERE brand_id=$brand_id LIMIT 1")->getRow();
                if (empty($check3)) {
                    $query = "DELETE FROM brands WHERE brand_id=$brand_id";
                    $this->db->query($query);
                    $config['title'] = "Brand Delete";
                    if ($this->db->affectedRows() > 0) {
                        $config['log_text'] = "[ Brand successfully deleted ]";
                        $config['ref_link'] = 'erp/inventory/brands';
                        $this->session->setFlashdata("op_success", "Brand successfully deleted");
                    } else {
                        $config['log_text'] = "[ Brand failed to delete ]";
                        $config['ref_link'] = 'erp/inventory/brands';
                        $this->session->setFlashdata("op_error", "Brand failed to delete");
                    }
                    log_activity($config);
                } else {
                    $this->session->setFlashdata("op_error", "Brand is already in use");
                }
            } else {
                $this->session->setFlashdata("op_error", "Brand is already in use");
            }
        } else {
            $this->session->setFlashdata("op_error", "Brand is already in use");
        }
    }

    public function get_brands_export($type)
    {
        $columns = array(
            "brand name" => "brand_name",
        );
        $limit = $_GET['limit'];
        $offset = $_GET['offset'];
        $search = $_GET['search'] ?? "";
        $orderby = isset($_GET['orderby']) ? strtoupper($_GET['orderby']) : "";
        $ordercol = isset($_GET['ordercol']) ? $columns[$_GET['ordercol']] : "";

        $query = "SELECT brand_name FROM brands ";
        $where = false;

        if (!empty($search)) {
            if (!$where) {
                $query .= " WHERE brand_name LIKE '%" . $search . "%' ";
                $where = true;
            } else {
                $query .= " AND brand_name LIKE '%" . $search . "%' ";
            }
        }

        if (!empty($ordercol) && !empty($orderby)) {
            $query .= " ORDER BY " . $ordercol . " " . $orderby;
        }

        $result = $this->db->query($query)->getResultArray();
        return $result;
    }
}
