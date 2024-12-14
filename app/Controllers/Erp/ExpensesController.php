<?php

namespace App\Controllers\Erp;

use App\Controllers\BaseController;
use App\Models\Erp\ExpensesModel;
use App\Models\Erp\ExpenseTypeModel;
use App\Models\Erp\CustomersModel;
use App\Models\Erp\CurrenciesModel;
use App\Models\Erp\TaxesModel;
use App\Models\Erp\PaymentModesModel;
use App\Models\Erp\AttachmentModel;
use App\Models\Erp\ProjectsModel;
use App\Models\Erp\TaskModel;
use App\Models\Erp\ExpenseTaskModel;
use App\Models\Erp\NotificationModel;
use App\Models\Erp\Sale\InvoiceModel;
use App\Libraries\Exporter;
use App\Libraries\Exporter\ExporterConst;

class ExpensesController extends BaseController
{

    protected $db;
    protected $data;
    protected $ExpensesModel;
    protected $Expensestype;
    protected $CustomersModel;
    protected $CurrenciesModel;
    protected $TaxesModel;
    protected $PaymentModesModel;
    protected $AttachmentModel;
    protected $ProjectsModel;
    protected $TaskModel;
    protected $ExpenseTaskModel;
    protected $NotificationModel;
    protected $InvoiceModel;

    public function __construct()
    {
        $this->ExpensesModel = new ExpensesModel();
        $this->Expensestype = new ExpenseTypeModel();
        $this->CustomersModel = new CustomersModel();
        $this->CurrenciesModel = new CurrenciesModel();
        $this->TaxesModel = new TaxesModel();
        $this->PaymentModesModel = new PaymentModesModel();
        $this->AttachmentModel = new AttachmentModel();
        $this->ProjectsModel = new ProjectsModel();
        $this->TaskModel = new TaskModel();
        $this->ExpenseTaskModel = new ExpenseTaskModel();
        $this->NotificationModel = new NotificationModel();
        $this->InvoiceModel = new InvoiceModel();
        $this->db = \Config\Database::connect();

        helper(['erp', 'form', 'pdf']);
    }

    public function index()
    {

        $this->data['menu'] = "Expenses";
        $this->data['submenu'] = "Expenses";
        $this->data['page'] = "Expenses/expenses";
        $this->data['dt_config'] = $this->ExpensesModel->ajax_data_table_coloumn();


        return view('erp/index', $this->data);
    }

    public function reportview()
    {
        $this->data['menu'] = "Expenses";
        $this->data['submenu'] = "report";
        $this->data['page'] = "Expenses/report";

        return view('erp/index', $this->data);
    }

    public function addExpense()
    {
        $this->data['menu'] = "Expenses";
        $this->data['submenu'] = "Expenses";
        $this->data['page'] = "Expenses/expensesadd";
        // $this->data['currency_data'] = $this->CurrenciesModel->get_all_currency();
        $this->data['tax_data'] = $this->TaxesModel->Taxes();
        $this->data['payment_modes'] = $this->PaymentModesModel->payment_mode_fetch();
        $this->data['customers'] = $this->CustomersModel->fetch_customer();

        if ($this->request->getPost()) {
            $data = [
                "exp_name" => $this->request->getPost("name"),
                "category" => $this->request->getPost("related_id"),
                // "currency" => $this->request->getPost("currency"),
                "amount" => $this->request->getPost("amount"),
                "tax" => $this->request->getPost("tax1"),
                "tax2" => $this->request->getPost("tax2"),
                "reference_no" => $this->request->getPost("refrence"),
                "note" => $this->request->getPost("ExpensesNotes"),
                "clientid" => $this->request->getPost("dropdown_customer_type"),
                "billable" => $this->request->getPost("billable"),
                "project_id" => $this->request->getPost("project_id"),
                "paymentmode" => $this->request->getPost("payment"),
                "date" => $this->request->getPost("expense_date"),
                "addedfrom" => get_user_id(),
            ];
            if (isset($data["billable"])) {
                $data["billable"] = 1;
            } else {
                $data["billable"] = 0;
            }
            $data = $this->ExpensesModel->addExpense($data);
            if (!empty($data)) {
                if (!empty($_FILES['attachment']['name'])) {
                    $response = $this->ExpensesModel->upload_attachment("Expenses_Attachment", $data);
                    if ($response["error"] == 0) {
                        $this->response->redirect(url_to("erp.expensesview"));
                        $this->session->setFlashdata("op_success", "Added successfully");
                    } elseif ($response["error"] == 2) {
                        if ($this->ExpensesModel->expense_delete($data)) {
                            $this->response->redirect(url_to("erp.expensesadd"));
                            $this->session->setFlashdata("op_error", "File already Exist!");
                        } else {
                            $this->response->redirect(url_to("erp.expensesadd"));
                            $this->session->setFlashdata("op_error", "delete_error");
                        }
                    } else {
                        $this->response->redirect(url_to("erp.expensesadd"));
                        $this->session->setFlashdata("op_error", $response["reason"]);
                    }
                }
            } else {
                $this->response->redirect(url_to("erp.expensesview"));
                $this->session->setFlashdata("op_error", "Error Occured");
            }
        }
        return view('erp/index', $this->data);
    }
    //update
    public function updateExpenses($id)
    {

        $this->data['menu'] = "Expenses";
        $this->data['submenu'] = "Expenses";
        $this->data['page'] = "Expenses/expensesupdate";
        // $this->data['currency_data'] = $this->CurrenciesModel->get_all_currency();
        $this->data['tax_data'] = $this->TaxesModel->Taxes();
        $this->data['payment_modes'] = $this->PaymentModesModel->payment_mode_fetch();
        $this->data['customers'] = $this->CustomersModel->fetch_customer();
        $this->data['id'] = $id;
        $this->data["exist_attachment"] = $this->AttachmentModel->get_attachments_by_id("Expenses_Attachment", $id);
        if ($this->request->getPost()) {
            $data = [
                "exp_name" => $this->request->getPost("name"),
                "category" => $this->request->getPost("related_id"),
                // "currency" => $this->request->getPost("currency"),
                "amount" => $this->request->getPost("amount"),
                "tax" => $this->request->getPost("tax1"),
                "tax2" => $this->request->getPost("tax2"),
                "reference_no" => $this->request->getPost("refrence"),
                "note" => $this->request->getPost("ExpensesNotes"),
                "clientid" => $this->request->getPost("dropdown_customer_type"),
                "billable" => $this->request->getPost("billable"),
                "project_id" => $this->request->getPost("project_id"),
                "paymentmode" => $this->request->getPost("payment"),
                "date" => $this->request->getPost("expense_date"),
                "addedfrom" => get_user_id(),
            ];
            if (isset($data["billable"])) {
                $data["billable"] = 1;
            } else {
                $data["billable"] = 0;
            }
            if (!empty($_FILES['attachment']['name'])) {
                $response = $this->ExpensesModel->upload_attachment("Expenses_Attachment", $id);
                if ($response["error"] == 0) {
                    if ($this->ExpensesModel->Expense_update($id, $data)) {
                        $this->response->redirect(url_to("erp.expensesview"));
                        $this->session->setFlashdata("op_success", "Updated successfully");
                    }
                } elseif ($response["error"] == 2) {
                    $this->response->redirect(url_to("erp.expenses.update.view", $id));
                    $this->session->setFlashdata("op_error", "File already Exist!");
                } else {
                    $this->response->redirect(url_to("erp.expenses.update.view", $id));
                    $this->session->setFlashdata("op_error", $response["reason"]);
                }
            } else {
                if ($this->ExpensesModel->Expense_update($id, $data)) {
                    $this->response->redirect(url_to("erp.expensesview"));
                    $this->session->setFlashdata("op_success", "Updated successfully");
                } else {
                    $this->response->redirect(url_to("erp.expenses.update.view", $id));
                    $this->session->setFlashdata("op_error", "error");
                }
            }
        }
        $this->data["existing_data"] = $this->ExpensesModel->get_exsisting_data($id);
        return view("erp/index", $this->data);
    }
    //attach update
    public function attachment_update($id)
    {
        if (!empty($_FILES['attachment']['name'])) {
            $response = $this->ExpensesModel->upload_attachment("Expenses_Attachment", $id);
            if ($response["error"] == 0) {
                $this->response->redirect(url_to("erp.expenses.view.page", $id));
                $this->session->setFlashdata("op_success", "Updated successfully");
            } elseif ($response["error"] == 2) {
                $this->response->redirect(url_to("erp.expenses.view.page", $id));
                $this->session->setFlashdata("op_error", "File already Exist!");
            } else {
                $this->response->redirect(url_to("erp.expenses.view.page", $id));
                $this->session->setFlashdata("op_error", $response["reason"]);
            }
        } else {
            $this->response->redirect(url_to("erp.expenses.view.page", $id));
            $this->session->setFlashdata("op_error", "No New File Found");
        }
    }

    //exp
    public function fetch_expense_categories()
    {
        $result = [];
        $result = $this->Expensestype->fetch_expense_type();

        return $this->response->setJSON($result);
    }

    public function add_expense_categories()
    {
        $data = [
            "name" => $this->request->getPost("Category_Name"),
            "description" => $this->request->getPost("Category_Description"),
        ];

        if ($this->Expensestype->addtype($data)) {
            return $this->response->setJSON(["success" => true]);
        } else {
            return $this->response->setJSON(["success" => false]);
        }
    }

    public function fetch_customer_details_ajax()
    {
        $result = [];
        $result = $this->CustomersModel->fetch_customer();
        return $this->response->setJSON($result);
    }
    public function ajax_data_table_expense()
    {
        $result = array();

        $result = $this->ExpensesModel->fetch_data_table();

        return $this->response->setJSON($result);
    }
    public function deleteattachment($id, $exp_id)
    {
        $response = $this->AttachmentModel->delete_attachment("Expenses_Attachment", $id);
        if ($response["error"] == 0) {
            $this->response->redirect(url_to("erp.expenses.update.view", $exp_id));
            $this->session->setFlashdata("op_success", $response["reason"]);
        }
    }
    public function deleteattachmentview($id, $exp_id)
    {
        $response = $this->AttachmentModel->delete_attachment("Expenses_Attachment", $id);
        if ($response["error"] == 0) {
            $this->response->redirect(url_to("erp.expenses.view.page", $exp_id));
            $this->session->setFlashdata("op_success", $response["reason"]);
        }
    }
    public function expense_delete($id)
    {
        if ($this->ExpensesModel->expense_delete($id)) {
            $this->AttachmentModel->delete_attachment("Expenses_Attachment", $id);
            $this->response->redirect(url_to("erp.expensesview"));
            $this->session->setFlashdata("op_success", "deleted successfully");
        }
    }

    public function expense_view($id)
    {
        $this->data['menu'] = "Expenses";
        $this->data['submenu'] = "Expenses";
        $this->data['page'] = "Expenses/expensesview";
        $this->data["exist_attachment"] = $this->AttachmentModel->get_attachments_by_id("Expenses_Attachment", $id);
        $this->data["existing_data"] = $this->ExpensesModel->get_exsisting_data($id);
        $this->data["id"] = $id;
        $this->data["dt_config"] = $this->ExpensesModel->get_datatable_header();
        $this->data["dt_r_config"] = $this->ExpensesModel->get_datatable_reminder_header();
        $this->data['task_status'] = $this->TaskModel->get_task_status();
        $this->data['task_priority'] = $this->TaskModel->get_taskpriority_status();
        $this->data['task_related'] = $this->TaskModel->get_taskRelated_status();
        $this->data["t_count"] = $this->ExpenseTaskModel->expense_task_count($id);
        $this->data["r_count"] = $this->NotificationModel->expense_r_count($id);
        $invoice_id = $this->ExpensesModel->get_invoice_id($id);
        $this->data["status"] = $this->InvoiceModel->get_status($invoice_id["id"]);
        $this->data['invoice_status_bg'] = $this->InvoiceModel->get_invoice_status_bg();


        return view("erp/index", $this->data);
    }
    public function project_fetch()
    {
        $id = $_GET["id"];
        $returnData = $this->ProjectsModel->get_project_by_customer_id($id);
        return $this->response->setJSON($returnData);
    }
    public function expense_details()
    {
        $returnData = [];
        $returnData = $this->ExpensesModel->get_expenses();
        return $this->response->setJSON($returnData);
    }
    public function expense_task_add($id)
    {
        $data = [
            "expense_id" => $id,
            "name" => $this->request->getPost("name"),
            "status" => $this->request->getPost("status"),
            "start_date" => $this->request->getPost("start_date"),
            "due_date" => $this->request->getPost("due_date"),
            "related_id" => $this->request->getPost("related_id"),
            "priority" => $this->request->getPost("priority"),
            "assignees" => $this->request->getPost("assignees"),
            "followers" => $this->request->getPost("followers"),
            "task_description" => $this->request->getPost("task_description"),
            "created_by" => get_user_id(),
        ];

        if ($this->ExpenseTaskModel->task_add($data)) {
            return $this->response->setJSON(["success" => true]);
        } else {
            return $this->response->setJSON(["success" => false]);
        }
    }
    public function expense_task_data_table()
    {
        $id = $_GET["id"];
        $result = array();
        $result = $this->ExpenseTaskModel->task_data_table($id);

        return $this->response->setJSON($result);
    }

    public function expense_reminder_data_table()
    {
        $id = $_GET["reminder_id"];
        $result = array();
        $result = $this->ExpensesModel->reminder_data_table($id);

        return $this->response->setJSON($result);
    }

    public function expense_reminder_exist_data()
    {
        $id = $_POST["id"];

        $result = $this->ExpensesModel->get_reminder_by_id($id);
        return json_encode($result);
    }
    public function expense_task_exist_data()
    {
        $id = $_POST["id"];

        $data["task"] = $this->ExpenseTaskModel->get_exist_data($id);

        return $this->response->setJSON($data);
    }

    public function expense_task_update()
    {

        $id = $this->request->getPost("task_id");
        $data = [
            "name" => $this->request->getPost("name"),
            "status" => $this->request->getPost("status_update"),
            "start_date" => $this->request->getPost("start_date"),
            "due_date" => $this->request->getPost("due_date"),
            "related_id" => $this->request->getPost("related_id"),
            "priority" => $this->request->getPost("priority"),
            "assignees" => $this->request->getPost("assignees"),
            "followers" => $this->request->getPost("followers"),
            "task_description" => $this->request->getPost("task_description"),
            "created_by" => get_user_id(),
        ];

        if ($this->ExpenseTaskModel->expense_task_update($id, $data)) {
            return $this->response->setJSON(["success" => true]);
        } else {
            return $this->response->setJSON(["success" => false]);
        }
    }
    public function expense_task_delete()
    {
        $id = $_POST["id"];

        if ($this->ExpenseTaskModel->task_delete($id)) {
            return $this->response->setJSON(["success" => true]);
        } else {
            return $this->response->setJSON(["success" => false]);
        }
    }
    public function expense_reminder_add($id)
    {
        $data = [
            "title" => "Expense Reminder",
            "notify_at" => $this->request->getPost("start_reminder_date"),
            "related_id" => $id,
            "related_to" => "expense",
            "notify_text" => $this->request->getPost("reminder_description"),
            "notify_email" => $this->request->getPost("email_reminder"),
            "created_by" => get_user_id(),
            "user_id" => $this->request->getPost("reminder_to"),
            "related_base_url" => "erp.expenses.view.page",
            "job_id" => 0
        ];
        if (isset($data["notify_email"])) {
            $data["notify_email"] = 1;
        } else {
            $data["notify_email"] = 0;
        }
        if ($this->NotificationModel->insertexpensenotify($data)) {
            return $this->response->setJSON(["success" => true]);
        } else {
            return $this->response->setJSON(["success" => false]);
        }
    }
    public function expense_reminder_exist_update()
    {
        $id = $this->request->getPost("reminder_id");
        $data = [
            "notify_at" => $this->request->getPost("start_reminder_date"),
            "notify_text" => $this->request->getPost("reminder_description"),
            "notify_email" => $this->request->getPost("email_reminder"),
            "user_id" => $this->request->getPost("reminder_to")
        ];

        if (isset($data["notify_email"])) {
            $data["notify_email"] = 1;
        } else {
            $data["notify_email"] = 0;
        }

        if ($this->NotificationModel->updateexpenssenotify($id, $data)) {
            return $this->response->setJSON(["success" => true]);
        } else {
            return $this->response->setJSON(["success" => false]);
        }
    }

    public function expense_reminder_delete()
    {
        $id = $_POST["id"];
        if ($this->NotificationModel->deleteReminder($id)) {
            return $this->response->setJSON(["success" => true]);
        } else {
            return $this->response->setJSON(["success" => false]);
        }
    }

    public function contract_export()
    {
        $type = $_GET["export"];
        $config = array();
        $config["title"] = "Expense";
        if ($type == "pdf") {
            $config["column"] = array("Sno", "Category", "Amount", "Name", "Receipt", "Date", "Project", "Customer", "Invoice", "Reference #", "Payment Mode");
        } else {
            $config["column"] = array("Sno", "Category", "Amount", "Name", "Receipt", "Date", "Project", "Customer", "Invoice", "Reference #", "Payment Mode");
            $config["column_data_type"] = array(ExporterConst::E_INTEGER, ExporterConst::E_STRING, ExporterConst::E_STRING, ExporterConst::E_STRING, ExporterConst::E_STRING, ExporterConst::E_STRING, ExporterConst::E_STRING, ExporterConst::E_STRING, ExporterConst::E_STRING, ExporterConst::E_STRING, ExporterConst::E_STRING);
        }
        $config["data"] = $this->ExpensesModel->get_expense_data();

        // var_dump($config["data"]);
        // exit;

        if ($type == "pdf") {
            Exporter::export(ExporterConst::PDF, $config);
        } else if ($type == "csv") {
            Exporter::export(ExporterConst::CSV, $config);
        } else if ($type == "excel") {

            Exporter::export(ExporterConst::EXCEL, $config);
        }
    }

    public function contract_response_export(){

        $logger = \Config\services::logger();

        $type = $_GET["export"];
        $year = $_GET["year"];

        $config = array();
        $config["title"] = "Yearly Expense Report (".$year.')';
        $config["column"] = array("Expense Category","January","February","March","April","May","June","July","August","September","October","November","December","Year");
        $config["column_data_type"] = array(ExporterConst::E_STRING,ExporterConst::E_STRING,
        ExporterConst::E_STRING,ExporterConst::E_STRING,ExporterConst::E_STRING,ExporterConst::E_STRING,ExporterConst::E_STRING,
        ExporterConst::E_STRING,ExporterConst::E_STRING,ExporterConst::E_STRING,ExporterConst::E_STRING,ExporterConst::E_STRING,
        ExporterConst::E_STRING,ExporterConst::E_STRING);

        $config["data"] = $this->ExpensesModel->expense_export_report($year);

        // var_dump($config["data"]);
        // exit;

        if ($type == "pdf") {
            Exporter::export(ExporterConst::PDF, $config);
        } else if ($type == "csv") {
            Exporter::export(ExporterConst::CSV, $config);
        } else if ($type == "excel") {
            Exporter::export(ExporterConst::EXCEL, $config);
        }
    }

    public function ajaxExpenseTable()
    {
        if ($this->request->getPost()) {

            $logger = \Config\services::logger();

            $year = $this->request->getPost("val");
            $data = $this->ExpensesModel->get_Report($year);

            // $logger->error(print_r($data));

            $array1 = [];

            $array2 = [];

            $array3 = [];

            $array4 = [];

            foreach ($data as $rec) {

                // $logger->error($rec);
                $grandTotal = 0;

                for ($month = 1; $month <= 13; $month++) {
                    // Format the start of each month (e.g., "2024-01-01")
                    $startOfMonth = "$year-" . str_pad($month, 2, '0', STR_PAD_LEFT) . "-01";

                    // Format the end of each month (e.g., "2024-01-31")
                    $endOfMonth = date('Y-m-t', strtotime($startOfMonth));

                    // Query for expenses in the given month for the current category
                    $expense = $this->ExpensesModel->where("category", $rec['id'])
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
                        $array1[$rec['name']][$month] = $grandTotal;
                    } else {
                        $array1[$rec['name']][$month] = $total;
                    }
                }
            }

            $netammountGrandTotal = 0;
            $gstammountGrandTotal = 0;
            $totalammountGrandTotal = 0;

            for ($mon = 1; $mon <= 13; $mon++) {

                $startOfMonth2 = "$year-" . str_pad($mon, 2, '0', STR_PAD_LEFT) . "-01";

                $onemonth = date('Y-m-t', strtotime($startOfMonth2));

                $netammount = $this->ExpensesModel->select("amount,tax,tax2,date")
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
                    // $logger->error($netammounttotal);
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
                    $array2[$mon] = $netammountGrandTotal;
                    $array3[$mon] = $gstammountGrandTotal;
                    $array4[$mon] = $totalammountGrandTotal;
                } else {
                    $array2[$mon] = $netammounttotal;
                    $array3[$mon] = $gstammounttotal;
                    $array4[$mon] = $totalammounttotal;
                }

            }

            // $logger->error($array1);
            // $logger->error($array2);
            // $logger->error($array3);
            // $logger->error($array4);

            return $this->response->setJSON(["data" => $array1, "netamount" => $array2, "gst_amount" => $array3, "total_amount" => $array4]);

        }
    }
}
