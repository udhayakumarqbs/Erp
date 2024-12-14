<?php

namespace App\Controllers\Erp;

use App\Controllers\BaseController;
use App\Models\Erp\ProjectsModel;
use App\Models\Erp\Sale\InvoiceModel;
use App\Models\Erp\Sale\SalePaymentModel;
use App\Models\Erp\CalendarModel;

class DashboardController extends BaseController
{
    protected $projectsModel;
    protected $InvoiceModel;
    protected $CalendarModel;
    protected $logger;
    public function __construct()
    {
        $this->projectsModel = new ProjectsModel();
        $this->InvoiceModel = new InvoiceModel();
        $this->CalendarModel = new CalendarModel();
        $this->logger = service('log');
        helper(["erp", "form"]);
    }

    public function index()
    {
        helper('erp');
        $data['statusCounts'] = $this->projectsModel->countStatus();
        $data['leadsCountStatus'] = $this->projectsModel->leadsCountStatus();
        $data['leads2CountStatus'] = $this->projectsModel->leads2CountStatus();
        $data['invoiceCount'] = $this->InvoiceModel->invoiceCount();
        // echo '<pre>';
        // return var_dump($data['invoiceCount']);
        $data['quotationCount'] = $this->InvoiceModel->quotationCount();
        $data['menu'] = "dashboard";
        $data['submenu'] = "";
        $data['page'] = "dashboard/dashboard";
        // $this->logger->error($data);
        return view("erp/index", $data);
    }

    public function orderStatus()
    {
        $salePaymentModel = new SalePaymentModel();
        $data = $salePaymentModel->orderStatus();
        return json_encode($data);
    }

    public function calender_add()
    {
        $data = [
            "title" => $this->request->getPost("title"),
            "description" => $this->request->getPost("description"),
            "user_id" => get_user_id(),
            "Start_data" => $this->request->getPost("start_date"),
            "end_data" => $this->request->getPost("end_date"),
            "public_event" => $this->request->getPost("public-event"),
            "reminder_before" => $this->request->getPost("notification"),
            "reminder_before_type" => $this->request->getPost("timing"),
            "event_color" => $this->request->getPost("color_input")
        ];

        if (isset($data["public_event"])) {
            $data["public_event"] = 1;
        } else {
            $data["public_event"] = 0;
        }

        $result = $this->CalendarModel->calendar_events_add($data);

        if ($result) {
            return $this->response->setJSON(["success" => true]);
        } else {
            return $this->response->setJSON(["success" => false, "error" => "failed to insert data"]);
        }
    }

    public function ajax_events()
    {
        $result = $this->CalendarModel->fetch_events();
        $data["result"] = $result;
        return $this->response->setJSON($data);
    }
    public function ajax_events_update()
    {
        $data = [
            "title" => $this->request->getPost("title"),
            "description" => $this->request->getPost("description"),
            "user_id" => get_current_user(),
            "Start_data" => $this->request->getPost("start_date"),
            "end_data" => $this->request->getPost("end_date"),
            "public_event" => $this->request->getPost("public-event"),
            "reminder_before" => $this->request->getPost("notification"),
            "reminder_before_type" => $this->request->getPost("timing"),
            "event_color" => $this->request->getPost("color_input")
        ];
        if (isset($data["public_event"])) {
            $data["public_event"] = 1;
        } else {
            $data["public_event"] = 0;
        }
        $id = $this->request->getPost("eventid");
        $result = $this->CalendarModel->update_event($data, $id);
        if ($result) {
            return $this->response->setJSON(["success" => true]);
        } else {
            return $this->response->setJSON(["success" => false, "error" => "failed to update data"]);
        }
    }
    public function ajax_exist()
    {
        // $data = $_GET;
        $exist_data = $this->CalendarModel->fetch_exist_data($_GET["id"]);
        return json_encode($exist_data);
    }
    //delete event 
    public function event_Delete(){
        $result = $this->CalendarModel->event_delete($_POST["event_id"]);
        if($result){
            return $this->response->setJSON(["success"=>true]);
        }
        else{
            return $this->response->setJSON(["success"=>false]);
        }
    }
}
