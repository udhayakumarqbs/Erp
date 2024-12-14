<?php

namespace App\Models\Erp\Sale;

use CodeIgniter\Model;

class DispatchModel extends Model
{
    protected $table            = 'dispatch';
    protected $primaryKey       = 'dispatch_id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'order_code',
        'warehouse',
        'delivery_date',
        'customer',
        'suppiler_id',
        'order_id',
        'cust_id',
        'status',
        'pick_list_id',
        'description'
    ];


    // Dates
    protected $useTimestamps = false;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // protected $logger;
    // protected $db;
    // public function __construct()
    // {
    //     $this->logger = \Config\Services::logger();
    //     $this->db = \Config\Database::connect();
    // }

    protected $dispatch_status = array(
        0 => "Started",
        1 => "Picked",
        2 => "On Delivery",
        3 => "Delivered"
    );

    protected $dispatch_status_bg = array(
        0 => "st_dark",
        1 => "st_violet",
        2 => "st_success",
        3 => "st_danger"
    );

    public function get_dtconfig_m_dispatch()
    {
        $config = array(
            "columnNames" => ["sno", "order id", "customer", "delivery date", "description", "status"],
            "sortable" => [0, 1, 0, 1, 0, 0, 0],
            "filters" => ["status"]
        );
        return json_encode($config);
    }
    
    
    public function get_dtconfig_warehouse_dispatch()
    {
        $config = array(
            "columnNames" => ["sno", "order id", "customer", "delivery date", "description", "status", "action"],
            "sortable" => [0, 1, 0, 1, 0, 0, 0],
            "filters" => ["status"]
        );
        return json_encode($config);
    }


    public function get_dispatch_status()
    {
        return $this->dispatch_status;
    }

    public function get_dispatch_status_bg()
    {
        return $this->dispatch_status_bg;
    }


    public function get_Sale_Dispatch_Datatable($limit, $offset, $search, $orderby, $ordercolPost, $status)
    {
        $columns = [
            "order_code" => "order_code",
            "delivery_date" => "delivery_date",
            "status" => "status"
        ];


        $ordercol = $ordercolPost ? $columns[$ordercolPost] : "";
        $query = "SELECT dispatch_id, order_code, customer, delivery_date, description, status FROM dispatch " ;
        $count = "SELECT COUNT(dispatch_id) AS total FROM dispatch ";
        $where = false;

        $filter_1_col = isset($_GET['status']) ? $columns['status'] : "";
        $filter_1_val = $_GET['status'];

        if (!empty($filter_1_col) && $filter_1_val !== "") {
            $query .= " WHERE " . $filter_1_col . "=" . $filter_1_val . " ";
            $count .= " WHERE " . $filter_1_col . "=" . $filter_1_val . " ";
            $where = true;
        }

        if (!empty($search)) {
            if (!$where) {
                $query .= " WHERE ( order_code LIKE '%" . $search . "%' OR customer LIKE '%" . $search . "%' ) ";
                $count .= " WHERE ( order_code LIKE '%" . $search . "%' OR customer LIKE '%" . $search . "%' ) ";
                $where = true;
            } else {
                $query .= " AND ( order_code LIKE '%" . $search . "%' OR customer LIKE '%" . $search . "%' ) ";
                $count .= " AND ( order_code LIKE '%" . $search . "%' OR customer LIKE '%" . $search . "%' ) ";
            }
        }

        if (!empty($ordercol) && !empty($orderby)) {
            $query .= " ORDER BY " . $ordercol . " " . $orderby;
        }

        $query .= " LIMIT $offset,$limit ";
        $result = $this->db->query($query)->getResultArray();
        $m_result = [];
        $sno = $offset + 1;

        foreach ($result as $r) {
            $action = '<div class="dropdown tableAction">
                            <a type="button" class="dropBtn HoverA "><i class="fa fa-ellipsis-v"></i></a>
                            <div class="dropdown_container">
                                <ul class="BB_UL flex">
                                <li><a href="' . url_to('erp.dispatch.delete', $r['dispatch_id']) . '" title="Delete" class="bg-danger del-confirm"><i class="fa fa-trash"></i> </a></li>
                                </ul>
                            </div>
                        </div>';

            $status = "<span class='st " . $this->dispatch_status_bg[$r['status']] . "' >" . $this->dispatch_status[$r['status']] . "</span>";

            $m_result[] = [
                $r['dispatch_id'],
                $sno,
                $r['order_code'],
                $r['customer'],
                $r['delivery_date'],
                $r['description'],
                $status
            ];

            $sno++;
        }

        $response = [
            'total_rows' => $this->db->query($count)->getRow()->total,
            'data' => $m_result
        ];

        return $response;
    }


    public function get_warehouse_dispatch_Datatable($limit, $offset, $search, $orderby, $ordercolPost, $status)
    {
        $columns = [
            "order_code" => "order_code",
            "delivery_date" => "delivery_date",
            "status" => "status"
        ];


        $ordercol = $ordercolPost ? $columns[$ordercolPost] : "";
        $query = "SELECT dispatch_id, order_code, customer, delivery_date, description, status FROM dispatch " ;
        $count = "SELECT COUNT(dispatch_id) AS total FROM dispatch ";
        $where = false;

        $filter_1_col = isset($_GET['status']) ? $columns['status'] : "";
        $filter_1_val = $_GET['status'];

        if (!empty($filter_1_col) && $filter_1_val !== "") {
            $query .= " WHERE " . $filter_1_col . "=" . $filter_1_val . " ";
            $count .= " WHERE " . $filter_1_col . "=" . $filter_1_val . " ";
            $where = true;
        }

        if (!empty($search)) {
            if (!$where) {
                $query .= " WHERE ( order_code LIKE '%" . $search . "%' OR customer LIKE '%" . $search . "%' ) ";
                $count .= " WHERE ( order_code LIKE '%" . $search . "%' OR customer LIKE '%" . $search . "%' ) ";
                $where = true;
            } else {
                $query .= " AND ( order_code LIKE '%" . $search . "%' OR customer LIKE '%" . $search . "%' ) ";
                $count .= " AND ( order_code LIKE '%" . $search . "%' OR customer LIKE '%" . $search . "%' ) ";
            }
        }

        if (!empty($ordercol) && !empty($orderby)) {
            $query .= " ORDER BY " . $ordercol . " " . $orderby;
        }

        $query .= " LIMIT $offset,$limit ";
        $result = $this->db->query($query)->getResultArray();
        $m_result = [];
        $sno = $offset + 1;

        foreach ($result as $r) {
            $action = '<div class="dropdown tableAction">
                            <a type="button" class="dropBtn HoverA "><i class="fa fa-ellipsis-v"></i></a>
                            <div class="dropdown_container">
                                <ul class="BB_UL flex">
                                <li><a href="' . url_to('erp.dispatch.edit', $r['dispatch_id']) . '" title="Edit" class="bg-success"><i class="fa fa-pencil"></i> </a></li>
                                <li><a href="' . url_to('erp.dispatch.delete', $r['dispatch_id']) . '" title="Delete" class="bg-danger del-confirm"><i class="fa fa-trash"></i> </a></li>
                                </ul>
                            </div>
                        </div>';

            $status = "<span class='st " . $this->dispatch_status_bg[$r['status']] . "' >" . $this->dispatch_status[$r['status']] . "</span>";

            $m_result[] = [
                $r['dispatch_id'],
                $sno,
                $r['order_code'],
                $r['customer'],
                $r['delivery_date'],
                $r['description'],
                $status,
                $action
            ];

            $sno++;
        }

        $response = [
            'total_rows' => $this->db->query($count)->getRow()->total,
            'data' => $m_result
        ];

        return $response;
    }


    //ADD Dispatch

    public function insertdispatch($data)
    {
        $inserted = false;
        $dispatchAdd = $this->db->table('dispatch');
        // $this->insert($data);
        $dispatchAdd->insert($data);
        $dispatchId = $this->insertID();

        $config = ['title' => 'Dispatch Insert'];

        if (!empty($dispatchId)) {
            $inserted = true;
            $config['log_text'] = "[ Dispatch successfully created ]";
            $config['ref_link'] = "sale/order_add_dispatch/";
            session()->setFlashdata("op_success", "Dispatch successfully created");
        } else {
            $config['log_text'] = "[ Dispatch failed to create ]";
            $config['ref_link'] = 'sale/order_add_dispatch/';
            session()->setFlashdata("op_error", "Dispatch failed to create");
        }

        log_activity($config);
        return $inserted;
    }

    //order Dispatch Export

    public function get_dispatch_order_export($type, $limit, $offset, $search, $orderby, $ordercolPost, $status)
    {
        $columns = [
            "code" => "code",
            "name" => "name",
            "bought_date" => "bought_date",
            "status" => "status"
        ];

        // $limit = $this->request->getGet('limit');
        // $offset = $this->request->getGet('offset');
        // $search = $this->request->getGet('search') ?? "";
        // $orderby = strtoupper($this->request->getGet('orderby')) ?? "";
        $ordercol = $ordercolPost ? $columns[$ordercolPost] : "";

        $query = "";

        if ($type == "pdf") {
            $query = "SELECT order_code,customer,delivery_date,description,
                CASE ";
            foreach ($this->dispatch_status as $key => $value) {
                $query .= " WHEN status=$key THEN '$value' ";
            }
            $query .= " END AS status FROM dispatch ";
        } else {
            $query = "SELECT order_code,customer,delivery_date,description,
                CASE ";
            foreach ($this->dispatch_status as $key => $value) {
                $query .= " WHEN status=$key THEN '$value' ";
            }
            $query .= " END AS status, description FROM dispatch ";
        }

        $where = false;

        // $filter_1_col = $status ? $columns['status'] : "";
        // $filter_1_val = $status;

        // if ($filter_1_col !== "" && null !== $filter_1_val) {
        //     $query .= " WHERE " . $filter_1_col . "=" . $filter_1_val . " ";
        //     $where = true;
        // }

        $filter_1_col=isset($_GET['status'])?$columns['status']:"";
        $filter_1_val=$_GET['status'];

        if(!empty($filter_1_col) && $filter_1_val!=="" ){
            $query.=" WHERE ".$filter_1_col."=".$filter_1_val." ";
            //$count.=" WHERE ".$filter_1_col."=".$filter_1_val." ";
            $where=true;
        }

        if (!empty($search)) {
            if (!$where) {
                $query .= " WHERE ( name LIKE '%" . $search . "%' OR code LIKE '%" . $search . "%' ) ";
                $where = true;
            } else {
                $query .= " AND ( name LIKE '%" . $search . "%' OR code LIKE '%" . $search . "%' ) ";
            }
        }

        if (!empty($ordercol) && !empty($orderby)) {
            $query .= " ORDER BY " . $ordercol . " " . $orderby;
        }

        $query .= " LIMIT $offset,$limit ";
        $result = $this->db->query($query)->getResultArray();
        return $result;
    }

   

    public function get_dispatch_warehouse_export($type, $limit, $offset, $search, $orderby, $ordercolPost, $status)
    {
        $columns = [
            "code" => "code",
            "name" => "name",
            "bought_date" => "bought_date",
            "status" => "status"
        ];

        // $limit = $this->request->getGet('limit');
        // $offset = $this->request->getGet('offset');
        // $search = $this->request->getGet('search') ?? "";
        // $orderby = strtoupper($this->request->getGet('orderby')) ?? "";
        $ordercol = $ordercolPost ? $columns[$ordercolPost] : "";

        $query = "";

        if ($type == "pdf") {
            $query = "SELECT order_code,customer,delivery_date,description,
                CASE ";
            foreach ($this->dispatch_status as $key => $value) {
                $query .= " WHEN status=$key THEN '$value' ";
            }
            $query .= " END AS status FROM dispatch ";
        } else {
            $query = "SELECT order_code,customer,delivery_date,description,
                CASE ";
            foreach ($this->dispatch_status as $key => $value) {
                $query .= " WHEN status=$key THEN '$value' ";
            }
            $query .= " END AS status, description FROM dispatch ";
        }

        $where = false;

        // $filter_1_col = $status ? $columns['status'] : "";
        // $filter_1_val = $status;

        // if ($filter_1_col !== "" && null !== $filter_1_val) {
        //     $query .= " WHERE " . $filter_1_col . "=" . $filter_1_val . " ";
        //     $where = true;
        // }

        $filter_1_col=isset($_GET['status'])?$columns['status']:"";
        $filter_1_val=$_GET['status'];

        if(!empty($filter_1_col) && $filter_1_val!=="" ){
            $query.=" WHERE ".$filter_1_col."=".$filter_1_val." ";
            //$count.=" WHERE ".$filter_1_col."=".$filter_1_val." ";
            $where=true;
        }

        if (!empty($search)) {
            if (!$where) {
                $query .= " WHERE ( name LIKE '%" . $search . "%' OR code LIKE '%" . $search . "%' ) ";
                $where = true;
            } else {
                $query .= " AND ( name LIKE '%" . $search . "%' OR code LIKE '%" . $search . "%' ) ";
            }
        }

        if (!empty($ordercol) && !empty($orderby)) {
            $query .= " ORDER BY " . $ordercol . " " . $orderby;
        }

        $query .= " LIMIT $offset,$limit ";
        $result = $this->db->query($query)->getResultArray();
        return $result;
    }

      // Delete Dispatch
      public function Delete_Sale_Dispatch($dispatch_id)
      {
          $deleted = false;
  
          // Retrieve Dispatch details before deletion for logging purposes
          $dispatch = $this->get_dispatch_by_id($dispatch_id);
  
          // Delete the Dispatch
          $this->builder()->where('dispatch_id', $dispatch_id)->delete();
  
          if ($this->db->affectedRows() > 0) {
              $deleted = true;
  
              // Log the deletion
              $config = [
                  'title' => 'Dispatch Deletion',
                  'log_text' => "[ Dispatch successfully deleted ]",
                  'ref_link' => 'erp.dispatch.delete' . $dispatch_id,
                  'additional_info' => json_encode($dispatch), // Log Dispatch details before deletion
              ];
              log_activity($config);
          } else {
              $config = [
                  'title' => 'Dispatch Deletion',
                  'log_text' => "[ Dispatch failed to delete ]",
                  'ref_link' => 'erp.dispatch.delete' . $dispatch_id,
              ];
              log_activity($config);
          }
  
          return $deleted;
      }

      public function get_dispatch_by_id($dispatch_id)
      {
          return $this->builder()->select()->where('dispatch_id', $dispatch_id)->get()->getRow();
      }


        // Edit / Update
    public function dispatch_edit($dispatch_id, $data)
    {
        $updated = false;
        $logger = \Config\Services::logger();
        $logger->error($data);
        $config['title'] = "Dispatch Update";
        if ($this->builder()->where('dispatch_id', $dispatch_id)->update($data)) {
            $updated = true;
            $config['log_text'] = "[ Dispatch successfully updated ]";
            $config['ref_link'] = 'erp.dispatch.edit' . $dispatch_id;
            session()->setFlashdata("op_success", "Dispatch successfully updated");
        } else {
            $config['log_text'] = "[ Dispatch failed to update ]";
            $config['ref_link'] = 'erp.dispatch.edit' . $dispatch_id;
            session()->setFlashdata("op_error", "Dispatch failed to update");
        }

        log_activity($config);

        return $updated;
    }

  
}

