<?php

namespace App\Controllers\Erp;

use App\Controllers\BaseController;
use App\Libraries\Exporter;
use App\Libraries\Exporter\ExporterConst;
use App\Models\Erp\AmenityModel;
use App\Models\Erp\AttachmentModel;
use App\Models\Erp\ContractorPaymentsModel;
use App\Models\Erp\ContractorsModel;
use App\Models\Erp\ErpUsersModel;
use App\Models\Erp\PaymentModesModel;
use App\Models\Erp\ProjectExpenseModel;
use App\Models\Erp\ProjectMembersModel;
use App\Models\Erp\ProjectPhaseModel;
use App\Models\Erp\ProjectRawMaterialsModel;
use App\Models\Erp\ProjectsModel;
use App\Models\Erp\ProjectTestingModel;
use App\Models\Erp\ProjectWorkgroupModel;
use App\Models\Erp\PropertyTypeModel;
use App\Models\Erp\Sale\EstimatesModel;
use App\Models\Erp\TeamMembersModel;
use App\Models\Erp\TeamsModel;
use App\Models\Erp\WorkGroupsModel;

class  ProjectManagementController extends BaseController
{

    public $teamsModel;
    public $teamMembersModel;
    public $workGroupsModel;
    public $estimatesModel;
    public $projectsModel;
    public $amenityModel;
    public $propertyTypeModel;
    public $erpUsersModel;
    public $contractorsModel;
    public $projectWorkgroupModel;
    public $projectMembersModel;
    public $projectTestingModel;
    public $projectPhaseModel;
    public $projectExpenseModel;
    public $projectRawMaterialsModel;
    public $attachmentModel;
    public $paymentModesModel;
    public $contractorPaymentsModel;
    public $crmController;



    public $data;
    private $db;
    protected $session;
    public function __construct()
    {
        $this->teamsModel = new TeamsModel();
        $this->teamMembersModel = new TeamMembersModel();
        $this->workGroupsModel = new WorkGroupsModel();
        $this->estimatesModel = new EstimatesModel();
        $this->projectsModel = new ProjectsModel();
        $this->amenityModel = new AmenityModel();
        $this->propertyTypeModel = new PropertyTypeModel();
        $this->erpUsersModel = new ErpUsersModel();
        $this->contractorsModel = new ContractorsModel();
        $this->projectWorkgroupModel = new ProjectWorkgroupModel();
        $this->projectMembersModel = new ProjectMembersModel();
        $this->projectTestingModel = new ProjectTestingModel();
        $this->projectPhaseModel = new ProjectPhaseModel();
        $this->projectExpenseModel = new ProjectExpenseModel();
        $this->projectRawMaterialsModel = new ProjectRawMaterialsModel();
        $this->attachmentModel = new AttachmentModel();
        $this->paymentModesModel = new PaymentModesModel();
        $this->contractorPaymentsModel = new ContractorPaymentsModel();
        $this->crmController = new CRMController();
        $this->db = \Config\Database::connect();
        $this->session = \Config\Services::session();
        helper(['erp', 'form']);
    }

    public function teams()
    {
        $this->data['team_datatable_config'] = $this->teamsModel->get_dtconfig_teams();
        $this->data['page'] = "project/teams";
        $this->data['menu'] = "project";
        $this->data['submenu'] = "team";
        return view("erp/index", $this->data);
    }

    public function ajaxTeamsResponse()
    {
        $response = array();
        $response = $this->teamsModel->get_team_datatable();
        return $this->response->setJSON($response);
    }

    public function teamadd()
    {
        $post = [
            'name' => $this->request->getPost("name"),
            'team_count' => $this->request->getPost("team_count"),
            'lead_by' => $this->request->getPost("lead_by"),
            'description' => $this->request->getPost("description"),
        ];
        if ($this->request->getPost("name")) {
            if ($this->teamsModel->insert_team($post)) {
                return $this->response->redirect(url_to("erp.project.teams"));
            } else {
                return $this->response->redirect(url_to("erp.project.teamadd"));
            }
        }
        $this->data['page'] = "project/teamadd";
        $this->data['menu'] = "project";
        $this->data['submenu'] = "team";
        return view("erp/index", $this->data);
    }

    public function ajaxTeamNameUnique()
    {
        $name = $_GET['data'];
        $id = $_GET['id'] ?? 0;
        $query = "SELECT name FROM teams WHERE name='$name' ";
        if (!empty($id)) {
            $query = "SELECT name FROM teams WHERE name='$name' AND team_id<>$id";
        }
        $check = $this->db->query($query)->getRow();
        $response = array();
        if (empty($check)) {
            $response['valid'] = 1;
        } else {
            $response['valid'] = 0;
            $response['msg'] = "Team Name already exists";
        }
        return $this->response->setJSON($response);
    }

    public function teamedit($team_id)
    {

        $post = [
            'name' => $this->request->getPost("name"),
            'team_count' => $this->request->getPost("team_count"),
            'lead_by' => $this->request->getPost("lead_by"),
            'description' => $this->request->getPost("description"),
        ];
        if ($this->request->getPost("name")) {
            if ($this->teamsModel->update_team($team_id, $post)) {
                return $this->response->redirect(url_to("erp.project.teams"));
            } else {
                return $this->response->redirect(url_to("erp.project.teamedit", $team_id));
            }
        }
        $this->data['team'] = $this->teamsModel->get_team_by_id($team_id);
        if (empty($this->data['team'])) {
            $this->session->setFlashdata("op_error", "Team Not Found");
            return $this->response->redirect(url_to("erp.project.teams"));
        }
        $this->data['team_id'] = $team_id;
        $this->data['page'] = "project/teamedit";
        $this->data['menu'] = "project";
        $this->data['submenu'] = "team";
        return view("erp/index", $this->data);
    }

    public function teamview($team_id)
    {
        $this->data['team'] = $this->teamsModel->get_team_by_id($team_id);
        if (empty($this->data['team'])) {
            $this->session->setFlashdata("op_error", "Team Not Found");
            return $this->response->redirect(url_to("erp.project.teams"));
        }
        $this->data['team_id'] = $team_id;
        $this->data['attachments'] = $this->teamsModel->get_attachments("team", $team_id);
        $this->data['member_datatable_config'] = $this->teamMembersModel->get_dtconfig_teammembers();
        $this->data['page'] = "project/teamview";
        $this->data['menu'] = "project";
        $this->data['submenu'] = "team";
        return view("erp/index", $this->data);
    }

    public function uploadTeamAttachment()
    {
        $id = $_GET["teamid"] ?? 0;
        $response = array();

        if (!empty($_FILES['attachment']['name'])) {
            $response = $this->teamsModel->upload_attachment("team", $id);
        } else {
            $response['error'] = 1;
            $response['reason'] = "No file to upload";
        }

        return $this->response->setJSON($response);
    }

    public function teamDeleteAttachment()
    {
        $attach_id = $this->request->getGet("id") ?? 0;
        $response = $this->teamsModel->delete_attachment("team", $attach_id);

        return $this->response->setJSON($response);
    }

    public function teammember($team_id)
    {
        $member = $this->request->getPost("member");
        $this->teamMembersModel->insert_teammember($team_id, $member);
        return $this->response->redirect(url_to("erp.project.teamview", $team_id));
    }

    public function ajaxfetchemployees()
    {
        $search = $_GET['search'] ?? "";
        $response = array();
        $response['error'] = 1;
        if (!empty($search)) {
            $query = "SELECT employee_id,CONCAT(first_name,' ',last_name) AS name FROM employees WHERE status=0 AND first_name LIKE '%" . $search . "%' LIMIT 10";
            $result = $this->db->query($query)->getResultArray();
            $m_result = array();
            foreach ($result as $r) {
                array_push($m_result, array(
                    "key" => $r['employee_id'],
                    "value" => $r['name']
                ));
            }
            $response['error'] = 0;
            $response['data'] = $m_result;
        }
        return $this->response->setJSON($response);
    }

    public function ajaxTeammemberResponse()
    {
        $team_id = $this->request->getGet("teamid");
        $response = array();
        $response = $this->teamMembersModel->get_teammember_datatable($team_id);
        return $this->response->setJSON($response);
    }

    //NOT IMPLEMENTED
    public function teammemberdelete($member_id)
    {
        $this->teamMembersModel->delete_teammember($member_id);
        return $this->response->redirect(url_to("erp.project.teams"));
    }

    //NOT IMPLEMENTED
    public function teamdelete($team_id)
    {
        $this->teamsModel->teamsDelete($team_id);
        return $this->response->redirect(url_to("erp.project.teams"));
    }

    public function teamsExport()
    {
        $type = $_GET['export'];
        $config = array();
        $config['title'] = "Team";
        if ($type == "pdf") {
            $config['column'] = array("Name", "Team count", "Lead By", "Description");
        } else {
            $config['column'] = array("Name", "Team count", "Lead By", "Description");
            $config['column_data_type'] = array(ExporterConst::E_STRING, ExporterConst::E_STRING, ExporterConst::E_STRING, ExporterConst::E_STRING);
        }
        $config['data'] = $this->teamsModel->get_team_export($type);
        if ($type == "pdf") {
            Exporter::export(ExporterConst::PDF, $config);
        } else if ($type == "excel") {
            Exporter::export(ExporterConst::EXCEL, $config);
        } else if ($type == "csv") {
            Exporter::export(ExporterConst::CSV, $config);
        }
    }

    //team view members export

    public function teamsMemberExport()
    {
        $type = $_GET['export'];
        $config = array();
        $config['title'] = "Team";
        if ($type == "pdf") {
            $config['column'] = array("Name", "Team count", "Lead By", "Description");
        } else {
            $config['column'] = array("Name", "Team count", "Lead By", "Description");
            $config['column_data_type'] = array(ExporterConst::E_STRING, ExporterConst::E_STRING, ExporterConst::E_STRING, ExporterConst::E_STRING);
        }
        $config['data'] = $this->teamsModel->get_team_export($type);
        if ($type == "pdf") {
            Exporter::export(ExporterConst::PDF, $config);
        } else if ($type == "excel") {
            Exporter::export(ExporterConst::EXCEL, $config);
        } else if ($type == "csv") {
            Exporter::export(ExporterConst::CSV, $config);
        }
    }


    public function workgroups()
    {
        $this->data['workgroup_datatable_config'] = $this->workGroupsModel->get_dtconfig_workgroups();
        $this->data['page'] = "project/workgroups";
        $this->data['menu'] = "project";
        $this->data['submenu'] = "workgroup";
        return view("erp/index", $this->data);
    }

    public function ajaxWorkgroupsResponse()
    {
        $response = array();
        $response = $this->workGroupsModel->get_workgroup_datatable();
        return $this->response->setJSON($response);
    }

    public function workgroupadd()
    {
        $post = [
            'name' => $this->request->getPost("name"),
            'approx_days' => $this->request->getPost("approx_days"),
            'description' => $this->request->getPost("description"),
        ];
        $equipments = $this->request->getPost("equipments");
        $qtys = $this->request->getPost("qty");
        $related_ids = $this->request->getPost("related_id");
        if ($this->request->getPost("name")) {
            if ($this->workGroupsModel->insert_workgroup($post, $equipments, $qtys, $related_ids)) {
                return $this->response->redirect(url_to("erp.project.workgroups"));
            } else {
                return $this->response->redirect(url_to("erp.project.workgroupadd"));
            }
        }
        $this->data['equipments'] = $this->estimatesModel->get_all_equipments();
        $this->data['page'] = "project/workgroupadd";
        $this->data['menu'] = "project";
        $this->data['submenu'] = "workgroup";
        return view("erp/index", $this->data);
    }

    public function ajaxWorkgroupNameUnique()
    {
        $name = $_GET['data'];
        $id = $_GET['id'] ?? 0;
        $query = "SELECT name FROM work_groups WHERE name='$name' ";
        if (!empty($id)) {
            $query = "SELECT name FROM work_groups WHERE name='$name' AND wgroup_id<>$id";
        }
        $check = $this->db->query($query)->getRow();
        $response = array();
        if (empty($check)) {
            $response['valid'] = 1;
        } else {
            $response['valid'] = 0;
            $response['msg'] = "Workgroup Name already exists";
        }
        return $this->response->setJSON($response);
    }

    public function workgroupedit($wgroup_id)
    {
        $post = [
            'name' => $this->request->getPost("name"),
            'approx_days' => $this->request->getPost("approx_days"),
            'description' => $this->request->getPost("description"),
        ];
        $equipments = $this->request->getPost("equipments");
        $qtys = $this->request->getPost("qty");
        $related_ids = $this->request->getPost("related_id");
        if ($this->request->getPost("name")) {
            if ($this->workGroupsModel->update_workgroup($wgroup_id, $post, $equipments, $qtys, $related_ids)) {
                return $this->response->redirect(url_to("erp.project.workgroups"));
            } else {
                return $this->response->redirect(url_to("erp.project.workgroupedit", $wgroup_id));
            }
        }
        $this->data['workgroup'] = $this->workGroupsModel->get_workgroup_by_id($wgroup_id);
        if (empty($this->data['workgroup'])) {
            $this->session->setFlashdata("op_error", "Workgroup Not Found");
            return $this->response->redirect(url_to("erp.project.workgroups"));
        }
        $this->data['items'] = $this->workGroupsModel->get_workgroup_items($wgroup_id);
        $this->data['wgroup_id'] = $wgroup_id;
        $this->data['equipments'] = $this->workGroupsModel->get_all_equipments();
        $this->data['page'] = "project/workgroupedit";
        $this->data['menu'] = "project";
        $this->data['submenu'] = "workgroup";
        return view("erp/index", $this->data);
    }

    //NOT IMPLEMENTED
    public function workgroupdelete($wgroup_id)
    {
        $this->workGroupsModel->delete_workgroups($wgroup_id);
        return $this->response->redirect(url_to("erp.project.workgroups"));
    }

    public function workgroupsExport()
    {
        $type = $_GET['export'];
        $config = array();
        $config['title'] = "Workgroups";
        if ($type == "pdf") {
            $config['column'] = array("Name", "Approx Days", "Description");
        } else {
            $config['column'] = array("Name", "Approx Days", "Description");
            $config['column_data_type'] = array(ExporterConst::E_STRING, ExporterConst::E_STRING, ExporterConst::E_STRING);
        }
        $config['data'] = $this->workGroupsModel->get_workgroup_export($type);
        if ($type == "pdf") {
            Exporter::export(ExporterConst::PDF, $config);
        } else if ($type == "excel") {
            Exporter::export(ExporterConst::EXCEL, $config);
        } else if ($type == "csv") {
            Exporter::export(ExporterConst::CSV, $config);
        }
    }

    public function projects()
    {
        if (is_manufacturing_system()) {
            $this->data['project_datatable_config'] = $this->projectsModel->get_dtconfig_m_projects();
            $this->data['page'] = "project/m_projects";
        } else {
            $this->data['project_datatable_config'] = $this->projectsModel->get_dtconfig_c_projects();
            $this->data['page'] = "project/c_projects";
        }
        $project_id = session('client_cust_id'); 
        $this->data['project_status'] = $this->projectsModel->get_project_status();
        $this->data['menu'] = "project";
        $this->data['submenu'] = "project";
        return view("erp/index", $this->data);
    }

    public function ajaxMprojectsResponse()
    {
        $response = array();
        $response = $this->projectsModel->get_m_project_datatable();
        return $this->response->setJSON($response);
    }

    public function ajaxCprojectsResponse()
    {
        $response = array();
        $response = $this->projectsModel->get_c_project_datatable();
        return $this->response->setJSON($response);
    }

    public function projectadd()
    {
        $post = [
            'name' => $this->request->getPost("name"),
            'start_date' => $this->request->getPost("start_date"),
            'end_date' => $this->request->getPost("end_date"),
            'budget' => $this->request->getPost("budget"),
            'cust_id' => $this->request->getPost("cust_id"),
            'related_id' => $this->request->getPost("related_id"),
            'description' => $this->request->getPost("description"),
        ];

        $post1 = [
            'name' => $this->request->getPost("name"),
            'start_date' => $this->request->getPost("start_date"),
            'end_date' => $this->request->getPost("end_date"),
            'budget' => $this->request->getPost("budget"),
            'cust_id' => $this->request->getPost("cust_id"),
            'description' => $this->request->getPost("description"),
            'units' => $this->request->getPost("units"),
            'type_id' => $this->request->getPost("type_id"),
            'address' => $this->request->getPost("address"),
            'city' => $this->request->getPost("city"),
            'state' => $this->request->getPost("state"),
            'country' => $this->request->getPost("country"),
            'zipcode' => $this->request->getPost("zipcode"),
        ];
        $amenity = $this->request->getPost("amenity");
        $members = $this->request->getPost("members");
        if (is_manufacturing_system()) {
            if ($this->request->getPost("name")) {
                if ($this->projectsModel->insert_m_project($post, $members)) {
                    return $this->response->redirect(url_to("erp.project.projects"));
                } else {
                    return $this->response->redirect(url_to("erp.project.projectadd"));
                }
            }
            $this->data['page'] = "project/m_projectadd";
        } else {
            if ($this->request->getPost("name")) {
                if ($this->projectsModel->insert_c_project($post1, $amenity, $members)) {
                    return $this->response->redirect(url_to("erp.project.projects"));
                } else {
                    return $this->response->redirect(url_to("erp.project.projectadd"));
                }
            }
            $this->data['amenities'] = $this->amenityModel->get_all_amenities();
            $this->data['types'] = $this->propertyTypeModel->get_all_propertytype();
            $this->data['page'] = "project/c_projectadd";
        }
        $this->data['members'] = $this->erpUsersModel->get_all_users();
        $this->data['menu'] = "project";
        $this->data['submenu'] = "project";
        return view("erp/index", $this->data);
    }

    public function ajaxProjectNameUnique()
    {
        $name = $_GET['data'];
        $id = $_GET['id'] ?? 0;
        $query = "SELECT name FROM projects WHERE name='$name' ";
        if (!empty($id)) {
            $query = "SELECT name FROM projects WHERE name='$name' AND project_id<>$id";
        }
        $check = $this->db->query($query)->getRow();
        $response = array();
        if (empty($check)) {
            $response['valid'] = 1;
        } else {
            $response['valid'] = 0;
            $response['msg'] = "Project Name already exists";
        }
        return $this->response->setJSON($response);
    }

    public function projectedit($project_id)
    {

        $post = [
            'name' => $this->request->getPost("name"),
            'start_date' => $this->request->getPost("start_date"),
            'end_date' => $this->request->getPost("end_date"),
            'budget' => $this->request->getPost("budget"),
            'cust_id' => $this->request->getPost("cust_id"),
            'related_id' => $this->request->getPost("related_id"),
            'description' => $this->request->getPost("description"),
        ];
        $post1 = [
            'name' => $this->request->getPost("name"),
            'start_date' => $this->request->getPost("start_date"),
            'end_date' => $this->request->getPost("end_date"),
            'budget' => $this->request->getPost("budget"),
            'cust_id' => $this->request->getPost("cust_id"),
            'description' => $this->request->getPost("description"),
            'units' => $this->request->getPost("units"),
            'type_id' => $this->request->getPost("type_id"),
            'address' => $this->request->getPost("address"),
            'city' => $this->request->getPost("city"),
            'state' => $this->request->getPost("state"),
            'country' => $this->request->getPost("country"),
            'zipcode' => $this->request->getPost("zipcode"),
        ];
        $amenity = $this->request->getPost("amenity");
        $members = $this->request->getPost("members");
        if (is_manufacturing_system()) {
            if ($this->request->getPost("name")) {
                if ($this->projectsModel->update_m_project($project_id, $post, $members)) {
                    return $this->response->redirect(url_to("erp.project.projects"));
                } else {
                    return $this->response->redirect(url_to("erp.project.projectedit", $project_id));
                }
            }
            $this->data['project'] = $this->projectsModel->get_m_project_by_id($project_id);
            if (empty($this->data['project'])) {
                $this->session->setFlashdata("op_error", "Project Not Found");
                return $this->response->redirect(url_to("erp.project.projects"));
            }
            $this->data['page'] = "project/m_projectedit";
        } else {
            if ($this->request->getPost("name")) {
                if ($this->projectsModel->update_c_project($project_id, $post1, $amenity, $members)) {
                    return $this->response->redirect(url_to("erp.project.projects"));
                } else {
                    return $this->response->redirect(url_to("erp.project.projectedit", $project_id));
                }
            }
            $this->data['project'] = $this->projectsModel->get_c_project_by_id($project_id);
            if (empty($this->data['project'])) {
                $this->session->setFlashdata("op_error", "Project Not Found");
                return $this->response->redirect(url_to("erp/project/projects"));
            }
            $this->data['amenities'] = $this->amenityModel->get_all_amenities();
            $this->data['types'] = $this->propertyTypeModel->get_all_propertytype();
            $this->data['page'] = "project/c_projectedit";
        }
        $this->data['project_id'] = $project_id;
        $this->data['members'] = $this->erpUsersModel->get_all_users();
        $this->data['menu'] = "project";
        $this->data['submenu'] = "project";
        return view("erp/index", $this->data);
    }

    public function projectview($project_id)
    {
        if (is_manufacturing_system()) {
            $this->data['project'] = $this->projectsModel->get_m_project_for_view($project_id);
            if (empty($this->data['project'])) {
                $this->session->setFlashdata("op_error", "Project Not Found");
                return $this->response->redirect(url_to("erp.project.projects"));
            }
            $this->data['page'] = "project/m_projectview";
        } else {
            $this->data['project'] = $this->projectsModel->get_c_project_for_view($project_id);
            $this->data['contractors'] = $this->contractorsModel->get_all_contractors();
            $this->data['contractor_datatable_config'] = $this->projectWorkgroupModel->get_dtconfig_project_contractor();
            if (empty($this->data['project'])) {
                $this->session->setFlashdata("op_error", "Project Not Found");
                return $this->response->redirect(url_to("erp.project.projects"));
            }
            $this->data['page'] = "project/c_projectview";
        }
        $this->data['project_members'] = $this->projectMembersModel->get_project_members($project_id);
        $this->data['project_status'] = $this->projectMembersModel->get_project_status();
        $this->data['project_status_bg'] = $this->projectMembersModel->get_project_status_bg();
        $this->data['testing_datatable_config'] = $this->projectTestingModel->get_dtconfig_project_testing();
        $this->data['workgroups'] = $this->workGroupsModel->get_all_workgroups();
        $this->data['teams'] = $this->teamsModel->get_all_teams();
        $this->data['phases'] = $this->projectPhaseModel->get_all_phases($project_id);
        $this->data['expense_datatable_config'] = $this->projectExpenseModel->get_dtconfig_project_expense();
        $this->data['rawmaterial_datatable_config'] = $this->projectRawMaterialsModel->get_dtconfig_project_rawmaterial();
        $this->data['attachments'] = $this->attachmentModel->get_attachments("project", $project_id);
        $this->data['project_id'] = $project_id;
        $this->data['menu'] = "project";
        $this->data['submenu'] = "project";
        return view("erp/index", $this->data);
    }

    public function uploadProjectAttachment()
    {
        $id = $this->request->getGet("id") ?? 0;
        $response = array();
        if (!empty($_FILES['attachment']['name'])) {
            $response = $this->attachmentModel->upload_attachment("project", $id);
        } else {
            $response['error'] = 1;
            $response['reason'] = "No file to upload";
        }
        return $this->response->setJSON($response);
    }

    public function projectDeleteAttachment()
    {
        $attach_id = $this->request->getGet("id") ?? 0;
        $response = array();
        $response = $this->attachmentModel->delete_attachment("project", $attach_id);
        return $this->response->setJSON($response);
    }

    public function expenseadd($project_id)
    {
        $post = [
            'name' => $this->request->getPost("name"),
            'expense_date' => $this->request->getPost("expense_date"),
            'amount' => $this->request->getPost("amount"),
            'payment_id' => $this->request->getPost("payment_id"),
            'description' => $this->request->getPost("description"),
        ];

        if ($this->request->getPost("name")) {
            if ($this->projectExpenseModel->insert_project_expense($project_id, $post)) {
                return $this->response->redirect(url_to("erp.project.projectview", $project_id));
            } else {
                var_dump($post);
                var_dump('E:\\Xampp Servers\\xampp8\\tmp\\php3BD9.tmp');
                var_dump('E:\\Xampp Servers\\xampp8\\htdocs\\ERP-CI4\\public\\uploads/expense/export(1)_4.xlsx');
                return $this->response->redirect(url_to("erp.project.expenseadd", $project_id));
            }
        }
        $this->data['paymentmodes'] = $this->paymentModesModel->get_all_paymentmodes();
        $this->data['project_id'] = $project_id;
        $this->data['page'] = "project/expenseadd";
        $this->data['menu'] = "project";
        $this->data['submenu'] = "project";
        return view("erp/index", $this->data);
    }

    public function ajaxProjectExpenseResponse()
    {
        $project_id = $this->request->getGet("projectid");
        $response = array();
        $response = $this->projectExpenseModel->get_project_expense_datatable($project_id);
        return $this->response->setJSON($response);
    }

    public function expenseedit($project_id, $expense_id)
    {
        $post = [
            'name' => $this->request->getPost("name"),
            'expense_date' => $this->request->getPost("expense_date"),
            'amount' => $this->request->getPost("amount"),
            'payment_id' => $this->request->getPost("payment_id"),
            'description' => $this->request->getPost("description"),
        ];
        if ($this->request->getPost("name")) {
            if ($this->projectExpenseModel->update_project_expense($project_id, $expense_id, $post)) {
                return $this->response->redirect(url_to("erp.project.projectview", $project_id));
            } else {
                return $this->response->redirect(url_to("erp.project.expenseedit", $project_id, $expense_id));
            }
        }
        $this->data['expense'] = $this->projectExpenseModel->get_project_expense_by_id($expense_id);
        $this->data['paymentmodes'] = $this->paymentModesModel->get_all_paymentmodes();
        $this->data['project_id'] = $project_id;
        $this->data['expense_id'] = $expense_id;
        $this->data['page'] = "project/expenseedit";
        $this->data['menu'] = "project";
        $this->data['submenu'] = "project";
        return view("erp/index", $this->data);
    }

    //NOT IMPLEMENTED
    public function expensedelete($project_id, $expense_id)
    {
        $this->projectExpenseModel->delete_projectexpense($project_id, $expense_id);
        return $this->response->redirect(url_to("erp.project.projectview", $project_id));
    }

    public function projectExpenseExport()
    {
        $project_id = $this->request->getGet("projectid");
        $type = $_GET['export'];
        $config = array();
        $config['title'] = "Project Expense";
        if ($type == "pdf") {
            $config['column'] = array("Name", "Expense Date", "Amount", "Payment Mode", "Description");
        } else {
            $config['column'] = array("Name", "Expense Date", "Amount", "Payment Mode", "Description");
            $config['column_data_type'] = array(ExporterConst::E_STRING, ExporterConst::E_STRING, ExporterConst::E_STRING, ExporterConst::E_STRING, ExporterConst::E_STRING);
        }
        $config['data'] = $this->projectExpenseModel->get_project_expense_export($type, $project_id);

        if ($type == "pdf") {
            Exporter::export(ExporterConst::PDF, $config);
        } else if ($type == "excel") {
            Exporter::export(ExporterConst::EXCEL, $config);
        } else if ($type == "csv") {
            Exporter::export(ExporterConst::CSV, $config);
        }
    }

    public function phaseaddedit($project_id)
    {
        $phase_id = $this->request->getPost("phase_id") ?? 0;
        $name = $this->request->getPost("name");
        $this->projectPhaseModel->insert_update_project_phase($project_id, $phase_id, $name);
        return $this->response->redirect(url_to("erp.project.projectview", $project_id));
    }

    public function phasestart($project_id, $phase_id)
    {
        $this->projectPhaseModel->start_phase($phase_id);
        return $this->response->redirect(url_to("erp.project.projectview", $project_id));
    }

    public function phaseworkgroup($project_id)
    {
        $post = [
            'wgroup_id' => $this->request->getPost("workgroup"),
            'phase_id' => $this->request->getPost("phase_id"),
            'team_id' => $this->request->getPost("team"),
        ];

        $post1 = [
            'wgroup_id' => $this->request->getPost("workgroup"),
            'phase_id' => $this->request->getPost("phase_id"),
            'worker_type' => $this->request->getPost("worker_type"),
            'team_id' => $this->request->getPost("team") ?? 0,
            'contractor_id' => $this->request->getPost("contractor") ?? 0,
        ];
        if (is_manufacturing_system()) {
            $this->projectWorkgroupModel->insert_phase_workgroup($project_id, $post);
        } else {
            $this->projectWorkgroupModel->insert_c_phase_workgroup($project_id, $post1);
        }
        return $this->response->redirect(url_to("erp.project.projectview", $project_id));
    }

    public function phasewgroupdelete($project_id, $project_wgrp_id)
    {
        $this->projectWorkgroupModel->delete_phase_workgroup($project_id, $project_wgrp_id);
        return $this->response->redirect(url_to("erp.project.projectview", $project_id));
    }

    public function phasewgroupcomplete($project_id, $project_wgrp_id)
    {
        $this->projectWorkgroupModel->complete_phase_workgroup($project_wgrp_id);
        return $this->response->redirect(url_to("erp.project.projectview", $project_id));
    }

    //Testing Project Management

    public function testingadd($project_id)
    {
        $post = [
            'name' => $this->request->getPost("name"),
            'complete_before' => $this->request->getPost("complete_before"),
            'assigned_to' => $this->request->getPost("assigned_to"),
            'description' => $this->request->getPost("description"),
        ];
        if ($this->request->getPost("name")) {
            if ($this->projectWorkgroupModel->insert_project_testing($project_id, $post)) {
                return $this->response->redirect(url_to("erp.project.projectview", $project_id));
            } else {
                return $this->response->redirect(url_to("erp.project.testingadd", $project_id));
            }
        }
        $this->data['project_id'] = $project_id;
        $this->data['page'] = "project/testingadd";
        $this->data['menu'] = "project";
        $this->data['submenu'] = "project";
        return view("erp/index", $this->data);
    }


    public function ajaxProjectTestingResponse()
    {   
        $logger = \Config\Services::logger();
        $logger->error($_GET);
        $project_id = $this->request->getGet("projectid");
        $response = array();
        $response = $this->projectTestingModel->get_project_testing_datatable($project_id);
        return $this->response->setJSON($response);
    }

    public function testingedit($project_id, $project_test_id)
    {
        $post = [
            'name' => $this->request->getPost("name"),
            'complete_before' => $this->request->getPost("complete_before"),
            'assigned_to' => $this->request->getPost("assigned_to"),
            'description' => $this->request->getPost("description"),
        ];

        if ($this->request->getPost("name")) {
            if ($this->projectTestingModel->update_project_testing($project_id, $project_test_id, $post)) {
                return $this->response->redirect(url_to("erp.project.projectview", $project_id));
            } else {
                return $this->response->redirect(url_to("erp.project.testingedit", $project_id, $project_test_id));
            }
        }
        $this->data['testing'] = $this->projectTestingModel->get_project_testing_by_id($project_test_id);
        if (empty($this->data['testing'])) {
            $this->session->setFlashdata("op_error", "Project Testing Not Found");
            return $this->response->redirect(url_to("erp.project.projects"));
        }
        $this->data['project_id'] = $project_id;
        $this->data['project_test_id'] = $project_test_id;
        $this->data['page'] = "project/testingedit";
        $this->data['menu'] = "project";
        $this->data['submenu'] = "project";
        return view("erp/index", $this->data);
    }

    public function testingview($project_id ,$project_test_id)
    {
        $this->data['testing'] = $this->projectTestingModel->get_project_testing_for_view($project_test_id);
        if (empty($this->data['testing'])) {
            $this->session->setFlashdata("op_error", "Project Testing Not Found");
            return $this->response->redirect(url_to("erp.project.projects"));
        }
        $this->data['testing_status'] = $this->projectTestingModel->get_testing_status();
        $this->data['testing_status_bg'] = $this->projectTestingModel->get_testing_status_bg();
        $this->data['attachments'] = $this->attachmentModel->get_attachments("project_testing", $project_test_id);
        $this->data['project_test_id'] = $project_test_id;
        $this->data['page'] = "project/testingview";
        $this->data['menu'] = "project";
        $this->data['project_id'] = $project_id;
        $this->data['submenu'] = "project";
        return view("erp/index", $this->data);
    }

    public function uploadTestingAttachment()
    {
        $id = $this->request->getGet("id") ?? 0;
        $response = array();
        if (!empty($_FILES['attachment']['name'])) {
            $response = $this->attachmentModel->upload_attachment("project_testing", $id);
        } else {
            $response['error'] = 1;
            $response['reason'] = "No file to upload";
        }
        return $this->response->setJSON($response);
    }

    public function testingDeleteAttachment()
    {
        $attach_id = $this->request->getGet("id") ?? 0;
        $response = array();
        $response = $this->attachmentModel->delete_attachment("project_testing", $attach_id);
        return $this->response->setJSON($response);
    }
    
    public function testingupdate($project_test_id)
    {
        $post = [
            'remarks' => $this->request->getPost("remarks"),
            'result' => $this->request->getPost("result"),
        ];
        $this->projectTestingModel->testing_update($project_test_id, $post);
        return $this->response->redirect(url_to("erp.project.testingview", $project_test_id));
    }



    public function projectTestingExport()
    {
        $project_id = $this->request->getGet("projectid");
        $type = $_GET['export'];
        $config = array();
        $config['title'] = "Project Testing";
        if ($type == "pdf") {
            $config['column'] = array("Name", "Created on", "Assigned to", "Complete Before", "Result");
        } else {
            $config['column'] = array("Name", "Description", "Created on", "Assigned to", "Complete Before", "Result", "Completed on", "Remarks");
            $config['column_data_type'] = array(ExporterConst::E_STRING, ExporterConst::E_STRING, ExporterConst::E_STRING, ExporterConst::E_STRING, ExporterConst::E_STRING, ExporterConst::E_STRING, ExporterConst::E_STRING, ExporterConst::E_STRING);
        }
        $config['data'] = $this->projectTestingModel->get_project_testing_export($type, $project_id);
        if ($type == "pdf") {
            Exporter::export(ExporterConst::PDF, $config);
        } else if ($type == "excel") {
            Exporter::export(ExporterConst::EXCEL, $config);
        } else if ($type == "csv") {
            Exporter::export(ExporterConst::CSV, $config);
        }
    }

    //NOT IMPLEMENTED
    // public function testingdelete($project_test_id)
    // {
    //     $this->projectTestingModel->delete_testing($project_test_id);
    //     return $this->response->redirect(url_to("erp.project.projects"));
    // }

    
    public function testingdelete($project_test_id, $project_id)
    {   
        if($this->projectTestingModel->delete_testing($project_test_id)){
            // session()->setFlashdata("op_success", "Project Testing Deleted Successfully");
            return $this->response->redirect(url_to("erp.project.projectview", $project_id));
        }else{
            // session()->setFlashdata("op_error", "Failed To Delete Project Testing");
            return $this->response->redirect(url_to("erp.project.projectview", $project_id));
        }
        
    }

    public function fromactiveworkgroup($project_id)
    {
        $this->projectWorkgroupModel->insert_from_active_workgroup($project_id);
        return $this->response->redirect(url_to("erp.project.projectview", $project_id));
    }

    public function ajaxProjectRawmaterialResponse()
    {
        $project_id = $this->request->getGet("projectid");
        $response = array();
        $response = $this->projectRawMaterialsModel->get_project_rawmaterial_datatable($project_id);
        return $this->response->setJSON($response);
    }

    public function rawmaterialadd($project_id)
    {
        $req_qty = $this->request->getPost("req_qty");
        $related_id = $this->request->getPost("related_id");
        if ($this->request->getPost("related_id")) {
            $this->projectRawMaterialsModel->insert_rawmaterial($project_id, $related_id, $req_qty);
        }
        return $this->response->redirect(url_to("erp.project.projectview", $project_id));
    }

    public function ajaxFetchRawmaterialQty($project_raw_id)
    {
        $response = array();
        $query = "SELECT project_raw_id,req_qty FROM project_rawmaterials WHERE project_raw_id=$project_raw_id ";
        $result = $this->db->query($query)->getRow();
        if (!empty($result)) {
            $response['error'] = 0;
            $response['data'] = $result;
        } else {
            $response['error'] = 1;
        }
        return $this->response->setJSON($response);
    }

    public function updateqty($project_id)
    {
        $project_raw_id = $this->request->getPost("project_raw_id");
        $req_qty = $this->request->getPost("req_qty");
        if ($this->request->getPost("req_qty")) {
            $this->projectRawMaterialsModel->update_rawmaterial($project_id, $project_raw_id, $req_qty);
        }
        return $this->response->redirect(url_to("erp.project.projectview", $project_id));
    }

    public function reqdispatch($project_id, $project_raw_id)
    {
        $this->projectRawMaterialsModel->request_dispatch($project_id, $project_raw_id);
        return $this->response->redirect(url_to("erp.project.projectview", $project_id));
    }


    public function rawmaterialdelete($project_id, $project_raw_id)
    {
        $this->projectRawMaterialsModel->delete_rawmaterial($project_id, $project_raw_id);
        return $this->response->redirect(url_to("erp.project.projectview", $project_id));
    }

    public function ajax_project_contractor_response()
    {
        $response = array();
        $response = $this->projectWorkgroupModel->get_project_contractor_datatable();
        return $this->response->setJSON($response);
    }

    public function ajax_fetch_contractor($project_wgrp_id)
    {
        $response = array();
        $query = "SELECT project_wgrp_id,amount,pay_before FROM project_workgroup WHERE project_wgrp_id=$project_wgrp_id ";
        $result = $this->db->query($query)->getRow();
        if (!empty($result)) {
            $response['error'] = 0;
            $response['data'] = $result;
        } else {
            $response['error'] = 1;
        }
        return $this->response->setJSON($response);
    }

    public function contractoredit($project_id)
    {
        $post = [
            'amount' => $this->request->getPost("amount"),
            'pay_before' => $this->request->getPost("pay_before"),
            'project_wgrp_id' => $this->request->getPost("project_wgrp_id"),
        ];
        if ($this->request->getPost("amount")) {
            $this->projectWorkgroupModel->update_contractor($project_id, $post);
        }
        return $this->response->redirect(url_to("erp.project.projectview", $project_id));
    }

    public function contractorview($project_id, $project_wgrp_id)
    {
        $this->data['contractor'] = $this->projectWorkgroupModel->get_project_contractor_for_view($project_wgrp_id);
        if (empty($this->data['contractor'])) {
            return $this->response->redirect(url_to("erp.project.projects"));
        }
        $this->data['contractor_status'] = $this->contractorsModel->get_contractor_status();
        $this->data['contractor_status_bg'] = $this->contractorsModel->get_contractor_status_bg();
        $this->data['project_id'] = $project_id;
        $this->data['project_wgrp_id'] = $project_wgrp_id;
        $this->data['paymentmodes'] = $this->paymentModesModel->get_all_paymentmodes();
        $this->data['payment_datatable_config'] = $this->paymentModesModel->get_dtconfig_payments();
        $this->data['attachments'] = $this->attachmentModel->get_attachments("project_contractor", $project_wgrp_id);
        $this->data['page'] = "project/contractorview";
        $this->data['menu'] = "project";
        $this->data['submenu'] = "project";
        return view("erp/index", $this->data);
    }

    public function upload_contractorattachment()
    {
        $id = $this->request->getGet('attach_id');
        $response = array();
        if (!empty($_FILES['attachment']['name'])) {
            $response = $this->attachmentModel->upload_attachment("project_contractor", $id);
        } else {
            $response['error'] = 1;
            $response['reason'] = "No file to upload";
        }
        return $this->response->setJSON($response);
    }

    public function contractor_delete_attachment()
    {
        $attach_id = $this->request->getGet('attach_id');
        $response = array();
        $response = $this->attachmentModel->delete_attachment("project_contractor", $attach_id);
        return $this->response->setJSON($response);
    }

    public function ajax_contractorpayment_response()
    {
        $response = array();
        $response = $this->contractorPaymentsModel->get_contractor_payment_datatable();
        return $this->response->setJSON($response);
    }

    public function contractorpayment($project_id, $project_wgrp_id)
    {
        $post = [
            'amount' => $this->request->getPost("amount"),
            'paid_on' => $this->request->getPost("paid_on"),
            'payment_id' => $this->request->getPost("payment_id"),
            'transaction_id' => $this->request->getPost("transaction_id"),
        ];
        $contractor_pay_id = $this->request->getPost("contractor_pay_id") ?? 0;
        if ($this->request->getPost("amount")) {
            $this->contractorPaymentsModel->insert_update_payment($project_id, $project_wgrp_id, $contractor_pay_id, $post);
        }
        return $this->response->redirect(url_to("erp.project.projectview" . $project_id));
    }

    public function ajax_fetch_contractorpayment($contractor_pay_id)
    {
        $response = array();
        $query = "SELECT * FROM contractor_payments WHERE contractor_pay_id=$contractor_pay_id";
        $result = $this->db->query($query)->getRow();
        if (!empty($result)) {
            $response['error'] = 0;
            $response['data'] = $result;
        } else {
            $response['error'] = 1;
        }
        return $this->response->setJSON($response);
    }

    public function contractorpaymentdelete($project_wgrp_id, $contractor_pay_id)
    {
        $query = "SELECT project_id FROM project_workgroup WHERE project_wgrp_id=$project_wgrp_id ";
        $project_id = $this->db->query($query)->getRow();
        if (!empty($project_id)) {
            $this->contractorPaymentsModel->delete_payment($project_id->project_id, $project_wgrp_id, $contractor_pay_id);
            return $this->response->redirect(url_to("erp.project.projectview", $project_id->project_id));
        } else {
            return $this->response->redirect(url_to("erp.project.projects"));
        }
    }

    public function contractor_payment_export()
    {
        $project_wgrp_id = $this->request->getGet("project_wgrp_id");
        $type = $_GET['export'];
        $config = array();
        $config['title'] = "Contractor Payments";
        if ($type == "pdf") {
            $config['column'] = array("Payment Mode", "Amount", "Paid on", "Transaction ID", "Notes");
        } else {
            $config['column'] = array("Payment Mode", "Amount", "Paid on", "Transaction ID", "Notes");
            $config['column_data_type'] = array(ExporterConst::E_STRING, ExporterConst::E_STRING, ExporterConst::E_STRING, ExporterConst::E_STRING, ExporterConst::E_STRING);
        }
        $config['data'] = $this->contractorPaymentsModel->get_contractorpayment_export($type, $project_wgrp_id);
        if ($type == "pdf") {
            Exporter::export(ExporterConst::PDF, $config);
        } else if ($type == "excel") {
            Exporter::export(ExporterConst::EXCEL, $config);
        } else if ($type == "csv") {
            Exporter::export(ExporterConst::CSV, $config);
        }
    }

    public function project_contractor_export()
    {
        $project_id = $this->request->getGet("projectid");
        $type = $_GET['export'];
        $config = array();
        $config['title'] = "Project Contractors";
        if ($type == "pdf") {
            $config['column'] = array("Workgroup", "Contractor", "Amount", "Pay Before", "Paid Till", "Due", "Status");
        } else {
            $config['column'] = array("Workgroup", "Contractor", "Amount", "Pay Before", "Paid Till", "Due", "Status");
            $config['column_data_type'] = array(ExporterConst::E_STRING, ExporterConst::E_STRING, ExporterConst::E_STRING, ExporterConst::E_STRING, ExporterConst::E_STRING, ExporterConst::E_STRING, ExporterConst::E_STRING);
        }
        $config['data'] = $this->contractorPaymentsModel->get_project_contractor_export($type, $project_id);
        if ($type == "pdf") {
            Exporter::export(ExporterConst::PDF, $config);
        } else if ($type == "excel") {
            Exporter::export(ExporterConst::EXCEL, $config);
        } else if ($type == "csv") {
            Exporter::export(ExporterConst::CSV, $config);
        }
    }

    //NOT IMPLEMENTED
    public function projectdelete($project_id)
    {
        $this->projectsModel->projectDelete($project_id);
        return $this->response->redirect(url_to("erp.project.projects"));
    }

    public function projectsExport()
    {
        $type = $_GET['export'];
        $config = array();
        $config['title'] = "Projects";
        if (is_manufacturing_system()) {
            if ($type == "pdf") {
                $config['column'] = array("Name", "Product", "Budget", "Start Date", "End Date", "Status");
            } else {
                $config['column'] = array("Name", "Product", "Customer", "Budget", "Start Date", "End Date", "Status", "Description");
                $config['column_data_type'] = array(ExporterConst::E_STRING, ExporterConst::E_STRING, ExporterConst::E_STRING, ExporterConst::E_STRING, ExporterConst::E_STRING, ExporterConst::E_STRING, ExporterConst::E_STRING, ExporterConst::E_STRING);
            }
            $config['data'] = $this->projectsModel->get_m_project_export($type);
        } else {
            if ($type == "pdf") {
                $config['column'] = array("Name", "Type", "Budget", "Start Date", "End Date", "Status");
            } else {
                $config['column'] = array("Name", "Type", "Units", "Customer", "Budget", "Start Date", "End Date", "Status", "Address", "City", "State", "Country", "Zipcode", "Description");
                $config['column_data_type'] = array(ExporterConst::E_STRING, ExporterConst::E_STRING, ExporterConst::E_STRING, ExporterConst::E_STRING, ExporterConst::E_STRING, ExporterConst::E_STRING, ExporterConst::E_STRING, ExporterConst::E_STRING, ExporterConst::E_STRING, ExporterConst::E_STRING, ExporterConst::E_STRING, ExporterConst::E_STRING, ExporterConst::E_STRING, ExporterConst::E_STRING);
            }
            $config['data'] = $this->projectsModel->get_c_project_export($type);
        }

        if ($type == "pdf") {
            Exporter::export(ExporterConst::PDF, $config);
        } else if ($type == "excel") {
            Exporter::export(ExporterConst::EXCEL, $config);
        } else if ($type == "csv") {
            Exporter::export(ExporterConst::CSV, $config);
        }
    }

    public function updateStatus()
    {
        $project_id = $this->request->getGet("projectid");
        $status = $this->request->getPost('status');

        $query = "UPDATE projects SET status = ? WHERE project_id = ?";
        $result = $this->db->query($query, array($project_id, $status));

        if ($result) {
            return $this->response->setJSON(['result' => true]);
        } else {
            $error = $this->db->error();
            return $this->response->setJSON(['result' => false, 'error' => $error['message']]);
        }
    }
}
