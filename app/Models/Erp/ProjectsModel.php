<?php

namespace App\Models\Erp;

use CodeIgniter\Model;
use Exception;

class ProjectsModel extends Model
{
    protected $table      = 'projects';
    protected $primaryKey = 'project_id';

    protected $allowedFields = [
        'name',
        'cust_id',
        'related_to',
        'related_id',
        'start_date',
        'end_date',
        'budget',
        'description',
        'status',
        'type',
        'units',
        'type_id',
        'address',
        'city',
        'state',
        'country',
        'zipcode',
        'created_at',
        'created_by'
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = '';
    protected $session;
    protected $db;
    protected $attachmentModel;
    public function __construct()
    {
        $this->attachmentModel = new AttachmentModel();
        $this->session = \Config\Services::session();
        $this->db = \Config\Database::connect();
    }
    private $project_status_bg = array(
        0 => "st_dark",
        1 => "st_primary",
        2 => "st_warning",
        3 => "st_success",
        4 => "st_danger"
    );
    
    private $project_status = array(
        0 => "Created",
        1 => "Progress",
        2 => "On Hold",
        3 => "Completed",
        4 => "Cancelled"
    );

    public function get_project_status_bg()
    {
        return $this->project_status_bg;
    }

    public function get_project_status()
    {
        return $this->project_status;
    }


    public function get_dtconfig_m_projects()
    {
        $config = array(
            "columnNames" => ["sno", "name", "start date", "end date", "budget", "product", "status", "action"],
            "sortable" => [0, 1, 1, 1, 1, 0, 0, 0],
            "filters" => ["status"]
        );
        return json_encode($config);
    }

    public function get_dtconfig_c_projects()
    {
        $config = array(
            "columnNames" => ["sno", "name", "start date", "end date", "budget", "type", "status", "action"],
            "sortable" => [0, 1, 1, 1, 1, 0, 0, 0],
            "filters" => ["status"]
        );
        return json_encode($config);
    }
    public function get_m_project_datatable()
    {
        $columns = array(
            "name" => "name",
            "start date" => "start_date",
            "end date" => "end_date",
            "budget" => "budget",
            "status" => "status"
        );
        $limit = $_GET['limit'];
        $offset = $_GET['offset'];
        $search = $_GET['search'] ?? "";
        $orderby = isset($_GET['orderby']) ? strtoupper($_GET['orderby']) : "";
        $ordercol = isset($_GET['ordercol']) ? $columns[$_GET['ordercol']] : "";

        $query = "SELECT project_id,projects.name,start_date,end_date,budget,CONCAT(finished_goods.name,' ',finished_goods.code) AS product,status FROM projects JOIN finished_goods ON related_id=finished_goods.finished_good_id WHERE type=1 ";
        $count = "SELECT COUNT(project_id) AS total FROM projects JOIN finished_goods ON related_id=finished_goods.finished_good_id WHERE type=1 ";
        $where = true;

        if (!empty($search)) {
            if (!$where) {
                $query .= " WHERE name LIKE '%" . $search . "%' ";
                $count .= " WHERE name LIKE '%" . $search . "%' ";
                $where = true;
            } else {
                $query .= " AND name LIKE '%" . $search . "%' ";
                $count .= " AND name LIKE '%" . $search . "%' ";
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
                                <li><a href="' . url_to('erp.project.projectview', $r['project_id']) . '" title="View" class="bg-warning"><i class="fa fa-eye"></i> </a></li>
                                <li><a href="' . url_to('erp.project.projectedit', $r['project_id']) . '" title="Edit" class="bg-success"><i class="fa fa-pencil"></i> </a></li>
                                <li><a href="' . url_to('erp.project.projectdelete', $r['project_id']) . '" title="Delete" class="bg-danger del-confirm"><i class="fa fa-trash"></i> </a></li>
                            </ul>
                        </div>
                    </div>';
            $status = "<span class='st " . $this->project_status_bg[$r['status']] . "'>" . $this->project_status[$r['status']] . " </span>";
            array_push(
                $m_result,
                array(
                    $r['project_id'],
                    $sno,
                    $r['name'],
                    $r['start_date'],
                    $r['end_date'],
                    $r['budget'],
                    $r['product'],
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

    public function get_m_project_export($type)
    {
        $columns = array(
            "name" => "name",
            "start date" => "start_date",
            "end date" => "end_date",
            "budget" => "budget",
            "status" => "status"
        );
        $limit = $_GET['limit'];
        $offset = $_GET['offset'];
        $search = $_GET['search'] ?? "";
        $orderby = isset($_GET['orderby']) ? strtoupper($_GET['orderby']) : "";
        $ordercol = isset($_GET['ordercol']) ? $columns[$_GET['ordercol']] : "";
        $query = "";
        if ($type == "pdf") {
            $query = "SELECT projects.name,CONCAT(finished_goods.name,' ',finished_goods.code) AS product,budget,start_date,end_date,
            CASE ";
            foreach ($this->project_status as $key => $value) {
                $query .= " WHEN status=$key THEN '$value' ";
            }
            $query .= " END AS status FROM projects JOIN finished_goods ON related_id=finished_goods.finished_good_id WHERE type=1 ";
        } else {
            $query = "SELECT projects.name,CONCAT(finished_goods.name,' ',finished_goods.code) AS product,IFNULL(customers.name,'Not for Customer') AS customer,budget,start_date,end_date,
            CASE ";
            foreach ($this->project_status as $key => $value) {
                $query .= " WHEN status=$key THEN '$value' ";
            }
            $query .= " END AS status,projects.description FROM projects JOIN finished_goods ON related_id=finished_goods.finished_good_id LEFT JOIN customers ON projects.cust_id=customers.cust_id WHERE type=1 ";
        }
        $where = true;

        if (!empty($search)) {
            if (!$where) {
                $query .= " WHERE name LIKE '%" . $search . "%' ";
                $where = true;
            } else {
                $query .= " AND name LIKE '%" . $search . "%' ";
            }
        }

        if (!empty($ordercol) && !empty($orderby)) {
            $query .= " ORDER BY " . $ordercol . " " . $orderby;
        }
        $result = $this->db->query($query)->getResultArray();
        return $result;
    }

    public function get_c_project_datatable()
    {
        $columns = array(
            "name" => "name",
            "start date" => "start_date",
            "end date" => "end_date",
            "budget" => "budget",
            "status" => "status"
        );
        $limit = $_GET['limit'];
        $offset = $_GET['offset'];
        $search = $_GET['search'] ?? "";
        $orderby = isset($_GET['orderby']) ? strtoupper($_GET['orderby']) : "";
        $ordercol = isset($_GET['ordercol']) ? $columns[$_GET['ordercol']] : "";

        $query = "SELECT project_id,projects.name,start_date,end_date,budget,type_name,status FROM projects JOIN propertytype ON projects.type_id=propertytype.type_id WHERE type=0 ";
        $count = "SELECT COUNT(project_id) AS total FROM projects JOIN propertytype ON projects.type_id=propertytype.type_id WHERE type=0 ";
        $where = true;

        if (!empty($search)) {
            if (!$where) {
                $query .= " WHERE name LIKE '%" . $search . "%' ";
                $count .= " WHERE name LIKE '%" . $search . "%' ";
                $where = true;
            } else {
                $query .= " AND name LIKE '%" . $search . "%' ";
                $count .= " AND name LIKE '%" . $search . "%' ";
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
                                <li><a href="' . url_to('erp.project.projectview', $r['project_id']) . '" title="View" class="bg-warning"><i class="fa fa-eye"></i> </a></li>
                                <li><a href="' . url_to('erp.project.projectedit', $r['project_id']) . '" title="Edit" class="bg-success"><i class="fa fa-pencil"></i> </a></li>
                                <li><a href="' . url_to('erp.project.projectdelete', $r['project_id']) . '" title="Delete" class="bg-danger del-confirm"><i class="fa fa-trash"></i> </a></li>
                            </ul>
                        </div>
                    </div>';
            $status = "<span class='st " . $this->project_status_bg[$r['status']] . "'>" . $this->project_status[$r['status']] . " </span>";
            array_push(
                $m_result,
                array(
                    $r['project_id'],
                    $sno,
                    $r['name'],
                    $r['start_date'],
                    $r['end_date'],
                    $r['budget'],
                    $r['type_name'],
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

    public function insert_m_project($post, $members)
    {
        $inserted = false;
        $name = $_POST["name"];
        $start_date = $_POST["start_date"];
        $end_date = $_POST["end_date"];
        $budget = $_POST["budget"];
        $cust_id = $_POST["cust_id"];
        $related_id = $_POST["related_id"];
        $description = $_POST["description"];
        $created_by = get_user_id();

        $this->db->transBegin();
        $query = "INSERT INTO projects(name,start_date,end_date,budget,cust_id,related_to,related_id,description,type,created_at,created_by) VALUES(?,?,?,?,?,?,?,?,?,?,?) ";
        $this->db->query($query, array($name, $start_date, $end_date, $budget, $cust_id, 'finished_good', $related_id, $description, 1, time(), $created_by));
        $project_id = $this->db->insertId();

        if (empty($project_id)) {
            $this->session->setFlashdata("op_error", "Project failed to create");
            return $inserted;
        }

        if (!$this->update_project_members($project_id, $members)) {
            $this->db->transRollback();
            $this->session->setFlashdata("op_error", "Project failed to create");
            return $inserted;
        }

        $this->db->transComplete();
        $config['title'] = "Project Insert";
        if ($this->db->transStatus() === FALSE) {
            $this->db->transRollback();
            $config['log_text'] = "[ Project failed to create ]";
            $config['ref_link'] = '';
            $this->session->setFlashdata("op_error", "Project failed to create");
        } else {
            $inserted = true;
            $this->db->transCommit();
            $config['log_text'] = "[ Project successfully created ]";
            $config['ref_link'] = url_to('erp.project.projectview', $project_id);
            $this->session->setFlashdata("op_success", "Project successfully created");
        }
        log_activity($config);
        return $inserted;
    }

    private function update_project_members($project_id, $members)
    {
        $updated = true;
        $old_member_ids = array();
        $query = "SELECT member_id FROM project_members WHERE project_id=$project_id ";
        $result = $this->db->query($query)->getResultArray();
        foreach ($result as $row) {
            array_push($old_member_ids, $row['member_id']);
        }
        $new_member_ids = explode(",", $members);
        $ins = array();
        $dels = array();
        foreach ($new_member_ids as $new) {
            if (!in_array($new, $old_member_ids)) {
                array_push($ins, $new);
            }
        }
        foreach ($old_member_ids as $old) {
            if (!in_array($old, $new_member_ids)) {
                array_push($dels, $old);
            }
        }
        if (!empty($dels)) {
            $query = "DELETE FROM project_members WHERE project_id=$project_id AND member_id IN (" . implode(",", $dels) . ")";
            $this->db->query($query);
        }
        $query = "INSERT INTO project_members(project_id,member_id) VALUES(?,?) ";
        foreach ($ins as $member_id) {
            $this->db->query($query, array($project_id, $member_id));
            if (empty($this->db->insertId())) {
                $updated = false;
                break;
            }
        }
        return $updated;
    }

    public function insert_c_project($post, $amenity, $members)
    {
        $inserted = false;
        $name = $post["name"];
        $start_date = $post["start_date"];
        $end_date = $post["end_date"];
        $budget = $post["budget"];
        $cust_id = $post["cust_id"];
        $description = $post["description"];

        $units = $post["units"];
        $type_id = $post["type_id"];
        $address = $post["address"];
        $city = $post["city"];
        $state = $post["state"];
        $country = $post["country"];
        $zipcode = $post["zipcode"];

        $created_by = get_user_id();

        $this->db->transBegin();
        $query = "INSERT INTO projects(name,start_date,end_date,budget,cust_id,description,type,created_at,created_by,units,type_id,address,city,state,country,zipcode) VALUES(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?) ";
        $this->db->query($query, array($name, $start_date, $end_date, $budget, $cust_id, $description, 0, time(), $created_by, $units, $type_id, $address, $city, $state, $country, $zipcode));
        $project_id = $this->db->insertId();

        if (empty($project_id)) {
            $this->session->setFlashdata("op_error", "Project failed to create");
            return $inserted;
        }

        if (!$this->update_project_members($project_id, $members)) {
            $this->db->transRollback();
            $this->session->setFlashdata("op_error", "Project failed to create");
            return $inserted;
        }

        if (!$this->update_property_amenity($project_id, $amenity)) {
            $this->db->transRollback();
            $this->session->setFlashdata("op_error", "Project failed to create");
            return $inserted;
        }

        $this->db->transComplete();
        $config['title'] = "Project Insert";
        if ($this->db->transStatus() === FALSE) {
            $this->db->transRollback();
            $config['log_text'] = "[ Project failed to create ]";
            $config['ref_link'] = '';
            $this->session->setFlashdata("op_error", "Project failed to create");
        } else {
            $inserted = true;
            $this->db->transCommit();
            $config['log_text'] = "[ Project successfully created ]";
            $config['ref_link'] = url_to('erp.project.projectview', $project_id);
            $this->session->setFlashdata("op_success", "Project successfully created");
        }
        log_activity($config);
        return $inserted;
    }

    private function update_property_amenity($project_id, $amenity)
    {
        $updated = true;
        $amenities = explode(",", $amenity);
        if (!empty($amenities)) {
            $result = $this->db->query("SELECT amenity_id FROM project_amenity WHERE project_id=$project_id")->getResultArray();
            $old_set = array();
            foreach ($result as $row) {
                array_push($old_set, $row['amenity_id']);
            }
            $intersect = array_intersect($amenities, $old_set);
            $del_set = array();
            $ins_set = array();
            foreach ($amenities as $value) {
                if (!in_array($value, $intersect)) {
                    array_push($ins_set, $value);
                }
            }
            foreach ($old_set as $value) {
                if (!in_array($value, $intersect)) {
                    array_push($del_set, $value);
                }
            }
            $query = "INSERT INTO project_amenity(project_id,amenity_id) VALUES(?,?) ";
            foreach ($ins_set as $amenity_id) {
                $this->db->query($query, array($project_id, $amenity_id));
                if (empty($this->db->insertId())) {
                    $updated = false;
                    break;
                }
            }
            if ($updated && !empty($del_set)) {
                $del_amenity_ids = implode(",", $del_set);
                $query = "DELETE FROM project_amenity WHERE project_id=$project_id AND amenity_id IN (" . $del_amenity_ids . ") ";
                $this->db->query($query);
                if ($this->db->affectedRows() <= 0) {
                    $updated = false;
                }
            }
        }
        return $updated;
    }

    public function get_m_project_by_id($project_id)
    {
        $query = "SELECT projects.project_id,projects.name,start_date,end_date,budget,related_id,CONCAT(finished_goods.name,' ',finished_goods.code) AS product,projects.cust_id,customers.name AS customer,projects.description,GROUP_CONCAT(member_id SEPARATOR ',' ) AS members FROM projects JOIN finished_goods ON related_id=finished_goods.finished_good_id LEFT JOIN customers ON projects.cust_id=customers.cust_id JOIN project_members ON projects.project_id=project_members.project_id WHERE projects.project_id=$project_id ";
        $result = $this->db->query($query)->getRow();
        return $result;
    }


    public function update_m_project($project_id, $post, $members)
    {
        $updated = false;
        $name = $post["name"];
        $start_date = $post["start_date"];
        $end_date = $post["end_date"];
        $budget = $post["budget"];
        $cust_id = $post["cust_id"];
        $related_id = $post["related_id"];
        $description = $post["description"];

        $this->db->transBegin();
        $query = "UPDATE projects SET name=? , start_date=? , end_date=? , budget=? , cust_id=? , related_id=? , description=? WHERE project_id=$project_id ";
        $this->db->query($query, array($name, $start_date, $end_date, $budget, $cust_id, $related_id, $description));

        if (!$this->update_project_members($project_id, $members)) {
            $this->db->transRollback();
            $this->session->setFlashdata("op_error", "Project failed to update");
            return $updated;
        }

        $this->db->transComplete();
        $config['title'] = "Project Update";
        if ($this->db->transStatus() === FALSE) {
            $this->db->transRollback();
            $config['log_text'] = "[ Project failed to update ]";
            $config['ref_link'] = url_to('erp.project.projectview.', $project_id);
            $this->session->setFlashdata("op_error", "Project failed to update");
        } else {
            $updated = true;
            $this->db->transCommit();
            $config['log_text'] = "[ Project successfully updated ]";
            $config['ref_link'] = url_to('erp.project.projectview', $project_id);
            $this->session->setFlashdata("op_success", "Project successfully updated");
        }
        log_activity($config);
        return $updated;
    }

    public function update_c_project($project_id, $post1, $amenity, $members)
    {
        $updated = false;
        $name = $post1["name"];
        $start_date = $post1["start_date"];
        $end_date = $post1["end_date"];
        $budget = $post1["budget"];
        $cust_id = $post1["cust_id"];
        $description = $post1["description"];

        $units = $post1["units"];
        $type_id = $post1["type_id"];
        $address = $post1["address"];
        $city = $post1["city"];
        $state = $post1["state"];
        $country = $post1["country"];
        $zipcode = $post1["zipcode"];

        $this->db->transBegin();
        $query = "UPDATE projects SET name=? , start_date=? , end_date=? , budget=? , cust_id=? , description=? , units=? , type_id=? , address=? , city=? , state=? , country=? , zipcode=? WHERE project_id=$project_id ";
        $this->db->query($query, array($name, $start_date, $end_date, $budget, $cust_id, $description, $units, $type_id, $address, $city, $state, $country, $zipcode));

        if (!$this->update_project_members($project_id, $members)) {
            $this->db->transRollback();
            $this->session->setFlashdata("op_error", "Project failed to create");
            return $updated;
        }

        if (!$this->update_property_amenity($project_id, $amenity)) {
            $this->db->transRollback();
            $this->session->setFlashdata("op_error", "Project failed to create");
            return $updated;
        }

        $this->db->transComplete();
        $config['title'] = "Project Update";
        if ($this->db->transStatus() === FALSE) {
            $this->db->transRollback();
            $config['log_text'] = "[ Project failed to update ]";
            $config['ref_link'] = 'erp/project/projectview/' . $project_id;
            $this->session->setFlashdata("op_error", "Project failed to update");
        } else {
            $updated = true;
            $this->db->transCommit();
            $config['log_text'] = "[ Project successfully updated ]";
            $config['ref_link'] = 'erp/project/projectview/' . $project_id;
            $this->session->setFlashdata("op_success", "Project successfully updated");
        }
        log_activity($config);
        return $updated;
    }

    public function get_c_project_by_id($project_id)
    {
        $query = "SELECT projects.project_id,projects.name,start_date,end_date,projects.cust_id,IFNULL(customers.name,'Not for customer') AS customer,type_id,GROUP_CONCAT(amenity_id) AS amenities,GROUP_CONCAT(member_id) AS members,projects.description,budget,projects.address,projects.city,projects.state,projects.country,projects.zipcode,units FROM projects JOIN project_amenity ON projects.project_id=project_amenity.project_id JOIN project_members ON projects.project_id=project_members.project_id LEFT JOIN customers ON projects.cust_id=customers.cust_id WHERE projects.project_id=$project_id";
        $result = $this->db->query($query)->getRow();
        return $result;
    }

    public function get_m_project_for_view($project_id)
    {
        $query = "SELECT project_id,projects.name AS project_name,customers.name AS customer,CONCAT(finished_goods.name,' ',finished_goods.code) AS product,start_date,end_date,budget,projects.description,status,projects.created_at FROM projects LEFT JOIN customers ON projects.cust_id=customers.cust_id LEFT JOIN finished_goods ON projects.related_id=finished_goods.finished_good_id WHERE project_id=$project_id ";
        $result = $this->db->query($query)->getRow();
        return $result;
    }

    public function get_c_project_for_view($project_id)
    {
        $query = "SELECT project_id,projects.name AS project_name,customers.name AS customer,start_date,end_date,budget,projects.description,status,projects.created_at,type_name,units,projects.address,projects.city,projects.state,projects.country,projects.zipcode FROM projects LEFT JOIN customers ON projects.cust_id=customers.cust_id JOIN propertytype ON projects.type_id=propertytype.type_id WHERE project_id=$project_id ";
        $result = $this->db->query($query)->getRow();
        return $result;
    }


    public function get_c_project_export($type)
    {
        $columns = array(
            "name" => "name",
            "start date" => "start_date",
            "end date" => "end_date",
            "budget" => "budget",
            "status" => "status"
        );
        $limit = $_GET['limit'];
        $offset = $_GET['offset'];
        $search = $_GET['search'] ?? "";
        $orderby = isset($_GET['orderby']) ? strtoupper($_GET['orderby']) : "";
        $ordercol = isset($_GET['ordercol']) ? $columns[$_GET['ordercol']] : "";
        $query = "";
        if ($type == "pdf") {
            $query = "SELECT projects.name,type_name,start_date,end_date,budget,CASE ";
            foreach ($this->project_status as $key => $value) {
                $query .= " WHEN status=$key THEN '$value' ";
            }
            $query .= " END AS status FROM projects JOIN propertytype ON projects.type_id=propertytype.type_id WHERE type=0 ";
        } else {
            $query = "SELECT projects.name,type_name,units,customers.name AS customer,start_date,end_date,budget,CASE ";
            foreach ($this->project_status as $key => $value) {
                $query .= " WHEN status=$key THEN '$value' ";
            }
            $query .= " END AS status,projects.address,projects.city,projects.state,projects.country,projects.zipcode,projects.description FROM projects JOIN propertytype ON projects.type_id=propertytype.type_id LEFT JOIN customers ON projects.cust_id=customers.cust_id WHERE type=0 ";
        }

        $where = true;

        if (!empty($search)) {
            if (!$where) {
                $query .= " WHERE name LIKE '%" . $search . "%' ";
                $where = true;
            } else {
                $query .= " AND name LIKE '%" . $search . "%' ";
            }
        }

        if (!empty($ordercol) && !empty($orderby)) {
            $query .= " ORDER BY " . $ordercol . " " . $orderby;
        }
        $result = $this->db->query($query)->getResultArray();
        return $result;
    }

    public function projectDelete($project_id)
    {
        try {
            $this->db->transStart();

            $this->db->table('projects')
                ->where('project_id', $project_id)
                ->delete();

            $this->db->table('project_testing')
                ->where('project_id', $project_id)
                ->delete();

            $attach_ids = $this->db->table('attachments')
                ->select('attach_id')
                ->where('related_id', $project_id)
                ->where('related_to', 'project')
                ->get()
                ->getResult();

            if (!empty($attach_ids)) {
                $attach_id = $attach_ids[0]->attach_id;
                $this->attachmentModel->delete_attachment("project", $attach_id);
            }

            $this->db->table('project_phase')
                ->where('project_id', $project_id)
                ->delete();

            $this->db->table('project_rawmaterials')
                ->where('project_id', $project_id)
                ->delete();
            $this->db->table('project_expense')
                ->where('project_id', $project_id)
                ->delete();

            $this->db->transComplete();

            if ($this->db->transStatus() === false) {
                throw new Exception("Transaction failed");
            }
            session()->setFlashdata("op_success", "Project and related data deleted successfully");
            return ['op_success' => true, 'message' => 'Project and related data deleted successfully'];
        } catch (Exception $e) {
            session()->setFlashdata("op_error", "Error deleting project and related data: Contact Support");
            return ['op_Error' => false, 'message' => 'Error deleting project and related data:Contact Support'];
        }
    }

    public function countStatus()
    {
        $query = "SELECT status, COUNT(status) as status_count FROM projects WHERE status IN (0, 1, 2, 3, 4) GROUP BY status";
        $result = $this->db->query($query)->getResultArray();

        $statusCounts = [];
        foreach ($result as $row) {
            $statusCounts[$row['status']] = $row['status_count'];
        }

        return $statusCounts;
    }
    public function leadsCountStatus()
    {
        $query = "SELECT status, COUNT(status) as status_count FROM leads WHERE status IN (0, 1, 2, 3, 4) GROUP BY status";
        $result = $this->db->query($query)->getResultArray();

        $statusCounts = [];
        foreach ($result as $row) {
            $statusCounts[$row['status']] = $row['status_count'];
        }

        return $statusCounts;
    }

    public function leads2CountStatus()
    {
        $query = "SELECT source_id, COUNT(source_id) as source_count FROM leads WHERE source_id IN (0, 1, 2, 3, 4) GROUP BY source_id";
        $result = $this->db->query($query)->getResultArray();

        $sourceCount = [];
        foreach ($result as $row) {
            $sourceCount[$row['source_id']] = $row['source_count'];
        }

        return $sourceCount;
    }

    public function getProject()
    {
        // "SELECT project_id, projects.cust_id,customers.name FROM projects JOIN customers ON projects.cust_id = customers.cust_id;";
        return $this->builder()
            ->select("project_id,name as projectname")
            ->get()
            ->getResultArray();
    }
    public function getProjectName($relatedOptionValue)
    {
        // "SELECT project_id, projects.cust_id,customers.name FROM projects JOIN customers ON projects.cust_id = customers.cust_id;";
        return $this->builder()
            ->select("name")
            ->where("project_id",$relatedOptionValue)
            ->get()
            ->getRow();
    }

    public function get_project_by_customer_id($id){
        return $this->builder()->select()->where("cust_id",$id)->get()->getResultArray();
    }

    public function getprojectbycustId($cust_id){
        return $this->builder()->where("cust_id",$cust_id)->get()->getResultArray();
    }
}
