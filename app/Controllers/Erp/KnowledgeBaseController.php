<?php

namespace App\Controllers\Erp;

use App\Controllers\BaseController;
use CodeIgniter\Controller;
use App\Models\Erp\KnowledgebaseModel;
use App\Models\Erp\KnowledgebaseArticleModel;
use App\Models\Erp\KnowledgeBaseFeedbackModel;
use App\Models\Erp\UserModel;
use App\Models\Erp\CustomersModel;
use App\Libraries\Exporter;
use App\Libraries\Exporter\ExporterConst;
use PhpOffice\PhpSpreadsheet\Shared\Trend\ExponentialBestFit;

class KnowledgeBaseController extends BaseController
{
    protected $data;
    protected $Knowledgebasemodel;
    protected $KnowledgebaseArticlemodel;
    protected $KnowledgeBaseFeedbackModel;
    protected $UserModel;
    protected $CustomersModel;


    public function __construct()
    {
        $this->Knowledgebasemodel = new KnowledgebaseModel();
        $this->KnowledgebaseArticlemodel = new KnowledgebaseArticleModel();
        $this->KnowledgeBaseFeedbackModel = new KnowledgeBaseFeedbackModel();
        $this->UserModel = new UserModel();
        $this->CustomersModel = new CustomersModel();
        helper(['erp', 'form']);
    }

    public function index()
    {
        $this->data['menu'] = 'knowledgebase';
        $this->data['submenu'] = 'knowledgebase';
        $this->data['page'] = 'Knowledgebase/Knowledgebase';
        $this->data["dt_config"] = $this->KnowledgebaseArticlemodel->ajaxtablecolumn();

        return view('erp/index', $this->data);
    }

    public function admin_internel_view($article_id){
        $this->data['menu'] = 'knowledgebase';
        $this->data['submenu'] = 'knowledgebase';
        $this->data['page'] = 'Knowledgebase/articleview';
        $user_id = get_user_id(); 
        $is_voted = $this->KnowledgeBaseFeedbackModel->is_user_voted($user_id,$article_id,"staff");
        $data = $this->KnowledgebaseArticlemodel->get_kb_info($article_id);
        if($data){
            if($is_voted){
                $data["is_voted"] = true;
            }else{
                $data["is_voted"] = false;
            }
            $this->data['data'] =[$data];
        }else{
            $this->session->setFlashdata("op_error","Oops, Something went Wrong!");
            $this->response->redirect(url_to("erp.Knowledgebase"));
        }

        if(!empty(get_user_id())){
            return view('erp/index', $this->data);
        }

    }

    public function report_view()
    {
        $this->data['menu'] = 'knowledgebase';
        $this->data['submenu'] = 'report';
        $this->data['page'] = 'Knowledgebase/report';
        $this->data['groups'] = $this->Knowledgebasemodel->get_groups_menu();
        return view('erp/index', $this->data);
    }

    public function get_knowledgebase_ajax()
    {
        $group_id = $this->request->getPost("id");
        $knowledgebase = $this->KnowledgebaseArticlemodel->get_all_knowledgebase_article($group_id);
        $feedback = $this->KnowledgeBaseFeedbackModel->admin_kb_feedback();
        $staffcount = $this->UserModel->get_users_count();
        $clientcount = $this->CustomersModel->get_clients_count();
        if ($knowledgebase) {
            return $this->response->setJSON(["success" => true, "kb" => $knowledgebase, 'fb' => $feedback,'staffcount'=> $staffcount,'clientcount'=> $clientcount]);
        } else {
            return $this->response->setJSON(["success" => false, "data" => $group_id]);
        }
    }



    public function add()
    {
        $this->data['menu'] = 'knowledgebase';
        $this->data['submenu'] = 'knowledgebase';
        $this->data['page'] = 'Knowledgebase/Knowledgebaseadd';

        if ($this->request->getpost()) {
            $data = [
                "article_subject" => $this->request->getPost("subject"),
                "article_group_id" => $this->request->getPost("dropdown_group"),
                "Internal_article" => $this->request->getPost("Internal_Article"),
                "disabled" => $this->request->getPost("Disabled"),
                "article_description" => $this->request->getPost("articledescription"),
            ];

            if (isset($data["Internal_article"])) {
                $data["Internal_article"] = 1;
            } else {
                $data["Internal_article"] = 0;
            }
            if (isset($data["disabled"])) {
                $data["disabled"] = 1;
            } else {
                $data["disabled"] = 0;
            }

            $result = $this->KnowledgebaseArticlemodel->insert_article($data);

            if ($result) {
                $this->session->setFlashdata("op_success", "Article added successfully");
                $this->response->redirect(url_to("erp.Knowledgebase"));
            } else {
                $this->session->setFlashdata("op_error", "Error occured");
            }
        }

        return view('erp/index', $this->data);
    }

    public function ajaxgroup()
    {
        $data = $this->Knowledgebasemodel->group_fetch();

        $response["groups"] = $data;

        return json_encode($response);
    }

    public function ajaxtable_article()
    {
        $result = array();
        $result = $this->KnowledgebaseArticlemodel->ajaxtablearticle();

        return $this->response->setJSON($result);
    }
    //Article delete

    public function article_delete($id)
    {
        $result = $this->KnowledgebaseArticlemodel->article_delete($id);
        if ($result) {
            $this->session->setFlashdata("op_success", "Article deleted successfully");
            $this->response->redirect(url_to("erp.Knowledgebase"));
        } else {
            $this->session->setFlashdata("op_error", "Error occured");
        }
    }
    //Article delete

    public function article_update($id)
    {

        $this->data["menu"] = "knowledgebase";
        $this->data["submenu"] = "knowledgebase";
        $this->data["page"] = "Knowledgebase/KnowledgebaseArticleUpdate";

        if ($this->request->getpost()) {
            $data = [
                "article_subject" => $this->request->getpost("subject"),
                "article_group_id" => $this->request->getpost("dropdown_group"),
                "Internal_article" => $this->request->getpost("Internal_Article"),
                "disabled" => $this->request->getpost("Disabled"),
                "article_description" => $this->request->getpost("articledescription")
            ];

            if (isset($data["Internal_article"])) {
                $data["Internal_article"] = 1;
            } else {
                $data["Internal_article"] = 0;
            }

            if (isset($data["disabled"])) {
                $data["disabled"] = 1;
            } else {
                $data["disabled"] = 0;
            }

            $result = $this->KnowledgebaseArticlemodel->article_update($id, $data);
            if ($result) {
                $this->session->setFlashdata("op_success", "Article updated");
                $this->response->redirect(url_to("erp.Knowledgebase"));
            } else {
                $this->session->setFlashdata("op_error", "Error occured");
            }
        }
        $this->data["article"] = $this->KnowledgebaseArticlemodel->fetch_exist_article($id);
        $this->data["id"] = $id;

        return view("erp/index", $this->data);

    }

    public function insert_group()
    {
        $groups = [
            'group_name' => $this->request->getPost('name'),
            'short_description' => $this->request->getPost('description'),
            'group_order' => $this->request->getPost('order'),
            'disabled' => $this->request->getPost('disable')
        ];

        // $this->logger->error($groups);
        // $this->logger->error($_POST);

        // var_dump($groups);
        // exit;

        if (isset($groups['disabled'])) {
            $groups["disabled"] = 1;
        } else {
            $groups["disabled"] = 0;
        }

        $result = $this->Knowledgebasemodel->add_group($groups);

        if ($result) {
            return $this->response->setJSON(["success" => true]);
        } else {
            return $this->response->setJSON(["success" => false, "error" => "Failed to insert data"]);
        }

    }
    public function group_insert()
    {
        $groups = [
            'group_name' => $this->request->getPost('name'),
            'short_description' => $this->request->getPost('description'),
            'group_order' => $this->request->getPost('order'),
            'disabled' => $this->request->getPost('disable')
        ];

        if (isset($groups['disabled'])) {
            $groups["disabled"] = 1;
        } else {
            $groups["disabled"] = 0;
        }

        $result = $this->Knowledgebasemodel->add_group($groups);

        if ($result) {
            $this->session->setFlashdata("op_success", "Group added successfully");
            $this->response->redirect(url_to('erp.Knowledgebasegroupview'));
        } else {
            $this->session->setFlashdata("op_error", "Error occured");
            $this->response->redirect(url_to('erp.Knowledgebasegroupview'));
        }

    }
    //group view
    public function group_view()
    {
        $this->data['menu'] = 'knowledgebase';
        $this->data['submenu'] = 'knowledgebase';
        $this->data['page'] = 'Knowledgebase/groupview';
        $this->data['dt_config'] = $this->Knowledgebasemodel->get_dtconfig_knowledgebase();

        return view("erp/index", $this->data);
    }
    //group table api json
    public function getajaxknowledgebase()
    {
        $result = array();
        $result = $this->Knowledgebasemodel->get_ajax_knowledgebase();
        return $this->response->setJSON($result);
    }
    //group update
    public function knowledgebase_update_view($id)
    {
        $this->data['menu'] = 'knowledgebase';
        $this->data['submenu'] = 'knowledgebase';
        $this->data['page'] = 'Knowledgebase/groupupdate';

        if ($this->request->getpost()) {
            $groups = [
                "group_name" => $this->request->getpost("name"),
                "short_description" => $this->request->getpost("description"),
                "group_order" => $this->request->getpost("order"),
                "disabled" => $this->request->getpost("disable"),
            ];

            if (isset($groups["disabled"])) {
                $groups["disabled"] = 1;
            }
            $result = $this->Knowledgebasemodel->update_group($id, $groups);

            if ($result) {
                $this->session->setFlashdata("op_success", "Group updated");
                $this->response->redirect(url_to('erp.Knowledgebasegroupview'));
            } else {
                $this->session->setFlashdata("op_error", "Error occured");
            }
        }
        $this->data['Knowledgebase'] = $this->Knowledgebasemodel->get_exist_group($id);
        $this->data['id'] = $id;
        return view("erp/index", $this->data);

    }
    //group delete 
    public function knowledgebase_delete($id)
    {
        $article_id = $this->KnowledgebaseArticlemodel->article_group_id($id);
        $count = count($article_id);
        if ($count > 0) {
            $this->session->setFlashdata("op_error", "This ID of the Group is already using!");
            $this->response->redirect(url_to("erp.Knowledgebasegroupview"));
        } else {
            $result = $this->Knowledgebasemodel->delete_group($id);
            if ($result) {
                $this->session->setFlashdata("op_success", "Group deleted successfully");
                $this->response->redirect(url_to('erp.Knowledgebasegroupview'));
            } else {
                $this->session->setFlashdata("op_error", "Error occured");
                $this->response->redirect(url_to('erp.Knowledgebasegroupview'));
            }
        }
    }
    //export groups
    public function export_knowledgebase()
    {
        $type = $_GET["export"];
        $config = array();
        $config["title"] = "Groups";
        if ($type == "pdf") {
            $config["column"] = array("GroupName", "ShortDescription", "GroupOrder", "Disabled");
        } else {
            $config["column"] = array("GroupName", "ShortDescription", "GroupOrder", "Disabled");
            $config["column_data_type"] = array(ExporterConst::E_STRING, ExporterConst::E_STRING, ExporterConst::E_STRING, ExporterConst::E_STRING);
        }

        $config["data"] = $this->Knowledgebasemodel->export_groups($type);
        
        if ($type == "pdf") {
            Exporter::export(ExporterConst::PDF, $config);
        } elseif ($type == "excel") {
            // var_dump(($type));
            // exit;
            Exporter::export(ExporterConst::EXCEL, $config);
        } elseif ($type == "csv") {
            Exporter::export(ExporterConst::CSV, $config);
        }
    }
    //export groups

    public function export_knowledgebase_article()
    {
        $type = $_GET["export"];
        $config = array();
        $config["title"] = "Knowledgebase Article";

        if ($type == "pdf") {
            $config["column"] = array("article_id", "article_subject", "article_group", "Internal_article", "disabled", "article_description", "date_added");
        } else {
            $config["column"] = array("article_id", "article_subject", "article_group", "Internal_article", "disabled", "article_description", "date_added");
            $config["column_data_type"] = array(ExporterConst::E_INTEGER, ExporterConst::E_STRING, ExporterConst::E_STRING, ExporterConst::E_INTEGER, ExporterConst::E_INTEGER, ExporterConst::E_STRING, ExporterConst::E_DATETIME);
        }
        $config["data"] = $this->KnowledgebaseArticlemodel->article_export($type);

        if ($type == "pdf") {
            Exporter::export(ExporterConst::PDF, $config);
        } else if ($type == "excel") {
            Exporter::export(ExporterConst::EXCEL, $config);
        } else if ($type == "csv") {
            Exporter::export(ExporterConst::CSV, $config);
        } 
    }

    public function admin_article_review_store(){

        $user_id = get_user_id();
        $response_type = $this->request->getPost("type");
        $article_id = $this->request->getPost("id");

        $result = $this->KnowledgeBaseFeedbackModel->store($user_id, $response_type, $article_id,"staff");
       
        return $this->response->setJSON($result);
    }
}
