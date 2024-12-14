<?php

namespace App\Models\Erp;

use CodeIgniter\Model;
use DateTime;
use App\libraries\Scheduler;
use App\libraries\Job\Notify;

class LeadsModel extends Model
{
    protected $table = 'leads';
    protected $primaryKey = 'lead_id';
    protected $allowedFields = [
        'source_id',
        'status',
        'assigned_to',
        'name',
        'position',
        'address',
        'city',
        'state',
        'country',
        'zip',
        'phone',
        'email',
        'website',
        'company',
        'description',
        'remarks',
        'created_at',
        'created_by'
    ];
    protected $useTimestamps = false;


    public $session;
    public $db;
    public $logger;
    public function __construct()
    {
        $this->session = \Config\Services::session();
        $this->logger = \Config\Services::logger();
        $this->db = \Config\Database::connect();
    }

    private $lead_status = array(
        0 => "OPEN",
        1 => "CONTACTED",
        2 => "WORKING",
        3 => "DISQUALIFIED",
        4 => "CUSTOMER"
    );

    private $lead_status_bg = array(
        0 => "st_violet",
        1 => "st_primary",
        2 => "st_dark",
        3 => "st_danger",
        4 => "st_success"
    );

    public function get_lead_status_bg_by_idx($idx)
    {
        return $this->lead_status_bg[$idx];
    }
    public function get_lead_status_by_idx($idx)
    {
        return $this->lead_status[$idx];
    }
    public function get_lead_status()
    {
        return $this->lead_status;
    }

    public function get_dtconfig_leads()
    {
        $config = array(
            "columnNames" => ["sno", "name", "company", "email", "position", "status", "source", "action"],
            "sortable" => [0, 1, 0, 1, 0, 1, 1, 0],
            "filters" => ["source", "status"]
        );
        return json_encode($config);
    }

    public function get_leads_datatable()
    {
        $columns = array(
            "name" => "name",
            "email" => "email",
            "status" => "status",
            "source" => "leads.source_id"
        );
        $limit = $_GET['limit'];
        $offset = $_GET['offset'];
        $search = $_GET['search'] ?? "";

        $orderby = isset($_GET['orderby']) ? strtoupper($_GET['orderby']) : "";
        $ordercol = isset($_GET['ordercol']) ? $columns[$_GET['ordercol']] : "";
        $query = "SELECT lead_id,name,email,position,company,status,source_name FROM leads LEFT JOIN lead_source ON leads.source_id=lead_source.source_id ";
        $count = "SELECT COUNT(lead_id) AS total FROM leads LEFT JOIN lead_source ON leads.source_id=lead_source.source_id ";

        $where = false;
        $filter_1_col = isset($_GET['source']) ? $columns['source'] : "";
        $filter_1_val = $_GET['source'];

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
                $query .= " WHERE ( company LIKE '%" . $search . "%' OR name LIKE '%" . $search . "%' OR email LIKE '%" . $search . "%' ) ";
                $count .= " WHERE ( company LIKE '%" . $search . "%' OR name LIKE '%" . $search . "%' OR email LIKE '%" . $search . "%' ) ";
                $where = true;
            } else {
                $query .= " AND ( company LIKE '%" . $search . "%' OR name LIKE '%" . $search . "%' OR email LIKE '%" . $search . "%' ) ";
                $count .= " AND ( company LIKE '%" . $search . "%' OR name LIKE '%" . $search . "%' OR email LIKE '%" . $search . "%' ) ";
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
                                <li><a target="_BLANK" href="' . url_to('erp.crm.leadview', $r['lead_id']) . '" title="View" class="bg-warning"><i class="fa fa-eye"></i> </a></li>
                                <li><a href="' . url_to('erp.crm.leadedit', $r['lead_id']) . '" title="Edit" class="bg-success"><i class="fa fa-pencil"></i> </a></li>
                                <li><a href="' . url_to('erp.crm.leaddelete', $r['lead_id']) . '" title="Delete" class="bg-danger del-confirm"><i class="fa fa-trash"></i> </a></li>
                            </ul>
                        </div>
                    </div>';
            $status = "<span class='st " . $this->lead_status_bg[$r['status']] . "' >" . $this->lead_status[$r['status']] . "</span>";
            array_push(
                $m_result,
                array(
                    $r['lead_id'],
                    $sno,
                    $r['name'],
                    $r['company'],
                    $r['email'],
                    $r['position'],
                    $status,
                    $r['source_name'],
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

    public function insert_lead($post)
    {
        $inserted = false;
        $source = $post["lead_source"];
        $status = $post["lead_status"];
        $assigned = $post["lead_assigned_to"];
        $name = $post["name"];
        $position = $post["position"];
        $address = $post["address"];
        $state = $post["state"];
        $city = $post["city"];
        $country = $post["country"];
        $zip = $post["zip"];
        $website = $post["website"];
        $company = $post["company"];
        $description = $post["description"];
        $remarks = $post["remarks"];
        $email = $post["email"];
        $phone = $post["phone"];

        $created_by = get_user_id();
        $query = "INSERT INTO leads(source_id,status,assigned_to,name,position,address,state,city,country,zip,website,company,description,remarks,email,phone,created_at,created_by) VALUES(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?) ";
        $this->db->query($query, array($source, $status, $assigned, $name, $position, $address, $state, $city, $country, $zip, $website, $company, $description, $remarks, $email, $phone, time(), $created_by));

        $id = $this->db->insertId();
        $config['title'] = "Lead Insert";
        if ($this->db->affectedRows() > 0) {
            $inserted = true;
            $config['log_text'] = "[ Lead successfully created ]";
            $config['ref_link'] = url_to('erp.crm.leadview', $id);
            $this->session->setFlashdata("op_success", "Lead successfully created");
        } else {
            $config['log_text'] = "[ Lead failed to create ]";
            $config['ref_link'] = url_to('erp.crm.leadview', $id);
            $this->session->setFlashdata("op_error", "Lead failed to create");
        }
        log_activity($config);
        return $inserted;
    }

    public function get_leads_export($type)
    {
        $columns = array(
            "name" => "leads.name",
            "email" => "leads.email",
            "status" => "leads.status",
            "source" => "leads.source_id"
        );
        $search = $_GET['search'] ?? "";

        $orderby = isset($_GET['orderby']) ? strtoupper($_GET['orderby']) : "";
        $ordercol = isset($_GET['ordercol']) ? $columns[$_GET['ordercol']] : "";
        $query = "";

        if ($type == "pdf") {
            $query = "SELECT leads.name,leads.email,leads.phone,leads.position,leads.company,
            CASE ";
            for ($i = 0; $i < count($this->lead_status); $i++) {
                $query .= " WHEN leads.status=" . $i . " THEN '" . $this->lead_status[$i] . " '";
            }
            $query .= " END AS status,lead_source.source_name FROM leads 
            LEFT JOIN lead_source ON leads.source_id=lead_source.source_id 
            JOIN erp_users ON leads.assigned_to=erp_users.user_id";
        } else {
            $query = "SELECT leads.name,leads.email,leads.phone,leads.position,leads.company,
            CASE ";
            for ($i = 0; $i < count($this->lead_status); $i++) {
                $query .= " WHEN leads.status=" . $i . " THEN '" . $this->lead_status[$i] . " '";
            }
            $query .= " END AS status,lead_source.source_name,erp_users.name AS assigned_to,leads.address,leads.city,
            leads.state,leads.country,leads.zip,leads.website FROM leads 
            LEFT JOIN lead_source ON leads.source_id=lead_source.source_id 
            JOIN erp_users ON leads.assigned_to=erp_users.user_id";
        }

        $where = false;
        $filter_1_col = isset($columns['source']) ? $columns['source'] : "";
        $filter_1_val = isset($_GET['source']) ? $_GET['source'] : "";

        $filter_2_col = isset($columns['status']) ? $columns['status'] : "";
        $filter_2_val = isset($_GET['status']) ? $_GET['status'] : "";

        $filter_3_col = isset($columns['name']) ? $columns['name'] : "";
        $filter_3_val = isset($_GET['name']) ? $_GET['name'] : "";

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

        if (!empty($filter_3_col) && $filter_3_val !== "") {
            if (!$where) {
                $query .= " WHERE " . $filter_3_col . "=" . $filter_3_val . " ";
                $where = true;
            } else {
                $query .= " AND " . $filter_3_col . "=" . $filter_3_val . " ";
            }
        }

        if (!empty($search)) {
            if (!$where) {
                $query .= " WHERE ( leads.company LIKE '%" . $search . "%' OR leads.name LIKE '%" . $search . "%' OR leads.email LIKE '%" . $search . "%' ) ";
                $where = true;
            } else {
                $query .= " AND ( leads.company LIKE '%" . $search . "%' OR leads.name LIKE '%" . $search . "%' OR leads.email LIKE '%" . $search . "%' ) ";
            }
        }

        if (!empty($ordercol) && !empty($orderby)) {
            $query .= " ORDER BY " . $ordercol . " " . $orderby;
        }

        $result = $this->db->query($query)->getResultArray();
        return $result;
    }



    public function get_lead_by_id($id)
    {
        $query = "SELECT leads.lead_id,lead_source.source_id,lead_source.source_name,leads.status, leads.assigned_to, erp_users.name AS assigned , leads.name, leads.position, leads.address, leads.city, leads.state,leads.country, leads.zip, leads.phone, leads.email, leads.website, leads.company, leads.description, leads.remarks FROM leads JOIN lead_source ON leads.source_id=lead_source.source_id JOIN erp_users ON leads.assigned_to=erp_users.user_id WHERE lead_id=$id";
        $result = $this->db->query($query)->getRow();
        return $result;
    }

    public function insert_update_lead_notify($id, $notify_id, $post)
    {
        if (!empty($notify_id)) {
            $this->update_lead_notify($notify_id, $id, $post);
        } else {
            $this->insert_lead_notify($id, $post);
        }
    }


    private function update_lead_notify($notify_id, $id, $post)
    {
        $updated = false;
        $notify_title = $post["notify_title"];
        $notify_desc = $post["notify_desc"];
        $notify_to = $post["notify_to"];
        $notify_at = $post["notify_at"];
        $notify_email = $post["notify_email"] ?? 0;
        $datetime = DateTime::createFromFormat("Y-m-d\TH:i", $notify_at);
        $timestamp = $datetime->getTimestamp();

        $jobresult = $this->db->query("SELECT job_id FROM notification WHERE notify_id=$notify_id")->getRow();
        $this->db->transBegin();
        $query = "UPDATE notification SET title=? , notify_text=? , user_id=? , notify_email=? , notify_at=? WHERE notify_id=$notify_id ";
        $this->db->query($query, array($notify_title, $notify_desc, $notify_to, $notify_email, $timestamp));
        if ($this->db->affectedrows() > 0) {
            $param['notify_id'] = $notify_id;
            $job = new Notify();
            $jobid = Scheduler::replaceOldJob($jobresult->job_id, $job, $param, $timestamp);
            if (empty($jobid)) {
                $this->db->transRollback();
            } else {
                $query = "UPDATE notification SET job_id=? WHERE notify_id=$notify_id";
                $this->db->query($query, array($jobid));
                if ($this->db->affectedrows() > 0) {
                    $updated = true;
                } else {
                    $this->db->transRollback();
                }
            }
        }

        $config['title'] = "Lead Notification Update";
        $this->db->transComplete();
        if ($this->db->transStatus() === FALSE) {
            $this->db->transRollback();
            $config['log_text'] = "[ Lead Notification failed to update ]";
            $config['ref_link'] = url_to('erp.crm.leadview', $id);
            session()->setFlashdata("op_error", "Lead Notification failed to update");
        } else {
            $updated = true;
            $this->db->transCommit();
            $config['log_text'] = "[ Lead Notification successfully updated ]";
            $config['ref_link'] = url_to('erp.crm.leadview', $id);
            session()->setFlashdata("op_success", "Lead Notification successfully updated");
        }
        log_activity($config);
        return $updated;
    }

    private function insert_lead_notify($id, $post)
    {
        $inserted = false;
        $notify_title = $post["notify_title"];
        $notify_desc = $post["notify_desc"];
        $notify_to = $post["notify_to"];
        $notify_at = $post["notify_at"];
        $notify_email = $post["notify_email"] ?? 0;
        $related_to = 'lead';
        $created_by = get_user_id();
        $datetime = DateTime::createFromFormat("Y-m-d\TH:i", $notify_at);
        $timestamp = $datetime->getTimestamp();

        $this->db->transBegin();
        $query = "INSERT INTO notification(title,notify_text,user_id,notify_email,notify_at,related_to,related_id,created_at,created_by) VALUES(?,?,?,?,?,?,?,?,?) ";
        $this->db->query($query, array($notify_title, $notify_desc, $notify_to, $notify_email, $timestamp, $related_to, $id, time(), $created_by));
        $notify_id = $this->db->insertID();
        if (empty($notify_id)) {
            session()->setFlashdata("op_error", "Lead Notification failed to create");
            return;
        }
        $param['notify_id'] = $notify_id;
        $job = new Notify();
        $job_id = Scheduler::dispatch($job, $param, $timestamp);
        if (empty($job_id)) {
            $this->db->transRollback();
        } else {
            $query = "UPDATE notification SET job_id=? WHERE notify_id=$notify_id";
            $this->db->query($query, array($job_id));
            if ($this->db->affectedrows() > 0) {
                $inserted = true;
            } else {
                $this->db->transRollback();
            }
        }

        $config['title'] = "Lead Notification Insert";
        $this->db->transComplete();
        if ($this->db->transStatus() === FALSE) {
            $inserted = false;
            $this->db->transRollback();
            $config['log_text'] = "[ Lead Notification failed to create ]";
            $config['ref_link'] = url_to('erp.crm.leadview', $id);
            session()->setFlashdata("op_error", "Lead Notification failed to create");
        } else {
            $this->db->transCommit();
            $config['log_text'] = "[ Lead Notification successfully created ]";
            $config['ref_link'] = url_to('erp.crm.leadview', $id);
            session()->setFlashdata("op_success", "Lead Notification successfully created");
        }
        log_activity($config);
        return $inserted;
    }


    public function convert_lead_customer($lead_id)
    {
        $query = "SELECT * FROM leads WHERE lead_id=$lead_id";
        $lead = $this->db->query($query)->getRow();
        $inserted = false;
        if (empty($lead)) {
            $this->session->setFlashdata("op_error", "Customer failed to create");
            return $inserted;
        }
        $check = $this->db->query("SELECT email FROM customers WHERE email='$lead->email' LIMIT 1")->getRow();
        if (!empty($check)) {
            $this->session->setFlashdata("op_error", "Duplicate Email ID");
            return $inserted;
        }
        $name = $lead->name;
        $position = $lead->position;
        $address = $lead->address;
        $state = $lead->state;
        $city = $lead->city;
        $country = $lead->country;
        $zip = $lead->zip;
        $website = $lead->website;
        $company = $lead->company;
        $description = $lead->description;
        $remarks = $lead->remarks;
        $email = $lead->email;
        $phone = $lead->phone;
        $fax_number = '';
        $office_number = '';
        $gst = '';

        $created_by = get_user_id();
        $this->db->transBegin();
        $query = "INSERT INTO customers(name,position,address,state,city,country,zip,website,company,description,remarks,email,phone,fax_num,office_num,gst,created_at,created_by) VALUES(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?) ";
        $this->db->query($query, array($name, $position, $address, $state, $city, $country, $zip, $website, $company, $description, $remarks, $email, $phone, $fax_number, $office_number, $gst, time(), $created_by));
        $id = $this->db->insertId();

        if (empty($id)) {
            $this->session->setFlashdata("op_error", "Customer failed to create");
            $this->db->transRollback();
            return $inserted;
        }

        if (!$this->update_customer_groups($id)) {
            $this->session->setFlashdata("op_error", "Customer failed to create");
            $this->db->transRollback();
            return $inserted;
        }

        if (!$this->update_customer_cfv($id)) {
            $this->session->setFlashdata("op_error", "Customer failed to create");
            $this->db->transRollback();
            return $inserted;
        }

        $config['title'] = "Customer Insert";
        $this->db->transComplete();
        if ($this->db->transStatus() === FALSE) {
            $this->db->transRollback();
            $config['log_text'] = "[ Customer failed to create ]";
            $config['ref_link'] = "";
            $this->session->setFlashdata("op_error", "Customer failed to create");
        } else {
            $this->db->transCommit();
            $inserted = true;
            $config['log_text'] = "[ Customer successfully created ]";
            $config['ref_link'] = url_to('erp.crm.customerview', $id);
            $this->session->setFlashdata("op_success", "Customer successfully created");
            $this->db->query("UPDATE leads SET status=4 WHERE lead_id=$lead_id");
        }
        log_activity($config);
        return $inserted;
    }

    private function update_customer_cfv($id)
    {
        $updated = true;
        $query = "DELETE cfv FROM custom_field_values cfv INNER JOIN custom_fields cf ON cfv.cf_id=cf.cf_id WHERE cf.field_related_to='customer' AND cfv.related_id=$id";
        $this->db->query($query);
        $checkbox = array();
        $query = "INSERT INTO custom_field_values(cf_id,related_id,field_value) VALUES(?,?,?) ";
        foreach ($_POST as $key => $value) {
            if (stripos($key, "cf_checkbox_") === 0) {
                $checkbox[$key] = $value;
            } else if (stripos($key, "cf_") === 0) {
                $cf_id = substr($key, strlen("cf_"));
                $this->db->query($query, array($cf_id, $id, $value));
                if (empty($this->db->insertId())) {
                    $updated = false;
                    break;
                }
            }
        }
        $checkbox_counter = intval($_POST["customfield_chkbx_counter"]);
        for ($i = 0; $i <= $checkbox_counter; $i++) {
            $values = array();
            $cf_id = 0;
            foreach ($checkbox as $key => $value) {
                if (stripos($key, "cf_checkbox_" . $i . "_") === 0) {
                    array_push($values, $value);
                    $cf_id = explode("_", $key)[3];
                    //$cf_id=substr($key,strlen("cf_checkbox_".$i."_"),stripos($key,"_",strlen("cf_checkbox_".$i."_")+1));
                }
            }
            if (!empty($cf_id)) {
                $this->db->query($query, array($cf_id, $id, implode(",", $values)));
                if (empty($this->db->insertId())) {
                    $updated = false;
                    break;
                }
            }
        }

        $config['title']="Contract Attachment Added";
        $config['ref_link']="";
        $config['log_text']="[ Added attachment in Contract ]";
        log_activity($config);
        return $updated;
    }

    private function update_customer_groups($id)
    {
        $updated = true;
        $query = "DELETE g2 FROM erp_groups_map g2 INNER JOIN erp_groups g1 ON g2.group_id=g1.group_id WHERE g1.related_to='customer' AND g2.related_id=$id";
        $this->db->query($query);
        $groups = explode(",", $_POST["groups"]);
        $query = "INSERT INTO erp_groups_map(group_id,related_id) VALUES(?,?) ";
        foreach ($groups as $group) {
            $this->db->query($query, array($group, $id));
            if (empty($this->db->insertId())) {
                $updated = false;
                break;
            }
        }
        return $updated;
    }


    public function get_dtconfig_customers()
    {
        $config = array(
            "columnNames" => ["sno", "name", "company", "email", "position", "phone", "group", "action"],
            "sortable" => [0, 1, 0, 1, 0, 0, 0, 0],
            "filters" => ["group"]
        );
        return json_encode($config);
    }

    public function update_lead($id, $post)
    {
        $updated = false;
        $source = $post["lead_source"];
        $status = $post["lead_status"];
        $assigned = $post["lead_assigned_to"];
        $name = $post["name"];
        $position = $post["position"];
        $address = $post["address"];
        $state = $post["state"];
        $city = $post["city"];
        $country = $post["country"];
        $zip = $post["zip"];
        $website = $post["website"];
        $company = $post["company"];
        $description = $post["description"];
        $remarks = $post["remarks"];
        $email = $post["email"];
        $phone = $post["phone"];

        $query = "UPDATE leads SET source_id=? , status=? , assigned_to=? , name=? , position=? , address=? , state=? , city=? , country=? , zip=? , website=? , company=? , description=? , remarks=? , email=? , phone=? WHERE lead_id=$id ";
        $this->db->query($query, array($source, $status, $assigned, $name, $position, $address, $state, $city, $country, $zip, $website, $company, $description, $remarks, $email, $phone));

        $config['title'] = "Lead Update";
        if ($this->db->affectedRows() > 0) {
            $updated = true;
            $config['log_text'] = "[ Lead successfully updated ]";
            $config['ref_link'] = url_to('erp.crm.leadview', $id);
            $this->session->setFlashdata("op_success", "Lead successfully updated");
        } else {
            $config['log_text'] = "[ Lead failed to update ]";
            $config['ref_link'] = url_to('erp.crm.leadview', $id);
            $this->session->setFlashdata("op_error", "Lead failed to update");
        }
        log_activity($config);
        return $updated;
    }

    public function getLeadData()
    {
        return $this->builder()->select("lead_id,name")->get()->getResultArray();
    }

    public function getLeadDataName($relatedOptionValue)
    {
        return $this->builder()->select("name")->where("lead_id", $relatedOptionValue)->get()->getRow();
    }

    //Leads Delete

    public function get_crm_leads_by_id($lead_id)
    {
        return $this->db->table('leads')->select()->where('lead_id', $lead_id)->get()->getRow();
    }

    public function delete_leads($lead_id)
    {
        $deleted = false;
        $lead = $this->get_crm_leads_by_id($lead_id);
        $this->builder()->where('lead_id', $lead_id)->delete();

        if ($this->db->affectedRows() > 0) {
            $deleted = true;


            $config = [
                'title' => 'Leads Deletion',
                'log_text' => "[ Leads successfully deleted ]",
                'ref_link' => 'erp.crm.leaddelete' . $lead_id,
                'additional_info' => json_encode($lead),
            ];
            log_activity($config);
        } else {
            $config = [
                'title' => 'Leads Deletion',
                'log_text' => "[ Leads failed to delete ]",
                'ref_link' => '' . $lead_id,
            ];
            log_activity($config);
        }
        return $deleted;
    }

    public function leadsSourceCountStatus()
    {
        $query = "SELECT leads.source_id, lead_source.source_name, COUNT(leads.source_id) as source_count FROM leads JOIN lead_source ON leads.source_id = lead_source.source_id GROUP BY leads.source_id, lead_source.source_name";
        $result = $this->db->query($query)->getResultArray();

        $sourceCount = [];
        foreach ($result as $row) {
            $sourceCount[] = [
                'source_id' => $row['source_id'],
                'source_name' => $row['source_name'],
                'source_count' => $row['source_count']
            ];
        }

        return json_encode($sourceCount);
    }

    public function leadReportDashboard()
    {
        $leadContectedCount = $this->leadContectedCount();
        $leadTotalCount = $this->leadtotal();
        $totalRecode = [
            "total" => $leadTotalCount,
            "contacted_total" => $leadContectedCount,
        ];
        return $totalRecode;
    }

    private function leadtotal()
    {
        $query = "SELECT COUNT(lead_id) as total FROM `leads`";
        $rusult = $this->db->query($query)->getResult();
        return $rusult;
    }

    private function leadContectedCount()
    {
        $query = "SELECT COUNT(status) as count FROM `leads` WHERE status= 1";
        $result = $this->db->query($query)->getResult();
        return $result;
    }
    
    public function get_for_goals_with_count($assigned_person_id = 0, $start_date, $end_date) 
    {
        $builder = $this->db->table('leads')
        ->where('created_at >=', strtotime($start_date))
        ->where('created_at <=', strtotime($end_date))
        ->where('status =', 4);
        if(!empty($assigned_person_id)){
            $builder->where('assigned_to', $assigned_person_id);
        }
        
        $results = $builder->get()->getResultArray();
        $count = 0;
        if(!empty($results)){
            $count = count($results);
        }
    
        return ['count' => $count, 'results' => $results];
    }
    
    
}
