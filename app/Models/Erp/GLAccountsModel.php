<?php

namespace App\Models\Erp;

use CodeIgniter\Model;

class GLAccountsModel extends Model
{
    protected $table = 'gl_accounts';
    protected $primaryKey = 'gl_acc_id';
    protected $allowedFields = [
        'acc_group_id',
        'account_code',
        'account_name',
        'cash_flow',
        'order_num',
        'created_at',
        'created_by',
    ];

    public function insert_update_glaccounts()
    {
        $gl_acc_id = $this->input->post("gl_acc_id");
        if (empty($gl_acc_id)) {
            $this->insert_glaccount();
        } else {
            $this->update_glaccount($gl_acc_id);
        }
    }

    private function insert_glaccount()
    {
        $inserted = false;
        $period = date('Y-m-d', mktime(0, 0, 0, date('m'), 1, date('Y')));
        $acc_code = $this->input->post("account_code");
        $acc_name = $this->input->post("account_name");
        $acc_group = $this->input->post("account_group");
        $cashflow = $this->input->post("cash_flow");
        $ordernum = $this->input->post("order_num");
        $balance_fwd = $this->input->post("balance_fwd");
        $created_by = get_user_id();
        $this->db->transBegin();
        $query = "INSERT INTO gl_accounts(account_code,account_name,acc_group_id,cash_flow,order_num,created_at,created_by) VALUES(?,?,?,?,?,?,?) ";
        $this->db->query($query, array($acc_code, $acc_name, $acc_group, $cashflow, $ordernum, time(), $created_by));
        $gl_acc_id = $this->db->insertId();
        if (!empty($gl_acc_id)) {
            $query = "INSERT INTO general_ledger(gl_acc_id,period,actual_amt,balance_fwd) VALUES(?,?,?,?) ";
            $this->db->query($query, array($gl_acc_id, $period, 0.00, $balance_fwd));
            if (!empty($this->db->insertId())) {
                $inserted = true;
            } else {
                $this->db->transRollback();
            }
        }
        $this->db->transComplete();
        if ($this->db->transStatus() === FALSE) {
            $inserted = false;
            $this->db->transRollback();
        } else {
            if ($inserted) {
                $this->db->transCommit();
            } else {
                $this->db->transRollback();
            }
        }
        $config['title'] = "GL Account Insert";
        if ($inserted) {
            $config['log_text'] = "[ GL Account successfully created ]";
            $config['ref_link'] = 'erp/finance/glaccounts/';
            $this->session->set_flashdata("op_success", "GL Account successfully created");
        } else {
            $config['log_text'] = "[ GL Account failed to create ]";
            $config['ref_link'] = "erp/finance/glaccounts/";
            $this->session->set_flashdata("op_error", "GL Account failed to create");
        }
        log_activity($config);
    }

    private function update_glaccount($gl_acc_id)
    {
        $updated = false;
        $acc_code = $this->input->post("account_code");
        $acc_name = $this->input->post("account_name");
        $acc_group = $this->input->post("account_group");
        $cashflow = $this->input->post("cash_flow");
        $ordernum = $this->input->post("order_num");

        $query = "UPDATE gl_accounts SET account_code=? , account_name=? , acc_group_id=? , cash_flow=? , order_num=? WHERE gl_acc_id=$gl_acc_id";
        $this->db->query($query, array($acc_code, $acc_name, $acc_group, $cashflow, $ordernum));

        $config['title'] = "GL Account Update";
        if ($this->db->affectedRows() > 0) {
            $config['log_text'] = "[ GL Account successfully updated ]";
            $config['ref_link'] = 'erp/finance/glaccounts/';
            $this->session->set_flashdata("op_success", "GL Account successfully updated");
        } else {
            $config['log_text'] = "[ GL Account failed to update ]";
            $config['ref_link'] = "erp/finance/glaccounts/";
            $this->session->set_flashdata("op_error", "GL Account failed to update");
        }
        log_activity($config);
    }

    public function get_dtconfig_glaccounts()
    {
        $config = array(
            "columnNames" => ["sno", "account code", "account name", "account group", "cashflow", "action"],
            "sortable" => [0, 1, 1, 0, 0, 0],
            "filters" => ["group"]
        );
        return json_encode($config);
    }

    public function get_glaccounts_datatable()
    {
        $columns = array(
            "account code" => "account_code",
            "account name" => "account_name",
            "group" => "account_groups.acc_group_id"
        );
        $limit = $_GET['limit'];
        $offset = $_GET['offset'];
        $search = $_GET['search'] ?? "";
        $orderby = isset($_GET['orderby']) ? strtoupper($_GET['orderby']) : "";
        $ordercol = isset($_GET['ordercol']) ? $columns[$_GET['ordercol']] : "";

        $query = "SELECT gl_acc_id,account_code,account_name,account_groups.group_name,cash_flow FROM gl_accounts JOIN account_groups ON gl_accounts.acc_group_id=account_groups.acc_group_id ";
        $count = "SELECT COUNT(gl_acc_id) AS total FROM gl_accounts JOIN account_groups ON gl_accounts.acc_group_id=account_groups.acc_group_id";

        $where = false;
        $filter_1_col = isset($_GET['group']) ? $columns['group'] : "";
        $filter_1_val = $_GET['group'];

        if (!empty($filter_1_col) && $filter_1_val !== "") {
            $query .= " WHERE " . $filter_1_col . "=" . $filter_1_val . " ";
            $count .= " WHERE " . $filter_1_col . "=" . $filter_1_val . " ";
            $where = true;
        }

        if (!empty($search)) {
            if (!$where) {
                $query .= " WHERE ( account_code LIKE '%" . $search . "%' OR account_name LIKE '%" . $search . "%' ) ";
                $count .= " WHERE ( account_code LIKE '%" . $search . "%' OR account_name LIKE '%" . $search . "%' ) ";
                $where = true;
            } else {
                $query .= " AND ( account_code LIKE '%" . $search . "%' OR account_name LIKE '%" . $search . "%' ) ";
                $count .= " AND ( account_code LIKE '%" . $search . "%' OR account_name LIKE '%" . $search . "%' ) ";
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
                                <li><a href="#" data-ajax-url="' . base_url() . 'erp/finance/ajax_fetch_glaccount/' . $r['gl_acc_id'] . '" title="Edit" class="bg-success modalBtn"><i class="fa fa-pencil"></i> </a></li>
                                <li><a href="' . base_url() . 'erp/finance/glaccountdelete/' . $r['gl_acc_id'] . '" title="Delete" class="bg-danger del-confirm"><i class="fa fa-trash"></i> </a></li>
                            </ul>
                        </div>
                    </div>';
            $cashflow = '<span class="st ' . $this->cashflow_bg[$r['cash_flow']] . '" >' . $this->cashflow[$r['cash_flow']] . '</span>';
            array_push(
                $m_result,
                array(
                    $r['gl_acc_id'],
                    $sno,
                    $r['account_code'],
                    $r['account_name'],
                    $r['group_name'],
                    $cashflow,
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

    public function delete_glaccount($gl_acc_id)
    {
        $deleted = false;
        $check1 = $this->db->query("SELECT COUNT(gl_acc_id) AS total FROM journal_entry WHERE gl_acc_id=$gl_acc_id")->getRow();
        $check2 = $this->db->query("SELECT COUNT(gl_acc_id) AS total FROM bankaccounts WHERE gl_acc_id=$gl_acc_id")->getRow();
        if ($check1->total <= 0 && $check2->total <= 0) {
            $this->db->transBegin();
            $query = "DELETE FROM gl_accounts WHERE gl_acc_id=$gl_acc_id";
            $this->db->query($query);
            $query = "DELETE FROM general_ledger WHERE gl_acc_id=$gl_acc_id";
            $this->db->query($query);
            $this->db->transComplete();
            if ($this->db->transStatus() === FALSE) {
                $this->db->transRollback();
            } else {
                $deleted = true;
                $this->db->transCommit();
            }
        }
        $config['title'] = "GL Account Delete";
        if ($deleted) {
            $config['log_text'] = "[ GL Account successfully deleted ]";
            $config['ref_link'] = 'erp/finance/glaccounts/';
            $this->session->set_flashdata("op_success", "GL Account successfully deleted");
        } else {
            $config['log_text'] = "[ GL Account failed to delete ]";
            $config['ref_link'] = "erp/finance/glaccounts/";
            $this->session->set_flashdata("op_error", "GL Account failed to delete");
        }
        log_activity($config);
    }

    public function get_glaccounts_export($type)
    {
        $columns = array(
            "account code" => "account_code",
            "account name" => "account_name",
            "group" => "account_groups.acc_group_id"
        );
        $limit = $_GET['limit'];
        $offset = $_GET['offset'];
        $search = $_GET['search'] ?? "";
        $orderby = isset($_GET['orderby']) ? strtoupper($_GET['orderby']) : "";
        $ordercol = isset($_GET['ordercol']) ? $columns[$_GET['ordercol']] : "";

        $query = "SELECT account_code,account_name,account_groups.group_name, CASE ";
        foreach ($this->cashflow as $key => $value) {
            $query .= " WHEN cash_flow=" . $key . " THEN '" . $value . "' ";
        }
        $query .= " END AS cashflow FROM gl_accounts JOIN account_groups ON gl_accounts.acc_group_id=account_groups.acc_group_id ";

        $where = false;
        $filter_1_col = isset($_GET['group']) ? $columns['group'] : "";
        $filter_1_val = $_GET['group'];

        if (!empty($filter_1_col) && $filter_1_val !== "") {
            $query .= " WHERE " . $filter_1_col . "=" . $filter_1_val . " ";
            $where = true;
        }

        if (!empty($search)) {
            if (!$where) {
                $query .= " WHERE ( account_code LIKE '%" . $search . "%' OR account_name LIKE '%" . $search . "%' ) ";
                $where = true;
            } else {
                $query .= " AND ( account_code LIKE '%" . $search . "%' OR account_name LIKE '%" . $search . "%' ) ";
            }
        }

        if (!empty($ordercol) && !empty($orderby)) {
            $query .= " ORDER BY " . $ordercol . " " . $orderby;
        }
        $result = $this->db->query($query)->getResultArray();
        return $result;
    }
}
