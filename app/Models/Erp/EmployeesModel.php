<?php

namespace App\Models\Erp;

use CodeIgniter\Model;
use Exception;

class EmployeesModel extends Model
{
    protected $table = 'employees';
    protected $primaryKey = 'employee_id';

    protected $allowedFields = [
        'emp_code',
        'first_name',
        'last_name',
        'designation_id',
        'gender',
        'joining_date',
        'phone_no',
        'mobile_no',
        'email',
        'date_of_birth',
        'marital_status',
        'blood_group',
        'address',
        'city',
        'state',
        'country',
        'zipcode',
        'qualification',
        'years_of_exp',
        'status',
        'w_hr_salary',
        'ot_hr_salary',
        'salary',
        'created_at',
        'created_by'
    ];
    private $marital_status = array(
        0 => "Not married",
        1 => "Married",
        2 => "Divorced"
    );
    public function get_marital_status()
    {
        return $this->marital_status;
    }
    protected $db;
    protected $session;
    protected $attachmentModel;

    public function __construct()
    {
        $this->attachmentModel = new AttachmentModel();
        $this->db = \Config\Database::connect();
        $this->session = \Config\Services::session();
    }
    private $gender = array(
        0 => "Male",
        1 => "Female"
    );
    public function get_gender()
    {
        return $this->gender;
    }
    private $emp_status = array(
        0 => "Working",
        1 => "Resigned"
    );
    public function get_emp_status()
    {
        return $this->emp_status;
    }
    public function get_dtconfig_employees()
    {
        $config = array(
            "columnNames" => ["sno", "code", "name", "designation", "joining date", "email", "gender", "status", "action"],
            "sortable" => [0, 1, 1, 0, 1, 0, 0, 0, 0],
            "filters" => ["gender", "status"]
        );
        return json_encode($config);
    }

    public function get_employee_datatable()
    {
        $columns = array(
            "name" => "first_name",
            "code" => "emp_code",
            "joining date" => "joining_date",
            "gender" => "gender",
            "status" => "status"
        );
        $limit = $_GET['limit'];
        $offset = $_GET['offset'];
        $search = $_GET['search'] ?? "";
        $orderby = isset($_GET['orderby']) ? strtoupper($_GET['orderby']) : "";
        $ordercol = isset($_GET['ordercol']) ? $columns[$_GET['ordercol']] : "";

        $query = "SELECT employee_id,emp_code,CONCAT(first_name,' ',last_name) AS emp_name,designation.name AS desig,joining_date,email,gender,status FROM employees JOIN designation ON employees.designation_id=designation.designation_id ";
        $count = "SELECT COUNT(employee_id) AS total FROM employees JOIN designation ON employees.designation_id=designation.designation_id ";
        $where = false;
        $filter_1_col = isset($_GET['gender']) ? $columns['gender'] : "";
        $filter_1_val = $_GET['gender'];

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

        if (!empty($search)) {
            if (!$where) {
                $query .= " WHERE ( emp_code LIKE '%" . $search . "%' OR first_name LIKE '%" . $search . "%' OR designation.name LIKE '%" . $search . "%' ) ";
                $count .= " WHERE ( emp_code LIKE '%" . $search . "%' OR first_name LIKE '%" . $search . "%' OR designation.name LIKE '%" . $search . "%' ) ";
                $where = true;
            } else {
                $query .= " AND ( emp_code LIKE '%" . $search . "%' OR first_name LIKE '%" . $search . "%' OR designation.name LIKE '%" . $search . "%' ) ";
                $count .= " AND ( emp_code LIKE '%" . $search . "%' OR first_name LIKE '%" . $search . "%' OR designation.name LIKE '%" . $search . "%' ) ";
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
                                <li><a target="_blank" href="' . url_to('erp.hr.employeeview', $r['employee_id']) . '" title="View" class="bg-warning modalBtn"><i class="fa fa-eye"></i> </a></li>
                                <li><a href="' . url_to('erp.hr.employeeedit', $r['employee_id']) . '" title="Edit" class="bg-success modalBtn"><i class="fa fa-pencil"></i> </a></li>
                                <li><a href="' . url_to('erp.hr.employeedelete', $r['employee_id']) . '" title="Delete" class="bg-danger del-confirm"><i class="fa fa-trash"></i> </a></li>
                            </ul>
                        </div>
                    </div>';
            array_push(
                $m_result,
                array(
                    $r['employee_id'],
                    $sno,
                    $r['emp_code'],
                    $r['emp_name'],
                    $r['desig'],
                    $r['joining_date'],
                    $r['email'],
                    $this->gender[$r['gender']],
                    $this->emp_status[$r['status']],
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

    public function get_employee_export($type)
    {
        $columns = array(
            "name" => "first_name",
            "code" => "emp_code",
            "joining date" => "joining_date",
            "gender" => "gender",
            "status" => "status"
        );
        $limit = $_GET['limit'];
        $offset = $_GET['offset'];
        $search = $_GET['search'] ?? "";
        $orderby = isset($_GET['orderby']) ? strtoupper($_GET['orderby']) : "";
        $ordercol = isset($_GET['ordercol']) ? $columns[$_GET['ordercol']] : "";
        $query = "";
        if ($type == "pdf") {
            $query = "SELECT emp_code,CONCAT(first_name,' ',last_name) AS emp_name,designation.name AS desig,joining_date,email,CASE WHEN gender=0 THEN 'Male' ELSE 'Female' END AS gender,CASE WHEN status=0 THEN 'Working' ELSE 'Resigned' END AS status FROM employees JOIN designation ON employees.designation_id=designation.designation_id ";
        } else {
            $query = "SELECT emp_code,CONCAT(first_name,' ',last_name) AS emp_name,email,phone_no,mobile_no,qualification,years_of_exp,designation.name AS desig,joining_date,CASE WHEN gender=0 THEN 'Male' ELSE 'Female' END AS gender,CASE WHEN status=0 THEN 'Working' ELSE 'Resigned' END AS status,
            CASE ";
            foreach ($this->marital_status as $key => $value) {
                $query .= " WHEN marital_status=$key THEN '" . $value . "' ";
            }
            $query .= "END AS marital_status,date_of_birth,blood_group,address,city,state,country,zipcode,w_hr_salary,ot_hr_salary,salary FROM employees JOIN designation ON employees.designation_id=designation.designation_id ";
        }

        $where = false;
        $filter_1_col = isset($_GET['gender']) ? $columns['gender'] : "";
        $filter_1_val = $_GET['gender'];

        $filter_2_col = isset($_GET['status']) ? $columns['status'] : "";
        $filter_2_val = $_GET['status'];

        if (!empty($filter_1_col) && $filter_1_val !== "") {
            $query .= " WHERE " . $filter_1_col . "=" . $filter_1_val . " ";
            $where = true;
        }

        if (!empty($filter_2_col) && $filter_2_val !== "") {
            if (!$where) {
                $query .= " WHERE " . $filter_2_col . "=" . $filter_2_val . " ";
                $where = true;
            } else {
                $query .= " AND " . $filter_2_col . "=" . $filter_2_val . " ";
            }
        }

        if (!empty($search)) {
            if (!$where) {
                $query .= " WHERE ( emp_code LIKE '%" . $search . "%' OR first_name LIKE '%" . $search . "%' OR designation.name LIKE '%" . $search . "%' ) ";
                $where = true;
            } else {
                $query .= " AND ( emp_code LIKE '%" . $search . "%' OR first_name LIKE '%" . $search . "%' OR designation.name LIKE '%" . $search . "%' ) ";
            }
        }

        if (!empty($ordercol) && !empty($orderby)) {
            $query .= " ORDER BY " . $ordercol . " " . $orderby;
        }
        $result = $this->db->query($query)->getResultArray();
        return $result;
    }

    public function get_employee_by_id($employee_id)
    {
        $query = "SELECT * FROM employees WHERE employee_id=$employee_id ";
        $result = $this->db->query($query)->getRow();
        return $result;
    }

    public function get_employee_for_view($employee_id)
    {
        $query = "SELECT employee_id,emp_code,CONCAT(first_name,' ',last_name) AS emp_name,email,phone_no,mobile_no,qualification,years_of_exp,designation.name AS desig,joining_date,CASE WHEN gender=0 THEN 'Male' ELSE 'Female' END AS gender,CASE WHEN status=0 THEN 'Working' ELSE 'Resigned' END AS status,
        CASE ";
        foreach ($this->marital_status as $key => $value) {
            $query .= " WHEN marital_status=$key THEN '" . $value . "' ";
        }
        $query .= "END AS marital_status,date_of_birth,blood_group,address,city,state,country,zipcode,w_hr_salary,ot_hr_salary,salary FROM employees JOIN designation ON employees.designation_id=designation.designation_id ";
        $result = $this->db->query($query)->getRow();
        return $result;
    }

    public function insert_employee($post)
    {
        $inserted = false;
        $code = $post["emp_code"];
        $first_name = $post["first_name"];
        $last_name = $post["last_name"];
        $email = $post["email"];
        $phone_no = $post["phone_no"];
        $mobile_no = $post["mobile_no"];
        $qualification = $post["qualification"];
        $years_of_exp = $post["years_of_exp"];
        $designation_id = $post["designation_id"];
        $joining_date = $post["joining_date"];
        $gender = $post["gender"];
        $emp_status = $post["emp_status"];
        $marital_status = $post["marital_status"];
        $date_of_birth = $post["date_of_birth"];
        $blood_group = $post["blood_group"];
        $address = $post["address"];
        $city = $post["city"];
        $state = $post["state"];
        $country = $post["country"];
        $zipcode = $post["zipcode"];
        $w_hr_salary = $post["w_hr_salary"];
        $ot_hr_salary = $post["ot_hr_salary"];
        $salary = $post["salary"];
        $created_by = get_user_id();

        $query = "INSERT INTO employees(emp_code,first_name,last_name,email,phone_no,mobile_no,qualification,years_of_exp,designation_id,joining_date,gender,status,marital_status,date_of_birth,blood_group,address,city,state,country,zipcode,created_at,created_by,w_hr_salary,ot_hr_salary,salary) VALUES(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?) ";
        $this->db->query($query, array($code, $first_name, $last_name, $email, $phone_no, $mobile_no, $qualification, $years_of_exp, $designation_id, $joining_date, $gender, $emp_status, $marital_status, $date_of_birth, $blood_group, $address, $city, $state, $country, $zipcode, time(), $created_by, $w_hr_salary, $ot_hr_salary, $salary));

        $config['title'] = "Employee Insert";
        if (!empty($this->db->insertId())) {
            $inserted = true;
            $config['log_text'] = "[ Employee successfully created ]";
            $config['ref_link'] = 'erp/hr/employees';
            $this->session->setFlashdata("op_success", "Employee successfully created");
        } else {
            $config['log_text'] = "[ Employee failed to create ]";
            $config['ref_link'] = 'erp/hr/employees';
            $this->session->setFlashdata("op_error", "Employee failed to create");
        }
        log_activity($config);
        return $inserted;
    }

    public function update_employee($employee_id, $post)
    {
        $updated = false;
        $code = $post["emp_code"];
        $first_name = $post["first_name"];
        $last_name = $post["last_name"];
        $email = $post["email"];
        $phone_no = $post["phone_no"];
        $mobile_no = $post["mobile_no"];
        $qualification = $post["qualification"];
        $years_of_exp = $post["years_of_exp"];
        $designation_id = $post["designation_id"];
        $joining_date = $post["joining_date"];
        $gender = $post["gender"];
        $emp_status = $post["emp_status"];
        $marital_status = $post["marital_status"];
        $date_of_birth = $post["date_of_birth"];
        $blood_group = $post["blood_group"];
        $address = $post["address"];
        $city = $post["city"];
        $state = $post["state"];
        $country = $post["country"];
        $zipcode = $post["zipcode"];
        $w_hr_salary = $post["w_hr_salary"];
        $ot_hr_salary = $post["ot_hr_salary"];
        $salary = $post["salary"];

        $query = "UPDATE employees SET emp_code=? , first_name=? , last_name=? , email=? , phone_no=? , mobile_no=? , qualification=? , years_of_exp=? , designation_id=? , joining_date=? , gender=? , status=? , marital_status=? , date_of_birth=? , blood_group=? , address=? , city=? , state=? , country=? , zipcode=? , w_hr_salary= ? , ot_hr_salary=? , salary=? WHERE employee_id=$employee_id ";
        $this->db->query($query, array($code, $first_name, $last_name, $email, $phone_no, $mobile_no, $qualification, $years_of_exp, $designation_id, $joining_date, $gender, $emp_status, $marital_status, $date_of_birth, $blood_group, $address, $city, $state, $country, $zipcode, $w_hr_salary, $ot_hr_salary, $salary));

        $config['title'] = "Employee Update";
        if ($this->db->affectedRows() > 0) {
            $updated = true;
            $config['log_text'] = "[ Employee successfully updated ]";
            $config['ref_link'] = 'erp/hr/employees/';
            $this->session->setFlashdata("op_success", "Employee successfully updated");
        } else {
            $config['log_text'] = "[ Employee failed to update ]";
            $config['ref_link'] = 'erp/hr/employees/';
            $this->session->setFlashdata("op_error", "Employee failed to update");
        }
        log_activity($config);
        return $updated;
    }

    // public function delete_employees($employee_id)
    // {
    //     $query = "DELETE FROM employees WHERE employee_id=$employee_id";
    //     $this->db->query($query);
    //     $config['title'] = "Employee Delete";
    //     if ($this->db->affectedRows() > 0) {
    //         $config['log_text'] = "[ Employee successfully deleted ]";
    //         $config['ref_link'] = 'erp/hr/employees/';
    //         $this->session->setFlashdata("op_success", "Employee successfully deleted");
    //     } else {
    //         $config['log_text'] = "[ Employee failed to delete ]";
    //         $config['ref_link'] = 'erp/hr/employees/';
    //         $this->session->setFlashdata("op_error", "Employee failed to delete");
    //     }
    //     log_activity($config);
    // }

    public function deleteEmployee($employee_id)
    {
        try {
            $this->db->transStart();

            $this->db->table('employees')
                ->where('employee_id', $employee_id)
                ->delete();

            $attach_ids = $this->db->table('attachments')
                ->select('attach_id')
                ->where('related_id', $employee_id)
                ->where('related_to', 'employee')
                ->get()
                ->getResultArray();

            if (!empty($attach_ids)) {
                foreach ($attach_ids as $attach) {
                    $this->attachmentModel->delete_attachment("employee", $attach['attach_id']);
                }
            }

            $this->db->transComplete();

            $config['title'] = 'Employee Delete';
            $config['log_text'] = "[ Employee and related data deleted successfully ]";
            $config['ref_link'] = 'erp/hr/employees/';

            if ($this->db->transStatus() === false) {
                throw new Exception("Transaction failed");
            }

            log_activity($config);

            return $this->session->setFlashdata("op_success", "Employee and related data deleted successfully");
        } catch (Exception $e) {
            $config['title'] = 'Employee Delete';
            $config['log_text'] = "[ Error deleting Employee and related data ]";
            $config['ref_link'] = 'erp/hr/employees/';
            log_activity($config);
            log_message('error', 'Error deleting Employee and related data: ' . $e->getMessage());
            return $this->session->setFlashdata("op_error", "Error deleting Employee and related data");
        }
    }
}
