<?php

namespace App\Models\Erp\MRP;
use CodeIgniter\Model;

class ScrapModel extends Model
{
    protected $table = 'scrap';
    protected $primaryKey = 'scrap_id';
    protected $protectFields = true;
    protected $allowedFields = [
        'item_code',
        'item_name',
        'quantity',
        'rate',
    ];

    protected $db;

    public function __construct()
    {
        $this->session = \Config\Services::session();
        $this->db = \Config\Database::connect();
    }
    public function get_bom_scrap()
    {
        return $this->builder()->select('scrap_id, item_name,item_code, quantity, rate,')->get()->getResultArray();
    }

    public function get_dtconfig_bom_scrap()
    {
        $config = array(
            "columnNames" => ["sno", "item code", "item name ", "qty", "rate", "action"],
            "sortable" => [0, 1, 1, 0, 0, 0],
        );
        return json_encode($config);
    }


    public function insert_update_scrap($scrap_id, $data)
    {

        if (empty($scrap_id)) {
            $this->insert_source($data);
        } else {
            $this->update_source($scrap_id, $data);
        }
    }



    public function get_datatable_scrap()
    {
        $columns = array(
            "item code" => "item_code",
            "item name" => "item_name",
            "qty" => "quantity",
            "rate" => "rate",
        );

        // Validate limit and offset
        $limit = isset($_GET['limit']) && is_numeric($_GET['limit']) ? (int) $_GET['limit'] : 10;
        $offset = isset($_GET['offset']) && is_numeric($_GET['offset']) ? (int) $_GET['offset'] : 0;

        $orderby = isset($_GET['orderby']) ? strtoupper($_GET['orderby']) : "";
        $ordercol = isset($_GET['ordercol']) && isset($columns[$_GET['ordercol']]) ? $columns[$_GET['ordercol']] : "";

        // Build main query
        $query = "SELECT scrap_id, item_id, item_name, qty, rate FROM scrap";

        // Add ORDER BY clause if valid columns and order are provided
        if (!empty($ordercol) && in_array($orderby, ['ASC', 'DESC'])) {
            $query .= " ORDER BY " . $ordercol . " " . $orderby;
        }

        // Add LIMIT and OFFSET
        $query .= " LIMIT :offset:, :limit:";

        // Fetch data
        $result = $this->db->query($query, ['offset' => $offset, 'limit' => $limit])->getResultArray();

        // Build total count query
        $countQuery = "SELECT COUNT(scrap_id) AS total FROM scrap";
        $totalRows = $this->db->query($countQuery)->getRow()->total;

        // Prepare results
        $m_result = [];
        $sno = $offset + 1;

        foreach ($result as $r) {
            $action = '<div class="dropdown tableAction">
                    <a type="button" class="dropBtn HoverA"><i class="fa fa-ellipsis-v"></i></a>
                    <div class="dropdown_container">
                        <ul class="BB_UL flex">
                            <li>
                                <a href="#" data-ajax-url="' . url_to('erp.supplier.sources_edit', $r['scrap_id']) . '" 
                                   title="Edit" class="bg-success modalBtn">
                                   <i class="fa fa-pencil"></i>
                                </a>
                            </li>
                            <li>
                                <a href="' . url_to('erp.supplier.sources_delete', $r['scrap_id']) . '" 
                                   title="Delete" class="bg-danger del-confirm">
                                   <i class="fa fa-trash"></i>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>';

            $m_result[] = [
                $r['scrap_id'],
                $sno,
                $r['item_code'],
                $r['item_name'],
                $r['qty'],
                $r['rate'],
                $action
            ];
            $sno++;
        }

        // Return response
        return [
            'total_rows' => $totalRows,
            'data' => $m_result
        ];
    }






}