<?php

namespace App\Models\Erp;

use CodeIgniter\Model;

class KnowledgebaseModel extends Model
{
    protected $logger;
    protected $db;
    protected $table = "knowledgebase_groups";
    protected $primarykey = "group_id";
    protected $allowedFields = [
        'group_name',
        'short_description',
        'group_order',
        'disabled'
    ];

    public function __construct()
    {
        $this->db = \Config\Database::connect();
        $this->logger = \Config\Services::logger();
    }
    public function add_group($result)
    {
        return $this->insert($result);
    }
    //groups fetch
    public function group_fetch()
    {
        return $this->builder()
            ->select()
            ->where("disabled",0)
            ->orderBy("group_order", "Asce")
            ->get()
            ->getResultArray();
    }
    public function get_dtconfig_knowledgebase()
    {
        $config = array(
            "columnNames" => ["sno", "Name","Status", "action"],
            "sortable" => [0, 1,0, 0],
        );
        return json_encode($config);
    }
    public function get_ajax_knowledgebase()
    {
        $limit = $_GET["limit"];
        $offset = $_GET["offset"];
        $search = $_GET['search'] ?? "";

        $query = "SELECT group_id,group_name,disabled FROM knowledgebase_groups ";
        $count = "SELECT count(group_id) as count FROM knowledgebase_groups ";

        if (!empty($search)) {
            $query .= " WHERE ( group_name LIKE '%" . $search . "%') ";
            $count .= " WHERE ( group_name LIKE '%" . $search . "%') ";
        }

        $query .= " LIMIT $offset,$limit";
        $result = $this->db->query($query)->getResultArray();

        $a_result = array();

        $sno = $offset + 1;

        foreach ($result as $a) {
            $action = '<div class="dropdown tableAction">
            <a type="button" class="dropBtn HoverA "><i class="fa fa-ellipsis-v"></i></a>
            <div class="dropdown_container">
                <ul class="BB_UL flex justify-content-around">
                    <li><a href="' . url_to('erp.knowledgebaseupdate', $a["group_id"]) . '" title="Edit" class="bg-success"><i class="fa fa-pencil"></i> </a></li>
                    <li><a href="' . url_to('erp.knowledgebasedelete', $a["group_id"]) . '" title="Delete" class="bg-danger del-confirm"><i class="fa fa-trash"></i> </a></li>
                </ul>
            </div>
        </div>';
        $status ="";
        if(!$a['disabled']){
            $status = '<span class="sign-tr-value"> Active </span>';
        }else{
            $status = '<span class="sign-tr-null"> Disable </span>';
        }
            array_push($a_result, array(
                '',
                $sno,
                $a["group_name"],
                $status,
                $action
            ));
            $sno++;
        }
        // $this->logger->error($a_result);
        $result["total_rows"] =  $this->db->query($count)->getRow()->count;
        $result["data"] = $a_result;

        return $result;
    }
    public function get_exist_group($id)
    {
        return $this->builder()
            ->select()
            ->where("group_id", $id)
            ->get()
            ->getRow();
    }
    public function update_group($id, $data)
    {
        if (
            $this->builder()
                ->where("group_id", $id)
                ->update($data)
        ) {
            return true;
        } else {
            return false;
        }
    }

    public function delete_group($id)
    {

        $this->builder()
            ->from('knowledgebase_groups')
            ->where("group_id", $id)
            ->delete();

        if ($this->affectedRows() > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function export_groups($type)
    {
        $query = "";

        if ($type == "pdf") {
            $query = "SELECT group_name,short_description,group_order,disabled FROM knowledgebase_groups";
        } else {
            $query = "SELECT group_name,short_description,group_order,disabled FROM knowledgebase_groups";
        }

        $result = $this->db->query($query)->getResultArray();

        // foreach ( $result as &$row) {
        //     foreach ($row as $key => &$value) {
        //         if (is_array($value)) {
        //             $value = implode(', ', $value); // Convert arrays to comma-separated strings
        //         }
        //     }
        // }

        return $result;
    }

    public function get_groups_menu(){
        return $this->builder()->where("disabled",0)->get()->getResultArray();
    }
}
