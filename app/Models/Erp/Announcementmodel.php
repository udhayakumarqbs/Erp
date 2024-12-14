<?php
namespace App\Models\Erp;

use CodeIgniter\Model;
use DateTime;
class Announcementmodel extends Model{
    protected $db;
    protected $logger;
    public function __construct()
    {
        $this->db = \Config\Database::connect();
        $this->logger = \Config\Services::logger();
    }

    protected $table = 'announcements';
    protected $primarykey = "announcementid";
    protected $allowedFields =[
        'name',
        'message',
        'showtousers',
        'showtostaff',
        'showname',
        'userid'
    ];

    protected $useTimestamps = true; // Enable automatic timestamps

    // Specify the fields to be used for automatic timestamps
    protected $createdField  = 'dateadded';
    protected $updatedField ='updated_at';
    
    
    public function insert_announcement($post){

        return $this->insert($post);  
    }

    public function get_dtconfig_announcement()
    {
        $config = array(
            "columnNames" => ["sno", "subject", "date", "action"],
            "sortable" => [0, 1, 1,0],
        );
        return json_encode($config);
    }
    public function get_announcementData(){
        $limit = $_GET['limit'];
        $offset = $_GET['offset'];
        $search = $_GET['search'] ?? "";

        $query = "SELECT announcementid,name,dateadded FROM announcements ";
        $count = "SELECT count('announcementid') AS total FROM announcements ";

        if (!empty($search)) {
            $query .= " WHERE ( name LIKE '%" . $search . "%' OR dateadded LIKE '%" . $search . "%' ) ";
            $count .= " WHERE ( name LIKE '%" . $search . "%' OR dateadded LIKE '%" . $search . "%' ) ";
        }
        $query .= " LIMIT $offset,$limit";

        $result = $this->db->query($query)->getResultArray();
        // $this->logger->error($result);
        // $this->logger->error($query);    



        $a_result = array();
        $sno = $offset+1;

        foreach($result as $r){

            $action = '<div class="dropdown tableAction">
                        <a type="button" class="dropBtn HoverA "><i class="fa fa-ellipsis-v"></i></a>
                        <div class="dropdown_container">
                            <ul class="BB_UL flex justify-content-around">
                                <li><a href="' . url_to('erp.announcementsupdate',$r["announcementid"]) . '" title="Edit" class="bg-success"><i class="fa fa-pencil"></i> </a></li>
                                <li><a href="' . url_to('erp.announcementsdelete',$r["announcementid"]) . '" title="Delete" class="bg-danger del-confirm"><i class="fa fa-trash"></i> </a></li>
                            </ul>
                        </div>
                    </div>';
            array_push($a_result,array(
                '',
                $sno,
                $r["name"],
                $r["dateadded"],
                $action
            ));
            $sno++;
        };

        // $this->logger->error($a_result);

        $response = array();

        $response["total_rows"] = $this->db->query($count)->getrow()->total; 
        $response["data"] = $a_result;
        return $response;
    }

    public function delete_row($id){
        $query = "DELETE FROM announcements WHERE announcementid = $id";
        $result = $this->db->query($query);
        return $result;
    }

    public function getDataById($id){
        return $this->builder()->select()->where('announcementid', $id)->get()->getRow();
    }

    public function update_exist_data($data,$id){
        // return $this->builder()->update($data);


        if($this->builder()->where('announcementid',$id)->update($data)){
            

            return true;
        }
        else{
            return false;
        }


    }
    public function AnnouncementAjaxData(){
        
        return $this->builder()
        ->select()->orderBy('updated_at','desc')
        ->limit(5)->get()->getResultArray();
    }
    public function get_announcement_export($type)
    {
        $columns = array(
            "name" => "name",
            "message" => "message",
        );

        $orderby = isset($_GET['orderby']) ? strtoupper($_GET['orderby']) : "";
        $ordercol = isset($_GET['   ']) ? $columns[$_GET['ordercol']] : "";
        $query = "";

        if ($type == "pdf") {
            $query = "SELECT name,message,showtousers,showtostaff,showname,dateadded,updated_at,userid FROM announcements";
        } else {
            $query = "SELECT name,message,showtousers,showtostaff,showname,dateadded,updated_at,userid FROM announcements";
        }

        // if (!empty($ordercol) && !empty($orderby)) {
        //     $query .= " ORDER BY " . $ordercol . " " . $orderby;
        // }

        $result = $this->db->query($query)->getResultArray();
        return $result;
    }
}
?>