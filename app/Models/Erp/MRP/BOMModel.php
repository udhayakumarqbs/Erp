<?php

namespace App\Models\Erp\MRP;

// use CodeIgniter\Config\Config;
use CodeIgniter\Model;
use App\Models\ERP\MRP\PlanningModel;
use App\Models\ERP\MRP\BOMItemsModel;
use App\Models\ERP\MRP\BomscrapitemsModel;
use App\Models\ERP\MRP\BomOperationModel;
use Config\Database;
use CodeIgniter\Database\Exceptions\DatabaseException;

class BOMModel extends Model
{
    protected $table = 'bom';
    protected $primaryKey = 'bom_id';

    protected $protectFields = true;

    protected $allowedFields = ['related_id', 'product_id', 'skucode', 'amount', 'quantity', 'date', 'loss_percentage', 'loss_qty', 'created_at', 'warhouse_id', 'status', 'operation_unit_cost', 'updated_at'];

    protected $useTimestamps = false;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    protected $db;
    protected $logger;
    protected $PlanningModel;
    protected $BOMItemsModel;
    protected $BomscrapitemsModel;
    protected $BomOperationModel;

    public function __construct()
    {
        $this->db = \Config\Database::connect();
        $this->logger = \Config\Services::logger();
        $this->session = \Config\Services::session();
        $this->PlanningModel = new PlanningModel();
        $this->BOMItemsModel = new BOMItemsModel();
        $this->BomscrapitemsModel = new BomscrapitemsModel();
        $this->BomOperationModel = new BomOperationModel();
    }


    public function get_dtconfig_bom()
    {
        $config = array(
            "columnNames" => ["Sno", "Planning", "SKU Code", "Warehouse", "BOM Date", "Amount", "Quantity", "status", "Action"],
            "sortable" => [1, 0, 1, 0, 0, 0, 0, 1, 0],
            "filters" => ["status"]
        );
        return json_encode($config);
    }
    public function get_dtconfig_overall_bom()
    {
        $config = array(
            "columnNames" => ["Sno", "Product", "SKU Code", "Warehouse", "BOM Date", "Amount", "Quantity", "status", "Action"],
            "sortable" => [1, 1, 1, 0, 1, 0, 0, 1, 0],
            "filters" => ["status"]
        );
        return json_encode($config);
    }

    public function get_product_links()
    {
        return $this->product_links;
    }

    public function get_product_types()
    {
        return $this->product_types;
    }


    public function get_all_warehouses()
    {
        $query = "SELECT warehouse_id,name FROM warehouses";
        $result = $this->db->query($query)->getResultArray();
        return $result;
    }

    public function getStock($planning_id)
    {
        $query = "SELECT stock FROM planning WHERE planning_id=$planning_id";
        return $result = $this->db->query($query)->getResultArray();
    }

    public function getFinishedGoodProduct($planning_id)
    {
        $query = "SELECT planning.finished_good_id,CONCAT(finished_goods.name,'-',finished_goods.code)as product_name FROM planning JOIN finished_goods ON planning.finished_good_id=finished_goods.finished_good_id WHERE  planning_id=$planning_id";
        return $result = $this->db->query($query)->getResultArray();
    }


    public function insert_mrp_scheduling($planning_id)
    {
        $inserted = false;
        $code = $_POST["sku"];
        $product_id = $_POST["item_id"];
        $related_id = $planning_id;
        $quantity = $_POST["quantity"];
        $warehouse_id = $_POST["warehouse_id"];
        $date = $_POST["mfg_date"];
        $status = $_POST["status"];
        $loss_percentage = $_POST["process_loss"];
        $loss_qty = $_POST["process_loss_qty"];
        $ammt = $_POST["total_amount"];
        $operation_per_unit_cost = 0;
        if ($_POST['operation_type'] == 'operation_cost_area' && !empty($_POST["operation_per_unit_cost"])) {
            $operation_per_unit_cost = $_POST["operation_per_unit_cost"];
        }

        $this->db->transBegin();

        if (!empty($quantity)) {
            $query1 = "INSERT INTO bom(skucode, related_id,product_id,quantity,amount,warehouse_id,date,status,loss_percentage,loss_qty,operation_per_unit_cost) VALUES (?,? ,?,?, ?,?, ?,?,?,?,?)";
            if ($this->db->query($query1, array($code, $related_id, $product_id, $quantity, $ammt, $warehouse_id, $date, $status, $loss_percentage, $loss_qty, $operation_per_unit_cost))) {
                $inserted_id = $this->db->insertID();
                $inserted = true;
            }

        }

        $this->db->transCommit();

        $array = array();
        $data = $_POST["product"];
        foreach ($data as $k => $v) {
            array_push($array, array(
                "product_id" => $v["product_id"],
                "product_type" => $v["product_type"],
                "price" => $v["price"],
                "quantity" => $v["quantity"],
                "amount" => $v["amount"],
            ));
        }


        if ($inserted) {
            $this->db->transBegin();
            try {
                foreach ($array as $key) {
                    $result = true;

                    $d = [
                        "product_type" => $key["product_type"],
                        "item_id" => $key["product_id"],
                        "quantity" => $key["quantity"],
                        "unit_price" => $key["price"],
                        "bom_id" => $inserted_id,
                        "amount" => $key["amount"],
                    ];

                    $ins = $this->BOMItemsModel->insert($d);

                    if (!$ins) {

                        $result = false;
                        throw new DatabaseException('Failed to insert row: ' . json_encode($ins));
                    }

                    if (!$result) {
                        $inserted = false;
                        return;
                    }
                }
                $this->db->transCommit();
            } catch (DatabaseException $e) {
                $this->db->transRollback(); // Rollback the transaction
                $inserted = false;
            }
        }

        $this->db->transCommit();

        //scrap
        if (isset($_POST["scrap"])) {

            $array_scrap = array();
            $data_scrap = $_POST["scrap"];
            foreach ($data_scrap as $k => $v) {
                array_push($array_scrap, array(
                    "product_id" => $v["scrap_id"],
                    "product_type" => $v["scrap_type"],
                    "price" => $v["price"],
                    "quantity" => $v["quantity"],
                    "amount" => $v["amount"],
                ));
            }

            if ($inserted) {
                $this->db->transBegin();
                try {
                    foreach ($array_scrap as $key) {
                        $result = true;

                        $d = [
                            "product_type" => $key["product_type"],
                            "item_id" => $key["product_id"],
                            "quantity" => $key["quantity"],
                            "unit_price" => $key["price"],
                            "bom_id" => $inserted_id,
                            "amount" => $key["amount"],
                        ];


                        $ins = $this->BomscrapitemsModel->insert($d);



                        if (!$ins) {

                            $result = false;
                            throw new DatabaseException('Failed to insert row: ' . json_encode($ins));
                        }

                        if (!$result) {

                            $inserted = false;
                            return;
                        }
                    }
                    $this->db->transCommit();
                } catch (DatabaseException $e) {

                    $this->db->transRollback(); // Rollback the transaction
                    $inserted = false;
                }
            }
        }


        //opration
        $this->db->transCommit();
        if ($_POST['operation_type'] == 'operation_adding_area') {

            if (isset($_POST["operation_row"])) {

                $array_op = array();
                $data_operation = $_POST["operation_row"];
                foreach ($data_operation as $k => $v) {
                    array_push($array_op, array(
                        "operation_id" => $v["op_id"],
                        "per_hour_cost" => $v["total_cost"],
                        "operating_hours" => $v["operating_hours"],
                        "amount" => $v["total_amount"],

                    ));
                }


                if ($inserted) {
                    $this->db->transBegin();
                    try {
                        foreach ($array_op as $key) {
                            $result = true;

                            $d_op = [
                                "operation_id" => $key["operation_id"],
                                "per_hour_cost" => $key["per_hour_cost"],
                                "opertaion_time" => $key["operating_hours"],
                                "amount" => $key["amount"],
                                "bom_id" => $inserted_id
                            ];


                            $ins_op = $this->BomOperationModel->insert($d_op);



                            if (!$ins_op) {

                                $result = false;
                                throw new DatabaseException('Failed to insert operation: ' . json_encode($ins_op));
                            }

                            if (!$result) {

                                $inserted = false;
                                return;
                            }
                        }
                        $this->db->transCommit();
                    } catch (DatabaseException $e) {

                        $this->db->transRollback(); // Rollback the transaction
                        $inserted = false;
                    }
                }
            }

        }

        if ($inserted) {
            if ($status == 2) {
                $related_to = "finished_good";
                $product_id_copy = $product_id;
                $warehouse_id_copy = $warehouse_id;
                $quantity_copy = $quantity;
                $price_id = $this->PlanningModel->where("planning_id", $planning_id)->get()->getRowArray();

                $query3 = "INSERT INTO stocks(related_to,related_id,warehouse_id,quantity,price_id) VALUE (?,?,?,?,?)";
                $resul = $this->db->query($query3, array($related_to, $product_id_copy, $warehouse_id_copy, $quantity_copy, $price_id["price_id"]));


                if (!$resul) {
                    $this->session->setFlashdata("op_success", "Bom is Created But error while creating stock");
                }

            }
        }
        $config['title'] = "MRP Scheduling Insert";
        $config['log_text'] = $inserted ? "[BOM successfully created ]" : "[ BOM failed to create ]";
        $config['ref_link'] = $inserted ? url_to('erp.mrp.planningview', $planning_id) : url_to('erp.mrp.planningschedule');

        log_activity($config);

        return $inserted;
    }

    public function get_completed_bom_stock($planning_id)
    {
        $result = $this->builder()->where("related_id", $planning_id)->where("status", 2)->get()->getResultArray();
        $stock = 0;
        foreach ($result as $r) {
            $stock += $r["quantity"];
        }

        return $stock;
    }

    public function is_existitems($product_id, $bom_id, $type)
    {
        $result = $this->BOMItemsModel->select("id,product_type,item_id,bom_id")->where("item_id", $product_id)->where("bom_id", $bom_id)->where("product_type", $type)->get()->getRowArray();
        return $result;
    }
    public function is_scrap_existitems($product_id, $bom_id, $type)
    {
        $result = $this->BomscrapitemsModel->select("id,product_type,item_id,bom_id")->where("item_id", $product_id)->where("bom_id", $bom_id)->where("product_type", $type)->get()->getRowArray();
        return $result;
    }
    public function is_operation_exist_list($op_id, $bom_id)
    {
        $result = $this->BomOperationModel->select("id,bom_id,operation_id")->where("operation_id", $op_id)->where("bom_id", $bom_id)->get()->getRowArray();
        return $result;
    }
    public function update_mrp_scheduling($bom_id, $planning_id)
    {
        $update = true;
        $code = $_POST["sku"];
        $product_id = $_POST["item_id"];
        $related_id = $planning_id;
        $quantity = $_POST["quantity"];
        $warehouse_id = $_POST["warehouse_id"];
        $date = $_POST["mfg_date"];
        $status = $_POST["status"];
        $loss_percentage = $_POST["process_loss"];
        $loss_qty = $_POST["process_loss_qty"];
        $ammt = $_POST["total_amount_scrap"];

        $this->db->transBegin();

        $operation_per_unit_cost = 0;
        if ($_POST["operation_type"] == 'operation_cost_area' && !empty($_POST["operation_per_unit_cost"])) {
            $operation_per_unit_cost = $_POST["operation_per_unit_cost"];
        }
        if($_POST["operation_type"] != 'operation_adding_area'){
            if(!$this->delete_operation_from_bom($bom_id)){
                $update = false;
            }
        }


        if (!empty($quantity) && $update) {
            $query1 = "UPDATE bom SET skucode = ?, related_id = ?,product_id = ?,quantity = ?,warehouse_id = ?,amount = ?,date =?,status = ?,loss_percentage = ?,loss_qty = ?, operation_per_unit_cost = ? WHERE bom_id = ? AND related_id = ? ";
            if ($this->db->query($query1, array($code, $related_id, $product_id, $quantity, $warehouse_id, $ammt, $date, $status, $loss_percentage, $loss_qty, $operation_per_unit_cost, $bom_id, $planning_id))) {
                $inserted_id = $bom_id;
                $update = true;
            }

            $query2 = "UPDATE planning SET stock=? WHERE planning_id=?";
            $this->db->query($query2, array($quantity, $planning_id));

        }

        $this->db->transCommit();

        $array = array();
        $data = $_POST["product"];

        foreach ($data as $k => $v) {
            array_push($array, array(
                "product_id" => $v["product_id"],
                "product_type" => $v["product_type"],
                "price" => $v["price"],
                "quantity" => $v["quantity"],
                "amount" => $v["amount"],
            ));
        }

        if ($update) {
            $this->db->transBegin(); // Start transaction
            $id_array = array();
            try {
                foreach ($array as $key) {
                    $res = $this->is_existitems($key["product_id"], $inserted_id, $key["product_type"]);

                    $result = true;
                    if (empty($res)) {
                        $d = [
                            "product_type" => $key["product_type"],
                            "item_id" => $key["product_id"],
                            "quantity" => $key["quantity"],
                            "unit_price" => $key["price"],
                            "bom_id" => $inserted_id,
                            "amount" => $key["amount"],
                        ];
                        $this->logger->info($d);
                        $ins = $this->BOMItemsModel->insert($d);

                        if (!$ins) {
                            $result = false;
                            throw new DatabaseException('Failed to insert row: ' . json_encode($ins));
                        }

                        array_push($id_array, $this->db->insertID());
                    } else {

                        $d = [
                            "quantity" => $key["quantity"],
                            "unit_price" => $key["price"],
                            "amount" => $key["amount"],
                        ];

                        $upd = $this->BOMItemsModel->update($res["id"], $d);

                        if (!$upd) {
                            $result = false;
                            throw new DatabaseException('Failed to insert row: ' . json_encode($ins));
                        }

                        array_push($id_array, $res["id"]);
                    }

                    if (!$result) {
                        $update = false;
                        return;
                    }
                }


                $get_old_items = $this->BOMItemsModel->where("bom_id", $bom_id)->get()->getResultArray();
                $old_items_ids = array_column($get_old_items, 'id');
                $old_items_ids = array_diff($old_items_ids, $id_array);
                $dele_result = true;

                foreach ($old_items_ids as $i) {
                    $dele_result = $this->exist_data_delete($i);
                }

                if (!$dele_result) {
                    $update = false;
                }

                $this->db->transCommit(); // Commit the transaction
            } catch (DatabaseException $e) {
                $this->db->transRollback(); // Rollback the transaction
                $update = false;
            }

        }

        //scrap
        if (isset($_POST["scrap"]) && $update) {

            $array_scrap = array();
            $data_scrap = $_POST["scrap"];

            foreach ($data_scrap as $k => $v) {
                array_push($array_scrap, array(
                    "product_id" => $v["scrap_id"],
                    "product_type" => $v["scrap_type"],
                    "price" => $v["price"],
                    "quantity" => $v["quantity"],
                    "amount" => $v["amount"],
                ));
            }

            if ($update) {
                $this->db->transBegin(); // Start transaction
                $id_array_scrap = array();
                try {
                    foreach ($array_scrap as $key) {
                        $res1 = $this->is_scrap_existitems($key["product_id"], $inserted_id, $key["product_type"]);

                        $result1 = true;
                        if (empty($res1)) {
                            $d_scrap = [
                                "product_type" => $key["product_type"],
                                "item_id" => $key["product_id"],
                                "quantity" => $key["quantity"],
                                "unit_price" => $key["price"],
                                "bom_id" => $inserted_id,
                                "amount" => $key["amount"],
                            ];

                            $ins1 = $this->BomscrapitemsModel->insert($d_scrap);

                            if (!$ins1) {
                                $result1 = false;
                                throw new DatabaseException('Failed to insert scrap row: ' . json_encode($ins1));
                            }

                            array_push($id_array_scrap, $this->db->insertID());
                        } else {

                            $d_scrap = [
                                "quantity" => $key["quantity"],
                                "unit_price" => $key["price"],
                                "amount" => $key["amount"],
                            ];

                            $upd1 = $this->BomscrapitemsModel->update($res1["id"], $d_scrap);

                            if (!$upd1) {
                                $result1 = false;
                                throw new DatabaseException('Failed to insert row: ' . json_encode($ins1));
                            }

                            array_push($id_array_scrap, $res1["id"]);
                        }

                        if (!$result1) {
                            $update = false;
                            return;
                        }
                    }

                    $get_old_items1 = $this->BomscrapitemsModel->where("bom_id", $bom_id)->get()->getResultArray();
                    $old_items_ids1 = array_column($get_old_items1, 'id');
                    $old_items_ids1 = array_diff($old_items_ids1, $id_array_scrap);
                    $dele_result1 = true;

                    foreach ($old_items_ids1 as $i) {
                        $dele_result1 = $this->exist_scrap_data_delete($i);
                    }

                    if (!$dele_result1) {
                        $update = false;
                    }

                    $this->db->transCommit();
                } catch (DatabaseException $e) {
                    $this->db->transRollback();
                    $update = false;
                }

            }
        }

        //operation
        if ($_POST["operation_type"] == 'operation_adding_area') {
            $array_op = array();
            $data_op = $_POST["operation_row"];
        }

        if (isset($data_op) && $update) {

            foreach ($data_op as $k => $v) {
                array_push($array_op, array(
                    "op_id" => $v["op_id"],
                    "workstation_name" => $v["workstation_name"],
                    "total_cost" => $v["total_cost"],
                    "operating_hours" => $v["operating_hours"],
                    "total_amount" => $v["total_amount"],
                ));
            }


            if ($update) {
                $this->db->transBegin();
                $id_array_op = array();
                try {
                    foreach ($array_op as $key) {
                        $res2 = $this->is_operation_exist_list($key["op_id"], $inserted_id);

                        $result2 = true;
                        if (empty($res2)) {
                            $d_op = [
                                "operation_id" => $key["op_id"],
                                "per_hour_cost" => $key["total_cost"],
                                "opertaion_time" => $key["operating_hours"],
                                "amount" => $key["total_amount"],
                                "bom_id" => $inserted_id
                            ];

                            $ins2 = $this->BomOperationModel->insert($d_op);

                            if (!$ins2) {
                                $result2 = false;
                                throw new DatabaseException('Failed to insert operation row: ' . json_encode($ins2));
                            }

                            array_push($id_array_op, $this->db->insertID());
                        } else {

                            $d_op = [
                                "per_hour_cost" => $key["total_cost"],
                                "opertaion_time" => $key["operating_hours"],
                                "amount" => $key["total_amount"],
                            ];

                            $upd2 = $this->BomOperationModel->update($res2["id"], $d_op);

                            if (!$upd2) {
                                $result2 = false;
                                throw new DatabaseException('Failed to insert operation row: ' . json_encode($ins2));
                            }

                            array_push($id_array_op, $res2["id"]);
                        }

                        if (!$result2) {
                            $update = false;
                            return;
                        }
                    }

                    $get_old_items2 = $this->BomOperationModel->where("bom_id", $bom_id)->get()->getResultArray();
                    $old_items_ids2 = array_column($get_old_items2, 'id');
                    $old_items_ids2 = array_diff($old_items_ids2, $id_array_op);
                    $dele_result2 = true;

                    foreach ($old_items_ids2 as $i) {
                        $dele_result2 = $this->exist_operation_data_delete($i);
                    }

                    if (!$dele_result2) {
                        $update = false;
                    }

                    $this->db->transCommit(); // Commit the transaction
                } catch (DatabaseException $e) {
                    $this->db->transRollback(); // Rollback the transaction
                    $update = false;
                }

            }
        }

        if ($update) {
            if ($status == 2) {
                $related_to = "finished_good";
                $product_id_copy = $product_id;
                $warehouse_id_copy = $warehouse_id;
                $quantity_copy = $quantity;
                $price_id = $this->PlanningModel->where("planning_id", $planning_id)->get()->getRowArray();

                $query3 = "INSERT INTO stocks(related_to,related_id,warehouse_id,quantity,price_id) VALUE (?,?,?,?,?)";
                $resul = $this->db->query($query3, array($related_to, $product_id_copy, $warehouse_id_copy, $quantity_copy, $price_id["price_id"]));

                // $this->logger->info($resul);
                if (!$resul) {
                    $this->session->setFlashdata("op_success", "Bom is Created But error while creating stock");
                }

            }
        }

        $config['title'] = "MRP Scheduling update";
        $config['log_text'] = $update ? "[BOM successfully updated ]" : "[ BOM failed to updated ]";
        $config['ref_link'] = $update ? url_to('erp.mrp.planningview', $planning_id) : url_to('erp.mrp.planningschedule');

        // $this->session->setFlashdata($inserted ? "op_success" : "op_error", $inserted ? "MRP Scheduling successfully created" : "MRP Scheduling failed to create");
        log_activity($config);
        return $update;
    }

    public function exist_data_delete($id)
    {
        return $this->BOMItemsModel->delete($id);
    }
    public function exist_scrap_data_delete($id)
    {
        return $this->BomscrapitemsModel->delete($id);
    }
    public function exist_operation_data_delete($id)
    {
        return $this->BomOperationModel->delete($id);
    }

    public function get_datatable_bom($id)
    {
        $planning_id = $id;
        $limit = $_GET['limit'];
        $offset = $_GET['offset'];
        $search = $_GET['search'] ?? "";
        $orderby = isset($_GET['orderby']) ? strtoupper($_GET['orderby']) : "";
        // $ordercol = isset($_GET['ordercol']) ? $columns[$_GET['ordercol']] : "";
        $where = true;
        $query = "SELECT finished_goods.name as f_name,warehouses.name as w_name,bom.related_id as planning_id,bom.bom_id as bom_id,bom.status as status,bom.quantity as bom_qty,bom.amount as amount,
        bom.date as bom_date,bom.skucode as bom_code FROM bom
        JOIN finished_goods ON finished_goods.finished_good_id = bom.product_id JOIN warehouses ON warehouses.warehouse_id = bom.warehouse_id  where bom.related_id = $planning_id ";
        $count = "SELECT count(*) as total FROM bom JOIN finished_goods ON finished_goods.finished_good_id = bom.product_id JOIN warehouses ON warehouses.warehouse_id = bom.warehouse_id where bom.related_id = $planning_id ";

        if (!empty($search)) {
            if (!$where) {
                $query .= " AND ( bom.skucode LIKE '%" . $search . "%' OR warehouses.name LIKE '%" . $search . "%' ) ";
                $count .= "  AND ( bom.skucode LIKE '%" . $search . "%' OR warehouses.name LIKE '%" . $search . "%' ) ";
                $where = true;
            } else {
                $query .= " AND ( bom.skucode LIKE '%" . $search . "%' OR warehouses.name LIKE '%" . $search . "%' ) ";
                $count .= " AND ( bom.skucode LIKE '%" . $search . "%' OR warehouses.name LIKE '%" . $search . "%' ) ";
            }
        }

        $query .= "ORDER BY bom.created_at DESC LIMIT $offset,$limit ";

        $result = $this->db->query($query)->getResultArray();
        $m_result = array();
        $sno = 1;

        $p_status = $this->PlanningModel->get_planning_status();
        $p_status_bg = $this->PlanningModel->get_planning_status_bg();

        foreach ($result as $r) {

            $action = '<div class="dropdown tableAction">
            <a type="button" class="dropBtn HoverA "><i class="fa fa-ellipsis-v"></i></a>
            <div class="dropdown_container">
                <ul class="BB_UL flex">';

            $action .= '<li><a href="' . url_to('erp.bill.of.materials.edit', $r['bom_id'], $r["planning_id"]) . '" title="Edit" class="bg-success "><i class="fa fa-pencil"></i> </a></li>';
            $action .= '<li><a href="' . url_to('erp.bill.of.materials.delete', $r['bom_id'], $r["planning_id"]) . '" title="Delete" class="bg-danger del-confirm"><i class="fa fa-trash"></i> </a></li>
                </ul>
            </div>
            </div>';

            $status = "<span class='st " . $p_status_bg[$r['status']] . "'>" . $p_status[$r['status']] . " </span>";

            array_push(
                $m_result,
                array(
                    "",
                    $sno,
                    $r['bom_code'],
                    $r['f_name'],
                    $r['w_name'],
                    $r['bom_date'],
                    $r['amount'],
                    $r['bom_qty'],
                    $status,
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

    public function get_bom_datatable_overall()
    {
        $columns = array(
            "status" => "b.status",
        );
        $limit = $_GET['limit'];
        $offset = $_GET['offset'];
        $search = $_GET['search'] ?? "";
        // $orderby = isset($_GET['orderby']) ? strtoupper($_GET['orderby']) : "";
        // $ordercol = isset($_GET['ordercol']) ? $columns[$_GET['ordercol']] : "";
        $where = false;
        $query = "SELECT b.bom_id as bom_id,p.planning_id as planning_id,b.skucode as code,r.name as product_name,b.status as status,b.date as bom_date,b.quantity as
         quantity,b.amount as amount,w.name as warehouse_name
         FROM bom as b JOIN finished_goods as r ON r.finished_good_id = b.product_id JOIN planning as p ON p.planning_id = b.related_id JOIN warehouses as w ON w.warehouse_id = b.warehouse_id ";
        $count = "SELECT count(*) as total FROM bom as b JOIN finished_goods as r ON r.finished_good_id = b.product_id JOIN planning as p ON p.planning_id = b.related_id JOIN warehouses as w ON w.warehouse_id = b.warehouse_id ";

        $filter_1_col = isset($_GET['status']) ? $columns['status'] : "";
        $filter_1_val = $_GET['status'];

        if (!empty($filter_1_col) && $filter_1_val !== "") {
            $query .= " WHERE " . $filter_1_col . "=" . $filter_1_val . " ";
            $count .= " WHERE " . $filter_1_col . "=" . $filter_1_val . " ";
            $where = true;
        }

        if (!empty($search)) {
            if (!$where) {
                $query .= " WHERE ( b.skucode LIKE '%" . $search . "%' OR r.name LIKE '%" . $search . "%' OR w.name LIKE '%" . $search . "%') ";
                $count .= " WHERE ( b.skucode LIKE '%" . $search . "%' OR r.name LIKE '%" . $search . "%' OR w.name LIKE '%" . $search . "%') ";
                $where = true;
            } else {
                $query .= " AND ( b.skucode LIKE '%" . $search . "%' OR r.name LIKE '%" . $search . "%' OR w.name LIKE '%" . $search . "%') ";
                $count .= " AND ( b.skucode LIKE '%" . $search . "%' OR r.name LIKE '%" . $search . "%' OR w.name LIKE '%" . $search . "%') ";
            }
        }

        $query .= " ORDER BY b.created_at DESC LIMIT $offset,$limit ";

        $result = $this->db->query($query)->getResultArray();
        $m_result = array();
        $sno = 1;

        $p_status = $this->PlanningModel->get_planning_status();
        $p_status_bg = $this->PlanningModel->get_planning_status_bg();

        foreach ($result as $r) {

            $action = '<div class="dropdown tableAction">
            <a type="button" class="dropBtn HoverA "><i class="fa fa-ellipsis-v"></i></a>
            <div class="dropdown_container">
                <ul class="BB_UL flex">';
            $action .= '<li><a href="' . url_to('erp.mrp.planningview', $r['planning_id']) . '" title="View" class="bg-warning"><i class="fa fa-eye"></i> </a></li>
                </ul>
            </div>
            </div>';

            $status = "<span class='st " . $p_status_bg[$r['status']] . "'>" . $p_status[$r['status']] . " </span>";

            array_push(
                $m_result,
                array(
                    "",
                    $sno,
                    $r['product_name'],
                    $r['code'],
                    $r['warehouse_name'],
                    $r['bom_date'],
                    $r['amount'],
                    $r['quantity'],
                    $status,
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

    public function getOverallDataExport()
    {
        $columns = array(
            "status" => "b.status",
        );
        $search = $_GET['search'] ?? "";
        $where = false;
        $query = "SELECT b.bom_id as bom_id,p.planning_id as planning_id,b.skucode as code,r.name as product_name,b.status as status,b.date as bom_date,b.quantity as
         quantity,b.amount as amount,w.name as warehouse_name
         FROM bom as b JOIN finished_goods as r ON r.finished_good_id = b.product_id JOIN planning as p ON p.planning_id = b.related_id JOIN warehouses as w ON w.warehouse_id = b.warehouse_id ";

        $filter_1_col = isset($_GET['status']) ? $columns['status'] : "";
        $filter_1_val = $_GET['status'];

        if (!empty($filter_1_col) && $filter_1_val !== "") {
            $query .= " WHERE " . $filter_1_col . "=" . $filter_1_val . " ";
            $where = true;
        }

        if (!empty($search)) {
            if (!$where) {
                $query .= " WHERE ( b.skucode LIKE '%" . $search . "%' OR r.name LIKE '%" . $search . "%' OR w.name LIKE '%" . $search . "%') ";
                $where = true;
            } else {
                $query .= " AND ( b.skucode LIKE '%" . $search . "%' OR r.name LIKE '%" . $search . "%' OR w.name LIKE '%" . $search . "%') ";
            }
        }

        $query .= " ORDER BY b.created_at DESC ";

        $result = $this->db->query($query)->getResultArray();
        $m_result = array();
        $sno = 1;

        $p_status = $this->PlanningModel->get_planning_status();

        foreach ($result as $r) {
            $status = $p_status[$r['status']];

            array_push(
                $m_result,
                array(
                    $sno,
                    $r['product_name'],
                    $r['code'],
                    $r['warehouse_name'],
                    $r['bom_date'],
                    $r['amount'],
                    $r['quantity'],
                    $status,
                )
            );
            $sno++;
        }

        return $m_result;
    }

    public function get_exist_bom($bom_id, $planning_id)
    {

        return $this->db->table('bom')->select('bom.*, warehouses.name as w_name, warehouses.warehouse_id as w_id')->join('warehouses', 'bom.warehouse_id = warehouses.warehouse_id')->where("related_id", $planning_id)->where("bom_id", $bom_id)->get()->getRowArray();
    }

    public function delete_bom($bom_id, $planning_id)
    {
        return $this->builder()->where("bom_id", $bom_id)->where("related_id", $planning_id)->delete();
    }




    public function get_dtconfig_operations()
    {
        $config = array(
            "columnNames" => ["Sno", "Planning", "SKU Code", "related To", "Warehouse", "BOM Date", "Amount", "Quantity", "status", "Action"],
            "sortable" => [1, 1, 0, 1, 0, 0, 0, 0, 1, 0],
            "filters" => ["status"]
        );
        return json_encode($config);
    }



    //     public function getTotalCost($planning_id)
    // {

    //     $query = "select raw_material_cost+operating_cost+scrap_cost as total_cost FROM costing WHERE planning_id = $planning_id ";
    //     return $this->db->query($query)->getRowArray();
    // }

    public function delete_operation_from_bom($bom_id)
    {
        $query = "delete from bom_operation_list WHERE bom_id = " . $bom_id;
        return $this->db->query($query);
    }

}

