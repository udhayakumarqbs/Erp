<?php

namespace App\Models\Erp;

use CodeIgniter\Model;

class AutoTransactionModel extends Model
{
    protected $table = 'auto_transaction';
    protected $primaryKey = 'autotrans_id';
    protected $allowedFields = [
        'trans_id',
        'debit_gl_account',
        'credit_gl_account',
        'auto_posting',
        'active',
    ];
    public $db;
    public $session;

    public function __construct()
    {
        $this->db = \Config\Database::connect();
        $this->session = \Config\Services::session();
    }
    public function get_dtconfig_automation()
    {
        $config = array(
            "columnNames" => ["sno", "transaction name", "debit account", "credit account", "auto post", "active", "action"],
            "sortable" => [0, 1, 0, 0, 0, 0, 0],
            "filters" => ["auto_post"]
        );
        return json_encode($config);
    }

    public function get_automation_datatable()
    {
        $columns = array(
            "transaction name" => "auto_trans_list.transaction_name",
            "auto_post" => "auto_posting"
        );
        $limit = $_GET['limit'];
        $offset = $_GET['offset'];
        $search = $_GET['search'] ?? "";
        $orderby = isset($_GET['orderby']) ? strtoupper($_GET['orderby']) : "";
        $ordercol = isset($_GET['ordercol']) ? $columns[$_GET['ordercol']] : "";

        $query = "SELECT autotrans_id,auto_trans_list.transaction_name,CONCAT(gl1.account_code,' ',gl1.account_name) AS debit_account,CONCAT(gl2.account_code,' ',gl2.account_name) AS credit_account,auto_posting,active FROM auto_transaction JOIN gl_accounts gl1 ON auto_transaction.debit_gl_account=gl1.gl_acc_id JOIN gl_accounts gl2 ON auto_transaction.credit_gl_account=gl2.gl_acc_id JOIN auto_trans_list ON auto_transaction.trans_id=auto_trans_list.trans_id ";
        $count = "SELECT COUNT(autotrans_id) AS total FROM auto_transaction JOIN gl_accounts gl1 ON auto_transaction.debit_gl_account=gl1.gl_acc_id JOIN gl_accounts gl2 ON auto_transaction.credit_gl_account=gl2.gl_acc_id JOIN auto_trans_list ON auto_transaction.trans_id=auto_trans_list.trans_id ";
        $where = false;

        $filter_2_col = isset($_GET['auto_post']) ? $columns['auto_post'] : "";
        $filter_2_val = $_GET['auto_post'];

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

        if (!empty($search)) {
            if (!$where) {
                $query .= " WHERE  auto_trans_list.transaction_name LIKE '%" . $search . "%' ";
                $count .= " WHERE  auto_trans_list.transaction_name LIKE '%" . $search . "%' ";
                $where = true;
            } else {
                $query .= " AND  auto_trans_list.transaction_name LIKE '%" . $search . "%' ";
                $count .= " AND  auto_trans_list.transaction_name LIKE '%" . $search . "%' ";
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
                                <li><a href="#" data-ajax-url="' . url_to('erp.finance.ajaxfetchautomation', $r['autotrans_id']) . '"  title="Edit" class="bg-success modalBtn"><i class="fa fa-pencil"></i> </a></li>
                                <li><a href="' . url_to('erp.finance.automationdelete', $r['autotrans_id']) . '" title="Delete" class="bg-danger del-confirm"><i class="fa fa-trash"></i> </a></li>
                            </ul>
                        </div>
                    </div>';
            $active = '
                    <label class="toggle active-toggler-1" data-ajax-url="' . url_to('erp.finance.ajaxautomationactive', $r['autotrans_id']) . '" >
                        <input type="checkbox" ';
            if ($r['active'] == 1) {
                $active .= ' checked ';
            }
            $active .= ' />
                        <span class="togglebar"></span>
                    </label>';
            $auto_post = '<span class="st ';
            if ($r['auto_posting'] == 1) {
                $auto_post .= ' st_success" > Yes</span>';
            } else {
                $auto_post .= ' st_dark" > No</span>';
            }
            array_push(
                $m_result,
                array(
                    $r['autotrans_id'],
                    $sno,
                    $r['transaction_name'],
                    $r['debit_account'],
                    $r['credit_account'],
                    $auto_post,
                    $active,
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

    public function get_autotranslist()
    {
        $query = "SELECT trans_id,transaction_name FROM auto_trans_list";
        $result = $this->db->query($query)->getResultArray();
        return $result;
    }

    public function insert_update_automation($autotrans_id, $post)
    {
        if (empty($autotrans_id)) {
            $this->insert_automation($post);
        } else {
            $this->update_automation($autotrans_id, $post);
        }
    }

    private function insert_automation($post)
    {
        $trans_id = $post["trans_id"];
        $debit_account = $post["debit_gl_account"];
        $credit_account = $post["credit_gl_account"];
        $auto_posting = $post["auto_posting"] ?? 0;

        if ($debit_account === $credit_account) {
            $this->session->setFlashdata("op_error", "Debit and Credit account should be different");
            return;
        }

        $check = $this->db->query("SELECT trans_id FROM auto_transaction WHERE trans_id=$trans_id")->getRow();
        if (!empty($check)) {
            $this->session->setFlashdata("op_error", "Duplicate automation for same transaction");
            return;
        }
        $query = "INSERT INTO auto_transaction(trans_id,debit_gl_account,credit_gl_account,auto_posting) VALUES(?,?,?,?) ";
        $this->db->query($query, array($trans_id, $debit_account, $credit_account, $auto_posting));

        $config['title'] = "Automation Insert";
        if (!empty($this->db->insertId())) {
            $config['log_text'] = "[ Automation successfully created ]";
            $config['ref_link'] = url_to("erp.finance.automation");
            $this->session->setFlashdata("op_success", "Automation successfully created");
        } else {
            $config['log_text'] = "[ Automation failed to create ]";
            $config['ref_link'] = url_to("erp.finance.automation");
            $this->session->setFlashdata("op_error", "Automation failed to create");
        }
        log_activity($config);
    }

    private function update_automation($autotrans_id, $post)
    {
        $trans_id = $post["trans_id"];
        $debit_account = $post["debit_gl_account"];
        $credit_account = $post["credit_gl_account"];
        $auto_posting = $post["auto_posting"] ?? 0;

        if ($debit_account === $credit_account) {
            $this->session->setFlashdata("op_error", "Debit and Credit account should be different");
            return;
        }

        $check = $this->db->query("SELECT trans_id FROM auto_transaction WHERE trans_id=$trans_id AND autotrans_id<>$autotrans_id")->getRow();
        if (!empty($check)) {
            $this->session->setFlashdata("op_error", "Duplicate automation for same transaction");
            return;
        }

        $query = "UPDATE auto_transaction SET trans_id=? , debit_gl_account=? , credit_gl_account=? , auto_posting=?  WHERE autotrans_id=$autotrans_id ";
        $this->db->query($query, array($trans_id, $debit_account, $credit_account, $auto_posting));

        $config['title'] = "Automation Update";
        if ($this->db->affectedRows() > 0) {
            $config['log_text'] = "[ Automation successfully updated ]";
            $config['ref_link'] = url_to("erp.finance.automation");
            $this->session->setFlashdata("op_success", "Automation successfully updated");
        } else {
            $config['log_text'] = "[ Automation failed to update ]";
            $config['ref_link'] = url_to("erp.finance.automation");
            $this->session->setFlashdata("op_error", "Automation failed to update");
        }
        log_activity($config);
    }

    public function delete_automation($autotrans_id)
    {
        $query = "DELETE FROM auto_transaction WHERE autotrans_id=$autotrans_id";
        $this->db->query($query);
        $config['title'] = "Automation Delete";
        if ($this->db->affectedRows() > 0) {
            $config['log_text'] = "[ Automation successfully deleted ]";
            $config['ref_link'] = url_to("erp.finance.automation");
            $this->session->setFlashdata("op_success", "Automation successfully deleted");
        } else {
            $config['log_text'] = "[ Automation failed to delete ]";
            $config['ref_link'] = url_to("erp.finance.automation");
            $this->session->setFlashdata("op_error", "Automation failed to delete");
        }
        log_activity($config);
    }

    public function get_automation_export($type)
    {
        $columns = array(
            "transaction name" => "auto_trans_list.transaction_name",
            "auto_post" => "auto_posting"
        );
        $limit = $_GET['limit'];
        $offset = $_GET['offset'];
        $search = $_GET['search'] ?? "";
        $orderby = isset($_GET['orderby']) ? strtoupper($_GET['orderby']) : "";
        $ordercol = isset($_GET['ordercol']) ? $columns[$_GET['ordercol']] : "";

        $query = "SELECT auto_trans_list.transaction_name,CONCAT(gl1.account_code,' ',gl1.account_name) AS debit_account,CONCAT(gl2.account_code,' ',gl2.account_name) AS credit_account,CASE WHEN auto_posting=1 THEN 'Yes' ELSE 'No' END AS auto_posting ,CASE WHEN active=1 THEN 'Yes' ELSE 'No' END AS active FROM auto_transaction JOIN gl_accounts gl1 ON auto_transaction.debit_gl_account=gl1.gl_acc_id JOIN gl_accounts gl2 ON auto_transaction.credit_gl_account=gl2.gl_acc_id JOIN auto_trans_list ON auto_transaction.trans_id=auto_trans_list.trans_id ";
        $where = false;

        $filter_2_col = isset($_GET['auto_post']) ? $columns['auto_post'] : "";
        $filter_2_val = $_GET['auto_post'];

        if (!empty($filter_2_col) && $filter_2_val !== "") {
            if ($where) {
                $query .= " AND " . $filter_2_col . "=" . $filter_2_val . " ";
            } else {
                $query .= " WHERE " . $filter_2_col . "=" . $filter_2_val . " ";
                $where = true;
            }
        }

        if (!empty($search)) {
            if (!$where) {
                $query .= " WHERE auto_trans_list.transaction_name LIKE '%" . $search . "%' ";
                $where = true;
            } else {
                $query .= " AND auto_trans_list.transaction_name LIKE '%" . $search . "%' ";
            }
        }

        if (!empty($ordercol) && !empty($orderby)) {
            $query .= " ORDER BY " . $ordercol . " " . $orderby;
        }
        $result = $this->db->query($query)->getResultArray();
        return $result;
    }
}
