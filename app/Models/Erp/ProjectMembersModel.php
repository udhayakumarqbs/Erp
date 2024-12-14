<?php

namespace App\Models\Erp;

use CodeIgniter\Model;

class ProjectMembersModel extends Model
{
    protected $table      = 'project_members';
    protected $primaryKey = 'project_mem_id';

    protected $allowedFields = [
        'project_id',
        'member_id',
    ];

    protected $useTimestamps = false;

    private $project_status = array(
        0 => "Created",
        1 => "Progress",
        2 => "On Hold",
        3 => "Completed",
        4 => "Cancelled"
    );

    private $project_status_bg = array(
        0 => "st_dark",
        1 => "st_primary",
        2 => "st_warning",
        3 => "st_success",
        4 => "st_danger"
    );
    
    public function get_project_status_bg()
    {
        return $this->project_status_bg;
    }
    public function get_project_status()
    {
        return $this->project_status;
    }

    public function get_project_members($project_id)
    {
        $query = "SELECT erp_users.name,erp_users.email,erp_users.position FROM project_members JOIN erp_users ON project_members.member_id=erp_users.user_id WHERE project_id=$project_id ";
        $result = $this->db->query($query)->getResultArray();
        return $result;
    }
}
