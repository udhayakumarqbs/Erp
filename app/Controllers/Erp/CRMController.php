<?php

namespace App\Controllers\Erp;

use App\Controllers\BaseController;
use App\Libraries\Exporter;
use App\Libraries\Exporter\ExporterConst;
use App\Libraries\Importer;
use App\Libraries\Importer\AbstractImporter;
use App\Libraries\Requestor;
use App\Models\Erp\AttachmentModel;
use App\Models\Erp\ContractorsModel;
use App\Models\Erp\CustomerContactsModel;
use App\Models\Erp\CustomerShippingAddrModel;
use App\Models\Erp\CustomerBillingAddrsModel;
use App\Models\Erp\CustomersModel;
use App\Models\Erp\CustomFieldModel;
use App\Models\Erp\ErpGroupsModel;
use App\Models\Erp\LeadsModel;
use App\Models\Erp\LeadSourceModel;
use App\Models\Erp\MarketingModel;
use App\Models\Erp\NotificationModel;
use App\Models\Erp\ProjectsModel;
use App\Models\Erp\Sale\EstimatesModel;
use App\Models\Erp\RawMaterialModel;
use App\Models\Erp\Sale\InvoiceModel;
use App\Models\Erp\TicketcommentModel;
use App\Models\Erp\Sale\QuotationModel;
use App\Models\Erp\Sale\RequestModel;
use App\Models\Erp\TaskModel;
use App\Models\Erp\TicketsModel;
use CodeIgniter\HTTP\Exceptions\HTTPException;
use Error;

class CRMController extends BaseController
{
    public $data;
    protected $LeadsModel;
    protected $LeadSourceModel;
    protected $AttachmentModel;
    protected $RawMaterialModel;
    protected $CustomFieldModel;
    protected $NotificationModel;
    protected $ErpGroupsModel;
    protected $CustomersModel;
    protected $TaskModel;
    protected $CustomerContactsModel;
    protected $ProjectsModel;
    protected $InvoiceModel;
    protected $EstimatesModel;
    protected $ContractorsModel;
    protected $QuotationModel;
    protected $MarketingModel;
    protected $RequestModel;
    protected $TicketsModel;
    protected $TicketcommentModel;
    protected $CustomerShippingAddrModel;
    protected $CustomerBillingAddrsModel;
    protected $logger;
    private $db;
    protected $session;

    public function __construct()
    {
        $this->LeadsModel = new LeadsModel();
        $this->LeadSourceModel = new LeadSourceModel();
        $this->AttachmentModel = new AttachmentModel();
        $this->CustomFieldModel = new CustomFieldModel();
        $this->NotificationModel = new NotificationModel();
        $this->ErpGroupsModel = new ErpGroupsModel();
        $this->CustomersModel = new CustomersModel();
        $this->CustomerContactsModel = new CustomerContactsModel();
        $this->CustomerShippingAddrModel = new CustomerShippingAddrModel();
        $this->CustomerBillingAddrsModel = new CustomerBillingAddrsModel();
        $this->MarketingModel = new MarketingModel();
        $this->RequestModel = new RequestModel();
        $this->TicketsModel = new TicketsModel();
        $this->RawMaterialModel = new RawMaterialModel();
        $this->TaskModel = new TaskModel();
        $this->ProjectsModel = new ProjectsModel();
        $this->InvoiceModel = new InvoiceModel();
        $this->EstimatesModel = new EstimatesModel();
        $this->ContractorsModel = new ContractorsModel();
        $this->QuotationModel = new QuotationModel();
        $this->TicketcommentModel = new TicketcommentModel();
        $this->db = \Config\Database::connect();
        $this->session = \Config\Services::session();
        $this->logger = \Config\Services::logger();
        helper(['erp', 'form']);
    }

    // Ajax Requests and Responses
    public function ajaxFetchFinishedGoods()
    {
        $search = $_GET['search'] ?? "";
        $response = array();
        $response['error'] = 1;
        if (!empty($search)) {
            $query = "SELECT finished_good_id,CONCAT(name,' ',code) AS name FROM finished_goods WHERE name LIKE '%" . $search . "%' OR code LIKE '%" . $search . "%'  LIMIT 10";
            $result = $this->db->query($query)->getResultArray();
            $m_result = array();
            foreach ($result as $r) {
                array_push($m_result, array(
                    "key" => $r['finished_good_id'],
                    "value" => $r['name']
                ));
            }
            $response['error'] = 0;
            $response['data'] = $m_result;
        }
        return $this->response->setJSON($response);
    }
    public function ajaxFetchrawmaterials()
    {
        $search = $_GET['search'] ?? "";
        $response = array();
        $response['error'] = 1;
        if (!empty($search)) {
            $query = "SELECT raw_material_id,CONCAT(name,' ',code) AS name FROM raw_materials WHERE name LIKE '%" . $search . "%' OR code LIKE '%" . $search . "%'  LIMIT 10";
            $result = $this->db->query($query)->getResultArray();
            $m_result = array();
            foreach ($result as $r) {
                array_push($m_result, array(
                    "key" => $r['raw_material_id'],
                    "value" => $r['name']
                ));
            }
            $response['error'] = 0;
            $response['data'] = $m_result;
        }
        return $this->response->setJSON($response);
    }
    public function ajaxFetchwarehouse()
    {
        $search = $_GET['search'] ?? "";
        $response = array();
        $response['error'] = 1;
        if (!empty($search)) {
            $query = "SELECT warehouse_id,name FROM warehouses WHERE name LIKE '%" . $search . "%'  LIMIT 10";
            $result = $this->db->query($query)->getResultArray();
            $m_result = array();
            foreach ($result as $r) {
                array_push($m_result, array(
                    "key" => $r['warehouse_id'],
                    "value" => $r['name']
                ));
            }
            $response['error'] = 0;
            $response['data'] = $m_result;
        }
        return $this->response->setJSON($response);
    }

    
    ///udhaya

    public function ajaxFetchSemiFinished()
    {
        $search = $_GET['search'] ?? "";
        $response = array();
        $response['error'] = 1;
        if (!empty($search)) {
            $query = "SELECT semi_finished_id,CONCAT(name,' ',code) AS name FROM semi_finished WHERE name LIKE '%" . $search . "%' OR code LIKE '%" . $search . "%'  LIMIT 6";
            $result = $this->db->query($query)->getResultArray();
            $m_result = array();
            foreach ($result as $r) {
                array_push($m_result, array(
                    "key" => $r['semi_finished_id'],
                    "value" => $r['name']
                ));
            }
            $response['error'] = 0;
            $response['data'] = $m_result;
        }
        return $this->response->setJSON($response);
    }

    // Fetch Users
    public function ajaxfetchusers()
    {
        $search = $_GET['search'] ?? "";
        $response = array();
        $response['error'] = 1;
        if (!empty($search)) {
            $query = "SELECT user_id,name FROM erp_users WHERE active=1 AND name LIKE '%" . $search . "%' LIMIT 10";
            $result = $this->db->query($query)->getResultArray();
            $m_result = array();
            foreach ($result as $r) {
                array_push($m_result, array(
                    "key" => $r['user_id'],
                    "value" => $r['name']
                ));
            }
            $response['error'] = 0;
            $response['data'] = $m_result;
        }
        return $this->response->setJSON($response);
    }

    public function ajaxfetchcustomers()
    {
        $search = $_GET['search'] ?? "";
        $response = array();
        $response['error'] = 1;
        if (!empty($search)) {
            $query = "SELECT a.cust_id,b.contact_id,CONCAT(a.name,' - ',a.company,' ( ',b.firstname,' ',b.lastname,' ) ') AS name FROM customers
             as a JOIN customer_contacts AS b ON b.cust_id = a.cust_id AND b.primary_contact = 1 WHERE name LIKE
              '%" . $search . "%' OR a.company LIKE '%" . $search . "%' LIMIT 10";
            $result = $this->db->query($query)->getResultArray();
            $m_result = array();
            foreach ($result as $r) {
                array_push($m_result, array(
                    "key" => $r['contact_id'],
                    "value" => $r['name']
                ));
            }
            $response['error'] = 0;
            $response['data'] = $m_result;
        }
        return $this->response->setJSON($response);
    }
    public function ajaxfetchcustomersfortickets()
    {
        $search = $_GET['search'] ?? "";
        $response = array();
        $response['error'] = 1;
        if (!empty($search)) {
            $query = "SELECT a.cust_id,b.contact_id,CONCAT(a.name,' - ',a.company,' ( ',b.firstname,' ',b.lastname,' ) ') AS name FROM customers
             as a JOIN customer_contacts AS b ON b.cust_id = a.cust_id AND b.primary_contact = 1 WHERE name LIKE
              '%" . $search . "%' OR a.company LIKE '%" . $search . "%' LIMIT 10";
            $result = $this->db->query($query)->getResultArray();
            $m_result = array();
            foreach ($result as $r) {
                array_push($m_result, array(
                    "key" => $r['contact_id'],
                    "value" => $r['name']
                ));
            }
            $response['error'] = 0;
            $response['data'] = $m_result;
        }
        return $this->response->setJSON($response);
    }

    public function index()
    {
        $this->leads();
    }

    public function leads()
    {
        $this->data['datatable_config'] = $this->LeadsModel->get_dtconfig_leads();
        $this->data['lead_status'] = $this->LeadsModel->get_lead_status();
        $this->data['lead_source'] = $this->LeadSourceModel->get_lead_source();
        $this->data['page'] = "crm/leads";
        $this->data['menu'] = "crm";
        $this->data['submenu'] = "lead";
        return view("erp/index", $this->data);
    }

    public function leadadd()
    {
        $post = [
            'lead_source' => $this->request->getPost("lead_source"),
            'lead_status' => $this->request->getPost("lead_status"),
            'lead_assigned_to' => $this->request->getPost("lead_assigned_to"),
            'name' => $this->request->getPost("name"),
            'position' => $this->request->getPost("position"),
            'address' => $this->request->getPost("address"),
            'state' => $this->request->getPost("state"),
            'city' => $this->request->getPost("city"),
            'country' => $this->request->getPost("country"),
            'zip' => $this->request->getPost("zip"),
            'website' => $this->request->getPost("website"),
            'company' => $this->request->getPost("company"),
            'description' => $this->request->getPost("description"),
            'remarks' => $this->request->getPost("remarks"),
            'email' => $this->request->getPost("email"),
            'phone' => $this->request->getPost("phone"),
        ];
        if ($this->request->getPost("lead_source")) {
            if ($this->LeadsModel->insert_lead($post)) {
                return $this->response->redirect(url_to("erp.crm.leads"));
            } else {
                return $this->response->redirect(url_to("erp.crm.leadadd"));
            }
        }
        $this->data['lead_status'] = $this->LeadsModel->get_lead_status();
        $this->data['lead_source'] = $this->LeadSourceModel->get_lead_source();

        $this->data['page'] = "crm/leadadd";
        $this->data['menu'] = "crm";
        $this->data['submenu'] = "lead";
        return view("erp/index", $this->data);
    }

    public function leadExport()
    {
        $type = $_GET['export'];
        $config = array();
        $config['title'] = "Leads";
        if ($type == "pdf") {
            $config['column'] = array("Name", "Email", "Phone", "Position", "Company", "Status", "Source");
        } else {
            $config['column'] = array("Name", "Email", "Phone", "Position", "Company", "Status", "Source", "Assigned To", "Address", "City", "State", "Country", "Zipcode", "Website");
            $config['column_data_type'] = array(ExporterConst::E_STRING, ExporterConst::E_STRING, ExporterConst::E_STRING, ExporterConst::E_STRING, ExporterConst::E_STRING, ExporterConst::E_STRING, ExporterConst::E_STRING, ExporterConst::E_STRING, ExporterConst::E_STRING, ExporterConst::E_STRING, ExporterConst::E_STRING, ExporterConst::E_STRING, ExporterConst::E_STRING, ExporterConst::E_STRING);
        }
        $config['data'] = $this->LeadsModel->get_leads_export($type);
        if ($type == "pdf") {
            Exporter::export(ExporterConst::PDF, $config);
        } else if ($type == "excel") {
            Exporter::export(ExporterConst::EXCEL, $config);
        } else if ($type == "csv") {
            Exporter::export(ExporterConst::CSV, $config);
        }
    }

    public function ajaxLeadMailUnique()
    {
        $email = $_GET['data'];
        $id = $_GET['id'] ?? 0;
        $query = "SELECT email FROM leads WHERE email='$email' ";
        if (!empty($id)) {
            $query = "SELECT email FROM leads WHERE email='$email' AND lead_id<>$id";
        }
        $check = $this->db->query($query)->getRow();
        $response = array();
        if (empty($check)) {
            $response['valid'] = 1;
        } else {
            $response['valid'] = 0;
            $response['msg'] = "Email ID already exists";
        }
        return $this->response->setJSON($response);
    }

    public function ajaxLeadsResponse()
    {
        $response = array();
        $response = $this->LeadsModel->get_leads_datatable();
        return $this->response->setJSON($response);
    }

    public function leadview($lead_id)
    {
        $this->data['lead_id'] = $lead_id;
        $this->data['lead'] = $this->LeadsModel->get_lead_by_id($lead_id);
        if (empty($this->data['lead'])) {
            $this->session->setFlashdata("op_error", "Lead not found");
            return $this->response->redirect(url_to("erp.crm.leads"));
        }
        $this->data['leadstatus'] = $this->LeadsModel->get_lead_status_by_idx($this->data['lead']->status);
        $this->data['leadstatus_bg'] = $this->LeadsModel->get_lead_status_bg_by_idx($this->data['lead']->status);
        $this->data['attachments'] = $this->AttachmentModel->get_attachments("lead", $lead_id);
        $this->data['notify_datatable_config'] = $this->NotificationModel->get_dtconfig_notify();
        $this->data['customer_groups'] = $this->ErpGroupsModel->get_customer_groups();
        $this->data['customfields'] = $this->CustomFieldModel->get_custom_fields_for_form("customer");
        $this->data['customfield_chkbx_counter'] = $this->CustomFieldModel->get_checkbox_counter();
        $this->data['page'] = "crm/leadview";
        $this->data['menu'] = "crm";
        $this->data['submenu'] = "lead";
        return view("erp/index", $this->data);
    }

    public function uploadLeadattachment()
    {
        $id = $this->request->getGet("id") ?? 0;
        $response = array();
        if (!empty($_FILES['attachment']['name'])) {
            $response = $this->AttachmentModel->upload_attachment("lead", $id);
        } else {
            $response['error'] = 1;
            $response['reason'] = "No file to upload";
        }
        return $this->response->setJSON($response);
    }

    public function leadDeleteattachment()
    {
        $attach_id = $this->request->getGet("id") ?? 0;
        $response = array();
        $response = $this->AttachmentModel->delete_attachment("lead", $attach_id);
        return $this->response->setJSON($response);
    }

    // public function leadnotifyAdd($lead_id)
    // {
    //     $post = [
    //         'notify_title' => $this->request->getPost("notify_title"),
    //         'notify_desc' => $this->request->getPost("notify_desc"),
    //         'notify_to' => $this->request->getPost("notify_to"),
    //         'notify_at' => $this->request->getPost("notify_at"),
    //         'notify_email' => $this->request->getPost("notify_email"),
    //     ];

    //     $notify_id = $this->request->getPost("notify_id");
    //     if ($this->request->getPost("notify_title")) {
    //         $this->NotificationModel->insert_update_lead_notify($lead_id, $notify_id, $post);
    //     }
    //     return $this->response->redirect(url_to("erp.crm.leadview", $lead_id));
    // }


    // public function leadnotify($lead_id)
    // {
    //     $post = [
    //         'notify_title' => $this->request->getPost("notify_title"),
    //         'notify_desc' => $this->request->getPost("notify_desc"),
    //         'notify_to' => $this->request->getPost("notify_to"),
    //         'notify_at' => $this->request->getPost("notify_at"),
    //         'notify_email' => $this->request->getPost("notify_email"),
    //     ];
    //     $notify_id = $this->request->getPost("notify_id");
    //     if ($this->request->getPost("notify_title")) {
    //         $this->NotificationModel->insert_update_lead_notify($lead_id);
    //     }
    //     return $this->response->redirect(url_to("erp.crm.leadview", $lead_id));
    // }

    public function leadnotify($lead_id)
    {
        $post = [
            'notify_title' => $this->request->getPost("notify_title"),
            'notify_desc' => $this->request->getPost("notify_desc"),
            'notify_to' => $this->request->getPost("notify_to"),
            'notify_at' => $this->request->getPost("notify_at"),
            'notify_email' => $this->request->getPost("notify_email") ?? 0,
        ];
        $notify_id = $this->request->getPost("notify_id");
        if ($this->request->getPost("notify_title")) {
            $this->LeadsModel->insert_update_lead_notify($lead_id, $notify_id, $post);
        }
        return $this->response->redirect(url_to("erp.crm.leadview", $lead_id));
    }


    public function leadnotifydelete($lead_id, $notify_id)
    {
        $this->NotificationModel->delete_lead_notify($lead_id, $notify_id);
        return $this->response->redirect(url_to("erp.crm.leadview", $lead_id));
    }

    public function ajaxLeadnotifyResponse()
    {
        $lead_id = $this->request->getGet("leadid");
        $response = array();
        $response = $this->NotificationModel->get_leadnotify_datatable($lead_id);
        return $this->response->setJSON($response);
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
        return $this->response->setJSON($response);
    }

    public function leadNotifyExport()
    {
        $lead_id = $this->request->getGet("leadid");
        $type = $_GET['export'];
        $config = array();
        $config['title'] = "Lead Notification";
        if ($type == "pdf") {
            $config['column'] = array("Title", "Description", "Notify At", "Notify Email", "Notified", "Notify To");
        } else {
            $config['column'] = array("Title", "Description", "Notify At", "Notify Email", "Notified", "Notify To");
            $config['column_data_type'] = array(ExporterConst::E_STRING, ExporterConst::E_STRING, ExporterConst::E_STRING, ExporterConst::E_STRING, ExporterConst::E_STRING, ExporterConst::E_STRING);
        }
        $config['data'] = $this->NotificationModel->get_leadnotify_export($type, $lead_id);
        if ($type == "pdf") {
            Exporter::export(ExporterConst::PDF, $config);
        } else if ($type == "excel") {
            Exporter::export(ExporterConst::EXCEL, $config);
        } else if ($type == "csv") {
            Exporter::export(ExporterConst::CSV, $config);
        }
    }

    public function convertLeadCustomer($leadid)
    {
        if ($this->LeadsModel->convert_lead_customer($leadid)) {
            return $this->response->redirect(url_to("erp.crm.customers"));
        } else {
            return $this->response->redirect(url_to("erp.crm.leadview", $leadid));
        }
    }

    //Leads Delete Implemeted Thamizh

    public function leaddelete($lead_id)
    {
        $lead = $this->LeadsModel->get_crm_leads_by_id($lead_id);

        if (empty($lead)) {
            session()->setFlashdata("op_error", "Leads Not Found");
            return $this->response->redirect(url_to('erp.crm.leads'));
        }

        $deleted = $this->LeadsModel->delete_leads($lead_id);

        if ($deleted) {
            // Leads successfully deleted
            session()->setFlashdata("op_success", "Leads successfully deleted");
        } else {
            // Failed to delete Leads
            session()->setFlashdata("op_error", "Leads failed to delete");
        }

        return $this->response->redirect(url_to('erp.crm.leads'));
    }

    public function leadedit($lead_id)
    {
        $post = [

            'lead_source' => $this->request->getPost("lead_source"),
            'lead_status' => $this->request->getPost("lead_status"),
            'lead_assigned_to' => $this->request->getPost("lead_assigned_to"),
            'name' => $this->request->getPost("name"),
            'position' => $this->request->getPost("position"),
            'address' => $this->request->getPost("address"),
            'state' => $this->request->getPost("state"),
            'city' => $this->request->getPost("city"),
            'country' => $this->request->getPost("country"),
            'zip' => $this->request->getPost("zip"),
            'website' => $this->request->getPost("website"),
            'company' => $this->request->getPost("company"),
            'description' => $this->request->getPost("description"),
            'remarks' => $this->request->getPost("remarks"),
            'email' => $this->request->getPost("email"),
            'phone' => $this->request->getPost("phone"),
        ];
        if ($this->request->getPost("lead_source")) {
            if ($this->LeadsModel->update_lead($lead_id, $post)) {
                return $this->response->redirect(url_to("erp.crm.leads"));
            } else {
                return $this->response->redirect(url_to("erp.crm.leadedit", $lead_id));
            }
        }
        $this->data['lead_status'] = $this->LeadsModel->get_lead_status();
        $this->data['lead_source'] = $this->LeadSourceModel->get_lead_source();
        $this->data['lead_id'] = $lead_id;
        $this->data['lead'] = $this->LeadsModel->get_lead_by_id($lead_id);
        if (empty($this->data['lead'])) {
            $this->session->setFlashdata("op_error", "Lead not found");
            return $this->response->redirect(url_to("erp.crm.leads"));
        }
        $this->data['leadstatus'] = $this->LeadsModel->get_lead_status_by_idx($this->data['lead']->status);

        $this->data['page'] = "crm/leadedit";
        $this->data['menu'] = "crm";
        $this->data['submenu'] = "lead";

        return view("erp/index", $this->data);
    }

    public function leadImport()
    {
        $importer = Importer::init(AbstractImporter::LEAD, AbstractImporter::CSV);
        if (!empty($_FILES['csvfile']['name'])) {
            if (strtolower(pathinfo($_FILES['csvfile']['name'], PATHINFO_EXTENSION)) == "csv") {
                $path = get_tmp_path() . get_rand_str() . ".csv";
                if (move_uploaded_file($_FILES['csvfile']['tmp_name'], $path)) {

                    $data['source_id'] = $this->request->getPost("lead_source");
                    $data['status'] = $this->request->getPost("lead_status");
                    $data['assigned_to'] = $this->request->getPost("lead_assigned_to");
                    $data['created_at'] = time();
                    $data['created_by'] = get_user_id();

                    $retval = $importer->import($path, $data);
                    $config['title'] = "Lead Import";
                    $config['ref_link'] = "";
                    if ($retval == -1) {
                        $config['log_text'] = "[ Lead failed to import ]";
                        $this->session->setFlashdata("op_error", "Lead failed to import");
                        log_activity($config);
                    } else if ($retval == 0) {
                        $config['log_text'] = "[ Lead partially imported ]";
                        $this->session->setFlashdata("op_success", "Lead partially imported");
                        log_activity($config);
                        return $this->response->redirect(url_to("erp.crm.leads"));
                    } else if ($retval == 1) {
                        $config['log_text'] = "[ Lead successfully imported ]";
                        $this->session->setFlashdata("op_success", "Lead successfully imported");
                        log_activity($config);
                        return $this->response->redirect(url_to("erp.crm.leads"));
                    }
                } else {
                    $this->session->setFlashdata("op_error", "Internal Error while uploading file");
                }
            } else {
                $this->session->setFlashdata("op_error", "File should be in CSV format");
            }
            return $this->response->redirect(url_to("erp.crm.leadimport"));
        }
        $this->data['lead_status'] = $this->LeadsModel->get_lead_status();
        $this->data['lead_source'] = $this->LeadSourceModel->get_lead_source();
        $this->data['columns'] = $importer->get_columns();
        $this->data['page'] = "crm/leadimport";
        $this->data['menu'] = "crm";
        $this->data['submenu'] = "lead";
        return view("erp/index", $this->data);
    }

    public function leadimporttemplate()
    {
        $importer = Importer::init(AbstractImporter::LEAD, AbstractImporter::CSV);
        $importer->download_template();
    }

    public function customers()
    {
        $this->data['customer_groups'] = $this->ErpGroupsModel->get_customer_groups();
        $this->data['datatable_config'] = $this->CustomersModel->get_dtconfig_customers();
        $this->data['page'] = "crm/customers";
        $this->data['menu'] = "crm";
        $this->data['submenu'] = "customer";
        return view("erp/index", $this->data);
    }

    public function ajaxCustomersResponse()
    {
        $response = array();
        $response = $this->CustomersModel->get_customers_datatable();
        return $this->response->setJSON($response);
    }

    public function customeradd()
    {
        $post = [
            'name' => $this->request->getPost("name"),
            'position' => $this->request->getPost("position"),
            'address' => $this->request->getPost("address"),
            'state' => $this->request->getPost("state"),
            'city' => $this->request->getPost("city"),
            'country' => $this->request->getPost("country"),
            'zip' => $this->request->getPost("zip"),
            'website' => $this->request->getPost("website"),
            'company' => $this->request->getPost("company"),
            'description' => $this->request->getPost("description"),
            'remarks' => $this->request->getPost("remarks"),
            'email' => $this->request->getPost("email"),
            'phone' => $this->request->getPost("phone"),
            'fax_number' => $this->request->getPost("fax_number"),
            'office_number' => $this->request->getPost("office_number"),
            'gst' => $this->request->getPost("gst"),
        ];
        if ($this->request->getPost("name")) {
            if ($this->CustomersModel->insert_customer($post)) {
                return $this->response->redirect(url_to("erp.crm.customers"));
            } else {
                return $this->response->redirect(url_to("erp.crm.customeradd"));
            }
        }
        $this->data['customer_groups'] = $this->ErpGroupsModel->get_customer_groups();
        $this->data['customfields'] = $this->CustomFieldModel->get_custom_fields_for_form("customer");
        $this->data['customfield_chkbx_counter'] = $this->CustomFieldModel->get_checkbox_counter();
        $this->data['page'] = "crm/customeradd";
        $this->data['menu'] = "crm";
        $this->data['submenu'] = "customer";
        return view("erp/index", $this->data);
    }

    public function ajaxCustomerMailUnique()
    {
        $email = $_GET['data'];
        $id = $_GET['id'] ?? 0;
        $query = "SELECT email FROM customers WHERE email='$email' ";
        if (!empty($id)) {
            $query = "SELECT email FROM customers WHERE email='$email' AND cust_id<>$id";
        }
        $check = $this->db->query($query)->getRow();
        $response = array();
        if (empty($check)) {
            $response['valid'] = 1;
        } else {
            $response['valid'] = 0;
            $response['msg'] = "Email ID already exists";
        }
        return $this->response->setJSON($response);
    }

    public function customeredit($cust_id)
    {
        $post = [
            'name' => $this->request->getPost("name"),
            'position' => $this->request->getPost("position"),
            'address' => $this->request->getPost("address"),
            'state' => $this->request->getPost("state"),
            'city' => $this->request->getPost("city"),
            'country' => $this->request->getPost("country"),
            'zip' => $this->request->getPost("zip"),
            'website' => $this->request->getPost("website"),
            'company' => $this->request->getPost("company"),
            'description' => $this->request->getPost("description"),
            'remarks' => $this->request->getPost("remarks"),
            'email' => $this->request->getPost("email"),
            'phone' => $this->request->getPost("phone"),
            'fax_number' => $this->request->getPost("fax_number"),
            'office_number' => $this->request->getPost("office_number"),
            'gst' => $this->request->getPost("gst"),
        ];

        if ($this->request->getPost("name")) {
            if ($this->CustomersModel->update_customer($cust_id, $post)) {
                // exit();
                return $this->response->redirect(url_to("erp.crm.customers"));
            } else {
                // exit();
                return $this->response->redirect(url_to("erp.crm.customeredit", $cust_id));
            }
        }
        $this->data['customer_groups'] = $this->ErpGroupsModel->get_customer_groups();
        $this->data['customfields'] = $this->CustomFieldModel->get_custom_fields_for_edit("customer", $cust_id);
        $this->data['customfield_chkbx_counter'] = $this->CustomFieldModel->get_checkbox_counter();
        $this->data['customer_id'] = $cust_id;
        $this->data['customer'] = $this->CustomersModel->get_customer_by_id($cust_id);
        if (empty($this->data['customer'])) {
            $this->session->setFlashdata("op_error", "Customer not found");
            return $this->response->redirect(url_to("erp.crm.customers"));
        }
        $this->data['page'] = "crm/customeredit";
        $this->data['menu'] = "crm";
        $this->data['submenu'] = "customer";
        return view("erp/index", $this->data);
    }

    public function customerview($cust_id)
    {
        $this->data['customer'] = $this->CustomersModel->get_customer_for_view($cust_id);
        $this->data['customer_id'] = $cust_id;
        $this->data['custom_field_values'] = $this->CustomFieldModel->get_custom_fields_for_view('customer', $cust_id);
        $this->data['contact_datatable_config'] = $this->CustomerContactsModel->get_dtconfig_contacts();
        $this->data['shipping_datatable_config'] = $this->CustomerShippingAddrModel->get_dtconfig_shipping();
        $this->data['billing_datatable_config'] = $this->CustomerBillingAddrsModel->get_dtconfig_billing();
        $this->data['attachments'] = $this->AttachmentModel->get_attachments("customer", $cust_id);
        $this->data['notify_datatable_config'] = $this->NotificationModel->get_dtconfig_notify();
        $this->data['get_billing_data'] = $this->CustomerBillingAddrsModel->getBillingData($cust_id);
        $this->data['get_shipping_data'] = $this->CustomerShippingAddrModel->getShippingData($cust_id);
        // return var_dump($this->data['get_shipping_data']);
        $this->data['page'] = "crm/customerview";
        $this->data['menu'] = "crm";
        $this->data['submenu'] = "customer";
        return view("erp/index", $this->data);
    }

    public function uploadCustomerattachment()
    {
        $id = $this->request->getGet("id") ?? 0;
        $response = array();
        if (!empty($_FILES['attachment']['name'])) {
            $response = $this->AttachmentModel->upload_attachment("customer", $id);
        } else {
            $response['error'] = 1;
            $response['reason'] = "No file to upload";
        }
        return $this->response->setJSON($response);
    }

    public function customerDeleteattachment()
    {
        $attach_id = $this->request->getGet("id") ?? 0;
        $response = array();
        $response = $this->AttachmentModel->delete_attachment("customer", $attach_id);
        return $this->response->setJSON($response);
    }

    public function customernotify($cust_id)
    {
        $post = [
            'notify_title' => $this->request->getPost("notify_title"),
            'notify_desc' => $this->request->getPost("notify_desc"),
            'notify_to' => $this->request->getPost("notify_to"),
            'notify_at' => $this->request->getPost("notify_at"),
            'notify_email' => $this->request->getPost("notify_email") ?? 0,
        ];

        $notify_id = $this->request->getPost("notify_id");
        if ($this->request->getPost("notify_title")) {
            $this->NotificationModel->insert_update_customer_notify($cust_id, $notify_id, $post);
        }
        return $this->response->redirect(url_to("erp.crm.customerview", $cust_id));
    }

    public function customernotifydelete($cust_id, $notify_id)
    {
        $this->NotificationModel->delete_customer_notify($cust_id, $notify_id);
        return $this->response->redirect(url_to("erp.crm.customerview", $cust_id));
    }

    public function ajaxCustomernotifyResponse()
    {
        $cust_id = $this->request->getGet("custid");
        $response = array();
        $response = $this->NotificationModel->get_customernotify_datatable($cust_id);
        return $this->response->setJSON($response);
    }

    public function customerNotifyExport()
    {
        $cust_id = $this->request->getGet("custid");
        $type = $_GET['export'];
        $config = array();
        $config['title'] = "Customer Notification";
        if ($type == "pdf") {
            $config['column'] = array("Title", "Description", "Notify At", "Notify Email", "Notified", "Notify To");
        } else {
            $config['column'] = array("Title", "Description", "Notify At", "Notify Email", "Notified", "Notify To");
            $config['column_data_type'] = array(ExporterConst::E_STRING, ExporterConst::E_STRING, ExporterConst::E_STRING, ExporterConst::E_STRING, ExporterConst::E_STRING, ExporterConst::E_STRING);
        }
        $config['data'] = $this->NotificationModel->get_customernotify_export($type, $cust_id);
        if ($type == "pdf") {
            Exporter::export(ExporterConst::PDF, $config);
        } else if ($type == "excel") {
            Exporter::export(ExporterConst::EXCEL, $config);
        } else if ($type == "csv") {
            Exporter::export(ExporterConst::CSV, $config);
        }
    }


    // IMPLEMENTED By Thamizh
    public function customerdelete($cust_id)
    {
        $customer = $this->CustomersModel->get_crm_customer_by_id($cust_id);

        if (empty($customer)) {
            session()->setFlashdata("op_error", "Leads Not Found");
            return $this->response->redirect(url_to('erp.crm.customers'));
        }

        $deleted = $this->CustomersModel->delete_customer($cust_id);

        if ($deleted) {
            // Leads successfully deleted
            session()->setFlashdata("op_success", "Leads successfully deleted");
        } else {
            // Failed to delete Leads
            session()->setFlashdata("op_error", "Leads failed to delete");
        }

        return $this->response->redirect(url_to('erp.crm.leads'));
    }



    public function customerExport()
    {
        $type = $_GET['export'];
        $config = array();
        $config['title'] = "Customers";
        if ($type == "pdf") {
            $config['column'] = array("Name", "Email", "Phone", "Position", "Company", "GST", "Groups");
        } else {
            $config['column'] = array("Name", "Email", "Phone", "Fax Number", "Office Number", "Position", "Company", "GST", "Groups", "Address", "City", "State", "Country", "Zipcode", "Website");
            $config['column_data_type'] = array(ExporterConst::E_STRING, ExporterConst::E_STRING, ExporterConst::E_STRING, ExporterConst::E_STRING, ExporterConst::E_STRING, ExporterConst::E_STRING, ExporterConst::E_STRING, ExporterConst::E_STRING, ExporterConst::E_STRING, ExporterConst::E_STRING, ExporterConst::E_STRING, ExporterConst::E_STRING, ExporterConst::E_STRING, ExporterConst::E_STRING, ExporterConst::E_STRING);
        }
        $config['data'] = $this->CustomersModel->get_Customers_export($type);
        if ($type == "pdf") {
            Exporter::export(ExporterConst::PDF, $config);
        } else if ($type == "excel") {
            Exporter::export(ExporterConst::EXCEL, $config);
        } else if ($type == "csv") {
            Exporter::export(ExporterConst::CSV, $config);
        }
    }

    public function customerImport()
    {
        $importer = Importer::init(AbstractImporter::CUSTOMER, AbstractImporter::CSV);
        if (!empty($_FILES['csvfile']['name'])) {
            if (strtolower(pathinfo($_FILES['csvfile']['name'], PATHINFO_EXTENSION)) == "csv") {
                $path = get_tmp_path() . get_rand_str() . ".csv";
                if (move_uploaded_file($_FILES['csvfile']['tmp_name'], $path)) {

                    $data['created_at'] = time();
                    $data['created_by'] = get_user_id();

                    $retval = $importer->import($path, $data);
                    $config['title'] = "Customer Import";
                    $config['ref_link'] = "";
                    if ($retval == -1) {
                        $config['log_text'] = "[ Customer failed to import ]";
                        $this->session->setFlashdata("op_error", "Customer failed to import");
                        log_activity($config);
                    } else if ($retval == 0) {
                        $config['log_text'] = "[ Customer partially imported ]";
                        $this->session->setFlashdata("op_success", "Customer partially imported");
                        log_activity($config);
                        return $this->response->redirect(url_to("erp.crm.customers"));
                    } else if ($retval == 1) {
                        $config['log_text'] = "[ Customer successfully imported ]";
                        $this->session->setFlashdata("op_success", "Customer successfully imported");
                        log_activity($config);
                        return $this->response->redirect(url_to("erp.crm.customers"));
                    }
                } else {
                    $this->session->setFlashdata("op_error", "Internal Error while uploading file");
                }
            } else {
                $this->session->setFlashdata("op_error", "File should be in CSV format");
            }
            return $this->response->redirect(url_to("erp.crm.customerimport"));
        }

        $this->data['customer_groups'] = $this->ErpGroupsModel->get_customer_groups();
        $this->data['customfields'] = $this->CustomFieldModel->get_custom_fields_for_form("customer");
        $this->data['customfield_chkbx_counter'] = $this->CustomFieldModel->get_checkbox_counter();
        $this->data['columns'] = $importer->get_columns();
        $this->data['page'] = "crm/customerimport";
        $this->data['menu'] = "crm";
        $this->data['submenu'] = "customer";
        return view("erp/index", $this->data);
    }

    public function customerimporttemplate()
    {
        $importer = Importer::init(AbstractImporter::CUSTOMER, AbstractImporter::CSV);
        $importer->download_template();
    }

    public function ajaxContactMailUnique()
    {
        $email = $_GET['data'];
        $id = $_GET['cust_id'];
        $contact_id = $_GET['contact_id'] ?? 0;
        $query = "SELECT email FROM customer_contacts WHERE email='$email' AND cust_id=$id ";
        if (!empty($contact_id)) {
            $query = "SELECT email FROM customer_contacts WHERE email='$email' AND cust_id=$id AND contact_id<>$contact_id";
        }
        $check = $this->db->query($query)->getRow();
        $response = array();
        if (empty($check)) {
            $response['valid'] = 1;
        } else {
            $response['valid'] = 0;
            $response['msg'] = "Email ID already exists";
        }
        return $this->response->setJSON($response);
    }

    //updated by udhaya kumar R
    public function customercontact($cust_id)
    {
        $logger = \Config\Services::logger();

        // var_dump($this->request->getPost());
        // exit;

        $contact_id = $this->request->getPost("contact_id");
        $post = [
            'firstname' => $this->request->getPost("firstname"),
            'lastname' => $this->request->getPost("lastname"),
            'email' => $this->request->getPost("email"),
            'phone' => $this->request->getPost("phone"),
            'position' => $this->request->getPost("position"),
            'password' => $this->request->getPost("password") ?? '',
            'primary_contact' => $this->request->getPost("Primary_contact"),
            'hidden_primary_contact' => $this->request->getPost("hidden_primary_contact")
        ];
        // var_dump($this->request->getPost("Primary_contact"));
        // exit;
        $permission = $this->request->getPost("permission") ?? [];
        $permission = json_encode($permission);
        $post["permission"] = $permission;

        // var_dump($post['hidden_primary_contact']);
        // exit;

        if ($post['hidden_primary_contact']) {
            $post['primary_contact'] = 1;
        } else {
            $post['primary_contact'] = 0;
        }

        if (!empty($post['password'])) {
            $post['password'] = password_hash($post['password'], PASSWORD_DEFAULT);
        }

        $profile = $this->request->getFile('profile_image') ?? '';

        if (!empty($profile) && $profile->isValid() && !$profile->hasMoved()) {

            $image_name = $profile->getname();
            $path = get_attachment_path("customer") . $image_name;

            // $logger->info($image_name);  
            // $logger->info($path);  

            if (file_exists($path)) {
                // $logger->info('2');  
                session()->setFlashdata("op_error", "Image already Exist!");
                return $this->response->redirect(url_to("erp.crm.customerview", $cust_id));
            }

            try {
                $profile->move(FCPATH . 'uploads/customer', $image_name);
                $post['profile_image'] = $image_name;

            } catch (HTTPException $ex) {

            }
        }

        if ($this->request->getPost("firstname")) {
            $this->CustomerContactsModel->insert_update_customer_contact($cust_id, $post, $contact_id);
        }
        return $this->response->redirect(url_to("erp.crm.customerview", $cust_id));
    }

    public function ajaxCustomercontactResponse()
    {
        $cust_id = $this->request->getGet("custid");
        $response = array();
        $response = $this->CustomerContactsModel->get_customercontact_datatable($cust_id);
        return $this->response->setJSON($response);
    }

    public function ajaxFetchContact($contact_id)
    {
        $response = array();
        $query = "SELECT contact_id,firstname,lastname,email,phone,profile_image,primary_contact,position,permission FROM customer_contacts WHERE contact_id=$contact_id";
        $result = $this->db->query($query)->getRow();
        if (!empty($result)) {
            $response['error'] = 0;
            $response['data'] = $result;
        } else {
            $response['error'] = 1;
        }
        return $this->response->setJSON($response);
    }

    public function ajaxContactActive($contact_id)
    {
        $response = array();
        $query = "UPDATE customer_contacts SET active=active^1 WHERE contact_id=$contact_id";
        $this->db->query($query);
        if ($this->db->affectedRows() > 0) {
            $response['error'] = 0;
        } else {
            $response['error'] = 1;
        }
        return $this->response->setJSON($response);
    }

    //Implemented By Thamizh
    public function customercontactdelete($cust_id, $contact_id)
    {
        $contact = $this->CustomerContactsModel->get_customercontact_by_id($cust_id);

        if (empty($contact)) {
            session()->setFlashdata("op_error", "Contact Not Found");
            return $this->response->redirect(url_to('erp.crm.customers'));
        }

        $deleted = $this->CustomerContactsModel->delete_contact($cust_id);

        if ($deleted) {
            // Contact successfully deleted
            session()->setFlashdata("op_success", "Contact successfully deleted");
        } else {
            // Failed to delete Contact
            session()->setFlashdata("op_error", "Contact failed to delete");
        }

        return $this->response->redirect(url_to('erp.crm.customers'));
    }

    public function customerContactExport()
    {
        $type = $_GET['export'];
        $config = array();
        $config['title'] = "Customer Contacts";
        if ($type == "pdf") {
            $config['column'] = array("Firstname", "Lastname", "Email", "Phone", "Position", "Active");
        } else {
            $config['column'] = array("Firstname", "Lastname", "Email", "Phone", "Position", "Active");
            $config['column_data_type'] = array(ExporterConst::E_STRING, ExporterConst::E_STRING, ExporterConst::E_STRING, ExporterConst::E_STRING, ExporterConst::E_STRING, ExporterConst::E_STRING);
        }
        $config['data'] = $this->CustomerContactsModel->get_customer_contacts_export($type);
        if ($type == "pdf") {
            Exporter::export(ExporterConst::PDF, $config);
        } else if ($type == "excel") {
            Exporter::export(ExporterConst::EXCEL, $config);
        } else if ($type == "csv") {
            Exporter::export(ExporterConst::CSV, $config);
        }
    }

    public function customershippingaddr($cust_id)
    {
        $post = [
            'address' => $this->request->getPost("address"),
            'city' => $this->request->getPost("city"),
            'state' => $this->request->getPost("state"),
            'country' => $this->request->getPost("country"),
            'zip' => $this->request->getPost("zip"),
        ];
        $shippingaddr_id = $this->request->getPost("shippingaddr_id") ?? 0;
        if ($this->request->getPost("address")) {
            $this->CustomerShippingAddrModel->insert_update_customer_shippingaddr($cust_id, $shippingaddr_id, $post);
        }
        return $this->response->redirect(url_to("erp.crm.customerview", $cust_id));
    }

    public function ajaxCustomershippingResponse()
    {
        $cust_id = $this->request->getGet("custid");
        $response = array();
        $response = $this->CustomerShippingAddrModel->get_customershipping_datatable($cust_id);
        return $this->response->setJSON($response);
    }

    public function ajaxFetchShipping($shippingaddr_id)
    {
        $response = array();
        $query = "SELECT shippingaddr_id,address,city,state,country,zipcode FROM customer_shippingaddr WHERE shippingaddr_id=$shippingaddr_id";
        $result = $this->db->query($query)->getRow();
        if (!empty($result)) {
            $response['error'] = 0;
            $response['data'] = $result;
        } else {
            $response['error'] = 1;
        }
        return $this->response->setJSON($response);
    }

    //NOT IMPLEMENTED   
    public function customershippingdelete($custId, $shippingaddr_id)
    {
        $deletedRows = $this->CustomerShippingAddrModel->deleteByCustId($custId, $shippingaddr_id);
        return $this->response->redirect(url_to("erp.crm.customerview", $custId));
    }

    public function customerShippingExport()
    {
        $cust_id = $this->request->getGet("custid");
        $type = $_GET['export'];
        $config = array();
        $config['title'] = "Customer Shipping Address";
        if ($type == "pdf") {
            $config['column'] = array("Address", "City", "State", "Country", "Zipcode");
        } else {
            $config['column'] = array("Address", "City", "State", "Country", "Zipcode");
            $config['column_data_type'] = array(ExporterConst::E_STRING, ExporterConst::E_STRING, ExporterConst::E_STRING, ExporterConst::E_STRING, ExporterConst::E_STRING);
        }
        $config['data'] = $this->CustomerShippingAddrModel->get_customer_shipping_export($type, $cust_id);
        if ($type == "pdf") {
            Exporter::export(ExporterConst::PDF, $config);
        } else if ($type == "excel") {
            Exporter::export(ExporterConst::EXCEL, $config);
        } else if ($type == "csv") {
            Exporter::export(ExporterConst::CSV, $config);
        }
    }

    //New Implementation Billing Address

    public function ajaxCustomerbillingResponse()
    {
        $cust_id = $this->request->getGet("custid");
        $response = array();
        $response = $this->CustomerBillingAddrsModel->get_customerbilling_datatable($cust_id);
        return $this->response->setJSON($response);
    }

    public function ajaxFetchBilling($billingaddr_id)
    {
        $response = array();
        $query = "SELECT billingaddr_id, address, city, state, country, zipcode FROM customer_billingaddr WHERE billingaddr_id=$billingaddr_id";
        $result = $this->db->query($query)->getRow();
        if (!empty($result)) {
            $response['error'] = 0;
            $response['data'] = $result;
        } else {
            $response['error'] = 1;
        }
        return $this->response->setJSON($response);
    }

    public function customerbillingaddr($cust_id)
    {
        $post = [
            'address' => $this->request->getPost("address"),
            'city' => $this->request->getPost("city"),
            'state' => $this->request->getPost("state"),
            'country' => $this->request->getPost("country"),
            'zip' => $this->request->getPost("zip"),
        ];
        $billingaddr_id = $this->request->getPost("billingaddr_id") ?? 0;
        if ($this->request->getPost("address")) {
            $this->CustomerBillingAddrsModel->insert_update_customer_billingaddr($cust_id, $billingaddr_id, $post);
        }
        return $this->response->redirect(url_to("erp.crm.customerview", $cust_id));
    }

    public function customerBillingExport()
    {
        $cust_id = $this->request->getGet("custid");
        $type = $_GET['export'];
        $config = array();
        $config['title'] = "Customer Billing Address";
        if ($type == "pdf") {
            $config['column'] = array("Address", "City", "State", "Country", "Zipcode");
        } else {
            $config['column'] = array("Address", "City", "State", "Country", "Zipcode");
            $config['column_data_type'] = array(ExporterConst::E_STRING, ExporterConst::E_STRING, ExporterConst::E_STRING, ExporterConst::E_STRING, ExporterConst::E_STRING);
        }
        $config['data'] = $this->CustomerBillingAddrsModel->get_customer_billing_export($type, $cust_id);
        if ($type == "pdf") {
            Exporter::export(ExporterConst::PDF, $config);
        } else if ($type == "excel") {
            Exporter::export(ExporterConst::EXCEL, $config);
        } else if ($type == "csv") {
            Exporter::export(ExporterConst::CSV, $config);
        }
    }

    //NOT IMPLEMENTED
    public function customerbillingdelete($custId, $billingaddr_id)
    {
        $deletedRows = $this->CustomerBillingAddrsModel->deleteByCustId($custId, $billingaddr_id);
        return $this->response->redirect(url_to("erp.crm.customerview", $custId));
    }


    public function marketing()
    {
        $this->data['marketing_products'] = $this->MarketingModel->get_marketing_products();
        $this->data['marketing_datatable_config'] = $this->MarketingModel->get_dtconfig_marketing();
        $this->data['page'] = "crm/marketing";
        $this->data['menu'] = "crm";
        $this->data['submenu'] = "marketing";
        return view("erp/index", $this->data);
    }

    public function ajaxMarketingResponse()
    {
        $response = array();
        $response = $this->MarketingModel->get_marketing_datatable();
        return $this->response->setJSON($response);
    }

    public function marketingadd()
    {
        $post = [
            'name' => $this->request->getPost("name"),
            'amount' => $this->request->getPost("amount"),
            'related_to' => $this->request->getPost("related_to"),
            'related_id' => $this->request->getPost("related_id"),
            'done_by' => $this->request->getPost("done_by"),
            'email' => $this->request->getPost("email"),
            'phone1' => $this->request->getPost("phone1"),
            'phone2' => $this->request->getPost("phone2"),
            'company' => $this->request->getPost("company"),
            'address' => $this->request->getPost("address"),
            'description' => $this->request->getPost("description"),
        ];
        if ($this->request->getPost("name")) {
            if ($this->MarketingModel->insert_marketing($post)) {
                return $this->response->redirect(url_to("erp.crm.marketing"));
            } else {
                return $this->response->redirect(url_to("erp.crm.marketingadd"));
            }
        }
        $this->data['marketing_products'] = $this->MarketingModel->get_marketing_products();
        $this->data['marketing_products_links'] = $this->MarketingModel->get_marketing_products_link();
        $this->data['page'] = "crm/marketingadd";
        $this->data['menu'] = "crm";
        $this->data['submenu'] = "marketing";
        return view("erp/index", $this->data);
    }

    public function ajaxMarketingActive($marketing_id)
    {
        $response = array();
        $query = "UPDATE marketing SET active=active^1 WHERE marketing_id=$marketing_id";
        $this->db->query($query);
        if ($this->db->affectedRows() > 0) {
            $response['error'] = 0;
        } else {
            $response['error'] = 1;
        }
        return $this->response->setJSON($response);
    }

    public function marketingedit($marketing_id)
    {

        $post = [
            'name' => $this->request->getPost("name"),
            'amount' => $this->request->getPost("amount"),
            'related_to' => $this->request->getPost("related_to"),
            'related_id' => $this->request->getPost("related_id"),
            'done_by' => $this->request->getPost("done_by"),
            'email' => $this->request->getPost("email"),
            'phone1' => $this->request->getPost("phone1"),
            'phone2' => $this->request->getPost("phone2"),
            'company' => $this->request->getPost("company"),
            'address' => $this->request->getPost("address"),
            'description' => $this->request->getPost("description"),
        ];
        if ($this->request->getPost("name")) {
            if ($this->MarketingModel->update_marketing($marketing_id, $post)) {
                return $this->response->redirect(url_to("erp.crm.marketing"));
            } else {
                return $this->response->redirect(url_to("erp.crm.marketingedit", $marketing_id));
            }
        }
        $this->data['marketing'] = $this->MarketingModel->get_marketing_by_id($marketing_id);
        if (empty($this->data['marketing'])) {
            $this->session->setFlashdata("op_error", "Marketing not found");
            return $this->response->redirect(url_to("erp.crm.marketing"));
        }
        $this->data['marketing_id'] = $marketing_id;
        $this->data['marketing_products'] = $this->MarketingModel->get_marketing_products();
        $this->data['marketing_products_links'] = $this->MarketingModel->get_marketing_products_link();
        $this->data['page'] = "crm/marketingedit";
        $this->data['menu'] = "crm";
        $this->data['submenu'] = "marketing";
        return view("erp/index", $this->data);
    }

    //NOT IMPLEMENTED
    public function marketingdelete($marketing_id)
    {
        $deletedRows = $this->MarketingModel->marketing_delete($marketing_id);
        return $this->response->redirect(url_to("erp.crm.marketing"));
    }
    public function ajaxMarketingNameUnique()
    {
        $name = $_GET['data'];
        $id = $_GET['id'] ?? 0;
        $query = "SELECT name FROM marketing WHERE name='$name' LIMIT 1";
        if (!empty($id)) {
            $query = "SELECT name FROM marketing WHERE name='$name' AND marketing_id<>$id LIMIT 1";
        }
        $check = $this->db->query($query)->getRow();
        $response = array();
        if (empty($check)) {
            $response['valid'] = 1;
        } else {
            $response['valid'] = 0;
            $response['msg'] = "Name already exists";
        }
        return $this->response->setJSON($response);
    }


    //NOT IMPLEMENTED
    public function ajaxfetchtradinggoods()
    {
        $search = $_GET['search'] ?? "";
        $response = array();
        $response['error'] = 1;
        if (!empty($search)) {
            $query = "SELECT finished_good_id,CONCAT(name,' ',code) AS name FROM finished_goods WHERE name LIKE '%" . $search . "%' OR code LIKE '%" . $search . "%'  LIMIT 6";
            $result = $this->db->query($query)->getResultArray();
            $m_result = array();
            foreach ($result as $r) {
                array_push($m_result, array(
                    "key" => $r['finished_good_id'],
                    "value" => $r['name']
                ));
            }
            $response['error'] = 0;
            $response['data'] = $m_result;
        }
        return $this->response->setJSON($response);
    }

    public function ajaxfetchinventoryservices()
    {
        $search = $_GET['search'] ?? "";
        $response = array();
        $response['error'] = 1;
        if (!empty($search)) {
            $query = "SELECT invent_service_id,CONCAT(name,' ',code) AS name FROM inventory_services WHERE name LIKE '%" . $search . "%' OR code LIKE '%" . $search . "%'  LIMIT 6";
            $result = $this->db->query($query)->getResultArray();
            $m_result = array();
            foreach ($result as $r) {
                array_push($m_result, array(
                    "key" => $r['invent_service_id'],
                    "value" => $r['name']
                ));
            }
            $response['error'] = 0;
            $response['data'] = $m_result;
        }
        return $this->response->setJSON($response);
    }

    public function marketingExport()
    {
        $type = $_GET['export'];
        $config = array();
        $config['title'] = "Marketing List";
        if ($type == "pdf") {
            $config['column'] = array("Name", "Product Type", "Product", "Amount", "Done by", "Active");
        } else {
            $config['column'] = array("Name", "Product Type", "Product", "Amount", "Done by", "Company", "Address", "Email", "Phone 1", "Phone 2", "Description", "Active");
            $config['column_data_type'] = array(ExporterConst::E_STRING, ExporterConst::E_STRING, ExporterConst::E_STRING, ExporterConst::E_STRING, ExporterConst::E_STRING, ExporterConst::E_STRING, ExporterConst::E_STRING, ExporterConst::E_STRING, ExporterConst::E_STRING, ExporterConst::E_STRING, ExporterConst::E_STRING);
        }
        $config['data'] = $this->MarketingModel->get_marketing_export($type);
        if ($type == "pdf") {
            Exporter::export(ExporterConst::PDF, $config);
        } else if ($type == "excel") {
            Exporter::export(ExporterConst::EXCEL, $config);
        } else if ($type == "csv") {
            Exporter::export(ExporterConst::CSV, $config);
        }
    }

    public function request()
    {
        $this->data['request_datatable_config'] = $this->RequestModel->get_dtconfig_request();
        $this->data['page'] = "crm/request";
        $this->data['menu'] = "crm";
        $this->data['submenu'] = "request";
        return view("erp/index", $this->data);
    }

    public function ajax_request_response()
    {
        $response = array();
        $response = $this->RequestModel->get_request_datatable();
        return $this->response->setJSON($response);
    }

    public function requestadd()
    {
        if ($this->request->getPost("request_type")) {
            $requestor = new Requestor($this->request->getPost("request_type"));
            if ($requestor->request(true)) {
                return $this->response->redirect(url_to("erp.crm.request"));
            } else {
                return $this->response->redirect(url_to("erp.crm.requestadd"));
            }
        }
        $requestor = new Requestor();
        $this->data['requesttypes'] = $requestor->getRequestTypes("CRM");
        $this->data['attach_filetypes'] = $requestor->getAllowedFileTypes();
        $this->data['attach_maxfilesize'] = $requestor->getMaxFileSize();
        $this->data['page'] = "crm/requestadd";
        $this->data['menu'] = "crm";
        $this->data['submenu'] = "request";
        return view("erp/index", $this->data);
    }

    //NOT IMPLEMENTED
    public function requestview($request_id)
    {
        $this->data['request_id'] = $request_id;
        $this->data['page'] = "crm/requestview";
        $this->data['menu'] = "crm";
        $this->data['submenu'] = "request";
        return view("erp/index", $this->data);
    }

    //NOT IMPLEMENTED
    public function requestdelete($request_id)
    {
    }

    public function request_export()
    {
        $type = $_GET['export'];
        $config = array();
        $config['title'] = "CRM Requests";
        if ($type == "pdf") {
            $config['column'] = array("From", "To", "Purpose", "Customer", "Status", "Requested On", "Requested By");
        } else {
            $config['column'] = array("From", "To", "Purpose", "Customer", "Status", "Requested On", "Requested By", "Sent in Mail", "Description");
            $config['column_data_type'] = array(ExporterConst::E_STRING, ExporterConst::E_STRING, ExporterConst::E_STRING, ExporterConst::E_STRING, ExporterConst::E_STRING, ExporterConst::E_STRING, ExporterConst::E_STRING, ExporterConst::E_STRING, ExporterConst::E_STRING);
        }
        $config['data'] = $this->RequestModel->get_request_export($type);
        if ($type == "pdf") {
            Exporter::export(ExporterConst::PDF, $config);
        } else if ($type == "excel") {
            Exporter::export(ExporterConst::EXCEL, $config);
        } else if ($type == "csv") {
            Exporter::export(ExporterConst::CSV, $config);
        }
    }

    public function tickets()
    {
        $this->data['ticket_status'] = $this->TicketsModel->get_ticket_status();
        $this->data['ticket_priority'] = $this->TicketsModel->get_ticket_priority();
        $this->data['ticket_datatable_config'] = $this->TicketsModel->get_dtconfig_ticket();
        $this->data['page'] = "crm/tickets";
        $this->data['menu'] = "crm";
        $this->data['submenu'] = "support";
        return view("erp/index", $this->data);
    }

    public function ajaxTicketsResponse()
    {
        $response = array();
        $response = $this->TicketsModel->get_ticket_datatable();
        return $this->response->setJSON($response);
    }

    public function ticketadd()
    {
        $post = [
            'subject' => $this->request->getPost("subject"),
            'priority' => $this->request->getPost("priority"),
            'customer' => $this->request->getPost("customer"),
            'project_id' => $this->request->getPost("project"),
            'assigned_to' => $this->request->getPost("assigned_to"),
            'problem' => $this->request->getPost("problem"),
            'status' => $this->request->getPost("status")
        ];

        if ($this->request->getPost("subject")) {
            if ($this->TicketsModel->insert_ticket($post)) {
                return $this->response->redirect(url_to("erp.crm.tickets"));
            } else {
                return $this->response->redirect(url_to("erp.crm.ticketadd"));
            }
        }
        $this->data['ticket_status'] = $this->TicketsModel->get_ticket_status();
        $this->data['ticket_priority'] = $this->TicketsModel->get_ticket_priority();
        $this->data['page'] = "crm/ticketadd";
        $this->data['menu'] = "crm";
        $this->data['submenu'] = "support";
        return view("erp/index", $this->data);
    }

    public function ticketedit($ticket_id)
    {
        $post = [
            'subject' => $this->request->getPost("subject"),
            'priority' => $this->request->getPost("priority"),
            'customer' => $this->request->getPost("customer"),
            'project_id' => $this->request->getPost("project"),
            'assigned_to' => $this->request->getPost("assigned_to"),
            'problem' => $this->request->getPost("problem"),
            'status' => $this->request->getPost("status")
        ];
        if ($this->request->getPost("subject")) {
            // var_dump($post);
            // exit;
            if ($this->TicketsModel->update_ticket($ticket_id, $post)) {
                return $this->response->redirect(url_to("erp.crm.tickets"));
            } else {
                return $this->response->redirect(url_to("erp.crm.ticketedit", $ticket_id));
            }
        }
        $this->data['ticket'] = $this->TicketsModel->get_ticket_by_id($ticket_id);
        if (empty($this->data['ticket'])) {
            $this->session->setFlashdata("op_error", "Ticket Not Found");
            return $this->response->redirect(url_to("erp.crm.tickets"));
        }
        $this->data['ticket_id'] = $ticket_id;
        $this->data['ticket_priority'] = $this->TicketsModel->get_ticket_priority();
        $this->data['ticket_status'] = $this->TicketsModel->get_ticket_status();
        $this->data['page'] = "crm/ticketedit";
        $this->data['menu'] = "crm";
        $this->data['submenu'] = "support";
        return view("erp/index", $this->data);
    }

    public function ticketexport()
    {
        $type = $_GET["export"];
        $config = array();

        $config["title"] = "Tickets";
        if ($type == "pdf") {
            $config["column"] = array("Subject", "Priority", "Contact Name", "Project Name", "Assigned To", "Status", "Created On");
        } else {
            $config["column"] = array("Subject", "Priority", "Contact Name", "Project Name", "Assigned To", "Status", "Created On");
            $config["column_data_type"] = array(ExporterConst::E_STRING, ExporterConst::E_STRING, ExporterConst::E_STRING, ExporterConst::E_STRING, ExporterConst::E_STRING, ExporterConst::E_STRING, ExporterConst::E_DATETIME);
        }
        $config["data"] = $this->TicketsModel->export();

        if ($type == "pdf") {
            Exporter::export(ExporterConst::PDF, $config);
        } else if ($type == "csv") {
            Exporter::export(ExporterConst::CSV, $config);
        } else if ($type == "excel") {
            Exporter::export(ExporterConst::EXCEL, $config);
        }


    }

    public function ticketview($ticket_id)
    {
        $this->data['ticket'] = $this->TicketsModel->get_ticket_for_view($ticket_id);
        if (empty($this->data['ticket'])) {
            $this->session->setFlashdata("op_error", "Ticket Not Found");
            return $this->response->redirect(url_to("erp.crm.tickets"));
        }
        $this->data['ticket_status'] = $this->TicketsModel->get_ticket_status();
        $this->data['ticket_priority'] = $this->TicketsModel->get_ticket_priority();
        $this->data['ticket_status_bg'] = $this->TicketsModel->get_ticket_status_bg();
        $this->data['ticket_priority_bg'] = $this->TicketsModel->get_ticket_priority_bg();
        $this->data['attachments'] = $this->AttachmentModel->get_attachments("ticket", $ticket_id);
        $this->data['ticket_id'] = $ticket_id;
        $this->data['page'] = "crm/ticketview";
        $this->data['menu'] = "crm";
        $this->data['submenu'] = "support";
        return view("erp/index", $this->data);
    }

    public function uploadTicketattachment()
    {
        $id = $this->request->getGet("id") ?? 0;
        $response = array();
        if (!empty($_FILES['attachment']['name'])) {
            $response = $this->AttachmentModel->upload_attachment("ticket", $id);
        } else {
            $response['error'] = 1;
            $response['reason'] = "No file to upload";
        }
        return $this->response->setJSON($response);
    }

    public function ticketDeleteAttachment()
    {
        $attach_id = $this->request->getGet("id") ?? 0;
        $response = array();
        $response = $this->AttachmentModel->delete_attachment("ticket", $attach_id);
        return $this->response->setJSON($response);
    }

    public function handleticket($ticket_id)
    {
        $this->TicketsModel->handle_ticket($ticket_id);
        return $this->response->redirect(url_to("erp.crm.ticketview", $ticket_id));
    }

    public function ticketsubmit($ticket_id)
    {
        $post = [

            'remarks' => $this->request->getPost("remarks"),
            'status' => $this->request->getPost("tick_status"),
        ];
        $this->TicketsModel->submit_ticket($ticket_id, $post);
        return $this->response->redirect(url_to("erp.crm.ticketview", $ticket_id));
    }

    //NOT IMPLEMENTED
    public function ticketdelete($ticket_id)
    {
        $this->TicketsModel->delete_ticket($ticket_id);
        return $this->response->redirect(url_to("erp.crm.tickets"));
    }

    public function task()
    {
        $this->data['datatable_config'] = $this->TaskModel->get_dtconfig_task();
        $this->data['task_status'] = $this->TaskModel->get_task_status();
        $this->data['taskpriority_status'] = $this->TaskModel->get_taskpriority_status();
        $this->data['page'] = "crm/task";
        $this->data['menu'] = "crm";
        $this->data['submenu'] = "task";
        return view("erp/index", $this->data);
    }

    public function ajaxTaskResponse()
    {
        $response = array();
        $response = $this->TaskModel->get_task_datatable();
        return $this->response->setJSON($response);
    }

    public function taskadd()
    {
        $post = [
            'name' => $this->request->getPost("name"),
            'start_date' => $this->request->getPost("start_date"),
            'due_date' => $this->request->getPost("due_date"),
            'related_to' => $this->request->getPost("related_to"),
            'related_id' => $this->request->getPost("related_id"),
            'status' => $this->request->getPost("status"),
            'priority' => $this->request->getPost("priority"),
            'assignees' => $this->request->getPost("assignees"),
            'followers' => $this->request->getPost("followers"),
            'task_description' => $this->request->getPost("task_description"),
        ];

        if ($this->request->getPost("name")) {
            if ($this->TaskModel->insert_task($post)) {
                return $this->response->redirect(url_to("erp.crm.task"));
            } else {
                return $this->response->redirect(url_to("erp.crm.taskadd"));
            }
        }
        $this->data['task_status'] = $this->TaskModel->get_task_status();
        $this->data['task_priority'] = $this->TaskModel->get_taskpriority_status();
        $this->data['task_related'] = $this->TaskModel->get_taskRelated_status();
        $this->data['page'] = "crm/taskadd";
        $this->data['menu'] = "crm";
        $this->data['submenu'] = "task";
        return view("erp/index", $this->data);
    }


    public function taskview($task_id)
    {
        $this->data['task'] = $this->TaskModel->get_task_by_id($task_id);
        if (empty($this->data['task'])) {
            $this->session->setFlashdata("op_error", "Task Not Found");
            return $this->response->redirect(url_to("erp.crm.task"));
        }
        $this->data['tasks'] = $this->TaskModel->get_tasks_by_id($task_id);
        $this->data['followers'] = $this->TaskModel->get_tasks1_by_id($task_id);
        $relatedOptionValue = (int) $this->data['task']->related_id;
        $relateToOptionValue = $this->data['task']->related_to;

        $this->data['task_status'] = $this->TaskModel->get_task_status();
        $this->data['task_priority'] = $this->TaskModel->get_taskpriority_status();
        $this->data['task_status_bg'] = $this->TaskModel->get_task_status_bg();
        $this->data['task_priority_bg'] = $this->TaskModel->get_task_priority_bg();
        $this->data['relatedDataValue'] = $this->QuotationModel->relatedEditData($relatedOptionValue, $relateToOptionValue);
        // $this->logger->error($this->data['relatedDataValue']);
        $this->data['task_id'] = $task_id;
        $this->data['page'] = "crm/taskview";
        $this->data['menu'] = "crm";
        $this->data['submenu'] = "task";
        return view("erp/index", $this->data);
    }

    public function taskedit($task_id)
    {
        $post = [
            'name' => $this->request->getPost("name"),
            'start_date' => $this->request->getPost("start_date"),
            'due_date' => $this->request->getPost("due_date"),
            'related_to' => $this->request->getPost("related_to"),
            'related_id' => $this->request->getPost("related_id"),
            'status' => $this->request->getPost("status"),
            'priority' => $this->request->getPost("priority"),
            'assignees' => $this->request->getPost("assignees"),
            'followers' => $this->request->getPost("followers"),
            'task_description' => $this->request->getPost("task_description"),
        ];
        if ($this->request->getPost("name")) {
            if ($this->TaskModel->update_task($task_id, $post)) {
                return $this->response->redirect(url_to("erp.crm.task"));
            } else {
                return $this->response->redirect(url_to("erp.crm.taskedit", $task_id));
            }
        }
        $this->data['task'] = $this->TaskModel->get_task_by_id($task_id);
        $this->data['tasks'] = $this->TaskModel->get_tasks_by_id($task_id);
        $this->data['tasks1'] = $this->TaskModel->get_tasks1_by_id($task_id);
        $relatedOptionValue = (int) $this->data['task']->related_id;
        $relateToOptionValue = $this->data['task']->related_to;
        if (empty($this->data['task'])) {
            $this->session->setFlashdata("op_error", "Task Not Found");
            return $this->response->redirect(url_to("erp.crm.task"));
        }
        $this->data['relatedDataValue'] = $this->QuotationModel->relatedEditData($relatedOptionValue, $relateToOptionValue);
        // $this->logger->error($this->data['relatedDataValue']);
        $this->data['task_related'] = $this->TaskModel->get_taskRelated_status();
        $this->data['task_status'] = $this->TaskModel->get_task_status();
        $this->data['task_priority'] = $this->TaskModel->get_taskpriority_status();
        $this->data['task_id'] = $task_id;
        $this->data['page'] = "crm/taskedit";
        $this->data['menu'] = "crm";
        $this->data['submenu'] = "task";
        return view("erp/index", $this->data);
    }
    public function taskDelete($task_id)
    {
        $this->TaskModel->taskDelete($task_id);
        return $this->response->redirect(url_to("erp.crm.task"));
    }

    public function relatedJobTask()
    {
        $data = $this->request->getGet('option');
        $returnData = [];
        if ($data === "lead") {
            $returnData = $this->LeadsModel->getLeadData();
            return $this->response->setJSON($returnData);
        } else if ($data === "project") {
            $returnData = $this->ProjectsModel->getProject();
            return $this->response->setJSON($returnData);
        } else if ($data === "invoice") {
            $returnData = $this->InvoiceModel->getInvoice();
            return $this->response->setJSON($returnData);
        } else if ($data === "customer") {
            $returnData = $this->CustomersModel->getCustomer();
            return $this->response->setJSON($returnData);
        } else if ($data === "estimates") {
            $returnData = $this->EstimatesModel->getEstimates();
            return $this->response->setJSON($returnData);
        } else if ($data === "contract") {
            $returnData = $this->ContractorsModel->getContractors();
            return $this->response->setJSON($returnData);
        } else if ($data === "ticket") {
            $returnData = $this->TicketsModel->getTicket();
            return $this->response->setJSON($returnData);
        } else if ($data === "quotation") {
            $returnData = $this->QuotationModel->getQuotation();
            return $this->response->setJSON($returnData);
        }

        return $this->response->setJSON($returnData);
    }

    public function CRMReport()
    {
        $this->data['leadsSourceStatus'] = $this->LeadsModel->leadsSourceCountStatus();
        $this->data['leadReportDashboard'] = $this->LeadsModel->leadReportDashboard();
        $this->data['taskReportBox'] = $this->TaskModel->taskReportBox();
        $this->data['ticketReportBox'] = $this->TicketsModel->ticketReportBox();
        // $this->logger->error($this->data['taskReportBox']);
        $this->data['leadsCountStatus'] = $this->ProjectsModel->leadsCountStatus();
        $this->data['page'] = "crm/CRMreport";
        $this->data['menu'] = "crm";
        $this->data['submenu'] = "report";
        return view("erp/index", $this->data);
    }

    public function get_customer_project()
    {
        $contact_id = $_POST['cont_id'];

        $contact = $this->CustomerContactsModel->getContactbyId($contact_id);
        if (empty($contact)) {

            return $this->response->setJSON(["success" => false, "message" => "Contact not found"]);
        }

        $cust_id = $contact["cust_id"];

        $project = $this->ProjectsModel->getprojectbycustId($cust_id);

        return $this->response->setJSON(["success" => true, "data" => $project]);

    }

    public function addcomments()
    {
        $ticket_id = $_POST["ticketId"];
        $comment = $_POST["message"];
        $contact_id = get_user_id();
        $data = [
            "ticket_id" => $ticket_id,
            "related_id" => $contact_id,
            "related_type" => "staff",
            "comment" => $comment
        ];

        $result = $this->TicketcommentModel->add_comment($data);

        if ($result) {
            return $this->response->setJSON(["success" => true, "message" => "Comment send Successfully!"]);
        } else {
            return $this->response->setJSON(["success" => false, "message" => "Error while sending Comment!"]);
        }
    }

    public function getclientnamebyid($related_id)
    {
        $query = "SELECT firstname,lastname,profile_image FROM customer_contacts WHERE contact_id=$related_id";
        $result = $this->db->query($query)->getRowArray();
        return $result;
    }
    public function fetchallcomment()
    {
        $ticket_id = $_POST["ticketId"];
        $contact_id = get_user_id();

        $result = $this->TicketcommentModel->fetchallcomment($ticket_id, $contact_id, "staff");

        $new_array = array();

        foreach ($result as $value) {
            if ($value["related_type"] == "client") {
                $data = $this->getclientnamebyid($value["related_id"]);
                $value["related_id"] = $data["firstname"] . " " . $data["lastname"];
                $value["image"] = $data["profile_image"];
            }
            array_push($new_array, $value);
        }

        return $this->response->setJSON(["success" => true, "data" => $new_array]);
    }

}