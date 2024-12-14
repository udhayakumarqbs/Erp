<?php

namespace App\Controllers\Erp;

use App\Controllers\BaseController;
use App\Libraries\Exporter;
use App\Libraries\Exporter\ExporterConst;
use App\Libraries\Importer;
use App\Libraries\Importer\AbstractImporter;
use App\Models\Erp\AmenityModel;
use App\Models\Erp\AttachmentModel;
use App\Models\Erp\CustomFieldModel;
use App\Models\Erp\CustomFieldValueModel;
use App\Models\Erp\ErpGroupsModel;
use App\Models\Erp\BrandsModel;
use App\Models\Erp\FinishedGoodsModel;
use App\Models\Erp\InventoryServicesModel;
use App\Models\Erp\InventoryWarehouseModel;
use App\Models\Erp\PriceListModel;
use App\Models\Erp\PropertiesModel;
use App\Models\Erp\PropertyTypeModel;
use App\Models\Erp\PropertyUnitModel;
use App\Models\Erp\UnitModel;
use App\Models\Erp\RawMaterialModel;
use App\Models\Erp\SemiFinishedModel;
use App\Models\Erp\StockAlertsModel;
use App\Models\Erp\TaxesModel;
use App\Models\Erp\Warehouse\WarehouseModel;


class InventoryController extends BaseController
{
    public $data;
    protected $db;
    protected $session;
    public $brandsModel;
    public $unitModel;
    public $erpGroupsModel;
    public $warehouseModel;
    public $rawMaterialModel;
    public $customFieldModel;
    public $inventoryWarehouseModel;
    public $customFieldValueModel;
    public $attachmentModel;
    public $stockAlertsModel;
    public $semiFinishedModel;
    public $finishedGoodsModel;
    public $priceListModel;
    public $taxesModel;
    public $inventoryServicesModel;
    public $propertyTypeModel;
    public $amenityModel;
    public $propertiesModel;
    public $propertyUnitModel;

    public function __construct()
    {
        $this->session = \Config\Services::session();
        $this->db = \Config\Database::connect();
        $this->brandsModel = new BrandsModel();
        $this->unitModel = new UnitModel();
        $this->erpGroupsModel = new ErpGroupsModel();
        $this->warehouseModel = new WarehouseModel();
        $this->rawMaterialModel = new RawMaterialModel();
        $this->customFieldModel = new CustomFieldModel();
        $this->inventoryWarehouseModel = new InventoryWarehouseModel();
        $this->customFieldValueModel = new CustomFieldValueModel();
        $this->attachmentModel = new AttachmentModel();
        $this->stockAlertsModel = new StockAlertsModel();
        $this->semiFinishedModel = new SemiFinishedModel();
        $this->finishedGoodsModel = new FinishedGoodsModel();
        $this->priceListModel = new PriceListModel();
        $this->taxesModel = new TaxesModel();
        $this->inventoryServicesModel = new InventoryServicesModel();
        $this->propertyTypeModel = new PropertyTypeModel();
        $this->amenityModel = new AmenityModel();
        $this->propertiesModel = new PropertiesModel();
        $this->propertyUnitModel = new PropertyUnitModel();
        helper(['erp', 'form']);
    }


    public function rawmaterials()
    {
        $this->data['units'] = $this->unitModel->get_units();
        $this->data['brands'] = $this->brandsModel->get_brands();
        $this->data['rawmaterial_groups'] = $this->erpGroupsModel->get_rawmaterials_groups();
        $this->data['warehouses'] = $this->warehouseModel->get_warehouses();
        $this->data['rawmaterial_datatable_config'] = $this->rawMaterialModel->get_dtconfig_rawmaterials();
        $this->data['page'] = "inventory/rawmaterials";
        $this->data['menu'] = "inventory";
        $this->data['submenu'] = "rawmaterial";
        return view("erp/index", $this->data);
    }

    public function ajaxRawmaterialsResponse()
    {
        $response = array();
        $response = $this->rawMaterialModel->get_rawmaterials_datatable();
        return $this->response->setJSON($response);
    }

    public function rawmaterialsExport()
    {
        $type = $_GET['export'];
        $config = array();
        $config['title'] = "Raw Materials";
        if ($type == "pdf") {
            $config['column'] = array("Name", "Code", "Group", "Unit", "Brand", "Warehouses");
        } else {
            $config['column'] = array("Name", "Code", "Group", "Unit", "Brand", "Warehouses", "Short Description", "Long Description");
            $config['column_data_type'] = array(ExporterConst::E_STRING, ExporterConst::E_STRING, ExporterConst::E_STRING, ExporterConst::E_STRING, ExporterConst::E_STRING, ExporterConst::E_STRING, ExporterConst::E_STRING, ExporterConst::E_STRING);
        }
        $config['data'] = $this->rawMaterialModel->get_rawmaterials_export($type);
        if ($type == "pdf") {
            Exporter::export(ExporterConst::PDF, $config);
        } else if ($type == "excel") {
            Exporter::export(ExporterConst::EXCEL, $config);
        } else if ($type == "csv") {
            Exporter::export(ExporterConst::CSV, $config);
        }
    }

    public function rawmaterialadd()
    {
        $warehouses = $this->request->getPost("warehouses");
        $customfield_chkbx_counter = $this->request->getPost("customfield_chkbx_counter");
        if ($this->request->getPost("name")) {
            $data['inserted'] = false;
            $data['name'] = $this->request->getPost("name");
            $data['code'] = $this->request->getPost("code");
            $data['unit'] = $this->request->getPost("unit");
            $data['brand'] = $this->request->getPost("brand");
            $data['group'] = $this->request->getPost("group");
            $data['short_desc'] = $this->request->getPost("short_desc");
            $data['long_desc'] = $this->request->getPost("long_desc");
            $data['created_by'] = get_user_id();

            $logger = \Config\Services::logger();
            $logger->error($data);
            if ($this->rawMaterialModel->insert_rawmaterial($customfield_chkbx_counter, $warehouses, $data)) {
                return $this->response->redirect(url_to('erp.inventory.rawmaterials'));
            } else {
                return $this->response->redirect(url_to('erp.inventory.rawmaterialadd'));
            }
        }

        $this->data['rawMaterial_count'] = $this->rawMaterialModel->get_raw_materials_count();
        $this->data['units'] = $this->unitModel->get_units();
        $this->data['brands'] = $this->brandsModel->get_brands();
        $this->data['rawmaterial_groups'] = $this->erpGroupsModel->get_rawmaterials_groups();
        $this->data['warehouses'] = $this->warehouseModel->get_warehouses();
        $this->data['customfields'] = $this->customFieldModel->get_custom_fields_for_form("raw_material");
        $this->data['customfield_chkbx_counter'] = $this->customFieldModel->get_checkbox_counter();
        $this->data['page'] = "inventory/rawmaterialadd";
        $this->data['menu'] = "inventory";
        $this->data['submenu'] = "rawmaterial";
        return view("erp/index", $this->data);
    }

    public function ajaxRawmaterialCodeunique()
    {
        $code = $_GET['data'];
        $id = $_GET['id'] ?? 0;
        $query = "SELECT code FROM raw_materials WHERE code='$code' ";
        if (!empty($id)) {
            $query = "SELECT code FROM raw_materials WHERE code='$code' AND raw_material_id<>$id";
        }
        $check = $this->db->query($query)->getRow();
        $response = array();
        if (empty($check)) {
            $response['valid'] = 1;
        } else {
            $response['valid'] = 0;
            $response['msg'] = "Item Code already exists";
        }
        return $this->response->setJSON($response);
    }


    public function rawmaterialedit($raw_material_id)
    {
        $post = [
            'name' => $this->request->getPost("name"),
            'code' => $this->request->getPost("code"),
            'unit' => $this->request->getPost("unit"),
            'brand' => $this->request->getPost("brand"),
            'group' => $this->request->getPost("group"),
            'short_desc' => $this->request->getPost("short_desc"),
            'long_desc' => $this->request->getPost("long_desc"),
        ];
        if ($this->request->getPost("name")) {
            $warehouses = $this->request->getPost("warehouses");
            $customfield_chkbx_counter = $this->request->getPost("customfield_chkbx_counter");
            if ($this->rawMaterialModel->update_rawmaterial($raw_material_id, $warehouses, $customfield_chkbx_counter, $post)) {
                return $this->response->redirect(url_to("erp.inventory.rawmaterials"));
            } else {
                return $this->response->redirect(url_to("erp.inventory.rawmaterialedit", $raw_material_id));
            }
        }
        $this->data['raw_material'] = $this->rawMaterialModel->get_rawmaterial_by_id($raw_material_id);
        if (empty($this->data['raw_material'])) {
            $this->session->setFlashdata("op_error", "Raw Material not found");
            return $this->response->redirect(url_to("erp.inventory.rawmaterials"));
        }
        $this->data['rawmaterial_warehouse'] = $this->inventoryWarehouseModel->get_raw_material_warehouse($raw_material_id);
        $this->data['raw_material_id'] = $raw_material_id;
        $this->data['units'] = $this->unitModel->get_units();
        $this->data['brands'] = $this->brandsModel->get_brands();
        $this->data['rawmaterial_groups'] = $this->erpGroupsModel->get_rawmaterials_groups();
        $this->data['warehouses'] = $this->warehouseModel->get_warehouses();
        $this->data['customfields'] = $this->customFieldValueModel->get_custom_fields_for_edit("raw_material", $raw_material_id);
        $this->data['customfield_chkbx_counter'] = $this->customFieldModel->get_checkbox_counter();
        $this->data['page'] = "inventory/rawmaterialedit";
        $this->data['menu'] = "inventory";
        $this->data['submenu'] = "rawmaterial";
        return view("erp/index", $this->data);
    }




    public function rawmaterialview($raw_material_id)
    {
        $data['raw_material'] = $this->rawMaterialModel->get_rawmaterial_for_view($raw_material_id);
        $data['attachments'] = $this->attachmentModel->get_attachments("raw_material", $raw_material_id);
        $data['stock_alert'] = $this->stockAlertsModel->get_stock_alert_for_view("raw_material", $raw_material_id);
        $data['raw_material_id'] = $raw_material_id;
        $data['page'] = "inventory/rawmaterialview";
        $data['menu'] = "inventory";
        $data['submenu'] = "rawmaterial";
        return view("erp/index", $data);
    }

    public function uploadRawmaterialAttachment()
    {
        $id = $this->request->getGet("id") ?? 0;
        $response = array();
        if (!empty($_FILES['attachment']['name'])) {
            $response = $this->attachmentModel->upload_attachment("raw_material", $id);
        } else {
            $response['error'] = 1;
            $response['reason'] = "No file to upload";
        }
        return $this->response->setJSON($response);
    }

    public function rawmaterialDeleteAttachment()
    {
        $id = $this->request->getGet("id") ?? 0;
        $response = array();
        $response = $this->attachmentModel->delete_attachment("raw_material", $id);
        return $this->response->setJSON($response);
    }

    public function rawmaterialStockalert($raw_material_id)
    {
        $post = [
            'alert_qty_level' => $this->request->getPost('alert_qty_level'),
            'alert_before' => $this->request->getPost('alert_before'),
            'recurring' => $this->request->getPost('recurring'),
        ];
        $link = url_to("erp.inventory.rawmaterialview", $raw_material_id);
        $this->stockAlertsModel->insert_update_stockalert("raw_material", $raw_material_id, $link, $post);
        return $this->response->redirect(url_to("erp.inventory.rawmaterialview", $raw_material_id));
    }

    //NOT IMPLEMENTED
    public function rawmaterialdelete($raw_material_id)
    {
        $this->rawMaterialModel->rawmaterialDelete($raw_material_id);
        return $this->response->redirect(url_to('erp.inventory.rawmaterials'));
    }

    public function rawmaterialImport()
    {
        $importer = Importer::init(AbstractImporter::RAWMATERIAL, AbstractImporter::CSV);
        if (!empty($_FILES['csvfile']['name'])) {
            if (strtolower(pathinfo($_FILES['csvfile']['name'], PATHINFO_EXTENSION)) == "csv") {
                $path = get_tmp_path() . get_rand_str() . ".csv";
                if (move_uploaded_file($_FILES['csvfile']['tmp_name'], $path)) {

                    $data['unit_id'] = $this->request->getPost("unit");
                    $data['brand_id'] = $this->request->getPost("brand");
                    $data['group_id'] = $this->request->getPost("group");
                    $data['created_at'] = time();
                    $data['created_by'] = get_user_id();

                    $retval = $importer->import($path, $data);
                    $config['title'] = "Raw Material Import";
                    $config['ref_link'] = "";
                    if ($retval == -1) {
                        $config['log_text'] = "[ Raw Material failed to import ]";
                        $this->session->setFlashdata("op_error", "Raw Material failed to import");
                        log_activity($config);
                    } else if ($retval == 0) {
                        $config['log_text'] = "[ Raw Material partially imported ]";
                        $this->session->setFlashdata("op_success", "Raw Material partially imported");
                        log_activity($config);
                        return $this->response->redirect(url_to("erp.inventory.rawmaterials"));
                    } else if ($retval == 1) {
                        $config['log_text'] = "[ Raw Material successfully imported ]";
                        $this->session->setFlashdata("op_success", "Raw Material successfully imported");
                        log_activity($config);
                        return $this->response->redirect(url_to("erp.inventory.rawmaterials"));
                    }
                } else {
                    $this->session->setFlashdata("op_error", "Internal Error while uploading file");
                }
            } else {
                $this->session->setFlashdata("op_error", "File should be in CSV format");
            }
            return $this->response->redirect(url_to("erp.inventory.rawmaterialimport"));
        }

        $this->data['units'] = $this->unitModel->get_units();
        $this->data['brands'] = $this->brandsModel->get_brands();
        $this->data['rawmaterial_groups'] = $this->erpGroupsModel->get_rawmaterials_groups();
        $this->data['warehouses'] = $this->warehouseModel->get_warehouses();
        $this->data['customfields'] = $this->customFieldModel->get_custom_fields_for_form("raw_material");
        $this->data['customfield_chkbx_counter'] = $this->customFieldModel->get_checkbox_counter();
        $this->data['columns'] = $importer->get_columns();
        $this->data['page'] = "inventory/rawmaterialimport";
        $this->data['menu'] = "inventory";
        $this->data['submenu'] = "rawmaterial";
        return view("erp/index", $this->data);
    }

    public function rawmaterialImportTemplate()
    {
        $importer = Importer::init(AbstractImporter::RAWMATERIAL, AbstractImporter::CSV);
        $importer->download_template();
    }

    public function semifinished()
    {
        $this->data['units'] = $this->unitModel->get_units();
        $this->data['brands'] = $this->brandsModel->get_brands();
        $this->data['semifinished_groups'] = $this->erpGroupsModel->get_semifinished_groups();
        $this->data['warehouses'] = $this->warehouseModel->get_warehouses();
        $this->data['semifinished_datatable_config'] = $this->semiFinishedModel->get_dtconfig_semifinished();
        $this->data['page'] = "inventory/semifinished";
        $this->data['menu'] = "inventory";
        $this->data['submenu'] = "semifinished";
        return view("erp/index", $this->data);
    }

    public function ajaxSemifinishedResponse()
    {
        $response = array();
        $response = $this->semiFinishedModel->get_semifinished_datatable();
        return $this->response->setJSON($response);
    }

    public function semifinishedExport()
    {
        $type = $_GET['export'];
        $config = array();
        $config['title'] = "Semi Finished";
        if ($type == "pdf") {
            $config['column'] = array("Name", "Code", "Group", "Unit", "Brand", "Warehouses");
        } else {
            $config['column'] = array("Name", "Code", "Group", "Unit", "Brand", "Warehouses", "Short Description", "Long Description");
            $config['column_data_type'] = array(ExporterConst::E_STRING, ExporterConst::E_STRING, ExporterConst::E_STRING, ExporterConst::E_STRING, ExporterConst::E_STRING, ExporterConst::E_STRING, ExporterConst::E_STRING, ExporterConst::E_STRING);
        }
        $config['data'] = $this->semiFinishedModel->get_semifinished_export($type);
        if ($type == "pdf") {
            Exporter::export(ExporterConst::PDF, $config);
        } else if ($type == "excel") {
            Exporter::export(ExporterConst::EXCEL, $config);
        } else if ($type == "csv") {
            Exporter::export(ExporterConst::CSV, $config);
        }
    }

    public function semifinishedadd()
    {
        $post = [
            'name' => $this->request->getPost("name"),
            'code' => $this->request->getPost("code"),
            'unit' => $this->request->getPost("unit"),
            'brand' => $this->request->getPost("brand"),
            'group' => $this->request->getPost("group"),
            'short_desc' => $this->request->getPost("short_desc"),
            'long_desc' => $this->request->getPost("long_desc")
        ];
        $warehouses = $this->request->getPost("warehouses");
        $customfield_chkbx_counter = $this->request->getPost("customfield_chkbx_counter");
        if ($this->request->getPost("name")) {
            if ($this->semiFinishedModel->insert_semifinished($post, $warehouses, $customfield_chkbx_counter)) {
                return $this->response->redirect(url_to("erp.inventory.semifinished"));
            } else {
                return $this->response->redirect(url_to("erp.inventory.semifinishedadd"));
            }
        }
        $this->data['semiFinished_count'] = $this->semiFinishedModel->get_semiFinished_count();
        $this->data['units'] = $this->unitModel->get_units();
        $this->data['brands'] = $this->brandsModel->get_brands();
        $this->data['semifinished_groups'] = $this->erpGroupsModel->get_semifinished_groups();
        $this->data['warehouses'] = $this->warehouseModel->get_warehouses();
        $this->data['customfields'] = $this->customFieldModel->get_custom_fields_for_form("semi_finished");
        $this->data['customfield_chkbx_counter'] = $this->customFieldModel->get_checkbox_counter();
        $this->data['page'] = "inventory/semifinishedadd";
        $this->data['menu'] = "inventory";
        $this->data['submenu'] = "semifinished";
        return view("erp/index", $this->data);
    }

    public function ajaxSemifinishedCodeUnique()
    {
        $code = $_GET['data'];
        $id = $_GET['id'] ?? 0;
        $query = "SELECT code FROM semi_finished WHERE code='$code' ";
        if (!empty($id)) {
            $query = "SELECT code FROM semi_finished WHERE code='$code' AND semi_finished_id<>$id";
        }
        $check = $this->db->query($query)->getRow();
        $response = array();
        if (empty($check)) {
            $response['valid'] = 1;
        } else {
            $response['valid'] = 0;
            $response['msg'] = "Item Code already exists";
        }
        return $this->response->setJSON($response);
    }

    public function semifinishededit($semi_finished_id)
    {
        $post = [
            'name' => $this->request->getPost("name"),
            'code' => $this->request->getPost("code"),
            'unit' => $this->request->getPost("unit"),
            'brand' => $this->request->getPost("brand"),
            'group' => $this->request->getPost("group"),
            'short_desc' => $this->request->getPost("short_desc"),
            'long_desc' => $this->request->getPost("long_desc")
        ];
        $warehouses = $this->request->getPost("warehouses");
        $customfield_chkbx_counter = $this->request->getPost("customfield_chkbx_counter");
        if ($this->request->getPost("name")) {
            if ($this->semiFinishedModel->update_semifinished($semi_finished_id, $post, $warehouses, $customfield_chkbx_counter)) {
                return $this->response->redirect(url_to("erp.inventory.semifinished"));
            } else {
                return $this->response->redirect(url_to("erp.inventory.semifinishededit", $semi_finished_id));
            }
        }
        $this->data['semi_finished'] = $this->semiFinishedModel->get_semifinished_by_id($semi_finished_id);
        if (empty($this->data['semi_finished'])) {
            $this->session->setFlashdata("op_error", "Semi Finished not found");
            return $this->response->redirect(url_to("erp.inventory.semifinished"));
        }
        $this->data['semifinished_warehouse'] = $this->inventoryWarehouseModel->get_semi_finished_warehouse($semi_finished_id);
        $this->data['semi_finished_id'] = $semi_finished_id;
        $this->data['units'] = $this->unitModel->get_units();
        $this->data['brands'] = $this->brandsModel->get_brands();
        $this->data['semifinished_groups'] = $this->erpGroupsModel->get_semifinished_groups();
        $this->data['warehouses'] = $this->warehouseModel->get_warehouses();
        $this->data['customfields'] = $this->customFieldValueModel->get_custom_fields_for_edit("semi_finished", $semi_finished_id);
        $this->data['customfield_chkbx_counter'] = $this->customFieldModel->get_checkbox_counter();
        $this->data['page'] = "inventory/semifinishededit";
        $this->data['menu'] = "inventory";
        $this->data['submenu'] = "semifinished";
        return view("erp/index", $this->data);
    }

    public function semifinishedview($semi_finished_id)
    {
        $this->data['semi_finished'] = $this->semiFinishedModel->get_semifinished_for_view($semi_finished_id);
        $this->data['attachments'] = $this->attachmentModel->get_attachments("semi_finished", $semi_finished_id);
        $this->data['stock_alert'] = $this->stockAlertsModel->get_stock_alert_for_view("semi_finished", $semi_finished_id);
        $this->data['semi_finished_id'] = $semi_finished_id;
        $this->data['page'] = "inventory/semifinishedview";
        $this->data['menu'] = "inventory";
        $this->data['submenu'] = "semifinished";
        return view("erp/index", $this->data);
    }

    public function uploadSemifinishedAttachment()
    {
        $id = $this->request->getGet("id") ?? 0;
        $response = array();
        if (!empty($_FILES['attachment']['name'])) {
            $response = $this->attachmentModel->upload_attachment("semi_finished", $id);
        } else {
            $response['error'] = 1;
            $response['reason'] = "No file to upload";
        }
        return $this->response->setJSON($response);
    }

    public function semifinishedDeleteAttachment()
    {
        $attach_id = $this->request->getGet("id") ?? 0;
        $response = array();
        $response = $this->attachmentModel->delete_attachment("semi_finished", $attach_id);
        return $this->response->setJSON($response);
    }

    public function semifinishedStockAlert($semi_finished_id)
    {
        $post = [
            'alert_qty_level' => $this->request->getPost('alert_qty_level'),
            'alert_before' => $this->request->getPost('alert_before'),
            'recurring' => $this->request->getPost('recurring'),
        ];
        $link = "erp/inventory/semifinishedview/" . $semi_finished_id;
        $this->stockAlertsModel->insert_update_stockalert("semi_finished", $semi_finished_id, $link, $post);
        return $this->response->redirect(url_to("erp.inventory.semifinishedview", $semi_finished_id));
    }

    //NOT IMPLEMENTED
    public function semifinisheddelete($semi_finished_id)
    {
        $this->semiFinishedModel->semifinishedDelete($semi_finished_id);
        return $this->response->redirect(url_to("erp.inventory.semifinished"));
    }

    public function semifinishedImport()
    {
        $importer = Importer::init(AbstractImporter::SEMIFINISHED, AbstractImporter::CSV);
        if (!empty($_FILES['csvfile']['name'])) {
            if (strtolower(pathinfo($_FILES['csvfile']['name'], PATHINFO_EXTENSION)) == "csv") {
                $path = get_tmp_path() . get_rand_str() . ".csv";
                if (move_uploaded_file($_FILES['csvfile']['tmp_name'], $path)) {

                    $data['unit_id'] = $this->request->getPost("unit");
                    $data['brand_id'] = $this->request->getPost("brand");
                    $data['group_id'] = $this->request->getPost("group");
                    $data['created_at'] = time();
                    $data['created_by'] = get_user_id();

                    $retval = $importer->import($path, $data);
                    $config['title'] = "Semi Finished Import";
                    $config['ref_link'] = "";
                    if ($retval == -1) {
                        $config['log_text'] = "[ Semi Finished failed to import ]";
                        $this->session->setFlashdata("op_error", "Semi Finished failed to import");
                        log_activity($config);
                    } else if ($retval == 0) {
                        $config['log_text'] = "[ Semi Finished partially imported ]";
                        $this->session->setFlashdata("op_success", "Semi Finished partially imported");
                        log_activity($config);
                        return $this->response->redirect(url_to("erp.inventory.semifinished"));
                    } else if ($retval == 1) {
                        $config['log_text'] = "[ Semi Finished successfully imported ]";
                        $this->session->setFlashdata("op_success", "Semi Finished successfully imported");
                        log_activity($config);
                        return $this->response->redirect(url_to("erp.inventory.semifinished"));
                    }
                } else {
                    $this->session->setFlashdata("op_error", "Internal Error while uploading file");
                }
            } else {
                $this->session->setFlashdata("op_error", "File should be in CSV format");
            }
            return $this->response->redirect(url_to("erp.inventory.semifinishedimport"));
        }

        $this->data['units'] = $this->unitModel->get_units();
        $this->data['brands'] = $this->brandsModel->get_brands();
        $this->data['semifinished_groups'] = $this->erpGroupsModel->get_semifinished_groups();
        $this->data['warehouses'] = $this->warehouseModel->get_warehouses();
        $this->data['customfields'] = $this->customFieldModel->get_custom_fields_for_form("semi_finished");
        $this->data['customfield_chkbx_counter'] = $this->customFieldModel->get_checkbox_counter();
        $this->data['columns'] = $importer->get_columns();
        $this->data['page'] = "inventory/semifinishedimport";
        $this->data['menu'] = "inventory";
        $this->data['submenu'] = "semifinished";
        return view("erp/index", $this->data);
    }

    public function semifinishedimporttemplate()
    {
        $importer = Importer::init(AbstractImporter::SEMIFINISHED, AbstractImporter::CSV);
        $importer->download_template();
    }

    public function finishedgoods()
    {
        $this->data['units'] = $this->unitModel->get_units();
        $this->data['brands'] = $this->brandsModel->get_brands();
        $this->data['finished_goods_groups'] = $this->finishedGoodsModel->get_finished_goods_groups();
        $this->data['warehouses'] = $this->warehouseModel->get_warehouses();
        $this->data['finishedgoods_datatable_config'] = $this->finishedGoodsModel->get_dtconfig_finishedgoods();
        $this->data['page'] = "inventory/finishedgoods";
        $this->data['menu'] = "inventory";
        $this->data['submenu'] = "finishedgood";
        return view("erp/index", $this->data);
    }

    public function ajaxFinishedgoodsResponse()
    {
        $response = array();
        $response = $this->finishedGoodsModel->get_finishedgoods_datatable();
        return $this->response->setJSON($response);
    }

    public function finishedgoodExport()
    {
        $type = $_GET['export'];
        $config = array();
        $config['title'] = "Finished Goods";
        if ($type == "pdf") {
            $config['column'] = array("Name", "Code", "Group", "Unit", "Brand", "Warehouses");
        } else {
            $config['column'] = array("Name", "Code", "Group", "Unit", "Brand", "Warehouses", "Short Description", "Long Description");
            $config['column_data_type'] = array(ExporterConst::E_STRING, ExporterConst::E_STRING, ExporterConst::E_STRING, ExporterConst::E_STRING, ExporterConst::E_STRING, ExporterConst::E_STRING, ExporterConst::E_STRING, ExporterConst::E_STRING);
        }
        $config['data'] = $this->finishedGoodsModel->get_finishedgoods_export($type);
        if ($type == "pdf") {
            Exporter::export(ExporterConst::PDF, $config);
        } else if ($type == "excel") {
            Exporter::export(ExporterConst::EXCEL, $config);
        } else if ($type == "csv") {
            Exporter::export(ExporterConst::CSV, $config);
        }
    }

    public function finishedgoodadd()
    {
        $post = [
            'name' => $this->request->getPost("name"),
            'code' => $this->request->getPost("code"),
            'unit' => $this->request->getPost("unit"),
            'brand' => $this->request->getPost("brand"),
            'group' => $this->request->getPost("group"),
            'short_desc' => $this->request->getPost("short_desc"),
            'long_desc' => $this->request->getPost("long_desc")
        ];
        $warehouses = $this->request->getPost("warehouses");
        $customfield_chkbx_counter = $this->request->getPost("customfield_chkbx_counter");
        if ($this->request->getPost("name")) {
            if ($this->finishedGoodsModel->insert_finishedgood($post, $warehouses, $customfield_chkbx_counter)) {
                return $this->response->redirect(url_to("erp.inventory.finishedgoods"));
            } else {
                return $this->response->redirect(url_to("erp.inventory.finishedgoodadd"));
            }
        }
        $code = $this->finishedGoodsModel->getcode();
        $count= 0;
        if(count($code)> 0){
            $count = $code["id"];
        }
        $this->data["skucode"] = "SKU";
        $this->data["autocode"] = $count;
        $this->data['units'] = $this->unitModel->get_units();
        $this->data['brands'] = $this->brandsModel->get_brands();
        $this->data['finished_goods_groups'] = $this->finishedGoodsModel->get_finished_goods_groups();
        $this->data['warehouses'] = $this->warehouseModel->get_warehouses();
        $this->data['customfields'] = $this->customFieldModel->get_custom_fields_for_form("finished_good");
        $this->data['customfield_chkbx_counter'] = $this->customFieldModel->get_checkbox_counter();
        $this->data['page'] = "inventory/finishedgoodadd";
        $this->data['menu'] = "inventory";
        $this->data['submenu'] = "finishedgood";
        return view("erp/index", $this->data);
    }

    public function ajaxFinishedgoodCodeUnique()
    {
        $code = $_GET['data'];
        $id = $_GET['id'] ?? 0;
        $query = "SELECT code FROM finished_goods WHERE code='$code' ";
        if (!empty($id)) {
            $query = "SELECT code FROM finished_goods WHERE code='$code' AND finished_good_id<>$id";
        }
        $check = $this->db->query($query)->getRow();
        $response = array();
        if (empty($check)) {
            $response['valid'] = 1;
        } else {
            $response['valid'] = 0;
            $response['msg'] = "Item Code already exists";
        }
        return $this->response->setJSON($response);
    }

    public function finishedgoodedit($finished_good_id)
    {

        $post = [
            'name' => $this->request->getPost("name"),
            'code' => $this->request->getPost("code"),
            'unit' => $this->request->getPost("unit"),
            'brand' => $this->request->getPost("brand"),
            'group' => $this->request->getPost("group"),
            'short_desc' => $this->request->getPost("short_desc"),
            'long_desc' => $this->request->getPost("long_desc")
        ];
        $warehouses = $this->request->getPost("warehouses");
        $customfield_chkbx_counter = $this->request->getPost("customfield_chkbx_counter");
        if ($this->request->getPost("name")) {
            if ($this->finishedGoodsModel->update_finishedgood($finished_good_id, $post, $warehouses, $customfield_chkbx_counter)) {
                return $this->response->redirect(url_to("erp.inventory.finishedgoods"));
            } else {
                return $this->response->redirect(url_to("erp.inventory.finishedgoodedit", $finished_good_id));
            }
        }
        $this->data['finished_good'] = $this->finishedGoodsModel->get_finishedgood_by_id($finished_good_id);
        if (empty($this->data['finished_good'])) {
            $this->session->setFlashdata("op_error", "Finished Good not found");
            return $this->response->redirect(url_to("erp.inventory.finishedgoods"));
        }
        $this->data['finishedgood_warehouse'] = $this->finishedGoodsModel->get_finished_good_warehouse($finished_good_id);
        $this->data['finished_good_id'] = $finished_good_id;
        $this->data['units'] = $this->unitModel->get_units();
        $this->data['brands'] = $this->brandsModel->get_brands();
        $this->data['finished_goods_groups'] = $this->erpGroupsModel->get_finished_goods_groups();
        $this->data['warehouses'] = $this->warehouseModel->get_warehouses();
        $this->data['customfields'] = $this->customFieldValueModel->get_custom_fields_for_edit("finished_good", $finished_good_id);
        $this->data['customfield_chkbx_counter'] = $this->customFieldModel->get_checkbox_counter();
        $this->data['page'] = "inventory/finishedgoodedit";
        $this->data['menu'] = "inventory";
        $this->data['submenu'] = "finishedgood";
        return view("erp/index", $this->data);
    }

    public function finishedgoodview($finished_good_id)
    {
        $this->data['finished_good'] = $this->finishedGoodsModel->get_finishedgood_for_view($finished_good_id);
        $this->data['attachments'] = $this->attachmentModel->get_attachments("finished_good", $finished_good_id);
        $this->data['stock_alert'] = $this->stockAlertsModel->get_stock_alert_for_view("finished_good", $finished_good_id);
        $this->data['finished_good_id'] = $finished_good_id;
        $this->data['page'] = "inventory/finishedgoodview";
        $this->data['menu'] = "inventory";
        $this->data['submenu'] = "finishedgood";
        return view("erp/index", $this->data);
    }

    public function uploadFinishedgoodAttachment()
    {
        $id = $this->request->getGet("id") ?? 0;
        $response = array();
        if (!empty($_FILES['attachment']['name'])) {
            $response = $this->attachmentModel->upload_attachment("finished_good", $id);
        } else {
            $response['error'] = 1;
            $response['reason'] = "No file to upload";
        }
        return $this->response->setJSON($response);
    }

    public function finishedgoodDeleteAttachment()
    {
        $attach_id = $this->request->getGet("id") ?? 0;
        $response = array();
        $response = $this->attachmentModel->delete_attachment("finished_good", $attach_id);
        return $this->response->setJSON($response);
    }

    public function finishedgoodStockAlert($finished_good_id)
    {
        $post = [
            'alert_qty_level' => $this->request->getPost("alert_qty_level"),
            'alert_before' => $this->request->getPost("alert_before"),
            'recurring' => $this->request->getPost("recurring"),
        ];
        $link = url_to("erp.inventory.finishedgoodview", $finished_good_id);
        $this->stockAlertsModel->insert_update_stockalert("finished_good", $finished_good_id, $link, $post);
        return $this->response->redirect(url_to("erp.inventory.finishedgoodview", $finished_good_id));
    }

    //NOT IMPLEMENTED
    public function finishedgooddelete($finished_good_id)
    {
        $this->finishedGoodsModel->finishedgoodDelete($finished_good_id);
        return $this->response->redirect(url_to("erp.inventory.finishedgoods"));
    }

    public function finishedgoodImport()
    {
        $importer = Importer::init(AbstractImporter::FINISHEDGOOD, AbstractImporter::CSV);
        if (!empty($_FILES['csvfile']['name'])) {
            if (strtolower(pathinfo($_FILES['csvfile']['name'], PATHINFO_EXTENSION)) == "csv") {
                $path = get_tmp_path() . get_rand_str() . ".csv";
                if (move_uploaded_file($_FILES['csvfile']['tmp_name'], $path)) {

                    $data['unit_id'] = $this->request->getPost("unit");
                    $data['brand_id'] = $this->request->getPost("brand");
                    $data['group_id'] = $this->request->getPost("group");
                    $data['created_at'] = time();
                    $data['created_by'] = get_user_id();

                    $retval = $importer->import($path, $data);
                    $config['title'] = "Finished Good Import";
                    $config['ref_link'] = "";
                    if ($retval == -1) {
                        $config['log_text'] = "[ Finished Good failed to import ]";
                        $this->session->setFlashdata("op_error", "Finished Good failed to import");
                        log_activity($config);
                    } else if ($retval == 0) {
                        $config['log_text'] = "[ Finished Good partially imported ]";
                        $this->session->setFlashdata("op_success", "Finished Good partially imported");
                        log_activity($config);
                        return $this->response->redirect(url_to("erp.inventory.finishedgoods"));
                    } else if ($retval == 1) {
                        $config['log_text'] = "[ Finished Good successfully imported ]";
                        $this->session->setFlashdata("op_success", "Finished Good successfully imported");
                        log_activity($config);
                        return $this->response->redirect(url_to("erp.inventory.finishedgoods"));
                    }
                } else {
                    $this->session->setFlashdata("op_error", "Internal Error while uploading file");
                }
            } else {
                $this->session->setFlashdata("op_error", "File should be in CSV format");
            }
            return $this->response->redirect(url_to("erp.inventory.finishedgoodimport"));
        }

        $this->data['units'] = $this->unitModel->get_units();
        $this->data['brands'] = $this->brandsModel->get_brands();
        $this->data['finished_goods_groups'] = $this->finishedGoodsModel->get_finished_goods_groups();
        $this->data['warehouses'] = $this->warehouseModel->get_warehouses();
        $this->data['customfields'] = $this->customFieldModel->get_custom_fields_for_form("finished_good");
        $this->data['customfield_chkbx_counter'] = $this->customFieldModel->get_checkbox_counter();
        $this->data['columns'] = $importer->get_columns();
        $this->data['page'] = "inventory/finishedgoodimport";
        $this->data['menu'] = "inventory";
        $this->data['submenu'] = "finishedgood";
        return view("erp/index", $this->data);
    }

    public function finishedgoodimporttemplate()
    {
        $importer = Importer::init(AbstractImporter::FINISHEDGOOD, AbstractImporter::CSV);
        $importer->download_template();
    }

    public function units()
    {
        $unit_name = $this->request->getPost("unit_name");
        $unit_id = $this->request->getPost("unit_id");
        if ($this->request->getPost("unit_name")) {
            $this->unitModel->insert_update_unit($unit_id, $unit_name);
            return $this->response->redirect(url_to("erp.inventory.units"));
        }
        $this->data['unit_datatable_config'] = $this->unitModel->get_dtconfig_units();
        $this->data['page'] = "inventory/units";
        $this->data['menu'] = "inventory";
        $this->data['submenu'] = "unit";
        return view("erp/index", $this->data);
    }


    public function ajaxUnitResponse()
    {
        $response = array();
        $response = $this->unitModel->get_units_datatable();
        return $this->response->setJSON($response);
    }

    public function unitdelete($unit_id)
    {
        $this->unitModel->delete_unit($unit_id);
        return $this->response->redirect(url_to("erp.inventory.units"));
    }

    public function ajaxFetchUnit($unit_id)
    {
        $response = array();
        $query = "SELECT unit_id,unit_name FROM units WHERE unit_id=$unit_id";
        $result = $this->db->query($query)->getRow();
        if (!empty($result)) {
            $response['error'] = 0;
            $response['data'] = $result;
        } else {
            $response['error'] = 1;
        }
        return $this->response->setJSON($response);
    }

    public function unitsExport()
    {
        $type = $_GET['export'];
        $config = array();
        $config['title'] = "Units";
        if ($type == "pdf") {
            $config['column'] = array("Unit Name");
        } else {
            $config['column'] = array("Unit Name");
            $config['column_data_type'] = array(ExporterConst::E_STRING);
        }
        $config['data'] = $this->unitModel->get_units_export($type);
        if ($type == "pdf") {
            Exporter::export(ExporterConst::PDF, $config);
        } else if ($type == "excel") {
            Exporter::export(ExporterConst::EXCEL, $config);
        } else if ($type == "csv") {
            Exporter::export(ExporterConst::CSV, $config);
        }
    }

    public function brands()
    {
        $brand_id = $this->request->getPost("brand_id");
        $brand_name = $this->request->getPost("brand_name");
        if ($brand_name) {
            $this->brandsModel->insert_update_brand($brand_id, $brand_name);
            return $this->response->redirect(url_to("erp.inventory.brands"));
        }
        $this->data['brand_datatable_config'] = $this->brandsModel->get_dtconfig_brands();
        $this->data['page'] = "inventory/brands";
        $this->data['menu'] = "inventory";
        $this->data['submenu'] = "brand";
        return view("erp/index", $this->data);
    }


    public function ajaxBrandResponse()
    {
        $response = array();
        $response = $this->brandsModel->get_brands_datatable();
        return $this->response->setJSON($response);
    }

    public function branddelete($brand_id)
    {
        $this->brandsModel->delete_brand($brand_id);
        return $this->response->redirect(url_to("erp.inventory.brands"));
    }

    public function ajaxFetchBrand($brand_id)
    {
        $response = array();
        $query = "SELECT brand_id,brand_name FROM brands WHERE brand_id=$brand_id";
        $result = $this->db->query($query)->getRow();
        if (!empty($result)) {
            $response['error'] = 0;
            $response['data'] = $result;
        } else {
            $response['error'] = 1;
        }
        return $this->response->setJSON($response);
    }

    public function brandsExport()
    {
        $type = $_GET['export'];
        $config = array();
        $config['title'] = "Brands";
        if ($type == "pdf") {
            $config['column'] = array("Brand Name");
        } else {
            $config['column'] = array("brand Name");
            $config['column_data_type'] = array(ExporterConst::E_STRING);
        }
        $config['data'] = $this->brandsModel->get_brands_export($type);
        if ($type == "pdf") {
            Exporter::export(ExporterConst::PDF, $config);
        } else if ($type == "excel") {
            Exporter::export(ExporterConst::EXCEL, $config);
        } else if ($type == "csv") {
            Exporter::export(ExporterConst::CSV, $config);
        }
    }

    public function pricelist()
    {
        $this->data['pricelist_datatable_config'] = $this->priceListModel->get_dtconfig_pricelist();
        $this->data['page'] = "inventory/pricelist";
        $this->data['menu'] = "inventory";
        $this->data['submenu'] = "pricelist";
        return view("erp/index", $this->data);
    }

    public function pricelistadd()
    {
        $post = [
            'name' => $this->request->getPost('name'),
            'amount' => $this->request->getPost('amount'),
            'tax1' => $this->request->getPost('tax1'),
            'tax2' => $this->request->getPost('tax2'),
            'description' => $this->request->getPost('description'),
        ];

        if ($this->request->getPost("name")) {
            if ($this->priceListModel->insert_pricelist($post)) {
                return $this->response->redirect(url_to("erp.inventory.pricelist"));
            } else {
                return $this->response->redirect(url_to("erp.inventory.pricelistadd"));
            }
        }
        $this->data['taxes'] = $this->taxesModel->get_taxes();
        $this->data['page'] = "inventory/pricelistadd";
        $this->data['menu'] = "inventory";
        $this->data['submenu'] = "pricelist";
        return view("erp/index", $this->data);
    }

    public function ajaxPricelistResponse()
    {
        $response = array();
        $response = $this->priceListModel->get_pricelist_datatable();
        return $this->response->setJSON($response);
    }

    public function ajaxPricelistActive($price_id)
    {
        $response = array();
        $query = "UPDATE price_list SET active=active^1 WHERE price_id=$price_id";
        $this->db->query($query);
        if ($this->db->affectedRows() > 0) {
            $response['error'] = 0;
        } else {
            $response['error'] = 1;
        }
        return $this->response->setJSON($response);
    }

    public function pricelistedit($price_id)
    {
        $post = [
            'name' => $this->request->getPost('name'),
            'amount' => $this->request->getPost('amount'),
            'tax1' => $this->request->getPost('tax1'),
            'tax2' => $this->request->getPost('tax2'),
            'description' => $this->request->getPost('description'),
        ];
        if ($this->request->getPost("name")) {
            if ($this->priceListModel->update_pricelist($price_id, $post)) {
                return $this->response->redirect(url_to("erp.inventory.pricelist"));
            } else {
                return $this->response->redirect(url_to("erp.inventory.pricelistedit", $price_id));
            }
        }
        $this->data['pricelist'] = $this->priceListModel->get_pricelist_by_id($price_id);
        if (empty($this->data['pricelist'])) {
            $this->session->setFlashdata("op_error", "Price List not found");
            return $this->response->redirect(url_to("erp.inventory.pricelist"));
        }
        $this->data['price_id'] = $price_id;
        $this->data['taxes'] = $this->taxesModel->get_taxes();
        $this->data['page'] = "inventory/pricelistedit";
        $this->data['menu'] = "inventory";
        $this->data['submenu'] = "pricelist";
        return view("erp/index", $this->data);
    }

    public function pricelistdelete($price_id)
    {
        $this->priceListModel->delete_pricelist($price_id);
        return $this->response->redirect(url_to("erp.inventory.pricelist"));
    }

    public function pricelistExport()
    {
        $type = $_GET['export'];
        $config = array();
        $config['title'] = "Price List";
        if ($type == "pdf") {
            $config['column'] = array("Name", "Amount", "Tax", "Description", "Active");
        } else {
            $config['column'] = array("Name", "Amount", "Tax", "Description", "Active");
            $config['column_data_type'] = array(ExporterConst::E_STRING, ExporterConst::E_STRING, ExporterConst::E_STRING, ExporterConst::E_STRING, ExporterConst::E_STRING);
        }
        $config['data'] = $this->priceListModel->get_pricelist_export($type);
        if ($type == "pdf") {
            Exporter::export(ExporterConst::PDF, $config);
        } else if ($type == "excel") {
            Exporter::export(ExporterConst::EXCEL, $config);
        } else if ($type == "csv") {
            Exporter::export(ExporterConst::CSV, $config);
        }
    }

    public function services()
    {
        $this->data['service_groups'] = $this->erpGroupsModel->get_services_groups();
        $this->data['service_datatable_config'] = $this->inventoryServicesModel->get_dtconfig_services();
        $this->data['page'] = "inventory/services";
        $this->data['menu'] = "inventory";
        $this->data['submenu'] = "service";
        return view("erp/index", $this->data);
    }

    public function ajaxServicesResponse()
    {
        $response = array();
        $response = $this->inventoryServicesModel->get_services_datatable();
        return $this->response->setJSON($response);
    }

    public function serviceadd()
    {
        $post = [
            'name' => $this->request->getPost("name"),
            'code' => $this->request->getPost("code"),
            'group' => $this->request->getPost("group"),
            'price' => $this->request->getPost("price"),
            'tax1' => $this->request->getPost("tax1"),
            'tax2' => $this->request->getPost("tax2"),
            'short_desc' => $this->request->getPost("short_desc"),
            'long_desc' => $this->request->getPost("long_desc"),
        ];
        $customfield_chkbx_counter = $this->request->getPost("customfield_chkbx_counter");
        if ($this->request->getPost("name")) {
            if ($this->inventoryServicesModel->insert_service($post, $customfield_chkbx_counter)) {
                return $this->response->redirect(url_to("erp.inventory.services"));
            } else {
                return $this->response->redirect(url_to("erp.inventory.serviceadd"));
            }
        }
        $this->data['taxes'] = $this->taxesModel->get_taxes();
        $this->data['service_groups'] = $this->erpGroupsModel->get_services_groups();
        $this->data['customfields'] = $this->customFieldModel->get_custom_fields_for_form("inventory_service");
        $this->data['customfield_chkbx_counter'] = $this->customFieldModel->get_checkbox_counter();
        $this->data['page'] = "inventory/serviceadd";
        $this->data['menu'] = "inventory";
        $this->data['submenu'] = "service";
        return view("erp/index", $this->data);
    }

    public function ajaxServiceCodeUnique()
    {
        $code = $_GET['data'];
        $id = $_GET['id'] ?? 0;
        $query = "SELECT code FROM inventory_services WHERE code='$code' ";
        if (!empty($id)) {
            $query = "SELECT code FROM inventory_services WHERE code='$code' AND invent_service_id<>$id";
        }
        $check = $this->db->query($query)->getRow();
        $response = array();
        if (empty($check)) {
            $response['valid'] = 1;
        } else {
            $response['valid'] = 0;
            $response['msg'] = "Service Code already exists";
        }
        return $this->response->setJSON($response);
    }

    public function serviceedit($invent_service_id)
    {
        $post = [
            'name' => $this->request->getPost("name"),
            'code' => $this->request->getPost("code"),
            'group' => $this->request->getPost("group"),
            'price' => $this->request->getPost("price"),
            'tax1' => $this->request->getPost("tax1"),
            'tax2' => $this->request->getPost("tax2"),
            'short_desc' => $this->request->getPost("short_desc"),
            'long_desc' => $this->request->getPost("long_desc"),
        ];
        $customfield_chkbx_counter = $this->request->getPost("customfield_chkbx_counter");
        if ($this->request->getPost("name")) {
            if ($this->inventoryServicesModel->update_service($invent_service_id, $post, $customfield_chkbx_counter)) {
                return $this->response->redirect(url_to("erp.inventory.services"));
            } else {
                return $this->response->redirect(url_to("erp.inventory.serviceedit", $invent_service_id));
            }
        }
        $this->data['service'] = $this->inventoryServicesModel->get_service_by_id($invent_service_id);
        if (empty($this->data['service'])) {
            $this->session->setFlashdata("op_error", "Service not found");
            return $this->response->redirect(url_to("erp.inventory.services"));
        }
        $this->data['invent_service_id'] = $invent_service_id;
        $this->data['taxes'] = $this->taxesModel->get_taxes();
        $this->data['service_groups'] = $this->erpGroupsModel->get_services_groups();
        $this->data['customfields'] = $this->customFieldModel->get_custom_fields_for_edit("inventory_service", $invent_service_id);
        $this->data['customfield_chkbx_counter'] = $this->customFieldModel->get_checkbox_counter();
        $this->data['page'] = "inventory/serviceedit";
        $this->data['menu'] = "inventory";
        $this->data['submenu'] = "service";
        return view("erp/index", $this->data);
    }

    public function serviceview($invent_service_id)
    {
        $this->data['service'] = $this->inventoryServicesModel->get_service_for_view($invent_service_id);
        $this->data['attachments'] = $this->attachmentModel->get_attachments("inventory_service", $invent_service_id);
        $this->data['invent_service_id'] = $invent_service_id;
        $this->data['page'] = "inventory/serviceview";
        $this->data['menu'] = "inventory";
        $this->data['submenu'] = "service";
        return view("erp/index", $this->data);
    }

    public function uploadServiceAttachment()
    {
        $id = $this->request->getGet("id") ?? 0;
        $response = array();
        if (!empty($_FILES['attachment']['name'])) {
            $response = $this->attachmentModel->upload_attachment("inventory_service", $id);
        } else {
            $response['error'] = 1;
            $response['reason'] = "No file to upload";
        }
        return $this->response->setJSON($response);
    }

    public function serviceDeleteAttachment()
    {
        $attach_id = $this->request->getGet("id") ?? 0;
        $response = array();
        $response = $this->attachmentModel->delete_attachment("inventory_service", $attach_id);
        return $this->response->setJSON($response);
    }

    //NOT IMPLEMENTED
    public function servicedelete($invent_service_id)
    {
        $this->inventoryServicesModel->serviceDelete($invent_service_id);
        return $this->response->redirect(url_to("erp.inventory.services"));
    }

    public function serviceExport()
    {
        $type = $_GET['export'];
        $config = array();
        $config['title'] = "Services";
        if ($type == "pdf") {
            $config['column'] = array("Name", "Code", "Group", "Price", "Tax 1", "Tax 2", "Short Description");
        } else {
            $config['column'] = array("Name", "Code", "Group", "Price", "Tax 1", "Tax 2", "Short Description", "Long Description");
            $config['column_data_type'] = array(ExporterConst::E_STRING, ExporterConst::E_STRING, ExporterConst::E_STRING, ExporterConst::E_STRING, ExporterConst::E_STRING, ExporterConst::E_STRING, ExporterConst::E_STRING, ExporterConst::E_STRING);
        }
        $config['data'] = $this->inventoryServicesModel->get_service_export($type);
        if ($type == "pdf") {
            Exporter::export(ExporterConst::PDF, $config);
        } else if ($type == "excel") {
            Exporter::export(ExporterConst::EXCEL, $config);
        } else if ($type == "csv") {
            Exporter::export(ExporterConst::CSV, $config);
        }
    }

    public function propertytype()
    {
        $type_id = $this->request->getPost("type_id");
        $type_name = $this->request->getPost("type_name");
        $this->logger->error($type_id . ' ' . $type_name);
        if ($this->request->getPost("type_name")) {
            $this->propertyTypeModel->insert_update_propertytype($type_id, $type_name);
            return $this->response->redirect(url_to("erp.inventory.propertytype"));
        }
        $this->data['propertytype_datatable_config'] = $this->propertyTypeModel->get_dtconfig_propertytype();
        $this->data['page'] = "inventory/propertytype";
        $this->data['menu'] = "inventory";
        $this->data['submenu'] = "propertytype";
        return view("erp/index", $this->data);
    }


    public function ajaxPropertytypeResponse()
    {
        $response = array();
        $response = $this->propertyTypeModel->get_propertytype_datatable();
        return $this->response->setJSON($response);
    }

    public function propertytypedelete($type_id)
    {
        if($this->propertyTypeModel->delete_propertytype($type_id)){
            session()->setFlashdata("op_success", "Property Type Deleted Successfully");
            return $this->response->redirect(url_to("erp.inventory.propertytype"));
        }else{
            session()->setFlashdata("op_error", "Failed To Delete Property Type");
            return $this->response->redirect(url_to("erp.inventory.propertytype"));
        }
        
    }

    public function ajaxFetchPropertytype($type_id)
    {
        $response = array();
        $query = "SELECT type_id,type_name FROM propertytype WHERE type_id=$type_id";
        $result = $this->db->query($query)->getRow();
        if (!empty($result)) {
            $response['error'] = 0;
            $response['data'] = $result;
        } else {
            $response['error'] = 1;
        }
        return $this->response->setJSON($response);
    }

    public function propertytypeExport()
    {
        $type = $_GET['export'];
        $config = array();
        $config['title'] = "Property Type";
        if ($type == "pdf") {
            $config['column'] = array("Type Name");
        } else {
            $config['column'] = array("Type Name");
            $config['column_data_type'] = array(ExporterConst::E_STRING);
        }
        $config['data'] = $this->propertyTypeModel->get_propertytype_export($type);
        if ($type == "pdf") {
            Exporter::export(ExporterConst::PDF, $config);
        } else if ($type == "excel") {
            Exporter::export(ExporterConst::EXCEL, $config);
        } else if ($type == "csv") {
            Exporter::export(ExporterConst::CSV, $config);
        }
    }



    public function amenity()
    {
        $amenity_name = $this->request->getPost("amenity_name");
        $amenity_id = $this->request->getPost("amenity_id");
        if ($this->request->getPost("amenity_name")) {
            $this->amenityModel->insert_update_amenity($amenity_id, $amenity_name,);
            return $this->response->redirect(url_to("erp.inventory.amenity"));
        }
        $this->data['amenity_datatable_config'] = $this->amenityModel->get_dtconfig_amenity();
        $this->data['page'] = "inventory/amenity";
        $this->data['menu'] = "inventory";
        $this->data['submenu'] = "amenity";
        return view("erp/index", $this->data);
    }

    public function ajaxAmenityResponse()
    {
        $response = array();
        $response = $this->amenityModel->get_amenity_datatable();
        return $this->response->setJSON($response);
    }

    public function amenitydelete($amenity_id)
    {
        $this->amenityModel->delete_amenity($amenity_id);
        return $this->response->redirect(url_to("erp.inventory.amenity"));
    }

    public function ajaxFetchAmenity($amenity_id)
    {
        $response = array();
        $query = "SELECT amenity_id,amenity_name FROM amenity WHERE amenity_id=$amenity_id";
        $result = $this->db->query($query)->getRow();
        if (!empty($result)) {
            $response['error'] = 0;
            $response['data'] = $result;
        } else {
            $response['error'] = 1;
        }
        return $this->response->setJSON($response);
    }

    public function amenityExport()
    {
        $type = $_GET['export'];
        $config = array();
        $config['title'] = "Amenity";
        if ($type == "pdf") {
            $config['column'] = array("Amenity Name");
        } else {
            $config['column'] = array("Amenity Name");
            $config['column_data_type'] = array(ExporterConst::E_STRING);
        }
        $config['data'] = $this->amenityModel->get_amenity_export($type);
        if ($type == "pdf") {
            Exporter::export(ExporterConst::PDF, $config);
        } else if ($type == "excel") {
            Exporter::export(ExporterConst::EXCEL, $config);
        } else if ($type == "csv") {
            Exporter::export(ExporterConst::CSV, $config);
        }
    }

    public function property()
    {
        $this->data['status'] = $this->amenityModel->get_property_status();
        $this->data['types'] = $this->propertyTypeModel->get_all_propertytype();
        $this->data['property_datatable_config'] = $this->propertiesModel->get_dtconfig_properties();
        $this->data['page'] = "inventory/properties";
        $this->data['menu'] = "inventory";
        $this->data['submenu'] = "property";
        return view("erp/index", $this->data);
    }

    public function ajaxPropertyResponse()
    {
        $response = array();
        $response = $this->propertiesModel->get_properties_datatable();
        return $this->response->setJSON($response);
    }

    public function propertyadd()
    {
        $post = [
            'name' => $this->request->getPost("name"),
            'units' => $this->request->getPost("units"),
            'status' => $this->request->getPost("status"),
            'type_id' => $this->request->getPost("type_id"),
            'address' => $this->request->getPost("address"),
            'city' => $this->request->getPost("city"),
            'state' => $this->request->getPost("state"),
            'country' => $this->request->getPost("country"),
            'zipcode' => $this->request->getPost("zipcode"),
            'construct_start' => $this->request->getPost("construct_start"),
            'construct_end' => $this->request->getPost("construct_end"),
            'description' => $this->request->getPost("description"),
        ];

        $amenity = $this->request->getPost("amenity");
        if ($this->request->getPost("name")) {
            if ($this->propertiesModel->insert_property($post, $amenity)) {
                return $this->response->redirect(url_to('erp.inventory.property'));
            } else {
                return $this->response->redirect(url_to('erp.inventory.propertyadd'));
            }
        }
        $this->data['amenities'] = $this->amenityModel->get_all_amenities();
        $this->data['status'] = $this->propertiesModel->get_property_status();
        $this->data['types'] = $this->propertyTypeModel->get_all_propertytype();
        $this->data['page'] = "inventory/propertyadd";
        $this->data['menu'] = "inventory";
        $this->data['submenu'] = "property";
        return view("erp/index", $this->data);
    }

    public function propertyedit($property_id)
    {
        $post = [
            'name' => $this->request->getPost("name"),
            'units' => $this->request->getPost("units"),
            'status' => $this->request->getPost("status"),
            'type_id' => $this->request->getPost("type_id"),
            'address' => $this->request->getPost("address"),
            'city' => $this->request->getPost("city"),
            'state' => $this->request->getPost("state"),
            'country' => $this->request->getPost("country"),
            'zipcode' => $this->request->getPost("zipcode"),
            'construct_start' => $this->request->getPost("construct_start"),
            'construct_end' => $this->request->getPost("construct_end"),
            'description' => $this->request->getPost("description"),
        ];
        $amenity = $this->request->getPost("amenity");
        if ($this->request->getPost("name")) {
            if ($this->propertiesModel->update_property($property_id, $post, $amenity)) {
                return $this->response->redirect(url_to('erp.inventory.property'));
            } else {
                return $this->response->redirect(url_to("erp.inventory.propertyedit", $property_id));
            }
        }
        $this->data['property'] = $this->propertiesModel->get_property_by_id($property_id);
        if (empty($this->data['property'])) {
            $this->session->setFlashdata("op_error", "Property Not Found");
            return $this->response->redirect(url_to('erp.inventory.property'));
        }
        $this->data['property_id'] = $property_id;
        $this->data['amenities'] = $this->amenityModel->get_all_amenities();
        $this->data['status'] = $this->propertiesModel->get_property_status();
        $this->data['types'] = $this->propertyTypeModel->get_all_propertytype();
        $this->data['page'] = "inventory/propertyedit";
        $this->data['menu'] = "inventory";
        $this->data['submenu'] = "property";
        return view("erp/index", $this->data);
    }

    public function propertyview($property_id)
    {
        $this->data['property'] = $this->propertiesModel->get_property_for_view($property_id);
        if (empty($this->data['property'])) {
            $this->session->setFlashdata("op_error", "Property Not Found");
            return $this->response->redirect(url_to('erp.inventory.property'));
        }
        $this->data['property_id'] = $property_id;
        $this->data['status'] = $this->propertiesModel->get_property_status();
        $this->data['status_bg'] = $this->propertiesModel->get_property_status_bg();
        $this->data['attachments'] = $this->attachmentModel->get_attachments("property", $property_id);
        $this->data['propertyunit_status'] = $this->propertyUnitModel->get_propertyunit_status();
        $this->data['propertyunit_datatable_config'] = $this->propertyUnitModel->get_dtconfig_propertyunit();
        $this->data['page'] = "inventory/propertyview";
        $this->data['menu'] = "inventory";
        $this->data['submenu'] = "property";
        return view("erp/index", $this->data);
    }

    public function uploadPropertyAttachment()
    {
        $id = $this->request->getGet("id") ?? 0;
        $response = array();
        if (!empty($_FILES['attachment']['name'])) {
            $response = $this->attachmentModel->upload_attachment("property", $id);
        } else {
            $response['error'] = 1;
            $response['reason'] = "No file to upload";
        }
        return $this->response->setJSON($response);
    }

    public function propertyDeleteAttachment()
    {
        $attach_id = $this->request->getGet("id") ?? 0;
        $response = array();
        $response = $this->attachmentModel->delete_attachment("property", $attach_id);
        return $this->response->setJSON($response);
    }

    public function ajaxPropertyunitResponse()
    {
        $property_id = $this->request->getGet("property_id");
        $response = array();
        $response = $this->propertyUnitModel->get_propertyunit_datatable($property_id);
        return $this->response->setJSON($response);
    }

    public function propertyunitadd($property_id)
    {
        $post = [
            'unit_name' => $this->request->getPost("unit_name"),
            'floor_no' => $this->request->getPost("floor_no"),
            'area_sqft' => $this->request->getPost("area_sqft"),
            'direction' => $this->request->getPost("direction"),
            'price' => $this->request->getPost("price"),
            'tax1' => $this->request->getPost("tax1"),
            'tax2' => $this->request->getPost("tax2"),
            'description' => $this->request->getPost("description"),
        ];
        if ($this->request->getPost("unit_name")) {
            if ($this->propertyUnitModel->insert_propertyunit($property_id, $post)) {
                return $this->response->redirect(url_to("erp.inventory.propertyview", $property_id));
            } else {
                return $this->response->redirect(url_to("erp.inventory.propertyunitadd", $property_id));
            }
        }
        $this->data['taxes'] = $this->taxesModel->get_taxes();
        $this->data['property_id'] = $property_id;
        $this->data['page'] = "inventory/propertyunitadd";
        $this->data['menu'] = "inventory";
        $this->data['submenu'] = "property";
        return view("erp/index", $this->data);
    }

    public function ajaxpropertyunitNameUnique()
    {
        $unit_name = $_GET['data'];
        $id = $_GET['id'] ?? 0;
        $query = "SELECT unit_name FROM property_unit WHERE unit_name='$unit_name' ";
        if (!empty($id)) {
            $query = "SELECT unit_name FROM property_unit WHERE unit_name='$unit_name' AND prop_unit_id<>$id";
        }
        $check = $this->db->query($query)->getRow();
        $response = array();
        if (empty($check)) {
            $response['valid'] = 1;
        } else {
            $response['valid'] = 0;
            $response['msg'] = "Unit Name already exists";
        }
        return $this->response->setJSON($response);
    }

    public function propertyunitedit($property_id, $prop_unit_id)
    {
        $post = [
            'unit_name' => $this->request->getPost("unit_name"),
            'floor_no' => $this->request->getPost("floor_no"),
            'area_sqft' => $this->request->getPost("area_sqft"),
            'direction' => $this->request->getPost("direction"),
            'price' => $this->request->getPost("price"),
            'tax1' => $this->request->getPost("tax1"),
            'tax2' => $this->request->getPost("tax2"),
            'description' => $this->request->getPost("description"),
        ];
        if ($this->request->getPost("unit_name")) {
            if ($this->propertyUnitModel->update_propertyunit($property_id, $prop_unit_id, $post)) {
                return $this->response->redirect(url_to("erp.inventory.propertyview", $property_id));
            } else {
                return $this->response->redirect(url_to("erp.inventory.propertyunitedit", $property_id, $prop_unit_id));
            }
        }
        $this->data['propertyunit'] = $this->propertyUnitModel->get_propertyunit_by_id($prop_unit_id);
        if (empty($this->data['propertyunit'])) {
            $this->session->setFlashdata("op_error", "Property Unit Not Found");
            return $this->response->redirect(url_to("erp.inventory.propertyview", $property_id));
        }
        $this->data['taxes'] = $this->taxesModel->get_taxes();
        $this->data['property_id'] = $property_id;
        $this->data['prop_unit_id'] = $prop_unit_id;
        $this->data['page'] = "inventory/propertyunitedit";
        $this->data['menu'] = "inventory";
        $this->data['submenu'] = "property";
        return view("erp/index", $this->data);
    }

    public function propertyunitExport()
    {
        $property_id = $this->request->getGet("property_id");
        $type = $_GET['export'];
        $config = array();
        $config['title'] = "Property Units";
        if ($type == "pdf") {
            $config['column'] = array("Name", "Floor No", "Area sqft", "Price", "Tax 1", "Tax 2", "Status");
        } else {
            $config['column'] = array("Name", "Floor No", "Area sqft", "Direction", "Price", "Tax 1", "Tax 2", "Status", "Description");
            $config['column_data_type'] = array(ExporterConst::E_STRING, ExporterConst::E_STRING, ExporterConst::E_STRING, ExporterConst::E_STRING, ExporterConst::E_STRING, ExporterConst::E_STRING, ExporterConst::E_STRING, ExporterConst::E_STRING, ExporterConst::E_STRING);
        }
        $config['data'] = $this->propertyUnitModel->get_propertyunit_export($type, $property_id);
        if ($type == "pdf") {
            Exporter::export(ExporterConst::PDF, $config);
        } else if ($type == "excel") {
            Exporter::export(ExporterConst::EXCEL, $config);
        } else if ($type == "csv") {
            Exporter::export(ExporterConst::CSV, $config);
        }
    }

    //NOT IMPLEMENTED
    public function propertyunitdelete($prop_unit_id)
    {
    }

    //NOT IMPLEMENTED
    public function propertydelete($property_id)
    {
        $this->propertiesModel->propertyDelete($property_id);
        return $this->response->redirect(url_to('erp.inventory.property'));
    }

    public function propertyExport()
    {
        $type = $_GET['export'];
        $config = array();
        $config['title'] = "Properties";
        if ($type == "pdf") {
            $config['column'] = array("Name", "Units", "Type", "Status");
        } else {
            $config['column'] = array("Name", "Units", "Type", "Status", "Construction Start", "Construction End", "Address", "City", "State", "Country", "Zipcode", "Description");
            $config['column_data_type'] = array(ExporterConst::E_STRING, ExporterConst::E_STRING, ExporterConst::E_STRING, ExporterConst::E_STRING, ExporterConst::E_STRING, ExporterConst::E_STRING, ExporterConst::E_STRING, ExporterConst::E_STRING, ExporterConst::E_STRING, ExporterConst::E_STRING, ExporterConst::E_STRING, ExporterConst::E_STRING);
        }
        $config['data'] = $this->propertiesModel->get_property_export($type);
        if ($type == "pdf") {
            Exporter::export(ExporterConst::PDF, $config);
        } else if ($type == "excel") {
            Exporter::export(ExporterConst::EXCEL, $config);
        } else if ($type == "csv") {
            Exporter::export(ExporterConst::CSV, $config);
        }
    }
}
