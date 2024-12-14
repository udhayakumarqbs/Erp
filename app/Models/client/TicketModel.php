<?php

namespace App\Models\client;

use CodeIgniter\Config\Config;
use CodeIgniter\Model;

class TicketModel extends Model
{
    protected $table = "tickets";
    protected $primaryKey = "ticket_id";
    protected $allowedFields = [
        'subject',
        'priority',
        'cust_id',
        'project_id',
        'assigned_to',
        'problem',
        'status',
        'remarks',
        'created_at',
        'created_by'
    ];
    protected $useTimestamps = false;
    protected $db;
    protected $session;

    private $ticket_status = array(
        0 => "OPEN",
        1 => "IN PROGRESS",
        2 => "SOLVED",
        3 => "CLOSED"
    );
    private $ticket_priority = array(
        0 => "LOW",
        1 => "MEDIUM",
        2 => "HIGH",
        3 => "URGENT"
    );

    private $ticket_status_bg = array(
        0 => "st_violet",
        1 => "st_primary",
        2 => "st_success",
        3 => "st_danger"
    );

    private $ticket_priority_bg = array(
        0 => "st_dark",
        1 => "st_primary",
        2 => "st_violet",
        3 => "st_danger"
    );

    public function get_ticket_priority_bg()
    {
        return $this->ticket_priority_bg;
    }

    public function get_ticket_status_bg()
    {
        return $this->ticket_status_bg;
    }
    public function get_ticket_priority()
    {
        return $this->ticket_priority;
    }
    public function get_ticket_status()
    {
        return $this->ticket_status;
    }

    public function __construct()
    {
        $this->db = \Config\Database::connect();
        $this->session = \Config\Services::session();
    }

    public function get_count_by_status($contact_id)
    {
        $result = $this->builder()->select("status,COUNT(status) as count")->where("cust_id", $contact_id)->groupBy("status")->get()->getResultArray();
        $final_result = array();
        foreach ($result as $key) {
            foreach ($this->ticket_status as $k => $v) {
                // $final_result = [];
                if ($key["status"] == $k) {
                    // $res = $v;
                    $final_result[$v] = $key["count"];
                    // return;
                }
            }
            // array_push($final_result,$key);
            }
        // var_dump($final_result);
        // exit;
        return $final_result;
    }

    public function get_all_ticket($cont_id): array{
        $query = "SELECT ticket_id,subject,priority,CONCAT(customer_contacts.firstname,' ',customer_contacts.lastname) AS customer,projects.name as project_name,erp_users.name AS assigned,tickets.status,FROM_UNIXTIME(tickets.created_at,'%Y-%m-%d') AS created_at FROM tickets JOIN customer_contacts ON tickets.cust_id=customer_contacts.contact_id LEFT JOIN projects ON projects.project_id = tickets.project_id LEFT JOIN erp_users ON tickets.assigned_to=erp_users.user_id where tickets.cust_id = $cont_id ORDER BY tickets.created_at DESC ";

        // $result = $this->builder()->where("cust_id", $cont_id)->get()->getResultArray();
        $result = $this->db->query($query)->getResultArray();

        $m_result =[];

        foreach($result as $r){
            $subject = "<a class='text-primary' href='".url_to('front.ticket.detail.view',$r['ticket_id'])."' >".$r["subject"]."</a>";
            $priority = "<span class='st " . $this->ticket_priority_bg[$r['priority']] . "'>" . $this->ticket_priority[$r['priority']] . " </span>";
            $status = "<span class='st " . $this->ticket_status_bg[$r['status']] . "'>" . $this->ticket_status[$r['status']] . " </span>";
            
            array_push($m_result,array(
                "subject"=>$subject,
                "priority"=>$priority,
                "cust_id"=>$r['customer'],
                "project_id"=>$r['project_name'] ?? '-',
                "status"=>$status,
                "created_at"=> $r["created_at"]
            ));
        }

        return $m_result;
    }

    public function getTicketDetailbyId($id,$contact_id){

        $query = "SELECT ticket_id,subject,priority,CONCAT(customer_contacts.firstname,' ',customer_contacts.lastname) AS customer,projects.name as project_name,erp_users.name AS assigned,tickets.status,FROM_UNIXTIME(tickets.created_at,'%Y-%m-%d') AS created_at FROM tickets JOIN customer_contacts ON tickets.cust_id=customer_contacts.contact_id LEFT JOIN projects ON projects.project_id = tickets.project_id LEFT JOIN erp_users ON tickets.assigned_to=erp_users.user_id where tickets.cust_id = $contact_id AND tickets.ticket_id = $id";
        
        $result = $this->db->query($query)->getResult();
        $m_array = array();
        foreach($result as $k => $v){
            $priority = "<span class='st " . $this->ticket_priority_bg[$v->priority] . "'>" . $this->ticket_priority[$v->priority] . " </span>";
            $status = "<span class='st " . $this->ticket_status_bg[$v->status] . "'>" . $this->ticket_status[$v->status] . " </span>";
            array_push($m_array,array(
                "ticket_id"=> $v->ticket_id,
                "subject" => $v->subject,
                "created_at" => $v->created_at,
                "project_name" => $v->project_name ?? "",
                "priority" => $priority,
                "status" => $status,
            ));
        }
        
        return $m_array[0];
    } 

}


?>