<?php

namespace App\Libraries\Finance;

use CodeIgniter\HTTP\RequestInterface;

abstract class AbstractAutomation
{
    const AFTER_INSERT = 1;
    const AFTER_UPDATE = 2;
    const AFTER_DELETE = 3;

    protected $ci;
    protected $rules;
    protected $amount;

    protected $journal_rel_id;
    protected $journal_rel_to;

    protected $narration;
    protected $db;
    protected $session;

    public function __construct($rel_id, $rel_to, RequestInterface $request)
    {
        $this->db = \Config\Database::connect();
        $this->session = \Config\Services::session();
        $this->ci = service('request');
        $this->journal_rel_id = $rel_id;
        $this->journal_rel_to = $rel_to;
        $this->narration = "[ Finance Automation added this entry ]";
        $this->ci->helpers(['erp']);
    }

    public abstract function getAmount();
    public abstract function getTransactionRules();
    public abstract function processResult($code);

    public function doTransaction($type)
    {
        $transaction_done = false;
        $this->rules = $this->getTransactionRules();
        if (empty($this->rules->active) || $this->rules->active === "0") {
            return $transaction_done;
        }
        $this->amount = $this->getAmount();
        switch ($type) {
            case self::AFTER_INSERT:
                $transaction_done = $this->afterInsert();
                break;
            case self::AFTER_UPDATE:
                $transaction_done = $this->afterUpdate();
                break;
            case self::AFTER_DELETE:
                $transaction_done = $this->afterDelete();
                break;
            default:
                break;
        }
        return $transaction_done;
    }

    protected function afterInsert($config = [])
    {
        $created_at = time();
        $created_by = get_user_id();
        $this->db->query("SET autocommit=0");
        $this->db->query("START TRANSACTION");
        $this->db->query("SAVEPOINT start_mark");
        $query = " SELECT finance_automation_insert(?,?,?,?,?,?,?,?,?) AS result ";
        $result = $this->db->query($query, array($this->amount, $this->rules->debit_gl_account, $this->rules->credit_gl_account, $this->narration, $created_at, $created_by, $this->journal_rel_to, $this->journal_rel_id, $this->rules->auto_posting))->getRow();
        if ($result->result == 1) {
            $this->db->query("ROLLBACK TO SAVEPOINT start_mark");
        } else {
            $this->db->query("COMMIT");
        }
        $this->db->query("SET autocommit=1");
        $this->processResult($result->result);
    }

    protected function afterUpdate($config = [])
    {
        $created_at = time();
        $created_by = get_user_id();
        $this->db->query("SET autocommit=0");
        $this->db->query("START TRANSACTION");
        $this->db->query("SAVEPOINT start_mark");
        $query = " SELECT finance_automation_insert(?,?,?,?,?,?,?,?,?) AS result ";
        $result = $this->db->query($query, array($this->amount, $this->rules->debit_gl_account, $this->rules->credit_gl_account, $this->narration, $created_at, $created_by, $this->journal_rel_to, $this->journal_rel_id, $this->rules->auto_posting))->getRow();
        if ($result->result == 1) {
            $this->db->query("ROLLBACK TO SAVEPOINT start_mark");
        } else {
            $this->db->query("COMMIT");
        }
        $this->db->query("SET autocommit=1");
        $this->processResult($result->result);
    }

    protected function afterDelete($config = [])
    {
        $created_at = time();
        $created_by = get_user_id();
        $this->db->query("SET autocommit=0");
        $this->db->query("START TRANSACTION");
        $this->db->query("SAVEPOINT start_mark");
        $query = " SELECT finance_automation_insert(?,?,?,?,?,?,?,?,?) AS result ";
        $result = $this->db->query($query, array($this->amount, $this->rules->debit_gl_account, $this->rules->credit_gl_account, $this->narration, $created_at, $created_by, $this->journal_rel_to, $this->journal_rel_id, $this->rules->auto_posting))->getRow();
        if ($result->result == 1) {
            $this->db->query("ROLLBACK TO SAVEPOINT start_mark");
        } else {
            $this->db->query("COMMIT");
        }
        $this->db->query("SET autocommit=1");
        $this->processResult($result->result);
    }
}
