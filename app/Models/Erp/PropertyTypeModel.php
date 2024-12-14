<?php

namespace App\Models\Erp;

use CodeIgniter\Model;

class PropertyTypeModel extends Model
{
    protected $table      = 'propertytype';
    protected $primaryKey = 'type_id';

    protected $allowedFields = ['type_name', 'created_at', 'created_by'];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = '';

    protected $session;
    protected $db;
    protected $logger;
    public function __construct()
    {
        $this->session = \Config\Services::session();
        $this->db = \Config\Database::connect();
        $this->logger = \Config\Services::logger();
    }


    public function insert_update_propertytype($type_id,$type_name)
    {
        // $type_id = $this->request->getPost("type_id");
        if (empty($type_id)) {
            $this->insert_propertytype($type_name);
        } else {
            $this->update_propertytype($type_id,$type_name);
        }
    }

    private function insert_propertytype($type_name)
    {
        // $type_name = $this->request->getPost("type_name");
        $created_by = get_user_id();
        $query = "INSERT INTO propertytype(type_name,created_at,created_by) VALUES(?,?,?) ";
        $this->db->query($query, array($type_name, time(), $created_by));

        $config['title'] = "Property Type Insert";
        if (!empty($this->db->insertId())) {
            $config['log_text'] = "[ Property Type successfully created ]";
            $config['ref_link'] = 'erp/inventory/propertytype';
            $this->session->setFlashdata("op_success", "Property Type successfully created");
        } else {
            $config['log_text'] = "[ Property Type failed to create ]";
            $config['ref_link'] = 'erp/inventory/propertytype';
            $this->session->setFlashdata("op_error", "Property Type failed to create");
        }
        log_activity($config);
    }

    private function update_propertytype($type_id,$type_name)
    {
        // $type_name = $this->request->getPost("type_name");
        $query = "UPDATE propertytype SET type_name=? WHERE type_id=$type_id";
        $this->db->query($query, array($type_name));

        $config['title'] = "Property Type Update";
        if ($this->db->affectedRows() > 0) {
            $config['log_text'] = "[ Property Type successfully updated ]";
            $config['ref_link'] = 'erp/inventory/propertytype';
            $this->session->setFlashdata("op_success", "Property Type successfully updated");
        } else {
            $config['log_text'] = "[ Property Type failed to update ]";
            $config['ref_link'] = 'erp/inventory/propertytype';
            $this->session->setFlashdata("op_error", "Property Type failed to update");
        }
        log_activity($config);
    }

    public function delete_propertytype($type_id)
    {
        $query = "DELETE FROM propertytype
        WHERE type_id =".$type_id;
        if($this->db->query($query)){
            
            return true;
        }
    }

    public function get_all_propertytype()
    {
        $query = "SELECT type_id,type_name FROM propertytype";
        $result = $this->db->query($query)->getResultArray();
        return $result;
    }

    public function get_dtconfig_propertytype()
    {
        $config = array(
            "columnNames" => ["sno", "type name", "action"],
            "sortable" => [0, 1, 0]
        );
        return json_encode($config);
    }

    public function get_propertytype_datatable()
    {
        $columns = array(
            "type name" => "type_name",
        );
        $limit = $_GET['limit'];
        $offset = $_GET['offset'];
        $search = $_GET['search'] ?? "";
        $orderby = isset($_GET['orderby']) ? strtoupper($_GET['orderby']) : "";
        $ordercol = isset($_GET['ordercol']) ? $columns[$_GET['ordercol']] : "";

        $query = "SELECT type_id,type_name FROM propertytype ";
        $count = "SELECT COUNT(type_id) AS total FROM propertytype ";
        $where = false;

        if (!empty($search)) {
            if (!$where) {
                $query .= " WHERE type_name LIKE '%" . $search . "%' ";
                $count .= " WHERE type_name LIKE '%" . $search . "%' ";
                $where = true;
            } else {
                $query .= " AND type_name LIKE '%" . $search . "%' ";
                $count .= " AND type_name LIKE '%" . $search . "%' ";
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
                                <li><a href="#" data-ajax-url="' . url_to('erp.inventory.ajaxfetchpropertytype', $r['type_id']) . '" title="Edit" class="bg-success modalBtn"><i class="fa fa-pencil"></i> </a></li>
                                <li><a href="' . url_to('erp.inventory.propertytypedelete', $r['type_id']) . '" title="Delete" class="bg-danger del-confirm"><i class="fa fa-trash"></i> </a></li>
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

    public function get_propertytype_export($type)
    {
        $columns = array(
            "type name" => "type_name",
        );
        $limit = $_GET['limit'];
        $offset = $_GET['offset'];
        $search = $_GET['search'] ?? "";
        $orderby = isset($_GET['orderby']) ? strtoupper($_GET['orderby']) : "";
        $ordercol = isset($_GET['ordercol']) ? $columns[$_GET['ordercol']] : "";

        $query = "SELECT type_name FROM propertytype ";
        $where = false;

        if (!empty($search)) {
            if (!$where) {
                $query .= " WHERE type_name LIKE '%" . $search . "%' ";
                $where = true;
            } else {
                $query .= " AND type_name LIKE '%" . $search . "%' ";
            }
        }

        if (!empty($ordercol) && !empty($orderby)) {
            $query .= " ORDER BY " . $ordercol . " " . $orderby;
        }

        $result = $this->db->query($query)->getResultArray();
        return $result;
    }
}
