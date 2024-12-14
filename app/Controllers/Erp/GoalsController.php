<?php

namespace App\Controllers\Erp;

use App\Controllers\BaseController;
use App\Models\Erp\LeadsModel;
use CodeIgniter\Controller;
use App\Models\Erp\GoalsModel;
use App\Models\Erp\ContracttypeModel;
use App\Models\Erp\NotificationModel;
use App\Libraries\Exporter;
use App\Libraries\Exporter\ExporterConst;


class GoalsController extends BaseController
{
    protected $data;
    protected $GoalsModel;
    protected $ContracttypeModel;
    protected $NotificationModel;
    protected $db;

    public function __construct()
    {
        $this->db = \Config\Database::connect();
        $this->GoalsModel = new GoalsModel();
        $this->ContracttypeModel = new ContracttypeModel();
        $this->NotificationModel = new NotificationModel();
        helper(['erp', 'form']);
    }
    //view 
    public function index()
    {
        $this->data['menu'] = 'goals';
        $this->data['submenu'] = 'goals';
        $this->data['page'] = 'Goals/goalsview';
        $this->data['dt_config'] = $this->GoalsModel->ajax_dt_config_column();



        return view('erp/index', $this->data);
    }
    //add new goals
    public function goalsadd()
    {
        $this->data['menu'] = 'goals';
        $this->data['submenu'] = 'goals';
        $this->data['page'] = 'Goals/goalsadd';
        $this->data['contractType'] = $this->ContracttypeModel->fetch_contract_type();

        if ($this->request->getPost()) {
            $config['title']="Goals Add";
            $data = [
                "subject" => $this->request->getPost("name"),
                "description" => $this->request->getPost("description"),
                "start_date" => $this->request->getPost("start_date"),
                "end_date" => $this->request->getPost("end_date"),
                "goal_type" => $this->request->getPost("goal_type"),
                "contract_type" => $this->request->getPost("c_type"),
                "achievement" => $this->request->getPost("achievement"),
                "notify_when_fail" => $this->request->getPost("goal_failed_to_achieve"),
                "notify_when_achieve" => $this->request->getPost("when_goal_achieve"),
                "staff_id" => $this->request->getPost("staff_member"),
            ];

            //notify_when_fail
            if (isset($data["notify_when_fail"])) {
                $data["notify_when_fail"] = 1;
            } else {
                $data["notify_when_fail"] = 0;
            }

            //notify_when_achieve
            if (isset($data["notify_when_achieve"])) {
                $data["notify_when_achieve"] = 1;
            } else {
                $data["notify_when_achieve"] = 0;
            }

            
            //staff 
            $data["staff_id"] = isset($data["staff_id"]) ?  $data["staff_id"] : 0;
            
            if ($this->GoalsModel->add_goals($data)) {
                $config['log_text']="[ Added New Goals ]";
                $this->session->setFlashdata("op_success", "Goals added successfully");
                $this->response->redirect(url_to("erp.goalsview"));
            } else {
                $config['log_text']="[ Can't Add new Goals ]";
                $this->session->setFlashdata("op_error", "Error");
            }
            $config['ref_link']="";
            log_activity($config);
        }


        return view('erp/index', $this->data);
    }

    //update Goals
    public function goalsupdate($id)
    {
        $this->data['menu'] = 'goals';
        $this->data['submenu'] = 'goals';
        $this->data['page'] = 'Goals/goalsupdate';
        $this->data['contractType'] = $this->ContracttypeModel->fetch_contract_type();


        if ($this->request->getPost()) {
            $config['title']="Goals Update";
            $data = [
                "subject" => $this->request->getPost("name"),
                "description" => $this->request->getPost("description"),
                "start_date" => $this->request->getPost("start_date"),
                "end_date" => $this->request->getPost("end_date"),
                "goal_type" => $this->request->getPost("goal_type"),
                "contract_type" => $this->request->getPost("c_type"),
                "achievement" => $this->request->getPost("achievement"),
                "notify_when_fail" => $this->request->getPost("goal_failed_to_achieve"),
                "notify_when_achieve" => $this->request->getPost("when_goal_achieve"),
                "staff_id" => $this->request->getPost("staff_member"),
            ];

            //notify_when_fail
            if (isset($data["notify_when_fail"])) {
                $data["notify_when_fail"] = 1;
            } else {
                $data["notify_when_fail"] = 0;
            }

            //notify_when_achieve
            if (isset($data["notify_when_achieve"])) {
                $data["notify_when_achieve"] = 1;
            } else {
                $data["notify_when_achieve"] = 0;
            }

            //staff 
            $data["staff_id"] = isset($data["staff_id"]) ?  $data["staff_id"] : 0;

            if ($this->GoalsModel->update_goals($id, $data)) {
                $this->session->setFlashdata("op_success", "Updated successfully");
                $config['log_text']="[ Goal Updated Successfully ]";
                $this->response->redirect(url_to("erp.goalsupdate", $id));
            } else {
                $config['log_text']="[ Can't Update Goal ]";
                $this->session->setFlashdata("op_error", "Error");
            }


            $config['ref_link']="";
            log_activity($config);
        }

        $leads_model = new LeadsModel();
        $existData = $this->GoalsModel->get_existing_data($id);
        $achievement = 0;
        if($existData->goal_type == '2'){
            $achievement = $leads_model->get_for_goals_with_count($existData->staff_id,$existData->start_date,$existData->end_date);
        }else if($existData->goal_type == '1'){

        }
        $this->data['achievement_done'] = $achievement['count'] ?? 0; 
        $this->data["existData"] = $existData;

        return view('erp/index', $this->data);
    }

    public function goalsdelete($id)
    {
        $config['title']="Goals Delete";
        if ($this->GoalsModel->goal_delete($id)) {
            if ($this->NotificationModel->notify_Goal_delete($id)) {
                $this->session->Setflashdata("op_success", "deleted successfully");
                $config['log_text']="[ Goal Deleted successfully ]";
                $this->response->redirect(url_to("erp.goalsview"));
            }
            else{
                $config['log_text']="[ Can't Delete the Goals ]";
                $this->session->Setflashdata("op_error", "error in notification delete");
            }
        } else {
            $this->session->Setflashdata("op_error", "Error occured");
        }

        $config['ref_link']="";
        log_activity($config);
    }

    public function get_data_table()
    {
        $result = array();

        $result = $this->GoalsModel->get_goals_data_table();

        return $this->response->setJSON($result);
    }

    public function goals_reminder()
    {
        // var_dump($_POST);
        // exit;
        $related_id = $_POST["goalid"];
        $related_to = "Goal";
        $user_id = $_POST["staff_id"];
        $goal_type = $_POST["goal_type"];
        $notify_type = $_POST["notify_type"];

        if ($this->NotificationModel->add_reminder_goals($related_id, $related_to, $user_id, $notify_type)) {
            return $this->response->setJSON(["success" => true]);
        } else {
            return $this->response->setJSON(["success" => false]);
        }
    }

    public function goals_export(){
        $type = $_GET["export"];
        $config = array();
        $config["title"] = "Goals";
        if($type == "pdf"){
            $config["column"] = array("Subject","Staff Member","Achievement","Start Date","End Date","Goal Type","Progress");
        }else{
            $config["column"] = array("Subject","Staff Member","Achievement","Start Date","End Date","Goal Type","Progress");
            $config["column_data_type"] = array(ExporterConst::E_STRING,ExporterConst::E_STRING,ExporterConst::E_STRING,ExporterConst::E_STRING,ExporterConst::E_STRING,ExporterConst::E_STRING,ExporterConst::E_STRING);
        }
        $config["data"] = $this->GoalsModel->get_full_goals_export();
        if($type == "pdf"){
            Exporter::export(ExporterConst::PDF,$config);
        }
        else if($type == "csv"){
            Exporter::export(ExporterConst::CSV,$config);
        }
        else if($type == "excel"){
            Exporter::export(ExporterConst::EXCEL,$config);
        }
    }

}
