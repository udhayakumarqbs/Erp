<?php

namespace App\Models\Erp\Sale;

use CodeIgniter\Model;
use DateTime;
use App\libraries\Scheduler;
use App\libraries\job\Notify;


class CreditNotesModel extends Model
{
    protected $table            = 'credit_notes';
    protected $primaryKey       = 'credit_id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'code',
        'applied',
        'invoice_id',
        'issued_date',
        'other_charge',
        'payment_terms',
        'terms_condition',
        'remarks',
        'created_by',
        'created_at'
    ];

    // Dates
    protected $useTimestamps = false;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    public function get_dtconfig_creditnotes()
    {
        $config = array(
            "columnNames" => ["sno", "code", "customer Company", "issued date", "amount", "outstanding amount", "status", "action"],
            "sortable" => [0, 1, 1, 1, 1, 1, 1, 0],
        );
        return json_encode($config);
    }

    public function getCreditnoteDatatable()
    {
        $columns = array(
            "code" => "credit_notes.code",
            "customer" => "customers.company",
            "issued date" => "issued_date",
            "amount" => "total_amount"
        );

        $limit = $_GET['limit'];
        $offset = $_GET['offset'];
        $search = $_GET['search'] ?? "";

        $orderby = isset($_GET['orderby']) ? strtoupper($_GET['orderby']) : "";
        $ordercol = isset($_GET['ordercol']) ? $columns[$_GET['ordercol']] : "";

        $builder = $this->db->table('credit_notes');
        $builder->select('credit_notes.credit_id, credit_notes.code AS credit_code, customers.company AS company, issued_date, other_charge, balance_amount');
        $builder->join('customers', 'credit_notes.cust_id = customers.cust_id');

        $subquery = $this->db->table('credits_applied')
        ->selectSum('amount')->where('credit_id', 'credit_notes.credit_id', false);
       
        $builder->select("(" . $subquery->getCompiledSelect() . ") AS balance_amount");

        if (!empty($search)) {
            $builder->groupStart();
            $builder->like('credit_code', '%' . $search . '%');
            $builder->groupEnd();
        }

        if (!empty($ordercol) && !empty($orderby)) {
            $builder->orderBy($ordercol, $orderby);
        }

        $builder->groupBy('credit_notes.credit_id');

        if (!empty($limit)) {
            $builder->limit($limit, $offset);
        }

        $result = $builder->get()->getResultArray();
        $m_result = [];
        $sno = $offset + 1;

        foreach ($result as $r) {
            $action = '<div class="dropdown tableAction">
            <a type="button" class="dropBtn HoverA "><i class="fa fa-ellipsis-v"></i></a>
            <div class="dropdown_container">
            <ul class="BB_UL flex">
            <li><a href="' . url_to('erp.sale.creditnotesview', $r['credit_id']) . '" title="View" class="bg-warning "><i class="fa fa-eye"></i> </a></li>
            <li><a href="' .url_to('erp.edit.creditnoteedit', $r['credit_id']). '" title="Edit" class="bg-success "><i class="fa fa-pencil"></i> </a></li>
            <li><a href="' . url_to('erp.sale.creditnotesdelete', $r['credit_id']) . '" title="Delete" class="bg-danger del-confirm"><i class="fa fa-trash"></i> </a></li>
            </ul>
            </div>';        

                if($r['balance_amount'] == $r['other_charge']){
                    $due_amount = '0.00';
                }elseif($r['balance_amount'] == 0 || $r['balance_amount'] == ''){
                    $due_amount = $r['other_charge'];
                }elseif($r['balance_amount'] != $r['other_charge']){
                    $due_amount = $r['other_charge'] - $r['balance_amount'] .'.00';
                }

                if ($due_amount == 0.00) {
                    $status = '<button class="overdue-btn">CLOSED</button>';
                } else {
                    $status = '<button class="unpaid-btn">OPEN</button>';
                }

            $m_result[] = [
                $r['credit_id'],
                $sno,
                $r['credit_code'],
                $r['company'],
                $r['issued_date'],
                $r['other_charge'],
                $due_amount,
                $status,
                $action
            ];

            $sno++;
        }

        $response = [
            'total_rows' => $builder->countAllResults(false),
            'data' => $m_result,
        ];

        return $response;
    }

    public function upload_attachment($type, $id)
    {
        $path = get_attachment_path($type);
        $ext = pathinfo($_FILES['attachment']['name'], PATHINFO_EXTENSION);
        $filename = preg_replace('/[\/:"*?<>| ]+/i', "", $_FILES['attachment']['name']);
        $filepath = $path . $filename;
        $response = array();
        if (file_exists($filepath)) {
            $response['error'] = 1;
            $response['reason'] = "File already exists";
        } else {
            if (move_uploaded_file($_FILES['attachment']['tmp_name'], $filepath)) {
                $query = "INSERT INTO attachments(filename,related_to,related_id) VALUES(?,?,?) ";
                if (empty($id)) {
                    $response['error'] = 1;
                    $response['reason'] = "Internal Error";
                } else {
                    $this->db->query($query, array($filename, $type, $id));
                    $attach_id = $this->db->insertID();
                    if (!empty($attach_id)) {
                        $response['error'] = 0;
                        $response['reason'] = "File uploaded successfully";
                        $response['filename'] = $filename;
                        $response['insert_id'] = $attach_id;
                        $response['filelink'] = get_attachment_link($type) . $filename;
                    } else {
                        unlink($filepath);
                        $response['error'] = 1;
                        $response['reason'] = "Internal Error";
                    }
                }
            } else {
                $response['error'] = 1;
                $response['reason'] = "File upload Error";
            }
        }
        return $response;
    }

    public function get_creditnotify_datatable()
    {
        $columns = array(
            "notify at" => "notify_at",
            "title" => "title"
        );
        $limit = $_GET['limit'];
        $offset = $_GET['offset'];
        $search = $_GET['search'] ?? "";
        $orderby = isset($_GET['orderby']) ? strtoupper($_GET['orderby']) : "";
        $ordercol = isset($_GET['ordercol']) ? $columns[$_GET['ordercol']] : "";
        $credit_id = $_GET["creditid"];
        $query = $this->db->table('notification')
            ->select('notify_id, title, notify_at, status, erp_users.name AS notify_to, notify_text')
            ->join('erp_users', 'notification.user_id = erp_users.user_id')
            ->where('related_to', 'credit_note')
            ->where('related_id', $credit_id);

        if (!empty($search)) {
            $query->groupStart()
                ->like('title', $search)
                ->orLike('erp_users.name', $search)
                ->groupEnd();
        }

        if (!empty($ordercol) && !empty($orderby)) {
            $query->orderBy($ordercol, $orderby);
        }

        $query->limit($limit, $offset);

        $result = $query->get()->getResultArray();

        $m_result = [];
        $sno = $offset + 1;

        foreach ($result as $r) {
            $action = '<div class="dropdown tableAction">
                        <a type="button" class="dropBtn HoverA "><i class="fa fa-ellipsis-v"></i></a>
                        <div class="dropdown_container">
                            <ul class="BB_UL flex">
                                <li><a href="#" data-ajax-url="' . url_to('erp.sale.ajaxFetchNotify', $r['notify_id']) . '" title="Edit" class="bg-success modalBtn"><i class="fa fa-pencil"></i> </a></li>
                                <li><a href="' . url_to('erp.sale.creditNotifyDelete', $credit_id, $r['notify_id']) . '" title="Delete" class="bg-danger del-confirm"><i class="fa fa-trash"></i> </a></li>
                            </ul>
                        </div>
                    </div>';
            $notify_at = date("Y-m-d H:i:s", $r['notify_at']);
            $notified = '<span class="st ';
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
                    $r['notify_text'],
                    $notify_at,
                    $r['notify_to'],
                    $notified,
                    $action
                )
            );
            $sno++;
        }

        $response = [];
        $response['total_rows'] = $this->db->table('notification')
            ->where('related_to', 'credit_note')
            ->where('related_id', $credit_id)
            ->countAllResults();
        $response['data'] = $m_result;
        return $response;
    }

    public function delete_credit_notify($credit_id, $notify_id)
    {
        $jobresult = $this->db->table('notification')
            ->select('job_id')
            ->where('notify_id', $notify_id)
            ->get()
            ->getRow();

        $config['title'] = "Credit Notes Notification Delete";
        if (!empty($jobresult)) {
            $jobid = $jobresult->job_id;

            $this->db->table('notification')->where('notify_id', $notify_id)->delete();
            Scheduler::safeDelete($jobid);

            $config['log_text'] = "[ Credit Notes Notification successfully deleted ]";
            $config['ref_link'] = 'erp/sale/creditview/' . $credit_id;
            session()->setFlashdata("op_success", "Credit Notes Notification successfully deleted");
        } else {
            $config['log_text'] = "[ Credit Notes Notification failed to delete ]";
            $config['ref_link'] = 'erp/sale/creditview/' . $credit_id;
            session()->setFlashdata("op_success", "Credit Notes Notification failed to delete");
        }
        log_activity($config);
    }

    public function delete_attachment($type)
    {
        $attach_id = $_GET["id"] ?? 0;
        $response = array();
        if (!empty($attach_id)) {
            $filename = $this->db->query("SELECT filename FROM attachments WHERE attach_id=$attach_id")->getRow();
            if (!empty($filename)) {
                $filepath = get_attachment_path($type) . $filename->filename;
                $this->db->query("DELETE FROM attachments WHERE attach_id=$attach_id");
                if ($this->db->affectedRows() > 0) {
                    unlink($filepath);
                    $response['error'] = 0;
                    $response['reason'] = "File successfully deleted";
                } else {
                    $response['error'] = 1;
                    $response['reason'] = "File delete error";
                }
            } else {
                $response['error'] = 1;
                $response['reason'] = "File delete error";
            }
        } else {
            $response['error'] = 1;
            $response['reason'] = "File delete error";
        }
        return $response;
    }


    //NOT IMPLEMENTED
    public function apply_credit($credit_id)
    {
    }

    public function get_creditnote_for_view($credit_id)
    {
        $query = "SELECT credit_notes.code AS credit_code,issued_date,customers.name AS customer,customers.email, credit_notes.cust_id, other_charge, credit_notes.payment_terms, credit_notes.terms_condition, credit_notes.remarks FROM credit_notes 
        JOIN customers ON credit_notes.cust_id=customers.cust_id 
        WHERE credit_notes.credit_id=$credit_id ";
        $result = $this->db->query($query)->getRow();   
        return $result;
    }

    public function get_invoice_exist($credit_id){
        $query = "SELECT sale_invoice.invoice_id FROM credit_notes 
        JOIN customers ON credit_notes.cust_id=customers.cust_id 
        JOIN sale_invoice ON customers.cust_id = sale_invoice.cust_id
        WHERE credit_notes.credit_id=$credit_id ";
        $result = $this->db->query($query)->getRow();   
        return $result;
    }

    public function get_creditnote_items($credit_id)
    {
        $query = "SELECT CONCAT(finished_goods.name,' ',finished_goods.code) AS product,qty,unit_price,amount FROM credit_items JOIN finished_goods ON credit_items.related_id=finished_goods.finished_good_id WHERE credit_id=$credit_id ";
        $result = $this->db->query($query)->getResultArray();
        return $result;
    }

    public function get_dtconfig_notify()
    {
        $config = array(
            "columnNames" => ["sno", "title", "description", "notify at", "notify to", "notified", "action"],
            "sortable" => [0, 1, 0, 1, 0, 0, 0]
        );
        return json_encode($config);
    }

    public function get_attachments($type, $related_id)
    {
        $query = "SELECT filename,attach_id FROM attachments WHERE related_to='$type' AND related_id=$related_id";
        $result = $this->db->query($query)->getResultArray();
        return $result;
    }

    public function insert_update_creditnotify($credit_id, $notify_id, $data)
    {
        if (empty($notify_id)) {
            $this->insert_creditnotify($credit_id, $data);
        } else {
            $this->update_creditnotify($credit_id, $notify_id, $data);
        }
    }

    private function insert_creditnotify($credit_id, $data)
    {
        $related_to = 'credit_note';
        $created_by = get_user_id();
        $datetime = DateTime::createFromFormat("Y-m-d\TH:i", $data['notify_at']);
        $timestamp = $datetime->getTimestamp();
        $this->db->transbegin();
        $query = "INSERT INTO notification(title,notify_text,user_id,notify_email,notify_at,related_to,related_id,created_at,created_by) VALUES(?,?,?,?,?,?,?,?,?) ";
        $this->db->query($query, array($data['notify_title'], $data['notify_desc'], $data['notify_to'], $data['notify_email'], $timestamp, $related_to, $credit_id, time(), $created_by));
        $notify_id = $this->db->insertid();
        if (empty($notify_id)) {
            session()->setFlashdata("op_error", "Notification failed to create");
            return;
        }

        $param['notify_id'] = $notify_id;
        $job = new Notify();
        $job_id = Scheduler::dispatch($job, $param, $timestamp);
        if (empty($job_id)) {
            $this->db->transrollback();
        } else {
            $query = "UPDATE notification SET job_id=? WHERE notify_id=$notify_id";
            $this->db->query($query, array($job_id));
            if ($this->db->affectedrows() > 0) {
                $inserted = true;
            } else {
                $this->db->transrollback();
            }
        }

        $config['title'] = "Credit Notes Notification Insert";
        $this->db->transcomplete();
        if ($this->db->transstatus() === FALSE) {
            $this->db->transrollback();
            $config['log_text'] = "[ Credit Notes Notification failed to create ]";
            $config['ref_link'] = 'erp/sale/creditview/' . $credit_id;
            session()->setFlashdata("op_error", "Credit Notes Notification failed to create");
        } else {
            $this->db->transcommit();
            $config['log_text'] = "[ Credit Notes Notification successfully created ]";
            $config['ref_link'] = 'erp/sale/creditview/' . $credit_id;
            session()->setFlashdata("op_success", "Credit Notes Notification successfully created");
        }
        log_activity($config);
    }

    private function update_creditnotify($credit_id, $notify_id, $data)
    {
        $datetime = DateTime::createFromFormat("Y-m-d\TH:i", $data['notify_at']);
        $timestamp = $datetime->getTimestamp();
        $jobresult = $this->db->query("SELECT job_id FROM notification WHERE notify_id=$notify_id")->getRow();
        $this->db->transbegin();
        $query = "UPDATE notification SET title=? , notify_text=? , user_id=? , notify_email=? , notify_at=? WHERE notify_id=$notify_id ";
        $this->db->query($query, array($data['notify_title'], $data['notify_desc'], $data['notify_to'], $data['notify_email'], $timestamp));

        if ($this->db->affectedrows() > 0) {
            $param['notify_id'] = $notify_id;
            $job = new Notify();
            $jobid = Scheduler::replaceOldJob($jobresult->job_id, $job, $param, $timestamp);
            if (empty($jobid)) {
                $this->db->transrollback();
            } else {
                $query = "UPDATE notification SET job_id=? WHERE notify_id=$notify_id";
                $this->db->query($query, array($jobid));
                if ($this->db->affectedrows() > 0) {
                    $updated = true;
                } else {
                    $this->db->transrollback();
                }
            }
        }

        $config['title'] = "Credit Notes Notification Update";
        $this->db->transcomplete();
        if ($this->db->transstatus() === FALSE) {
            $inserted = false;
            $this->db->transrollback();
            $config['log_text'] = "[ Credit Notes Notification failed to update ]";
            $config['ref_link'] = 'erp/sale/creditview/' . $credit_id;
            session()->setFlashdata("op_error", "Credit Notes Notification failed to update");
        } else {
            $this->db->transcommit();
            $config['log_text'] = "[ Credit Notes Notification successfully updated ]";
            $config['ref_link'] = 'erp/sale/creditview/' . $credit_id;
            session()->setFlashdata("op_success", "Credit Notes Notification successfully updated");
        }
        log_activity($config);
    }

    public function get_creditnotify_export($type, $credit_id)
    {

        $columns = array(
            "notify at" => "notify_at",
            "title" => "title"
        );
        $limit = $_GET['limit'];
        $offset = $_GET['offset'];
        $search = $_GET['search'] ?? "";
        $orderby = isset($_GET['orderby']) ? strtoupper($_GET['orderby']) : "";
        $ordercol = isset($_GET['ordercol']) ? $columns[$_GET['ordercol']] : "";

        $query = "SELECT title,notify_text,FROM_UNIXTIME(notify_at,'%Y-%m-%d %h:%i:%s') AS notify_at,erp_users.name AS notify_to,CASE WHEN status=1 THEN 'Yes' ELSE 'No' END AS notified FROM notification JOIN erp_users ON notification.user_id=erp_users.user_id WHERE related_to='credit_note' AND related_id=$credit_id ";
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

   // creditnote Export

    public function get_creditnote_export($type)
    {
        $columns = array(
            "code" => "credit_notes.code",
            "issued date" => "issued_date",
            "amount" => "total_amount"
        );

        $limit = $_GET['limit'];
        $offset = $_GET['offset'];
        $search = $_GET['search'] ?? "";

        $orderby = isset($_GET['orderby']) ? strtoupper($_GET['orderby']) : "";
        $ordercol = isset($_GET['ordercol']) ? $columns[$_GET['ordercol']] : "";
        $query = "SELECT credit_notes.code AS credit_code, customers.company AS company, issued_date, other_charge, balance_amount FROM credit_notes JOIN customers ON credit_notes.cust_id = customers.cust_id";
        $where = true;
        

        if (!empty($search)) {
            if (!$where) {
                $query .= " WHERE ( credit_code LIKE '%" . $search . "%' OR invoice_code LIKE '%" . $search . "%' ) ";
                $where = true;
            } else {
                $query .= " AND ( credit_code LIKE '%" . $search . "%' OR invoice_code LIKE '%" . $search . "%' ) ";
            }
        }

        if (!empty($ordercol) && !empty($orderby)) {
            $query .= " ORDER BY " . $ordercol . " " . $orderby;
        }
        $query .= " GROUP BY credit_notes.credit_id ";
        $result = $this->db->query($query)->getResultArray();
        return $result;
    }


    //creditnote delete Implemented


    public function delete_creditnote($credit_id)
    {
        $deleted = false;
        $creditnote = $this->get_creditnote_by_id($credit_id);
        $this->db->transBegin();
    
        $this->db->table('credits_applied')->where('credit_id', $credit_id)->delete();
        if ($this->db->affectedRows() > 0) {
           
            $this->builder()->where('credit_id', $credit_id)->delete();
    
            if ($this->db->affectedRows() > 0) {
                $deleted = true;
    
                $config = [
                    'title' => 'Credit Note Deletion',
                    'log_text' => "[ Credit Note successfully deleted ]",
                    'ref_link' => 'erp.sale.creditnotesdelete' . $credit_id,
                    'additional_info' => json_encode($creditnote),
                ];
                log_activity($config);
                $this->db->transCommit();
            } else {
                $this->db->transRollback();
            }
        } else {
            $this->db->transRollback();
            $config = [
                'title' => 'Credit Note Deletion',
                'log_text' => "[ Credit Note failed to delete ]",
                'ref_link' => '' . $credit_id,
            ];
            log_activity($config);
        }
    
        return $deleted;
    }
    

    public function get_creditnote_by_id($credit_id)
    {
        $query = "SELECT credit_id,code,invoice_id,applied,issued_date,other_charge,payment_terms,terms_condition,remarks FROM credit_notes  WHERE credit_id=$credit_id";
        $result = $this->db->query($query)->getRow();
        return $result;
    }

    public function getCreditNoteDetailsByCustomerId($customer_id)
    {
        $query = "SELECT credit_id,code,invoice_id,issued_date,other_charge FROM credit_notes  WHERE cust_id=$customer_id";
        $result = $this->db->query($query)->getResultArray();
        return $result;
    }

    public function getCreditAmountByCreditId($credit_id)
    {
        $query = "SELECT CASE WHEN balance_amount IS NOT NULL AND balance_amount <> 0 THEN balance_amount ELSE other_charge END AS final_amount
            FROM credit_notes WHERE credit_id = $credit_id";
        $result = $this->db->query($query)->getRow()->final_amount;
        return $result;
    }

   
    public function getAppliedCreditsByInvoiceId($invoice_id)
    {
        $query = "SELECT COALESCE(SUM(amount),0) as amount FROM credits_applied WHERE invoice_id=$invoice_id ";
        $result = $this->db->query($query)->getRow()->amount;
        return $result;
    }

    public function getAllTime(){
        return $this->builder()->select('created_at')->get()->getResultArray();
    }

    public function getCustomerId($creditId){
        return $this->builder()->select('cust_id')->where('credit_id', $creditId)->get()->getRow()->cust_id;
    }

    public function getCreditData($credit_id){
        return $this->builder()->select('code, other_charge')->where('credit_id', $credit_id)->get()->getResultArray()[0];
    }

    public function insertCreditAmountEntry($data){
        $query = "INSERT INTO credits_applied(credit_id, invoice_id, amount, staff_id, date_applied) VALUES(?,?,?,?,?)";
        return $this->db->query($query, [
                $data['credit_id'],
                $data['invoice_id'],
                $data['amount'],
                $data['staff_id'],
                $data['date_applied']
        ]);
    }

    public function updateCreditNote($data)
    {
        $updated = false;
        $this->db->transBegin();
        $this->db->table('credit_notes')
                 ->set('code', $data['code'])
                 ->set('cust_id', $data['cust_id'])
                 ->set('issued_date', $data['issued_date'])
                 ->set('other_charge', $data['other_charge'])
                 ->set('payment_terms', $data['payment_terms'])
                 ->set('terms_condition', $data['terms_condition'])
                 ->set('remarks', $data['remarks'])
                 ->set('created_at', time())
                 ->set('created_by', $data['created_by'])
                 ->where('credit_id', $data['credit_id'])
                 ->update();
    
        if (empty($data['credit_id'])) {
            session()->setFlashdata("op_error", "Credit Note failed to update");
            return $updated;
        }
    
        $config['title'] = "Credit Note Update";
        $this->db->transComplete();
    
        if ($this->db->transStatus() === FALSE) {
            $this->db->transRollback();
            $config['log_text'] = "[Credit Note failed to update]";
            $config['ref_link'] = url_to('erp.edit.creditnoteedit', $data['credit_id']);
            session()->setFlashdata("op_error", "Credit Note failed to update");
        } else {
            $updated = true;
            $this->db->transCommit();
            $config['log_text'] = "[ Credit Note successfully updated ]";
            $config['ref_link'] = url_to('erp.sale.creditnotes');
            session()->setFlashdata("op_success", "Credit Note successfully updated");
        }
    
        log_activity($config);
        return $updated;
    }
    

    public function getCreditnoteDataById($credit_id){
        $query = "SELECT credit_id,cust_id, code, invoice_id, issued_date, other_charge, payment_terms, terms_condition, remarks FROM credit_notes  WHERE credit_id=$credit_id";
        $result = $this->db->query($query)->getResultArray()[0];
        return $result;
    }

    // public function getCreditDataByInvoiceId($invoice_id){
    //   $query ="SELECT  credit_notes.code, credit_id FROM credit_notes
    //             JOIN sale_invoice ON credit_notes.cust_id = sale_invoice.cust_id 
    //             WHERE sale_invoice.invoice_id = $invoice_id";
    //   return $this->db->query($query)->getResultArray();
    // }

    public function getCreditDataByInvoiceId($invoice_id) {
        $query = "SELECT credit_notes.code, credit_notes.credit_id 
                  FROM credit_notes
                  JOIN sale_invoice ON credit_notes.cust_id = sale_invoice.cust_id 
                  LEFT JOIN (
                      SELECT credit_id, SUM(amount) AS total_applied_amount
                      FROM credits_applied
                      GROUP BY credit_id
                  ) AS applied_amounts ON credit_notes.credit_id = applied_amounts.credit_id
                  WHERE sale_invoice.invoice_id = $invoice_id
                  AND (applied_amounts.total_applied_amount IS NULL OR credit_notes.other_charge > applied_amounts.total_applied_amount)";
        return $this->db->query($query)->getResultArray();
    } 

    public function getCreditDataById($credit_id){
        $queryApplied = "SELECT COALESCE(SUM(credits_applied.amount), 0) as total_applied_amount FROM credits_applied WHERE credit_id = $credit_id";
        $resultApplied = $this->db->query($queryApplied)->getRowArray();
        $queryTotalCredit = "SELECT COALESCE(credit_notes.other_charge, 0) as total_credit FROM credit_notes WHERE credit_id = $credit_id";
        $resultTotalCredit = $this->db->query($queryTotalCredit)->getRowArray();
    
        if ($resultApplied['total_applied_amount'] == 0) {
            $resultApplied['total_applied_amount'] = 0;
        }
    
        $remainingCredit = $resultTotalCredit['total_credit'] - $resultApplied['total_applied_amount'];
    
        $result = [
            'total_applied_amount' => $resultApplied['total_applied_amount'],
            'total_credit' => $resultTotalCredit['total_credit'],
            'remaining_credit_amount' => $remainingCredit,
        ];
    
        return $result;
    }

    public function getAppliedCredits($invoice_id){
       $query = "SELECT SUM(amount) as applied_amount FROM credits_applied WHERE invoice_id = $invoice_id";
       return $this->db->query($query)->getResultArray()[0];
    }

    public function getCustomerContactById($credit_id){
        $query = "SELECT customer_contacts.email FROM credit_notes JOIN customer_contacts ON credit_notes.cust_id = customer_contacts.cust_id WHERE credit_id = $credit_id";
        return $this->db->query($query)->getResultArray();
    }   

    public function getCustomerMailById($credit_id){
        $query = "SELECT customers.email FROM credit_notes JOIN customers ON credit_notes.cust_id = customers.cust_id WHERE credit_id = $credit_id";
        return $this->db->query($query)->getResultArray();
    }


}




