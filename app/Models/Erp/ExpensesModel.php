<?php

namespace App\Models\Erp;

use CodeIgniter\Model;
use PSpell\Config;
use App\Models\Erp\TaskModel;
class ExpensesModel extends Model
{

    protected $table = "erpexpenses";
    protected $primarykey = "id";
    protected $allowedFields = [
        'category',
        'amount',
        'tax',
        'tax2',
        'reference_no',
        'note',
        'clientid',
        'project_id',
        'billable',
        'invoice_id',
        'paymentmode',
        'date',
        'dateadded',
        'addedfrom',
    ];

    protected $db;
    protected $logger;
    protected $TaxesModel;


    public function __construct()
    {
        $this->db = \Config\Database::connect();
        $this->logger = \Config\Services::logger();
        $this->TaxesModel = new TaxesModel();
    }

    public function ajax_data_table_coloumn()
    {
        $config = array(
            "columnNames" => ["sno", "Category", "Amount", "Name", "Receipt", "Date", "Project", "Customer", "Refrence", "Payment Mode", "Options"],
            "sortable" => [1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1]
        );
        return json_encode($config);
    }

    public function addExpense($data)
    {
        if ($this->builder()->insert($data)) {
            return $this->db->insertID();
        } else {
            return false;
        }
    }

    public function upload_attachment($type, $id)
    {
        $path = get_attachment_path($type);

        // Ensure the directory exists
        if (!is_dir($path)) {
            mkdir($path, 0777, true);
        }

        $ext = pathinfo($_FILES['attachment']['name'], PATHINFO_EXTENSION);
        $filename = preg_replace('/[\/:"*?<>| ]+/i', "", $_FILES['attachment']['name']);
        $filepath = $path . $filename;
        $response = [];

        if (file_exists($filepath)) {
            $response['error'] = 2;
            $response['reason'] = "File already exists";
        } else {
            if (move_uploaded_file($_FILES['attachment']['tmp_name'], $filepath)) {
                $query = "INSERT INTO attachments(filename, related_to, related_id) VALUES(?, ?, ?) ";

                if (empty($id)) {
                    $response['error'] = 1;
                    $response['reason'] = "Internal Error";
                } else {
                    $this->db->query($query, [$filename, $type, $id]);
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

    public function fetch_data_table()
    {
        $limit = $_GET["limit"];
        $offset = $_GET["offset"];
        $search = $_GET['search'] ?? "";
        $query = "SELECT a.id as id ,a.exp_name as name,b.name as cat,a.amount as amount,p.name as project_name,c.filename as file,
                  a.date as date,d.company as customer,a.reference_no as refrence,e.name as payment FROM erpexpenses as a 
                  LEFT JOIN erp_expenses_categories as b ON b.id = a.category
                  LEFT JOIN attachments as c ON a.id = c.related_id AND c.related_to = 'Expenses_Attachment'
                  JOIN customers as d ON d.cust_id = a.clientid
                  LEFT JOIN payment_modes as e ON e.payment_id = a.paymentmode 
                  LEFT JOIN projects as p ON p.project_id = a.project_id";

        $count = "SELECT * FROM erpexpenses as a 
                  LEFT JOIN erp_expenses_categories as b ON b.id = a.category
                  LEFT JOIN attachments as c ON a.id = c.related_id AND c.related_to = 'Expenses_Attachment'
                  JOIN customers as d ON d.cust_id = a.clientid
                  LEFT JOIN payment_modes as e ON e.payment_id = a.paymentmode 
                  LEFT JOIN projects as p ON p.project_id = a.project_id ";
        
        if (!empty($search)) {
            $query .= " WHERE ( a.exp_name LIKE '%" . $search . "%' OR b.name LIKE '%" . $search . "%' ) ";
            $count .= " WHERE ( a.exp_name LIKE '%" . $search . "%' OR b.name LIKE '%" . $search . "%' ) ";
        }

        $query .= " ORDER BY a.dateadded DESC LIMIT $offset, $limit";

        $result = $this->db->query($query)->getResultArray();
        $a_result = array();

        $sno = $offset + 1;

        foreach ($result as $r) {
            $action = "<div class='dropdown tableAction'>
                        <a type='button' class='dropBtn HoverA '><i class='fa fa-ellipsis-v'></i></a>
                        <div class='dropdown_container'>
                            <ul class='BB_UL flex justify-content-around'>
                                <li><a href='" . url_to("erp.expenses.view.page", $r['id']) . "' title='view' class='bg-warning'><i class='fa fa-eye'></i> </a></li>
                                <li><a href='" . url_to("erp.expenses.update.view", $r['id']) . "' title='Edit' class='updateit bg-success'><i class='fa fa-pencil'></i> </a></li>
                                <li><a href='" . url_to("erp.expenses.delete", $r['id']) . "' title='Delete' class='bg-danger del-confirm'><i class='fa fa-trash'></i> </a></li>
                            </ul>
                        </div>
                    </div>";

            if ($r['file'] == "") {
                $r['file'] = "<p style='text-align :center'>-</p>";
            } else {
                $r['file'] = "<a target=\"_BLANK\" download class=\"text-primary\" href=\"" . get_attachment_link('Expenses_Attachment') . $r['file'] . "\">" . $r['file'] . "</a>";
            }
            if ($r["project_name"] == "") {
                $r["project_name"] = "<p class ='text-center'> - </p>";
            }
            if ($r["refrence"] == "") {
                $r["refrence"] = "<p class ='text-center'> - </p>";
            }
            array_push($a_result, array(
                '',
                $sno,
                $r["cat"],
                "₹" . number_format($r["amount"], 2, '.', ','),
                $r["name"],
                $r["file"],
                $r["date"],
                $r["project_name"],
                $r["customer"],
                $r["refrence"],
                $r["payment"],
                $action
            ));
            $sno++;
        }
        ;
        $response = array();
        $response["total_rows"] = count($this->db->query($count)->getResultArray());
        $response["data"] = $a_result;
        return $response;
    }

    public function reminder_data_table($id)
    {
        $limit = $_GET["limit"];
        $offset = $_GET["offset"];

        $query = "SELECT a.notify_id as id,a.notify_text as description,a.notify_at as date,a.user_id as employee_id, CONCAT(c.first_name,' ',c.last_name) as reminder_name,a.is_notified as notified_or_not
        FROM notification as a 
        JOIN erpexpenses as b ON b.id = a.related_id
        JOIN employees as c ON a.user_id = c.employee_id 
        WHERE a.related_id = $id and a.related_to = 'expense' ORDER BY a.created_at";
        $query .= " LIMIT $offset, $limit";
        $count = "SELECT COUNT(notify_id) AS Total FROM notification";

        $result = $this->db->query($query)->getResultArray();
        $r_result = array();

        $sno = $offset + 1;

        foreach ($result as $r) {
            $action = "<div class='dropdown tableAction'>
                        <a type='button' class='dropBtn HoverA '><i class='fa fa-ellipsis-v'></i></a>
                        <div class='dropdown_container'>
                            <ul class='BB_UL flex justify-content-around'>
                                <li><a href='#' onclick=update_reminder(" . $r['id'] . ") title='Edit' class='updateit bg-success'><i class='fa fa-pencil'></i> </a></li>
                                <li><a href='#' onclick=delete_reminder(" . $r['id'] . ") title='Delete' class='bg-danger del-confirm'><i class='fa fa-trash'></i> </a></li>
                            </ul>
                        </div>
                    </div>";

            if ($r["notified_or_not"] == 0) {
                $r["notified_or_not"] = "<p class =''> Not notified </p>";
            } else {
                $r["notified_or_not"] = "<p class =''> Notified </p>";
            }

            array_push($r_result, array(
                '',
                $sno,
                $r["description"],
                $r["date"],
                $r["reminder_name"],
                $r["notified_or_not"],
                $action
            ));
            $sno++;
        }
        ;
        $response = array();
        $response["total_row"] = $this->db()->query($count)->getrow()->Total;
        $response["data"] = $r_result;
        return $response;
    }

    public function get_exsisting_data($id)
    {
        // return $this->builder()->select()->where("id",$id)->get()->getResultArray();
        $query = "SELECT a.id as id ,a.exp_name as name,b.name as cat_name,a.note as exp_note,a.category as cat_id,a.billable as billable,a.amount as amount,c.filename as file,
                a.date as date,CONCAT(d.name,' - ',d.company) as customer,a.clientid as customer_id,p.name as project_name,a.project_id as project_id,g.tax_name as tax_1_name,g.percent as tax_1_per,
                a.tax as tax_1,h.tax_name as tax_2_name,h.percent as tax_2_per,a.tax2 as tax_2,
                a.reference_no as refrence,
                e.name as payment,a.paymentmode  as payment_id,a.addedfrom as addedfrom,
                a.invoice_id as invoice_id,i.code as invoice_code,i.status as invoice_status FROM erpexpenses as a 
                LEFT JOIN erp_expenses_categories as b ON b.id = a.category
                LEFT JOIN attachments as c ON a.id = c.related_id AND c.related_to = 'Expenses_Attachment'
                JOIN customers as d ON d.cust_id = a.clientid
                LEFT JOIN payment_modes as e ON e.payment_id = a.paymentmode 
                LEFT JOIN taxes as g ON g.tax_id  = a.tax  
                LEFT JOIN taxes as h ON h.tax_id  = a.tax2  
                LEFT JOIN projects as p ON p.project_id = a.project_id  
                LEFT JOIN sale_invoice as i ON i.invoice_id  = a.invoice_id 
                Where a.id = $id";

        $result = $this->db->query($query)->getRow();

        return $result;
    }

    public function get_reminder_by_id($id)
    {

        $query = "SELECT a.notify_id as id,a.notify_text as description,a.notify_at as date,a.notify_email as email,a.user_id as employee_id, CONCAT(c.first_name,' ',c.last_name) as reminder_name,a.is_notified as notified_or_not
        FROM notification as a 
        JOIN erpexpenses as b ON b.id = a.related_id
        JOIN employees as c ON a.user_id = c.employee_id 
        WHERE a.notify_id = $id";

        $result = $this->db->query($query)->getRow();
        return $result;
    }

    public function Expense_update($id, $data)
    {
        if ($this->builder()->where("id", $id)->update($data)) {
            return true;
        } else {
            return false;
        }
    }

    public function expense_delete($id)
    {
        if ($this->builder()->where("id", $id)->delete()) {
            return true;
        } else {
            return false;
        }
    }

    public function get_datatable_header()
    {
        $config = array(
            "columnNames" => ["sno", "Name", "Status", "Start Date", "Due Date", "Priority", "Assigned To", "Action"],
            "sortable" => [0, 1, 0, 1, 0, 1, 1, 0]
        );

        return json_encode($config);
    }

    public function get_datatable_reminder_header()
    {
        $config = array(
            "columnNames" => ["sno", "Description", "Date", "Remind", "is notified?", "action"],
            "sortable" => [0, 1, 1, 1, 1, 0]
        );

        return json_encode($config);
    }

    public function get_expenses()
    {
        return $this->builder()->select("id,exp_name")->get()->getResultArray();
    }

    public function get_invoice_id($id)
    {
        $query = "SELECT invoice_id as id FROM erpexpenses WHERE id  = $id";
        $result = $this->db->query($query)->getRowArray();

        return $result;
    }

    public function get_expense_data()
    {
        $query = "SELECT a.id as exp_id,b.name as cat_name,a.amount as amount,a.exp_name as  exp_name,c.filename as file,
        a.date as date,p.name as project_name,
        CONCAT(d.name,' - ',d.company) as customer,
        i.code as invoice_code,
        g.percent as tax_1_per,
        h.percent as tax_2_per,
        a.reference_no as refrence,
        e.name as payment FROM erpexpenses as a 
        LEFT JOIN erp_expenses_categories as b ON b.id = a.category
        LEFT JOIN attachments as c ON a.id = c.related_id AND c.related_to = 'Expenses_Attachment'
        LEFT JOIN customers as d ON d.cust_id = a.clientid
        LEFT JOIN payment_modes as e ON e.payment_id = a.paymentmode 
        LEFT JOIN taxes as g ON g.tax_id  = a.tax  
        LEFT JOIN taxes as h ON h.tax_id  = a.tax2  
        LEFT JOIN projects as p ON p.project_id = a.project_id  
        LEFT JOIN sale_invoice as i ON i.invoice_id  = a.invoice_id ";

        $result = $this->db->query($query)->getResultArray();

        $expense = array();

        foreach ($result as $r) {
            if (isset($r["tax_1_per"]) && isset($r["tax_2_per"])) {
                $tax1 = ($r["tax_1_per"] / 100) * $r["amount"];
                $tax2 = ($r["tax_2_per"] / 100) * $r["amount"];

                $r["amount"] = $r["amount"] + $tax1 + $tax2;
            } elseif (isset($r["tax_1_per"])) {

                $tax1 = ($r["tax_1_per"] / 100) * $r["amount"];
                $r["amount"] = $r["amount"] + $tax1;
            } elseif (isset($r["tax_2_per"])) {

                $tax2 = ($r["tax_2_per"] / 100) * $r["amount"];
                $r["amount"] = $r["amount"] + $tax2;
            }

            array_push($expense, array(
                $r["exp_id"],
                $r["cat_name"],
                number_format($r["amount"], 2, '.', ','),
                $r["exp_name"],
                $r["file"],
                $r["date"],
                $r["project_name"],
                $r["customer"],
                $r["invoice_code"],
                $r["refrence"],
                $r["payment"],
            ));
        }
        return $expense;
    }

    public function get_expence_data_for_finance($year = "")
    {
        if (empty($year)) {
            $year = date("Y");
        }
        $query = "SELECT SUM(amount) AS total_expenditure, MONTH(date) AS month_number 
        FROM erpexpenses 
        WHERE YEAR(date) = $year
        GROUP BY YEAR(date), MONTH(date)
        ORDER BY month_number";
        return $this->db->query($query)->getResultArray();
    }

    public function get_Report($year)
    {
        $query = "SELECT id,name
        FROM erp_expenses_categories";

        return $this->db->query($query)->getResultArray();
    }

    public function expense_export_report($year)
    {
        $logger = \Config\Services::logger();
        $data = $this->get_Report($year);
        $array = [];
        $array2 = [];
        $array3 = [];
        $array4 = [];
        // $total_expense_array = [];
        $count = 1;
        foreach ($data as $rec) {

            $name = array($rec["name"]);
            // $logger->error($rec);
            $grandTotal = 0;
            $array_dump = array();
            for ($month = 1; $month <= 13; $month++) {
                // Format the start of each month (e.g., "2024-01-01")
                $startOfMonth = "$year-" . str_pad($month, 2, '0', STR_PAD_LEFT) . "-01";

                // Format the end of each month (e.g., "2024-01-31")
                $endOfMonth = date('Y-m-t', strtotime($startOfMonth));

                // Query for expenses in the given month for the current category
                $expense = $this->builder()->where("category", $rec['id'])
                    ->where('DATE(date) >=', $startOfMonth)
                    ->where('DATE(date) <=', $endOfMonth)
                    ->where('invoice_id !=', Null)
                    ->get()
                    ->getResultArray();


                $total = 0;

                foreach ($expense as $exp) {
                    if ($exp["tax"] != 0 && $exp['tax2'] != 0) {
                        $tax_1 = $this->TaxesModel->getTaxbyid($exp['tax']);
                        $tax_2 = $this->TaxesModel->getTaxbyid($exp['tax2']);
                        $totaltax = $tax_1->percent + $tax_2->percent;
                    } else if ($exp["tax"] != 0 && $exp["tax2"] == 0) {
                        $tax_1 = $this->TaxesModel->getTaxbyid($exp['tax']);
                        $totaltax = $tax_1->percent;
                    } else if ($exp["tax"] == 0 && $exp["tax2"] != 0) {
                        $tax_2 = $this->TaxesModel->getTaxbyid($exp['tax2']);
                        $totaltax = $tax_2->percent;
                    } else {
                        $totaltax = 0;
                    }

                    // $logger->error(gettype($totaltax));
                    if ($totaltax > 0) {
                        $total = $exp['amount'] + ($exp['amount'] * $totaltax) / 100;
                    } else {
                        $total = $exp['amount'];
                    }
                    $grandTotal += $total;
                }


                // Print expenses to check
                // var_dump($expense);
                // exit; // Remove this exit if you want to continue processing

                if ($month == 13) {
                    // $array[] = $grandTotal;
                    $array_dump[$month] = 'Rs. ' . $grandTotal;
                } else {
                    // $array[] = $total;
                    $array_dump[$month] = 'Rs. ' . $total;
                }
            }



            $array[] = array_merge($name, $array_dump);
            $count++;
        }

        // $logger->error($array);

        $count_1 = 1;

        $netammountGrandTotal = 0;
        $gstammountGrandTotal = 0;
        $totalammountGrandTotal = 0;

        for ($mon = 1; $mon <= 13; $mon++) {

            $startOfMonth2 = "$year-" . str_pad($mon, 2, '0', STR_PAD_LEFT) . "-01";

            $onemonth = date('Y-m-t', strtotime($startOfMonth2));

            $netammount = $this->builder()->select("amount,tax,tax2,date")
                ->where('DATE(date) >=', $startOfMonth2)
                ->where('DATE(date) <=', $onemonth)
                ->where('invoice_id !=', Null)
                ->get()
                ->getResultArray();


            $netammounttotal = 0;
            $gstammounttotal = 0;
            $totalammounttotal = 0;


            foreach ($netammount as $amt) {
                $netammounttotal += (int) $amt['amount'];
                $netammountGrandTotal += (int) $amt['amount'];
            }

            // $logger->error($netammounttotal);

            foreach ($netammount as $amt) {
                if ($amt["tax"] != 0 && $amt['tax2'] != 0) {
                    $tax_1 = $this->TaxesModel->getTaxbyid($amt['tax']);
                    $tax_2 = $this->TaxesModel->getTaxbyid($amt['tax2']);
                    $totaltaxgst = $tax_1->percent + $tax_2->percent;
                } else if ($amt["tax"] != 0 && $amt["tax2"] == 0) {
                    $tax_1 = $this->TaxesModel->getTaxbyid($amt['tax']);
                    $totaltaxgst = $tax_1->percent;
                } else if ($amt["tax"] == 0 && $amt["tax2"] != 0) {
                    $tax_2 = $this->TaxesModel->getTaxbyid($amt['tax2']);
                    $totaltaxgst = $tax_2->percent;
                } else {
                    $totaltaxgst = 0;
                }

                if ($totaltaxgst > 0) {
                    $gstammounttotal += ($amt['amount'] * $totaltaxgst) / 100;
                    $gstammountGrandTotal += ($amt['amount'] * $totaltaxgst) / 100;
                }
            }

            foreach ($netammount as $amt) {
                if ($amt["tax"] != 0 && $amt['tax2'] != 0) {
                    $tax_1 = $this->TaxesModel->getTaxbyid($amt['tax']);
                    $tax_2 = $this->TaxesModel->getTaxbyid($amt['tax2']);
                    $totalgst = $tax_1->percent + $tax_2->percent;
                } else if ($amt["tax"] != 0 && $amt["tax2"] == 0) {
                    $tax_1 = $this->TaxesModel->getTaxbyid($amt['tax']);
                    $totalgst = $tax_1->percent;
                } else if ($amt["tax"] == 0 && $amt["tax2"] != 0) {
                    $tax_2 = $this->TaxesModel->getTaxbyid($amt['tax2']);
                    $totalgst = $tax_2->percent;
                } else {
                    $totalgst = 0;
                }

                if ($totalgst > 0) {
                    $totalammounttotal += $amt['amount'] + ($amt['amount'] * $totalgst) / 100;
                    $totalammountGrandTotal += $amt['amount'] + ($amt['amount'] * $totalgst) / 100;
                } else {
                    $totalammounttotal += $amt['amount'];
                    $totalammountGrandTotal += $amt['amount'];
                }
            }

            if ($mon == 13) {
                $array2[$count_1] = 'Rs. ' . $netammountGrandTotal;
                $array3[$count_1] = 'Rs. ' . $gstammountGrandTotal;
                $array4[$count_1] = 'Rs. ' . $totalammountGrandTotal;
            } else {
                $array2[0] = "Net Amount (Subtotal)";
                $array2[$count_1] = 'Rs. ' . $netammounttotal;
                $array3[0] = "Total Tax";
                $array3[$count_1] = 'Rs. ' . $gstammounttotal;
                $array4[0] = "Total";
                $array4[$count_1] = 'Rs. ' . $totalammounttotal;
            }

            $count_1++;

        }
        $array[] = $array2;
        $array[] = $array3;
        $array[] = $array4;

        // $logger->error($array);


        // $total_expense_array[] = array_merge($array,$array2,$array3,$array4);
        return $array;
    }
}
