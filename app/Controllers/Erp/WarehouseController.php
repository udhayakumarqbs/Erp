<?php

namespace App\Controllers\Erp;

use App\Controllers\BaseController;
use App\Models\Erp\Warehouse\WarehouseModel;
use App\Libraries\Exporter;
use App\Libraries\Exporter\ExporterConst;
use App\Models\Erp\Warehouse\GRNModel;
use App\Models\Erp\Warehouse\CurrentStockModel;
use App\Models\Erp\Sale\DispatchModel;

class WarehouseController extends BaseController
{

    public function __construct()
    {
        // parent::__construct();
        helper(['erp']);
        // $WarehouseModel = new WarehouseModel();
    }

    public function index()
    {
        $WarehouseModel = new WarehouseModel();
        $data['warehouse_datatable_config'] = $WarehouseModel->get_dtconfig_warehouses();
        $data['page'] = "warehouse/warehouses";
        $data['menu'] = "warehouse";
        $data['submenu'] = "warehouse";
        return view("erp/index", $data);
    }

    public function warehouseadd()
    {
        $WarehouseModel = new WarehouseModel();
        if ($this->request->getPost("name")) {
            $name = $this->request->getpost("name");
            $address = $this->request->getpost("address");
            $city = $this->request->getpost("city");
            $state = $this->request->getpost("state");
            $country = $this->request->getpost("country");
            $zipcode = $this->request->getpost("zipcode");
            $has_bins = $this->request->getpost("has_bins") ?? 0;
            $aisle_count = $this->request->getpost("aisle_count") ?? 0;
            $racks_per_aisle = $this->request->getpost("racks_per_aisle") ?? 0;
            $shelf_per_rack = $this->request->getpost("shelf_per_rack") ?? 0;
            $bins_per_shelf = $this->request->getpost("bins_per_shelf") ?? 0;
            $description = $this->request->getpost("description");
            $config['title'] = "Wherehouse Add";
            $config['ref_link'] = "";
            if ($WarehouseModel->insert_warehouse($name, $address, $city, $state, $country, $zipcode, $has_bins, $aisle_count, $racks_per_aisle, $shelf_per_rack, $bins_per_shelf, $description)) {
                $config['log_text'] = "[ warehouse created successfully ]";
                log_activity($config);
                return $this->response->redirect(url_to('erp.warehouse.add'));
            } else {
                $config['log_text'] = "[ can't Create warehouse ]";
                log_activity($config);
                return $this->response->redirect(url_to('erp.warehouses'));
            }
        }
        $data['page'] = "warehouse/warehouseadd";
        $data['menu'] = "warehouse";
        $data['submenu'] = "warehouse";
        return view("erp/index", $data);
    }

    public function ajax_warehouse_response()
    {
        $WarehouseModel = new WarehouseModel();
        $response = array();
        $limit = $this->request->getGet("limit");
        $offset = $this->request->getGet("offset");
        $search = $this->request->getGet("search");
        $orderby = $this->request->getGet('orderby');
        $ordercol = $this->request->getGet("ordercol");
        $filterStatus = $this->request->getGet("filterStatus");
        $response = $WarehouseModel->getWarehousesDatatable($limit, $offset, $search, $orderby, $ordercol);
        header("Content-Type:application/json");
        echo json_encode($response);
        exit();
    }

    public function warehousedelete($warehouse_id)
    {
        $WarehouseModel = new WarehouseModel();

        $warehouse = $WarehouseModel->get_warehouse_by_id($warehouse_id);

        if (empty($warehouse)) {
            session()->setFlashdata("op_error", "Warehouse Not Found");
            return $this->response->redirect(url_to('erp.warehouse.delete'));
        }

        // Attempt to delete the warehouse
        $deleted = $WarehouseModel->delete_warehouse($warehouse_id);

        $config['title'] = "Wherehouse Delete";
        if ($deleted) {
            $config['log_text'] = "[ warehouse successfully deleted ]";
            // warehouse successfully deleted
            session()->setFlashdata("op_success", "warehouse successfully deleted");
        } else {
            // Failed to delete warehouse
            session()->setFlashdata("op_error", "warehouse failed to delete");
            $config['log_text'] = "[ warehouse failed to delete ]";
        }
        $config['ref_link'] = "";
        log_activity($config);

        // Redirect to the warehouse list page
        return $this->response->redirect(url_to('erp.warehouses'));
    }



    public function insert_pack()
    {
        $WarehouseModel = new WarehouseModel();
        $pack_name = $this->request->getpost("name");
        $pack_capacity = $this->request->getpost("capacity");
        $pack_width = $this->request->getpost("width");
        $pack_height = $this->request->getpost("height");
        $pack_desc = $this->request->getpost("description");
        $related_to = $this->request->getpost("related_to");
        $related_id = $this->request->getpost("related_id");
        $created_by = get_user_id();

        $query = "INSERT INTO packs(name,capacity,width,height,description,related_to,related_id,created_at,created_by) VALUES(?,?,?,?,?,?,?,?,?) ";
        $WarehouseModel->query($query, array($pack_name, $pack_capacity, $pack_width, $pack_height, $pack_desc, $related_to, $related_id, time(), $created_by));

        $config['title'] = "Pack Insert";
        if (!empty($WarehouseModel->insert_id())) {
            $config['log_text'] = "[ Pack successfully created ]";
            $config['ref_link'] = 'erp/warehouse/packs';
            session()->setFlashdata("op_success", "Pack successfully created");
        } else {
            $config['log_text'] = "[ Pack failed to create ]";
            $config['ref_link'] = 'erp/warehouse/packs';
            session()->setFlashdata("op_error", "Pack failed to create");
        }
        log_activity($config);
    }

    public function WarehouseEdit($warehouse_id)
    {
        $WarehouseModel = new WarehouseModel();

        if ($this->request->getPost("name")) {
            $name = $this->request->getPost("name");
            $address = $this->request->getPost("address");
            $city = $this->request->getPost("city");
            $state = $this->request->getPost("state");
            $country = $this->request->getPost("country");
            $zipcode = $this->request->getPost("zipcode");
            $has_bins = $this->request->getPost("has_bins") ?? 0;
            $aisle_count = $this->request->getPost("aisle_count");
            $racks_per_aisle = $this->request->getPost("racks_per_aisle");
            $shelf_per_rack = $this->request->getPost("shelf_per_rack");
            $bins_per_shelf = $this->request->getPost("bins_per_shelf");
            $description = $this->request->getPost("description");

            $racks_per_aisle = $this->request->getPost("racks_per_aisle");
            $config['title'] = "Warehouse Edit";
            $config['ref_link'] = "";
            log_activity($config);
            if ($WarehouseModel->update_warehouse($warehouse_id, $name, $address, $city, $state, $country, $zipcode, $has_bins, $aisle_count, $racks_per_aisle, $shelf_per_rack, $bins_per_shelf, $description)) {
                $config['log_text'] = "[ Warehouse Updated Successfully ]";
                return $this->response->redirect(url_to("erp.warehouses"));
            } else {
                $config['log_text'] = "[ Can't Updated Warehouse ]";
                return $this->response->redirect(url_to("erp.warehouse.edit", $warehouse_id));
            }
        }

        $data['warehouse'] = $WarehouseModel->get_warehouse_by_id($warehouse_id);

        if (empty($data['warehouse'])) {
            session()->setFlashdata("op_error", "Warehouse not found");
            return redirect()->to("erp/warehouse/warehouses");
        }

        $data['warehouse_id'] = $warehouse_id;
        $data['page'] = "warehouse/warehouseedit";
        $data['menu'] = "warehouse";
        $data['submenu'] = "warehouse";

        return view("erp/index", $data);
    }


    public function WarehouseExport()
    {
        $WarehouseModel = new WarehouseModel();
        $type = $_GET['export'];
        $config = array();
        $config['title'] = "Warehouses";
        if ($type == "pdf") {
            $config['column'] = array("Name", "Address", "City", "State", "Country", "Zipcode", "Has Bins");
        } else {
            $config['column'] = array("Name", "Address", "City", "State", "Country", "Zipcode", "Has Bins", "Aisle Count", "Racks per Aisle", "shelves per Rack", "Bins per shelf");
            $config['column_data_type'] = array(ExporterConst::E_STRING, ExporterConst::E_STRING, ExporterConst::E_STRING, ExporterConst::E_STRING, ExporterConst::E_STRING, ExporterConst::E_STRING, ExporterConst::E_STRING, ExporterConst::E_STRING, ExporterConst::E_STRING, ExporterConst::E_STRING, ExporterConst::E_STRING);
        }
        $config['data'] = $WarehouseModel->get_warehouse_export($type);

        $config['title'] = "Warehouses";
        $config['log_text'] = "[ Warehouse exported Successfully ]";
        $config['ref_link'] = "";
        log_activity($config);
        if ($type == "pdf") {
            Exporter::export(ExporterConst::PDF, $config);
        } else if ($type == "excel") {
            Exporter::export(ExporterConst::EXCEL, $config);
        } else if ($type == "csv") {
            Exporter::export(ExporterConst::CSV, $config);
        }
    }


    //grns

    public function Grns()
    {
        $GRNModel = new GRNModel();
        $data['grn_status'] = $GRNModel->get_grn_status();
        $data['grn_datatable_config'] = $GRNModel->get_dtconfig_grns();
        $data['page'] = "warehouse/grns";
        $data['menu'] = "warehouse";
        $data['submenu'] = "grn";
        return view("erp/index", $data);
    }

    public function ajax_grn_response()
    {
        $GRNModel = new GRNModel();
        $response = array();
        $response = $GRNModel->get_grn_datatable();
        header("Content-Type:application/json");
        echo json_encode($response);
        exit();
    }

    public function GrnExport()
    {
        $GRNModel = new GRNModel();
        $type = $_GET['export'];
        $config = array();
        $config['title'] = "GRN";
        if ($type == "pdf") {
            $config['column'] = array("Order Code", "Supplier", "Warehouse", "Status", "Delivered On", "Remarks");
        } else {
            $config['column'] = array("Order Code", "Supplier", "Warehouse", "Status", "Delivered On", "Remarks");
            $config['column_data_type'] = array(ExporterConst::E_STRING, ExporterConst::E_STRING, ExporterConst::E_STRING, ExporterConst::E_STRING, ExporterConst::E_STRING, ExporterConst::E_STRING);
        }
        $config['data'] = $GRNModel->GrnExport();
        $config['title'] = "Warehouses";
        $config['log_text'] = "[ Warehouse GRN exported Successfully ]";
        $config['ref_link'] = "";
        log_activity($config);
        if ($type == "pdf") {
            Exporter::export(ExporterConst::PDF, $config);
        } else if ($type == "excel") {
            Exporter::export(ExporterConst::EXCEL, $config);
        } else if ($type == "csv") {
            Exporter::export(ExporterConst::CSV, $config);
        }
    }
    public function GrnView($grn_id)
    {
        $GRNModel = new GRNModel();
        $CurrentStockModel = new CurrentStockModel();
        $data['grn'] = $GRNModel->get_grn_for_view($grn_id);
        if (empty($data)) {
            session()->setFlashdata("op_error", "GRN Not Found");
            return $this->response->redirect(url_to("erp.warehouse.grn"));
        }
        // var_dump($data['grn']);
        // exit();
        $data['grn_status'] = $GRNModel->get_grn_status();
        $data['grn_status_bg'] = $GRNModel->get_grn_status_bg();
        $data['order_items'] = $CurrentStockModel->get_order_items($data['grn'][0]['order_id']);
        $data['order_items'] = $GRNModel->get_order_items($data['grn'][0]['order_id']);
        $data['grn_id'] = $grn_id;
        $data['page'] = "warehouse/grnview";
        $data['menu'] = "warehouse";
        $data['submenu'] = "grn";
        return view("erp/index", $data);
    }

    public function GrnUpdate($grn_id)
    {
        $GRNModel = new GRNModel();
        if ($this->request->getpost("delivered_on")) {
            $delivered_on = $this->request->getpost("delivered_on");
            $remarks = $this->request->getpost("remarks");
            $config['title'] = "Warehouse GRN Update";
            $config['ref_link'] = "";
            if ($GRNModel->GrnUpdate($grn_id, $delivered_on, $remarks)) {
                session()->setFlashdata("op_success", "GRN Updated");
                $config['log_text'] = "[ Warehouse GRN Updated Successfully ]";
                log_activity($config);
                return $this->response->redirect(url_to("erp.warehouse.grn"));
            } else {
                $config['log_text'] = "[ Can't Update Warehouse GRN ]";
                log_activity($config);
                session()->setFlashdata("op_error", "GRN Not Updated/Failure");
                return $this->response->redirect(url_to("erp.warehouse.grnupdate"));
            }
        }
        $data['order_items'] = $GRNModel->get_order_items_for_update($grn_id);
        if (empty($data)) {
            session()->setFlashdata("op_error", "GRN Not Found");
            return $this->response->redirect(url_to("erp.warehouse.grn"));
        }
        $data['grn_id'] = $grn_id;
        $data['page'] = "warehouse/grnupdate";
        $data['menu'] = "warehouse";
        $data['submenu'] = "grn";
        return view("erp/index", $data);
    }


    //CurrentStock

    public function CurrentStock()
    {
        $CurrentStockModel = new CurrentStockModel();
        $WarehouseModel = new WarehouseModel();
        $data['currentstock_datatable_config'] = $CurrentStockModel->get_dtconfig_currentstock();
        $data['product_types'] = $WarehouseModel->get_product_types();
        $data['warehouses'] = $WarehouseModel->get_all_warehouses();
        $data['page'] = "warehouse/currentstock";
        $data['menu'] = "warehouse";
        $data['submenu'] = "currentstock";
        return view("erp/index", $data);
    }

    public function ajax_currentstock_response()
    {
        $CurrentStockModel = new CurrentStockModel();
        $response = array();
        $response = $CurrentStockModel->get_currentstock_datatable();
        header("Content-Type:application/json");
        echo json_encode($response);
        exit();
    }


    public function stocktransfer()
    {
        $CurrentStockModel = new CurrentStockModel();
        if ($this->request->getPost("stock_id")) {
            // exit('Not Called'); 
            $CurrentStockModel->stock_transfer();
        }

        // var_dump($_POST);
        // exit();


        return $this->response->redirect(url_to("erp.warehouse.current_stock"));
    }

    public function CurrentStockExport()
    {
        $CurrentStockModel = new CurrentStockModel();
        $type = $_GET['export'];
        $config = array();
        $config['title'] = "Current Stock";
        if ($type == "pdf") {
            $config['column'] = array("Product Type", "Product", "Warehouse", "Stock", "Unit Price", "Amount");
        } else {
            $config['column'] = array("Product Type", "Product", "Warehouse", "Stock", "Unit Price", "Amount");
            $config['column_data_type'] = array(ExporterConst::E_STRING, ExporterConst::E_STRING, ExporterConst::E_STRING, ExporterConst::E_STRING, ExporterConst::E_STRING, ExporterConst::E_STRING);
        }
        $config['data'] = $CurrentStockModel->CurrentStockExport($type);
        if ($type == "pdf") {
            Exporter::export(ExporterConst::PDF, $config);
        } else if ($type == "excel") {
            Exporter::export(ExporterConst::EXCEL, $config);
        } else if ($type == "csv") {
            Exporter::export(ExporterConst::CSV, $config);
        }
    }


    //Manage Stock Model

    public function ManageStock()
    {
        $CurrentStockModel = new CurrentStockModel();
        $data['product_types'] = $CurrentStockModel->get_product_types();
        $data['managestock_datatable_config'] = $CurrentStockModel->get_dtconfig_managestock();
        $data['page'] = "warehouse/managestock";
        $data['menu'] = "warehouse";
        $data['submenu'] = "managestock";
        return view("erp/index", $data);
    }

    public function ajax_managestock_response()
    {
        $CurrentStockModel = new CurrentStockModel();
        $response = array();
        $response = $CurrentStockModel->get_managestock_datatable();
        header("Content-Type:application/json");
        echo json_encode($response);
        exit();
    }

    public function StockAdd()
    {
        $CurrentStockModel = new CurrentStockModel();
        if ($this->request->getpost("sku")) {
            if ($CurrentStockModel->insert_stock()) {
                return $this->response->redirect(url_to("erp.warehouse.managestock"));
            } else {
                return $this->response->redirect(url_to("erp.warehouse.managestockadd"));
            }
        }
        $data['warehouses'] = $CurrentStockModel->get_all_warehouses();
        $data['product_types'] = $CurrentStockModel->get_product_types();
        $data['product_links'] = $CurrentStockModel->get_product_links();
        $data['page'] = "warehouse/stockadd";
        $data['menu'] = "warehouse";
        $data['submenu'] = "managestock";
        return view("erp/index", $data);
    }

    public function StockEdit($pack_unit_id)
    {
        $CurrentStockModel = new CurrentStockModel();
        if ($this->request->getGet("sku")) {



            $config['title'] = "Stock Update";
            $config['ref_link'] = "";
            if ($CurrentStockModel->updatestock($pack_unit_id)) {
                $config['log_text'] = "[ Stocks Updated Successfully ]";
                log_activity($config);
                return $this->response->redirect(url_to("erp.warehouse.managestock"));
            } else {
                $config['log_text'] = "[ Can't update Stock ]";
                log_activity($config);
                return $this->response->redirect(url_to("erp.warehouse.managestock.edit"));
            }
        }

        $data['pack_unit_id'] = $pack_unit_id;
        $data['stock'] = $CurrentStockModel->get_stock_by_id($pack_unit_id);
        if (empty($data['stock'])) {
            session()->setFlashdata("op_error", "Stock Not Found");
            return $this->response->redirect(url_to("erp.warehouse.managestock"));
        }
        $data['page'] = "warehouse/stockedit";
        $data['menu'] = "warehouse";
        $data['submenu'] = "managestock";
        return view("erp/index", $data);
    }


    public function ManageStockExport()
    {
        $CurrentStockModel = new CurrentStockModel();
        $type = $_GET['export'];
        $config = array();
        $config['title'] = "Manage Stock";
        if ($type == "pdf") {
            $config['column'] = array("SKU", "Product Type", "Product", "Bin Name", "Manufactured Date", "Batch No", "Lot No");
        } else {
            $config['column'] = array("SKU", "Product Type", "Product", "Bin Name", "Manufactured Date", "Batch No", "Lot No");
            $config['column_data_type'] = array(ExporterConst::E_STRING, ExporterConst::E_STRING, ExporterConst::E_STRING, ExporterConst::E_STRING, ExporterConst::E_STRING, ExporterConst::E_STRING, ExporterConst::E_STRING);
        }
        $config['data'] = $CurrentStockModel->get_managestock_export($type);
        $config['title'] = "Stock Export";
        $config['log_text'] = "[ Stock exported Successfully ]";
        $config['ref_link'] = "";
        log_activity($config);
        if ($type == "pdf") {
            Exporter::export(ExporterConst::PDF, $config);
        } else if ($type == "excel") {
            Exporter::export(ExporterConst::EXCEL, $config);
        } else if ($type == "csv") {
            Exporter::export(ExporterConst::CSV, $config);
        }
    }


    public function ajax_sku_unique()
    {
        $CurrentStockModel = new CurrentStockModel();
        // var_dump($_GET);
        // exit();
        $sku = $_GET['data'];
        $id = $_GET['id'] ?? 0;
        $query = "SELECT sku FROM pack_unit WHERE sku='$sku' ";
        if (!empty($id)) {
            $query = "SELECT sku FROM pack_unit WHERE sku='$sku' AND pack_unit_id=$id";
        }
        $check = $CurrentStockModel->query($query)->getRow();
        $response = array();
        if (empty($check)) {
            $response['valid'] = 1;
        } else {
            $response['valid'] = 0;
            $response['msg'] = "SKU already exists";
        }
        header("content-type:application/json");
        echo json_encode($response);
        exit();
    }



    public function stockdelete($pack_unit_id)
    {
        $CurrentStockModel = new CurrentStockModel();



        $managestock = $CurrentStockModel->get_manage_stock_by_id($pack_unit_id);


        $logger = \Config\Services::logger();
        $logger->error($managestock);

        if (empty($managestock)) {
            session()->setFlashdata("op_error", "Stock Not Found");
            return $this->response->redirect(url_to('erp.warehouse.managestock'));
        }

        $deleted = $CurrentStockModel->delete_managestock($pack_unit_id);

        if ($deleted) {

            session()->setFlashdata("op_success", "Stock successfully deleted");
        } else {

            session()->setFlashdata("op_error", "Stock failed to delete");
        }

        return $this->response->redirect(url_to('erp.warehouse.managestock'));
    }


    //Dispatch Implemented

    public function DispatchList()
    {
        $DispatchModel = new DispatchModel();
        $data['dispatch_status'] = $DispatchModel->get_dispatch_status();
        $data['dispatch_datatable_config'] = $DispatchModel->get_dtconfig_warehouse_dispatch();
        $data['page'] = "warehouse/dispatch";
        $data['menu'] = "warehouse";
        $data['submenu'] = "dispatch";
        return view("erp/index", $data);
    }


    public function ajax_warehouse_dispatch_response()
    {
        $DispatchModel = new DispatchModel();
        $response = array();
        $limit = $this->request->getGet('limit');
        $offset = $this->request->getGet('offset');
        $search = $this->request->getGet('search') ?? "";
        // Handle 'orderby' possibly being null
        $orderby = $this->request->getGet('orderby') ?? "";
        $orderby = $orderby !== null ? strtoupper($orderby) : "";
        $ordercolPost = $this->request->getGet('ordercol');
        $filterStatus = $this->request->getGet('status');
        // var_dump($_GET);
        // exit();
        $response = $DispatchModel->get_warehouse_dispatch_Datatable($limit, $offset, $search, $orderby, $ordercolPost, $filterStatus);
        header("Content-Type:application/json");
        echo json_encode($response);
        exit();
    }


    //  Add/Create
    public function DispatchWarehouseAdd()
    {
        $DispatchModel = new DispatchModel();
        $session = session();
        $dataPost = [
            'order_code' => $this->request->getPost("order_code"),
            'customer' => $this->request->getPost("customer"),
            'delivery_date' => $this->request->getPost("delivery_date"),
            'status' => $this->request->getPost("dispatch_status"),
            'description' => $this->request->getPost("dispatch_desc"),
            'created_at' => time(),
            'updated_at' => time(),
        ];

        if ($this->request->getPost("name")) {
            if ($DispatchModel->insertdispatch($dataPost)) {
                return $this->response->redirect(url_to('erp.warehouse.dispatch'));
            } else {
                return $this->response->redirect(url_to('warehouse.dispatch.add.post'));
            }
        }

        $data['dispatch_status'] = $DispatchModel->get_dispatch_status();
        $data['dispatch_datatable_config'] = $DispatchModel->get_dtconfig_warehouse_dispatch();
        $data['page'] = "warehouse/dispatchadd";
        $data['menu'] = 'warehouse';
        $data['submenu'] = 'dispatch';

        return view('erp/index', $data);
    }





    // Edit Dispatch
    public function DispatchEdit($dispatch_id)
    {
        $DispatchModel = new DispatchModel();

        if ($this->request->getPost("order_code")) {
            $dataPost = [
                'delivery_date' => $this->request->getPost("delivery_date"),
                'description' => $this->request->getPost("dispatch_desc"),
                'status' => $this->request->getPost("dispatch_status"),
            ];
            if ($DispatchModel->dispatch_edit($dispatch_id, $dataPost)) {
                return $this->response->redirect(url_to('erp.warehouse.dispatch'));
            } else {
                return $this->response->redirect(url_to('erp.dispatch.edit', $dispatch_id));
            }
        }

        $data['dispatch'] = $DispatchModel->get_dispatch_by_id($dispatch_id);

        if (empty($data['dispatch'])) {
            session()->setFlashdata("op_error", "Dispatch Not Found");
            return $this->response->redirect(url_to('erp.warehouse.dispatch'));
        }

        $data['dispatch_id'] = $dispatch_id;
        $data['dispatch_status'] = $DispatchModel->get_dispatch_status();
        $data['page'] = "warehouse/dispatchedit";
        $data['menu'] = "warehouse";
        $data['submenu'] = "dispatch";

        return view('erp/index', $data);
    }




    public function DispatchExport()
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
            $config['column'] = ["Order Code", "Customer", "Delivery Date", "Description", "Status"];
        } else {
            $config['column'] = ["Order Code", "Customer", "Delivery Date", "Description", "Status"];
            $config['column_data_type'] = [ExporterConst::E_STRING, ExporterConst::E_STRING, ExporterConst::E_STRING, ExporterConst::E_STRING, ExporterConst::E_STRING, ExporterConst::E_STRING, ExporterConst::E_STRING, ExporterConst::E_STRING, ExporterConst::E_STRING, ExporterConst::E_STRING, ExporterConst::E_STRING];
        }

        $DispatchModel = new DispatchModel();
        $config['data'] = $DispatchModel->get_dispatch_warehouse_export($type, $limit, $offset, $search, $orderby, $ordercolPost, $status);

        if ($type == "pdf") {
            return Exporter::export(ExporterConst::PDF, $config);
        } else if ($type == "excel") {
            return Exporter::export(ExporterConst::EXCEL, $config);
        } else if ($type == "csv") {
            return Exporter::export(ExporterConst::CSV, $config);
        }
    }

    //Dispatch Delete

    public function DeleteWarehouseDispatch($dispatch_id)
    {
        $DispatchModel = new DispatchModel();

        $dispatch = $DispatchModel->get_dispatch_by_id($dispatch_id);

        if (empty($dispatch)) {
            session()->setFlashdata("op_error", "Dispatch Not Found");
            return redirect()->to('erp/asset/equipments');
        }

        // Attempt to delete the Dispatch
        $deleted = $DispatchModel->Delete_Sale_Dispatch($dispatch_id);

        if ($deleted) {
            // Dispatch successfully deleted
            session()->setFlashdata("op_success", "Dispatch successfully deleted");
        } else {
            // Failed to delete Dispatch
            session()->setFlashdata("op_error", "Dispatch failed to delete");
        }

        // Redirect to the Dispatch list page
        return $this->response->redirect(url_to('erp.warehouse.dispatch'));
    }




}
