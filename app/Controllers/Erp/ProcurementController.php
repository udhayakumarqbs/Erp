<?php

namespace App\Controllers\Erp;

use App\Libraries\pdfs\AbstractPdf;
use App\Controllers\BaseController;
use App\Libraries\Job\RfqSend;
use App\Models\Erp\Procurement\ProcurementModel;
use App\Libraries\Exporter\ExporterConst;
use App\Libraries\Exporter;
use App\Libraries\PdfCreater as LibrariesPdfCreater;
use App\Libraries\Scheduler;
use App\Models\Erp\CustomFieldModel;


class ProcurementController extends BaseController
{

    public $data;
    private $db;
    protected $session;
    private $procurement_m;
    private $CustomFieldModel;
    private $pdfCreator;
    protected $logger;

    public function __construct()
    {
        $this->db = \Config\Database::connect();
        $this->session = \Config\Services::session();
        $this->logger = \Config\Services::logger();
        $this->procurement_m = new ProcurementModel();
        $this->CustomFieldModel = new CustomFieldModel();
        $this->pdfCreator = new LibrariesPdfCreater();
        helper(['erp', 'form']);
    }

    public function requisition()
    {
        // return "Hello their";
        $this->data['requisition_datatable_config'] = $this->procurement_m->get_dtconfig_requisition();
        $this->data['priority'] = $this->procurement_m->get_req_priority();
        $this->data['status'] = $this->procurement_m->get_req_status();
        $this->data['page'] = "procurement/requisition";
        $this->data['menu'] = "procurement";
        $this->data['submenu'] = "requisition";
        $this->data['session'] = $this->session;
        return view("erp/index", $this->data);
    }

    public function ajaxfetchrawmaterials()
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
        header("Content-Type:application/json");
        echo json_encode($response);
        exit();
    }

    public function ajax_requisition_response()
    {

        $response = array();
        $response = $this->procurement_m->get_requisition_datatable();
        header("Content-Type:application/json");
        echo json_encode($response);
        exit();
    }

    public function requisitionadd()
    {
        if ($this->request->getPost("req_code")) {

            // return var_dump($_POST);
            // return $this->procurement_m->insert_requisition();

            if ($this->procurement_m->insert_requisition()) {
                return $this->response->redirect(url_to('erp.procurement.requisition'));
            } else {
                return $this->response->redirect(url_to('erp.procurement.requisitionadd'));
            }
        }

        $this->data['priority'] = $this->procurement_m->get_req_priority();
        $this->data['priority_bg'] = $this->procurement_m->get_req_priority_bg();
        $this->data['product_types'] = $this->procurement_m->get_product_types();
        $this->data['product_links'] = $this->procurement_m->get_product_links();
        $this->data['total_req_count'] = $this->procurement_m->get_req_count();
        $this->data['page'] = "procurement/requisitionadd";
        $this->data['menu'] = "procurement";
        $this->data['submenu'] = "requisition";
        return view("erp/index", $this->data);
    }

    public function requisition_export()
    {

        $type = $_GET['export'];
        $config = array();
        $config['title'] = "Requsition";
        if ($type == "pdf") {
            $config['column'] = array("Requisition code", "Assigned to", "Status", "Priority", "Sent in Mail ", "Description", "Remarks");
        } else {
            $config['column'] = array("Requisition code", "Assigned to", "Status", "Priority", "Sent in Mail ", "Description", "Remarks");
            $config['column_data_type'] = array(ExporterConst::E_STRING, ExporterConst::E_STRING, ExporterConst::E_STRING, ExporterConst::E_STRING, ExporterConst::E_STRING, ExporterConst::E_STRING, ExporterConst::E_STRING);
        }
        $config['data'] = $this->procurement_m->get_requisition_export($type);
        if ($type == "pdf") {
            Exporter::export(ExporterConst::PDF, $config);
        } else if ($type == "excel") {
            Exporter::export(ExporterConst::EXCEL, $config);
        } else if ($type == "csv") {
            Exporter::export(ExporterConst::CSV, $config);
        }
    }

    public function ajax_requisition_code_unique()
    {

        $req_code = $this->request->getGet("data");
        $id = $_GET['id'] ?? 0;
        $query = "SELECT req_code FROM requisition WHERE req_code='$req_code' LIMIT 1";
        if (!empty($id)) {
            $query = "SELECT req_code FROM requisition WHERE req_code='$req_code' AND req_id<>$id LIMIT 1";
        }
        $check = $this->db->query($query)->getRow();
        $response = array();
        if (empty($check)) {
            $response['valid'] = 1;
        } else {
            $response['valid'] = 0;
            $response['msg'] = "Code already exists";
        }
        header("content-type:application/json");
        echo json_encode($response);
        exit();
    }

    public function ajaxfetchsemifinished()
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
        header("Content-Type:application/json");
        echo json_encode($response);
        exit();
    }

    public function requisitionview($req_id)
    {

        $this->data['requisition'] = $this->procurement_m->get_requisition_for_view($req_id);
        if (empty($this->data['requisition'])) {
            session()->setFlashdata("op_error", "Requisition Not Found");
            return $this->response->redirect(url_to("erp.procurement.requisition"));
        }
        $this->data['requisition_items'] = $this->procurement_m->get_requisition_items($req_id);
        $this->data['req_id'] = $req_id;
        $this->data['priority'] = $this->procurement_m->get_req_priority();
        $this->data['priority_bg'] = $this->procurement_m->get_req_priority_bg();
        $this->data['status'] = $this->procurement_m->get_req_status();
        $this->data['status_bg'] = $this->procurement_m->get_req_status_bg();
        $this->data['is_rfq_created'] = $this->procurement_m->is_rfq_created($req_id);
        $this->data['is_order_created'] = $this->procurement_m->is_order_created($req_id);
        $this->data['page'] = "procurement/requisitionview";
        $this->data['menu'] = "procurement";
        $this->data['submenu'] = "requisition";
        return view("erp/index", $this->data);
    }

    public function requisitionhandle($req_id)
    {

        $this->procurement_m->handle_requisition($req_id);
        return  $this->response->redirect(url_to('erp.procurement.requisitionview', $req_id));
    }

    public function requisitiondelete($req_id)
    {

        $this->procurement_m->requisitiondelete($req_id);
        return $this->response->redirect(url_to('erp.procurement.requisition'));
    }

    public function requisitionedit($req_id)
    {

        if ($this->request->getPost("req_code")) {
            return $this->procurement_m->update_requisition($req_id);
            if ($this->procurement_m->update_requisition($req_id)) {
                return $this->response->redirect(base_url('erp.procurement.requisition'));
            } else {
                return $this->response->redirect(url_to('erp.procurement.requisitionedit', $req_id));
            }
        }
        $this->data['requisition'] = $this->procurement_m->get_requisition_by_id($req_id);
        if (empty($this->data['requisition'])) {
            $this->session->setFlashdata("op_error", "Requisition Not Found");
            return $this->response->redirect(url_to("erp.procurement.requisition"));
        }
        $this->data['requisition_items'] = $this->procurement_m->get_requisition_items($req_id);
        $this->data['req_id'] = $req_id;
        $this->data['priority'] = $this->procurement_m->get_req_priority();
        $this->data['priority_bg'] = $this->procurement_m->get_req_priority_bg();
        $this->data['product_types'] = $this->procurement_m->get_product_types();
        $this->data['product_links'] = $this->procurement_m->get_product_links();
        $this->data['page'] = "procurement/requisitionedit";
        $this->data['menu'] = "procurement";
        $this->data['submenu'] = "requisition";
        return view("erp/index", $this->data);
    }

    public function requisitionaction($req_id)
    {

        if ($this->request->getPost("req_status")) {
            $this->procurement_m->requisition_action($req_id);
        }
        return $this->response->redirect(url_to('erp.procurement.requisitionview', $req_id));
    }

    //RFQ
    public function rfq()
    {

        $this->data['rfq_datatable_config'] = $this->procurement_m->get_dtconfig_rfq();
        $this->data['status'] = $this->procurement_m->get_rfq_status();
        $this->data['page'] = "procurement/rfq";
        $this->data['menu'] = "procurement";
        $this->data['submenu'] = "rfq";
        return view("erp/index", $this->data);
    }

    public function ajax_rfq_response()
    {

        $response = array();
        $response = $this->procurement_m->get_rfq_datatable();
        header("Content-Type:application/json");
        echo json_encode($response);
        exit();
    }

    public function createrfq($req_id)
    {

        if (isset($_POST["rfq_code"]) && !empty($_POST["rfq_code"])) {
            if ($this->procurement_m->insert_rfq($req_id)) {
                return $this->response->redirect(url_to('erp.procurement.rfq'));
            } else {
                return $this->response->redirect(url_to('erp.procurement.requisitionview', $req_id));
            }
        }
        $this->data['req_id'] = $req_id;
        $this->data['customfields'] = $this->CustomFieldModel->get_custom_fields_for_form("rfq");
        $this->data['customfield_chkbx_counter'] = $this->CustomFieldModel->get_checkbox_counter();
        $this->data['total_rfq_count'] = $this->procurement_m->get_rfq_count();
        $this->data['page'] = "procurement/rfqadd";
        $this->data['menu'] = "procurement";
        $this->data['submenu'] = "rfq";
        return view("erp/index", $this->data);
    }

    public function createorder_req($req_id)
    {
        if (isset($_POST) && !empty($_POST["order_code"])) {
            // return $this->procurement_m->insert_order_req($req_id); 
            if ($this->procurement_m->insert_order_req($req_id)) {
                return $this->response->redirect(url_to('erp.procurement.orders'));
            } else {
                return $this->response->redirect(url_to('erp.procurement.createorder_req', $req_id));
            }
        }

        $this->data['req_id'] = $req_id;
        $this->data['warehouses'] = $this->procurement_m->get_all_warehouses();
        $this->data['selection_rules'] = $this->procurement_m->get_all_selection_rules();
        $this->data['requisition_items'] = $this->procurement_m->get_requisition_items($req_id);
        $this->data['get_order_count'] = $this->procurement_m->get_order_count();
        $this->data['supplier_location_url'] = base_url() . "erp/procurement/ajax_supplier_locations?";
        $this->data['page'] = "procurement/orderaddreq";
        $this->data['menu'] = "procurement";
        $this->data['submenu'] = "order";
        return view("erp/index", $this->data);
    }

    public function ajax_order_response()
    {

        $response = array();
        $response = $this->procurement_m->get_order_datatable();
        header("Content-Type:application/json");
        echo json_encode($response);
        exit();
    }

    public function ajaxfetchtransport()
    {
        $search = $_GET['search'] ?? "";
        $response = array();
        $response['test'] = $search;
        $response['error'] = 1;
        if (!empty($search)) {
            $query = "SELECT transport_id,CONCAT(name,' ',code) AS transport FROM transports WHERE active=1 AND status=0 AND (name LIKE '%" . $search . "%' OR code LIKE '%" . $search . "%') LIMIT 10";
            $result = $this->db->query($query)->getResultArray();
            $m_result = array();
            foreach ($result as $r) {
                array_push($m_result, array(
                    "key" => $r['transport_id'],
                    "value" => $r['transport']
                ));
            }
            $response['error'] = 0;
            $response['data'] = $m_result;
        }
        header("Content-Type:application/json");
        echo json_encode($response);
        exit();
    }

    public function ajaxfetchpricelist()
    {
        $search = $_GET['search'] ?? "";
        $response = array();
        $response['error'] = 1;
        if (!empty($search)) {
            $query = "SELECT price_id,name,amount,CONCAT(t1.tax_name,' ',t1.percent,' , ',IFNULL(t2.tax_name,''),' ',IFNULL(t2.percent,'')) AS tax FROM price_list LEFT JOIN taxes t1 ON price_list.tax1=t1.tax_id LEFT JOIN taxes t2 ON price_list.tax2=t2.tax_id WHERE name LIKE '%" . $search . "%' AND active=1  LIMIT 10";
            $result = $this->db->query($query)->getResultArray();
            $m_result = array();
            foreach ($result as $r) {
                $extra = array();
                array_push($extra, $r['amount']);
                array_push($extra, $r['tax']);
                array_push($m_result, array(
                    "key" => $r['price_id'],
                    "value" => $r['name'],
                    "extra" => $extra,
                ));
            }
            $response['error'] = 0;
            $response['data'] = $m_result;
        }
        header("Content-Type:application/json");
        echo json_encode($response);
        exit();
    }

    public function ajax_supplier_locations()
    {
        $supplier_id = $_GET['data'] ?? 0;
        $response = array();
        $response['error'] = 1;
        $response['reason'] = "No data";
        if (!empty($supplier_id)) {
            $query = "SELECT location_id,CONCAT(address,' , ',city) AS location FROM supplier_locations WHERE supplier_id=$supplier_id";
            $result = $this->db->query($query)->getResultArray();
            $response['error'] = 0;
            $response['data'] = $result;
        }
        header("Content-Type:application/json");
        echo json_encode($response);
        exit();
    }

    public function ajax_order_code_unique()
    {
        $order_code = $_GET['data'];
        $id = $_GET['id'] ?? 0;
        $query = "SELECT order_code FROM purchase_order WHERE order_code='$order_code' ";
        if (!empty($id)) {
            $query = "SELECT order_code FROM purchase_order WHERE order_code='$order_code' AND order_id<>$id";
        }
        $check = $this->db->query($query)->getRow();
        $response = array();
        if (empty($check)) {
            $response['valid'] = 1;
        } else {
            $response['valid'] = 0;
            $response['msg'] = "Order code already exists";
        }
        header("content-type:application/json");
        echo json_encode($response);
        exit();
    }

    public function ajax_rfq_code_unique()
    {
        $rfq_code = $_GET['data'];
        $id = $_GET['id'] ?? 0;
        $query = "SELECT rfq_code FROM rfq WHERE rfq_code='$rfq_code' ";
        if (!empty($id)) {
            $query = "SELECT rfq_code FROM rfq WHERE rfq_code='$rfq_code' AND rfq_id<>$id";
        }
        $check = $this->db->query($query)->getRow();
        $response = array();
        if (empty($check)) {
            $response['valid'] = 1;
        } else {
            $response['valid'] = 0;
            $response['msg'] = "RFQ code already exists";
        }
        header("content-type:application/json");
        echo json_encode($response);
        exit();
    }

    public function rfq_export()
    {

        $type = $_GET['export'];
        $config = array();
        $config['title'] = "RFQ";
        if ($type == "pdf") {
            $config['column'] = array("RFQ Code", "REQ Code", "Expiry Date", "Status", "Terms and Condition", "Created on");
        } else {
            $config['column'] = array("RFQ Code", "REQ Code", "Expiry Date", "Status", "Terms and Condition", "Created on");
            $config['column_data_type'] = array(ExporterConst::E_STRING, ExporterConst::E_STRING, ExporterConst::E_STRING, ExporterConst::E_STRING, ExporterConst::E_STRING, ExporterConst::E_STRING);
        }
        $config['data'] = $this->procurement_m->get_rfq_export();
        if ($type == "pdf") {
            Exporter::export(ExporterConst::PDF, $config);
        } else if ($type == "excel") {
            Exporter::export(ExporterConst::EXCEL, $config);
        } else if ($type == "csv") {
            Exporter::export(ExporterConst::CSV, $config);
        }
    }

    public function rfqview($rfq_id)
    {

        $this->data['rfq'] = $this->procurement_m->get_rfq_for_view($rfq_id);
        // return var_dump($this->data['rfq']);
        if (empty($this->data['rfq'])) {
            $this->session->setFlashdata("op_error", "RFQ Not Found");
            return $this->response->redirect(url_to("erp.procurement.rfq"));
        }
        $this->data['rfq_id'] = $rfq_id;
        $this->data['rfq_items'] = $this->procurement_m->get_requisition_items($this->data['rfq']->req_id);
        $this->data['status'] = $this->procurement_m->get_rfq_status();
        $this->data['status_bg'] = $this->procurement_m->get_rfq_status_bg();
        $this->data['attachments'] = $this->procurement_m->get_attachments("rfq", $rfq_id);
        $this->data['rfqsupplier_datatable_config'] = $this->procurement_m->get_dtconfig_rfqsupplier();
        $this->data['page'] = "procurement/rfqview";
        $this->data['menu'] = "procurement";
        $this->data['submenu'] = "rfq";
        return view("erp/index", $this->data);
    }

    public function ajax_rfqsupplier_response()
    {
        $response = array();
        $response = $this->procurement_m->get_rfqsupplier_datatable();
        header("Content-Type:application/json");
        echo json_encode($response);
        exit();
    }

    public function upload_rfqattachment()
    {

        $response = array();
        if (!empty($_FILES['attachment']['name'])) {
            $response = $this->procurement_m->upload_attachment("rfq");
        } else {
            $response['error'] = 1;
            $response['reason'] = "No file to upload";
        }
        header("Content-Type:application/json");
        echo json_encode($response);
        exit();
    }

    public function rfq_delete_attachment()
    {

        $response = array();
        $response = $this->procurement_m->delete_attachment("rfq");
        header("Content-Type:application/json");
        echo json_encode($response);
        exit();
    }

    public function rfqsupplier_export()
    {

        $type = $_GET['export'];
        $config = array();
        $config['title'] = "RFQ Supplier";
        if ($type == "pdf") {
            $config['column'] = array("RFQ Code", "Supplier", "Mail Status", "Send to Contacts", "Include Attachments", "Responded");
        } else {
            $config['column'] = array("RFQ Code", "Supplier", "Mail Status", "Send to Contacts", "Include Attachments", "Responded");
            $config['column_data_type'] = array(ExporterConst::E_STRING, ExporterConst::E_STRING, ExporterConst::E_STRING, ExporterConst::E_STRING, ExporterConst::E_STRING, ExporterConst::E_STRING);
        }
        $config['data'] = $this->procurement_m->get_rfqsupplier_export($type);
        if ($type == "pdf") {
            Exporter::export(ExporterConst::PDF, $config);
        } else if ($type == "excel") {
            Exporter::export(ExporterConst::EXCEL, $config);
        } else if ($type == "csv") {
            Exporter::export(ExporterConst::CSV, $config);
        }
    }

    public function rfqsupplieradd($rfq_id)
    {
        if (isset($_POST["supplier"]) && !empty($_POST["supplier"])) {
            // return $this->procurement_m->insert_rfqsupplier($rfq_id);
            if ($this->procurement_m->insert_rfqsupplier($rfq_id)) {
                // return "success";
                return $this->response->redirect(url_to('erp.procurement.rfqview', $rfq_id));
                // redirect("erp/procurement/rfqview/".$rfq_id);
            } else {
                // return "failure";
                return $this->response->redirect(url_to('erp.procurement.rfqsupplieradd', $rfq_id));
                // redirect("erp/procurement/rfqsupplieradd/".$rfq_id);
            }
        }
        $this->data['rfq_id'] = $rfq_id;
        $this->data['selection_rules'] = $this->procurement_m->get_all_selection_rules();
        $this->data['page'] = "procurement/rfqsupplieradd";
        $this->data['menu'] = "procurement";
        $this->data['submenu'] = "rfq";
        return view("erp/index", $this->data);
    }

    public function ajax_get_selection_basis()
    {
        $basis = $this->request->getGet("data");
        $data = $this->procurement_m->get_selection_basis($basis);
        $response = array();
        if (!empty($data)) {
            $response['error'] = 0;
            $response['data'] = $data;
        } else {
            $response['error'] = 1;
            $response['reason'] = "Internal Error";
        }
        header("Content-Type:application/json");
        echo json_encode($response);
        exit();
    }

    public function ajaxfetchsupplierbyproduct()
    {
        $search = $_GET['search'] ?? "";
        $response = array();
        $response['error'] = 1;
        if (!empty($search)) {
            $query = "SELECT suppliers.supplier_id, CONCAT(name,' - ',code) 
            AS name FROM suppliers WHERE 
            lower(CONCAT(name,' ',code,'---')) LIKE  lower('%" . $search . "%')
            ORDER BY name DESC LIMIT 10";
            $result = $this->db->query($query)->getResultArray();
            $m_result = array();
            foreach ($result as $r) {
                array_push($m_result, array(
                    "key" => $r['supplier_id'],
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

    public function rfqedit($rfq_id)
    {
        if ($this->request->getPost("rfq_code")) {
            if ($this->procurement_m->update_rfq($rfq_id)) {
                return $this->response->redirect(url_to("erp.procurement.rfq"));
            } else {
                return $this->response->redirect(url_to("erp.procurement.rfqview", $rfq_id));
            }
        }
        $this->data['rfq'] = $this->procurement_m->get_rfq_for_edit($rfq_id);
        if (empty($this->data['rfq'])) {
            $this->session->setFlashdata("op_error", "RFQ Not Found");
            return $this->response->redirect(url_to("erp.procurement.rfq", $rfq_id));

            // redirect("erp/procurement/rfq");
        }
        $this->data['customfields'] = $this->CustomFieldModel->get_custom_fields_for_edit("rfq", $rfq_id);
        $this->data['customfield_chkbx_counter'] = $this->CustomFieldModel->get_checkbox_counter();
        $this->data['rfq_id'] = $rfq_id;
        $this->data['page'] = "procurement/rfqedit";
        $this->data['menu'] = "procurement";
        $this->data['submenu'] = "rfq";
        return  view("erp/index", $this->data);
    }

    public function rfqsendbatch($rfq_id)
    {
        $query = "SELECT supp_rfq_id FROM supplier_rfq WHERE rfq_id=$rfq_id";
        $result = $this->db->query($query)->getResultArray();
        if (empty($result)) {
            $config['title'] = "RFQ Send Batch";
            $config['ref_link'] = "erp/procurement/rfqview/" . $rfq_id;
            $config['log_text'] = "[ RFQ Sent without suppliers ]";
            $this->session->setFlashdata("op_error", "Can't create RFQ Send Batch");
            log_activity($config);
            return $this->response->redirect(url_to("erp.procurement.rfqview", $rfq_id));
        } else {
            $job = new RfqSend();
            $timeadd = $job->getTimeForNextJob();
            $index = $timeadd;
            foreach ($result as $row) {
                $job_config = array(
                    "supp_rfq_id" => $row['supp_rfq_id']
                );
                Scheduler::dispatch($job, $job_config, $index);
                $index += $timeadd;
            }
            $query = "UPDATE rfq SET status = 1 WHERE rfq_id = $rfq_id";
            $this->db->query($query);
            $config['title'] = "RFQ Send Batch";
            $config['ref_link'] = "erp/procurement/rfqview/" . $rfq_id;
            $config['log_text'] = "[ RFQ Send Batch sucessfully created ]";
            $this->session->setFlashdata("op_success", "RFQ Send Batch sucessfully created");
            log_activity($config);
            return $this->response->redirect(url_to("erp.procurement.rfqview", $rfq_id));
        }
    }

    // Not implemented
    public function rfqdelete($id)
    {
        $this->procurement_m->rfqdelete($id);
        return $this->response->redirect(url_to('erp.procurement.rfq'));
    }

    public function rfqsupplierdelete($supp_rfq_id, $id)
    {
        $this->procurement_m->rfqsupplierdelete($supp_rfq_id, $id);
        return $this->response->redirect(url_to('erp.procurement.rfqview', $id));
    }

    //Orders
    public function orders()
    {
        $this->data['order_datatable_config'] = $this->procurement_m->get_dtconfig_order();
        $this->data['order_status'] = $this->procurement_m->get_order_status();
        $this->data['page'] = "procurement/orders";
        $this->data['menu'] = "procurement";
        $this->data['submenu'] = "order";
        return view("erp/index", $this->data);
    }

    public function orderdelete($order_id)
    {
        $this->procurement_m->orderdelete($order_id);
        return $this->response->redirect(url_to('erp.procurement.orders', $order_id));
    }

    public function orderview($order_id)
    {

        $this->data['order'] = $this->procurement_m->get_order_for_view($order_id);
        if (empty($this->data['order'])) {
            $this->session->setFlashdata("op_error", "Purchase Order Not Found");
            return $this->response->redirect(url_to('erp.procurement.orders'));
        }
        $this->data['order_id'] = $order_id;
        $this->data['order_items'] = $this->procurement_m->get_order_items($order_id);
        $this->data['status'] = $this->procurement_m->get_order_status();
        $this->data['status_bg'] = $this->procurement_m->get_order_status_bg();
        $this->data['page'] = "procurement/orderview";
        $this->data['menu'] = "procurement";
        $this->data['submenu'] = "order";
        return view("erp/index", $this->data);
    }

    public function ordercancel($order_id)
    {
        $this->procurement_m->change_order_status($order_id, 3);
        return $this->response->redirect(url_to('erp.procurement.orderview', $order_id));
    }

    public function orderapprove($order_id)
    {
        $this->procurement_m->change_order_status($order_id, 2);
        return $this->response->redirect(url_to('erp.procurement.orderview', $order_id));
    }

    public function orderpdf($order_id)
    {
        $order = $this->procurement_m->get_order_for_pdf($order_id);
        return var_dump($order);
        $this->data['order'] = $order;
        $this->data['filename'] = "purchase_order.pdf";
        LibrariesPdfCreater::create(AbstractPdf::PURCHASE_ORDER, AbstractPdf::FMT_INLINE, $this->data);
    }

    public function ordersend($order_id, $supp_rfq_id = 0)
    {
        return '<div class="text-center my-5 py-5">We are working on it, pleae come again later<div>';

        $job = new RfqSend();

        $timeadd = $job->getTimeForNextJob();
        $index = $timeadd;
        $job_config = array(
            "supp_rfq_id" => $supp_rfq_id
        );
        Scheduler::dispatch($job, $job_config, $index);
        $config['title'] = "RFQ Resend";
        $config['ref_link'] = "erp/procurement/rfqview/" . $order_id;
        $config['log_text'] = "[ RFQ Resend sucessfully created ]";
        $this->session->setFlashdata("op_success", "RFQ Resend sucessfully created");
        log_activity($config);
        redirect("erp/procurement/rfqview/" . $order_id);
    }

    public function order_export()
    {
        $type = $_GET['export'];
        $config = array();
        $config['title'] = "Purchase Orders";
        if ($type == "pdf") {
            $config['column'] = array("Order Code", "Supplier", "Company Transport", "Delivery date", "Status");
        } else {
            $config['column'] = array("Order Code", "Supplier", "Company Transport", "Transport", "Units", "Charge", "Delivery date", "Status", "Terms and Condition", "Notes", "REQ Code", "Supplier Location");
            $config['column_data_type'] = array(ExporterConst::E_STRING, ExporterConst::E_STRING, ExporterConst::E_STRING, ExporterConst::E_STRING, ExporterConst::E_STRING, ExporterConst::E_STRING, ExporterConst::E_STRING, ExporterConst::E_STRING, ExporterConst::E_STRING, ExporterConst::E_STRING, ExporterConst::E_STRING, ExporterConst::E_STRING);
        }
        $config['data'] = $this->procurement_m->get_order_export($type);
        if ($type == "pdf") {
            Exporter::export(ExporterConst::PDF, $config);
        } else if ($type == "excel") {
            Exporter::export(ExporterConst::EXCEL, $config);
        } else if ($type == "csv") {
            Exporter::export(ExporterConst::CSV, $config);
        }
    }

    public function orderedit($order_id)
    {
        if (isset($_POST["order_code"]) && !empty($_POST["order_code"])) {

            if ($this->procurement_m->update_order($order_id)) {
                return $this->response->redirect(url_to('erp.procurement.orders'));
            } else {
                return $this->response->redirect(url_to('erp.procurement.orderedit', $order_id));
            }
        }
        $this->data['order'] = $this->procurement_m->get_order_by_id($order_id);
        if (empty($this->data['order'])) {
            $this->session->setFlashdata("op_error", "Purchase Order Not Found");
            return $this->response->redirect(url_to("erp.procurement.orders"));
        }
        $this->data['order_id'] = $order_id;
        $this->data['warehouses'] = $this->procurement_m->get_all_warehouses();
        $this->data['order_items'] = $this->procurement_m->get_order_items($order_id);
        $this->data['supplier_select'] = $this->procurement_m->get_selection_basis($this->data['order']->selection_rule, $this->data['order']->supplier_id, $this->data['order']->supplier);
        $this->data['selection_rules'] = $this->procurement_m->get_all_selection_rules();
        $this->data['supplier_location_url'] = base_url() . "erp/procurement/ajax_supplier_locations?";
        $this->data['page'] = "procurement/orderedit";
        $this->data['menu'] = "procurement";
        $this->data['submenu'] = "order";
        return view("erp/index", $this->data);
    }

    public function Report()
    {
        $all_data = $this->procurement_m->builder()->select('req_id,status')->get()->getResultArray();
        $status0 = 0;
        $status1 = 0;
        $status2 = 0;
        $status3 = 0;
        $total = 0;
        foreach ($all_data as $data) {
            $total++;
            if ($data['status'] == 0) {
                $status0++;
            } else if ($data['status'] == 1) {
                $status1++;
            } else if ($data['status'] == 2) {
                $status2++;
            } else if ($data['status'] == 3) {
                $status3++;
            }
        }
        $rfqstatus0 = 0;
        $rfqstatus1 = 0;
        $rfqstatus2 = 0;
        $rfqstatus3 = 0;
        $rfqstatus4 = 0;
        $rfqtotal = 0;
        $all_Rfqdata = $this->procurement_m->builder('rfq')->select('rfq_id,status')->get()->getResultArray();
        foreach ($all_Rfqdata as $data) {
            $rfqtotal++;
            if ($data['status'] == 0) {
                $rfqtotal++;
            } else if ($data['status'] == 1) {
                $rfqstatus1++;
            } else if ($data['status'] == 2) {
                $rfqstatus2++;
            } else if ($data['status'] == 3) {
                $rfqstatus3++;
            } else if ($data['status'] == 3) {
                $rfqstatus4++;
            }
        }
        // $this->logger->error($this->data['invoiceData']);
        $this->data['invoicData'] = $this->procurement_m->getInvoiceData();;
        $this->data['test'] = $all_Rfqdata;
        $this->data['total'] = $total;
        $this->data['status0'] = $status0;
        $this->data['status1'] = $status1;
        $this->data['status2'] = $status2;
        $this->data['status3'] = $status3;
        $this->data['rfqtotal'] = $rfqtotal;
        $this->data['rfqstatus0'] = $rfqstatus0;
        $this->data['rfqstatus1'] = $rfqstatus1;
        $this->data['rfqstatus2'] = $rfqstatus2;
        $this->data['rfqstatus3'] = $rfqstatus3;
        $this->data['rfqstatus4'] = $rfqstatus4;
        $this->data['page'] = "procurement/report";
        $this->data['menu'] = "procurement";
        $this->data['submenu'] = "Report";
        return view("erp/index", $this->data);
    }


    public function createorder_rfq($supp_rfq_id, $req_id)
    {
        // if (isset($_POST) && !empty($_POST["order_code"])) {
        //     // return $this->procurement_m->insert_order_rfq($supp_rfq_id, $req_id);
        //     if ($this->procurement_m->insert_order_rfq($supp_rfq_id, $req_id)) {
        //         return $this->response->redirect(url_to('erp.procurement.orders'));
        //     } else {
        //         return $this->response->redirect(url_to('erp.procurement.createorder_req', $req_id));
        //     }
        // }

        $this->data['req_id'] = $req_id;
        $this->data['warehouses'] = $this->procurement_m->get_all_warehouses();
        $this->data['selection_rules'] = $this->procurement_m->get_all_selection_rules();
        $this->data['Req_id'] = $this->procurement_m->getrfq_id_By_Req_id($req_id);
        $this->data['requisition_items'] = $this->procurement_m->get_requisition_items($this->data['Req_id'] ?? "");
        $this->data['get_order_count'] = $this->procurement_m->get_order_count();
        $this->data['rfq_supplier'] = $this->procurement_m->get_rfq_supplier_details($supp_rfq_id);
        if(!empty($this->data['rfq_supplier'])){
            if(!empty($this->data['rfq_supplier']->supplier_id)){
                $this->data['supplier_details'] = $this->procurement_m->get_supplier_details($this->data['rfq_supplier']->supplier_id);
            }

        }
        // return var_dump($this->data['requisition_items']);
        $this->data['supplier_location_url'] = base_url() . "erp/procurement/ajax_supplier_locations?";
        $this->data['page'] = "procurement/orderaddrfq";
        $this->data['menu'] = "procurement";
        $this->data['submenu'] = "order";

        //for debugging
        // foreach($this->data as $key => $value){
        //     echo var_dump($key);
        //     echo '<br>';
        //     echo var_dump($value);
        //     echo '<br>';
        //     echo '<br>';
        //     echo '<br>';
        // }
        // return "";

        return view("erp/index", $this->data);
    }

    public function rfqsupplierresend($rfq_id, $supp_rfq_id)
    {
        $job =  new RfqSend();
        // return var_dump($job);
        //RfqSend
        $timeadd = $job->getTimeForNextJob();
        $index = $timeadd;
        $job_config = array(
            "supp_rfq_id" => $supp_rfq_id
        );
        Scheduler::dispatch($job, $job_config, $index);
        $config['title'] = "RFQ Resend";
        $config['ref_link'] = "erp/procurement/rfqview/" . $rfq_id;
        $config['log_text'] = "[ RFQ Resend sucessfully created ]";
        $this->session->setFlashdata("op_success", "RFQ Resend sucessfully created");
        log_activity($config);
        return $this->response->redirect(url_to('erp.procurement.rfqview', $rfq_id));
    }

    // invoice
    public function invoices()
    {
        $this->data['invoice_datatable_config'] = $this->procurement_m->get_dtconfig_invoice();
        $this->data['invoice_status'] = $this->procurement_m->get_invoice_status();
        $this->data['page'] = "procurement/invoices";
        $this->data['menu'] = "procurement";
        $this->data['submenu'] = "invoice";
        return view("erp/index", $this->data);
    }

    public function ajax_invoice_response()
    {
        $response = array();
        $response = $this->procurement_m->get_invoice_datatable();
        header("Content-Type:application/json");
        echo json_encode($response);
        exit();
    }

    public function orderinvoice($order_id)
    {
        $this->procurement_m->create_invoice($order_id);
        return $this->response->redirect(url_to('erp.procurement.orderview', $order_id));
    }

    public function invoice_export()
    {
        $type = $_GET['export'];
        $config = array();
        $config['title'] = "Purchase Invoices";
        if ($type == "pdf") {
            $config['column'] = array("Order Code", "Supplier", "Delivered", "Status", "Amount");
        } else {
            $config['column'] = array("Order Code", "Supplier", "Delivered", "Status", "Amount");
            $config['column_data_type'] = array(ExporterConst::E_STRING, ExporterConst::E_STRING, ExporterConst::E_STRING, ExporterConst::E_STRING, ExporterConst::E_STRING);
        }
        $config['data'] = $this->procurement_m->get_invoice_export($type);
        if ($type == "pdf") {
            Exporter::export(ExporterConst::PDF, $config);
        } else if ($type == "excel") {
            Exporter::export(ExporterConst::EXCEL, $config);
        } else if ($type == "csv") {
            Exporter::export(ExporterConst::CSV, $config);
        }
    }

    public function invoiceview($invoice_id)
    {
        $this->data['invoice'] = $this->procurement_m->get_invoice_for_view($invoice_id);
        if (empty($this->data['invoice'])) {
            $this->session->setFlashdata("op_error", "Purchase Invoice Not Found");
            return $this->response->redirect(url_to('erp.procurement.invoices'));
        }
        $grn_updated = ($this->data['invoice']->grn_updated != 0) ? true : false;
        $this->data['order_items'] = $this->procurement_m->get_order_items($this->data['invoice']->order_id, $grn_updated);
        $this->data['invoice_id'] = $invoice_id;
        $this->data['invoice_status'] = $this->procurement_m->get_invoice_status();
        $this->data['invoice_status_bg'] = $this->procurement_m->get_invoice_status_bg();
        $this->data['attachments'] = $this->procurement_m->get_attachments("purchase_invoice", $invoice_id);
        $this->data['paymentmodes'] = $this->procurement_m->get_all_paymentmodes();
        $this->data['payment_datatable_config'] = $this->procurement_m->get_dtconfig_payments();
        $this->data['page'] = "procurement/invoiceview";
        $this->data['menu'] = "procurement";
        $this->data['submenu'] = "invoice";
        return view("erp/index", $this->data);
    }

    public function upload_invoiceattachment()
    {
        $response = array();
        if (!empty($_FILES['attachment']['name'])) {
            $response = $this->procurement_m->upload_attachment("purchase_invoice");
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
        $response = $this->procurement_m->delete_attachment("purchase_invoice");
        header("Content-Type:application/json");
        echo json_encode($response);
        exit();
    }

    public function invoicepayment($invoice_id)
    {
        if ($this->request->getPost("amount")) {
            $this->procurement_m->insert_update_payment($invoice_id);
        }
        return $this->response->redirect(url_to('erp.procurement.invoiceview', $invoice_id));
    }

    public function ajax_purchasepayment_response()
    {
        $response = array();
        $response = $this->procurement_m->get_payment_datatable();
        header("Content-Type:application/json");
        echo json_encode($response);
        exit();
    }

    public function invoicepaymentdelete($invoice_id, $purchase_pay_id)
    {
        $this->procurement_m->delete_payment($invoice_id, $purchase_pay_id);
        return $this->response->redirect(url_to('erp/procurement/invoiceview', $invoice_id));
    }

    public function ajax_fetch_invoicepayment($purchase_pay_id)
    {
        $response = array();
        $query = "SELECT * FROM purchase_payments WHERE purchase_pay_id=$purchase_pay_id";
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

    public function invoice_payment_export()
    {
        $type = $_GET['export'];
        $config = array();
        $config['title'] = "Purchase Payments";
        if ($type == "pdf") {
            $config['column'] = array("Payment Mode", "Amount", "Paid on", "Transaction ID", "Notes");
        } else {
            $config['column'] = array("Payment Mode", "Amount", "Paid on", "Transaction ID", "Notes");
            $config['column_data_type'] = array(ExporterConst::E_STRING, ExporterConst::E_STRING, ExporterConst::E_STRING, ExporterConst::E_STRING, ExporterConst::E_STRING);
        }
        $config['data'] = $this->procurement_m->get_invoicepayment_export($type);
        if ($type == "pdf") {
            Exporter::export(ExporterConst::PDF, $config);
        } else if ($type == "excel") {
            Exporter::export(ExporterConst::EXCEL, $config);
        } else if ($type == "csv") {
            Exporter::export(ExporterConst::CSV, $config);
        }
    }

    //return
    public function returns()
    {
        $this->data['return_datatable_config'] = $this->procurement_m->get_dtconfig_returns();
        $this->data['page'] = "procurement/returns";
        $this->data['menu'] = "procurement";
        $this->data['submenu'] = "return";
        return view("erp/index", $this->data);
    }

    public function ajax_returns_response()
    {
        $response = array();
        $response = $this->procurement_m->get_return_datatable();
        header("Content-Type:application/json");
        echo json_encode($response);
        exit();
    }

    public function return_export()
    {
        $type = $_GET['export'];
        $config = array();
        $config['title'] = "Purchase Returns";
        if ($type == "pdf") {
            $config['column'] = array("Order Code", "Supplier", "Product", "Returned Qty");
        } else {
            $config['column'] = array("Order Code", "Supplier", "Product", "Returned Qty");
            $config['column_data_type'] = array(ExporterConst::E_STRING, ExporterConst::E_STRING, ExporterConst::E_STRING, ExporterConst::E_STRING);
        }
        $config['data'] = $this->procurement_m->get_return_export($type);
        if ($type == "pdf") {
            Exporter::export(ExporterConst::PDF, $config);
        } else if ($type == "excel") {
            Exporter::export(ExporterConst::EXCEL, $config);
        } else if ($type == "csv") {
            Exporter::export(ExporterConst::CSV, $config);
        }
    }

    //grn
    public function ordergrn($order_id)
    {
        $this->procurement_m->create_grn($order_id);
        return $this->response->redirect(url_to('erp.procurement.orderview', $order_id));
    }
}
