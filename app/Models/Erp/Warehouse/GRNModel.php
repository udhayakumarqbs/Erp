<?php

namespace App\Models\Erp\Warehouse;

use CodeIgniter\Model;

class GRNModel extends Model
{
    protected $table            = 'grn';
    protected $primaryKey       = 'grn_id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'order_id',
        'status',
        'remarks',
        'delivered_on',
        'updated_on',
        'updated_by',
    ];

    protected $db;
    public function __construct()
    {
        $this->db = \Config\Database::connect();
    }

    private $grn_status = array(
        0 => "Created",
        1 => "Completed",
        2 => "Ready to Stock",
        3 => "Stocked"
    );

    private $grn_status_bg = array(
        0 => "st_dark",
        1 => "st_primary",
        2 => "st_violet",
        3 => "st_success"
    );

    //Grn 
    public function get_grn_status()
    {
        return $this->grn_status;
    }

    public function get_grn_status_bg()
    {
        return $this->grn_status_bg;
    }

    public function get_product_types()
    {
        return $this->product_types;
    }

    public function get_product_links()
    {
        return $this->product_links;
    }



    public function get_grn_datatable()
    {
        $columns=array(
            "order code"=>"purchase_order.order_code",
            "warehouse"=>"warehouses.name",
            "status"=>"grn.status"
        );
        $limit=$_GET['limit'];
        $offset=$_GET['offset'];
        $search=$_GET['search']??"";
        $orderby=isset($_GET['orderby'])?strtoupper($_GET['orderby']):"";
        $ordercol=isset($_GET['ordercol'])?$columns[$_GET['ordercol']]:"";

        $query="SELECT grn_id,purchase_order.order_code,CONCAT(suppliers.name,' ',suppliers.code) AS supplier,warehouses.name As warehouse,grn.status FROM grn JOIN purchase_order ON grn.order_id=purchase_order.order_id JOIN warehouses ON purchase_order.warehouse_id=warehouses.warehouse_id JOIN suppliers ON purchase_order.supplier_id=suppliers.supplier_id ";
        $count="SELECT COUNT(grn_id) AS total FROM grn JOIN purchase_order ON grn.order_id=purchase_order.order_id JOIN warehouses ON purchase_order.warehouse_id=warehouses.warehouse_id JOIN suppliers ON purchase_order.supplier_id=suppliers.supplier_id ";
        $where=false;

        $filter_1_col=isset($_GET['status'])?$columns['status']:"";
        $filter_1_val=$_GET['status'];

        if(!empty($filter_1_col) && $filter_1_val!=="" ){
            $query.=" WHERE ".$filter_1_col."=".$filter_1_val." ";
            $count.=" WHERE ".$filter_1_col."=".$filter_1_val." ";
            $where=true;
        }
        if(!empty($search)){
            if(!$where){
                $query.=" WHERE ( suppliers.name LIKE '%".$search."%' OR warehouses.name LIKE '%".$search."%' OR purchase_order.order_code LIKE '%".$search."%' ) ";
                $count.=" WHERE ( suppliers.name LIKE '%".$search."%' OR warehouses.name LIKE '%".$search."%' OR purchase_order.order_code LIKE '%".$search."%' ) ";
                $where=true;
            }else{
                $query.=" AND ( suppliers.name LIKE '%".$search."%' OR warehouses.name LIKE '%".$search."%' OR purchase_order.order_code LIKE '%".$search."%' ) ";
                $count.=" AND ( suppliers.name LIKE '%".$search."%' OR warehouses.name LIKE '%".$search."%' OR purchase_order.order_code LIKE '%".$search."%' ) ";
            }
        }

        if(!empty($ordercol) && !empty($orderby)){
            $query.=" ORDER BY ".$ordercol." ".$orderby;
        }
        $query.=" LIMIT $offset,$limit ";
        $result=$this->db->query($query)->getResultArray();
        $m_result=array();
        $sno=$offset+1;
        foreach($result as $r){
            $action='<div class="dropdown tableAction">
                        <a type="button" class="dropBtn HoverA "><i class="fa fa-ellipsis-v"></i></a>
                        <div class="dropdown_container">
                            <ul class="BB_UL flex">
                                <li><a href="'. url_to('erp.warehouse.grnview',$r['grn_id']).'" title="View" class="bg-warning"><i class="fa fa-eye"></i> </a></li>
                                <li><a href="'. url_to('erp.warehouse.grnupdate',$r['grn_id']).'" title="Edit" class="bg-success"><i class="fa fa-pencil"></i> </a></li>
                            </ul>
                        </div>
                    </div>';
            $status='<span class="st '.$this->grn_status_bg[$r['status']].' " >'.$this->grn_status[$r['status']].'</span>';
            array_push(
                $m_result,
                array(
                    $r['grn_id'],
                    $sno,
                    $r['order_code'],
                    $r['supplier'],
                    $r['warehouse'],
                    $status,
                    $action
                )
            );
            $sno++;
        }
        $response=array();
        $response['total_rows']=$this->db->query($count)->getRow()->total;
        $response['data']=$m_result;
        return $response;
    }
    

    public function get_dtconfig_grns()
    {
        $config = array(
            "columnNames" => ["sno", "order code", "supplier", "warehouse", "status", "action"],
            "sortable" => [0, 1, 0, 1, 0, 0],
            "filters" => ["status"]
        );
        return json_encode($config);
    }

    public function GrnExport()
    {
        $columns = array(
            "order code" => "purchase_order.order_code",
            "warehouse" => "warehouses.name",
            "status" => "grn.status"
        );
        $limit = $_GET['limit'];
        $offset = $_GET['offset'];
        $search = $_GET['search'] ?? "";
        $orderby = isset($_GET['orderby']) ? strtoupper($_GET['orderby']) : "";
        $ordercol = isset($_GET['ordercol']) ? $columns[$_GET['ordercol']] : "";

        $query = "SELECT purchase_order.order_code,CONCAT(suppliers.name,' ',suppliers.code) AS supplier,warehouses.name As warehouse,
     CASE ";
        foreach ($this->grn_status as $key => $value) {
            $query .= " WHEN grn.status=$key THEN '" . $value . "' ";
        }
        $query .= " END AS status,grn.delivered_on,grn.remarks FROM grn JOIN purchase_order ON grn.order_id=purchase_order.order_id JOIN warehouses ON purchase_order.warehouse_id=warehouses.warehouse_id JOIN suppliers ON purchase_order.supplier_id=suppliers.supplier_id ";
        $where = false;

        $filter_1_col = isset($_GET['status']) ? $columns['status'] : "";
        $filter_1_val = $_GET['status'];

        if (!empty($filter_1_col) && $filter_1_val !== "") {
            $query .= " WHERE " . $filter_1_col . "=" . $filter_1_val . " ";
            $where = true;
        }
        if (!empty($search)) {
            if (!$where) {
                $query .= " WHERE ( suppliers.name LIKE '%" . $search . "%' OR warehouses.name LIKE '%" . $search . "%' OR purchase_order.order_code LIKE '%" . $search . "%' ) ";
                $where = true;
            } else {
                $query .= " AND ( suppliers.name LIKE '%" . $search . "%' OR warehouses.name LIKE '%" . $search . "%' OR purchase_order.order_code LIKE '%" . $search . "%' ) ";
            }
        }

        if (!empty($ordercol) && !empty($orderby)) {
            $query .= " ORDER BY " . $ordercol . " " . $orderby;
        }

        $result = $this->db->query($query)->getResultArray();
        return $result;
    }

    public function get_grn_for_view($grn_id)
    {
        $query = "SELECT grn_id,grn.order_id,purchase_order.order_code,CONCAT(suppliers.name,' ',suppliers.code) AS supplier,
         warehouses.name As warehouse,grn.status,grn.remarks,delivered_on
         FROM grn JOIN purchase_order ON grn.order_id=purchase_order.order_id 
         JOIN warehouses ON purchase_order.warehouse_id=warehouses.warehouse_id 
         JOIN suppliers ON purchase_order.supplier_id=suppliers.supplier_id WHERE grn_id=$grn_id ";

        $result = $this->db->query($query)->getResultArray();
        return $result;
    }
    public function GrnUpdate($grn_id,$delivered_on,$remarks)
    {
        // $delivered_on = $this->request->getpost("delivered_on");
        // $remarks = $this->request->getpost("remarks");
        $updated_by = get_user_id();
        $updated = false;
        $this->db->transBegin();
        $query = "UPDATE purchase_order_items SET received_qty=? , returned_qty=? WHERE order_item_id=? ";
        foreach ($_POST as $key => $value) {
            if (stripos($key, "recv_qty_") === 0) {
                $order_item_id = substr($key, strlen("recv_qty_"));
                $this->db->query($query, array($value, $_POST['ret_qty_' . $order_item_id], $order_item_id));
            }
        }
        $query = "UPDATE grn SET delivered_on=? , remarks=? , status=1 , updated_by=? , updated_on=? WHERE grn_id=$grn_id";
        $this->db->query($query, array($delivered_on, $remarks, $updated_by, date("Y-m-d H:i:s")));

        $this->db->transComplete();
        $config['title'] = "GRN Update";
        if ($this->db->transStatus() === FALSE) {
            $this->db->transRollback();
            $config['log_text'] = "[ GRN failed to update ]";
            $config['ref_link'] = 'erp/warehouse/grnview/' . $grn_id;
            session()->setFlashdata("op_error", "GRN failed to update");
        } else {
            $this->db->transCommit();
            $updated = true;
            $config['log_text'] = "[ GRN successfully updated ]";
            $config['ref_link'] = 'erp/warehouse/grnview/' . $grn_id;
            session()->setFlashdata("op_success", "GRN successfully updated");
        }
        log_activity($config);
        return $updated;
    }

    public function get_order_items($order_id)
    {
        $query = "SELECT COALESCE(CONCAT(raw_materials.name,' ',raw_materials.code),CONCAT(semi_finished.name,' ',semi_finished.code)) AS product,price_list.name,price_list.amount AS unit_price,CONCAT(t1.tax_name,' ',t1.percent,' , ',IFNULL(t2.tax_name,''),' ',IFNULL(t2.percent,'')) AS tax,quantity,received_qty,returned_qty FROM purchase_order_items LEFT JOIN raw_materials ON related_id=raw_materials.raw_material_id AND related_to='raw_material' LEFT JOIN semi_finished ON related_id=semi_finished.semi_finished_id AND related_to='semi_finished' JOIN price_list ON purchase_order_items.price_id=price_list.price_id LEFT JOIN taxes t1 ON price_list.tax1=t1.tax_id LEFT JOIN taxes t2 ON price_list.tax2=t2.tax_id WHERE order_id=$order_id";
        $result = $this->db->query($query)->getResultArray();
        return $result;
    }
    public function get_order_items_for_update($grn_id)
    {
        $query = "SELECT order_item_id,COALESCE(CONCAT(raw_materials.name,' ',raw_materials.code),CONCAT(semi_finished.name,' ',semi_finished.code)) AS product,quantity FROM grn JOIN purchase_order_items ON grn.order_id=purchase_order_items.order_id LEFT JOIN raw_materials ON related_id=raw_materials.raw_material_id AND related_to='raw_material' LEFT JOIN semi_finished ON related_id=semi_finished.semi_finished_id AND related_to='semi_finished' WHERE grn_id=$grn_id ";
        $result = $this->db->query($query)->getResultArray();
        return $result;
    }
    public function get_grn_items_for_pack($grn_id)
    {
        $query = "SELECT received_qty,COALESCE(CONCAT(raw_materials.name,' ',raw_materials.code),CONCAT(semi_finished.name,' ',semi_finished.code)) AS product,GROUP_CONCAT(packs.pack_id SEPARATOR ',') AS pack_ids, GROUP_CONCAT(packs.name SEPARATOR ',') AS pack_names FROM grn JOIN purchase_order_items ON grn.order_id=purchase_order_items.order_id LEFT JOIN raw_materials ON purchase_order_items.related_id=raw_materials.raw_material_id AND purchase_order_items.related_to='raw_material' LEFT JOIN semi_finished ON purchase_order_items.related_id=semi_finished.semi_finished_id AND purchase_order_items.related_to='semi_finished' LEFT JOIN packs ON purchase_order_items.related_id=packs.related_id AND purchase_order_items.related_to=packs.related_to WHERE grn_id=$grn_id GROUP BY purchase_order_items.order_item_id";
        $result = $this->db->query($query)->getResultArray();
        return $result;
    }
    public function get_dtconfig_packs()
    {
        $config = array(
            "columnNames" => ["sno", "name", "capacity", "product type", "product", "description", "action"],
            "sortable" => [0, 1, 1, 0, 0, 0, 0]
        );
        return json_encode($config);
    }

    public function get_packs_datatable()
    {
        $columns = array(
            "name" => "name",
            "capacity" => "capacity"
        );
        $limit = $_GET['limit'];
        $offset = $_GET['offset'];
        $search = $_GET['search'] ?? "";
        $orderby = isset($_GET['orderby']) ? strtoupper($_GET['orderby']) : "";
        $ordercol = isset($_GET['ordercol']) ? $columns[$_GET['ordercol']] : "";

        $query = "SELECT packs.name,capacity,packs.description,
         CASE ";
        foreach ($this->product_types as $key => $value) {
            $query .= " WHEN packs.related_to='" . $key . "' THEN '" . $value . "' ";
        }
        $query .= " END AS related_to,COALESCE(CONCAT(raw_materials.name,' ',raw_materials.code),CONCAT(semi_finished.name,' ',semi_finished.code),CONCAT(finished_goods.name,' ',finished_goods.code)) AS product,width,height FROM packs LEFT JOIN raw_materials ON packs.related_id=raw_materials.raw_material_id AND packs.related_to='raw_material' LEFT JOIN semi_finished ON packs.related_id=semi_finished.semi_finished_id AND packs.related_to='semi_finished' LEFT JOIN finished_goods ON packs.related_id=finished_good_id AND packs.related_to='finished_good' ";
        $where = false;

        if (!empty($search)) {
            if (!$where) {
                $query .= " WHERE ( packs.name LIKE '%" . $search . "%' OR raw_materials.name LIKE '%" . $search . "%' OR semi_finished.name LIKE '%" . $search . "%' OR finished_goods.name LIKE '%" . $search . "%' ) ";
                $where = true;
            } else {
                $query .= " AND ( packs.name LIKE '%" . $search . "%' OR raw_materials.name LIKE '%" . $search . "%' OR semi_finished.name LIKE '%" . $search . "%' OR finished_goods.name LIKE '%" . $search . "%' ) ";
            }
        }

        if (!empty($ordercol) && !empty($orderby)) {
            $query .= " ORDER BY " . $ordercol . " " . $orderby;
        }
        $result = $this->db->query($query)->getResultArray();
        return $result;
    }
    //  public function insert_pack(){

    //      $query="INSERT INTO packs(name,capacity,width,height,description,related_to,related_id,created_at,created_by) VALUES(?,?,?,?,?,?,?,?,?) ";
    //      $this->db->query($query,array($pack_name,$pack_capacity,$pack_width,$pack_height,$pack_desc,$related_to,$related_id,time(),$created_by));

    //      $config['title']="Pack Insert";
    //      if(!empty($this->db->insertID())){
    //          $config['log_text']="[ Pack successfully created ]";
    //          $config['ref_link']='erp/warehouse/packs';
    //          $this->session->set_flashdata("op_success","Pack successfully created");
    //      }else{
    //          $config['log_text']="[ Pack failed to create ]";
    //          $config['ref_link']='erp/warehouse/packs';
    //          $this->session()->setFlashdata("op_error","Pack failed to create");
    //      }
    //      log_activity($config);
    //  }














}
