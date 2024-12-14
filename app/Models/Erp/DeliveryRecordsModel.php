<?php

namespace App\Models\Erp;

use CodeIgniter\Model;

class DeliveryRecordsModel extends Model
{
    protected $table = 'delivery_records';
    protected $primaryKey = 'delivery_id';
    protected $allowedFields = ['transport_id', 'related_to', 'related_id', 'status', 'type'];
    protected $db;
    protected $session;
    public function __construct()
    {
        $this->db = \Config\Database::connect();
        $this->session = \Config\Services::session();
    }
    private $delivery_type_bg = array(
        0 => "st_success",
        1 => "st_violet"
    );
    public function get_delivery_type_bg()
    {
        return $this->delivery_type_bg;
    }
    private $delivery_type = array(
        0 => "Inside",
        1 => "Outside"
    );
    public function get_delivery_type()
    {
        return $this->delivery_type;
    }
    private $delivery_status = array(
        0 => "Waiting",
        1 => "Delivering",
        2 => "Delivered",
        3 => "Cancelled"
    );

    private $delivery_status_bg = array(
        0 => "st_violet",
        1 => "st_primary",
        2 => "st_success",
        3 => "st_danger"
    );
    public function get_delivery_status()
    {
        return $this->delivery_status;
    }

    public function get_delivery_status_bg()
    {
        return $this->delivery_status_bg;
    }
    public function get_dtconfig_deliveryrecords()
    {
        $config = array(
            "columnNames" => ["sno", "name", "related to", "type", "status", "location", "action"],
            "sortable" => [0, 1, 0, 0, 0, 0, 0],
            "filters" => ["status", "type"]
        );
        return json_encode($config);
    }

    public function get_datatable_deliveryrecords()
    {
        $columns = array(
            "name" => "transports.name",
            "type" => "delivery_records.type",
            "status" => "delivery_records.status"
        );
        $limit = $_GET['limit'];
        $offset = $_GET['offset'];
        $search = $_GET['search'] ?? "";
        $orderby = isset($_GET['orderby']) ? strtoupper($_GET['orderby']) : "";
        $ordercol = isset($_GET['ordercol']) ? $columns[$_GET['ordercol']] : "";
        $query = "SELECT delivery_id,CONCAT(transports.name,' ',transports.code) AS name,delivery_records.status,delivery_records.type,COALESCE(CONCAT(supplier_locations.address,',',supplier_locations.city),'') AS location,COALESCE(purchase_order.order_code,'') AS related_to FROM delivery_records JOIN transports ON delivery_records.transport_id=transports.transport_id LEFT JOIN purchase_order ON delivery_records.related_id=purchase_order.order_id AND related_to='purchase_order' LEFT JOIN supplier_locations ON purchase_order.supp_location_id=supplier_locations.location_id  ";
        $count = "SELECT COUNT(delivery_id) AS total FROM delivery_records JOIN transports ON delivery_records.transport_id=transports.transport_id LEFT JOIN purchase_order ON delivery_records.related_id=purchase_order.order_id AND related_to='purchase_order' LEFT JOIN supplier_locations ON purchase_order.supp_location_id=supplier_locations.location_id  ";
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
                $query .= " AND transports.name LIKE '%" . $search . "%' ";
                $count .= " AND transports.name LIKE '%" . $search . "%' ";
            } else {
                $query .= " WHERE transports.name LIKE '%" . $search . "%' ";
                $count .= " WHERE transports.name LIKE '%" . $search . "%' ";
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
                            <ul class="BB_UL flex">';
            if ($r['status'] == 0) {
                $action .= '<li><a href="' . url_to('erp.transport.deliverystart', $r['delivery_id']) . '" title="Delivering" class="bg-primary "><i class="fa fa-truck"></i> </a></li>';
            }
            if ($r['status'] <= 1) {
                $action .= '
                                <li><a href="' . url_to('erp.transport.deliveryend', $r['delivery_id']) . '" title="Delivered" class="bg-success"><i class="fa fa-check-square-o"></i> </a></li>
                                <li><a href="' . url_to('erp.transport.deliverycancel', $r['delivery_id']) . '" title="Cancel" class="bg-danger del-confirm"><i class="fa fa-trash"></i> </a></li>
                                ';
            }
            $action .= '</ul>
                        </div>
                    </div>';
            $status = "<span class='st " . $this->delivery_status_bg[$r['status']] . "' >" . $this->delivery_status[$r['status']] . "</span>";
            $type = "<span class='st " . $this->delivery_type_bg[$r['type']] . "' >" . $this->delivery_type[$r['type']] . "</span>";
            array_push(
                $m_result,
                array(
                    $r['delivery_id'],
                    $sno,
                    $r['name'],
                    $r['related_to'],
                    $type,
                    $status,
                    $r['location'],
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

    public function change_delivery_status($delivery_id, $status)
    {
        $query = "UPDATE delivery_records SET status=$status WHERE delivery_id=$delivery_id";
        $this->db->query($query);

        $config['title'] = "Delivery Record Update";
        if ($this->db->affectedRows() > 0) {
            $config['log_text'] = "[ Delivery Record successfully updated ]";
            $config['ref_link'] = '';
            $this->session->setFlashdata("op_success", "Delivery Record successfully updated");
        } else {
            $config['log_text'] = "[ Delivery Record failed to update ]";
            $config['ref_link'] = '';
            $this->session->setFlashdata("op_error", "Delivery Record failed to update");
        }
        log_activity($config);
    }

    public function get_deliveryrecords_export($type)
    {
        $columns = array(
            "name" => "transports.name",
            "type" => "delivery_records.type",
            "status" => "delivery_records.status"
        );
        $limit = $_GET['limit'];
        $offset = $_GET['offset'];
        $search = $_GET['search'] ?? "";
        $orderby = isset($_GET['orderby']) ? strtoupper($_GET['orderby']) : "";
        $ordercol = isset($_GET['ordercol']) ? $columns[$_GET['ordercol']] : "";
        $query = "SELECT CONCAT(transports.name,' ',transports.code) AS name,COALESCE(purchase_order.order_code,'') AS related_to,
        CASE ";
        foreach ($this->delivery_status as $key => $value) {
            $query .= " WHEN delivery_records.status=$key THEN '" . $value . "' ";
        }
        $query .= "END AS status,CASE WHEN delivery_records.type=1 THEN 'Outside' ELSE 'Inside' END AS type,COALESCE(CONCAT(supplier_locations.address,',',supplier_locations.city),'') AS location FROM delivery_records JOIN transports ON delivery_records.transport_id=transports.transport_id LEFT JOIN purchase_order ON delivery_records.related_id=purchase_order.order_id AND related_to='purchase_order' LEFT JOIN supplier_locations ON purchase_order.supp_location_id=supplier_locations.location_id  ";
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
                $query .= " AND transports.name LIKE '%" . $search . "%' ";
            } else {
                $query .= " WHERE transports.name LIKE '%" . $search . "%' ";
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
