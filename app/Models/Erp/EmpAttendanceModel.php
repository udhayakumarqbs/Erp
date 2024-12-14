<?php

namespace App\Models\Erp;

use App\Libraries\Attendance as LibrariesAttendance;
use Attendance;
use CodeIgniter\Model;

class EmpAttendanceModel extends Model
{
    protected $table = 'emp_attendance';
    protected $primaryKey = 'attend_id';

    protected $allowedFields = [
        'employee_id',
        'rec_date',
        'status',
        'work_hours',
        'ot_hours',
    ];

    protected $db;
    protected $session;

    public function __construct()
    {
        $this->db = \Config\Database::connect();
        $this->session = \Config\Services::session();
    }
    public function get_attendance_months()
    {
        $months = array(
            1 => "January",
            2 => "February",
            3 => "March",
            4 => "April",
            5 => "May",
            6 => "June",
            7 => "July",
            8 => "August",
            9 => "September",
            10 => "October",
            11 => "November",
            12 => "December"
        );
        return $months;
    }
    private $types = array(
        0 => "Present",
        1 => "Weekoff",
        2 => "Absent",
        3 => "Paid Leave",
        4 => "Sick Leave",
        5 => "Festival Leave"
    );
    public function getTypes()
    {
        return $this->types;
    }
    public function get_attendance_years()
    {
        $query = "SELECT DISTINCT(EXTRACT(YEAR FROM rec_date)) AS years FROM emp_attendance ORDER BY years DESC ";
        $result = $this->db->query($query)->getResultArray();
        return $result;
    }

    public function get_dtconfig_attendance()
    {
        $config = array(
            "columnNames" => ["sno", "date", "code", "name", "work hours", "ot hours", "status", "action"],
            "sortable" => [0, 1, 1, 1, 1, 1, 0, 0],
            "filters" => ["status"]
        );
        return json_encode($config);
    }

    public function get_attendance_datatable()
    {
        $attendance = new LibrariesAttendance();
        $types = $attendance->getTypes();
        $columns = array(
            "date" => "rec_date",
            "code" => "emp_code",
            "name" => "first_name",
            "work hours" => "work_hours",
            "ot hours" => "ot_hours",
            "status" => "emp_attendance.status"
        );
        $limit = $_GET['limit'];
        $offset = $_GET['offset'];
        $search = $_GET['search'] ?? "";
        $orderby = isset($_GET['orderby']) ? strtoupper($_GET['orderby']) : "";
        $ordercol = isset($_GET['ordercol']) ? $columns[$_GET['ordercol']] : "";

        $query = "SELECT attend_id,rec_date,emp_code,CONCAT(first_name,' ',last_name) AS emp_name,work_hours,ot_hours,emp_attendance.status FROM emp_attendance JOIN employees ON emp_attendance.employee_id=employees.employee_id WHERE employees.status=0 ";
        $count = "SELECT COUNT(attend_id) AS total FROM emp_attendance JOIN employees ON emp_attendance.employee_id=employees.employee_id WHERE employees.status=0 ";
        $where = true;
        $filter_1_col = isset($_GET['status']) ? $columns['status'] : "";
        $filter_1_val = $_GET['status'];

        if (!empty($filter_1_col) && $filter_1_val !== "") {
            if (!$where) {
                $query .= " WHERE " . $filter_1_col . "=" . $filter_1_val . " ";
                $count .= " WHERE " . $filter_1_col . "=" . $filter_1_val . " ";
                $where = true;
            } else {
                $query .= " AND " . $filter_1_col . "=" . $filter_1_val . " ";
                $count .= " AND " . $filter_1_col . "=" . $filter_1_val . " ";
            }
        }

        if (!empty($search)) {
            if (!$where) {
                $query .= " WHERE ( emp_code LIKE '%" . $search . "%' OR first_name LIKE '%" . $search . "%' ) ";
                $count .= " WHERE ( emp_code LIKE '%" . $search . "%' OR first_name LIKE '%" . $search . "%' ) ";
                $where = true;
            } else {
                $query .= " AND ( emp_code LIKE '%" . $search . "%' OR first_name LIKE '%" . $search . "%' ) ";
                $count .= " AND ( emp_code LIKE '%" . $search . "%' OR first_name LIKE '%" . $search . "%' ) ";
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
                                <li><a href="#" data-ajax-url="' . url_to('erp.hr.ajaxfetchattendance', $r['attend_id']) . '" title="Edit" class="bg-success modalBtn" ><i class="fa fa-pencil"></i></a></li>
                            </ul>
                        </div>
                    </div>';
            $status = "<span class='st " . $types[$r['status']] . "'>" . $types[$r['status']] . " </span>";
            array_push(
                $m_result,
                array(
                    $r['attend_id'],
                    $sno,
                    $r['rec_date'],
                    $r['emp_code'],
                    $r['emp_name'],
                    $r['work_hours'],
                    $r['ot_hours'],
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

    public function get_attendance_export($type)
    {
        $attendance = new LibrariesAttendance();
        $types = $attendance->getTypes();
        $columns = array(
            "date" => "rec_date",
            "code" => "emp_code",
            "name" => "first_name",
            "work hours" => "work_hours",
            "ot hours" => "ot_hours",
            "status" => "emp_attendance.status"
        );
        $limit = $_GET['limit'];
        $offset = $_GET['offset'];
        $search = $_GET['search'] ?? "";
        $orderby = isset($_GET['orderby']) ? strtoupper($_GET['orderby']) : "";
        $ordercol = isset($_GET['ordercol']) ? $columns[$_GET['ordercol']] : "";

        $query = "SELECT rec_date,emp_code,CONCAT(first_name,' ',last_name) AS emp_name,work_hours,ot_hours,
        CASE ";
        foreach ($types as $key => $value) {
            $query .= " WHEN emp_attendance.status=$key THEN '$value' ";
        }
        $query .= " END AS status FROM emp_attendance JOIN employees ON emp_attendance.employee_id=employees.employee_id WHERE employees.status=0 ";
        $where = true;
        $filter_1_col = isset($_GET['status']) ? $columns['status'] : "";
        $filter_1_val = $_GET['status'];

        if (!empty($filter_1_col) && $filter_1_val !== "") {
            if (!$where) {
                $query .= " WHERE " . $filter_1_col . "=" . $filter_1_val . " ";
                $where = true;
            } else {
                $query .= " AND " . $filter_1_col . "=" . $filter_1_val . " ";
            }
        }

        if (!empty($search)) {
            if (!$where) {
                $query .= " WHERE ( emp_code LIKE '%" . $search . "%' OR first_name LIKE '%" . $search . "%' ) ";
                $where = true;
            } else {
                $query .= " AND ( emp_code LIKE '%" . $search . "%' OR first_name LIKE '%" . $search . "%' ) ";
            }
        }

        if (!empty($ordercol) && !empty($orderby)) {
            $query .= " ORDER BY " . $ordercol . " " . $orderby;
        }
        $query .= " LIMIT $offset,$limit ";
        $result = $this->db->query($query)->getResultArray();
        return $result;
    }

    public function add_attendance($post)
    {
        $employee_id = $post["employee_id"];
        $rec_date = $post["rec_date"];
        $work_hours = $post["work_hours"];
        $ot_hours = $post["ot_hours"];

        $query = "INSERT INTO emp_attendance( employee_id ,rec_date, work_hours , ot_hours) VALUE (?,?,?,?)";
        $this->db->query($query, array($employee_id, $rec_date, $work_hours, $ot_hours));
        if ($this->db->affectedRows() > 0) {
            $this->session->setFlashdata("op_success", "Attendance successfully added");
        } else {
            $this->session->setFlashdata("op_error", "Attendance failed to added");
        }
    }
    public function update_attendance($post)
    {
        $attend_id = $post["attend_id"];
        $status = $post["status"];
        $work_hours = $post["work_hours"];
        $ot_hours = $post["ot_hours"];

        $query = "UPDATE emp_attendance SET status=? , work_hours=? , ot_hours=? WHERE attend_id=$attend_id ";
        $this->db->query($query, array($status, $work_hours, $ot_hours));
        if ($this->db->affectedRows() > 0) {
            $this->session->setFlashdata("op_success", "Attendance successfully updated");
        } else {
            $this->session->setFlashdata("op_error", "Attendance failed to update");
        }
    }
}
