<?php
use CodeIgniter\Router\RouteCollection;


/**
 * @var RouteCollection $routes
 */


// $routes->get('/', 'Erp\LoginController::index', ['as' => 'erp.quick_login']);
$routes->get('/', function () {
    return \Config\Services::response()->redirect(url_to('front.login'));
});

//client route
$routes->group('erp/auth/', ['namespace' => 'App\Controllers\Client'], static function ($routes) {
    $routes->get('login', 'ClientloginController::index', ['as' => 'front.login']);
    $routes->post('login/auth', 'ClientloginController::Authenticate_client', ['as' => 'front.auth']);
    $routes->get('log-out', 'ClientloginController::Logout', ['as' => 'front.log.out']);
});

// $routes->group('', ['filter' => ['b_authenticate']], static function ($routes);
$routes->group('erp/auth/', ['namespace' => 'App\Controllers\Erp'], static function ($routes) {
    $routes->get('login/admin', 'LoginController::index', ['as' => 'erp.login']);
    $routes->post('login/admin/auth', 'LoginController::Authenticate', ['as' => 'erp.auth']);
    $routes->get('forgot_password', 'LoginController::ForgotPassword', ['as' => 'erp.forgotpassword']);
    $routes->get('logout', 'LoginController::Logout', ['as' => 'erp.logout']);
});

// Authentication and Login Routes
$routes->group('erp', ['namespace' => 'App\Controllers\Erp', 'filter' => 'my_filter'], static function ($routes) {

    // $routes->group('erp', ['namespace' => 'App\Controllers\Erp'], function ($routes) {

    // Dashboard
    $routes->group('dashboard', function ($routes) {
        $routes->get('/', 'DashboardController::index', ['as' => 'erp.dashboard']);
        $routes->get('order-status', 'DashboardController::orderStatus', ['as' => 'erp.orderstatus']);
        $routes->match(['get', 'post'], 'calender_add', 'DashboardController::calender_add', ['as' => 'erp.calender_modal']);
        $routes->match(['get', 'post'], 'calenderEvent', 'DashboardController::ajax_events', ['as' => 'erp.calenderEventAjax']);
        $routes->match(['get', 'post'], 'calenderEventUpdate', 'DashboardController::ajax_events_update', ['as' => 'erp.calender_modal_update']);
        $routes->match(['get', 'post'], 'calenderEventExist', 'DashboardController::ajax_exist', ['as' => 'erp.calender_exist_modal']);
        $routes->match(['get', 'post'], 'calenderEventDelete', 'DashboardController::event_Delete', ['as' => 'erp.calenderDelete']);
        // $routes->get('project-count', 'DashboardController::projectCount', ['as' => 'erp.projectcount']);
    });

    //////////////      Assets & Equipments    //////////////
    $routes->group('assets', function ($routes) {
        $routes->get('/', 'AssetController::index', ['as' => 'erp.assets']);
        $routes->get('ajax_equipments_response', 'AssetController::ajax_equipments_response', ['as' => 'erp.ajaxresponse.for.equipments']);
        $routes->get('view_equipment/(:num)', 'AssetController::EquipmentView/$1', ['as' => 'erp.equipment.view']);
        $routes->get('add_equipment', 'AssetController::EquipmentAdd', ['as' => 'erp.equipment.add']);
        $routes->post('add_equipment_post', 'AssetController::EquipmentAdd', ['as' => 'erp.equipment.add.post']);
        $routes->get('edit_equipment/(:num)', 'AssetController::EquipmentEdit/$1', ['as' => 'erp.equipment.edit']);
        $routes->post('edit_equipment_post/(:num)', 'AssetController::EquipmentEdit/$1', ['as' => 'erp.equipment.edit.post']);
        $routes->get('delete_equipment/(:num)', 'AssetController::DeleteEquipment/$1', ['as' => 'erp.equipment.delete']);
        $routes->post('upload_equipment_attachment', 'AssetController::Upload_Equipattachment', ['as' => 'erp.equipment.upload.document']);
        $routes->get('delete_equipment_attachment', 'AssetController::Delete_Equipattachment', ['as' => 'erp.equipment.delete.document']);
        $routes->get('ajax_equip_code_unique', 'AssetController::ajaxEquipCodeUnique', ['as' => 'erp.equipment.ajaxEquipCodeUnique']);
        $routes->get('exporter_equipment', 'AssetController::EquipmentsExport', ['as' => 'erp.equipment.exportEquipment']);
    });

    //////////////     Inventory Start         ///////////////
    $routes->group('inventory', function ($routes) {
        $routes->get('raw-materials', 'InventoryController::rawmaterials', ['as' => 'erp.inventory.rawmaterials']);
        $routes->match(['get', 'post'], 'rawmaterials-add', 'InventoryController::rawmaterialadd', ['as' => 'erp.inventory.rawmaterialadd']);
        $routes->match(['get', 'post'], 'rawmaterials-edit/(:num)', 'InventoryController::rawmaterialedit/$1', ['as' => 'erp.inventory.rawmaterialedit']);
        $routes->get('raw-materials-export', 'InventoryController::rawmaterialsExport', ['as' => 'erp.inventory.rawmaterialsexport']);
        $routes->match(['get', 'post'], 'rawmaterials-import', 'InventoryController::rawmaterialImport', ['as' => 'erp.inventory.rawmaterialimport']);
        $routes->get('rawmaterials-import-templete', 'InventoryController::rawmaterialImportTemplate', ['as' => 'erp.inventory.rawmaterialimporttemplate']);
        $routes->get('rawmaterial-code-unique', 'InventoryController::ajaxRawmaterialCodeunique', ['as' => 'erp.inventory.rawmaterialcodeunique']);
        $routes->get('rawmaterials-response', 'InventoryController::ajaxRawmaterialsResponse', ['as' => 'erp.inventory.rawmaterialsresponse']);
        $routes->get('rawmaterials-view/(:num)', 'InventoryController::rawmaterialview/$1', ['as' => 'erp.inventory.rawmaterialview']);
        $routes->get('rawmaterials-delete/(:num)', 'InventoryController::rawmaterialdelete/$1', ['as' => 'erp.inventory.rawmaterialdelete']);
        $routes->get('rawmaterials-attachment-delete', 'InventoryController::rawmaterialDeleteAttachment', ['as' => 'erp.inventory.rawmaterialdeleteattachment']);
        $routes->post('rawmaterials-stockalert/(:num)', 'InventoryController::rawmaterialStockalert/$1', ['as' => 'erp.inventory.rawmaterialstockalert']);
        $routes->post('rawmaterials-attachment', 'InventoryController::uploadRawmaterialAttachment', ['as' => 'erp.inventory.rawmaterialattachment']);
        // .
        //Semifinished
        $routes->get('semifinished', 'InventoryController::semifinished', ['as' => 'erp.inventory.semifinished']);
        $routes->match(['get', 'post'], 'semifinished-add', 'InventoryController::semifinishedadd', ['as' => 'erp.inventory.semifinishedadd']);
        $routes->match(['get', 'post'], 'semifinished-edit/(:num)', 'InventoryController::semifinishededit/$1', ['as' => 'erp.inventory.semifinishededit']);
        $routes->get('semifinished-view/(:num)', 'InventoryController::semifinishedview/$1', ['as' => 'erp.inventory.semifinishedview']);
        $routes->get('semifinished-delete/(:num)', 'InventoryController::semifinisheddelete/$1', ['as' => 'erp.inventory.semifinisheddelete']);
        $routes->match(['get', 'post'], 'semifinished-stock-alert/(:num)', 'InventoryController::semifinishedStockAlert/$1', ['as' => 'erp.inventory.semifinishedstockalert']);
        $routes->get('semifinished-response', 'InventoryController::ajaxSemifinishedResponse', ['as' => 'erp.inventory.semifinishedresponse']);
        $routes->match(['get', 'post'], 'semifinished-import', 'InventoryController::semifinishedImport', ['as' => 'erp.inventory.semifinishedimport']);
        $routes->get('semifinished-code-unique', 'InventoryController::ajaxSemifinishedCodeUnique', ['as' => 'erp.inventory.ajaxsemifinishedcodeunique']);
        $routes->get('semifinished-import-template', 'InventoryController::semifinishedimporttemplate', ['as' => 'erp.inventory.semifinishedimporttemplate']);
        $routes->match(['get', 'post'], 'semifinished-attachment', 'InventoryController::uploadSemifinishedAttachment', ['as' => 'erp.inventory.uploadsemifinishedattachment']);
        $routes->get('semifinished-attachment-delete', 'InventoryController::semifinishedDeleteAttachment', ['as' => 'erp.inventory.semifinisheddeleteattachment']);
        $routes->get('semifinished-export', 'InventoryController::semifinishedExport', ['as' => 'erp.inventory.semifinishedexport']);


        //Finishedgoods
        $routes->get('finishedgoods', 'InventoryController::finishedgoods', ['as' => 'erp.inventory.finishedgoods']);
        $routes->match(['get', 'post'], 'finishedgood-add', 'InventoryController::finishedgoodadd', ['as' => 'erp.inventory.finishedgoodadd']);
        $routes->match(['get', 'post'], 'finishedgood-edit/(:num)', 'InventoryController::finishedgoodedit/$1', ['as' => 'erp.inventory.finishedgoodedit']);
        $routes->get('finishedgood-view/(:num)', 'InventoryController::finishedgoodview/$1', ['as' => 'erp.inventory.finishedgoodview']);
        $routes->get('finishedgood-delete/(:num)', 'InventoryController::finishedgooddelete/$1', ['as' => 'erp.inventory.finishedgooddelete']);
        $routes->match(['get', 'post'], 'finishedgood-import', 'InventoryController::finishedgoodImport', ['as' => 'erp.inventory.finishedgoodimport']);
        $routes->get('finishedgood-response', 'InventoryController::ajaxFinishedgoodsResponse', ['as' => 'erp.inventory.ajaxfinishedgoodsresponse']);
        $routes->match(['get', 'post'], 'finishedgood-stock-alert/(:num)', 'InventoryController::finishedgoodStockAlert/$1', ['as' => 'erp.inventory.finishedgoodstockalert']);
        $routes->get('finishedgood-code-unique', 'InventoryController::ajaxFinishedgoodCodeUnique', ['as' => 'erp.inventory.ajaxfinishedgoodcodeunique']);
        $routes->get('finishedgood-import-template', 'InventoryController::finishedgoodimporttemplate', ['as' => 'erp.inventory.finishedgoodimporttemplate']);
        $routes->get('finishedgood-export', 'InventoryController::finishedgoodExport', ['as' => 'erp.inventory.finishedgoodexport']);
        $routes->match(['get', 'post'], 'finishedgood-attachment', 'InventoryController::uploadFinishedgoodAttachment', ['as' => 'erp.inventory.uploadfinishedgoodattachment']);
        $routes->get('finishedgood-attachment-delete', 'InventoryController::finishedgoodDeleteAttachment', ['as' => 'erp.inventory.finishedgooddeleteattachment']);

        //Services
        $routes->get('services', 'InventoryController::services', ['as' => 'erp.inventory.services']);
        $routes->match(['get', 'post'], 'services-add', 'InventoryController::serviceadd', ['as' => 'erp.inventory.serviceadd']);
        $routes->get('services-response', 'InventoryController::ajaxServicesResponse', ['as' => 'erp.inventory.ajaxservicesresponse']);
        $routes->get('services-code-unique', 'InventoryController::ajaxServiceCodeUnique', ['as' => 'erp.inventory.ajaxservicecodeunique']);
        $routes->get('services-export', 'InventoryController::serviceExport', ['as' => 'erp.inventory.serviceexport']);
        $routes->get('services-view/(:num)', 'InventoryController::serviceview/$1', ['as' => 'erp.inventory.serviceview']);
        $routes->match(['get', 'post'], 'services-edit/(:num)', 'InventoryController::serviceedit/$1', ['as' => 'erp.inventory.serviceedit']);
        $routes->get('services-delete/(:num)', 'InventoryController::servicedelete/$1', ['as' => 'erp.inventory.servicedelete']);
        $routes->match(['get', 'post'], 'services-attachment', 'InventoryController::uploadServiceAttachment', ['as' => 'erp.inventory.uploadserviceattachment']);
        $routes->get('services-attachment-delete', 'InventoryController::serviceDeleteAttachment', ['as' => 'erp.inventory.servicedeleteattachment']);

        //Propertytype
        $routes->match(['get', 'post'], 'propertytype', 'InventoryController::propertytype', ['as' => 'erp.inventory.propertytype']);
        $routes->get('propertytype-delete/(:num)', 'InventoryController::propertytypedelete/$1', ['as' => 'erp.inventory.propertytypedelete']);
        $routes->get('propertytype-response', 'InventoryController::ajaxPropertytypeResponse', ['as' => 'erp.inventory.ajaxpropertytyperesponse']);
        $routes->get('propertytype-fetch/(:num)', 'InventoryController::ajaxFetchPropertytype/$1', ['as' => 'erp.inventory.ajaxfetchpropertytype']);
        $routes->get('propertytype-export', 'InventoryController::propertytypeExport', ['as' => 'erp.inventory.propertytypeexport']);
        $routes->match(['get', 'post'], 'serviceedit/(:num)', 'InventoryController::serviceedit/$1', ['as' => 'erp.inventory.propertytypeedit']);


        //Amenity
        $routes->match(['get', 'post'], 'amenity', 'InventoryController::amenity', ['as' => 'erp.inventory.amenity']);
        $routes->get('amenity-response', 'InventoryController::ajaxAmenityResponse', ['as' => 'erp.inventory.ajaxamenityresponse']);
        $routes->get('amenity-export', 'InventoryController::amenityExport', ['as' => 'erp.inventory.amenityexport']);
        $routes->match(['get', 'post'], 'amenity-fetch/(:num)', 'InventoryController::ajaxFetchAmenity/$1', ['as' => 'erp.inventory.ajaxfetchamenity']);
        $routes->get('amenity-delete/(:num)', 'InventoryController::amenitydelete/$1', ['as' => 'erp.inventory.amenitydelete']);
        //Properties
        $routes->get('property', 'InventoryController::property', ['as' => 'erp.inventory.property']);
        $routes->match(['get', 'post'], 'property-add', 'InventoryController::propertyadd', ['as' => 'erp.inventory.propertyadd']);
        $routes->match(['get', 'post'], 'property-edit/(:num)', 'InventoryController::propertyedit/$1', ['as' => 'erp.inventory.propertyedit']);
        $routes->get('property-view/(:num)', 'InventoryController::propertyview/$1', ['as' => 'erp.inventory.propertyview']);
        $routes->get('property-delete/(:num)', 'InventoryController::propertydelete/$1', ['as' => 'erp.inventory.propertydelete']);
        $routes->match(['get', 'post'], 'property-attachment', 'InventoryController::uploadPropertyAttachment', ['as' => 'erp.inventory.uploadpropertyattachment']);
        $routes->match(['get', 'post'], 'property-delete-attachment', 'InventoryController::propertyDeleteAttachment', ['as' => 'erp.inventory.propertydeleteattachment']);
        $routes->get('property-response', 'InventoryController::ajaxPropertyResponse', ['as' => 'erp.inventory.ajaxpropertyresponse']);
        $routes->get('property-export', 'InventoryController::propertyExport', ['as' => 'erp.inventory.propertyexport']);

        //Property Unit
        $routes->match(['get', 'post'], 'property-add/(:num)', 'InventoryController::propertyunitadd/$1', ['as' => 'erp.inventory.propertyunitadd']);
        $routes->match(['get', 'post'], 'property-delete/(:num)', 'InventoryController::propertyunitdelete/$1', ['as' => 'erp.inventory.propertyunitdelete']);
        $routes->get('property-unit-responsedd', 'InventoryController::ajaxPropertyunitResponse', ['as' => 'erp.inventory.ajaxpropertyunitresponse']);
        $routes->get('property-unit-nameunique', 'InventoryController::ajaxpropertyunitNameUnique', ['as' => 'erp.inventory.ajaxpropertyunitnameunique']);
        $routes->get('propertyunit-export', 'InventoryController::propertyunitExport', ['as' => 'erp.inventory.propertyunitexport']);
        $routes->match(['get', 'post'], 'property-unit-edit/(:num)/(:num)', 'InventoryController::propertyunitedit/$2/$2', ['as' => 'erp.inventory.propertyunitedit']);

        //Pricelist
        $routes->get('pricelist', 'InventoryController::pricelist', ['as' => 'erp.inventory.pricelist']);
        $routes->get('pricelist-response', 'InventoryController::ajaxPricelistResponse', ['as' => 'erp.inventory.ajaxpricelistresponse']);
        $routes->get('pricelist-export', 'InventoryController::pricelistExport', ['as' => 'erp.inventory.pricelistexport']);
        $routes->match(['get', 'post'], 'pricelist-add', 'InventoryController::pricelistadd', ['as' => 'erp.inventory.pricelistadd']);
        $routes->match(['get', 'post'], 'pricelist-edit/(:num)', 'InventoryController::pricelistedit/$1', ['as' => 'erp.inventory.pricelistedit']);
        $routes->get('pricelist-delete/(:num)', 'InventoryController::pricelistdelete/$1', ['as' => 'erp.inventory.pricelistdelete']);
        $routes->get('pricelist-active/(:num)', 'InventoryController::ajaxPricelistActive/$1', ['as' => 'erp.inventory.ajaxpricelistactive']);

        //Units
        $routes->match(['get', 'post'], 'units', 'InventoryController::units', ['as' => 'erp.inventory.units']);
        $routes->get('units-export', 'InventoryController::unitsExport', ['as' => 'erp.inventory.unitsexport']);
        $routes->get('units-response', 'InventoryController::ajaxUnitResponse', ['as' => 'erp.inventory.ajaxunitresponse']);
        $routes->get('units-delete/(:num)', 'InventoryController::unitdelete/$1', ['as' => 'erp.inventory.unitdelete']);
        $routes->get('units-fetch-unit/(:num)', 'InventoryController::ajaxFetchUnit/$1', ['as' => 'erp.inventory.ajaxfetchunit']);


        //Brands
        $routes->match(['get', 'post'], 'brands', 'InventoryController::brands', ['as' => 'erp.inventory.brands']);
        $routes->get('brands-delete/(:num)', 'InventoryController::branddelete/$1', ['as' => 'erp.inventory.branddelete']);
        $routes->get('brands-fetch/(:num)', 'InventoryController::ajaxFetchBrand/$1', ['as' => 'erp.inventory.ajaxfetchbrand']);
        $routes->get('ajaxBrandResponse', 'InventoryController::ajaxBrandResponse', ['as' => 'erp.inventory.ajaxbrandresponse']);
        $routes->get('brands-export', 'InventoryController::brandsExport', ['as' => 'erp.inventory.brandsexport']);
    });

    //////////////     Inventory End         ///////////////

    //Warehouses
    $routes->group('warehouse', function ($routes) {
        $routes->get('/', 'WarehouseController::index', ['as' => 'erp.warehouses']);
        $routes->post('add', 'WarehouseController::warehouseadd', ['as' => 'erp.warehouse.add']);
        $routes->get('add-form', 'WarehouseController::warehouseadd', ['as' => 'erp.warehouse.addview']);
        $routes->get('edit/(:num)', 'WarehouseController::WarehouseEdit/$1', ['as' => 'erp.warehouse.edit']);
        $routes->post('edit_post/(:num)', 'WarehouseController::WarehouseEdit/$1', ['as' => 'erp.warehouse.edit.post']);
        $routes->get('data_for_datatable', 'WarehouseController::ajax_warehouse_response', ['as' => 'erp.warehouse.datatable']);
        $routes->get('delete_warehouse/(:num)', 'WarehouseController::warehousedelete/$1', ['as' => 'erp.warehouse.delete']);
        $routes->get('exporter_warehouse', 'WarehouseController::WarehouseExport', ['as' => 'erp.warehouse.exportwarehouse']);

        //grn
        $routes->get('list', 'WarehouseController::Grns', ['as' => 'erp.warehouse.grn']);
        $routes->get('get_grn_datatable', 'WarehouseController::ajax_grn_response', ['as' => 'erp.grn.datatable']);
        $routes->get('view_get/(:num)', 'WarehouseController::GrnView/$1', ['as' => 'erp.warehouse.grnview']);
        $routes->get('grn_update/(:num)', 'WarehouseController::GrnUpdate/$1', ['as' => 'erp.warehouse.grnupdate']);
        $routes->post('grn_update_post/(:num)', 'WarehouseController::GrnUpdate/$1', ['as' => 'erp.warehouse.grnupdate.post']);
        $routes->get('exporter_grn', 'WarehouseController::GrnExport', ['as' => 'erp.grn.exportgrn']);

        //Currentstock
        $routes->get('currentstock', 'WarehouseController::CurrentStock', ['as' => 'erp.warehouse.current_stock']);
        $routes->get('currentstock_datatable', 'WarehouseController::ajax_currentstock_response', ['as' => 'erp.warehouse.currentstock.datatable']);
        $routes->get('exporter_current_stock', 'WarehouseController::CurrentStockExport', ['as' => 'erp.warehouse.currentstock.exportcs']);

        $routes->post('current_stock_convert', 'WarehouseController::stocktransfer', ['as' => 'erp.warehouse.currentstock.convert']);

        //Managestock
        $routes->get('managestock', 'WarehouseController::ManageStock', ['as' => 'erp.warehouse.managestock']);
        $routes->get('managestock_datatable', 'WarehouseController::ajax_managestock_response', ['as' => 'erp.warehouse.managestock.datatable']);
        $routes->get('exporter_managestock', 'WarehouseController::ManageStockExport', ['as' => 'erp.managestock.exporter']);
        $routes->get('delete_stock_managestock/(:num)', 'WarehouseController::stockdelete/$1', ['as' => 'erp.warehouse.managestock.delete']);
        //add
        $routes->post('add_stock', 'WarehouseController::StockAdd', ['as' => 'erp.warehouse.managestockadd']);
        $routes->get('add_form_managestock', 'WarehouseController::StockAdd', ['as' => 'erp.warehouse.managestock.addview']);
        $routes->get('ajax_manage_stockadd', 'WarehouseController::ajax_sku_unique', ['as' => 'erp.ajax.managestock']);



        //Pricelist
        //edit
        $routes->get('edit_managestock/(:num)', 'WarehouseController::StockEdit/$1', ['as' => 'erp.warehouse.managestock.edit']);
        $routes->get('edit_form_managestock/(:num)', 'WarehouseController::StockEdit/$1', ['as' => 'erp.warehouse.managestock.edit.post']);
        //delete
        $routes->get('delete_warehouse/(:num)', 'WarehouseController::warehousedelete/$1', ['as' => 'erp.warehouse.delete']);

        //Dispatch Tamil

        $routes->get('dispatch', 'WarehouseController::DispatchList', ['as' => 'erp.warehouse.dispatch']);
        $routes->get('ajax_dispatch_response_datatable', 'WarehouseController::ajax_warehouse_dispatch_response', ['as' => 'erp.ajaxresponse.warehouse.datatable']);
        $routes->get('add_warehouse_dispatch', 'WarehouseController::DispatchWarehouseAdd', ['as' => 'warehouse.dispatch.add']);
        $routes->post('add_warehouse_dispatch_post', 'WarehouseController::DispatchWarehouseAdd', ['as' => 'warehouse.dispatch.add.post']);
        $routes->get('edit_dispatch/(:num)', 'WarehouseController::DispatchEdit/$1', ['as' => 'erp.dispatch.edit']);
        $routes->post('edit_dispatch_post/(:num)', 'WarehouseController::DispatchEdit/$1', ['as' => 'erp.dispatch.edit.post']);
        $routes->get('delete_warehouse_dispatch/(:num)', 'WarehouseController::DeleteWarehouseDispatch/$1', ['as' => 'erp.dispatch.delete']);
        $routes->get('exporter_warehouse_dispatch', 'WarehouseController::DispatchExport', ['as' => 'erp.warehosue.dispatch.exporter']);
    });

    //////////////        Transport           //////////////
    $routes->group('transport', function ($routes) {
        $routes->get('types', 'TransportController::Types', ['as' => 'erp.transport.types']);
        $routes->get('type-fetch/(:num)', 'TransportController::ajaxFetchType/$1', ['as' => 'erp.transport.ajaxfetchtype']);
        $routes->get('type-delete/(:num)', 'TransportController::typedelete/$1', ['as' => 'erp.transport.typedelete']);
        $routes->match(['get', 'post'], 'type-addedit', 'TransportController::typeaddedit', ['as' => 'erp.transport.typeaddedit']);
        $routes->match(['get', 'post'], 'transport-addedit', 'TransportController::transportaddedit', ['as' => 'erp.transport.transportaddedit']);
        $routes->get('transport-type-response', 'TransportController::ajaxTypesResponse', ['as' => 'erp.transport.ajaxtypesresponse']);
        $routes->get('transport-response', 'TransportController::ajaxTransportsResponse', ['as' => 'erp.transport.ajaxtransportsresponse']);
        $routes->get('type-export', 'TransportController::typeExport', ['as' => 'erp.transport.typeexport']);
        $routes->get('transport-export', 'TransportController::transportExport', ['as' => 'erp.transport.transportexport']);
        $routes->get('transport-type-unique', 'TransportController::ajaxTransportTypeUnique', ['as' => 'erp.transport.ajaxtransporttypeunique']);
        $routes->get('transport-code-unique', 'TransportController::ajaxTransportCodeUnique', ['as' => 'erp.transport.ajaxtransportcodeunique']);
        $routes->get('deliveryrecords-response', 'TransportController::ajaxDeliveryrecordsResponse', ['as' => 'erp.transport.ajaxdeliveryrecordsresponse']);
        $routes->get('deliveryrecord-export', 'TransportController::deliveryrecordExport', ['as' => 'erp.transport.deliveryrecordExport']);


        // transports
        $routes->get('transports', 'TransportController::transports', ['as' => 'erp.transport.transports']);
        $routes->get('transport-fetch/(:num)', 'TransportController::ajaxFetchTransport/$1', ['as' => 'erp.transport.ajaxfetchtransport']);
        $routes->get('transport-delete/(:num)', 'TransportController::transportdelete/$1', ['as' => 'erp.transport.transportdelete']);
        $routes->get('transport-active/(:num)', 'TransportController::ajaxTransportActive/$1', ['as' => 'erp.transport.ajaxtransportactive']);

        // deliveryrecords
        $routes->get('deliveryrecords', 'TransportController::deliveryrecords', ['as' => 'erp.transport.deliveryrecords']);
        $routes->get('delivery-start/(:num)', 'TransportController::deliverystart/$1', ['as' => 'erp.transport.deliverystart']);
        $routes->get('delivery-end/(:num)', 'TransportController::deliveryend/$1', ['as' => 'erp.transport.deliveryend']);
        $routes->get('delivery-cancel/(:num)', 'TransportController::deliverycancel/$1', ['as' => 'erp.transport.deliverycancel']);
    });

    //////////////     Suppliers Start        ///////////////
    $routes->group('supplier', function ($routes) {
        //Suppliers
        $routes->get('/', 'SupplierController::suppliers', ['as' => 'erp.supplier.page']);
        $routes->get('ajax-supplier', 'SupplierController::AjaxSuppliersResponse', ['as' => 'erp.supplier.ajax']);
        $routes->get('active/(:num)', 'SupplierController::ajaxSupplierActive/$1', ['as' => 'erp.supplier.active']);
        $routes->match(['get', 'post'], 'supplier-add', 'SupplierController::supplieradd', ['as' => 'erp.supplier.add']);
        $routes->match(['get', 'post'], 'supplier-edit/(:num)', 'SupplierController::supplieredit/$1', ['as' => 'erp.supplier.edit']);
        $routes->get('ajax-code-unique', 'SupplierController::ajaxSupplierCodeUnique', ['as' => 'erp.supplier.ajaxcodeunique']);
        $routes->get('supplier-import', 'SupplierController::supplierImport', ['as' => 'erp.supplier.import']);
        $routes->post('supplier-import-post', 'SupplierController::supplierImport', ['as' => 'erp.supplier.importpost']);
        $routes->get('supplier-export', 'SupplierController::supplierExport', ['as' => 'erp.supplier.export']);
        $routes->match(['get', 'post'], 'supplier-save', 'SupplierController::ajaxSupplierMailUnique', ['as' => 'erp.supplier.save']);
        $routes->get('supplier-view/(:num)', 'SupplierController::supplierview/$1', ['as' => 'erp.supplier.view']);
        $routes->get('supplier-delete/(:num)', 'SupplierController::supplierDelete/$1', ['as' => 'erp.supplier.delete']);

        //Contact
        $routes->get('contact', 'SupplierController::ajaxSuppliercontactResponse', ['as' => 'erp.supplier.contactresponse']);
        $routes->get('contact-mail', 'SupplierController::ajaxContactMailUnique', ['as' => 'erp.supplier.contactmail']);
        $routes->post('supplier-contact/(:num)', 'SupplierController::suppliercontact/$1', ['as' => 'erp.supplier.suppliercontact']);
        $routes->get('ajax-fetch-contact/(:num)', 'SupplierController::ajaxFetchContact/$1', ['as' => 'erp.supplier.ajaxfetchcontact']);
        $routes->get('contact-active/(:num)', 'SupplierController::ajaxContactActive/$1', ['as' => 'erp.supplier.contactactive']);
        $routes->get('contact-delete/(:num)/(:num)', 'SupplierController::supplierContentDelete/$1/$2', ['as' => 'erp.supplier.supliercontentdelete']);
        $routes->get('contact-export', 'SupplierController::supplierContactExport', ['as' => 'erp.supplier.contactexport']);

        //Location
        $routes->get('ajax-fetch-location/(:num)', 'SupplierController::ajaxFetchLocation/$1', ['as' => 'erp.supplier.ajaxfetchlocation']);
        $routes->post('supplier-location/(:num)', 'SupplierController::supplierlocation/$1', ['as' => 'erp.supplier.supplierlocation']);
        $routes->get('location-delete/(:num)/(:num)', 'SupplierController::suplierLocationDelete/$1/$2', ['as' => 'erp.supplier.suplierlocationdelete']);
        $routes->get('location-response', 'SupplierController::ajaxSupplierlocationResponse', ['as' => 'erp.supplier.locationresponse']);
        $routes->get('location-export', 'SupplierController::supplierLocationExport', ['as' => 'erp.supplier.Locationexport']);

        //Attachment
        $routes->post('attachment', 'SupplierController::uploadSupplierattachment', ['as' => 'erp.supplier.attachment']);
        $routes->get('attachment-delete', 'SupplierController::supplierDeleteAttachment', ['as' => 'erp.supplier.attachmentdelete']);


        //Raw-materials
        $routes->get('raw-materials', 'SupplierController::ajaxfetchrawmaterials', ['as' => 'erp.supplier.rawmaterials']);

        //List
        $routes->get('list-response', 'SupplierController::ajaxSupplyListResponse', ['as' => 'erp.supplier.listresponse']);
        $routes->get('list-export', 'SupplierController::supplierSupplyListExport', ['as' => 'erp.supplier.listexport']);
        $routes->post('supply-list/(:num)', 'SupplierController::suppliersupplylist/$1', ['as' => 'erp.supplier.supplylist']);
        $routes->get('list-delete/(:num)/(:num)', 'SupplierController::suplierListDelete/$1/$2', ['as' => 'erp.supplier.suplierlistdelete']);
        $routes->get('import-template', 'SupplierController::supplierImportTemplate', ['as' => 'erp.supplier.importtemplate']);
        $routes->get('ajax-fetch-list/(:num)', 'SupplierController::ajaxFetchSupplyList/$1', ['as' => 'erp.supplier.ajaxfetchlist']);

        //Semi-finished
        $routes->get('semi-finished', 'SupplierController::ajaxFetchSemiFinished', ['as' => 'erp.supplier.semifinished']);

        //Sources
        $routes->get('sources', 'SupplierController::sources', ['as' => 'erp.supplier.sources']);
        $routes->post('sources-add', 'SupplierController::sourceaddedit', ['as' => 'erp.supplier.sourcesedit']);
        $routes->get('sources-delete/(:num)', 'SupplierController::sourcedelete/$1', ['as' => 'erp.supplier.sourcedelete']);
        $routes->get('sources-response', 'SupplierController::ajaxSourcesResponse', ['as' => 'erp.supplier.sourcesresponse']);
        $routes->get('sources-edit/(:num)', 'SupplierController::ajaxFetchSource/$1', ['as' => 'erp.supplier.sourcesedit']);
        $routes->get('sources-export', 'SupplierController::sourceExport', ['as' => 'erp.supplier.sourceexport']);

        //Segment
        $routes->get('segments', 'SupplierController::segments', ['as' => 'erp.supplier.segments']);
        $routes->post('supplier-segment/(:num)', 'SupplierController::supplierSegment/$1', ['as' => 'erp.supplier.suppliersegment']);
        $routes->match(['get', 'post'], 'segments-add', 'SupplierController::segmentadd', ['as' => 'erp.supplier.segmentsadd']);
        $routes->match(['get', 'post'], 'segments-edit/(:num)', 'SupplierController::segmentedit/$1', ['as' => 'erp.supplier.segmentsedit']);
        $routes->get('segments-delete/(:num)', 'SupplierController::segmentdelete/$1', ['as' => 'erp.supplier.segmentsdelete']);
        $routes->get('segments-response', 'SupplierController::ajaxSegmentsResponse', ['as' => 'erp.supplier.segmentsresponse']);
        $routes->get('segments-export', 'SupplierController::segmentExport', ['as' => 'erp.supplier.segmentexport']);
        $routes->get('segment-key-unique', 'SupplierController::ajaxSegmentKeyUnique', ['as' => 'erp.supplier.segmentkeyunique']);

        //Selection Rules
        $routes->get('selection-rules', 'SupplierController::selectionrules', ['as' => 'erp.supplier.selectionrules']);
        $routes->match(['get', 'post'], 'selection-rules-add', 'SupplierController::selectionruleadd', ['as' => 'erp.supplier.selectionruleadd']);
        $routes->get('selection-rules-response', 'SupplierController::ajaxSelectionruleResponse', ['as' => 'erp.supplier.ruleresponse']);
        $routes->get('selection-rules-export', 'SupplierController::selectionruleExport', ['as' => 'erp.supplier.ruleexpoer']);
        $routes->get('rules-name-unique', 'SupplierController::ajaxRuleNameUnique', ['as' => 'erp.supplier.rulesnameunique']);
        $routes->match(['get', 'post'], 'selection-rules-edit/(:num)', 'SupplierController::selectionruleedit/$1', ['as' => 'erp.supplier.selectionruleedit']);
        $routes->get('selection-rules-delete/(:num)', 'SupplierController::selectionruledelete/$1', ['as' => 'erp.supplier.selectionruledelete']);
    });

    //////////////     Suppliers End         ///////////////


    //Finance
    $routes->group('finance', function ($routes) {
        //Account Group
        $routes->get('/', 'FinanceController::AccountGroup', ['as' => 'erp.finance.accountgroup']);
        $routes->post('/', 'FinanceController::AccountgroupAdd', ['as' => 'erp.finance.accountgroup']);
        $routes->get('fetch/(:num)', 'FinanceController::Ajax_fetch_accgroup/$1', ['as' => 'erp.finance.ajax']);
        $routes->get('ajax', 'FinanceController::Ajax_accgroup_response', ['as' => 'erp.finance.ajaxaccresponse']);
        $routes->get('delete/(:num)', 'FinanceController::AccgroupDelete/$1', ['as' => 'erp.finance.delete']);
        $routes->get('export_finance', 'FinanceController::AccgroupExport', ['as' => 'erp.finance.accgroupexport']);
        //GL Accounts
        $routes->get('list_glaccounts', 'FinanceController::GlAccounts', ['as' => 'erp.finance.glaccounts']);
        $routes->post('add_glaccounts', 'FinanceController::GlAccountsAdd', ['as' => 'erp.finance.glaccountadd']);
        $routes->get('ajax_accode_unique', 'FinanceController::ajaxAccountcodeUnique', ['as' => 'erp.finance.ajaxaccountcodeunique']);
        $routes->get('response', 'FinanceController::AjaxGlAccountResponse', ['as' => 'erp.finance.ajaxglaccresponse']);
        $routes->get('edit_glaccounts/(:num)', 'FinanceController::AjaxFetchGlAccount/$1', ['as' => 'erp.finance.ajaxglaccount']);
        $routes->get('delete_glaccounts/(:num)', 'FinanceController::GlAccountDelete/$1', ['as' => 'erp.finance.glaccountdelete']);
        $routes->get('export_glaccounts', 'FinanceController::getGlaccountsExport', ['as' => 'erp.finance.glaccgroupexport']);
        $routes->get('import_glaccounts', 'FinanceController::GlaccountImport', ['as' => 'erp.finance.glaccountimport']);
        $routes->post('import_glaccounts_post', 'FinanceController::GlaccountImport', ['as' => 'erp.finance.glaccountimportpost']);
        $routes->get('import_template', 'FinanceController::GlaccountImportTemplate', ['as' => 'erp.finance.GlaccountImportTemplate']);
        $routes->get('ajax_fetchglaccounts', 'FinanceController::ajaxFetchGlAccounts', ['as' => 'erp.finance.ajaxfetchglaccounts']);
        //Bank Accounts
        $routes->get('list_bankaccounts', 'FinanceController::BankAccounts', ['as' => 'erp.finance.bankaccounts']);
        $routes->post('add_bankaccounts', 'FinanceController::BankAccountsAdd', ['as' => 'erp.finance.bankaccountsadd']);
        $routes->get('ajax_bankaccresponse', 'FinanceController::ajaxBankaccountResponse', ['as' => 'erp.finance.ajaxbankaccountresponse']);
        $routes->get('edit_bankaccounts/(:num)', 'FinanceController::ajaxFetchBankaccount/$1', ['as' => 'erp.finance.ajaxfetchbankaccount']);
        $routes->get('export_bankaccounts', 'FinanceController::bankaccountExport', ['as' => 'erp.finance.bankaccountexport']);
        $routes->get('delete_bankaccounts/(:num)', 'FinanceController::bankAccountDelete/$1', ['as' => 'erp.finance.bankaccountdelete']);

        //Journalentry
        $routes->get('journalentry', 'FinanceController::journalentry', ['as' => 'erp.finance.journalentry']);
        $routes->get('journalentry-post/(:num)', 'FinanceController::journalentrypost/$1', ['as' => 'erp.finance.journalentrypost']);
        $routes->get('journalentry-delete/(:num)', 'FinanceController::journalentrydelete/$1', ['as' => 'erp.finance.journalentrydelete']);
        $routes->match(['get', 'post'], 'journalentry-add', 'FinanceController::journalentryadd', ['as' => 'erp.finance.journalentryadd']);
        $routes->match(['get', 'post'], 'journalentry-response', 'FinanceController::ajaxJournalentryResponse', ['as' => 'erp.finance.ajaxjournalentryresponse']);
        $routes->get('journalentry-export', 'FinanceController::journalentryExport', ['as' => 'erp.finance.journalentryexport']);

        // Accountgroups
        $routes->match(['get', 'post'], 'accountgroups', 'FinanceController::accountgroups', ['as' => 'erp.finance.accountgroups']);

        //Automation
        $routes->match(['get', 'post'], 'automation', 'FinanceController::automation', ['as' => 'erp.finance.automation']);
        $routes->get('automation-export', 'FinanceController::automationExport', ['as' => 'erp.finance.automationexport']);
        $routes->get('automation-response', 'FinanceController::ajaxAutomationResponse', ['as' => 'erp.finance.ajaxautomationresponse']);
        $routes->get('automation-active/(:num)', 'FinanceController::ajaxAutomationActive/$1', ['as' => 'erp.finance.ajaxautomationactive']);
        $routes->get('automation-delete/(:num)', 'FinanceController::automationdelete/$1', ['as' => 'erp.finance.automationdelete']);
        $routes->get('automation-fetch/(:num)', 'FinanceController::ajaxFetchAutomation/$1', ['as' => 'erp.finance.ajaxfetchautomation']);

        //paymentmode
        $routes->match(['get', 'post'], 'paymentmode', 'FinanceController::paymentmode', ['as' => 'erp.finance.paymentmode']);
        $routes->get('paymentmode-response', 'FinanceController::ajaxPaymentmodeResponse', ['as' => 'erp.finance.ajaxpaymentmoderesponse']);
        $routes->get('paymentmode-export', 'FinanceController::paymentmodeExport', ['as' => 'erp.finance.paymentmodeexport']);
        $routes->get('paymentmode-active/(:num)', 'FinanceController::ajaxPaymentmodeActive/$1', ['as' => 'erp.finance.ajaxpaymentmodeactive']);
        $routes->get('paymentmode-fetch/(:num)', 'FinanceController::ajaxFetchPaymentmode/$1', ['as' => 'erp.finance.ajaxfetchpaymentmode']);
        $routes->get('paymentmode-delete/(:num)', 'FinanceController::paymentmodedelete/$1', ['as' => 'erp.finance.paymentmodedelete']);

        // tax
        $routes->match(['get', 'post'], 'tax', 'FinanceController::tax', ['as' => 'erp.finance.tax']);
        $routes->get('tax-response', 'FinanceController::ajaxTaxResponse', ['as' => 'erp.finance.ajaxtaxresponse']);
        $routes->get('tax-export', 'FinanceController::taxExport', ['as' => 'erp.finance.taxexport']);
        $routes->get('tax-fetch/(:num)', 'FinanceController::ajaxFetchTax/$1', ['as' => 'erp.finance.ajaxfetchtax']);
        $routes->get('tax-delete/(:num)', 'FinanceController::taxdelete/$1', ['as' => 'erp.finance.taxdelete']);

        // currency
        $routes->match(['get', 'post'], 'currency', 'FinanceController::currency', ['as' => 'erp.finance.currency']);
        $routes->get('currency-response', 'FinanceController::ajaxCurrencyResponse', ['as' => 'erp.finance.ajaxcurrencyresponse']);
        $routes->get('currency-export', 'FinanceController::currencyExport', ['as' => 'erp.finance.currencyexport']);
        $routes->get('currency-baseset/(:num)', 'FinanceController::currencybaseset/$1', ['as' => 'erp.finance.currencybaseset']);
        $routes->get('currency-fetch/(:num)', 'FinanceController::ajaxFetchCurrency/$1', ['as' => 'erp.finance.ajaxfetchcurrency']);
        $routes->get('currency-delete/(:num)', 'FinanceController::currencydelete/$1', ['as' => 'erp.finance.currencydelete']);

        // report
        $routes->get('finance-report', 'FinanceController::financeReport', ['as' => 'erp.finance.financereport']);
    });

    // Sale Routes
    $routes->group('sales', function ($routes) {
        // Requests
        $routes->get('request', 'SaleController::Request', ['as' => 'erp.sale.requests']);
        $routes->get('request_datatable', 'SaleController::AjaxRequestResponse', ['as' => 'erp.sale.getdatafor.datatable']);
        $routes->get('request_add', 'SaleController::Requestadd', ['as' => 'erp.sale.requests.add']);
        $routes->post('request_add_post', 'SaleController::Requestadd', ['as' => 'erp.sale.requests.add.post']); //Others are pending not found on old ERP,
        //Yet to be Implemented


        //Overall Reports
        $routes->get('reports', 'SaleController::listReports', ['as' => 'erp.sale.reports']);
        $routes->get('ajax-reports-datatable', 'SaleController::ajaxReportData', ['as' => 'erp.sale.ajax.reports']);
        $routes->get('ajax-period-report', 'SaleController::ajaxPeriodReport', ['as' => 'erp.sale.period.report']);
        $routes->post('chart_reports', 'SaleController::listChartReports', ['as' => 'erp.sales.chart']);


        // Estimates
        $routes->get('estimates', 'SaleController::Estimates', ['as' => 'erp.sale.estimates']);
        $routes->get('estimates_add', 'SaleController::EstimateAdd', ['as' => 'erp.sale.estimates.add']);
        $routes->post('estimates_add_post', 'SaleController::EstimateAdd', ['as' => 'erp.sale.estimates.add.post']);
        $routes->get('estimates_view/(:num)', 'SaleController::EstimateView/$1', ['as' => 'erp.sale.estimates.view']);
        $routes->post('notify_add_post/(:num)', 'SaleController::EstimateNotifyAdd/$1', ['as' => 'erp.sale.EstimateNotifyAdd']);
        $routes->get('estimates_edit/(:num)', 'SaleController::EstimateEdit/$1', ['as' => 'erp.sale.estimates.edit']);
        $routes->post('estimates_edit_post/(:num)', 'SaleController::EstimateEdit/$1', ['as' => 'erp.sale.estimates.edit.post']);
        $routes->get('delete_estimate/(:num)', 'SaleController::EstimateDelete/$1', ['as' => 'erp.sale.estimates.delete']);
        $routes->get('export_estimate', 'SaleController::estimateExport/$1', ['as' => 'erp.sale.estimateExport']);

        $routes->post('upload_attachments', 'SaleController::uploadEstimateAttachment', ['as' => 'erp.sale.upload.attachments']);
        $routes->get('delete_attachments', 'SaleController::deleteEstimateAttachment', ['as' => 'erp.sale.delete.attachments']);
        $routes->get('estimate_notifydelete/(:num)/(:num)', 'SaleController::estimatenotifydelete/$1/$2', ['as' => 'sale.estimatenotify.order.delete']);
        //End of Estimates

        // Quotations
        $routes->get('quotations', 'SaleController::Quotation', ['as' => 'erp.sale.quotations']);
        $routes->match(['get', 'post'], 'quotation-add', 'SaleController::QuotationAdd', ['as' => 'erp.sale.quotation.add']);
        $routes->get('quotations_datatable', 'SaleController::ajax_quotations_response', ['as' => 'erp.sale.quotations.datatable']);
        $routes->get('quotation_export', 'SaleController::QuotationExport', ['as' => 'erp.sale.quotations.export']);
        $routes->get('quotation_edit/(:num)', 'SaleController::QuotationEdit/$1', ['as' => 'erp.sale.quotations.edit']);
        $routes->post('quotation_edit_post/(:num)', 'SaleController::QuotationEdit/$1', ['as' => 'erp.sale.quotations.edit.post']);
        $routes->get('quotations_view/(:num)', 'SaleController::QuotationView/$1', ['as' => 'erp.sale.quotations.view']);
        $routes->get('quotations_delete/(:num)', 'SaleController::quotation_delete/$1', ['as' => 'erp.sale.quotation.delete']);
        $routes->get('quotations_view_accept/(:num)', 'SaleController::quotationaccept/$1', ['as' => 'erp.sale.quotations.accept']);
        $routes->get('quotations_view_decline/(:num)', 'SaleController::quotationdecline/$1', ['as' => 'erp.sale.quotations.decline']);
        $routes->get('quotations_unique_id', 'SaleController::ajax_quotation_code_unique', ['as' => 'erp.sale.quotations.quotation_code_unique']);
        $routes->post('upload_quote_attachment', 'SaleController::upload_quoteattachment', ['as' => 'erp.sale.quotations.upload_quoteattachment']);
        $routes->get('quote_delete_attachment', 'SaleController::quote_delete_attachment', ['as' => 'erp.sale.quotations.quote_delete_attachment']);
        $routes->get('ajax_notification_fetch', 'SaleController::ajax_quotationnotify_response', ['as' => 'erp.sale.quotations.fetch.notification']);
        $routes->post('quotation_insert_update/(:num)', 'SaleController::quotationnotify/$1', ['as' => 'erp.sale.quotations.insert_updated_notification']);
        $routes->get('quotationnotifydelete/(:num)/(:num)', 'SaleController::quotationnotifydelete/$1/$2', ['as' => 'erp.sale.quotation.quotationnotifydelete']);
        $routes->get('quotation_notify_export', 'SaleController::quotation_notify_export', ['as' => 'erp.sale.quotation_notify_export']);
        $routes->match(['get', 'post'], 'convertorder-quote/(:num)', 'SaleController::convertorderQuote/$1', ['as' => 'erp.sale.convertorderquote']);
        $routes->get('quotation-status', 'SaleController::quotationChartReport', ['as' => 'erp.sale.quotations.chart']);
        $routes->get('quotation-view-pdf/(:num)/(:any)', 'SaleController::quotationpdf/$1/$2', ['as' => 'erp.sale.quotation.pdf.view']);

        #End of Quotation

        // Orders
        $routes->get('orders', 'SaleController::Orders', ['as' => 'erp.sale.orders']);
        $routes->get('order_view/(:num)', 'SaleController::OrderView/$1', ['as' => 'erp.sale.orders.view']);
        $routes->get('ajax_fetchOrders', 'SaleController::ajaxFetchOrders', ['as' => 'erp.sale.orders.ajaxfetch']);
        $routes->match(['get', 'post'], 'order_add', 'SaleController::OrderAdd', ['as' => 'erp.sale.orders.orderadd']);
        $routes->get('order_export', 'SaleController::OrderExport', ['as' => 'erp.sale.order.export']);
        $routes->match(['get', 'post'], 'order_add', 'SaleController::OrderAdd', ['as' => 'erp.sale.order.add']);
        $routes->get('saleorderdelete/(:num)', 'SaleController::orderdelete/$1', ['as' => 'erp.sale.orderdelete']);

        $routes->post('upload_attachments_order', 'SaleController::upload_orderattachment', ['as' => 'erp.sale.order.upload.attachments']);
        $routes->get('delete_attachments_order', 'SaleController::order_delete_attachment', ['as' => 'erp.sale.order.delete.attachments']);
        $routes->get('notify_order_export', 'SaleController::order_notify_export', ['as' => 'sale.order.notify.export']);
        $routes->get('notify_order_datatable', 'SaleController::ajax_ordernotify_response', ['as' => 'sale.order.view.ajax.datatable']);
        $routes->post('notify_order/(:num)', 'SaleController::ordernotify/$1', ['as' => 'sale.order.notify.add']);
        $routes->get('order_notifydelete/(:num)/(:num)', 'SaleController::ordernotifydelete/$1/$2', ['as' => 'sale.notify.order.delete']);
        $routes->match(['get', 'post'], 'sale_order_edit/(:num)', 'SaleController::SaleOrderEdit/$1', ['as' => 'erp.sale.order.edit']);
        $routes->get('order_view_export', 'SaleController::OrderViewExport', ['as' => 'erp.sale.order.view.export']);

        // Order Dispatch Implemented Tamil
        $routes->get('orders_view_dispatch', 'SaleController::Dispatch', ['as' => 'erp.sale.orders.view.dispatch']);
        $routes->get('order_dispatch_datatable', 'SaleController::ajax_order_dispatch_response', ['as' => 'sale.order.view.dispatch.ajax.datatable']);
        $routes->post('order_add_dispatch/(:num)', 'SaleController::DispatchAdd/$1', ['as' => 'order.view.dispatch.add']);
        $routes->get('delete_dispatch/(:num)', 'SaleController::DeleteSaleDispatch/$1', ['as' => 'erp.dispatch.delete']);

        $routes->get('dispatch_exporter', 'SaleController::DispatchOrderExport', ['as' => 'sale.order.view.dispatch.export']);

        $routes->get('ajax_order_code_unique', 'SaleController::ajax_order_code_unique', ['as' => 'erp.sale.orders.ajax_order_code_unique']);
        $routes->get('ajaxfetchproperties', 'SaleController::ajaxfetchproperties', ['as' => 'erp.sale.orders.ajaxfetchproperties']);
        $routes->get('ajaxfetchpropertyunits', 'SaleController::ajaxfetchpropertyunits', ['as' => 'erp.sale.orders.ajaxfetchpropertyunits']);

        // Ajax
        $routes->get('get_customer_data-;', 'SaleController::ajaxFetchCustomers', ['as' => 'erp.sale.getCustomer']);
        $routes->get('get_shippingaddress_data-;', 'SaleController::ajaxFetchShippingAddr', ['as' => 'erp.sale.getShippingDataCustomer']);
        $routes->get('get_billingaddress_data-;', 'SaleController::ajaxFetchBillingAddr', ['as' => 'erp.sale.getBillingDataCustomer']);
        $routes->get('get_pricelist_data-;', 'SaleController::ajaxFetchPriceList', ['as' => 'erp.sale.getPriceData']);
        $routes->get('get_uniquecode_data-;', 'SaleController::ajaxEstimateCodeUnique', ['as' => 'erp.sale.ajaxEstimateCodeUnique']);
        $routes->get('get_notify_data-;/(:num)', 'SaleController::ajaxFetchNotify/$1', ['as' => 'erp.sale.ajaxFetchNotify']);
        $routes->get('get_notify_data_for_datatable-;', 'SaleController::ajaxEstimatenotifyResponse', ['as' => 'erp.sale.ajaxEstimatenotifyResponse']);

        $routes->post('edit_shippingaddress_data', 'SaleController::ajaxEditShippingAddr', ['as' => 'erp.sale.editShippingDataCustomer']);
        $routes->get('get_currency_data', 'SaleController::ajaxFetchCurrencies', ['as' => 'erp.sale.getCurrencies']);



        // Notify Export
        $routes->get('export-notify-;', 'SaleController::estimateNotifyExport', ['as' => 'erp.sale.estimateNotifyExport']);
        $routes->get('estimates_datatable', 'SaleController::ajax_estimate_response', ['as' => 'erp.sale.estimates.datatable']);

        //Sale_invoice
        $routes->get('invoice', 'SaleController::Invoices', ['as' => 'erp.sale.invoice']);
        $routes->get('invoice_datatable', 'SaleController::AjaxInvoiceResponse', ['as' => 'erp.sale.invoice.datatable']);
        $routes->get('invoice_manufacture_datatable', 'SaleController::AjaxInvoiceResponseManufature', ['as' => 'erp.sale.invoiceManufauture.datatable']);
        $routes->get('invoice_view/(:num)', 'SaleController::InvoiceView/$1', ['as' => 'erp.sale.invoice.view']);
        $routes->get('exporter_invoice', 'SaleController::invoice_export', ['as' => 'erp.sale.invoice.export']);
        //add
        // $routes->get('invoice_add_form', 'SaleController::invoiceadd', ['as' => 'erp.sale.addview.invoice']);
        $routes->match(['get', 'post'], 'invoice_add', 'SaleController::invoiceadd', ['as' => 'erp.sale.invoice.add']);
        $routes->match(['get', 'post'], 'invoice_add_expense/(:any)', 'SaleController::add_expense_invoice/$1', ['as' => 'erp.sale.expense.invoice.add']);
        $routes->get('fetchdata_invoice_add', 'SaleController::ajax_invoice_code_unique', ['as' => 'sale.invoice.add.fetchcode']);
        $routes->get('expense_invoice_add/(:any)', 'SaleController::add_expense_invoice/$1', ['as' => 'sale.expense.add.invoice']);
        //edit
        $routes->get('invoice_edit_form/(:num)', 'SaleController::invoiceedit/$1', ['as' => 'erp.sale.invoice.edit']);
        $routes->post('invoice_edit/(:num)', 'SaleController::invoiceedit/$1', ['as' => 'erp.sale.invoice.edit.post']);
        //delete
        $routes->get('invoice_delete/(:num)', 'SaleController::invoicedelete/$1', ['as' => 'erp.sale.invoice.delete']);
        $routes->get('invoice_view_pdf/(:num)/(:any)', 'SaleController::invoicepdf/$1/$2', ['as' => 'erp.sale.invoice.pdf.view']);
        $routes->post('ship_eidt', 'SaleController::shippingEdit', ['as' => 'edit.editshipping']);
        $routes->get('ship_detail', 'SaleController::getShippingDetails', ['as' => 'edit.getshipping']);
        $routes->get('quote_mail_phone', 'SaleController::getMobileAndEmail', ['as' => 'quotation.getMobileAndEmailData']);

        $routes->get('getCurrencySymbol', 'SaleController::getCurrenySymbol', ['as' => 'erp.getCurrencySymbol']);


        //view credit note
        $routes->get('creditnote_add_form/(:num)', 'SaleController::creditadd/$1', ['as' => 'erp.invoice.add.creditnote.view']);
        $routes->post('creditnote_add/(:num)', 'SaleController::creditadd/$1', ['as' => 'invoice.creditnote.add']);
        $routes->get('creditnote_code_ajax', 'SaleController::ajax_creditnote_code_unique', ['as' => 'erp.invoice.add.creditnote.code.ajax']);
        $routes->post('upload_attachments_invoice', 'SaleController::upload_invoiceattachment', ['as' => 'erp.sale.invoice.upload.attachments']);
        $routes->get('delete_attachments_invoice', 'SaleController::invoice_delete_attachment', ['as' => 'erp.sale.invoice.delete.attachments']);
        $routes->get('notify_exporter', 'SaleController::invoice_notify_export', ['as' => 'sale.invoice.view.notify.exporter']);
        $routes->get('notifydatatable', 'SaleController::ajax_invoicenotify_response', ['as' => 'sale.invoice.view.createnotify']);
        $routes->post('notify/(:num)', 'SaleController::invoicenotify/$1', ['as' => 'sale.invoice.notify']);
        $routes->get('notifydelete/(:num)/(:num)', 'SaleController::invoicenotifydelete/$1/$2', ['as' => 'sale.notify.invoice.delete']);

        //Payments
        $routes->post('invoice_payment/(:num)', 'SaleController::invoicePayment/$1', ['as' => 'erp.sale.invoicepayment']);
        $routes->get('ajax_sale_payment_response', 'SaleController::ajaxSalepaymentResponse', ['as' => 'erp.sale.ajax_salepayment_response']);
        $routes->get('ajax_fetch_invoice_payment/(:num)', 'SaleController::ajaxFetchInvoicepayment/$1', ['as' => 'erp.sale.ajaxfetchinvoicepayment']);
        $routes->get('ajax_payment_delete/(:num)/(:num)', 'SaleController::invoicePaymentDelete/$1/$2', ['as' => 'erp.sale.invoicepaymentdelete']);
        $routes->get('invoice_payment_export', 'SaleController::invoicePaymentExport', ['as' => 'erp.sale.invoice_payment_export']);

        $routes->get('payments', 'SaleController::invoicePaymentList', ['as' => 'erp.sale.payments']);
        $routes->get('paymentlist-response', 'SaleController::ajaxPaymentlistResponse', ['as' => 'erp.finance.ajaxpaymentlistresponse']);
        $routes->get('payments-view/(:num)', 'SaleController::paymentViewPage/$1', ['as' => 'erp.sale.payments.view']);
        $routes->get('payments-edit/(:num)', 'SaleController::paymentEditPage/$1', ['as' => 'erp.sale.payments.edit']);
        $routes->post('payments-edit-page/(:num)', 'SaleController::paymentEdit/$1', ['as' => 'erp.sale.payments.editPage']);
        $routes->get('payments-delete/(:num)', 'SaleController::paymentDelete/$1', ['as' => 'erp.sale.payments.delete']);
        $routes->get('payment_view_pdf/(:num)/(:any)', 'SaleController::paymentpdf/$1/$2', ['as' => 'erp.sale.payment.pdf.view']);


        //Credit Notes
        $routes->get('credit_notes', 'SaleController::creditNotes', ['as' => 'erp.sale.creditnotes']);
        $routes->get('ajax_credit_notes', 'SaleController::ajaxCreditnoteResponse', ['as' => 'erp.sale.ajaxcreditnotes']);
        $routes->get('credit_notes_view/(:num)', 'SaleController::creditNotesView/$1', ['as' => 'erp.sale.creditnotesview']);
        $routes->post('apply_credit/(:num)', 'SaleController::applyCredit/$1', ['as' => 'erp.sale.applycredit']);
        $routes->get('credit_note_export', 'SaleController::creditnote_export', ['as' => 'erp.sale.credit_export']);
        $routes->post('upload_credit_attachment', 'SaleController::uploadCreditAttachment', ['as' => 'erp.sale.uploadattachment']);
        $routes->get('credit_delete_attachment', 'SaleController::creditDeleteAttachment', ['as' => 'erp.sale.creditdeleteattachment']);
        $routes->get('ajax_credit_notify_response', 'SaleController::ajaxCreditnotifyResponse', ['as' => 'erp.sale.ajax_creditnotify_response']);
        $routes->get('credit_notify_delete/(:num)/(:num)', 'SaleController::creditNotifyDelete/$1/$2', ['as' => 'erp.sale.creditNotifyDelete']);
        $routes->post('credit_notify/(:num)', 'SaleController::creditNotify/$1', ['as' => 'erp.sale.creditNotify']);
        $routes->get('credit_notes_delete/(:num)', 'SaleController::creditNotesDelete/$1', ['as' => 'erp.sale.creditnotesdelete']);
        $routes->get('credit_notify_export', 'SaleController::creditNotifyExport', ['as' => 'erp.sale.credit_notify_export']);

        //Credit Notes New, Based on Customer
        $routes->match(['get', 'post'], 'creditnote_add', 'SaleController::creditNoteAddPage', ['as' => 'erp.add.creditnoteadd']);
        $routes->get('credit_apply/(:num)', 'SaleController::creditApplyPage/$1', ['as' => 'erp.invoice.add.creditnote']);
        $routes->post('credit_apply_form/(:num)', 'SaleController::creditApply/$1', ['as' => 'erp.add.creditnoteapplyadd']);
        $routes->post('credit_apply_ajax', 'SaleController::getCreditAmountByAjax', ['as' => 'creditnoteapply.ajax']);
        $routes->get('invoice_apply/(:num)', 'SaleController::invoiceApplyPage/$1', ['as' => 'erp.sale.invocieapply']);
        $routes->post('invoice_apply_form/(:num)', 'SaleController::invoiceApply/$1', ['as' => 'erp.add.invocieapplyadd']);
        $routes->post('credit_apply_to_invoice_ajax', 'SaleController::getInvoiceData', ['as' => 'creditapply.invoice']);
        $routes->match(['get', 'post'], 'creditnote_edit/(:num)', 'SaleController::creditNoteEditPage/$1', ['as' => 'erp.edit.creditnoteedit']);

        $routes->get('manage_view/(:num)', 'SaleController::InvoiceManageView/$1', ['as' => 'erp.invoice.manage.payandcredit']);
        $routes->post('credit_data_ajax', 'SaleController::getCreditData', ['as' => 'creditdata.invoice']);


        //Email Send    
        $routes->post('mail_send/(:num)/(:any)', 'SaleController::sendEmail/$1/$2', ['as' => 'erp.sale.invoice.view.mailsend']);
    });

    //ProjectManagement
    $routes->group('project', function ($routes) {
        $routes->get('teams', 'ProjectManagementController::teams', ['as' => 'erp.project.teams']);
        $routes->match(['get', 'post'], 'teams-add', 'ProjectManagementController::teamadd', ['as' => 'erp.project.teamadd']);
        $routes->get('teams-response', 'ProjectManagementController::ajaxTeamsResponse', ['as' => 'erp.project.ajaxteamsresponse']);
        $routes->match(['get', 'post'], 'teams-edit/(:num)', 'ProjectManagementController::teamedit/$1', ['as' => 'erp.project.teamedit']);
        $routes->get('teams-view/(:num)', 'ProjectManagementController::teamview/$1', ['as' => 'erp.project.teamview']);
        $routes->get('teams-delete/(:num)', 'ProjectManagementController::teamdelete/$1', ['as' => 'erp.project.teamdelete']);
        $routes->get('teams-name-unque', 'ProjectManagementController::ajaxTeamNameUnique', ['as' => 'erp.project.ajaxteamnameunique']);
        $routes->post('teams-attachment', 'ProjectManagementController::uploadTeamattachment', ['as' => 'erp.project.uploadteamattachment']);
        $routes->get('teams-delete-attachment', 'ProjectManagementController::teamdeleteattachment', ['as' => 'erp.project.teamdeleteattachment']);
        $routes->match(['get', 'post'], 'teams-member-response', 'ProjectManagementController::ajaxTeammemberResponse', ['as' => 'erp.project.ajaxteammemberresponse']);
        $routes->match(['get', 'post'], 'teams-member/(:num)', 'ProjectManagementController::teammember/$1', ['as' => 'erp.project.teammember']);
        $routes->get('teams-fetch-employees', 'ProjectManagementController::ajaxfetchemployees', ['as' => 'erp.project.ajaxfetchemployees']);
        $routes->get('teams-member-delete/(:num)', 'ProjectManagementController::teammemberdelete/$1', ['as' => 'erp.project.teammemberdelete']);
        $routes->get('teams-export', 'ProjectManagementController::teamsExport', ['as' => 'erp.project.teamsexport']);
        $routes->post('update-status', 'ProjectManagementController::updateStatus', ['as' => 'erp.updatestatus']);
        $routes->get('team-members-export', 'ProjectManagementController::teamsMemberExport', ['as' => 'erp.project.teams.members.export']);
        //Workgroups
        $routes->get('workgroups', 'ProjectManagementController::workgroups', ['as' => 'erp.project.workgroups']);
        $routes->match(['get', 'post'], 'workgroups-add', 'ProjectManagementController::workgroupadd', ['as' => 'erp.project.workgroupadd']);
        $routes->match(['get', 'post'], 'workgroups-edit/(:num)', 'ProjectManagementController::workgroupedit/$1', ['as' => 'erp.project.workgroupedit']);
        $routes->get('workgroups-delete/(:num)', 'ProjectManagementController::workgroupdelete/$1', ['as' => 'erp.project.workgroupdelete']);
        $routes->get('workgroups-response', 'ProjectManagementController::ajaxWorkgroupsResponse', ['as' => 'erp.project.ajaxworkgroupsresponse']);
        $routes->get('workgroups-export', 'ProjectManagementController::workgroupsExport', ['as' => 'erp.project.workgroupsexport']);
        $routes->get('workgroups-name-unique', 'ProjectManagementController::ajaxWorkgroupNameUnique', ['as' => 'erp.project.ajaxworkgroupnameunique']);

        // projects
        $routes->get('projects', 'ProjectManagementController::projects', ['as' => 'erp.project.projects']);
        $routes->get('m-projects-response', 'ProjectManagementController::ajaxMprojectsResponse', ['as' => 'erp.project.ajaxmprojectsresponse']);
        $routes->match(['get', 'post'], 'projects-add', 'ProjectManagementController::projectadd', ['as' => 'erp.project.projectadd']);
        $routes->match(['get', 'post'], 'projects-edit/(:num)', 'ProjectManagementController::projectedit/$1', ['as' => 'erp.project.projectedit']);
        $routes->match(['get', 'post'], 'projects-view/(:num)', 'ProjectManagementController::projectview/$1', ['as' => 'erp.project.projectview']);
        $routes->get('projects-delete/(:num)', 'ProjectManagementController::projectdelete/$1', ['as' => 'erp.project.projectdelete']);
        $routes->get('projects-export', 'ProjectManagementController::projectsExport', ['as' => 'erp.project.projectsexport']);
        $routes->get('projects-name-unique', 'ProjectManagementController::ajaxProjectNameUnique', ['as' => 'erp.project.ajaxprojectnameunique']);
        $routes->get('c-projects-response', 'ProjectManagementController::ajaxCprojectsResponse', ['as' => 'erp.project.ajaxcprojectsresponse']);

        //Phasestart
        $routes->get('from-active-workgroup/(:num)', 'ProjectManagementController::fromactiveworkgroup/$1', ['as' => 'erp.project.fromactiveworkgroup']);
        $routes->get('project-testing-response', 'ProjectManagementController::ajaxProjectTestingResponse', ['as' => 'erp.project.ajaxProjectTestingResponse']);
        $routes->get('project-expense-export', 'ProjectManagementController::projectExpenseExport', ['as' => 'erp.project.projectexpenseexport']);
        $routes->get('project-expense-response', 'ProjectManagementController::ajaxProjectExpenseResponse', ['as' => 'erp.project.ajaxprojectexpenseresponse']);
        $routes->get('project-rawmaterial-response', 'ProjectManagementController::ajaxProjectRawmaterialResponse', ['as' => 'erp.project.ajaxprojectrawmaterialresponse']);
        $routes->get('project-testing-export', 'ProjectManagementController::projectTestingExport', ['as' => 'erp.project.projecttestingexport']);
        $routes->match(['get', 'post'], 'project-attachment', 'ProjectManagementController::uploadProjectAttachment', ['as' => 'erp.project.uploadprojectattachment']);
        $routes->get('project-delete-attachment', 'ProjectManagementController::projectDeleteAttachment', ['as' => 'erp.project.projectdeleteattachment']);
        $routes->match(['get', 'post'], 'project-testingadd/(:num)', 'ProjectManagementController::testingadd/$1', ['as' => 'erp.project.testingadd']);
        $routes->match(['get', 'post'], 'project-expenseadd/(:num)', 'ProjectManagementController::expenseadd/$1', ['as' => 'erp.project.expenseadd']);
        $routes->match(['get', 'post'], 'project-rawmaterialadd/(:num)', 'ProjectManagementController::rawmaterialadd/$1', ['as' => 'erp.project.rawmaterialadd']);
        $routes->match(['get', 'post'], 'project-phaseaddedit/(:num)', 'ProjectManagementController::phaseaddedit/$1', ['as' => 'erp.project.phaseaddedit']);
        $routes->match(['get', 'post'], 'project-phaseworkgroup/(:num)', 'ProjectManagementController::phaseworkgroup/$1', ['as' => 'erp.project.phaseworkgroup']);
        $routes->match(['get', 'post'], 'project-updateqty/(:num)', 'ProjectManagementController::updateqty/$1', ['as' => 'erp.project.updateqty']);
        $routes->get('project-testingdelete/(:num)/(:num)', 'ProjectManagementController::testingdelete/$1/$2', ['as' => 'erp.project.testingdelete']);
        $routes->match(['get', 'post'], 'project-phasestart/(:num)/(:num)', 'ProjectManagementController::phasestart/$1/$2', ['as' => 'erp.project.phasestart']);
        $routes->match(['get', 'post'], 'project-phasewgroupdelete/(:num)/(:num)', 'ProjectManagementController::phasewgroupdelete/$1/$2', ['as' => 'erp.project.phasewgroupdelete']);
        $routes->match(['get', 'post'], 'project-expenseedit/(:num)/(:num)', 'ProjectManagementController::expenseedit/$1/$2', ['as' => 'erp.project.expenseedit']);
        $routes->match(['get', 'post'], 'project-expensedelete/(:num)/(:num)', 'ProjectManagementController::expensedelete/$1/$2', ['as' => 'erp.project.expensedelete']);
        $routes->match(['get', 'post'], 'project-phasewgroupcomplete/(:num)/(:num)', 'ProjectManagementController::phasewgroupcomplete/$1/$2', ['as' => 'erp.project.phasewgroupcomplete']);
        $routes->match(['get', 'post'], 'project-testingedit/(:num)/(:num)', 'ProjectManagementController::testingedit/$1/$2', ['as' => 'erp.project.testingedit']);
        $routes->get('project-testingview/(:num)/(:num)', 'ProjectManagementController::testingview/$1/$2', ['as' => 'erp.project.testingview']);
        $routes->match(['get', 'post'], 'testing-attachment', 'ProjectManagementController::uploadTestingAttachment', ['as' => 'erp.project.uploadtestingattachment']);
        $routes->get('testing-delete-attachment', 'ProjectManagementController::testingDeleteAttachment', ['as' => 'erp.project.testingdeleteattachment']);
        $routes->match(['get', 'post'], 'project-testingupdate/(:num)', 'ProjectManagementController::testingupdate/$1', ['as' => 'erp.project.testingupdate']);
        $routes->match(['get', 'post'], 'project-reqdispatch/(:num)/(:num)', 'ProjectManagementController::reqdispatch/$1/$2', ['as' => 'erp.project.reqdispatch']);
        $routes->get('fetch-rawmaterial-qty/(:num)', 'ProjectManagementController::ajaxFetchRawmaterialQty/$1', ['as' => 'erp.project.fetchrawmaterialqty']);
        $routes->get('rawmaterial-delete/(:num)/(:num)', 'ProjectManagementController::rawmaterialdelete/$1/$2', ['as' => 'erp.project.rawmaterialdelete']);
    });
    // service managmement
    $routes->group('service', function ($routes) {
        $routes->get('service', 'ServiceManagement::index', ['as' => 'erp.service.service']);
        $routes->get('service-report', 'ServiceManagement::report', ['as' => 'erp.service.report']);
        $routes->match(['get', 'post'], 'service-add', 'ServiceManagement::serviceadd', ['as' => 'erp.service.serviceadd']);
        $routes->match(['get', 'post'], 'service-edit/(:num)', 'ServiceManagement::serviceedit/$1', ['as' => 'erp.service.serviceedit']);
        $routes->get('service-view/(:num)', 'ServiceManagement::serviceView/$1', ['as' => 'erp.service.serviceview']);
        $routes->get('service-delete/(:num)', 'ServiceManagement::serviceDelete/$1', ['as' => 'erp.service.servicedelete']);
        $routes->get('service-export', 'ServiceManagement::serviceExport', ['as' => 'erp.service.export']);
        $routes->get('ajax-service-response', 'ServiceManagement::ajaxServiceResponse', ['as' => 'erp.service.ajaxserviceresponse']);
        $routes->get('ajax-service-code-unique', 'ServiceManagement::ajaxServiceCodeunique', ['as' => 'erp.service.ajaxservicecodeunique']);
        // scheduling
        $routes->match(['get', 'post'], 'scheduling-add/(:num)', 'ServiceManagement::schedulingadd/$1', ['as' => 'erp.service.schedulingadd']);
        $routes->match(['get', 'post'], 'service-scheduling', 'ServiceManagement::Scheduling', ['as' => 'erp.service.scheduling']);
        $routes->match(['get', 'post'], 'service-notify/(:num)', 'ServiceManagement::serviceNotif/$1', ['as' => 'erp.service.servicenotify']);
        $routes->get('scheduling-delete/(:num)/(:num)', 'ServiceManagement::schedulingDelete/$1/$2', ['as' => 'erp.service.schedulingdelete']);
        $routes->match(['get', 'post'], 'ajax-fetch-scheduling/(:num)', 'ServiceManagement::ajaxFetchScheduling/$1', ['as' => 'erp.service.ajaxfetchscheduling']);
        $routes->get('ajax-response', 'ServiceManagement::ajaxSchedulingResponse', ['as' => 'erp.service.schedulingresponse']);
        $routes->get('service-report-response', 'ServiceManagement::ajaxServiceReportResponse', ['as' => 'erp.service.reportresponse']);
        $routes->get('ajax-service-notify-response', 'ServiceManagement::ajaxServiceNotifyResponse', ['as' => 'erp.service.ajaxservicenotifyresponse']);
        // report
        $routes->get('service-report-export', 'ServiceManagement::serviceReportExport', ['as' => 'erp.service.reportexport']);
    });

    $routes->group('mrp', function ($routes) {
        // dashboard
        $routes->get('dashboard', 'MRPController::dashboard', ['as' => 'erp.mrp.dashboard']);
        $routes->post('yearly_stock','MRPController::yearlyStockAjax',['as' => 'erp.dashboard.yearly.ajax.data']);
        $routes->get('forecasting', 'MRPController::Forecasting', ['as' => 'erp.mrp.forecasting']);
        $routes->post('forecasting_post', 'MRPController::Forecasting', ['as' => 'erp.mrp.forecasting.post']);
        $routes->get('export_excel_forecastdata', 'MRPController::ForecastExportasExcel', ['as' => 'erp.mrp.forecasting.excel']);
        $routes->match(['get', 'post'], 'product-planning-add', 'MRPController::productPlanningAdding', ['as' => 'erp.mrp.productplanningadding']);
        $routes->match(['get', 'post'], 'product-planning-export', 'MRPController::productPlanningexport', ['as' => 'erp.mrp.productplanningexport']);
        $routes->match(['get', 'post'], 'add-mrp-stock/(:num)', 'MRPController::addMRPStock/$1', ['as' => 'erp.mrp.addmrpstock']);
        $routes->match(['get', 'post'], 'edit-mrp-stock/(:num)/(:num)', 'MRPController::editMRPStock/$1/$2', ['as' => 'erp.mrp.editmrpstock']);
        $routes->match(['get', 'post'], 'product-planning-edit/(:num)', 'MRPController::productPlanningEdit/$1', ['as' => 'erp.mrp.productplanningedit']);
        $routes->get('planning-view/(:num)', 'MRPController::planningView/$1', ['as' => 'erp.mrp.planningview']);
        $routes->get('planning-schedule', 'MRPController::planningSchedule', ['as' => 'erp.mrp.planningschedule']);
        $routes->get('ajax-planning-response', 'MRPController::ajaxPlanningResponse', ['as' => 'erp.mrp.ajaxplanningresponse']);
        $routes->get('ajax-mrp-scheduling-response', 'MRPController::ajaxMrpSchedulingResponse', ['as' => 'erp.mrp.ajaxmrpschedulingresponse']);

        // BOM
        // $routes->get('add_form_bom', 'MRPController::AddMrpBOM', ['as' => 'erp.mrp.viewadd.bom']);
        $routes->get('bill_of_material', 'MRPController::BOM', ['as' => 'erp.mrp.bom']);
        $routes->Post('get_stock_goods', 'MRPController::get_stock_goods', ['as' => 'erp.ajax.goods.stock']);
        $routes->match(['get', 'post'], 'bom-edit-view/(:any)/(:any)', 'MRPController::bom_edit_view/$1/$2', ['as' => 'erp.bill.of.materials.edit']);
        // $routes->match(['get', 'post'], 'bom-data-view/(:any)/(:any)', 'MRPController::bom_data_view/$1/$2', ['as' => 'erp.mrp.bomdata.view']);
        $routes->match(['get', 'post'], 'delete/(:any)/(:any)', 'MRPController::bom_delete/$1/$2', ['as' => 'erp.bill.of.materials.delete']);
        $routes->match(['get', 'post'], 'add_bom/(:num)', 'MRPController::AddMrpBOM/$1', ['as' => 'erp.mrp.add.bom']);
        $routes->match(['get', 'post'], 'datatable_bom/(:any)', 'MRPController::datatable_bom/$1', ['as' => 'erp.mob.ajaxmrpschedulingresponse']);

        //workstationtype
        $routes->match(['get', 'post'], 'wstationtype-datatable', 'MRPController::workstationtype_datatable', ['as' => 'erp.mrp.workstationtypedatatable']);
        $routes->match(['get', 'post'], 'workstationtype', 'MRPController::workstationtypeview', ['as' => 'erp.mrp.worstationtype']);
        $routes->match(['get', 'post'], 'workstationtype-add', 'MRPController::workstationtype_add', ['as' => 'erp.add.workstationtype']);
        $routes->match(['get', 'post'], 'workstationtype-edit', 'MRPController::workstationtype_update', ['as' => 'erp.edit.workstationtype']);
        $routes->match(['get', 'post'], 'workstationtype-delete/(:any)', 'MRPController::workstationtype_delete/$1', ['as' => 'erp.mrp.workstationtype.delete']);
        $routes->match(['get', 'post'], 'get-workstationtype-data', 'MRPController::getworkstationtypedata', ['as' => 'erp.mrp.workstation.data']);
        $routes->match(['get', 'post'], 'get-workstationtype-list', 'MRPController::ajaxFetchworkstationtype', ['as' => 'erp.mrp.ajax.workstation.types.list']);
        $routes->match(['get', 'post'], 'ajax-workstation-details', 'MRPController::ajaxgetworkstationdetails', ['as' => 'erp.mrp.get.worstationdetails']);


        //workstation
        $routes->match(['get', 'post'], 'workstation', 'MRPController::workstationview', ['as' => 'erp.mrp.worstation']);
        $routes->match(['get', 'post'], 'workstation-datatable', 'MRPController::workstationdatatable', ['as' => 'erp.mrp.workstationdatatable']);
        $routes->match(['get', 'post'], 'workstation-add', 'MRPController::workstation_add', ['as' => 'erp.mrp.workstationadd.view']);
        $routes->match(['get', 'post'], 'workstation-edit/(:num)', 'MRPController::workstation_edit/$1', ['as' => 'erp.mrp.workstationedit.view']);
        $routes->match(['get', 'post'], 'workstation-delete/(:num)', 'MRPController::workstation_delete/$1', ['as' => 'erp.mrp.workstation.delete']);

        // Operations
        $routes->match(['get', 'post'], 'operation_list', 'MRPController::OperationList', ['as' => 'erp.mrp.operation.list']);
        $routes->match(['get', 'post'], 'operation_add', 'MRPController::OperationAdd', ['as' => 'erp.mrp.operation.add']);
        $routes->match(['get', 'post'], 'operation_edit/(:any)', 'MRPController::operationEdit/$1', ['as' => 'erp.mrp.operation.edit']);
        $routes->match(['get', 'post'], 'operation_delete/(:any)', 'MRPController::OperationDelete/$1', ['as' => 'erp.mrp.operation.delete']);
        $routes->match(['get', 'post'], 'operation_data_table', 'MRPController::ajaxOperationResponse', ['as' => 'erp.mrp.operation.datatable']);
        $routes->match(['get', 'post'], 'operation_ajax_data', 'MRPController::ajaxOperationlist', ['as' => 'erp.mrp.operation.ajax_data']);
        $routes->match(['get', 'post'], 'operation_data_bom', 'MRPController::getDataForBom', ['as' => 'erp.mrp.operation.bomdata']);

        // Overall BOM List
        $routes->match(['get', 'post'], 'overall-bom-list', 'MRPController::overallbomList', ['as' => 'erp.mrp.overall.bom.list']);
        $routes->match(['get', 'post'], 'overall-bom-datatable', 'MRPController::overallbomdatatable', ['as' => 'erp.mrp.overall.bom.datatable']);
        $routes->match(['get', 'post'], 'overall-bom-export', 'MRPController::overall_bom_export', ['as' => 'erp.mrp.overall.bom.export']);


        // scrap

        //Sources
        $routes->post('scrap-add', 'MRPController::scrapaddedit', ['as' => 'erp.mrp.scrap.add']);
        $routes->get('scrap-response', 'MRPController::ajaxScrapResponse', ['as' => 'erp.mrp.scrap.response']);
        // $routes->get('sources-edit/(:num)', 'MRPController::ajaxFetchSource/$1', ['as' => 'erp.supplier.sourcesedit']);


        // Costing

        $routes->match(['get', 'post'], 'costing_add/(:num)', 'MRPController::CostingAdd/$1', ['as' => 'erp.mrp.costing.information']);
    });

    // CRM Routes
    $routes->group('crm', function ($routes) {
        $routes->get('leads', 'CRMController::leads', ['as' => 'erp.crm.leads']);
        $routes->match(['get', 'post'], 'lead-add', 'CRMController::leadadd', ['as' => 'erp.crm.leadadd']);
        $routes->match(['get', 'post'], 'lead-edit/(:num)', 'CRMController::leadedit/$1', ['as' => 'erp.crm.leadedit']);
        $routes->get('lead-delete/(:num)', 'CRMController::leaddelete/$1', ['as' => 'erp.crm.leaddelete']);
        $routes->get('crm-repoet', 'CRMController::CRMReport', ['as' => 'erp.crm.crmreport']);
        $routes->match(['get', 'post'], 'lead-import', 'CRMController::leadImport', ['as' => 'erp.crm.leadimport']);
        $routes->get('lead-export', 'CRMController::leadExport', ['as' => 'erp.crm.leadexport']);
        $routes->get('lead-response', 'CRMController::ajaxLeadsResponse', ['as' => 'erp.crm.ajaxleadsresponse']);
        $routes->match(['get', 'post'], 'lead-upload-attachment', 'CRMController::uploadLeadattachment', ['as' => 'erp.crm.uploadleadattachment']);
        $routes->get('lead-import-template', 'CRMController::leadimporttemplate', ['as' => 'erp.crm.leadimporttemplate']);
        $routes->get('lead-delete-attachment', 'CRMController::leadDeleteattachment', ['as' => 'erp.crm.leaddeleteattachment']);
        $routes->get('lead-notify-response', 'CRMController::ajaxLeadnotifyResponse', ['as' => 'erp.crm.ajaxleadnotifyresponse']);
        $routes->get('lead-notify-export', 'CRMController::leadNotifyExport', ['as' => 'erp.crm.leadnotifyexport']);
        $routes->get('lead-view/(:num)', 'CRMController::leadview/$1', ['as' => 'erp.crm.leadview']);
        $routes->get('lead-fetch-notify/(:num)', 'CRMController::ajaxFetchNotify/$1', ['as' => 'erp.crm.ajaxfetchnotify']);
        $routes->get('lead-notify-delete/(:num)/(:num)', 'CRMController::leadnotifydelete/$1/$2', ['as' => 'erp.crm.leadnotifydelete']);
        $routes->match(['get', 'post'], 'lead-notify/(:num)', 'CRMController::leadnotify/$1', ['as' => 'erp.crm.leadnotify']);
        $routes->match(['get', 'post'], 'lead-convert-customer/(:num)', 'CRMController::convertLeadCustomer/$1', ['as' => 'erp.crm.convertleadcustomer']);
        $routes->match(['get', 'post'], 'lead-customer-view/(:num)', 'CRMController::customerview/$1', ['as' => 'erp.crm.customerview']);
        $routes->get('lead-mail-unique', 'CRMController::ajaxLeadMailUnique', ['as' => 'erp.crm.ajaxleadmailunique']);
        $routes->post('get-max-stock', 'CRMController::get_max_stock_by_id', ['as' => 'get.max.stock.byid']);
        $routes->get('get_fetchfinishedgoods', 'CRMController::ajaxFetchFinishedGoods', ['as' => 'erp.crm.ajaxfetchfinishedgoods']);
        $routes->get('get_fetchrawmaterials', 'CRMController::ajaxFetchrawmaterials', ['as' => 'erp.crm.ajaxfetchrawmaterials']);
        $routes->get('get_fetchwarehouse', 'CRMController::ajaxFetchwarehouse', ['as' => 'erp.crm.ajaxfetchwarehouse']);
        $routes->get('get_ajaxfetchusers', 'CRMController::ajaxFetchUsers', ['as' => 'erp.crm.ajaxFetchUsers']);
        $routes->get('fetch-customers', 'CRMController::ajaxfetchcustomers', ['as' => 'erp.crm.ajaxfetchcustomers']);
        $routes->get('fetch-tickets', 'CRMController::ajaxfetchcustomersfortickets', ['as' => 'erp.crm.ajaxfetchcustomersforticktes']);

        $routes->get('ajaxfetchsemifinished', 'CRMController::ajaxFetchSemiFinished', ['as' => 'erp.crm.semifinished']);
        $routes->get('ajaxfetchtradinggoods', 'CRMController::ajaxfetchtradinggoods', ['as' => 'erp.crm.ajaxfetchtradinggoods']);
        $routes->get('ajaxfetchinventoryservices', 'CRMController::ajaxfetchinventoryservices', ['as' => 'erp.crm.ajaxfetchinventoryservices']);
        $routes->get('related-jobtask', 'CRMController::relatedJobTask', ['as' => 'erp.crm.relatedJobTask']);



        // customers
        $routes->get('customers', 'CRMController::customers', ['as' => 'erp.crm.customers']);
        $routes->get('customer-mail-unique', 'CRMController::ajaxCustomerMailUnique', ['as' => 'erp.crm.ajaxcustomermailunique']);
        $routes->match(['get', 'post'], 'customer-add', 'CRMController::customeradd', ['as' => 'erp.crm.customeradd']);
        $routes->match(['get', 'post'], 'customer-import', 'CRMController::customerImport', ['as' => 'erp.crm.customerimport']);
        $routes->match(['get', 'post'], 'customer-export', 'CRMController::customerExport', ['as' => 'erp.crm.customerExport']);
        $routes->get('customer-response', 'CRMController::ajaxCustomersResponse', ['as' => 'erp.crm.ajaxcustomersresponse']);
        $routes->match(['get', 'post'], 'customer-upload-attachment', 'CRMController::uploadCustomerattachment', ['as' => 'erp.crm.uploadcustomerattachment']);
        $routes->get('customer-delete-attachment', 'CRMController::customerDeleteattachment', ['as' => 'erp.crm.customerdeleteattachment']);
        $routes->get('customer-contact-response', 'CRMController::ajaxCustomercontactResponse', ['as' => 'erp.crm.ajaxcustomercontactresponse']);
        $routes->get('customer-contact-export', 'CRMController::customerContactExport', ['as' => 'erp.crm.customercontactexport']);
        $routes->get('customer-shipping-response', 'CRMController::ajaxCustomershippingResponse', ['as' => 'erp.crm.ajaxcustomershippingresponse']);
        $routes->get('customer-billing-response', 'CRMController::ajaxCustomerbillingResponse', ['as' => 'erp.crm.ajaxcustomerbillingresponse']);
        $routes->get('customer-shipping-export', 'CRMController::customerShippingExport', ['as' => 'erp.crm.customershippingexport']);
        $routes->get('customer-billing-export', 'CRMController::customerBillingExport', ['as' => 'erp.crm.customerbillingexport']);
        $routes->get('customer-notify-response', 'CRMController::ajaxCustomernotifyResponse', ['as' => 'erp.crm.ajaxcustomernotifyresponse']);
        $routes->get('customer-notify-export', 'CRMController::customerNotifyExport', ['as' => 'erp.crm.customernotifyexport']);
        $routes->get('customer-contact-mail-unique', 'CRMController::ajaxContactMailUnique', ['as' => 'erp.crm.ajaxcontactmailunique']);
        $routes->match(['get', 'post'], 'customer-notify/(:num)', 'CRMController::customernotify/$1', ['as' => 'erp.crm.customernotify']);
        $routes->match(['get', 'post'], 'customer-shippingaddr/(:num)', 'CRMController::customershippingaddr/$1', ['as' => 'erp.crm.customershippingaddr']);
        $routes->match(['get', 'post'], 'customer-billingaddr/(:num)', 'CRMController::customerbillingaddr/$1', ['as' => 'erp.crm.customerbillingaddr']);
        $routes->match(['get', 'post'], 'customer-contact/(:num)', 'CRMController::customercontact/$1', ['as' => 'erp.crm.customercontact']);
        $routes->match(['get', 'post'], 'customer-edit/(:num)', 'CRMController::customeredit/$1', ['as' => 'erp.crm.customeredit']);
        $routes->get('customer-delete/(:num)', 'CRMController::customerdelete/$1', ['as' => 'erp.crm.customerdelete']);
        $routes->get('customer-fetch-contact/(:num)', 'CRMController::ajaxFetchContact/$1', ['as' => 'erp.crm.ajaxfetchcontact']);
        $routes->get('customer-contact-delete/(:num)/(:num)', 'CRMController::customercontactdelete/$1/$2', ['as' => 'erp.crm.customercontactdelete']);
        $routes->get('customer-shipping-delete/(:num)/(:num)', 'CRMController::customershippingdelete/$1/$2', ['as' => 'erp.crm.customershippingdelete']);
        $routes->get('customer-billing-delete/(:num)/(:num)', 'CRMController::customerbillingdelete/$1/$2', ['as' => 'erp.crm.customerbillingdelete']);
        $routes->get('customer-notify-delete/(:num)/(:num)', 'CRMController::customernotifydelete/$1/$2', ['as' => 'erp.crm.customernotifydelete']);
        $routes->get('customer-contact-active/(:num)', 'CRMController::ajaxContactActive/$1', ['as' => 'erp.crm.ajaxcontactactive']);
        $routes->get('customer-fetch-shipping/(:num)', 'CRMController::ajaxFetchShipping/$1', ['as' => 'erp.crm.ajaxfetchshipping']);
        $routes->get('customer-fetch-billing/(:num)', 'CRMController::ajaxFetchBilling/$1', ['as' => 'erp.crm.ajaxfetchbilling']);


        // marketing
        $routes->get('marketing', 'CRMController::marketing', ['as' => 'erp.crm.marketing']);
        $routes->match(['get', 'post'], 'marketing-add', 'CRMController::marketingadd', ['as' => 'erp.crm.marketingadd']);
        $routes->match(['get', 'post'], 'marketing-edit/(:num)', 'CRMController::marketingedit/$1', ['as' => 'erp.crm.marketingedit']);
        $routes->get('marketing-active/(:num)', 'CRMController::ajaxMarketingActive/$1', ['as' => 'erp.crm.ajaxmarketingactive']);
        $routes->get('marketing-export', 'CRMController::marketingExport', ['as' => 'erp.crm.marketingexport']);
        $routes->get('marketing-name-unique', 'CRMController::ajaxMarketingNameUnique', ['as' => 'erp.crm.ajaxmarketingnameunique']);
        $routes->get('marketing-response', 'CRMController::ajaxMarketingResponse', ['as' => 'erp.crm.ajaxmarketingresponse']);
        $routes->get('marketing-delete/(:num)', 'CRMController::marketingdelete/$1', ['as' => 'erp.crm.marketingdelete']);

        // tickets
        $routes->get('tickets', 'CRMController::tickets', ['as' => 'erp.crm.tickets']);
        $routes->match(['get', 'post'], 'ticket-add', 'CRMController::ticketadd', ['as' => 'erp.crm.ticketadd']);
        $routes->match(['get', 'post'], 'ticket-edit/(:num)', 'CRMController::ticketedit/$1', ['as' => 'erp.crm.ticketedit']);
        $routes->match(['get', 'post'], 'ticket-handle/(:num)', 'CRMController::handleticket/$1', ['as' => 'erp.crm.handleticket']);
        $routes->match(['get', 'post'], 'ticket-submit/(:num)', 'CRMController::ticketsubmit/$1', ['as' => 'erp.crm.ticketsubmit']);
        $routes->get('ticket-response', 'CRMController::ajaxTicketsResponse', ['as' => 'erp.crm.ajaxticketsresponse']);
        $routes->match(['get', 'post'], 'ticket-view/(:num)', 'CRMController::ticketview/$1', ['as' => 'erp.crm.ticketview']);
        $routes->match(['get', 'post'], 'ticket-delete/(:num)', 'CRMController::ticketdelete/$1', ['as' => 'erp.crm.ticketdelete']);
        $routes->match(['get', 'post'], 'ticket-attachment', 'CRMController::uploadTicketattachment', ['as' => 'erp.crm.uploadticketattachment']);
        $routes->get('ticket-delete-attachment', 'CRMController::ticketDeleteAttachment', ['as' => 'erp.crm.ticketdeleteattachment']);
        $routes->post('ajax-project', 'CRMController::get_customer_project', ['as' => 'fetch.contact.project']);
        $routes->get('tickets-export', 'CRMController::ticketexport', ['as' => 'erp.crm.ticket.export']);
        $routes->post('tickets-add-comments', 'CRMController::addcomments', ['as' => 'erp.ticket.add.comment']);
        $routes->post('tickets-fetch-comments', 'CRMController::fetchallcomment', ['as' => 'erp.ticket.comment.fetch']);

        // Task
        $routes->get('task', 'CRMController::task', ['as' => 'erp.crm.task']);
        $routes->match(['get', 'post'], 'task-add', 'CRMController::taskadd', ['as' => 'erp.crm.taskadd']);
        $routes->match(['get', 'post'], 'task-edit/(:num)', 'CRMController::taskedit/$1', ['as' => 'erp.crm.taskedit']);
        $routes->get('task-view/(:num)', 'CRMController::taskview/$1', ['as' => 'erp.crm.taskview']);
        $routes->get('task-delete/(:num)', 'CRMController::taskDelete/$1', ['as' => 'erp.crm.taskdelete']);
        $routes->get('task-response', 'CRMController::ajaxTaskResponse', ['as' => 'erp.crm.ajaxtaskresponse']);
    });

    //////////////      Procurement      //////////////
    $routes->group('procurement', function ($routes) {
        //API
        $routes->get('ajax_requisition_response', 'ProcurementController::ajax_requisition_response', ['as' => 'erp.procurement.ajax_requisition_response']);
        $routes->get('ajax_requisition_code_unique', 'ProcurementController::ajax_requisition_code_unique', ['as' => 'erp.procurement.ajax_requisition_code_unique']);
        $routes->get('ajaxfetchrawmaterials', 'ProcurementController::ajaxfetchrawmaterials', ['as' => 'erp.procurement.ajaxfetchrawmaterials']);
        $routes->get('ajaxfetchsemifinished', 'ProcurementController::ajaxfetchsemifinished', ['as' => 'erp.procurement.ajaxfetchsemifinished']);
        $routes->get('ajax_rfq_response', 'ProcurementController::ajax_rfq_response', ['as' => 'erp.procurement.ajax_rfq_response']);
        $routes->get('ajax_rfq_code_unique', 'ProcurementController::ajax_rfq_code_unique', ['as' => 'erp.procurement.ajax_rfq_code_unique']);
        $routes->get('ajax_get_selection_basis', 'ProcurementController::ajax_get_selection_basis', ['as' => 'erp.procurement.ajax_get_selection_basis']);
        $routes->get('ajaxfetchsupplierbyproduct', 'ProcurementController::ajaxfetchsupplierbyproduct', ['as' => 'erp.procurement.ajaxfetchsupplierbyproduct']);
        $routes->get('ajaxfetchtransport', 'ProcurementController::ajaxfetchtransport', ['as' => 'erp.procurement.ajaxfetchtransport']);
        $routes->get('ajaxfetchpricelist', 'ProcurementController::ajaxfetchpricelist', ['as' => 'erp.procurement.ajaxfetchpricelist']);
        $routes->get('ajax_supplier_locations', 'ProcurementController::ajax_supplier_locations', ['as' => 'erp.procurement.ajax_supplier_locations']);
        $routes->get('ajax_order_code_unique', 'ProcurementController::ajax_order_code_unique', ['as' => 'erp.procurement.ajax_order_code_unique']);
        $routes->get('ajax_order_response', 'ProcurementController::ajax_order_response', ['as' => 'erp.procurement.ajax_order_response']);
        $routes->get('ajax_returns_response', 'ProcurementController::ajax_returns_response', ['as' => 'erp.procurement.ajax_returns_response']);
        $routes->get('ajax_purchasepayment_response', 'ProcurementController::ajax_purchasepayment_response', ['as' => 'erp.procurement.ajax_purchasepayment_response']);
        $routes->post('upload_invoiceattachment', 'ProcurementController::upload_invoiceattachment', ['as' => 'erp.procurement.upload_invoiceattachment']);
        $routes->post('invoicepayment/(:num)', 'ProcurementController::invoicepayment/$1', ['as' => 'erp.procurement.invoicepayment']);
        $routes->get('invoice_payment_export', 'ProcurementController::invoice_payment_export', ['as' => 'erp.procurement.invoice_payment_export']);


        //Requsition
        $routes->get('requisition', 'ProcurementController::requisition', ['as' => 'erp.procurement.requisition']);
        $routes->get('requisition_export', 'ProcurementController::requisition_export', ['as' => 'erp.procurement.requisition_export']);
        $routes->get('requisitionadd', 'ProcurementController::requisitionadd', ['as' => 'erp.procurement.requisitionadd']);
        $routes->post('requisitionadd_add', 'ProcurementController::requisitionadd', ['as' => 'erp.procurement.requisitionadd_form']);
        $routes->get('requisitiondelete/(:num)', 'ProcurementController::requisitiondelete/$1', ['as' => 'erp.procurement.requisitiondelete']);
        $routes->get('requisitionview/(:num)', 'ProcurementController::requisitionview/$1', ['as' => 'erp.procurement.requisitionview']);
        $routes->get('requisitionhandle/(:num)', 'ProcurementController::requisitionhandle/$1', ['as' => 'erp.procurement.requisitionhandle']);
        $routes->match(['get', 'post'], 'requisitionaction/(:num)', 'ProcurementController::requisitionaction/$1', ['as' => 'erp.procurement.requisitionaction']);
        $routes->match(['get', 'post'], 'createorder_req(:num)', 'ProcurementController::createorder_req/$1', ['as' => 'erp.procurement.createorder_req']);
        $routes->match(['get', 'post'], 'createorder_rfq/(:num)/(:num)', 'ProcurementController::createorder_rfq/$1/$2', ['as' => 'erp.procurement.createorder_rfq']);

        //RFQ
        $routes->get('rfq', 'ProcurementController::rfq', ['as' => 'erp.procurement.rfq']);
        $routes->match(['get', 'post'], 'createrfq/(:num)', 'ProcurementController::createrfq/$1', ['as' => 'erp.procurement.createrfq']);
        $routes->get('requisitionedit/(:num)', 'ProcurementController::requisitionedit/$1', ['as' => 'erp.procurement.requisitionedit']);
        $routes->post('requisitionedit/(:num)', 'ProcurementController::requisitionedit/$1', ['as' => 'erp.procurement.requisitionedit']);
        $routes->get('rfq_export', 'ProcurementController::rfq_export', ['as' => 'erp.procurement.rfq_export']);
        $routes->get('rfqview/(:num)', 'ProcurementController::rfqview/$1', ['as' => 'erp.procurement.rfqview']);
        $routes->match(['get', 'post'], 'rfqedit/(:num)', 'ProcurementController::rfqedit/$1', ['as' => 'erp.procurement.rfqedit']);
        $routes->get('ajax_rfqsupplier_response', 'ProcurementController::ajax_rfqsupplier_response', ['as' => 'erp.procurement.ajax_rfqsupplier_response']);
        $routes->post('upload_rfqattachment', 'ProcurementController::upload_rfqattachment', ['as' => 'erp.procurement.upload_rfqattachment']);
        $routes->get('rfq_delete_attachment', 'ProcurementController::rfq_delete_attachment', ['as' => 'erp.procurement.rfq_delete_attachment']);
        $routes->get('rfqsupplier_export', 'ProcurementController::rfqsupplier_export', ['as' => 'erp.procurement.rfqsupplier_export']);
        $routes->match(['get', 'post'], 'rfqsupplieradd/(:num)', 'ProcurementController::rfqsupplieradd/$1', ['as' => 'erp.procurement.rfqsupplieradd']);
        $routes->get('rfqdelete/(:num)', 'ProcurementController::rfqdelete/$1', ['as' => 'erp.procurement.rfqdelete']);
        $routes->get('rfqsupplierdelete/(:num)/(:num)', 'ProcurementController::rfqsupplierdelete/$1/$2', ['as' => 'erp.procurement.rfqsupplierdelete']);
        $routes->get('rfqsendbatch/(:num)', 'ProcurementController::rfqsendbatch/$1', ['as' => 'erp.procurement.rfqsendbatch']);

        //Orders
        $routes->get('orders', 'ProcurementController::orders', ['as' => 'erp.procurement.orders']);
        $routes->get('orderview/(:num)', 'ProcurementController::orderview/$1', ['as' => 'erp.procurement.orderview']);
        $routes->get('orderdelete/(:num)', 'ProcurementController::orderdelete/$1', ['as' => 'erp.procurement.orderdelete']);
        $routes->get('orderpdf/(:num)', 'ProcurementController::orderpdf/$1', ['as' => 'erp.procurement.orderpdf']);
        $routes->get('orderapprove/(:num)', 'ProcurementController::orderapprove/$1', ['as' => 'erp.procurement.orderapprove']);
        $routes->match(['get', 'post'], 'orderedit/(:num)', 'ProcurementController::orderedit/$1', ['as' => 'erp.procurement.orderedit']);
        $routes->match(['get', 'post'], 'rfqsupplierresend/(:num)/(:num)', 'ProcurementController::rfqsupplierresend/$1/$2', ['as' => 'erp.procurement.rfqsupplierresend']);
        $routes->get('order_export', 'ProcurementController::order_export', ['as' => 'erp.procurement.order_export']);
        $routes->get('return_export', 'ProcurementController::return_export', ['as' => 'erp.procurement.return_export']);
        $routes->get('ordercancel/(:num)', 'ProcurementController::ordercancel/$1', ['as' => 'erp.procurement.ordercancel']);

        // need to work
        $routes->get('ordersend/(:num)', 'ProcurementController::ordersend/$1', ['as' => 'erp.procurement.ordersend']);

        //invoice
        $routes->get('invoices', 'ProcurementController::invoices', ['as' => 'erp.procurement.invoices']);
        $routes->post('get-invoice-data', 'ProcurementController::getInvoiceData', ['as' => 'erp.procurement.getinvoicedata']);
        $routes->get('orderinvoice/(:num)', 'ProcurementController::orderinvoice/$1', ['as' => 'erp.procurement.orderinvoice']);
        $routes->get('ajax_invoice_response', 'ProcurementController::ajax_invoice_response', ['as' => 'erp.procurement.ajax_invoice_response']);
        $routes->get('invoice_export', 'ProcurementController::invoice_export', ['as' => 'erp.procurement.invoice_export']);
        $routes->get('invoiceview/(:num)', 'ProcurementController::invoiceview/$1', ['as' => 'erp.procurement.invoiceview']);
        $routes->get('upload_invoiceattachment', 'ProcurementController::upload_invoiceattachment', ['as' => 'erp.procurement.upload_invoiceattachment']);
        $routes->get('invoice_delete_attachment', 'ProcurementController::invoice_delete_attachment', ['as' => 'erp.procurement.invoice_delete_attachment']);



        //GRN
        $routes->get('ordergrn/(:num)', 'ProcurementController::ordergrn/$1', ['as' => 'erp.procurement.ordergrn']);

        //returns
        $routes->get('returns', 'ProcurementController::returns', ['as' => 'erp.procurement.returns']);

        //reports
        $routes->get('Report', 'ProcurementController::Report', ['as' => 'erp.procurement.Report']);
    });

    // Departments
    $routes->group('hr', function ($routes) {
        $routes->match(['get', 'post'], 'departments', 'HRController::departments', ['as' => 'erp.hr.departments']);
        $routes->get('department-response', 'HRController::ajaxDepartmentResponse', ['as' => 'erp.hr.ajaxdepartmentresponse']);
        $routes->get('department-export', 'HRController::departmentExport', ['as' => 'erp.hr.departmentexport']);
        $routes->get('department-name-unique', 'HRController::ajaxDepartmentNameUnique', ['as' => 'erp.hr.ajaxdepartmentnameunique']);
        $routes->get('department-fetch/(:num)', 'HRController::ajaxFetchDepartment/$1', ['as' => 'erp.hr.ajaxfetchdepartment']);
        $routes->get('department-delete/(:num)', 'HRController::departmentdelete/$1', ['as' => 'erp.hr.departmentdelete']);

        // Designation
        $routes->match(['get', 'post'], 'designation', 'HRController::designation', ['as' => 'erp.hr.designation']);
        $routes->get('designation-response', 'HRController::ajaxDesignationResponse', ['as' => 'erp.hr.ajaxdesignationresponse']);
        $routes->get('designation-export', 'HRController::designationExport', ['as' => 'erp.hr.designationExport']);
        $routes->get('designation-fetch/(:num)', 'HRController::ajaxFetchDesignation/$1', ['as' => 'erp.hr.ajaxfetchdesignation']);
        $routes->get('designation-delete/(:num)', 'HRController::designationdelete/$1', ['as' => 'erp.hr.designationdelete']);

        // Employees
        $routes->match(['get', 'post'], 'employees', 'HRController::employees', ['as' => 'erp.hr.employees']);
        $routes->match(['get', 'post'], 'employee-add', 'HRController::employeeadd', ['as' => 'erp.hr.employeeadd']);
        $routes->match(['get', 'post'], 'employee-adding', 'HRController::attendanceadding', ['as' => 'erp.hr.attendanceadding']);
        $routes->match(['get', 'post'], 'employee-import', 'HRController::employeeImport', ['as' => 'erp.hr.employeeimport']);
        $routes->get('employee-response', 'HRController::ajaxEmployeesResponse', ['as' => 'erp.hr.ajaxemployeesresponse']);
        $routes->get('employee-export', 'HRController::employeeExport', ['as' => 'erp.hr.employeeexport']);
        $routes->get('employee-view/(:num)', 'HRController::employeeview/$1', ['as' => 'erp.hr.employeeview']);
        $routes->match(['get', 'post'], 'employee-edit/(:num)', 'HRController::employeeedit/$1', ['as' => 'erp.hr.employeeedit']);
        $routes->get('employee-delete/(:num)', 'HRController::employeedelete/$1', ['as' => 'erp.hr.employeedelete']);
        $routes->match(['get', 'post'], 'employee-import-template', 'HRController::employeeimporttemplate', ['as' => 'erp.hr.employeeimporttemplate']);
        $routes->get('employee-emp-code-unique', 'HRController::ajaxEmpCodeUnique', ['as' => 'erp.hr.ajaxempcodeunique']);
        $routes->get('employee-emp-mail-unique', 'HRController::ajaxEmpEmailUnique', ['as' => 'erp.hr.ajaxempemailunique']);
        $routes->get('employee-emp-phone-unique', 'HRController::ajaxEmpPhoneUnique', ['as' => 'erp.hr.ajaxempphoneunique']);
        $routes->match(['get', 'post'], 'employee-attachment', 'HRController::uploadEmployeeAttachment', ['as' => 'erp.hr.uploademployeeattachment']);
        $routes->get('employee-delete-attachment', 'HRController::employeeDeleteAttachment', ['as' => 'erp.hr.employeedeleteattachment']);

        // Contractors
        $routes->match(['get', 'post'], 'contractors', 'HRController::contractors', ['as' => 'erp.hr.contractors']);
        $routes->match(['get', 'post'], 'contractor-add', 'HRController::contractoradd', ['as' => 'erp.hr.contractoradd']);
        $routes->get('contractor-response', 'HRController::ajaxContractorsResponse', ['as' => 'erp.hr.ajaxcontractorsresponse']);
        $routes->match(['get', 'post'], 'contractor-import', 'HRController::contractorImport', ['as' => 'erp.hr.contractorimport']);
        $routes->get('contractor-export', 'HRController::contractorExport', ['as' => 'erp.hr.contractorexport']);
        $routes->match(['get', 'post'], 'contractor-email-unique', 'HRController::ajaxContractorEmailUnique', ['as' => 'erp.hr.ajaxcontractoremailunique']);
        $routes->get('contractor-phone-unique', 'HRController::ajaxContractorPhoneUnique', ['as' => 'erp.hr.ajaxcontractorphoneunique']);
        $routes->match(['get', 'post'], 'contractor-attachment', 'HRController::uploadContractorAttachment', ['as' => 'erp.hr.uploadcontractorattachment']);
        $routes->get('contractor-delete-attachment', 'HRController::contractorDeleteAttachment', ['as' => 'erp.hr.contractordeleteattachment']);
        $routes->match(['get', 'post'], 'contractor-import-template', 'HRController::contractorimporttemplate', ['as' => 'erp.hr.contractorimporttemplate']);
        $routes->match(['get', 'post'], 'contractor-code-unique', 'HRController::ajaxContractorCodeUnique', ['as' => 'erp.hr.ajaxcontractorcodeunique']);
        $routes->get('contractor-delete/(:num)', 'HRController::contractordelete/$1', ['as' => 'erp.hr.contractordelete']);
        $routes->get('contractor-active/(:num)', 'HRController::ajaxContractorActive/$1', ['as' => 'erp.hr.ajaxcontractoractive']);
        $routes->get('contractor-view/(:num)', 'HRController::contractorview/$1', ['as' => 'erp.hr.contractorview']);
        $routes->match(['get', 'post'], 'contractor-edit/(:num)', 'HRController::contractoredit/$1', ['as' => 'erp.hr.contractoredit']);

        //Attendance
        $routes->match(['get', 'post'], 'attendance', 'HRController::attendance', ['as' => 'erp.hr.attendance']);
        $routes->match(['get', 'post'], 'attendance-add', 'HRController::attendanceadd', ['as' => 'erp.hr.attendanceadd']);
        $routes->get('attendance-response', 'HRController::ajaxAttendanceResponse', ['as' => 'erp.hr.ajaxattendanceresponse']);
        $routes->get('attendance-export', 'HRController::attendanceExport', ['as' => 'erp.hr.attendanceexport']);
        $routes->get('attendance-empty-template', 'HRController::attendanceemptytemplate', ['as' => 'erp.hr.attendanceemptytemplate']);
        $routes->match(['get', 'post'], 'attendance-update-template', 'HRController::attendanceupdatetemplate', ['as' => 'erp.hr.attendanceupdatetemplate']);
        $routes->match(['get', 'post'], 'attendance-edit', 'HRController::attendanceedit', ['as' => 'erp.hr.attendanceedit']);
        $routes->get('attendance-fetch/(:num)', 'HRController::ajaxFetchAttendance/$1', ['as' => 'erp.hr.ajaxfetchattendance']);
        $routes->get('attendance-fetch-employees', 'ProjectManagementController::ajaxfetchemployees', ['as' => 'erp.hr.ajaxfetchemployees']);
        //Deductions
        $routes->match(['get', 'post'], 'deductions', 'HRController::deductions', ['as' => 'erp.hr.deductions']);
        $routes->get('deduction-response', 'HRController::ajaxDeductionsResponse', ['as' => 'erp.hr.ajaxdeductionsresponse']);
        $routes->get('deduction-export', 'HRController::deductionExport', ['as' => 'erp.hr.deductionexport']);
        $routes->get('deduction-fetch/(:num)', 'HRController::ajaxFetchDeduction/$1', ['as' => 'erp.hr.ajaxfetchdeduction']);
        $routes->get('deduction-delete/(:num)', 'HRController::deductiondelete/$1', ['as' => 'erp.hr.deductiondelete']);
        $routes->match(['get', 'post'], 'deduction-addedit', 'HRController::deductionaddedit', ['as' => 'erp.hr.deductionaddedit']);

        // Additions
        $routes->match(['get', 'post'], 'additions', 'HRController::additions', ['as' => 'erp.hr.additions']);
        $routes->get('addition-response', 'HRController::ajaxAdditionsResponse', ['as' => 'erp.hr.ajaxadditionsresponse']);
        $routes->get('addition-export', 'HRController::additionExport', ['as' => 'erp.hr.additionexport']);
        $routes->match(['get', 'post'], 'addition-addedit', 'HRController::additionaddedit', ['as' => 'erp.hr.additionaddedit']);
        $routes->get('addition-fetch/(:num)', 'HRController::ajaxFetchAddition/$1', ['as' => 'erp.hr.ajaxfetchaddition']);
        $routes->get('addition-delete/(:num)', 'HRController::additiondelete/$1', ['as' => 'erp.hr.additiondelete']);

        // Payrolls
        $routes->match(['get', 'post'], 'payrolls', 'HRController::payrolls', ['as' => 'erp.hr.payrolls']);
        $routes->match(['get', 'post'], 'payroll-add', 'HRController::payrolladd', ['as' => 'erp.hr.payrolladd']);
        $routes->match(['get', 'post'], 'payroll-edit/(:num)', 'HRController::payrolledit/$1', ['as' => 'erp.hr.payrolledit']);
        $routes->get('payroll-response', 'HRController::ajaxPayrollsResponse', ['as' => 'erp.hr.ajaxpayrollsresponse']);
        $routes->get('payrollentry-export', 'HRController::payrollentryExport', ['as' => 'erp.hr.payrollentryexport']);
        $routes->get('payroll-view/(:num)', 'HRController::payrollview/$1', ['as' => 'erp.hr.payrollview']);
        $routes->get('payroll-delete/(:num)', 'HRController::payrolldelete/$1', ['as' => 'erp.hr.payrolldelete']);
        $routes->get('payroll-process/(:num)', 'HRController::payrollprocess/$1', ['as' => 'erp.hr.payrollprocess']);
    });

    // setting
    $routes->group('setting', function ($routes) {
        // users
        $routes->get('users', 'SettingController::users', ['as' => 'erp.setting.users']);
        $routes->get('user-response', 'SettingController::ajaxUsersResponse', ['as' => 'erp.setting.ajaxusersresponse']);
        $routes->get('user-export', 'SettingController::userExport', ['as' => 'erp.setting.userexport']);
        $routes->get('user-mail-unique', 'SettingController::ajaxMailUnique', ['as' => 'erp.setting.ajaxmailunique']);
        $routes->match(['get', 'post'], 'user-add', 'SettingController::useradd', ['as' => 'erp.users.add']);
        $routes->match(['get', 'post'], 'user-edit/(:num)', 'SettingController::useredit/$1', ['as' => 'erp.setting.useredit']);
        $routes->match(['get', 'post'], 'employee', 'SettingController::ajaxEmployee', ['as' => 'erp.setting.employee']);
        $routes->match(['get', 'post'], 'employee-details', 'SettingController::employees_details_by_id', ['as' => 'erp.setting.employee.details']);

        // roles
        $routes->match(['get', 'post'], 'role-add', 'SettingController::roleadd', ['as' => 'erp.setting.roleadd']);
        $routes->match(['get', 'post'], 'role-edit/(:num)', 'SettingController::roleedit/$1', ['as' => 'erp.setting.roleedit']);
        $routes->get('role-view/(:num)', 'SettingController::roleview/$1', ['as' => 'erp.setting.roleview']);
        $routes->get('role-viedeletew/(:num)', 'SettingController::roledelete/$1', ['as' => 'erp.setting.roledelete']);
        $routes->get('roles', 'SettingController::roles', ['as' => 'erp.setting.roles']);
        $routes->get('role-response', 'SettingController::ajaxRolesResponse', ['as' => 'erp.setting.ajaxrolesresponse']);
        $routes->get('role-export', 'SettingController::roleExport', ['as' => 'erp.setting.roleexport']);

        // smtp
        $routes->match(['get', 'post'], 'smtp', 'SettingController::smtp', ['as' => 'erp.setting.smtp']);
        $routes->match(['get', 'post'], 'sendtestemail', 'SettingController::sendtestemail', ['as' => 'erp.setting.sendtestemail']);


        $routes->group('email-templates', function ($routes) {
            $routes->get('/', 'EmailTemplatesController::index', ['as' => 'email.templates.list']);
            $routes->post('form', 'EmailTemplatesController::form', ['as' => 'email.templates.form']);
            $routes->post('save', 'EmailTemplatesController::save', ['as' => 'email.templates.save']);
            $routes->post('add_template_modal_form', 'EmailTemplatesController::form', ['as' => 'email.templates.add.modal.form']);
            $routes->post('different_language_form/(:any)', 'EmailTemplatesController::different_language_form/$1', ['as' => 'email.templates.different.modal.form']);
        });

        // Groups
        $routes->get('groups', 'SettingController::groups', ['as' => 'erp.setting.groups']);
        $routes->get('group-response', 'SettingController::ajaxGroupsResponse', ['as' => 'erp.setting.ajaxgroupsresponse']);
        $routes->get('group-fetch/(:num)', 'SettingController::ajaxFetchGroup/$1', ['as' => 'erp.setting.ajaxfetchgroup']);
        $routes->get('group-delete/(:num)', 'SettingController::groupdelete/$1', ['as' => 'erp.setting.groupdelete']);
        $routes->match(['get', 'post'], 'group-edit', 'SettingController::groupaddedit', ['as' => 'erp.setting.groupaddedit']);

        // finance
        $routes->match(['get', 'post'], 'finance', 'SettingController::finance', ['as' => 'erp.setting.finance']);

        //Company Information
        $routes->match(['get', 'post'], 'company', 'SettingController::companyInformation', ['as' => 'erp.setting.companyinformation']);

        //Activity Log
        $routes->get('activitylog', 'SettingController::activityLogger', ['as' => 'erp.setting.activitylog']);
        $routes->get('activitylog-delete', 'SettingController::activityLoggerDelete', ['as' => 'erp.setting.activityDelete']);
        $routes->get('get-activity-log', 'SettingController::activityLogDt', ['as' => 'erp.settings.activity']);

        //contracttype
        $routes->get('/', 'SettingController::contracttype_view', ['as' => 'er.setting.contracttype']);
        $routes->match(['get', 'post'], 'Contract_type', 'SettingController::ajaxaddcontract', ['as' => 'erp.setting.contracttype_add']);
        $routes->match(['get', 'post'], 'Contract_type_datatable', 'SettingController::ajaxContractDataTable', ['as' => 'erp.setting.contracttypeajax.datatype']);
        $routes->match(['get', 'post'], 'Contract_type_update', 'SettingController::contracttypeupdate', ['as' => 'erp.contracttype.update']);
        $routes->get('Contract_type_delete/(:any)', 'SettingController::contracttypedelete/$1', ['as' => 'erp.contracttype.delete']);




        //Database backup
        $routes->get('backupdata_base_view', 'SettingController::backupIndex', ['as' => 'erp.setting.databasebackupview']);
        $routes->get('backupdata_base_generate', 'SettingController::generateDbBackup', ['as' => 'erp.setting.databasebackupaction']);
        $routes->get('backupdata_base_download/(:any)', 'SettingController::download/$1', ['as' => 'erp.setting.databasebackupdownload']);
        $routes->get('backupdata_base_delete/(:any)', 'SettingController::db_delete/$1', ['as' => 'erp.setting.databasebackupdelete']);
    });

    //Announcement Routes

    $routes->group('announcements', function ($routes) {
        $routes->get('/', 'AnnouncementController::index', ['as' => 'erp.announcements']);
        $routes->get('announcementadd', 'AnnouncementController::add', ['as' => 'erp.announcementsadd']);
        $routes->post('announcementinsert', 'AnnouncementController::insert_announcement', ['as' => 'erp.announcementsinsert']);
        $routes->get('announcement', 'AnnouncementController::ajaxAnnouncementResponse', ['as' => 'erp.announcementsresponse']);
        $routes->get('announcementdelete/(:any)', 'AnnouncementController::deleteannouncement/$1', ['as' => 'erp.announcementsdelete']);
        $routes->match(['get', 'post'], 'announcementupdate/(:any)', 'AnnouncementController::updateannouncement/$1', ['as' => 'erp.announcementsupdate']);
        $routes->get('ajaxdata', 'AnnouncementController::ajaxAnnouncementData', ['as' => 'erp.ajaxannouncement']);
        $routes->get('announcements-export', 'AnnouncementController::announcements_Export', ['as' => 'erp.Announcements.announcementexport']);
    });
    //Knowledge base
    $routes->group('knowledgebase', function ($routes) {
        $routes->get('/', 'KnowledgeBaseController::index', ['as' => 'erp.Knowledgebase']);
        $routes->get('Report', 'KnowledgeBaseController::report_view', ['as' => 'erp.Knowledgebase.report']);
        $routes->get('Internal_article/(:any)', 'KnowledgeBaseController::admin_internel_view/$1', ['as' => 'erp.knowledgebase.internelarticle.view']);
        $routes->post('Feedback-submit', 'KnowledgeBaseController::admin_article_review_store', ['as' => 'erp.knowledgebase.internelarticle.submit']);
        $routes->post('ajax-group', 'KnowledgeBaseController::get_knowledgebase_ajax', ['as' => 'erp.group.report.ajax']);
        $routes->match(['get', 'post'], 'Knowledgebaseadd', 'KnowledgeBaseController::add', ['as' => 'erp.Knowledgebaseadd']);
        $routes->post('Knowledgebase_group_add', 'KnowledgeBaseController::insert_group', ['as' => 'erp.KnowledgebasegroupAdd']);
        $routes->get('Knowledgebase_group_view', 'KnowledgeBaseController::group_view', ['as' => 'erp.Knowledgebasegroupview']);
        $routes->get('Knowledgebase_group', 'KnowledgeBaseController::getajaxknowledgebase', ['as' => 'erp.Knowledgebaseajax']);
        $routes->post('knowledgebasegroup', 'KnowledgeBaseController::ajaxgroup', ['as' => 'erp.ajaxgroup']);
        $routes->match(['get', 'post'], 'KnowledgebaseArticle_delete/(:any)', 'KnowledgeBaseController::article_delete/$1', ['as' => 'erp.knowledgebasearticledelete']);
        $routes->match(['get', 'post'], 'KnowledgebaseArticle_update/(:any)', 'KnowledgeBaseController::article_update/$1', ['as' => 'erp.knowledgebasearticleupdate']);
        $routes->post('Knowledgebase_group_insert', 'KnowledgeBaseController::group_insert', ['as' => 'erp.Knowledgebase.add']);
        $routes->get('Knowledgebase_article_view', 'KnowledgeBaseController::ajaxtable_article', ['as' => 'erp.Knowledgebaseajaxarticle']);
        $routes->match(['get', 'post'], 'Knowledgebase_update/(:any)', 'KnowledgeBaseController::knowledgebase_update_view/$1', ['as' => 'erp.knowledgebaseupdate']);
        $routes->match(['get', 'post'], 'Knowledgebase_delete/(:any)', 'KnowledgeBaseController::knowledgebase_delete/$1', ['as' => 'erp.knowledgebasedelete']);
        //$routes->match(['get', 'post'],'Knowledgebase_delete','KnowledgeBaseController::knowledgebase_article_add', ['as' => 'erp.knowledgebasearticleadd']);
        $routes->get('Knowledgebase_group_export', 'KnowledgeBaseController::export_knowledgebase', ['as' => 'erp.knowledgebase.group.export']);
        $routes->get('Knowledgebase_article_export', 'KnowledgeBaseController::export_knowledgebase_article', ['as' => 'erp.knowledgebase.article.export']);


    });

    //contracts
    $routes->group('Contracts', function ($routes) {
        $routes->get('/', 'ContractController::index', ['as' => 'erp.contractview']);
        $routes->get('Contract_data_table', 'ContractController::ajax_data_table_contract', ['as' => 'erp.contract_Data_table']);
        $routes->match(['get', 'post'], 'New_Contract_Add', 'ContractController::contractadd', ['as' => 'erp.contractadd']);
        $routes->POST('customer_fetch_detail', 'ContractController::customer_details_ajax', ['as' => 'erp.fetchCustomerDetails']);
        $routes->POST('contracttype_fetch_detail', 'ContractController::contracttype_details_ajax', ['as' => 'erp.fetchContracttypeDetails']);
        $routes->match(['get', 'post'], 'Add_new_contract_type', 'ContractController::contracttype_add', ['as' => 'erp.contract.add.contracttype']);
        $routes->match(['get', 'post'], 'contract_view/(:any)', 'ContractController::contract_view/$1', ['as' => 'erp.contract.view']);
        $routes->match(['get', 'post'], 'contract_delete/(:any)', 'ContractController::contract_delete/$1', ['as' => 'erp.contract.delete']);
        $routes->match(['get', 'post'], 'contract_commend_add/(:any)', 'ContractController::contract_commend_add/$1', ['as' => 'erp.contractCommendAdd']);
        $routes->match(['get', 'post'], 'contract_commend_update', 'ContractController::contract_commend_update', ['as' => 'erp.contractCommendupdate']);
        $routes->match(['get', 'post'], 'contract_commend_delete/', 'ContractController::contract_commend_delete', ['as' => 'erp.comment.delete']);
        $routes->match(['get', 'post'], 'contract_commend_fetch', 'ContractController::contract_commend_fetch', ['as' => 'erp.contractCommendfetch']);
        $routes->match(['get', 'post'], 'contract_contract_update/(:any)', 'ContractController::contract_update/$1', ['as' => 'erp.contract.update']);
        $routes->match(['get', 'post'], 'contract_email', 'ContractController::send_mail', ['as' => 'erp.contract.email']);
        $routes->match(['get', 'post'], 'contract_content/(:any)', 'ContractController::add_content/$1', ['as' => 'erp.contract.add_content']);
        $routes->match(['get', 'post'], 'contract_attachment', 'ContractController::uploadContractAttachment', ['as' => 'erp.contract.Attachment']);
        $routes->get('contract-delete-attachment', 'ContractController::ContractDeleteAttachment', ['as' => 'erp.contract.deleteattachment']);
        $routes->match(['get', 'post'], 'contract-renewal/(:any)', 'ContractController::renewal_contract/$1', ['as' => 'erp.update.renewal']);
        $routes->match(['get', 'post'], 'contract-renewal_delete', 'ContractController::renewal_delete', ['as' => 'erp.renewal.delete']);
        $routes->match(['get', 'post'], 'contract-renewal_data/(:any)', 'ContractController::get_exist_renewal/$1', ['as' => 'erp.renewal.data']);
        $routes->match(['get', 'post'], 'contract-task-view', 'ContractController::contract_task_datatable', ['as' => 'erp.contract_task_datatable']);
        $routes->match(['get', 'post'], 'contract-task-related-to', 'ContractController::relatedJobTask', ['as' => 'erp.contract.task.relate_to']);
        $routes->get('contract-ajaxfetchemployee', 'ContractController::ajaxFetchemployee', ['as' => 'erp.contract.task.ajaxFetchemployee']);
        $routes->match(['get', 'post'], 'contract-ajaxContractTaskAdd/(:any)', 'ContractController::contract_task_add/$1', ['as' => 'erp.contract.task.add']);
        $routes->match(['get', 'post'], 'contract-ajaxContractTaskfetch', 'ContractController::contract_exist_data', ['as' => 'erp.taskexist.data.fetch']);
        $routes->match(['get', 'post'], 'contract-ajaxContractTaskUpdate', 'ContractController::contract_task_update', ['as' => 'erp.contract.task.update']);
        $routes->match(['get', 'post'], 'contract-ajaxContractTaskdelete', 'ContractController::contract_task_delete', ['as' => 'erp.contract.task.delete']);
        $routes->match(['get', 'post'], 'contract-ajaxContract-signature', 'ContractController::contract_view_sign', ['as' => 'erp.contract.sign']);
        $routes->match(['get', 'post'], 'contract-ajaxContract-signature/(:num)/(:any)', 'ContractController::contract_view_pdf/$1/$2', ['as' => 'erp.contract.view.pdf.download']);
        $routes->get('Contract-Export', 'ContractController::contract_export', ['as' => 'erp.contract.export']);
    });

    $routes->group('expenses', function ($routes) {
        $routes->get('Expenses-view', 'ExpensesController::index', ['as' => 'erp.expensesview']);
        $routes->get('Expenses-Report', 'ExpensesController::reportview', ['as' => 'erp.expensesreportview']);
        $routes->Post('Expenses-ajax', 'ExpensesController::ajaxExpenseTable', ['as' => 'erp.ajaxexpensetable']);
        $routes->match(["get", "post"], 'Expenses-add', 'ExpensesController::addExpense', ['as' => 'erp.expensesadd']);
        $routes->match(["get", "post"], 'Expenses-datatable', 'ExpensesController::ajax_data_table_expense', ['as' => 'erp.expense.datatable']);
        $routes->match(['get', 'post'], 'Expenses-fetch-type', 'ExpensesController::fetch_expense_categories', ['as' => 'erp.fetchexpensescategories']);
        $routes->match(['get', 'post'], 'Expenses-add-type', 'ExpensesController::add_expense_categories', ['as' => 'erp.expenses.type.add']);
        $routes->match(['get', 'post'], 'fetch_customer_details_ajax', 'ExpensesController::fetch_customer_details_ajax', ['as' => 'erp.expenses.customer.fetch']);
        $routes->match(['get', 'post'], 'Currency-fetch-type', 'ExpensesController::fetch_currency_details_ajax', ['as' => 'erp.currency.type']);
        $routes->match(['get', 'post'], 'Expenses-update/(:any)', 'ExpensesController::updateExpenses/$1', ['as' => 'erp.expenses.update.view']);
        $routes->match(['get', 'post'], 'Expenses-delete/(:any)', 'ExpensesController::expense_delete/$1', ['as' => 'erp.expenses.delete']);
        $routes->match(['get', 'post'], 'Expenses-attachment-delete/(:num)/(:num)', 'ExpensesController::deleteattachment/$1/$2', ['as' => 'expense.attachment.recipt.delete']);
        $routes->match(['get', 'post'], 'Expenses-attachment-view-delete/(:num)/(:num)', 'ExpensesController::deleteattachmentview/$1/$2', ['as' => 'expense.view.attachment.recipt.delete']);
        $routes->match(['get', 'post'], 'Expenses-view/(:num)', 'ExpensesController::expense_view/$1', ['as' => 'erp.expenses.view.page']);
        $routes->match(['get', 'post'], 'project-fetch', 'ExpensesController::project_fetch', ['as' => 'erp.expenses.project.fetch']);
        $routes->match(['get', 'post'], 'attachment_update/(:any)', 'ExpensesController::attachment_update/$1', ['as' => 'erp.expenses.attachment.update']);
        $routes->match(['get', 'post'], 'expense-fetch', 'ExpensesController::expense_details', ['as' => 'erp.expense.task.relate_to']);
        $routes->match(['get', 'post'], 'expense-task-add/(:any)', 'ExpensesController::expense_task_add/$1', ['as' => 'erp.expense.task.add']);
        $routes->match(['get', 'post'], 'expense-task-table', 'ExpensesController::expense_task_data_table', ['as' => 'erp.expense.task.fetchTable']);
        $routes->match(['get', 'post'], 'expense-task-exist-data', 'ExpensesController::expense_task_exist_data', ['as' => 'erp.expense.taskexist.data.fetch']);
        $routes->match(['get', 'post'], 'expense-task-update', 'ExpensesController::expense_task_update', ['as' => 'erp.expense.task.update']);
        $routes->match(['get', 'post'], 'expense-task-delete', 'ExpensesController::expense_task_delete', ['as' => 'erp.expense.task.delete']);
        $routes->match(['get', 'post'], 'expense-reminder-add/(:any)', 'ExpensesController::expense_reminder_add/$1', ['as' => 'erp.expense.reminder.add']);
        $routes->match(['get', 'post'], 'expense-reminder-datatable', 'ExpensesController::expense_reminder_data_table', ['as' => 'erp.expense.reminder.fetchTable']);
        $routes->match(['get', 'post'], 'expense-reminder-exist-data', 'ExpensesController::expense_reminder_exist_data', ['as' => 'erp.reminder.exist.modal.data']);
        $routes->match(['get', 'post'], 'expense-reminder-exist-data-update', 'ExpensesController::expense_reminder_exist_update', ['as' => 'erp.expense.reminder.update']);
        $routes->match(['get', 'post'], 'expense-reminder-delete', 'ExpensesController::expense_reminder_delete', ['as' => 'erp.expenses.reminder.delete']);
        $routes->get('Expense-Export', 'ExpensesController::contract_export', ['as' => 'erp.expenses.export']);
        $routes->get('Expense-Report-Export', 'ExpensesController::contract_response_export', ['as' => 'erp.expenses.report.export']);
    });


    //Goals 
    $routes->group('Goals', function ($routes) {
        $routes->get('/', 'GoalsController::index', ['as' => 'erp.goalsview']);
        $routes->match(['get', 'post'], 'Goals_add', 'GoalsController::goalsadd', ['as' => 'erp.goalsadd']);
        $routes->match(['get', 'post'], 'Goals_update/(:any)', 'GoalsController::goalsupdate/$1', ['as' => 'erp.goalsupdate']);
        $routes->match(['get', 'post'], 'Goals_delete/(:any)', 'GoalsController::goalsdelete/$1', ['as' => 'erp.goalsdelete']);
        $routes->match(['get', 'post'], 'Goals_datatable', 'GoalsController::get_data_table', ['as' => 'erp.goals.data.table']);
        $routes->match(['get', 'post'], 'Goals_reminder', 'GoalsController::goals_reminder', ['as' => 'erp.notify.reminder.goals']);
        $routes->get('Goals-Export', 'GoalsController::goals_export', ['as' => 'erp.goals.export']);
    });

    // Notification Routes
    $routes->group('notify', function ($routes) {
        // Notification Center
        $routes->get('notification_center', 'NotificationController::index', ['as' => 'erp.notification.center']);

        $routes->get('get_notification_ajax', 'NotificationController::getNotifyByUser', ['as' => 'erp.notify.getNotifyByUser']);

        $routes->get('ajax_fetch_notify/(:num)', 'NotificationController::ajax_fetch_notify/$1', ['as' => 'ajax_fetch_notify']);
    });

    // Timesheet Routes
    $routes->group('timesheet', function ($routes) {
        $routes->get('list_timesheets', 'Timesheetcontroller::list_timesheets', ['as' => 'erp.timesheet.list']);
        $routes->post('get_running_timer', 'Timesheetcontroller::check_for_running_timer_api', ['as' => 'erp.timesheet.running_timerapi']);
        $routes->post('running_timer', 'Timesheetcontroller::check_for_running_timer', ['as' => 'erp.timesheet.running_timers']);
        $routes->post('add_timer', 'Timesheetcontroller::add_timer', ['as' => 'erp.timesheet.add_timer']);
        $routes->get('delete_timer/(:num)', 'Timesheetcontroller::delete_timer/$1', ['as' => 'erp.timesheet.delete_timer']);
        $routes->post('delete_timer_api', 'Timesheetcontroller::delete_timer_api', ['as' => 'erp.timesheet.delete_timer_api']);
        $routes->post('stop_timer', 'Timesheetcontroller::end_timer_api', ['as' => 'erp.timesheet.stop_timer']);
        $routes->get('data_for_datatable', 'Timesheetcontroller::ajax_timesheet_response', ['as' => 'erp.timesheet.datatable']);
        $routes->get('timeSheetExport', 'Timesheetcontroller::timeSheetExport', ['as' => 'erp.timesheet.export']);
        $routes->post('timeSheet_get_tasks', 'Timesheetcontroller::get_all_tasks', ['as' => 'erp.timesheet.get_all_tasks']);
    });

});


// client - routes
require APPPATH . 'Config/MyRoutes/client-routes.php';