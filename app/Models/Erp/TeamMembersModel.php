<?php

namespace App\Models\Erp;

use CodeIgniter\Model;

class TeamMembersModel extends Model
{
    protected $table      = 'team_members';
    protected $primaryKey = 'member_id';

    protected $allowedFields = [
        'team_id',
        'employee_id'
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = '';

    protected $session;
    protected $db;
    public function __construct()
    {
        $this->session = \Config\Services::session();
        $this->db = \Config\Database::connect();
    }

    public function get_dtconfig_teammembers()
    {
        $config = array(
            "columnNames" => ["sno", "employee code", "name", "designation", "email", "action"],
            "sortable" => [0, 1, 1, 0, 0, 0],
        );
        return json_encode($config);
    }

    public function get_teammember_datatable($team_id)
    {
        // $team_id = $_GET["teamid"];
        $columns = array(
            "employee code" => "emp_code",
            "name" => "first_name"
        );
        $offset = isset($_GET['offset']) ? $_GET['offset'] : 0;
        $limit = isset($_GET['limit']) ? $_GET['limit'] : 10;
        $search = isset($_GET['search']) ?? "";
        $orderby = isset($_GET['orderby']) ? strtoupper($_GET['orderby']) : "";
        $ordercol = isset($_GET['ordercol']) ? $columns[$_GET['ordercol']] : "";

        $query = "SELECT member_id,emp_code,CONCAT(first_name,' ',last_name) AS name,designation.name AS desig,email FROM team_members JOIN employees ON team_members.employee_id=employees.employee_id JOIN designation ON employees.designation_id=designation.designation_id WHERE team_id=$team_id ";
        $count = "SELECT COUNT(member_id) AS total FROM team_members JOIN employees ON team_members.employee_id=employees.employee_id JOIN designation ON employees.designation_id=designation.designation_id WHERE team_id=$team_id ";
        $where = true;

        if (!empty($search)) {
            if (!$where) {
                $query .= " WHERE first_name LIKE '%" . $search . "%' ";
                $count .= " WHERE first_name LIKE '%" . $search . "%' ";
                $where = true;
            } else {
                $query .= " AND first_name LIKE '%" . $search . "%' ";
                $count .= " AND first_name LIKE '%" . $search . "%' ";
            }
        }

        if (!empty($ordercol) && !empty($orderby)) {
            $query .= " ORDER BY " . $ordercol . " " . $orderby;
        }
        $query .= " LIMIT $offset, $limit ";
        $result = $this->db->query($query)->getResultArray();
        $m_result = array();
        $sno = $offset + 1;
        foreach ($result as $r) {
            $action = '<div class="dropdown tableAction">
                        <a type="button" class="dropBtn HoverA "><i class="fa fa-ellipsis-v"></i></a>
                        <div class="dropdown_container">
                            <ul class="BB_UL flex">
                                <li><a href="' . url_to('erp.project.teammemberdelete', $r['member_id']) . '" title="Delete" class="bg-danger del-confirm"><i class="fa fa-trash"></i> </a></li>
                            </ul>
                        </div>
                    </div>';
            array_push(
                $m_result,
                array(
                    $r['member_id'],
                    $sno,
                    $r['emp_code'],
                    $r['name'],
                    $r['desig'],
                    $r['email'],
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

    

    public function insert_teammember($team_id, $member)
    {
        $check1 = $this->db->query("SELECT member_id FROM team_members WHERE team_id=$team_id AND employee_id=$member ")->getRow();
        if (!empty($check1)) {
            $this->session->setFlashdata("op_error", "Duplicate member not allowed");
            return;
        }
        $query = "INSERT INTO team_members(team_id,employee_id) VALUES(?,?) ";
        $this->db->query($query, array($team_id, $member));
        $member_id = $this->db->insertId();
        $config['title'] = "Team Member Insert";
        if (!empty($member_id)) {
            $config['log_text'] = "[ Team Member successfully created ]";
            $config['ref_link'] = url_to('erp.project.teamview', $team_id);
            $this->session->setFlashdata("op_success", "Team Member successfully created");
        } else {
            $config['log_text'] = "[ Team Member failed to create ]";
            $config['ref_link'] = url_to('erp.project.teamview', $team_id);
            $this->session->setFlashdata("op_error", "Team Member failed to create");
        }
        log_activity($config);
    }

    public function delete_teammember($member_id)
    {
        $query = "DELETE FROM team_members WHERE member_id=$member_id";
        $this->db->query($query);
        $config['title'] = "Team Member Delete";
        if ($this->db->affectedRows() > 0) {
            $config['log_text'] = "[ Team Member successfully deleted ]";
            $config['ref_link'] = url_to("erp.project.teams");
            $this->session->setFlashdata("op_success", "Team Member successfully deleted");
        } else {
            $config['log_text'] = "[ Team Member failed to delete ]";
            $config['ref_link'] = url_to("erp.project.teams");
            $this->session->setFlashdata("op_error", "Team Member failed to delete");
        }
        log_activity($config);
    }
}
