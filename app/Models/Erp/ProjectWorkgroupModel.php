<?php

namespace App\Models\Erp;

use CodeIgniter\Model;

class ProjectWorkgroupModel extends Model
{
    protected $table      = 'project_workgroup';
    protected $primaryKey = 'project_wgrp_id';

    protected $allowedFields = [
        'phase_id',
        'wgroup_id',
        'team_id',
        'project_id',
        'started_at',
        'completed_at',
        'completed',
        'fetched',
        'worker_type',
        'contractor_id',
        'amount',
        'paid_till',
        'pay_before',
        'c_status',
    ];

    protected $useTimestamps = false;

    public $db;
    public $session;
    public function __construct()
    {
        $this->db = \Config\Database::connect();
        $this->session = \Config\Services::session();
    }

    private $contractor_status_bg = array(
        0 => "st_violet",
        1 => "st_primary",
        2 => "st_success"
    );

    public function get_contractor_status_bg()
    {
        return $this->contractor_status_bg;
    }


    public function get_dtconfig_project_contractor()
    {
        $config = array(
            "columnNames" => ["sno", "workgroup", "contractor", "amount", "due", "status", "action"],
            "sortable" => [0, 1, 1, 1, 0, 0],
        );
        return json_encode($config);
    }

    public function get_project_contractor_datatable()
    {
        $project_id = $this->input->get("projectid");
        $columns = array(
            "workgroup" => "work_groups.name",
            "contractor" => "contractors.name",
            "amount" => "amount"
        );
        $limit = $_GET['limit'];
        $offset = $_GET['offset'];
        $search = $_GET['search'] ?? "";
        $orderby = isset($_GET['orderby']) ? strtoupper($_GET['orderby']) : "";
        $ordercol = isset($_GET['ordercol']) ? $columns[$_GET['ordercol']] : "";

        $query = "SELECT project_wgrp_id,work_groups.name AS workgroup,CONCAT(contractors.name,' ',con_code) AS contractor,amount,paid_till,c_status FROM project_workgroup JOIN contractors ON project_workgroup.contractor_id=contractors.contractor_id JOIN work_groups ON project_workgroup.wgroup_id=work_groups.wgroup_id WHERE project_id=$project_id AND worker_type='Contractor' ";
        $count = "SELECT COUNT(project_wgrp_id) AS total FROM project_workgroup JOIN contractors ON project_workgroup.contractor_id=contractors.contractor_id JOIN work_groups ON project_workgroup.wgroup_id=work_groups.wgroup_id WHERE project_id=$project_id AND worker_type='Contractor' ";
        $where = true;

        if (!empty($search)) {
            if (!$where) {
                $query .= " WHERE work_groups.name LIKE '%" . $search . "%' ";
                $count .= " WHERE work_groups.name LIKE '%" . $search . "%' ";
                $where = true;
            } else {
                $query .= " AND work_groups.name LIKE '%" . $search . "%' ";
                $count .= " AND work_groups.name LIKE '%" . $search . "%' ";
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
            $action = '<div class="dropdown tableAction" >
                        <a type="button" class="dropBtn HoverA" ><i class="fa fa-ellipsis-v"></i></a>
                        <div class="dropdown_container" >
                            <ul class="BB_UL flex" >
                                <li><a href="' . url_to('erp.project.contractorview', $project_id,  $r['project_wgrp_id']) . '" title="View" class="bg-warning"><i class="fa fa-eye"></i> </a></li>
                                <li><a href="#" data-ajax-url="' . url_to('erp.project.ajaxfetchcontractor', $r['project_wgrp_id']) . '" title="Edit" class="bg-success modalBtn" ><i class="fa fa-pencil"></i> </a></li>
                            </ul>
                        </div>
                    </div>';
            $status = '<span class="st ' . $this->contractor_status_bg[$r['c_status']] . '" >' . $this->contractor_status[$r['c_status']] . '</span>';
            array_push(
                $m_result,
                array(
                    $r['project_wgrp_id'],
                    $sno,
                    $r['workgroup'],
                    $r['contractor'],
                    $r['amount'],
                    $r['amount'] - $r['paid_till'],
                    $status,
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

    public function insert_phase_workgroup($project_id, $post)
    {
        $wgroup_id = $post["wgroup_id"];
        $phase_id = $post["phase_id"];
        $team_id = $post["team_id"];
        $worker_type = "Team";
        $check = $this->db->query("SELECT project_wgrp_id FROM project_workgroup WHERE phase_id=$phase_id AND wgroup_id=$wgroup_id ")->getRow();
        if (!empty($check)) {
            $this->session->setFlashdata("op_error", "Duplicate Project Workgroup");
        } else {
            $query = "INSERT INTO project_workgroup(phase_id,wgroup_id,project_id,team_id,worker_type) VALUES(?,?,?,?,?) ";
            $this->db->query($query, array($phase_id, $wgroup_id, $project_id, $team_id, $worker_type));
            $project_wgrp_id = $this->db->insertId();
            $config['title'] = "Project Workgroup Insert";
            if (!empty($project_wgrp_id)) {
                $config['log_text'] = "[ Project Workgroup successfully created ]";
                $config['ref_link'] = url_to('erp.project.projectview', $project_id);
                $this->session->setFlashdata("op_success", "Project Workgroup successfully created");
            } else {
                $config['log_text'] = "[ Project Workgroup failed to create ]";
                $config['ref_link'] = url_to('erp.project.projectview', $project_id);
                $this->session->setFlashdata("op_error", "Project Workgroup failed to create");
            }
            log_activity($config);
        }
    }

    public function insert_c_phase_workgroup($project_id, $post1)
    {
        $wgroup_id = $post1["wgroup_id"];
        $phase_id = $post1["phase_id"];
        $worker_type = $post1["worker_type"];
        $team_id = $post1["team_id"];
        $contractor_id = $post1["contractor_id"];
        $check = $this->db->query("SELECT project_wgrp_id FROM project_workgroup WHERE phase_id=$phase_id AND wgroup_id=$wgroup_id ")->getRow();
        if (!empty($check)) {
            $this->session->setFlashdata("op_error", "Duplicate Project Workgroup");
        } else {
            $query = "INSERT INTO project_workgroup(phase_id,wgroup_id,project_id,team_id,contractor_id,worker_type) VALUES(?,?,?,?,?,?) ";
            $this->db->query($query, array($phase_id, $wgroup_id, $project_id, $team_id, $contractor_id, $worker_type));
            $project_wgrp_id = $this->db->insertId();
            $config['title'] = "Project Workgroup Insert";
            if (!empty($project_wgrp_id)) {
                $config['log_text'] = "[ Project Workgroup successfully created ]";
                $config['ref_link'] = url_to('erp.project.projectview', $project_id);
                $this->session->setFlashdata("op_success", "Project Workgroup successfully created");
            } else {
                $config['log_text'] = "[ Project Workgroup failed to create ]";
                $config['ref_link'] = url_to('erp.project.projectview', $project_id);
                $this->session->setFlashdata("op_error", "Project Workgroup failed to create");
            }
            log_activity($config);
        }
    }

    public function delete_phase_workgroup($project_id, $project_wgrp_id)
    {
        $query = "DELETE FROM project_workgroup WHERE project_wgrp_id=$project_wgrp_id ";
        $this->db->query($query);
        $config['title'] = "Project Workgroup Delete";
        if (!empty($project_wgrp_id)) {
            $config['log_text'] = "[ Project Workgroup successfully deleted ]";
            $config['ref_link'] = url_to('erp.project.projectview', $project_id);
            $this->session->setFlashdata("op_success", "Project Workgroup successfully deleted");
        } else {
            $config['log_text'] = "[ Project Workgroup failed to delete ]";
            $config['ref_link'] = url_to('erp.project.projectview', $project_id);
            $this->session->setFlashdata("op_error", "Project Workgroup failed to delete");
        }
        log_activity($config);
    }

    public function complete_phase_workgroup($project_wgrp_id)
    {
        $query = "UPDATE project_workgroup SET completed=1 , completed_at=? WHERE project_wgrp_id=$project_wgrp_id ";
        $this->db->query($query, array(date('Y-m-d')));
        $phase_id = 0;
        $query = "SELECT phase_id FROM project_workgroup WHERE project_wgrp_id=$project_wgrp_id";
        $result = $this->db->query($query)->getRow();
        $phase_id = $result->phase_id;
        $query = "UPDATE project_workgroup SET started_at=? WHERE started_at='' AND phase_id=$phase_id ORDER BY project_wgrp_id ASC LIMIT 1";
        $this->db->query($query, array(date("Y-m-d")));
    }

    public function insert_project_testing($project_id, $post)
    {
        $inserted = false;
        $name = $post["name"];
        $complete_before = $post["complete_before"];
        $assigned_to = $post["assigned_to"];
        $description = $post["description"];
        $created_by = get_user_id();

        $query = "INSERT INTO project_testing(project_id,name,assigned_to,complete_before,description,created_at,created_by) VALUES(?,?,?,?,?,?,?) ";
        $this->db->query($query, array($project_id, $name, $assigned_to, $complete_before, $description, time(), $created_by));
        $project_test_id = $this->db->insertId();
        $config['title'] = "Project Testing Insert";
        if (!empty($project_test_id)) {
            $inserted = true;
            $config['log_text'] = "[ Project Testing successfully created ]";
            $config['ref_link'] = url_to('erp.project.projectview', $project_id);
            $this->session->setFlashdata("op_success", "Project Testing successfully created");
        } else {
            $config['log_text'] = "[ Project Testing failed to create ]";
            $config['ref_link'] = url_to('erp.project.projectview', $project_id);
            $this->session->setFlashdata("op_error", "Project Testing failed to create");
        }
        log_activity($config);
        return $inserted;
    }

    public function insert_from_active_workgroup($project_id)
    {
        $active_workgroups = array();
        $query = "SELECT wgroup_id FROM project_workgroup WHERE project_id=$project_id AND started_at<>'' AND completed=0 AND fetched=0 ";
        $result = $this->db->query($query)->getResultArray();
        foreach ($result as $row) {
            array_push($active_workgroups, $row['wgroup_id']);
        }
        if (!empty($active_workgroups)) {
            $query = "SELECT work_group_items.related_to,work_group_items.related_id,qty FROM work_group_items WHERE wgroup_id IN (" . implode(",", $active_workgroups) . ") ";
            $result = $this->db->query($query)->getResultArray();
            $this->db->transBegin();
            $op_failed = false;
            $query = "SELECT project_rawmaterial_insert(?,?,?,?) AS error_flag ";
            foreach ($result as $row) {
                $error = $this->db->query($query, array($row['related_id'], $row['qty'], $project_id, 1))->getRow()->error_flag;
                if ($error == 1) {
                    $this->db->transRollback();
                    $this->session->setFlashdata("op_error", "Project Rawmaterials failed to insert ");
                    $op_failed = true;
                    break;
                }
            }
            if ($op_failed) {
            } else {
                $query = "UPDATE project_workgroup SET fetched=1 WHERE wgroup_id IN (" . implode(",", $active_workgroups) . ") AND project_id=$project_id ";
                $this->db->query($query);
                if ($this->db->affectedRows() <= 0) {
                    $this->db->transRollback();
                    $this->session->setFlashdata("op_error", "Project Rawmaterials failed to insert");
                } else {
                    $this->db->transComplete();
                    $config['title'] = "Project Rawmaterials Insert";
                    if ($this->db->transStatus() === FALSE) {
                        $this->db->transRollback();
                        $config['log_text'] = "[ Project Rawmaterials failed to insert ]";
                        $config['ref_link'] = url_to('erp.project.projectview', $project_id);
                        $this->session->setFlashdata("op_error", "Project Rawmaterials failed to insert");
                    } else {
                        $this->db->transCommit();
                        $config['log_text'] = "[ Project Rawmaterials successfully inserted ]";
                        $config['ref_link'] = url_to('erp.project.projectview', $project_id);
                        $this->session->setFlashdata("op_success", "Project Rawmaterials successfully inserted");
                    }
                    log_activity($config);
                }
            }
        } else {
            $this->session->setFlashdata("op_error", "Project Rawmaterials failed to insert");
        }
    }

    public function update_contractor($project_id, $post)
    {
        $amount = $post["amount"];
        $pay_before = $post["pay_before"];
        $project_wgrp_id = $post["project_wgrp_id"];

        $query = "UPDATE project_workgroup SET amount=? , pay_before=? WHERE project_wgrp_id=$project_wgrp_id ";
        $this->db->query($query, array($amount, $pay_before));
        $config['title'] = "Project Contractor Update";
        if ($this->db->affectedRows() > 0) {
            $config['log_text'] = "[ Project Contractor successfully updated ]";
            $config['ref_link'] = 'erp/project/projectview/' . $project_id;
            $this->session->setFlashdata("op_success", "Project Contractor successfully updated");
        } else {
            $config['log_text'] = "[ Project Contractor failed to update ]";
            $config['ref_link'] = 'erp/project/projectview/' . $project_id;
            $this->session->setFlashdata("op_error", "Project Contractor failed to update");
        }
        log_activity($config);
    }

    public function get_project_contractor_for_view($project_wgrp_id)
    {
        $query = "SELECT work_groups.name AS workgroup,contractors.name AS contractor,con_code,contact_person,amount,paid_till,(amount-paid_till) AS due,pay_before,c_status FROM project_workgroup JOIN work_groups ON project_workgroup.wgroup_id=work_groups.wgroup_id JOIN contractors ON project_workgroup.contractor_id=contractors.contractor_id WHERE project_wgrp_id=$project_wgrp_id ";
        $result = $this->db->query($query)->getRow();
        return $result;
    }
}
