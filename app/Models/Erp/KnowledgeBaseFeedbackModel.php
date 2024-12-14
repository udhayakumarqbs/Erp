<?php

namespace App\Models\Erp;

use CodeIgniter\Model;

class KnowledgeBaseFeedbackModel extends Model{


    protected $table = "knowledge_base_feedback";
    protected $primarykey = "id";
    protected $allowedFields = [
        "customer_id",
        "article_id",
        "user_type",
        "positive",
        "negative"
    ];

    protected $useTimestamps = false; // Enable automatic timestamps
    protected $dateFormat = "datetime";
    protected $createdField = "created_at";

    protected $db;

    protected $logger;
    public function __construct(){
        $this->db = \Config\Database::connect();
        $this->logger = \Config\Services::logger();
    }

    public function exist_entry($user_id,$article_id){
        $result = $this->builder()->where("customer_id",$user_id)->where("article_id",$article_id)->where("user_type","staff")->get()->getRow();
        if($result){
            return true; 
        }else{
            return false;
        }
    }

    public function store($user_id, $response_type, $article_id,$type){

        $is_exist =$this->exist_entry($user_id,$article_id);
        $message = [];
        if(!$is_exist){

            $data = [
                "customer_id" => $user_id,
                "user_type" => $type,
                "article_id" => $article_id,
                "positive" => ($response_type == "positive") ? 1 : 0,
                "negative" => ($response_type == "negative") ? 1 : 0
            ];

            if($this->builder()->insert($data)){
                return $message = ["success"=>true,"message" => "Thanks for Your Feedback"];
            }else{
                return $message = ["success"=>false,"message" => "error while storing feedback"];
            }
        }else{
            $data = [
                "positive" => ($response_type == "positive") ? 1 : 0,
                "negative" => ($response_type == "negative") ? 1 : 0
            ];

            if($this->builder()->where(["customer_id"=> $user_id,"user_type" => $type,"article_id"=>$article_id])->update($data)){
                return $message = ["success"=>true,"message" => "Your Feedback has been Updated!"];
            }else{
                return $message = ["success"=>false,"message" => "error while updating your feedback!"];
            }

        }
    }

    public function is_user_voted($user_id,$article_id,$user_type){
        $result = $this->builder()->where("customer_id",$user_id)->where("article_id",$article_id)->where("user_type",$user_type)->get()->getRow();
        
        if($result){
            return  true;
        }else{
            return false;
        }
    }

    // public function feed_back_update(){

    // }

    public function admin_kb_feedback(){
        return $this->builder()->select('article_id,SUM(positive) as positive,SUM(negative) as negative')->groupBy("article_id")->get()->getResultArray();
    }





}

?>