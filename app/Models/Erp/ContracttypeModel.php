<?php 
namespace App\Models\Erp;

use CodeIgniter\Model;

class ContracttypeModel extends Model{

    protected $db;
    protected $logger;
    protected $table = "contracttype";
    protected $primarykey = "cont_id";
    protected $allowedFields = [
    "cont_name",
    "created_at"
    ];  
    protected $useTimestamps = true; // Enable automatic timestamps

    // Specify the fields to be used for automatic timestamps
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';

    public function __construct()
    {
        $this->logger = \Config\Services::logger();
        $this->db = \Config\Database::connect();
    }
    public function get_fetch_coloum(){
        $config = array(
            "columnNames" => ["sno","Names","","","","","","Options"],
            "sortable" => [1,1,0,0,0,0,0,0]
        );

        return json_encode($config);
    }

    public function add_contract_type($data){
        if($this->builder()->insert($data)){
            return true;
        }
        else{
            return false;
        }
    }

    //fetch contract for datatable
    public function fetch_contract_data_table(){
        $limit = $_GET["limit"];
        $offset = $_GET["offset"];
        $search = $_GET['search'] ?? "";

        $query = "SELECT cont_id,cont_name FROM contracttype";
        $count = "SELECT COUNT(cont_name) AS Total FROM contracttype";

        if (!empty($search)) {
            $query .= " WHERE ( cont_name LIKE '%" . $search . "%') ";
            $count .= " WHERE ( cont_name LIKE '%" . $search . "%') ";
        }

        $query .= " ORDER BY cont_name DESC LIMIT $offset, $limit";

        $result = $this->db->query($query)->getResultArray();
        $a_result = array();

        $sno = $offset+1;

        foreach($result as $r){
            $action = "<div class='dropdown tableAction'>
                        <a type='button' class='dropBtn HoverA '><i class='fa fa-ellipsis-v'></i></a>
                        <div class='dropdown_container'>
                            <ul class='BB_UL flex justify-content-around'>
                                <li><a href='" . url_to("erp.contracttype.update") . "' title='Edit' class='updateit bg-success' onclick=update(".$r['cont_id'].",'".$r['cont_name']."') data-toggle='modal' data-target='#contracttype_modal' ><i class='fa fa-pencil'></i> </a></li>
                                <li><a href='" . url_to("erp.contracttype.delete",$r['cont_id']) ."' title='Delete' class='bg-danger del-confirm'><i class='fa fa-trash'></i> </a></li>
                            </ul>
                        </div>
                    </div>";
            array_push($a_result,array(
                '',
                $sno,
                $r["cont_name"],
                "",
                "",
                "",
                "",
                "",
                $action
            ));
            $sno++;
                
        };
        $response = array();
        $response["total_rows"] = $this->db->query($count)->getrow()->Total;
        $response["data"] =  $a_result;
        return $response;
    }
    public function get_contract_data($id,$data){
        if($this->builder()->where("cont_id",$id)->update($data)){
            return true;
        }
        else{
            return false;
        }
    }
    public function delete_contract_type($id){
        $query = "SELECT COUNT(c.contract_type) AS total FROM  erpcontract AS c WHERE c.contract_type = $id";

        $count = $this->db->query($query)->getrow()->total;

        if($count > 0){
            return false;
        }
        elseif($count<=0){
            if($this->builder()->from("contracttype")->where("cont_id",$id)->delete()){
                return true;
            }
            else{
                $data = "error";
                return $data;
            }
        }
        
    }
    public function fetch_contract_type(){
        return $this->builder()->select("cont_id,cont_name")->orderBy("created_at","Desc")->get()->getResultArray();
    }
    public function getContractTypeById($id){
        return $this->builder()->select()->where("cont_id ",$id)->get()->getRowArray();
    }
}

?>