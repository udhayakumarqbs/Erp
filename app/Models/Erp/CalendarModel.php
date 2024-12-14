<?php 

namespace App\Models\Erp;

use CodeIgniter\Model;
use Config\Database;

class CalendarModel extends Model{
    protected $table = "calender_events";
    protected $primarykey = "event_id";
    protected $allowedFields = [
        "title",
        "description",
        "user_id",
        "Start_data",
        "end_data",
        "public_event",
        "is_start_notified",
        "reminder_before",
        "reminder_before_type",
        "event_color"
    ];
    protected $db;
    protected $logger;

    public function __construct(){
        $this->db = \Config\Database::connect();
        $this->logger = \Config\Services::logger();
    }

    public function calendar_events_add($data){
        if ($this->builder()->insert($data)){
            return true;
        }
        else{
            return false;
        }
    }
    public function update_event($data,$id){
        if($this->builder()->where("event_id",$id)->update($data)){
            return true;
        }
        else{
            return false;
        }
    }
    public function fetch_events(){
        $query = "SELECT title as title,Start_data as start,end_data as end,event_color as color,event_id as id FROM calender_events";
        return $this->db->query($query)->getResultArray();
    }
    //fetching data for update form pass
    public function fetch_exist_data($id){
        $result = $this->builder()->select()->where("event_id",$id)->get()->getRow();
        return $result; 
    }
    public function event_delete($id){
        if($this->builder()->from("calender_events")->where("event_id",$id)->delete()){
            return true;
        }
        else{
            return false;
        }
    }
}
?>