<?php

namespace App\Models\Erp;

use CodeIgniter\Model;
use App\Libraries\Job\Notify;
use App\Libraries\Scheduler;
use App\Models\Erp\GoalsModel;
use DateTime;

class NotificationModel extends Model
{
    protected $table            = 'notification';
    protected $primaryKey       = 'notify_id';
    protected $allowedFields    = [
        'title',
        'notify_text',
        'status',
        'user_id',
        'notify_email',
        'notify_at',
        'created_by',
        'related_to',
        'related_id',
        'job_id',
        'related_base_url'
    ];

    // Dates
    protected $useTimestamps = false;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    protected $session;
    protected $db;

    public function __construct()
    {
        $this->session = \Config\Services::session();
        $this->db = \Config\Database::connect();
    }


    // Get Notification
    public function getNotifyForUser($user_id)
    {
        // $thequery = "select * from notification where status = 0 and user_id =".$user_id;
        // return $this->db->query($thequery)->getResultArray();

        return $this->builder()->select('notification.* , erp_users.name, erp_users.last_name')
            ->where('notification.status', 0)
            ->where('notification.user_id', $user_id)->join("erp_users", "notification.created_by = erp_users.user_id")->orderBy('created_at', 'DESC')
            ->limit(3)->get()->getResultArray();
    }


    // Notify Crud Estimates
    public function insert_update_estimatenotify($estimate_id)
    {
        $notify_id = $_POST["notify_id"] ?? 0;
        if (empty($notify_id)) {
            $this->insert_estimatenotify($estimate_id);
        } else {
            $this->update_estimatenotify($estimate_id, $notify_id);
        }
    }

    private function insert_estimatenotify($estimate_id)
    {
        $notify_title = $_POST["notify_title"];
        $notify_desc = $_POST["notify_desc"];
        $notify_to = $_POST["notify_to"];
        $notify_at = $_POST["notify_at"];
        // $notify_base_link = $_POST["notify_base_link"];
        $notify_email = $_POST["notify_email"] ?? 0;
        $related_to = 'estimate';
        $created_by = get_user_id();
        $datetime = DateTime::createFromFormat("Y-m-d\TH:i", $notify_at);
        $timestamp = $datetime->getTimestamp();

        $this->db->transBegin();
        $query = "INSERT INTO notification(title,notify_text,user_id,notify_email,notify_at,related_to,related_id,created_at,created_by,related_base_url) VALUES(?,?,?,?,?,?,?,?,?,?) ";
        $this->db->query($query, array($notify_title, $notify_desc, $notify_to, $notify_email, $timestamp, $related_to, $estimate_id, time(), $created_by, "erp.sale.estimates.view"));
        $notify_id = $this->db->insertID();
        if (empty($notify_id)) {
            $this->session->setFlashdata("op_error", "Notification failed to create");
            return;
        }

        $param['notify_id'] = $notify_id;
        $job = new Notify;
        $job_id = Scheduler::dispatch($job, $param, $timestamp);
        if (empty($job_id)) {
            $this->db->transRollback();
        } else {
            $query = "UPDATE notification SET job_id=? WHERE notify_id=$notify_id";
            $this->db->query($query, array($job_id));
            if ($this->db->affectedRows() > 0) {
                $inserted = true;
            } else {
                $this->db->transRollback();
            }
        }

        $config['title'] = "Estimate Notification Insert";
        $this->db->transComplete();
        if ($this->db->transStatus() === FALSE) {
            $inserted = false;
            $this->db->transRollback();
            $config['log_text'] = "[ Estimate Notification failed to create ]";
            $config['ref_link'] = 'erp/sale/estimateview/' . $estimate_id;
            $this->session->setFlashdata("op_error", "Estimate Notification failed to create");
        } else {
            $this->db->transCommit();
            $config['log_text'] = "[ Estimate Notification successfully created ]";
            $config['ref_link'] = 'erp/sale/estimateview/' . $estimate_id;
            $this->session->setFlashdata("op_success", "Estimate Notification successfully created");
        }
        log_activity($config);
    }

    private function update_estimatenotify($estimate_id, $notify_id)
    {
        $notify_title = $_POST["notify_title"];
        $notify_desc = $_POST["notify_desc"];
        $notify_to = $_POST["notify_to"];
        $notify_at = $_POST["notify_at"];
        $notify_email = $_POST["notify_email"] ?? 0;
        $datetime = DateTime::createFromFormat("Y-m-d\TH:i", $notify_at);
        $timestamp = $datetime->getTimestamp();
        $jobresult = $this->db->query("SELECT job_id FROM notification WHERE notify_id=$notify_id")->getRow();
        $this->db->transBegin();
        $query = "UPDATE notification SET title=? , notify_text=? , user_id=? , notify_email=? , notify_at=? WHERE notify_id=$notify_id ";
        $this->db->query($query, array($notify_title, $notify_desc, $notify_to, $notify_email, $timestamp));

        if ($this->db->affectedRows() > 0) {
            $param['notify_id'] = $notify_id;
            $job = new Notify();
            $jobid = Scheduler::replaceOldJob($jobresult->job_id, $job, $param, $timestamp);
            if (empty($jobid)) {
                $this->db->transRollback();
            } else {
                $query = "UPDATE notification SET job_id=? WHERE notify_id=$notify_id";
                $this->db->query($query, array($jobid));
                if ($this->db->affectedRows() > 0) {
                    $updated = true;
                } else {
                    $this->db->transRollback();
                }
            }
        }

        $config['title'] = "Estimate Notification Update";
        $this->db->transComplete();
        if ($this->db->transStatus() === FALSE) {
            $inserted = false;
            $this->db->transRollback();
            $config['log_text'] = "[ Estimate Notification failed to update ]";
            $config['ref_link'] = 'erp/sale/estimateview/' . $estimate_id;
            $this->session->setFlashdata("op_error", "Estimate Notification failed to update");
        } else {
            $this->db->transCommit();
            $config['log_text'] = "[ Estimate Notification successfully updated ]";
            $config['ref_link'] = 'erp/sale/estimateview/' . $estimate_id;
            $this->session->setFlashdata("op_success", "Estimate Notification successfully updated");
        }
        log_activity($config);
    }

    public function delete_estimate_notify($estimate_id, $notify_id)
    {
        $jobresult = $this->db->query("SELECT job_id FROM notification WHERE notify_id=$notify_id")->getRow();
        $config['title'] = "Estimate Notification Delete";
        if (!empty($jobresult)) {
            $jobid = $jobresult->job_id;
            $query = "DELETE FROM notification WHERE notify_id=$notify_id ";
            $this->db->query($query);
            Scheduler::safeDelete($jobid);
            $config['log_text'] = "[ Estimate Notification successfully deleted ]";
            $config['ref_link'] = 'erp/sale/estimateview/' . $estimate_id;
            $this->session->setFlashdata("op_success", "Estimate Notification successfully deleted");
        } else {
            $config['log_text'] = "[ Estimate Notification failed to delete ]";
            $config['ref_link'] = 'erp/sale/estimateview/' . $estimate_id;
            $this->session->setFlashdata("op_success", "Estimate Notification failed to delete");
        }
        log_activity($config);
    }


    // Quotation Notification
    public function insert_update_quotationnotify($quote_id)
    {
        $notify_id = $_POST["notify_id"] ?? 0;
        if (empty($notify_id)) {
            $this->insert_quotationnotify($quote_id);
        } else {
            $this->update_quotationnotify($quote_id, $notify_id);
        }
    }

    private function insert_quotationnotify($quote_id)
    {
        $notify_title = $_POST["notify_title"];
        $notify_desc = $_POST["notify_desc"];
        $notify_to = $_POST["notify_to"];
        $notify_at = $_POST["notify_at"];
        $notify_email = $_POST["notify_email"] ?? 0;
        $related_to = 'quotation';
        $created_by = get_user_id();
        $datetime = DateTime::createFromFormat("Y-m-d\TH:i", $notify_at);
        $timestamp = $datetime->getTimestamp();

        $this->db->transBegin();
        $query = "INSERT INTO notification(title,notify_text,user_id,notify_email,notify_at,related_to,related_id,created_at,created_by,related_base_url) VALUES(?,?,?,?,?,?,?,?,?,?) ";
        $this->db->query($query, array($notify_title, $notify_desc, $notify_to, $notify_email, $timestamp, $related_to, $quote_id, time(), $created_by, "erp.sale.quotations.view"));
        $notify_id = $this->db->insertID();
        if (empty($notify_id)) {
            $this->session->setFlashdata("op_error", "Notification failed to create");
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
            if ($this->db->affectedRows() > 0) {
                $inserted = true;
            } else {
                $this->db->transRollback();
            }
        }

        $config['title'] = "Quotation Notification Insert";
        $this->db->transComplete();
        if ($this->db->transStatus() === FALSE) {
            $inserted = false;
            $this->db->transRollback();
            $config['log_text'] = "[ Quotation Notification failed to create ]";
            $config['ref_link'] = url_to('erp.sale.quotations.view', $quote_id);
            $this->session->setFlashdata("op_error", "Quotation Notification failed to create");
        } else {
            $this->db->transCommit();
            $config['log_text'] = "[ Quotation Notification successfully created ]";
            $config['ref_link'] = url_to('erp.sale.quotations.view', $quote_id);
            $this->session->setFlashdata("op_success", "Quotation Notification successfully created");
        }
        log_activity($config);
    }

    private function update_quotationnotify($quote_id, $notify_id)
    {
        $notify_title = $_POST["notify_title"];
        $notify_desc = $_POST["notify_desc"];
        $notify_to = $_POST["notify_to"];
        $notify_at = $_POST["notify_at"];
        $notify_email = $_POST["notify_email"] ?? 0;
        $datetime = DateTime::createFromFormat("Y-m-d\TH:i", $notify_at);
        $timestamp = $datetime->getTimestamp();
        $jobresult = $this->db->query("SELECT job_id FROM notification WHERE notify_id=$notify_id")->getRow();
        $this->db->transBegin();
        $query = "UPDATE notification SET title=? , notify_text=? , user_id=? , notify_email=? , notify_at=? WHERE notify_id=$notify_id ";
        $this->db->query($query, array($notify_title, $notify_desc, $notify_to, $notify_email, $timestamp));

        if ($this->db->affectedRows() > 0) {
            $param['notify_id'] = $notify_id;
            $job = new Notify();
            $jobid = Scheduler::replaceOldJob($jobresult->job_id, $job, $param, $timestamp);
            if (empty($jobid)) {
                $this->db->transRollback();
            } else {
                $query = "UPDATE notification SET job_id=? WHERE notify_id=$notify_id";
                $this->db->query($query, array($jobid));
                if ($this->db->affectedRows() > 0) {
                    $updated = true;
                } else {
                    $this->db->transRollback();
                }
            }
        }

        $config['title'] = "Quotation Notification Update";
        $this->db->transComplete();
        if ($this->db->transStatus() === FALSE) {
            $inserted = false;
            $this->db->transRollback();
            $config['log_text'] = "[ Quotation Notification failed to update ]";
            $config['ref_link'] = 'erp/sale/quotationview/' . $quote_id;
            $this->session->setFlashdata("op_error", "Quotation Notification failed to update");
        } else {
            $this->db->transCommit();
            $config['log_text'] = "[ Quotation Notification successfully updated ]";
            $config['ref_link'] = 'erp/sale/quotationview/' . $quote_id;
            $this->session->setFlashdata("op_success", "Quotation Notification successfully updated");
        }
        log_activity($config);
    }

    public function delete_quotation_notify($quote_id, $notify_id)
    {
        $jobresult = $this->db->query("SELECT job_id FROM notification WHERE notify_id=$notify_id")->getRow();
        $config['title'] = "Quotation Notification Delete";
        if (!empty($jobresult)) {
            $jobid = $jobresult->job_id;
            $query = "DELETE FROM notification WHERE notify_id=$notify_id ";
            $this->db->query($query);
            Scheduler::safeDelete($jobid);
            $config['log_text'] = "[ Quotation Notification successfully deleted ]";
            $config['ref_link'] = 'erp/sale/quotationview/' . $quote_id;
            $this->session->setFlashdata("op_success", "Quotation Notification successfully deleted");
        } else {
            $config['log_text'] = "[ Quotation Notification failed to delete ]";
            $config['ref_link'] = 'erp/sale/quotationview/' . $quote_id;
            $this->session->setFlashdata("op_success", "Quotation Notification failed to delete");
        }
        log_activity($config);
    }

    public function get_quotationnotify_export($type)
    {
        $quote_id = $_GET["quoteid"];
        $columns = array(
            "notify at" => "notify_at",
            "title" => "title"
        );
        $limit = $_GET['limit'];
        $offset = $_GET['offset'];
        $search = $_GET['search'] ?? "";
        $orderby = isset($_GET['orderby']) ? strtoupper($_GET['orderby']) : "";
        $ordercol = isset($_GET['ordercol']) ? $columns[$_GET['ordercol']] : "";

        $query = "SELECT title,notify_text,FROM_UNIXTIME(notify_at,'%Y-%m-%d %h:%i:%s') AS notify_at,erp_users.name AS notify_to,CASE WHEN status=1 THEN 'Yes' ELSE 'No' END AS notified FROM notification JOIN erp_users ON notification.user_id=erp_users.user_id WHERE related_to='quotation' AND related_id=$quote_id ";
        $where = true;

        if (!empty($search)) {
            if (!$where) {
                $query .= " WHERE ( title LIKE '%" . $search . "%' OR erp_users.name LIKE '%" . $search . "%' ) ";
                $where = true;
            } else {
                $query .= " AND ( title LIKE '%" . $search . "%' OR erp_users.name LIKE '%" . $search . "%' ) ";
            }
        }

        if (!empty($ordercol) && !empty($orderby)) {
            $query .= " ORDER BY " . $ordercol . " " . $orderby;
        }
        $query .= " LIMIT $offset,$limit ";
        $result = $this->db->query($query)->getResultArray();
        return $result;
    }

    public function get_dtconfig_notify()
    {
        $config = array(
            "columnNames" => ["sno", "title", "notify at", "notify to", "notified", "action"],
            "sortable" => [0, 0, 1, 0, 1, 0]
        );
        return json_encode($config);
    }

    public function ajax_fetch_notify($notify_id)
    {
        $response = array();
        $query = "SELECT notify_id,notify_email,title,notify_text,notify_at,erp_users.user_id,erp_users.name FROM notification JOIN erp_users ON notification.user_id=erp_users.user_id WHERE notify_id=$notify_id";
        $result = $this->db->query($query)->getRow();
        if (!empty($result)) {
            $response['error'] = 0;
            $result->notify_at = date("Y-m-d\TH:i", $result->notify_at);
            $response['data'] = $result;
        } else {
            $response['error'] = 1;
        }
        header("Content-Type:application/json");
        echo json_encode($response);
        exit();
    }

    // public function insert_update_lead_notify($id,$notify_id,$post){
    //     if(!empty($notify_id)){
    //         $this->update_lead_notify($notify_id,$id,$post);
    //     }else{
    //         $this->insert_lead_notify($id,$post);
    //     }
    // }

    // private function update_lead_notify($notify_id,$id,$post){
    //     $updated=false;
    //     $notify_title=$post["notify_title"];
    //     $notify_desc=$post["notify_desc"];
    //     $notify_to=$post["notify_to"];
    //     $notify_at=$post["notify_at"];
    //     $notify_email=$post["notify_email"]??0;
    //     $datetime=DateTime::createFromFormat("Y-m-d\TH:i",$notify_at);
    //     $timestamp=$datetime->getTimestamp();

    //     $jobresult=$this->db->query("SELECT job_id FROM notification WHERE notify_id=$notify_id")->getRow();
    //     $this->db->transBegin();
    //     $query="UPDATE notification SET title=? , notify_text=? , user_id=? , notify_email=? , notify_at=? WHERE notify_id=$notify_id ";
    //     $this->db->query($query,array($notify_title,$notify_desc,$notify_to,$notify_email,$timestamp));
    //     if($this->db->affectedRows() > 0){
    //         $param['notify_id']=$notify_id;
    //         $job=new Notify();
    //         $jobid=Scheduler::replaceOldJob($jobresult->job_id,$job,$param,$timestamp);
    //         if(empty($jobid)){
    //             $this->db->transRollback();
    //         }else{
    //             $query="UPDATE notification SET job_id=? WHERE notify_id=$notify_id";
    //             $this->db->query($query,array($jobid));
    //             if($this->db->affectedRows() > 0){
    //                 $updated=true;
    //             }else{
    //                 $this->db->transRollback();
    //             }
    //         }
    //     }

    //     $config['title']="Lead Notification Update";
    //     $this->db->transComplete();
    //     if($this->db->transStatus()===FALSE){
    //         $this->db->transRollback();
    //         $config['log_text']="[ Lead Notification failed to update ]";
    //         $config['ref_link']=url_to('erp.crm.leadview', $id);
    //         $this->session->setFlashdata("op_error","Lead Notification failed to update");
    //     }else{
    //         $updated=true;
    //         $this->db->transCommit();
    //         $config['log_text']="[ Lead Notification successfully updated ]";
    //         $config['ref_link']=url_to('erp.crm.leadview', $id);
    //         $this->session->setFlashdata("op_success","Lead Notification successfully updated");
    //     }
    //     log_activity($config);
    //     return $updated;
    // }

    // private function insert_lead_notify($id,$post){
    //     $inserted=false;
    //     $notify_title=$post["notify_title"];
    //     $notify_desc=$post["notify_desc"];
    //     $notify_to=$post["notify_to"];
    //     $notify_at=$post["notify_at"];
    //     $notify_email=$post["notify_email"]??0;
    //     $related_to='lead';
    //     $created_by=get_user_id();
    //     $datetime=DateTime::createFromFormat("Y-m-d\TH:i",$notify_at);
    //     $timestamp=$datetime->getTimestamp();

    //     $this->db->transBegin();
    //     $query="INSERT INTO notification(title,notify_text,user_id,notify_email,notify_at,related_to,related_id,created_at,created_by) VALUES(?,?,?,?,?,?,?,?,?) ";
    //     $this->db->query($query,array($notify_title,$notify_desc,$notify_to,$notify_email,$timestamp,$related_to,$id,time(),$created_by));
    //     $notify_id=$this->db->insertId();
    //     if(empty($notify_id)){
    //         $this->session->setFlashdata("op_error","Lead Notification failed to create");
    //         return;
    //     }
    //     $param['notify_id']=$notify_id;
    //     $job=new Notify();
    //     $job_id=Scheduler::dispatch($job,$param,$timestamp);
    //     if(empty($job_id)){
    //         $this->db->transRollback();
    //     }else{
    //         $query="UPDATE notification SET job_id=? WHERE notify_id=$notify_id";
    //         $this->db->query($query,array($job_id));
    //         if($this->db->affectedRows() > 0){
    //             $inserted=true;
    //         }else{
    //             $this->db->transRollback();
    //         }
    //     }

    //     $config['title']="Lead Notification Insert";
    //     $this->db->transComplete();
    //     if($this->db->transStatus()===FALSE){
    //         $inserted=false;
    //         $this->db->transRollback();
    //         $config['log_text']="[ Lead Notification failed to create ]";
    //         $config['ref_link']=url_to('erp.crm.leadview', $id);
    //         $this->session->setFlashdata("op_error","Lead Notification failed to create");
    //     }else{
    //         $this->db->transCommit();
    //         $inserted=true;
    //         $config['log_text']="[ Lead Notification successfully created ]";
    //         $config['ref_link']=url_to('erp.crm.leadview', $id);
    //         $this->session->setFlashdata("op_success","Lead Notification successfully created");
    //     }
    //     log_activity($config);
    //     return $inserted;
    // }
    public function insert_update_lead_notify($id)
    {
        $notify_id = $_POST["notify_id"];
        if (!empty($notify_id)) {
            $this->update_lead_notify($notify_id, $id);
        } else {
            $this->insert_lead_notify($id);
        }
    }

    private function update_lead_notify($notify_id, $id)
    {
        $updated = false;
        $notify_title = $_POST["notify_title"];
        $notify_desc = $_POST["notify_desc"];
        $notify_to = $_POST["notify_to"];
        $notify_at = $_POST["notify_at"];
        $notify_email = $_POST["notify_email"] ?? 0;
        $datetime = DateTime::createFromFormat("Y-m-d\TH:i", $notify_at);
        $timestamp = $datetime->getTimestamp();

        $jobresult = $this->db->query("SELECT job_id FROM notification WHERE notify_id=$notify_id")->getRow();
        $this->db->transBegin();
        $query = "UPDATE notification SET title=? , notify_text=? , user_id=? , notify_email=? , notify_at=? WHERE notify_id=$notify_id ";
        $this->db->query($query, array($notify_title, $notify_desc, $notify_to, $notify_email, $timestamp));
        if ($this->db->affectedRows() > 0) {
            $param['notify_id'] = $notify_id;
            $job = new Notify();
            $jobid = Scheduler::replaceOldJob($jobresult->job_id, $job, $param, $timestamp);
            if (empty($jobid)) {
                $this->db->transRollback();
            } else {
                $query = "UPDATE notification SET job_id=? WHERE notify_id=$notify_id";
                $this->db->query($query, array($jobid));
                if ($this->db->affectedRows() > 0) {
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
            $this->session->setFlashdata("op_error", "Lead Notification failed to update");
        } else {
            $updated = true;
            $this->db->transCommit();
            $config['log_text'] = "[ Lead Notification successfully updated ]";
            $config['ref_link'] = url_to('erp.crm.leadview', $id);
            $this->session->setFlashdata("op_success", "Lead Notification successfully updated");
        }
        log_activity($config);
        return $updated;
    }

    private function insert_lead_notify($id)
    {
        $inserted = false;
        $notify_title = $_POST["notify_title"];
        $notify_desc = $_POST["notify_desc"];
        $notify_to = $_POST["notify_to"];
        $notify_at = $_POST["notify_at"];
        $notify_email = $_POST["notify_email"] ?? 0;
        $related_to = 'lead';
        $created_by = get_user_id();
        $datetime = DateTime::createFromFormat("Y-m-d\TH:i", $notify_at);
        $timestamp = $datetime->getTimestamp();

        $this->db->transBegin();
        $query = "INSERT INTO notification(title,notify_text,user_id,notify_email,notify_at,related_to,related_id,created_at,created_by,related_base_url) VALUES(?,?,?,?,?,?,?,?,?,?) ";
        $this->db->query($query, array($notify_title, $notify_desc, $notify_to, $notify_email, $timestamp, $related_to, $id, time(), $created_by, "erp.crm.leadview"));
        $notify_id = $this->db->insertId();
        if (empty($notify_id)) {
            $this->session->setFlashdata("op_error", "Lead Notification failed to create");
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
            if ($this->db->affectedRows() > 0) {
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
            $this->session->setFlashdata("op_error", "Lead Notification failed to create");
        } else {
            $this->db->transCommit();
            $config['log_text'] = "[ Lead Notification successfully created ]";
            $config['ref_link'] = url_to('erp.crm.leadview', $id);
            $this->session->setFlashdata("op_success", "Lead Notification successfully created");
        }
        log_activity($config);
        return $inserted;
    }

    public function delete_lead_notify($leadid, $notify_id)
    {
        $jobresult = $this->db->query("SELECT job_id FROM notification WHERE notify_id=$notify_id")->getRow();
        $config['title'] = "Lead Notification Delete";
        if (!empty($jobresult)) {
            $jobid = $jobresult->job_id;
            $query = "DELETE FROM notification WHERE notify_id=$notify_id ";
            $this->db->query($query);
            Scheduler::safeDelete($jobid);
            $config['log_text'] = "[ Lead Notification successfully deleted ]";
            $config['ref_link'] = url_to('erp.crm.leadview', $leadid);
            $this->session->setFlashdata("op_success", "Lead Notification successfully deleted");
        } else {
            $config['log_text'] = "[ Lead Notification failed to delete ]";
            $config['ref_link'] = url_to('erp.crm.leadview', $leadid);
            $this->session->setFlashdata("op_success", "Lead Notification failed to delete");
        }
        log_activity($config);
    }

    public function get_leadnotify_datatable($lead_id)
    {
        $columns = array(
            "notify at" => "notify_at",
            "notified" => "notified"
        );
        $limit = $_GET['limit'];
        $offset = $_GET['offset'];
        $search = $_GET['search'] ?? "";
        $orderby = isset($_GET['orderby']) ? strtoupper($_GET['orderby']) : "";
        $ordercol = isset($_GET['ordercol']) ? $columns[$_GET['ordercol']] : "";

        $query = "SELECT notify_id,title,notify_at,status,erp_users.name AS notify_to FROM notification JOIN erp_users ON notification.user_id=erp_users.user_id WHERE related_to='lead' AND related_id=$lead_id ";
        $count = "SELECT COUNT(notify_id) AS total FROM notification JOIN erp_users ON notification.user_id=erp_users.user_id WHERE related_to='lead' AND related_id=$lead_id ";
        $where = true;

        if (!empty($search)) {
            if (!$where) {
                $query .= " WHERE ( title LIKE '%" . $search . "%' OR erp_users.name LIKE '%" . $search . "%' ) ";
                $count .= " WHERE ( title LIKE '%" . $search . "%' OR erp_users.name LIKE '%" . $search . "%' ) ";
                $where = true;
            } else {
                $query .= " AND ( title LIKE '%" . $search . "%' OR erp_users.name LIKE '%" . $search . "%' ) ";
                $count .= " AND ( title LIKE '%" . $search . "%' OR erp_users.name LIKE '%" . $search . "%' ) ";
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
                                <li><a href="#" data-ajax-url="' . url_to('erp.crm.ajaxfetchnotify', $r['notify_id']) . '" title="Edit" class="bg-success modalBtn"><i class="fa fa-pencil"></i> </a></li>
                                <li><a href="' . url_to('erp.crm.leadnotifydelete', $lead_id, $r['notify_id']) . '" title="Delete" class="bg-danger del-confirm"><i class="fa fa-trash"></i> </a></li>
                            </ul>
                        </div>
                    </div>';
            $notify_at = date("Y-m-d H:i:s", $r['notify_at']);
            $notified = '<span class="st  ';
            if ($r['status'] == 1) {
                $notified .= 'st_violet" >Yes</span>';
            } else {
                $notified .= 'st_dark" >No</span>';
            }
            array_push(
                $m_result,
                array(
                    $r['notify_id'],
                    $sno,
                    $r['title'],
                    $notify_at,
                    $r['notify_to'],
                    $notified,
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


    public function get_leadnotify_export($type, $lead_id)
    {
        $columns = array(
            "notify at" => "notify_at",
            "notified" => "notified"
        );
        $limit = $_GET['limit'];
        $offset = $_GET['offset'];
        $search = $_GET['search'] ?? "";
        $orderby = isset($_GET['orderby']) ? strtoupper($_GET['orderby']) : "";
        $ordercol = isset($_GET['ordercol']) ? $columns[$_GET['ordercol']] : "";

        $query = "SELECT title,notify_text,FROM_UNIXTIME(notify_at) AS notify_at,CASE WHEN notify_email=1 THEN 'Yes' ELSE 'No' END AS notify_email ,CASE WHEN status=1 THEN 'Yes' ELSE 'No' END AS notified,erp_users.name AS notify_to FROM notification JOIN erp_users ON notification.user_id=erp_users.user_id WHERE related_to='lead' AND related_id=$lead_id ";
        $where = true;
        if (!empty($search)) {
            if (!$where) {
                $query .= " WHERE ( title LIKE '%" . $search . "%' OR erp_users.name LIKE '%" . $search . "%' ) ";
                $where = true;
            } else {
                $query .= " AND ( title LIKE '%" . $search . "%' OR erp_users.name LIKE '%" . $search . "%' ) ";
            }
        }

        if (!empty($ordercol) && !empty($orderby)) {
            $query .= " ORDER BY " . $ordercol . " " . $orderby;
        }
        $result = $this->db->query($query)->getResultArray();
        return $result;
    }

    public function insert_update_customer_notify($id, $notify_id, $post)
    {
        if (!empty($notify_id)) {
            $this->update_customer_notify($notify_id, $id, $post);
        } else {
            $this->insert_customer_notify($id, $post);
        }
    }

    private function update_customer_notify($notify_id, $id, $post)
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

        if ($this->db->affectedRows() > 0) {
            $param['notify_id'] = $notify_id;
            $job = new Notify();
            $jobid = Scheduler::replaceOldJob($jobresult->job_id, $job, $param, $timestamp);
            if (empty($jobid)) {
                $this->db->transrollback();
            } else {
                $query = "UPDATE notification SET job_id=? WHERE notify_id=$notify_id";
                $this->db->query($query, array($jobid));
                if ($this->db->affectedRows() > 0) {
                    $updated = true;
                } else {
                    $this->db->transRollback();
                }
            }
        }

        $config['title'] = "Customer Notification Update";
        $this->db->transComplete();
        if ($this->db->transStatus() === FALSE) {
            $this->db->transRollback();
            $config['log_text'] = "[ Customer Notification failed to update ]";
            $config['ref_link'] = url_to('erp.crm.customerview', $id);
            $this->session->setFlashdata("op_error", "Customer Notification failed to update");
        } else {
            $updated = true;
            $this->db->transCommit();
            $config['log_text'] = "[ Customer Notification successfully updated ]";
            $config['ref_link'] = url_to('erp.crm.customerview', $id);
            $this->session->setFlashdata("op_success", "Customer Notification successfully updated");
        }
        log_activity($config);
        return $updated;
    }

    private function insert_customer_notify($id, $post)
    {
        $inserted = false;
        $notify_title = $post["notify_title"];
        $notify_desc = $post["notify_desc"];
        $notify_to = $post["notify_to"];
        $notify_at = $post["notify_at"];
        $notify_email = $post["notify_email"] ?? 0;
        $related_to = 'customer';
        $created_by = get_user_id();
        $datetime = DateTime::createFromFormat("Y-m-d\TH:i", $notify_at);
        $timestamp = $datetime->getTimestamp();

        $this->db->transBegin();
        $query = "INSERT INTO notification(title,notify_text,user_id,notify_email,notify_at,related_to,related_id,created_at,created_by,related_base_url) VALUES(?,?,?,?,?,?,?,?,?,?) ";
        $this->db->query($query, array($notify_title, $notify_desc, $notify_to, $notify_email, $timestamp, $related_to, $id, time(), $created_by, "erp.crm.customerview"));
        $notify_id = $this->db->insertId();
        if (empty($notify_id)) {
            $this->session->setFlashdata("op_error", "Notification failed to create");
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

        $config['title'] = "Customer Notification Insert";
        $this->db->transComplete();
        if ($this->db->transStatus() === FALSE) {
            $inserted = false;
            $this->db->transRollback();
            $config['log_text'] = "[ Customer Notification failed to create ]";
            $config['ref_link'] = url_to('erp.crm.customerview', $id);
            $this->session->setFlashdata("op_error", "Customer Notification failed to create");
        } else {
            $this->db->transCommit();
            $config['log_text'] = "[ Customer Notification successfully created ]";
            $config['ref_link'] = url_to('erp.crm.customerview', $id);
            $this->session->setFlashdata("op_success", "Customer Notification successfully created");
        }
        log_activity($config);
        return $inserted;
    }

    public function delete_customer_notify($cust_id, $notify_id)
    {
        $jobresult = $this->db->query("SELECT job_id FROM notification WHERE notify_id=$notify_id")->getRow();
        $config['title'] = "Customer Notification Delete";
        if (!empty($jobresult)) {
            $jobid = $jobresult->job_id;
            $query = "DELETE FROM notification WHERE notify_id=$notify_id ";
            $this->db->query($query);
            Scheduler::safeDelete($jobid);
            $config['log_text'] = "[ Customer Notification successfully deleted ]";
            $config['ref_link'] = url_to('erp.crm.customerview', $cust_id);
            $this->session->setFlashdata("op_success", "Customer Notification successfully deleted");
        } else {
            $config['log_text'] = "[ Customer Notification failed to delete ]";
            $config['ref_link'] = url_to('erp.crm.customerview', $cust_id);
            $this->session->setFlashdata("op_success", "Customer Notification failed to delete");
        }
        log_activity($config);
    }


    public function get_customernotify_datatable($cust_id)
    {
        $columns = array(
            "notify at" => "notify_at",
            "notified" => "notified"
        );
        $limit = $_GET['limit'];
        $offset = $_GET['offset'];
        $search = $_GET['search'] ?? "";
        $orderby = isset($_GET['orderby']) ? strtoupper($_GET['orderby']) : "";
        $ordercol = isset($_GET['ordercol']) ? $columns[$_GET['ordercol']] : "";

        $query = "SELECT notify_id,title,notify_at,status,erp_users.name AS notify_to FROM notification JOIN erp_users ON notification.user_id=erp_users.user_id WHERE related_to='customer' AND related_id=$cust_id ";
        $count = "SELECT COUNT(notify_id) AS total FROM notification JOIN erp_users ON notification.user_id=erp_users.user_id WHERE related_to='customer' AND related_id=$cust_id ";
        $where = true;

        if (!empty($search)) {
            if (!$where) {
                $query .= " WHERE ( title LIKE '%" . $search . "%' OR erp_users.name LIKE '%" . $search . "%' ) ";
                $count .= " WHERE ( title LIKE '%" . $search . "%' OR erp_users.name LIKE '%" . $search . "%' ) ";
                $where = true;
            } else {
                $query .= " AND ( title LIKE '%" . $search . "%' OR erp_users.name LIKE '%" . $search . "%' ) ";
                $count .= " AND ( title LIKE '%" . $search . "%' OR erp_users.name LIKE '%" . $search . "%' ) ";
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
                                <li><a href="#" data-ajax-url="' . url_to('erp.crm.ajaxfetchnotify', $r['notify_id']) . '" title="Edit" class="bg-success modalBtn"><i class="fa fa-pencil"></i> </a></li>
                                <li><a href="' . url_to('erp.crm.customernotifydelete', $cust_id, $r['notify_id']) . '" title="Delete" class="bg-danger del-confirm"><i class="fa fa-trash"></i> </a></li>
                            </ul>
                        </div>
                    </div>';
            $notify_at = date("Y-m-d H:i:s", $r['notify_at']);
            $notified = '<span class="st  ';
            if ($r['status'] == 1) {
                $notified .= 'st_violet" >Yes</span>';
            } else {
                $notified .= 'st_dark" >No</span>';
            }
            array_push(
                $m_result,
                array(
                    $r['notify_id'],
                    $sno,
                    $r['title'],
                    $notify_at,
                    $r['notify_to'],
                    $notified,
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

    public function get_customernotify_export($cust_id)
    {
        $cust_id = $this->input->get("custid");
        $columns = array(
            "notify at" => "notify_at",
            "notified" => "notified"
        );
        $limit = $_GET['limit'];
        $offset = $_GET['offset'];
        $search = $_GET['search'] ?? "";
        $orderby = isset($_GET['orderby']) ? strtoupper($_GET['orderby']) : "";
        $ordercol = isset($_GET['ordercol']) ? $columns[$_GET['ordercol']] : "";

        $query = "SELECT title,notify_text,FROM_UNIXTIME(notify_at) AS notify_at,CASE WHEN notify_email=1 THEN 'Yes' ELSE 'No' END AS notify_email ,CASE WHEN status=1 THEN 'Yes' ELSE 'No' END AS notified,erp_users.name AS notify_to FROM notification JOIN erp_users ON notification.user_id=erp_users.user_id WHERE related_to='customer' AND related_id=$cust_id ";
        $where = true;
        if (!empty($search)) {
            if (!$where) {
                $query .= " WHERE ( title LIKE '%" . $search . "%' OR erp_users.name LIKE '%" . $search . "%' ) ";
                $where = true;
            } else {
                $query .= " AND ( title LIKE '%" . $search . "%' OR erp_users.name LIKE '%" . $search . "%' ) ";
            }
        }

        if (!empty($ordercol) && !empty($orderby)) {
            $query .= " ORDER BY " . $ordercol . " " . $orderby;
        }
        $result = $this->db->query($query)->getresultArray();
        return $result;
    }

    // public function delete_service_notify($service_id, $notify_id)
    // {
    //     $jobresult = $this->db->query("SELECT job_id FROM notification WHERE notify_id=$notify_id")->getRow();
    //     $config['title'] = "Service Notification Delete";
    //     if (!empty($jobresult)) {
    //         $jobid = $jobresult->job_id;
    //         $query = "DELETE FROM notification WHERE notify_id=$notify_id ";
    //         $this->db->query($query);
    //         Scheduler::safeDelete($jobid);
    //         $config['log_text'] = "[ Service Notification successfully deleted ]";
    //         $config['ref_link'] = url_to('erp.service.serviceview', $service_id);
    //         $this->session->setFlashdata("op_success", "Service Notification successfully deleted");
    //     } else {
    //         $config['log_text'] = "[ Customer Notification failed to delete ]";
    //         $config['ref_link'] = url_to('erp.service.serviceview', $service_id);
    //         $this->session->setFlashdata("op_success", "Service Notification failed to delete");
    //     }
    //     log_activity($config);
    // }

    // public function insert_update_service_notify($service_id, $notify_id, $post)
    // {
    //     if (!empty($notify_id)) {
    //         $this->insert_service_notify($notify_id, $service_id, $post);
    //     } else {
    //         $this->update_service_notify($notify_id,$service_id, $post);
    //     }
    // }

    // private function update_service_notify($notify_id, $service_id, $post)
    // {
    //     $updated = false;
    //     $notify_title = $post["notify_title"];
    //     $notify_desc = $post["notify_desc"];
    //     $notify_to = $post["notify_to"];
    //     $notify_at = $post["notify_at"];
    //     $notify_email = $post["notify_email"] ?? 0;
    //     $datetime = DateTime::createFromFormat("Y-m-d\TH:i", $notify_at);
    //     $timestamp = $datetime->getTimestamp();
    //     $jobresult = $this->db->query("SELECT job_id FROM notification WHERE notify_id=$notify_id")->getRow();
    //     $this->db->transBegin();
    //     $query = "UPDATE notification SET title=? , notify_text=? , user_id=? , notify_email=? , notify_at=? WHERE notify_id=$notify_id ";
    //     $this->db->query($query, array($notify_title, $notify_desc, $notify_to, $notify_email, $timestamp));

    //     if ($this->db->affectedRows() > 0) {
    //         $param['notify_id'] = $notify_id;
    //         $job = new Notify();
    //         $jobid = Scheduler::replaceOldJob($jobresult->job_id, $job, $param, $timestamp);
    //         if (empty($jobid)) {
    //             $this->db->transrollback();
    //         } else {
    //             $query = "UPDATE notification SET job_id=? WHERE notify_id=$notify_id";
    //             $this->db->query($query, array($jobid));
    //             if ($this->db->affectedRows() > 0) {
    //                 $updated = true;
    //             } else {
    //                 $this->db->transRollback();
    //             }
    //         }
    //     }

    //     $config['title'] = "Service Notification Update";
    //     $this->db->transComplete();
    //     if ($this->db->transStatus() === FALSE) {
    //         $this->db->transRollback();
    //         $config['log_text'] = "[ Service Notification failed to update ]";
    //         $config['ref_link'] = url_to('erp.service.serviceview', $service_id);
    //         $this->session->setFlashdata("op_error", "Service Notification failed to update");
    //     } else {
    //         $updated = true;
    //         $this->db->transCommit();
    //         $config['log_text'] = "[ Service Notification successfully updated ]";
    //         $config['ref_link'] = url_to('erp.service.serviceview', $service_id);
    //         $this->session->setFlashdata("op_success", "Service Notification successfully updated");
    //     }
    //     log_activity($config);
    //     return $updated;
    // }

    // private function insert_service_notify($service_id, $post)
    // {
    //     $inserted = false;
    //     $notify_title = $post["notify_title"];
    //     $notify_desc = $post["notify_desc"];
    //     $notify_to = $post["notify_to"];
    //     $notify_at = $post["notify_at"];
    //     $notify_email = $post["notify_email"] ?? 0;
    //     $related_to = 'service';
    //     $created_by = get_user_id();
    //     $datetime = DateTime::createFromFormat("Y-m-d\TH:i", $notify_at);
    //     $timestamp = $datetime->getTimestamp();

    //     $this->db->transBegin();
    //     $query = "INSERT INTO notification(title,notify_text,user_id,notify_email,notify_at,related_to,related_id,created_at,created_by) VALUES(?,?,?,?,?,?,?,?,?) ";
    //     $this->db->query($query, array($notify_title, $notify_desc, $notify_to, $notify_email, $timestamp, $related_to, $service_id, time(), $created_by));
    //     $notify_id = $this->db->insertId();
    //     if (empty($notify_id)) {
    //         $this->session->setFlashdata("op_error", "Notification failed to create");
    //         return;
    //     }

    //     $param['notify_id'] = $notify_id;
    //     $job = new Notify();
    //     $job_id = Scheduler::dispatch($job, $param, $timestamp);
    //     if (empty($job_id)) {
    //         $this->db->transRollback();
    //     } else {
    //         $query = "UPDATE notification SET job_id=? WHERE notify_id=$notify_id";
    //         $this->db->query($query, array($job_id));
    //         if ($this->db->affectedrows() > 0) {
    //             $inserted = true;
    //         } else {
    //             $this->db->transRollback();
    //         }
    //     }

    //     $config['title'] = "Service Notification Insert";
    //     $this->db->transComplete();
    //     if ($this->db->transStatus() === FALSE) {
    //         $inserted = false;
    //         $this->db->transRollback();
    //         $config['log_text'] = "[ Service Notification failed to create ]";
    //         $config['ref_link'] = url_to('erp.service.serviceview', $service_id);
    //         $this->session->setFlashdata("op_error", "Service Notification failed to create");
    //     } else {
    //         $this->db->transCommit();
    //         $config['log_text'] = "[ Service Notification successfully created ]";
    //         $config['ref_link'] = url_to('erp.service.serviceview', $service_id);
    //         $this->session->setFlashdata("op_success", "Service Notification successfully created");
    //     }
    //     log_activity($config);
    //     return $inserted;
    // }

    //udhaya
    public function insertexpensenotify($data)
    {
        if ($this->builder()->insert($data)) {
            return true;
        } else {
            return false;
        }
    }
    public function updateexpenssenotify($id, $data)
    {
        if ($this->builder()->where("notify_id", $id)->update($data)) {
            return true;
        } else {
            return false;
        }
    }
    public function deleteReminder($id)
    {
        if ($this->builder()->where("notify_id", $id)->delete()) {
            return true;
        } else {
            return false;
        }
    }
    public function expense_r_count($id)
    {
        return $this->builder()->select("COUNT(notify_id) as total")->where("related_to", "expense")->where("related_id", $id)->get()->getRow()->total;
    }

    public function add_reminder_goals($related_id, $related_to, $user_id, $notify_type)
    {
        $datetime = date('Y-m-d H:i:s');
        $GoalsModel = new GoalsModel();
        if ($user_id == 0) {
            $query = "SELECT employee_id  FROM employees";
            $employees = $this->db->query($query)->getResultArray();
            foreach ($employees as $emp) {
                $data = [
                    "related_id" => $related_id,
                    "related_to" => $related_to,
                    "user_id" => $emp["employee_id"],
                    "title" => "Goals Notification",
                    "notify_text" => "",
                    "notify_at" =>  $datetime,
                    "is_notified" => 1,
                    "created_by" => get_user_id(),
                ];
                if ($notify_type == "failed") {
                    $data["notify_text"] = "not_goal_message_failed";
                } else {
                    $data["notify_text"] = "not_goal_message_success";
                }
                $insert = $this->builder()->insert($data);
            }

            if ($insert) {
                if ($GoalsModel->is_notified($related_id)) {
                    return true;
                } else {
                    return false;
                }
            } else {
                return false;
            }
        } else {
            $data = [
                "related_id" => $related_id,
                "related_to" => $related_to,
                "user_id" => $user_id,
                "title" => "Goals Notification",
                "notify_text" => "",
                "notify_at" =>  $datetime,
                "is_notified" => 1,
                "created_by" => get_user_id()
            ];
            if ($notify_type == "failed") {
                $data["notify_text"] = "not_goal_message_failed";
            } else {
                $data["notify_text"] = "not_goal_message_success";
            }

            if ($this->builder()->insert($data)) {
                if ($GoalsModel->is_notified($related_id)) {
                    return true;
                } else {
                    return false;
                }
            } else {
                return false;
            }
        }
    }

    public function notify_Goal_delete($id){
        if($this->builder()->where("related_to","Goal")->where("related_id",$id)->delete()){
            return true;
        }else{
            return false;
        }
    }
}
