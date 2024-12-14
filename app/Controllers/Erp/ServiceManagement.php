<?php

namespace App\Controllers\Erp;

use App\Controllers\BaseController;
use App\Libraries\Exporter;
use App\Libraries\Exporter\ExporterConst;
use App\Models\Erp\AssetModel;
use App\Models\Erp\NotificationModel;
use App\Models\Erp\SchedulingModel;
use App\Models\Erp\ServiceModel;

class ServiceManagement extends BaseController
{
    protected $data;
    protected $db;
    protected $session;
    protected $ServiceModel;
    protected $SchedulingModel;
    protected $NotificationModel;

    public function __construct()
    {
        $this->ServiceModel = new ServiceModel();
        $this->SchedulingModel = new SchedulingModel();
        $this->NotificationModel = new NotificationModel();
        $this->db = \Config\Database::connect();
        $this->session = \Config\Services::session();
        helper(['erp', 'form']);
    }

    public function index()
    {
        $this->data['datatable_config'] = $this->ServiceModel->get_dtconfig_searvice();
        $this->data['service_status'] = $this->ServiceModel->get_service_status();
        $this->data['servicePriority_status'] = $this->ServiceModel->get_servicePriority_status();
        $this->data['menu'] = "service";
        $this->data['submenu'] = "service-list";
        $this->data['page'] = "service/service";
        return view("erp/index", $this->data);
    }

    public function ajaxServiceResponse()
    {
        $response = array();
        $response = $this->ServiceModel->get_service_datatable();
        return $this->response->setJSON($response);
    }

    public function serviceadd()
    {
        $post = [
            'code' => $this->request->getPost("code"),
            'name' => $this->request->getPost("name"),
            'priority' => $this->request->getPost("priority"),
            'assigned_to' => $this->request->getPost("assigned_to"),
            'employee_id' => $this->request->getPost("employee_id"),
            'service_desc' => $this->request->getPost("service_desc"),
        ];

        if ($this->request->getPost("name")) {
            if ($this->ServiceModel->insert_service($post)) {
                return $this->response->redirect(url_to("erp.service.service"));
            } else {
                return $this->response->redirect(url_to("erp.service.serviceadd"));
            }
        }
        $this->data['service_status'] = $this->ServiceModel->get_service_status();
        $this->data['service_priority'] = $this->ServiceModel->get_servicePriority_status();
        $this->data['page'] = "service/serviceadd";
        $this->data['menu'] = "service";
        $this->data['submenu'] = "service-list";
        return view("erp/index", $this->data);
    }

    public function ajaxServiceCodeunique()
    {
        $code = $this->request->getGet('data');
        $id = $this->request->getGet('id') ?? 0;
        $query = "SELECT code FROM service WHERE code='$code' ";
        if (!empty($id)) {
            $query = "SELECT code FROM service WHERE code='$code' AND service_id<>$id";
        }
        $check = $this->db->query($query)->getRow();
        $response = array();
        if (empty($check)) {
            $response['valid'] = 1;
        } else {
            $response['valid'] = 0;
            $response['msg'] = "Item Code already exists";
        }
        return $this->response->setJSON($response);
    }

    public function serviceedit($service_id)
    {
        $post = [
            'code' => $this->request->getPost("code"),
            'name' => $this->request->getPost("name"),
            'priority' => $this->request->getPost("priority"),
            'assigned_to' => $this->request->getPost("assigned_to"),
            'employee_id' => $this->request->getPost("employee_id"),
            'service_desc' => $this->request->getPost("service_desc"),
            'status' => $this->request->getPost("status"),
        ];
        if ($this->request->getPost("name")) {
            if ($this->ServiceModel->update_service($service_id, $post)) {
                return $this->response->redirect(url_to("erp.service.service"));
            } else {
                return $this->response->redirect(url_to("erp.service.serviceedit", $service_id));
            }
        }
        $this->data['service'] = $this->ServiceModel->get_service_by_id($service_id);
        if (empty($this->data['service'])) {
            $this->session->setFlashdata("op_error", "Service Not Found");
            return $this->response->redirect(url_to("erp.service.service"));
        }
        // $this->logger->error($this->data['service']);
        $this->data['service_status'] = $this->ServiceModel->get_service_status();
        $this->data['service_priority'] = $this->ServiceModel->get_servicePriority_status();
        $this->data['service_id'] = $service_id;
        $this->data['page'] = "service/serviceedit";
        $this->data['menu'] = "service";
        $this->data['submenu'] = "service-list";
        return view("erp/index", $this->data);
    }

    public function serviceView($service_id)
    {
        $this->data['service'] = $this->ServiceModel->get_service_by_id($service_id);
        if (empty($this->data['service'])) {
            return $this->response->redirect(url_to("erp.service.service"));
        }
        $this->data['service_code'] = $this->ServiceModel->getServiceCode($service_id);
        // $this->logger->error($this->data['service_code']);
        $this->data['scheduling_datatable_config'] = $this->SchedulingModel->get_dtconfig_scheduling();
        $this->data['service_priority'] = $this->ServiceModel->get_servicePriority_status();
        $this->data['service_status'] = $this->ServiceModel->get_service_status();
        $this->data['service_status_bg'] = $this->ServiceModel->get_service_status_bg();
        $this->data['service_priority_bg'] = $this->ServiceModel->get_servicePriority_bg();
        $this->data['servicePriority_status'] = $this->ServiceModel->get_servicePriority_status();
        $this->data['service_id'] = $service_id;
        $this->data['page'] = "service/serviceview";
        $this->data['menu'] = "service";
        $this->data['submenu'] = "service-view";
        return view("erp/index", $this->data);
    }

    public function report()
    {
        $this->data['status'] = $this->ServiceModel->ReportStatus();
        $this->logger->error($this->data['status']);

        $this->data['datatable_config'] = $this->ServiceModel->get_dtconfig_service_report();
        $this->data['menu'] = "service";
        $this->data['submenu'] = "service-report";
        $this->data['page'] = "service/report";
        return view("erp/index", $this->data);
    }

    public function serviceExport()
    {
        $type = $_GET['export'];
        $config = array();
        $config['title'] = "Service list";
        if ($type == "pdf") {
            $config['column'] = array("Service Code", "Service Name", "Priority", "Assigned To", "Lobers", "Status");
        } else {
            $config['column'] = array("Service Code", "Service Name", "Priority", "Assigned To", "Lobers", "Status");
            $config['column_data_type'] = array(ExporterConst::E_STRING, ExporterConst::E_STRING, ExporterConst::E_STRING);
        }
        $config['data'] = $this->ServiceModel->getServiceListExport($type);
        if ($type == "pdf") {
            Exporter::export(ExporterConst::PDF, $config);
        } else if ($type == "excel") {
            Exporter::export(ExporterConst::EXCEL, $config);
        } else if ($type == "csv") {
            Exporter::export(ExporterConst::CSV, $config);
        }
    }

    public function Scheduling()
    {
        $this->data['datatable_config'] = $this->ServiceModel->get_dtconfig_searvice();
        $this->data['menu'] = "service";
        $this->data['submenu'] = "service-scheduling";
        $this->data['page'] = "service/scheduling";
        return view("erp/index", $this->data);
    }

    public function ajaxSchedulingResponse()
    {
        $serviceId = $this->request->getGet('service_id');
        $response = array();
        $response = $this->SchedulingModel->getSchedulingDatatable($serviceId);
        return $this->response->setJSON($response);
    }

    public function ajaxFetchScheduling($scheduling_id)
    {
        $response = array();
        $query = "SELECT scheduling_id,start_date,due_date,location,service_id FROM scheduling WHERE scheduling_id=$scheduling_id";
        $result = $this->db->query($query)->getRow();
        if (!empty($result)) {
            $response['error'] = 0;
            $response['data'] = $result;
        } else {
            $response['error'] = 1;
        }
        return $this->response->setJSON($response);
    }

    public function schedulingadd($service_id)
    {
        $post = [
            'start_date' => $this->request->getPost("start_date"),
            'due_date' => $this->request->getPost("due_date"),
            'location' => $this->request->getPost("location"),
        ];
        //  $this->logger->error($post);
        $scheduling_id = $this->request->getPost("scheduling_id") ?? 0;
        if ($service_id) {
            $this->SchedulingModel->insert_update_scheduling($service_id, $scheduling_id, $post);
        }
        return $this->response->redirect(url_to("erp.service.serviceview", $service_id));
    }

    // public function serviceNotify($service_id)
    // {
    //     $post = [
    //         'notify_title' => $this->request->getPost("notify_title"),
    //         'notify_desc' => $this->request->getPost("notify_desc"),
    //         'notify_to' => $this->request->getPost("notify_to"),
    //         'notify_at' => $this->request->getPost("notify_at"),
    //         'notify_email' => $this->request->getPost("notify_email") ?? 0,
    //     ];

    //     $notify_id = $this->request->getPost("notify_id");
    //     if ($this->request->getPost("notify_title")) {
    //         $this->NotificationModel->insert_update_service_notify($service_id, $notify_id, $post);
    //     }
    //     return $this->response->redirect(url_to("erp.crm.serviceview", $service_id));
    // }

    // public function ajaxServiceNotifyResponse()
    // {
    //     $serviceId = $this->request->getGet("service_id");
    //     $response = array();
    //     $response = $this->ServiceModel->get_schedulingnotify_datatable($serviceId);
    //     return $this->response->setJSON($response);
    // }

    // public function serviceNotifydelete($service_id, $notify_id)
    // {
    //     $this->NotificationModel->delete_service_notify($service_id, $notify_id);
    //     return $this->response->redirect(url_to("erp.service.serviceview", $service_id));
    // }

    public function ajaxServiceReportResponse()
    {
        $response = array();
        $response = $this->ServiceModel->get_service_report_datatable();
        return $this->response->setJSON($response);
    }

    public function serviceReportExport()
    {
        $type = $_GET['export'];
        $config = array();
        $config['title'] = "Service Scheduling Report";
        if ($type == "pdf") {
            $config['column'] = array("Assigned to", "Service Code", "Service Name", "Lobers", "Start Date", "Due Date", "Location", "Status");
        } else {
            $config['column'] = array("Assigned to", "Service Code", "Service Name", "Lobers", "Start Date", "Due Date", "Location", "Status");
            $config['column_data_type'] = array(ExporterConst::E_STRING, ExporterConst::E_STRING, ExporterConst::E_STRING);
        }
        $config['data'] = $this->ServiceModel->getServiceReportExport($type);
        if ($type == "pdf") {
            Exporter::export(ExporterConst::PDF, $config);
        } else if ($type == "excel") {
            Exporter::export(ExporterConst::EXCEL, $config);
        } else if ($type == "csv") {
            Exporter::export(ExporterConst::CSV, $config);
        }
    }

    public function schedulingDelete($service_id, $scheduling_id)
    {
        $this->SchedulingModel->schedulingDelete($service_id, $scheduling_id);
        return $this->response->redirect(url_to('erp.service.serviceview',$service_id));
    }

    public function serviceDelete($service_id)
    {
        $this->ServiceModel->serviceDelete($service_id);
        return $this->response->redirect(url_to("erp.service.service"));
    }
}
