<?php

namespace App\Models\Erp;

use CodeIgniter\Model;

class SupplyListModel extends Model
{
    protected $table = 'supply_list';
    protected $primaryKey = 'supply_list_id';
    protected $useAutoIncrement = true;

    protected $allowedFields = [
        'related_to',
        'related_id',
        'supplier_id',
        'created_by',
        'created_at',
    ];

    // protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    // protected $updatedField  = 'updated_at';

    private $supplylist_products=array(
        "raw_material"=>"Raw Materials",
        "semi_finished"=>"Semi Finished"
    );

    private $supplylist_products_link=array(
        "raw_material"=>'erp/supplier/ajaxfetchrawmaterials',
        "semi_finished"=>'erp/supplier/ajaxfetchsemifinished'
    );

    public function get_supplylist_products()
    {
        return $this->supplylist_products;
    }

    public function get_supplylist_products_link()
    {
        return $this->supplylist_products_link;
    }

    public function listDelete($supply_list_id, $supplier_id)
    {
        if (!empty($supplier_id)) {
            $this->where('supply_list_id', $supply_list_id)->delete();
        }
    }

    public function insert_update_supplylist($supplyListId, $supplier_id, $related_to, $related_id)
    {
        if (empty($supplyListId)) {
            $this->insert_supply_list($supplier_id, $related_to, $related_id);
        } else {
            $this->update_supply_list($supplier_id, $supplyListId, $related_to, $related_id);
        }
    }

    private function insert_supply_list($supplier_id, $related_to, $related_id)
    {
        $session = \Config\Services::session();
        // $created_by = get_user_id();

        $data = [
            'related_to'  => $related_to,
            'related_id'  => $related_id,
            'supplier_id' => $supplier_id,
            'created_at'  => time(),
            'created_by'  => get_user_id(),
        ];

        $result = $this->insert($data);

        $config = [
            'title'    => "Supplier Supply List Insert",
            'ref_link' => 'erp/supplier/supplier-view/' . $supplier_id,
        ];

        if ($result) {
            $config['log_text'] = "[ Supplier Supply List successfully created ]";
            $session->setFlashdata("op_success", "Supplier Supply List successfully created");
        } else {
            $config['log_text'] = "[ Supplier Supply List failed to create ]";
            $session->setFlashdata("op_error", "Supplier Supply List failed to create");
        }

        log_activity($config);
    }

    private function update_supply_list($supplier_id, $supply_list_id, $related_to, $related_id)
    {
        $session = \Config\Services::session();

        $data = [
            'related_to'  => $related_to,
            'related_id'  => $related_id,
            'supplier_id' => $supplier_id,
        ];

        $this->set($data);
        $this->where('supply_list_id', $supply_list_id);
        $result = $this->update();

        $config = [
            'title'    => "Supplier Supply List Update",
            'ref_link' => 'erp/supplier/supplier-view/' . $supplier_id,
        ];

        if ($result) {
            $config['log_text'] = "[ Supplier Supply List successfully updated ]";
            $session->setFlashdata("op_success", "Supplier Supply List successfully updated");
        } else {
            $config['log_text'] = "[ Supplier Supply List failed to update ]";
            $session->setFlashdata("op_error", "Supplier Supply List failed to update");
        }

        log_activity($config);
    }

    public function get_suppliersupplylist_datatable($supplier_id)
    {
        // $supplier_id = $this->input->get("supplierid");
        $columns = array(
            "product type" => "related_to",
            "product" => "related_id"
        );
        $limit = $_GET['limit'];
        $offset = $_GET['offset'];
        $search = $_GET['search'] ?? "";
        $orderby = isset($_GET['orderby']) ? strtoupper($_GET['orderby']) : "";
        $ordercol = isset($_GET['ordercol']) ? $columns[$_GET['ordercol']] : "";

        $query = "SELECT supply_list_id,related_to,COALESCE(CONCAT(raw_materials.name,' ',raw_materials.code),CONCAT(semi_finished.name,' ',semi_finished.code)) AS product FROM supply_list LEFT JOIN raw_materials ON related_to='raw_material' AND related_id=raw_materials.raw_material_id LEFT JOIN semi_finished ON related_to='semi_finished' AND related_id=semi_finished.semi_finished_id WHERE supplier_id=$supplier_id ";
        $count = "SELECT COUNT(supply_list_id) AS total FROM supply_list LEFT JOIN raw_materials ON related_to='raw_material' AND related_id=raw_materials.raw_material_id LEFT JOIN semi_finished ON related_to='semi_finished' AND related_id=semi_finished.semi_finished_id WHERE supplier_id=$supplier_id ";
        $where = true;

        if (!empty($search)) {
            if (!$where) {
                $query .= " WHERE  related_to LIKE '%" . $search . "%' ";
                $count .= " WHERE  related_to LIKE '%" . $search . "%' ";
                $where = true;
            } else {
                $query .= " AND  related_to LIKE '%" . $search . "%' ";
                $count .= " AND  related_to LIKE '%" . $search . "%' ";
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
                                <li><a href="#" data-ajax-url="' . base_url() . 'erp/supplier/ajax-fetch-list/' . $r['supply_list_id'] . '" title="Edit" class="bg-success modalBtn"><i class="fa fa-pencil"></i> </a></li>
                                <li><a href="' . base_url() . 'erp/supplier/list-delete/' . $supplier_id . '/' . $r['supply_list_id'] . '" title="Delete" class="bg-danger del-confirm"><i class="fa fa-trash"></i> </a></li>
                            </ul>
                        </div>
                    </div>';
            array_push(
                $m_result,
                array(
                    $r['supply_list_id'],
                    $sno,
                    $r['related_to'],
                    $r['product'],
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


    public function get_supplier_supply_list_export($type, $supplier_id)
    {
        // $supplier_id = $this->request->getGet("supplierid");
        $columns = [
            "product type" => "related_to",
            "product" => "related_id"

        ];

        // var_dump($this->supplylist_products);
        // exit();
        $query = $this->db->table('supply_list')
            ->select('CASE 
                WHEN related_to=\'raw_material\' THEN \'' . $this->supplylist_products['raw_material'] . '\' 
                WHEN related_to=\'semi_finished\' THEN \'' . $this->supplylist_products['semi_finished'] . '\' 
                END AS related_to, COALESCE(CONCAT(raw_materials.name, \' \', raw_materials.code), CONCAT(semi_finished.name, \' \', semi_finished.code)) AS product')
            ->join('raw_materials', 'related_to=\'raw_material\' AND related_id=raw_materials.raw_material_id', 'left')
            ->join('semi_finished', 'related_to=\'semi_finished\' AND related_id=semi_finished.semi_finished_id', 'left')
            ->where('supplier_id', $supplier_id);

        $where = false;

        if (!empty($search)) {
            $query->groupStart()
                ->like('related_to', $search)
                ->groupEnd();

            $where = true;
        }

        if (!empty($ordercol) && !empty($orderby)) {
            $query->orderBy($ordercol, $orderby);
        }

        $result = $query->get()->getResultArray();

        return $result;
    }
}
