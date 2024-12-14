<?php

namespace App\Controllers\Erp;

use TCPDF;
use App\Controllers\BaseController;
use App\Libraries\PhpMailerLib;
use App\Models\Erp\ContractModel;
use App\Models\Erp\CustomersModel;
use App\Models\Erp\ContracttypeModel;
use App\Models\Erp\ContractCommendModel;
use App\Models\Erp\ContractRenewelModel;
use PhpParser\Node\Expr\FuncCall;
use App\Models\Erp\TaskModel;
use App\Models\Erp\LeadsModel;
use App\Models\Erp\ProjectsModel;
use App\Models\Erp\Sale\InvoiceModel;
use App\Models\Erp\Sale\EstimatesModel;
use App\Models\Erp\TicketsModel;
use App\Models\Erp\Sale\QuotationModel;
use App\Models\Erp\ContractTaskModel;
use Egulias\EmailValidator\Result\Reason\UnclosedComment;
use CodeIgniter\I18n\Time;
use App\Models\Erp\ErpCompanyInformationModel;
use App\Libraries\Exporter;
use App\Libraries\Exporter\ExporterConst;

use Imagick;

class ContractController extends BaseController
{

    protected $data;
    protected $ContractModel;
    protected $CustomersModel;
    protected $ContracttypeModel;
    protected $ContractCommendModel;
    protected $ContractRenewelModel;
    protected $TaskModel;
    protected $LeadsModel;
    protected $ProjectsModel;
    protected $InvoiceModel;
    protected $EstimatesModel;
    protected $TicketsModel;
    protected $QuotationModel;
    protected $ContractTaskModel;
    protected $db;
    protected $Mailer;
    protected $logger;
    protected $ErpCompanyInformationModel;

    public function __construct()
    {
        $this->ContractModel = new ContractModel();
        $this->CustomersModel = new CustomersModel();
        $this->ContracttypeModel = new ContracttypeModel();
        $this->ContractCommendModel = new ContractCommendModel();
        $this->ContractRenewelModel = new ContractRenewelModel();
        $this->TaskModel = new TaskModel();
        $this->LeadsModel = new LeadsModel();
        $this->ProjectsModel = new ProjectsModel();
        $this->InvoiceModel = new InvoiceModel();
        $this->EstimatesModel = new EstimatesModel();
        $this->TicketsModel = new TicketsModel();
        $this->QuotationModel = new QuotationModel();
        $this->ContractTaskModel = new ContractTaskModel();
        $this->ErpCompanyInformationModel = new ErpCompanyInformationModel();
        $this->logger = \Config\Services::logger();
        $this->db = \Config\Database::connect();
        helper(['erp', 'form', 'pdf']);


        $setting = get_email_settings();

        $config = [
            "host" => $setting["smtp_host"],
            "username" => $setting["smtp_username"],
            "password" => $setting["smtp_password"],
            "port" => $setting["smtp_port"],
            "smtp_secure" => $setting["email_encryption"],
        ];

        $this->Mailer = new PhpMailerLib($config);
    }
    public function index()
    {
        $this->data["menu"] = "contract";
        $this->data["submenu"] = "contract";
        $this->data["page"] = "Contracts/contractview";
        $this->data["dt_config"] = $this->ContractModel->fetch_coloumn_dtconfig();

        return view("erp/index", $this->data);
    }
    public function contractadd()
    {
        $this->data["menu"] = "contract";
        $this->data["submenu"] = "contract";
        $this->data["page"] = "Contracts/contractadd";

        if ($this->request->getPost()) {
            $data = [
                "description" => $this->request->getPost("contractdescription"),
                "subject" => $this->request->getPost("subject"),
                "client" => $this->request->getPost("dropdown_customer"),
                "datestart" => $this->request->getPost("start_date"),
                "dateend" => $this->request->getPost("end_date"),
                "contract_type" => $this->request->getPost("dropdown_contract_type"),
                "addedfrom" =>  get_user_id(),
                "contract_value" => $this->request->getPost("contractType"),
                "trash" => $this->request->getPost("trash"),
                "not_visible_to_client" => $this->request->getPost("hide_from_customer"),
            ];

            if (isset($data["trash"])) {
                $data["trash"] = 1;
            } else {
                $data["trash"] = 0;
            }
            if (isset($data["not_visible_to_client"])) {
                $data["not_visible_to_client"] = 1;
            } else {
                $data["not_visible_to_client"] = 0;
            }


            $result = $this->ContractModel->contractadd($data);

            if ($result) {
                $config['log_text']="[ Contract Adddded Successfully ]";
                $this->session->setFlashdata("op_success", "Contract added successfully");
            } else {
                $config['log_text']="[ Can't Add Contract ]";
                $this->session->setFlashdata("op_error", "error occured");
            }
            $config['title']="Contract";
            $config['ref_link']="";
            log_activity($config);
            $this->response->redirect(url_to("erp.contractview"));
        }

        return view("erp/index", $this->data);
    }

    //returning customers to contract drop down
    public function customer_details_ajax()
    {
        $result = $this->CustomersModel->fetch_customer();
        $data["customers"] = $result;

        return json_encode($data);
    }

    public function contracttype_details_ajax()
    {
        $result = $this->ContracttypeModel->fetch_contract_type();
        $data["contracttype"] = $result;

        return json_encode($data);
    }

    public function contracttype_add()
    {
        $data = [
            "cont_name" => $this->request->getPost("contract_name")
        ];
        $result = $this->ContracttypeModel->add_contract_type($data);

        $config['title']="Contract Type";
        $config['ref_link']="";
        if ($result) {
            $config['log_text']="[ Can't Add Contract Type ]";
            log_activity($config);
            return $this->response->setJSON(["success" => true]);
        } else {
            $config['log_text']="[ Can't Add Contract Type ]";
            log_activity($config);
            return $this->response->setJSON(["success" => false, "error" => "something went wrong!"]);
        }
    }

    public function ajax_data_table_contract()
    {
        $result = array();
        $result = $this->ContractModel->ajaxContractDataTable();

        return $this->response->setJSON($result);
    }

    public function contract_view($id)
    {
        $this->data["contract_details"] = $this->ContractModel->fetch_contract_view($id);
        $this->data["attachments"] = $this->ContractModel->get_attachments("contract_Attachment", $id);
        $this->data["attachments_count"] = $this->ContractModel->get_attachments_count("contract_Attachment", $id);
        return view("erp/Contracts/contractDetailView", $this->data);
    }

    public function contract_commend_add($id)
    {
        $data = [
            'dicussion_note' => $this->request->getPost("comment"),
            'contract_id' => $id,
                'user_id' => get_user_id(),
        ];
        $result = $this->ContractCommendModel->insert_commend($data);

        $config['title']="Contract Comment";
        $config['ref_link']="";
       
        if ($result) {
            $config['log_text']="[ Comment added in  contract successfully ]";
            log_activity($config);
            return $this->response->setJSON(["success" => true]);
        } else {
            $config['log_text']="[ Can't Add Comment in contract ]";
            log_activity($config);
            return $this->response->setJSON(["success" => false]);
        }
    }

    public function contract_commend_update()
    {
        // var_dump($_POST);
        // exit;
        $comment_id = $_POST["id"];

        $data = [
            'dicussion_note' => $this->request->getPost("comment"),
            'user_id' => get_user_id(),
        ];
        $config['title']="Contract Comment";
        $config['ref_link']="";
        if ($this->ContractCommendModel->update_commend($comment_id, $data)) {
            $config['log_text']="[ Contract Comment Updated Successfully ]";
            log_activity($config);
            return $this->response->setJSON(["success" => true]);
        } else {
            $config['log_text']="[ Can't  updated Contract Comment ]";
            log_activity($config);
            return $this->response->setJSON(["success" => false]);
        }

        // return $this->response->setJSON($data);
    }
    //comment delete
    public function contract_commend_delete()
    {
        $id = $_POST["id"];
        $config['title']="Contract Comment";
        $config['ref_link']="";
        if ($this->ContractCommendModel->commend_delete($id)) {
            $config['log_text']="[ Contract Comment Deleted ]";
            log_activity($config);
            return $this->response->setJSON(["success" => true]);
        } else {
            $config['log_text']="[ Can't Delete Contract Comment ]";
            log_activity($config);
            return $this->response->setJSON(["success" => false]);
        }
    }

    public function contract_commend_fetch()
    {
        $id = $_GET["id"];
        $result = $this->ContractCommendModel->fetch_contract_commend($id);
        $data["commends"] = $result;

        return json_encode($data);
    }

    //contract update
    public function contract_update($id)
    {

        $this->data["menu"] = "contract";
        $this->data["submenu"] = "contract";
        $this->data["page"] = "Contracts/contractedit";
        $this->data["dt_config"] = $this->ContractModel->get_datatable_header();
        if ($this->request->getPost()) {
            $data = [
                "description" => $this->request->getPost("contractdescription"),
                "subject" => $this->request->getPost("subject"),
                "client" => $this->request->getPost("dropdown_customer"),
                "datestart" => $this->request->getPost("start_date"),
                "dateend" => $this->request->getPost("end_date"),
                "contract_type" => $this->request->getPost("dropdown_contract_type"),
                "addedfrom" =>  get_user_id(),
                "contract_value" => $this->request->getPost("contractType"),
                "trash" => $this->request->getPost("trash"),
                "not_visible_to_client" => $this->request->getPost("hide_from_customer"),
            ];
            if (isset($data["trash"])) {
                $data["trash"] = 1;
            } else {
                $data["trash"] = 0;
            }
            if (isset($data["not_visible_to_client"])) {
                $data["not_visible_to_client"] = 1;
            } else {
                $data["not_visible_to_client"] = 0;
            }

            $config['title']="Contract Update";
            $config['ref_link']="";
            
            $return = $this->ContractModel->contract_update($id, $data);
            if ($return) {
                $config['log_text']="[ Can't Update Contract ]";
                log_activity($config);
                $this->response->redirect(url_to("erp.contract.update", $id));
                $this->session->setFlashdata("op_success", "Updated");
            } else {
                $config['log_text']="[ Contract Updated successfully ]";
                log_activity($config);
                $this->response->redirect(url_to("erp.contract.update", $id));
                $this->session->setFlashdata("op_error", "Error");
            }
        }
        $this->data["email"] = $this->ContractModel->contract_customer_email($id);
        $this->data["data"] = $this->ContractModel->Contract_Existing_data($id);
        $this->data["t_count"] = $this->ContractTaskModel->Contract_task_count($id);
        $this->data["attachments"] = $this->ContractModel->get_attachments("contract_Attachment", $id);
        $this->data["a_count"] = count($this->ContractModel->get_attachments("contract_Attachment", $id));
        $this->data['task_status'] = $this->TaskModel->get_task_status();
        $this->data['task_priority'] = $this->TaskModel->get_taskpriority_status();
        $this->data['task_related'] = $this->TaskModel->get_taskRelated_status();
        return view('erp/index', $this->data);
    }

    public function contract_delete($id)
    {
        $config['title']="Contract Delete";
        $config['ref_link']="";
        if ($this->ContractModel->delete_contract($id)) {
            $config['log_text']="[ Contract Deleted Successfully ]";
            log_activity($config);
            $this->response->redirect(url_to("erp.contractview", $id));
            $this->session->setFlashdata("op_success", "Deleted");
        } else {
            $config['log_text']="[ Can't Delete Contract ]";
            log_activity($config);
            $this->response->redirect(url_to("erp.contractview", $id));
            $this->session->setFlashdata("op_error", "Error");
        }
    }
    public function send_mail()
    {
        $data = [
            "sender" => get_role_name(),
            "setTo" => $this->request->getPost("customer_email"),
            "setSubject" => $this->request->getPost("customer_email_subject"),
            "setBody" => $this->request->getPost("customer_description")
        ];
        // var_dump($_POST);
        // exit;
        $cc = $this->request->getPost("customer_email_cc");
        if (isset($cc) && $cc != "" && !empty($cc)) {
            $this->Mailer->addCC($data["addCC"]);
        }
        $this->Mailer->setFrom("ashokkumar@qbrainstorm.com", $data["sender"]);
        $this->Mailer->setTo($data["setTo"]);
        $this->Mailer->setSubject($data["setSubject"]);
        $this->Mailer->setBody($data["setBody"]);

        $config['title']="Contract Mail Send";
        $config['ref_link']="";
        
        if ($this->Mailer->sendEmail()) {
            $config['log_text']="[ Contract Mail Sent Successfully ! ]";
            log_activity($config);
            return $this->response->setJSON(["success" => true]);
        } else {
            $config['log_text']="[ Can't Send Contract Mail ]";
            log_activity($config);
            return $this->response->setJSON(["success" => false]);
        }
    }
    public function add_content($id)
    {
        // return $this->response->setJSON(["success" => $id]);
        $data = [
            "content" => $this->request->getPost("content_value")
        ];

        $result = $this->ContractModel->contract_update($id, $data);
        $config['title']="Contract Add Content";
        $config['ref_link']="";
        
        
        if ($result) {
            $config['log_text']="[ Content Added Successfully ]";
            log_activity($config);
            return $this->response->setJSON(["success" => true]);
        } else {
            $config['log_text']="[ Can't Add Content ]";
            log_activity($config);
            return $this->response->setJSON(["success" => false]);
        }
    }
    //ATTACHMENT UPLOAD AND DELETE
    public function uploadContractAttachment()
    {
        $id = $this->request->getGet("id") ?? 0;
        $response = array();
        if (!empty($_FILES['attachment']['name'])) {
            $response = $this->ContractModel->upload_attachment("contract_Attachment", $id);
            $config['title']="Contract Attachment Added";
            $config['ref_link']="";
            $config['log_text']="[ Added attachment in Contract ]";
            log_activity($config);
        } else {
            $response['error'] = 1;
            $response['reason'] = "No file to upload";
        }
        return $this->response->setJSON($response);
    }

    public function ContractDeleteAttachment()
    {
        $attach_id = $this->request->getGet("id") ?? 0;
        $response = array();
        $response = $this->ContractModel->delete_attachment("contract_Attachment", $attach_id);
        $config['title']="Contract Attachment Delete";
        $config['ref_link']="";
        $config['log_text']="[ Contract Attachment Deleted successfully ]";
        log_activity($config);
        return $this->response->setJSON($response);
    }

    public function renewal_contract($id)
    {
        $data_1 = [
            "datestart" => $this->request->getPost("renewal_start_date"),
            "dateend" => $this->request->getPost("renewal_end_date"),
            "contract_value" => $this->request->getPost("renewal-value"),
        ];

        $old_data = $this->ContractModel->get_old_data($id);
        $data_2 = [
            "contractid" => $id,
            "old_start_date" => $old_data["datestart"],
            "new_start_date" => $this->request->getPost("renewal_start_date"),
            "old_end_date" => $old_data["dateend"],
            "new_end_date" => $this->request->getPost("renewal_end_date"),
            "old_value" => $old_data["contract_value"],
            "new_value" => $this->request->getPost("renewal-value"),
            "renewed_by" => get_role_name(),
            "renewed_by_staff_id" => get_user_id(),
        ];

        $result_1 =  $this->ContractModel->renewal_update($id, $data_1);
        $result_2 =  $this->ContractRenewelModel->renewal_add($data_2);

        $config['title']="Contract Renewal";
        $config['ref_link']="";
        
        if ($result_1 && $result_2) {
            
            $this->session->setFlashdata("op_success", "Updated successfully");
            $this->response->redirect(url_to("erp.contract.update", $id));
            $config['log_text']="[ Updated Successfully ]";
            log_activity($config);
        } else {
            $this->session->setFlashdata("op_error", "Error occured");
            $this->response->redirect(url_to("erp.contract.update", $id));
            $config['log_text']="[ Can't Updated Contract ]";
            log_activity($config);
        }
    }
    
    public function get_exist_renewal($id)
    {

        $result = $this->ContractRenewelModel->get_exist_data($id);
        $data["renewal"] = $result;
        return json_encode($data);
    }

    public function renewal_delete()
    {
        $id = $_POST["id"];
        $result = $this->ContractRenewelModel->renewal_delete($id);

        $config['title']="Contract Renewal Delete";
        $config['ref_link']="";
        
        if ($result) {
            $config['log_text']="[ Contract Renewal Delete successfully ]";
            log_activity($config);
            return $this->response->setJSON(["success" => true]);
        } else {
            $config['log_text']="[ Can't Delete Contract Renewal ]";
            log_activity($config);
            return $this->response->setJSON(["success" => false]);
        }
    }

    public function relatedJobTask()
    {
        $returnData = [];
        $returnData = $this->ContractModel->getContract();
        return $this->response->setJSON($returnData);
    }

    public function ajaxfetchemployee()
    {
        $search = $_GET['search'] ?? "";
        $response = array();
        $response['error'] = 1;
        if (!empty($search)) {
            $query = "SELECT employee_id ,CONCAT(first_name,' ',last_name) AS name FROM employees WHERE first_name LIKE '%" . $search . "%' LIMIT 10";
            $result = $this->db->query($query)->getResultArray();
            $m_result = array();
            foreach ($result as $r) {
                array_push($m_result, array(
                    "key" => $r['employee_id'],
                    "value" => $r['name']
                ));
            }
            $response['error'] = 0;
            $response['data'] = $m_result;
        }
        return $this->response->setJSON($response);
    }

    public function contract_task_add($id)
    {
        $data = [
            "contract_id" => $id,
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

        $config['title']="Contract Task Add";
        $config['ref_link']="";
        
        if ($this->ContractTaskModel->task_add($data)) {
            $config['log_text']="[ Added Contract Task Added Successfully ]";
            log_activity($config);
            return $this->response->setJSON(["success" => true]);
        } else {
            $config['log_text']="[ Can't Add Contract Task ]";
            log_activity($config);
            return $this->response->setJSON(["success" => false]);
        }
    }

    public function contract_task_datatable()
    {
        $id = $_GET["id"];
        $result = array();
        $result = $this->ContractTaskModel->get_task_datatable($id);

        return $this->response->setJSON($result);
    }

    public function contract_exist_data()
    {
        $id = $_POST["id"];

        $data["task"] = $this->ContractTaskModel->get_exist_data($id);

        return $this->response->setJSON($data);
    }

    public function contract_task_update()
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

        $config['title']="Contract Attachment Added";
        $config['ref_link']="";
        
        if ($this->ContractTaskModel->contract_task_update($id, $data)) {
            $config['log_text']="[ Added attachment in Contract ]";
            log_activity($config);
            return $this->response->setJSON(["success" => true]);
        } else {
            $config['log_text']="[ Added attachment in Contract ]";
            log_activity($config);
            return $this->response->setJSON(["success" => false]);
        }
    }

    public function contract_task_delete()
    {
        $id = $_POST["id"];

        $config['title']="Contract Task Delete";
        $config['ref_link']="";
        
        if ($this->ContractTaskModel->task_delete($id)) {
            $config['log_text']="[ Task Deleted Successfully ]";
            log_activity($config);
            return $this->response->setJSON(["success" => true]);
        } else {
            $config['log_text']="[ Can't Delete the Task ]";
            log_activity($config);
            return $this->response->setJSON(["success" => false]);
        }
    }
    public function contract_view_sign()
    {
        $currentdate = Time::now()->toDateString();
        $id = $_POST["cont_id"];
        $data = [
            "signed" => 1,
            "signature" => $_POST["sign"],
            "acceptance_firstname" => $_POST["firstName"],
            "acceptance_lastname" => $_POST["lastName"],
            "acceptance_email" => $_POST["email"],
            "acceptance_date" => $currentdate,
            "acceptance_ip" => $this->request->getIPAddress(),
        ];
        
        if ($this->ContractModel->contract_update($id, $data)) {
            return $this->response->setJSON(["success" => true]);
        } else {
            return $this->response->setJSON(["success" => false]);
        }
    }

    public function contract_view_pdf($contract_id, $type)
    {
        $organisation_data = $this->ErpCompanyInformationModel->getAlldata();
        $contractModel = $this->ContractModel->getContractById($contract_id);
        $ContracttypeModel = $this->ContracttypeModel->getContractTypeById($contractModel["contract_type"]);
        $CustomersModel = $this->CustomersModel->getCustomerById($contractModel["client"]);

        $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
        $pdf->SetTitle("");
        $pdf->setPrintHeader(false);

        $pdf->AddPage();

        // Logo on the left
        $companyInfo = '<div><img src="' . base_url() . 'assets/images/' . $organisation_data['company_logo'] . '" style="width:150px;height:50px;" ><br><br>';

        //company name
        $companyInfo .= '<div style =" text-align:left">';
        $companyInfo .= '<span style="font-weight:bold;font-size:15px;">COMPANY </span><br/>';
        $companyInfo .= '<b style="color:#4169E1;">' . $organisation_data['company_name'] . '</b></div></div>';

        //right side approvel 
        $contractsign = '<div style="text-align: right;"><br>';
        $signerfName = $contractModel['acceptance_firstname'];
        $signerlName = $contractModel['acceptance_lastname'];
        $signerEmail = $contractModel['acceptance_email'];
        $signerDate = $contractModel['acceptance_date'];
        $s_date = strtotime($signerDate);
        $sign_date = date('d F Y', $s_date);
        $signerIp = $contractModel['acceptance_ip'];
        if ($contractModel["signed"] > 0) {
            $contractsign .= '<span style="font-weight:bold;font-size:15px;">#SIGNER DETAILS</span><br />';
            $contractsign .= '<div style = "font-family:Times New Roman;">Signer Name :' . $signerfName . ' ' . $signerlName . '<br />';
            $contractsign .= 'Signer Email :' . $signerEmail . '<br />';
            $contractsign .= 'Signer Date :' . $sign_date . '<br />';
            $contractsign .= 'Signer IP :' . $signerIp . '<br /><br>';
            $contractsign .= '<img src="' . base_url('assets/images/signed.png') . '" style="width:65px" /><br></div></div>';
        } else {
            $contractsign .= '<img src="' . base_url('assets/images/notsigned.png') . '" style="width:65px" /><br></div>';
        }


        $table = '<table width="100%" border="0"><tr>';
        $table .= '<td width="50%">' . $companyInfo . '</td>';
        $table .= '<td width="50%">' . $contractsign . '</td>';
        $table .= '</tr></table><br>';

        $pdf->writeHTML($table, true, false, false, false, '');

        //Client details
        $clientInfo = '<div style =" text-align:left"><br>';
        $clientInfo .= '<span style="font-weight:bold;font-size:15px;">#CLIENT DETAILS</span><br />';
        $clientInfo .= '<div style = "font-family:Times New Roman;">Name  : ' . $CustomersModel["company"] . '<br />';
        $clientInfo .= 'Email  : ' . $CustomersModel["email"] . '<br />';
        $clientInfo .= 'phone Number  : ' . $CustomersModel["phone"] . '<br />';
        $clientInfo .= 'Address  : ';
        $clientInfo .= '<span>' . $CustomersModel["address"] . ',<br/>';
        $clientInfo .= '<span>     </span><span>     </span><span>     </span><span>     </span><span>     </span><span></span>' . $CustomersModel["city"] . ',' . $CustomersModel["state"] . ',<br />';
        $clientInfo .= '<span>     </span><span>     </span><span>     </span><span>     </span><span>     </span><span></span>' . $CustomersModel["country"] . ',<br />';
        $clientInfo .= '<span>     </span><span>     </span><span>     </span><span>     </span><span>     </span><span></span>' . $CustomersModel["zip"] . '.</span></div></div><br><br>';

        $pdf->writeHTML($clientInfo, true, false, false, false, '');

        //Contract details
        $start_date = strtotime($contractModel['datestart']);
        $end_date = strtotime($contractModel['dateend']);
        $contract_start_date = date('d F Y', $start_date);
        $contract_end_date = date('d F Y', $end_date);
        $contract_value = number_format($contractModel["contract_value"], 2, '.', ',');
        $rupees = "₹";
        $contractInfo = '<div style =" text-align:left"><br>';
        $contractInfo .= '<span style="font-weight:bold;font-size:15px;">#CONTRACT DETAILS</span><br />';
        $contractInfo .= '<div style = "font-family:Times New Roman;">#Contract Number  : ' . $contractModel["contract_id"] . '<br />';
        $contractInfo .= 'Name  : ' . $contractModel['subject'] . '<br />';
        $contractInfo .= 'Type  : ' . $ContracttypeModel['cont_name'] . '<br />';
        $contractInfo .= 'Start Date  : ' . $contract_start_date  . '<br />';
        $contractInfo .= 'End Date  : ' . $contract_end_date . '<br />';
        $contractInfo .= 'Contract Value  : <span style="font-family:dejavusans;">₹ </span>' . $contract_value  . '<br />';
        if ($contractModel["description"] != null) {
            $contractInfo .= 'Description  : ' . $contractModel["description"] . '<br />';
        }
        if ($contractModel["content"] != null) {
            $contractInfo .= 'Content  : ' . $contractModel["content"] . '<br />';
        }
        $contractInfo .= '</div>';


        $pdf->writeHTML($contractInfo, true, false, false, false, '');



        //signature Detail
        $sign_data = $contractModel["signature"];
        $startPos = strpos($sign_data, '<svg');

        // Find the end position of the </svg> tag
        $endPos = strpos($sign_data, '</svg>') + 6; // Add 6 to include the length of the </svg> tag itself

        // Extract the SVG data
        $svgData = substr($sign_data, $startPos, $endPos - $startPos);


        $signature_detail = '<div style ="text-align:right  margin-top : 400px"><br>';
        if ($contractModel["signature"] != null) {
            $pdf->SVGImage($svgData, 140, 220, 80, 15);   
        } 

        $pdf->writeHTML($signature_detail, true, false, false, false, '');

        
        $pdfFilePath = FCPATH . 'uploads/contractdownload/' . $contract_id . '.pdf';

        if ($type == 'view') {
            $pdf->Output('contract.pdf', 'I');
        } elseif ($type == 'download') {
            $pdf->Output($pdfFilePath, 'F');
            header('Content-Type: application/pdf');
            header('Content-Disposition: attachment; filename="contract.pdf"');
            readfile($pdfFilePath);
        }
        exit();
    }

    public function contract_export(){
        $type = $_GET["export"];
        $config = array();
        $config["title"] = "Contract";
        if($type == "pdf"){
            $config["column"] = array("Subject","Customer","Contract Type","Contract Value","Start Date","End Date","Signature");
        }else{
            $config["column"] = array("Subject","Customer","Contract Type","Contract Value","Start Date","End Date","Signature");
            $config["column_data_type"] = array(ExporterConst::E_STRING,ExporterConst::E_STRING,ExporterConst::E_STRING,ExporterConst::E_STRING,ExporterConst::E_STRING,ExporterConst::E_STRING,ExporterConst::E_STRING);
        }
        $config["data"] = $this->ContractModel->get_data_export();
        
        $config['title']="Contract Export";
        $config['ref_link']="";
        $config['log_text']="[ Contract Exported ]";
        log_activity($config);

        if($type == "pdf"){
            Exporter::export(ExporterConst::PDF,$config);
        }
        else if($type == "csv"){
            Exporter::export(ExporterConst::CSV,$config);
        }
        else if($type == "excel"){
            Exporter::export(ExporterConst::EXCEL,$config);
        }
    }
}
