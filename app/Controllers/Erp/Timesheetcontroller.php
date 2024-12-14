<?php

namespace App\Controllers\Erp;

use App\Controllers\BaseController;
use App\Libraries\Exporter;
use App\Libraries\Exporter\ExporterConst;
use App\Models\Erp\TimesheetModel;
use DateTime;

class Timesheetcontroller extends BaseController
{
    protected $timesheetModel;
    protected $db;
    protected $data;
    protected $session;

    public function __construct()
    {
        $this->timesheetModel = new TimesheetModel();
        $this->db = \Config\Database::connect();
        $this->session = \Config\Services::session();
        helper('erp');
    }

    public function list_timesheets()
    {
        $this->data['list_datatable_config'] = $this->timesheetModel->get_dtconfig_types();
        $this->data['menu'] = "";
        $this->data['submenu'] = "";
        $this->data['page'] = "timesheet/index";
        return view("erp/index", $this->data);
    }

    public function check_for_running_timer($assigned_to)
    {
        if (!empty($assigned_to)) {
            return $this->timesheetModel->find_running_timer($assigned_to);
        } else {
            return null;
        }
    }

    public function check_for_running_timer_api()
    {
        if (!empty($this->request->getPost('assigned_to'))) {
            return $this->timesheetModel->find_running_timer($this->request->getPost('assigned_to'));
        } else {
            $response['result'] = 'failure';
            $response['message'] = "Can't get Timer !";
            return $this->response->setJSON($response);
        }
    }

    public function add_timer()
    {
        $response['result'] = 'failure';
        $response['message'] = "Can't Add Timer !";
        if ($this->request->getPost()) {
            if ($this->timesheetModel->add_new_timer()) {
                $response['result'] = 'success';
                $response['message'] = 'Timer Added Successfully !';
            }
        }
        $response['data'] = $this->check_for_running_timer($_POST['assigned_to']);
        return $this->response->setJSON($response);
    }

    public function end_timer_api()
    {
        $response['result'] = 'failure';
        $response['message'] = "Can't Add Timer !";

        if (!empty($this->request->getPost('note'))) {
            $data["note"] = $this->request->getPost('note');
        }
        if (!empty($this->request->getPost('related_to'))) {
            $data["related_to"] = $this->request->getPost('related_to');
        }
        if (!empty($this->request->getPost('related_url'))) {
            $data["related_url"] = $this->request->getPost('related_url');
        }

        $user_id = session()->get('erp_userid');

        $running_timer_details = $this->check_for_running_timer($user_id);
     
        

        if (!empty($running_timer_details)) {
            $past_time = new DateTime($running_timer_details->start_timer);
            $current_time = new DateTime();
            $interval = $past_time->diff($current_time);
            $data["time_taken"] = "";
            if(!empty($interval->days)){
                $data["time_taken"] .= "Days ".$interval->days;
            }
            if(!empty($interval->h)){
                $data["time_taken"] .= " Hours ". $interval->h;
            }
            if(!empty($interval->i)){
                $data["time_taken"] .= " Minutes ". $interval->i;
            }
            if ($this->request->getPost('timer_id')) {
                if ($this->timesheetModel->stop_all_running_timers($user_id)) {                    
                    $response['message'] = "Timer Stoped but can't Update Information !";
                    if ($this->timesheetModel->update($this->request->getPost('timer_id'), $data)) {
                        $response['result'] = 'success';
                        $response['message'] = 'Timer Stopped Successfully !';
                    }
                }
            }
        }
        return $this->response->setJSON($response);
    }

    public function delete_timer($timer_id)
    {
        $result = false;

        if (!empty($timer_id)) {
            if ($this->timesheetModel->delete($timer_id)) {
                $result = true;
            }
        }
        if ($result) {
            $this->session->setFlashdata("op_success", "Timer Deleted");
        } else {
            $this->session->setFlashdata("op_error", "Can't Delete Timer");
        }

        return $this->response->redirect(url_to("erp.timesheet.list"));
    }

    public function delete_timer_api()
    {
        $response['result'] = 'failure';
        $response['message'] = "Can't Delete the Timer !";
        if ($this->request->getPost('timer_id')) {
            if (!empty($this->request->getPost('timer_id'))) {
                if ($this->timesheetModel->delete($this->request->getPost('timer_id'))) {
                    $response['result'] = 'success';
                    $response['message'] = 'Timer Deleted Successfully !';
                }
            }
        }
        return $this->response->setJSON($response);
    }

    public function ajax_timesheet_response()
    {
        $response = array();
        $limit = $this->request->getGet("limit");
        $offset = $this->request->getGet("offset");
        $search = $this->request->getGet("search");
        $orderby = $this->request->getGet('orderby');
        $ordercol = $this->request->getGet("ordercol");
        $filterStatus = $this->request->getGet("filterStatus");
        $response = $this->timesheetModel->getTimesheetDatatable($limit, $offset, $search, $orderby, $ordercol);
        header("Content-Type:application/json");
        echo json_encode($response);
        exit();
    }

    public function timeSheetExport()
    {
        $type = $_GET['export'];
        $config = array();
        $config['title'] = "Current Stock";
        if ($type == "pdf") {
            $config['column'] = array("Product Type", "Product", "Warehouse", "Stock", "Unit Price", "Amount");
        } else {
            $config['column'] = array("Product Type", "Product", "Warehouse", "Stock", "Unit Price", "Amount");
            $config['column_data_type'] = array(ExporterConst::E_STRING, ExporterConst::E_STRING, ExporterConst::E_STRING, ExporterConst::E_STRING, ExporterConst::E_STRING, ExporterConst::E_STRING);
        }
        $config['data'] = $this->timesheetModel->timeSheetExport($type);
        if ($type == "pdf") {
            Exporter::export(ExporterConst::PDF, $config);
        } else if ($type == "excel") {
            Exporter::export(ExporterConst::EXCEL, $config);
        } else if ($type == "csv") {
            Exporter::export(ExporterConst::CSV, $config);
        }
    }

    public function get_all_tasks()
    {
        $response['result'] = 'failure';
        $response['message'] = "can't fetch tasks";
        $user_id = session()->get('erp_userid');
        if ($user_id) {
            $result = [];
            $query = $this->db->query("SELECT task_id as id,name FROM tasks where NOT status = 3 and assignees =" . $user_id)->getResultArray();
            foreach ($query as $row) {
                array_push($result, ['url' => url_to('erp.crm.taskview', $row['id']), 'text' => $row['name']]);
            }
            $query = $this->db->query("SELECT contract_id as id,followers FROM contract_tasks where NOT status = 0 and assignees =" . $user_id)->getResultArray();
            foreach ($query as $row) {
                array_push($result, ['url' => 'url' . $row['id'], 'text' => $row['name']]);
            }
            $query = $this->db->query("SELECT expense_id  as id,followers FROM expense_task where NOT status = 0 and assignees =" . $user_id)->getResultArray();
            foreach ($query as $row) {
                array_push($result, ['url' => 'url' . $row['id'], 'text' => $row['name']]);
            }
            $response['result'] = 'success';
            $response['message'] = 'fetched tasks Successfully !';
            $response['data'] = $result;
            return $this->response->setJSON($response);

        } else {
            return $this->response->setJSON($response);
        }
    }
}

