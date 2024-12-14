<?php

namespace App\Controllers\Erp;

use App\Controllers\BaseController;
use App\Libraries\Exporter;
use App\Libraries\Exporter\ExporterConst;
use App\Libraries\Importer;
use App\Libraries\Importer\AbstractImporter;
use App\Models\Erp\AdditionsModel;
use App\Models\Erp\AttachmentModel;
use App\Models\Erp\ContractorsModel;
use App\Models\Erp\DeductionsModel;
use App\Models\Erp\DepartmentModel;
use App\Models\Erp\DesignationModel;
use App\Models\Erp\EmpAttendanceModel;
use App\Models\Erp\EmployeesModel;
use App\Models\Erp\PayrollEntryModel;
use App\Libraries\Attendance;

class HRController extends BaseController
{
    protected $data;
    protected $db;
    protected $session;
    protected $DepartmentModel;
    protected $DesignationModel;
    protected $DeductionsModel;
    protected $EmployeesModel;
    protected $AttachmentModel;
    protected $EmpAttendanceModel;
    protected $ContractorsModel;
    protected $AdditionsModel;
    protected $PayrollEntryModel;
    protected $logger;
    public function __construct()
    {
        $this->DepartmentModel = new DepartmentModel();
        $this->DesignationModel = new DesignationModel();
        $this->DeductionsModel = new DeductionsModel();
        $this->EmployeesModel = new EmployeesModel();
        $this->AttachmentModel = new AttachmentModel();
        $this->EmpAttendanceModel = new EmpAttendanceModel();
        $this->ContractorsModel = new ContractorsModel();
        $this->AdditionsModel = new AdditionsModel();
        $this->PayrollEntryModel = new PayrollEntryModel();
        $this->db = \Config\Database::connect();
        $this->session = \Config\Services::session();
        $this->logger = \Config\Services::session();
        helper(['erp', 'form']);
    }

    public function departments()
    {
        $post = [
            'department_name' => $this->request->getPost("department_name"),
            'description' => $this->request->getPost("description"),
        ];
        $department_id = $this->request->getPost("department_id") ?? 0;
        if ($this->request->getPost("department_name")) {
            $this->DepartmentModel->insert_update_department($department_id, $post);
            return $this->response->redirect(url_to("erp.hr.departments"));
        }
        $this->data['department_datatable_config'] = $this->DepartmentModel->get_dtconfig_department();
        $this->data['page'] = "hr/departments";
        $this->data['menu'] = "hr";
        $this->data['submenu'] = "department";
        return view("erp/index", $this->data);
    }

    public function ajaxDepartmentResponse()
    {
        $response = array();
        $response = $this->DepartmentModel->get_department_datatable();
        return $this->response->setJSON($response);
    }

    public function ajaxDepartmentNameUnique()
    {
        $name = $_GET['data'];
        $id = $_GET['id'] ?? 0;
        $query = "SELECT name FROM department WHERE name='$name' ";
        if (!empty($id)) {
            $query = "SELECT name FROM department WHERE name='$name' AND department_id<>$id";
        }
        $check = $this->db->query($query)->getRow();
        $response = array();
        if (empty($check)) {
            $response['valid'] = 1;
        } else {
            $response['valid'] = 0;
            $response['msg'] = "Name already exists";
        }
        return $this->response->setJSON($response);
    }

    public function ajaxFetchDepartment($department_id)
    {
        $response = array();
        $query = "SELECT department_id,name,description FROM department WHERE department_id=$department_id";
        $result = $this->db->query($query)->getRow();
        if (!empty($result)) {
            $response['error'] = 0;
            $response['data'] = $result;
        } else {
            $response['error'] = 1;
        }
        return $this->response->setJSON($response);
    }

    public function departmentdelete($department_id)
    {
        $this->DepartmentModel->delete_department($department_id);
        return $this->response->redirect(url_to("erp.hr.departments"));
    }

    public function departmentExport()
    {
        $type = $_GET['export'];
        $config = array();
        $config['title'] = "Departments";
        if ($type == "pdf") {
            $config['column'] = array("Name", "Description");
        } else {
            $config['column'] = array("Name", "Description");
            $config['column_data_type'] = array(ExporterConst::E_STRING, ExporterConst::E_STRING);
        }
        $config['data'] = $this->DepartmentModel->get_department_export($type);
        if ($type == "pdf") {
            Exporter::export(ExporterConst::PDF, $config);
        } else if ($type == "excel") {
            Exporter::export(ExporterConst::EXCEL, $config);
        } else if ($type == "csv") {
            Exporter::export(ExporterConst::CSV, $config);
        }
    }

    public function designation()
    {
        $post = [
            'designation_name' => $this->request->getPost("designation_name"),
            'description' => $this->request->getPost("description"),
            'department_id' => $this->request->getPost("department_id") ?? 0,
        ];
        $designation_id = $this->request->getPost("designation_id") ?? 0;
        if ($this->request->getPost("designation_name")) {
            $this->DesignationModel->insert_update_designation($designation_id, $post);
            return $this->response->redirect(url_to("erp.hr.designation"));
        }
        $this->data['departments'] = $this->DepartmentModel->get_all_departments();
        $this->data['designation_datatable_config'] = $this->DesignationModel->get_dtconfig_designation();
        $this->data['page'] = "hr/designation";
        $this->data['menu'] = "hr";
        $this->data['submenu'] = "designation";
        return view("erp/index", $this->data);
    }

    public function ajaxDesignationResponse()
    {
        $response = array();
        $response = $this->DesignationModel->get_designation_datatable();
        return $this->response->setJSON($response);
    }

    public function ajaxFetchDesignation($designation_id)
    {
        $response = array();
        $query = "SELECT designation_id,department_id,name,description FROM designation WHERE designation_id=$designation_id";
        $result = $this->db->query($query)->getRow();
        if (!empty($result)) {
            $response['error'] = 0;
            $response['data'] = $result;
        } else {
            $response['error'] = 1;
        }
        return $this->response->setJSON($response);
    }

    public function designationdelete($designation_id)
    {
        $this->DesignationModel->delete_designation($designation_id);
        return $this->response->redirect(url_to("erp.hr.designation"));
    }

    public function designationExport()
    {
        $type = $_GET['export'];
        $config = array();
        $config['title'] = "Designations";
        if ($type == "pdf") {
            $config['column'] = array("Name", "Department", "Description");
        } else {
            $config['column'] = array("Name", "Department", "Description");
            $config['column_data_type'] = array(ExporterConst::E_STRING, ExporterConst::E_STRING, ExporterConst::E_STRING);
        }
        $config['data'] = $this->DesignationModel->get_designation_export($type);
        if ($type == "pdf") {
            Exporter::export(ExporterConst::PDF, $config);
        } else if ($type == "excel") {
            Exporter::export(ExporterConst::EXCEL, $config);
        } else if ($type == "csv") {
            Exporter::export(ExporterConst::CSV, $config);
        }
    }

    public function employees()
    {
        $this->data['genders'] = $this->DepartmentModel->get_gender();
        $this->data['emp_status'] = $this->DepartmentModel->get_emp_status();
        $this->data['employee_datatable_config'] = $this->EmployeesModel->get_dtconfig_employees();
        $this->data['page'] = "hr/employees";
        $this->data['menu'] = "hr";
        $this->data['submenu'] = "employee";
        return view("erp/index", $this->data);
    }

    public function employeeadd()
    {
        $post = [

            'emp_code' => $this->request->getPost("emp_code"),
            'first_name' => $this->request->getPost("first_name"),
            'last_name' => $this->request->getPost("last_name"),
            'email' => $this->request->getPost("email"),
            'phone_no' => $this->request->getPost("phone_no"),
            'mobile_no' => $this->request->getPost("mobile_no"),
            'qualification' => $this->request->getPost("qualification"),
            'years_of_exp' => $this->request->getPost("years_of_exp"),
            'designation_id' => $this->request->getPost("designation_id"),
            'joining_date' => $this->request->getPost("joining_date"),
            'gender' => $this->request->getPost("gender"),
            'emp_status' => $this->request->getPost("emp_status"),
            'marital_status' => $this->request->getPost("marital_status"),
            'date_of_birth' => $this->request->getPost("date_of_birth"),
            'blood_group' => $this->request->getPost("blood_group"),
            'address' => $this->request->getPost("address"),
            'city' => $this->request->getPost("city"),
            'state' => $this->request->getPost("state"),
            'country' => $this->request->getPost("country"),
            'zipcode' => $this->request->getPost("zipcode"),
            'w_hr_salary' => $this->request->getPost("w_hr_salary"),
            'ot_hr_salary' => $this->request->getPost("ot_hr_salary"),
            'salary' => $this->request->getPost("salary"),
        ];
        if ($this->request->getPost("emp_code")) {
            if ($this->EmployeesModel->insert_employee($post)) {
                return $this->response->redirect(url_to("erp.hr.employeeadd"));
            } else {
                return $this->response->redirect(url_to("erp.ht.employees"));
            }
        }
        $this->data['genders'] = $this->DepartmentModel->get_gender();
        $this->data['emp_status'] = $this->DepartmentModel->get_emp_status();
        $this->data['marital_status'] = $this->DepartmentModel->get_marital_status();
        $this->data['designations'] = $this->DesignationModel->get_all_designations();
        $this->data['page'] = "hr/employeeadd";
        $this->data['menu'] = "hr";
        $this->data['submenu'] = "employee";
        return view("erp/index", $this->data);
    }

    public function ajaxEmpCodeUnique()
    {
        $emp_code = $_GET['data'];
        $id = $_GET['id'] ?? 0;
        $query = "SELECT emp_code FROM employees WHERE emp_code='$emp_code' ";
        if (!empty($id)) {
            $query = "SELECT emp_code FROM employees WHERE emp_code='$emp_code' AND employee_id<>$id";
        }
        $check = $this->db->query($query)->getRow();
        $response = array();
        if (empty($check)) {
            $response['valid'] = 1;
        } else {
            $response['valid'] = 0;
            $response['msg'] = "Employee Code already exists";
        }
        return $this->response->setJSON($response);
    }

    public function ajaxEmpEmailUnique()
    {
        $email = $_GET['data'];
        $id = $_GET['id'] ?? 0;
        $query = "SELECT email FROM employees WHERE email='$email' ";
        if (!empty($id)) {
            $query = "SELECT email FROM employees WHERE email='$email' AND employee_id<>$id";
        }
        $check = $this->db->query($query)->getRow();
        $response = array();
        if (empty($check)) {
            $response['valid'] = 1;
        } else {
            $response['valid'] = 0;
            $response['msg'] = "Email already exists";
        }
        return $this->response->setJSON($response);
    }

    public function ajaxEmpPhoneUnique()
    {
        $phone_no = $_GET['data'];
        $id = $_GET['id'] ?? 0;
        $query = "SELECT phone_no FROM employees WHERE phone_no='$phone_no' ";
        if (!empty($id)) {
            $query = "SELECT phone_no FROM employees WHERE phone_no='$phone_no' AND employee_id<>$id";
        }
        $check = $this->db->query($query)->getRow();
        $response = array();
        if (empty($check)) {
            $response['valid'] = 1;
        } else {
            $response['valid'] = 0;
            $response['msg'] = "Phone already exists";
        }
        return $this->response->setJSON($response);
    }

    public function ajaxEmployeesResponse()
    {
        $response = array();
        $response = $this->EmployeesModel->get_employee_datatable();
        return $this->response->setJSON($response);
    }

    public function employeeExport()
    {
        $type = $_GET['export'];
        $config = array();
        $config['title'] = "Employees";
        if ($type == "pdf") {
            $config['column'] = array("Code", "Name", "Designation", "Joining Date", "Email", "Gender", "Status");
        } else {
            $config['column'] = array("Code", "Name", "Email", "Phone", "Mobile", "Qualification", "Years of Experience", "Designation", "Joining Date", "Gender", "Status", "Marital Status", "Date of Birth", "Blood Group", "Address", "City", "State", "Country", "Zipcode", "Work Hour Salary", "OT Hour Salary", "Salary");
            $config['column_data_type'] = array(ExporterConst::E_STRING, ExporterConst::E_STRING, ExporterConst::E_STRING, ExporterConst::E_STRING, ExporterConst::E_STRING, ExporterConst::E_STRING, ExporterConst::E_STRING, ExporterConst::E_STRING, ExporterConst::E_STRING, ExporterConst::E_STRING, ExporterConst::E_STRING, ExporterConst::E_STRING, ExporterConst::E_STRING, ExporterConst::E_STRING, ExporterConst::E_STRING, ExporterConst::E_STRING, ExporterConst::E_STRING, ExporterConst::E_STRING, ExporterConst::E_STRING, ExporterConst::E_STRING, ExporterConst::E_STRING, ExporterConst::E_STRING);
        }
        $config['data'] = $this->EmployeesModel->get_employee_export($type);
        if ($type == "pdf") {
            Exporter::export(ExporterConst::PDF, $config);
        } else if ($type == "excel") {
            Exporter::export(ExporterConst::EXCEL, $config);
        } else if ($type == "csv") {
            Exporter::export(ExporterConst::CSV, $config);
        }
    }

    public function employeeedit($employee_id)
    {
        $post = [
            'emp_code' => $this->request->getPost("emp_code"),
            'first_name' => $this->request->getPost("first_name"),
            'last_name' => $this->request->getPost("last_name"),
            'email' => $this->request->getPost("email"),
            'phone_no' => $this->request->getPost("phone_no"),
            'mobile_no' => $this->request->getPost("mobile_no"),
            'qualification' => $this->request->getPost("qualification"),
            'years_of_exp' => $this->request->getPost("years_of_exp"),
            'designation_id' => $this->request->getPost("designation_id"),
            'joining_date' => $this->request->getPost("joining_date"),
            'gender' => $this->request->getPost("gender"),
            'emp_status' => $this->request->getPost("emp_status"),
            'marital_status' => $this->request->getPost("marital_status"),
            'date_of_birth' => $this->request->getPost("date_of_birth"),
            'blood_group' => $this->request->getPost("blood_group"),
            'address' => $this->request->getPost("address"),
            'city' => $this->request->getPost("city"),
            'state' => $this->request->getPost("state"),
            'country' => $this->request->getPost("country"),
            'zipcode' => $this->request->getPost("zipcode"),
            'w_hr_salary' => $this->request->getPost("w_hr_salary"),
            'ot_hr_salary' => $this->request->getPost("ot_hr_salary"),
            'salary' => $this->request->getPost("salary"),
        ];
        if ($this->request->getPost("emp_code")) {
            if ($this->EmployeesModel->update_employee($employee_id, $post)) {
                return $this->response->redirect(url_to("erp.hr.employees"));
            } else {
                return $this->response->redirect(url_to("erp.hr.employeeedit/" . $employee_id));
            }
        }
        $this->data['employee'] = $this->EmployeesModel->get_employee_by_id($employee_id);
        if (empty($this->data['employee'])) {
            $this->session->setFlashdata("op_error", "Employee Not Found");
            return $this->response->redirect(url_to("erp.hr.employees"));
        }
        $this->data['employee_id'] = $employee_id;
        $this->data['genders'] = $this->DepartmentModel->get_gender();
        $this->data['emp_status'] = $this->DepartmentModel->get_emp_status();
        $this->data['marital_status'] = $this->DepartmentModel->get_marital_status();
        $this->data['designations'] = $this->DesignationModel->get_all_designations();
        $this->data['page'] = "hr/employeeedit";
        $this->data['menu'] = "hr";
        $this->data['submenu'] = "employee";
        return view("erp/index", $this->data);
    }

    public function employeeview($employee_id)
    {
        $this->data['employee'] = $this->EmployeesModel->get_employee_for_view($employee_id);
        if (empty($this->data['employee'])) {
            $this->session->setFlashdata("op_error", "Employee Not Found");
            return $this->response->redirect(url_to("erp.hr.employees"));
        }
        $this->data['attachments'] = $this->AttachmentModel->get_attachments("employee", $employee_id);
        $this->data['employee_id'] = $employee_id;
        $this->data['page'] = "hr/employeeview";
        $this->data['menu'] = "hr";
        $this->data['submenu'] = "employee";
        return view("erp/index", $this->data);
    }

    public function uploadEmployeeAttachment()
    {
        $id = $this->request->getGet("id") ?? 0;
        $response = array();
        if (!empty($_FILES['attachment']['name'])) {
            $response = $this->AttachmentModel->upload_attachment("employee", $id);
        } else {
            $response['error'] = 1;
            $response['reason'] = "No file to upload";
        }
        return $this->response->setJSON($response);
    }

    public function employeeDeleteAttachment()
    {
        $attach_id = $this->request->getGet("id") ?? 0;
        $response = array();
        $response = $this->AttachmentModel->delete_attachment("employee", $attach_id);
        return $this->response->setJSON($response);
    }

    //NOT IMPLEMENTED
    public function employeedelete($employee_id)
    {
        $this->EmployeesModel->deleteEmployee($employee_id);
        return $this->response->redirect(url_to("erp.hr.employees"));
    }

    //Need to change for salary
    public function employeeImport()
    {
        $importer = Importer::init(AbstractImporter::EMPLOYEE, AbstractImporter::CSV);
        if (!empty($_FILES['csvfile']['name'])) {
            if (strtolower(pathinfo($_FILES['csvfile']['name'], PATHINFO_EXTENSION)) == "csv") {
                $path = get_tmp_path() . get_rand_str() . ".csv";
                if (move_uploaded_file($_FILES['csvfile']['tmp_name'], $path)) {

                    $data['designation_id'] = $this->request->getPost("designation_id");
                    $data['gender'] = $this->request->getPost("gender");
                    $data['status'] = $this->request->getPost("emp_status");
                    $data['marital_status'] = $this->request->getPost("marital_status");
                    $data['created_at'] = time();
                    $data['created_by'] = get_user_id();

                    $retval = $importer->import($path, $data);
                    $config['title'] = "Employee Import";
                    $config['ref_link'] = "";
                    if ($retval == -1) {
                        $config['log_text'] = "[ Employee failed to import ]";
                        $this->session->setFlashdata("op_error", "Employee failed to import");
                        log_activity($config);
                    } else if ($retval == 0) {
                        $config['log_text'] = "[ Employee partially imported ]";
                        $this->session->setFlashdata("op_success", "Employee partially imported");
                        log_activity($config);
                        return $this->response->redirect(url_to("erp.hr.employees"));
                    } else if ($retval == 1) {
                        $config['log_text'] = "[ Employee successfully imported ]";
                        $this->session->setFlashdata("op_success", "Employee successfully imported");
                        log_activity($config);
                        return $this->response->redirect(url_to("erp.hr.employees"));
                    }
                } else {
                    $this->session->setFlashdata("op_error", "Internal Error while uploading file");
                }
            } else {
                $this->session->setFlashdata("op_error", "File should be in CSV format");
            }
            return $this->response->redirect(url_to("erp.hr.employee_import"));
        }
        $this->data['genders'] = $this->DepartmentModel->get_gender();
        $this->data['emp_status'] = $this->DepartmentModel->get_emp_status();
        $this->data['marital_status'] = $this->DepartmentModel->get_marital_status();
        $this->data['designations'] = $this->DesignationModel->get_all_designations();
        $this->data['columns'] = $importer->get_columns();
        $this->data['page'] = "hr/employeeimport";
        $this->data['menu'] = "hr";
        $this->data['submenu'] = "employee";
        return view("erp/index", $this->data);
    }

    public function employeeimporttemplate()
    {
        $importer = Importer::init(AbstractImporter::EMPLOYEE, AbstractImporter::CSV);
        $importer->download_template();
    }

    public function attendance()
    {
        $attendance = new Attendance();
        $post = [
            'employee_id' => $this->request->getPost("employee_id"),
            'select_month' => $this->request->getPost("select_month"),
            'select_year' => $this->request->getPost("select_year"),
        ];
        if ($this->request->getPost("select_month")) {
            $attendance->exportMonthlyAttendance($post);
        } else {
            $this->data['attendance_datatable_config'] = $this->EmpAttendanceModel->get_dtconfig_attendance();
            $this->data['months'] = $this->EmpAttendanceModel->get_attendance_months();
            $this->data['years'] = $this->EmpAttendanceModel->get_attendance_years();
            $this->data['attend_types'] = $attendance->getTypes();
            $this->data['page'] = "hr/attendance";
            $this->data['menu'] = "hr";
            $this->data['submenu'] = "attendance";
            return view("erp/index", $this->data);
        }
    }

    public function ajaxAttendanceResponse()
    {
        $response = array();
        $response = $this->EmpAttendanceModel->get_attendance_datatable();
        return $this->response->setJSON($response);
    }

    public function ajaxFetchAttendance($attend_id)
    {
        $response = array();
        $query = "SELECT attend_id,status,work_hours,ot_hours FROM emp_attendance WHERE attend_id=$attend_id ";
        $result = $this->db->query($query)->getRow();
        if (!empty($result)) {
            $response['error'] = 0;
            $response['data'] = $result;
        } else {
            $response['error'] = 1;
        }
        return $this->response->setJSON($response);
    }

    public function test()
    {
        $attendance = new Attendance();
        $attendance->test();
    }

    public function attendanceadding()
    {
        $post =
            [
                'rec_date' => $this->request->getPost("rec_date"),
                'employee_id' => $this->request->getPost("employee_id"),
                'work_hours' => $this->request->getPost("work_hours"),
                'ot_hours' => $this->request->getPost("ot_hours"),
            ];
        $this->EmpAttendanceModel->add_attendance($post);
        $this->data['page'] = "hr/attendanceadd";
        $this->data['menu'] = "hr";
        $this->data['submenu'] = "attendance";


        $config['title']="HR Attendence";
        $config['ref_link']="";
        $config['log_text']="[ Attendence Added successfully ]";
        log_activity($config);

        return $this->response->redirect(url_to("erp.hr.attendance"));
        return view("erp/index", $this->data);
    }


    public function attendanceadd()
    {
        $rec_date = $this->request->getPost("rec_date");
        if ($this->request->getPost("rec_date")) {
            if (!empty($_FILES['excelfile']['name'])) {
                $ext = pathinfo($_FILES['excelfile']['name'], PATHINFO_EXTENSION);
                if ($ext == "xlsx") {
                    $path = get_tmp_path() . get_rand_str() . "." . $ext;
                    if (move_uploaded_file($_FILES['excelfile']['tmp_name'], $path)) {
                        $attendance = new Attendance();
                        $rows = $attendance->importNewAttendance($path, $rec_date);
                        $config['title'] = "Employee Attendance Insert";
                        $config['ref_link'] = url_to("erp.hr.attendance");
                        if (empty($rows)) {
                            $config['log_text'] = "Employee Attendance Insert; 0 rows inserted";
                            $this->session->setFlashdata("op_error", "Employee Attendance Insert; 0 rows inserted");
                        } else {
                            $config['log_text'] = "Employee Attendance Insert; " . $rows . " rows inserted";
                            $this->session->setFlashdata("op_success", "Employee Attendance Insert; " . $rows . " rows inserted");
                        }
                        log_activity($config);
                        return $this->response->redirect(url_to("erp.hr.attendance"));
                    } else {
                        $this->session->setFlashdata("op_error", "File upload error");
                    }
                } else {
                    $this->session->setFlashdata("op_error", "Upload the downloaded excel file");
                }
            }
            return $this->response->redirect(url_to("erp.hr.attendanceadd"));
        }
        $this->data['page'] = "hr/attendanceadd";
        $this->data['menu'] = "hr";
        $this->data['submenu'] = "attendance";
        return view("erp/index", $this->data);
    }

    public function attendanceemptytemplate()
    {
        $attendance = new Attendance();
        $attendance->getEmptyAttendanceForImport();
    }

    public function attendanceupdatetemplate()
    {
        $attendance = new Attendance();
        $date = $this->request->getGet("date");
        $attendance->getUpdateAttendanceForImport($date);
    }

    public function attendanceedit()
    {
        $post = [
            'attend_id' => $this->request->getPost("attend_id"),
            'status' => $this->request->getPost("status"),
            'work_hours' => $this->request->getPost("work_hours"),
            'ot_hours' => $this->request->getPost("ot_hours"),
        ];
        $this->EmpAttendanceModel->update_attendance($post);
        $config['title']="HR Attendence";
        $config['ref_link']="";
        $config['log_text']="[ Attendence Updated successfully ]";
        log_activity($config);
        return $this->response->redirect(url_to("erp.hr.attendance"));
    }

    public function ajaxfetchemployees()
    {
        $search = $_GET['search'] ?? "";
        $response = array();
        $response['error'] = 1;
        if (!empty($search)) {
            $query = "SELECT employee_id,CONCAT(first_name,' ',last_name) AS emp_name FROM employees WHERE first_name LIKE '%" . $search . "%' OR last_name LIKE '%" . $search . "%' LIMIT 10";
            $result = $this->db->query($query)->getResultArray();
            $m_result = array();
            foreach ($result as $r) {
                array_push($m_result, array(
                    "key" => $r['employee_id'],
                    "value" => $r['emp_name']
                ));
            }
            $response['error'] = 0;
            $response['data'] = $m_result;
        }
        return $this->response->setJSON($response);
    }

    public function attendanceExport()
    {
        $type = $_GET['export'];
        $config = array();
        $config['title'] = "Attendance Export";
        if ($type == "pdf") {
            $config['column'] = array("Date", "Code", "Name", "Work Hours", "OT Hours", "Status");
        } else {
            $config['column'] = array("Date", "Code", "Name", "Work Hours", "OT Hours", "Status");
            $config['column_data_type'] = array(ExporterConst::E_STRING, ExporterConst::E_STRING, ExporterConst::E_STRING, ExporterConst::E_STRING, ExporterConst::E_STRING, ExporterConst::E_STRING);
        }
        $config['data'] = $this->EmpAttendanceModel->get_attendance_export($type);
        if ($type == "pdf") {
            Exporter::export(ExporterConst::PDF, $config);
        } else if ($type == "excel") {
            Exporter::export(ExporterConst::EXCEL, $config);
        } else if ($type == "csv") {
            Exporter::export(ExporterConst::CSV, $config);
        }
    }

    public function contractors()
    {
        $this->data['contractor_datatable_config'] = $this->ContractorsModel->get_dtconfig_contractors();
        $this->data['page'] = "hr/contractors";
        $this->data['menu'] = "hr";
        $this->data['submenu'] = "contractor";
        return view("erp/index", $this->data);
    }

    public function ajaxContractorsResponse()
    {
        $response = array();
        $response = $this->ContractorsModel->get_contractor_datatable();
        return $this->response->setJSON($response);
    }

    public function contractoradd()
    {
        $post = [
            'con_code' => $this->request->getPost("con_code"),
            'name' => $this->request->getPost("name"),
            'contact_person' => $this->request->getPost("contact_person"),
            'email' => $this->request->getPost("email"),
            'phone_1' => $this->request->getPost("phone_1"),
            'phone_2' => $this->request->getPost("phone_2"),
            'gst_no' => $this->request->getPost("gst_no"),
            'pan_no' => $this->request->getPost("pan_no"),
            'website' => $this->request->getPost("website"),
            'description' => $this->request->getPost("description"),
            'address' => $this->request->getPost("address"),
            'city' => $this->request->getPost("city"),
            'state' => $this->request->getPost("state"),
            'country' => $this->request->getPost("country"),
            'zipcode' => $this->request->getPost("zipcode"),
        ];
        
        if ($this->request->getPost("con_code")) {
            if ($this->ContractorsModel->insert_contractor($post)) {
                return $this->response->redirect(url_to("erp.hr.contractors"));
            } else {
                return $this->response->redirect(url_to("erp.hr.contractoradd"));
            }
        }
        $this->data['page'] = "hr/contractoradd";
        $this->data['menu'] = "hr";
        $this->data['submenu'] = "contractor";
        return view("erp/index", $this->data);
    }

    public function ajaxContractorCodeUnique()
    {
        $con_code = $_GET['data'];
        $id = $_GET['id'] ?? 0;
        $query = "SELECT con_code FROM contractors WHERE con_code='$con_code' ";
        if (!empty($id)) {
            $query = "SELECT con_code FROM contractors WHERE con_code='$con_code' AND contractor_id<>$id";
        }
        $check = $this->db->query($query)->getRow();
        $response = array();
        if (empty($check)) {
            $response['valid'] = 1;
        } else {
            $response['valid'] = 0;
            $response['msg'] = "Contractor Code already exists";
        }
        return $this->response->setJSON($response);
    }

    public function ajaxContractorEmailUnique()
    {
        $email = $_GET['data'];
        $id = $_GET['id'] ?? 0;
        $query = "SELECT email FROM contractors WHERE email='$email' ";
        if (!empty($id)) {
            $query = "SELECT email FROM contractors WHERE email='$email' AND contractor_id<>$id";
        }
        $check = $this->db->query($query)->getRow();
        $response = array();
        if (empty($check)) {
            $response['valid'] = 1;
        } else {
            $response['valid'] = 0;
            $response['msg'] = "Email already exists";
        }
        return $this->response->setJSON($response);
    }

    public function ajaxContractorPhoneUnique()
    {
        $phone_1 = $_GET['data'];
        $id = $_GET['id'] ?? 0;
        $query = "SELECT phone_1 FROM contractors WHERE phone_1='$phone_1' ";
        if (!empty($id)) {
            $query = "SELECT phone_1 FROM contractors WHERE phone_1='$phone_1' AND contractor_id<>$id";
        }
        $check = $this->db->query($query)->getRow();
        $response = array();
        if (empty($check)) {
            $response['valid'] = 1;
        } else {
            $response['valid'] = 0;
            $response['msg'] = "Phone already exists";
        }
        return $this->response->setJSON($response);
    }

    public function ajaxContractorActive($contractor_id)
    {
        $response = array();
        $query = "UPDATE contractors SET active=active^1 WHERE contractor_id=$contractor_id";
        $this->db->query($query);
        if ($this->db->affectedRows() > 0) {
            $response['error'] = 0;
        } else {
            $response['error'] = 1;
        }
        return $this->response->setJSON($response);
    }

    public function contractorExport()
    {
        $type = $_GET['export'];
        $config = array();
        $config['title'] = "Contractors";
        if ($type == "pdf") {
            $config['column'] = array("Code", "Name", "Contact Person", "Email", "Phone", "GST No", "Active");
        } else {
            $config['column'] = array("Code", "Name", "Contact Person", "Email", "Phone 1", "Phone 2", "Active", "GST No", "PAN No", "Website", "Address", "City", "State", "Country", "Zipcode", "Description");
            $config['column_data_type'] = array(ExporterConst::E_STRING, ExporterConst::E_STRING, ExporterConst::E_STRING, ExporterConst::E_STRING, ExporterConst::E_STRING, ExporterConst::E_STRING, ExporterConst::E_STRING, ExporterConst::E_STRING, ExporterConst::E_STRING, ExporterConst::E_STRING, ExporterConst::E_STRING, ExporterConst::E_STRING, ExporterConst::E_STRING, ExporterConst::E_STRING, ExporterConst::E_STRING, ExporterConst::E_STRING);
        }
        $config['data'] = $this->ContractorsModel->get_contractor_export($type);
        if ($type == "pdf") {
            Exporter::export(ExporterConst::PDF, $config);
        } else if ($type == "excel") {
            Exporter::export(ExporterConst::EXCEL, $config);
        } else if ($type == "csv") {
            Exporter::export(ExporterConst::CSV, $config);
        }
    }

    public function contractorview($contractor_id)
    {
        $this->data['contractor'] = $this->ContractorsModel->get_contractor_by_id($contractor_id);
        if (empty($this->data['contractor'])) {
            $this->session->setFlashdata("op_error", "Contractor Not Found");
            return $this->response->redirect(url_to("erp.hr.contractors"));
        }
        $this->data['attachments'] = $this->AttachmentModel->get_attachments("contractor", $contractor_id);
        $this->data['contractor_id'] = $contractor_id;
        $this->data['page'] = "hr/contractorview";
        $this->data['menu'] = "hr";
        $this->data['submenu'] = "contractor";
        return view("erp/index", $this->data);
    }

    public function uploadContractorAttachment()
    {
        $id = $this->request->getGet("id") ?? 0;
        $response = array();
        if (!empty($_FILES['attachment']['name'])) {
            $response = $this->AttachmentModel->upload_attachment("contractor", $id);
        } else {
            $response['error'] = 1;
            $response['reason'] = "No file to upload";
        }
        return $this->response->setJSON($response);
    }

    public function contractorDeleteAttachment()
    {
        $attach_id = $this->request->getGet("id") ?? 0;
        $response = array();
        $response = $this->AttachmentModel->delete_attachment("contractor", $attach_id);
        return $this->response->setJSON($response);
    }

    public function contractoredit($contractor_id)
    {
        $post = [
            'con_code' => $this->request->getPost("con_code"),
            'name' => $this->request->getPost("name"),
            'contact_person' => $this->request->getPost("contact_person"),
            'email' => $this->request->getPost("email"),
            'phone_1' => $this->request->getPost("phone_1"),
            'phone_2' => $this->request->getPost("phone_2"),
            'gst_no' => $this->request->getPost("gst_no"),
            'pan_no' => $this->request->getPost("pan_no"),
            'website' => $this->request->getPost("website"),
            'description' => $this->request->getPost("description"),
            'address' => $this->request->getPost("address"),
            'city' => $this->request->getPost("city"),
            'state' => $this->request->getPost("state"),
            'country' => $this->request->getPost("country"),
            'zipcode' => $this->request->getPost("zipcode"),
        ];
        if ($this->request->getPost("con_code")) {
            if ($this->ContractorsModel->update_contractor($contractor_id, $post)) {
                return $this->response->redirect(url_to("erp.hr.contractors"));
            } else {
                return $this->response->redirect(url_to("erp.hr.contractoredit", $contractor_id));
            }
        }
        $this->data['contractor'] = $this->ContractorsModel->get_contractor_by_id($contractor_id);
        if (empty($this->data['contractor'])) {
            $this->session->setFlashdata("op_error", "Contractor Not Found");
            return $this->response->redirect(url_to("erp.hr.contractors"));
        }
        $this->data['contractor_id'] = $contractor_id;
        $this->data['page'] = "hr/contractoredit";
        $this->data['menu'] = "hr";
        $this->data['submenu'] = "contractor";
        return view("erp/index", $this->data);
    }

    //NOT IMPLEMENTED
    public function contractordelete($contractor_id)
    {
        $this->ContractorsModel->deleteContractor($contractor_id);
        return $this->response->redirect(url_to("erp.hr.contractors"));
    }

    public function contractorImport()
    {
        $importer = Importer::init(AbstractImporter::CONTRACTOR, AbstractImporter::CSV);
        if (!empty($_FILES['csvfile']['name'])) {
            if (strtolower(pathinfo($_FILES['csvfile']['name'], PATHINFO_EXTENSION)) == "csv") {
                $path = get_tmp_path() . get_rand_str() . ".csv";
                if (move_uploaded_file($_FILES['csvfile']['tmp_name'], $path)) {

                    $data['created_at'] = time();
                    $data['created_by'] = get_user_id();

                    $retval = $importer->import($path, $data);
                    $config['title'] = "Contractor Import";
                    $config['ref_link'] = "";
                    if ($retval == -1) {
                        $config['log_text'] = "[ Contractor failed to import ]";
                        $this->session->setFlashdata("op_error", "Contractor failed to import");
                        log_activity($config);
                    } else if ($retval == 0) {
                        $config['log_text'] = "[ Contractor partially imported ]";
                        $this->session->setFlashdata("op_success", "Contractor partially imported");
                        log_activity($config);
                        return $this->response->redirect(url_to("erp.hr.contractors"));
                    } else if ($retval == 1) {
                        $config['log_text'] = "[ Contractor successfully imported ]";
                        $this->session->setFlashdata("op_success", "Contractor successfully imported");
                        log_activity($config);
                        return $this->response->redirect(url_to("erp.hr.contractors"));
                    }
                } else {
                    $this->session->setFlashdata("op_error", "Internal Error while uploading file");
                }
            } else {
                $this->session->setFlashdata("op_error", "File should be in CSV format");
            }
            return $this->response->redirect(url_to("erp.hr.contractor_import"));
        }
        $this->data['columns'] = $importer->get_columns();
        $this->data['page'] = "hr/contractorimport";
        $this->data['menu'] = "hr";
        $this->data['submenu'] = "contractor";
        return view("erp/index", $this->data);
    }

    public function contractorimporttemplate()
    {
        $importer = Importer::init(AbstractImporter::CONTRACTOR, AbstractImporter::CSV);
        $importer->download_template();
    }

    public function deductions()
    {
        $this->data['deduction_datatable_config'] = $this->DeductionsModel->get_dtconfig_deductions();
        $this->data['amt_type'] = $this->DepartmentModel->get_amt_type();
        $this->data['page'] = "hr/deductions";
        $this->data['menu'] = "hr";
        $this->data['submenu'] = "deduction";
        return view("erp/index", $this->data);
    }

    public function ajaxDeductionsResponse()
    {
        $response = array();
        $response = $this->DeductionsModel->get_deduction_datatable();
        return $this->response->setJSON($response);
    }

    public function ajaxFetchDeduction($deduct_id)
    {
        $response = array();
        $query = "SELECT deduct_id,name,description,type,value FROM deductions WHERE deduct_id=$deduct_id";
        $result = $this->db->query($query)->getRow();
        if (!empty($result)) {
            $response['error'] = 0;
            $response['data'] = $result;
        } else {
            $response['error'] = 1;
        }
        return $this->response->setJSON($response);
    }

    public function deductionaddedit()
    {
        $post = [
            'name' => $this->request->getPost("name"),
            'description' => $this->request->getPost("description"),
            'type' => $this->request->getPost("type"),
            'value' => $this->request->getPost("value"),
        ];
        $deduct_id = $this->request->getPost("deduct_id");
        if ($this->request->getPost("name")) {
            $this->DeductionsModel->insert_update_deduction($deduct_id, $post);
        }
        return $this->response->redirect(url_to("erp.hr.deductions"));
    }

    public function deductionExport()
    {
        $type = $_GET['export'];
        $config = array();
        $config['title'] = "Deductions";
        if ($type == "pdf") {
            $config['column'] = array("Name", "Description", "Type", "Value");
        } else {
            $config['column'] = array("Name", "Description", "Type", "Value");
            $config['column_data_type'] = array(ExporterConst::E_STRING, ExporterConst::E_STRING, ExporterConst::E_STRING, ExporterConst::E_STRING);
        }
        $config['data'] = $this->DeductionsModel->get_deduction_export($type);
        if ($type == "pdf") {
            Exporter::export(ExporterConst::PDF, $config);
        } else if ($type == "excel") {
            Exporter::export(ExporterConst::EXCEL, $config);
        } else if ($type == "csv") {
            Exporter::export(ExporterConst::CSV, $config);
        }
    }

    //NOT IMPLEMENTED
    public function deductiondelete($deduct_id)
    {
        $this->DeductionsModel->delete_deductions($deduct_id);
        return $this->response->redirect(url_to("erp.hr.deductions"));
    }

    public function additions()
    {
        $this->data['addition_datatable_config'] = $this->AdditionsModel->get_dtconfig_additions();
        $this->data['amt_type'] = $this->DepartmentModel->get_amt_type();
        $this->data['page'] = "hr/additions";
        $this->data['menu'] = "hr";
        $this->data['submenu'] = "addition";
        return view("erp/index", $this->data);
    }

    public function ajaxAdditionsResponse()
    {
        $response = array();
        $response = $this->AdditionsModel->get_addition_datatable();
        return $this->response->setJSON($response);
    }

    public function ajaxFetchAddition($add_id)
    {
        $response = array();
        $query = "SELECT add_id,name,description,type,value FROM additions WHERE add_id=$add_id";
        $result = $this->db->query($query)->getRow();
        if (!empty($result)) {
            $response['error'] = 0;
            $response['data'] = $result;
        } else {
            $response['error'] = 1;
        }
        return $this->response->setJSON($response);
    }

    public function additionaddedit()
    {
        $post = [
            'name' => $this->request->getPost("name"),
            'description' => $this->request->getPost("description"),
            'type' => $this->request->getPost("type"),
            'value' => $this->request->getPost("value"),
        ];
        $add_id = $this->request->getPost("add_id");
        if ($this->request->getPost("name")) {
            $this->AdditionsModel->insert_update_addition($add_id, $post);
        }
        return $this->response->redirect(url_to("erp.hr.additions"));
    }

    public function additionExport()
    {
        $type = $_GET['export'];
        $config = array();
        $config['title'] = "Additions";
        if ($type == "pdf") {
            $config['column'] = array("Name", "Description", "Type", "Value");
        } else {
            $config['column'] = array("Name", "Description", "Type", "Value");
            $config['column_data_type'] = array(ExporterConst::E_STRING, ExporterConst::E_STRING, ExporterConst::E_STRING, ExporterConst::E_STRING);
        }
        $config['data'] = $this->AdditionsModel->get_addition_export($type);
        if ($type == "pdf") {
            Exporter::export(ExporterConst::PDF, $config);
        } else if ($type == "excel") {
            Exporter::export(ExporterConst::EXCEL, $config);
        } else if ($type == "csv") {
            Exporter::export(ExporterConst::CSV, $config);
        }
    }

    //NOT IMPLEMENTED
    public function additiondelete($add_id)
    {
        $this->AdditionsModel->delete_addition($add_id);
        return $this->response->redirect(url_to("erp.hr.additions"));
    }

    public function payrolls()
    {
        $this->data['payroll_datatable_config'] = $this->PayrollEntryModel->get_dtconfig_payrolls();
        $this->data['page'] = "hr/payrolls";
        $this->data['menu'] = "hr";
        $this->data['submenu'] = "payroll";
        return view("erp/index", $this->data);
    }

    public function ajaxPayrollsResponse()
    {
        $response = array();
        $response = $this->PayrollEntryModel->get_payroll_datatable();
        return $this->response->setJSON($response);
    }

    public function payrolladd()
    {
        $post = [
            'name' => $this->request->getPost("name"),
            'payment_date' => $this->request->getPost("payment_date"),
            'payment_from' => $this->request->getPost("payment_from"),
            'payment_to' => $this->request->getPost("payment_to"),
        ];
        $post1 = [
            'additions' => $this->request->getPost("additions"),
            'deductions' => $this->request->getPost("deductions"),
        ];
        if ($this->request->getPost("name")) {
            if ($this->PayrollEntryModel->insert_payroll($post, $post1)) {
                return $this->response->redirect(url_to("erp.hr.payrolls"));
            } else {
                return $this->response->redirect(url_to("erp.hr.payrolladd"));
            }
        }
        $this->data['additions'] = $this->AdditionsModel->get_all_additions();
        $this->data['deductions'] = $this->DeductionsModel->get_all_deductions();
        $this->data['page'] = "hr/payrolladd";
        $this->data['menu'] = "hr";
        $this->data['submenu'] = "payroll";
        return view("erp/index", $this->data);
    }

    public function payrolledit($pay_entry_id)
    {
        $post = [
            'name' => $this->request->getPost("name"),
            'payment_date' => $this->request->getPost("payment_date"),
            'payment_from' => $this->request->getPost("payment_from"),
            'payment_to' => $this->request->getPost("payment_to"),
        ];
        $post1 = [
            'additions' => $this->request->getPost("additions"),
            'deductions' => $this->request->getPost("deductions"),
        ];
        if ($this->request->getPost("name")) {
            if ($this->PayrollEntryModel->update_payroll($pay_entry_id, $post, $post1)) {
                return $this->response->redirect(url_to("erp.hr.payrolls"));
            } else {
                return $this->response->redirect(url_to("erp.hr.payrolledit", $pay_entry_id));
            }
        }
        $this->data['payroll'] = $this->PayrollEntryModel->get_payroll_entry_by_id($pay_entry_id);
        if (empty($this->data['payroll'])) {
            $this->session->setFlashdata("op_error", "Payroll Not Found");
            return $this->response->redirect(url_to("erp.hr.payrolls"));
        }
        $this->data['pay_entry_id'] = $pay_entry_id;
        $this->data['additions'] = $this->AdditionsModel->get_all_additions();
        $this->data['deductions'] = $this->DeductionsModel->get_all_deductions();
        $this->data['page'] = "hr/payrolledit";
        $this->data['menu'] = "hr";
        $this->data['submenu'] = "payroll";
        return view("erp/index", $this->data);
    }

    public function payrollview($pay_entry_id)
    {
        $this->data['payroll'] = $this->PayrollEntryModel->get_payroll_entry_by_id($pay_entry_id);
        if (empty($this->data['payroll'])) {
            $this->session->setFlashdata("op_error", "Payroll Not Found");
            return $this->response->redirect(url_to("erp.hr.payrolls"));
        }
        $this->data['pay_entry_id'] = $pay_entry_id;
        $this->data['page'] = "hr/payrollview";
        $this->data['menu'] = "hr";
        $this->data['submenu'] = "payroll";
        return view("erp/index", $this->data);
    }

    public function payrollprocess($pay_entry_id)
    {
        $this->PayrollEntryModel->process_payroll($pay_entry_id);
        return $this->response->redirect(url_to("erp.hr.payrolls"));
    }

    //NOT IMPLEMENTED
    public function payrolldelete($pay_entry_id)
    {
        $this->PayrollEntryModel->delete_payroll($pay_entry_id);
        return $this->response->redirect(url_to("erp.hr.payrolls"));
    }

    public function payrollentryExport()
    {
        $type = $_GET['export'];
        $config = array();
        $config['title'] = "Payroll Entry";
        if ($type == "pdf") {
            $config['column'] = array("Name", "Payment Date", "Payment from", "Payment to", "Processed");
        } else {
            $config['column'] = array("Name", "Payment Date", "Payment from", "Payment to", "Processed");
            $config['column_data_type'] = array(ExporterConst::E_STRING, ExporterConst::E_STRING, ExporterConst::E_STRING, ExporterConst::E_STRING, ExporterConst::E_STRING);
        }
        $config['data'] = $this->PayrollEntryModel->get_payrollentry_export($type);
        if ($type == "pdf") {
            Exporter::export(ExporterConst::PDF, $config);
        } else if ($type == "excel") {
            Exporter::export(ExporterConst::EXCEL, $config);
        } else if ($type == "csv") {
            Exporter::export(ExporterConst::CSV, $config);
        }
    }
}
