<?php

namespace App\Models\Erp;

use CodeIgniter\Model;

class BankAccountsModel extends Model
{
    protected $table = 'bankaccounts';
    protected $primaryKey = 'bank_id';
    protected $allowedFields = [
        'gl_acc_id',
        'bank_name',
        'bank_acc_no',
        'bank_code',
        'branch',
        'address',
        'created_at',
        'created_by',
    ];

    public function insert_update_bankaccounts()
    {
        $bank_id = $this->input->post("bank_id");
        if (empty($bank_id)) {
            $this->insert_bankaccount();
        } else {
            $this->update_bankaccount($bank_id);
        }
    }

    private function insert_bankaccount()
    {
        $gl_acc_id = $this->input->post("gl_acc_id");
        $bank_name = $this->input->post("bank_name");
        $bank_acc_no = $this->input->post("bank_acc_no");
        $bank_code = $this->input->post("bank_code");
        $branch = $this->input->post("branch");
        $address = $this->input->post("address");
        $created_by = get_user_id();

        $query = "INSERT INTO bankaccounts(gl_acc_id,bank_name,bank_acc_no,bank_code,branch,address,created_at,created_by) VALUES(?,?,?,?,?,?,?,?) ";
        $this->db->query($query, array($gl_acc_id, $bank_name, $bank_acc_no, $bank_code, $branch, $address, time(), $created_by));

        $config['title'] = "Bank Account Insert";
        if (!empty($this->db->insertId())) {
            $config['log_text'] = "[ Bank Account successfully created ]";
            $config['ref_link'] = 'erp/finance/bankaccounts/';
            $this->session->setFlashdata("op_success", "Bank Account successfully created");
        } else {
            $config['log_text'] = "[ Bank Account failed to create ]";
            $config['ref_link'] = "erp/finance/bankaccounts/";
            $this->session->setFlashdata("op_error", "Bank Account failed to create");
        }
        log_activity($config);
    }

    private function update_bankaccount($bank_id)
    {
        $gl_acc_id = $this->input->post("gl_acc_id");
        $bank_name = $this->input->post("bank_name");
        $bank_acc_no = $this->input->post("bank_acc_no");
        $bank_code = $this->input->post("bank_code");
        $branch = $this->input->post("branch");
        $address = $this->input->post("address");

        $query = "UPDATE bankaccounts SET gl_acc_id=? , bank_name=? , bank_acc_no=? , bank_code=? , branch=? , address=? WHERE bank_id=$bank_id ";
        $this->db->query($query, array($gl_acc_id, $bank_name, $bank_acc_no, $bank_code, $branch, $address));

        $config['title'] = "Bank Account Update";
        if ($this->db->affectedRows() > 0) {
            $config['log_text'] = "[ Bank Account successfully updated ]";
            $config['ref_link'] = 'erp/finance/bankaccounts/';
            $this->session->setFlashdata("op_success", "Bank Account successfully updated");
        } else {
            $config['log_text'] = "[ Bank Account failed to update ]";
            $config['ref_link'] = "erp/finance/bankaccounts/";
            $this->session->setFlashdata("op_error", "Bank Account failed to update");
        }
        log_activity($config);
    }

    public function get_dtconfig_bankaccounts()
    {
        $config = array(
            "columnNames" => ["sno", "bank name", "bank account no", "bank code", "gl account", "branch", "action"],
            "sortable" => [0, 1, 1, 0, 0, 0],
        );
        return json_encode($config);
    }

    public function get_bankaccounts_datatable()
    {
        $columns = array(
            "bank code" => "bank_code",
            "bank name" => "bank_name",
        );
        $limit = $_GET['limit'];
        $offset = $_GET['offset'];
        $search = $_GET['search'] ?? "";
        $orderby = isset($_GET['orderby']) ? strtoupper($_GET['orderby']) : "";
        $ordercol = isset($_GET['ordercol']) ? $columns[$_GET['ordercol']] : "";

        $query = "SELECT bank_id,bank_name,bank_acc_no,bank_code,CONCAT(gl_accounts.account_code,' ',gl_accounts.account_name) AS gl_account,branch FROM bankaccounts JOIN gl_accounts ON bankaccounts.gl_acc_id=gl_accounts.gl_acc_id ";
        $count = "SELECT COUNT(bank_id) AS total FROM bankaccounts JOIN gl_accounts ON bankaccounts.gl_acc_id=gl_accounts.gl_acc_id ";

        $where = false;

        if (!empty($search)) {
            if (!$where) {
                $query .= " WHERE ( bank_code LIKE '%" . $search . "%' OR bank_name LIKE '%" . $search . "%' ) ";
                $count .= " WHERE ( bank_code LIKE '%" . $search . "%' OR bank_name LIKE '%" . $search . "%' ) ";
                $where = true;
            } else {
                $query .= " AND ( bank_code LIKE '%" . $search . "%' OR bank_name LIKE '%" . $search . "%' ) ";
                $count .= " AND ( bank_code LIKE '%" . $search . "%' OR bank_name LIKE '%" . $search . "%' ) ";
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
                                <li><a href="#" data-ajax-url="' . base_url() . 'erp/finance/ajax_fetch_bankaccount/' . $r['bank_id'] . '" title="Edit" class="bg-success modalBtn"><i class="fa fa-pencil"></i> </a></li>
                                <li><a href="' . base_url() . 'erp/finance/bankaccountdelete/' . $r['bank_id'] . '" title="Delete" class="bg-danger del-confirm"><i class="fa fa-trash"></i> </a></li>
                            </ul>
                        </div>
                    </div>';

            array_push(
                $m_result,
                array(
                    $r['bank_id'],
                    $sno,
                    $r['bank_name'],
                    $r['bank_acc_no'],
                    $r['bank_code'],
                    $r['gl_account'],
                    $r['branch'],
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

    //NOT IMPLEMENTED
    public function delete_bankaccount($bank_id)
    {
    }

    public function get_bankaccounts_export($type)
    {
        $columns = array(
            "bank code" => "bank_code",
            "bank name" => "bank_name",
        );
        $limit = $_GET['limit'];
        $offset = $_GET['offset'];
        $search = $_GET['search'] ?? "";
        $orderby = isset($_GET['orderby']) ? strtoupper($_GET['orderby']) : "";
        $ordercol = isset($_GET['ordercol']) ? $columns[$_GET['ordercol']] : "";

        $query = "SELECT bank_name,bank_acc_no,bank_code,CONCAT(gl_accounts.account_code,' ',gl_accounts.account_name) AS gl_account,branch,address FROM bankaccounts JOIN gl_accounts ON bankaccounts.gl_acc_id=gl_accounts.gl_acc_id ";
        $where = false;

        if (!empty($search)) {
            if (!$where) {
                $query .= " WHERE ( bank_code LIKE '%" . $search . "%' OR bank_name LIKE '%" . $search . "%' ) ";
                $where = true;
            } else {
                $query .= " AND ( bank_code LIKE '%" . $search . "%' OR bank_name LIKE '%" . $search . "%' ) ";
            }
        }

        if (!empty($ordercol) && !empty($orderby)) {
            $query .= " ORDER BY " . $ordercol . " " . $orderby;
        }

        $result = $this->db->query($query)->getResultArray();
        return $result;
    }
}
