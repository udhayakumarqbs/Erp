<?php

namespace App\Models\Erp\Warehouse;

use CodeIgniter\Model;
use Exception;

class CurrentStockModel extends Model
{
    protected $table            = 'stocks';
    protected $primaryKey       = 'stock_id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'related_to',
        'related_id',
        'warehouse_id',
        'quantity',
        'price_id'
    ];

    protected $product_types = array(
        "finished_good" => "Finished Goods",
        "semi_finished" => "Semi Finished",
        "raw_material" => "Raw Materials",
    );

    public function get_product_types()
    {
        return $this->product_types;
    }
    public function get_product_links()
    {
        return $this->product_links;
    }

    protected $product_links = array(
        "finished_good" => "erp/crm/get_fetchfinishedgoods",
        "semi_finished" => "erp/procurement/ajaxfetchsemifinished",
        "raw_material" => "erp/procurement/ajaxfetchrawmaterials",
    );



    public function add_stock_from_grn($grn_id)
    {
        $query = "SELECT add_to_stock(?) AS error_flag ";
        $this->db->transBegin();
        $flag = $this->db->query($query, array($grn_id))->getRow()->error_flag;
        if ($flag == 1) {
            $this->db->transRollback();
            session()->setFlashdata("op_error", "Add to Stock Failed");
        } else {
            $this->db->transComplete();
            $config['title'] = "Add to Stock";
            if ($this->db->transStatus() === FALSE) {
                $this->db->transRollback();
                $config['log_text'] = "[ Add to Stock failed ]";
                $config['ref_link'] = 'erp/warehouse/grnview/' . $grn_id;
                session()->setFlashdata("op_error", "Add to Stock failed");
            } else {
                $this->db->transCommit();
                $config['log_text'] = "[ Add to Stock successfully done ]";
                $config['ref_link'] = 'erp/warehouse/grnview/' . $grn_id;
                session()->setFlashdata("op_success", "Add to Stock successfully done");
            }
            log_activity($config);
        }
    }
    //
    public function get_dtconfig_currentstock()
    {
        $config = array(
            "columnNames" => ["sno", "product type", "product", "warehouse", "stock", "unit price", "amount", "action"],
            "sortable" => [0, 0, 0, 0, 1, 1, 0, 0],
            "filters" => ["product_type", "warehouse"]
        );
        return json_encode($config);
    }

    public function get_currentstock_datatable()
    {
        $columns = array(
            "product_type" => "related_to",
            "warehouse" => "warehouses.warehouse_id",
            "stock" => "quantity",
            "unit price" => "price_list.amount"
        );
        $limit = $_GET['limit'];
        $offset = $_GET['offset'];
        $search = $_GET['search'] ?? "";
        $orderby = isset($_GET['orderby']) ? strtoupper($_GET['orderby']) : "";
        $ordercol = isset($_GET['ordercol']) ? $columns[$_GET['ordercol']] : "";

        $query = "SELECT stock_id,related_to,COALESCE(CONCAT(raw_materials.name,' ',raw_materials.code),CONCAT(semi_finished.name,' ',semi_finished.code),CONCAT(finished_goods.name,' ',finished_goods.code)) AS product,warehouses.name,quantity,price_list.amount AS unit_price,(quantity*price_list.amount) AS amount FROM stocks JOIN warehouses ON stocks.warehouse_id=warehouses.warehouse_id JOIN price_list ON stocks.price_id=price_list.price_id LEFT JOIN raw_materials ON related_id=raw_materials.raw_material_id AND related_to='raw_material' LEFT JOIN semi_finished ON related_id=semi_finished.semi_finished_id AND related_to='semi_finished' LEFT JOIN finished_goods ON related_id=finished_goods.finished_good_id ";
        $count = "SELECT COUNT(stock_id) AS total FROM stocks JOIN warehouses ON stocks.warehouse_id=warehouses.warehouse_id JOIN price_list ON stocks.price_id=price_list.price_id LEFT JOIN raw_materials ON related_id=raw_materials.raw_material_id AND related_to='raw_material' LEFT JOIN semi_finished ON related_id=semi_finished.semi_finished_id AND related_to='semi_finished' LEFT JOIN finished_goods ON related_id=finished_goods.finished_good_id ";
        $where = false;

        $filter_1_col = isset($_GET['product_type']) ? $columns['product_type'] : "";
        $filter_1_val = $_GET['product_type'];

        $filter_2_col = isset($_GET['warehouse']) ? $columns['warehouse'] : "";
        $filter_2_val = $_GET['warehouse'];

        if (!empty($filter_1_col) && $filter_1_val !== "") {
            $query .= " WHERE " . $filter_1_col . "= '" . $filter_1_val . "' ";
            $count .= " WHERE " . $filter_1_col . "= '" . $filter_1_val . "' ";
            $where = true;
        }

        if (!empty($filter_2_col) && $filter_2_val !== "") {
            if ($where) {
                $query .= " AND " . $filter_2_col . "=" . $filter_2_val . " ";
                $count .= " AND " . $filter_2_col . "=" . $filter_2_val . " ";
            } else {
                $query .= " WHERE " . $filter_2_col . "=" . $filter_2_val . " ";
                $count .= " WHERE " . $filter_2_col . "=" . $filter_2_val . " ";
                $where = true;
            }
        }

        if (!empty($search)) {
            if (!$where) {
                $query .= " WHERE ( raw_materials.name LIKE '%" . $search . "%' OR semi_finished.name LIKE '%" . $search . "%' OR finished_goods.name LIKE '%" . $search . "%' ) ";
                $count .= " WHERE ( raw_materials.name LIKE '%" . $search . "%' OR semi_finished.name LIKE '%" . $search . "%' OR finished_goods.name LIKE '%" . $search . "%' ) ";
                $where = true;
            } else {
                $query .= " AND ( raw_materials.name LIKE '%" . $search . "%' OR semi_finished.name LIKE '%" . $search . "%' OR finished_goods.name LIKE '%" . $search . "%' ) ";
                $count .= " AND ( raw_materials.name LIKE '%" . $search . "%' OR semi_finished.name LIKE '%" . $search . "%' OR finished_goods.name LIKE '%" . $search . "%' ) ";
            }
        }

        if (!empty($ordercol) && !empty($orderby)) {
            $query .= " ORDER BY " . $ordercol . " " . $orderby;
        }

        $query .= " LIMIT $offset,$limit ";
        $result = $this->db->query($query)->getResultArray();
        $m_result = array();
        $sno = $offset + 1;
        foreach ($result as $r) {
            $action = '<div class="dropdown tableAction">
                        <a type="button" class="dropBtn HoverA "><i class="fa fa-ellipsis-v"></i></a>
                        <div class="dropdown_container">
                            <ul class="BB_UL flex">
                                <li><a href="#" data-stock-id="' . $r['stock_id'] . '" title="Transfer" class="bg-success modalBtn"><i class="fa fa-refresh"></i> </a></li>
                            </ul>
                        </div>
                    </div>';
            array_push(
                $m_result,
                array(
                    $r['stock_id'],
                    $sno,
                    $r['related_to'],
                    $r['product'],
                    $r['name'],
                    $r['quantity'],
                    $r['unit_price'],
                    $r['amount'],
                    $action
                )
            );
            $sno++;
        }
        $response = array();
        $response['total_rows'] = $this->db->query($count)->getRow()->total;
        $response['data'] = $m_result;
        return $response;
    }

    public function stock_transfer()
    {
        $stock_id =$_POST["stock_id"];
        $warehouse_id = $_POST["warehouse_id"];
        $qty = $_POST["quantity"];

        $stock = $this->db->query("SELECT * FROM stocks WHERE stock_id=$stock_id")->getRow();
        $config['title'] = "Stock Transfer";
        $config['ref_link'] = "erp/warehouse/currentstock";
        if (!empty($stock) && $qty <= $stock->quantity) {
            $query = "SELECT stock_id,warehouse_id FROM stocks WHERE related_to=? AND related_id=? AND warehouse_id=? AND price_id=? ";
            $result = $this->db->query($query, array($stock->related_to, $stock->related_id, $warehouse_id, $stock->price_id))->getRow();
            if (!empty($result)) {
                if ($stock->warehouse_id != $result->warehouse_id) {
                    $merge_stock_id = $result->stock_id;
                    $query = "UPDATE stocks SET quantity=quantity+$qty WHERE stock_id=$merge_stock_id ";
                    $this->db->query($query);
                    if ($this->db->affectedRows() > 0) {
                        $this->db->query("UPDATE stocks SET quantity=quantity-$qty WHERE stock_id=$stock_id");
                        $config['log_text'] = "[ Stock Tranfer successfully done ]";
                        session()->setFlashdata("op_success", "Stock Tranfer successfully done");
                    } else {
                        $config['log_text'] = "[ Stock Transfer failed ]";
                        session()->setFlashdata("op_error", "Stock Transfer failed");
                    }
                } else {
                    $config['log_text'] = "[ Stock Transfer failed ]";
                    session()->setFlashdata("op_error", "Stock Transfer failed");
                }
            } else {
                $query = "INSERT INTO stocks(related_to,related_id,warehouse_id,price_id,quantity) VALUES(?,?,?,?,?) ";
                $this->db->query($query, array($stock->related_to, $stock->related_id, $warehouse_id, $stock->price_id, $qty));
                if (!empty($this->db->insertId())) {
                    $this->db->query("UPDATE stocks SET quantity=quantity-$qty WHERE stock_id=$stock_id");
                    $config['log_text'] = "[ Stock Tranfer successfully done ]";
                    session()->setFlashdata("op_success", "Stock Tranfer successfully done");
                } else {
                    $config['log_text'] = "[ Stock Transfer failed ]";
                    session()->setFlashdata("op_error", "Stock Transfer failed");
                }
            }
            log_activity($config);
        } else {
            session()->setFlashdata("op_error", "Stock Transfer failed");
        }
    }



    public function CurrentStockExport($type)
    {
        $columns = array(
            "product_type" => "related_to",
            "warehouse" => "warehouses.warehouse_id",
            "stock" => "quantity",
            "unit price" => "price_list.amount"
        );
        $limit = $_GET['limit'];
        $offset = $_GET['offset'];
        $search = $_GET['search'] ?? "";
        $orderby = isset($_GET['orderby']) ? strtoupper($_GET['orderby']) : "";
        $ordercol = isset($_GET['ordercol']) ? $columns[$_GET['ordercol']] : "";

        $query = "SELECT CASE ";
        foreach ($this->product_types as $key => $value) {
            $query .= " WHEN related_to='$key' THEN '$value' ";
        }
        $query .= " END AS related_to,COALESCE(CONCAT(raw_materials.name,' ',raw_materials.code),CONCAT(semi_finished.name,' ',semi_finished.code),CONCAT(finished_goods.name,' ',finished_goods.code)) AS product,warehouses.name,quantity,price_list.amount AS unit_price,(quantity*price_list.amount) AS amount FROM stocks JOIN warehouses ON stocks.warehouse_id=warehouses.warehouse_id JOIN price_list ON stocks.price_id=price_list.price_id LEFT JOIN raw_materials ON related_id=raw_materials.raw_material_id AND related_to='raw_material' LEFT JOIN semi_finished ON related_id=semi_finished.semi_finished_id AND related_to='semi_finished' LEFT JOIN finished_goods ON related_id=finished_goods.finished_good_id ";
        $where = false;

        $filter_1_col = isset($_GET['product_type']) ? $columns['product_type'] : "";
        $filter_1_val = $_GET['product_type'];

        $filter_2_col = isset($_GET['warehouse']) ? $columns['warehouse'] : "";
        $filter_2_val = $_GET['warehouse'];

        if (!empty($filter_1_col) && $filter_1_val !== "") {
            $query .= " WHERE " . $filter_1_col . "= '" . $filter_1_val . "' ";
            $where = true;
        }

        if (!empty($filter_2_col) && $filter_2_val !== "") {
            if ($where) {
                $query .= " AND " . $filter_2_col . "=" . $filter_2_val . " ";
            } else {
                $query .= " WHERE " . $filter_2_col . "=" . $filter_2_val . " ";
                $where = true;
            }
        }

        if (!empty($search)) {
            if (!$where) {
                $query .= " WHERE ( raw_materials.name LIKE '%" . $search . "%' OR semi_finished.name LIKE '%" . $search . "%' OR finished_goods.name LIKE '%" . $search . "%' ) ";
                $where = true;
            } else {
                $query .= " AND ( raw_materials.name LIKE '%" . $search . "%' OR semi_finished.name LIKE '%" . $search . "%' OR finished_goods.name LIKE '%" . $search . "%' ) ";
            }
        }

        if (!empty($ordercol) && !empty($orderby)) {
            $query .= " ORDER BY " . $ordercol . " " . $orderby;
        }
        $result = $this->db->query($query)->getResultArray();
        return $result;
    }





    //Manage Stock 


    public function get_dtconfig_managestock()
    {
        $config = array(
            "columnNames" => ["sno", "sku", "product type", "product", "bin name", "manufactured date", "action"],
            "sortable" => [0, 1, 0, 0, 0, 1, 0],
            "filters" => ["product_type"]
        );
        return json_encode($config);
    }

    public function get_managestock_datatable()
    {
        $columns = array(
            "sku" => "sku",
            "manufactured date" => "mfg_date",
            "product_type" => "related_to"
        );
        $limit = $_GET['limit'];
        $offset = $_GET['offset'];
        $search = $_GET['search'] ?? "";
        $orderby = isset($_GET['orderby']) ? strtoupper($_GET['orderby']) : "";
        $ordercol = isset($_GET['ordercol']) ? $columns[$_GET['ordercol']] : "";

        $query = "SELECT pack_unit_id,sku,related_to,COALESCE(CONCAT(raw_materials.name,' ',raw_materials.code),CONCAT(semi_finished.name,' ',semi_finished.code),CONCAT(finished_goods.name,' ',finished_goods.code)) AS product,bin_name,mfg_date FROM pack_unit LEFT JOIN raw_materials ON related_id=raw_materials.raw_material_id AND related_to='raw_material' LEFT JOIN semi_finished ON related_id=semi_finished.semi_finished_id AND related_to='semi_finished' LEFT JOIN finished_goods ON related_id=finished_goods.finished_good_id AND related_to='finished_good' ";
        $count = "SELECT COUNT(pack_unit_id) AS total FROM pack_unit LEFT JOIN raw_materials ON related_id=raw_materials.raw_material_id AND related_to='raw_material' LEFT JOIN semi_finished ON related_id=semi_finished.semi_finished_id AND related_to='semi_finished' LEFT JOIN finished_goods ON related_id=finished_goods.finished_good_id AND related_to='finished_good'  ";
        $where = false;

        $filter_1_col = isset($_GET['product_type']) ? $columns['product_type'] : "";
        $filter_1_val = $_GET['product_type'];

        if (!empty($filter_1_col) && $filter_1_val !== "") {
            $query .= " WHERE " . $filter_1_col . "='" . $filter_1_val . "' ";
            $count .= " WHERE " . $filter_1_col . "='" . $filter_1_val . "' ";
            $where = true;
        }

        if (!empty($search)) {
            if (!$where) {
                $query .= " WHERE ( raw_materials.name LIKE '%" . $search . "%' OR semi_finished.name LIKE '%" . $search . "%' OR finished_goods.name LIKE '%" . $search . "%' ) ";
                $count .= " WHERE ( raw_materials.name LIKE '%" . $search . "%' OR semi_finished.name LIKE '%" . $search . "%' OR finished_goods.name LIKE '%" . $search . "%' ) ";
                $where = true;
            } else {
                $query .= " AND ( raw_materials.name LIKE '%" . $search . "%' OR semi_finished.name LIKE '%" . $search . "%' OR finished_goods.name LIKE '%" . $search . "%' ) ";
                $count .= " AND ( raw_materials.name LIKE '%" . $search . "%' OR semi_finished.name LIKE '%" . $search . "%' OR finished_goods.name LIKE '%" . $search . "%' ) ";
            }
        }

        if (!empty($ordercol) && !empty($orderby)) {
            $query .= " ORDER BY " . $ordercol . " " . $orderby;
        }
        $query .= " LIMIT $offset,$limit ";
        $result = $this->db->query($query)->getResultArray();
        $m_result = array();
        $sno = $offset + 1;
        foreach ($result as $r) {
            $action = '<div class="dropdown tableAction">
                        <a type="button" class="dropBtn HoverA "><i class="fa fa-ellipsis-v"></i></a>
                        <div class="dropdown_container">
                            <ul class="BB_UL flex">
                                <li><a href="' . url_to('erp.warehouse.managestock.edit', $r['pack_unit_id']) . '" title="Edit" class="bg-success modalBtn"><i class="fa fa-pencil"></i> </a></li>
                                <li><a href="' . url_to('erp.warehouse.managestock.delete',$r['pack_unit_id']) . '" title="Delete" class="bg-danger del-confirm"><i class="fa fa-trash"></i> </a></li>
                            </ul>
                        </div>
                    </div>';
            array_push(
                $m_result,
                array(
                    $r['pack_unit_id'],
                    $sno,
                    $r['sku'],
                    $this->product_types[$r['related_to']],
                    $r['product'],
                    $r['bin_name'],
                    $r['mfg_date'],
                    $action
                )
            );
            $sno++;
        }
        $response = array();
        $response['total_rows'] = $this->db->query($count)->getRow()->total;
        $response['data'] = $m_result;
        return $response;
    }

    public function get_all_warehouses()
    {
        $query = "SELECT warehouse_id,name FROM warehouses";
        $result = $this->db->query($query)->getResultArray();
        return $result;
    }



    public function insert_stock()
    {
        $inserted = false;
        $sku = $_POST["sku"];
        $warehouse_id = $_POST["warehouse_id"];
        $related_to = $_POST["related_to"];
        $related_id = $_POST["related_id"];
        $price_id = $_POST["price_id"];
        $bin_name = $_POST["bin_name"];
        $mfg_date = $_POST["mfg_date"];
        $batch_no = $_POST["batch_no"];
        $lot_no = $_POST["lot_no"];
        $this->db->transBegin();
        $query = "INSERT INTO pack_unit(sku,related_to,related_id,bin_name,mfg_date,batch_no,lot_no) VALUES(?,?,?,?,?,?,?)";
        $this->db->query($query, array($sku, $related_to, $related_id, $bin_name, $mfg_date, $batch_no, $lot_no));
        if (empty($this->db->insertID())) {
            session()->setFlashdata("op_error", "Stock failed to add");
            return $inserted;
        }
        $query = "SELECT add_to_stock2(?,?,?,?) AS flag ";
        $flag = $this->db->query($query, array($related_to, $related_id, $price_id, $warehouse_id))->getRow()->flag;
        if ($flag == 1) {
            $this->db->transRollback();
            session()->setFlashdata("op_error","Stock failed to add");
            return $inserted;
        } else {
            $this->db->transComplete();
            $config['title'] = "Add to Stock Manual";
            if ($this->db->transStatus() === FALSE) {
                $this->db->transRollback();
                $config['log_text'] = "[ Add to Stock Manual failed ]";
                $config['ref_link'] = 'erp/warehouse/managestock/';
                session()->setFlashdata("op_error", "Add to Stock Manual failed");
            } else {
                $inserted = true;
                $this->db->transCommit();
                $config['log_text'] = "[ Add to Stock Manual successfully done ]";
                $config['ref_link'] = 'erp/warehouse/managestock/';
                session()->setFlashdata("op_success", "Add to Stock Manual successfully done");
            }
            log_activity($config);
        }
        return $inserted;
    }

    public function updatestock($pack_unit_id)
    {
        $updated = false;
        $sku = $_GET["sku"];
        $bin_name = $_GET["bin_name"];
        $mfg_date = $_GET["mfg_date"];
        $batch_no = $_GET["batch_no"];
        $lot_no = $_GET["lot_no"];

        $query = "UPDATE pack_unit SET sku=? , bin_name=? , mfg_date=? , batch_no=? , lot_no=? WHERE pack_unit_id=$pack_unit_id";
        $this->db->query($query, array($sku, $bin_name, $mfg_date, $batch_no, $lot_no));

        $config['title'] = "Stock Update";
        if ($this->db->affectedRows() > 0) {
            $updated = true;
            $config['log_text'] = "[ Stock successfully updated ]";
            $config['ref_link'] = 'erp/warehouse/managestock';
            session()->setFlashdata("op_success", "Stock successfully updated");
        } else {
            $config['log_text'] = "[ Stock failed to update ]";
            $config['ref_link'] = 'erp/warehouse/managestock';
            session()->setFlashdata("op_error", "Stock failed to update");
        }
        log_activity($config);
        return $updated;
    }




    public function get_managestock_export($type)
    {
        $columns = array(
            "sku" => "sku",
            "manufactured date" => "mfg_date",
            "product_type" => "related_to"
        );
        $limit = $_GET['limit'];
        $offset = $_GET['offset'];
        $search = $_GET['search'] ?? "";
        $orderby = isset($_GET['orderby']) ? strtoupper($_GET['orderby']) : "";
        $ordercol = isset($_GET['ordercol']) ? $columns[$_GET['ordercol']] : "";

        $query = "SELECT sku,
        CASE ";
        foreach ($this->product_types as $key => $value) {
            $query .= " WHEN related_to='$key' THEN '$value' ";
        }
        $query .= " END AS related_to,COALESCE(CONCAT(raw_materials.name,' ',raw_materials.code),CONCAT(semi_finished.name,' ',semi_finished.code),CONCAT(finished_goods.name,' ',finished_goods.code)) AS product,bin_name,mfg_date,batch_no,lot_no FROM pack_unit LEFT JOIN raw_materials ON related_id=raw_materials.raw_material_id AND related_to='raw_material' LEFT JOIN semi_finished ON related_id=semi_finished.semi_finished_id AND related_to='semi_finished' LEFT JOIN finished_goods ON related_id=finished_goods.finished_good_id AND related_to='finished_good' ";
        $where = false;

        $filter_1_col = isset($_GET['product_type']) ? $columns['product_type'] : "";
        $filter_1_val = $_GET['product_type'];

        if (!empty($filter_1_col) && $filter_1_val !== "") {
            $query .= " WHERE " . $filter_1_col . "='" . $filter_1_val . "' ";
            $where = true;
        }

        if (!empty($search)) {
            if (!$where) {
                $query .= " WHERE ( raw_materials.name LIKE '%" . $search . "%' OR semi_finished.name LIKE '%" . $search . "%' OR finished_goods.name LIKE '%" . $search . "%' ) ";
                $where = true;
            } else {
                $query .= " AND ( raw_materials.name LIKE '%" . $search . "%' OR semi_finished.name LIKE '%" . $search . "%' OR finished_goods.name LIKE '%" . $search . "%' ) ";
            }
        }
        if (!empty($ordercol) && !empty($orderby)) {
            $query .= " ORDER BY " . $ordercol . " " . $orderby;
        }
        $result = $this->db->query($query)->getResultArray();
        return $result;
    }

    public function get_order_items($order_id)
    {
        $query = "SELECT COALESCE(CONCAT(raw_materials.name,' ',raw_materials.code),CONCAT(semi_finished.name,' ',semi_finished.code)) AS product,price_list.name,price_list.amount AS unit_price,CONCAT(t1.tax_name,' ',t1.percent,' , ',IFNULL(t2.tax_name,''),' ',IFNULL(t2.percent,'')) AS tax,quantity,received_qty,returned_qty FROM purchase_order_items LEFT JOIN raw_materials ON related_id=raw_materials.raw_material_id AND related_to='raw_material' LEFT JOIN semi_finished ON related_id=semi_finished.semi_finished_id AND related_to='semi_finished' JOIN price_list ON purchase_order_items.price_id=price_list.price_id LEFT JOIN taxes t1 ON price_list.tax1=t1.tax_id LEFT JOIN taxes t2 ON price_list.tax2=t2.tax_id WHERE order_id=$order_id";
        $result = $this->db->query($query)->getResultArray();
        return $result;
    }
//managestock delete
      public function get_manage_stock_by_id($pack_unit_id)
      {
          return $this->db->table('pack_unit')->select()->where('pack_unit_id', $pack_unit_id)->get()->getRow();
      }


    public function delete_managestock($pack_unit_id)
    {
        $deleted = false;

        $managestock = $this->get_manage_stock_by_id($pack_unit_id);

   
        $this->db->table('pack_unit')->where('pack_unit_id', $pack_unit_id)->delete();

        if ($this->db->affectedRows() > 0) {
            $deleted = true;

           
            $config = [
                'title' => 'Managestock Deletion',
                'log_text' => "[ Managestock successfully deleted ]",
                'ref_link' => 'erp.warehouse.managestock',
                'additional_info' => json_encode( $managestock), 
            ];
            log_activity($config);
        } else {
            $config = [
                'title' => 'Managestock Deletion',
                'log_text' => "[ Managestock failed to delete ]",
                'ref_link' => 'erp.warehouse.managestock' . $pack_unit_id,
            ];
            log_activity($config);
        }

        return $deleted;
    }

    public function get_stock_by_id($pack_unit_id){
        $query="SELECT sku,bin_name,mfg_date,batch_no,lot_no FROM pack_unit WHERE pack_unit_id=$pack_unit_id";
        $result=$this->db->query($query)->getRow();
        return $result;
    }
}
