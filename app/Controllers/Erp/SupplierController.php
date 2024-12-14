<?php

namespace App\Controllers\Erp;

use App\Libraries\Exporter;
use App\Libraries\Importer\AbstractImporter;
use App\Libraries\Importer;

use App\Controllers\BaseController;
use App\Libraries\Exporter\ExporterConst;
use App\Models\Erp\AttachmentModel;
use App\Models\Erp\CustomFieldModel;
use App\Models\Erp\CustomFieldValueModel;
use App\Models\Erp\ErpGroupsModel;
use App\Models\Erp\SelectionRuleModel;
use App\Models\Erp\SelectionRuleSegmentModel;
use App\Models\Erp\SupplierContactModel;
use App\Models\Erp\SupplierLocationModel;
use App\Models\Erp\SupplierSegmentMapModel;
use App\Models\Erp\SupplierSegmentModel;
use App\Models\Erp\SuppliersModel;
use App\Models\Erp\SupplierSourcesModel;
use App\Models\Erp\SupplyListModel;
use CodeIgniter\HTTP\Response;
use CodeIgniter\HTTP\ResponseInterface;


class SupplierController extends BaseController
{
    public $erpGroupsModel;
    public $supplierSourcesModel;
    public $suppliersModel;
    public $customFieldModel;
    public $customFieldValueModel;
    public $attachmentModel;
    public $supplierSegmentMapModel;
    public $supplierContactModel;
    public $supplierLocationModel;
    public $supplyListModel;
    public $supplierSegmentModel;
    public $selectionRuleModel;
    public $selectionRuleSegmentModel;
    public $db;
    protected $session;

    public function __construct()
    {
        $this->erpGroupsModel = new ErpGroupsModel();
        $this->supplierSourcesModel = new SupplierSourcesModel();
        $this->suppliersModel = new SuppliersModel();
        $this->customFieldModel = new CustomFieldModel();
        $this->customFieldValueModel = new CustomFieldValueModel();
        $this->attachmentModel = new AttachmentModel();
        $this->supplierSegmentMapModel  = new SupplierSegmentMapModel();
        $this->supplierContactModel  = new SupplierContactModel();
        $this->supplierLocationModel  = new SupplierLocationModel();
        $this->supplyListModel  = new SupplyListModel();
        $this->supplierSegmentModel   = new SupplierSegmentModel();
        $this->selectionRuleModel    = new SelectionRuleModel();
        $this->selectionRuleSegmentModel    = new SelectionRuleSegmentModel();
        $this->db = \Config\Database::connect();
        $this->session = \Config\Services::session();
        helper(['erp', 'form']);
    }

    public function suppliers()
    {
        $data['supplier_groups'] = $this->erpGroupsModel->get_supplier_groups();
        $data['supplier_sources'] = $this->supplierSourcesModel->get_supplier_sources();
        $data['supplier_datatable_config'] = $this->suppliersModel->get_dtconfig_suppliers();
        $data['page'] = "supplier/suppliers";
        $data['menu'] = "supplier";
        $data['submenu'] = "supplier";
        return view("erp/index", $data);
    }

    public function AjaxSuppliersResponse()
    {

        $response = $this->suppliersModel->get_datatable_suppliers();
        return $this->response->setJSON($response);
    }

    public function ajaxSupplierActive($supplier_id)
    {
        $db = \Config\Database::connect();
        $response = [];

        $db->table('suppliers')
            ->set('active', 'active ^ 1', false)
            ->where('supplier_id', $supplier_id)
            ->update();

        if ($db->affectedRows() > 0) {
            $response['error'] = 0;
        } else {
            $response['error'] = 1;
        }

        return $this->response->setJSON($response);
    }

    public function supplieradd()
    {
        helper('form');
        $created_by = get_user_id();
        $post = [
            'supplier_source' => $this->request->getPost("supplier_source") ?? '',
            'name' => $this->request->getPost("name") ?? '',
            'code' => $this->request->getPost("code") ?? '',
            'address' => $this->request->getPost("address") ?? '',
            'position' => $this->request->getPost("position") ?? '',
            'city' => $this->request->getPost("city") ?? '',
            'email' => $this->request->getPost("email") ?? '',
            'state' => $this->request->getPost("state") ?? '',
            'phone' => $this->request->getPost("phone") ?? '',
            'country' => $this->request->getPost("country") ?? '',
            'fax_number' => $this->request->getPost("fax_number") ?? '',
            'zipcode' => $this->request->getPost("zipcode") ?? '',
            'office_number' => $this->request->getPost("office_number") ?? '',
            'website' => $this->request->getPost("website") ?? '',
            'company' => $this->request->getPost("company") ?? '',
            'gst' => $this->request->getPost("gst") ?? '',
            'payment_terms' => $this->request->getPost("payment_terms") ?? '',
            'groups' => $this->request->getPost("groups") ?? '',
            'description' => $this->request->getPost('description') ?? '',
            'created_by' => $created_by,
        ];
        $customfield_chkbx_counter = $this->request->getPost("customfield_chkbx_counter");

        $data['supplier_groups'] = $this->erpGroupsModel->get_supplier_groups();
        $data['customfields'] = $this->customFieldModel->get_custom_fields_for_form("supplier");
        $data['customfield_chkbx_counter'] = $this->customFieldModel->get_checkbox_counter();
        $data['supplier_sources'] = $this->supplierSourcesModel->get_supplier_sources();
        $data['page'] = "supplier/supplieradd";
        $data['menu'] = "supplier";
        $data['submenu'] = "supplier";

        if ($this->request->getPost("name")) {
            if ($this->suppliersModel->insert_supplier($post, $customfield_chkbx_counter)) {
                return $this->response->redirect(url_to("erp.supplier.page"));
            } else {
                return $this->response->redirect(url_to("erp.supplier.add"));
            }
        }

        return view("erp/index", $data);
    }

    public function supplierDelete($supplier_id)
    {
        $this->suppliersModel->suplierDelete($supplier_id);
        return $this->response->redirect(url_to("erp.supplier.page"));
    }

    public function supplierContentDelete($contact_id, $supplier_id)
    {
        $this->supplierContactModel->contentDelete($supplier_id, $contact_id);
        return $this->response->redirect(url_to("erp.supplier.page"));
    }

    public function suplierLocationDelete($location_id, $supplier_id)
    {
        $this->supplierLocationModel->locationDelete($supplier_id, $location_id);
        return $this->response->redirect(url_to("erp.supplier.page"));
    }

    public function suplierListDelete($supply_list_id, $supplier_id)
    {
        $this->supplyListModel->listDelete($supplier_id, $supply_list_id);
        return $this->response->redirect(url_to("erp.supplier.page"));
    }
    public function ajaxSupplierCodeUnique()
    {
        $code = $this->request->getGet('data');
        $id = $this->request->getGet('id') ?? 0;

        $builder = $this->db->table('suppliers');
        $builder->select('code');

        if (!empty($id)) {
            $builder->where('code', $code)
                ->where('supplier_id <>', $id);
        } else {
            $builder->where('code', $code);
        }

        $check = $builder->get()->getRow();

        $response = [];

        if (empty($check)) {
            $response['valid'] = 1;
        } else {
            $response['valid'] = 0;
            $response['msg'] = 'Supplier Code already exists';
        }

        return $this->response->setJSON($response);
    }

    public function ajaxSupplierMailUnique()
    {
        $email = $this->request->getGet('data');
        $id = $this->request->getGet('id') ?? 0;

        $builder = $this->db->table('suppliers');
        $builder->select('email');

        if (!empty($id)) {
            $builder->where('email', $email)
                ->where('supplier_id <>', $id);
        } else {
            $builder->where('email', $email);
        }

        $check = $builder->get()->getRow();

        $response = [];

        if (empty($check)) {
            $response['valid'] = 1;
        } else {
            $response['valid'] = 0;
            $response['msg'] = 'Email ID already exists';
        }

        return $this->response->setJSON($response);
    }

    public function supplieredit($supplier_id)
    {
        helper('form');
        $session = \Config\Services::session();
        $post['supplier_source'] = $this->request->getPost("supplier_source");
        $post['name'] = $this->request->getPost("name");
        $post['code'] = $this->request->getPost("code");
        $post['address'] = $this->request->getPost("address");
        $post['position'] = $this->request->getPost("position");
        $post['city'] = $this->request->getPost("city");
        $post['email'] = $this->request->getPost("email");
        $post['state'] = $this->request->getPost("state");
        $post['phone'] = $this->request->getPost("phone");
        $post['country'] = $this->request->getPost("country");
        $post['fax_number'] = $this->request->getPost("fax_number");
        $post['zipcode'] = $this->request->getPost("zipcode");
        $post['office_number'] = $this->request->getPost("office_number");
        $post['website'] = $this->request->getPost("website");
        $post['company'] = $this->request->getPost("company");
        $post['gst'] = $this->request->getPost("gst");
        $post['payment_terms'] = $this->request->getPost("payment_terms");
        $post['description'] = $this->request->getPost("description");
        $post['groups'] = $this->request->getPost("groups");
        $customfield_chkbx_counter = $this->request->getPost("customfield_chkbx_counter");

        if ($this->request->getPost("name")) {
            if ($this->suppliersModel->update_supplier($supplier_id, $post, $customfield_chkbx_counter)) {
                return $this->response->redirect(url_to("erp.supplier.page"));
            } else {
                return $this->response->redirect(url_to('erp.supplier.edit', $supplier_id));
            }
        }

        $supplier = $this->suppliersModel->get_supplier_by_id($supplier_id);

        if (empty($supplier)) {
            $session()->setFlashdata("op_error", "Supplier Not Found");
            return $this->response->redirect(url_to("erp.supplier.page"));
        }

        $data['supplier'] = $supplier;
        $data['supplier_id'] = $supplier_id;
        $data['supplier_groups'] = $this->erpGroupsModel->get_supplier_groups();
        $data['customfields'] = $this->customFieldModel->get_custom_fields_for_edit("supplier", $supplier_id);
        $data['customfield_chkbx_counter'] = $this->customFieldModel->get_checkbox_counter();
        $data['supplier_sources'] = $this->supplierSourcesModel->get_supplier_sources();
        $data['page'] = "supplier/supplieredit";
        $data['menu'] = "supplier";
        $data['submenu'] = "supplier";

        return view("erp/index", $data);
    }

    public function supplierview($supplier_id)
    {
        $supplier = $this->suppliersModel->get_supplier_for_view($supplier_id);

        if (empty($supplier)) {
            session()->setFlashdata("op_error", "Supplier Not Found");
            return $this->response->redirect(url_to("erp.supplier.page"));
        }

        $data['supplier'] = $supplier;
        $data['product_types'] = $this->suppliersModel->get_supplylist_products();
        $data['product_links'] = $this->suppliersModel->get_supplylist_products_link();
        $data['contact_datatable_config'] = $this->suppliersModel->get_dtconfig_contacts();
        $data['location_datatable_config'] = $this->suppliersModel->get_dtconfig_location();
        $data['supplylist_datatable_config'] = $this->suppliersModel->get_dtconfig_supplylist();
        $data['custom_field_values'] = $this->customFieldValueModel->get_custom_fields_for_view("supplier", $supplier_id);
        $data['attachments'] = $this->attachmentModel->get_attachments("supplier", $supplier_id);
        $data['segment_html'] = $this->supplierSegmentMapModel->get_segments_for_supplier($supplier_id);
        $data['supplier_id'] = $supplier_id;
        $data['page'] = "supplier/supplierview";
        $data['menu'] = "supplier";
        $data['submenu'] = "supplier";

        return view("erp/index", $data);
    }

    public function uploadSupplierattachment()
    {
        $id = $this->request->getGet("id") ?? 0;
        $response = [];

        if (!empty($_FILES['attachment']['name'])) {

            helper(['form', 'url']);
            $validation = \Config\Services::validation();


            $rules = [
                'attachment' => 'uploaded[attachment]|max_size[attachment,10240]|ext_in[attachment,pdf,doc,docx,xls,xlsx]',
            ];

            if ($this->validate($rules)) {
                $response = $this->attachmentModel->upload_attachment("supplier", $id);
            } else {
                $response['error'] = 1;
                $response['reason'] = $validation->getError('attachment');
            }
        } else {
            $response['error'] = 1;
            $response['reason'] = "No file to upload";
        }

        return $this->response->setJSON($response);
    }



    public function supplierDeleteAttachment()
    {
        $attach_id = $this->request->getGet("id") ?? 0;
        $response = [];
        $response = $this->attachmentModel->delete_attachment("supplier", $attach_id);

        return $this->response->setJSON($response);
    }

    public function suppliercontact($supplier_id)
    {
        helper('form');
        $contact_id = $this->request->getPost("contact_id");

        $data = [
            'firstname' => $this->request->getPost("firstname"),
            'lastname' => $this->request->getPost("lastname"),
            'email' => $this->request->getPost("email"),
            'position' => $this->request->getPost("position"),
            'phone' => $this->request->getPost("phone"),
            'supplier_id' => $supplier_id,
        ];

        $data1 = [
            'firstname' => $this->request->getPost("firstname"),
            'lastname' => $this->request->getPost("lastname"),
            'email' => $this->request->getPost("email"),
            'position' => $this->request->getPost("position"),
            'phone' => $this->request->getPost("phone"),
            'supplier_id' => $supplier_id,
            'created_at' => time(),
            'created_by' => get_user_id(),
        ];

        if ($this->request->getPost("firstname")) {
            $this->supplierContactModel->insert_update_supplier_contact($supplier_id, $contact_id, $data, $data1);
        }

        return $this->response->redirect(url_to("erp.supplier.view", $supplier_id));
    }

    public function ajaxContactMailUnique()
    {
        $email = $this->request->getGet('data');
        $supplierId = $this->request->getGet('supplierid');
        $contactId = $this->request->getGet('contact_id') ?? 0;

        $model = new SupplierContactModel();

        $query = "SELECT email FROM supplier_contacts WHERE email = ? AND supplier_id = ?";
        $params = [$email, $supplierId];

        if (!empty($contactId)) {
            $query .= " AND contact_id <> ?";
            $params[] = $contactId;
        }

        $check = $model->db->query($query, $params)->getRow();
        $response = [];

        if (empty($check)) {
            $response['valid'] = 1;
        } else {
            $response['valid'] = 0;
            $response['msg'] = "Email ID already exists";
        }

        return $this->response->setJSON($response);
    }

    public function ajaxSuppliercontactResponse()
    {
        $supplier_id = $this->request->getGet("supplierid");
        $response = array();
        $response = $this->supplierContactModel->get_suppliercontact_datatable($supplier_id);
        return $this->response->setJSON($response);
    }

    public function ajaxFetchContact($contact_id)
    {
        $response = [];

        $query = $this->db->table('supplier_contacts')
            ->select('contact_id, firstname, lastname, email, phone, position')
            ->where('contact_id', $contact_id)
            ->get();

        $result = $query->getRow();

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
        $response = [];

        $this->db->table('supplier_contacts')
            ->set('active', 'active ^ 1', false)
            ->where('contact_id', $contact_id)
            ->update();

        if ($this->db->affectedRows() > 0) {
            $response['error'] = 0;
        } else {
            $response['error'] = 1;
        }

        return $this->response->setJSON($response);
    }


    
    public function supplierContactExport()
    {
        $type = $this->request->getGet('export');
        $supplier_id = $this->request->getGet("supplierid");
        $config = [];
        $config['title'] = "Supplier Locations";

        if ($type == "pdf") {
            $config['column'] = ["Firstname", "Lastname", "Email", "Phone", "Position", "Active"];
            //     } else {
        } else {
            $config['column'] = ["Firstname", "Lastname", "Email", "Phone", "Position", "Active"];
            //     } else {
            $config['column_data_type'] = [
                ExporterConst::E_STRING,
                ExporterConst::E_STRING,
                ExporterConst::E_STRING,
                ExporterConst::E_STRING,
                ExporterConst::E_STRING
            ];
        }

        $config['data'] = $this->supplierContactModel->get_supplier_contacts_export($type, $supplier_id);

        if ($type == "pdf") {
            Exporter::export(ExporterConst::PDF, $config);
        } else if ($type == "excel") {
            Exporter::export(ExporterConst::EXCEL, $config);
        } else if ($type == "csv") {
            Exporter::export(ExporterConst::CSV, $config);
        }
    }



    public function supplierlocation($supplier_id)
    {
        $location_id = $this->request->getPost("location_id") ?? 0;
        if (isset($location_id)) {
            $post['address'] = $this->request->getPost("address");
            $post['city'] = $this->request->getPost("city");
            $post['state'] = $this->request->getPost("state");
            $post['country'] = $this->request->getPost("country");
            $post['zip'] = $this->request->getPost("zip");
        } else {
            $post['address'] = $this->request->getPost("address");
            $post['city'] = $this->request->getPost("city");
            $post['state'] = $this->request->getPost("state");
            $post['country'] = $this->request->getPost("country");
            $post['zip'] = $this->request->getPost("zip");
            // $post['created_by'] = get_user_id();
        }

        if ($this->request->getPost("address")) {
            $this->supplierLocationModel->insert_update_supplier_location($supplier_id, $location_id, $post);
        }
        return $this->response->redirect(url_to('erp.supplier.view', $supplier_id));
    }

    public function ajaxSupplierlocationResponse()
    {
        $supplier_id = $this->request->getGet("supplierid");
        $response = $this->supplierLocationModel->get_supplierlocation_datatable($supplier_id);
        return $this->response->setJSON($response);
    }

    public function ajaxFetchLocation($location_id)
    {
        $response = [];
        $query = "SELECT location_id,address,city,state,country,zipcode FROM supplier_locations WHERE location_id=$location_id";
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
    public function supplierLocationDelete($supplier_id, $location_id)
    {
    }

    public function supplierLocationExport()
    {
        $type = $this->request->getGet('export');
        $supplier_id = $this->request->getGet("supplierid");
        $config = [];
        $config['title'] = "Supplier Locations";

        if ($type == "pdf") {
            $config['column'] = ["Address", "City", "State", "Country", "Zipcode"];
        } else {
            $config['column'] = ["Address", "City", "State", "Country", "Zipcode"];
            $config['column_data_type'] = [
                ExporterConst::E_STRING,
                ExporterConst::E_STRING,
                ExporterConst::E_STRING,
                ExporterConst::E_STRING,
                ExporterConst::E_STRING
            ];
        }

        $config['data'] = $this->supplierLocationModel->get_supplier_location_export($type, $supplier_id);

        if ($type == "pdf") {
            Exporter::export(ExporterConst::PDF, $config);
        } else if ($type == "excel") {
            Exporter::export(ExporterConst::EXCEL, $config);
        } else if ($type == "csv") {
            Exporter::export(ExporterConst::CSV, $config);
        }
    }

    public function ajaxfetchrawmaterials()
    {
        $search = $this->request->getGet('search') ?? "";
        $response = [];
        $response['error'] = 1;

        if (!empty($search)) {
            $query = "SELECT raw_material_id, CONCAT(name, ' ', code) AS name FROM raw_materials WHERE name LIKE '%" . $search . "%' OR code LIKE '%" . $search . "%' LIMIT 6";
            $result = $this->db->query($query)->getResultArray();

            $m_result = [];

            foreach ($result as $r) {
                array_push($m_result, [
                    'key' => $r['raw_material_id'],
                    'value' => $r['name']
                ]);
            }

            $response['error'] = 0;
            $response['data'] = $m_result;
        }

        return $this->response->setJSON($response);
    }

    public function ajaxFetchSemiFinished()
    {
        $search = $this->request->getGet('search') ?? "";
        $response = [
            'error' => 1,
        ];

        if (!empty($search)) {
            $query = "SELECT semi_finished_id, CONCAT(name, ' ', code) AS name FROM semi_finished WHERE name LIKE '%" . $search . "%' OR code LIKE '%" . $search . "%' LIMIT 6";
            $result = $this->db->query($query)->getResultArray();

            $mResult = [];
            foreach ($result as $r) {
                $mResult[] = [
                    'key'   => $r['semi_finished_id'],
                    'value' => $r['name'],
                ];
            }

            $response = [
                'error' => 0,
                'data'  => $mResult,
            ];
        }

        return $this->response->setJSON($response);
    }

    // public function supplyList($supplier_id)
    // {
    //     $supplyListId = $this->request->getPost("supply_list_id") ?? 0;
    //     $related_to = $this->request->getPost("related_to");
    //     $related_id = $this->request->getPost("related_id");
    //     if ($this->request->getPost("related_to")) {
    //         $this->supplyListModel->insert_update_supplylist($supplyListId, $supplier_id, $related_to, $related_id);
    //     }
    //     return $this->response->redirect(url_to("erp/supplier/supplier-view/" . $supplier_id));
    // }

    public function suppliersupplylist($supplier_id)
    {
        $supplyListId = $this->request->getPost("supply_list_id") ?? 0;
        $related_to   = $this->request->getPost("related_to");
        $related_id   = $this->request->getPost("related_id");

        if ($this->request->getPost("related_to")) {
            $this->supplyListModel->insert_update_supplylist($supplyListId, $supplier_id, $related_to, $related_id);
        }

        return $this->response->redirect(url_to('erp.supplier.view', $supplier_id));
    }
    public function ajaxSupplyListResponse()
    {
        // $related_to = $this->request->getPost("related_to");
        // $related_id = $this->request->getPost("related_id");
        $supplier_id = $this->request->getGet("supplierid");
        // $response = $this->supplyListModel->get_suppliersupplylist_datatable($supplier_id, $related_to, $related_id);
        $response = $this->supplyListModel->get_suppliersupplylist_datatable($supplier_id);

        return $this->response->setJSON($response);
    }


    public function ajaxFetchSupplyList($supply_list_id)
    {
        $response = [];

        $query = $this->db->table('supply_list')
            ->select('supply_list_id, related_id, related_to, COALESCE(CONCAT(raw_materials.name, \' \', raw_materials.code), CONCAT(semi_finished.name, \' \', semi_finished.code)) AS product')
            ->join('raw_materials', 'related_to=\'raw_material\' AND related_id=raw_materials.raw_material_id', 'left')
            ->join('semi_finished', 'related_to=\'semi_finished\' AND related_id=semi_finished.semi_finished_id', 'left')
            ->where('supply_list_id', $supply_list_id)
            ->get()
            ->getRow();

        if (!empty($query)) {
            $response['error'] = 0;
            $response['data'] = $query;
        } else {
            $response['error'] = 1;
        }

        return $this->response->setJSON($response);
    }

    public function supplierSupplyListExport()
    {
        $type = $this->request->getGet('export');
        $config = [];
        $config['title'] = "Supply List";

        if ($type == "pdf") {
            $config['column'] = ["Product Type", "Product"];
        } else {
            $config['column'] = ["Product Type", "Product"];
            $config['column_data_type'] = [ExporterConst::E_STRING, ExporterConst::E_STRING];
        }
        $supplier_id = $this->request->getGet("supplierid");
        $config['data'] = $this->supplyListModel->get_supplier_supply_list_export($type, $supplier_id);

        if ($type == "pdf") {
            Exporter::export(ExporterConst::PDF, $config);
        } elseif ($type == "excel") {
            Exporter::export(ExporterConst::EXCEL, $config);
        } elseif ($type == "csv") {
            Exporter::export(ExporterConst::CSV, $config);
        }
    }

    public function supplierSegment($supplier_id)
    {
        if ($this->request->getPost("formsubmit")) {
            $this->supplierSegmentModel->update_supplier_segment($supplier_id);
        }

        return $this->response->redirect(url_to('erp.supplier.view', $supplier_id));
    }

    // NOT IMPLEMENTED
    public function supplier_delete($supplier_id)
    {
        // Implementation for supplier delete if needed
    }

    public function supplierExport()
    {
        $type = $this->request->getGet('export');
        $config = [];
        $config['title'] = "Suppliers";
        // $exporterConst =new ExporterConst();
        if ($type == "pdf") {
            $config['column'] = ["Name", "Email", "Phone", "Position", "Company", "Source", "Groups"];
        } else {
            $config['column'] = ["Name", "Email", "Phone", "Fax Number", "Office Number", "Position", "Company", "GST", "Source", "Groups", "Active", "Address", "City", "State", "Country", "Zipcode", "Website", "Payment Terms", "Description"];
            $E_STRING = 'string';
            $config['column_data_type'] = [
                $E_STRING, $E_STRING, $E_STRING, $E_STRING,
                $E_STRING, $E_STRING, $E_STRING, $E_STRING,
                $E_STRING, $E_STRING, $E_STRING, $E_STRING,
                $E_STRING, $E_STRING, $E_STRING, $E_STRING,
                $E_STRING, $E_STRING
            ];
        }
        $config['data'] = $this->suppliersModel->get_suppliers_export($type);
        $export = new Exporter();
        $EXCEL = 'excel';
        $PDF = 'pdf';
        $CSV = 'csv';
        if ($type == "pdf") {
            $export->export($PDF, $config);
        } elseif ($type == "excel") {

            $export->export($EXCEL, $config);
        } elseif ($type == "csv") {
            $export->export($CSV, $config);
        }
    }

    public function supplierImport()
    {
        helper('form');
        $importer = Importer::init(AbstractImporter::SUPPLIER, AbstractImporter::CSV);
        if ($this->request->isAJAX()) {
            // Handle AJAX request if needed
        } elseif ($this->request->getPost() === 'post') {
            $file = $this->request->getFile('csvfile');

            if ($file->isValid() && $file->getClientExtension() === 'csv') {
                $path = get_tmp_path();
                $filename =  get_rand_str() . '.csv';
                $file->move($path, $filename);
                $fullPath = $path . $filename;

                $data = [
                    'source_id' => $this->request->getPost("supplier_source"),
                    'created_at' => time(),
                    'created_by' => get_user_id(),
                ];

                $retval = $importer->import($fullPath, $data);

                $config['title'] = "Supplier Import";
                $config['ref_link'] = "";

                if ($retval == -1) {
                    $config['log_text'] = "[ Supplier failed to import ]";
                    session()->setFlashdata("op_error", "Supplier failed to import");
                    log_activity($config);
                } elseif ($retval == 0) {
                    $config['log_text'] = "[ Supplier partially imported ]";
                    session()->setFlashdata("op_success", "Supplier partially imported");
                    log_activity($config);
                    return $this->response->redirect(url_to('erp.supplier.page'));
                } elseif ($retval == 1) {
                    $config['log_text'] = "[ Supplier successfully imported ]";
                    session()->setFlashdata("op_success", "Supplier successfully imported");
                    log_activity($config);
                    return $this->response->redirect(url_to('erp.supplier.page'));
                }
            } else {
                session()->setFlashdata("op_error", "File should be in CSV format");
            }
        }

        $data['supplier_groups'] = $this->erpGroupsModel->get_supplier_groups();
        $data['customfields'] = $this->customFieldModel->get_custom_fields_for_form("supplier");
        $data['customfield_chkbx_counter'] = $this->customFieldModel->get_checkbox_counter();
        $data['supplier_sources'] = $this->supplierSourcesModel->get_supplier_sources();
        $data['columns'] = $importer->get_columns();
        $data['page'] = "supplier/supplierimport";
        $data['menu'] = "";
        $data['submenu'] = "";

        return view("erp/index", $data);
    }

    public function supplierImportTemplate()
    {
        $importer = Importer::init(AbstractImporter::SUPPLIER, AbstractImporter::CSV);
        $importer->download_template();
    }

    public function sources()
    {
        helper('form');
        $data['source_datatable_config'] = $this->supplierSourcesModel->get_dtconfig_sources();
        $data['page'] = "supplier/sources";
        $data['menu'] = "supplier";
        $data['submenu'] = "source";

        return view("erp/index", $data);
    }

    public function sourceaddedit()
    {
        $source_id = $this->request->getPost("source_id");
        $source_name = $this->request->getPost("source_name");
        if ($source_name) {
            $this->supplierSourcesModel->insert_update_source($source_id, $source_name);
        }

        return $this->response->redirect(url_to("erp.supplier.sources"));
    }

    public function ajaxSourcesResponse()
    {
        $response = array();
        $response = $this->supplierSourcesModel->get_datatable_sources();
        return $this->response->setJSON($response);
    }

    public function ajaxFetchSource($source_id)
    {
        $response = [];
        $query = "SELECT source_id, source_name FROM supplier_sources WHERE source_id=?";
        $result = $this->db->query($query, [$source_id])->getRow();

        if (!empty($result)) {
            $response['error'] = 0;
            $response['data'] = $result;
        } else {
            $response['error'] = 1;
        }

        return $this->response->setJSON($response);
    }

    public function sourceExport()
    {
        $type = $this->request->getGet('export');
        $config = array();
        $config['title'] = "Supplier Sources";

        if ($type == "pdf") {
            $config['column'] = array("Source Name");
        } else {
            $config['column'] = array("Source Name");
            $config['column_data_type'] = array(ExporterConst::E_STRING);
        }

        $config['data'] = $this->supplierSourcesModel->get_sources_export($type);

        if ($type == "pdf") {
            Exporter::export(ExporterConst::PDF, $config);
        } else if ($type == "excel") {
            Exporter::export(ExporterConst::EXCEL, $config);
        } else if ($type == "csv") {
            Exporter::export(ExporterConst::CSV, $config);
        }
    }

    public function sourcedelete($source_id)
    {
        $this->suppliersModel->delete_source($source_id);
        return $this->response->redirect(url_to("erp.supplier.sources"));
    }

    public function segments()
    {

        $data['segment_datatable_config'] = $this->supplierSegmentModel->get_dtconfig_segments();
        $data['page'] = "supplier/segments";
        $data['menu'] = "supplier";
        $data['submenu'] = "segment";
        echo view("erp/index", $data);
    }

    public function ajaxSegmentsResponse()
    {
        $response = $this->supplierSegmentModel->get_datatable_segments();
        return $this->response->setJSON($response);
    }
    ///////////////////////////////////////////////////////////
    public function segmentadd()
    {
        $key = $this->request->getPost("segment_key");
        $values = $this->request->getPost("segment_value");
        if ($this->request->getPost("segment_key")) {
            if ($this->supplierSegmentModel->insert_segment($key, $values)) {
                return $this->response->redirect(url_to('erp.supplier.segments'));
            } else {
                return $this->response->redirect(url_to('erp.supplier.segmentsadd'));
            }
        }

        $data['page'] = "supplier/segmentadd";
        $data['menu'] = "supplier";
        $data['submenu'] = "segment";
        return view("erp/index", $data);
    }

    public function segmentedit($segment_id)
    {
        $key = $this->request->getPost("segment_key");
        $values = $this->request->getPost("segment_value");
        if ($this->request->getPost("segment_key")) {
            if ($this->supplierSegmentModel->update_segment($segment_id, $key, $values)) {
                return $this->response->redirect(url_to('erp.supplier.segments'));
            } else {
                return $this->response->redirect(url_to('erp.supplier.segmentsedit' . $segment_id));
                // return redirect()->to("erp/supplier/segmentedit/{$segment_id}");
            }
        }

        $data['segment'] = $this->supplierSegmentModel->get_segment_by_id($segment_id);

        if (empty($data['segment'])) {
            session()->setFlashdata("op_error", "Segment Not Found");
            return redirect()->to("erp/supplier/segments");
        }

        $data['segment_id'] = $segment_id;
        $data['page'] = "supplier/segmentedit";
        $data['menu'] = "supplier";
        $data['submenu'] = "segment";
        return view("erp/index", $data);
    }

    public function ajaxSegmentKeyUnique()
    {
        $segment_key = $this->request->getGet('data');
        $id = $this->request->getGet('id') ?? 0;

        $builder = $this->db->table('supplier_segments');
        $builder->select('segment_key');
        $builder->where('segment_key', $segment_key);

        if (!empty($id)) {
            $builder->where('segment_id <>', $id);
        }

        $check = $builder->get()->getRow();

        $response = [];
        if (empty($check)) {
            $response['valid'] = 1;
        } else {
            $response['valid'] = 0;
            $response['msg'] = "Segment Key already exists";
        }

        return $this->response->setJSON($response);
    }

    public function segmentdelete($segment_id)
    {
        $this->supplierSegmentModel->segmentdelete($segment_id);

        return $this->response->redirect(url_to("erp.supplier.segments"));
    }

    public function segmentExport()
    {
        $type = $_GET['export'];
        $config = array();
        $config['title'] = "Supplier Segments";
        if ($type == "pdf") {
            $config['column'] = array("Segment Key", "Segment Value");
        } else {
            $config['column'] = array("Segment Key", "Segment Value");
            $config['column_data_type'] = array(ExporterConst::E_STRING, ExporterConst::E_STRING);
        }
        $config['data'] = $this->supplierSegmentModel->get_segment_export($type);
        if ($type == "pdf") {
            Exporter::export(ExporterConst::PDF, $config);
        } else if ($type == "excel") {
            Exporter::export(ExporterConst::EXCEL, $config);
        } else if ($type == "csv") {
            Exporter::export(ExporterConst::CSV, $config);
        }
    }
    ////////////////////////////////////////////////
    public function selectionrules()
    {
        $data['selectionrule_datatable_config'] = $this->selectionRuleModel->get_dtconfig_selectionrule();
        $data['page'] = "supplier/selectionrule";
        $data['menu'] = "supplier";
        $data['submenu'] = "selectionrule";
        return view('erp/index', $data);
    }

    public function selectionruleadd()
    {
        $rule_name = $this->request->getPost("rule_name");
        $description = $this->request->getPost("description");
        if ($rule_name) {
            if ($this->selectionRuleModel->insert_selection_rule($rule_name, $description)) {
                return $this->response->redirect(url_to("erp.supplier.selectionrules"));
            } else {
                return $this->response->redirect(url_to("erp.supplier.selectionruleadd"));
            }
        }

        $data['segments'] = $this->supplierSegmentModel->get_all_segments();

        if (empty($data['segments'])) {
            $this->session->setFlashdata("op_error", "Segments Not Available");
            return $this->response->redirect(url_to("erp.supplier.segments"));
        }

        $data['page'] = "supplier/selectionruleadd";
        $data['menu'] = "supplier";
        $data['submenu'] = "selectionrule";
        return view('erp/index', $data);
    }

    public function ajaxRuleNameUnique()
    {
        $rule_name = $this->request->getGet('data');
        $id = $this->request->getGet('id') ?? 0;
        $query = "SELECT rule_name FROM selection_rule WHERE rule_name='$rule_name' ";
        if (!empty($id)) {
            $query = "SELECT rule_name FROM selection_rule WHERE rule_name='$rule_name' AND rule_id<>$id";
        }
        $check = $this->db->query($query)->getRow();
        $response = array();
        if (empty($check)) {
            $response['valid'] = 1;
        } else {
            $response['valid'] = 0;
            $response['msg'] = "Rule Name already exists";
        }
        return $this->response->setJSON($response);
    }

    public function ajaxSelectionruleResponse()
    {
        $response = $this->selectionRuleModel->get_selectionrule_datatable();
        return $this->response->setJSON($response);
    }

    public function selectionruleExport()
    {
        $type = $this->request->getGet('export');
        $config = array();
        $config['title'] = "Selection Rules";
        if ($type == "pdf") {
            $config['column'] = array("Rule Name", "Created On", "Description");
        } else {
            $config['column'] = array("Rule Name", "Created On", "Description");
            $config['column_data_type'] = array(ExporterConst::E_STRING, ExporterConst::E_STRING, ExporterConst::E_STRING);
        }
        $config['data'] = $this->selectionRuleModel->get_selectionrule_export($type);
        if ($type == "pdf") {
            Exporter::export(ExporterConst::PDF, $config);
        } else if ($type == "excel") {
            Exporter::export(ExporterConst::EXCEL, $config);
        } else if ($type == "csv") {
            Exporter::export(ExporterConst::CSV, $config);
        }
    }

    public function selectionruleedit($rule_id)
    {
        $rule_name = $this->request->getPost("rule_name");
        $description = $this->request->getPost("description");
        if ($rule_name) {
            if ($this->selectionRuleModel->update_selection_rule($rule_id, $rule_name, $description)) {
                return $this->response->redirect(url_to("erp.supplier.selectionrules"));
            } else {
                return $this->response->redirect(url_to("erp.supplier.selectionruleedit" . $rule_id));
            }
        }

        $data['selectionrule'] = $this->selectionRuleModel->get_selectionrule_by_id($rule_id);

        if (empty($data['selectionrule'])) {
            $this->session->setFlashdata("op_error", "Selection Rule Not Available");
            return $this->response->redirect(url_to("erp.supplier.selectionrules"));
        }

        $data['rule_id'] = $rule_id;
        $data['selectionrule_segments'] = $this->selectionRuleSegmentModel->get_selectionrule_segment($rule_id);
        $data['segments'] = $this->selectionRuleModel->get_all_segments();
        $data['page'] = "supplier/selectionruleedit";
        $data['menu'] = "supplier";
        $data['submenu'] = "selectionrule";
        return view("erp/index", $data);
    }

    public function selectionruledelete($rule_id)
    {
        $this->selectionRuleSegmentModel->delete_selectionrule($rule_id);
        return $this->response->redirect(url_to("erp.supplier.selectionrules"));
    }
}
