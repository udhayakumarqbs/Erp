<?php

namespace App\Models\Erp;

use CodeIgniter\Model;

class ProjectExpenseModel extends Model
{
    protected $table      = 'project_expense';
    protected $primaryKey = 'expense_id';

    protected $allowedFields = [
        'project_id',
        'name',
        'expense_date',
        'amount',
        'payment_id',
        'receipt',
        'description',
    ];

    protected $useTimestamps = false;

    public $db;
    public $session;
    public function __construct()
    {
        $this->db = \Config\Database::connect();
        $this->session = \Config\Services::session();
    }


    public function get_dtconfig_project_expense()
    {
        $config = array(
            "columnNames" => ["sno", "name", "expense date", "amount", "payment mode", "receipt", "action"],
            "sortable" => [0, 1, 1, 1, 0, 0, 0],
        );
        return json_encode($config);
    }

    public function get_project_expense_datatable($project_id)
    {
        $columns = array(
            "name" => "name",
            "expense date" => "expense_date",
            "amount" => "amount"
        );
        $limit = $_GET['limit'];
        $offset = $_GET['offset'];
        $search = $_GET['search'] ?? "";
        $orderby = isset($_GET['orderby']) ? strtoupper($_GET['orderby']) : "";
        $ordercol = isset($_GET['ordercol']) ? $columns[$_GET['ordercol']] : "";

        $query = "SELECT expense_id,project_expense.name AS expense_name ,expense_date,amount,payment_modes.name AS paymentmode,receipt FROM project_expense JOIN payment_modes ON project_expense.payment_id=payment_modes.payment_id WHERE project_id=$project_id ";
        $count = "SELECT COUNT(expense_id) AS total FROM project_expense JOIN payment_modes ON project_expense.payment_id=payment_modes.payment_id WHERE project_id=$project_id ";
        $where = true;

        if (!empty($search)) {
            if (!$where) {
                $query .= " WHERE project_expense.name LIKE '%" . $search . "%' ";
                $count .= " WHERE project_expense.name LIKE '%" . $search . "%' ";
                $where = true;
            } else {
                $query .= " AND project_expense.name LIKE '%" . $search . "%' ";
                $count .= " AND project_expense.name LIKE '%" . $search . "%' ";
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
                                <li><a href="' . url_to('erp.project.expenseedit' , $project_id , $r['expense_id']) . '" title="Edit" class="bg-success"><i class="fa fa-pencil"></i> </a></li>
                                <li><a href="' . url_to('erp.project.expensedelete' , $project_id , $r['expense_id']) . '" title="Delete" class="bg-danger del-confirm"><i class="fa fa-trash"></i> </a></li>
                            </ul>
                        </div>
                    </div>';
            $receipt = '<a href="#" ></a>';
            if (!empty($r['receipt'])) {
                $receipt = '<a download href="' . get_attachment_link('expense') . $r['receipt'] . '" class="text-primary" >' . $r['receipt'] . '</a>';
            }
            array_push(
                $m_result,
                array(
                    $r['expense_id'],
                    $sno,
                    $r['expense_name'],
                    $r['expense_date'],
                    $r['amount'],
                    $r['paymentmode'],
                    $receipt,
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

    public function get_project_expense_export($type, $project_id)
    {
        $columns = array(
            "name" => "name",
            "expense date" => "expense_date",
            "amount" => "amount"
        );
        $limit = $_GET['limit'];
        $offset = $_GET['offset'];
        $search = $_GET['search'] ?? "";
        $orderby = isset($_GET['orderby']) ? strtoupper($_GET['orderby']) : "";
        $ordercol = isset($_GET['ordercol']) ? $columns[$_GET['ordercol']] : "";

        $query = "SELECT project_expense.name AS expense_name ,expense_date,amount,payment_modes.name AS paymentmode,project_expense.description FROM project_expense JOIN payment_modes ON project_expense.payment_id=payment_modes.payment_id WHERE project_id=$project_id ";
        $where = true;

        if (!empty($search)) {
            if (!$where) {
                $query .= " WHERE project_expense.name LIKE '%" . $search . "%' ";
                $where = true;
            } else {
                $query .= " AND project_expense.name LIKE '%" . $search . "%' ";
            }
        }

        if (!empty($ordercol) && !empty($orderby)) {
            $query .= " ORDER BY " . $ordercol . " " . $orderby;
        }
        $result = $this->db->query($query)->getResultArray();
        return $result;
    }

    public function insert_project_expense($project_id, $post)
    {
        $inserted = false;
        $name = $post["name"];
        $expense_date = $post["expense_date"];
        $amount = $post["amount"];
        $payment_id = $post["payment_id"];
        $description = $post["description"];
        $receipt = "";

        if (!empty($_FILES['receipt']['name'])) {
            $res = $this->db->query("SELECT expense_id FROM project_expense ORDER BY expense_id DESC LIMIT 1 ")->getRow();
            $last_id = 1;
            if (!empty($res)) {
                $last_id = $res->expense_id;
                ++$last_id;
            }
            $filename = preg_replace('/[\/:"*?<>| ]+/i', "", $_FILES['receipt']['name']);
            $ext = pathinfo($_FILES['receipt']['name'], PATHINFO_EXTENSION);
            $index = strpos($filename, '.' . $ext);
            $filename = substr($filename, 0, $index) . '_' . $last_id . substr($filename, $index);
            $path = get_attachment_path("expense");
            if (move_uploaded_file($_FILES['receipt']['tmp_name'], $path . $filename)) {
                $receipt = $filename;
            }
        }

        $query = "INSERT INTO project_expense(project_id,name,expense_date,amount,payment_id,receipt,description) VALUES(?,?,?,?,?,?,?) ";
        $this->db->query($query, array($project_id, $name, $expense_date, $amount, $payment_id, $receipt, $description));
        $expense_id = $this->db->insertId();
        $config['title'] = "Project Expense Insert";
        if (!empty($expense_id)) {
            $inserted = true;
            $config['log_text'] = "[ Project Expense successfully created ]";
            $config['ref_link'] = url_to('erp.project.projectview', $project_id);
            $this->session->setFlashdata("op_success", "Project Expense successfully created");
        } else {
            if (empty($receipt)) {
                $path = get_attachment_path("expense");
                @unlink($path . $receipt);
            }
            $config['log_text'] = "[ Project Expense failed to create ]";
            $config['ref_link'] = url_to('erp.project.projectview', $project_id);
            $this->session->setFlashdata("op_error", "Project Expense failed to create");
        }
        log_activity($config);
        return $inserted;
    }


    public function update_project_expense($project_id, $expense_id, $post)
    {
        $updated = false;
        $name = $post["name"];
        $expense_date = $post["expense_date"];
        $amount = $post["amount"];
        $payment_id = $post["payment_id"];
        $description = $post["description"];
        $res = $this->db->query("SELECT receipt FROM project_expense ORDER BY expense_id DESC LIMIT 1 ")->getRow();
        $receipt = $res->receipt;

        if (!empty($_FILES['receipt']['name'])) {
            $last_id = $expense_id;
            $filename = preg_replace('/[\/:"*?<>| ]+/i', "", $_FILES['receipt']['name']);
            $ext = pathinfo($_FILES['receipt']['name'], PATHINFO_EXTENSION);
            $index = strpos($filename, '.' . $ext);
            $filename = substr($filename, 0, $index) . '_' . $last_id . substr($filename, $index);
            $path = get_attachment_path("expense");
            if (move_uploaded_file($_FILES['receipt']['tmp_name'], $path . $filename)) {
                if (!empty($receipt)) {
                    @unlink($path . $receipt);
                }
                $receipt = $filename;
            }
        }
        $query = "UPDATE project_expense SET name=? , expense_date=? , amount=? , payment_id=? , receipt=? , description=? WHERE expense_id=$expense_id ";
        $this->db->query($query, array($name, $expense_date, $amount, $payment_id, $receipt, $description));
        $config['title'] = "Project Expense Update";
        if ($this->db->affectedRows() > 0) {
            $updated = true;
            $config['log_text'] = "[ Project Expense successfully updated ]";
            $config['ref_link'] = url_to('erp.project.projectview', $project_id);
            $this->session->setFlashdata("op_success", "Project Expense successfully updated");
        } else {
            $config['log_text'] = "[ Project Expense failed to update ]";
            $config['ref_link'] = url_to('erp.project.projectview', $project_id);
            $this->session->setFlashdata("op_error", "Project Expense failed to update");
        }
        log_activity($config);
        return $updated;
    }

    public function get_project_expense_by_id($expense_id)
    {
        $query = "SELECT * FROM project_expense WHERE expense_id=$expense_id ";
        $result = $this->db->query($query)->getRow();
        return $result;
    }

    public function delete_projectexpense($project_id, $expense_id)
    {
        $query = "DELETE FROM project_expense WHERE expense_id=$expense_id";
        $this->db->query($query);
        $config['title'] = "Project Expense Delete";
        if ($this->db->affectedRows() > 0) {
            $config['log_text'] = "[ Project Expense successfully deleted ]";
            $config['ref_link'] =url_to('erp.project.projectview', $project_id);
            $this->session->setFlashdata("op_success", "Project Expense successfully deleted");
        } else {
            $config['log_text'] = "[ Project Expense failed to delete ]";
            $config['ref_link'] = url_to('erp.project.projectview', $project_id);
            $this->session->setFlashdata("op_error", "Project Expense failed to delete");
        }
        log_activity($config);
    }
}
