<?php

namespace App\Models\Erp;

use CodeIgniter\Model;
use DateTime;

class JournalEntryModel extends Model
{
    protected $table = 'journal_entry';
    protected $primaryKey = 'journal_id';
    protected $allowedFields = [
        'gl_acc_id',
        'credit',
        'debit',
        'narration',
        'amount',
        'transaction_date',
        'type',
        'posted',
        'posted_date',
        'created_at',
        'created_by',
        'related_to',
        'related_id',
        'prev_amount',
    ];
    public $db;
    public $session;

    public function __construct()
    {
        $this->db = \Config\Database::connect();
        $this->session = \Config\Services::session();
    }
    public function insert_journal_entry($post)
    {
        $inserted = false;
        $transaction_date = $post["transaction_date"];
        $journal_type = $post["journal_type"];
        $created_by = get_user_id();
        $query = "INSERT INTO journal_entry(gl_acc_id,credit,debit,narration,amount,transaction_date,type,posted,created_at,created_by) VALUES(?,?,?,?,?,?,?,?,?,?) ";
        $data = array();
        foreach ($_POST as $key => $value) {
            if (stripos($key, "gl_acc_id_") === 0) {
                $idx = substr($key, strlen("gl_acc_id_"));
                $tmp = array();
                $tmp['gl_acc_id'] = $value;
                $tmp['credit'] = 0;
                $tmp['debit'] = 0;
                $tmp['narration'] = $_POST['narration_' . $idx] ?? " ";
                if ($_POST['credit_' . $idx] !== "0.00") {
                    $tmp['credit'] = 1;
                    $tmp['amount'] = $_POST['credit_' . $idx];
                } else {
                    $tmp['debit'] = 1;
                    $tmp['amount'] = $_POST['debit_' . $idx];
                }
                array_push($data, $tmp);
            }
        }

        $this->db->transBegin();
        foreach ($data as $array) {
            $this->db->query($query, array($array['gl_acc_id'], $array['credit'], $array['debit'], $array['narration'], $array['amount'], $transaction_date, $journal_type, 0, time(), $created_by));
            if (empty($this->db->insertId())) {
                $this->db->transRollback();
                break;
            }
        }
        $this->db->transComplete();
        if ($this->db->transStatus() === FALSE) {
            $this->db->transRollback();
        } else {
            $this->db->transCommit();
            $inserted = true;
        }

        $config['title'] = "Journal Entry Insert";
        if ($inserted) {
            $config['log_text'] = "[ Journal Entry successfully created ]";
            $config['ref_link'] = 'erp/finance/journalentry/';
            $this->session->setFlashdata("op_success", "Journal Entry successfully created");
        } else {
            $config['log_text'] = "[ Journal Entry failed to create ]";
            $config['ref_link'] = "erp/finance/journalentry/";
            $this->session->setFlashdata("op_error", "Journal Entry failed to create");
        }
        log_activity($config);
    }

    public function get_dtconfig_journalentry()
    {
        $config = array(
            "columnNames" => ["sno", "gl account", "transaction type", "amount", "narration", "journal type", "transaction date", "posted", "action"],
            "sortable" => [0, 0, 0, 1, 0, 0, 0, 0],
            "filters" => ["trans_type", "journal_type", "posted", "trans_from", "trans_to"]
        );
        return json_encode($config);
    }

    public function get_journalentry_datatable()
    {
        $columns = array(
            "trans_type" => "credit_debit",
            "amount" => "amount",
            "journal_type" => "type",
            "posted" => "posted",
            "trans_from" => "trans_from",
            "trans_to" => "trans_to"
        );
        $limit = $_GET['limit'];
        $offset = $_GET['offset'];
        $search = $_GET['search'] ?? "";
        $orderby = isset($_GET['orderby']) ? strtoupper($_GET['orderby']) : "";
        $ordercol = isset($_GET['ordercol']) ? $columns[$_GET['ordercol']] : "";

        $query = "SELECT journal_id,CONCAT(gl_accounts.account_code,' ',gl_accounts.account_name) AS gl_account,credit,debit,amount,transaction_date,narration,type,posted FROM journal_entry JOIN gl_accounts ON journal_entry.gl_acc_id=gl_accounts.gl_acc_id ";
        $count = "SELECT COUNT(journal_id) AS total FROM journal_entry JOIN gl_accounts ON journal_entry.gl_acc_id=gl_accounts.gl_acc_id ";
        $where = false;

        $filter_1_val = $_GET['trans_type'];

        if ($filter_1_val !== "") {
            if ($filter_1_val == "credit") {
                $query .= " WHERE credit=1 ";
                $where = true;
            } else {
                $query .= " WHERE debit=1 ";
                $where = true;
            }
        }

        $filter_2_col = isset($_GET['journal_type']) ? $columns['journal_type'] : "";
        $filter_2_val = $_GET['journal_type'];

        if (!empty($filter_2_col) && $filter_2_val !== "") {
            if ($where) {
                $query .= " AND " . $filter_2_col . "=" . $filter_2_val . " ";
                $count .= " AND " . $filter_2_col . "=" . $filter_2_val . " ";
            } else {
                $query .= " WHERE " . $filter_2_col . "=" . $filter_2_val . " ";
                $count .= " WHERE " . $filter_2_col . "=" . $filter_2_val . " ";
                $where = true;
            }
        }

        $filter_3_col = isset($_GET['trans_from']) ? $columns['trans_from'] : "";
        $filter_3_val = $_GET['trans_from'];

        $filter_4_col = isset($_GET['trans_to']) ? $columns['trans_to'] : "";
        $filter_4_val = $_GET['trans_to'];

        if (!empty($filter_3_col) && $filter_3_val !== "" && !empty($filter_4_col) && $filter_4_val !== "") {
            if ($where) {
                $query .= " AND ( transaction_date BETWEEN " . $filter_3_val . " AND " . $filter_4_val . " ) ";
                $count .= " AND ( transaction_date BETWEEN " . $filter_3_val . " AND " . $filter_4_val . " ) ";
            } else {
                $query .= " WHERE ( transaction_date BETWEEN " . $filter_3_val . " AND " . $filter_4_val . " ) ";
                $count .= " WHERE ( transaction_date BETWEEN " . $filter_3_val . " AND " . $filter_4_val . " ) ";
                $where = true;
            }
        } else if (!empty($filter_3_col) && $filter_3_val !== "") {
            if ($where) {
                $query .= " AND  transaction_date >= " . $filter_3_val . " ";
                $count .= " AND  transaction_date >= " . $filter_3_val . " ";
            } else {
                $query .= " WHERE  transaction_date >= " . $filter_3_val . " ";
                $count .= " WHERE  transaction_date >= " . $filter_3_val . " ";
                $where = true;
            }
        } else if (!empty($filter_4_col) && $filter_4_val !== "") {
            if ($where) {
                $query .= " AND  transaction_date <= " . $filter_4_val . " ";
                $count .= " AND  transaction_date <= " . $filter_4_val . " ";
            } else {
                $query .= " WHERE  transaction_date <= " . $filter_4_val . " ";
                $count .= " WHERE  transaction_date <= " . $filter_4_val . " ";
                $where = true;
            }
        }

        $filter_5_col = isset($_GET['posted']) ? $columns['posted'] : "";
        $filter_5_val = $_GET['posted'];

        if (!empty($filter_5_col) && $filter_5_val !== "") {
            if ($where) {
                $query .= " AND " . $filter_5_col . "=" . $filter_5_val . " ";
                $count .= " AND " . $filter_5_col . "=" . $filter_5_val . " ";
            } else {
                $query .= " WHERE " . $filter_5_col . "=" . $filter_5_val . " ";
                $count .= " WHERE " . $filter_5_col . "=" . $filter_5_val . " ";
                $where = true;
            }
        }

        if (!empty($search)) {
            if (!$where) {
                $query .= " WHERE ( gl_accounts.account_code LIKE '%" . $search . "%' OR gl_accounts.account_name LIKE '%" . $search . "%' ) ";
                $count .= " WHERE ( gl_accounts.account_code LIKE '%" . $search . "%' OR gl_accounts.account_name LIKE '%" . $search . "%' ) ";
                $where = true;
            } else {
                $query .= " AND ( gl_accounts.account_code LIKE '%" . $search . "%' OR gl_accounts.account_name LIKE '%" . $search . "%' ) ";
                $count .= " AND ( gl_accounts.account_code LIKE '%" . $search . "%' OR gl_accounts.account_name LIKE '%" . $search . "%' ) ";
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
                            <ul class="BB_UL flex">';
            if ($r['posted'] == 0) {
                $action .= '<li><a href="' . url_to('erp.finance.journalentrypost', $r['journal_id']) . '" title="Post" class="bg-warning"><i class="fa fa-caret-square-o-up"></i> </a></li>';
            }
            $action .= '<li><a href="' . url_to('erp.finance.journalentrydelete', $r['journal_id']) . '" title="Delete" class="bg-danger del-confirm"><i class="fa fa-trash"></i> </a></li>
                            </ul>
                        </div>
                    </div>';
            $transaction_type = '<span class="st ';
            if ($r['credit'] == 1) {
                $transaction_type .= ' st_violet" > Credit</span>';
            } else {
                $transaction_type .= ' st_primary" > Debit</span>';
            }

            $journal_type = '<span class="st ';
            if ($r['type'] == 1) {
                $journal_type .= ' st_warning" > Revising</span>';
            } else {
                $journal_type .= ' st_success" > Normal</span>';
            }
            $posted = '<span class="st ';
            if ($r['posted'] == 1) {
                $posted .= ' st_success" > Yes</span>';
            } else {
                $posted .= ' st_dark" > No</span>';
            }
            array_push(
                $m_result,
                array(
                    $r['journal_id'],
                    $sno,
                    $r['gl_account'],
                    $transaction_type,
                    $r['amount'],
                    $r['narration'],
                    $journal_type,
                    $r['transaction_date'],
                    $posted,
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

    public function get_journalentry_export($type)
    {
        $columns = array(
            "trans_type" => "credit_debit",
            "amount" => "amount",
            "journal_type" => "type",
            "posted" => "posted",
            "trans_from" => "trans_from",
            "trans_to" => "trans_to"
        );
        $limit = $_GET['limit'];
        $offset = $_GET['offset'];
        $search = $_GET['search'] ?? "";
        $orderby = isset($_GET['orderby']) ? strtoupper($_GET['orderby']) : "";
        $ordercol = isset($_GET['ordercol']) ? $columns[$_GET['ordercol']] : "";

        $query = "";
        if ($type == "pdf") {
            $query = "SELECT CONCAT(gl_accounts.account_code,' ',gl_accounts.account_name) AS gl_account,CASE WHEN credit=1 THEN 'Credit' ELSE 'Debit' END AS transaction_type,amount,transaction_date,CASE WHEN type=1 THEN 'Revising' ELSE 'Normal' END AS journal_type, CASE WHEN posted=1 THEN 'Yes' ELSE 'No' END AS posted  FROM journal_entry JOIN gl_accounts ON journal_entry.gl_acc_id=gl_accounts.gl_acc_id ";
        } else {
            $query = "SELECT CONCAT(gl_accounts.account_code,' ',gl_accounts.account_name) AS gl_account,CASE WHEN credit=1 THEN 'Credit' ELSE 'Debit' END AS transaction_type,amount,narration,transaction_date,CASE WHEN type=1 THEN 'Revising' ELSE 'Normal' END AS journal_type, CASE WHEN posted=1 THEN 'Yes' ELSE 'No' END AS posted  FROM journal_entry JOIN gl_accounts ON journal_entry.gl_acc_id=gl_accounts.gl_acc_id ";
        }
        $where = false;

        $filter_1_val = $_GET['trans_type'];

        if ($filter_1_val !== "") {
            if ($filter_1_val == "credit") {
                $query .= " WHERE credit=1 ";
                $where = true;
            } else {
                $query .= " WHERE debit=1 ";
                $where = true;
            }
        }

        $filter_2_col = isset($_GET['journal_type']) ? $columns['journal_type'] : "";
        $filter_2_val = $_GET['journal_type'];

        if (!empty($filter_2_col) && $filter_2_val !== "") {
            if ($where) {
                $query .= " AND " . $filter_2_col . "=" . $filter_2_val . " ";
            } else {
                $query .= " WHERE " . $filter_2_col . "=" . $filter_2_val . " ";
                $where = true;
            }
        }

        $filter_3_col = isset($_GET['trans_from']) ? $columns['trans_from'] : "";
        $filter_3_val = $_GET['trans_from'];

        $filter_4_col = isset($_GET['trans_to']) ? $columns['trans_to'] : "";
        $filter_4_val = $_GET['trans_to'];

        if (!empty($filter_3_col) && $filter_3_val !== "" && !empty($filter_4_col) && $filter_4_val !== "") {
            if ($where) {
                $query .= " AND ( transaction_date BETWEEN " . $filter_3_val . " AND " . $filter_4_val . " ) ";
            } else {
                $query .= " WHERE ( transaction_date BETWEEN " . $filter_3_val . " AND " . $filter_4_val . " ) ";
                $where = true;
            }
        } else if (!empty($filter_3_col) && $filter_3_val !== "") {
            if ($where) {
                $query .= " AND  transaction_date >= " . $filter_3_val . " ";
            } else {
                $query .= " WHERE  transaction_date >= " . $filter_3_val . " ";
                $where = true;
            }
        } else if (!empty($filter_4_col) && $filter_4_val !== "") {
            if ($where) {
                $query .= " AND  transaction_date <= " . $filter_4_val . " ";
            } else {
                $query .= " WHERE  transaction_date <= " . $filter_4_val . " ";
                $where = true;
            }
        }

        $filter_5_col = isset($_GET['posted']) ? $columns['posted'] : "";
        $filter_5_val = $_GET['posted'];

        if (!empty($filter_5_col) && $filter_5_val !== "") {
            if ($where) {
                $query .= " AND " . $filter_5_col . "=" . $filter_5_val . " ";
            } else {
                $query .= " WHERE " . $filter_5_col . "=" . $filter_5_val . " ";
                $where = true;
            }
        }

        if (!empty($search)) {
            if (!$where) {
                $query .= " WHERE ( gl_accounts.account_code LIKE '%" . $search . "%' OR gl_accounts.account_name LIKE '%" . $search . "%' ) ";
                $where = true;
            } else {
                $query .= " AND ( gl_accounts.account_code LIKE '%" . $search . "%' OR gl_accounts.account_name LIKE '%" . $search . "%' ) ";
            }
        }

        if (!empty($ordercol) && !empty($orderby)) {
            $query .= " ORDER BY " . $ordercol . " " . $orderby;
        }
        $query .= " LIMIT $offset,$limit ";
        $result = $this->db->query($query)->getResultArray();
        return $result;
    }

    //IMPLEMENTED BUT NEED TO CHECK     
    public function post_journalentry($journal_id)
    {
        $posted = false;
        $query = "SELECT * FROM journal_entry WHERE journal_id=" . $journal_id;
        $result = $this->db->query($query)->getRow();
        if (!empty($result)) {
            $trans_date = $result->transaction_date;
            $gl_acc_id = $result->gl_acc_id;
            $amount = $result->amount;
            $credit = $result->credit;
            $debit = $result->debit;
            $datetime = DateTime::createFromFormat("Y-m-d", $trans_date);
            $timestamp = $datetime->getTimestamp();
            $period = date('Y-m-d', mktime(0, 0, 0, date("m", $timestamp), 1, date("Y", $timestamp)));

            $check1 = $this->db->query("SELECT gl_acc_id FROM general_ledger WHERE DATE(period)=DATE('$period') AND gl_acc_id=$gl_acc_id")->getRow();
            if (!empty($check1)) {
                $this->db->transBegin();
                if ($debit == 1) {
                    $query = "UPDATE general_ledger SET actual_amt=actual_amt+$amount , balance_fwd=balance_fwd+$amount WHERE DATE(period)=DATE('$period') AND gl_acc_id=$gl_acc_id";
                    $this->db->query($query);
                    $query = "UPDATE general_ledger SET balance_fwd=balance_fwd+$amount WHERE DATE(period) > DATE('$period') AND gl_acc_id=$gl_acc_id ";
                    $this->db->query($query);
                } else {
                    $query = "UPDATE general_ledger SET actual_amt=actual_amt-$amount , balance_fwd=balance_fwd-$amount WHERE DATE(period)=DATE('$period') AND gl_acc_id=$gl_acc_id";
                    $this->db->query($query);
                    $query = "UPDATE general_ledger SET balance_fwd=balance_fwd-$amount WHERE DATE(period) > DATE('$period') AND gl_acc_id=$gl_acc_id ";
                    $this->db->query($query);
                }
                $posted_time = date("Y-m-d");
                $this->db->query("UPDATE journal_entry SET posted=1 , posted_date='$posted_time' WHERE journal_id=$journal_id");
                $this->db->transComplete();
                if ($this->db->transStatus() === FALSE) {
                    $this->db->transRollback();
                } else {
                    $posted = true;
                    $this->db->transCommit();
                }
            } else {
                // $query="SELECT SUM(balance_fwd) AS balance_fwd_sum FROM general_ledger WHERE DATE(period) < DATE('$period') AND gl_acc_id=$gl_acc_id";
                // $balance_fwd=$this->db->query($query)->row()->balance_fwd_sum;
                // if($debit==1){
                //     $balance_fwd+=$amount;
                // }else{
                //     $balance_fwd-=$amount;
                // }
                $this->db->transBegin();
                $query = "INSERT INTO general_ledger(gl_acc_id,period,actual_amt,balance_fwd) VALUES(?,?,?,?) ";
                $this->db->query($query, array($gl_acc_id, $period, 0.00, 0.00));
                // if($debit==1){
                //     $query="UPDATE general_ledger SET balance_fwd=balance_fwd+$amount WHERE DATE(period) > DATE('$period') AND gl_acc_id=$gl_acc_id ";
                //     $this->db->query($query);
                // }else{
                //     $query="UPDATE general_ledger SET balance_fwd=balance_fwd-$amount WHERE DATE(period) > DATE('$period') AND gl_acc_id=$gl_acc_id ";
                //     $this->db->query($query);
                // }
                // $this->db->query("UPDATE journal_entry SET posted=1 WHERE journal_id=$journal_id");
                if ($debit == 1) {
                    $query = "UPDATE general_ledger SET actual_amt=actual_amt+$amount , balance_fwd=balance_fwd+$amount WHERE DATE(period)=DATE('$period') AND gl_acc_id=$gl_acc_id";
                    $this->db->query($query);
                    $query = "UPDATE general_ledger SET balance_fwd=balance_fwd+$amount WHERE DATE(period) > DATE('$period') AND gl_acc_id=$gl_acc_id ";
                    $this->db->query($query);
                } else {
                    $query = "UPDATE general_ledger SET actual_amt=actual_amt-$amount , balance_fwd=balance_fwd-$amount WHERE DATE(period)=DATE('$period') AND gl_acc_id=$gl_acc_id";
                    $this->db->query($query);
                    $query = "UPDATE general_ledger SET balance_fwd=balance_fwd-$amount WHERE DATE(period) > DATE('$period') AND gl_acc_id=$gl_acc_id ";
                    $this->db->query($query);
                }
                $posted_time = date("Y-m-d");
                $this->db->query("UPDATE journal_entry SET posted=1 , posted_date='$posted_time' WHERE journal_id=$journal_id");
                $this->db->transComplete();
                if ($this->db->transStatus() === FALSE) {
                    $this->db->transRollback();
                } else {
                    $posted = true;
                    $this->db->transCommit();
                }
            }
        }
        $config['title'] = "Journal Entry Post";
        if ($posted) {
            $config['log_text'] = "[ Journal Entry successfully posted ]";
            $config['ref_link'] = 'erp/finance/journalentry/';
            $this->session->setFlashdata("op_success", "Journal Entry successfully posted");
        } else {
            $config['log_text'] = "[ Journal Entry failed to post ]";
            $config['ref_link'] = "erp/finance/journalentry/";
            $this->session->setFlashdata("op_error", "Journal Entry failed to post");
        }
        log_activity($config);
    }

    public function deleteJournalentry($journal_id)
    {
        $query = "DELETE FROM journal_entry WHERE journal_id=$journal_id";
        $this->db->query($query);
        $config['title'] = "Journal Entry Delete";
        if ($this->db->affectedRows() > 0) {
            $config['log_text'] = "[ Journal Entry successfully deleted ]";
            $config['ref_link'] = "erp/finance/journalentry/";
            $this->session->setFlashdata("op_success", "Journal Entry successfully deleted");
        } else {
            $config['log_text'] = "[ Journal Entry failed to delete ]";
            $config['ref_link'] = "erp/finance/journalentry/";
            $this->session->setFlashdata("op_error", "Journal Entry failed to delete");
        }
        log_activity($config);
    }

    public function journalEntryCount()
    {
        $query = "SELECT journal_entry.gl_acc_id ,CONCAT(gl_accounts.account_code,' - ',gl_accounts.account_name) AS gl_name,COUNT(journal_entry.gl_acc_id) AS gl_count,journal_entry.posted FROM `journal_entry` JOIN gl_accounts ON journal_entry.gl_acc_id=gl_accounts.gl_acc_id WHERE gl_accounts.created_by=1 AND posted=1 GROUP BY gl_acc_id";
        $result = $this->db->query($query)->getResultArray();
        return $result;
    }

    public function journalEntryIdCount()
    {
        $query = "SELECT COUNT(journal_id) as total_count FROM `journal_entry`";
        $result = $this->db->query($query)->getResultArray();
        return $result;
    }
}
