<?php

namespace App\Controllers\Client;

use App\Controllers\BaseController;
use App\Models\client\TicketModel;
use App\Models\ERP\ProjectsModel;
use App\Models\Erp\TicketcommentModel;
use App\Models\Erp\TicketsModel;
use CodeIgniter\Config\Config;

class SupportController extends BaseController{

    protected $TicketModel;
    protected $TicketcommentModel;
    protected $ProjectsModel;
    protected $TicketsModel;
    protected $db;
    public function __construct(){
        $this->TicketModel = new TicketModel();
        $this->TicketcommentModel = new TicketcommentModel();
        $this->ProjectsModel = new ProjectsModel();
        $this->TicketsModel = new TicketsModel();
        $this->db = \Config\Database::connect();
        
        helper(['form','erp','Cookie']);
    }

    public function index(){
        $data["pageFolder"] = "support/index";
        $cont_id = session("contact_id");
        $data["ticketsummary"] = $this->TicketModel->get_count_by_status($cont_id);
        $data["tickets"] = $this->TicketModel->get_all_ticket($cont_id);
        return  view('client/index',$data);
    }

    public function ticketview($id){
        $data["pageFolder"] = "support/ticketview";
        $contact_id= session("contact_id");
        $data["ticketdetails"] = $this->TicketModel->getTicketDetailbyId($id,$contact_id);
        return view('client/index',$data);
    }

    public function addcomment(){
        $ticket_id = $_POST["ticketId"];
        $comment = $_POST["message"];
        $contact_id= session("contact_id");
        $data = [
            "ticket_id" => $ticket_id,
            "related_id" => $contact_id,
            "related_type" => "client",
            "comment" => $comment
        ];

        $result = $this->TicketcommentModel->add_comment($data);

        if($result){
            return $this->response->setJSON(["success"=>true,"message"=>"Comment send Successfully!"]);
        }else{
            return $this->response->setJSON(["success"=>false,"message"=>"Error while sending Comment!"]);
        }
    }

    public function getstaffnamebyid($id){
        $query = "SELECT name,last_name FROM erp_users WHERE user_id=$id";
        $result = $this->db->query($query)->getRowArray();
        return $result["name"]." ".$result["last_name"]; 
    }

    public function  fetchallcomment(){
        $ticket_id = $_POST["ticketId"];
        $contact_id= session("contact_id");

        $result = $this->TicketcommentModel->fetchallcomment($ticket_id,$contact_id,"client");

        $new_array = array();

        foreach($result as $value){
            if($value["related_type"] == "staff"){
                $value["related_id"] = $this->getstaffnamebyid($value["related_id"]);
            }
            array_push($new_array,$value);
        }

        return $this->response->setJSON(["success"=>true,"data"=> $new_array]);
    }

    public function ticketaddview(){
        $data["pageFolder"] = "support/ticketadd";
        $data["priority"] =  $this->TicketModel->get_ticket_priority();
        $data["statuss"] =  $this->TicketModel->get_ticket_status();
        $cust_id= session("client_cust_id");
        $data["projects"] = $this->ProjectsModel->getprojectbycustId($cust_id);
        return view('client/index',$data);
    }
    public function ticketadd(){
        $post = [
            'subject' => $this->request->getPost("subject"),
            'priority' => $this->request->getPost("priority"),
            'customer' => $this->request->getPost("contact_id"),
            'project_id' => $this->request->getPost("project"),
            'assigned_to' => $this->request->getPost("assigned_to") ?? 0,
            'problem' => $this->request->getPost("problem"),
            'status' => $this->request->getPost("status")
        ];

        if ($this->TicketsModel->insert_ticket($post)) {
            return $this->response->redirect(url_to("front.supports.view"));
        } else {
            return $this->response->redirect(url_to("fornt.ticket.add"));
        }

    }
}



?>