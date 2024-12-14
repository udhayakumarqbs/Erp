<?php

namespace App\Models\Erp;

use CodeIgniter\Model;

class AdditionsModel extends Model
{
    protected $table = 'additions';
    protected $primaryKey = 'add_id';

    protected $allowedFields = [
        'name',
        'description',
        'type',
        'value',
        'created_at',
        'created_by',
    ];
    protected $db;
    protected $session;

    public function __construct()
    {
        $this->db = \Config\Database::connect();
        $this->session = \Config\Services::session();
    }
    private $amt_type = array(
        0 => "Percent",
        1 => "Amount"
    );

    public function get_amt_type()
    {
        return $this->amt_type;
    }
    public function get_dtconfig_additions()
    {
        $config = array(
            "columnNames" => ["sno", "name", "type", "value", "action"],
            "sortable" => [0, 1, 0, 0, 0]
        );
        return json_encode($config);
    }

    public function get_addition_datatable()
    {
        $columns = array(
            "name" => "name"
        );
        $limit = $_GET['limit'];
        $offset = $_GET['offset'];
        $search = $_GET['search'] ?? "";
        $orderby = isset($_GET['orderby']) ? strtoupper($_GET['orderby']) : "";
        $ordercol = isset($_GET['ordercol']) ? $columns[$_GET['ordercol']] : "";

        $query = "SELECT add_id,name,type,value FROM additions ";
        $count = "SELECT COUNT(add_id) AS total FROM additions ";
        $where = false;

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
                                <li><a href="#" data-ajax-url="' . url_to('erp.hr.ajaxfetchaddition', $r['add_id']) . '" title="Edit" class="bg-success modalBtn"><i class="fa fa-pencil"></i> </a></li>
                                <li><a href="' . url_to('erp.hr.additiondelete', $r['add_id']) . '" title="Delete" class="bg-danger del-confirm"><i class="fa fa-trash"></i> </a></li>
                            </ul>
                        </div>
                    </div>';
            array_push(
                $m_result,
                array(
                    $r['add_id'],
                    $sno,
                    $r['name'],
                    $this->amt_type[$r['type']],
                    $r['value'],
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

    public function get_addition_export($type)
    {
        $columns = array(
            "name" => "name"
        );
        $limit = $_GET['limit'];
        $offset = $_GET['offset'];
        $search = $_GET['search'] ?? "";
        $orderby = isset($_GET['orderby']) ? strtoupper($_GET['orderby']) : "";
        $ordercol = isset($_GET['ordercol']) ? $columns[$_GET['ordercol']] : "";

        $query = "SELECT name,description,CASE WHEN type=0 THEN 'Percent' ELSE 'Amount' END AS type,value FROM additions ";
        $where = false;

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

    public function get_all_additions()
    {
        $query = "SELECT add_id,name FROM additions";
        $result = $this->db->query($query)->getResultArray();
        return $result;
    }

    public function insert_update_addition($add_id, $post)
    {
        if (empty($add_id)) {
            $this->insert_addition($post);
        } else {
            $this->update_addition($add_id, $post);
        }
    }

    private function insert_addition($post)
    {
        $name = $post["name"];
        $description = $post["description"];
        $type = $post["type"];
        $value = $post["value"];
        $created_by = get_user_id();

        $query = "INSERT INTO additions(name,description,type,value,created_at,created_by) VALUES(?,?,?,?,?,?) ";
        $this->db->query($query, array($name, $description, $type, $value, time(), $created_by));

        $config['title'] = "Addition Insert";
        if (!empty($this->db->insertId())) {
            $config['log_text'] = "[ Addition successfully created ]";
            $config['ref_link'] = 'erp/hr/additions/';
            $this->session->setFlashdata("op_success", "Addition successfully created");
        } else {
            $config['log_text'] = "[ Addition failed to create ]";
            $config['ref_link'] = 'erp/hr/additions/';
            $this->session->setFlashdata("op_error", "Addition failed to create");
        }
        log_activity($config);
    }

    private function update_addition($add_id, $post)
    {
        $name = $post["name"];
        $description = $post["description"];
        $type = $post["type"];
        $value = $post["value"];

        $query = "UPDATE additions SET name=? , description=? , type=? , value=? WHERE add_id=$add_id ";
        $this->db->query($query, array($name, $description, $type, $value));

        $config['title'] = "Addition Update";
        if ($this->db->affectedRows() > 0) {
            $config['log_text'] = "[ Addition successfully updated ]";
            $config['ref_link'] = 'erp/hr/additions/';
            $this->session->setFlashdata("op_success", "Addition successfully updated");
        } else {
            $config['log_text'] = "[ Addition failed to update ]";
            $config['ref_link'] = 'erp/hr/additions/';
            $this->session->setFlashdata("op_error", "Addition failed to update");
        }
        log_activity($config);
    }

    public function delete_addition($add_id)
    {
        $query = "DELETE FROM additions WHERE add_id=$add_id";
        $this->db->query($query);
        $config['title'] = "Addition Delete";
        if ($this->db->affectedRows() > 0) {
            $config['log_text'] = "[ Addition successfully deleted ]";
            $config['ref_link'] = 'erp/hr/additions/';
            $this->session->setFlashdata("op_success", "Addition successfully deleted");
        } else {
            $config['log_text'] = "[ Addition failed to delete ]";
            $config['ref_link'] = 'erp/hr/additions/';
            $this->session->setFlashdata("op_error", "Addition failed to delete");
        }
        log_activity($config);
    }
}
