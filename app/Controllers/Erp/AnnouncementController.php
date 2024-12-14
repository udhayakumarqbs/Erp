<?php

namespace App\Controllers\Erp;


use App\Controllers\BaseController;
use App\Libraries\Exporter;
use App\Libraries\Exporter\ExporterConst;
use App\Libraries\Importer;
use App\Libraries\Importer\AbstractImporter;
use App\Libraries\Requestor;
use CodeIgniter\Controller;
use App\Models\Erp\Announcementmodel;           


class AnnouncementController extends BaseController
{   

    protected $data;
    protected $Announcementmodel;

    public function __construct()
    {
        $this->Announcementmodel = new Announcementmodel();
        helper(['erp', 'form']);
    }

    // index page for announcements
    public function index()
    {
        $this->data['menu'] = "announcements";
        $this->data['submenu'] = "announcements";
        $this->data['page'] = "Announcements/announcements";

        $this->data['dt_config'] = $this->Announcementmodel->get_dtconfig_announcement();

        return view("erp/index", $this->data);
    }

    public function add()
    {

        $this->data['menu'] = "announcements";
        $this->data['submenu'] = "announcements";
        $this->data['page'] = "Announcements/announcementsadd";

        return view("erp/index", $this->data);
    }

    public function insert_announcement()
    {

        $data = [
            'name' => '',
            'message' => '',
            'showtousers' => $this->request->getpost('showtousers'),
            'showtostaff' =>  $this->request->getpost('showtostaff'),
            'showname' =>  $this->request->getpost('showname'),
        ]; 
        $data['name'] = $this->request->getpost('name');
        $data['message'] = html_purify($this->request->getpost('message'));
        $data['userid'] = get_user_id();
      
        
        if (isset($data['showtostaff'])) {
            $data['showtostaff'] = 1;
        } else {
            $data['showtostaff'] = 0;
        }
        if (isset($data['showtousers'])) {
            $data['showtousers'] = 1;
        } else {
            $data['showtousers'] = 0;
        }
        if (isset($data['showname'])) {
            $data['showname'] = 1;
        } else {
            $data['showname'] = 0;
        }
        $insert = $this->Announcementmodel->insert_announcement($data);
        

        if($insert){
           $this->session->setFlashdata('op_success','Announcement added successuflly');
            $this->response->redirect(url_to('erp.announcements'));
        }
        else{
           $this->session->setFlashdata('op_error','Error occured!');
            $this->response->redirect(url_to('erp.announcementsadd')); 
        }

        // if ($id == '') {
        //     $id = $this->Announcementmodel->insert_announcement($data);
        //     if ($id) {
        //         $this->session->setFlashdata('op_success', 'Announcement added successfully');
        //         $this->response->redirect(url_to('' , $id));
        //     }
        // }
    }
        
    public function ajaxAnnouncementResponse()
    {
        $response = array();
        $response = $this->Announcementmodel->get_announcementData();
        return $this->response->setJSON($response);
    }
    
    //announcement delete

    public function deleteannouncement($a_id){
     
        $delete_announcement =  $this->Announcementmodel->delete_row($a_id);
        // var_dump($a_id);
        if($delete_announcement){
            $this->session->setFlashdata('op_success','Announcement deleted successfully');
            $this->response->redirect(url_to('erp.announcements'));
        }
        else{
            $this->session->setFlashdata('op_error','Error occured');
            $this->response->redirect(url_to('erp.announcements'));
        }
    }

    //announcement update view
    public function updateannouncement($a_id){
        $this->data['menu'] = "announcements";
        $this->data['submenu'] = "announcements";
        $this->data['page'] = "Announcements/announcementupdate";
        
        if($this->request->getPost()){

            $data = [
                'name' => $this->request->getpost('name'),
                'message' => html_purify($this->request->getpost('message')),
                'showtousers' => $this->request->getpost('showtousers'),
                'showtostaff' =>  $this->request->getpost('showtostaff'),
                'showname' =>  $this->request->getpost('showname'),
            ]; 

            // var_dump($data , $a_id);
            // exit;

            if (isset($data['showtostaff'])) {
                $data['showtostaff'] = 1;
            } else {
                $data['showtostaff'] = 0;
            }
            if (isset($data['showtousers'])) {
                $data['showtousers'] = 1;
            } else {
                $data['showtousers'] = 0;
            }
            if (isset($data['showname'])) {
                $data['showname'] = 1;
            } else {
                $data['showname'] = 0;
            }
            
            
            $update = $this->Announcementmodel->update_exist_data($data ,$a_id);

            if($update){
                // $error = $this->Announcementmodel->errors() ?? '';
                // $logger->error($error);
                $this->session->setFlashdata('op_success','Announcement Updated successfully');
                $this->response->redirect(url_to('erp.announcements'));
            }
            else{
                $this->session->setFlashdata('op_error','Error occured');
                $this->response->redirect(url_to('erp.announcementsupdate'));
            }

            

        }

        $this->data['announcement'] = $this->Announcementmodel->getDataById($a_id);
        $this->data['id'] = $a_id;
    
        return view("erp/index", $this->data);
    }

    function ajaxAnnouncementData(){
        
        $data = $this->Announcementmodel->AnnouncementAjaxData();
        
        $response['announcement'] = $data;
        echo json_encode($response);

    }
    public function announcements_Export()
    {
        $type = $_GET['export'];
        $config = array();
        $config['title'] = "announcements";
        if ($type == "pdf") {
            $config['column'] = array("Name", "message", "showtousers", "showtostaff", "showname", "dateadded", "updated_at","userid");
        } else {
            $config['column'] = array("Name", "message", "showtousers", "showtostaff", "showname", "dateadded", "updated_at", "userid");
            $config['column_data_type'] = array(ExporterConst::E_STRING, ExporterConst::E_STRING, ExporterConst::E_STRING, ExporterConst::E_STRING, ExporterConst::E_STRING, ExporterConst::E_STRING, ExporterConst::E_STRING, ExporterConst::E_STRING);
        }
        $config['data'] = $this->Announcementmodel->get_announcement_export($type);

        // var_dump($config['data']);
        // exit;
        if ($type == "pdf") {
            Exporter::export(ExporterConst::PDF, $config);  
        } else if ($type == "excel") {
            Exporter::export(ExporterConst::EXCEL, $config);
        } else if ($type == "csv") {
            Exporter::export(ExporterConst::CSV, $config);
        }
    }
    
    
}
