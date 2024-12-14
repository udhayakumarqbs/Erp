<?php

namespace App\Models\Erp;

use CodeIgniter\Model;

class SchedulingModel extends Model
{
    protected $table = 'scheduling';
    protected $primaryKey = 'scheduling_id';

    protected $allowedFields = ['start_date', 'due_date', 'location', 'service_id'];
    protected $session;
    protected $db;
    public function __construct()
    {
        $this->db = \Config\Database::connect();
        $this->session = \Config\Services::session();
    }
    public function get_dtconfig_scheduling()
    {
        $config = array(
            "columnNames" => ["sno", "start date", "due date", "location", "action"],
            "sortable" => [0, 0, 0, 0, 0, 0],
        );
        return json_encode($config);
    }

    public function getSchedulingDatatable($serviceId)
    {
        $columns = array(
            "code" => "service.code",
        );
        $limit = $_GET['limit'];
        $offset = $_GET['offset'];
        $search = $_GET['search'] ?? "";
        $orderby = isset($_GET['orderby']) ? strtoupper($_GET['orderby']) : "";
        $ordercol = isset($_GET['ordercol']) ? $columns[$_GET['ordercol']] : "";

        $query = "SELECT scheduling_id ,start_date,due_date,location FROM scheduling WHERE service_id=$serviceId";
        $count = "SELECT COUNT(scheduling_id) AS total FROM scheduling";
        $where = true;

        if (!empty($search)) {
            if (!$where) {
                $query .= " WHERE ( start_date LIKE '%" . $search . "%') ";
                $count .= " WHERE ( start_date LIKE '%" . $search . "%') ";
                $where = true;
            } else {
                $query .= " AND (due_date LIKE '%" . $search . "%') ";
                $count .= " AND (due_date LIKE '%" . $search . "%') ";
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
                                <li><a href="#" data-ajax-url="' . url_to('erp.service.ajaxfetchscheduling', $r['scheduling_id']) . '" title="Edit" class="bg-success modalBtn"><i class="fa fa-pencil"></i> </a></li>
                                <li><a href="' . url_to('erp.service.schedulingdelete', $serviceId, $r['scheduling_id']) . '" title="Delete" class="bg-danger del-confirm"><i class="fa fa-trash"></i> </a></li>
                            </ul>
                        </div>
                    </div>';
            array_push(
                $m_result,
                array(
                    $r['scheduling_id'],
                    $sno,
                    $r['start_date'],
                    $r['due_date'],
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

    public function insert_update_scheduling($service_id, $scheduling_id, $post)
    {

        if (empty($scheduling_id)) {
            $this->insert_schedulingaddr($service_id, $post);
        } else {
            $this->update_schedulingaddr($service_id, $scheduling_id, $post);
        }
    }

    private function insert_schedulingaddr($service_id, $post)
    {
        $start_date = $post["start_date"];
        $due_date = $post["due_date"];
        $location = $post["location"];

        $created_by = get_user_id();
        $query = "INSERT INTO scheduling(start_date,due_date,location,service_id) VALUES(?,?,?,?)";
        $this->db->query($query, array($start_date, $due_date, $location, $service_id));
        $scheduling_id = $this->db->insertId();

        $config['title'] = "Scheduling Insert";
        if (!empty($scheduling_id)) {
            $config['log_text'] = "[ Scheduling successfully created ]";
            $config['ref_link'] = url_to('erp.service.serviceview', $service_id);
            $this->session->setFlashdata("op_success", "Scheduling successfully created");
        } else {
            $config['log_text'] = "[ Scheduling failed to create ]";
            $config['ref_link'] = url_to('erp.service.serviceview', $service_id);
            $this->session->setFlashdata("op_error", "Scheduling failed to create");
        }
        log_activity($config);
    }

    private function update_schedulingaddr($service_id, $scheduling_id, $post)
    {
        $start_date = $post["start_date"];
        $due_date = $post["due_date"];
        $location = $post["location"];

        $query = "UPDATE scheduling SET start_date=? , due_date=? , location=?,service_id=? WHERE scheduling_id=$scheduling_id";
        $this->db->query($query, array($start_date, $due_date, $location, $service_id));

        $config['title'] = "Scheduling Update";
        if ($this->db->affectedRows() > 0) {
            $config['log_text'] = "[ Scheduling successfully updated ]";
            $config['ref_link'] = url_to('erp.service.serviceview', $service_id);
            $this->session->setFlashdata("op_success", "Scheduling successfully updated");
        } else {
            $config['log_text'] = "[ Scheduling failed to update ]";
            $config['ref_link'] = url_to('erp.service.serviceview', $service_id);
            $this->session->setFlashdata("op_error", "Scheduling failed to update");
        }
        log_activity($config);
    }

    public function schedulingDelete($service_id, $scheduling_id)
    {
        $jobresult = $this->builder()->where('service_id', $service_id)->where('scheduling_id', $scheduling_id)->get()->getRow();

        $config['title'] = "Scheduling Delete";

        if (!empty($jobresult)) {

            $this->db->table('scheduling')->where('scheduling_id', $scheduling_id)->delete();
            $config['log_text'] = "[ Scheduling successfully deleted ]";
            $config['ref_link'] = url_to('erp.service.serviceview', $service_id);
            $this->session->setFlashdata("op_success", "Scheduling successfully deleted");
        } else {
            $config['log_text'] = "[ Scheduling failed to delete ]";
            $config['ref_link'] = url_to('erp.service.serviceview', $service_id);
            $this->session->setFlashdata("op_success", "Scheduling failed to delete");
        }

        log_activity($config);
    }
}
