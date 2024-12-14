<?php

namespace App\Models\Erp;

use CodeIgniter\Model;

class TransportModel extends Model
{
    protected $table = 'transports';
    protected $primaryKey = 'transport_id';
    protected $allowedFields = [
        'name',
        'type_id',
        'code',
        'active',
        'status',
        'delivery_count',
        'description',
        'created_at',
        'created_by'
    ];
    protected $db;
    protected $session;
    public function __construct()
    {
        $this->db = \Config\Database::connect();
        $this->session = \Config\Services::session();
        helper(['erp', 'form']);
    }
    private $transport_status = array(
        0 => "Free",
        1 => "Booked"
    );

    private $transport_status_bg = array(
        0 => "st_success",
        1 => "st_danger"
    );

    public function get_transport_status()
    {
        return $this->transport_status;
    }

    public function get_transport_status_bg()
    {
        return $this->transport_status_bg;
    }
    public function insert_update_transport($transport_id, $post)
    {

        if (empty($transport_id)) {
            $this->insert_transport($post);
        } else {
            $this->update_transport($transport_id, $post);
        }
    }

    public function insert_transport($post)
    {
        $name = $post["name"];
        $code = $post["code"];
        $description = $post["description"];
        $type_id = $post["type_id"];
        $created_by = get_user_id();
        $query = "INSERT INTO transports(name,code,description,type_id,created_at,created_by) VALUES(?,?,?,?,?,?) ";
        $this->db->query($query, array($name, $code, $description, $type_id, time(), $created_by));
        $id = $this->db->insertId();

        $config['title'] = "Transport Insert";
        if (!empty($id)) {
            $config['log_text'] = "[ Transport successfully created ]";
            $config['ref_link'] = '';
            $this->session->setFlashdata("op_success", "Transport successfully created");
        } else {
            $config['log_text'] = "[ Transport failed to create ]";
            $config['ref_link'] = '';
            $this->session->setFlashdata("op_error", "Transport failed to create");
        }
        log_activity($config);
    }

    public function update_transport($transport_id, $post)
    {
        $name = $post["name"];
        $code = $post["code"];
        $description = $post["description"];
        $type_id = $post["type_id"];
        $query = "UPDATE transports SET name=? , code=? , description=? , type_id=? WHERE transport_id=$transport_id ";
        $this->db->query($query, array($name, $code, $description, $type_id));

        $config['title'] = "Transport Update";
        if ($this->db->affectedRows() > 0) {
            $config['log_text'] = "[ Transport successfully updated ]";
            $config['ref_link'] = '';
            $this->session->setFlashdata("op_success", "Transport successfully updated");
        } else {
            $config['log_text'] = "[ Transport failed to update ]";
            $config['ref_link'] = '';
            $this->session->setFlashdata("op_error", "Transport failed to update");
        }
        log_activity($config);
    }
    public function delete_type($type_id)
    {
        $check = $this->db->query("SELECT COUNT(type_id) AS total FROM transports WHERE type_id=$type_id")->getRow();
        if ($check->total > 0) {
            $this->session->setFlashdata("op_error", "Transport Type is used in Transports");
        } else {
            $query = "DELETE FROM transport_type WHERE type_id=$type_id";
            $this->db->query($query);
            $config['title'] = "Transport Type Delete";
            if ($this->db->affectedRows() > 0) {
                $config['log_text'] = "[ Transport Type successfully deleted ]";
                $config['ref_link'] = '';
                $this->session->setFlashdata("op_success", "Transport Type successfully deleted");
            } else {
                $config['log_text'] = "[ Transport Type failed to delete ]";
                $config['ref_link'] = '';
                $this->session->setFlashdata("op_error", "Transport Type failed to delete");
            }
            log_activity($config);
        }
    }

    public function get_dtconfig_transports()
    {
        $config = array(
            "columnNames" => ["sno", "name", "code", "type", "status", "active", "action"],
            "sortable" => [0, 1, 1, 0, 0, 0, 0],
            "filters" => ["type", "status"]
        );
        return json_encode($config);
    }

    public function get_datatable_transports()
    {
        $columns = array(
            "name" => "name",
            "code" => "code",
            "type" => "transport_type.type_id",
            "status" => "status"
        );
        $limit = $_GET['limit'];
        $offset = $_GET['offset'];
        $search = $_GET['search'] ?? "";
        $orderby = isset($_GET['orderby']) ? strtoupper($_GET['orderby']) : "";
        $ordercol = isset($_GET['ordercol']) ? $columns[$_GET['ordercol']] : "";
        $query = "SELECT transport_id,name,code,transport_type.type_name,status,active FROM transports JOIN transport_type ON transports.type_id=transport_type.type_id ";
        $count = "SELECT COUNT(transport_id) AS total FROM transports JOIN transport_type ON transports.type_id=transport_type.type_id ";
        $where = false;

        $filter_1_col = isset($_GET['type']) ? $columns['type'] : "";
        $filter_1_val = $_GET['type'];

        $filter_2_col = isset($_GET['status']) ? $columns['status'] : "";
        $filter_2_val = $_GET['status'];

        if (!empty($filter_1_col) && $filter_1_val !== "") {
            $query .= " WHERE " . $filter_1_col . "=" . $filter_1_val . " ";
            $count .= " WHERE " . $filter_1_col . "=" . $filter_1_val . " ";
            $where = true;
        }

        if (!empty($filter_2_col) && $filter_2_val !== "") {
            if (!$where) {
                $query .= " WHERE " . $filter_2_col . "=" . $filter_2_val . " ";
                $count .= " WHERE " . $filter_2_col . "=" . $filter_2_val . " ";
                $where = true;
            } else {
                $query .= " AND " . $filter_2_col . "=" . $filter_2_val . " ";
                $count .= " AND " . $filter_2_col . "=" . $filter_2_val . " ";
            }
        }

        if (!empty($search)) {
            if ($where) {
                $query .= " AND name LIKE '%" . $search . "%' ";
                $count .= " AND name LIKE '%" . $search . "%' ";
            } else {
                $query .= " WHERE name LIKE '%" . $search . "%' ";
                $count .= " WHERE name LIKE '%" . $search . "%' ";
                $where = true;
            }
        }
        if (!empty($ordercol) && !empty($orderby)) {
            $query .= " ORDER BY " . $ordercol . " " . $orderby;
        }
        $query .= "  LIMIT $offset,$limit ";

        $result = $this->db->query($query)->getResultArray();
        $m_result = array();
        $sno = $offset + 1;
        foreach ($result as $r) {
            $action = '<div class="dropdown tableAction">
                        <a type="button" class="dropBtn HoverA "><i class="fa fa-ellipsis-v"></i></a>
                        <div class="dropdown_container">
                            <ul class="BB_UL flex">
                                <li><a href="#" data-ajax-url="' . url_to('erp.transport.ajaxfetchtransport' , $r['transport_id']) . '" title="Edit" class="bg-success modalBtn "><i class="fa fa-pencil"></i> </a></li>
                                <li><a href="' . url_to('erp.transport.transportdelete' , $r['transport_id'] ). '" title="Delete" class="bg-danger del-confirm"><i class="fa fa-trash"></i> </a></li>
                            </ul>
                        </div>
                    </div>';
            $active = '
                    <label class="toggle active-toggler-1" data-ajax-url="' . url_to('erp.transport.ajaxtransportactive' , $r['transport_id']) . '" >
                        <input type="checkbox" ';
            if ($r['active'] == 1) {
                $active .= ' checked ';
            }
            $active .= ' />
                        <span class="togglebar"></span>
                    </label>';
            $status = "<span class='st " . $this->transport_status_bg[$r['status']] . "' >" . $this->transport_status[$r['status']] . "</span>";
            array_push(
                $m_result,
                array(
                    $r['transport_id'],
                    $sno,
                    $r['name'],
                    $r['code'],
                    $r['type_name'],
                    $status,
                    $active,
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

    public function get_transports_export($type)
    {
        $columns = array(
            "name" => "name",
            "code" => "code",
            "type" => "transport_type.type_id",
            "status" => "status"
        );
        $limit = $_GET['limit'];
        $offset = $_GET['offset'];
        $search = $_GET['search'] ?? "";
        $orderby = isset($_GET['orderby']) ? strtoupper($_GET['orderby']) : "";
        $ordercol = isset($_GET['ordercol']) ? $columns[$_GET['ordercol']] : "";
        $query = "SELECT name,code,transport_type.type_name,
        CASE ";
        foreach ($this->transport_status as $key => $value) {
            $query .= " WHEN status=$key THEN '" . $value . "' ";
        }
        $query .= "END AS status,CASE WHEN active=1 THEN 'Yes' ELSE 'No' END AS active,description FROM transports JOIN transport_type ON transports.type_id=transport_type.type_id ";
        $where = false;

        $filter_1_col = isset($_GET['type']) ? $columns['type'] : "";
        $filter_1_val = $_GET['type'];

        $filter_2_col = isset($_GET['status']) ? $columns['status'] : "";
        $filter_2_val = $_GET['status'];

        if (!empty($filter_1_col) && $filter_1_val !== "") {
            $query .= " WHERE " . $filter_1_col . "=" . $filter_1_val . " ";
            $where = true;
        }

        if (!empty($filter_2_col) && $filter_2_val !== "") {
            if (!$where) {
                $query .= " WHERE " . $filter_2_col . "=" . $filter_2_val . " ";
                $where = true;
            } else {
                $query .= " AND " . $filter_2_col . "=" . $filter_2_val . " ";
            }
        }

        if (!empty($search)) {
            if ($where) {
                $query .= " AND name LIKE '%" . $search . "%' ";
            } else {
                $query .= " WHERE name LIKE '%" . $search . "%' ";
                $where = true;
            }
        }
        if (!empty($ordercol) && !empty($orderby)) {
            $query .= " ORDER BY " . $ordercol . " " . $orderby;
        }

        $result = $this->db->query($query)->getResultArray();
        return $result;
    }
}
