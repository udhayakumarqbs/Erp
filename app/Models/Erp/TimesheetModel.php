<?php

namespace App\Models\Erp;

use CodeIgniter\Model;
use DateTime;

class TimesheetModel extends Model
{
    protected $table = 'timesheet';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'related_to',
        'related_url',
        'start_timer',
        'end_timer',
        'time_taken',
        'assigned_to',
        'assigned_by',
        'created_at',
        'updated_at',
        'note',
    ];

    protected $useTimestamps = false;
    protected $db;
    protected $session;
    public function __construct()
    {
        $this->db = \Config\Database::connect();
        $this->session = \Config\Services::session();
        helper(['erp', 'form']);
    }

    private $ticket_status = array(
        0 => "OPEN",
        1 => "IN PROGRESS",
        2 => "SOLVED",
        3 => "CLOSED"
    );

    public function find_running_timer($assigned_to)
    {
        $result = $this->builder()
            ->where("assigned_to", $assigned_to)
            ->where("end_timer", '0000-00-00 00:00:00')
            ->get()
            ->getRow();

        if (!empty($result)) {
            if (!empty($result->start_timer)) {
                $current_time = new DateTime();
                $past_time = new DateTime($result->start_timer);
                $interval = $past_time->diff($current_time);
                $result->logged_time = ($interval->days * 24 * 60) + ($interval->h * 60) + $interval->i;
                $result->logged_days = $interval->days;
                $result->logged_hour = $interval->h;
                $result->logged_minutes = $interval->i;
            } else {
                $result->logged_time = "-";
            }
            return $result;
        } else {
            return null;
        }
    }

    public function stop_all_running_timers($assigned_to)
    {
        return $this->builder()
            ->update(["end_timer" => date("Y-m-d H:i:s")], ["assigned_to" => $assigned_to, "end_timer" => null]);
    }

    public function add_new_timer()
    {
        $data = [];
        if (!empty($_POST['assigned_to'])) {
            $data["assigned_to"] = $_POST['assigned_to'];
        }
        if (!empty($_POST['start_timer'])) {
            $data["start_timer"] = $_POST['start_timer'];
        } else {
            $data["start_timer"] = date("Y-m-d H:i:s");
        }
        if (!empty($_POST['end_timer'])) {
            $data["end_timer"] = $_POST['end_timer'];
        }
        if (!empty($_POST['start_timer'])) {
            $data["related_to"] = $_POST['start_timer'];
        }
        if (!empty($_POST['start_timer'])) {
            $data["related_url"] = $_POST['start_timer'];
        }
        if (!empty($_POST['note'])) {
            $data["note"] = $_POST['note'];
        }

        $data["assigned_by"] = session()->get("erp_userid");
        $data["created_at"] = date("Y-m-d H:i:s");
        // echo var_dump($data);
        // exit();
        return $this->builder()->insert($data);
    }

    public function getTimesheetDatatable($limit, $offset, $search, $orderby, $ordercol)
    {
        $columns = [
            "name" => "name",
        ];


        $builder = $this->db->table('timesheet');
        $builder->select('id , related_to, start_timer, end_timer, note, time_taken, assigned_by,erp_users.name as assigner');

        if (!empty($search)) {
            $builder->groupStart()
                ->orLike('related_to', $search)
                ->orLike('note', $search)
                ->orLike('assigned_by', $search)
                ->groupEnd();
        }

        if (!empty($ordercol) && !empty($orderby)) {
            $builder->orderBy($ordercol, $orderby);
        }

        $builder->join('erp_users', 'timesheet.assigned_by = erp_users.user_id', 'LEFT');
        $builder->limit($limit, $offset);

        $result = $builder->get()->getResultArray();

        $m_result = [];
        $sno = $offset + 1;
        $user_name = session()->get('erp_username');

        foreach ($result as $r) {
            $action = '<ul class="BB_UL flex"><li><a href="' . url_to('erp.timesheet.delete_timer', $r['id']) . '" title="Delete" class="bg-danger del-confirm"><i class="fa fa-trash"></i> </a></li></ul>';

            if ($r['end_timer'] == "0000-00-00 00:00:00") {
                $endTime = "<button type='button' class='btn-outline-danger timer_sheet_toggle' data-timer_id='".$r['id']."' >Stop timer</button>";
            } else {
                $endTime = $r['start_timer'];
            }

            $total_time = !empty($r['time_taken']) ? $r['time_taken'] : "-";

            if($r['assigner'] == $user_name){
                $assignee_name = "you";
            }else{
                $assignee_name = $r['assigner'];
            }

            array_push(
                $m_result,
                [
                    $r['id'],
                    $sno,
                    $r['start_timer'],
                    $endTime,
                    $total_time,
                    $assignee_name,
                    $r['note'],
                    $action,
                ]
            );

            $sno++;
        }

        $response = [];
        $response['total_rows'] = $this->db->table('timesheet')->countAllResults();
        $response['data'] = $m_result;

        return $response;
    }

    public function get_dtconfig_types()
    {
        $config = array(
            "columnNames" => ["sno", "Start", "End", "total time", "Started by", "note", "action"],
            "sortable" => [0, 1, 0],
        );
        return json_encode($config);
    }

    public function timeSheetExport($type)
    {
        $columns = array(
            "product_type" => "related_to",
            "warehouse" => "warehouses.warehouse_id",
            "stock" => "quantity",
            "unit price" => "price_list.amount"
        );
        $limit = $_GET['limit'];
        $offset = $_GET['offset'];
        $search = $_GET['search'] ?? "";
        $orderby = isset($_GET['orderby']) ? strtoupper($_GET['orderby']) : "";
        $ordercol = isset($_GET['ordercol']) ? $columns[$_GET['ordercol']] : "";

        $query = "SELECT CASE ";
        foreach ($this->product_types as $key => $value) {
            $query .= " WHEN related_to='$key' THEN '$value' ";
        }
        $query .= " END AS related_to,COALESCE(CONCAT(raw_materials.name,' ',raw_materials.code),CONCAT(semi_finished.name,' ',semi_finished.code),CONCAT(finished_goods.name,' ',finished_goods.code)) AS product,warehouses.name,quantity,price_list.amount AS unit_price,(quantity*price_list.amount) AS amount FROM stocks JOIN warehouses ON stocks.warehouse_id=warehouses.warehouse_id JOIN price_list ON stocks.price_id=price_list.price_id LEFT JOIN raw_materials ON related_id=raw_materials.raw_material_id AND related_to='raw_material' LEFT JOIN semi_finished ON related_id=semi_finished.semi_finished_id AND related_to='semi_finished' LEFT JOIN finished_goods ON related_id=finished_goods.finished_good_id ";
        $where = false;


        if (!empty($search)) {
            if (!$where) {
                $query .= " WHERE ( raw_materials.name LIKE '%" . $search . "%' OR semi_finished.name LIKE '%" . $search . "%' OR finished_goods.name LIKE '%" . $search . "%' ) ";
                $where = true;
            } else {
                $query .= " AND ( raw_materials.name LIKE '%" . $search . "%' OR semi_finished.name LIKE '%" . $search . "%' OR finished_goods.name LIKE '%" . $search . "%' ) ";
            }
        }

        if (!empty($ordercol) && !empty($orderby)) {
            $query .= " ORDER BY " . $ordercol . " " . $orderby;
        }
        $result = $this->db->query($query)->getResultArray();
        return $result;
    }

}