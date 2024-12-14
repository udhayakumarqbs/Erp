<?php

namespace App\Models\Erp;

use CodeIgniter\Model;
use Exception;

class ServiceModel extends Model
{

    protected $db;
    public $session;
    public $logger;

    public function __construct()
    {
        $this->session = \Config\Services::session();
        $this->db = \Config\Database::connect();
        $this->logger = \Config\Services::logger();

        helper(['erp', 'form']);
    }
    private $service_status = array(
        0 => "Not Started",
        1 => "Ongoing",
        2 => "Complete",
        3 => "Cancelled",
    );

    private $servicePriority_status = array(
        0 => "Low",
        1 => "Medium",
        2 => "high",
        3 => "Urgent",
    );

    private $service_status_bg = array(
        0 => "st_violet",
        2 => "st_success",
        1 => "st_primary",
        3 => "st_danger"
    );

    private $service_priority_bg = array(
        0 => "st_dark",
        1 => "st_primary",
        2 => "st_violet",
        3 => "st_danger"
    );

    public function get_service_status()
    {
        return $this->service_status;
    }
    public function get_servicePriority_status()
    {
        return $this->servicePriority_status;
    }
    public function get_servicePriority_bg()
    {
        return $this->service_priority_bg;
    }
    public function get_service_status_bg()
    {
        return $this->service_status_bg;
    }
    public function get_dtconfig_searvice()
    {
        $config = array(
            "columnNames" => ["sno", "service code", "service name", "priority", "assigned to", "lobers", "status", "active"],
            "sortable" => [0, 0, 0, 1, 0, 0, 1, 0],
            "filters" => ["priority", "status"]
        );
        return json_encode($config);
    }

    public function get_service_datatable()
    {
        $columns = array(
            "code" => "service.code",
            "name" => "service.name",
            "priority" => "service.priority",
            "status" => "service.status",
            "employee" => "employees.name",
            "assigned to" => "erp_users.name",
        );
        $limit = $_GET['limit'] ?? "";
        $offset = $_GET['offset'] ?? "";
        $search = $_GET['search'] ?? "";

        $orderby = isset($_GET['orderby']) ? strtoupper($_GET['orderby']) : "";
        $ordercol = isset($_GET['ordercol']) ? $columns[$_GET['ordercol']] : "";

        $query = "SELECT service.service_id,service.code,service.name,service.status,service.priority,CONCAT(employees.first_name,' ',employees.last_name) as employee,erp_users.name AS assigned FROM service JOIN erp_users ON service.assigned_to=erp_users.user_id JOIN employees ON service.employee_id=employees.employee_id";
        $count = "SELECT COUNT(service_id) AS total FROM service JOIN erp_users ON service.assigned_to=erp_users.user_id";
        $where = false;

        $filter_1_col = isset($_GET['priority']) ? $columns['priority'] : "";
        $filter_1_val = isset($_GET['priority']) ? $_GET['priority'] : '';

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
        $this->logger->error($_GET);

        if (!empty($search)) {
            if (!$where) {
                $query .= " WHERE ( service.name LIKE '%" . $search . "%' OR erp_users.name LIKE '%" . $search . "%' )";
                $count .= " WHERE ( service.name LIKE '%" . $search . "%' OR erp_users.name LIKE '%" . $search . "%' )";
                $where = true;
            } else {
                $query .= " AND ( service.name LIKE '%" . $search . "%' OR erp_users.name LIKE '%" . $search . "%' )";
                $count .= " AND ( service.name LIKE '%" . $search . "%' OR erp_users.name LIKE '%" . $search . "%' )";
            }
        }
        if (!empty($ordercol) && !empty($orderby)) {
            $query .= " ORDER BY " . $ordercol . " " . $orderby;
        }
        $query .= " LIMIT $offset,$limit ";

        $result = $this->db->query($query)->getResultArray();
        // $this->logger->error($result);
        $m_result = array();
        $sno = $offset + 1;
        foreach ($result as $r) {
            $action = '<div class="dropdown tableAction">
            <a type="button" class="dropBtn HoverA "><i class="fa fa-ellipsis-v"></i></a>
            <div class="dropdown_container">
                <ul class="BB_UL flex">
                    <li><a href="' . url_to('erp.service.serviceview', $r['service_id']) . '" title="View" class="bg-warning"><i class="fa fa-eye"></i> </a></li>
                    <li><a href="' . url_to('erp.service.serviceedit', $r['service_id']) . '" title="Edit" class="bg-success"><i class="fa fa-pencil"></i> </a></li>'
                . ($r['status'] != 2 ? '<li><a href="' . url_to('erp.service.servicedelete', $r['service_id']) . '" title="Delete" class="bg-danger del-confirm"><i class="fa fa-trash"></i> </a></li>' : '') .
                '</ul>
            </div>
        </div>';
            $priority = "<span class='st " . $this->service_priority_bg[$r['priority']] . "'>" . $this->servicePriority_status[$r['priority']] . " </span>";
            $status = "<span class='st " . $this->service_status_bg[$r['status']] . "'>" . $this->service_status[$r['status']] . " </span>";
            array_push(
                $m_result,
                array(
                    $r['service_id'],
                    $sno,
                    $r['code'],
                    $r['name'],
                    $priority,
                    $r['assigned'],
                    $r['employee'],
                    $status,
                    $action
                )
            );
            $sno++;
        }
        $response = array();
        $response['total_rows'] = $this->db->query($count)->getRow()->total;
        $response['data'] = $m_result;
        // $this->logger->error($response);
        return $response;
    }

    public function insert_service($post)
    {

        $inserted = false;
        $code = $post["code"];
        $name = $post["name"];
        $priority = $post["priority"];
        $assigned_to = $post["assigned_to"];
        $employee_id = $post["employee_id"];
        $service_desc = $post["service_desc"];
        $created_by = get_user_id();


        $query = "INSERT INTO service(code,name,priority,assigned_to,employee_id,service_desc,created_by) VALUES(?,?,?,?,?,?,?)";
        $this->db->query($query, array($code, $name, $priority, $assigned_to, $employee_id, $service_desc, $created_by));

        $config['title'] = "Service Insert";
        if (!empty($this->db->insertId())) {
            $inserted = true;
            $config['log_text'] = "[ Service successfully created ]";
            $config['ref_link'] = url_to('erp.service.service');
            $this->session->setFlashdata("op_success", "Service successfully created");
        } else {
            $config['log_text'] = "[ Service failed to create ]";
            $config['ref_link'] = url_to('erp.service.service');
            $this->session->setFlashdata("op_error", "Service failed to create");
        }
        log_activity($config);
        return $inserted;
    }

    public function update_service($service_id, $post)
    {
        $updated = false;
        $code = $post["code"];
        $name = $post["name"];
        $priority = $post["priority"];
        $assigned_to = $post["assigned_to"];
        $employee_id = $post["employee_id"];
        $service_desc = $post["service_desc"];
        $status = $post["status"];


        $query = "UPDATE service SET code=?,name=?,priority=?,status=?,assigned_to=?,employee_id=?,service_desc=? WHERE service_id=$service_id";
        $this->db->query($query, array($code, $name, $priority, $status, $assigned_to, $employee_id, $service_desc));

        $config['title'] = "Service Update";
        if ($this->db->affectedRows() > 0) {
            $updated = true;
            $config['log_text'] = "[ Service successfully updated ]";
            $config['ref_link'] = url_to('erp.service.service');
            $this->session->setFlashdata("op_success", "Service successfully updated");
        } else {
            $config['log_text'] = "[ Service failed to update ]";
            $config['ref_link'] = url_to('erp.service.service');
            $this->session->setFlashdata("op_error", "Service failed to update");
        }
        log_activity($config);
        return $updated;
    }

    public function get_service_by_id($service_id)
    {
        $query = "SELECT service.service_id,service.code,service.name as service_name,service.service_desc,service.status,service.priority,employees.employee_id,erp_users.user_id AS assigned,CONCAT(employees.first_name,' ',employees.last_name) as employee,erp_users.name AS name FROM service JOIN erp_users ON service.assigned_to=erp_users.user_id JOIN employees ON service.employee_id=employees.employee_id WHERE service_id=$service_id";
        $result = $this->db->query($query)->getRow();
        return $result;
    }

    public function getServiceListExport($type)
    {
        $columns = array(
            "payment mode name" => "name"
        );
        $limit = $_GET['limit'];
        $offset = $_GET['offset'];
        $search = $_GET['search'] ?? "";
        $orderby = isset($_GET['orderby']) ? strtoupper($_GET['orderby']) : "";
        $ordercol = isset($_GET['ordercol']) ? $columns[$_GET['ordercol']] : "";

        $query = "SELECT service.code, service.name,
        CASE 
            WHEN service.priority=0 THEN 'Low'  
            WHEN service.priority=1 THEN 'Medium'  
            WHEN service.priority=2 THEN 'High'  
            WHEN service.priority=3 THEN 'Urgent'  
        END AS priority,
        erp_users.name AS assigned_to,
        CONCAT(employees.first_name, ' ', employees.last_name) as employee,
        CASE 
            WHEN service.status=0 THEN 'Not Started'  
            WHEN service.status=1 THEN 'Ongoing'  
            WHEN service.status=2 THEN 'Complete'  
            WHEN service.status=3 THEN 'Cancelled'  
        END AS status
    FROM service 
    JOIN erp_users ON service.assigned_to = erp_users.user_id 
    JOIN employees ON service.employee_id = employees.employee_id";

        $where = false;

        if (!empty($search)) {
            if (!$where) {
                $query .= " WHERE service.name LIKE '%" . $search . "%' ";
                $where = true;
            } else {
                $query .= " AND service.name LIKE '%" . $search . "%' ";
            }
        }
        if (!empty($ordercol) && !empty($orderby)) {
            $query .= " ORDER BY " . $ordercol . " " . $orderby;
        }
        $result = $this->db->query($query)->getResultArray();
        return $result;
    }

    public function getServiceCode($service_id)
    {
        $query = "SELECT service_id,code FROM service WHERE service_id=" . $service_id;
        $result = $this->db->query($query)->getRow();
        return $result;
    }

    // public function get_schedulingnotify_datatable($serviceId)
    // {
    //     $columns = array(
    //         "notify at" => "notify_at",
    //         "notified" => "notified"
    //     );
    //     $limit = $_GET['limit'];
    //     $offset = $_GET['offset'];
    //     $search = $_GET['search'] ?? "";
    //     $orderby = isset($_GET['orderby']) ? strtoupper($_GET['orderby']) : "";
    //     $ordercol = isset($_GET['ordercol']) ? $columns[$_GET['ordercol']] : "";

    //     $query = "SELECT notify_id,title,notify_at,status,erp_users.name AS notify_to FROM notification JOIN erp_users ON notification.user_id=erp_users.user_id WHERE related_to='customer' AND related_id=$serviceId ";
    //     $count = "SELECT COUNT(notify_id) AS total FROM notification JOIN erp_users ON notification.user_id=erp_users.user_id WHERE related_to='customer' AND related_id=$serviceId ";
    //     $where = true;

    //     if (!empty($search)) {
    //         if (!$where) {
    //             $query .= " WHERE ( title LIKE '%" . $search . "%' OR erp_users.name LIKE '%" . $search . "%' ) ";
    //             $count .= " WHERE ( title LIKE '%" . $search . "%' OR erp_users.name LIKE '%" . $search . "%' ) ";
    //             $where = true;
    //         } else {
    //             $query .= " AND ( title LIKE '%" . $search . "%' OR erp_users.name LIKE '%" . $search . "%' ) ";
    //             $count .= " AND ( title LIKE '%" . $search . "%' OR erp_users.name LIKE '%" . $search . "%' ) ";
    //         }
    //     }

    //     if (!empty($ordercol) && !empty($orderby)) {
    //         $query .= " ORDER BY " . $ordercol . " " . $orderby;
    //     }
    //     $query .= " LIMIT $offset,$limit ";
    //     $result = $this->db->query($query)->getResultArray();
    //     $m_result = array();
    //     $sno = $offset + 1;
    //     foreach ($result as $r) {
    //         $action = '<div class="dropdown tableAction">
    //                     <a type="button" class="dropBtn HoverA "><i class="fa fa-ellipsis-v"></i></a>
    //                     <div class="dropdown_container">
    //                         <ul class="BB_UL flex">
    //                             <li><a href="#" data-ajax-url="' . url_to('erp.crm.ajaxfetchnotify', $r['notify_id']) . '" title="Edit" class="bg-success modalBtn"><i class="fa fa-pencil"></i> </a></li>
    //                             <li><a href="' . url_to('erp.crm.customernotifydelete', $serviceId, $r['notify_id']) . '" title="Delete" class="bg-danger del-confirm"><i class="fa fa-trash"></i> </a></li>
    //                         </ul>
    //                     </div>
    //                 </div>';
    //         $notify_at = date("Y-m-d H:i:s", $r['notify_at']);
    //         $notified = '<span class="st  ';
    //         if ($r['status'] == 1) {
    //             $notified .= 'st_violet" >Yes</span>';
    //         } else {
    //             $notified .= 'st_dark" >No</span>';
    //         }
    //         array_push(
    //             $m_result,
    //             array(
    //                 $r['notify_id'],
    //                 $sno,
    //                 $r['title'],
    //                 $notify_at,
    //                 $r['notify_to'],
    //                 $notified,
    //                 $action
    //             )
    //         );
    //         $sno++;
    //     }
    //     $response = array();
    //     $response['total_rows'] = $this->db->query($count)->getRow()->total;
    //     $response['data'] = $m_result;
    //     return $response;
    // }

    public function get_dtconfig_service_report()
    {
        $config = array(
            "columnNames" => ["sno", "assigned to", "service code", "service name", "lobers", "start date", "due date", "location", "status"],
            "sortable" => [0, 0, 0, 1, 1, 0, 0, 1],
            // "filters" => ["status"]
        );
        return json_encode($config);
    }

    public function get_service_report_datatable()
    {
        $columns = array(
            "code" => "service.code",
            "name" => "service.name",
            "status" => "service.status",
            "employee" => "employees.name",
            "assigned to" => "erp_users.name",
        );
        $limit = $_GET['limit'] ?? "";
        $offset = $_GET['offset'] ?? "";
        $search = $_GET['search'] ?? "";

        $orderby = isset($_GET['orderby']) ? strtoupper($_GET['orderby']) : "";
        $ordercol = isset($_GET['ordercol']) ? $columns[$_GET['ordercol']] : "";
        $query = "SELECT service.service_id, service.code, service.name, erp_users.name as assigned, service.status, CONCAT(employees.first_name, ' ', employees.last_name) as employee, scheduling.start_date, scheduling.due_date, scheduling.location 
        FROM service 
        JOIN scheduling ON scheduling.service_id = service.service_id 
        JOIN employees ON employees.employee_id = service.employee_id 
        JOIN erp_users ON service.assigned_to = erp_users.user_id";

        $count = "SELECT COUNT(service.service_id) as total 
        FROM service 
        JOIN scheduling ON scheduling.service_id = service.service_id 
        JOIN employees ON employees.employee_id = service.employee_id 
        JOIN erp_users ON service.assigned_to = erp_users.user_id";

        $where = false;
        $this->logger->error($_GET);

        if (!empty($search)) {
            if (!$where) {
                $query .= " WHERE ( service.name LIKE '%" . $search . "%' OR erp_users.name LIKE '%" . $search . "%' )";
                $count .= " WHERE ( service.name LIKE '%" . $search . "%' OR erp_users.name LIKE '%" . $search . "%' )";
                $where = true;
            } else {
                $query .= " AND ( service.name LIKE '%" . $search . "%' OR erp_users.name LIKE '%" . $search . "%' )";
                $count .= " AND ( service.name LIKE '%" . $search . "%' OR erp_users.name LIKE '%" . $search . "%' )";
            }
        }
        if (!empty($ordercol) && !empty($orderby)) {
            $query .= " ORDER BY " . $ordercol . " " . $orderby;
        }
        $query .= " LIMIT $offset,$limit ";

        $result = $this->db->query($query)->getResultArray();
        // $this->logger->error($result);
        $m_result = array();
        $sno = $offset + 1;
        foreach ($result as $r) {
            $status = "<span class='st " . $this->service_status_bg[$r['status']] . "'>" . $this->service_status[$r['status']] . " </span>";
            array_push(
                $m_result,
                array(
                    $r['service_id'],
                    $sno,
                    $r['assigned'],
                    $r['code'],
                    $r['name'],
                    $r['employee'],
                    $r['start_date'],
                    $r['due_date'],
                    $r['location'],
                    $status,
                )
            );
            $sno++;
        }
        $response = array();
        $response['total_rows'] = $this->db->query($count)->getRow()->total;
        $response['data'] = $m_result;
        // $this->logger->error($response);
        return $response;
    }

    public function getServiceReportExport($type)
    {
        $columns = array(
            "payment mode name" => "name"
        );
        $limit = $_GET['limit'];
        $offset = $_GET['offset'];
        $search = $_GET['search'] ?? "";
        $orderby = isset($_GET['orderby']) ? strtoupper($_GET['orderby']) : "";
        $ordercol = isset($_GET['ordercol']) ? $columns[$_GET['ordercol']] : "";

        $query = "SELECT erp_users.name AS assigned_to, service.code, service.name,
        CONCAT(employees.first_name, ' ', employees.last_name) as employee,scheduling.start_date,scheduling.due_date,scheduling.location,
        CASE 
            WHEN service.status=0 THEN 'Not Started'  
            WHEN service.status=1 THEN 'Ongoing'  
            WHEN service.status=2 THEN 'Complete'  
            WHEN service.status=3 THEN 'Cancelled'  
        END AS status
    FROM service 
    JOIN erp_users ON service.assigned_to = erp_users.user_id 
    JOIN employees ON service.employee_id = employees.employee_id
    JOIN scheduling ON service.service_id = scheduling.service_id";

        $where = false;

        if (!empty($search)) {
            if (!$where) {
                $query .= " WHERE service.name LIKE '%" . $search . "%' ";
                $where = true;
            } else {
                $query .= " AND service.name LIKE '%" . $search . "%' ";
            }
        }
        if (!empty($ordercol) && !empty($orderby)) {
            $query .= " ORDER BY " . $ordercol . " " . $orderby;
        }
        $result = $this->db->query($query)->getResultArray();
        return $result;
    }
    public function ReportStatus()
    {
        $query = "SELECT COUNT(status) as total FROM service WHERE status in(0,1,2,3) GROUP by status";
        $result = $this->db->query($query)->getResultArray();
        return $result;
    }

    public function serviceDelete($service_id)
    {
        try {
            $this->db->transStart();

            $this->db->table('service')
                ->where('service_id', $service_id)
                ->delete();

            $this->db->table('scheduling')
                ->where('service_id', $service_id)
                ->delete();

            $this->db->transComplete();
            if ($this->db->transStatus() === false) {
                throw new Exception("Transaction failed");
            }
            return $this->session->setFlashdata("op_success", "Service and related data deleted successfully");
        } catch (\Exception $e) {
            return $this->session->setFlashdata("op_error", "Error deleting Service and related data");
        }
    }
}
