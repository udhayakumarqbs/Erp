<?php 

namespace App\Models\Erp;

use CodeIgniter\Config\Config;
use CodeIgniter\Model;


class TicketcommentModel extends Model{


    protected $table = "ticket_comment";
    protected $primaryKey = "id";
    protected $allowedFields = [
        'ticket_id',
        'related_id',
        'related_type',
        'comment',
        'created_at'
    ];

    protected $useTimestamps = false;

    protected $db;
    protected $session;
    public function __construct(){
        $this->db = \Config\Database::connect();
        $this->session = \Config\Services::session();
        helper(["form,erp"]);
    }

    public function add_comment($data){
        if($this->builder()->insert($data)){
            return true;
        }else{
            return false;
        }
    }

    public function fetchallcomment($ticket_id,$contact_id,$type){
        $result = $this->builder()->where("ticket_id",$ticket_id)->orderBy("created_at","DESC")->get()->getResultArray();
        return $result;
    }
}

?>