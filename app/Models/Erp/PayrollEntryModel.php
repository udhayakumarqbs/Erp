<?php

namespace App\Models\Erp;

use CodeIgniter\Model;

class PayrollEntryModel extends Model
{
    protected $table = 'payroll_entry';
    protected $primaryKey = 'pay_entry_id';

    protected $allowedFields = [
        'name',
        'payment_date',
        'payment_from',
        'payment_to',
        'processed',
    ];
    protected $db;
    protected $session;

    public function __construct()
    {
        $this->db = \Config\Database::connect();
        $this->session = \Config\Services::session();
    }
    public function get_dtconfig_payrolls()
    {
        $config = array(
            "columnNames" => ["sno", "name", "payment date", "from", "to", "processed", "action"],
            "sortable" => [0, 1, 1, 0, 0, 0, 0]
        );
        return json_encode($config);
    }

    public function get_payroll_datatable()
    {
        $columns = array(
            "name" => "name",
            "payment date" => "payment_date"
        );
        $limit = $_GET['limit'];
        $offset = $_GET['offset'];
        $search = $_GET['search'] ?? "";
        $orderby = isset($_GET['orderby']) ? strtoupper($_GET['orderby']) : "";
        $ordercol = isset($_GET['ordercol']) ? $columns[$_GET['ordercol']] : "";

        $query = "SELECT pay_entry_id,name,payment_date,payment_from,payment_to,CASE WHEN processed=0 THEN 'No' ELSE 'Yes' END AS processed FROM payroll_entry ";
        $count = "SELECT COUNT(pay_entry_id) AS total FROM payroll_entry ";
        $where = false;

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
                            <ul class="BB_UL flex">';
            if ($r['processed'] == 'No') {
                $action .= '<li><a href="' . url_to('erp.hr.payrolledit', $r['pay_entry_id']) . '" title="Edit" class="bg-success modalBtn"><i class="fa fa-pencil"></i> </a></li>
                                    <li><a href="' . url_to('erp.hr.payrollprocess', $r['pay_entry_id']) . '" title="Process" class="bg-warning"><i class="fa fa-refresh"></i> </a></li>';
            } else {
                $action .= '<li><a href="' . url_to('erp.hr.payrollview', $r['pay_entry_id']) . '" title="View" class="bg-warning"><i class="fa fa-eye"></i> </a></li>';
            }
            $action .= '<li><a href="' . url_to('erp.hr.payrolldelete', $r['pay_entry_id']) . '" title="Delete" class="bg-danger del-confirm"><i class="fa fa-trash"></i> </a></li>
                            </ul>
                        </div>
                    </div>';
            array_push(
                $m_result,
                array(
                    $r['pay_entry_id'],
                    $sno,
                    $r['name'],
                    $r['payment_date'],
                    $r['payment_from'],
                    $r['payment_to'],
                    $r['processed'],
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

    public function get_payrollentry_export($type)
    {
        $columns = array(
            "name" => "name",
            "payment date" => "payment_date"
        );
        $limit = $_GET['limit'];
        $offset = $_GET['offset'];
        $search = $_GET['search'] ?? "";
        $orderby = isset($_GET['orderby']) ? strtoupper($_GET['orderby']) : "";
        $ordercol = isset($_GET['ordercol']) ? $columns[$_GET['ordercol']] : "";

        $query = "SELECT name,payment_date,payment_from,payment_to,CASE WHEN processed=0 THEN 'No' ELSE 'Yes' END AS processed FROM payroll_entry ";
        $where = false;

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

    public function process_payroll($pay_entry_id)
    {
        $query = "SELECT payroll_process(?) AS error_flag ";
        $error = $this->db->query($query, array($pay_entry_id))->getRow()->error_flag;
        $config['title'] = "Payroll Process";
        if (empty($error)) {
            $config['log_text'] = "[ Payroll successfully processed ]";
            $config['ref_link'] = 'erp/hr/payrolls';
            $this->session->setFlashdata("op_success", "Payroll successfully processed");
        } else {
            $config['log_text'] = "[ Payroll failed to process ]";
            $config['ref_link'] = 'erp/hr/payrolls';
            $this->session->setFlashdata("op_error", "Payroll failed to process");
        }
        log_activity($config);
    }

    public function insert_payroll($post, $post1)
    {
        $inserted = false;
        $name = $post["name"];
        $payment_date = $post["payment_date"];
        $payment_from = $post["payment_from"];
        $payment_to = $post["payment_to"];

        $this->db->transBegin();
        $query = "INSERT INTO payroll_entry(name,payment_date,payment_from,payment_to) VALUES(?,?,?,?) ";
        $this->db->query($query, array($name, $payment_date, $payment_from, $payment_to));
        $pay_entry_id = $this->db->insertId();
        if (empty($pay_entry_id)) {
            $this->session->setFlashdata("op_error", "Payroll failed to create");
            return $inserted;
        }
        if (!empty($post1["deductions"])) {
            if (!$this->update_payroll_deduction($pay_entry_id, $post1)) {
                $this->db->transRollback();
                $this->session->setFlashdata("op_error", "Payroll failed to create");
                return $inserted;
            }
        }
        if (!empty($post1["additions"])) {
            if (!$this->update_payroll_addition($pay_entry_id, $post1)) {
                $this->db->transRollback();
                $this->session->setFlashdata("op_error", "Payroll failed to create");
                return $inserted;
            }
        }
        $this->db->transComplete();
        $config['title'] = "Payroll Insert";
        if ($this->db->transStatus() === FALSE) {
            $config['log_text'] = "[ Payroll failed to create ]";
            $config['ref_link'] = 'erp/hr/payrolls';
            $this->session->setFlashdata("op_error", "Payroll failed to create");
        } else {
            $inserted = true;
            $config['log_text'] = "[ Payroll successfully created ]";
            $config['ref_link'] = 'erp/hr/payrolls';
            $this->session->setFlashdata("op_success", "Payroll successfully created");
        }
        log_activity($config);
        return $inserted;
    }

    private function update_payroll_deduction($pay_entry_id, $post1)
    {
        $updated = true;
        $old_deduct_ids = array();
        $query = "SELECT deduct_id FROM payroll_deductions WHERE pay_entry_id=$pay_entry_id ";
        $result = $this->db->query($query)->getResultArray();
        foreach ($result as $row) {
            array_push($old_deduct_ids, $row['deduct_id']);
        }
        $ins = array();
        $del = array();
        $new_deduct_ids = explode(",", $post1["deductions"]);
        foreach ($new_deduct_ids as $new) {
            if (!in_array($new, $old_deduct_ids)) {
                array_push($ins, $new);
            }
        }
        foreach ($old_deduct_ids as $old) {
            if (!in_array($old, $new_deduct_ids)) {
                array_push($del, $old);
            }
        }
        if (!empty($del)) {
            $query = "DELETE FROM payroll_deductions WHERE deduct_id IN (" . implode($del) . ") AND pay_entry_id=$pay_entry_id ";
            $this->db->query($query);
            if ($this->db->affectedRows() <= 0) {
                $updated = false;
                return $updated;
            }
        }
        foreach ($ins as $deduct_id) {
            $query = "INSERT INTO payroll_deductions(pay_entry_id,deduct_id) VALUES(?,?) ";
            $this->db->query($query, array($pay_entry_id, $deduct_id));
            if (empty($this->db->insertId())) {
                $updated = false;
                break;
            }
        }
        return $updated;
    }

    private function update_payroll_addition($pay_entry_id, $post1)
    {
        $updated = true;
        $old_add_ids = array();
        $query = "SELECT add_id FROM payroll_additions WHERE pay_entry_id=$pay_entry_id ";
        $result = $this->db->query($query)->getResultArray();
        foreach ($result as $row) {
            array_push($old_add_ids, $row['add_id']);
        }
        $ins = array();
        $del = array();
        $new_add_ids = explode(",", $post1["additions"]);
        foreach ($new_add_ids as $new) {
            if (!in_array($new, $old_add_ids)) {
                array_push($ins, $new);
            }
        }
        foreach ($old_add_ids as $old) {
            if (!in_array($old, $new_add_ids)) {
                array_push($del, $old);
            }
        }
        if (!empty($del)) {
            $query = "DELETE FROM payroll_additions WHERE add_id IN (" . implode($del) . ") AND pay_entry_id=$pay_entry_id ";
            $this->db->query($query);
            if ($this->db->affectedRows() <= 0) {
                $updated = false;
                return $updated;
            }
        }
        foreach ($ins as $add_id) {
            $query = "INSERT INTO payroll_additions(pay_entry_id,add_id) VALUES(?,?) ";
            $this->db->query($query, array($pay_entry_id, $add_id));
            if (empty($this->db->insertId())) {
                $updated = false;
                break;
            }
        }
        return $updated;
    }

    public function get_payroll_entry_by_id($pay_entry_id)
    {
        $query = "SELECT payroll_entry.pay_entry_id,name,payment_date,payment_from,payment_to,processed,IFNULL(GROUP_CONCAT(deduct_id),'') AS deduction,IFNULL(GROUP_CONCAT(add_id),'') AS addition FROM payroll_entry LEFT JOIN payroll_deductions ON payroll_entry.pay_entry_id=payroll_deductions.pay_entry_id LEFT JOIN payroll_additions ON payroll_entry.pay_entry_id=payroll_additions.pay_entry_id WHERE payroll_entry.pay_entry_id=$pay_entry_id";
        $result = $this->db->query($query)->getRow();
        return $result;
    }

    public function update_payroll($pay_entry_id, $post, $post1)
    {
        $updated = false;
        $name = $post["name"];
        $payment_date = $post["payment_date"];
        $payment_from = $post["payment_from"];
        $payment_to = $post["payment_to"];

        $this->db->transBegin();
        $query = "UPDATE payroll_entry SET name=? , payment_date=? , payment_from=? , payment_to=? WHERE pay_entry_id=$pay_entry_id ";
        $this->db->query($query, array($name, $payment_date, $payment_from, $payment_to));

        if (!$this->update_payroll_deduction($pay_entry_id, $post1)) {
            $this->db->transRollback();
            $this->session->setFlashdata("op_error", "Payroll failed to update");
            return $updated;
        }
        if (!$this->update_payroll_addition($pay_entry_id, $post1)) {
            $this->db->transRollback();
            $this->session->setFlashdata("op_error", "Payroll failed to update");
            return $updated;
        }
        $this->db->transComplete();
        $config['title'] = "Payroll Update";
        if ($this->db->transStatus() === FALSE) {
            $config['log_text'] = "[ Payroll failed to update ]";
            $config['ref_link'] = 'erp/hr/payrolls';
            $this->session->setFlashdata("op_error", "Payroll failed to update");
        } else {
            $updated = true;
            $config['log_text'] = "[ Payroll successfully updated ]";
            $config['ref_link'] = 'erp/hr/payrolls';
            $this->session->setFlashdata("op_success", "Payroll successfully updated");
        }
        log_activity($config);
        return $updated;
    }


    public function delete_payroll($pay_entry_id)
    {
        $query = "DELETE FROM payroll_entry WHERE pay_entry_id=$pay_entry_id";
        $this->db->query($query);
        $config['title'] = "Payroll Delete";
        if ($this->db->affectedRows() > 0) {
            $config['log_text'] = "[ Payroll successfully deleted ]";
            $config['ref_link'] = 'erp/hr/payrolls';
            $this->session->setFlashdata("op_success", "Payroll successfully deleted");
        } else {
            $config['log_text'] = "[ Payroll failed to delete ]";
            $config['ref_link'] = 'erp/hr/payrolls';
            $this->session->setFlashdata("op_error", "Payroll failed to delete");
        }
        log_activity($config);
    }
}
