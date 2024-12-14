<?php

namespace App\Models\Erp;

use CodeIgniter\Model;


class KnowledgebaseArticleModel extends Model
{

    protected $table = "knowledge_base";
    protected $primarykey = "article_id";
    protected $allowedFields = [
        "article_subject",
        "article_group_id",
        "Internal_article",
        "disabled",
        "vote",
        "article_description",
        "date_added"
    ];
    protected $db;
    protected $logger;

    protected $useTimestamps = false; // Enable automatic timestamps

    // Specify the fields to be used for automatic timestamps
    protected $dateFormat = 'datetime';
    protected $createdField = 'date_added';

    public function __construct()
    {
        $this->db = \Config\Database::connect();
        $this->logger = \Config\Services::logger();
    }

    public function insert_article($data)
    {
        if ($this->builder()->insert($data)) {
            return true;
        } else {
            return false;
        }
    }

    public function ajaxtablecolumn()
    {
        $config = array(
            "columnNames" => ["sno", "Article Name", "Group", "Status", "Date Published"],
            "sortable" => [0, 1, 1, 0, 0]
        );

        return json_encode($config);
    }

    public function ajaxtablearticle()
    {
        $limit = $_GET["limit"];
        $offset = $_GET["offset"];
        $search = $_GET['search'] ?? "";

        $query = "SELECT a.article_id,a.article_subject,g.group_name,a.disabled,a.date_added,a.Internal_article
                  FROM knowledge_base AS a
                  JOIN  knowledgebase_groups as g
                  ON a.article_group_id = g.group_id ";

        $count = "SELECT a.article_id,a.article_subject,g.group_name,a.disabled,a.date_added
                  FROM knowledge_base AS a
                  JOIN  knowledgebase_groups as g
                  ON a.article_group_id = g.group_id ";

        if (!empty($search)) {
            $query .= " WHERE ( a.article_subject LIKE '%" . $search . "%' OR g.group_name LIKE '%" . $search . "%' ) ";
            $count .= " WHERE ( a.article_subject LIKE '%" . $search . "%' OR g.group_name LIKE '%" . $search . "%' ) ";
        }

        $query .= " LIMIT $offset,$limit";

        $result = $this->db->query($query)->getResultArray();

        $r_result = array();
        $sno = $offset + 1;
        foreach ($result as $r) {
            $action = '<div class="dropdown tableAction">
            <a type="button" class="dropBtn HoverA "><i class="fa fa-ellipsis-v"></i></a>
            <div class="dropdown_container">
                <ul class="BB_UL flex justify-content-around">
                    <li><a target="_BLANK" href="' . url_to('erp.knowledgebase.internelarticle.view', $r["article_id"]) . '" title="View" class="bg-warning"><i class="fa fa-eye"></i> </a></li>
                    <li><a href="' . url_to('erp.knowledgebasearticleupdate', $r["article_id"]) . '" title="Edit" class="bg-success"><i class="fa fa-pencil"></i> </a></li>
                    <li><a href="' . url_to('erp.knowledgebasearticledelete', $r["article_id"]) . '" title="Delete" class="bg-danger del-confirm"><i class="fa fa-trash"></i> </a></li>
                </ul>
            </div>
        </div>';
            if (!$r['disabled']) {
                $status = '<span class="sign-tr-value"> Active </span>';
            } else {
                $status = '<span class="sign-tr-null"> Disable </span>';
            }

            if($r['Internal_article']){
                $subject = "<span class='d-flex justify-content-between align-item-center'>".$r["article_subject"]."<span class='internal-article'>Internal Article</span></span>";
            }else{
                $subject = $r["article_subject"];
            }
            array_push($r_result, array(
                " ",
                $sno,
                $subject,
                $r["group_name"],
                $status,
                $r["date_added"],
                $action
            ));
            $sno++;
        }
        $result["total_rows"] = count($this->db->query($count)->getResultArray());
        $result["data"] = $r_result;

        return $result;
    }
    public function article_delete($id)
    {
        if (
            $this->builder()
                ->from("knowledge_base")
                ->where("article_id", $id)
                ->delete()
        ) {
            return true;
        } else {
            return false;
        }
    }
    public function article_update($id, $data)
    {
        if (
            $this->builder()
                ->where("article_id", $id)
                ->update($data)
        ) {
            return true;
        } else {
            return false;
        }
    }
    public function fetch_exist_article($id)
    {
        return $this->builder()
            ->select()
            ->where("article_id", $id)
            ->get()
            ->getRow();
    }

    public function article_group_id($id)
    {
        $query = "SELECT article_group_id FROM knowledge_base WHERE article_group_id=$id";
        $result = $this->db->query($query)->getResultArray();
        return $result;
    }

    public function article_export($type)
    {
        $query = "";
        if ($type == "pdf") {
            $query = "SELECT a.article_id,a.article_subject,g.group_name,a.Internal_article,a.disabled,REGEXP_REPLACE(a.article_description, '<[^>]*>', '') AS description,a.date_added FROM knowledge_base AS a JOIN  knowledgebase_groups as g ON a.article_group_id = g.group_id";
        } else {
            $query = "SELECT a.article_id,a.article_subject,g.group_name,a.Internal_article,a.disabled,REGEXP_REPLACE(a.article_description, '<[^>]*>', '') AS description,a.date_added FROM knowledge_base AS a JOIN  knowledgebase_groups as g ON a.article_group_id = g.group_id";
        }
        $result = $this->db->query($query)->getResultArray();

        return $result;
    }

    public function get_all_knowledgebase_article($group_id)
    {
        return $this->builder()->where("article_group_id", $group_id)->where("disabled", 0)->get()->getResultArray();
    }

    public function get_kb_info($article_id){
        $result = $this->builder()->where("article_id",$article_id)->get()->getRowArray();

        if($result){
            return $result;
        }else{
            return false;
        }
    }
}
