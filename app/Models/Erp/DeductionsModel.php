<?php

namespace App\Models\Erp;

use CodeIgniter\Model;

class DeductionsModel extends Model
{
    protected $table = 'deductions';
    protected $primaryKey = 'deduct_id';

    protected $allowedFields = [
        'name',
        'description',
        'type',
        'value',
        'created_at',
        'created_by'
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
    public function get_dtconfig_deductions()
    {
        $config = array(
            "columnNames" => ["sno", "name", "type", "value", "action"],
            "sortable" => [0, 1, 0, 0, 0]
        );
        return json_encode($config);
    }

    public function get_deduction_datatable()
    {
        $columns = array(
            "name" => "name"
        );
        $limit = $_GET['limit'];
        $offset = $_GET['offset'];
        $search = $_GET['search'] ?? "";
        $orderby = isset($_GET['orderby']) ? strtoupper($_GET['orderby']) : "";
        $ordercol = isset($_GET['ordercol']) ? $columns[$_GET['ordercol']] : "";

        $query = "SELECT deduct_id,name,type,value FROM deductions ";
        $count = "SELECT COUNT(deduct_id) AS total FROM deductions ";
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
                                <li><a href="#" data-ajax-url="' . url_to('erp.hr.ajaxfetchdeduction', $r['deduct_id']) . '" title="Edit" class="bg-success modalBtn"><i class="fa fa-pencil"></i> </a></li>
                                <li><a href="' . url_to('erp.hr.deductiondelete', $r['deduct_id']) . '" title="Delete" class="bg-danger del-confirm"><i class="fa fa-trash"></i> </a></li>
                            </ul>
                        </div>
                    </div>';
            array_push(
                $m_result,
                array(
                    $r['deduct_id'],
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

    public function get_deduction_export($type)
    {
        $columns = array(
            "name" => "name"
        );
        $limit = $_GET['limit'];
        $offset = $_GET['offset'];
        $search = $_GET['search'] ?? "";
        $orderby = isset($_GET['orderby']) ? strtoupper($_GET['orderby']) : "";
        $ordercol = isset($_GET['ordercol']) ? $columns[$_GET['ordercol']] : "";

        $query = "SELECT name,description,CASE WHEN type=0 THEN 'Percent' ELSE 'Amount' END AS type,value FROM deductions ";
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

    public function insert_update_deduction($deduct_id, $post)
    {

        if (empty($deduct_id)) {
            $this->insert_deduction($post);
        } else {
            $this->update_deduction($deduct_id, $post);
        }
    }

    private function insert_deduction($post)
    {
        $name = $post["name"];
        $description = $post["description"];
        $type = $post["type"];
        $value = $post["value"];
        $created_by = get_user_id();

        $query = "INSERT INTO deductions(name,description,type,value,created_at,created_by) VALUES(?,?,?,?,?,?) ";
        $this->db->query($query, array($name, $description, $type, $value, time(), $created_by));

        $config['title'] = "Deduction Insert";
        if (!empty($this->db->insertId())) {
            $config['log_text'] = "[ Deduction successfully created ]";
            $config['ref_link'] = 'erp/hr/deductions/';
            $this->session->setFlashdata("op_success", "Deduction successfully created");
        } else {
            $config['log_text'] = "[ Deduction failed to create ]";
            $config['ref_link'] = 'erp/hr/deductions/';
            $this->session->setFlashdata("op_error", "Deduction failed to create");
        }
        log_activity($config);
    }

    private function update_deduction($deduct_id, $post)
    {
        $name = $post["name"];
        $description = $post["description"];
        $type = $post["type"];
        $value = $post["value"];
        $created_by = get_user_id();

        $query = "UPDATE deductions SET name=? , description=? , type=? , value=? WHERE deduct_id=$deduct_id ";
        $this->db->query($query, array($name, $description, $type, $value));

        $config['title'] = "Deduction Update";
        if ($this->db->affectedRows() > 0) {
            $config['log_text'] = "[ Deduction successfully updated ]";
            $config['ref_link'] = 'erp/hr/deductions/';

            $this->session->setFlashdata("op_success", "Deduction successfully updated");
        } else {
            $config['log_text'] = "[ Deduction failed to update ]";
            $config['ref_link'] = 'erp/hr/deductions/';
            $this->session->setFlashdata("op_error", "Deduction failed to update");
        }
        log_activity($config);
    }

    public function get_all_deductions()
    {
        $query = "SELECT deduct_id,name FROM deductions";
        $result = $this->db->query($query)->getResultArray();
        return $result;
    }

    public function delete_deductions($deduct_id)
    {
        $query = "DELETE FROM deductions WHERE deduct_id=$deduct_id";
        $this->db->query($query);
        $config['title'] = "Deduction Delete";
        if ($this->db->affectedRows() > 0) {
            $config['log_text'] = "[ Deduction successfully deleted ]";
            $config['ref_link'] = 'erp/hr/deductions/';
            $this->session->setFlashdata("op_success", "Deduction successfully deleted");
        } else {
            $config['log_text'] = "[ Deduction failed to delete ]";
            $config['ref_link'] = 'erp/hr/deductions/';
            $this->session->setFlashdata("op_error", "Deduction failed to delete");
        }
        log_activity($config);
    }
}
