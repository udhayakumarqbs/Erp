<?php

namespace App\Models\Erp;

use CodeIgniter\Model;

class ProjectPhaseModel extends Model
{
    protected $table      = 'project_phase';
    protected $primaryKey = 'phase_id';

    protected $allowedFields = [
        'name',
        'started',
        'project_id',
    ];

    protected $useTimestamps = false;

    public $db;
    public $session;
    public function __construct()
    {
        $this->db = \Config\Database::connect();
        $this->session = \Config\Services::session();
    }

    public function get_all_phases($project_id)
    {
        $query = "SELECT project_phase.phase_id,project_phase.name AS phase_name,started,work_groups.name AS workgroup,approx_days,project_wgrp_id,completed,started_at,completed_at,teams.name AS team_name,erp_users.name AS lead,contractors.name AS contractor,contractors.contact_person FROM project_phase LEFT JOIN project_workgroup ON project_phase.phase_id=project_workgroup.phase_id LEFT JOIN work_groups ON project_workgroup.wgroup_id=work_groups.wgroup_id LEFT JOIN teams ON project_workgroup.team_id=teams.team_id LEFT JOIN erp_users ON teams.lead_by=erp_users.user_id LEFT JOIN contractors ON project_workgroup.contractor_id=contractors.contractor_id WHERE project_phase.project_id=$project_id ";
        $result = $this->db->query($query)->getResultArray();
        $m_result = array();
        foreach ($result as $row) {
            $m_result[$row['phase_id']]['phase_name'] = $row['phase_name'];
            $m_result[$row['phase_id']]['started'] = $row['started'];
            if (isset($m_result[$row['phase_id']]['workgroups'])) {
                if (!empty($row['workgroup'])) {
                    array_push($m_result[$row['phase_id']]['workgroups'], array(
                        "workgroup_name" => $row['workgroup'],
                        "approx_days" => $row['approx_days'],
                        "project_wgrp_id" => $row['project_wgrp_id'],
                        "completed" => $row['completed'],
                        "team" => $row['team_name'],
                        "lead_by" => $row['lead'],
                        "started_at" => $row['started_at'],
                        "completed_at" => $row['completed_at'],
                        "contractor" => $row['contractor'],
                        "contact_person" => $row['contact_person']
                    ));
                }
            } else {
                $m_result[$row['phase_id']]['workgroups'] = array();
                if (!empty($row['workgroup'])) {
                    array_push($m_result[$row['phase_id']]['workgroups'], array(
                        "workgroup_name" => $row['workgroup'],
                        "approx_days" => $row['approx_days'],
                        "project_wgrp_id" => $row['project_wgrp_id'],
                        "completed" => $row['completed'],
                        "team" => $row['team_name'],
                        "lead_by" => $row['lead'],
                        "started_at" => $row['started_at'],
                        "completed_at" => $row['completed_at'],
                        "contractor" => $row['contractor'],
                        "contact_person" => $row['contact_person']
                    ));
                }
            }
        }
        return $m_result;
    }

    public function insert_update_project_phase($project_id, $phase_id, $name)
    {

        if (empty($phase_id)) {
            $this->insert_project_phase($project_id, $name);
        } else {
            $this->update_project_phase($project_id, $phase_id, $name);
        }
    }

    private function insert_project_phase($project_id, $name)
    {
        // $name = $this->input->post("name");
        $query = "INSERT INTO project_phase(project_id,name) VALUES(?,?) ";
        $this->db->query($query, array($project_id, $name));
        $phase_id = $this->db->insertId();
        $config['title'] = "Project Phase Insert";
        if (!empty($phase_id)) {
            $config['log_text'] = "[ Project Phase successfully created ]";
            $config['ref_link'] = url_to('erp.project.projectview', $project_id);
            $this->session->setFlashdata("op_success", "Project Phase successfully created");
        } else {
            $config['log_text'] = "[ Project Phase failed to create ]";
            $config['ref_link'] = url_to('erp.project.projectview', $project_id);
            $this->session->setFlashdata("op_error", "Project Phase failed to create");
        }
        log_activity($config);
    }

    private function update_project_phase($project_id, $phase_id, $name)
    {
        // $name = $this->input->post("name");
        $query = "UPDATE project_phase SET name=? WHERE phase_id=$phase_id";
        $this->db->query($query, array($name));
        $config['title'] = "Project Phase Update";
        if ($this->db->affectedRows() > 0) {
            $config['log_text'] = "[ Project Phase successfully updated ]";
            $config['ref_link'] = url_to('erp.project.projectview', $project_id);
            $this->session->setFlashdata("op_success", "Project Phase successfully updated");
        } else {
            $config['log_text'] = "[ Project Phase failed to update ]";
            $config['ref_link'] = url_to('erp.project.projectview', $project_id);
            $this->session->setFlashdata("op_error", "Project Phase failed to update");
        }
        log_activity($config);
    }


    public function start_phase($phase_id)
    {
        $query = "UPDATE project_phase SET started=1 WHERE phase_id=$phase_id ";
        $this->db->query($query);
        $query = "UPDATE project_workgroup SET started_at=? WHERE started_at='' AND phase_id=$phase_id ORDER BY project_wgrp_id ASC LIMIT 1";
        $this->db->query($query, array(date("Y-m-d")));
    }
}
