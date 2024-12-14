<?php

namespace App\Libraires\Job;
use App\Libraries\Job\Job;

class SafeRoleDelete extends Job
{
    public function __construct()
    {
        parent::__construct();
        $this->can_burry = true;
        $this->max_attempt = 3;
        $this->priority = Job::HIGH;
        $this->can_notify = false;
        $this->time_gap_for_next_job = 120;
        $this->system_job = true;
    }

    public function getName()
    {
        return "saferoledelete";
    }

    public function execute($config = [])
    {
        $db = db_connect();
        $query = "SELECT role_id FROM erp_roles WHERE can_be_purged=1 ";
        $roles = $db->query($query)->getResultArray();

        if (!empty($roles)) {
            $deleteQuery = "DELETE FROM erp_roles WHERE role_id=? AND (SELECT COUNT(user_id) AS total FROM erp_users WHERE role_id=?)=0 ";

            foreach ($roles as $role) {
                $db->query($deleteQuery, [$role['role_id'], $role['role_id']]);
            }
        }

        return true;
    }

    public function getRunAt()
    {
        // 7 Days Once
        return time() + (7 * 24 * 60 * 60);
    }

    public function getTryAfter()
    {
        // 1 Day
        return time() + (1 * 24 * 60 * 60);
    }
}
