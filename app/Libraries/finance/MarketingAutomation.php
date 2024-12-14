<?php

namespace App\Libraries\Finance;

class MarketingAutomation extends AbstractAutomation
{


    protected $db;
    protected $session;

    public function __construct($rel_id, $rel_to)
    {
        $this->db = \Config\Database::connect();
        $this->session = \Config\Services::session();
        $this->narration = "[ Finance Automation added Marketing Entry ]";
    }


    public function processResult($code)
    {
        $config['title'] = "Finance Automation";
        $config['log_text'] = "[ Finance Automation added Marketing Entry ; ID=" . $this->journal_rel_id . " ]";
        $config['ref_link'] = "";
        log_activity($config);
    }

    public function getAmount()
    {

        $query = "SELECT amount FROM marketing WHERE marketing_id = ?";

        $result = $this->db->query($query, [$this->journal_rel_id])->getRow();

        if ($result) {
            return $result->amount;
        }

        return 0; 
    }

    public function getTransactionRules()
    {
        $query = "SELECT auto_transaction.debit_gl_account,auto_transaction.credit_gl_account,
            auto_transaction.auto_posting,auto_transaction.active FROM auto_trans_list 
            JOIN auto_transaction ON auto_trans_list.trans_id=auto_transaction.trans_id 
            WHERE auto_trans_list.related_to='marketing' ";
        $result = $this->db->query($query)->getRow();
        return $result;
    }
}
