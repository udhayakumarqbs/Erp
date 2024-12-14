<?php

namespace App\Config;

namespace App\Controllers\Erp;

use CodeIgniter\Database\Backup;
use App\Controllers\BaseController;
use App\Libraries\Exporter;
use App\Libraries\Exporter\ExporterConst;
use App\Libraries\mailer\MailerFactory as MailerMailerFactory;
use App\Libraries\MailerFactory;
use App\Models\Erp\ErpGroupsModel;
use App\Models\Erp\ErpRolesModel;
use App\Models\Erp\ErpSettingsModel;
use App\Models\Erp\ErpUsersModel;
use App\Models\Erp\UserModel;
use App\Models\Erp\ErpCompanyInformationModel;
use App\Models\Erp\ErpActivityLoggerModel;
use App\Models\Erp\ContracttypeModel;
use App\Models\Erp\Employee2Model;
use App\Models\Erp\EmailTemplateModel;
use App\Config\Mimes;

use CodeIgniter\HTTP\Exceptions\HTTPException;

class SettingController extends BaseController
{
    protected $data;
    protected $db;
    protected $session;
    protected $ErpUsersModel;
    protected $ErpRolesModel;
    protected $ErpSettingsModel;
    protected $ErpGroupsModel;
    protected $UserModel;
    protected $ErpCompanyInformationModel;
    protected $ErpActivityLoggerModel;
    protected $mimes;
    protected $ContracttypeModel;
    protected $Employee2Model;

    protected $EmailtemplateModel;

    public function __construct()
    {
        $this->ErpUsersModel = new ErpUsersModel();
        $this->ErpRolesModel = new ErpRolesModel();
        $this->ErpSettingsModel = new ErpSettingsModel();
        $this->ErpGroupsModel = new ErpGroupsModel();
        $this->UserModel = new UserModel();
        $this->ErpCompanyInformationModel = new ErpCompanyInformationModel();
        $this->ErpActivityLoggerModel = new ErpActivityLoggerModel();
        $this->ContracttypeModel = new ContracttypeModel();
        $this->Employee2Model = new Employee2Model();
        $this->EmailtemplateModel = new EmailTemplateModel();
        $this->db = \Config\Database::connect();
        $this->session = \Config\Services::session();
        helper(['erp', 'form', 'filesystem']);
    }

    public function users()
    {
        if (!is_admin()) {
            $this->session->setFlashdata("op_error", "Access Denied !!!");
            return $this->response->redirect(url_to("erp.login"));
        }
        $this->data['datatable_config'] = $this->ErpUsersModel->get_dtconfig_users();
        $this->data['roles'] = $this->ErpUsersModel->get_all_roles();
        $this->data['menu'] = "setting";
        $this->data['submenu'] = "user";
        $this->data['page'] = "setting/users";
        return view("erp/index", $this->data);
    }
    //user add udhayakumar R
    public function useradd()
    {

        if ($this->request->getPost()) {

            $UserModel = new UserModel();
            $n = $this->request->getPost("staff_member");

            $first_name = '';
            $last_name = '';

            if (!empty($n)) {
                $name_parts = preg_split('/\s+/', trim($n));
                $first_name = $name_parts[0];

                if (count($name_parts) > 2){
                    $last_name = $name_parts[1] +  $name_parts[2];
                }else{
                    $last_name = $name_parts[1];
                }

            }
            $post = [
                'name' => $first_name,
                'last_name' => $last_name,
                'staff_id' => $this->request->getPost("staff_id"),
                'email' => $this->request->getPost("email"),
                'phone' => $this->request->getPost("phone"),
                'role_id' => $this->request->getPost("role_id") ?? 0,
                'position' => $this->request->getPost("position"),
                'is_admin' => $this->request->getPost("is_admin") ?? 0,
                'description' => $this->request->getPost("description"),
                'password' => $this->request->getPost("password"),
            ];
            $post['password'] = password_hash($post['password'],PASSWORD_DEFAULT);

            if (!is_admin()) {
                $this->session->setFlashdata("op_error", "Access Denied !!!");
                return $this->response->redirect(url_to("erp.login"));
            }

            if ($UserModel->where("staff_id", $post["staff_id"])->first() > 0) {
                $this->session->setFlashdata("op_error", "User Already Exist!");
                return $this->response->redirect(url_to('erp.setting.users'));
            } 


            if ($UserModel->addusers($post)) {
                if (is_admin()) {
                    return $this->response->redirect(url_to('erp.setting.users'));
                }
            } else {
                return $this->response->redirect(url_to('erp.users.add'));
            }
        }
   
        $this->data['roles'] = $this->ErpUsersModel->get_all_roles();
        $this->data['menu'] = "setting";
        $this->data['submenu'] = "user";
        $this->data['page'] = "setting/useradd";
        return view("erp/index", $this->data);
    }
    //

    public function useredit($id = 0)
    {
        $post = [
            'name' => $this->request->getPost("name"),
            'last_name' => $this->request->getPost("last_name"),
            'email' => $this->request->getPost("email"),
            'phone' => $this->request->getPost("phone"),
            'role_id' => $this->request->getPost("role_id") ?? 0,
            'position' => $this->request->getPost("position"),
            'is_admin' => $this->request->getPost("is_admin") ?? 0,
            'description' => $this->request->getPost("description"),
            'password' => $this->request->getPost("password"),
        ];
        if (!is_admin() && !is_same_user($id)) {
            $this->session->setFlashdata("op_error", "Access Denied !!!");
            return $this->response->redirect(url_to("erp.login"));
        }
        if ($this->request->getPost("name")) {
            if ($this->ErpUsersModel->update_user($id, $post)) {
                if (is_admin()) {
                    return $this->response->redirect(url_to('erp.setting.users'));
                } else if (is_same_user($id)) {
                    return $this->response->redirect(url_to('erp.login'));
                }
            } else {
                return $this->response->redirect(url_to('erp.setting.useredit', $id));
            }
        }
        $this->data['user_id'] = $id;
        $this->data['roles'] = $this->ErpUsersModel->get_all_roles();
        $this->data['user'] = $this->ErpUsersModel->get_user_by_id($id);
        if (empty($this->data['user'])) {
            $this->session->setFlashdata("op_error", "User not found");
            return $this->response->redirect(url_to("erp.setting.users"));
        }
        $this->data['menu'] = "setting";
        $this->data['submenu'] = "user";
        $this->data['page'] = "setting/useredit";
        return view("erp/index", $this->data);
    }

    public function ajaxMailUnique()
    {
        $email = $_GET['data'];
        $id = $_GET['id'];
        $query = "SELECT email FROM erp_users WHERE email='$email' AND user_id<>$id";
        $check = $this->db->query($query)->getRow();
        $response = array();
        if (empty($check)) {
            $response['valid'] = 1;
        } else {
            $response['valid'] = 0;
            $response['msg'] = "Email ID already exists";
        }
        header("content-type:application/json");
        echo json_encode($response);
        exit();
    }

    public function ajaxUsersResponse()
    {
        $response = array();
        if (!is_admin()) {
            $response['data'] = "failed to fetch";
            $response['reason'] = "auth failed";
        } else {
            $response = $this->UserModel->get_users_datatable();
        }
        header("Content-Type:application/json");
        echo json_encode($response);
        exit();
    }

    public function userExport()
    {
        $type = $_GET['export'];
        $config = array();
        $config['title'] = "Users";
        if ($type == "pdf") {
            $config['column'] = array("Name", "Email", "Phone", "Position", "Role");
        } else {
            $config['column'] = array("Name", "Email", "Phone", "Position", "Role");
            $config['column_data_type'] = array(ExporterConst::E_STRING, ExporterConst::E_STRING, ExporterConst::E_STRING, ExporterConst::E_STRING, ExporterConst::E_STRING);
        }
        $config['data'] = $this->ErpUsersModel->get_users_export($type);

        if ($type == "pdf") {
            Exporter::export(ExporterConst::PDF, $config);
        } else if ($type == "excel") {
            Exporter::export(ExporterConst::EXCEL, $config);
        } else if ($type == "csv") {
            Exporter::export(ExporterConst::CSV, $config);
        }
    }

    public function roles()
    {
        if (!is_admin()) {
            $this->session->setFlashdata("op_error", "Access Denied !!!");
            return $this->response->redirect(url_to("erp.login"));
        }
        $this->data['datatable_config'] = $this->ErpRolesModel->get_dtconfig_roles();
        $this->data['menu'] = "setting";
        $this->data['submenu'] = "role";
        $this->data['page'] = "setting/roles";
        return view("erp/index", $this->data);
    }

    public function roleadd()
    {
        $post = [
            'role_name' => $this->request->getPost("role_name"),
            'role_desc' => $this->request->getPost("role_desc"),
        ];
        if (!is_admin()) {
            $this->session->setFlashdata("op_error", "Access Denied !!!");
            return $this->response->redirect(url_to("erp.login"));
        }
        if ($this->request->getPost("role_name")) {
            if ($this->ErpRolesModel->insert_role($post)) {
                return $this->response->redirect(url_to('erp.setting.roles'));
            } else {
                return $this->response->redirect(url_to('erp.setting.roleadd'));
            }
        }
        $this->data['menu'] = "setting";
        $this->data['submenu'] = "role";
        $this->data['page'] = "setting/roleadd";
        return view("erp/index", $this->data);
    }

    public function ajaxRolesResponse()
    {
        $response = array();
        if (!is_admin()) {
            $response['data'] = "failed to fetch";
            $response['reason'] = "auth failed";
        } else {
            $response = $this->ErpRolesModel->get_datatable_roles();
        }
        header("Content-Type:application/json");
        echo json_encode($response);
        exit();
    }

    public function roleedit($id)
    {
        $post = [
            'role_name' => $this->request->getPost("role_name"),
            'role_desc' => $this->request->getPost("role_desc"),
            'do_reflect' => $this->request->getPost("do_reflect") ?? 0,
        ];
        if (!is_admin()) {
            $this->session->setFlashdata("op_error", "Access Denied !!!");
            return $this->response->redirect(url_to("erp.login"));
        }
        if ($this->request->getPost("role_name")) {
            if ($this->ErpRolesModel->update_role($id, $post)) {
                return $this->response->redirect(url_to('erp.setting.roles'));
            } else {
                return $this->response->redirect(url_to('erp.setting.roleedit', $id));
            }
        }
        $this->data['role_id'] = $id;
        $this->data['role'] = $this->ErpRolesModel->get_role_by_id($id);
        if (empty($this->data['role'])) {
            $this->session->setFlashdata("op_error", "Role not found");
            return $this->response->redirect(url_to("erp.setting.roles"));
        }
        $this->data['menu'] = "setting";
        $this->data['submenu'] = "role";
        $this->data['page'] = "setting/roleedit";
        return view("erp/index", $this->data);
    }

    public function roledelete($id)
    {
        if (!is_admin()) {
            $this->session->setFlashdata("op_error", "Access Denied !!!");
            return $this->response->redirect(url_to("erp.login"));
        }
        $this->ErpRolesModel->delete_role($id);
        return $this->response->redirect(url_to('erp.setting.roles'));
    }

    public function roleview($id)
    {
        if (!is_admin()) {
            $this->session->setFlashdata("op_error", "Access Denied !!!");
            return $this->response->redirect(url_to("erp.login"));
        }
        $this->data['role'] = $this->ErpRolesModel->get_role_by_id($id);
        if (empty($this->data['role'])) {
            $this->session->setFlashdata("op_error", "Role not found");
            return $this->response->redirect(url_to("erp.setting.roles"));
        }
        $this->data['menu'] = "setting";
        $this->data['submenu'] = "role";
        $this->data['page'] = "setting/roleview";
        return view("erp/index", $this->data);
    }

    public function roleExport()
    {
        $type = $_GET['export'];
        $config = array();
        $config['title'] = "Roles";
        if ($type == "pdf") {
            $config['column'] = array("Id", "Role Name", "Description");
        } else {
            $config['column'] = array("Id", "Role Name", "Description");
            $config['column_data_type'] = array(ExporterConst::E_INTEGER, ExporterConst::E_STRING, ExporterConst::E_STRING);
        }
        $config['data'] = $this->ErpRolesModel->get_roles_export($type);

        if ($type == "pdf") {
            Exporter::export(ExporterConst::PDF, $config);
        } else if ($type == "excel") {
            Exporter::export(ExporterConst::EXCEL, $config);
        } else if ($type == "csv") {
            Exporter::export(ExporterConst::CSV, $config);
        }
    }

    public function smtp()
    {
        if (!is_admin()) {
            $this->session->setFlashdata("op_error", "Access Denied !!!");
            return $this->response->redirect(url_to("erp.login"));
        }
        if (!empty($this->request->getPost("mail_engine"))) {
            $this->ErpSettingsModel->update_smtp();
            return $this->response->redirect(url_to('erp.setting.smtp'));
        }
        $this->data['smtp'] = $this->ErpSettingsModel->get_smtp_settings();
        $this->data['menu'] = "setting";
        $this->data['submenu'] = "smtp";
        $this->data['page'] = "setting/smtp";
        return view("erp/index", $this->data);
    }

    public function sendtestemail()
    {
        if (!is_admin()) {
            $this->session->setFlashdata("op_error", "Access Denied !!!");
            return $this->response->redirect(url_to("erp.login"));
        }

        $email_to = $this->request->getPost("email_to");
        if (!empty($email_to)) {
            $mailer = MailerFactory::getMailer();
            $mailer->addTo($email_to);
            $mailer->addSubject("Testing Mail From ERP");
            $mailer->addBody("<p>This is a test email </p>");
            if ($mailer->send()) {
                $this->session->setFlashdata("op_success", "Seems like your Mail Settings are correct");
            } else {
                $this->session->setFlashdata("op_error", "Mail Settings are incorrect !!!");
            }
        }
        return $this->response->redirect(url_to("erp.setting.smtp"));
    }

    public function test()
    {
        return view("erp.emailtemplates.basetemplate", $this->data);
    }

    public function groups()
    {
        if (!is_admin()) {
            $this->session->setFlashdata("op_error", "Access Denied !!!");
            return $this->response->redirect(url_to("erp.login"));
        }
        $this->data['datatable_config'] = $this->ErpGroupsModel->get_dtconfig_groups();
        $this->data['related_to'] = $this->ErpGroupsModel->get_related_to();
        $this->data['menu'] = "setting";
        $this->data['submenu'] = "group";
        $this->data['page'] = "setting/groups";
        return view("erp/index", $this->data);
    }

    public function ajaxGroupsResponse()
    {
        $response = array();
        if (!is_admin()) {
            $response['data'] = "failed to fetch";
            $response['reason'] = "auth failed";
        } else {
            $response = $this->ErpGroupsModel->get_datatable_groups();
        }
        header("Content-Type:application/json");
        echo json_encode($response);
        exit();
    }

    public function groupaddedit()
    {
        $post = [
            'group_id' => $this->request->getPost("group_id"),
            'group_name' => $this->request->getPost("group_name"),
            'related_to' => $this->request->getPost("related_to"),
        ];
        if (!is_admin()) {
            $this->session->setFlashdata("op_error", "Access Denied !!!");
            return $this->response->redirect(url_to("erp.login"));
        }
        $this->ErpGroupsModel->insert_update_group($post);
        return $this->response->redirect(url_to("erp.setting.groups"));
    }

    public function groupdelete($id)
    {
        if (!is_admin()) {
            $this->session->setFlashdata("op_error", "Access Denied !!!");
            return $this->response->redirect(url_to("erp.login"));
        }
        $this->ErpGroupsModel->delete_group($id);
        return $this->response->redirect(url_to("erp.setting.groups"));
    }

    public function ajaxFetchGroup($group_id)
    {
        $response = array();
        if (!is_admin()) {
            $response['data'] = "failed to fetch";
            $response['reason'] = "auth failed";
        } else {
            $query = "SELECT * FROM erp_groups WHERE group_id=$group_id";
            $result = $this->db->query($query)->getRow();
            if (!empty($result)) {
                $response['error'] = 0;
                $response['data'] = $result;
            } else {
                $response['error'] = 1;
                $response['reason'] = "No data";
            }
        }
        header("Content-Type:application/json");
        echo json_encode($response);
        exit();
    }

    public function finance()
    {
        if (!is_admin()) {
            $this->session->setFlashdata("op_error", "Access Denied !!!");
            return $this->response->redirect(url_to("erp.login"));
        }
        if (!empty($this->request->getPost("close_account_book"))) {
            $this->ErpSettingsModel->update_finance();
            return $this->response->redirect(url_to('erp.setting.finance'));
        }
        $this->data['finance'] = $this->ErpSettingsModel->get_finance_settings();
        $this->data['menu'] = "setting";
        $this->data['submenu'] = "finance";
        $this->data['page'] = "setting/finance";
        return view("erp/index", $this->data);
    }

    public function companyInformation()
    {
        if ($data['company_name'] = $this->request->getPost('company_name')) {
            $data['company_name'] = $this->request->getPost('company_name');
            $data['address'] = $this->request->getPost('address');
            $data['city'] = $this->request->getPost('city');
            $data['state'] = $this->request->getPost('state');
            $data['country'] = $this->request->getPost('country');
            $data['zipcode'] = $this->request->getPost('zipcode');
            $data['phone_number'] = $this->request->getPost('phone');
            $data['vat_number'] = $this->request->getPost('vat_number');
            $data['license_number'] = $this->request->getPost('license_number');

            $newLogo = $this->request->getFile('company_logo');
            if (!empty($newLogo) && $newLogo->isValid() && !$newLogo->hasMoved()) {
                $filename = $newLogo->getRandomName();
                try {
                    $newLogo->move(FCPATH . 'assets/uploads/setting/upload_images', $filename);
                    $this->ErpCompanyInformationModel->deleteLogo();
                    $data['company_logo'] = $filename;
                } catch (HTTPException $ex) {
                }
            } else { 
                $data['company_logo'] = $this->request->getPost("company_logo");
            }

            if ($this->ErpCompanyInformationModel->updateCompanyData($data)) {
                $config['log_text'] = "[Company Information Updated Successfully!]";
                $config['ref_link'] = url_to('erp.setting.companyinformation');
                $this->session->setFlashdata('op_success', 'Company Information Updated Successfully!');
                return $this->response->redirect(url_to('erp.setting.companyinformation'));
            } else {
                $config['log_text'] = "[Company Information Failed to Update.]";
                $config['ref_link'] = url_to('erp.setting.companyinformation');
                $this->session->setFlashdata('op_error', 'Company Information Failed to Update!');
                return $this->response->redirect(url_to('erp.setting.companyinformation'));
            }
        }
        $this->data['companyData']  = $this->ErpCompanyInformationModel->getAllData();
        $this->data['menu'] = "setting";
        $this->data['submenu'] = "company_info";
        $this->data['page'] = "setting/companyinformation";
        return view("erp/index", $this->data);
    }


    public function activityLogger()
    {
        $this->data['allData'] = $this->ErpActivityLoggerModel->getAllData();
        $this->data['menu'] = "setting";
        $this->data['submenu'] = "activitylog";
        $this->data['page'] = "setting/activitylog";

        $this->data['data_config'] = $this->ErpActivityLoggerModel->getDtConfig();
        return view("erp/index", $this->data);
    }

    public function activityLogDt()
    {
    }



    public function activityLoggerDelete()
    {
        if ($this->ErpActivityLoggerModel->deleteAllData()) {
            $this->session->setFlashdata('op_success', "Activity Logger Deleted Successfully");
            $config['log_text'] = "[Activity Log Deleted Successfully.]";
            $config['ref_link'] = url_to('erp.setting.activitylog');
        } else {
            $this->session->setFlashdata('op_error', "Activity Logger Failed to Delete");
            $config['log_text'] = "[Activity Log Failed to Delete.]";
            $config['ref_link'] = url_to('erp.setting.activitylog');
        }
        return $this->response->redirect(url_to('erp.setting.activitylog'));
    }

    //udhaya
    public function backupIndex()
    {

        $this->data['menu'] = "setting";
        $this->data['submenu'] = "database_backup";
        $this->data['page'] = "setting/databasebackup";

        return view("erp/index", $this->data);
    }
    //udhaya
    public function generateDbBackup()
    {
        $response = array();

        $db = \Config\Database::connect();
        $util = \Config\Database::Utils($db);

        $prefs = [
            'format' => 'sql'
        ];

        $backup = $util->backup($prefs);


        //$backup = $utils->export();
        $backup_file_name = 'backup_' . date('y_m_d_His') . '.sql';
        $backup_path = get_attachment_path("database") . $backup_file_name;
        $config = [];
        $config['title'] = 'Generating Backup';
        if (write_file($backup_path, $backup)) {
            $this->session->setFlashdata('op_success', "Datebase Backup Created Successfully");
            $config['log_text'] = "[Datebase Backup Created Successfully.]";
            $config['ref_link'] = url_to('erp.setting.databasebackupview');
        } else {
            $this->session->setFlashdata('op_error', "Datebase backup failed to create");
            $config['log_text'] = "[Datebase Backup Created Successfully.]";
            $config['ref_link'] = url_to('erp.setting.databasebackupview');
        }
        log_activity($config);
        return $this->response->redirect(url_to('erp.setting.databasebackupview'));
    }
    //database Download

    function download($path)
    {
        $pathFile = get_attachment_path('database') . $path . '.sql';
        if (file_exists($pathFile)) {
            force_download($pathFile, null);
        } else {
            $this->session->setFlashdata('op_error', "Could not download backup file.");
            $this->response->redirect(url_to('erp.setting.databasebackupview'));
        }
    }
    //udhaya database delete
    function db_delete($path)
    {
        $path = get_attachment_path('database') . $path . '.sql';
        if (unlink($path)) {
            $this->session->setFlashdata('op_success', 'Backup deleted successfully');
        }
        $this->response->redirect(url_to('erp.setting.databasebackupview'));
    }

    public function contracttype_view()
    {
        $this->data["menu"] = "setting";
        $this->data["submenu"] = "contracttype";
        $this->data["page"] = "setting/contracttype_view";
        $this->data["dt_config"] = $this->ContracttypeModel->get_fetch_coloum();

        return view("erp/index", $this->data);
    }


  
    public function ajaxaddcontract()
    {
        $data = [
            "cont_name" => $this->request->getPost("contract_name")
        ];
        $id = $this->request->getPost("id");
        if (empty($id)) {
            $result = $this->ContracttypeModel->add_contract_type($data);
            if ($result) {
                $this->session->setFlashdata('op_success', 'Contract Type Added successfully');
                $this->response->redirect(url_to('er.setting.contracttype'));
            } else {
                $this->session->setFlashdata('op_error', 'Error occured');
                $this->response->redirect(url_to('er.setting.contracttype'));
            }
        } else {
            $result = $this->ContracttypeModel->get_contract_data($id, $data);

            if ($result) {
                $this->session->setFlashdata('op_success', 'Contract Type Updated successfully');
                $this->response->redirect(url_to('er.setting.contracttype'));
            } else {
                $this->session->setFlashdata('op_error', 'Error occured');
                $this->response->redirect(url_to('er.setting.contracttype'));
            }
        }
    }

    public function ajaxContractDataTable()
    {

        $result = array();
        $result = $this->ContracttypeModel->fetch_contract_data_table();

        return $this->response->setJSON($result);
    }

    public function contracttypedelete($id)
    {
        $result = $this->ContracttypeModel->delete_contract_type($id);
        if ($result == true) {
            $this->session->setFlashdata('op_success', 'Contract type deleted Successfully');
            $this->response->redirect(url_to('er.setting.contracttype'));
        } elseif ($result == 'error') {
            $this->session->setFlashdata('op_error', 'Error Occured');
            $this->response->redirect(url_to('er.setting.contracttype'));
        } elseif ($result == false) {
            $this->session->setFlashdata('op_error', 'The ID of Contract Type is Already using!');
            $this->response->redirect(url_to('er.setting.contracttype'));
        }
    }

    public function ajaxemployee()
    {
        $search = $_GET['search'] ?? "";
        $response = array();
        $response['error'] = 1;
        $db2 = \Config\Database::connect("human_resource");
        if (!empty($search)) {
            $query = "SELECT emp_id,CONCAT(first_name,'',last_name) AS name FROM employee WHERE first_name LIKE '%" . $search . "%' OR last_name LIKE '%" . $search . "%' LIMIT 10";
            $result = $db2->query($query)->getResultArray();
            $m_result = array();
            foreach ($result as $r) {
                array_push($m_result, array(
                    "key" => $r['emp_id'],
                    "value" => $r['name']
                ));
            }
            $response['error'] = 0;
            $response['data'] = $m_result;
        }
        return $this->response->setJSON($response);
    }

    public function employees_details_by_id()
    {
        $id = $_POST["id"];
        $result = $this->Employee2Model->get_employees_details_id($id);
        return $this->response->setJSON($result);
    }
    



    // Email Templates

    public function emailTemplates()
    {
        $this->data["menu"] = "setting";
        $this->data["submenu"] = "mailtemplate";
        $this->data["page"] = "setting/email-template";
        $this->data["dt_config"] = $this->EmailtemplateModel->get_dtconfig_emailtemplate();

        return view("erp/index", $this->data);
    }

// email template view

    public function templateview()
    {
        $this->data["menu"] = "setting";
        $this->data["submenu"] = "mailtemplate";
        $this->data["page"] = "setting/email-template-view";
        return view("erp/index", $this->data);
    }

}
