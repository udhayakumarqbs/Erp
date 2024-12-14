<?php

namespace App\Controllers\Erp;

use TCPDF;
use App\Controllers\BaseController;
use App\Controllers\Erp\DateTime;
use App\Models\Erp\CustomFieldModel;
use App\Models\Erp\ErpCompanyInformationModel;
use App\Models\Erp\Sale\RequestModel;
use App\Models\Erp\ExpensesModel;

// use App\Libraries\Requestor;
use App\Libraries\PdfCreater as LibrariesPdfCreater;
use App\Libraries\Requestor;
use App\Libraries\Requests\AbstractRequest;
use App\Models\Erp\Sale\EstimatesModel;
use App\Libraries\Exporter;
use App\Libraries\Exporter\ExporterConst;
use App\Models\Erp\NotificationModel;
use App\Models\Erp\Sale\OrdersModel;
use App\Models\Erp\Sale\QuotationModel;
use App\Models\Erp\Sale\InvoiceModel;
use App\Models\Erp\Sale\CreditNotesModel;
use App\Models\Erp\Sale\SalePaymentModel;
use App\Models\Erp\Sale\DispatchModel;
use App\Models\Erp\Sale\SaleOrderModel;
use App\Models\Erp\CustomersModel;
use App\Models\Erp\UserModel;
use App\Models\Erp\CurrenciesModel;
use App\Models\Erp\PaymentModesModel;

use App\Models\Erp\Email_templates_model;

use App\Libraries\SendMailer;

// use CodeIgniter\HTTP\RequestInterface;
// use CodeIgniter\Session\Session;
// use PhpOffice\PhpSpreadsheet\Calculation\MathTrig\Subtotal;

class SaleController extends BaseController
{
    protected $SaleModel;
    protected $RequestModel;
    protected $CustomFieldModel;
    protected $EstimatesModel;
    protected $NotifyModel;
    protected $OrdersModel;
    protected $InvoiceModel;
    protected $CreditNotesModel;
    protected $QuotationModel;
    protected $SalePaymentModel;
    protected $DispatchModel;
    protected $SaleOrderModel;
    protected $CustomersModel;
    protected $UserModel;
    protected $ErpCompanyInformationModel;
    protected $CurrenciesModel;
    protected $PaymentModesModel;
    protected $ExpensesModel;


    protected $Invoices_model;

    /**
     * Summary of Users_model Used This for Customer Contact Info
     * @var $Users_model
     */
    protected $Users_model;

    /**
     * Summary of Email_templates_model
     * @var $Email_templates_model
     */
    protected $Email_templates_model;

    protected $db;
    protected $session;


    public function __construct()
    {
        //parent::__construct();
        helper('erp');
        helper('pdf');
        helper('form');
        //$this->SaleModel = new SaleModel();
        $this->RequestModel = new RequestModel();
        $this->CustomFieldModel = new CustomFieldModel();
        $this->EstimatesModel = new EstimatesModel();
        $this->NotifyModel = new NotificationModel();
        $this->QuotationModel = new QuotationModel();
        $this->OrdersModel = new OrdersModel();
        $this->InvoiceModel = new InvoiceModel();
        $this->CreditNotesModel = new CreditNotesModel();
        $this->SalePaymentModel = new SalePaymentModel();
        $this->DispatchModel = new DispatchModel();
        $this->SaleOrderModel = new SaleOrderModel();
        $this->CustomersModel = new CustomersModel();
        $this->UserModel = new UserModel();
        $this->CurrenciesModel = new CurrenciesModel();
        $this->ErpCompanyInformationModel = new ErpCompanyInformationModel();
        $this->PaymentModesModel = new PaymentModesModel();
        $this->ExpensesModel = new ExpensesModel();
        $this->db = \Config\Database::connect();
        $this->session = session();


        // i did this because i have some somberithanam!
        $this->Email_templates_model = new Email_templates_model();
        $this->Invoices_model = new InvoiceModel();
        $this->Users_model = new CustomersModel();
    }
    //request
    public function Request()
    {
        $data['request_datatable_config'] = $this->RequestModel->getDatatableConfig();
        $data['page'] = "sale/request";
        $data['menu'] = "sale";
        $data['submenu'] = "request";
        return view("erp/index", $data);
    }

    public function convertorderQuote($quote_id)
    {
        $post = [
            'order_expiry' => $this->request->getPost("order_expiry"),
            'code' => $this->request->getPost("code"),
        ];
        if ($this->request->getPost("order_expiry")) {
            if ($this->SaleOrderModel->convert_order_from_quote($quote_id, $post)) {
                return $this->response->redirect(url_to("erp.sale.orders"));
            } else {
                return $this->response->redirect(url_to("erp.sale.quotations", $quote_id));
            }
        }
        return $this->response->redirect(url_to("erp.sale.quotations", $quote_id));
    }

    public function AjaxRequestResponse()
    {
        $response = array();
        $response = $this->RequestModel->getRequestDatatable();
        header("Content-Type:application/json");
        echo json_encode($response);
        exit();
    }

    // Request Add
    public function Requestadd()
    {
        if ($this->request->getPost("request_type")) {
            $requestor = new Requestor($this->request->getPost("request_type"));
            if ($requestor->request(true)) {
                return $this->response->redirect(url_to('erp.sale.requests'));
            } else {
                return $this->response->redirect(url_to('erp.sale.requests.edit'));
            }
        }

        $requestor = new Requestor();
        $data['requesttypes'] = $requestor->getRequestTypes("Sales");
        $data['attach_filetypes'] = $requestor->getAllowedFileTypes();
        $data['attach_maxfilesize'] = $requestor->getMaxFileSize();
        $data['page'] = "sale/requestadd";
        $data['menu'] = "sale";
        $data['submenu'] = "request";

        return view("erp/index", $data);
    }

    public function requestaction($request_id)
    {
        if ($_POST["action"]) {
            $this->RequestModel->process_requestaction($request_id);
        }
        redirect("erp/sale/requestview/" . $request_id);
    }

    // Code for Estimates
    public function Estimates()
    {

        $data['estimate_datatable_config'] = $this->EstimatesModel->getDtconfigEstimates();
        $data['page'] = "sale/estimates";
        $data['menu'] = "sale";
        $data['submenu'] = "estimate";
        return view("erp/index", $data);
    }

    public function ajax_estimate_response()
    {
        $response = array();
        $response = $this->EstimatesModel->getEstimateDatatable();
        header("Content-Type:application/json");
        echo json_encode($response);
        exit();
    }

    // Add
    public function EstimateAdd()
    {
        if ($this->request->getPost("code")) {
            // var_dump($this->request->getPost("terms_condition"));
            // exit;
            $created_by = get_user_id();

            $dataPost = [
                'code' => $this->request->getPost("code"),
                'cust_id' => $this->request->getPost("cust_id"),
                'estimate_date' => $this->request->getPost("estimate_date"),
                'shippingaddr_id' => $this->request->getPost("shippingaddr_id"),
                'terms_condition' => $this->request->getPost("terms_condition"),
                'created_at' => time(),
                'updated_at' => time(),
                'created_by' => $created_by,
            ];

            if ($this->EstimatesModel->insertEstimate($dataPost)) {
                return $this->response->redirect(url_to('erp.sale.estimates'));
            } else {
                return $this->response->redirect(url_to('erp.sale.estimates.add'));
            }
        }
        $data['maxCode'] = $this->EstimatesModel->getMaximumEstimateNumber();
        // return var_dump($data['maxCode']);
        $data['page'] = "sale/m_estimateadd";
        $data['menu'] = "sale";
        $data['submenu'] = "estimate";
        return view("erp/index", $data);
    }

    public function estimateExport()
    {
        $type = $_GET['export'];
        $config = array();
        $config['title'] = "Estimates";
        if ($type == "pdf") {
            $config['column'] = array("Code", "Customer", "Shipping Address", "Estimate Date", "Total Amount", "Terms and Condition");
        } else {
            $config['column'] = array("Code", "Customer", "Shipping Address", "Estimate Date", "Total Amount", "Terms and Condition");
            $config['column_data_type'] = array(ExporterConst::E_STRING, ExporterConst::E_STRING, ExporterConst::E_STRING, ExporterConst::E_STRING, ExporterConst::E_STRING, ExporterConst::E_STRING);
        }
        $config['data'] = $this->EstimatesModel->get_estimate_export($type);
        if ($type == "pdf") {
            Exporter::export(ExporterConst::PDF, $config);
        } else if ($type == "excel") {
            Exporter::export(ExporterConst::EXCEL, $config);
        } else if ($type == "csv") {
            Exporter::export(ExporterConst::CSV, $config);
        }
    }


    // View
    public function EstimateView($estimate_id)
    {
        $user_email = session()->get('erp_email');
        $user_name = session()->get('erp_username');
        $data['estimate'] = $this->EstimatesModel->get_m_estimate_for_view($estimate_id);
        if (empty($data['estimate'])) {
            session()->setflashdata("op_error", "Estimate Not Found");
            redirect("erp/sale/estimates");
        }
        $data['estimate_id'] = $estimate_id;
        $data['estimate_items'] = $this->EstimatesModel->get_estimate_items_for_view($estimate_id);
        $data['notify_datatable_config'] = $this->EstimatesModel->get_dtconfig_notify();
        $data['attachments'] = $this->EstimatesModel->get_attachments("estimate", $estimate_id);
        $data['user_email'] = $user_email;
        $data['user_name'] = $user_name;
        $customerContact = $this->EstimatesModel->getCustomerContactById($estimate_id);
        $customerMail = $this->EstimatesModel->getCustomerMailById($estimate_id);
        if (!empty($customerContact)) {
            $data['customerContact'] = $customerContact;
        } else {
            $data['customerContact'] = $customerMail;
        }
        // echo '<pre>';
        // return var_dump($data);
        $data['page'] = "sale/m_estimateview";
        $data['menu'] = "sale";
        $data['submenu'] = "estimate";
        return view("erp/index", $data);
    }

    // Estimate Edit
    public function EstimateEdit($estimate_id)
    {
        if ($this->request->getPost("code")) {
            $data['product_ids'] = $this->request->getPost("product_id");
            $data['quantities'] = $this->request->getPost("quantity");
            $data['price_ids'] = $this->request->getPost("price_id");
            if ($this->EstimatesModel->updateEstimates($estimate_id, $data)) {
                return $this->response->redirect(url_to('erp.sale.estimates'));
            } else {
                // redirect("erp/sale/estimateedit/".$estimate_id);
                return $this->response->redirect(url_to('erp.sale.estimates.edit', $estimate_id));
            }
        }
        $data['estimate'] = $this->EstimatesModel->get_m_estimate_by_id($estimate_id);
        if (empty($data['estimate'])) {
            $this->session->setFlashdata("op_error", "Estimate Not Found");
            return $this->response->redirect(url_to('erp.sale.estimates'));
        }
        $data['shippingaddr'] = $this->EstimatesModel->get_m_shipping_addr($data['estimate']->cust_id);
        $data['estimate_items'] = $this->EstimatesModel->get_m_estimate_items($estimate_id);
        $data['page'] = "sale/m_estimateedit";
        $data['estimate_id'] = $estimate_id;
        $data['menu'] = "sale";
        $data['submenu'] = "estimate";
        return view("erp/index", $data);
    }

    // Estimate Delete
    //IMPLEMENTED BY ASHOK
    public function EstimateDelete($estimate_id)
    {

        $estimate = $this->EstimatesModel->get_m_estimate_by_id($estimate_id);

        if (empty($estimate)) {
            session()->setFlashdata("op_error", "Estimate Not Found");
            return $this->response->redirect(url_to('erp.sale.estimates'));
        }

        $deleted = $this->EstimatesModel->DeleteEstimate($estimate_id);

        if ($deleted) {
            // Equipment successfully deleted
            session()->setFlashdata("op_success", "Equipment successfully deleted");
        } else {
            // Failed to delete Estimate
            session()->setFlashdata("op_error", "Equipment failed to delete");
        }

        return $this->response->redirect(url_to('erp.sale.estimates'));
    }

    // Upload Attachements
    public function uploadEstimateAttachment()
    {
        $response = array();
        if (!empty($_FILES['attachment']['name'])) {
            $response = $this->EstimatesModel->upload_attachment("estimate");
        } else {
            $response['error'] = 1;
            $response['reason'] = "No file to upload";
        }
        header("Content-Type:application/json");
        echo json_encode($response);
        exit();
    }
    // Delete Attachements
    public function deleteEstimateAttachment()
    {
        $response = array();
        $response = $this->EstimatesModel->delete_attachment("estimate");
        header("Content-Type:application/json");
        echo json_encode($response);
        exit();
    }

    // Customer Fetch
    public function ajaxFetchCustomers()
    {
        $search = $_GET['search'] ?? "";
        $response = array();
        $response['error'] = 1;
        if (!empty($search)) {
            $query = "SELECT cust_id,CONCAT(name,' - ',company) AS name FROM customers WHERE name LIKE '%" . $search . "%' OR company LIKE '%" . $search . "%' LIMIT 10";
            $result = $this->db->query($query)->getResultArray();
            $m_result = array();
            foreach ($result as $r) {
                array_push($m_result, array(
                    "key" => $r['cust_id'],
                    "value" => $r['name']
                ));
            }
            $response['error'] = 0;
            $response['data'] = $m_result;
        }
        header("Content-Type:application/json");
        echo json_encode($response);
        exit();
    }

    // fetch Shipping address
    public function ajaxFetchShippingAddr()
    {
        $cust_id = $_GET['cust_id'] ?? 0;
        $response = array();
        $response['error'] = 1;
        if (!empty($cust_id)) {
            $query = "SELECT shippingaddr_id,CONCAT(address,' , ',city) AS name FROM customer_shippingaddr WHERE cust_id=$cust_id ";
            $result = $this->db->query($query)->getResultArray();
            $m_result = array();
            foreach ($result as $r) {
                array_push($m_result, array(
                    "key" => $r['shippingaddr_id'],
                    "value" => $r['name']
                ));
            }
            $response['error'] = 0;
            $response['data'] = $m_result;
        } else {
            $response['reason'] = "No shipping address";
        }
        header("Content-Type:application/json");
        echo json_encode($response);
        exit();
    }

    public function ajaxEditShippingAddr()
    {
        $cust_id = $_POST['cust_id'] ?? 0;
        $response = array();
        if (!empty($cust_id)) {
            $query = "SELECT shippingaddr_id, address, city, state, country, zipcode FROM customer_shippingaddr WHERE cust_id=$cust_id ";
            $result = $this->db->query($query)->getResultArray()[0];
            $response['data'] = $result;
        } else {
            $response['data'] = "No shipping address";
        }
        header("Content-Type:application/json");
        echo json_encode($response);
        exit();
    }


    // fetch Billing address
    public function ajaxFetchBillingAddr()
    {
        $cust_id = $_GET['cust_id'] ?? 0;
        $response = array();
        $response['error'] = 1;
        if (!empty($cust_id)) {
            $query = "SELECT billingaddr_id,CONCAT(address,' , ',city) AS name FROM customer_billingaddr WHERE cust_id=$cust_id ";
            $result = $this->db->query($query)->getResultArray();
            $m_result = array();
            foreach ($result as $r) {
                array_push($m_result, array(
                    "key" => $r['billingaddr_id'],
                    "value" => $r['name']
                ));
            }
            $response['error'] = 0;
            $response['data'] = $m_result;
        } else {
            $response['reason'] = "No billing address";
        }
        header("Content-Type:application/json");
        echo json_encode($response);
        exit();
    }


    //Invoice Edit Shipping Address

    public function shippingEdit()
    {
        $updatedValues = $this->request->getPost('updatedValues');
        $created_by = get_user_id();

        $shipping = [
            'cust_id' => $updatedValues['customer_id'],
            'address' => $updatedValues['updatedShippAddress'],
            'city' => $updatedValues['updatedShippCity'],
            'state' => $updatedValues['updatedShippState'],
            'zipcode' => $updatedValues['updatedShippZipcode'],
            'country' => $updatedValues['updatedShippCountry'],
            'created_by' => $created_by,
            'created_at' => time()
        ];

        $shippingData = $this->InvoiceModel->updateShippingAddress($shipping);

        $billing = [
            'cust_id' => $updatedValues['customer_id'],
            'address' => $updatedValues['updatedBillingAddress'],
            'city' => $updatedValues['updatedBillingCity'],
            'state' => $updatedValues['updatedBillingState'],
            'zipcode' => $updatedValues['updatedBillingZipcode'],
            'country' => $updatedValues['updatedBillingCountry'],
            'created_by' => $created_by,
            'created_at' => time()
        ];
        $billingData = $this->InvoiceModel->updateBillingAddress($billing);

        if ($shippingData && $billingData) {
            return $this->response->setJSON(true);
        } else {
            return $this->response->setJSON(false);
        }
    }

    public function getShippingDetails()
    {
        $customer_id = $this->request->getGet('selected_cust_id');
        $data['shippingAddressDetails'] = $this->InvoiceModel->getShippingAddressByshipId($customer_id);
        $data['billingAddressDetails'] = $this->InvoiceModel->getBillingAddressBybillId($customer_id);
        return $this->response->setJSON($data);
    }

    public function getMobileAndEmail()
    {
        $customer_id = $this->request->getGet('select_cust_id');
        $data['mobileEmail'] = $this->InvoiceModel->getMobileEmail($customer_id);
        return $this->response->setJSON($data);
    }


    // Price List
    // public function ajaxFetchPriceList()N
    // {
    //     $product_id = $_GET['product_id'] ?? 0;
    //     //echo var_dump($product_id);
    //     // exit();
    //     $response = array();
    //     $response['error'] = 1;
    //     if (!empty($product_id)) {
    //         $query = "SELECT stocks.price_id,price_list.amount,price_list.name,SUM(stocks.quantity) AS quantity FROM stocks JOIN price_list ON stocks.price_id=price_list.price_id WHERE stocks.related_id=$product_id AND stocks.related_to='finished_good' GROUP BY stocks.price_id";
    //         $result = $this->db->query($query)->getResultArray();
    //         $m_result = array();
    //         foreach ($result as $r) {
    //             $extra = array();
    //             array_push($extra, $r['amount']);
    //             array_push($extra, $r['quantity']);
    //             array_push($m_result, array(
    //                 "key" => $r['price_id'],
    //                 "value" => $r['name'],
    //                 "extra" => $extra
    //             ));
    //         }
    //         $response['error'] = 0;
    //         $response['data'] = $m_result;
    //     } else {
    //         $response['reason'] = "No Price List";
    //     }
    //     header("Content-Type:application/json");
    //     echo json_encode($response);
    //     exit();
    // }


    public function ajaxFetchPriceList()
    {
        $product_id = $_GET['product_id'] ?? 0;
        $response = array();
        $response['error'] = 1;

        if (!empty($product_id)) {
            $query = "SELECT stocks.price_id, price_list.amount, price_list.name, taxes1.tax_name AS tax1_name, taxes1.percent AS tax1_percent, taxes2.tax_name AS tax2_name, taxes2.percent AS tax2_percent, SUM(stocks.quantity) AS quantity 
                  FROM stocks 
                  JOIN price_list ON stocks.price_id = price_list.price_id 
                  LEFT JOIN taxes AS taxes1 ON price_list.tax1 = taxes1.tax_id
                  LEFT JOIN taxes AS taxes2 ON price_list.tax2 = taxes2.tax_id
                  WHERE stocks.related_id = $product_id AND stocks.related_to = 'finished_good' 
                  GROUP BY stocks.price_id";

            $result = $this->db->query($query)->getResultArray();
            $m_result = array();

            foreach ($result as $r) {
                $extra = array();
                array_push($extra, $r['amount']);
                array_push($extra, $r['quantity']);

                $tax_info = array(
                    "tax1_name" => $r['tax1_name'],
                    "tax1_percent" => $r['tax1_percent'],
                    "tax2_name" => $r['tax2_name'],
                    "tax2_percent" => $r['tax2_percent']
                );
                array_push($extra, $tax_info);
                array_push($m_result, array(
                    "key" => $r['price_id'],
                    "value" => $r['name'],
                    "extra" => $extra
                ));
            }
            $response['error'] = 0;
            $response['data'] = $m_result;
        } else {
            $response['reason'] = "No Price List";
        }

        header("Content-Type: application/json");
        echo json_encode($response);
        exit();
    }


    public function ajaxEstimateCodeUnique()
    {
        $code = $_GET['data'];
        $id = $_GET['id'] ?? 0;
        $query = "SELECT code FROM estimates WHERE code='$code' LIMIT 1";
        if (!empty($id)) {
            $query = "SELECT code FROM estimates WHERE code='$code' AND estimate_id<>$id LIMIT 1";
        }
        $check = $this->db->query($query)->getRow();
        $response = array();
        if (empty($check)) {
            $response['valid'] = 1;
        } else {
            $response['valid'] = 0;
            $response['msg'] = "Estimate Code already exists";
        }
        header("content-type:application/json");
        echo json_encode($response);
        exit();
    }
    // Notify Response List Estimates
    public function ajaxEstimatenotifyResponse()
    {
        $response = array();
        $response = $this->EstimatesModel->getEstimatenotifyDatatable();
        header("Content-Type:application/json");
        echo json_encode($response);
        exit();
    }

    public function ajaxFetchNotify($notify_id)
    {
        $response = array();
        $query = "SELECT notify_id,notify_email,title,notify_text,notify_at,erp_users.user_id,erp_users.name FROM notification JOIN erp_users ON notification.user_id=erp_users.user_id WHERE notify_id=$notify_id";
        $result = $this->db->query($query)->getRow();
        if (!empty($result)) {
            $response['error'] = 0;
            $result->notify_at = date("Y-m-d\TH:i", $result->notify_at);
            $response['data'] = $result;
        } else {
            $response['error'] = 1;
        }
        header("Content-Type:application/json");
        echo json_encode($response);
        exit();
    }

    public function estimatenotifydelete($estimate_id, $notify_id)
    {
        $this->EstimatesModel->delete_estimate_notify($estimate_id, $notify_id);
        return $this->response->redirect(url_to('erp.sale.estimates.view', $estimate_id));
    }

    public function estimateNotifyExport()
    {
        $type = $_GET['export'];
        $config = array();
        $config['title'] = "Estimate Notification";
        if ($type == "pdf") {
            $config['column'] = array("Title", "Description", "Notify At", "Notify To", "Notified");
        } else {
            $config['column'] = array("Title", "Description", "Notify At", "Notify To", "Notified");
            $config['column_data_type'] = array(ExporterConst::E_STRING, ExporterConst::E_STRING, ExporterConst::E_STRING, ExporterConst::E_STRING, ExporterConst::E_STRING);
        }
        $config['data'] = $this->EstimatesModel->get_estimatenotify_export($type);
        if ($type == "pdf") {
            Exporter::export(ExporterConst::PDF, $config);
        } else if ($type == "excel") {
            Exporter::export(ExporterConst::EXCEL, $config);
        } else if ($type == "csv") {
            Exporter::export(ExporterConst::CSV, $config);
        }
    }

    // Estimate Notify Add
    public function EstimateNotifyAdd($estimate_id)
    {
        if ($_POST["notify_title"]) {
            $this->NotifyModel->insert_update_estimatenotify($estimate_id);
            // exit('Insertion Happened');
        }
        // var_dump($_POST);
        // var_dump($_GET);
        // exit('Insertion Not Happened');
        return $this->response->redirect(url_to('erp.sale.estimates.view', $estimate_id));
    }

    // Quotation 
    public function Quotation()
    {
        $data['quote_status'] = $this->QuotationModel->get_quote_status();
        $data['quotation_datatable_config'] = $this->QuotationModel->get_dtconfig_quotations();
        $data['page'] = "sale/quotations";
        $data['menu'] = "sale";
        $data['submenu'] = "quotation";
        return view("erp/index", $data);
    }

    public function QuotationAdd()
    {
        $inserted = false;
        $logger = \Config\Services::logger();

        // $logger->error($_POST);
        $data['code'] = $this->request->getPost("code");
        $data['cust_id'] = $this->request->getPost("cust_id");
        $data['subject'] = $this->request->getPost("quote_subject");
        $data['quote_date'] = $this->request->getPost("quote_date");
        $data['expiry_date'] = $this->request->getPost("quote_exp_date");
        // $data['email'] = $this->request->getPost("quote_email");
        // $data['phone'] = $this->request->getPost("quote_phone");
        $data['currency_id'] = $this->request->getPost("currency_id");
        $data['currency_place'] = $this->request->getPost("currency_place");
        $data['terms_condition'] = $this->request->getPost("terms_condition");
        $data['payment_terms'] = $this->request->getPost("payment_terms");
        $data['transport_req'] = $this->request->getPost("transport_req") ?? 0;
        $data['trans_charge'] = $this->request->getPost("trans_charge");
        $data['discount'] = $this->request->getPost("discount");
        $data['created_by'] = get_user_id();


        if ($data['code']) {
            // echo '<pre>';
            // return var_dump($data); 
            if ($this->QuotationModel->insert_quotation($data)) {

                return $this->response->redirect(url_to('erp.sale.quotations'));
            } else {
                return $this->response->redirect(url_to('erp.sale.quotation.add'));
            }
        }
        $data['maxCode'] = $this->QuotationModel->getMaximumQuotationNumber();
        $data['currency_place'] = $this->CurrenciesModel->getCurrencyPlace();
        // return var_dump($data['currency_place']);
        $data['page'] = "sale/quotationadd";
        $data['menu'] = "sale";
        $data['submenu'] = "quotation";
        return view("erp/index", $data);
    }

    public function QuotationEdit($quote_id)
    {
        if ($this->request->getPost("code")) {
            if ($this->QuotationModel->update_quotation($quote_id)) {
                return $this->response->redirect(url_to('erp.sale.quotations'));
            } else {
                return $this->response->redirect(url_to('erp.sale.quotations.edit', $quote_id));
            }
        }
        $data['quotation'] = $this->QuotationModel->get_quotation_by_id($quote_id);
        if (empty($data['quotation'])) {
            $this->session->setFlashdata("op_error", "Quotation Not Found");
            return $this->response->redirect(url_to('erp.sale.quotations'));
        }
        $data['shipping_addr'] = $this->QuotationModel->get_m_shipping_addr($data['quotation']->cust_id);
        $data['quote_items'] = $this->QuotationModel->get_quotation_items($quote_id);
        $data['quote_id'] = $quote_id;
        $data['currency_place'] = $this->CurrenciesModel->getCurrencyPlace();
        $data['selected_currency_place'] = $this->QuotationModel->getSelectedCurrencyPlace($quote_id);
        $data['selected_currency_name'] = $this->QuotationModel->getSelectedCurrencyName($quote_id, $data['selected_currency_place']);
        // return var_dump($data['selected_currency_place']);
        $data['page'] = "sale/quotationedit";
        $data['menu'] = "sale";
        $data['submenu'] = "quotation";
        return view("erp/index", $data);
    }

    public function QuotationView($quote_id)
    {
        $user_email = session()->get('erp_email');
        $user_name = session()->get('erp_username');
        $data['quotation'] = $this->QuotationModel->get_quotation_for_view($quote_id);
        if (empty($data['quotation'])) {
            $this->session->setFlashdata("op_error", "Quotation Not Found");
            redirect("erp/sale/quotations");
        }
        $data['quote_status'] = $this->QuotationModel->get_quote_status();
        $data['quote_status_bg'] = $this->QuotationModel->get_quote_status_bg();
        $data['quote_id'] = $quote_id;
        $data['quotation_items'] = $this->QuotationModel->get_quotation_items_for_view($quote_id);
        $data['notify_datatable_config'] = $this->QuotationModel->get_dtconfig_notify();
        $data['attachments'] = $this->QuotationModel->get_attachments("quotation", $quote_id);
        $data['user_email'] = $user_email;
        $data['user_name'] = $user_name;
        $customerContact = $this->QuotationModel->getCustomerContactById($quote_id);
        $customerMail = $this->QuotationModel->getCustomerMailById($quote_id);
        if (!empty($customerContact)) {
            $data['customerContact'] = $customerContact;
        } else {
            $data['customerContact'] = $customerMail;
        }
        // echo '<pre>';
        // return var_dump($data);
        $data['page'] = "sale/quotationview";
        $data['menu'] = "sale";
        $data['submenu'] = "quotation";
        return view("erp/index", $data);
    }
    // End of CRUD

    // Notification
    public function ajax_quotationnotify_response()
    {
        $response = array();
        $response = $this->QuotationModel->get_quotationnotify_datatable();
        header("Content-Type:application/json");
        echo json_encode($response);
        exit();
    }

    public function quotationnotify($quote_id)
    {
        if ($this->request->getPost("notify_title")) {
            $this->NotifyModel->insert_update_quotationnotify($quote_id);
        }
        return $this->response->redirect(url_to('erp.sale.quotations.view', $quote_id));
    }

    public function quotationnotifydelete($quote_id, $notify_id)
    {
        $this->NotifyModel->delete_quotation_notify($quote_id, $notify_id);
        return $this->response->redirect(url_to('erp.sale.quotations.view', $quote_id));
    }


    public function quotation_notify_export()
    {
        $type = $_GET['export'];
        $config = array();
        $config['title'] = "Quotation Notification";
        if ($type == "pdf") {
            $config['column'] = array("Title", "Description", "Notify At", "Notify To", "Notified");
        } else {
            $config['column'] = array("Title", "Description", "Notify At", "Notify To", "Notified");
            $config['column_data_type'] = array(ExporterConst::E_STRING, ExporterConst::E_STRING, ExporterConst::E_STRING, ExporterConst::E_STRING, ExporterConst::E_STRING);
        }
        $config['data'] = $this->NotifyModel->get_quotationnotify_export($type);
        if ($type == "pdf") {
            Exporter::export(ExporterConst::PDF, $config);
        } else if ($type == "excel") {
            Exporter::export(ExporterConst::EXCEL, $config);
        } else if ($type == "csv") {
            Exporter::export(ExporterConst::CSV, $config);
        }
    }
    // QuoteNotification
    public function quotationaccept($quote_id)
    {
        $this->QuotationModel->accept_quotation($quote_id);
        return $this->response->redirect(url_to('erp.sale.quotations.view', $quote_id));
    }

    public function quotationdecline($quote_id)
    {
        $this->QuotationModel->decline_quotation($quote_id);
        return $this->response->redirect(url_to('erp.sale.quotations.view', $quote_id));
    }

    public function ajax_quotations_response()
    {
        $response = array();
        $response = $this->QuotationModel->get_quotation_datatable();
        header("Content-Type:application/json");
        echo json_encode($response);
        exit();
    }

    public function ajax_quotation_code_unique()
    {
        $code = $_GET['data'];
        $id = $_GET['id'] ?? 0;
        $query = "SELECT code FROM quotations WHERE code='$code' LIMIT 1";
        if (!empty($id)) {
            $query = "SELECT code FROM quotations WHERE code='$code' AND quote_id<>$id LIMIT 1";
        }
        $check = $this->db->query($query)->getRow();
        $response = array();
        if (empty($check)) {
            $response['valid'] = 1;
        } else {
            $response['valid'] = 0;
            $response['msg'] = "Quotation Code already exists";
        }
        header("content-type:application/json");
        echo json_encode($response);
        exit();
    }

    public function upload_quoteattachment()
    {
        $response = array();
        if (!empty($_FILES['attachment']['name'])) {
            $response = $this->QuotationModel->upload_attachment("quotation");
        } else {
            $response['error'] = 1;
            $response['reason'] = "No file to upload";
        }
        header("Content-Type:application/json");
        echo json_encode($response);
        exit();
    }

    public function quote_delete_attachment()
    {
        $response = array();
        $response = $this->QuotationModel->delete_attachment("quotation");
        header("Content-Type:application/json");
        echo json_encode($response);
        exit();
    }

    public function QuotationExport()
    {
        $type = $_GET['export'];
        $config = array();
        $config['title'] = "Quotations";
        if ($type == "pdf") {
            $config['column'] = array("Code", "Customer", "Quotation Date", "Status", "Transport Requested", "Transport Charge", "Discount", "Total Amount");
        } else {
            $config['column'] = array("Code", "Customer", "Shipping Address", "Quotation Date", "Status", "Transport Requested", "Transport Charge", "Discount", "Total Amount", "Payment Terms", "Terms Condition");
            $config['column_data_type'] = array(ExporterConst::E_STRING, ExporterConst::E_STRING, ExporterConst::E_STRING, ExporterConst::E_STRING, ExporterConst::E_STRING, ExporterConst::E_STRING, ExporterConst::E_STRING, ExporterConst::E_STRING, ExporterConst::E_STRING, ExporterConst::E_STRING);
        }
        $config['data'] = $this->QuotationModel->get_quotation_export($type);
        if ($type == "pdf") {
            Exporter::export(ExporterConst::PDF, $config);
        } else if ($type == "excel") {
            Exporter::export(ExporterConst::EXCEL, $config);
        } else if ($type == "csv") {
            Exporter::export(ExporterConst::CSV, $config);
        }
    }

    //Quotation Delete Implemented

    public function quotation_delete($quote_id)
    {

        $QuotationModel = new QuotationModel();
        $quotation = $QuotationModel->get_quote_by_id($quote_id);

        if (empty($quotation)) {
            session()->setFlashdata("op_error", "Quotation Not Found");
            return $this->response->redirect(url_to('erp.sale.quotations'));
        }


        $deleted = $QuotationModel->delete_Quotation($quote_id);

        if ($deleted) {

            session()->setFlashdata("op_success", "Quotation successfully deleted");
        } else {

            session()->setFlashdata("op_error", "Quotation failed to delete");
        }
        return $this->response->redirect(url_to('erp.sale.quotations'));
    }

    public function quotationpdf($quotation_id, $type)
    {
        $quotation_data = $this->QuotationModel->getQuotationNumberById($quotation_id);
        $customer_data = $this->QuotationModel->getCustomerDataByQuotationId($quotation_id);
        $organisation_data = $this->ErpCompanyInformationModel->getAlldata();
        $quotation_items = $this->QuotationModel->getQuotationItemsById($quotation_id);
        $currency_place = $this->QuotationModel->getCurrencyPlaceById($quotation_id);
        $currency_symbol = $this->QuotationModel->getCurrencySymbolbyId($quotation_id);
        $currenYear = date('Y');
        // echo '<pre>';
        // return var_dump($currency_place['currency_place']);

        $pdf = new TCPDF();
        $pdf->SetTitle($quotation_data['code']);
        $pdf->setPrintHeader(false);
        $pdf->AddPage();

        // Logo on the left
        $logo = '<div><img src="' . base_url() . 'assets/images/' . $organisation_data['company_logo'] . '" style="width:150px;height:50px;" ></div>';

        // Quotation information on the right
        $quotationInfo = '<div style="text-align: right;">';
        $quotationInfo .= '<span style="font-family:Times New Roman;font-size:14px;">Ref Number 
                         ' . $quotation_data['code'] . '</span><br />
                         <span style="font-family:Times New Roman;font-size:12px;">Our Ref  
                        #' . $quotation_data['subject'] . '</span><br /><br />
                        <span style="font-family:Times New Roman;font-size:12px;">Quote Ref # ' . $quotation_data['code'] . '/' . $currenYear . '</span>
                         ';

        //Invoice date & Due date
        $datetime = strtotime($quotation_data['quote_date']);
        $format = date("d F Y", $datetime);
        $quotationInfo .= '<div style = "font-family:Times New Roman;">Quotation Date: ' . $format . '<br />';
        $datetime = strtotime($quotation_data['expiry_date']);
        $format = date("d F Y", $datetime);
        $quotationInfo .= 'Quotation Expiry Date: ' . $format . '</div></div>';
        $table = '<table width="100%" border="0"><tr>';
        $table .= '<td width="50%">' . $logo . '</td>';
        $table .= '<td width="50%">' . $quotationInfo . '</td>';
        $table .= '</tr></table>';
        $pdf->writeHTML($table, true, false, false, false, '');
        $pdf->ln(5);


        //Customer Data 
        if (!empty($customer_data)) {
            $customerData = '<div style = "text-align:left; font-family:Times New Roman;"><b>Customer :</b>
                             <div style = "font-family: Times New Roman; font-size: 14px;">' . $customer_data['company'] . '</div>
                             Kind Attn: ' .
                $customer_data['name'] . ',<br/>' .
                $customer_data['address'] . ',<br/>' .
                $customer_data['city'] . ', ' . $customer_data['state'] . ',<br/>' .
                $customer_data['country'] . ',<br/>' .
                $customer_data['phone'] . ',<br/>Customer Email: ' .
                $customer_data['email'] . '<br/></div>';
        }

        //Organization Information
        $organizationInfo = '<div style = "text-align:right;font-family:Times New Roman;"><b>' . $organisation_data['company_name'] . '</b>
                                <div style="color:#2b2929;">
                                    ' . $organisation_data['address'] . ',<br/>
                                    ' . $organisation_data['city'] . ',<br/>
                                    ' . $organisation_data['state'] . ',<br/>
                                    ' . $organisation_data['country'] . ',<br/>
                                    ' . $organisation_data['zipcode'] . '.<br/>
                        Mobile No.: ' . $organisation_data['phone_number'] . '<br/>
                        VAT Number: ' . $organisation_data['vat_number'] . '<br/>
                            License ' . $organisation_data['license_number'] . '<br/>
                            </div></div>';

        $table = '<table width="100%" border="0"><tr>';
        $table .= '<td width="50%">' . $customerData . '</td>';
        $table .= '<td width="50%">' . $organizationInfo . '</td>';
        $table .= '</tr></table>';

        $pdf->writeHTML($table, true, false, false, false, '');
        $pdf->ln(4);


        //Quotation Items Table and Summary
        if (!empty($quotation_items)) {
            $tableHTML = '<table border="1" cellspacing="0" cellpadding="5">
                <tr style="text-align:center; background-color:#063970; color: white; font-family:Times New Roman;">
                    <th>S.No</th>
                    <th>Product</th>
                    <th>Qty</th>
                    <th style="font-family:Times New Roman; font-size:11px;">Unit Price</th>
                    <th style="font-family:Times New Roman; font-size:11px;">VAT</th>
                    <th style="font-family:Times New Roman; font-size:11px;">Amount</th>
                </tr>';
            $tableHTML .= '<tbody>';

            $s_no = 0;
            $sub_total = 0;
            $total_tax = 0;
            $total_amount_with_tax = 0;

            foreach ($quotation_items as $row) {
                ++$s_no;
                $tableHTML .= '<tr style = "text-align:center;">
                    <td style="font-family:Times New Roman; font-size:11px;">' . $s_no . '</td>
                    <td style="font-family:Times New Roman; font-size:11px;">' . $row['product_name'] . '</td>
                    <td style="font-family:Times New Roman; font-size:11px;">' . $row['quantity'] . '</td>
                    <td style="font-family:Times New Roman; font-size:11px;">' . number_format($row['unit_price'], 2, '.', ',') . '</td>
                    <td style="font-family:Times New Roman; font-size:11px;">' . $row['tax1_rate'] . '-' . $row['tax1_amount'] . '%,<br/>' . $row['tax2_rate'] . '-' . $row['tax2_amount'] . '%' . '</td>
                    <td style="font-family:Times New Roman; font-size:11px;">' . number_format($row['amount'], 2, '.', ',') . '</td>
                </tr>';
                $sub_total += $row['amount'];
                $total_tax += $row['total_tax'];
                $total_amount_with_tax += $row['unit_price'] + ($row['unit_price'] * $row['total_tax']);
                $fianl_amount = $total_amount_with_tax + ($row['trans_charge'] ?? 0) - ($row['discount'] ?? 0);
            }

            //Set font For all currencies symbol accept
            $pdf->SetFont('freeserif', '', 12);

            //Currency Position 
            if ($currency_place['currency_place'] === 'after') {
                $subTotal = number_format($sub_total, 2, '.', ',') . $currency_symbol['symbol'];
                $finalAmount = number_format($fianl_amount, 2, '.', ',') . $currency_symbol['symbol'];
            } elseif ($currency_place['currency_place'] === 'before') {
                $subTotal = $currency_symbol['symbol'] . number_format($sub_total, 2, '.', ',');
                $finalAmount = $currency_symbol['symbol'] . number_format($fianl_amount, 2, '.', ',');
            }

            // return var_dump($subTotal);

            // Summary Section
            $tableHTML .= '<tr>
                <td colspan="5" style="text-align:right; font-family:Times New Roman; font-size:11px;background-color:#f0f0f0;"><b>Sub Total</b></td>
                <td style="text-align:center; font-size:11px;background-color:#f0f0f0;">' . $subTotal . '</td>
             </tr>';
            // $tableHTML .= '<tr>
            //     <td colspan="5" style="text-align:right; font-family:Times New Roman; font-size:11px;background-color:#f0f0f0;"><b>VAT</b></td>
            //     <td style="text-align:center; font-family:Times New Roman; font-size:11px;background-color:#f0f0f0;">' . $total_tax . '</td>
            // </tr>';
            $tableHTML .= '<tr>
                <td colspan="5" style="text-align:right; font-family:Times New Roman; font-size:11px;background-color:#f0f0f0;"><b>Total Inc. VAT</b></td>
                <td style="text-align:center; font-size:11px;background-color:#f0f0f0;">' . $finalAmount . '</td>
            </tr></tbody></table>';

            $pdf->writeHTML($tableHTML, true, false, false, false, '');
        }
        $pdf->ln(5);

        // $para = '<center>' . '<p style="text-align:center;font-family:Times New Roman;">Amount in Words: </p>' . '</center>';
        // $pdf->writeHTML($para, true, false, false, false, '');

        $pdfFilePath = FCPATH . 'uploads/quotation/' . $quotation_id . '.pdf';
        if ($type == 'view') {
            $pdf->Output('quotation.pdf', 'I');
        } elseif ($type == 'download') {
            $pdf->Output($pdfFilePath, 'F');
            header('Content-Type: application/pdf');
            header('Content-Disposition: attachment; filename="quotation.pdf"');
            readfile($pdfFilePath);
        }
        exit();
    }



    // Orders Starts From Here
    public function Orders()
    {
        if (is_manufacturing_system()) {
            $data['order_status'] = $this->OrdersModel->get_order_status();
            $data['order_datatable_config'] = $this->OrdersModel->get_dtconfig_m_orders();
            $data['page'] = "sale/m_orders";
        } else {
            $data['order_status'] = $this->OrdersModel->get_order_status();
            $data['order_datatable_config'] = $this->OrdersModel->get_dtconfig_c_orders();
            $data['page'] = "sale/c_orders";
        }
        $data['menu'] = "sale";
        $data['submenu'] = "order";
        return view("erp/index", $data);
    }

    public function ajax_m_order_response()
    {
        $response = array();
        $response = $this->OrdersModel->get_m_order_datatable();
        header("Content-Type:application/json");
        echo json_encode($response);
        exit();
    }

    public function ajax_c_order_response()
    {
        $response = array();
        $response = $this->OrdersModel->get_c_order_datatable();
        header("Content-Type:application/json");
        echo json_encode($response);
        exit();
    }

    public function ajaxFetchOrders()
    {
        if (is_manufacturing_system()) {
            $this->ajax_m_order_response();
        } else {
            $this->ajax_c_order_response();
        }
    }

    public function OrderAdd()
    {
        if (is_manufacturing_system()) {
            if ($this->request->getPost("code")) {
                $updated = true;
                $data['product_ids'] = $this->request->getPost("product_id");
                $data['quantities'] = $this->request->getPost("quantity");
                $data['price_ids'] = $this->request->getPost("price_id");
                if ($this->OrdersModel->insert_m_order()) {
                    return $this->response->redirect(url_to('erp.sale.orders'));
                } else {
                    return $this->response->redirect(url_to('erp.sale.orders.orderadd'));
                }
            }
            $data['maxCode'] = $this->OrdersModel->getMaximumOrderNumber();
            // return var_dump($data['maxCode']);
            $data['page'] = "sale/m_orderadd";
        } else {
            if ($this->request->getPost("code")) {
                if ($this->OrdersModel->insert_c_order()) {
                    return $this->response->redirect(url_to('erp.sale.orders'));
                } else {
                    return $this->response->redirect(url_to('erp.sale.orders.orderadd'));
                }
            }
            $data['maxCode'] = $this->OrdersModel->getMaximumOrderNumber();
            $data['page'] = "sale/c_orderadd";
        }
        $data['menu'] = "sale";
        $data['submenu'] = "order";
        return view("erp/index", $data);
    }

    public function ajax_order_code_unique()
    {
        $code = $_GET['data'];
        $id = $_GET['id'] ?? 0;
        $query = "SELECT code FROM sale_order WHERE code='$code' LIMIT 1";
        if (!empty($id)) {
            $query = "SELECT code FROM sale_order WHERE code='$code' AND order_id<>$id LIMIT 1";
        }
        $check = $this->db->query($query)->getRow();
        $response = array();
        if (empty($check)) {
            $response['valid'] = 1;
        } else {
            $response['valid'] = 0;
            $response['msg'] = "Order Code already exists";
        }
        header("content-type:application/json");
        echo json_encode($response);
        exit();
    }

    public function ajaxfetchproperties()
    {
        $search = $_GET['search'] ?? "";
        $response = array();
        $response['error'] = 1;
        if (!empty($search)) {
            $query = "SELECT property_id,name FROM properties WHERE name LIKE '%" . $search . "%' LIMIT 10";
            $result = $this->db->query($query)->getResultArray();
            $m_result = array();
            foreach ($result as $r) {
                array_push($m_result, array(
                    "key" => $r['property_id'],
                    "value" => $r['name']
                ));
            }
            $response['error'] = 0;
            $response['data'] = $m_result;
        }
        header("Content-Type:application/json");
        echo json_encode($response);
        exit();
    }

    public function ajaxfetchpropertyunits()
    {
        $property_id = $_GET['property_id'] ?? "";
        $order_id = $_GET['order_id'] ?? 0;
        $response = array();
        $response['error'] = 1;
        if (!empty($property_id)) {
            $query = "SELECT prop_unit_id,unit_name,price FROM property_unit WHERE property_id=$property_id AND status=0";
            if (!empty($order_id)) {
                $query = "SELECT prop_unit_id,unit_name,price FROM property_unit WHERE property_id=$property_id AND ( status=0 OR EXISTS (SELECT sale_item_id FROM sale_order_items WHERE order_id=$order_id AND related_id=property_unit.prop_unit_id))";
            }
            $result = $this->db->query($query)->getResultArray();
            $m_result = array();
            foreach ($result as $r) {
                $extra = array();
                array_push($extra, $r['price']);
                array_push($m_result, array(
                    "key" => $r['prop_unit_id'],
                    "value" => $r['unit_name'],
                    "extra" => $extra
                ));
            }
            $response['error'] = 0;
            $response['data'] = $m_result;
        } else {
            $response['reason'] = "No property units";
        }
        header("Content-Type:application/json");
        echo json_encode($response);
        exit();
    }

    //edit order

    public function SaleOrderEdit($order_id)
    {
        if (is_manufacturing_system()) {
            if ($this->request->getPost("code")) {
                $data['code'] = $this->request->getPost("code");
                $data['cust_id'] = $this->request->getPost("cust_id");
                $data['order_date'] = $this->request->getPost("order_date");
                $data['shippingaddr_id'] = $this->request->getPost("shippingaddr_id");
                $data['terms_condition'] = $this->request->getPost("terms_condition");
                $data['payment_terms'] = $this->request->getPost("payment_terms");
                $data['transport_req'] = $this->request->getPost("transport_req") ?? 0;
                $data['trans_charge'] = $this->request->getPost("trans_charge");
                $data['discount'] = $this->request->getPost("discount");
                $data['order_expiry'] = $this->request->getPost("order_expiry");
                //  return var_dump($_POST, 'hlo',$order_id);
                if ($this->OrdersModel->update_m_order($order_id, $data)) {
                    return $this->response->redirect(url_to("erp.sale.orders"));
                } else {
                    return $this->response->redirect(url_to("erp.sale.order.edit", $order_id));
                }
            }
            $data['order'] = $this->OrdersModel->get_m_order_by_id($order_id);
            if (empty($data['order'])) {
                $this->session->setFlashdata("op_error", "Order Not Found");
                redirect("erp/sale/orders");
            }
            $data['shippingaddr'] = $this->OrdersModel->get_m_shipping_addr($data['order']->cust_id);
            $data['order_items'] = $this->OrdersModel->get_m_order_items($order_id);
            $data['page'] = "sale/m_orderedit";
        } else {
            if ($_POST["code"]) {
                $updated = false;
                $data['code'] = $this->request->getPost("code");
                $data['cust_id'] = $this->request->getPost("cust_id");
                $data['order_date'] = $this->request->getPost("order_date");
                $data['terms_condition'] = $this->request->getPost("terms_condition");
                $data['payment_terms'] = $this->request->getPost("payment_terms");
                $data['discount'] = $this->request->getPost("discount");
                $data['order_expiry'] = $this->request->getPost("order_expiry");
                if ($this->OrdersModel->update_c_order($order_id, $data)) {
                    redirect("erp/sale/orders");
                } else {
                    redirect("erp/sale/orderedit/" . $order_id);
                }
            }
            $data['order'] = $this->OrdersModel->get_c_order_by_id($order_id);
            if (empty($data['order'])) {
                session()->setFlashdata("op_error", "Order Not Found");
                redirect("erp/sale/orders");
            }
            $data['order_items'] = $this->OrdersModel->get_c_order_items($order_id);
            $data['page'] = "sale/c_orderedit";
        }
        $data['order_id'] = $order_id;
        $data['menu'] = "sale";
        $data['submenu'] = "order";
        return view("erp/index", $data);
    }

    // View Order
    public function OrderView($order_id)
    {
        $user_email = session()->get('erp_email');
        $user_name = session()->get('erp_username');
        if (is_manufacturing_system()) {

            $data['order'] = $this->OrdersModel->get_m_order_for_view($order_id);
            if (empty($data['order'])) {
                $this->session->setFlashdata("op_error", "Order Not Found");
                redirect("erp/sale/orders");
            }
            $data['stock_pick'] = $this->OrdersModel->get_m_stock_pick($order_id);
            $data['order_items'] = $this->OrdersModel->get_m_order_items_for_view($order_id);
            if ($data['order']->stock_pick == 1) {
                $data['stock_entry'] = $this->OrdersModel->get_stock_pick_items($order_id);
            }

            $customerContact = $this->OrdersModel->getCustomerContactById($order_id);
            $customerMail = $this->OrdersModel->getCustomerMailById($order_id);
            if (!empty($customerContact)) {
                $data['customerContact'] = $customerContact;
            } else {
                $data['customerContact'] = $customerMail;
            }

            $data['page'] = "sale/m_orderview";
        } else {
            $data['order'] = $this->OrdersModel->get_c_order_for_view($order_id);
            if (empty($data['order'])) {
                $this->session->setFlashdata("op_error", "Order Not Found");
                redirect("erp/sale/orders");
            }
            $data['order_items'] = $this->OrdersModel->get_c_order_items_for_view($order_id);
            $data['page'] = "sale/c_orderview";
        }
        $data['notify_datatable_config'] = $this->OrdersModel->get_dtconfig_notify();
        $data['attachments'] = $this->OrdersModel->get_attachments("sale_order", $order_id);
        $data['order_status'] = $this->OrdersModel->get_order_status();
        $data['order_status_bg'] = $this->OrdersModel->get_order_status_bg();
        $data['dispatch_datatable_config'] = $this->DispatchModel->get_dtconfig_m_dispatch();

        $data['dispatch_status'] = $this->DispatchModel->get_dispatch_status();
        $data['dispatch_status_bg'] = $this->DispatchModel->get_dispatch_status_bg();

        $logger = \Config\Services::logger();
        $logger->error($data['dispatch_status']);
        $logger->error($data['dispatch_status_bg']);
        $data['order_id'] = $order_id;
        $data['user_email'] = $user_email;
        $data['user_name'] = $user_name;
        $data['menu'] = "sale";
        $data['submenu'] = "order";
        return view("erp/index", $data);
    }
    //delete

    public function orderdelete($order_id)
    {
        $OrdersModel = new OrdersModel();

        $order = $OrdersModel->get_order_by_id($order_id);

        if (empty($order)) {
            session()->setFlashdata("op_error", "Stock Not Found");
            return $this->response->redirect(url_to('erp.warehouse.managestock.delete'));
        }


        $deleted = $OrdersModel->delete_orders($order_id);

        if ($deleted) {

            session()->setFlashdata("op_success", "Stock successfully deleted");
        } else {

            session()->setFlashdata("op_error", "Stock failed to delete");
        }


        return $this->response->redirect(url_to('erp.warehouse.managestock'));
    }


    public function OrderExport()
    {
        $type = $_GET['export'];
        $config = array();
        $config['title'] = "Sale Order";
        if (is_manufacturing_system()) {
            if ($type == "pdf") {
                $config['column'] = array("Code", "Customer", "Order Date", "Order Expiry", "Status", "Transport Requested", "Transport Charge", "Discount", "Total Amount");
            } else {
                $config['column'] = array("Code", "Customer", "Shipping Address", "Order Date", "Order Expiry", "Status", "Transport Requested", "Transport Charge", "Discount", "Total Amount", "Payment Terms", "Terms Condition");
                $config['column_data_type'] = array(ExporterConst::E_STRING, ExporterConst::E_STRING, ExporterConst::E_STRING, ExporterConst::E_STRING, ExporterConst::E_STRING, ExporterConst::E_STRING, ExporterConst::E_STRING, ExporterConst::E_STRING, ExporterConst::E_STRING, ExporterConst::E_STRING, ExporterConst::E_STRING);
            }
            $config['data'] = $this->OrdersModel->get_m_order_export($type);
        } else {
            if ($type == "pdf") {
                $config['column'] = array("Code", "Customer", "Order Date", "Order Expiry", "Status", "Discount", "Total Amount");
            } else {
                $config['column'] = array("Code", "Customer", "Order Date", "Order Expiry", "Status", "Discount", "Total Amount", "Payment Terms", "Terms Condition");
                $config['column_data_type'] = array(ExporterConst::E_STRING, ExporterConst::E_STRING, ExporterConst::E_STRING, ExporterConst::E_STRING, ExporterConst::E_STRING, ExporterConst::E_STRING, ExporterConst::E_STRING, ExporterConst::E_STRING, ExporterConst::E_STRING);
            }
            $config['data'] = $this->OrdersModel->get_c_order_export($type);
        }
        if ($type == "pdf") {
            Exporter::export(ExporterConst::PDF, $config);
        } else if ($type == "excel") {
            Exporter::export(ExporterConst::EXCEL, $config);
        } else if ($type == "csv") {
            Exporter::export(ExporterConst::CSV, $config);
        }
    }


    public function OrderViewExport()
    {
        $type = $_GET['export'];
        $config = [];
        $config['title'] = "Sale Order";

        if (is_manufacturing_system()) {
            $config['column'] = ["Code", "Customer", "Shipping Address", "Order Date", "Order Expiry", "Status", "Transport Requested", "Transport Charge", "Discount", "Total Amount"];
        } else {
            $config['column'] = ["Code", "Customer", "Order Date", "Order Expiry", "Status", "Discount", "Total Amount", "Payment Terms", "Terms Condition"];
            $config['column_data_type'] = [ExporterConst::E_STRING, ExporterConst::E_STRING, ExporterConst::E_STRING, ExporterConst::E_STRING, ExporterConst::E_STRING, ExporterConst::E_STRING, ExporterConst::E_STRING, ExporterConst::E_STRING, ExporterConst::E_STRING];
        }

        if (is_manufacturing_system()) {
            $config['data'] = $this->OrdersModel->get_m_order_export($type);
        } else {
            $config['data'] = $this->OrdersModel->get_c_order_export($type);
        }

        if ($type == "pdf") {
            Exporter::export(ExporterConst::PDF, $config);
        }
    }



    public function upload_orderattachment()
    {
        $response = array();
        if (!empty($_FILES['attachment']['name'])) {
            $response = $this->OrdersModel->upload_attachment("sale_order");
        } else {
            $response['error'] = 1;
            $response['reason'] = "No file to upload";
        }
        header("Content-Type:application/json");
        echo json_encode($response);
        exit();
    }

    public function order_delete_attachment()
    {
        $response = array();
        $response = $this->OrdersModel->delete_attachment("sale_order");
        header("Content-Type:application/json");
        echo json_encode($response);
        exit();
    }

    //notify export

    public function order_notify_export()
    {
        $type = $_GET['export'];
        $config = array();
        $config['title'] = "Sale Order Notification";
        if ($type == "pdf") {
            $config['column'] = array("Title", "Description", "Notify At", "Notify To", "Notified");
        } else {
            $config['column'] = array("Title", "Description", "Notify At", "Notify To", "Notified");
            $config['column_data_type'] = array(ExporterConst::E_STRING, ExporterConst::E_STRING, ExporterConst::E_STRING, ExporterConst::E_STRING, ExporterConst::E_STRING);
        }
        $config['data'] = $this->OrdersModel->get_ordernotify_export($type);
        if ($type == "pdf") {
            Exporter::export(ExporterConst::PDF, $config);
        } else if ($type == "excel") {
            Exporter::export(ExporterConst::EXCEL, $config);
        } else if ($type == "csv") {
            Exporter::export(ExporterConst::CSV, $config);
        }
    }
    // ORDER notify

    public function ordernotify($order_id)
    {
        if ($_POST["notify_title"]) {
            $this->OrdersModel->insert_update_ordernotify($order_id);
        }
        return $this->response->redirect(url_to("erp.sale.orders.view", $order_id));
    }

    public function ajax_ordernotify_response()
    {
        $response = array();
        $response = $this->OrdersModel->get_ordernotify_datatable();
        header("Content-Type:application/json");
        echo json_encode($response);
        exit();
    }

    public function ordernotifydelete($order_id, $notify_id)
    {
        $this->OrdersModel->delete_order_notify($order_id, $notify_id);
        return $this->response->redirect(url_to("erp.sale.orders.view", $order_id));
    }

    //////////////////////////////////////////////////////////////////////////////////////////////////////

    //Dispatch Implemented Ta
    public function Dispatch()
    {
        $data['dispatch_status'] = $this->DispatchModel->get_dispatch_status();
        $data['dispatch_datatable_config'] = $this->DispatchModel->get_dtconfig_m_dispatch();
        $data['page'] = "sale/m_orderview";
        $data['menu'] = "sale";
        $data['submenu'] = "dispatch";
        return view("erp/index", $data);
    }

    public function ajax_order_dispatch_response()
    {
        $response = array();
        $limit = $this->request->getGet('limit');
        $offset = $this->request->getGet('offset');
        $search = $this->request->getGet('search') ?? "";
        // Handle 'orderby' possibly being null
        $orderby = $this->request->getGet('orderby') ?? "";
        $orderby = $orderby !== null ? strtoupper($orderby) : "";
        $ordercolPost = $this->request->getGet('ordercol');
        $status = $this->request->getGet('status');
        // var_dump($_GET);
        // exit();
        $response = $this->DispatchModel->get_Sale_Dispatch_Datatable($limit, $offset, $search, $orderby, $ordercolPost, $status);
        header("Content-Type:application/json");
        echo json_encode($response);
        exit();
    }

    //Dispatch ADD 

    public function DispatchAdd($order_id)
    {
        $logger = \Config\Services::logger();


        $dataPost = [
            'order_id' => $order_id,
            'order_code' => $this->request->getPost("order_code"),
            'customer' => $this->request->getPost("customer_name"),
            'delivery_date' => $this->request->getPost("delivery_date"),
            'description' => $this->request->getPost("dispatch_desc"),
            'status' => $this->request->getPost("dispatch_status"),
            'cust_id' => $this->request->getPost("cust_id"),
            'created_at' => time(),
            'updated_at' => time(),
        ];

        if ($this->request->getPost("order_code")) {
            if ($this->DispatchModel->insertdispatch($dataPost)) {
                return $this->response->redirect(url_to('erp.sale.orders.view', $order_id));
            } else {
                return $this->response->redirect(url_to('erp.sale.orders.view', $order_id));
            }
        } else {
            session()->setFlashdata("op_error", "There was an Error");
            return $this->response->redirect(url_to('erp.sale.orders.view', $order_id));
        }
    }

    //Dispatch Export

    public function DispatchOrderExport()
    {
        $type = $this->request->getGet('export');
        $limit = $this->request->getGet('limit');
        $offset = $this->request->getGet('offset');
        $search = $this->request->getGet('search') ?? "";
        // Handle 'orderby' possibly being null
        $orderby = $this->request->getGet('orderby') ?? "";
        $orderby = $orderby !== null ? strtoupper($orderby) : "";
        $ordercolPost = $this->request->getGet('ordercol');
        $status = $this->request->getGet('status');

        $config = [];

        $config['title'] = "dispatch";

        if ($type == "pdf") {
            $config['column'] = ["Order Code", "Customer", "Delivery Date", "description", "Status"];
        } else {
            $config['column'] = ["Order Code", "Customer", "Delivery Date", "Description", "Status"];
            $config['column_data_type'] = [ExporterConst::E_STRING, ExporterConst::E_STRING, ExporterConst::E_STRING, ExporterConst::E_STRING, ExporterConst::E_STRING, ExporterConst::E_STRING, ExporterConst::E_STRING, ExporterConst::E_STRING, ExporterConst::E_STRING, ExporterConst::E_STRING, ExporterConst::E_STRING];
        }

        $DispatchModel = new DispatchModel();
        $config['data'] = $this->DispatchModel->get_dispatch_order_export($type, $limit, $offset, $search, $orderby, $ordercolPost, $status);

        if ($type == "pdf") {
            return Exporter::export(ExporterConst::PDF, $config);
        } else if ($type == "excel") {
            return Exporter::export(ExporterConst::EXCEL, $config);
        } else if ($type == "csv") {
            return Exporter::export(ExporterConst::CSV, $config);
        }
    }

    public function DeleteSaleDispatch($dispatch_id)
    {

        $dispatch = $this->DispatchModel->get_dispatch_by_id($dispatch_id);

        if (empty($dispatch)) {
            session()->setFlashdata("op_error", "Dispatch Not Found");
            return redirect()->to('erp/asset/equipments');
        }

        // Attempt to delete the Dispatch
        $deleted = $this->DispatchModel->Delete_Sale_Dispatch($dispatch_id);

        if ($deleted) {
            // Dispatch successfully deleted
            session()->setFlashdata("op_success", "Dispatch successfully deleted");
        } else {
            // Failed to delete Dispatch
            session()->setFlashdata("op_error", "Dispatch failed to delete");
        }

        // Redirect to the Dispatch list page
        return $this->response->redirect(url_to('erp.sale.orders'));
    }





    /////////////////////////////////////////////////////////////////////////////////////////////////////////

    //Invoice sale
    public function Invoices()
    {
        if (is_manufacturing_system()) {
            $data['invoice_datatable_config'] = $this->InvoiceModel->get_dtconfig_m_invoices();
            $data['page'] = "sale/m_invoices";
        } else {
            $data['invoice_datatable_config'] = $this->InvoiceModel->get_dtconfig_c_invoices();
            $data['page'] = "sale/c_invoices";
        }
        $data['invoice_status'] = $this->InvoiceModel->get_invoice_status();
        $data['menu'] = "sale";
        $data['submenu'] = "invoice";
        return view("erp/index", $data);
    }

    public function AjaxInvoiceResponse()
    {
        $response = array();
        $response = $this->InvoiceModel->get_c_invoice_datatable();
        header("Content-Type:application/json");
        echo json_encode($response);
        exit();
    }

    //invoice view
    public function InvoiceView($invoice_id)
    {
        $user_email = session()->get('erp_email');
        $user_name = session()->get('erp_username');
        if (is_manufacturing_system()) {
            $type = $this->InvoiceModel->get_invoicetype_by_id($invoice_id);
            if ($type["type"] == 3) {
                $data['invoice'] = $this->InvoiceModel->get_e_invoice_for_view($invoice_id);
            } else {
                $data['invoice'] = $this->InvoiceModel->get_m_invoice_for_view($invoice_id);
            }
            $data['applied_credits'] = $this->CreditNotesModel->getAppliedCreditsByInvoiceId($invoice_id);

            // return var_dump($data['invoice']);
            if (empty($data['invoice'])) {
                $this->session->setFlashdata("op_error", "Invoice Not Found");
                redirect("erp/sale/invoices");
            }

            $customerContact = $this->InvoiceModel->getCustomerContactById($invoice_id);
            $customerMail = $this->InvoiceModel->getCustomerMailById($invoice_id);
            if (!empty($customerContact)) {
                $data['customerContact'] = $customerContact;
            } else {
                $data['customerContact'] = $customerMail;
            }
            // echo '<pre>';
            // return var_dump($data);
            $data["isexpense"] = false;
            if ($type["type"] == 3) {
                $data["isexpense"] = true;
                $data['invoice_items'] = $this->InvoiceModel->get_e_invoice_items_for_view($invoice_id);
            } else {
                $data['invoice_items'] = $this->InvoiceModel->get_m_invoice_items_for_view($invoice_id);
            }
            $data['page'] = "sale/m_invoiceview";
        } else {
            $data['invoice'] = $this->InvoiceModel->get_c_invoice_for_view($invoice_id);
            if (empty($data['invoice'])) {
                $this->session->setFlashdata("op_error", "Invoice Not Found");
                redirect("erp/sale/invoices");
            }
            $data['invoice_items'] = $this->InvoiceModel->get_c_invoice_items_for_view($invoice_id);
            $data['page'] = "sale/c_invoiceview";
        }
        $data['paymentmodes'] = $this->InvoiceModel->get_all_paymentmodes();
        $data['payment_datatable_config'] = $this->InvoiceModel->get_dtconfig_payments();
        $data['notify_datatable_config'] = $this->InvoiceModel->get_dtconfig_notify();
        $data['attachments'] = $this->InvoiceModel->get_attachments("sale_invoice", $invoice_id);
        $data['invoice_status'] = $this->InvoiceModel->get_invoice_status();
        $data['invoice_status_bg'] = $this->InvoiceModel->get_invoice_status_bg();
        $data['invoice']->status = $this->InvoiceModel->getInvoiceStatus($data['invoice'], $data['applied_credits']);
        $this->InvoiceModel->updateInvoiceStatus($invoice_id, $data['invoice']->status);
        // return var_dump($data['invoice']->status);
        $data['invoice_id'] = $invoice_id;
        $data['user_email'] = $user_email;
        $data['user_name'] = $user_name;

        $logger = \Config\services::logger();

        $logger->error($data['customerContact']);

        $data['email_template'] = $this->get_send_invoice_template($invoice_id, $data['customerContact'][0]['cust_id'], "array");

        $data['menu'] = "sale";
        $data['submenu'] = "invoice";
        return view("erp/index", $data);
    }


    /**
     * function that prepares the email template for invoice
     * 
     * @auther Ashok kumar
     * 
     * @param int $invoice_id
     * @param int $contact_id
     * @param string $return_type
     * @param mixed $invoice_info
     * @param mixed $contact_info
     * @return string | array
     */
    function get_send_invoice_template($invoice_id = 0, $contact_id = 0, $return_type = "", $invoice_info = "", $contact_info = "")
    {
        if (!$invoice_info) {
            $options = array("invoice_id" => $invoice_id);
            $invoice_info = $this->Invoices_model->where($options)->asObject()->first();
        }

        if (!$contact_info) {
            $contact_info = $this->Users_model->getCustomerContactInfo($contact_id);
        }

        $email_template = $this->Email_templates_model->get_final_template("send_invoice", true);

        $invoice_total_summary = $this->Invoices_model->get_invoice_total_summary($invoice_id);

        $logger = \Config\services::logger();


        $parser_data["INVOICE_FULL_ID"] = $invoice_info->code;
        $parser_data["INVOICE_ID"] = $invoice_info->invoice_id;
        $parser_data["BALANCE_DUE"] = $invoice_total_summary->amount_due;
        // $parser_data["DUE_DATE"] = format_to_date($invoice_info->invoice_expiry, false);
        $parser_data["DUE_DATE"] = $invoice_info->invoice_expiry;
        $parser_data["INVOICE_URL"] = base_url();

        $logger->error($contact_info);


        if ($contact_info[0]) {
            $parser_data["CONTACT_FIRST_NAME"] = $contact_info[0]->firstname;
            $parser_data["CONTACT_LAST_NAME"] = $contact_info[0]->lastname;
            $parser_data["PROJECT_TITLE"] = $invoice_info->project_title ?? '';
            $parser_data["SIGNATURE"] = get_array_value($email_template, "signature_default");
            $parser_data["LOGO_URL"] = base_url();
            $parser_data["RECIPIENTS_EMAIL_ADDRESS"] = $contact_info[0]->email;
        } else {
            $parser_data["CONTACT_FIRST_NAME"] = $contact_info[1]->name;
            $parser_data["CONTACT_LAST_NAME"] = $contact_info[1]->company;
            $parser_data["PROJECT_TITLE"] = $invoice_info->project_title ?? '';
            $parser_data["SIGNATURE"] = get_array_value($email_template, "signature_default");
            $parser_data["LOGO_URL"] = base_url();
            $parser_data["RECIPIENTS_EMAIL_ADDRESS"] = $contact_info[1]->email;
        }


        $parser = \Config\Services::parser();

        $message = get_array_value($email_template, "message_default");
        $subject = get_array_value($email_template, "subject_default");

        // CODE REFERENCE FROM RISE CRM -> WILL BE IMPLEMENTED LATER
        //add public pay invoice url 

        // if (get_setting("client_can_pay_invoice_without_login") && strpos($message, "PUBLIC_PAY_INVOICE_URL")) {
        //     $verification_data = array(
        //         "type" => "invoice_payment",
        //         "code" => make_random_string(),
        //         "params" => serialize(array(
        //             "invoice_id" => $invoice_id,
        //             "client_id" => $contact_info->client_id,
        //             "contact_id" => $contact_info->id
        //         ))
        //     );

        //     $save_id = $this->Verification_model->ci_save($verification_data);

        //     $verification_info = $this->Verification_model->get_one($save_id);

        //     $parser_data["PUBLIC_PAY_INVOICE_URL"] = get_uri("pay_invoice/index/" . $verification_info->code);
        // }

        $message = $parser->setData($parser_data)->renderString($message);
        $subject = $parser->setData($parser_data)->renderString($subject);
        $message = htmlspecialchars_decode($message);
        $subject = htmlspecialchars_decode($subject);


        $logger->error($message);
        $logger->error($subject);

        if ($return_type == "json") {
            echo json_encode(array("success" => true, "message_view" => $message));
        } else {
            return array(
                "message" => $message,
                "subject" => $subject,
            );
        }
    }

    public function AjaxInvoiceResponseManufature()
    {
        $response = array();
        $response = $this->InvoiceModel->get_m_invoice_datatable();
        header("Content-Type:application/json");
        echo json_encode($response);
        exit();
    }



    //Export

    public function invoice_export()
    {
        $type = $_GET['export'];
        $config = array();
        $config['title'] = "Sale Invoice";
        if (is_manufacturing_system()) {
            if ($type == "pdf") {
                $config['column'] = array("Code", "Customer", "Invoice Date", "Invoice Expiry", "Status", "Transport Requested", "Transport Charge", "Discount", "Total Amount");
            } else {
                $config['column'] = array("Code", "Customer", "Shipping Address", "Invoice Date", "Invoice Expiry", "Status", "Transport Requested", "Transport Charge", "Discount", "Total Amount", "Payment Terms", "Terms Condition");
                $config['column_data_type'] = array(ExporterConst::E_STRING, ExporterConst::E_STRING, ExporterConst::E_STRING, ExporterConst::E_STRING, ExporterConst::E_STRING, ExporterConst::E_STRING, ExporterConst::E_STRING, ExporterConst::E_STRING, ExporterConst::E_STRING, ExporterConst::E_STRING, ExporterConst::E_STRING);
            }
            $config['data'] = $this->InvoiceModel->get_m_invoice_export($type);
        } else {
            if ($type == "pdf") {
                $config['column'] = array("Code", "Customer", "Invoice Date", "Invoice Expiry", "Status", "Discount", "Total Amount");
            } else {
                $config['column'] = array("Code", "Customer", "Invoice Date", "Invoice Expiry", "Status", "Discount", "Total Amount", "Payment Terms", "Terms Condition");
                $config['column_data_type'] = array(ExporterConst::E_STRING, ExporterConst::E_STRING, ExporterConst::E_STRING, ExporterConst::E_STRING, ExporterConst::E_STRING, ExporterConst::E_STRING, ExporterConst::E_STRING, ExporterConst::E_STRING, ExporterConst::E_STRING);
            }
            $config['data'] = $this->InvoiceModel->get_c_invoice_export($type);
        }
        if ($type == "pdf") {
            Exporter::export(ExporterConst::PDF, $config);
        } else if ($type == "excel") {
            Exporter::export(ExporterConst::EXCEL, $config);
        } else if ($type == "csv") {
            Exporter::export(ExporterConst::CSV, $config);
        }
    }


    //invoiceadd
    public function invoiceadd()
    {
       

        $data['code'] = $this->request->getpost("code");
        $data['cust_id'] = $this->request->getpost("cust_id");
        $data['invoice_date'] = $this->request->getpost("invoice_date");
        $data['shippingaddr_id'] = $this->request->getpost("shippingaddr_id");
        $data['terms_condition'] = $this->request->getpost("terms_condition");
        $data['currency_id'] = $this->request->getPost("currency_id");
        $data['currency_place'] = $this->request->getPost("currency_place");
        $data['payment_terms'] = $this->request->getpost("payment_terms");
        $data['transport_req'] = $this->request->getpost("transport_req") ?? 0;
        $data['trans_charge'] = $this->request->getpost("trans_charge");
        $data['discount'] = $this->request->getpost("discount");
        $data['invoice_expiry'] = $this->request->getpost("invoice_expiry");
        $data['status'] = $this->request->getpost("status");
        $data['created_by'] = get_user_id();
        $data['product_ids'] = $this->request->getpost("product_id");
        $data['quantities'] = $this->request->getpost("quantity");
        $data['price_ids'] = $this->request->getpost("price_id");
        if (is_manufacturing_system()) {
            if ($this->request->getpost("code")) {
                // var_dump($this->request->getpost());
                // exit;
                // return var_dump($_POST);
                if ($this->InvoiceModel->insert_m_invoice($data)) {
                    return $this->response->redirect(url_to('erp.sale.invoice'));
                } else {
                    return $this->response->redirect(url_to('erp.sale.invoice.add'));
                }
            }
            $data['maxCode'] = $this->InvoiceModel->getMaximumInvoiceNumber();
            $data['currency_place'] = $this->CurrenciesModel->getCurrencyPlace();
            $data['page'] = "sale/m_invoiceadd";
        } else {
            if ($this->request->getpost("code")) {
                
                if ($this->InvoiceModel->insert_c_invoice($data)) {
                    return $this->response->redirect(url_to('erp.sale.invoice'));
                } else {
                    return $this->response->redirect(url_to('erp.sale.invoice.add'));
                }
            }
            $data['page'] = "sale/c_invoiceadd";
        }
        $data['invoice_status'] = $this->InvoiceModel->get_invoice_status();
        $data['menu'] = "sale";
        $data['submenu'] = "invoice";
        return view("erp/index", $data);
    }


    public function invoiceedit($invoice_id)
    {
        if (is_manufacturing_system()) {
            if ($this->request->getPost("code")) {
                $data['code'] = $this->request->getPost("code");
                $data['cust_id'] = $this->request->getPost("cust_id");
                $data['invoice_date'] = $this->request->getPost("invoice_date");
                $data['shippingaddr_id'] = $this->request->getPost("shippingaddr_id");
                $data['billingaddr_id'] = $this->request->getPost("billingaddr_id");
                $data['currency_id'] = $this->request->getPost("currency_id");
                $data['currency_place'] = $this->request->getPost("currency_place");
                $data['terms_condition'] = $this->request->getPost("terms_condition");
                $data['payment_terms'] = $this->request->getPost("payment_terms");
                $data['transport_req'] = $this->request->getPost("transport_req") ?? 0;
                $data['trans_charge'] = $this->request->getPost("trans_charge");
                $data['discount'] = $this->request->getPost("discount");
                $data['invoice_expiry'] = $this->request->getPost("invoice_expiry");
                $data['product_ids'] = $this->request->getpost("product_id");
                $data['quantities'] = $this->request->getpost("quantity");
                $data['price_ids'] = $this->request->getpost("price_id");

                $logger = \Config\Services::logger();

                $logger->error($data);

                if ($this->InvoiceModel->update_m_invoice($invoice_id, $data)) {
                    return $this->response->redirect(url_to("erp.sale.invoice"));
                } else {
                    return $this->response->redirect(url_to("erp.sale.invoice", $invoice_id));
                }
            }

            $data['invoice'] = $this->InvoiceModel->get_m_invoice_by_id($invoice_id);


            if (empty($data['invoice'])) {
                $this->session->setFlashdata("op_error", "Invoice Not Found");
                return $this->response->redirect(url_to("erp.sale.invoice"));
            }
            $data['shippingaddr'] = $this->InvoiceModel->get_m_shipping_addr($data['invoice']->cust_id);
            $data['invoice_items'] = $this->InvoiceModel->get_m_invoice_items($invoice_id);
            $data['currency_place'] = $this->CurrenciesModel->getCurrencyPlace();
            $data['selected_currency_place'] = $this->InvoiceModel->getSelectedCurrencyPlace($invoice_id);
            $data['selected_currency_name'] = $this->InvoiceModel->getSelectedCurrencyName($invoice_id, $data['selected_currency_place']);
            $data['page'] = "sale/m_invoiceedit";
        } else {
            if ($this->request->getPost("code")) {
                $data['code'] = $this->request->getPost("code");
                $data['cust_id'] = $this->request->getPost("cust_id");
                $data['invoice_date'] = $this->request->getPost("invoice_date");
                $data['terms_condition'] = $this->request->getPost("terms_condition");
                $data['payment_terms'] = $this->request->getPost("payment_terms");
                $data['discount'] = $this->request->getPost("discount");
                $data['invoice_expiry'] = $this->request->getPost("invoice_expiry");
                $data['property_ids'] = $this->request->getPost("property_id");
                $data['unit_ids'] = $this->request->getPost("unit_id");

                if ($this->InvoiceModel->update_c_invoice($invoice_id, $data)) {
                    return $this->response->redirect(url_to("erp.sale.invoice"));
                } else {
                    return $this->response->redirect(url_to("erp.sale.invoice.edit.post" . $invoice_id));
                }
            }
            $data['invoice'] = $this->InvoiceModel->get_c_invoice_by_id($invoice_id);
            if (empty($data['invoice'])) {
                session()->setFlashdata("op_error", "Invoice Not Found");
                redirect("erp/sale/invoices");
            }
            $data['invoice_items'] = $this->InvoiceModel->get_c_invoice_items($invoice_id);
            $data['page'] = "sale/c_invoiceedit";
        }
        $data['invoice_id'] = $invoice_id;
        $data['menu'] = "sale";
        $data['submenu'] = "invoice";
        return view("erp/index", $data);
    }

    public function ajaxfetchinvoicepropertyunits()
    {
        $property_id = $_GET['property_id'] ?? "";
        $invoice_id = $_GET['invoice_id'] ?? 0;
        $response = array();
        $response['error'] = 1;
        if (!empty($property_id)) {
            $query = "SELECT prop_unit_id,unit_name,price FROM property_unit WHERE property_id=$property_id AND status=0";
            if (!empty($invoice_id)) {
                $query = "SELECT prop_unit_id,unit_name,price FROM property_unit WHERE property_id=$property_id AND ( status=0 OR EXISTS (SELECT sale_item_id FROM sale_order_items WHERE invoice_id=$invoice_id AND related_id=property_unit.prop_unit_id))";
            }
            $result = $this->db->query($query)->getResultArray();
            $m_result = array();
            foreach ($result as $r) {
                $extra = array();
                array_push($extra, $r['price']);
                array_push($m_result, array(
                    "key" => $r['prop_unit_id'],
                    "value" => $r['unit_name'],
                    "extra" => $extra
                ));
            }
            $response['error'] = 0;
            $response['data'] = $m_result;
        } else {
            $response['reason'] = "No property units";
        }
        header("Content-Type:application/json");
        echo json_encode($response);
        exit();
    }

    //invoice delete


    public function invoicedelete($invoice_id)
    {
        $InvoiceModel = new InvoiceModel();

        $invoice = $InvoiceModel->get_m_invoice_by_id($invoice_id);

        if (empty($invoice)) {
            session()->setFlashdata("op_error", "Invoice Not Found");
            return $this->response->redirect(url_to('erp.sale.invoice'));
        }

        $deleted = $InvoiceModel->delete_invoice($invoice_id);

        if ($deleted) {

            session()->setFlashdata("op_success", "Invoice successfully deleted");
        } else {

            session()->setFlashdata("op_error", "Invoice failed to delete");
        }

        return $this->response->redirect(url_to('erp.sale.invoice'));
    }

    public function createinvoice($order_id, $data)
    {
        $data['created'] = false;
        $data['invoice_date'] = $this->request->getpost("invoice_date");
        $data['invoice_expiry'] = $this->request->getpost("invoice_expiry");
        $data['code'] = $this->request->getpost("code");

        if (is_manufacturing_system()) {
            if ($this->InvoiceModel->create_m_invoice($order_id, $data)) {
                return $this->response->redirect(url_to('erp.sale.invoice'));
            } else {
                redirect("erp/sale/orderview/" . $order_id);
            }
        } else {
            $data['created'] = false;
            $data['invoice_date'] = $this->request->getpost("invoice_date");
            $data['invoice_expiry'] = $this->request->getpost("invoice_expiry");
            $data['code'] = $this->request->getpost("code");
            if ($this->InvoiceModel->create_c_invoice($order_id, $data)) {
                return $this->response->redirect(url_to('erp.sale.invoice'));
            } else {
                redirect("erp/sale/orderview/" . $order_id);
            }
        }
    }

    public function invoicepdf($invoice_id, $type)
    {
        $invoice_number = $this->InvoiceModel->getInvoiceNumberById($invoice_id);
        $invoice = $this->InvoiceModel->getInvoiceById($invoice_id);
        $bill_to = $this->InvoiceModel->getBillingAddressByInvocieId($invoice_id);
        $ship_to = $this->InvoiceModel->getShippingAddressByInvocieId($invoice_id);
        $organisation_data = $this->ErpCompanyInformationModel->getAlldata();
        $invoice_items = $this->InvoiceModel->getInvoiceItemsById($invoice_id);
        $payment_data = $this->InvoiceModel->getPaymentDataById($invoice_id);
        $applied_credits = $this->CreditNotesModel->getAppliedCredits($invoice_id);
        $total_paid = $this->InvoiceModel->getPaymentAmount($invoice_id);
        $currency_place = $this->InvoiceModel->getCurrencyPlaceById($invoice_id);
        $currency_symbol = $this->InvoiceModel->getCurrencySymbolbyId($invoice_id);
        // return var_dump($currency_place);

        $pdf = new TCPDF();
        $pdf->SetTitle($invoice_number);
        $pdf->setPrintHeader(false);
        $pdf->AddPage();

        // Logo on the left
        $logo = '<div><img src="' . base_url() . 'assets/images/' . $organisation_data['company_logo'] . '" style="width:150px;height:50px;" ></div>';

        // Invoice information on the right
        $invoiceInfo = '<div style="text-align: right;">';
        $invoiceInfo .= '<span style="font-weight:bold;font-size:15px;">INVOICE</span><br />';
        $invoiceInfo .= '<b style="color:#4e4e4e;">#' . $invoice_number . '</b><br />';

        // Paid/Unpaid logo
        if (!empty($invoice['status']) && $invoice['status'] == "2") {
            $invoiceInfo .= '<img src="' . base_url('assets/images/in1.png') . '" style="width:65px" />';
        } else {
            $invoiceInfo .= '<img src="' . base_url('assets/images/in.png') . '" style="width:65px" />';
        }

        //Invoice date & Due date
        $datetime = strtotime($invoice['invoice_date']);
        $format = date("d F Y", $datetime);
        $invoiceInfo .= '<div style = "font-family:Times New Roman;">Invocie Date: ' . $format . '<br />';
        $datetime = strtotime($invoice['invoice_expiry']);
        $format = date("d F Y", $datetime);
        $invoiceInfo .= 'Invoice Due Date: ' . $format . '</div></div>';

        $table = '<table width="100%" border="0"><tr>';
        $table .= '<td width="50%">' . $logo . '</td>';
        $table .= '<td width="50%">' . $invoiceInfo . '</td>';
        $table .= '</tr></table>';
        $pdf->writeHTML($table, true, false, false, false, '');
        $pdf->ln(5);



        //Bill to
        if (!empty($bill_to)) {
            $billTo = '<div style = "text-align:left; font-family:Times New Roman;"><b>Bill To :</b>';
            $billTo .= '<div style="color:#2b2929;">' .
                $bill_to['customer_name'] . '<br/>' .
                $bill_to['address'] . '<br/>' .
                $bill_to['city'] . ', ' . $bill_to['state'] . '<br/>' .
                $bill_to['country'] . ' - ' . $bill_to['zipcode'] . '</div></div>';
        }

        //Ship to
        if (!empty($bill_to)) {
            $shipTo = '<div style = "text-align:left; font-family:Times New Roman;"><b>Ship To :</b>';
            $shipTo .= '<div style="color:#2b2929;">' .
                $ship_to['customer_name'] . '<br/>' .
                $ship_to['address'] . '<br/>' .
                $ship_to['city'] . ', ' . $ship_to['state'] . '<br/>' .
                $ship_to['country'] . ' - ' . $ship_to['zipcode'] . '</div></div>';
        }

        //Organization Information
        $organizationInfo = '<div style = "text-align:right;font-family:Times New Roman;"><b>' . $organisation_data['company_name'] . '</b>
                                <div style="color:#2b2929;">
                                    ' . $organisation_data['address'] . ',<br/>
                                    ' . $organisation_data['city'] . ',<br/>
                                    ' . $organisation_data['state'] . ',<br/>
                                    ' . $organisation_data['country'] . ',<br/>
                                    ' . $organisation_data['zipcode'] . '.<br/>
                        Mobile No.: ' . $organisation_data['phone_number'] . '<br/>
                        VAT Number: ' . $organisation_data['vat_number'] . '<br/>
                            License ' . $organisation_data['license_number'] . '<br/>
                            </div></div>';

        $table = '<table width="100%" border="0"><tr>';
        $table .= '<td width="50%">' . $billTo . $shipTo . '</td>';
        $table .= '<td width="50%">' . $organizationInfo . '</td>';
        $table .= '</tr></table>';

        $pdf->writeHTML($table, true, false, false, false, '');
        $pdf->ln(5);

        // Invoice Items Table and Summary
        if (!empty($invoice_items)) {
            $tableHTML = '<table border="1" cellspacing="0" cellpadding="5">
                <tr style="text-align:center; background-color:#063970; color: white; font-family:Times New Roman;">
                    <th>S.No</th>
                    <th>Product</th>
                    <th>Qty</th>
                    <th style="font-family:Times New Roman; font-size:11px;">Unit Price</th>
                    <th style="font-family:Times New Roman; font-size:11px;">VAT</th>
                    <th style="font-family:Times New Roman; font-size:11px;">Amount</th>
                </tr>';
            $tableHTML .= '<tbody>';

            $s_no = 0;
            $sub_total = 0;
            $total_tax = 0;
            $total_amount_with_tax = 0;
            foreach ($invoice_items as $row) {
                ++$s_no;
                $tableHTML .= '<tr style = "text-align:center;">
                    <td style="font-family:Times New Roman; font-size:11px;">' . $s_no . '</td>
                    <td style="font-family:Times New Roman; font-size:11px;">' . $row['product_name'] . '</td>
                    <td style="font-family:Times New Roman; font-size:11px;">' . $row['quantity'] . '</td>
                    <td style="font-family:Times New Roman; font-size:11px;">' . number_format($row['unit_price'], 2, '.', ',') . '</td>
                    <td style="font-family:Times New Roman; font-size:11px;">' . $row['tax1_rate'] . '-' . $row['tax1_amount'] . '%,<br/>' . $row['tax2_rate'] . '-' . $row['tax2_amount'] . '%' . '</td>
                    <td style="font-family:Times New Roman; font-size:11px;">' . number_format($row['amount'], 2, '.', ',') . '</td>
                </tr>';
                $sub_total += $row['amount'];
                $total_tax += $row['total_tax'];
                $total_amount_with_tax += $row['amount'] + ($row['amount'] * $row['total_tax']);
                $fianl_amount = $total_amount_with_tax + ($row['trans_charge'] ?? 0) - ($row['discount'] ?? 0);
            }

            //Set font For all currencies symbol accept
            $pdf->SetFont('freeserif', '', 12);

            //Handling With Null Values
            $payment_amount = isset($total_paid['payment_amount']) ? (float) $total_paid['payment_amount'] : 0;
            $applied_amount = isset($applied_credits['applied_amount']) ? (float) $applied_credits['applied_amount'] : 0;
            $due_amount = $fianl_amount - ($payment_amount + $applied_amount);
            // return var_dump($fianl_amount);

            //Currency Position 
            if ($currency_place['currency_place'] === 'after') {

                $subTotal = number_format($sub_total, 2, '.', ',') . $currency_symbol['symbol'];
                $finalAmount = number_format($fianl_amount, 2, '.', ',') . $currency_symbol['symbol'];
                $paid_amount = number_format($total_paid['payment_amount'], 2, '.', ',') . $currency_symbol['symbol'];
                $applied_credits = number_format($applied_credits['applied_amount'], 2, '.', ',') . $currency_symbol['symbol'];
                $due_amount = number_format($due_amount, 2, '.', ',') . $currency_symbol['symbol'];
            } elseif ($currency_place['currency_place'] === 'before') {

                $subTotal = $currency_symbol['symbol'] . number_format($sub_total, 2, '.', ',');
                $finalAmount = $currency_symbol['symbol'] . number_format($fianl_amount, 2, '.', ',');
                $paid_amount = $currency_symbol['symbol'] . number_format($total_paid['payment_amount'], 2, '.', ',');
                $applied_credits = $currency_symbol['symbol'] . number_format($applied_credits['applied_amount'], 2, '.', ',');
                $due_amount = $currency_symbol['symbol'] . number_format($due_amount, 2, '.', ',');
            }

            // Summary Section
            $tableHTML .= '<tr>
                    <td colspan="5" style="text-align:right; font-family:Times New Roman; font-size:11px;background-color:#f0f0f0;"><b>Sub Total</b></td>
                    <td style="text-align:center; font-size:11px;background-color:#f0f0f0;">' . $subTotal . '</td>
                </tr>';
            // $tableHTML .= '<tr>
            //     <td colspan="5" style="text-align:right; font-family:Times New Roman; font-size:11px;background-color:#f0f0f0;"><b>VAT</b></td>
            //     <td style="text-align:center; font-family:Times New Roman; font-size:11px;background-color:#f0f0f0;">' . $total_tax . '</td>
            // </tr>';
            $tableHTML .= '<tr>
                <td colspan="5" style="text-align:right; font-family:Times New Roman; font-size:11px;background-color:#f0f0f0;"><b>Total Inc. VAT</b></td>
                <td style="text-align:center;font-size:11px;background-color:#f0f0f0;">' . $finalAmount . '</td>
            </tr>';

            // Check if Total Paid is available
            if (isset($total_paid['payment_amount']) && !empty($total_paid['payment_amount'])) {
                $tableHTML .= '<tr>
                <td colspan="5" style="text-align:right; font-family:Times New Roman; font-size:11px;background-color:#f0f0f0;"><b>Total Paid</b></td>
                <td style="text-align:center; font-size:11px;background-color:#f0f0f0;">' . $paid_amount . '</td>
            </tr>';
            }

            // Check if Applied Credits is available
            if (isset($applied_credits['applied_amount']) && !empty($applied_credits['applied_amount'])) {
                $tableHTML .= '<tr>
                <td colspan="5" style="text-align:right; font-family:Times New Roman; font-size:11px;background-color:#f0f0f0;"><b>Applied Credits</b></td>
                <td style="text-align:center; font-size:11px;background-color:#f0f0f0;">' . $applied_credits . '</td>
            </tr>';
            }

            $tableHTML .= '<tr>
                <td colspan="5" style="text-align:right; font-family:Times New Roman; font-size:11px;background-color:#f0f0f0;"><b>Amount Due</b></td>
                <td style="text-align:center; font-size:11px;background-color:#f0f0f0;">' . $due_amount . '</td>
            </tr>';

            $tableHTML .= '</tbody></table>';
            $pdf->writeHTML($tableHTML, true, false, false, false, '');
        }
        $pdf->ln(5);


        //Transaction table
        if (!empty($payment_data)) {
            $transaction_table = '<h4 style="font-family:Times New Roman;">Transactions :</h4><br/><br/><table border="0" cellspacing="0" cellpadding="3">
                <thead>
                    <tr style="background-color:#063970; color: white; font-family:Times New Roman;">
                        <th>Payment #</th>
                        <th>Payment Mode</th>
                        <th>Date</th>
                        <th>Amount</th>
                    </tr>
                </thead>
                <tbody>';

            foreach ($payment_data as $row) {

                $formatted_payment_amount = '';
                if ($currency_place['currency_place'] === 'after') {
                    $formatted_payment_amount = number_format($row['payment_amount'], 2, '.', ',') . $currency_symbol['symbol'];
                } elseif ($currency_place['currency_place'] === 'before') {
                    $formatted_payment_amount = $currency_symbol['symbol'] . number_format($row['payment_amount'], 2, '.', ',');
                }

                $transaction_table .= '<tr style="border-bottom: 0.5px solid #000;">
                <td>' . $row['transaction_id'] . '</td>
                <td>' . $row['name'] . '</td>
                <td>' . $row['paid_on'] . '</td>
                <td>' . $formatted_payment_amount . '</td> 
            </tr>';
            }

            $transaction_table .= '</tbody></table><hr/>';
            $pdf->writeHTML($transaction_table, true, false, false, false, '');
        }

        //Bank Account Details
        $pdf->AddPage();
        $bank_details = '<b style="text-align:center;font-size:12px;font-family:Times New Roman;"><u style="line-height:1.8;">BANK ACCOUNT DETAILS</u></b><br /><br />
        <table style="font-size:11px;">
        <tr style="font-family:Times New Roman;"><td><b>Account No</b></td><td colspan="3">: 90876-749-561-2401</td></tr>
        <tr style="font-family:Times New Roman;"><td><b>Bank Name</b></td><td colspan="3">: XXXX, TN</td></tr>
        <tr style="font-family:Times New Roman;"><td><b>Account Name</b></td><td colspan="3">: Q BRAINSTORM SOFTWARE</td></tr>
        <tr style="font-family:Times New Roman;"><td><b>IBAN Number</b></td><td colspan="3">: 1234 567 890</td></tr>
        <tr style="font-family:Times New Roman;"><td><b>SWIFT Code</b></td><td colspan="3">: ZZZ</td></tr>
        </table>';
        $pdf->writeHTML($bank_details, true, false, true, false, '');

        $para = '<center>' . '<p style="text-align:center;font-family:Times New Roman;">This is computer generated invoice no signature required</p>' . '</center>';
        $pdf->writeHTML($para, true, false, false, false, '');

        $pdfFilePath = FCPATH . 'uploads/saleinvoice/' . $invoice_id . '.pdf';

        if ($type == 'view') {
            $pdf->Output('invoice.pdf', 'I');
        } elseif ($type == 'download') {
            $pdf->Output($pdfFilePath, 'F');
            header('Content-Type: application/pdf');
            header('Content-Disposition: attachment; filename="invoice.pdf"');
            readfile($pdfFilePath);
        }
        exit();
    }


    //Attachments invoice
    public function upload_invoiceattachment()
    {
        $response = array();
        if (!empty($_FILES['attachment']['name'])) {
            $response = $this->InvoiceModel->upload_attachment("sale_invoice");
        } else {
            $response['error'] = 1;
            $response['reason'] = "No file to upload";
        }
        header("Content-Type:application/json");
        echo json_encode($response);
        exit();
    }

    public function invoice_delete_attachment()
    {
        $response = array();
        $response = $this->InvoiceModel->delete_attachment("sale_invoice");
        header("Content-Type:application/json");
        echo json_encode($response);
        exit();
    }

    public function invoicenotify($invoice_id)
    {
        if ($_POST["notify_title"]) {
            $this->InvoiceModel->insert_update_invoicenotify($invoice_id);
        }
        return $this->response->redirect(url_to('erp.sale.invoice.view', $invoice_id));
    }

    public function ajax_invoicenotify_response()
    {
        $response = array();
        $response = $this->InvoiceModel->get_invoicenotify_datatable();
        header("Content-Type:application/json");
        echo json_encode($response);
        exit();
    }

    public function invoicenotifydelete($invoice_id, $notify_id)
    {
        $this->InvoiceModel->delete_invoice_notify($invoice_id, $notify_id);
        return $this->response->redirect(url_to('erp.sale.invoice.view', $invoice_id));
    }


    public function invoice_notify_export()
    {
        $type = $_GET['export'];
        $config = array();
        $config['title'] = "Sale Invoice Notification";
        if ($type == "pdf") {
            $config['column'] = array("Title", "Description", "Notify At", "Notify To", "Notified");
        } else {
            $config['column'] = array("Title", "Description", "Notify At", "Notify To", "Notified");
            $config['column_data_type'] = array(ExporterConst::E_STRING, ExporterConst::E_STRING, ExporterConst::E_STRING, ExporterConst::E_STRING, ExporterConst::E_STRING);
        }
        $config['data'] = $this->InvoiceModel->get_invoicenotify_export($type);
        if ($type == "pdf") {
            Exporter::export(ExporterConst::PDF, $config);
        } else if ($type == "excel") {
            Exporter::export(ExporterConst::EXCEL, $config);
        } else if ($type == "csv") {
            Exporter::export(ExporterConst::CSV, $config);
        }
    }

    // creditnote add

    public function creditadd($invoice_id)
    {
        if ($this->request->getpost("code")) {

            $data['code'] = $this->request->getpost("code");
            $data['issued_date'] = $this->request->getpost("issued_date");
            $data['other_charge'] = $this->request->getpost("other_charge");
            $data['payment_terms'] = $this->request->getpost("payment_terms");
            $data['terms_condition'] = $this->request->getpost("terms_condition");
            $data['remarks'] = $this->request->getpost("remarks");
            $data['created_by'] = get_user_id();
            $data['product_ids'] = $this->request->getpost("product_id");
            $data['qtys'] = $this->request->getpost("quantity");
            $data['unit_prices'] = $this->request->getpost("unit_price");
            if ($this->InvoiceModel->insertcredit($invoice_id, $data)) {
                return $this->response->redirect(url_to("erp.sale.invoice", $invoice_id));
            } else {
                return $this->response->redirect(url_to('erp.sale.invoice', $invoice_id));
            }
        }
        $data['invoice_items'] = $this->InvoiceModel->get_m_invoice_items_for_credit($invoice_id);
        if (empty($data['invoice_items'])) {
            session()->setFlashdata("op_error", "Invoice Not Found");
            return $this->response->redirect(url_to("erp.sale.invoice"));
        }
        $data['invoice_id'] = $invoice_id;
        $data['page'] = "sale/creditadd";
        $data['menu'] = "sale";
        $data['submenu'] = "invoice";
        return view("erp/index", $data);
    }


    //New credit Note 
    public function creditNoteAddPage()
    {
        if ($this->request->getpost("code")) {
            $data['code'] = $this->request->getpost("code");
            $data['cust_id'] = $this->request->getpost("customer_id");
            $data['issued_date'] = $this->request->getpost("issued_date");
            $data['other_charge'] = $this->request->getpost("other_charge");
            $data['payment_terms'] = $this->request->getpost("payment_terms");
            $data['terms_condition'] = $this->request->getpost("terms_condition");
            $data['remarks'] = $this->request->getpost("remarks");
            $data['created_by'] = get_user_id();
            // return var_dump($data);

            if ($this->InvoiceModel->insertCreditNote($data)) {
                return $this->response->redirect(url_to("erp.sale.creditnotes"));
            } else {
                return $this->response->redirect(url_to('erp.add.creditnoteadd'));
            }
        }
        $data['maxCode'] = $this->InvoiceModel->getMaximumCreditNoteNumber();
        $data['customer_company'] = $this->CustomersModel->getCustomersCompanyName();
        $data['page'] = "sale/creditnoteadd";
        $data['menu'] = "sale";
        $data['submenu'] = "invoice";
        return view("erp/index", $data);
    }

    public function creditNoteEditPage($credit_id)
    {
        $data['credit_id'] = $credit_id;
        if ($this->request->getpost("code")) {
            $data['code'] = $this->request->getpost("code");
            $data['cust_id'] = $this->request->getpost("customer_id");
            $data['issued_date'] = $this->request->getpost("issued_date");
            $data['other_charge'] = $this->request->getpost("other_charge");
            $data['payment_terms'] = $this->request->getpost("payment_terms");
            $data['terms_condition'] = $this->request->getpost("terms_condition");
            $data['remarks'] = $this->request->getpost("remarks");
            $data['created_by'] = get_user_id();

            if ($this->CreditNotesModel->updateCreditNote($data)) {
                return $this->response->redirect(url_to("erp.sale.creditnotes"));
            } else {
                return $this->response->redirect(url_to('erp.edit.creditnoteedit', $credit_id));
            }
        }

        $data['credit_note'] = $this->CreditNotesModel->getCreditnoteDataById($credit_id);
        $data['customer_company'] = $this->CustomersModel->getCustomersCompanyName();
        $data['page'] = "sale/creditnoteedit";
        $data['menu'] = "sale";
        $data['submenu'] = "invoice";
        return view("erp/index", $data);
    }

    //New CreditNote Apply Page Via Invoice
    public function creditApplyPage($invoice_id)
    {
        $data['invoice_id'] = $invoice_id;
        $data['credit_note'] = $this->CreditNotesModel->getCreditDataByInvoiceId($invoice_id);
        $data['invoice_code'] = $this->InvoiceModel->getInvoiceCodeByInvoiceId($invoice_id);
        $type = $this->InvoiceModel->get_invoicetype_by_id($invoice_id);
        if ($type["type"] == 3) {
            $data['invoice_amount'] = $this->InvoiceModel->getexpenseInvoiceAmountById($invoice_id);
        } else {
            $data['invoice_amount'] = $this->InvoiceModel->getInvoiceAmountById($invoice_id);
        }


        $data['page'] = "sale/creditnoteapply";
        $data['menu'] = "sale";
        $data['submenu'] = "invoice";
        return view("erp/index", $data);
    }

    public function creditApply($invoice_id)
    {
        $user_email = session()->get('erp_email');
        $data['credit_id'] = $this->request->getPost('credit_id');
        $data['invoice_id'] = $this->request->getPost('invoice_id');
        $data['amount'] = $this->request->getPost('amount_credit');
        $data['date_applied'] = date("Y-m-d");
        $data['staff_id'] = $this->UserModel->getStaffId($user_email);
        if ($this->CreditNotesModel->insertCreditAmountEntry($data)) {
            $this->session->setFlashdata("op_success", "Credit Note Applied Succssfully!");
            return $this->response->redirect(url_to('erp.invoice.manage.payandcredit', $invoice_id));
        } else {
            $this->session->setFlashdata("op_error", "Credit Note Failed to Applied!");
            return $this->response->redirect(url_to('erp.invoice.manage.payandcredit', $invoice_id));
        }
        $data['menu'] = "sale";
        $data['submenu'] = "invoice";
        $data['page'] = "sale/creditnoteapply";
        return view("erp/index", $data);
    }

    public function getCreditAmountByAjax()
    {
        $response = array();
        $credit_id = $this->request->getPost('creditId');
        $data['amount'] = $this->CreditNotesModel->getCreditAmountByCreditId($credit_id);
        return $this->response->setJSON($data);
    }

    public function invoiceApplyPage($credit_id)
    {
        $data['credit_id'] = $credit_id;
        $data['credit_note'] = $this->CreditNotesModel->getCreditData($credit_id);
        $customer_id = $this->CreditNotesModel->getCustomerId($credit_id);
        $data['invoice_code'] = $this->InvoiceModel->getInvoiceByCustomerId($customer_id);
        $data['balance_amount'] = $this->InvoiceModel->getTotalUsedAmount($credit_id);
        $data['page'] = "sale/invoice_credit_apply";
        $data['menu'] = "sale";
        $data['submenu'] = "creditnotes";
        return view("erp/index", $data);
    }

    public function invoiceApply($credit_id)
    {
        $user_email = session()->get('erp_email');
        $data['credit_id'] = $this->request->getPost('credit_id');
        $data['invoice_id'] = $this->request->getPost('invoice_id');
        $data['amount'] = $this->request->getPost('amount_credit');
        $data['date_applied'] = date("Y-m-d");
        $data['staff_id'] = $this->UserModel->getStaffId($user_email);

        if ($this->CreditNotesModel->insertCreditAmountEntry($data)) {
            $this->session->setFlashdata("op_success", "Credit Note Applied Succssfully!");
            return $this->response->redirect(url_to('erp.sale.creditnotesview', $credit_id));
        } else {
            $this->session->setFlashdata("op_error", "Credit Note Failed to Applied!");
            return $this->response->redirect(url_to('erp.sale.creditnotesview', $credit_id));
        }

        $data['menu'] = "sale";
        $data['submenu'] = "creditnotes";
        $data['page'] = "sale/creditviewPage";
        return view("erp/index", $data);
    }

    public function getInvoiceData()
    {
        $selected_inv_id = $this->request->getPost('selected_inv_id');
        $data = $this->InvoiceModel->getInvoiceDataById($selected_inv_id);
        return $this->response->setJSON($data);
    }

    //New Invoice via Credit apply Module
    public function InvoiceManageView($invoice_id)
    {
        $user_email = session()->get('erp_email');
        $user_name = session()->get('erp_username');
        if (is_manufacturing_system()) {
            $type = $this->InvoiceModel->get_invoicetype_by_id($invoice_id);
            if ($type["type"] == 3) {
                $data['invoice'] = $this->InvoiceModel->get_e_invoice_for_view($invoice_id);
                $data['applied_credits'] = $this->CreditNotesModel->getAppliedCreditsByInvoiceId($invoice_id);
            } else {
                $data['invoice'] = $this->InvoiceModel->get_m_invoice_for_view($invoice_id);
                $data['applied_credits'] = $this->CreditNotesModel->getAppliedCreditsByInvoiceId($invoice_id);
            }
            if (empty($data['invoice'])) {
                $this->session->setFlashdata("op_error", "Invoice Not Found");
                redirect("erp/sale/invoices");
            }
            $data['invoiceCreditItems'] = $this->InvoiceModel->get_m_invoice_Credititems($invoice_id);
            $data['page'] = "sale/m_invoice_manage";
        } else {
            $data['invoice'] = $this->InvoiceModel->get_c_invoice_for_view($invoice_id);
            if (empty($data['invoice'])) {
                $this->session->setFlashdata("op_error", "Invoice Not Found");
                redirect("erp/sale/invoices");
            }
            $data['invoiceCreditItems'] = $this->InvoiceModel->get_c_invoice_Credititems($invoice_id);
            $data['page'] = "sale/c_invoiceview";
        }
        $data['paymentmodes'] = $this->InvoiceModel->get_all_paymentmodes();
        $data['payment_datatable_config'] = $this->InvoiceModel->get_dtconfig_payments();
        $data['notify_datatable_config'] = $this->InvoiceModel->get_dtconfig_notify();
        $data['attachments'] = $this->InvoiceModel->get_attachments("sale_invoice", $invoice_id);
        $data['invoice_status'] = $this->InvoiceModel->get_invoice_status();
        $data['invoice_status_bg'] = $this->InvoiceModel->get_invoice_status_bg();
        $data['invoice_id'] = $invoice_id;
        $data['user_email'] = $user_email;
        $data['user_name'] = $user_name;
        $data['menu'] = "sale";
        $data['submenu'] = "invoice";
        return view("erp/index", $data);
    }

    public function getCreditData()
    {
        $selected_credit_id = $this->request->getPost('selected_credit_id');
        $data = $this->CreditNotesModel->getCreditDataById($selected_credit_id);
        return $this->response->setJSON($data);
    }




    public function stock_pick($order_id)
    {
        $this->OrdersModel->stock_pick($order_id);
        redirect("erp/sale/orderview/" . $order_id);
    }




    public function ajax_invoice_code_unique()
    {
        $code = $_GET['data'];
        $id = $_GET['id'] ?? 0;
        $query = "SELECT code FROM sale_invoice WHERE code='$code' LIMIT 1";
        if (!empty($id)) {
            $query = "SELECT code FROM sale_invoice WHERE code='$code' AND invoice_id<>$id LIMIT 1";
        }
        $check = $this->db->query($query)->getRow();
        $response = array();
        if (empty($check)) {
            $response['valid'] = 1;
        } else {
            $response['valid'] = 0;
            $response['msg'] = "Invoice Code already exists";
        }
        header("content-type:application/json");
        echo json_encode($response);
        exit();
    }

    //Payments

    public function ajaxSalepaymentResponse()
    {
        $response = array();
        $invoice_id = $this->request->getGet("invoiceid");
        $response = $this->SalePaymentModel->get_payment_datatable($invoice_id);
        header("Content-Type:application/json");
        echo json_encode($response);
        exit();
    }

    public function invoicePayment($invoice_id)
    {
        if ($this->request->getPost("amount")) {
            $data['amount'] = $this->request->getPost("amount");
            $data['paid_on'] = $this->request->getPost("paid_on");
            $data['payment_id'] = $this->request->getPost("payment_id");
            $data['transaction_id'] = $this->request->getPost("transaction_id");
            $data['notes'] = $this->request->getPost("notes");
            $sale_pay_id = $this->request->getPost("sale_pay_id") ?? 0;
            $this->SalePaymentModel->insert_update_payment($invoice_id, $sale_pay_id, $data);
        }
        return $this->response->redirect(url_to('erp.sale.invoice.view', $invoice_id));
    }

    public function ajaxFetchInvoicepayment($sale_pay_id)
    {
        $response = array();
        $query = "SELECT * FROM sale_payments WHERE sale_pay_id=$sale_pay_id";
        $result = $this->db->query($query)->getRow();
        if (!empty($result)) {
            $response['error'] = 0;
            $response['data'] = $result;
        } else {
            $response['error'] = 1;
        }
        header("Content-Type:application/json");
        echo json_encode($response);
        exit();
    }

    public function invoicePaymentDelete($invoice_id, $sale_pay_id)
    {
        $this->SalePaymentModel->delete_payment($invoice_id, $sale_pay_id);
        return $this->response->redirect(url_to('erp.sale.invoice.view', $invoice_id));
    }

    public function invoicePaymentExport()
    {
        $type = $_GET['export'];
        $config = array();
        $config['title'] = "Sale Payments";
        if ($type == "pdf") {
            $config['column'] = array("Payment Mode", "Amount", "Paid on", "Transaction ID", "Notes");
        } else {
            $config['column'] = array("Payment Mode", "Amount", "Paid on", "Transaction ID", "Notes");
            $config['column_data_type'] = array(ExporterConst::E_STRING, ExporterConst::E_STRING, ExporterConst::E_STRING, ExporterConst::E_STRING, ExporterConst::E_STRING);
        }
        $config['data'] = $this->SalePaymentModel->get_invoicepayment_export($type);
        if ($type == "pdf") {
            Exporter::export(ExporterConst::PDF, $config);
        } else if ($type == "excel") {
            Exporter::export(ExporterConst::EXCEL, $config);
        } else if ($type == "csv") {
            Exporter::export(ExporterConst::CSV, $config);
        }
    }





    //Credit Notes Starts
    public function creditNotes()
    {
        $data['credit_datatable_config'] = $this->CreditNotesModel->get_dtconfig_creditnotes();
        $data['page'] = "sale/creditnotes";
        $data['menu'] = "sale";
        $data['submenu'] = "creditnote";
        return view("erp/index", $data);
    }


    public function ajax_creditnote_code_unique()
    {
        $code = $_GET['data'];
        $id = $_GET['id'] ?? 0;
        $query = "SELECT code FROM credit_notes WHERE code='$code' LIMIT 1";
        if (!empty($id)) {
            $query = "SELECT code FROM credit_notes WHERE code='$code' AND credit_id<>$id LIMIT 1";
        }
        $check = $this->db->query($query)->getRow();
        $response = array();
        if (empty($check)) {
            $response['valid'] = 1;
        } else {
            $response['valid'] = 0;
            $response['msg'] = "Credit Code already exists";
        }
        header("content-type:application/json");
        echo json_encode($response);
        exit();
    }

    public function ajaxCreditnoteResponse()
    {
        $response = array();
        $response = $this->CreditNotesModel->getCreditnoteDatatable();
        header("Content-Type:application/json");
        echo json_encode($response);
        exit();
    }

    public function creditnote_export()
    {
        $type = $_GET['export'];
        $config = array();
        $config['title'] = "Credit Note";
        if ($type == "pdf") {
            $config['column'] = array("Credit Code", "Customer Company", "Issued Date", "Amount", "Outstanding Amount");
        } else {
            $config['column'] = array("Credit Code", "Customer Company", "Issued Date", "Amount", "Outstanding Amount");
            $config['column_data_type'] = array(ExporterConst::E_STRING, ExporterConst::E_STRING, ExporterConst::E_STRING, ExporterConst::E_STRING, ExporterConst::E_STRING);
        }
        $config['data'] = $this->CreditNotesModel->get_creditnote_export($type);
        if ($type == "pdf") {
            Exporter::export(ExporterConst::PDF, $config);
        } else if ($type == "excel") {
            Exporter::export(ExporterConst::EXCEL, $config);
        } else if ($type == "csv") {
            Exporter::export(ExporterConst::CSV, $config);
        }
    }

    public function creditNotesView($credit_id)
    {
        $user_email = session()->get('erp_email');
        $user_name = session()->get('erp_username');
        $data['creditnote'] = $this->CreditNotesModel->get_creditnote_for_view($credit_id);
        $data['customer_invoice_id'] = $this->CreditNotesModel->get_invoice_exist($credit_id);
        // return var_dump($data['customer_invoice_id']);
        if (empty($data['creditnote'])) {
            $this->session->setFlashdata("op_error", "Credit Note Not Found");
            redirect("erp/sale/creditnotes");
        }
        $data['credit_items'] = $this->CreditNotesModel->get_creditnote_items($credit_id);
        $data['notify_datatable_config'] = $this->CreditNotesModel->get_dtconfig_notify();
        $data['attachments'] = $this->CreditNotesModel->get_attachments("credit_note", $credit_id);
        $data['credited_invoice'] = $this->InvoiceModel->getCreditedInvoiceData($credit_id);
        $data['used_amount'] = $this->InvoiceModel->getTotalUsedAmount($credit_id);
        $data['credit_id'] = $credit_id;
        $data['user_email'] = $user_email;
        $data['user_name'] = $user_name;
        // $data['page'] = "sale/creditview";

        $customerContact = $this->CreditNotesModel->getCustomerContactById($credit_id);
        $customerMail = $this->CreditNotesModel->getCustomerMailById($credit_id);
        if (!empty($customerContact)) {
            $data['customerContact'] = $customerContact;
        } else {
            $data['customerContact'] = $customerMail;
        }

        $data['page'] = "sale/creditviewPage";
        $data['menu'] = "sale";
        $data['submenu'] = "creditnote";
        return view("erp/index", $data);
    }

    public function applyCredit($credit_id)
    {
        $this->CreditNotesModel->apply_credit($credit_id);
        redirect("erp/sale/creditview/" . $credit_id);
    }

    public function uploadCreditAttachment()
    {
        $id = $_GET["id"] ?? 0;
        $response = array();
        if (!empty($_FILES['attachment']['name'])) {
            $response = $this->CreditNotesModel->upload_attachment("credit_note", $id);
        } else {
            $response['error'] = 1;
            $response['reason'] = "No file to upload";
        }
        header("Content-Type:application/json");
        echo json_encode($response);
        exit();
    }

    public function creditDeleteAttachment()
    {
        $response = array();
        $response = $this->CreditNotesModel->delete_attachment("credit_note");
        header("Content-Type:application/json");
        echo json_encode($response);
        exit();
    }

    public function ajaxCreditnotifyResponse()
    {
        $response = array();
        $response = $this->CreditNotesModel->get_creditnotify_datatable();
        header("Content-Type:application/json");
        echo json_encode($response);
        exit();
    }

    public function creditNotify($credit_id)
    {
        if ($this->request->getPost("notify_title")) {
            $notify_id = $this->request->getPost("notify_id") ?? 0;
            $data['notify_title'] = $this->request->getPost("notify_title");
            $data['notify_desc'] = $this->request->getPost("notify_desc");
            $data['notify_to'] = $this->request->getPost("notify_to");
            $data['notify_at'] = $this->request->getPost("notify_at");
            $data['notify_email'] = $this->request->getPost("notify_email") ?? 0;
            $this->CreditNotesModel->insert_update_creditnotify($credit_id, $notify_id, $data);
        }
        return $this->response->redirect(url_to('erp.sale.creditnotesview', $credit_id));
    }

    public function creditNotifyExport()
    {
        $type = $_GET['export'];
        $config = array();
        $config['title'] = "Credit Notes Notification";
        if ($type == "pdf") {
            $config['column'] = array("Title", "Description", "Notify At", "Notify To", "Notified");
        } else {
            $config['column'] = array("Title", "Description", "Notify At", "Notify To", "Notified");
            $config['column_data_type'] = array(ExporterConst::E_STRING, ExporterConst::E_STRING, ExporterConst::E_STRING, ExporterConst::E_STRING, ExporterConst::E_STRING);
        }
        $credit_id = $this->request->getGet("creditid");
        $config['data'] = $this->CreditNotesModel->get_creditnotify_export($type, $credit_id);
        if ($type == "pdf") {
            Exporter::export(ExporterConst::PDF, $config);
        } else if ($type == "excel") {
            Exporter::export(ExporterConst::EXCEL, $config);
        } else if ($type == "csv") {
            Exporter::export(ExporterConst::CSV, $config);
        }
    }


    public function creditNotifyDelete($credit_id, $notify_id)
    {
        $this->CreditNotesModel->delete_credit_notify($credit_id, $notify_id);
        return $this->response->redirect(url_to('erp.sale.creditnotesview', $credit_id));
    }

    // IMPLEMENTED  Credit Note Delete 
    public function creditNotesDelete($credit_id)
    {
        $creditNote = $this->CreditNotesModel->get_creditnote_by_id($credit_id);
        if (empty($creditNote)) {
            session()->setFlashdata("op_error", "Credit Note Not Found");
            return $this->response->redirect(url_to('erp.sale.creditnotes'));
        }
        $deleted = $this->CreditNotesModel->delete_creditnote($credit_id);
        if ($deleted) {
            session()->setFlashdata("op_success", "Credit Note successfully deleted");
        } else {
            session()->setFlashdata("op_error", "Credit Note failed to delete");
        }
        return $this->response->redirect(url_to('erp.sale.creditnotes'));
    }


    //Overall Reports
    public function listReports()
    {
        $data['quotationStatus'] = $this->QuotationModel->quotationStatus();
        // $this->logger->error();21
        $data['title'] = "Sale Reports";
        $data['page'] = 'sale/salereports';
        $data['menu'] = 'sale';
        $data['submenu'] = 'reports';
        return view('erp/index', $data);
    }

    public function ajaxReportData()
    {
        $type = $this->request->getGet('r_type');
        $response = array();

        if ($type == 'inv_report') {
            $response['datatable_config_title'] = $this->InvoiceModel->invoiceTitle();
            $response['datatable_config_data'] = $this->InvoiceModel->getInvoiceData();
        } elseif ($type == 'item_report') {
            $response['datatable_config_title'] = $this->InvoiceModel->itemTitle();
            $response['datatable_config_data'] = $this->InvoiceModel->getItemData();
        } elseif ($type == 'payment_report') {
            $response['datatable_config_title'] = $this->InvoiceModel->invoicePaymentTitle();
            $response['datatable_config_data'] = $this->InvoiceModel->getPaymentData();
        } elseif ($type == 'credit_report') {
            $response['datatable_config_title'] = $this->InvoiceModel->creditNoteTitle();
            $response['datatable_config_data'] = $this->InvoiceModel->getCreditNoteData();
        } elseif ($type == 'proposal_report') {
            $response['datatable_config_title'] = $this->InvoiceModel->quotationsTitle();
            $response['datatable_config_data'] = $this->InvoiceModel->getquotationsData();
        } elseif ($type == 'estimate_report') {
            $response['datatable_config_title'] = $this->InvoiceModel->EstimatesTitle();
            $response['datatable_config_data'] = $this->InvoiceModel->getEstimatesData();
        } elseif ($type == 'customer_report') {
            $response['datatable_config_title'] = $this->InvoiceModel->customerTitle();
            $response['datatable_config_data'] = $this->InvoiceModel->getCustomerData();
        }
        return $this->response->setJSON($response);
    }


    public function ajaxPeriodReport()
    {
        $report_type = $this->request->getGet('report_type');
        $selected_period = $this->request->getGet('selectedPeriod');
        $fromDate = $this->request->getGet('fromDate');
        $toDate = $this->request->getGet('toDate');

        $period = $this->InvoiceModel->getTimePeriod();
        $payment_date = $this->SalePaymentModel->getAllTime();
        $quotation_date = $this->QuotationModel->getAllTime();
        $creditNote_date = $this->CreditNotesModel->getAllTime();
        $estimate_date = $this->EstimatesModel->getAllTime();

        $response = array();

        //All Time Data
        if ($selected_period == 'all_time') {
            if ($report_type == 'inv_report') {
                $response['datatable_config_title'] = $this->InvoiceModel->invoiceTitle();
                $response['datatable_config_data'] = $this->InvoiceModel->getInvoiceData();
            } elseif ($report_type == 'item_report') {
                $response['datatable_config_title'] = $this->InvoiceModel->itemTitle();
                $response['datatable_config_data'] = $this->InvoiceModel->getItemData();
            } elseif ($report_type == 'payment_report') {
                $response['datatable_config_title'] = $this->InvoiceModel->invoicePaymentTitle();
                $response['datatable_config_data'] = $this->InvoiceModel->getPaymentData();
            } elseif ($report_type == 'credit_report') {
                $response['datatable_config_title'] = $this->InvoiceModel->creditNoteTitle();
                $response['datatable_config_data'] = $this->InvoiceModel->getCreditNoteData();
            } elseif ($report_type == 'proposal_report') {
                $response['datatable_config_title'] = $this->InvoiceModel->quotationsTitle();
                $response['datatable_config_data'] = $this->InvoiceModel->getquotationsData();
            } elseif ($report_type == 'estimate_report') {
                $response['datatable_config_title'] = $this->InvoiceModel->EstimatesTitle();
                $response['datatable_config_data'] = $this->InvoiceModel->getEstimatesData();
            } elseif ($report_type == 'customer_report') {
                $response['datatable_config_title'] = $this->InvoiceModel->customerTitle();
                $response['datatable_config_data'] = $this->InvoiceModel->getCustomerData();
            }

            //Current Month Data
        } elseif ($selected_period == 'this_month') {
            $currentMonth = date('m');
            if ($report_type == 'inv_report') {

                $filteredData = array_filter($period, function ($date) use ($currentMonth) {
                    return date('m', $date['created_at']) == $currentMonth;
                });
                $thisMonth = array_column($filteredData, 'created_at');
                $response['datatable_config_title'] = $this->InvoiceModel->invoiceTitle();
                $response['datatable_config_data'] = $this->InvoiceModel->getInvoiceDataByDate($thisMonth);
            } elseif ($report_type == 'item_report') {

                $response['datatable_config_title'] = $this->InvoiceModel->itemTitle();
                $response['datatable_config_data'] = $this->InvoiceModel->getItemDataByMonth();
            } elseif ($report_type == 'payment_report') {

                $filteredData = array_filter($payment_date, function ($date) use ($currentMonth) {
                    return date('m', strtotime($date['paid_on'])) == $currentMonth;
                });
                $thisMonth = array_column($filteredData, 'paid_on');
                $response['alld'] = $filteredData;
                $response['datatable_config_title'] = $this->InvoiceModel->invoicePaymentTitle();
                $response['datatable_config_data'] = $this->InvoiceModel->getPaymentDataByDate($thisMonth);
            } elseif ($report_type == 'credit_report') {

                $filteredData = array_filter($creditNote_date, function ($date) use ($currentMonth) {
                    return date('m', $date['created_at']) == $currentMonth;
                });
                $thisMonth = array_column($filteredData, 'created_at');
                $response['datatable_config_title'] = $this->InvoiceModel->creditNoteTitle();
                $response['datatable_config_data'] = $this->InvoiceModel->getCreditNoteDataByDate($thisMonth);
            } elseif ($report_type == 'proposal_report') {

                $filteredData = array_filter($quotation_date, function ($date) use ($currentMonth) {
                    return date('m', $date['created_at']) == $currentMonth;
                });
                $thisMonth = array_column($filteredData, 'created_at');
                $response['datatable_config_title'] = $this->InvoiceModel->quotationsTitle();
                $response['datatable_config_data'] = $this->InvoiceModel->getquotationsDataByDate($thisMonth);
            } elseif ($report_type == 'estimate_report') {

                $filteredData = array_filter($estimate_date, function ($date) use ($currentMonth) {
                    return date('m', $date['created_at']) == $currentMonth;
                });
                $thisMonth = array_column($filteredData, 'created_at');
                $response['alld'] = $thisMonth;
                $response['datatable_config_title'] = $this->InvoiceModel->EstimatesTitle();
                $response['datatable_config_data'] = $this->InvoiceModel->getEstimatesDataByDate($thisMonth);
            } elseif ($report_type == 'customer_report') {

                $filteredData = array_filter($period, function ($date) use ($currentMonth) {
                    return date('m', $date['created_at']) == $currentMonth;
                });
                $thisMonth = array_column($filteredData, 'created_at');
                $response['datatable_config_title'] = $this->InvoiceModel->customerTitle();
                $response['datatable_config_data'] = $this->InvoiceModel->getCustomerDataByDate($thisMonth);
            }

            //Customized Date Data
        } elseif ($selected_period == 'duration') {
            $fromDateTimestamp = strtotime($fromDate . ' 00:00:00');
            $toDateTimestamp = strtotime($toDate . ' 23:59:58');

            if ($report_type == 'inv_report') {
                $filteredData = array_filter($period, function ($date) use ($fromDateTimestamp, $toDateTimestamp) {
                    return $date['created_at'] >= $fromDateTimestamp && $date['created_at'] <= $toDateTimestamp;
                });
                $durationData = array_column($filteredData, 'created_at');
                $response['datatable_config_title'] = $this->InvoiceModel->invoiceTitle();
                $response['datatable_config_data'] = $this->InvoiceModel->getInvoiceDataByDate($durationData);
            } elseif ($report_type == 'item_report') {
                $response['datatable_config_title'] = $this->InvoiceModel->itemTitle();
                $response['datatable_config_data'] = $this->InvoiceModel->getItemDataBySpecific_period($fromDateTimestamp, $toDateTimestamp);
            } elseif ($report_type == 'payment_report') {
                $filteredData = array_filter($payment_date, function ($date) use ($fromDate, $toDate) {
                    return $date['paid_on'] >= $fromDate && $date['paid_on'] <= $toDate;
                });
                $durationData = array_column($filteredData, 'paid_on');
                $response['fromDate'] = $durationData;
                $response['datatable_config_title'] = $this->InvoiceModel->invoicePaymentTitle();
                $response['datatable_config_data'] = $this->InvoiceModel->getPaymentDataByDate($durationData);
            } elseif ($report_type == 'credit_report') {

                $filteredData = array_filter($creditNote_date, function ($date) use ($fromDateTimestamp, $toDateTimestamp) {
                    return $date['created_at'] >= $fromDateTimestamp && $date['created_at'] <= $toDateTimestamp;
                });
                $durationData = array_column($filteredData, 'created_at');
                $response['datatable_config_title'] = $this->InvoiceModel->creditNoteTitle();
                $response['datatable_config_data'] = $this->InvoiceModel->getCreditNoteDataByDate($durationData);
            } elseif ($report_type == 'proposal_report') {
                $filteredData = array_filter($quotation_date, function ($date) use ($fromDateTimestamp, $toDateTimestamp) {
                    return $date['created_at'] >= $fromDateTimestamp && $date['created_at'] <= $toDateTimestamp;
                });
                $durationData = array_column($filteredData, 'created_at');
                $response['datatable_config_title'] = $this->InvoiceModel->quotationsTitle();
                $response['datatable_config_data'] = $this->InvoiceModel->getquotationsDataByDate($durationData);
            } elseif ($report_type == 'estimate_report') {
                $filteredData = array_filter($estimate_date, function ($date) use ($fromDateTimestamp, $toDateTimestamp) {
                    return $date['created_at'] >= $fromDateTimestamp && $date['created_at'] <= $toDateTimestamp;
                });
                $durationData = array_column($filteredData, 'created_at');
                $response['alld'] = $durationData;
                $response['datatable_config_title'] = $this->InvoiceModel->EstimatesTitle();
                $response['datatable_config_data'] = $this->InvoiceModel->getEstimatesDataByDate($durationData);
            } elseif ($report_type == 'customer_report') {
                $filteredData = array_filter($period, function ($date) use ($fromDateTimestamp, $toDateTimestamp) {
                    return $date['created_at'] >= $fromDateTimestamp && $date['created_at'] <= $toDateTimestamp;
                });
                $durationData = array_column($filteredData, 'created_at');
                $response['datatable_config_title'] = $this->InvoiceModel->customerTitle();
                $response['datatable_config_data'] = $this->InvoiceModel->getCustomerDataByDate($durationData);
            }
        }
        return $this->response->setJSON($response);
    }

    public function listChartReports()
    {
        $chart = $this->request->getPost('selected_chart');
        if ($chart == 'total_income') {
            $data['result'] = $this->InvoiceModel->getTotalIncome();
        } elseif ($chart == 'payment_mode') {
            $data['result'] = $this->InvoiceModel->getPaymentDataChart();
        } elseif ($chart == 'total_customer_value') {
            $data['result'] = $this->InvoiceModel->getTotalValueByCustomerGroupsChart();
        }
        return $this->response->setJSON($data);
    }


    public function sendEmail($id, $page)
    {
        $toEmails = $this->request->getPost('to_mail');
        $ccEmails = $this->request->getPost('cc_mail');
        $subject = $this->request->getPost('subject_mail');
        $content = $this->request->getPost('mail_message');

        send_app_mail($toEmails, $subject, $content);
        //Test Purpose
        session()->setFlashdata("op_success", "Mail Sent Successfully!");

        // if ($mail->send()) {
        // session()->setFlashdata("op_success", "Mail Sent Successfully!");
        // } else {
        //    session()->setFlashdata("op_error", "Mail could not be sent!");
        // }

        if ($page == 'estimates'):
            $redirect_url = 'erp.sale.estimates.view';
        elseif ($page == 'quotations'):
            $redirect_url = 'erp.sale.quotations.view';
        elseif ($page == 'orders'):
            $redirect_url = 'erp.sale.orders.view';
        elseif ($page == 'invoice'):
            $redirect_url = 'erp.sale.invoice.view';
        elseif ($page == 'credit_notes'):
            $redirect_url = 'erp.sale.creditnotesview';
        endif;

        return $this->response->redirect(url_to($redirect_url, $id));
    }

    //Ajax Fetch Currencies
    public function ajaxFetchCurrencies()
    {
        $search = $_GET['search'] ?? '';
        $place = $_GET['place'] ?? '';

        $query = "SELECT currency_id, CONCAT(iso_code,'',symbol) AS currency_name FROM currencies";

        if (!empty($search)) {
            $query .= " WHERE (iso_code LIKE '%" . $search . "%' OR symbol LIKE '%" . $search . "%')";
            if (!empty($place)) {
                $query .= " AND place = '" . $place . "'";
            }
        } else if (!empty($place)) {
            $query .= " WHERE place = '" . $place . "'";
        }

        $query .= " LIMIT 10";

        $result = $this->db->query($query)->getResultArray();
        $m_result = [];
        foreach ($result as $row) {
            array_push($m_result, [
                "currency_id" => $row['currency_id'],
                "currency_name" => $row['currency_name']
            ]);
        }

        $response['data'] = $m_result;
        header("Content-Type:application/json");
        echo json_encode($response);
        exit();
    }

    public function getCurrenySymbol()
    {
        $currency_id = $this->request->getGet('currency_id');
        $currency_symbol = $this->CurrenciesModel->getCurrencySymbolById($currency_id);
        return $this->response->setJSON($currency_symbol);
    }

    //Payment Menu
    public function invoicePaymentList()
    {
        $data['payments_datatable_config'] = $this->PaymentModesModel->get_dtconfig_payments_list();
        $data['title'] = "payments";
        $data['page'] = 'sale/payments';
        $data['menu'] = 'sale';
        $data['submenu'] = 'payments';
        return view('erp/index', $data);
    }

    public function ajaxPaymentlistResponse()
    {
        $response = array();
        $response = $this->PaymentModesModel->get_payment_list_datatable();
        return $this->response->setJSON($response);
    }

    public function paymentViewPage($id)
    {
        $data['sale_pay_id'] = $id;
        $data['organisationData'] = $this->ErpCompanyInformationModel->getAlldata();
        $data['customer_data'] = $this->PaymentModesModel->getPaymentData($id);
        $data['invoice_data'] = $this->PaymentModesModel->getInvoiceData($id);
        $data['total_paid_amount'] = $this->PaymentModesModel->getTotalPaidAmount($data['invoice_data']['invoice_id']);
        $data['currency'] = $this->SalePaymentModel->getCurrenySymbolByid($id);
        $data['title'] = "payments";
        $data['page'] = 'sale/paymentview';
        $data['menu'] = 'sale';
        $data['submenu'] = 'payments';
        return view('erp/index', $data);
    }

    public function paymentEditPage($id)
    {
        $data['sale_pay_id'] = $id;
        $data['payment_data'] = $this->PaymentModesModel->getPaymentDataByPaymentId($id);
        $data['paymentmodes'] = $this->InvoiceModel->get_all_paymentmodes();
        $data['selected_payment_mode'] = $this->InvoiceModel->getpaymentmodeById($id);
        $data['title'] = "payments";
        $data['page'] = 'sale/paymentedit';
        $data['menu'] = 'sale';
        $data['submenu'] = 'payments';
        return view('erp/index', $data);
    }

    public function paymentEdit($id)
    {
        $data['sale_pay_id'] = $id;
        $data['amount'] = $this->request->getPost("amount");
        $data['paid_on'] = $this->request->getPost("paid_on");
        $data['payment_id'] = $this->request->getPost("payment_id");
        $data['transaction_id'] = $this->request->getPost("transaction_id");
        $data['notes'] = $this->request->getPost("notes") ?? '';

        if ($this->PaymentModesModel->update_payment($data)) {
            session()->setFlashdata("op_success", "Payment Successfully updated");
        } else {
            session()->setFlashdata("op_error", "Payment Failed to update");
        }
        return $this->response->redirect(url_to('erp.sale.payments'));
    }

    public function paymentDelete($id)
    {
        if ($this->SalePaymentModel->delete($id)) {
            session()->setFlashdata("op_success", "Payment Deleted Successfully");
        } else {
            session()->setFlashdata("op_error", "Payment Failed to Delete");
        }
        return $this->response->redirect(url_to('erp.sale.payments'));
    }

    public function paymentpdf($sale_pay_id, $type)
    {
        $invoice_data = $this->PaymentModesModel->getInvoiceData($sale_pay_id);
        $customer_data = $this->PaymentModesModel->getPaymentData($sale_pay_id);
        $organisation_data = $this->ErpCompanyInformationModel->getAlldata();
        $total_paid_amount = $this->PaymentModesModel->getTotalPaidAmount($invoice_data['invoice_id']);
        $currency = $this->SalePaymentModel->getCurrenySymbolByid($sale_pay_id);
        // echo '<pre>';
        // return var_dump($total_paid_amount);

        $pdf = new TCPDF();
        $pdf->SetTitle('Payment #' . $sale_pay_id);
        $pdf->setPrintHeader(false);
        $pdf->AddPage();

        //Customer Data 
        if (!empty($customer_data)) {
            $customerData = '<div style = "text-align:left; font-family:Times New Roman; color:#2b2929;">
                             <div style = "font-size: 15px;"><b>' . $customer_data['company'] . '</b></div>' .
                $customer_data['name'] . ',<br/>' .
                $customer_data['address'] . ',<br/>' .
                $customer_data['city'] . ', ' . $customer_data['state'] . ',<br/>' .
                $customer_data['country'] . '-' . $customer_data['zip'] . '</div>';
        }

        //Organization Information
        $organizationInfo = '<div style = "text-align:right; font-family:Times New Roman;"><b>' . $organisation_data['company_name'] . '</b>
                                <div style="color:#2b2929;">
                                    ' . $organisation_data['address'] . ',<br/>
                                    ' . $organisation_data['city'] . ',<br/>
                                    ' . $organisation_data['state'] . ',<br/>
                                    ' . $organisation_data['country'] . ',<br/>
                                    ' . $organisation_data['zipcode'] . '.<br/>
                        Mobile No.: ' . $organisation_data['phone_number'] . '<br/>
                        VAT Number: ' . $organisation_data['vat_number'] . '<br/>
                            License ' . $organisation_data['license_number'] . '<br/>
                            </div></div>';

        $table = '<table width="100%" border="0"><tr>';
        $table .= '<td width="50%">' . $customerData . '</td>';
        $table .= '<td width="50%">' . $organizationInfo . '</td>';
        $table .= '</tr></table>';

        $pdf->writeHTML($table, true, false, false, false, '');
        $pdf->ln(4);

        $payment_receipt = '<div style = "text-align:center; font-family:Times New Roman; font-size:16px;">PAYMENT RECEIPT</div><br><br><br>';
        $pdf->writeHTML($payment_receipt);

        // Currency Symbol Position
        $due_amount = $invoice_data['total_amount'] - ($total_paid_amount + $invoice_data['credited_amount']);
        if ($currency['place'] === 'after'):
            $totalAmount = number_format($invoice_data['total_amount'], 2, '.', ',') . '' . $currency['currency_symbol'];
            $paidAmount = number_format($invoice_data['paid_amount'], 2, '.', ',') . '' . $currency['currency_symbol'];
            $dueAmount = number_format($due_amount, 2, '.', ',') . '' . $currency['currency_symbol'];
        elseif ($currency['place'] === 'before'):
            $totalAmount = $currency['currency_symbol'] . number_format($invoice_data['total_amount'], 2, '.', ',');
            $paidAmount = $currency['currency_symbol'] . number_format($invoice_data['paid_amount'], 2, '.', ',');
            $dueAmount = $currency['currency_symbol'] . number_format($due_amount, 2, '.', ',');
        endif;

        $payment_rec_data = '<div>
        Payment Date :' . $invoice_data['paid_on'] . '<br><br>
        Payment Mode :' . $invoice_data['payment_mode'] . '<br>
        <p style="background-color: #74b544; color: #fff; padding: 90px; width: 50%; font-size: 16px; text-align:center; font-family:freeserif">Total Amount<br /> ' . $paidAmount . '</p></div>';

        $pdf->writeHTML($payment_rec_data);
        $pdf->ln(5);


        $payment_for = '<br><br><div style = "text-align:left; font-family:Times New Roman; font-size:15px;">PAYMENT FOR</div><br>';
        $pdf->writeHTML($payment_for);

        //Payment Items Table and Summary
        if (!empty($invoice_data)) {

            //Set font For all currencies symbol accept
            $pdf->SetFont('freeserif', '', 12);

            $tableHTML = '<table border="1" cellspacing="0" cellpadding="5">
                <tr style="text-align:center; background-color:#063970; color: white; font-family:Times New Roman;">
                    <th>Invoice Number</th>
                    <th>Invoice Date</th>
                    <th>Invoice Amount</th>
                    <th style="font-family:Times New Roman; font-size:11px;">Payment Amount</th>
                    <th style="font-family:Times New Roman; font-size:11px;">Amount Due</th>
                </tr>';
            $tableHTML .= '<tbody>';

            $tableHTML .= '<tr style = "text-align:center;">
                    <td style="font-family:Times New Roman; font-size:11px;">' . $invoice_data['code'] . '</td>
                    <td style="font-family:Times New Roman; font-size:11px;">' . $invoice_data['invoice_date'] . '</td>
                    <td style="font-size:11px;">' . $totalAmount . '</td>
                    <td style="font-size:11px;">' . $paidAmount . '</td>
                    <td style="font-size:11px;">' . $dueAmount . '</td>
                </tr></tbody></table>';
        }
        $pdf->writeHTML($tableHTML, true, false, false, false, '');
        $pdf->ln(5);

        $pdfFilePath = FCPATH . 'uploads/payments/' . $sale_pay_id . '.pdf';
        if ($type == 'view') {
            $pdf->Output('payment.pdf', 'I');
        } elseif ($type == 'download') {
            $pdf->Output($pdfFilePath, 'F');
            header('Content-Type: application/pdf');
            header('Content-Disposition: attachment; filename="payment.pdf"');
            readfile($pdfFilePath);
        }
        exit();
    }
    public function add_expense_invoice($id)
    {
        $data['code'] = $this->request->getPost("code");
        $data['cust_id'] = $this->request->getPost("cust_id");
        $data['invoice_date'] = $this->request->getPost("invoice_date");
        $data['shippingaddr_id'] = $this->request->getPost("shippingaddr_id");
        $data['terms_condition'] = $this->request->getPost("terms_condition");
        $data['currency_id'] = $this->request->getPost("currency_id");
        $data['currency_place'] = $this->request->getPost("currency_place");
        $data['payment_terms'] = $this->request->getPost("payment_terms");
        $data['transport_req'] = $this->request->getPost("transport_req") ?? 0;
        $data['trans_charge'] = $this->request->getPost("trans_charge");
        $data['invoice_expiry'] = $this->request->getPost("invoice_expiry");
        $data['status'] = $this->request->getPost("status");
        $data['created_by'] = get_user_id();
        //$data['product_ids'] = $this->request->getpost("product_id");
        //$data['quantities'] = $this->request->getpost("quantity");
        //$data['price_ids'] = $this->request->getpost("price_id");
        $data['related_id'] = $this->request->getPost("related_id");
        $data['expense_amount'] = $this->request->getPost("expense_amount");
        $data['tax_1'] = $this->request->getPost("tax1");
        $data['tax_2'] = $this->request->getPost("tax2");
        if (is_manufacturing_system()) {
            if ($this->request->getpost("code")) {
                // return var_dump($_POST);
                if ($this->InvoiceModel->insert_e_invoice($data, $id)) {
                    return $this->response->redirect(url_to('erp.sale.invoice'));
                } else {
                    return $this->response->redirect(url_to('erp.sale.invoice.add'));
                }
            }
            $data['maxCode'] = $this->InvoiceModel->getMaximumInvoiceNumber();
            $data['currency_place'] = $this->CurrenciesModel->getCurrencyPlace();
            $data['page'] = "sale/e_invoice_add";
        } else {
            if ($this->request->getpost("code")) {
                if ($this->InvoiceModel->insert_c_invoice("")) {
                    return $this->response->redirect(url_to('erp.sale.invoice'));
                } else {
                    return $this->response->redirect(url_to('erp.sale.invoice.add'));
                }
            }
            $data['page'] = "sale/c_invoiceadd";
        }
        $data['invoice_status'] = $this->InvoiceModel->get_invoice_status();
        $data['expense_existing_data'] = $this->ExpensesModel->get_exsisting_data($id);
        $data['menu'] = "sale";
        $data['submenu'] = "invoice";
        return view("erp/index", $data);
    }
}
