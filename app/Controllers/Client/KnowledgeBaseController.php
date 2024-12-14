<?php

namespace App\Controllers\Client;

use App\Controllers\BaseController;
use App\Models\Erp\KnowledgebaseArticleModel;
use App\Models\Erp\KnowledgeBaseFeedbackModel;
use PhpOffice\PhpSpreadsheet\Calculation\Web\Service;
class KnowledgeBaseController extends BaseController
{

    protected $KnowledgebaseArticleModel;
    protected $KnowledgeBaseFeedbackModel;
    protected $logger;

    public function __construct()
    {
        $this->KnowledgebaseArticleModel = new KnowledgebaseArticleModel();
        $this->KnowledgeBaseFeedbackModel = new KnowledgeBaseFeedbackModel();
        helper(['erp', 'form']);
        $this->logger = \Config\Services::logger();
    }


    public function index()
    {
        $data['pageFolder'] = "knowledgebase/index";

        return view('client/index', $data);
    }


    public function search()
    {
        $search = $this->request->getPost('search');
        $client_id = session('contact_id');

        if (empty($client_id)) {
            $this->session->setFlashdata("error", "Oops something went wrong!");
            return $this->response->redirect(
                url_to('front.Knowledgebase.view')
            );
        }

        $result = [];
        if (!empty($search)) {
            $result = $this->KnowledgebaseArticleModel
                ->select('knowledge_base.article_id as article_id,knowledge_base.article_subject as article_subject,knowledge_base.article_description as article_description,knowledge_base.date_added as date_added,
                knowledge_base_feedback.id as id,knowledge_base_feedback.customer_id as customer_id,knowledge_base_feedback.article_id as feedback_article_id') // Select columns explicitly
                ->join('knowledgebase_groups', 'knowledge_base.article_group_id = knowledgebase_groups.group_id') // Specify the table and join condition
                ->join('knowledge_base_feedback', 'knowledge_base_feedback.article_id = knowledge_base.article_id AND knowledge_base_feedback.customer_id = ' . $client_id . '  AND knowledge_base_feedback.user_type = "client"','LEFT') // Specify the table and join condition
                ->where("knowledge_base.Internal_article", 0)
                ->where("knowledgebase_groups.disabled", 0)
                ->where("knowledge_base.disabled", 0)
                // ->where("knowledge_base_feedback.customer_id", $client_id)
                // ->where("knowledge_base_feedback.user_type", "client")
                // ->orWhere("knowledge_base_feedback.customer_id IS NULL")
                // ->groupStart()
                // ->groupEnd()
                ->like("knowledge_base.article_subject", $search)
                ->get()
                ->getResultArray();

            $this->logger->error($result);
        }
        $data['pageFolder'] = "knowledgebase/index";
        $data["knowledgebase_article"] = $result;
        $data["searchText"] = $search;
        // $this->logger->error(isset($data["knowledgebase_article"]));

        return view('client/index', $data);
    }

    public function feedback()
    {
        $article_id = $_POST["id"];
        $type = $_POST["type"];
        $customer_id = $_POST["contact_id"];

        if (empty($customer_id)) {
            return $this->response->setJSON(["success" => false, "message" => "Oops Something went wrong!"]);
        }
        $result = $this->KnowledgeBaseFeedbackModel->store($customer_id, $type, $article_id, "client");
        return $this->response->setJSON($result);
    }
}
?>