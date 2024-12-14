<?php

namespace App\Controllers\Erp;

use App\Controllers\BaseController;
use App\Libraries\Exporter;
use App\Libraries\Exporter\ExporterConst;
use App\Models\Erp\PurchaseOrderModel;
use App\Models\Erp\DeliveryRecordsModel;
use App\Models\Erp\TransportModel;
use App\Models\Erp\Transport\TypesModel;
use App\Models\Erp\TransportTypeModel;

class TransportController extends BaseController
{
    protected $data;
    protected $db;
    protected $session;
    protected $TransportTypeModel;
    protected $TransportModel;
    protected $TypesModel;
    protected $PurchaseOrderModel;
    protected $DeliveryRecordsModel;


    public function __construct()
    {
        $this->TransportTypeModel = new TransportTypeModel();
        $this->TransportModel = new TransportModel();
        $this->TypesModel = new TypesModel();
        $this->PurchaseOrderModel = new PurchaseOrderModel();
        $this->DeliveryRecordsModel = new DeliveryRecordsModel();

        $this->session = \Config\Services::session();
        $this->db = \Config\Database::connect();
        helper(['form', 'erp']);
    }


    public function Types()
    {
        $TypesModel = new TypesModel();
        $data['type_datatable_config'] = $TypesModel->get_dtconfig_types();
        $data['page'] = "transport/transporttype";
        $data['menu'] = "transport";
        $data['submenu'] = "type";
        return view("erp/index", $data);
    }

    public function ajaxTransportTypeUnique()
    {
        $TypesModel = new TypesModel();
        $type_name = $_GET['data'];
        $id = $_GET['id'] ?? 0;
        $query = "SELECT type_name FROM transport_type WHERE type_name='$type_name' ";
        if (!empty($id)) {
            $query = "SELECT type_name FROM transport_type WHERE type_name='$type_name' AND type_id<>$id";
        }
        $check = $TypesModel->query($query)->getRow();
        $response = array();
        if (empty($check)) {
            $response['valid'] = 1;
        } else {
            $response['valid'] = 0;
            $response['msg'] = "Type Name already exists";
        }
        return $this->response->setJSON($response);
    }

    public function typeaddedit()
    {
        $type_id = $this->request->getPost("type_id");
        $type_name = $this->request->getPost("type_name");
        if ($this->request->getPost("type_name")) {
            $this->TransportTypeModel->insert_update_type($type_id, $type_name);
        }
        return $this->response->redirect(url_to("erp.transport.types"));
    }

    public function ajaxTypesResponse()
    {
        $response = array();
        $response = $this->TransportTypeModel->get_datatable_types();
        return $this->response->setJSON($response);
    }

    public function ajaxFetchType($type_id)
    {
        $response = array();
        $query = "SELECT type_id,type_name FROM transport_type WHERE type_id=$type_id";
        $result = $this->db->query($query)->getRow();
        if (!empty($result)) {
            $response['error'] = 0;
            $response['data'] = $result;
        } else {
            $response['error'] = 1;
        }
        return $this->response->setJSON($response);
    }

    public function typedelete($type_id)
    {
        $this->TransportModel->delete_type($type_id);
        return $this->response->redirect(url_to("erp.transport.types"));
    }

    public function typeExport()
    {
        $type = $_GET['export'];
        $config = array();
        $config['title'] = "Transport Types";
        if ($type == "pdf") {
            $config['column'] = array("Type Name");
        } else {
            $config['column'] = array("Type Name");
            $config['column_data_type'] = array(ExporterConst::E_STRING);
        }
        $config['data'] = $this->TransportTypeModel->get_type_export($type);
        if ($type == "pdf") {
            Exporter::export(ExporterConst::PDF, $config);
        } else if ($type == "excel") {
            Exporter::export(ExporterConst::EXCEL, $config);
        } else if ($type == "csv") {
            Exporter::export(ExporterConst::CSV, $config);
        }
    }

    public function transports()
    {
        $this->data['transport_types'] = $this->TransportTypeModel->get_all_types();
        $this->data['transport_status'] = $this->TransportModel->get_transport_status();
        $this->data['transport_datatable_config'] = $this->TransportModel->get_dtconfig_transports();
        $this->data['page'] = "transport/transport";
        $this->data['menu'] = "transport";
        $this->data['submenu'] = "transport";
        return view("erp/index", $this->data);
    }

    public function transportaddedit()
    {
        $post = [
            'name' => $this->request->getPost("name"),
            'code' => $this->request->getPost("code"),
            'description' => $this->request->getPost("description"),
            'type_id' => $this->request->getPost("type_id"),
        ];
        $transport_id = $this->request->getPost("transport_id");
        if ($this->request->getPost("name")) {
            $this->TransportModel->insert_update_transport($transport_id,$post);
        }
        return $this->response->redirect(url_to("erp.transport.transports"));
    }

    public function ajaxTransportCodeUnique()
    {
        $code = $_GET['data'];
        $id = $_GET['id'] ?? 0;
        $query = "SELECT code FROM transports WHERE code='$code' ";
        if (!empty($id)) {
            $query = "SELECT code FROM transports WHERE code='$code' AND transport_id<>$id";
        }
        $check = $this->db->query($query)->getRow();
        $response = array();
        if (empty($check)) {
            $response['valid'] = 1;
        } else {
            $response['valid'] = 0;
            $response['msg'] = "Code already exists";
        }
        return $this->response->setJSON($response);
    }

    public function ajaxTransportsResponse()
    {
        $response = array();
        $response = $this->TransportModel->get_datatable_transports();
        return $this->response->setJSON($response);
    }

    public function ajaxTransportActive($transport_id)
    {
        $response = array();
        $query = "UPDATE transports SET active=active^1 WHERE transport_id=$transport_id";
        $this->db->query($query);
        if ($this->db->affectedRows() > 0) {
            $response['error'] = 0;
        } else {
            $response['error'] = 1;
        }
        return $this->response->setJSON($response);
    }

    public function ajaxFetchTransport($transport_id)
    {
        $response = array();
        $query = "SELECT transport_id,name,code,type_id,description FROM transports WHERE transport_id=$transport_id";
        $result = $this->db->query($query)->getRow();
        if (!empty($result)) {
            $response['error'] = 0;
            $response['data'] = $result;
        } else {
            $response['error'] = 1;
        }
        return $this->response->setJSON($response);
    }

    public function transportdelete($transport_id)
    {
        $this->PurchaseOrderModel->delete_transport($transport_id);
        return $this->response->redirect(url_to("erp.transport.transports"));
    }

    public function transportExport()
    {
        $type = $_GET['export'];
        $config = array();
        $config['title'] = "Transports";
        if ($type == "pdf") {
            $config['column'] = array("Name", "Code", "Type", "Status", "Active", "Description");
        } else {
            $config['column'] = array("Name", "Code", "Type", "Status", "Active", "Description");
            $config['column_data_type'] = array(ExporterConst::E_STRING, ExporterConst::E_STRING, ExporterConst::E_STRING, ExporterConst::E_STRING, ExporterConst::E_STRING, ExporterConst::E_STRING);
        }
        $config['data'] = $this->TransportModel->get_transports_export($type);
        if ($type == "pdf") {
            Exporter::export(ExporterConst::PDF, $config);
        } else if ($type == "excel") {
            Exporter::export(ExporterConst::EXCEL, $config);
        } else if ($type == "csv") {
            Exporter::export(ExporterConst::CSV, $config);
        }
    }

    public function deliveryrecords()
    {
        $this->data['delivery_datatable_config'] = $this->DeliveryRecordsModel->get_dtconfig_deliveryrecords();
        $this->data['delivery_status'] = $this->DeliveryRecordsModel->get_delivery_status();
        $this->data['delivery_type'] = $this->TypesModel->get_delivery_type();
        $this->data['page'] = "transport/deliveryrecords";
        $this->data['menu'] = "transport";
        $this->data['submenu'] = "deliveryrecord";
        return view("erp/index", $this->data);
    }

    public function ajaxDeliveryrecordsResponse()
    {
        $response = array();
        $response = $this->DeliveryRecordsModel->get_datatable_deliveryrecords();
        return $this->response->setJSON($response);
    }

    public function deliverystart($delivery_id)
    {
        $this->DeliveryRecordsModel->change_delivery_status($delivery_id, 1);
        return $this->response->redirect(url_to("erp.transport.deliveryrecords"));
    }

    public function deliveryend($delivery_id)
    {
        $this->DeliveryRecordsModel->change_delivery_status($delivery_id, 2);
        return $this->response->redirect(url_to("erp.transport.deliveryrecords"));
    }

    public function deliverycancel($delivery_id)
    {
        $this->DeliveryRecordsModel->change_delivery_status($delivery_id, 3);
        return $this->response->redirect(url_to("erp.transport.deliveryrecords"));
    }

    public function deliveryrecordExport()
    {
        $type = $_GET['export'];
        $config = array();
        $config['title'] = "Delivery Records";
        if ($type == "pdf") {
            $config['column'] = array("Transport Name", "Related To", "Status", "Type", "Location");
        } else {
            $config['column'] = array("Transport Name", "Related To", "Status", "Type", "Location");
            $config['column_data_type'] = array(ExporterConst::E_STRING, ExporterConst::E_STRING, ExporterConst::E_STRING, ExporterConst::E_STRING, ExporterConst::E_STRING);
        }
        $config['data'] = $this->DeliveryRecordsModel->get_deliveryrecords_export($type);
        if ($type == "pdf") {
            Exporter::export(ExporterConst::PDF, $config);
        } else if ($type == "excel") {
            Exporter::export(ExporterConst::EXCEL, $config);
        } else if ($type == "csv") {
            Exporter::export(ExporterConst::CSV, $config);
        }
    }
}
