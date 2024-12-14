<?php

namespace App\Models\Erp;

use CodeIgniter\Model;
use ReturnTypeWillChange;

class GoalsModel extends Model
{
    protected $table = "erp_goals";
    protected $primarykey = "goals_id";
    protected $allowedFields = [
        "subject",
        "description",
        "start_date",
        "end_date",
        "goal_type",
        "contract_type",
        "achievement",
        "notify_when_fail",
        "notify_when_achieve",
        "notified",
        "staff_id",
        "dateadded",
    ];

    public function ajax_dt_config_column()
    {
        $config = array(
            'columnNames' => ['Sno', 'Subject', 'Staff Member', 'Achievement', 'Start Date', 'End Date', 'Goal Type', 'Progress', 'Action'],
            'sortable' => [0, 1, 1, 1, 1, 1, 1, 1, 0]
        );
        return json_encode($config);
    }

    public function add_goals($data)
    {
        if ($this->builder()->insert($data)) {
            return true;
        } else {
            return false;
        }
    }
    public function get_goals_data_table()
    {
        $limit = $_GET["limit"];
        $offset = $_GET["offset"];
        $search = $_GET['search'] ?? "";

        $query = "SELECT a.goals_id as id,a.subject as subject,a.start_date as s_date,a.end_date as e_date,a.goal_type as goaltype,
        a.achievement as achievement,CONCAT(b.name,b.last_name) as emp_name FROM erp_goals as a
        LEFT JOIN erp_users AS b  ON  a.staff_id = b.user_id ";

        $count = "SELECT * FROM erp_goals as a
        LEFT JOIN erp_users AS b  ON  a.staff_id = b.user_id ";

        if (!empty($search)) {
            $query .= " WHERE ( a.subject LIKE '%" . $search . "%' OR b.name LIKE '%" . $search . "%' OR b.last_name LIKE '%" . $search . "%' ) ";
            $count .= " WHERE ( a.subject LIKE '%" . $search . "%' OR b.name LIKE '%" . $search . "%' OR b.last_name LIKE '%" . $search . "%' ) ";
        }



        $query .= " ORDER BY a.dateadded DESC LIMIT $offset, $limit";

        $result = $this->db->query($query)->getResultArray();

        $m_result = array();

        $sno = $offset + 1;

        foreach ($result as $r) {
            $action = '<div class="dropdown tableAction">
            <a type="button" class="dropBtn HoverA "><i class="fa fa-ellipsis-v"></i></a>
            <div class="dropdown_container">
                <ul class="BB_UL flex justify-content-around">
                    <li><a href="' . url_to("erp.goalsupdate", $r["id"]) . '" title="Edit" class="bg-success" ><i class="fa fa-pencil"></i> </a></li>
                    <li><a href="' . url_to("erp.goalsdelete", $r["id"]) . '"  title="Delete" class="bg-danger del-confirm"><i class="fa fa-trash"></i> </a></li>
                </ul>
            </div>
           </div>';
            foreach (get_goal_types() as $type) {
                if ($type["key"] == $r["goaltype"]) {
                    $r["goaltype"] = $type["lang_key"];
                }
            }

            if ($r["achievement"] > 0) {
                $progress = '<div class= "progress-align">
                                <div class="prog-not-finished">
                                    <span>0.00%</span>                                                                                                  
                                </div>
                            </div>';
            } else {
                $progress = '<div class= "progress-align">
                                <div class="prog-finished">
                                    <span>100.00%</span>                                                                                                  
                                </div>
                            </div>';
            }

            array_push($m_result, array(
                '',
                $sno,
                $r["subject"] ?? "-",
                $r["emp_name"] ?? "All Staff",
                $r["achievement"] ?? "-",
                $r["s_date"] ?? "-",
                $r["e_date"] ?? "-",
                $r["goaltype"] ?? "-",
                $progress,
                $action
            ));
            $sno++;
        }

        $response["data"] = $m_result;
        $response["total_rows"] = count($this->db->query($count)->getResultArray());

        return $response;
    }

    public function get_existing_data($id)
    {
        $query = "SELECT a.goals_id as goals_id,a.subject as subject,a.description as description,
        a.start_date as start_date,a.end_date as end_date,a.goal_type as goal_type,a.contract_type as contract_type,c.cont_name as contracttype_name,
        a.achievement as achievement,a.notify_when_fail as notify_when_fail,a.notify_when_achieve as notify_when_achieve,a.notified as notified,
        a.staff_id as staff_id,CONCAT(b.name,b.last_name) as staff_name   
        FROM erp_goals as a
        LEFT JOIN erp_users as b ON b.user_id = a.staff_id
        LEFT JOIN contracttype as c ON c.cont_id  = a.contract_type
        WHERE a.goals_id = $id";
        return $this->db->query($query)->getRow();
    }

    public function get_full_goals_export()
    {
        $query = "SELECT a.subject as subject,
        a.staff_id as staff_id,
        CONCAT(b.name,' ',b.last_name) as staff_name,
        a.achievement as achievement,
        a.start_date as start_date,
        a.end_date as end_date,
        a.goal_type as goal_type,
        a.achievement as progress
        FROM erp_goals as a
        LEFT JOIN erp_users as b ON b.user_id = a.staff_id
        LEFT JOIN contracttype as c ON c.cont_id  = a.contract_type";

        $result = $this->db->query($query)->getResultArray();
        $goals = array();
        foreach ($result as $r) {
            foreach (get_goal_types() as $type) {
                if ($type["key"] == $r["goal_type"]) {
                    $r["goal_type"] = $type["lang_key"];
                }
            }
            if ($r["progress"] > 0) {
                $r["progress"] = "0%";
            } else {
                $r["progress"] = "100%";
            }
            if ($r["staff_id"] == 0) {
                $r["staff_name"] = "To All Staff";
            }
            array_push($goals, array(
                $r["subject"],
                $r["staff_name"],
                $r["achievement"],
                $r["start_date"],
                $r["end_date"],
                $r["goal_type"],
                $r["progress"]
            ));
        }
        return $goals;
    }

    public function update_goals($id, $data)
    {
        if ($this->builder()->where("goals_id ", $id)->update($data)) {
            return true;
        } else {
            return false;
        }
    }

    public function goal_delete($id)
    {
        if ($this->builder()->where("goals_id", $id)->delete()) {
            return true;
        } else {
            return false;
        }
    }
    public function is_notified($id)
    {
        $data = [
            "notified" => 1
        ];
        if ($this->builder()->where("goals_id", $id)->update($data)) {
            return true;
        } else {
            return false;
        }
    }
}
