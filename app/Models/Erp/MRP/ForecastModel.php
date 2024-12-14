<?php

namespace App\Models\ERP\MRP;

use CodeIgniter\Model;

class ForecastModel extends Model
{
    protected $table = 'mrp_dataset_forecast';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = [
        'timestamp',
        'related_to',
        'related_id',
        'quantity',
        'current_stocks_on_inventory',
    ];

    protected $product_types = array(
        "finished_good" => "Finished Goods",
        // "semi_finished" => "Semi Finished",
        // "raw_material" => "Raw Materials",
    );

    public function get_dtconfig_forecast()
    {
        $config = array(
            "columnNames" => ["sno", "timestamp", "on hand stocks", "requirements", "expected on demand", "to be scheduled"],
            "sortable" => [1, 0, 0, 0, 0, 0],
        );
        return json_encode($config);
    }

    public function GetOnHandStocks()
    {

    }

    public function GetProductType()
    {
        return $this->product_types;
    }

    public function GetDataWithTimestamp()
    {
        $startDate = $_POST['fromDate'];
        $endDate = $_POST['toDate'];
        $related_id = $_POST['related_id'];
        $prevYearStartDate = date('Y-m-d H:i:s', strtotime('-1 year', strtotime($startDate)));

        // Get the previous year for end date
        $prevYearEndDate = date('Y-m-d H:i:s', strtotime('-1 year', strtotime($endDate)));
        return $this->builder()->select('timestamp,quantity')
            ->where('related_id', $related_id)
            ->where('timestamp >=', $prevYearStartDate)
            ->where('timestamp <=', $prevYearEndDate)->get()
            ->getResultArray();
    }

    public function GetCurrentStock($related_to, $related_id)
    {
        $query = "SELECT SUM(stock_entry.qty) as qty 
                  FROM stock_entry 
                  JOIN stocks ON stock_entry.stock_id = stocks.stock_id 
                  WHERE stocks.related_to = ? 
                  AND stocks.related_id = ?";
        return $this->db->query($query, [$related_to, $related_id])->getRow()->qty ?? 0;
    }


    public function GetFutureStocks($date)
    {
        $query = "SELECT SUM(stock) as qty FROM planning WHERE end_date <=" . date(strtotime('+1 year', strtotime($date)));
        return $this->db->query($query)->getRow()->qty;
    }



    public function GetRequiredQty($timestamp)
    {
        $startDate = $_POST['fromDate'];
        $endDate = $_POST['toDate'];

        $query = "SELECT SUM(quantity) as qty FROM sale_order_items 
        WHERE related_to = '" . $_POST['related_to'] . "' 
        AND related_id = " . $_POST['related_id'] . "
        AND timestamp BETWEEN '" . $startDate . "' AND '" . $endDate . "'";
        return $this->db->query($query)->getRow()->qty;
    }
}
