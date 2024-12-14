<?php
namespace App\Models\Erp\MRP;

use CodeIgniter\Model;


class WorkstationtypeModel extends Model
{
    protected $table = 'workstationtype';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'name',
        'electricity_cost',
        'rent_cost',
        'consumable_cost',
        'wages_cost',
        'created_at',
        'updated_at'
    ];

    protected $useTimestamps = false;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    protected $db;
    public $session;
    public $logger;


    public function __construct()
    {
        $this->db = \Config\Database::connect();
        $this->session = \Config\Services::session();
        $this->logger = \Config\Services::logger();
    }

    public function get_dtconfig_planning()
    {
        $config = array(
            "columnNames" => ["sno", "Name", "Electricity Cost", "Rent cost", "Consumable cost", "Wages Cost", "Created at", "active"],
            "sortable" => [1, 1, 0, 0, 0, 0, 0, 0],
            "filters" => ["status"]
        );
        return json_encode($config);
    }

    public function datatable()
    {
        $limit = $_GET['limit'];
        $offset = $_GET['offset'];
        $search = $_GET['search'] ?? "";
        $orderby = isset($_GET['orderby']) ? strtoupper($_GET['orderby']) : "";
        // $ordercol = isset($_GET['ordercol']) ? $columns[$_GET['ordercol']] : "";
        $where = true;
        $query = "SELECT * FROM workstationtype ";
        $count = "SELECT COUNT(*) as total FROM workstationtype ";

        if (!empty($search)) {
            if (!$where) {
                $query .= " WHERE Name  LIKE '%" . $search . "%' ";
                $count .= " WHERE Name LIKE '%" . $search . "%' ";
                $where = true;
            } else {
                $query .= " WHERE Name  LIKE '%" . $search . "%' ";
                $count .= " WHERE Name LIKE '%" . $search . "%' ";
            }
        }

        $query .= "ORDER BY created_at DESC LIMIT $offset,$limit ";

        $result = $this->db->query($query)->getResultArray();
        $m_result = array();
        $sno = 1;

        foreach ($result as $r) {

            $action = '<div class="dropdown tableAction">
            <a type="button" class="dropBtn HoverA "><i class="fa fa-ellipsis-v"></i></a>
            <div class="dropdown_container">
                <ul class="BB_UL flex">';

            $action .= '<li><a href="javascript:void(0)" title="Edit" onclick="edit_model('.$r['id'].')" class="bg-success "><i class="fa fa-pencil"></i></a></li>';

            $action .= '<li><a href="'.url_to("erp.mrp.workstationtype.delete",$r['id']).'" title="Delete" class="bg-danger del-confirm"><i class="fa fa-trash"></i> </a></li>
                </ul>
            </div>
            </div>';

            array_push(
                $m_result,
                array(
                    "",
                    $sno,
                    $r['name'],
                    $r['electricity_cost'],
                    $r['rent_cost'],
                    $r['consumable_cost'],
                    $r['wages_cost'],
                    $r['created_at'],
                    $action,
                )
            );
            $sno++;
        }
        $response = array();
        $response['total_rows'] = $this->db->query($count)->getRow()->total;
        $response['data'] = $m_result;
        return $response;
    }

    public function get_workstation_by_id($id){
        return $this->builder()->where("id",$id)->get()->getRowArray();
    }

    public function add($data)
    {
        if ($this->builder()->insert($data)) {
            return true;
        } else {
            return false;
        }
    }

    public function  edit($id,$data){
        if($this->builder()->update($data,["id"=>$id])){
            return true;
        }else{
            return false;
        }
    }

    public function remove($id){
        if($this->builder()->delete(["id" => $id])){
            return true;
        }else{
            return false;
        }
    }

    public function get_all_workstation(){
        return $this->builder()->get()->getResultArray();
    }

    public function get_workstation_type_details_by_id($id){
        return $this->builder()->where("id",$id)->get()->getRowArray();
    }

    
}
?>