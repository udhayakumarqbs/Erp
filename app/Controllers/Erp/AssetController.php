<?php

namespace App\Controllers\Erp;

use App\Controllers\BaseController;
use App\Models\Erp\AssetModel;
use App\Libraries\Exporter;
use App\Libraries\Exporter\ExporterConst;

class AssetController extends BaseController
{
    public function __construct()
    {
        //parent::__construct();
        helper(['erp']);
        //$AssetModel = new AssetModel();
    }

    public function index()
    {
        $AssetModel = new AssetModel();
        $data['equip_status'] = $AssetModel->get_equip_status();
        $data['equip_datatable_config'] = $AssetModel->get_dtconfig_equipments();
        $data['page'] = "asset/equipments";
        $data['menu'] = "asset";
        $data['submenu'] = "equipment";
        return view("erp/index", $data);
    }

    public function ajax_equipments_response()
    {
        $AssetModel = new AssetModel();
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
        $response = $AssetModel->getEquipmentDatatable($limit, $offset, $search, $orderby, $ordercolPost, $filterStatus);
        header("Content-Type:application/json");
        echo json_encode($response);
        exit();
    }
    //  Add/Create
    public function EquipmentAdd()
    {
        $AssetModel = new AssetModel();
        $session = session();
        $dataPost = [
            'name' => $this->request->getPost("name"),
            'code' => $this->request->getPost("code"),
            'model' => $this->request->getPost("model"),
            'maker' => $this->request->getPost("maker"),
            'bought_date' => $this->request->getPost("bought_date"),
            'age' => $this->request->getPost("age"),
            'work_type' => $this->request->getPost("work_type"),
            'consump_type' => $this->request->getPost("consump_type"),
            'consumption' => $this->request->getPost("consumption"),
            'status' => $this->request->getPost("equip_status"),
            'description' => $this->request->getPost("description"),
            'created_at' => time(),
            'created_by' => get_user_id(),
        ];

        if ($this->request->getPost("name")) {
            if ($AssetModel->insertEquipment($dataPost)) {
                return redirect()->to('erp/assets');
            } else {
                return redirect()->to('erp/assets/equipmentadd');
            }
        }

        $data['equip_status'] = $AssetModel->get_equip_status();
        $data['page'] = 'asset/equipmentadd';
        $data['menu'] = 'asset';
        $data['submenu'] = 'equipment';

        return view('erp/index', $data);
    }

    // View
    public function EquipmentView($equip_id)
    {
        $AssetModel = new AssetModel();
        $data['equipment'] = $AssetModel->get_equipment_by_id($equip_id);
        if (empty($data['equipment'])) {
            session()->setFlashdata("op_error", "Equipment Not Found");
            return $this->response->redirect(url_to('erp.assets'));
        }
        $data['equip_status'] = $AssetModel->get_equip_status();
        $data['equip_status_bg'] = $AssetModel->get_equip_status_bg();
        $data['equip_id'] = $equip_id;
        $data['attachments'] = $AssetModel->get_attachments("equipment", $equip_id);
        // var_dump($data['attachments']);
        // exit();
        $data['page'] = "asset/equipmentview";
        $data['menu'] = "asset";
        $data['submenu'] = "equipment";
        return view("erp/index", $data);
    }

    // Edit
    public function EquipmentEdit($equip_id)
    {
        $AssetModel = new AssetModel(); // Assuming AssetModel is a CodeIgniter 4 model

        if ($this->request->getPost("name")) {
            $dataPost = [
                'name' => $this->request->getPost("name"),
                'code' => $this->request->getPost("code"),
                'model' => $this->request->getPost("model"),
                'maker' => $this->request->getPost("maker"),
                'bought_date' => $this->request->getPost("bought_date"),
                'age' => $this->request->getPost("age"),
                'work_type' => $this->request->getPost("work_type"),
                'consump_type' => $this->request->getPost("consump_type"),
                'consumption' => $this->request->getPost("consumption"),
                'status' => $this->request->getPost("equip_status"),
                'description' => $this->request->getPost("description"),
            ];
            if ($AssetModel->updateEquipment($equip_id, $dataPost)) {
                return $this->response->redirect(url_to('erp.assets'));
            } else {
                return $this->response->redirect(url_to('erp.equipment.edit', $equip_id));
            }
        }

        $data['equipment'] = $AssetModel->get_equipment_by_id($equip_id);

        if (empty($data['equipment'])) {
            session()->setFlashdata("op_error", "Equipment Not Found");
            return $this->response->redirect(url_to('erp.assets'));
        }

        $data['equip_id'] = $equip_id;
        $data['equip_status'] = $AssetModel->get_equip_status();
        $data['page'] = "asset/equipmentedit";
        $data['menu'] = "asset";
        $data['submenu'] = "equipment";

        return view('erp/index', $data);
    }

    // Delete
    public function DeleteEquipment($equip_id)
    {
        $AssetModel = new AssetModel();


        $equipment = $AssetModel->get_equipment_by_id($equip_id);

        if (empty($equipment)) {
            session()->setFlashdata("op_error", "Equipment Not Found");
            return redirect()->to('erp/asset/equipments');
        }

        // Attempt to delete the equipment
        $deleted = $AssetModel->delete_equipment($equip_id);

        if ($deleted) {
            // Equipment successfully deleted
            session()->setFlashdata("op_success", "Equipment successfully deleted");
        } else {
            // Failed to delete equipment
            session()->setFlashdata("op_error", "Equipment failed to delete");
        }

        // Redirect to the equipment list page
        return $this->response->redirect(url_to('erp.assets'));
    }


    public function Upload_Equipattachment()
    {
        $AssetModel = new AssetModel();
        $file = $this->request->getFile('attachment');
        $id = $this->request->getPostGet('id') ?? 0;
        // var_dump($file);
        // exit();
        $response = array();
        if (!empty($_FILES['attachment']['name'])) {
            $response = $AssetModel->upload_attachment("equipment", $file, $id);
        } else {
            $response['error'] = 1;
            $response['reason'] = "No file to upload";
        }
        header("Content-Type:application/json");
        echo json_encode($response);
        exit();
    }

    public function Delete_Equipattachment()
    {
        $AssetModel = new AssetModel();
        $attach_id = $this->request->getGet("id") ?? 0;
        $response = array();
        $response = $AssetModel->delete_attachment("equipment", $attach_id);
        header("Content-Type:application/json");
        echo json_encode($response);
        exit();
    }


    public function ajaxEquipCodeUnique()
    {
        $db = \Config\Database::connect();
        $code = $this->request->getGet('data');
        $id = $this->request->getGet('id') ?? 0;

        $query = $db->table('equipments')
            ->select('code')
            ->where('code', $code);

        if (!empty($id)) {
            $query->where('equip_id <>', $id);
        }

        $check = $query->get()->getRow();
        $response = [];

        if (empty($check)) {
            $response['valid'] = 1;
        } else {
            $response['valid'] = 0;
            $response['msg'] = "Equipment Code already exists";
        }

        header("Content-Type: application/json");
        echo json_encode($response);
        exit();
    }

    // Export Code
    public function EquipmentsExport()
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

        $config['title'] = "Equipments";

        if ($type == "pdf") {
            $config['column'] = ["Code", "Name", "Model", "Maker", "Bought Date", "Age", "Status"];
        } else {
            $config['column'] = ["Code", "Name", "Model", "Maker", "Bought Date", "Age", "Status", "Work Type", "Consumption Type", "Consumption", "Description"];
            $config['column_data_type'] = [ExporterConst::E_STRING, ExporterConst::E_STRING, ExporterConst::E_STRING, ExporterConst::E_STRING, ExporterConst::E_STRING, ExporterConst::E_STRING, ExporterConst::E_STRING, ExporterConst::E_STRING, ExporterConst::E_STRING, ExporterConst::E_STRING, ExporterConst::E_STRING];
        }

        $assetModel = new AssetModel();
        $config['data'] = $assetModel->get_equipment_export($type, $limit, $offset, $search, $orderby, $ordercolPost, $status);

        if ($type == "pdf") {
            return Exporter::export(ExporterConst::PDF, $config);
        } else if ($type == "excel") {
            return Exporter::export(ExporterConst::EXCEL, $config);
        } else if ($type == "csv") {
            return Exporter::export(ExporterConst::CSV, $config);
        }
    }
}
