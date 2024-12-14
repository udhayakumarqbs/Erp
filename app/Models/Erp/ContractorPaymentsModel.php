<?php

namespace App\Models\Erp;

use CodeIgniter\Model;

class ContractorPaymentsModel extends Model
{
    protected $table = 'contractor_payments';
    protected $primaryKey = 'contractor_pay_id';

    protected $allowedFields = [
        'project_wgrp_id',
        'payment_id',
        'amount',
        'paid_on',
        'transaction_id',
        'notes',
    ];


    public function get_contractor_payment_datatable()
    {
        $project_wgrp_id = $this->input->get("project_wgrp_id");
        $columns = array(
            "payment mode" => "payment_modes.name",
            "amount" => "amount",
            "paid on" => "paid_on"
        );

        $limit = $_GET['limit'];
        $offset = $_GET['offset'];
        $search = $_GET['search'] ?? "";

        $orderby = isset($_GET['orderby']) ? strtoupper($_GET['orderby']) : "";
        $ordercol = isset($_GET['ordercol']) ? $columns[$_GET['ordercol']] : "";
        $query = "SELECT contractor_pay_id,payment_modes.name,amount,paid_on,transaction_id FROM contractor_payments JOIN payment_modes ON contractor_payments.payment_id=payment_modes.payment_id WHERE project_wgrp_id=$project_wgrp_id ";
        $count = "SELECT COUNT(contractor_pay_id) AS total FROM contractor_payments JOIN payment_modes ON contractor_payments.payment_id=payment_modes.payment_id WHERE project_wgrp_id=$project_wgrp_id ";
        $where = true;

        if (!empty($search)) {
            if (!$where) {
                $query .= " WHERE payment_modes.name LIKE '%" . $search . "%' ";
                $count .= " WHERE payment_modes.name LIKE '%" . $search . "%' ";
                $where = true;
            } else {
                $query .= " AND payment_modes.name LIKE '%" . $search . "%' ";
                $count .= " AND payment_modes.name LIKE '%" . $search . "%' ";
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
                                <li><a href="#" data-ajax-url="' . url_to('erp.project/ajaxfetchcontractorpayment', $r['contractor_pay_id']) . '" title="Edit" class="bg-success modalBtn"><i class="fa fa-pencil"></i> </a></li>
                                <li><a href="' . url_to('erp.project.contractorpaymentdelete', $project_wgrp_id, $r['contractor_pay_id']) . '" title="Delete" class="bg-danger del-confirm"><i class="fa fa-trash"></i> </a></li>
                            </ul>
                        </div>
                    </div>';
            array_push(
                $m_result,
                array(
                    $r['contractor_pay_id'],
                    $sno,
                    $r['name'],
                    $r['amount'],
                    $r['paid_on'],
                    $r['transaction_id'],
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

    public function insert_update_payment($project_id, $project_wgrp_id, $contractor_pay_id, $post)
    {

        if (empty($contractor_pay_id)) {
            $this->insert_payment($project_id, $project_wgrp_id, $post);
        } else {
            $this->update_payment($project_id, $project_wgrp_id, $contractor_pay_id, $post);
        }
    }

    private function insert_payment($project_id, $project_wgrp_id, $post)
    {
        $amount = $post["amount"];
        $paid_on = $post["paid_on"];
        $payment_id = $post["payment_id"];
        $transaction_id = $post["transaction_id"];
        $notes = $post["notes"];

        $this->db->transBegin();
        $query = "INSERT INTO contractor_payments(project_wgrp_id,payment_id,amount,paid_on,transaction_id,notes) VALUES(?,?,?,?,?,?) ";
        $this->db->query($query, array($project_wgrp_id, $payment_id, $amount, $paid_on, $transaction_id, $notes));
        if (empty($this->db->insertId())) {
            $this->db->transRollback();
            $this->session->setFlashdata("op_error", "Contractor Payment failed to create");
            return;
        }
        $query = " UPDATE project_workgroup SET c_status=
        CASE 
        WHEN (paid_till+?)<=0 THEN 0
        WHEN amount <= (paid_till+?) THEN 2
        WHEN amount > (paid_till+?) THEN 1
        END, paid_till=paid_till+? WHERE project_wgrp_id=? ";

        $this->db->query($query, array($amount, $amount, $amount, $amount, $project_wgrp_id));
        $this->db->transComplete();
        $config['title'] = "Contractor Payment Insert";
        if ($this->db->transStatus() === FALSE) {
            $this->db->transRollback();
            $config['log_text'] = "[ Contractor Payment failed to create ]";
            $config['ref_link'] = "erp/project/projectview/" . $project_id;
            $this->session->setFlashdata("op_error", "Contractor Payment failed to create");
        } else {
            $this->db->transCommit();
            $config['log_text'] = "[ Contractor Payment successfully created ]";
            $config['ref_link'] = "erp/project/projectview/" . $project_id;
            $this->session->setFlashdata("op_success", "Contractor Payment successfully created");
        }
        log_activity($config);
    }

    private function update_payment($project_id, $project_wgrp_id, $contractor_pay_id, $post)
    {
        $amount = $post["amount"];
        $paid_on = $post["paid_on"];
        $payment_id = $post["payment_id"];
        $transaction_id = $post["transaction_id"];
        $notes = $post["notes"];

        $this->db->transBegin();
        $sale_amount = $this->db->query("SELECT (SUM(amount)+trans_charge-discount) AS amount FROM sale_invoice JOIN sale_order_items ON sale_invoice.invoice_id=sale_order_items.invoice_id WHERE sale_invoice.invoice_id=$invoice_id")->getRow()->amount;
        $old_amount = $this->db->query("SELECT amount FROM sale_payments WHERE sale_pay_id=$sale_pay_id")->getRow()->amount;
        $diff = $amount - $old_amount;
        $query = "UPDATE sale_payments SET invoice_id=? , payment_id=? , amount=? , paid_on=? , transaction_id=? , notes=? WHERE sale_pay_id=$sale_pay_id ";
        $this->db->query($query, array($invoice_id, $payment_id, $amount, $paid_on, $transaction_id, $notes));
        if ($diff != 0) {
            $query = " UPDATE sale_invoice SET status=
            CASE 
            WHEN (paid_till+?)<=0 THEN 0
            WHEN $sale_amount <= (paid_till+?) THEN 1
            WHEN $sale_amount > (paid_till+?) THEN 2
            END, paid_till=paid_till+?  WHERE invoice_id=? ";
            $this->db->query($query, array($diff, $diff, $diff, $diff, $invoice_id));
        }
        $this->db->transComplete();

        $config['title'] = "Sale Payment Update";
        if ($this->db->transStatus() === FALSE) {
            $this->db->transRollback();
            $config['log_text'] = "[ Sale Payment failed to update ]";
            $config['ref_link'] = url_to("erp.sale.invoiceview", $invoice_id);
            $this->session->setFlashdata("op_error", "Sale Payment failed to update");
        } else {
            $this->db->transCommit();
            $config['log_text'] = "[ Sale Payment successfully updated ]";
            $config['ref_link'] = url_to("erp.sale.invoiceview", $invoice_id);
            $this->session->setFlashdata("op_success", "Sale Payment successfully updated");
        }
        log_activity($config);
    }

    public function delete_payment($project_id, $project_wgrp_id, $contractor_pay_id)
    {
        $this->db->transBegin();
        $old_amount = $this->db->query("SELECT amount FROM contractor_payments WHERE contractor_pay_id=$contractor_pay_id")->getRow()->amount;
        $query = "DELETE FROM contractor_payments WHERE contractor_pay_id=$contractor_pay_id ";
        $this->db->query($query);
        $query = " UPDATE project_workgroup SET c_status=
            CASE 
            WHEN (paid_till-?)<=0 THEN 0
            WHEN amount <= (paid_till-?) THEN 2
            WHEN amount > (paid_till-?) THEN 1
            END, paid_till=paid_till-?  WHERE project_wgrp_id=? ";
        $this->db->query($query, array($old_amount, $old_amount, $old_amount, $old_amount, $project_wgrp_id));
        $this->db->transComplete();

        $config['title'] = "Contractor Payment Delete";
        if ($this->db->transStatus() === FALSE) {
            $this->db->transRollback();
            $config['log_text'] = "[ Contractor Payment failed to delete ]";
            $config['ref_link'] = "erp/project/projectview/" . $project_id;
            $this->session->setFlashdata("op_error", "Contractor Payment failed to delete");
        } else {
            $this->db->transCommit();
            $config['log_text'] = "[ Contractor Payment successfully deleted ]";
            $config['ref_link'] = "erp/project/projectview/" . $project_id;
            $this->session->setFlashdata("op_success", "Contractor Payment successfully deleted");
        }
        log_activity($config);
    }

    public function get_contractorpayment_export($type, $project_wgrp_id)
    {

        $columns = array(
            "payment mode" => "payment_modes.name",
            "amount" => "amount",
            "paid on" => "paid_on"
        );

        $limit = $_GET['limit'];
        $offset = $_GET['offset'];
        $search = $_GET['search'] ?? "";

        $orderby = isset($_GET['orderby']) ? strtoupper($_GET['orderby']) : "";
        $ordercol = isset($_GET['ordercol']) ? $columns[$_GET['ordercol']] : "";
        $query = "SELECT payment_modes.name,amount,paid_on,transaction_id,notes FROM contractor_payments JOIN payment_modes ON contractor_payments.payment_id=payment_modes.payment_id WHERE project_wgrp_id=$project_wgrp_id ";
        $where = true;

        if (!empty($search)) {
            if (!$where) {
                $query .= " WHERE payment_modes.name LIKE '%" . $search . "%' ";
                $where = true;
            } else {
                $query .= " AND payment_modes.name LIKE '%" . $search . "%' ";
            }
        }

        if (!empty($ordercol) && !empty($orderby)) {
            $query .= " ORDER BY " . $ordercol . " " . $orderby;
        }
        $result = $this->db->query($query)->getResultArray();
        return $result;
    }

    public function get_project_contractor_export($type, $project_id)
    {
        
        $columns = array(
            "workgroup" => "work_groups.name",
            "contractor" => "contractors.name",
            "amount" => "amount"
        );
        $limit = $_GET['limit'];
        $offset = $_GET['offset'];
        $search = $_GET['search'] ?? "";
        $orderby = isset($_GET['orderby']) ? strtoupper($_GET['orderby']) : "";
        $ordercol = isset($_GET['ordercol']) ? $columns[$_GET['ordercol']] : "";

        $query = "SELECT work_groups.name AS workgroup,CONCAT(contractors.name,' ',con_code) AS contractor,amount,pay_before,paid_till,(amount-paid_till) AS due,
        CASE ";
        foreach ($this->contractor_status as $key => $value) {
            $query .= " WHEN c_status=$key THEN '$value' ";
        }
        $query .= " END status FROM project_workgroup JOIN contractors ON project_workgroup.contractor_id=contractors.contractor_id JOIN work_groups ON project_workgroup.wgroup_id=work_groups.wgroup_id WHERE project_id=$project_id AND worker_type='Contractor' ";
        $where = true;

        if (!empty($search)) {
            if (!$where) {
                $query .= " WHERE work_groups.name LIKE '%" . $search . "%' ";
                $where = true;
            } else {
                $query .= " AND work_groups.name LIKE '%" . $search . "%' ";
            }
        }

        if (!empty($ordercol) && !empty($orderby)) {
            $query .= " ORDER BY " . $ordercol . " " . $orderby;
        }
        $result = $this->db->query($query)->getResultArray();
        return $result;
    }

    public function get_contractor_payments_data_for_finance($year = "")
    {
        if(empty($year)){
            $year = date('Y');
        }
        $query = "SELECT SUM(amount) AS total_expenditure, MONTH(paid_on) AS month_number
        FROM contractor_payments 
        WHERE YEAR(paid_on) = 2022 
        GROUP BY YEAR(paid_on), MONTH(paid_on) 
        ORDER BY month_number";
        return $this->db->query($query)->getResultArray();
    }
}
