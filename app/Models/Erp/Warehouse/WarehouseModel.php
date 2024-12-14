<?php

namespace App\Models\Erp\Warehouse;

use CodeIgniter\Model;

class WarehouseModel extends Model
{
    protected $table            = 'warehouses';
    protected $primaryKey       = 'warehouse_id  ';
    protected $useAutoIncrement = true;
    protected $allowedFields = [
        'name',
        'address',
        'city',
        'state',
        'country',
        'zipcode',
        'has_bins',
        'description',
        'aisle_count',
        'racks_per_aisle',
        'shelf_per_rack',
        'bins_per_shelf'
    ];
    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    protected $product_types = array(
        "finished_good" => "Finished Goods",
        "semi_finished" => "Semi Finished",
        "raw_material" => "Raw Materials",
    );
    //
    public function get_product_types()
    {
        return $this->product_types;
    }

    public function insert_warehouse($name, $address, $city, $state, $country, $zipcode, $has_bins, $aisle_count, $racks_per_aisle, $shelf_per_rack, $bins_per_shelf, $description)
    {
        $data = [
            'name' => $name,
            'address' => $address,
            'city' => $city,
            'state' => $state,
            'country' => $country,
            'zipcode' => $zipcode,
            'has_bins' => $has_bins,
            'description' => $description,
            // Add other fields here
        ];

        if (!empty($has_bins)) {
            $data['aisle_count'] = $aisle_count;
            $data['racks_per_aisle'] = $racks_per_aisle;
            $data['shelf_per_rack'] = $shelf_per_rack;
            $data['bins_per_shelf'] = $bins_per_shelf;
        }

        $this->builder()->insert($data);

        $config['title'] = "Warehouse Insert";
        if (!empty($this->db->insertID())) {
            $config['log_text'] = "[ Warehouse successfully created]";
            $config['ref_link'] = 'erp/warehouse/warehouses';
            session()->setFlashdata("op_success", "Warehouse successfully created");
        } else {
            $config['log_text'] = "[ Warehouse failed to create ]";
            $config['ref_link'] = 'erp/warehouse/warehouses';
            session()->setFlashdata("op_error", "Warehouse failed to create");
        }
        log_activity($config);
    }

    // Get All Warehouses ID
    public function get_all_warehouses()
    {
        $query = "SELECT warehouse_id,name FROM warehouses";
        $result = $this->db->query($query)->getResultArray();
        return $result;
    }

    public function get_dtconfig_warehouses()
    {
        $config = array(
            "columnNames" => ["sno", "name", "address", "city", "state", "country", "zipcode", "has bins", "action"],
            "sortable" => [0, 1, 0, 0, 0, 0, 0, 0, 0]
        );
        return json_encode($config);
    }

    public function getWarehousesDatatable($limit, $offset, $search, $orderby, $ordercol)
    {
        $columns = [
            "name" => "name",
        ];


        $builder = $this->db->table('warehouses');
        $builder->select('warehouse_id, name, address, city, state, country, zipcode, has_bins');

        if (!empty($search)) {
            $builder->groupStart()
                ->like('name', $search)
                ->orLike('city', $search)
                ->orLike('state', $search)
                ->groupEnd();
        }

        if (!empty($ordercol) && !empty($orderby)) {
            $builder->orderBy($ordercol, $orderby);
        }

        $builder->limit($limit, $offset);

        // Custom expression for has_bins
        $builder->select('(CASE WHEN has_bins=1 THEN "Yes" ELSE "No" END) AS has_bins', false);

        $result = $builder->get()->getResultArray();

        $m_result = [];
        $sno = $offset + 1;

        foreach ($result as $r) {
            $action = '<div class="dropdown tableAction">
                        <a type="button" class="dropBtn HoverA "><i class="fa fa-ellipsis-v"></i></a>
                        <div class="dropdown_container">
                            <ul class="BB_UL flex">
                                <li><a href="' . url_to('erp.warehouse.edit', $r['warehouse_id']) . '" title="Edit" class="bg-success modalBtn"><i class="fa fa-pencil"></i> </a></li>
                                <li><a href="' . url_to('erp.warehouse.delete', $r['warehouse_id']) . '" title="Delete" class="bg-danger del-confirm"><i class="fa fa-trash"></i> </a></li>
                            </ul>
                        </div>
                    </div>';

            array_push(
                $m_result,
                [
                    $r['warehouse_id'],
                    $sno,
                    $r['name'],
                    $r['address'],
                    $r['city'],
                    $r['state'],
                    $r['country'],
                    $r['zipcode'],
                    $r['has_bins'],
                    $action,
                ]
            );

            $sno++;
        }

        $response = [];
        $response['total_rows'] = $this->db->table('warehouses')->countAllResults();
        $response['data'] = $m_result;

        return $response;
    }

    public function update_warehouse($warehouse_id, $name, $address, $city, $state, $country, $zipcode, $has_bins, $aisle_count, $racks_per_aisle, $shelf_per_rack, $bins_per_shelf, $description)
    {
        $updated = false;

        $query = "UPDATE warehouses SET name=?, address=?, city=?, state=?, country=?, zipcode=?, description=?, has_bins=?";
        $queryParams = [$name, $address, $city, $state, $country, $zipcode, $description, $has_bins];

        if (!empty($has_bins)) {
            $query .= ", aisle_count=?, racks_per_aisle=?, shelf_per_rack=?, bins_per_shelf=?";
            $queryParams = array_merge($queryParams, [$aisle_count, $racks_per_aisle, $shelf_per_rack, $bins_per_shelf]);
        }

        $query .= " WHERE warehouse_id = ?";

        $queryParams[] = $warehouse_id;

        $this->db->query($query, $queryParams);

        $config['title'] = "Warehouse Update";

        if ($this->db->affectedRows() > 0) {
            $updated = true;
            $config['log_text'] = "[ Warehouse successfully updated ]";
            $config['ref_link'] = 'erp/warehouse/warehouses';
            session()->setFlashdata("op_success", "Warehouse successfully updated");
        } else {
            $config['log_text'] = "[ Warehouse failed to update ]";
            $config['ref_link'] = 'erp/warehouse/warehouses';
            session()->setFlashdata("op_error", "Warehouse failed to update");
        }

        log_activity($config);

        return $updated;
    }



    // Get Warehouse By Id
    public function get_warehouse_by_id($warehouse_id)
    {
        return $this->builder()->select()->where('warehouse_id', $warehouse_id)->get()->getRow();
    }


    public function delete_warehouse($warehouse_id)
    {
        $deleted = false;

        // Retrieve equipment details before deletion for logging purposes
        $warehouse = $this->get_warehouse_by_id($warehouse_id);

        // Delete the equipment
        $this->builder()->where('warehouse_id', $warehouse_id)->delete();

        if ($this->db->affectedRows() > 0) {
            $deleted = true;

            // Log the deletion
            $config = [
                'title' => 'warehouse Deletion',
                'log_text' => "[ Warehouse successfully deleted ]",
                'ref_link' => 'erp.warehouse.delete' . $warehouse_id,
                'additional_info' => json_encode($warehouse), // Log warehouse details before deletion
            ];
            log_activity($config);
        } else {
            $config = [
                'title' => 'Warehouse Deletion',
                'log_text' => "[ Warehouse failed to delete ]",
                'ref_link' => '' . $warehouse_id,
            ];
            log_activity($config);
        }

        return $deleted;
    }

    public function get_warehouse_export($type)
    {
        $columns = array(
            "name" => "name",
        );
        $limit = $_GET['limit'];
        $offset = $_GET['offset'];
        $search = $_GET['search'] ?? "";
        $orderby = isset($_GET['orderby']) ? strtoupper($_GET['orderby']) : "";
        $ordercol = isset($_GET['ordercol']) ? $columns[$_GET['ordercol']] : "";

        $query = "";
        if ($type == "pdf") {
            $query = "SELECT name,address,city,state,country,zipcode,CASE WHEN has_bins=1 THEN 'Yes' ELSE 'No' END AS has_bins FROM warehouses ";
        } else {
            $query = "SELECT name,address,city,state,country,zipcode,CASE WHEN has_bins=1 THEN 'Yes' ELSE 'No' END AS has_bins,aisle_count,racks_per_aisle,shelf_per_rack,bins_per_shelf FROM warehouses ";
        }
        $where = false;
        if (!empty($search)) {
            if (!$where) {
                $query .= " WHERE ( name LIKE '%" . $search . "%' OR city LIKE '%" . $search . "%' OR state LIKE '%" . $search . "%' ) ";
                $where = true;
            } else {
                $query .= " AND ( name LIKE '%" . $search . "%' OR city LIKE '%" . $search . "%' OR state LIKE '%" . $search . "%' ) ";
            }
        }
        if (!empty($ordercol) && !empty($orderby)) {
            $query .= " ORDER BY " . $ordercol . " " . $orderby;
        }
        $result = $this->db->query($query)->getResultArray();
        return $result;
    }

    public function get_warehouses()
    {
        $query = "SELECT warehouse_id,name FROM warehouses";
        $result = $this->db->query($query)->getResultArray();
        return $result;
    }
}
