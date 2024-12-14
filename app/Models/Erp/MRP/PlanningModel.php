<?php

namespace App\Models\Erp\MRP;

use CodeIgniter\Model;
use Exception;

class PlanningModel extends Model
{
    protected $table = 'planning';
    protected $primaryKey = 'planning_id';

    protected $allowedFields = [
        'costing_id',
        'finished_good_id',
        'start_date',
        'end_date',
        'finished_date',
        'price_id',
        'stock',
        'status',
        'created_by'
    ];

    protected $db;
    public $session;
    public $logger;

    public function __construct()
    {
        $this->session = \Config\Services::session();
        $this->db = \Config\Database::connect();
        $this->logger = \Config\Services::logger();

        helper(['erp', 'form']);
    }

    private $planning_status = array(
        0 => "Not Started",
        1 => "Ongoing",
        2 => "Complete",
        3 => "Cancelled",
    );

    // private $servicePriority_status = array(
    //     0 => "Low",
    //     1 => "Medium",
    //     2 => "high",
    //     3 => "Urgent",
    // );

    private $planning_status_bg = array(
        0 => "st_violet",
        2 => "st_success",
        1 => "st_primary",
        3 => "st_danger"
    );

    private $service_priority_bg = array(
        0 => "st_dark",
        1 => "st_primary",
        2 => "st_violet",
        3 => "st_danger"
    );

    public function get_planning_status()
    {
        return $this->planning_status;
    }

    public function get_count_status($status = 0)
    {
        $query = $this->db->query("
            SELECT COUNT(status) as status_count 
            FROM planning 
            WHERE status = ?", [$status]);

        return $query->getRowArray();
    }

    public function get_planning_status_bg()
    {
        return $this->planning_status_bg;
    }

    protected $product_types = array(
        "finished_good" => "Finished Goods",
        // "semi_finished" => "Semi Finished",
        // "raw_material" => "Raw Materials",
    );

    public function get_product_types()
    {
        return $this->product_types;
    }

    public function get_product_links()
    {
        return $this->product_links;
    }

    public function get_all_warehouses()
    {
        $query = "SELECT warehouse_id,name FROM warehouses";
        $result = $this->db->query($query)->getResultArray();
        return $result;
    }

    protected $product_links = array(
        "finished_good" => "erp/crm/get_fetchfinishedgoods",
        // "semi_finished" => "erp/procurement/ajaxfetchsemifinished",
        // "raw_material" => "erp/procurement/ajaxfetchrawmaterials",
    );
    // protected $product_types = array(
    //     "finished_good" => "Finished Goods",
    //     "semi_finished" => "Semi Finished",
    //     "raw_material" => "Raw Materials",
    // );
    // public function get_product_types()
    // {
    //     return $this->product_types;
    // }
    // public function get_product_links()
    // {
    //     return $this->product_links;
    // }

    // protected $product_links = array(
    //     "finished_good" => "erp/crm/get_fetchfinishedgoods",
    //     "semi_finished" => "erp/procurement/ajaxfetchsemifinished",
    //     "raw_material" => "erp/procurement/ajaxfetchrawmaterials",
    // );
    public function get_dtconfig_planning()
    {
        $config = array(
            "columnNames" => ["sno", "product", "start date", "end date", "finished date", "stock", "status", "active"],
            "sortable" => [0, 0, 0, 0, 1, 0],
            "filters" => ["status"]
        );
        return json_encode($config);
    }

    public function get_planning_datatable()
    {
        $columns = array(
            "product" => "finished_goods.name",
            "status" => "planning.status",
        );
        $limit = $_GET['limit'] ?? "";
        $offset = $_GET['offset'] ?? "";
        $search = $_GET['search'] ?? "";

        $orderby = isset($_GET['orderby']) ? strtoupper($_GET['orderby']) : "";
        $ordercol = isset($_GET['ordercol']) ? $columns[$_GET['ordercol']] : "";

        $query = "SELECT planning.planning_id,finished_goods.name,planning.start_date,planning.end_date,planning.finished_date,planning.stock,planning.status FROM planning JOIN finished_goods ON planning.finished_good_id=finished_goods.finished_good_id";
        $count = "SELECT COUNT(planning_id) AS total FROM planning JOIN finished_goods ON planning.finished_good_id=finished_goods.finished_good_id";
        $where = false;

        $filter_2_col = isset($_GET['status']) ? $columns['status'] : "";
        $filter_2_val = $_GET['status'];

        if (!empty($filter_2_col) && $filter_2_val !== "") {
            if (!$where) {
                $query .= " WHERE " . $filter_2_col . "=" . $filter_2_val . " ";
                $count .= " WHERE " . $filter_2_col . "=" . $filter_2_val . " ";
                $where = true;
            } else {
                $query .= " AND " . $filter_2_col . "=" . $filter_2_val . " ";
                $count .= " AND " . $filter_2_col . "=" . $filter_2_val . " ";
            }
        }
        $this->logger->error($_GET);

        if (!empty($search)) {
            if (!$where) {
                $query .= " WHERE ( finished_goods.name LIKE '%" . $search . "%')";
                $count .= " WHERE ( finished_goods.name LIKE '%" . $search . "%')";
                $where = true;
            } else {
                $query .= " AND ( finished_goods.name LIKE '%" . $search . "%')";
                $count .= " AND ( finished_goods.name LIKE '%" . $search . "%')";
            }
        }
        if (!empty($ordercol) && !empty($orderby)) {
            $query .= " ORDER BY " . $ordercol . " " . $orderby;
        }
        $query .= " LIMIT $offset,$limit ";

        $result = $this->db->query($query)->getResultArray();
        // $this->logger->error($result);
        $m_result = array();
        $sno = $offset + 1;
        foreach ($result as $r) {
            $action = '<div class="dropdown tableAction">
            <a type="button" class="dropBtn HoverA "><i class="fa fa-ellipsis-v"></i></a>
            <div class="dropdown_container">
                <ul class="BB_UL flex">
                    <li><a href="' . url_to('erp.mrp.planningview', $r['planning_id']) . '" title="View" class="bg-warning"><i class="fa fa-eye"></i> </a></li>
                    <li><a href="' . url_to('erp.mrp.productplanningedit', $r['planning_id']) . '" title="Edit" class="bg-success"><i class="fa fa-pencil"></i> </a></li>'
                . ($r['status'] != 2 ? '<li><a href="' . url_to('erp.service.servicedelete', $r['planning_id']) . '" title="Delete" class="bg-danger del-confirm"><i class="fa fa-trash"></i> </a></li>' : '') .
                '</ul>
            </div>
        </div>';
            $status = "<span class='st " . $this->planning_status_bg[$r['status']] . "'>" . $this->planning_status[$r['status']] . " </span>";
            array_push(
                $m_result,
                array(
                    $r['planning_id'],
                    $sno,
                    $r['name'],
                    $r['start_date'],
                    $r['end_date'],
                    $r['finished_date'],
                    $r['stock'],
                    $status,
                    $action
                )
            );
            $sno++;
        }
        $response = array();
        $response['total_rows'] = $this->db->query($count)->getRow()->total;
        $response['data'] = $m_result;
        // $this->logger->error($response);
        return $response;
    }

    public function get_planning_datatable_export()
    {
        $columns = array(
            "product" => "finished_goods.name",
            "status" => "planning.status",
        );
        $search = $_GET['search'] ?? "";

        $orderby = isset($_GET['orderby']) ? strtoupper($_GET['orderby']) : "";
        $ordercol = isset($_GET['ordercol']) ? $columns[$_GET['ordercol']] : "";

        $query = "SELECT planning.planning_id,finished_goods.name,planning.start_date,planning.end_date,planning.finished_date,planning.stock,planning.status FROM planning JOIN finished_goods ON planning.finished_good_id=finished_goods.finished_good_id";
        $where = false;

        $filter_2_col = isset($_GET['status']) ? $columns['status'] : "";
        $filter_2_val = $_GET['status'];

        if (!empty($filter_2_col) && $filter_2_val !== "") {
            if (!$where) {
                $query .= " WHERE " . $filter_2_col . "=" . $filter_2_val . " ";
                $where = true;
            } else {
                $query .= " AND " . $filter_2_col . "=" . $filter_2_val . " ";
            }
        }
        $this->logger->error($_GET);

        if (!empty($search)) {
            if (!$where) {
                $query .= " WHERE ( finished_goods.name LIKE '%" . $search . "%')";
                $where = true;
            } else {
                $query .= " AND ( finished_goods.name LIKE '%" . $search . "%')";
            }
        }
        if (!empty($ordercol) && !empty($orderby)) {
            $query .= " ORDER BY " . $ordercol . " " . $orderby;
        }

        $result = $this->db->query($query)->getResultArray();
        // $this->logger->error($result);
        $m_result = array();
        $sno = 1;
        foreach ($result as $r) {
            $status = $this->planning_status[$r['status']];
            array_push(
                $m_result,
                array(
                    $sno,
                    $r['name'],
                    $r['start_date'],
                    $r['end_date'],
                    $r['finished_date'],
                    $r['stock'],
                    $status,
                )
            );
            $sno++;
        }
        return $m_result;
        ;
    }

    public function finishedGoodsProduct()
    {
        $query = "SELECT finished_good_id,name FROM `finished_goods`";
        $result = $this->db->query($query)->getResultArray();
        return $result;
    }

    public function insert_planning($post)
    {
        $inserted = false;
        $finished_good_id = $post["finished_good_id"];
        $stock = $post["stock"];
        // $status = $post["status"];
        $start_date = $post["start_date"];
        $end_date = $post["end_date"];
        $price_id = $post["price_id"];
        $finished_date = "0000-00-00";
        $created_by = get_user_id();


        $finished_date_timestamp = strtotime($finished_date);
        $query = "INSERT INTO planning(finished_good_id, start_date, end_date, finished_date, price_id, stock,created_by) VALUES (?, ?, ?, ?, ?, ?, ?)";
        $this->db->query($query, array($finished_good_id, $start_date, $end_date, date('Y-m-d', $finished_date_timestamp), $price_id, $stock, $created_by));

        $config['title'] = "Planning Insert";
        if (!empty($this->db->insertId())) {
            $inserted = true;
            $config['log_text'] = "[ Planning successfully created ]";
            $config['ref_link'] = url_to('erp.mrp.planningschedule');
            $this->session->setFlashdata("op_success", "Planning successfully created");
        } else {
            $config['log_text'] = "[ Planning failed to create ]";
            $config['ref_link'] = url_to('erp.mrp.planningschedule');
            $this->session->setFlashdata("op_error", "Planning failed to create");
        }
        log_activity($config);
        return $inserted;
    }

    public function update_planning($planning_id, $post)
    {
        $updated = false;
        $finished_good_id = $post["finished_good_id"];
        $start_date = $post["start_date"];
        $end_date = $post["end_date"];
        $finished_date = $post["finished_date"];
        $stock = $post["stock"];
        $status = $post["status"];
        $price_id = $post["price_id"];
        if ($status == 2) {
            echo "successfully";
            $query = "UPDATE planning SET finished_good_id=?,start_date=?,end_date=?,finished_date=?,stock=?,status=? WHERE planning_id=$planning_id";
            $queryStock = "INSERT INTO ";
            $this->db->query($query, array($finished_good_id, $start_date, $end_date, date('Y-m-d'), $stock, $status));
        } else {
            $finished_date_timestamp = strtotime($finished_date);
            $query = "UPDATE planning SET finished_good_id=?,start_date=?,end_date=?,finished_date=?,stock=?,price_id=?,status=? WHERE planning_id=$planning_id";
            $this->db->query($query, array($finished_good_id, $start_date, $end_date, date('Y-m-d', $finished_date_timestamp), $stock, $price_id, $status));
        }

        $config['title'] = "Planning Update";
        if ($this->db->affectedRows() > 0) {
            $updated = true;
            $config['log_text'] = "[ Planning successfully updated ]";
            $config['ref_link'] = url_to('erp.mrp.planningschedule');
            $this->session->setFlashdata("op_success", "Planning successfully updated");
        } else {
            $config['log_text'] = "[ Planning failed to update ]";
            $config['ref_link'] = url_to('erp.mrp.planningschedule');
            $this->session->setFlashdata("op_error", "Planning failed to update");
        }
        log_activity($config);
        return $updated;
    }

    public function get_planning_by_id($planning_id)
    {
        //old
        // $query = "SELECT planning.planning_id,planning.finished_good_id,planning.start_date,planning.end_date,planning.stock,planning.finished_date,planning.status,finished_goods.name FROM planning JOIN finished_goods ON planning.finished_good_id=finished_goods.finished_good_id WHERE planning_id=$planning_id";
        // $query = "SELECT planning.planning_id,planning.finished_good_id,planning.start_date,planning.end_date WHERE planning_id=$planning_id";
        $query = "SELECT price_list.name as price_name,price_list.amount as price_amount,planning.price_id,planning.planning_id,planning.finished_good_id,planning.start_date,planning.end_date,planning.stock,planning.finished_date,planning.status,finished_goods.name FROM planning
         JOIN finished_goods ON planning.finished_good_id=finished_goods.finished_good_id 
         LEFT JOIN price_list ON price_list.price_id=planning.price_id 
         WHERE planning_id=$planning_id";
        $result = $this->db->query($query)->getRow();
        return $result;
    }

    public function getStock($planning_id)
    {
        $query = "SELECT stock FROM planning WHERE planning_id=$planning_id";
        return $result = $this->db->query($query)->getRowArray();
    }

    public function getFinishedGoodProduct($planning_id)
    {
        $query = "SELECT planning.finished_good_id,CONCAT(finished_goods.name,'-',finished_goods.code)as product_name FROM planning JOIN finished_goods ON planning.finished_good_id=finished_goods.finished_good_id WHERE  planning_id=$planning_id";
        return $result = $this->db->query($query)->getResultArray();
    }

    public function insert_mrp_scheduling($planning_id)
    {
        $inserted = false;

        $sku = $_POST["sku"];
        $warehouse_id = $_POST["warehouse_id"];
        $related_to = $_POST["related_to"];
        $related_id = $_POST["related_id"];
        $price_id = $_POST["price_id"];
        $stock = $_POST["stock"];
        $bin_name = $_POST["bin_name"];
        $mfg_date = $_POST["mfg_date"];
        $batch_no = $_POST["batch_no"];
        $lot_no = $_POST["lot_no"];

        try {
            $this->db->transBegin();

            if (!empty($stock)) {
                $query1 = "INSERT INTO mrp_scheduling(sku, warehouse_id, related_to, related_id, price_id, stock, bin_name, mfg_date, batch_no, lot_no,planning_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?,?)";
                $this->db->query($query1, array($sku, $warehouse_id, $related_to, $related_id, $price_id, $stock, $bin_name, $mfg_date, $batch_no, $lot_no, $planning_id));

                $query2 = "UPDATE planning SET stock=? WHERE planning_id=?";
                $this->db->query($query2, array($stock, $planning_id));

                $query3 = "INSERT INTO stocks(related_to,related_id,warehouse_id,quantity,price_id) VALUE (?,?,?,?,?)";
            }

            $this->db->transCommit();
            $inserted = true;
        } catch (Exception $e) {
            $this->db->transRollback();
            log_message('error', $e->getMessage()); // Log the error
        }

        $config['title'] = "MRP Scheduling Insert";
        $config['log_text'] = $inserted ? "[ MRP Scheduling successfully created ]" : "[ MRP Scheduling failed to create ]";
        $config['ref_link'] = $inserted ? url_to('erp.mrp.planningview', $planning_id) : url_to('erp.mrp.planningschedule');

        // $this->session->setFlashdata($inserted ? "op_success" : "op_error", $inserted ? "MRP Scheduling successfully created" : "MRP Scheduling failed to create");
        if ($inserted) {
            $this->session->setFlashdata("op_success", "MRP Scheduling successfully created");
        } else {
            $this->session->setFlashdata("op_error", "MRP Scheduling failed to create");
        }
        log_activity($config);

        return $inserted;
    }

    public function update_mrp_scheduling($planning_id, $mrp_scheduling_id)
    {
        $inserted = false;

        $sku = $_POST["sku"];
        $warehouse_id = $_POST["warehouse_id"];
        $related_to = $_POST["related_to"];
        $related_id = $_POST["related_id"];
        $price_id = $_POST["price_id"];
        $stock = $_POST["stock"];
        $bin_name = $_POST["bin_name"];
        $mfg_date = $_POST["mfg_date"];
        $batch_no = $_POST["batch_no"];
        $lot_no = $_POST["lot_no"];

        try {
            $this->db->transBegin();
            //working continue............by monday
            if (!empty($stock)) {
                $query1 = "UPDATE mrp_scheduling SET sku = ? ,warehouse_id = ?, related_to = ?, related_id = ?, price_id = ?,bin_name = ?,mfg_date =?,batch_no =?,lot_no = ?,stock = ? WHERE planning_id = ? AND mrp_scheduling_id = ? ";
                $this->db->query($query1, [$sku, $warehouse_id, $related_to, $related_id, $price_id, $bin_name, $mfg_date, $batch_no, $lot_no, $stock, $planning_id, $mrp_scheduling_id]);

                $query2 = "UPDATE planning SET stock=? WHERE planning_id=?";
                $this->db->query($query2, array($stock, $planning_id));

                $query3 = "INSERT INTO stocks(related_to,related_id,warehouse_id,quantity,price_id) VALUE (?,?,?,?,?)";
            }

            $this->db->transCommit();
            $inserted = true;
        } catch (Exception $e) {
            $this->db->transRollback();
            log_message('error', $e->getMessage()); // Log the error
        }

        $config['title'] = "MRP Scheduling Insert";
        $config['log_text'] = $inserted ? "[ MRP Scheduling successfully created ]" : "[ MRP Scheduling failed to create ]";
        $config['ref_link'] = $inserted ? url_to('erp.mrp.planningview', $planning_id) : url_to('erp.mrp.planningschedule');

        // $this->session->setFlashdata($inserted ? "op_success" : "op_error", $inserted ? "MRP Scheduling successfully created" : "MRP Scheduling failed to create");
        log_activity($config);
        return $inserted;
    }

    public function get_finishedgood_id($planning_id)
    {
        return $this->builder()->where("planning_id", $planning_id)->get()->getRowArray();
    }

    public function get_mrp_scheduling_by_id($planning_id, $mrp_scheduling_id)
    {
        $query = "SELECT mrp_scheduling_id,sku,related_to,related_id,warehouse_id,price_id,bin_name,mfg_date,batch_no,lot_no,stock FROM mrp_scheduling WHERE planning_id=$planning_id and mrp_scheduling_id = $mrp_scheduling_id ";
        $result = $this->db->query($query)->getRow();
        return $result;
    }

    public function get_dtconfig_mrpscheduling()
    {
        $config = array(
            "columnNames" => ["sno", "sku", "product", "warehouse", "manufactured date", "price list", "stock", "status", "action"],
            "sortable" => [0, 0, 0, 0, 0, 1, 0, 0, 0],
        );
        return json_encode($config);
    }

    public function getMrpSchedulingDatatable($planningId)
    {
        $columns = array(
            "finished_goods_name" => "finished_goods.name",
        );

        $limit = $_GET['limit'];
        $offset = $_GET['offset'];
        $search = $_GET['search'] ?? "";
        $orderby = isset($_GET['orderby']) ? strtoupper($_GET['orderby']) : "";
        $ordercol = isset($_GET['ordercol']) ? $columns[$_GET['ordercol']] : "";

        $query = "SELECT mrp_scheduling_id,mrp_scheduling.stock,sku,related_to,mfg_date,finished_goods.name as finished_goods_name,warehouses.name as warehouse_name,price_list.amount,price_list.tax1,price_list.tax2,
        planning.status FROM mrp_scheduling JOIN finished_goods ON finished_goods.finished_good_id = mrp_scheduling.related_id JOIN warehouses ON warehouses.warehouse_id = mrp_scheduling.warehouse_id JOIN price_list ON price_list.price_id = mrp_scheduling.price_id JOIN planning ON planning.planning_id = mrp_scheduling.planning_id WHERE mrp_scheduling.planning_id = $planningId";
        $count = "SELECT COUNT(mrp_scheduling_id) AS total FROM mrp_scheduling WHERE planning_id=$planningId";
        $where = true;

        if (!empty($search)) {
            if (!$where) {
                $query .= " WHERE ( warehouse_name LIKE '%" . $search . "%') ";
                $count .= " WHERE ( warehouse_name LIKE '%" . $search . "%') ";
                $where = true;
            } else {
                $query .= " AND (warehouse_name LIKE '%" . $search . "%') ";
                $count .= " AND (warehouse_name LIKE '%" . $search . "%') ";
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
                                <li><a href="' . url_to('erp.mrp.editmrpstock', $planningId, $r['mrp_scheduling_id']) . '" data-ajax-url="' . url_to('erp.mrp.editmrpstock', $planningId, $r['mrp_scheduling_id']) . '" title="Edit" class="bg-success modalBtn"><i class="fa fa-pencil"></i> </a></li>
                                <li><a href="' . url_to('erp.service.schedulingdelete', $planningId, $r['mrp_scheduling_id']) . '" title="Delete" class="bg-danger del-confirm"><i class="fa fa-trash"></i> </a></li>
                            </ul>
                        </div>
                    </div>';
            $status = "<span class='st " . $this->planning_status_bg[$r['status']] . "'>" . $this->planning_status[$r['status']] . " </span>";
            array_push(

                $m_result,
                array(
                    $r['mrp_scheduling_id'],
                    $sno,
                    $r['sku'],
                    $r['finished_goods_name'],
                    $r['warehouse_name'],
                    $r['mfg_date'],
                    $r['amount'],
                    $r['stock'],
                    $status,
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

    public function get_active_price_list()
    {
        $query = "select * from price_list where active = 1";
        $result = $this->db->query($query)->getResultArray();
        return $result;

    }

    public function year_base_production($year = '')
    {
        if ($year == '') {
            $year = date('Y'); // Ensure to use the current year as the default
        }

        // Use date range for filtering by year
        $start_of_year = "$year-01-01"; // Start of the year
        $end_of_year = "$year-12-31";  // End of the year

        $query = "SELECT 
                      finished_goods.name AS finished_good_name, 
                      planning.start_date,
                      planning.stock 
                  FROM 
                      planning 
                  JOIN 
                      finished_goods 
                  ON 
                      planning.finished_good_id = finished_goods.finished_good_id 
                  WHERE 
                      planning.start_date >= '$start_of_year' 
                      AND planning.start_date <= '$end_of_year'";

        $result = $this->db->query($query)->getResultArray();
        return $result;
    }











}
