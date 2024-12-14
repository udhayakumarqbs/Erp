<?php 

namespace App\Models\Erp;


use CodeIgniter\Model;

class Employee2Model extends Model{

    protected $table            = 'employee';
    protected $primaryKey       = 'emp_id ';
    protected $allowedFields = [
        'emp_code',
        'first_name',
        'last_name',
        'dob',
        'mail',
        'office_mail',
        'phone',
        'address',
        'gender',
        'dept_id',
        'pos_id',
        'work_type_id',
        'hire_date',
        'original_hire_date',
        'current_ctc',
        'marital_status',
        'emergency_contact',
        'blood_group',
        'emp_image',
        'claim_id',
        'status',
    ];
    protected $DBGroup = 'human_resource';
    protected $db;

    public function get_employee(){
        $result = $this->builder()->select("emp_id ,CONCAT(first_name,last_name) as name")->get()->getResultArray();

        return $result ;
    }

    public function get_employees_details_id($id){
        
        $query ="SELECT a.phone,a.mail,a.first_name,a.last_name,b.pos_name FROM employee as a JOIN job_position as b ON a.pos_id = b.pos_id WHERE a.emp_id= $id";
        $result = $this->db->query($query)->getRow();
        return $result;
    }



}


?>